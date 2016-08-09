<?php

class EccParserMedia {
	
	// db-connection object
	private $_db = false;
	// Daten des Files zum schreiben in
	// einem array
	private $_file_data = false;
	
	//
	private $_basepath = false;
	
	//
	private static $_timestamp = false;
	
	/*
	* Übergabe des DB-Objects
	*/
	public function __construct($obj_db, $path) {
		$this->_basepath = $this->normalize_path($path);
		$this->_timestamp = time();
		$this->_db = $obj_db;
	}
	
	/*
	* Überprüft, wie vorgegangen werden muss.
	* Ist ein File schon in der datenbank, muss es nicht
	* noch einmal indiziert werden.
	*/
	public function add_file($file_data) {
		
		$this->_file_data = $file_data;
		
		if ($this->_file_data) {
			// Was muss getan werden
			// update/insert
			$fd_exists = $this->filedata_exists();
			if (!$fd_exists) {
				// NEU - Noch nicht in der Datenbank vorhanden.
				$this->filedata_insert();
			} else {
				// UPDATE - Änderungen im File == neue checksumme
				if (
					($this->_file_data['FILE_MD5'] && $this->_file_data['FILE_MD5'] != $fd_exists['md5']) ||
					($this->_file_data['FILE_CRC32'] && $this->_file_data['FILE_CRC32'] != $fd_exists['crc32'])
				) {
					$this->filedata_update();
				} else {
					#print "V: ".$this->_file_data['FILE_PATH']."\n";
				}
			}
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
				'".sqlite_escape_string($this->_file_data['FILE_PATH'])."',
				'".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."',
				'".base64_encode(serialize($this->_file_data['MDATA']))."',
				NULL,
				0,
				".$duplicate.",
				".$this->_timestamp."
			)
		";
		#print $q."\n";
		#print " I: ".$this->_file_data['FILE_PATH']."\n";
		$this->_db->query($q);
	}
	
	/*
	* Bei geänderten Inhalt hat die gleiche datei eine
	* neue checksumme. In diesem Fall werden alle daten geupdated
	*/
	private function filedata_update() {
		
		if (!isset($this->_file_data['MDATA'])) {
			$this->_file_data['MDATA'] = array();
		}
		
		$duplicate = ($this->duplicate_exists()) ? "1" : "NULL" ;
		
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
			mdata = '".base64_encode(serialize($this->_file_data['MDATA']))."',
			duplicate = ".$duplicate.",
			cdate = ".$this->_timestamp."
			WHERE
			path = '".sqlite_escape_string($this->_file_data['FILE_PATH'])."' AND
			path_pack = '".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."'
			
		";
		#print $q."\n";
		#print " U: ".$this->_file_data['FILE_PATH']."\n";
		$this->_db->query($q);
	}
	
	/*
	* Löscht Files anhand des Pfades aus der datenbank.
	* Wird benötigt, wenn der user ein Verzeichnis gelöscht
	* hat oder die datei umbenannt hat.
	*/
	private function filedata_delete($path) {
		$q = "
			DELETE FROM
			fdata
			WHERE
			path = '".sqlite_escape_string($path)."'
		";
		#print " Delete from ecc: ".$path."\n";
		$this->_db->query($q);
	}
	
	/*
	* Kontrolliert, ob dieser Datensatz nicht schon in der DB
	* vorhanden ist.
	* return
	* 
	*/
	private function filedata_exists(){
		
		$q= "
			SELECT
			*
			FROM
			fdata
			WHERE
			path = '".sqlite_escape_string($this->_file_data['FILE_PATH'])."' and
			path_pack = '".sqlite_escape_string($this->_file_data['FILE_PATH_PACK'])."'
			LIMIT
			0,1
		";
		$hdl = $this->_db->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			// Checksummen zur weiteren controlle zurückreichen
			return $res;
		}
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
			SELECT
			count(*) AS cnt
			FROM
			fdata
			WHERE
			crc32 = '".sqlite_escape_string($this->_file_data['FILE_CRC32'])."' and
			eccident = '".sqlite_escape_string(strtolower($this->_file_data['FILE_EXT']))."'
		";
		#print "query: ".$q."\n";
		$hdl = $this->_db->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			// Checksummen zur weiteren controlle zurückreichen
			if ($res['cnt'] > 0) return true;
		}
		return false;
	}
	
	/*
	* ermittelt die filesize, die in der db gespeichert ist.
	*/
	public function get_file_size($path, $packed) {
		$q= "
			SELECT
			size
			FROM
			fdata
			WHERE
			path = '".sqlite_escape_string($path)."' AND
			path_pack = '".sqlite_escape_string($packed)."'
			LIMIT
			0,1
		";
		$hdl = $this->_db->query($q);
		if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			return $res['size'];
		}
		return false;
	}
	
	/*
	* Kontrolliert nach dem Parsen, ob die Daten in der Datenbank
	* valide sind, soll heißen, ob die Files in der DB noch im Filesystem
	* existieren. Wenn nicht, dann wird der DB-Eintrag gelöscht
	*/
	public function optimize($type=false) {
		
		if (!$type && ($this->_basepath)) {
			// nur files im und unterhalb des basepaths überprüfen.
			$q_snip = "AND path like '".$this->_basepath."%'";
		}
		else {
			// alle überprüfen
			$q_snip = "";
		}
		
		$q = "
			SELECT
			path
			FROM
			fdata
			WHERE
			cdate < ".$this->_timestamp."
			".$q_snip."
		";
		$hdl = $this->_db->query($q);
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
	public function normalize_path($path)
	{
		return str_replace("\\", "/", $path);
	}
}
?>
