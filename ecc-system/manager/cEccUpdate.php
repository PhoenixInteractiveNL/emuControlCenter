<?
class EccUpdate {
	
	private $dbms = false;
	
	// called by FACTORY
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	public function updateSystem($eccVersion) {
		
		// get version of ecc stored in db
		$eccDbVersion = $this->getEccDbVersion();
		
		// allready updated!
		if ($eccVersion == $eccDbVersion ) return true;
		if (!$this->backupEccDb()) return false;
		
		// handle all update from begining to now
		$errorVersion = false;
		switch (true) {
			case $eccDbVersion < '0.9.701':
				if ($this->updateEccFromConfig('0.9.701')) $this->updateEccDbVersion('0.9.701');
				else {
					$errorVersion = '0.9.701';
					break;
				}
			case $eccDbVersion < '0.9.706':
				if ($this->updateEccFromConfig('0.9.706')) $this->updateEccDbVersion('0.9.706');
				else {
					$errorVersion = '0.9.706';
					break;
				}
			case $eccDbVersion < '0.9.802':
				if ($this->updateEccFromConfig('0.9.802')) $this->updateEccDbVersion('0.9.802');
				else {
					$errorVersion = '0.9.802';
					break;
				}
			case $eccDbVersion < '0.9.805':
				if ($this->updateEccFromConfig('0.9.805')) $this->updateEccDbVersion('0.9.805');
				else {
					$errorVersion = '0.9.805';
					break;
				}
		}
		if (!$errorVersion) $this->updateEccDbVersion($eccVersion);
		print "VERSION NOW ".$eccVersion." #$errorVersion#".LF.LF;
	}
	
	private function updateEccFromConfig($version) {
		$success = true;
		require_once('updates/update_'.$version.'.php');
		foreach ($updateConfig['db'] as $index => $query) {
			@$this->dbms->query($query);
			#if (!$hdl) $success = false;
		}
		print "DATABASE UPDATED TO VERSION $version\n";
		return $success;
	}
	
	private function updateEccDbVersion($eccVersion) {
		
		print "Function: ".__FUNCTION__."\n";
		print_r($eccVersion)."\n";
		
		
		$q = "DELETE FROM eccdb_state";
		$hdl = $this->dbms->query($q);
		$q = "INSERT INTO eccdb_state (version, date) VALUES ('".sqlite_escape_string($eccVersion)."', ".(int)time().") ";
		print $q;
		$hdl = $this->dbms->query($q);
	}
	
	private function getEccDbVersion() {
		$q = "SELECT name FROM sqlite_master WHERE type='table' and name='eccdb_state' LIMIT 1";
		$hdl = $this->dbms->query($q);
		#if (!$hdl->fetchSingle()) {
			$q = 'CREATE TABLE "eccdb_state" ( "version" VARCHAR(9)  NOT NULL, "date" INTEGER(10)  NOT NULL)';
			@$this->dbms->query($q);
		#}
		$q = "SELECT version FROM eccdb_state";
		$hdl = $this->dbms->query($q);
		return ($eccDbVersion = $hdl->fetchSingle()) ? $eccDbVersion : false;
	}
	
	private function backupEccDb() {
		$backupDir = 'database/backup';
		if (!is_dir($backupDir)) mkdir($backupDir);
		return copy('database/eccdb', $backupDir.'/eccdb_'.date("Y-m-d_His", time()));
	}
}
?>
