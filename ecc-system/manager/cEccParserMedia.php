<?php

class EccParserMedia {
	
	// db-connection object
	private $dbms = false;
	// Daten des Files zum schreiben in
	// einem array
	private $_file_data = false;
	
	//
	private $_basepath = false;
	
	//
	private static $_timestamp = false;
	
	/*
	* ï¿½bergabe des DB-Objects
	*/
	public function __construct($path) {
		$this->_basepath = $this->normalize_path($path);
		$this->_timestamp = time();
	}
	
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	/*
	* ï¿½berprï¿½ft, wie vorgegangen werden muss.
	* Ist ein File schon in der datenbank, muss es nicht
	* noch einmal indiziert werden.
	*/
	public function add_file($data) {
		
		$this->_file_data = $data;
		
		if ($this->_file_data) {
			
			// Was muss getan werden
			// update/insert
			$fileId = false;
			$fd_exists = $this->filedata_exists();
			if (!$fd_exists) {
				// NEU
				$fileId = $this->filedata_insert();
			} else {
				// UPDATE
				if ($this->_file_data['FILE_CRC32'] && $this->_file_data['FILE_CRC32'] != $fd_exists['crc32']) {
					$fileId = $this->filedata_update();
				}
			}
			# insert state for multirom games
			if (isset($data['ROM_STATE'])) FACTORY::get('manager/ImportDatControlMame')->getBestMatch($data['ROM_STATE'], $fileId);
			
		}
		else {
			return false;
		}
	}
	
	/*
	* Speichert die File-Daten in die Datenbank
	*/
	private function filedata_insert() {
		
		if (!isset($this->_file_data['MDATA'])) {
			$this->_file_data['MDATA'] = array();
		}
		
		$duplicate = ($this->duplicate_exists()) ? "1" : "NULL" ;
		$isMultiFile = @$this->_file_data['IS_MULTIFILE'];
		
//		$this->_file_data['FILE_PATH'] = FileIO::covertStringToUtf8($this->_file_data['FILE_PATH']);
//		$this->_file_data['FILE_PATH_PACK'] = FileIO::covertStringToUtf8($this->_file_data['FILE_PATH_PACK']);		
		
		$q = "
			INSERT INTO
			fdata
			VALUES (
				null ,
				'".sqlite_escape_string(strtolower($this->_file_data['FILE_EXT']))."',
				'".sqlite_escape_string($this->_file_data['FILE_NAME'])."',
				'".(int)$this->_file_data['FILE_VALID']."',
				1,
				".(int)$this->_file_data['FILE_SIZE'].",
				'".sqlite_escape_string(strtoupper($this->_file_data['FILE_CRC32']))."',
				'".sqlite_escape_string(strtoupper($this->_file_data['FILE_MD5']))."',
				NULL,
				'".sqlite_escape_string($this->_file_data['FILE_PATH'])."',
				'".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."',
				".(int)$isMultiFile.",
				'".base64_encode(serialize($this->_file_data['MDATA']))."',
				NULL,
				0,
				".$duplicate.",
				".$this->_timestamp."
			)
		";
		#print $q."\n";
		#file_put_contents('utf8-output.txt', $q);
		#print " I: ".$this->_file_data['FILE_PATH']."\n";
		$this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	/*
	* Bei geï¿½nderten Inhalt hat die gleiche datei eine
	* neue checksumme. In diesem Fall werden alle daten geupdated
	*/
	private function filedata_update() {
		
		if (!isset($this->_file_data['MDATA'])) {
			$this->_file_data['MDATA'] = array();
		}
		
		$duplicate = ($this->duplicate_exists()) ? "1" : "NULL" ;
		$isMultiFile = @$this->_file_data['IS_MULTIFILE'];
		
//		$this->_file_data['FILE_PATH'] = FileIO::covertStringToUtf8($this->_file_data['FILE_PATH']);
//		$this->_file_data['FILE_PATH_PACK'] = FileIO::covertStringToUtf8($this->_file_data['FILE_PATH_PACK']);
//		
//		print "\n<pre>";
//		print_r($this->_file_data);
//		print "<pre>\n";
		
		$q = "
			UPDATE
			fdata
			SET
			eccident = '".sqlite_escape_string(strtolower($this->_file_data['FILE_EXT']))."',
			title = '".sqlite_escape_string($this->_file_data['FILE_NAME'])."',
			valid = '".(int)$this->_file_data['FILE_VALID']."',
			state = 1,
			size = ".(int)$this->_file_data['FILE_SIZE'].",
			crc32 = '".sqlite_escape_string(strtoupper($this->_file_data['FILE_CRC32']))."',
			md5 = '".sqlite_escape_string(strtoupper($this->_file_data['FILE_MD5']))."',
			path = '".sqlite_escape_string($this->_file_data['FILE_PATH'])."',
			path_pack = '".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."',
			isMultiFile = ".(int)$isMultiFile.",
			mdata = '".base64_encode(serialize($this->_file_data['MDATA']))."',
			duplicate = ".$duplicate.",
			cdate = ".$this->_timestamp."
			WHERE
			path = '".sqlite_escape_string($this->_file_data['FILE_PATH'])."' AND
			path_pack = '".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."'
			
		";
		#print $q."\n";
		#print " U: ".$this->_file_data['FILE_PATH']."\n";
		$this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	/*
	* Lï¿½scht Files anhand des Pfades aus der datenbank.
	* Wird benï¿½tigt, wenn der user ein Verzeichnis gelï¿½scht
	* hat oder die datei umbenannt hat.
	*/
	private function filedata_delete($path) {
		$q = "DELETE FROM fdata WHERE path = '".sqlite_escape_string($path)."'";
		$this->dbms->query($q);
	}
	
	/*
	* Kontrolliert, ob dieser Datensatz nicht schon in der DB
	* vorhanden ist.
	* return
	* 
	*/
	private function filedata_exists(){
		$q= "SELECT * FROM fdata WHERE path = '".sqlite_escape_string($this->_file_data['FILE_PATH'])."' and path_pack = '".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."' LIMIT 0,1";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) return $res;
		return false;
	}
	
	/*
	* Kontrolliert, ob dieser Datensatz nicht schon in der DB
	* vorhanden ist.
	* return
	* 
	*/
	private function duplicate_exists(){
		
		$q= "
			SELECT count(*) AS cnt FROM fdata WHERE
			crc32 = '".sqlite_escape_string($this->_file_data['FILE_CRC32'])."' and
			eccident = '".sqlite_escape_string(strtolower($this->_file_data['FILE_EXT']))."'
		";
		#print "query: ".$q."\n";
		$hdl = $this->dbms->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			// Checksummen zur weiteren controlle zurï¿½ckreichen
			if ($res['cnt'] > 0) return true;
		}
		return false;
	}
	
	/*
	* ermittelt die filesize, die in der db gespeichert ist.
	*/
	public function get_file_size($path, $packed) {
		$q= "
			SELECT size FROM fdata WHERE
			path = '".sqlite_escape_string($path)."' AND
			path_pack = '".sqlite_escape_string($packed)."'
			LIMIT 0,1
		";
		$hdl = $this->dbms->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			return $res['size'];
		}
		return false;
	}
	
	/*
	* Kontrolliert nach dem Parsen, ob die Daten in der Datenbank
	* valide sind, soll heiï¿½en, ob die Files in der DB noch im Filesystem
	* existieren. Wenn nicht, dann wird der DB-Eintrag gelï¿½scht
	*/
	public function optimize($type=false) {
		
		// only check files lower then basepath.		
		if (!$type && ($this->_basepath)) $q_snip = "AND path like '".sqlite_escape_string($this->_basepath)."%'";
		else $q_snip = "";
		
		$q = "SELECT path FROM fdata WHERE cdate < ".$this->_timestamp." ".$q_snip."";
		print $q;
		$hdl = $this->dbms->query($q);
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			if ($res['path'] && !file_exists($res['path'])) {
				#print "DELETE: ".$res['path']."\n";
				$this->filedata_delete($res['path']);
			}
		}
	}
	
	/*
	* nur / kommen in dateinamen vor
	*/
	public function normalize_path($path){
		return str_replace("\\", "/", $path);
	}
	
	/*
	* ermittelt die filesize, die in der db gespeichert ist.
	*/
	public function findMetaByFilesize($eccident, $filesize) {
		$q= "SELECT filesize FROM mdata WHERE eccident = '".sqlite_escape_string($eccident)."' AND filesize = ".$filesize." LIMIT 0,1";
		#print $q.LF;
		$hdl = $this->dbms->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) return true;
		return false;
	}
	public function findMetaByCrc32($eccident, $crc32) {
		$q= "SELECT crc32 FROM mdata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($crc32)."' LIMIT 0,1";
		#print $q.LF;
		$hdl = $this->dbms->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) return true;
		return false;
	}
	
	/**
	 * Add paths to an table for later reparse all function (like mame)
	 *
	 * @param string $eccident
	 * @param array $paths containing selected paths
	 */
	public function storeSelectedBasePaths($eccident, $paths = array()){
		foreach($paths as $aPath){
			if (!realpath($aPath)) continue;
			$aPath = FACTORY::get('manager/Os')->eccSetRelativeFile($aPath);
			$q = 'REPLACE INTO fdata_reparse (eccident, path, ctime) VALUES ("'.sqlite_escape_string($eccident).'", "'.sqlite_escape_string($aPath).'", '.time().')';
			$hdl = $this->dbms->query($q);
		}
	}
	
}
?>
