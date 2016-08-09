<?
abstract class ImportDat {
	
	protected $datFileName = false;
	protected $datFileString = false;
	
	# holds the status object
	protected $statusObj = false;

	/**
	 * Starts the import
	 *
	 */
	abstract function prepareData();
	
	/**
	 * Validates the given datfile headers
	 */
	abstract protected function validateHeader();
	
	/**
	 * Validates the given datfile internal format
	 */	
	abstract protected function validateFormat();
		
	/**
	 * Setup to use an File as source of the data
	 *
	 * @param string $datFileName filelocation of the datfile
	 */
	public function setFromFile($datFileName){
		$this->datFileName = $datFileName;
	}
	
	/**
	 * Setup to use an given string instead of an file
	 *
	 * @param unknown_type $datFileString
	 */
	public function setFromString($datFileString){
		$this->datFileString = $datFileString;
	}
	
	/**
	 * setter used from FACTORY
	 *
	 * @param object $dbmsObject
	 */
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	public function setStatusHandler($statusObj){
		$this->statusObj = $statusObj;
	}
	
	/**
	 * Search in the db, if this data allready exists
	 *
	 * @param string $eccident
	 * @param string $crc32
	 * @return bool
	 */
	protected function metaExists($eccident, $crc32) {
		$q = "SELECT id FROM fdata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($crc32)."' LIMIT 1";
		$hdl = $this->dbms->query($q);
		return ($res = $hdl->fetch(SQLITE_ASSOC)) ? true : false; 
	}
	
	/**
	 * Insert the metainfos into the mdata table
	 *
	 * @param array $data containing the metainformation
	 * @return int last inserted id
	 */
	protected function insertMeta($data) {
		$q = "
			INSERT INTO
			mdata
			(
				eccident,
				name,
				crc32,
				extension,
				info,
				info_id,
				running,
				bugs,
				trainer,
				intro,
				usermod,
				freeware,
				multiplayer,
				netplay,
				year,
				usk,
				category,
				creator,
				publisher,
				storage
			)
			VALUES
			(
				'".sqlite_escape_string($data['eccident'])."',
				'".sqlite_escape_string($data['name'])."',
				'".sqlite_escape_string($data['crc32'])."',
				'".sqlite_escape_string($data['extension'])."',
				'".sqlite_escape_string($data['info'])."',
				'".sqlite_escape_string($data['info_id'])."',
				".sqlite_escape_string($data['running']).",
				".sqlite_escape_string($data['bugs']).",
				".sqlite_escape_string($data['trainer']).",
				".sqlite_escape_string($data['intro']).",
				".sqlite_escape_string($data['usermod']).",
				".sqlite_escape_string($data['freeware']).",
				".sqlite_escape_string($data['multiplayer']).",
				".sqlite_escape_string($data['netplay']).",
				'".sqlite_escape_string($data['year'])."',
				".sqlite_escape_string($data['usk']).",
				".sqlite_escape_string($data['category']).",
				'".sqlite_escape_string($data['creator'])."',
				'".sqlite_escape_string($data['publisher'])."',
				".sqlite_escape_string($data['storage'])."
			)
		";
		#print $q;
		$this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	/**
	 * Updates an existing dataset with the new metainfos
	 *
	 * @param int $id id of the changed row
	 * @param array $data containing the metainformation
	 */
	protected function updateMeta($id, $data) {
		$q = "
			UPDATE
			mdata
			SET
			eccident = '".sqlite_escape_string($data['eccident'])."',
			name = '".sqlite_escape_string($data['name'])."',
			crc32 = '".sqlite_escape_string($data['crc32'])."',
			extension ='".sqlite_escape_string($data['extension'])."',
			info = '".sqlite_escape_string($data['info'])."',
			info_id = '".sqlite_escape_string($data['info_id'])."',
			running = ".sqlite_escape_string($data['running']).",
			bugs = ".sqlite_escape_string($data['bugs']).",
			trainer = ".sqlite_escape_string($data['trainer']).",
			intro = ".sqlite_escape_string($data['intro']).",
			usermod = ".sqlite_escape_string($data['usermod']).",
			freeware =  ".sqlite_escape_string($data['freeware']).",
			multiplayer = ".sqlite_escape_string($data['multiplayer']).",
			netplay = ".sqlite_escape_string($data['netplay']).",
			year = '".sqlite_escape_string($data['year'])."',
			usk = ".sqlite_escape_string($data['usk']).",
			category = ".sqlite_escape_string($data['category']).",
			creator = '".sqlite_escape_string($data['creator'])."',
			publisher = '".sqlite_escape_string($data['publisher'])."',
			storage = ".sqlite_escape_string($data['storage']).",
			uexport = NULL,
			cdate = NULL
			WHERE
			id = ".$id."
		";
		#print $q;
		$this->dbms->query($q);
	}
	
	/**
	 * Deletes and inserts the new found lanuguages
	 *
	 * @param int $mdata_id id of the row for update
	 * @param array $languages array of languages
	 */
	protected function updateMetaLanguage($id, $languages) {
		$q = "DELETE FROM mdata_language WHERE mdata_id=".$id;
		$hdl = $this->dbms->query($q);
		foreach ($languages as $cc => $void) {
			$q = "INSERT INTO mdata_language ( mdata_id, lang_id) VALUES ('".$id."', '".$cc."')";
			$hdl = $this->dbms->query($q);
		}
	}
	
	/**
	 * return the datfile content as an array
	 *
	 * @param string $filename
	 * @return array
	 */
	protected function readDatfileContent($filename){
		return FACTORY::get('manager/IniFile')->parse_ini_file_quotes_safe($filename);
	}
	
//	protected function updateStatusProgress($total, $current, $each, $message){
//
//		if (!$this->statusObj) return false;
//		
//		if ($current >= 1000) $current = 0;
//		
//		// status-area update
//		// --------------------
//		if ($current%$each==0) {
//			
//			if (!isset($sb_counter)) $sb_counter = 0;
//			if ($sb_counter >= 100) $sb_counter = 0;
//			$sb_counter++;
//			
//
//			$percent = (float)$sb_counter/100;
//			$this->statusObj->update_progressbar($percent, $message);
//
//			if ($this->statusObj->is_canceled()) return false;
//			
//			while (gtk::events_pending()) gtk::main_iteration();
//		}
//	}
	
	protected function updateStatusProgress($message, $total, $current, $transaction = false, $each = false){
		
		if (!$this->statusObj) return false;
		
		if ($each && $current%$each != 0) return false;
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		$percent_string = sprintf("%02d", ($current*100)/$total);
		$msg = $message.": ".$percent_string." % ($current/$total)";
		
		$percent = (float)$current/$total;
		$this->statusObj->update_progressbar($percent, $msg);
		
		if ($this->statusObj->is_canceled()){
			if ($transaction) $this->dbms->query('ROLLBACK TRANSACTION;');
			return 'FAIL';
		}

		return true;
	}
	
	protected function updateStatusProgressInfinity($message, $transaction = false){

		if (!$this->statusObj) return false;

		if (!isset($this->statusCurrentPos)) $this->statusCurrentPos = 0;
		if ($this->statusCurrentPos >= 100) $this->statusCurrentPos = 0;
		$this->statusCurrentPos++;
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		$this->statusObj->update_progressbar((float)$this->statusCurrentPos/100, $message);

		if ($this->statusObj->is_canceled()){
			if ($transaction) $this->dbms->query('ROLLBACK TRANSACTION;');
			return 'FAIL';
		}
		return true;
	}
}
?>