<?
class IniFile {
	
	private $ini = array();
	private $ini_platform = array();

	# ecc uses this folder as default!
	private $eccDefaultUserFolder = '../ecc-user/';
	
	private $eccDefaultConfigPath = 'conf/';
	private $eccUserConfigPath = '../ecc-user-configs/';
	
	# general ini file - ecc configuration
	private $eccIniGeneralName = 'ecc_general.ini';
	private $eccIniNavigationName = 'ecc_navigation.ini';
	private $eccIniGeneralFile = false;
	
	# history ini file - ecc runtime config
	private $eccIniHistoryName = 'ecc_history.ini';
	private $eccIniHistoryFile = false;
	
	# holds cached data for platform inis!
	private $cachePlatformIniData = array();
	
	/*
	*
	*/	
	public function __construct()	{

		# create userconfig-path, if needed
		if (!is_dir($this->eccUserConfigPath)) mkdir($this->eccUserConfigPath);

		# get the current history ini file
		$this->eccIniHistoryFile = $this->eccUserConfigPath.$this->eccIniHistoryName;
		if (!file_exists($this->eccIniHistoryFile)) {
			file_put_contents($this->eccIniHistoryFile, '');
		}
		
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
		if (!$this->eccDefaultConfigPath) return false;
		if (!$this->ini) {
			$ini = @parse_ini_file($this->getGeneralIniPath(), true);
			$this->ini = (count($ini)) ? $ini : false;

			$this->ini['NAVIGATION'] = reset(@parse_ini_file($this->eccDefaultConfigPath.$this->eccIniNavigationName, true));
			
			$this->ini['ECC_PLATFORM'] = $this->get_ecc_platform_data();
		}
		return $this->ini;
	}
	
	public function get_ecc_global_ini() {
		$ini = $this->get_ecc_ini();
		unset($ini['ECC_PLATFORM']);
		return $ini;
	}
	
	public function get_ecc_platform_ini($eccident, $cached=true) {
		if ($eccident == 'null') return false;
		
		if ($cached && isset($this->cachePlatformIniData[$eccident])) {
			return $this->cachePlatformIniData[$eccident];
		}
		$file = $this->getPlatformIniPathByFolderDispatcher('ecc_platform_'.$eccident.'.ini');
		if (!$file) return false;
		$iniData = $this->parse_ini_file_quotes_safe($file);
		$this->cachePlatformIniData[$eccident] = $iniData;
		
		$systemIni = $this->parse_ini_file_quotes_safe($this->eccDefaultConfigPath.'ecc_platform_'.$eccident.'.ini');
		$iniData['EXTENSIONS'] = $systemIni['EXTENSIONS'];
		$iniData['PARSER'] = $systemIni['PARSER'];

		return $iniData;
	}
	
	public function write_ecc_platform_ini($platform_ini) {
		$eccident = $platform_ini['GENERAL']['eccident'];
		$file = $this->getPlatformIniPathByFolderDispatcher('ecc_platform_'.$eccident.'.ini', true);
		if (!$this->backup_ini_platform($platform_ini['GENERAL']['eccident'])) return false;
		return $this->write_ini_file($file, $platform_ini);
	}
	
	function write_ini_file($path, $assoc_array) {
		
		$content = "";
		foreach ($assoc_array as $key => $item) {
			if (is_array($item)) {
				$content .= "[$key]\n";
				foreach ($item as $key2 => $item2) {
					if (0 !== strpos($item2, '"')) $item2 = '"'.$item2.'"';
					$content .= "$key2 = $item2\n";
				} 
			} else {
				if (0 === strpos($item, '"')) $item = '"'.$item.'"';
				$content .= "$key = $item\n";
			}
		}
		
		if (!$handle = fopen($path, 'w'))return false;
		if (!fwrite($handle, $content)) return false;
		
		fclose($handle);
		return true;
	}
	
	public function write_ini_global($assoc_array) {
		if (!is_array($assoc_array) || !count($assoc_array)) return false;
		
		$saveArray = $assoc_array;
		$eccNavigation['NAVIGATION'] = $saveArray['NAVIGATION'];
		unset($saveArray['NAVIGATION']);
		$this->write_ini_file($this->eccDefaultConfigPath.$this->eccIniNavigationName, $eccNavigation);

		return $this->write_ini_file($this->getGeneralIniPath(true), $saveArray);
	}
	
	public function get_ecc_platform_data($show_all=false) {
		if (!$this->ini) $this->get_ecc_ini();
		
		$this->ini_platform['null']['GENERAL']['navigation'] = "# All found";
		
		$nav_skeleton = $this->get_ecc_ini_key('NAVIGATION');
		foreach ($nav_skeleton as $eccident => $active) {
			if ($show_all || $active) {
				if ($data = $this->get_ecc_platform_ini($eccident)) {
					$this->ini_platform[$eccident] = $data;
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
		$file = realpath($this->eccDefaultConfigPath."/../infos/ecc_platform_".$eccident."_info.ini");
		if (!$file) return false;
		$ini = @parse_ini_file($file, true);

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
	
	public function getPlatformCategoryByEccIdent($eccident=false) {
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		if (isset($this->ini_platform[$eccident])) {
			return $this->ini_platform[$eccident]['GENERAL']['category'];
		}
		return false;
	}
	
	public function get_ecc_platform_categories($eccIdents=false) {
		
		if ($eccIdents) {
			$this->ini = array();
			$this->ini_platform = array();
		}
		if (!$this->ini) $this->get_ecc_ini();
		if (!$this->ini_platform) $this->get_ecc_platform_data();
		
		$count = array();
		$countTotal = 0;
		foreach ($this->ini_platform as $eccident => $platform_data) {
			$currentCat = (@$platform_data['GENERAL']['category']) ? $platform_data['GENERAL']['category'] : "???";
			
			if ($eccIdents) {
				if (in_array($eccident, $eccIdents)) {
					if (!isset($count[$currentCat])) {
						$count[$currentCat] = 1;
					}
					else {
						$count[$currentCat]++;				
					}
					$countTotal++;
				}
			}
			else {
				if (!isset($count[$currentCat])) {
					$count[$currentCat] = 1;
				}
				else {
					$count[$currentCat]++;				
				}
				$countTotal++;
			}
		}
		$out[''] = "All Categories (".$countTotal.")";
		foreach ($this->ini_platform as $eccident => $platform_data) {
			if ($eccIdents) {
				if (@in_array($eccident, $eccIdents)) {
					$out[$platform_data['GENERAL']['category']] = $platform_data['GENERAL']['category']." (".$count[$platform_data['GENERAL']['category']].")";;
				}
			}
			else {	
				if (isset($platform_data['GENERAL']['category'])) {
					$out[$platform_data['GENERAL']['category']] = $platform_data['GENERAL']['category']." (".$count[$platform_data['GENERAL']['category']].")";
				}
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
	public function read_ecc_histroy_ini($key=false)	{
		if (!file_exists($this->eccIniHistoryFile)) return false;
		// search for key
		$data = @parse_ini_file($this->eccIniHistoryFile);
		
		if ($key===false) {
			return $data;
		}
		else {
			return (isset($data[$key]) && $data[$key]) ? $data[$key] : false;	
		}
		
		
	}
	
	public function emptyEccHistory() {
		if (!file_exists($this->eccIniHistoryFile)) return false;
		file_put_contents($this->eccIniHistoryFile, "");
		return true;
	}
	
	/*
	*
	*/
	public function write_ecc_histroy_ini($search_key, $new_path, $check_path=false) {
		// check for real path... valid?
		if ($check_path) $new_path = realpath($new_path);
		
		if (!file_exists($this->eccIniHistoryFile)) return false;
		
		// search for key and replace value
		$data = @parse_ini_file($this->eccIniHistoryFile);
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
		if (!file_put_contents($this->eccIniHistoryFile, $new_ini)) return false;
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
				if (!$this->create_dirs_recursive($user_folder)) {
					return false;
				}
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
		#if (!$this->parentIsWritable($strPath)) return false;
		return is_dir($strPath) or ($this->create_dirs_recursive(dirname($strPath), $mode) and mkdir($strPath, $mode) );
	}
	
	public function parentIsWritable($path) {
		$parentPath = (substr($path, -1) == DIRECTORY_SEPARATOR) ? substr($path, 0, -1) : $path;
		$split = explode(DIRECTORY_SEPARATOR, $parentPath);
		array_pop($split);
		$parentPath = implode(DIRECTORY_SEPARATOR, $split);
		$parentPath = realpath($parentPath);
		if (!is_writable($parentPath)) {
			$this->setDefaultEccBasePath();
			#throw new Exception('parent folder not writable');
		}
		return true;
	}
	
	public function setDefaultEccBasePath() {
		$ini = $this->get_ecc_global_ini();
		$ini['USER_DATA']['base_path'] = $this->eccDefaultUserFolder;
		$this->write_ini_global($ini);
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
		if (FALSE == copy($this->getGeneralIniPath(), $this->getGeneralIniPath().".bak")) return FALSE;
		return true;
	}
	public function backup_ini_platform($eccident) {
		$file = $this->getPlatformIniPathByFolderDispatcher('ecc_platform_'.$eccident.'.ini');
		return copy($file, $file.".bak");
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
		$f=file($f);
		$row_count = ($row_count_limit) ? $row_count_limit : count($f);
		for ($i=0;$i<@$row_count;$i++) {
			while (gtk::events_pending()) gtk::main_iteration();
			$newsec=0;
			$w=@trim($f[$i]);
			$first_char = @substr($w,0,1);
			if ($w) {
				if ((!$r) or ($sec)) {
					if ((@substr($w,0,1)=="[") and (@substr($w,-1,1))=="]") {$sec=@substr($w,1,@strlen($w)-2);$newsec=1;}
					if ((stristr($comment_chars, $first_char) === FALSE)) {} else {$sec=$w;$k="Comment".$num_comments;$num_comments = $num_comments +1;$v=$w;$newsec=1;$r[$k]=$v;/*echo "comment".$w.$newline;*/}
				}
				if (!$newsec) {
					$w=@explode("=",$w);$k=@trim($w[0]);unset($w[0]); $v=@trim(@implode("=",$w));
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

	function getGeneralIniPath($transferToUserFolder=false) {
		# return path to user-folder, if ini should be transfered
		if ($transferToUserFolder) {
			return $this->eccUserConfigPath.$this->eccIniGeneralName;
		}
		else {
			if (file_exists($this->eccUserConfigPath.$this->eccIniGeneralName)) {
				return $this->eccUserConfigPath.$this->eccIniGeneralName;
			}
			return $this->eccDefaultConfigPath.$this->eccIniGeneralName;
		}
	}
	
	function getPlatformIniPathByFolderDispatcher($platformIniFilename, $transferToUserFolder=false) {
		# return path to user-folder, if ini should be transfered
		if ($transferToUserFolder) {
			return $this->eccUserConfigPath.$platformIniFilename;
		}
		else {
			if (file_exists($this->eccUserConfigPath.$platformIniFilename)) {
				return $this->eccUserConfigPath.$platformIniFilename;
			}
			return $this->eccDefaultConfigPath.$platformIniFilename;
		}
	}
	
}
?>
