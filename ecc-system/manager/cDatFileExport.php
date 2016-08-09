<?
/*
* @author: ascheibel
*/
class DatFileExport {
	
	private $dbms = false;
	private $_ident = false;
	private $ini = false;
	private $_export_user_only = true;
	private $sql_snipp_esearch = false;
	private $status_obj = false;
	private $ecc_release = array();
	
	private $exportType = 'CSV';

	public function __construct($ini, $status_obj, $ecc_release){
		$this->status_obj = $status_obj;
		$this->ini = $ini;
		$this->ecc_release = $ecc_release;
	}
	
	public function setDbms($dbmsObject){
		$this->dbms = $dbmsObject;
	}

	public function set_eccident($identifier){
		$this->_ident = strtolower($identifier);
	}
	
	public function setExportType($type='ECC'){
		$this->exportType = $type;
	}

	public function export_user_only($state=true){
		$this->_export_user_only = $state;
	}
	
	public function set_sqlsnipplet_esearch($sql_snipplet=false){
		$this->sql_snipp_esearch = $sql_snipplet;
	}

	public function export_data($path=false){
		return $this->save_dat_file_to_fs($path);
	}

	public function get_dbhdl_for_export_data(){
		$sql_snipp = array();
		if ($this->_ident) $sql_snipp[] =  "eccident = '".$this->_ident."'";
		if ($this->_export_user_only) $sql_snipp[] =  "cdate not null";
		if ($this->sql_snipp_esearch != "") $sql_snipp[] =  $this->sql_snipp_esearch;
		$sql_snipp = implode(" AND ", $sql_snipp);
		if ($sql_snipp) $sql_snipp = "WHERE ".$sql_snipp;
		
		$q = 'SELECT * FROM mdata '.$sql_snipp.' ORDER BY eccident, name, crc32';
		return $this->dbms->query($q);
	}
	
	public function get_language_data($mdat_id){
		$ret = array();
		$q = "SELECT lang_id FROM mdata_language WHERE mdata_id=".$mdat_id;
		$hdl = $this->dbms->query($q);
		while ($v = $hdl->fetch(1)) $ret[$v['lang_id']] = $v['lang_id'];
		return $ret;
	}
	
	public function getFileSize($eccident, $crc32){
		$q = "SELECT size FROM fdata WHERE eccident='".$eccident."' AND crc32='".$crc32."'";
		$hdl = $this->dbms->query($q);
		if($row = $hdl->fetch(1)) return $row['size'];
		return '';		
	}
	
	public function save_dat_file_to_fs($path=false){
		
		$dbhdl = $this->get_dbhdl_for_export_data();
		
		$this->exportHeader['export_ident'] = ($this->_ident) ? $this->_ident : "all";
		
		if ($path) {
			$user_folder_export = $path;
		}
		else {
			# 20070810 refactoring userfolder
			$user_folder_export = $this->ini->getUserFolder($this->exportHeader['export_ident'], DIRECTORY_SEPARATOR."exports".DIRECTORY_SEPARATOR, true);
		}
		
		if ($user_folder_export!==false) {
			
			// ECC_INFO
			$this->exportHeader['local_release_version'] = "".$this->ecc_release['local_release_version']." build ".$this->ecc_release['release_build']." ".$this->ecc_release['release_state']."";
			$this->exportHeader['eccdat_version'] = $this->ecc_release['eccdat_version'];

			$this->exportHeader['title'] = $this->ecc_release['title'];
			$this->exportHeader['title_short'] = $this->ecc_release['title_short'];
			
			// USER_DAT_CREDITS
			$this->exportHeader['author'] = $this->ini->getKey('USER_DAT_CREDITS', 'author');
			$this->exportHeader['website'] = $this->ini->getKey('USER_DAT_CREDITS', 'website');
			$this->exportHeader['email'] = $this->ini->getKey('USER_DAT_CREDITS', 'email');
			$this->exportHeader['comment'] = $this->ini->getKey('USER_DAT_CREDITS', 'comment');
			
			$this->exportHeader['export_date'] = date('Ymd_Hi', time());
			$this->exportHeader['export_type'] = ($this->_export_user_only) ? 'user' : 'complete';
			
			$this->exportHeader['export_esearch'] = "NO";
			if ($this->sql_snipp_esearch) {
				$this->exportHeader['export_type'] = "esearch";
				$this->exportHeader['export_esearch'] = $this->sql_snipp_esearch;
			}
			
			if ($this->ini->getKey('EXPERIMENTAL', 'exportType')) {
				$this->exportType = $this->ini->getKey('EXPERIMENTAL', 'exportType');
			}
			$header = "";
			switch($this->exportType) {
				case 'CSV':
					$header = $this->createCsvDatHeader();
					$exportTypeFileExtension = '.csv';
					break;
				default:
					$header = $this->createEccDatHeader();
					$exportTypeFileExtension = '.eccDat';
					break;
			}
			$export_file = $user_folder_export."/eccdat_".$this->exportHeader['export_ident']."_".$this->exportHeader['export_type'].".".$this->exportHeader['export_date'].$exportTypeFileExtension;			
			
			if (false !== $fhdl = fopen($export_file, 'w+')) {

				fwrite($fhdl, $header);
				
				#$cnt_total = count($data);
				$cnt_current = 0;
				$cnt_total = $dbhdl->numRows();
				if (!$cnt_total) {
					$this->status_obj->update_progressbar(1, "Error");
					$this->status_obj->update_message("Error: No data for export found!");
					return false;
				}
				$header = false;
				while ($v = $dbhdl->fetch(1)) {
					
					#print_r($v);
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$languages = $this->get_language_data($v['id']);
					$languages = implode("|", $languages);
					
					$fileSize = $this->getFileSize($v['eccident'], $v['crc32']);

//# TEST
//					$hideFields = array(
//						'id',
//						'uexport',
//						'cdate',
//					);
//					foreach($hideFields as $hideKey) unset($v[$hideKey]);
//
//$v['info'] = str_replace(" ","", $v['info']);
//$v['languages'] = $languages;
//$v['filesize'] = $fileSize;
//					
//					if(!$header){
//						print join(';', array_keys($v))."\n";
//						$header = true;
//					}
//					print join(';', $v)."\n";
//					
//					$this->createExportString($v, $hideFields, $addFields);

					# remove "invalid ;" in strings
					foreach($v as $key => $value) if(strpos($value, ';') !== false) $v[$key] = str_replace(';', ',', $value);
					
					$line = $v['eccident'].";".$v['name'].";".$v['extension'].";".$v['crc32'].";".$v['running'].";".$v['bugs'].";".$v['trainer'].";".$v['intro'].";".$v['usermod'].";".$v['freeware'].";".$v['multiplayer'].";".$v['netplay'].";".$v['year'].";".$v['usk'].";".$v['category'].";".$languages.";".$v['creator'].";;;".str_replace(" ","", $v['info']).";".$v['info_id'].";".$v['publisher'].";".$v['storage'].";".$fileSize.";".$v['programmer'].";".$v['musican'].";".$v['graphics'].";".$v['media_type'].";".$v['media_current'].";".$v['media_count'].";".$v['region'].";".$v['category_base'].";#\r\n";
					
					#print $line."\r\n";
					fwrite($fhdl, $line);
					$line = "";
					$cnt_current++;
					
					// ---------------------------------
					// STATUS BAR PROGRESS
					// ---------------------------------
					$percent_string = sprintf("%02d", ($cnt_current*100)/$cnt_total);
					$msg = "".$percent_string." % ($cnt_current/$cnt_total)";
					$percent = (float)$cnt_current/$cnt_total;
					$this->status_obj->update_progressbar($percent, $msg);
					// STATUS BAR MESSAGE
					// ---------------------------------
					$message  = "Current expoting:\r\n";
					$message .= "Entry $cnt_current of $cnt_total\r\n";
					$message .= $v['eccident']."\t".$v['name'].chr(13);
					$this->status_obj->update_message($message);
					// STATUS BAR OBSERVER CANCEL
					// ---------------------------------
					if ($this->status_obj->is_canceled()) return false;
					// ---------------------------------
				}
				fclose($fhdl);
				
				// ---------------------------------
				// STATUS BAR PROGRESS
				// ---------------------------------
				$this->status_obj->update_progressbar(1, "Export DONE");
				// STATUS BAR MESSAGE
				// ---------------------------------
				$message  = "Export done:\r\n";
				$message .= "$cnt_total Entries exported to \r\n";
				$message .= realpath($export_file);
				$this->status_obj->update_message($message);
				// STATUS BAR OBSERVER CANCEL
				// ---------------------------------
				if ($this->status_obj->is_canceled()) return false;
				// ---------------------------------
			}
		}
		else {
			#print "error - wrong filepath: $export_file\r\n";
		}
		
		$ret['file'] = realpath($export_file);
		$ret['count'] = $cnt_current;
		
		return $ret;
	}
	
//	public function createExportString($v, $hideFields, $addFields){
//	}
	
	private function createEccDatHeader(){

		$this->exportHeader['title'] = $this->ecc_release['title'];
		$this->exportHeader['title_short'] = $this->ecc_release['title_short'];
		
		$line  = "";
		$line .= "[ECC]\r\n";
		$line .= "DAT-TYPE=\teccMediaDat\r\n";
		$line .= "DAT-VERSION=\t".$this->exportHeader['eccdat_version']."\r\n";
		$line .= "GENERATOR=\t".$this->exportHeader['title']." (".$this->exportHeader['title_short'].")\r\n";
		$line .= "ECC-VERSION=\t".$this->exportHeader['local_release_version']."\r\n";
		$line .= "LANGUAGE=\t".i18n::getLanguageIdent()."\r\n";
		$line .= "\r\n";
		$line .= "[ECC_DAT]\r\n";
		$line .= "ECCIDENT=\t".$this->exportHeader['export_ident']."\r\n";
		$line .= "TYPE=\t".$this->exportHeader['export_type']."\r\n";
		$line .= "ESEARCH=\t".$this->exportHeader['export_esearch']."\r\n";
		$line .= "DATE=\t".$this->exportHeader['export_date']."\r\n";
		$line .= "\r\n";
		$line .= "[ECC_DAT_CREDITS]\r\n";
		$line .= "AUTHOR=\t".$this->exportHeader['author']."\r\n";
		$line .= "WEBSITE=\t".$this->exportHeader['website']."\r\n";
		$line .= "EMAIL=\t".$this->exportHeader['email']."\r\n";
		$line .= "COMMENT=\t".$this->exportHeader['comment']."\r\n";
		$line .= "\r\n";
		$line .= "[ECC_MEDIA]\r\n";
		$line .= "eccident;name;extension;crc32;running;bugs;trainer;intro;usermod;freeware;multiplayer;netplay;year;usk;category;languages;creator;hardware;doublettes;info;info_id;publisher;storage;filesize;programmer;musican;graphics;media_type;media_current;media_count;region;category_base;#\r\n";
		return $line;
	}
	
	private function createCsvDatHeader(){
		
		$head1 = "";
		$line1  = "";
		
		$head1 .= "GENERAL;";
		$line1 .= ";";
		
		$head1 .= "GENERATOR;";
		$line1 .= $this->exportHeader['title']." (".$this->exportHeader['title_short'].");";

		$head1 .= "TYPE;";
		$line1 .= "eccMediaDat;";

		$head1 .= "DAT-VERSION;";
		$line1 .= "".$this->exportHeader['eccdat_version'].";";
		
		$head1 .= "ECC-VERSION;";
		$line1 .= "".$this->exportHeader['local_release_version'].";";
		
		$head2 = "";
		$line2  = "";
		
		$head2 .= "DATFILE;";
		$line2 .= ";";
		
		$head2 .= "ECCIDENT;";
		$line2 .= "".$this->exportHeader['export_ident'].";";
		
		$head2 .= "TYPE;";
		$line2 .= "".$this->exportHeader['export_type'].";";

		$head2 .= "ESEARCH;";
		$line2 .= "".$this->exportHeader['export_esearch'].";";

		$head2 .= "DATE;";
		$line2 .= "".$this->exportHeader['export_date'].";";
		
		$head3 = "";
		$line3  = "";
		
		$head3 .= "DATCREDITS;";
		$line3 .= ";";
		
		$head3 .= "AUTHOR;";
		$line3 .= "".$this->exportHeader['author'].";";
		
		$head3 .= "WEBSITE;";
		$line3 .= "".$this->exportHeader['website'].";";
		
		$head3 .= "EMAIL;";
		$line3 .= "".$this->exportHeader['email'].";";
		
		$head3 .= "COMMENT;";
		$line3 .= "".$this->exportHeader['comment'].";";
		
		$lin5 = "eccident;name;extension;crc32;running;bugs;trainer;intro;usermod;freeware;multiplayer;netplay;year;usk;category;languages;creator;hardware;doublettes;info;info_id;#";
		
		$line = $head1."\r\n".$line1."\r\n".$head2."\r\n".$line2."\r\n".$head3."\r\n".$line3."\r\n\r\n".$lin5."\r\n";
		
		return $line;
	}
}
?>
