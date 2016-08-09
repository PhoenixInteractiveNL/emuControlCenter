<?
/*
* @author: ascheibel
*/
class PlattformMaintenance {
	
	private $dbms = false;
	private $_ident = false;
	private $_export_user_only = true;
	
	private $status_obj = false;
	
	/*
	* @author: ascheibel
	*/
	public function __construct($status_obj=false)
	{
		$this->status_obj = $status_obj;
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
	public function db_optimize()
	{
		#$this->optimize_db_eccident();
		$this->optimize_table_files();
		$this->optimize_table_bookmarks();
		#$this->vacuum_database();
		return true;
	}
	
	/*
	* @author: ascheibel
	*/
	public function db_clear()
	{
		$where_snip = ($this->_ident) ? "WHERE eccident ='".sqlite_escape_string(strtolower($this->_ident))."'" : '';
		
		$q = "
			DELETE
			FROM
			fdata
			".$where_snip."
		";
		$this->dbms->query($q);
		
		// danach alte bookmarks löschen
		$this->optimize_table_bookmarks();
		return "database now cleared";
	}
	
	/*
	* @author: ascheibel
	*/
	public function db_clear_dat()
	{
		$where_snip = ($this->_ident) ? "WHERE eccident ='".sqlite_escape_string(strtolower($this->_ident))."'" : '';
		$q = "
			DELETE
			FROM
			mdata
			".$where_snip."
		";
		$this->dbms->query($q);
		$this->optimize_table_mdata_languages();
		#$this->vacuum_database();
		return true;
	}
	
	/*
	* löscht nicht mehr vorhandene files
	*/
	public function optimize_table_files() {
		
		if ($this->status_obj) $this->status_obj->update_progressbar(0, "gathering data");
		if ($this->status_obj) $this->status_obj->update_message("search for files to optimize!");
		
		$where_snip = ($this->_ident) ? "WHERE eccident ='".sqlite_escape_string(strtolower($this->_ident))."'" : '';
		
		// get count total
		$q = "
			SELECT
			count(*) as cnt
			FROM
			fdata
			".$where_snip."
		";
		$hdl = $this->dbms->query($q);
		
		$cnt_total = $hdl->fetchSingle();
		$cnt_current = 0;
		$count_removed = 0;
		
		// get all files for snipplet
		$q = "
			SELECT
			*
			FROM
			fdata
			".$where_snip."
		";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		
		while($res = $hdl->fetch(1)) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			if (!file_exists($res['path'])) {
				$q_del = "
					DELETE
					FROM
					fdata
					WHERE
					id=".(int)$res['id']."
				";
				$this->dbms->query($q_del);
				$count_removed++;
			}
			
			######
			$cnt_current++;
			
			if ($this->status_obj) {
				$percent_string = sprintf("%02d", $cnt_current*100/$cnt_total);
				$msg = "validate files: ".$percent_string."%";
				$percent = (float)$cnt_current/$cnt_total;
				$this->status_obj->update_progressbar($percent, $msg);
				$message  = "check files\n";
				$message  = "checked $cnt_current of $cnt_total - removed from ecc-database: $count_removed files \n";
				$this->status_obj->update_message($message);
				if ($this->status_obj->is_canceled()) return false;
			}
			######
		}
		
		// danach alte bookmarks löschen
		$this->optimize_table_bookmarks();
		return true;
	}
	
	/*
	* löscht einträge auser der bookmark
	* tabelle, die nicht mehr als file in ecc erfasst
	* sind.
	*/
	public function optimize_table_bookmarks() {
		$q = "
			SELECT
			*
			FROM
			fdata_bookmarks AS b
			left join fdata AS fd on b.file_id=fd.id
		";
		$hdl = $this->dbms->query($q);
		
		$cnt_current = 0;
		$cnt_total = 100;
		
		while($res = $hdl->fetch(1)) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			if (!$res['fd.id']) {
				$q_del = "
					DELETE
					FROM
					fdata_bookmarks
					WHERE
					id=".(int)$res['b.id']."
				";
				$this->dbms->query($q_del);
			}
			
			$cnt_current++;
			
			if ($this->status_obj) {
				$msg = "optimize bookmarks";
				$percent = (float)$cnt_current/$cnt_total;
				$this->status_obj->update_progressbar($percent, $msg);
				$message  = "Optimizing bookmarks\n";
				$this->status_obj->update_message($message);
				if ($this->status_obj->is_canceled()) return false;
			}
			
			if ($cnt_current>=100) $cnt_current = 0;
		}
		$this->status_obj->update_progressbar(1, "optimize DONE");
		$this->status_obj->update_message("Optimizing bookmarks - done!");
		return true;
	}
	
	public function optimize_table_mdata_languages() {
		
		if (!$this->_ident) {
				// If there is no eccident assigned, all
				// data is removed from database... also lang
				// could be removed complete.
				$q_del = "DELETE FROM mdata_language";
				#print $q_del."\n";
				$this->dbms->query($q_del);
		}
		else {
			$q = "
				SELECT
				ml.mdata_id, m.id
				FROM
				mdata_language AS ml
				left join mdata AS m on ml.mdata_id=m.id
				WHERE
				m.id is null
				group by ml.mdata_id
			";
			#print $q;
			$hdl = $this->dbms->query($q);
			
			$cnt_current = 0;
			$del_cnt_current = 0;
			$del_cnt_max = 100;
			
			$del_ids = array();
			while($res = $hdl->fetch(1)) {
				
				while (gtk::events_pending()) gtk::main_iteration();
				
				$del_ids[$del_cnt_current][] = (int)$res['ml.mdata_id'];
				
				$cnt_current++;
				
				if ($cnt_current == $del_cnt_max) $del_cnt_current++;
				if ($cnt_current>$del_cnt_max) $cnt_current = 0;
			}
			
			if ($del_ids_count = count($del_ids)) {
				foreach($del_ids as $position => $ids) {
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$del_id_snipp = implode(", ", $ids);
					#print $del_id_snipp."\n\n";
					$q_del = "DELETE FROM mdata_language WHERE mdata_id in (".$del_id_snipp.")";
					#print $q_del."\n";
					$this->dbms->query($q_del);
					
					if ($this->status_obj) {
						$msg = "optimize languages ";
						$percent = (float)$position/$del_ids_count;
						$this->status_obj->update_progressbar($percent, $msg);
						$message  = "Optimizing languages - This could take a long time :-( .... please wait!\n";
						$this->status_obj->update_message($message);
						if ($this->status_obj->is_canceled()) return false;
					}
				}
			}
			$this->status_obj->update_progressbar(1, "optimize DONE");
			$this->status_obj->update_message("Database is cleared and optimized!");
		}
		return true;
	}
	
	
	/*
	* DEVEL-METHODE
	* stellt alle großgeschriebenen file-extensions auf
	* kleinschreibung um
	*/
	public function optimize_db_eccident() {
		$q = "
			SELECT
			*
			FROM
			mdata
		";
		#print $q;
		
		$hdl = $this->dbms->query($q);
		$out = array();
		while($res = $hdl->fetch(1)) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$q_upd = "update mdata set eccident='".strtolower($res['eccident'])."' where id=".$res['id'];
			#print $q_upd."\n";
			$this->dbms->query($q_upd);
		}
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function vacuum_database() {
		#$q = "VACUUM";
		#$hdl = $this->dbms->query($q);
	}
	
}
?>
