<?
class FileOrganizer extends App{
	
	private $dbms = false;
	private $eccident = false;
	
	private $skip_unknown_category = true;
	private $categories = false;
	private $destination_path = false;
	
	private $statistics = false;
	
	public $message = "";
	
	public function __construct($eccident=false, $ini, $status_obj) {
		#$this->db = $db;
		$this->eccident = $eccident;
		$this->ini = $ini;
		$this->set_destination_path();
		$this->status_obj = $status_obj;
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
		$destination_path = $this->ini->get_ecc_ini_user_folder($this->eccident.DIRECTORY_SEPARATOR."roms".DIRECTORY_SEPARATOR."organized".DIRECTORY_SEPARATOR, true);
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
			$opt_cat_name = $this->optimize_category_path($value['cat_name']);
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
		#$path = str_replace("//", "/", $path);
		#$path = str_replace("\\\\", "\\", $path);
		return $path;
	}
	
	/**
	* .......
	*
	* @author ascheibel
	* @return boolean
	*/
	private function optimize_category_path($category) {
		$optimized_cat = str_replace("  ", " ", $category);
		$optimized_cat = ereg_replace("[^0-9A-Za-z\-]", " ", $category);
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
		foreach($files as $id => $data) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$path_source = $data['path'];
			$path_destination =  $this->create_new_file_path($opt_cat_name, basename($data['path']));
			
			#print "1 path_source#### ".$path_source."\n";
			
			if (file_exists($path_source)) {
				
				#print "2 path_source#### ".$path_source."\n";
				
				// create directories, if needed
				if (!is_dir(dirname($path_destination))) {
					if ($this->reorganize_mode != 'preview') {
						$this->create_dirs_recursive(dirname($path_destination), 0777, true);
					}
				}
				if (!file_exists($path_destination)) {
					
					#print "3 path_destination#### ".$path_destination."\n";
					
					// copy, move or print out commands!
					if ($this->reorganize_mode == 'copy') {
						copy($path_source, $path_destination);
						
						// ABS-PATH TO REL-PATH...
						#print "1 copy ".$path_destination."\n";
						$this->update_fdata_by_path($path_source, $path_destination);
					}
					elseif ($this->reorganize_mode == 'move') {
						rename($path_source, $path_destination);
						
						// ABS-PATH TO REL-PATH...
						$path_destination = realpath($path_destination);
						#print "rename ".$path_destination."\n";
						$this->update_fdata_by_path($path_source, $path_destination);
					}
					
					
					
					$this->statistics['DONE'][$opt_cat_name][] = basename($path_destination);
				}
				else {
					$this->update_fdata_by_path($path_source, $path_destination);
					$this->statistics['ISSET'][$opt_cat_name][] = basename($path_destination);
				}
			}
			else {
				$this->statistics['MISSING'][$opt_cat_name][] = basename($path_destination);
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
			$this->message .= "\t".basename($path_destination)."\n";
			$this->status_obj->update_message($this->message);
			// STATUS BAR OBSERVER CANCEL
			// ---------------------------------
			if ($this->status_obj->is_canceled()) return false;
			// ---------------------------------
			
		}
		$this->message .= "DONE!\n\n";
		
		/*
		foreach($files['INVALID'] as $id => $data) {
			$this->statistics['INVALID_SOURCE'][$opt_cat_name][dirname($data['path'])][] = basename($data['path']);
		}
		*/
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
			}
			else {
				$out['INVALID'][$res['fd.id']]['crc32'] = $res['fd.crc32'];
				$out['INVALID'][$res['fd.id']]['path'] = $res['fd.path'];
			}
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
				$idx = (int) $res['cat_id'] +1;
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
			count(*) as cnt, m.category as cat_id, sum(fd.size) as size
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
	
	private function update_fdata_by_path($path_source, $path_destination) {
		
		// ABS-PATH TO REL-PATH...
		$path_destination = realpath($path_destination);
		#print "1update_fdata_by_path ".$path_destination."\n";
		if (strpos($path_destination, ECC_BASEDIR) == 0) {
			$path_destination = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $path_destination);
			$path_destination = str_replace("\\", "/", $path_destination);
			#print "2update_fdata_by_path ".$path_destination."\n";
		};
		
		$q = '
			UPDATE
			fdata
			SET
			path = "'.($path_destination).'"
			WHERE
			path = "'.$path_source.'"
		';
		#print $q."\n";
		$hdl = $this->dbms->query($q);
	}
	
	private function create_dirs_recursive($strPath, $mode = 0777) {
		return is_dir($strPath) or ($this->create_dirs_recursive(dirname($strPath), $mode) and mkdir($strPath, $mode) );
	}
}
?>
