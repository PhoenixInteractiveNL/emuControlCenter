<?
class IniFile {
	
	private $ini_path = false;
	
	private $ini = array();
	private $ini_platform = array();
	#private $ini_base_path = "ecc-system/conf/";
	
	/*
	*
	*/	
	public function __construct($ecc_ini_path)	{
		$this->ini_path = realpath($ecc_ini_path);
		if (!$this->ini_path) return false;
		$this->get_ecc_ini();
	}
	
	public function reload() {
		$this->ini = array();
		$this->get_ecc_ini();
	}
	
	/*
	* get ini-file from filesystem and writes the data to ini var.
	*/
	public function get_ecc_ini() {
		if (!$this->ini_path) return false;
		if (!count($this->ini)) {
			$ini = parse_ini_file($this->ini_path."/ecc_general.ini", true);
			$this->ini = (count($ini)) ? $ini : false;
			
			$this->ini['ECC_PLATFORM'] = $this->get_ecc_platform_data();
		}
		return $this->ini;
	}
	
	public function get_ecc_platform_ini($eccident) {
		$ini = array();
		$file = realpath($this->ini_path."/ecc_platform_".$eccident.".ini");
		if (!$file) return false;
		$ini = parse_ini_file($file, true);
		return $ini;
	}
	
	public function write_ecc_platform_ini($platform_ini) {
		$file = $this->ini_path."/ecc_platform_".$platform_ini['GENERAL']['eccident'].".ini";
		return $this->write_ini_file($file, $platform_ini);
	}
	
	function write_ini_file($path, $assoc_array) {
		
		$content = "";
		foreach ($assoc_array as $key => $item) {
			if (is_array($item)) {
				$content .= "[$key]\n";
				foreach ($item as $key2 => $item2) {
					$content .= "$key2 = $item2\n";
				} 
			} else {
				$content .= "$key = $item\n";
			}
		}
			
		if (!$handle = fopen($path, 'w')) {
			return false;
		}
		if (!fwrite($handle, $content)) {
			return false;
		}
		fclose($handle);
		return true;
	}
	
	public function get_ecc_platform_data() {
		if (!$this->ini) $this->get_ecc_ini();
		
		$this->ini_platform['null']['GENERAL']['navigation'] = "# All found";
		
		$nav_skeleton = $this->get_ecc_ini_key('NAVIGATION');
		foreach ($nav_skeleton as $eccident => $active) {
			if ($active) {
				if ($data = $this->get_ecc_platform_ini($eccident)) {
					$this->ini_platform[$eccident] = $this->get_ecc_platform_ini($eccident);
				}
				else {
					print "### ERROR! Missing ecc_platform_".$eccident.".ini - Nav for ".$eccident." is hidden! ###\n";
				}
			}
		}
		return $this->ini_platform;
	}
	
	public function get_ecc_platform_info_data($eccident) {
		if (!$this->ini) $this->get_ecc_ini();
		if ($eccident=='null' || !$eccident) $eccident = 'ecc';
		
		$ini = array();
		$file = realpath($this->ini_path."/../infos/ecc_platform_".$eccident."_info.ini");
		if (!$file) return false;
		$ini = parse_ini_file($file, true);
		return $ini;
	}
	
	
	public function get_ecc_platform_navigation($eccident=false) {
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		
		if ($eccident && isset($this->ini_platform[$eccident])) return $this->ini_platform[$eccident]['GENERAL']['navigation'];
		
		$out = array();
		foreach ($this->ini_platform as $eccident => $platform_data) {
			$out[$eccident] = $platform_data['GENERAL']['navigation'];
		}
		return $out;
	}
	
	public function get_ecc_platform_parser($eccident=false) {
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		
		$ret = array();
		if ($eccident) {
			$extensions = @$this->ini_platform[$eccident]['EXTENSIONS'];
			$parser = @$this->ini_platform[$eccident]['PARSER'];
			if ($parser && $extensions) $ret = $this->get_parser_from_ini($extensions, $parser);
		}
		else {
			foreach($this->ini_platform as $eccident => $data) {
				$extensions = @$this->ini_platform[$eccident]['EXTENSIONS'];
				$parser = @$this->ini_platform[$eccident]['PARSER'];
				$ret = array_merge($ret, $this->get_parser_from_ini($extensions, $parser));
			}
		}
		return $ret;
	}
	
	public function get_ecc_platform_extensions_by_eccident($eccident) {
		if (!$eccident) return array();
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		return $this->ini_platform[$eccident]['EXTENSIONS'];
	}
	
	public function get_ecc_platform_name_by_eccident($eccident) {
		if (!$eccident) $eccident="null";
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		return $this->ini_platform[$eccident]['GENERAL']['navigation'];
	}
	
	/*
	* gets the data from the ini-file. you can search something like this.
	* $this->get_ecc_ini_key('SECTION', 'ENTITY')
	*/
	public function get_ecc_ini_key($key1=false, $key2=false) {
		if (!$this->ini) $this->get_ecc_ini();
		if ($key2!==false) {
			return (isset($this->ini[$key1][$key2])) ? $this->ini[$key1][$key2] : false;
		}
		else {
			return (isset($this->ini[$key1])) ? $this->ini[$key1] : false;
		}
	}
	
	/*
	*
	*/
	public function read_ecc_histroy_ini($key)	{
		// get inipath
		#$filename = $this->get_ecc_ini_key('ECC_SYS_PATHS', 'histroy.ini');
		
		$filename = $this->ini_path."/ecc_history.ini";
		
		if (!file_exists($filename)) return false;
		// search for key
		$data = parse_ini_file($filename);
		return (isset($data[$key]) && $data[$key]) ? $data[$key] : false;
	}
	
	/*
	*
	*/
	public function write_ecc_histroy_ini($search_key, $new_path, $check_path=false) {
		// check for real path... valid?
		if ($check_path) $new_path = realpath($new_path);
		
		// get inipath
		#$ini_path = $this->get_ecc_ini_key('ECC_SYS_PATHS', 'histroy.ini');
		
		$ini_path = $this->ini_path."/ecc_history.ini";
		#$ini_path = "ecc-system/conf/history.ini";
		if (!file_exists($ini_path)) return false;
		
		// search for key and replace value
		$data = parse_ini_file($ini_path);
		
		if (!isset($data[$search_key])) {
			$data[$search_key] = $new_path;
		}
		
		$new_ini = "";
		foreach ($data as $key => $path) {
			if ($key == $search_key) {
				$new_ini .= $key."=\"".$new_path."\"\n";
			}
			else {
				$new_ini .= $key."=\"".$path."\"\n";
			}
		}
		if (!file_put_contents($ini_path, $new_ini)) return false;
		return true;
	}
	
	/*
	* get userfolder from ini and create subfolder, if needed.
	* @return mixed (new) userpath | false
	*/
	public function get_ecc_ini_user_folder($user_subfolder=false, $create_folder_recursive=false) {
		// get user-folder from ecc.ini
		$user_folder = $this->get_ecc_ini_key('USER_DATA', 'base_path');
		if (!($user_folder && realpath($user_folder))) return false;
		// only if user folder is selected, create subfolder if needed
		if ($user_subfolder) {
			// build path name
			$user_folder = $user_folder.DIRECTORY_SEPARATOR.$user_subfolder.DIRECTORY_SEPARATOR;
			if ($create_folder_recursive===true) {
				// create recursive directory, if dir doesnt exists
				if (!is_dir($user_folder)) {
					if (!mkdir($user_folder, 0777, true)) return false;
				}
			}
			else {
				$user_folder = realpath($user_folder);
			}
		}
		return $user_folder;
	}
	
	
	/*
	* Baut ein array auf, in dem der key die extension und die value
	* der parser ist. Im FileList Object wird dann die extension
	* gematcht und der richtige parser instanziiert.
	*/
	public function get_parser_from_ini($selected_extensions, $file_parser) {
		$wanted_extensions = array();
		if (isset($file_parser)) {
			foreach ($file_parser as $parser_name => $extensions) {
				$extensions_array = explode(",",$extensions);
				foreach ($extensions_array as $ext) {
					$ext = trim($ext);
					if (isset($selected_extensions[$ext]) && $selected_extensions[$ext]) {
						$wanted_extensions[$ext] = $parser_name;
					}
				}
			}
			return $wanted_extensions;
		}
		else {
			#print "no valid ini file\nParser are missing";
			return array();
		}
	}
}
?>
