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
			
			usleep(200000);
			
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

		$userData = $this->getModifiedUserData($perRun);
		
		$urlData = array();
		foreach ($userData as $key => $modData) {

			$eccident = trim($modData['md.eccident']);
			$crc32 = trim($modData['md.crc32']);
			$filename = trim($modData['fd.title']);
			$filesize = trim($modData['fd.size']);
			$filesize = ($filesize) ? round($filesize/1024, 1) : 0;
			
			$title = (trim($modData['md.name']));
			$rating = $modData['md.rating'];
			$data = trim($modData['md.running'].".".$modData['md.bugs'].".".$modData['md.trainer'].".".$modData['md.intro'].".".$modData['md.usermod'].".".$modData['md.freeware'].".".$modData['md.multiplayer'].".".$modData['md.netplay']);
			$lang = trim(implode(".", array_keys(FACTORY::get('manager/TreeviewData')->get_language_by_mdata_id($modData['md.id']))));
			$cat = (int)$modData['md.category'];
			$year = trim($modData['md.year']);
			
			$usk = trim($modData['md.usk']);
			$dev = trim($modData['md.creator']);
			
			$publisher = trim($modData['md.publisher']);
			$storage = trim($modData['md.storage']);
			
			$date = ($modData['fd.launchtime']) ? date('Ymd-His', $modData['fd.launchtime']) : 0;
			$stats = (int)$modData['fd.launchcnt'].'.'.$date;
			
			$urlData[$modData['md.id']]['title'] = $eccident."|".$crc32."|".$title;
			$urlData[$modData['md.id']]['url'] = $this->serviceUrl."?eccident=".urlencode($eccident)."&crc32=".urlencode($crc32)."&title=".urlencode($title)."&fname=".urlencode($filename)."&fsize=".urlencode((int)$filesize)."&rating=".urlencode($rating)."&eccvers=".urlencode($eccversion)."&data=".urlencode($data)."&lang=".urlencode($lang)."&year=".urlencode($year)."&cat=".urlencode($cat)."&dev=".urlencode($dev)."&usk=".urlencode($usk)."&sk=".urlencode($sessionKey)."&pub=".urlencode($publisher)."&sto=".urlencode($storage)."&stat=".urlencode($stats)."";
		}
		
		return $urlData;
	}
	
	public function getModifiedUserData($perRun)
	{
		$q = '
		SELECT
			md.*,
			fd.*
		FROM
			mdata md
			LEFT JOIN fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32)
		WHERE
			md.cdate NOT NULL AND
			md.uexport IS NULL
		GROUP BY
			md.id
		ORDER BY
			md.cdate DESC
		LIMIT '.(int)$perRun.'
		';
		$ret = array();
		$result = $this->dbms->query($q);
		while($row = $result->fetch(SQLITE_ASSOC)) {
			$ret[] = $row;
		}
		
		return $ret;
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
			$romdbSessionKey = @file_get_contents(ECC_BASEDIR.$this->cs['cscheckdat']);
			$this->eccDbSessionKey = ($romdbSessionKey) ? $romdbSessionKey : '........';
		}
	}
	
	private function addRomdbSessionKey($romdbSessionKey) {
		file_put_contents(ECC_BASEDIR.$this->cs['cscheckdat'], $romdbSessionKey);
		$this->eccDbSessionKey = $romdbSessionKey;
	}
	
	public function getRomdbDatfile(){
		return @file_get_contents($this->serviceUrl.'/eccdat_all_complete.'.date('Ymd', time()).'.eccDat');
	}
	
}
?>