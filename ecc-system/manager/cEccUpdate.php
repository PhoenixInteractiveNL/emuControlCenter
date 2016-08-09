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
			case $eccDbVersion < '0.5.901':
				if ($this->updateEccFromConfig('0.5.901')) $this->updateEccDbVersion('0.5.901');
				else {
					$errorVersion = '0.5.901';
					break;
				}
			case $eccDbVersion < '0.7.0':
				if ($this->updateEccFromConfig('0.7.0')) $this->updateEccDbVersion('0.7.0');
				else $errorVersion = '0.7.0';
			break;
			
		}
		if (!$errorVersion) $this->updateEccDbVersion($eccVersion);
		#print "VERSION NOW ".$eccVersion."".LF.LF;
	}
	
	private function updateEccFromConfig($version) {
		$success = true;
		require_once('updates/update_'.$version.'.php');
		foreach ($updateConfig['db'] as $index => $query) {
			$hdl = @$this->dbms->query($query);
			if (!$hdl) $success = false;
		}
		return $success;
	}
	
	private function updateEccDbVersion($eccVersion) {
		$q = "DELETE FROM eccdb_state";
		$hdl = $this->dbms->query($q);
		$q = "INSERT INTO eccdb_state (version, date) VALUES ('".sqlite_escape_string($eccVersion)."', ".(int)time().") ";
		$hdl = $this->dbms->query($q);
	}
	
	private function getEccDbVersion() {
		$q = "SELECT name FROM sqlite_master WHERE type='table' and name='eccdb_state' LIMIT 1";
		$hdl = $this->dbms->query($q);
		if (!$hdl->fetchSingle()) {
			$q = 'CREATE TABLE "eccdb_state" ( "version" VARCHAR(7)  NOT NULL, "date" INTEGER(10)  NOT NULL)';
			$this->dbms->query($q);
		}
		$q = "SELECT version FROM eccdb_state";
		$hdl = $this->dbms->query($q);
		return ($eccDbVersion = $hdl->fetchSingle()) ? $eccDbVersion : false;
	}
	
	private function backupEccDb() {
		return copy('database/eccdb', 'database/eccdb_bak_'.date("Ymd_His", time()));
	}
}
?>
