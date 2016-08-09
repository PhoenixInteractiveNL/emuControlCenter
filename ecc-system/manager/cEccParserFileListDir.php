<?php
require_once('iFileList.php');
/*
*
*/
class EccParserFileListDir implements FileList {
	
	//
	private $_file = array();
	private $_base_directory = false;
	private $_known_extensions = array();
	
	// statistics
	private $_stats = array();
	private $_stats_total = 0;
	private $_stats_packed = array();
	private $_stats_direct = array();
	
	private $__obj_observer = false;
	
	// only for progressbar
	private $_progress_count = 1;
	private $found_str = "";
	
	public $invalidFile = array();
	
	#public $pbar_parser;
	#public $statusbar_lbl_bottom;
	
	/*
	*
	*/
	public function __construct($base_directory=false, $known_extensions=array(), $pbar_parser, $statusbar_lbl_bottom, $status_obj) {
		$this->status_obj = $status_obj;
		foreach ($known_extensions as $key => $value) {
			$this->_known_extensions[strtoupper($key)] = $value[0];
		}
		$this->_known_extensions = $known_extensions;
		$this->_base_directory = $base_directory;
		$this->_create_file_list();
	}
	
	/*
	*
	*/
	private function _create_file_list($base_directory=false)
	{
		if ($base_directory===false) {
			$base_directory = $this->_base_directory;
		}
		// verzeichnis einlesen
		if ($dhdl = @opendir($base_directory)) {
			while (false !== ($file = readdir($dhdl))) {
				
				while (gtk::events_pending()) gtk::main_iteration();
				
				$found = "";
				if ($file != "." && $file != "..") {
					
					$full_file_path = $base_directory."/".$file;
					if (is_dir($full_file_path)) {
						$this->_create_file_list($full_file_path);
					}
					else {
						// ABS-PATH TO REL-PATH...
//						$full_file_path = realpath($full_file_path);
//						if (strpos($full_file_path, ECC_BASEDIR) == 0) {
//							$full_file_path = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $full_file_path);
//						};

						// ABS-PATH TO REL-PATH...
						// 20061116 as
						$full_file_path = FACTORY::get('manager/Os')->eccSetRelativeFile($full_file_path);
						
						if ($this->is_packed_file($full_file_path)) {
						}
						else {
							// nur in liste, wenn die extension
							// bekannt ist und geparst werden soll
							$file_extension = $this->get_file_ext($file);
							if (isset($this->_known_extensions[$file_extension][0])) {
								
								#$this->_file[$file_extension][]['FILE'] = $this->normalize_path($full_file_path);
								$this->_file[$file_extension][] = array(
									'FILE' => $this->normalize_path($full_file_path),
									'PACKED' => false,
									'DIRECT_FILE' => $this->normalize_path($full_file_path),
									'PACKED_FILE' => false,
								);
								
								if (!isset($this->_stats_direct[$file_extension])) {
									$this->_stats_direct[$file_extension] = 0;
								}
								$this->_stats_direct[$file_extension]++;
							}
							else {
								
							}
						}
					}
					
					$this->_stats_total++;
					
					if ($this->_progress_count < 1000) {
						$this->_progress_count++;
					}
					else {
						$this->_progress_count = 1;
					}
					$progressbar_string = sprintf(I18N::get('status', 'parse_rom_pbar_scan_count%s'), $this->_stats_total);
					$this->status_obj->update_progressbar($this->_progress_count/1000, $progressbar_string);
					$this->status_obj->update_message($this->format_results());
					if ($this->status_obj->is_canceled()) return false;
					
				}
			}
			closedir($dhdl);
			return $this->_file;
		}
	}
	
	public function format_results()
	{
		$txt  = "";
		#$txt .= "Found media by type and extension\n";
		$txt .= I18N::get('status', 'parse_rom_detail_scan_head');
		
		if (isset($this->_stats_direct) && count($this->_stats_direct)) {
			#$txt .= "Found direct (not packed)\n";
			$txt .= I18N::get('status', 'parse_rom_detail_scan_found_direct_head');
			foreach ($this->_stats_direct as $key => $value) {
				$txt .= "$key\t\t$value\n";
			}
		}
		if (isset($this->_stats_packed) && count($this->_stats_packed)) {
			#$txt .= "Found packed (eg. zip)\n";
			$txt .= I18N::get('status', 'parse_rom_detail_scan_found_direct_head');
			foreach ($this->_stats_packed as $key => $value) {
				$txt .=  "$key\t\t$value\n";
			}
		}
		return $txt;
	}
	
	public function is_packed_file($full_file_path)
	{
		$file_extension = $this->get_file_ext($full_file_path);
		switch ($file_extension) {
			case 'zip':
				if ($this->handle_zip_file($full_file_path)) {
					return true;
				};
				break;
			
			case 'rar':
				if ($this->handle_rar_file($full_file_path)) {
					return true;
				};
				break;
			
			default:
				break;
		}
	}
	
	public function handle_zip_file($zip_file) {
		
		// ABS-PATH TO REL-PATH...
		$zip_hdl = zip_open(realpath($zip_file));
		#$zip_hdl = @zip_open($zip_file);
		if ($zip_hdl === false || is_int($zip_hdl)) {
			$this->invalidFile[] = $zip_file;
			return false;
		}
		else {
			while ($zip_entry = zip_read($zip_hdl)) {
				$file = zip_entry_name($zip_entry);
				$file_extension = $this->get_file_ext($file);
				if (isset($this->_known_extensions[$file_extension][0])) {
					$this->_file[$file_extension][] = array(
						'FILE' => $this->normalize_path($file),
						'PACKED' => $this->normalize_path($zip_file),
						'DIRECT_FILE' => $this->normalize_path($zip_file),
						'PACKED_FILE' => $this->normalize_path($file),
					);
					if (!isset($this->_stats_packed[$file_extension])) {
						$this->_stats_packed[$file_extension] = 0;
					}
					$this->_stats_packed[$file_extension]++;
				}
			}
			zip_close($zip_hdl);
			return true;
		}
	}
	
	public function handle_rar_file() {
		return false;
	}
	
	/*
	* ermittelt die file-extension, z.b. 
	* zip, smc, mp3 aus einem pfad
	* oder file_name
	*/
	public function get_file_ext($file_name)
	{
		return strtolower(array_pop(explode('.', basename($file_name))));
	}
	
	/*
	* nur / kommen in dateinamen vor
	*/
	public function normalize_path($path)
	{
		#return ($path);
		return str_replace("\\", "/", $path);
	}
	
	/*
	* Statistik
	*/
	public function get_stats() {
		$this->_stats['DIRECT'] = $this->_stats_direct;
		$this->_stats['PACKED'] = $this->_stats_packed;
		$this->_stats['TOTAL'] = $this->_stats_total;
		return $this->_stats;
	}
	
	/*
	* 
	*/
	public function get_file_list() {
		return $this->_file;
	}
	
	/*
	* 
	*/
	public function get_known_extensions() {
		return $this->_known_extensions;
	}
	
	/*
	* 
	*/
	public function get_base_directory() {
		return $this->_base_directory;
	}
}
?>
