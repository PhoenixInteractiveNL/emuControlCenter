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
	
	/*
	* @author: ascheibel
	*/
	public function __construct($ini, $status_obj, $ecc_release)
	{
		$this->status_obj = $status_obj;
		$this->ini = $ini;
		$this->ecc_release = $ecc_release;
	}
	
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	/*
	* @author: ascheibel
	*/
	public function set_eccident($identifier)
	{
		$this->_ident = strtolower($identifier);
	}
	
	/*
	* @author: ascheibel
	*/
	public function export_user_only($state=true)
	{
		$this->_export_user_only = $state;
	}
	
	public function set_sqlsnipplet_esearch($sql_snipplet=false) {
		$this->sql_snipp_esearch = $sql_snipplet;
	}
	
	/*
	* @author: ascheibel
	*/
	public function export_data($path=false)
	{
		#$user_folder_images = $this->ini->get_ecc_ini_user_folder($this->_ident.DIRECTORY_SEPARATOR."export_backup".DIRECTORY_SEPARATOR, true);
		return $this->save_dat_file_to_fs($path);
	}
	
	/*
	* @author: ascheibel
	*/
	public function get_dbhdl_for_export_data()
	{
		$sql_snipp = array();
		if ($this->_ident) $sql_snipp[] =  "eccident = '".$this->_ident."'";
		if ($this->_export_user_only) $sql_snipp[] =  "cdate not null";
		if ($this->sql_snipp_esearch != "") $sql_snipp[] =  $this->sql_snipp_esearch;
		$sql_snipp = implode(" AND ", $sql_snipp);
		if ($sql_snipp) $sql_snipp = "WHERE ".$sql_snipp;
		
		$q = '
			SELECT
			*
			FROM
			mdata
			'.$sql_snipp.'
			ORDER BY
			eccident,
			name,
			crc32
		';
		#print $q;
		return $this->dbms->query($q);
	}
	
	/*
	* @author: ascheibel
	*/
	public function get_language_data($mdat_id)
	{
		$ret = array();
		
		$q = "SELECT lang_id FROM mdata_language WHERE mdata_id=".$mdat_id;
		#print $q;
		$hdl = $this->dbms->query($q);
		while ($v = $hdl->fetch(1)) {
			$ret[$v['lang_id']] = $v['lang_id'];
		}
		return $ret;
	}
	
	/*
	* @author: ascheibel
	*/
	public function save_dat_file_to_fs($path=false)
	{
		
		$dbhdl = $this->get_dbhdl_for_export_data();
		
		$export_ident = ($this->_ident) ? $this->_ident : "all";
		
		if ($path) {
			$user_folder_export = $path;
		}
		else {
			$user_folder_export = $this->ini->get_ecc_ini_user_folder($export_ident.DIRECTORY_SEPARATOR."exports".DIRECTORY_SEPARATOR, true);
		}
		
		if ($user_folder_export!==false) {
			
			// ECC_INFO
			$release_version = "".$this->ecc_release['release_version']." ".$this->ecc_release['release_build']." ".$this->ecc_release['release_state']."";
			$eccdat_version = $this->ecc_release['eccdat_version'];
			
			// USER_DAT_CREDITS
			$author = $this->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'author');
			$website = $this->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'website');
			$email = $this->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'email');
			$comment = $this->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'comment');
			
			$export_date = date('Ymd_Hi', time());
			$export_type = ($this->_export_user_only) ? 'user' : 'complete';
			
			$export_esearch = "NO";
			if ($this->sql_snipp_esearch) {
				$export_type = "esearch";
				$export_esearch = $this->sql_snipp_esearch;
			}
			
			$export_file = $user_folder_export."/eccdat_".$export_ident."_".$export_type.".".$export_date.".ecc";
			
			
			if (false !== $fhdl = fopen($export_file, 'w+')) {
				
				$msg_spacer = str_repeat("#", 80)."\n";
				
				$line  = "";
				$line .= "[ECC]\n";
				$line .= "DAT-TYPE=\teccMediaDat\n";
				$line .= "DAT-VERSION=\t".$eccdat_version."\n";
				$line .= "GENERATOR=\temuControlCenter\n";
				$line .= "ECC-VERSION=\t".$release_version."\n";
				$line .= "\n";
				$line .= "[ECC_DAT]\n";
				$line .= "ECCIDENT=\t$export_ident\n";
				$line .= "TYPE=\t$export_type\n";
				$line .= "ESEARCH=\t$export_esearch\n";
				$line .= "DATE=\t$export_date\n";
				$line .= "\n";
				$line .= "[ECC_DAT_CREDITS]\n";
				$line .= "AUTHOR=\t".$author."\n";
				$line .= "WEBSITE=\t".$website."\n";
				$line .= "EMAIL=\t".$email."\n";
				$line .= "COMMENT=\t".$comment."\n";
				$line .= "\n";
				$line .= "#eccident;name;extension;crc32;running;bugs;trainer;intro;usermod;freeware;multiplayer;netplay;year;usk;category;languages;creator;hardware;doublettes;info;info_id;#\n";
				$line .= "[ECC_MEDIA]\n";
				
				#$cnt_total = count($data);
				$cnt_current = 0;
				$cnt_total = $dbhdl->numRows();
				#foreach($data as $key => $v) {
				while ($v = $dbhdl->fetch(1)) {
					
					#print_r($v);
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$languages = $this->get_language_data($v['id']);
					$languages = implode("|", $languages);
					
					$line .= $v['eccident'].";".$v['name'].";".$v['extension'].";".$v['crc32'].";".$v['running'].";".$v['bugs'].";".$v['trainer'].";".$v['intro'].";".$v['usermod'].";".$v['freeware'].";".$v['multiplayer'].";".$v['netplay'].";".$v['year'].";".$v['usk'].";".$v['category'].";".$languages.";".$v['creator'].";;;".str_replace(" ","", $v['info']).";".$v['info_id'].";#\n";
					#print $line."\n";
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
					$message  = "Current expoting:\n";
					$message .= "Entry $cnt_current of $cnt_total\n";
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
				$message  = "Export done:\n";
				$message .= "$cnt_total Entries exported to \n";
				$message .= realpath($export_file);
				$this->status_obj->update_message($message);
				// STATUS BAR OBSERVER CANCEL
				// ---------------------------------
				if ($this->status_obj->is_canceled()) return false;
				// ---------------------------------
			}
		}
		else {
			#print "error - wrong filepath: $export_file\n";
		}
		
		$ret['file'] = realpath($export_file);
		$ret['count'] = $cnt_current;
		
		return $ret;
	}

	
}
?>
