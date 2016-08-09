<?
class IniFile {
	
	private $ini_path = false;
	
	private $ini = array();
	private $ini_platform = array();
	#private $ini_base_path = "ecc-system/conf/";
	
	private $ini_path_global = false;
	
	/*
	*
	*/	
	public function __construct($ecc_ini_path)	{
		$this->ini_path = realpath($ecc_ini_path);
		if (!$this->ini_path) return false;
		
		// THIS IS THE INI!!!!
		$this->ini_path_global = $this->ini_path."/ecc_general.ini";
		
		$this->get_ecc_ini();
	}
	
	public function get_ini_path_global() {
		return realpath($this->ini_path_global);
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
			$ini = @parse_ini_file($this->ini_path_global, true);
			#$ini = $this->parse_ini_file_quotes_safe($this->ini_path_global);
			$this->ini = (count($ini)) ? $ini : false;
			
			$this->ini['ECC_PLATFORM'] = $this->get_ecc_platform_data();
		}
		return $this->ini;
	}
	
	public function get_ecc_global_ini() {
		$ini = $this->get_ecc_ini();
		unset($ini['ECC_PLATFORM']);
		return $ini;
	}
	
	public function get_ecc_platform_ini($eccident) {
		$ini = array();
		$file = realpath($this->ini_path."/ecc_platform_".$eccident.".ini");
		if (!$file) return false;
		$ini = @parse_ini_file($file, true);
		#$ini = $this->parse_ini_file_quotes_safe($file);
		return $ini;
	}
	
	public function write_ecc_platform_ini($platform_ini) {
		$file = $this->ini_path."/ecc_platform_".$platform_ini['GENERAL']['eccident'].".ini";
		if (!$this->backup_ini_platform($platform_ini['GENERAL']['eccident'])) return false;
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
	
	public function write_ini_global($assoc_array) {
		if (!is_array($assoc_array) || !count($assoc_array)) return false;
		return $this->write_ini_file($this->ini_path_global, $assoc_array);
	}
	
	public function get_ecc_platform_data($show_all=false) {
		if (!$this->ini) $this->get_ecc_ini();
		
		$this->ini_platform['null']['GENERAL']['navigation'] = "# All found";
		
		$nav_skeleton = $this->get_ecc_ini_key('NAVIGATION');
		foreach ($nav_skeleton as $eccident => $active) {
			if ($show_all || $active) {
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
		$ini = @parse_ini_file($file, true);
		#$ini = $this->parse_ini_file_quotes_safe($file);
		return $ini;
	}
	
	
	public function get_ecc_platform_navigation($eccident=false, $category=false, $show_all=false) {
		if (!$this->ini) $this->get_ecc_ini();
		if ($show_all || !$this->ini_platform) $this->get_ecc_platform_data($show_all);
		
		if ($eccident && isset($this->ini_platform[$eccident])) return $this->ini_platform[$eccident]['GENERAL']['navigation'];
		$out = array();
		foreach ($this->ini_platform as $eccident => $platform_data) {
			if ($show_all || $eccident=='null' || (isset($this->ini['NAVIGATION'][$eccident]) && $this->ini['NAVIGATION'][$eccident])) {
				if ($category && @$platform_data['GENERAL']['category'] != $category) continue;
				$out[$eccident] = $platform_data['GENERAL']['navigation'];
			}
		}
		## SORT ##
		natcasesort($out);
		return $out;
	}
	
	public function get_ecc_platform_categories($reload=false) {
		if ($reload) {
			$this->ini = array();
			$this->ini_platform = array();
		}
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		
		$count = array();
		$countTotal = 0;
		foreach ($this->ini_platform as $eccident => $platform_data) {
			$currentCat = (@$platform_data['GENERAL']['category']) ? $platform_data['GENERAL']['category'] : "???";
			if (!isset($count[$currentCat])) {
				$count[$currentCat] = 1;
			}
			else {
				$count[$currentCat]++;				
			}
			$countTotal++;
		}
		$out[''] = "All Categories (".$countTotal.")";
		foreach ($this->ini_platform as $eccident => $platform_data) {
			if (isset($platform_data['GENERAL']['category'])) {
				$out[$platform_data['GENERAL']['category']] = $platform_data['GENERAL']['category']." (".$count[$platform_data['GENERAL']['category']].")";
			}
		}
		## SORT ##
		natcasesort($out);
		return $out;
	}
	
	public function get_ecc_platform_parser($eccident=false) {
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		
		$ret = array();
		if ($eccident) {
			$extensions = @$this->ini_platform[$eccident]['EXTENSIONS'];
			$parser = @$this->ini_platform[$eccident]['PARSER'];
			if ($parser && $extensions) {
				$data = $this->get_parser_from_ini($extensions, $parser);
				foreach ($data as $eccId => $eccParser) {
					$ret[$eccId][] = $eccParser;
				}
			}
		}
		else {
			$ret111 = array();
			foreach($this->ini_platform as $eccident => $data) {
				$extensions = @$this->ini_platform[$eccident]['EXTENSIONS'];
				$parser = @$this->ini_platform[$eccident]['PARSER'];
				//$ret = array_merge($ret, $this->get_parser_from_ini($extensions, $parser));
				
				$data = $this->get_parser_from_ini($extensions, $parser);
				foreach ($data as $eccId => $eccParser) {
					$ret[$eccId][] = $eccParser;
				}
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
		$filename = $this->ini_path."/ecc_history.ini";
		if (!file_exists($filename)) return false;
		// search for key
		$data = @parse_ini_file($filename);
		#$data = $this->parse_ini_file_quotes_safe($filename);
		return (isset($data[$key]) && $data[$key]) ? $data[$key] : false;
	}
	
	/*
	*
	*/
	public function write_ecc_histroy_ini($search_key, $new_path, $check_path=false) {
		// check for real path... valid?
		if ($check_path) $new_path = realpath($new_path);
		
		$ini_path = $this->ini_path."/ecc_history.ini";
		if (!file_exists($ini_path)) return false;
		
		// search for key and replace value
		$data = @parse_ini_file($ini_path);
		#$data = $this->parse_ini_file_quotes_safe($ini_path);
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
		#print $user_folder."\n";
		if (!($user_folder && realpath($user_folder))) return false;
		
		// only if user folder is selected, create subfolder if needed
		if ($user_subfolder) {
			// build path name
			$user_folder = $user_folder.DIRECTORY_SEPARATOR.$user_subfolder.DIRECTORY_SEPARATOR;
			if ($create_folder_recursive===true) {
				// create recursive directory, if dir doesnt exists
				$this->create_dirs_recursive($user_folder);
				#if (!is_dir($user_folder)) {
				#	if (!mkdir($user_folder, 0777, true)) return false;
				#}
			}
			else {
				$user_folder = realpath($user_folder);
			}
		}
		return $user_folder;
	}
	
	public function create_folder($user_folder) {
		return $this->create_dirs_recursive($user_folder);
	}
	
	private function create_dirs_recursive($strPath, $mode = 0777) {
		return is_dir($strPath) or ($this->create_dirs_recursive(dirname($strPath), $mode) and mkdir($strPath, $mode) );
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
	
	public function backup_ini_global() {
		if (FALSE == copy($this->ini_path_global, $this->ini_path_global.".bak")) return FALSE;
		return true;
	}
	public function backup_ini_platform($eccident) {
		$file = realpath($this->ini_path."/ecc_platform_".$eccident.".ini");
		if (FALSE == copy($file, $file.".bak")) return FALSE;
		return true;
	}
	
	public function strip_danger_chars($string="") {
		$regex = "[\"\'\;]+?";
		$matches = array();
		preg_match('/'.$regex.'/i', $string, $matches);
		if (!isset($matches[0])) return trim($string);
		return trim(preg_replace('/'.$regex.'/i', "", $string));
	}
	
	public function strip_chars_subfolder_url($string=array()) {
		$regex = '/([^0-9a-z_\-,]+)/iU';
		$split = explode(",", $string);
		$result = array();
		foreach ($split as $string) {
			$result[] = preg_replace($regex, "", trim($string));
		}
		return implode(",", $result);
	}
	
	public function parse_ini_file_quotes_safe($f, $row_count_limit=false)
	{
		$newline = "
		";
		$null = "";
		$r=$null;
		$first_char = "";
		$sec=$null;
		$comment_chars="/*<;#?>";
		$num_comments = "0";
		$header_section = "";
		
		//Read to end of file with the newlines still attached into $f
		$f=file($f);
		
		$row_count = ($row_count_limit) ? $row_count_limit : count($f);
		
		// Process all lines from 0 to count($f) 
		for ($i=0;$i<@$row_count;$i++) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$newsec=0;
			$w=@trim($f[$i]);
			$first_char = @substr($w,0,1);
			if ($w) {
				if ((!$r) or ($sec)) {
					// Look for [] chars round section headings
					if ((@substr($w,0,1)=="[") and (@substr($w,-1,1))=="]") {$sec=@substr($w,1,@strlen($w)-2);$newsec=1;}
					// Look for comments and number into array
					if ((stristr($comment_chars, $first_char) === FALSE)) {} else {$sec=$w;$k="Comment".$num_comments;$num_comments = $num_comments +1;$v=$w;$newsec=1;$r[$k]=$v;/*echo "comment".$w.$newline;*/}
					//
				}
				if (!$newsec) {
					//
					// Look for the = char to allow us to split the section into key and value 
					$w=@explode("=",$w);$k=@trim($w[0]);unset($w[0]); $v=@trim(@implode("=",$w));
					// look for the new lines 
					if ((@substr($v,0,1)=="\"") and (@substr($v,-1,1)=="\"")) {$v=@substr($v,1,@strlen($v)-2);}
					if ($sec) {$r[$sec][$k]=$v;} else {$r[$k]=$v;}
				}
			}
		}
		return $r;
	}
	
	public function getPlatformsByFileExtension($extesion) {
		if (!$this->ini) $this->get_ecc_ini();
		
		$platform = array();
		foreach ($this->ini['NAVIGATION'] as $eccident => $state) {
			if (!$state) continue;
			if ($this->ini['ECC_PLATFORM'][$eccident]['EXTENSIONS']) {
				if (isset($this->ini['ECC_PLATFORM'][$eccident]['EXTENSIONS'][$extesion])) {
					$platform[$eccident] = $this->ini['ECC_PLATFORM'][$eccident]['GENERAL']['navigation'];
				}
			}
		}
		return $platform;

	}
}
?>
