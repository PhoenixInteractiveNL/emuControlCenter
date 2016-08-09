<?php
/*
 * Created on 30.09.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once('cDbms.php');
class DbmsSqlite2 extends Dbms {
	
	private $dbms;
	private $connectionPath = false;
	private $connectionMode = '0666';
	
	public function __construct() {
		parent::__construct();
	}

	public function query($q) {
		#print $q.LF;
		return $this->dbms->query(trim($q));
	}

	public function lastInsertRowid() {
		return $this->dbms->lastInsertRowid();
	}
	
	public function connect() {
		$sqliteerror = false;
		$this->dbms = new SQLiteDatabase($this->connectionPath, $this->connectionMode, $sqliteerror);
		if ($sqliteerror) return $sqliteerror;
		
		#$q = "PRAGMA cache = 6000;";
		#$this->query($q);
		
		$q = "PRAGMA synchronous = OFF;";
		$this->query($q);

		$q = "PRAGMA temp_store = MEMORY;";
		$this->query($q);

		return $this->dbms;
	}
	
	public function setConnectionPath($path) {
		$this->connectionPath = $path;
	}
	
	public function setConnectionMode($mode) {
		$this->connectionMode = $mode;
	}
}

?>
