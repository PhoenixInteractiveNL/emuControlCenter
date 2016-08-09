<?
class FileOrganizer extends App{
	
	private $dbms = false;
	private $eccident = false;
	
	private $skip_unknown_category = true;
	private $categories = false;
	private $destination_path = false;
	
	private $statistics = false;
	
	private $oTreeviewDB;
	
	public $message = "";
	
	private $progress_count = 1;
	
	public function __construct($eccident=false, $ini, $status_obj) {
		#$this->db = $db;
		$this->eccident = $eccident;
		$this->ini = $ini;
		$this->set_destination_path();
		$this->status_obj = $status_obj;
		
		$this->oTreeviewDB = FACTORY::get('manager/TreeviewData');
		
	}
	
	// called by FACTORY
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	public function process() {
		
		$this->message = "Process destination: \"".$this->get_destination_path()."\"\n\n";
		
		if (!$this->eccident || !$this->categories || !$this->destination_path) return false;
		
		$cat_cnt = $this->get_cat_counts();
		if (!count($cat_cnt)) return false;
		
		$state = $this->reorganize_files($cat_cnt);
	}
	
	public function get_preview_statistics() {
		
		if (!$this->eccident) return false;
		
		$this->set_reorganize_mode('PREVIEW');
		
		$this->process();
		
		return $this->statistics;
	}
	
	/**
	* setter for categories
	*
	* @author ascheibel
	*/
	public function set_categories($categories) {
		if (!$categories) return false;
		$this->categories = $categories;
	}
	
	/**
	* setter for skip_unknown_category
	*
	* @author ascheibel
	*/
	public function set_skip_unknown_category($skip_unknown_category=true) {
		$this->skip_unknown_category = $skip_unknown_category;
	}
	
	/**
	* setter for destination path of organized files
	* this is the absolute or relative path to the folder
	* were the organized files should be stored!
	*
	* @author ascheibel
	*/
	public function set_destination_path() {
		# 20070810 refactoring userfolder
		$destination_path = $this->ini->getUserFolder($this->eccident, DIRECTORY_SEPARATOR."roms".DIRECTORY_SEPARATOR."organized".DIRECTORY_SEPARATOR, true);
		if (!$destination_path || !is_dir($destination_path)) return false;
		$this->destination_path = $destination_path;
	}
	
	/**
	* getter for destination_path
	*
	* @author ascheibel
	*/
	public function get_destination_path() {
		return realpath($this->destination_path);
	}
	
	/**
	*
	*
	* @author ascheibel
	*/
	public function set_reorganize_mode($mode=false) {
		if (!$mode) return false;
		switch(strtoupper($mode)) {
			case 'COPY':
				$this->reorganize_mode = 'copy';
				break;
			case 'MOVE':
				$this->reorganize_mode = 'move'; 
				break;
			case 'PREVIEW':
				$this->reorganize_mode = 'preview'; 
				break;
			default:
				$this->reorganize_mode = 'preview'; 
				break;
		};
	}	
	
	/**
	* .......
	*
	* @author ascheibel
	* @return boolean
	*/
	private function reorganize_files($cat_cnt) {
		$out = array();
		foreach ($cat_cnt as $cat_id => $value) {
			$opt_cat_name = $this->cleanFileName($value['cat_name']);
			$files = $this->get_files_by_category($cat_id);
			if (count($files)) {
				$this->write_organized_files_by_category($opt_cat_name, $files['VALID']);
			}
		}
	}
	
	/**
	* .......
	*
	* @author ascheibel
	* @return boolean
	*/
	private function create_new_file_path($opt_cat_name, $file_name) {
		$path = $this->destination_path.DIRECTORY_SEPARATOR.$opt_cat_name.DIRECTORY_SEPARATOR.$file_name;
		return $path;
	}
	
	/**
	* .......
	*
	* @author ascheibel
	* @return boolean
	*/
	private function cleanFileName($category) {
		$optimized_cat = str_replace("  ", " ", $category);
		$optimized_cat = ereg_replace("[^0-9A-Za-z\-\ ]", "", $category);
		return $optimized_cat;
	}
	
	/**
	* .......
	*
	* @author ascheibel
	* @return boolean
	*/
	private function write_organized_files_by_category($opt_cat_name, $files) {
		
		$cnt_current = 0;
		$cnt_total = count($files);
		$this->message .= "Category: $opt_cat_name:\n";
		
		$catFileColisionTest = array();
		
		foreach($files as $id => $data) {

			while (gtk::events_pending()) gtk::main_iteration();
			
			$path_source = $data['path'];
			$path_destination =  $this->create_new_file_path($opt_cat_name, basename($data['newFileName']));
			
			if (file_exists($path_source)) {
				
				// create directories, if needed
				if (!is_dir(dirname($path_destination))) {
					if ($this->reorganize_mode != 'preview') {
						$this->createDirectoryRecursive(dirname($path_destination), 0777, true);
					}
				}
				if (!file_exists($path_destination) && !isset($catFileColisionTest[$path_destination])) {
					
					// copy, move or print out commands!
					if ($this->reorganize_mode == 'copy') {
						copy($path_source, $path_destination);
						
						// ABS-PATH TO REL-PATH...
						#print "1 copy ".$path_destination."\n";
						$this->oTreeviewDB->update_fdata_by_path($path_source, $path_destination);
					}
					elseif ($this->reorganize_mode == 'move') {
						rename($path_source, $path_destination);
						
						// ABS-PATH TO REL-PATH...
						$path_destination = realpath($path_destination);
						#print "rename ".$path_destination."\n";
						$this->oTreeviewDB->update_fdata_by_path($path_source, $path_destination);
					}
					$this->statistics['DONE'][$opt_cat_name][] = basename($path_destination);
					$catFileColisionTest[$path_destination] = true;
					$this->message .= "\t".basename($path_destination)."\n";
				}
				else {
					#$this->oTreeviewDB->update_fdata_by_path($path_source, $path_destination);
					$this->statistics['ISSET'][$opt_cat_name][] = basename($path_destination);
				}
			}
			else {
				$this->statistics['MISSING'][$opt_cat_name][] = basename($path_source);
			}
			$cnt_current++;
			
			// ---------------------------------
			// STATUS BAR PROGRESS
			// ---------------------------------
			$percent_string = sprintf("%02d", ($cnt_current*100)/$cnt_total);
			$msg = "".$percent_string." % ($cnt_current/$cnt_total)";
			$percent = (float)$cnt_current/$cnt_total;
			$this->status_obj->update_progressbar($percent, $msg);
			// STATUS BAR MESSAGE
			// ---------------------------------
			$this->status_obj->update_message($this->message);
			// STATUS BAR OBSERVER CANCEL
			// ---------------------------------
			if ($this->status_obj->is_canceled()) return false;
			// ---------------------------------
			
		}
		$this->message .= "DONE!\n\n";
	}
	
	/**
	* Gathering infos (crc32 and path) for all roms by category
	* Stores data to valid/invalid-array.
	* Invalid roms are roms stored with other roms in one packed file.
	*
	* @author ascheibel
	* @todo Maybe ecc will get some kind of unpack and repack-mechanism
	* @return boolean
	*/
	private function get_files_by_category($category_id) {

		$snipp = array();
		$snipp[] = ($category_id) ? "m.category = ".$category_id."" : "m.category is null";
		$snipp[] = ($this->eccident) ? "fd.eccident = '".$this->eccident."'" : "1";
		$sql_snipp = implode(" AND ", $snipp);
		
		$q = "
			select
			*
			from
			fdata as fd
			left join mdata as m on fd.eccident=m.eccident and fd.crc32=m.crc32
			where
			".$sql_snipp."
			GROUP BY
			fd.eccident,
			fd.crc32
		";
		#print $q;
		$hdl = $this->dbms->query($q);
		
		$out = array();
		$out['VALID'] = array();
		$out['INVALID'] = array();
		
		while ($res = $hdl->fetch(1)) {
			if ($this->is_single_file($res['fd.path'])) {
				$out['VALID'][$res['fd.id']]['crc32'] = $res['fd.crc32'];
				$out['VALID'][$res['fd.id']]['path'] = $res['fd.path'];
				
				$fileExtension = strtolower(array_pop(explode('.', $res['fd.path'])));
				if (trim($res['m.name'])) {
					$fileName = $this->cleanFileName(trim($res['m.name'])).".".$fileExtension;
				}
				else {
					$fileName = $this->cleanFileName(basename($res['fd.path'])).".".$fileExtension;
				}
				$out['VALID'][$res['fd.id']]['newFileName'] = $fileName;
			}
			else {
				$out['INVALID'][$res['fd.id']]['crc32'] = $res['fd.crc32'];
				$out['INVALID'][$res['fd.id']]['path'] = $res['fd.path'];
			}
			
			// ---------------------------------
			// STATUS BAR PROGRESS
			// ---------------------------------
			if ($this->progress_count < 1000) {
				$this->progress_count++;
			}
			else {
				$this->progress_count = 1;
			}
			$progressbar_string = sprintf(I18N::get('status', 'reorg_prepare_data%s'), $this->progress_count);
			$this->status_obj->update_progressbar($this->progress_count/1000, $progressbar_string);
			// STATUS BAR OBSERVER CANCEL
			// ---------------------------------
			if ($this->status_obj->is_canceled()) return false;
			// ---------------------------------
			while (gtk::events_pending()) gtk::main_iteration();
		}

		return $out;
	}
	
	/**
	* Are there more than one roms in a packed file?
	* Auto-Fileorganize could not handle this....!
	*
	* @author ascheibel
	* @todo Maybe ecc will get some kind of unpack and repack-mechanism
	* @return boolean
	*/
	private function is_single_file($path) {
		$q = "select count(*) as cnt from fdata where path='".sqlite_escape_string($path)."' group by path limit 1";
		#print $q."\n";
		$hdl = $this->dbms->query($q);
		$res = $hdl->fetchSingle();
		if ($res > 1) return false; 
		return true;
	}
	
	/**
	* Get category counts for given eccident!
	* These counts will be used for creating a file-system
	*
	* @author ascheibel
	* @return boolean
	*/
	private function get_cat_counts() {
		
		$snipp = array();
		$snipp[] = "1";
		if ($this->eccident) $snipp[] = "fd.eccident = '".$this->eccident."'";
		if ($this->skip_unknown_category) $snipp[] = "m.category is not null";
		$sql_snipp = implode(" AND ", $snipp);
		
		$out = array();
		$q = "
			select
			count(*) as cnt, m.category as cat_id, sum(fd.size) as size
			from
			fdata as fd
			left join mdata as m on fd.eccident=m.eccident and fd.crc32=m.crc32
			where
			".$sql_snipp."
			group by m.category
			order by
			cnt desc
		";
		#print $q;
		$hdl = $this->dbms->query($q);
		while ($res = $hdl->fetch(1)) {
			$out[$res['cat_id']] = $res;
			if ($res['cat_id'] != '') {
				//$idx = (int) $res['cat_id'] +1;
				$idx = (int) $res['cat_id'];
				$out[$res['cat_id']]['cat_name'] = $this->categories[$idx];
			}
			else {
				$out[$res['cat_id']]['cat_name'] = 'Unknown';
			}
		}
		return $out;
	}
	
	public function categories_exists() {
		
		// reorga by cat only possible for selected platforms
		if (!$this->eccident) return false;
		
		$snipp = array();
		$snipp[] = "1";
		if ($this->eccident) $snipp[] = "fd.eccident = '".$this->eccident."'";
		$sql_snipp = implode(" AND ", $snipp);
		
		$out = array();
		$q = "
			select
			count(*) as cnt,
			m.category as cat_id,
			sum(fd.size) as size
			from
			fdata as fd, mdata as m
			where
			fd.eccident=m.eccident and fd.crc32=m.crc32 AND 
			".$sql_snipp."
			group by m.category
			limit 1
		";
		#print $q;
		$hdl = $this->dbms->query($q);
		if  ($res = $hdl->fetch(1)) {
			return true;
		}
		return false;
	}
	

	
	private function createDirectoryRecursive($strPath, $mode = 0777) {
		return is_dir($strPath) or ($this->createDirectoryRecursive(dirname($strPath), $mode) and mkdir($strPath) );
	}
}
?>
