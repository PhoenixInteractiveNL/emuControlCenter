<?php

class UserData {
	
	private $dbms = false;

	public function __construct() {}
	
	public function setDbms($dbmsObject) {
		$this->dbms =  $dbmsObject;
	}
	
	public function getUserdata($eccident, $crc32) {
		if (!$eccident || !$crc32) return false;
		$q="SELECT * FROM udata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($crc32)."' LIMIT 1";
		$hdl = $this->dbms->query($q);
		return $hdl->fetch(SQLITE_ASSOC);
	}
	
	public function updateNotesById($userDataId, $notes) {
		if (!$userDataId) return false;
		$q = "UPDATE udata SET notes = '".sqlite_escape_string($notes)."' WHERE id = ".(int)$userDataId."";
		$hdl = $this->dbms->query($q);
	}
	
	public function insertNotesByRomident($eccident, $crc32, $notes) {
		if (!$eccident || !$crc32) return false;
		$notes = trim($notes);
		$q = "INSERT INTO udata (eccident, crc32, notes) VALUES ('".sqlite_escape_string($eccident)."', '".sqlite_escape_string($crc32)."', '".sqlite_escape_string($notes)."')";
		$this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	public function deleteNotesByRomident($eccident, $crc32) {
		if (!$eccident || !$crc32) return false;
		$q = "DELETE FROM udata WHERE eccident = '".sqlite_escape_string($eccident)."' AND '".sqlite_escape_string($crc32)."'";
		$this->dbms->query($q);
	}
	
	public function deleteById($id) {
		if (!$id) return false;
		$q = "DELETE FROM udata WHERE id = ".(int)$id."";
		$this->dbms->query($q);
	}
}

?>
