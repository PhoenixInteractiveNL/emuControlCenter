<?php
/*
 * Created on 29.09.2006
 *
 * Functions to interact with the emuControlCenter Webservices
 * on www.camya.com
 */
class WebServices {
	
	private $dbms;
	
	private $serviceUrl = false;
	
	private $eccDbSessionKey = false;
	private $cs = false;
	private $status_obj = false;
	
	public function __construct() {
	}
	
	// called by FACTORY
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	public function setServiceUrl($url) {
		$this->serviceUrl = $url;
	}
	
	public function get() {
		$data = @file_get_contents($this->serviceUrl);
		return $this->unpackData($data);
	}
	
	public function set($data) {
		$data = $this->packData($data);
		$path = $this->serviceUrl."?d=".$data;
		return $path;
	}
	
	private function packData(Array $data) {
		$output = array();
		foreach($data as $key => $value) {
			if (trim($value)) $output[substr(md5($key),0,4)] = substr(md5($key),0,4)."=".$value;
		}
		return base64_encode(str_rot13(implode('&', $output)));
	}
	
	private function unpackData($data) {
		$data = base64_decode($data);
		if ($data = unserialize($data)) {
			return $data;
		}
		return FALSE;
	}
	
	public function setStateObject($status_obj) {
		$this->status_obj = $status_obj;
	}
	
	public function eccdbAddMetaData($perRun=10, $eccversion, $sessionKey, $cs) {
		$this->cs = $cs;
		$this->setRomdbSessionKey();

		$urls = $this->getEccdbUpdateUrls($perRun, $eccversion, $sessionKey);
		
		$state = array();
		$state['total'] = count($urls);
		$state['added'] = 0;
		$state['inplace'] = 0;
		$state['error'] = 0;
		
		$message = "Transfert data into eccdb/romdb:";
		
		if(LOGGER::$active) {
			LOGGER::add('romdbadd', $message, 1);
			LOGGER::add('romdbadd', join("\t", array('STATE', 'ECCIDENT', 'CRC32', 'TITLE')));
		}
		
		$message .= "\n";
		
		$position = 1;
		foreach($urls as $mid => $urlData) {
			
			$statusProcess = '';
			
			$error = false;
			
			$urlData['url'] .= "&csid=".urlencode($this->eccDbSessionKey);	
			
			$ret = file_get_contents($urlData['url'], false, NULL, 0, 30); // only read the first 30 chars
			#$ret = file_get_contents($urlData['url'], false, NULL, 0, 130); // only read the first 30 chars
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$split = explode(':', $ret);
			$eccDbIdent = (isset($split[0])) ? $split[0] : false;
			$eccDbState = (isset($split[1])) ? $split[1] : false;
			$eccDbSession = (isset($split[2])) ? $split[2] : false;
			
			if ($eccDbState == 'ADDED' && $eccDbSession) {
				$this->addRomdbSessionKey($eccDbSession);;
				$state['added']++;
				$statusProcess = '(ADDED)';
			}
			elseif ($eccDbState == 'INPLACE') {
				$state['inplace']++;
				$statusProcess = '(INPLACE)';
			}
			else {
				$error = true;
				$state['error']++;
				$statusProcess = '(ERROR)';
			}
			
			$info = explode('|', $urlData['title']);
			
			if(LOGGER::$active) {
				LOGGER::add('romdbadd', join("\t", array(substr($statusProcess, 1, 1), $info[0], $info[1], $info[2])));
			}
			
			if (!$error) $this->setExportedSession($mid, $sessionKey);
			
			usleep(100000);
			
			// ---------------------------------
			// STATUS BAR PROGRESS
			// ---------------------------------
			$percent_string = sprintf("%02d", ($position*100)/$perRun);
			$msg = "".$percent_string." % ($position/$perRun)";
			$percent = (float)$position/$perRun;
			$this->status_obj->update_progressbar($percent, $msg);
			// STATUS BAR MESSAGE
			// ---------------------------------
			$message .= "transfer... ".$info[2]." (".$info[0]."/".$info[1].") ".$statusProcess."\n";
			$this->status_obj->update_message($message);
			// STATUS BAR OBSERVER CANCEL
			// ---------------------------------
			if ($this->status_obj->is_canceled()) return false;
			// ---------------------------------
			
			$position++;
		}
		
		if(LOGGER::$active) {
			LOGGER::add('romdbadd', "DONE: transfered ".($position-1)." roms", 2);
		}
		
		return $state;
	}
	
	private function getEccdbUpdateUrls($perRun, $eccversion, $sessionKey) {

		$debug = true;
		
		$roms = $this->getModifiedUserData($perRun);
		
		$urlData = array();
		foreach ($roms as $rom) {

			$romFile = $rom->getRomFile();
			$romMeta = $rom->getRomMeta();
			
			$eccident = trim($romMeta->getSystemIdent());
			$crc32 = trim($romMeta->getCrc32());
			$filename = trim($romFile->getPlainFileName());
			$filesize = $romFile->getFileSize();
			$fileext = $romFile->getRomExtension();
			
			$title = trim($romMeta->getName());
			$rating = $romMeta->getRating();
			$features =
				$romMeta->getRunning().".".
				$romMeta->getBugs().".".
				$romMeta->getTrainer().".".
				$romMeta->getIntro().".".
				$romMeta->getUsermod().".".
				$romMeta->getFreeware().".".
				$romMeta->getMultiplayer().".".
				$romMeta->getNetplay()
			;
			$lang = trim(implode(".", array_keys(FACTORY::get('manager/TreeviewData')->get_language_by_mdata_id($romMeta->getId()))));
			$cat = (int)$romMeta->getCategory();
			$year = trim($romMeta->getYear());
			
			$usk = trim($romMeta->getUsk());
			$dev = trim($romMeta->getDeveloper());
			
			$publisher = trim($romMeta->getPublisher());
			$storage = trim($romMeta->getStorage());
			
			$date = ($romFile->getLaunchTime()) ? date('Ymd-His', $romFile->getLaunchTime()) : 0;
			$stats = (int)$romFile->getLaunchCount().'.'.$date;
			
			$data = array(
				'eccident' => urlencode($eccident),
				'crc32' => urlencode($crc32),
				'title' => urlencode($title),
				'fname' => urlencode($filename),
				'fsize' => urlencode((int)$filesize),
				'fext' => urlencode($fileext),
				'rating' => urlencode($rating),
				'eccvers' => urlencode($eccversion),
				'data' => urlencode($features),
				'lang' => urlencode($lang),
				'year' => urlencode($year),
				'cat' => urlencode($cat),
				'dev' => urlencode($dev),
				'usk' => urlencode($usk),
				'sk' => urlencode($sessionKey),
				'pub' => urlencode($publisher),
				'sto' => urlencode($storage),
				'stat' => urlencode($stats),
				'debug' => urlencode($debug),
				'pro' => urlencode(trim($romMeta->getProgrammer())),
				'mus' => urlencode(trim($romMeta->getMusican())),
				'gra' => urlencode(trim($romMeta->getGraphics())),
				'mtype' => urlencode($romMeta->getMedia_type()),
				'mcur' => urlencode($romMeta->getMedia_current()),
				'mcou' => urlencode($romMeta->getMedia_count()),
				'reg' => urlencode($romMeta->getRegion()),
				'catb' => urlencode($romMeta->getCategory_base()),
				'infs' => urlencode($romMeta->getInfo()),
				'infid' => urlencode($romMeta->getInfo_id()),
			);
			$params = array();
			foreach($data as $key => $value) $params[] = $key.'='.$value;
		
			$paramString = join('&', $params);
			
			$urlData[$romMeta->getId()]['title'] = $eccident."|".$crc32."|".$title;
			$urlData[$romMeta->getId()]['url'] = $this->serviceUrl.'?'.$paramString;
		}
		
		return $urlData;
	}
	
	public function getModifiedUserData($perRun)
	{
		$q = '
		SELECT
		md.crc32 as md_crc32,
		md.id as md_id,
		md.eccident as md_eccident,
		md.name as md_name,
		md.info as md_info,
		md.info_id as md_info_id,
		md.running as md_running,
		md.bugs as md_bugs,
		md.trainer as md_trainer,
		md.intro as md_intro,
		md.usermod as md_usermod,
		md.freeware as md_freeware,
		md.multiplayer as md_multiplayer,
		md.netplay as md_netplay,
		md.year as md_year,
		md.usk as md_usk,
		md.rating as md_rating,
		md.category as md_category,
		md.creator as md_creator,
		md.publisher as md_publisher,
		md.programmer as md_programmer,
		md.musican as md_musican,
		md.graphics as md_graphics,
		md.media_type as md_media_type,
		md.media_current as md_media_current,
		md.media_count as md_media_count,
		md.storage as md_storage,
		md.region as md_region,
		md.cdate as md_cdate,
		md.uexport as md_uexport,
		md.dump_type as md_dump_type,
		fd.id as id,
		fd.title as title,
		fd.path as path,
		fd.path_pack as path_pack,
		fd.crc32 as crc32,
		fd.md5 as md5,
		fd.size as size,
		fd.eccident as fd_eccident,
		fd.launchtime as fd_launchtime,
		fd.launchcnt as fd_launchcnt,
		fd.isMultiFile as fd_isMultiFile,
		fd.mdata as fd_mdata
		FROM
		mdata md
		LEFT JOIN fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32)
		WHERE
		md.cdate NOT NULL AND
		md.uexport IS NULL
		GROUP BY
		md.id
		ORDER
		BY md.cdate DESC
		LIMIT
		'.(int)$perRun.'
		';
		$result = $this->dbms->query($q);
		$roms = array();
		while($row = $result->fetch(SQLITE_ASSOC)) {
			
			$romFile = new RomFile();
			$romFile->fillFromDatabase($row);
			$romMeta = new RomMeta();
			$romMeta->fillFromDatabase($row);
			$romAudit = new RomAudit();
			
			$rom = new Rom();
			$rom->setRomFile($romFile);
			$rom->setRomMeta($romMeta);
			$rom->setRomAudit($romAudit);
			
			$roms[] = $rom;
		}
		return $roms;
	}
	
	public function getModifiedUserDataCount()
	{
		$q = '
		SELECT
		 count(*) as cnt
		FROM
			mdata md
		WHERE
			md.cdate NOT NULL AND
			md.uexport IS NULL
		';
		$result = $this->dbms->query($q);
		if ($row = $result->fetch(SQLITE_ASSOC)) {
			return $row['cnt'];
		}
		return false;
	}
	
	public function setExportedSession($id, $sessionKey) {
		$q = 'UPDATE mdata SET cdate = NULL, uexport="'.sqlite_escape_string($sessionKey).'" WHERE id = '.(int)$id;
		$this->dbms->query($q);
	}
	
	private function setRomdbSessionKey() {
		if (!$this->eccDbSessionKey) {
			$romdbSessionKey = @file_get_contents(ECC_DIR.'/'.$this->cs['cscheckdat']);
			$this->eccDbSessionKey = ($romdbSessionKey) ? $romdbSessionKey : '........';
		}
	}
	
	private function addRomdbSessionKey($romdbSessionKey) {
		file_put_contents(ECC_DIR.'/'.$this->cs['cscheckdat'], $romdbSessionKey);
		$this->eccDbSessionKey = $romdbSessionKey;
	}
	
	public function getRomdbDatfile(){

		$userFolder = FACTORY::get('manager/IniFile')->getUserFolder(false, '#_GLOBAL', true);
//		$userFolder = FACTORY::get('manager/IniFile')->getUserFolder().'/#_GLOBAL/';

		$filename = 'eccdat_all_complete.'.date('Ymd', time()).'.eccDat';
		
		# direct get datfile... no more caching!
		$data = file_get_contents($this->serviceUrl.'/'.$filename);
		
//		$cachedFile = $userFolder.$filename;
//		if(file_exists($cachedFile)){
//			$data = file_get_contents($cachedFile);
//		}
//		else{
//			$data = file_get_contents($this->serviceUrl.'/'.$filename);
//			if(trim($data)) file_put_contents($cachedFile, $data);
//		}
		
		return $data;
		
		#return @file_get_contents($this->serviceUrl.'/eccdat_all_complete.'.date('Ymd', time()).'.eccDat');
	}
	
	public function getRomImages($systemIdent, $crc32, $cs){
		
		$mngrValidator = FACTORY::get('manager/Validator');
		$this->eccdb = $mngrValidator->getEccCoreKey('eccdb');
		$session = file_get_contents(ECC_DIR.'/'.$cs['cicheckdat']);
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		// get zip
		$url = $this->eccdb['IMAGE_INJECT_SERVER'].'idt='.$session.'&systemIdent='.$systemIdent.'&crc32='.$crc32;
		
		echo '<pre>';
		print_r($url);
		echo '</pre>';
		
		$zipFileDownload = file_get_contents($url);
		if(!$zipFileDownload) {
			print "No file found\n";
			return false;
		}
		
		if(substr($zipFileDownload, 0, 5) == 'error'){
			print "xxx".$zipFileDownload."\n";
			return false;
		}
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		// store temp file
		$tempFilename = 'temp/eccimage_temp.zip';
		if(!file_put_contents($tempFilename, $zipFileDownload)) {
			print "Could not move File!\n";
			return false;
		}

		while (gtk::events_pending()) gtk::main_iteration();
		
		$mngrImage = FACTORY::get('manager/Image');
		$mngrFileIO = FACTORY::get('manager/FileIO');
		
		$userFolder = FACTORY::get('IniFile')->getUserFolder($systemIdent);
				
		$zipHandle = zip_open($tempFilename);
		if(!$zipHandle) {
			print "Could not open ZIP\n";
			return false;
		}
		
		print "x\n";
		
		while (false !== $zipEntry = zip_read($zipHandle)){
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$fileExtension = $mngrFileIO->get_ext_form_file(basename(zip_entry_name($zipEntry)));
			if(strtolower($fileExtension) == 'txt') continue;
			
			if (zip_entry_open($zipHandle, $zipEntry, "r")) {
				$imageStream = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
				$imagePath = $userFolder.''.zip_entry_name($zipEntry);

				// write images and creates thumbs
				print $mngrImage->copyImageFromStream($systemIdent, $crc32, $imageStream, $imagePath)."\n";
				
				zip_entry_close($zipEntry);
			}
			
		}
		zip_close($zipHandle);
		
		print "y\n";

		// remove temp file
		unlink($tempFilename);
		print "z\n";
		return true;
	}
	
}
?>