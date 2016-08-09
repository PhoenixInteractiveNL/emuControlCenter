<?php
require_once('SqlHelper.php');

class TreeviewData {
	
	private $dbms = false;
	
	private $showOnlyPersonal = false;
	private $personalMode = false;
	
	private $showOnlyDontHave = false;
	
	private $showOnlyPlayed = false;
	private $showOnlyMostPlayed = false;
	private $showOnlyNotPlayed = false;
	
	private $showOnlyBookmarks = false;
	
	// show only given disk
	private $showOnlyDisk = false;
	
	# search parameter
	private $searchMameDriver = false;
	
	private $dumpType;
	
	private $sqlFields = '
		UPPER(CASE WHEN md.name ISNULL THEN fd.title ELSE (CASE WHEN md.media_current ISNULL THEN md.name ELSE md.name||md.media_current END) END) as orderByThis,
		md.crc32 as md_crc32,
		md.id as md_id,
		md.eccident as md_eccident,
		md.name as md_name,
		md.info as md_info,
		md.info_id as md_info_id,
		md.running as md_running,
		md.bugs as md_bugs,
		md.trainer as md_trainer,
		md.intro as md_intro,
		md.usermod as md_usermod,
		md.freeware as md_freeware,
		md.multiplayer as md_multiplayer,
		md.netplay as md_netplay,
		md.year as md_year,
		md.usk as md_usk,
		md.rating as md_rating,
		md.category as md_category,
		md.creator as md_creator,
		md.publisher as md_publisher,
		md.programmer as md_programmer,
		md.musican as md_musican,
		md.graphics as md_graphics,
		md.media_type as md_media_type,
		md.media_current as md_media_current,
		md.media_count as md_media_count,
		md.storage as md_storage,
		md.region as md_region,
		md.cdate as md_cdate,
		md.uexport as md_uexport,
		md.dump_type as md_dump_type,
		fd.id as id,
		fd.title as title,
		fd.path as path,
		fd.path_pack as path_pack,
		fd.crc32 as crc32,
		fd.md5 as md5,
		fd.size as size,
		fd.eccident as fd_eccident,
		fd.launchtime as fd_launchtime,
		fd.launchcnt as fd_launchcnt,
		fd.isMultiFile as fd_isMultiFile,
		fd.mdata as fd_mdata,
		fa.fDataId as fa_fDataId,
		fa.isMatch as fa_isMatch,
		fa.fileName as fa_fileName,
		fa.isValidFileName as fa_isValidFileName,
		fa.isValidNonMergedSet as fa_isValidNonMergedSet,
		fa.isValidSplitSet as fa_isValidSplitSet,
		fa.isValidMergedSet as fa_isValidMergedSet,
		fa.hasTrashfiles as fa_hasTrashfiles,
		fa.cloneOf as fa_cloneOf,
		fa.romOf as fa_romOf,
		fa.mameDriver as fa_mameDriver
	';
	
	// called by FACTORY
	public function setDbms($dbmsObject){
		$this->dbms = $dbmsObject;
	}
	
	public function showOnlyPersonal($state){
		$this->showOnlyPersonal = $state;
	}
	
	public function setPersonalMode($mode){
		$this->personalMode = $mode;
	}
	
	public function showOnlyDontHave($state){
		$this->showOnlyDontHave = $state;
	}	
	
	public function showOnlyPlayed($state){
		$this->showOnlyPlayed = $state;
	}

	public function showOnlyBookmarks($state){
		$this->showOnlyBookmarks = $state;
	}
	
	public function showOnlyMostPlayed($state){
		$this->showOnlyMostPlayed = $state;
	}
	
	public function showOnlyNotPlayed($state){
		$this->showOnlyNotPlayed = $state;
	}
	
	public function setShowOnlyDisk($state){
		$this->showOnlyDisk = $state;
	}
	public function getShowOnlyDisk(){
		return $this->showOnlyDisk;
	}		
	
	public function setDumpType($dumpType){
		$this->dumpType = $dumpType;
	}
	public function getDumpType(){
		return $this->dumpType;
	}		
	
	
	
	/* ------------------------------------------------------------------------
	* VERSION TO GET ALSO META-DATA, IF THERE IS NO FOUND GAME
	* -------------------------------------------------------------------------
	*/
	public function getSearchResults(
		$extension,
		$like=false,
		$limit=array(),
		$return_count=true,
		$orderBy="",
		$language=false,
		$category=false,
		$search_ext=false,
		$onlyFiles=true,
		$hideDup=false,
		$hideMetaless=false,
		$searchRating = false,
		$randomGame = false,
		$updateCategories = false
	)
	{
		
		$snip_where = array();
		$sqlOrderBy = array();
		
		// freeform search like
		if ($like) $snip_where[] = $like;

		// Show/hide doublettes
		if ($hideDup) $snip_where[] = "fd.duplicate is null";
		
		// show/hide missing roms
		if (!$onlyFiles && !$this->showOnlyDontHave){
			if ($extension) $snip_where[] = "fd.eccident='".sqlite_escape_string($extension)."'";
		}
		else{
			if ($extension) $snip_where[] = "md.eccident='".sqlite_escape_string($extension)."'";
		}
		
		if ($this->searchMameDriver && $extension = 'mame') $snip_where[] = "fa.mameDriver IN (".$this->searchMameDriver.")";
		
		if ($searchRating) {
			$snip_where[] = "md.rating<=".(int)$searchRating."";
			$rateOrder = ($orderBy == 'DESC') ? 'ASC' : 'DESC';
			$sqlOrderBy[] = "md.rating ".$rateOrder."";
		}
		
		if ($category) $snip_where[] = "md.category=".$category."";
		if ($esearch = SqlHelper::createSqlExtSearch($search_ext)) $snip_where[] = $esearch;
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		if ($hideMetaless) $snip_where[] = "md.id IS NULL";
		
		$snip_join = array();
		
		if($this->showOnlyPlayed){
			$snip_where[] = "fd.launchcnt > 0";
			$playedOrder = ($orderBy == 'DESC') ? 'ASC' : 'DESC';
			$sqlOrderBy[] = 'fd.launchtime '.$playedOrder;				
		}
		elseif($this->showOnlyMostPlayed){
			$snip_where[] = "fd.launchcnt > 0";
			$playedOrder = ($orderBy == 'DESC') ? 'ASC' : 'DESC';
			$sqlOrderBy[] = 'fd.launchcnt '.$playedOrder;
		}
		elseif($this->showOnlyNotPlayed){
			$snip_where[] = "fd.launchcnt = 0";
		}
		
		$discCondition = $this->getShowOnlyDisk();
		if($discCondition){
			if($discCondition == 'one_plus'){
				$snip_where[] = '(md.media_current = 1 OR md.media_current IS NULL OR md.media_current = \'\')';
			}
			else{
				# only disk 1
				$snip_where[] = 'md.media_current = 1';
			}
		}
		
		if ($this->showOnlyDontHave){
			$snip_where[] = "fd.id IS NULL";
			$snip_join[] = "mdata AS md left join fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32)";
			$sqlOrderBy[] = "orderByThis ".$orderBy;
		}
		elseif($this->showOnlyBookmarks){
			$snip_join[] = "fdata_bookmarks AS fdb INNER JOIN fdata fd ON (fd.id=fdb.file_id) left join mdata AS md on (fd.eccident=md.eccident and fd.crc32=md.crc32)";
			$sqlOrderBy[] = "orderByThis ".$orderBy;
		}
		else{
			if ($this->showOnlyPersonal){
				$snip_join[] = "udata AS ud inner join fdata AS fd on (ud.eccident=fd.eccident and ud.crc32=fd.crc32) left join mdata AS md on (fd.eccident=md.eccident and fd.crc32=md.crc32)";
				
				switch($this->personalMode){
					case 'review':
						$snip_where[] = "(ud.review_title != '' OR ud.review_body != '')";
						break;	
					case 'notes':
					case '':
						$snip_where[] = "ud.notes != ''";
						break;	
				}
			}
			elseif (!$onlyFiles) $snip_join[] = "fdata AS fd left join mdata AS md on (fd.eccident=md.eccident and fd.crc32=md.crc32)";
			else $snip_join[] = "mdata AS md left join fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32)";
			$sqlOrderBy[] = "orderByThis ".$orderBy;	
		}
	
		if ($language) $snip_join[] = "left join mdata_language AS mdl on md.id=mdl.mdata_id";
		
		$snip_join[] = "left join fdata_audit AS fa on fd.id=fa.fDataId";
		
		#if($this->showOnlyBookmarks) $snip_join[] = "INNER JOIN fdata_bookmarks AS fdb ON (fd.id=fdb.file_id)";
		
		if ($randomGame) {
			$sqlOrderBy = array('random(*)');
			$limit = array(0,1);
		}
		
		#$snip_where[] = "(fa.cloneOf = '' OR fa.cloneOf is null) AND (fa.romOf = '' OR fa.romOf is null)"; 
		
		# create sql snipplets
		$snipSqlWhere = SqlHelper::createSqlWhere($snip_where);
		$snipSqlJoin = SqlHelper::createSqlJoin($snip_join);
		$snipSqlOrderBy = SqlHelper::createSqlOrder($sqlOrderBy);
		$snipSqlLimit = SqlHelper::createSqlLimit($limit);
		
		# create sql
		$q = "
			SELECT
			".$this->sqlFields."
			FROM
			".$snipSqlJoin."
			WHERE
			".$snipSqlWhere."
			".$snipSqlOrderBy."
			".$snipSqlLimit."
		";
		//print $q;
		$hdl = $this->dbms->query($q);
		$ret = array();
		$this->foundMameDriver = array();
		
		$romMetaObjects = array();
		
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			
			$compositeId = $res['id']."|".$res['md_id'];
			
			$ret['data'][$compositeId] = $res;
			$ret['data'][$compositeId]['composite_id'] = $compositeId;
			
			if ($res['fa_mameDriver']) $this->foundMameDriver[trim($res['fa_mameDriver'])] = trim($res['fa_mameDriver']);
			
			if (!isset($mameDrivers[trim($res['fa_mameDriver'])])) $mameDrivers[trim($res['fa_mameDriver'])] = 1;
			else $mameDrivers[trim($res['fa_mameDriver'])]++;

			// create rom file object
			$romFile = new RomFile(); // create new object
			$romFile->fillFromDatabase($res); // create objects from db fields
			
			// create rom meta data object
			$romMeta = new RomMeta(); // create new object
			$romMeta->fillFromDatabase($res); // create objects from db fields
			$romMeta->setLanguages($this->get_language_by_mdata_id($res['md_id'])); // set languages array

			// create rom audit object
			$romAudit = new RomAudit(); // create new object
			$romAudit->fillFromDatabase($res); // create objects from db fields
			
			$rom = new Rom();
			$rom->setRomFile($romFile);
			$rom->setRomMeta($romMeta);
			$rom->setRomAudit($romAudit);
			
			$ret['rom'][$compositeId] = $rom; // check valid stae
		}
		
		if (isset($mameDrivers)) {
			foreach ($mameDrivers as $aMameDriver => $count) $this->foundMameDriver[$aMameDriver] = $count;
		}
		
		if ($return_count===true) {
			$q = "SELECT count(*) FROM ".$snipSqlJoin." WHERE ".$snipSqlWhere."";
			#print $q."\n";
			$hdl = $this->dbms->query($q);
			$ret['count'] = $hdl->fetchSingle();
		}
				
		if ($updateCategories){
			$eccidentSql = ($extension) ? 'md.eccident="'.sqlite_escape_string($extension).'"' : '1';
			//$q = "SELECT md.category, count(*) AS cnt FROM ".$snipSqlJoin." WHERE ".$eccidentSql." GROUP BY md.category ORDER BY cnt DESC";
			$q = 'SELECT md.category, count(*) AS cnt FROM mdata md WHERE '.$eccidentSql.' GROUP BY md.category ORDER BY cnt DESC';
			#$q = "SELECT md.category, count(*) AS cnt FROM ".$snipSqlJoin." WHERE ".$snipSqlWhere." GROUP BY md.category ORDER BY cnt DESC";
			$hdl = $this->dbms->query($q);
			$ret['cat'] = array();
			while($res = $hdl->fetch(SQLITE_ASSOC)) {
				$ret['cat'][$res['md.category']] = $res['cnt'];
			}
		}
		return $ret;
	}
	
	public function getRecordByMetaId($id){
		
		$q = "
			SELECT
			".$this->sqlFields."
			FROM
			mdata AS md left join fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32)
			left join fdata_audit AS fa on fd.id=fa.fDataId
			WHERE
			md.id='".(int)$id."'
		";
		
		$romFile = new RomFile();
		$romMeta = new RomMeta();
		$romAudit = new RomAudit();
		
//		$ret = array();
		$hdl = $this->dbms->query($q);
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			
			$romMeta->fillFromDatabase($res);
			$romFile->fillFromDatabase($res);
			$romAudit->fillFromDatabase($res);
			
//			$ret['data'][$res['id']."|".$res['md_id']] = $res;
//			$ret['data'][$res['id']."|".$res['md_id']]['composite_id'] = $res['id']."|".$res['md_id'];
		}
		
		$rom = new Rom();
		$rom->setRomFile($romFile);
		$rom->setRomMeta($romMeta);
		$rom->setRomAudit($romAudit);
		
		return $rom;
	}
	
	public function getRecordByFileId($id){
		
		$q = "
			SELECT
			".$this->sqlFields."
			FROM
			fdata AS fd left join mdata AS md on (fd.eccident=md.eccident and fd.crc32=md.crc32)
			left join fdata_audit AS fa on fd.id=fa.fDataId
			WHERE
			fd.id='".(int)$id."'
			LIMIT 1
		";

		
		$romFile = new RomFile();
		$romMeta = new RomMeta();
		$romAudit = new RomAudit();

		$ret = array();
		$hdl = $this->dbms->query($q);
		if($res = $hdl->fetch(SQLITE_ASSOC)) {
			
			$romMeta->fillFromDatabase($res);
			$romFile->fillFromDatabase($res);
			$romAudit->fillFromDatabase($res);
			
//			$ret['data'][$res['id']."|".$res['md_id']] = $res;
//			$ret['data'][$res['id']."|".$res['md_id']]['composite_id'] = $res['id']."|".$res['md_id'];
		}
		
		$rom = new Rom();
		$rom->setRomFile($romFile);
		$rom->setRomMeta($romMeta);
		$rom->setRomAudit($romAudit);
		
		return $rom;
	}	
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $mdataId
	 * @param unknown_type $rating
	 * @return unknown
	 */
	public function addRatingByMdataId($mdataId, $rating)
	{
		if (!$mdataId || $rating > 6) return false;
		$q = "UPDATE mdata set rating = ".(int)$rating.", cdate = ".time().", uexport = NULL WHERE id = ".(int)$mdataId."";
		$this->dbms->query($q);
		return true;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $eccIdent
	 * @return unknown
	 */
	public function unsetRatingsByEccident($eccIdent) {
		$sqlWhere = ($eccIdent) ? " WHERE eccident = '".sqlite_escape_string($eccIdent)."'" : '';
		$q = "UPDATE mdata SET rating = NULL, cdate = ".time().", uexport = NULL ".$sqlWhere."";
		$this->dbms->query($q);
		return true;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function addBookmarkById($id)
	{
		if (!$id) return false;
		
		// is bookmark in db
		$q = "select file_id from fdata_bookmarks where file_id = ".(int)$id." ";
		$hdl = $this->dbms->query($q);
		if ($hdl->fetchSingle()) return false;
		
		// new bookmark
		$q = "INSERT INTO fdata_bookmarks (file_id) VALUES (".(int)$id.")";
		$hdl = $this->dbms->query($q);
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function deleteBookmarkById($id) {
		if ($id) {
			$q = 'DELETE FROM fdata_bookmarks WHERE file_id = '.(int)$id.'';
			$hdl = $this->dbms->query($q);
		}
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function remove_bookmark_all() {
		$q = 'DELETE FROM fdata_bookmarks';
		$hdl = $this->dbms->query($q);
	}

	public function get_duplicates_all($eccident, $remove = false) {
		
		$snip_where = array();
		if ($eccident) $snip_where[] = "eccident='".sqlite_escape_string($eccident)."'";
		$snip_where[] = "duplicate=1";
		
		#$sql_snip = implode(" AND ", $snip_where);
		$sql_snip = SqlHelper::createSqlWhere($snip_where);
		
		$removeString = ($remove) ? 'and removed (database only)' : 'your';
		$msg = LOGGER::add('romparse', "Find $removeString duplicate roms\r\nPlatform: $eccident", 1);
		
		$q = "
			SELECT
			eccident,
			count(*) as cnt
			FROM
			fdata
			WHERE
			".$sql_snip."
			GROUP BY
			eccident
			ORDER BY
			eccident";
		$hdl = $this->dbms->query($q);
		$out = array();
		$msgRoms = '';
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
				
			$msgRoms .= LOGGER::add('romparse', $res['eccident']." (".$res['cnt'].")");
			
			$log = array();
			$q2 = "select crc32 from fdata where eccident = '".$res['eccident']."' AND duplicate=1 ORDER BY crc32";
			$hdl2 = $this->dbms->query($q2);
			$lastEccident = false;
			while($res2 = $hdl2->fetch(SQLITE_ASSOC)) {

				$msgRoms .= LOGGER::add('romparse', $res2['crc32']);
				$msgRoms .= LOGGER::add('romparse', "STATE\tCRC32\tTITLE\tPATH");
				
				$q3 = "SELECT crc32, title, path, duplicate FROM fdata WHERE eccident='".sqlite_escape_string($res['eccident'])."' AND crc32='".sqlite_escape_string($res2['crc32'])."' ORDER BY duplicate, title ASC";
				$hdl3 = $this->dbms->query($q3);
				while($res3 = $hdl3->fetch(SQLITE_ASSOC)) {
					$state = ($res3['duplicate']) ? '-' : '=';
					$msgRoms .= LOGGER::add('romparse', $state."\t".$res3['crc32']."\t".$res3['title']."\t".$res3['path']);
				}
			}
		}
		$msg .= ($msgRoms) ? $msgRoms : 'congratulation - no duplicates found! :-)' ;
		
		if ($remove) {
			$q = "DELETE FROM fdata WHERE ".$sql_snip."";
			$hdl = $this->dbms->query($q);
		}
		
		return $msg;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function deleteRomFromDatabase($id, $eccident, $crc32) {
		if (!$id) return false;
		
		$q = 'DELETE FROM fdata WHERE id = '.(int)$id;
		$hdl = $this->dbms->query($q);
		
		$duplicates = $this->get_duplicates($eccident, $crc32);
		if (!count($duplicates)) return true;
		
		if (!in_array('', $duplicates)) {
			$this->update_duplicate(key($duplicates));
		}
		
		// remove bookmarks also
		$this->deleteBookmarkById($id);
		
		return true;
	}
	
	public function removeSingleMetaData($mdataId){
		$q = 'DELETE FROM mdata WHERE id = '.(int)$mdataId;
		$hdl = $this->dbms->query($q);
		$q = "DELETE FROM mdata_language WHERE mdata_id = ".(int)$mdataId;
		$hdl = $this->dbms->query($q);
	}
	
	
	public function get_duplicates($eccident, $crc32) {
		$q = "SELECT * FROM fdata WHERE eccident='".sqlite_escape_string($eccident)."' AND crc32='".sqlite_escape_string($crc32)."'";
		$hdl = $this->dbms->query($q);
		$out = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$out[$res['id']] = $res['duplicate'];
		}
		return $out;
	}
	
	public function update_duplicate($id) {
		$q = "UPDATE fdata SET duplicate = NULL WHERE id = ".$id."";
		$hdl = $this->dbms->query($q);
	}
	
	public function remove_media_duplicates($eccident, $crc32) {
		$q = "DELETE FROM fdata WHERE eccident='".sqlite_escape_string($eccident)."' AND crc32='".sqlite_escape_string($crc32)."'";
		$hdl = $this->dbms->query($q);
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function update_file_info($data, $modified=false) {
		
		//$modified_snip = ($modified) ? ", cdate = '".time()."'" : "";
		
		$q = "
			UPDATE
			mdata
			SET
			name = '".sqlite_escape_string($data['name'])."',
			info = '".sqlite_escape_string($data['info'])."',
			info_id = '".sqlite_escape_string($data['info_id'])."',
			running = ".$data['running'].",
			bugs = ".$data['bugs'].",
			trainer = ".$data['trainer'].",
			intro = ".$data['intro'].",
			usermod = ".$data['usermod'].",
			multiplayer = ".$data['multiplayer'].",
			netplay = ".$data['netplay'].",
			freeware = ".$data['freeware'].",
			year = '".sqlite_escape_string($data['year'])."',
			usk = '".sqlite_escape_string($data['usk'])."',
			category = ".$data['category'].",
			creator = '".sqlite_escape_string($data['creator'])."',
			publisher = '".sqlite_escape_string($data['publisher'])."',
			programmer = '".sqlite_escape_string($data['programmer'])."',
			musican = '".sqlite_escape_string($data['musican'])."',
			graphics = '".sqlite_escape_string($data['graphics'])."',
			media_type = ".(int)$data['media_type'].",
			media_current = ".$data['media_current'].",
			media_count = ".$data['media_count'].",
			storage = ".sqlite_escape_string($data['storage']).",
			region = ".sqlite_escape_string($data['region']).",
			cdate = ".time().",
			uexport = NULL
			WHERE
			id = ".$data['id']."
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	private function updateMetaData($id, $data) {
	}
	
	private function insertMetaData() {
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function insert_file_info($data) {
		
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
				programmer,
				musican,
				graphics,
				media_type,
				media_current,
				media_count,
				storage,
				region,
				cdate
			)
			VALUES
			(
				'".sqlite_escape_string($data['eccident'])."',
				'".sqlite_escape_string($data['name'])."',
				'".sqlite_escape_string($data['crc32'])."',
				'".sqlite_escape_string($data['extension'])."',
				'".sqlite_escape_string($data['info'])."',
				'".sqlite_escape_string($data['info_id'])."',
				".$data['running'].",
				".$data['bugs'].",
				".$data['trainer'].",
				".$data['intro'].",
				".$data['usermod'].",
				".$data['freeware'].",
				".$data['multiplayer'].",
				".$data['netplay'].",
				'".sqlite_escape_string($data['year'])."',
				'".sqlite_escape_string($data['usk'])."',
				".$data['category'].",
				'".sqlite_escape_string($data['creator'])."',
				'".sqlite_escape_string($data['publisher'])."',
				'".sqlite_escape_string($data['programmer'])."',
				'".sqlite_escape_string($data['musican'])."',
				'".sqlite_escape_string($data['graphics'])."',
				".$data['media_type'].",
				".$data['media_current'].",
				".$data['media_count'].",
				".$data['storage'].",
				".$data['region'].",
				".time()."
			)
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function save_language($data) {
		$q = "DELETE FROM mdata_language WHERE mdata_id=".$data['id'];
		$hdl = $this->dbms->query($q);
		foreach ($data['languages'] as $lang_ident => $void) {
			$q = "INSERT INTO mdata_language ( mdata_id, lang_id) VALUES ('".$data['id']."', '".sqlite_escape_string($lang_ident)."')";
			$hdl = $this->dbms->query($q);
		}
		return true;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_language_status($mdat_id, $lang_ident) {
		$ret = false;
		$q = "SELECT mdata_id FROM mdata_language WHERE mdata_id=".$mdat_id." AND lang_id='".sqlite_escape_string($lang_ident)."'";
		$hdl = $this->dbms->query($q);
		$ret = $hdl->fetchSingle();
		return ($ret) ? true : false;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_language_by_mdata_id($mdat_id) {
		$ret = array();;
		if (!$mdat_id) return $ret;
		$q = "SELECT lang_id FROM mdata_language WHERE mdata_id=".$mdat_id." ORDER BY lang_id";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$ret = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$ret[$res['lang_id']] = true;
		}
		return $ret;
	}
	
	public function update_launch_time($id) {
		$q = 'UPDATE fdata SET launchtime = '.time().', launchcnt = launchcnt+1 WHERE id = '.(int)$id.'';
		#print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_media_count_for_eccident($eccident, $hideDup) {
		$ret = false;
		
		$snip_where = array();
		if ($eccident) $snip_where[] = "eccident='".sqlite_escape_string($eccident)."'";
		if ($hideDup) $snip_where[] = "duplicate is null";
		// BUILD WHERE SNIPPLET STRING
		$snip_where_sql = SqlHelper::createSqlWhere($snip_where);
		
		$q = "SELECT count(*) as cnt FROM fdata WHERE ".$snip_where_sql."";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$ret = $hdl->fetchSingle();
		
		return $ret;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	
	public function getNavPlatformCounts($extension, $hideDup, $language=false, $category=false, $search_ext=false, $hideMetaless=false, $like=false, $onlyFiles=false)
	{
		// CREATE WHERE-CLAUSE
		$snip_where = array();
		// initial entry
		$snip_where[] = "1";

		if ($like) $snip_where[] = $like;
		
		// show only data with metadata assigned
		if ($hideMetaless) $snip_where[] = "md.id IS NULL";
		// doublettes
		if ($hideDup) $snip_where[] = "fd.duplicate is null";
		// category
		if ($category) $snip_where[] = "md.category=".$category;
		// language
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		// esearch
		$esearch = SqlHelper::createSqlExtSearch($search_ext);
		if ($esearch) $snip_where[] = $esearch;
		
		// inner = count only roms with metadata
		$joinType = ($onlyFiles) ? 'inner' : 'left';
		
		$sql_join = ($language) ? "left join mdata_language AS mdl on md.id=mdl.mdata_id " : "";
		
		if($this->showOnlyPersonal){
			$personalJoin = " udata AS ud inner join fdata AS fd on (ud.eccident=fd.eccident and ud.crc32=fd.crc32) ";
			
			switch($this->personalMode){
				case 'review':
					$snip_where[] = "(ud.review_title != '' OR ud.review_body != '')";
					break;	
				case 'notes':
				case '':
					$snip_where[] = "(ud.notes != '' OR ud.hiscore != '')";
					break;	
			}
		}
		else{
			$personalJoin = 'fdata AS fd';
		}
		
		
		$sqlNamespace = 'fd';
		$snipSqlJoin = $personalJoin.' '.$joinType.' join mdata AS md on fd.crc32=md.crc32 and fd.eccident=md.eccident '.$sql_join;
		$snipSqlGroup = 'group by fd.eccident';

		$discCondition = $this->getShowOnlyDisk();
		if($discCondition){
			if($discCondition == 'one_plus'){
				$snip_where[] = '(md.media_current = 1 OR md.media_current IS NULL)';
			}
			else{
				# only disk 1
				$snip_where[] = 'md.media_current = 1';
			}
		}
		
		if ($this->showOnlyDontHave){
			$sqlNamespace = 'md';
			$snip_where[] = 'fd.id IS NULL';
			$snipSqlJoin = ' mdata AS md left join fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32) ';
			$snipSqlJoin .= ($language) ? ' left join mdata_language AS mdl on md.id=mdl.mdata_id ' : '';
			$snipSqlGroup = 'group by md.eccident';
		}
		elseif($this->showOnlyBookmarks){
			$snipSqlJoin = "fdata_bookmarks AS fdb INNER JOIN fdata fd ON (fd.id=fdb.file_id) left join mdata AS md on (fd.eccident=md.eccident and fd.crc32=md.crc32)";
		}

		if($this->showOnlyPlayed || $this->showOnlyMostPlayed){
			$snip_where[] = "fd.launchcnt > 0";
		}
		elseif($this->showOnlyNotPlayed){
			$snip_where[] = "fd.launchcnt = 0";
		}
		
		#if($this->showOnlyBookmarks) $snipSqlJoin .= " INNER JOIN fdata_bookmarks AS fdb ON (fd.id=fdb.file_id) ";
		
		#$eccIdents = '"'.implode('","', $extension).'"';
		#$snip_where[] = $sqlNamespace.'.eccident in ('.sqlite_escape_string($eccIdents).')';
		
		# create sql snipplets
		$snipSqlWhere = SqlHelper::createSqlWhere($snip_where);
		
		// GET COUNT
		$q = "
			SELECT
			".$sqlNamespace.".eccident as eccident, count(".$sqlNamespace.".id) as cnt
			FROM
			".$snipSqlJoin."
			WHERE
			".$snipSqlWhere."
			".$snipSqlGroup."
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$ret = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$ret[$res['eccident']] = $res['cnt'];
		}
		return $ret;
	}
		
	public function vacuum_database() {
		$q = "VACUUM";
		$hdl = $this->dbms->query($q);
	}
	
	public function update_fdata_by_path($path_source, $path_destination) {
		
		// ABS-PATH TO REL-PATH... 20061116 as
		$path_destination = FACTORY::get('manager/Os')->eccSetRelativeFile($path_destination);
		
		// set new filename
		$fileName = FileIO::get_plain_filename($path_destination);
		
		$q = '
			UPDATE
			fdata
			SET
			title = "'.sqlite_escape_string($fileName).'",
			path = "'.($path_destination).'"
			WHERE
			path = "'.$path_source.'"
		';
		//print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	/**
	 * change the path in the dataset to a new
	 * position. used in fileoperations like rename and copy
	 *
	 * @param int $fdataId
	 * @param string $path_destination
	 * @return bool
	 */
	public function updatePathById($fdataId, $path_destination) {
		
		// ABS-PATH TO REL-PATH...
		$path_destination = FACTORY::get('manager/Os')->eccSetRelativeFile($path_destination);
		
		// set new filename
		$fileName = FileIO::get_plain_filename($path_destination);
		
		$q = '
			UPDATE
			fdata
			SET
			title = "'.sqlite_escape_string($fileName).'",
			path = "'.$path_destination.'"
			WHERE
			id = '.(int)$fdataId.'
		';
		//print $q."\n";
		$hdl = $this->dbms->query($q);
		
		return true;
	}
	
	public function deleteFdataById($id) {
		if (!$id) return false;
		$q = '
			DELETE FROM
			fdata
			WHERE
			id = '.(int)$id.'
		';
		$hdl = $this->dbms->query($q);

		// remove bookmarks also
		$this->deleteBookmarkById($id);
		
		return true;
	}
	
	/**
	 * NEEDS A NEW TABLE!!!!!
	 */
	public function getRomPersonalData() {}
	
	public function hasBookmark($fileId) {
		$q = "SELECT * from fdata_bookmarks WHERE file_id = ".(int)$fileId."";
		$hdl = $this->dbms->query($q);
		return ($hdl->fetchSingle()) ? true : false;
	}
	
	public function getFdataById($id) {
		$q = "SELECT * FROM fdata WHERE id = ".(int)$id."";
		$hdl = $this->dbms->query($q);
		return $hdl->fetch(SQLITE_ASSOC);
	}
	
	public function getAutoCompleteData($field = false, $onlyHaving = true) {
		$ret = array();
		
		$validFields = array(
			'name',
			'publisher',
			'creator',
			'programmer',
			'musican',
			'graphics',
			'year'
		);
		
		if (!$field || !in_array($field, $validFields)) return $ret;
		$join = ($onlyHaving) ? "INNER JOIN fdata AS fd ON (fd.eccident=md.eccident AND fd.crc32=md.crc32)" : '';
		$q="SELECT md.id as id, md.".$field." as ".$field." FROM mdata AS md ".$join." GROUP BY ".$field." ORDER BY ".$field." ASC";
		#print $q.LF;
		$hdl = $this->dbms->query($q);
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			if ($res[$field] && $res[$field] !== 'NULL') $ret[$res['id']] = $res[$field];
		}
		return $ret;
		
	}

	public function getReparsePathsByEccident($eccident){
		
		$osManager = FACTORY::get('manager/Os');

		$ret = array();
		$eccUserPath = FACTORY::get('manager/IniFile')->getUserFolder($eccident, 'roms');
		
		$fixedUserPath = $osManager->eccSetRelativeDir(realpath($eccUserPath));
		$ret[$fixedUserPath] = $osManager->eccSetRelativeDir($fixedUserPath);
		
		$q = 'SELECT * FROM fdata_reparse WHERE eccident = "'.sqlite_escape_string($eccident).'"';
		$hdl = $this->dbms->query($q);
		
		
		$allreadyProcessed = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			# all fine, parse roms
			
			$fixedPath = $osManager->eccSetRelativeDir(realpath($res['path']));

			if (is_dir($fixedPath) && !isset($allreadyProcessed[$fixedPath])){
				$ret[$fixedPath] = $fixedPath;
				$allreadyProcessed[$fixedPath] = $res['path'];
			}
			else {
				# clean up database, if path is not available
				$q = 'DELETE FROM fdata_reparse WHERE path = "'.sqlite_escape_string($res['path']).'" OR path = "'.sqlite_escape_string(@$allreadyProcessed[$fixedPath]).'"';
				$this->dbms->query($q);
			}
			
		}
		return $ret;
	}
	
	public function getReparsePathsAll(){
		
		$osManager = FACTORY::get('manager/Os');
		
		$q = 'SELECT * FROM fdata_reparse';
		$hdl = $this->dbms->query($q);
		
		$allreadyProcessed = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			
			$fixedPath = $osManager->eccSetRelativeDir(realpath($res['path']));
			
			# all fine, parse roms
			if (is_dir($fixedPath) && !isset($allreadyProcessed[$fixedPath])){
				$ret[$res['eccident']][$fixedPath] = $fixedPath;
				$allreadyProcessed[$fixedPath] = $res['path'];
			}
			else {
				# clean up database, if path is not available
				$q = 'DELETE FROM fdata_reparse WHERE path = "'.sqlite_escape_string($res['path']).'" OR path = "'.sqlite_escape_string($allreadyProcessed[$fixedPath]).'"';
				$this->dbms->query($q);
			}
		}
		return $ret;
	}
	
	public function getMameDriver($searchActive = false, $reset = false){
		
		if (!$searchActive && !$reset) {
			
			$this->foundMameDriver = array();
			
			$q = "
			SELECT
			count(*) AS theCnt, fa.mameDriver
			FROM
			fdata AS fd left join fdata_audit AS fa ON fd.id=fa.fDataId
			WHERE
			fd.eccident='mame'
			AND fa.isMatch
			/*AND (fa.isValidSplitSet = 1 OR fa.isValidNonMergedSet = 1 OR fa.isValidMergedSet)*/
			GROUP BY fa.mameDriver
			";
			#print $q;
			$hdl = $this->dbms->query($q);
			while($res = $hdl->fetch(SQLITE_ASSOC)) {
				$this->foundMameDriver[trim($res['fa.mameDriver'])] = (int)$res['theCnt'];
			}
		}

		ksort($this->foundMameDriver);
		return $this->foundMameDriver;
	}
	public function setSearchMameDriver($mameDriver = false){
		$this->searchMameDriver = $mameDriver;
	}
	public function getSearchMameDriver(){
		return $this->searchMameDriver;
	}
	
	public function getAllCrc32ForSystem($system){
		$availableCrc32 = false;
		$q = "SELECT crc32 FROM fdata WHERE eccident = '".sqlite_escape_string($system)."'";
		$hdl = $this->dbms->query($q);
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$availableCrc32[] = $res['crc32'];
		}
		return $availableCrc32;
	}
	
	public function searchForFile($fileName){
		$q = "SELECT title, path, path_pack FROM fdata WHERE path_pack LIKE '%".sqlite_escape_string($fileName)."%' OR path LIKE '%".sqlite_escape_string($fileName)."%' LIMIT 1";
		//print $q."\n";
		$hdl = $this->dbms->query($q);
		if($row = $hdl->fetch(SQLITE_ASSOC)){
//			return $row['title'];
			return $row;
		}
		return false;
	}
	
}

?>
