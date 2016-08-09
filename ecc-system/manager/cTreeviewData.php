<?php

class TreeviewData {
	
	private $dbms = false;
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function __construct() {}
	
	// called by FACTORY
	public function setDbms($dbmsObject) {
		$this->dbms =  $dbmsObject;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	private function get_sql_limit($limit=array())
	{
		if ( count($limit) == 2 && isset($limit[0]) && isset($limit[1])) {
			return 'LIMIT '.(int)$limit[0].', '.(int)$limit[1].'';
		}
		return "";
	}
	
	
	public function get_search_ext_snipplet($search_ext=false) {
		
		if (!$search_ext || !is_array($search_ext)) return "";
		
		$out = array();
		
		foreach ($search_ext as $key => $int) {
			
			$field = str_replace("scb_", '', $key);
			
			switch ($int) {
				case '0': // *
					// nothing
					break;
				case '1': // no
					$out[] = "$field <= 0";
					break;
				case '2': // no?
					$out[] = "($field <= 0 OR $field is NULL)";
					break;
				case '3': // yes
					$out[] = "$field > 0";
					break;
				case '4': //yes?
					$out[] = "($field > 0 OR $field is NULL)";
					break;
				case '5': // ?
					$out[] = "$field is NULL";
					break;
			}
			
		}
		
		$out_strg = implode(" AND ", $out);
		#if ($out_strg) $out_strg = " AND ".$out_strg;
		return $out_strg;
	}
	
	/* ------------------------------------------------------------------------
	* VERSION TO GET ALSO META-DATA, IF THERE IS NO FOUND GAME
	* -------------------------------------------------------------------------
	*/
	public function get_file_data_TEST_META(
		$extension,
		$search_like=false,
		$limit=array(),
		$return_count=true,
		$order_by="",
		$language=false,
		$category=false,
		$search_ext=false,
		$show_files_only=true,
		$toggle_show_doublettes=false,
		$toggle_show_metaless_roms_only=false,
		$searchRating = false
	)
	{
		$ret = array();
		$ret['data'] = array();
		$ret['count'] = array();
		
		$snip_where = array();
		$sqlOrderBy = array();
		
		// freeform search like
		if ($search_like) $snip_where[] = $search_like;
		// Show/hide doublettes
		if ($toggle_show_doublettes) $snip_where[] = "fd.duplicate is null";
		// show/hide missing roms
		if (!$show_files_only) {
			if ($extension) $snip_where[] = "fd.eccident='".sqlite_escape_string($extension)."'";
		}
		else {
			if ($extension) $snip_where[] = "md.eccident='".sqlite_escape_string($extension)."'";
		}
		
		if ($searchRating) {
			$snip_where[] = "md.rating<=".(int)$searchRating."";
			$rateOrder = ($order_by == 'DESC') ? 'ASC' : 'DESC';
			$sqlOrderBy[] = "md.rating ".$rateOrder."";
		}
		
		// category selection from dropdown
//		if ($category !== false && $category != "-1") $snip_where[] = "md.category=".$category."";
		if ($category) $snip_where[] = "md.category=".$category."";
		
		// eSearch selection
		if ($esearch = $this->get_search_ext_snipplet($search_ext)) $snip_where[] = $esearch;
		
		// languages selection from dropdown
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		
		// show only data with metadata assigned
		if ($toggle_show_metaless_roms_only) $snip_where[] = "md.id IS NULL";
		
		$snip_where_sql = implode(" AND ", $snip_where);
		if (!$snip_where_sql) $snip_where_sql = " 1 ";
		
		$snip_join = array();
		if (!$show_files_only) {
			$snip_join[] = "fdata AS fd left join mdata AS md on (fd.eccident=md.eccident and fd.crc32=md.crc32)";
		} else {
			$snip_join[] = "mdata AS md left join fdata AS fd on (md.eccident=fd.eccident and md.crc32=fd.crc32)";
		}
		if ($language) $snip_join[] = "left join mdata_language AS mdl on md.id=mdl.mdata_id";
		$snip_join_sql = implode(" ", $snip_join);

		$sqlOrderBy[] = "coalesce(md.name, 'ZZZ') ".$order_by;
		$sqlOrderBy[] = "fd.title ".$order_by;
		
		$snipSqlOrderBy = "ORDER BY ".implode(", ", $sqlOrderBy);
		
		$q = "
			SELECT
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
			md.cdate as md_cdate,
			md.uexport as md_uexport,
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
			fd.mdata as fd_mdata
			FROM
			".$snip_join_sql."
			WHERE
			".$snip_where_sql."
			".$snipSqlOrderBy."
			". $this->get_sql_limit($limit)."
		";
		
		//print $q."\n";
		$hdl = $this->dbms->query($q);
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$ret['data'][$res['id']."|".$res['md_id']] = $res;
			$ret['data'][$res['id']."|".$res['md_id']]['composite_id'] = $res['id']."|".$res['md_id'];
		}
		#print_r($ret);
		
		if ($return_count===true) {
			// GET COUNT
			$q = "
				SELECT
				count(*)
				FROM
				".$snip_join_sql."
				WHERE
				".$snip_where_sql."
			";
			#print $q."\n";
			$hdl = $this->dbms->query($q);
			$ret['count'] = $hdl->fetchSingle();
		}
		return $ret;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_bookmarks(
		$extension,
		$search_like=false,
		$limit=array(),
		$return_count=true,
		$order_by="",
		$language=false,
		$category=false,
		$search_ext=false,
		$show_files_only=true,
		$toggle_show_doublettes=false,
		$toggle_show_metaless_roms_only=false,
		$searchRating = false
	)
	{
		$ret = array();
		$ret['data'] = array();
		$ret['count'] = array();
		
		$snip_where = array();
		$sqlOrderBy = array();
		
		// languages selection from dropdown
		$snip_where[] = "fd.duplicate IS NULL";
		// search like set?
		if ($search_like) $snip_where[] = $search_like;
		// show only data with metadata assigned
		// show/hide missing roms
		if (!$show_files_only) {
			if ($extension) $snip_where[] = "fd.eccident='".sqlite_escape_string($extension)."'";
		}
		else {
			if ($extension) $snip_where[] = "md.eccident='".sqlite_escape_string($extension)."'";
		}
		
		if ($searchRating) {
			$snip_where[] = "md.rating<=".(int)$searchRating."";
			$rateOrder = ($order_by == 'DESC') ? 'ASC' : 'DESC';
			$sqlOrderBy[] = "md.rating ".$rateOrder."";
		}
		
		if ($toggle_show_metaless_roms_only) $snip_where[] = "md.id IS NULL";
		// esearch
		$esearch = $this->get_search_ext_snipplet($search_ext);
		if ($esearch) $snip_where[] = $esearch;
		// category
//		if ($category !== false && $category != "-1") $snip_where[] = "md.category=".$category;
		if ($category) $snip_where[] = "md.category=".$category;
		// language
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		$snip_where_sql = implode(" AND ", $snip_where);
		if (!$snip_where_sql) $snip_where_sql = " 1 ";
		
		// language
		$snipplet_language_join = ($language) ? " left join mdata_language AS mdl on md.id=mdl.mdata_id " : "";
		
		$sqlOrderBy[] = "coalesce(md.name, 'ZZZ') ".$order_by;
		$sqlOrderBy[] = "fd.title ".$order_by;
		
		$snipSqlOrderBy = "ORDER BY ".implode(", ", $sqlOrderBy);
		
		$q = "
			SELECT
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
			md.cdate as md_cdate,
			md.uexport as md_uexport,
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
			fd.mdata as fd_mdata
			FROM
			fdata_bookmarks as b
            left join fdata AS fd on b.file_id=fd.id
			left join mdata AS md on fd.crc32=md.crc32 and fd.eccident = md.eccident
			".$snipplet_language_join."
			WHERE
			".$snip_where_sql."
			".$snipSqlOrderBy."
			". $this->get_sql_limit($limit)."
		";
		
		#print "bookmark ###### ".$q."\n";
		$hdl = $this->dbms->query($q);
		$ret = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			#$ret['data'][$res['id']] = $res;
			$ret['data'][$res['id']."|".$res['md_id']] = $res;
			$ret['data'][$res['id']."|".$res['md_id']]['composite_id'] = $res['id']."|".$res['md_id'];
			#print $res['id']."|".$res['md_id']."\n";
		}
		
		if ($return_count===true) {
			// GET COUNT
			$q = "
				SELECT
				count(*)
				FROM
				fdata_bookmarks as b
				left join fdata AS fd on b.file_id=fd.id
				left join mdata AS md on fd.crc32=md.crc32
				".$snipplet_language_join."
				WHERE
				".$snip_where_sql."
			";
			#print $q."\n";
			$hdl = $this->dbms->query($q);
			$ret['count'] = $hdl->fetchSingle();
		}
		return $ret;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_last_launched(
		$extension,
		$search_like=false,
		$limit=array(),
		$return_count=true,
		$order_by="",
		$language=false,
		$category=false,
		$search_ext=false,
		$show_files_only=true,
		$toggle_show_doublettes=false,
		$toggle_show_metaless_roms_only=false,
		$searchRating = false
	)
	{
		
		$ret = array();
		$ret['data'] = array();
		$ret['count'] = array();

		// ORDER
		// order by must be reverse! :-(
		$order_by = ($order_by=='DESC') ? 'ASC' : 'DESC';
		
		// INIT WHERE SNIPPLET		
		$snip_where = array();
		$sqlOrderBy = array();
		
		// COMPILE WHERE SNIPPLET
		// languages selection from dropdown
		$snip_where[] = "fd.duplicate IS NULL";
		// search like set?
		if ($search_like) $snip_where[] = $search_like;
		// eccident
		if ($extension) $snip_where[] = "fd.eccident='".sqlite_escape_string($extension)."'";
		// category
//		if ($category !== false && $category != "-1") $snip_where[] = "md.category=".$category;
		if ($category) $snip_where[] = "md.category=".$category;
		
		if ($searchRating) {
			$snip_where[] = "md.rating<=".(int)$searchRating."";
			$sqlOrderBy[] = "md.rating ".$order_by."";
		}
		
		// language
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		// show only data with metadata assigned
		if ($toggle_show_metaless_roms_only) $snip_where[] = "md.id IS NULL";
		// esearch
		$esearch = $this->get_search_ext_snipplet($search_ext);
		if ($esearch) $snip_where[] = $esearch;
		// BUILD WHERE SNIPPLET STRING
		$snip_where_sql = implode(" AND ", $snip_where);
		if (!$snip_where_sql) $snip_where_sql = " 1 ";
		
		// JOINS
		// language
		$snipplet_language_join = ($language) ? " left join mdata_language AS mdl on md.id=mdl.mdata_id " : "";
		
		$sqlOrderBy[] = "launchtime ".$order_by;
		$snipSqlOrderBy = "ORDER BY ".implode(", ", $sqlOrderBy);
		

		
		$q = "
			SELECT
			md.id as md_id,
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
			md.cdate as md_cdate,
			md.uexport as md_uexport,
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
			fd.mdata as fd_mdata
			FROM
			fdata AS fd
			left join mdata AS md on (fd.eccident=md.eccident AND fd.crc32=md.crc32)
			".$snipplet_language_join."
			WHERE
			".$snip_where_sql." AND
			launchtime != ''
			".$snipSqlOrderBy."
			". $this->get_sql_limit($limit)."
		";

		$hdl = $this->dbms->query($q);
		$ret = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$ret['data'][$res['id']."|".$res['md_id']] = $res;
			$ret['data'][$res['id']."|".$res['md_id']]['composite_id'] = $res['id']."|".$res['md_id'];
		}
		
		if ($return_count===true) {
			// GET COUNT
			$q = "
				SELECT
				count(*)
				FROM
				fdata AS fd
				left join mdata AS md on fd.crc32=md.crc32 and fd.eccident=md.eccident
				".$snipplet_language_join."
				WHERE
				".$snip_where_sql." AND
				launchtime != ''
			";
			#print $q."\n";
			$hdl = $this->dbms->query($q);
			$ret['count'] = $hdl->fetchSingle();
		}
		return $ret;
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
		$q = "UPDATE mdata set rating = ".(int)$rating.", uexport = NULL WHERE id = ".(int)$mdataId."";
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
		$q = "UPDATE mdata SET rating = NULL, uexport = NULL ".$sqlWhere."";
		$this->dbms->query($q);
		return true;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function add_bookmark_by_id($id)
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
	public function remove_bookmark_by_id($id) {
		if ($id) {
			$q = '
				DELETE FROM
				fdata_bookmarks
				WHERE
				file_id = '.(int)$id.'
			';
			#print $q."\n";
			$hdl = $this->dbms->query($q);
		}
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function remove_bookmark_all() {
		$q = '
			DELETE FROM
			fdata_bookmarks
		';
		$hdl = $this->dbms->query($q);
	}

	public function get_duplicates_all($eccident) {
		$snip_where = array();
		if ($eccident) $snip_where[] = "eccident='".sqlite_escape_string($eccident)."'";
		$snip_where[] = "duplicate=1";
		$sql_snip = implode(" AND ", $snip_where);
		
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
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$out[$res['eccident']] = $res['cnt'];
		}
		
		$q = "DELETE FROM	fdata WHERE ".$sql_snip."";
		$hdl = $this->dbms->query($q);
		
		return $out;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function remove_media_from_fdata($id, $eccident, $crc32) {
		if (!$id) return false;
		
		$q = '
			DELETE FROM
			fdata
			WHERE
			id = '.(int)$id.'
		';
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		
		$duplicates = $this->get_duplicates($eccident, $crc32);
		if (!count($duplicates)) return true;
		
		if (!in_array('', $duplicates)) {
			$this->update_duplicate(key($duplicates));
		}
		
		// remove bookmarks also
		$this->remove_bookmark_by_id($id);
		
		return true;
	}
	
	public function get_duplicates($eccident, $crc32) {
		$q = "
			SELECT
			*
			FROM
			fdata
			WHERE
			eccident='".sqlite_escape_string($eccident)."' AND
			crc32='".sqlite_escape_string($crc32)."'
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$out = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$out[$res['id']] = $res['duplicate'];
		}
		return $out;
	}
	
	public function update_duplicate($id) {
		$q = "
			UPDATE
			fdata
			SET
			duplicate = NULL
			WHERE
			id = ".$id."
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	public function remove_media_duplicates($eccident, $crc32) {
		$q = "
			DELETE FROM
			fdata
			WHERE
			eccident='".sqlite_escape_string($eccident)."' AND
			crc32='".sqlite_escape_string($crc32)."'
		";
		#print $q."\n";
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
			cdate = ".time().",
			uexport = NULL
			WHERE
			id = ".$data['id']."
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	public function saveMetaData($inputData) {
		
		$data = array();
		$data['running'] = ($inputData['md_running']);
		$data['bugs'] = ($inputData['md_bugs']);
		$data['trainer'] = ($inputData['md_trainer']);
		$data['intro'] = ($inputData['md_intro']);
		$data['usermod'] = ($inputData['md_usermod']);
		$data['multiplayer'] = ($inputData['md_multiplayer']);
		$data['netplay'] = ($inputData['md_netplay']);
		$data['freeware'] = ($inputData['md_freeware']);
		$data['category'] = $inputData['md_category'];
		$data['cdate'] = time();
		
		foreach ($data as $key => $value) {
			if (!isset($data[$key])) $data[$key] = 'NULL';
		}

		$data['id'] = $inputData['md_id'];
		$data['eccident'] = strtolower($inputData['fd_eccident']);
		$data['crc32'] = $inputData['crc32'];
		$path = ($inputData['path_pack']) ? $inputData['path_pack'] : $inputData['path'];
		$data['extension'] = strtolower(".".FACTORY::get('manager/FileIO')->get_ext_form_file($path));
		$data['name'] = (trim($inputData['md_name'])) ? $inputData['md_name'] : FACTORY::get('manager/FileIO')->get_plain_filename($path);
		
		$data['info'] = $inputData['md_info'];
		$data['usk'] = $inputData['md_usk'];
		$data['info_id'] = $inputData['md_info_id'];
		$data['year'] = $inputData['md_year'];
		$data['creator'] = $inputData['md_creator'];
		
		
		if ($inputData['md_id']) {
			$this->update_file_info($data, false);
		}
		else {
			return $this->insert_file_info($data);
		}
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
	public function get_media_count_for_eccident($eccident, $toggle_show_doublettes) {
		$ret = false;
		
		$snip_where = array();
		if ($eccident) $snip_where[] = "eccident='".sqlite_escape_string($eccident)."'";
		if ($toggle_show_doublettes) $snip_where[] = "duplicate is null";
		// BUILD WHERE SNIPPLET STRING
		$snip_where_sql = implode(" AND ", $snip_where);
		if (!$snip_where_sql) $snip_where_sql = " 1 ";
		
		$q = "SELECT count(*) as cnt FROM fdata WHERE ".$snip_where_sql."";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$ret = $hdl->fetchSingle();
		
		return $ret;
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	
	public function getNavPlatformCounts($extension, $toggle_show_doublettes, $language=false, $category=false, $search_ext=false, $toggle_show_metaless_roms_only=false, $sqlLike=false)
	{
		// CREATE WHERE-CLAUSE
		$snip_where = array();
		// initial entry
		$snip_where[] = "1";

		//$search_like = $this->createSearchSqlLike();
		if ($sqlLike) $snip_where[] = $sqlLike;
		
		
		$eccIdents = '"'.implode('","', $extension).'"';
		$snip_where[] = "fd.eccident in (".sqlite_escape_string($eccIdents).")";
		
		// show only data with metadata assigned
		if ($toggle_show_metaless_roms_only) $snip_where[] = "md.id IS NULL";
		// doublettes
		if ($toggle_show_doublettes) $snip_where[] = "fd.duplicate is null";
		// category
		if ($category) $snip_where[] = "md.category=".$category;
		// language
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		// esearch
		$esearch = $this->get_search_ext_snipplet($search_ext);
		if ($esearch) $snip_where[] = $esearch;
		// concat strings to snipplet
		$sql_where = implode(" AND ", $snip_where);
		
		// CREATE JOIN, IF NEEDED
		$sql_join = ($language) ? "left join mdata_language AS mdl on md.id=mdl.mdata_id " : "";
		
		// GET COUNT
		$q = "
			SELECT
			fd.eccident as eccident, count(fd.id) as cnt
			FROM
			fdata AS fd
			left join mdata AS md on fd.crc32=md.crc32 and fd.eccident=md.eccident
			".$sql_join."
			WHERE
			".$sql_where."
			group by fd.eccident
		";
		//print $q."\n";
		$hdl = $this->dbms->query($q);
		$ret = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$ret[$res['eccident']] = $res['cnt'];
		}
		return $ret;
	}
		
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_media_count_for_eccident_search($extension, $toggle_show_doublettes, $language=false, $category=false, $search_ext=false, $toggle_show_metaless_roms_only=false, $sqlLike=false)
	{
		// CREATE WHERE-CLAUSE
		$snip_where = array();
		// initial entry
		$snip_where[] = "1";

		//$search_like = $this->createSearchSqlLike();
		if ($sqlLike) $snip_where[] = $sqlLike;
		
		// eccident
		if ($extension) $snip_where[] = "fd.eccident='".sqlite_escape_string($extension)."'";
	
		
		// show only data with metadata assigned
		if ($toggle_show_metaless_roms_only) $snip_where[] = "md.id IS NULL";
		// doublettes
		if ($toggle_show_doublettes) $snip_where[] = "fd.duplicate is null";
		// category
//		if ($category !== false && $category != "-1") $snip_where[] = "md.category=".$category;
		if ($category) $snip_where[] = "md.category=".$category;
		// language
		if ($language) $snip_where[] = "mdl.lang_id='".$language."'";
		// esearch
		$esearch = $this->get_search_ext_snipplet($search_ext);
		if ($esearch) $snip_where[] = $esearch;
		// concat strings to snipplet
		$sql_where = implode(" AND ", $snip_where);
		
		// CREATE JOIN, IF NEEDED
		$sql_join = ($language) ? "left join mdata_language AS mdl on md.id=mdl.mdata_id " : "";
		
		// GET COUNT
		$q = "
			SELECT
			count(fd.id)
			FROM
			fdata AS fd
			left join mdata AS md on fd.crc32=md.crc32 and fd.eccident=md.eccident
			".$sql_join."
			WHERE
			".$sql_where."
		";
		//print $q."\n";
		$hdl = $this->dbms->query($q);
		return $hdl->fetchSingle();
	}
	
	
	public function find_duplicate_by_id($id) {
		if (!$id) return false;
		$q="
			select
			md.id as md_id,
			md.name as md_name,
			md.crc32 as md_crc32
			from
			mdata_duplicate AS mdd left join mdata AS md on mdd.mdata_id_duplicate=md.id
			where
			mdd.mdata_id in (select mdata_id from mdata_duplicate where mdata_id_duplicate=".(int)$id.")
			group by mdd.mdata_id_duplicate
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$ret = array();
		while($res = $hdl->fetch(SQLITE_ASSOC)) {
			$ret[] = $res;
		}
		if (false && count($ret)) {
			#print "<pre>";
			#print_r($ret);
			#print "</pre>\n";
		}
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function vacuum_database() {
		$q = "VACUUM";
		$hdl = $this->dbms->query($q);
	}
	
	public function update_fdata_by_path($path_source, $path_destination) {
		
//		$path_destination = realpath($path_destination);
//		if (strpos($path_destination, ECC_BASEDIR) == 0) {
//			$path_destination = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $path_destination);
//			$path_destination = str_replace("\\", "/", $path_destination);
//		};
		
		// ABS-PATH TO REL-PATH...
		// 20061116 as
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
	
	public function updatePathById($fdataId, $path_destination) {
		
//		$path_destination = realpath($path_destination);
//		if (strpos($path_destination, ECC_BASEDIR) == 0) {
//			$path_destination = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $path_destination);
//			$path_destination = str_replace("\\", "/", $path_destination);
//		};
		
		// ABS-PATH TO REL-PATH...
		// 20061116 as
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
		$this->remove_bookmark_by_id($id);
		
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
	

}

?>
