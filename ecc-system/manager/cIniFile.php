<?
class IniFile {
	
	private $ini = array();
	private $cachedPlatformIni = array();
	private $dataCache = array();

	# ecc uses this folder as default!
	private $eccDefaultUserFolder = '../ecc-user/';
	private $eccDefaultConfigPath = 'conf/';
	private $eccUserConfigPath = '../ecc-user-configs/';
	
	# general ini file - ecc configuration
	private $eccIniGeneralName = 'ecc_general.ini';
	#private $eccIniGeneralFile = false;
	private $eccIniNavigationName = 'ecc_navigation.ini';
	private $eccIniHistoryName = 'ecc_history.ini';
	private $eccIniHistoryFile = false;
	
	public function __construct()	{
		# create userconfig-path, if needed
		$this->createUserConfigFolder();
		# get the current history ini file
		$this->createHistoryIni();
		# get complete ini data
		$this->getCompleteEccIni();
	}
	
	public function flushIni() {
		$this->ini = array();
		$this->cachedPlatformIni = array();
		$this->dataCache = array();
		$this->getCompleteEccIni();
	}
	
	# @todo - remove mkdir :-)
	public function createUserConfigFolder() {
		if (is_dir($this->eccUserConfigPath)) return true;
		else return mkdir($this->eccUserConfigPath);
	}
	
	public function createHistoryIni() {
		$this->eccIniHistoryFile = $this->eccUserConfigPath.$this->eccIniHistoryName;
		if (file_exists($this->eccIniHistoryFile)) return true;
		else return file_put_contents($this->eccIniHistoryFile, '');
	}
	
	/*
	* get ini-file from filesystem and writes the data to ini var.
	*/
	public function getCompleteEccIni() {
		if (!$this->eccDefaultConfigPath) return false;
		if (!$this->ini) {
			$ini = @parse_ini_file($this->getGeneralIniPath(), true);
			$this->ini = (count($ini)) ? $ini : false;
			$this->ini['NAVIGATION'] = reset(@parse_ini_file($this->eccDefaultConfigPath.$this->eccIniNavigationName, true));
			$this->ini['ECC_PLATFORM'] = $this->getCompletePlatformData();
		}
		return $this->ini;
	}
	
	public function getPlatformNavigation($eccident=false, $category=false, $show_all=false) {
		if (!$this->ini) $this->getCompleteEccIni();
		if ($show_all) $this->getCompletePlatformData($show_all);
		
		if ($eccident && isset($this->cachedPlatformIni[$eccident])) return $this->cachedPlatformIni[$eccident]['PLATFORM']['name'];
		$out = array();
		foreach ($this->cachedPlatformIni as $eccident => $platform_data) {
			if ($show_all || $eccident=='null' || (isset($this->ini['NAVIGATION'][$eccident]) && $this->ini['NAVIGATION'][$eccident])) {
				if ($category && @$platform_data['PLATFORM']['category'] != $category) continue;
				$out[$eccident] = $platform_data['PLATFORM']['name'];
			}
		}
		## SORT ##
		natcasesort($out);
		return $out;
	}
	
	public function getCompletePlatformData($show_all=false) {
		if (!$this->ini) $this->getCompleteEccIni();
		$this->cachedPlatformIni['null']['PLATFORM']['name'] = "# All found";
		$nav_skeleton = $this->getKey('NAVIGATION');
		foreach ($nav_skeleton as $eccident => $active) {
			if ($show_all || $active) {
				if ($data = $this->getPlatformIniByEccident($eccident)) {
					if ($show_all || $data['PLATFORM']['active']) $this->cachedPlatformIni[$eccident] = $data;
				}
				else {
					print "### ERROR! Missing ecc_".$eccident."_user|system.ini - Nav for ".$eccident." is hidden! ###\n";
				}
			}
		}
		return $this->cachedPlatformIni;
	}
	
	public function getPlatformIni($eccident) {
		if (!$eccident) return array();
		
		# get from default conf or form user-config folder!
		$platformUserIni = $this->getPlatformIniPathByFolderDispatcher('ecc_'.$eccident.'_user.ini');
		
		if (!$platformUserIni) return false;
		$platformUserIniData = $this->parse_ini_file_quotes_safe($platformUserIni);
		
		# get sys config from default config path!!!!!!
		$platformSysIni = $this->eccDefaultConfigPath.'ecc_'.$eccident.'_system.ini';
		if (!$platformSysIni) return false;
		$platformSysIniData = $this->parse_ini_file_quotes_safe($platformSysIni);
		
		$platformIniData = array_merge_recursive($platformUserIniData, $platformSysIniData);
		
		return $platformIniData;
	}
	
	public function getPlatformIniByEccident($eccident, $cached=true) {
		
		# @todo warum doppelt? getPlatformIni
		
		if ($eccident == 'null') return false;
		# get data from cache
		if ($cached && isset($this->dataCache['platform'][$eccident])) {
			return $this->dataCache['platform'][$eccident];
		}
		# get data
		$iniData = $this->getPlatformIni($eccident);
		# fill cache!
		$this->dataCache['platform'][$eccident] = $iniData;
		return $iniData;
	}
	
	/*
	* gets the data from the ini-file. you can search something like this.
	* $this->getKey('SECTION', 'ENTITY')
	*/
	public function getKey($key1=false, $key2=false) {
		if (!$this->ini) $this->getCompleteEccIni();
		if ($key2!==false) {
			return (isset($this->ini[$key1][$key2])) ? $this->ini[$key1][$key2] : false;
		}
		else {
			return (isset($this->ini[$key1])) ? $this->ini[$key1] : false;
		}
	}
	
	public function getHistoryKey($key=false) {
		# get from cache
		if (isset($this->dataCache['history'])) {
			if ($key===false) return $this->dataCache['history'];
			else return (isset($this->dataCache['history'][$key])) ? $this->dataCache['history'][$key] : false;
		}
		# not cached, get fresh
		if (!file_exists($this->eccIniHistoryFile)) return false;
		$data = $this->parse_ini_file_quotes_safe($this->eccIniHistoryFile);
		$this->dataCache['history'] = $data;
		
		# return data
		if ($key===false) return $data;
		else return (isset($data[$key]) && $data[$key]) ? $data[$key] : false;	
	}
	
	public function clearHistoryIni() {
		if (!file_exists($this->eccIniHistoryFile)) return false;
		unset($this->dataCache['history']);
		file_put_contents($this->eccIniHistoryFile, "");
		return true;
	}
	
	public function storeHistoryKey($key, $value, $validatePath=false) {

		if (!file_exists($this->eccIniHistoryFile)) $this->createHistoryIni();

		// check for real path... valid?
		if ($validatePath) $value = realpath($value);
		
		// search for key and replace value
		$data = @parse_ini_file($this->eccIniHistoryFile);
		if (!isset($data[$key])) $data[$key] = $value;
		
		$new_ini = "";
		foreach ($data as $iniKey => $path) {
			if ($iniKey == $key) $new_ini .= $key."=\"".$value."\"\n";
			else $new_ini .= $iniKey."=\"".$path."\"\n";
		}
		
		if (!file_put_contents($this->eccIniHistoryFile, $new_ini)) return false;
		
		// update cache
		$this->dataCache['history'][$key] = $value;
		
		return true;
	}
	
	public function getIniGlobalWithoutPlatforms() {
		$ini = $this->getCompleteEccIni();
		unset($ini['ECC_PLATFORM']);
		return $ini;
	}
	
	public function getCategoryByEccident($eccident=false) {
		if (!$this->cachedPlatformIni) $this->getCompletePlatformData();
		if (isset($this->cachedPlatformIni[$eccident])) {
			return $this->cachedPlatformIni[$eccident]['PLATFORM']['category'];
		}
		return false;
	}
	
	public function setDefaultEccBasePath() {
		$ini = $this->getIniGlobalWithoutPlatforms();
		$ini['USER_DATA']['base_path'] = $this->eccDefaultUserFolder;
		$this->storeIniGlobal($ini);
	}
	
	public function storeIniPlatformUser($eccident, $ini) {
		$file = $this->getPlatformIniPathByFolderDispatcher('ecc_'.$eccident.'_user.ini', true);
		if (!$file) return false;
		$this->backupFile($file);
		$newIni = $this->storeIniFile($file, $ini);
		return $newIni;
	}

	public function storeIniGlobal($assoc_array) {
		if (!is_array($assoc_array) || !count($assoc_array)) return false;
		$saveArray = $assoc_array;
		return $this->storeIniFile($this->getGeneralIniPath(true), $saveArray);
	}
	
	public function storeGlobalFont($fontDescription) {
		$gtkFontFile = '../ecc-core/etc/gtk-2.0/font';
		if (!$fontDescription) {
			@unlink($gtkFontFile);
			return false;
		}
		$iniData = array(
			'gtk-font-name' => $fontDescription,
		); 
		return $this->storeIniFile($gtkFontFile, $iniData);
	}
	
	public function backupFile($file) {
		$file = $this->getPlatformIniPathByFolderDispatcher(basename($file));
		return copy($file, $file.".bak");
	}
	
	public function backupIniGlobal() {
		return $this->backupFile($this->getGeneralIniPath());
	}
	
	function storeIniFile($path, $assoc_array) {
		$content = "";
		foreach ($assoc_array as $key => $item) {
			if (is_array($item)) {
				$content .= "[$key]\n";
				foreach ($item as $key2 => $item2) {
					if (0 !== strpos($item2, '"')) $item2 = '"'.$item2.'"';
					$content .= "$key2 = $item2\n";
				} 
			} else {
				#if (0 === strpos($item, '"')) $item = '"'.$item.'"';
				$item = '"'.$item.'"';
				$content .= "$key = $item\n";
			}
		}
		if (!$handle = fopen($path, 'w'))return false;
		if (!fwrite($handle, $content)) return false;
		fclose($handle);
		return true;
	}
	
	public function getPlatformsByFileExtension($extesion) {
		if (!$this->ini) $this->getCompleteEccIni();
		
		$platform = array();
		foreach ($this->ini['NAVIGATION'] as $eccident => $state) {
			if (!$state) continue;
			if ($this->ini['ECC_PLATFORM'][$eccident]['EXTENSIONS']) {
				if (isset($this->ini['ECC_PLATFORM'][$eccident]['EXTENSIONS'][$extesion])) {
					$platform[$eccident] = $this->ini['ECC_PLATFORM'][$eccident]['PLATFORM']['name'];
				}
			}
		}
		return $platform;
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
	
	/*
	* get userfolder from ini and create subfolder, if needed.
	* @return mixed (new) userpath | false
	*/
	public function getUserFolder($subFolder = false, $createRecursive = false) {
		// get user-folder from ecc.ini
		$userFolderBase = $this->getKey('USER_DATA', 'base_path');
		if (!($userFolderBase && realpath($userFolderBase))) return false;
		
		// only if user folder is selected, create subfolder if needed
		if ($subFolder) {
			$userFolderBase = $userFolderBase.DIRECTORY_SEPARATOR.$subFolder.DIRECTORY_SEPARATOR;
			if ($createRecursive===true) {
				if (!$this->createDirectoryRecursive($userFolderBase)) return false;
			}
			else {
				$userFolderBase = realpath($userFolderBase);
			}
		}
		return $userFolderBase;
	}
	
	public function cleanIniString($string="") {
		$regex = "[\"\'\;]+?";
		$matches = array();
		preg_match('/'.$regex.'/i', $string, $matches);
		if (!isset($matches[0])) return trim($string);
		return trim(preg_replace('/'.$regex.'/i', "", $string));
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
	
	public function getPlatformExtensionParser($eccident=false) {
		if (!$this->cachedPlatformIni) $this->getCompletePlatformData();
		
		$ret = array();
		if ($eccident) {
			$extensions = @$this->cachedPlatformIni[$eccident]['EXTENSIONS'];
			$parser = @$this->cachedPlatformIni[$eccident]['PARSER'];
			if ($parser && $extensions) {
				$data = $this->getExtensionParser($extensions, $parser);
				foreach ($data as $eccId => $eccParser) {
					$ret[$eccId][] = $eccParser;
				}
			}
		}
		else {
			$ret111 = array();
			foreach($this->cachedPlatformIni as $eccident => $data) {
				$extensions = @$this->cachedPlatformIni[$eccident]['EXTENSIONS'];
				$parser = @$this->cachedPlatformIni[$eccident]['PARSER'];
				$data = $this->getExtensionParser($extensions, $parser);
				foreach ($data as $eccId => $eccParser) {
					$ret[$eccId][] = $eccParser;
				}
			}
		}
		return $ret;
	}
	
	public function getParserOptions($eccident=false){
		if (!$this->cachedPlatformIni) $this->getCompletePlatformData();
		if (isset($this->cachedPlatformIni[$eccident]['OPTIONS'])){
			return $this->cachedPlatformIni[$eccident]['OPTIONS'];
		}
		return array();
	}
	
	/*
	* Baut ein array auf, in dem der key die extension und die value
	* der parser ist. Im FileList Object wird dann die extension
	* gematcht und der richtige parser instanziiert.
	*/
	public function getExtensionParser($selected_extensions, $file_parser) {
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
			return array();
		}
	}
	
	public function getPlatformInfo($eccident) {
		if (!$this->ini) $this->getCompleteEccIni();
		if ($eccident=='null' || !$eccident) $eccident = 'ecc';
		$ini = array();
		$file = realpath($this->eccDefaultConfigPath."/../infos/ecc_platform_".$eccident."_info.ini");
		if (!$file) return false;
		// @todo using other iniparser!
		$ini = @parse_ini_file($file, true);
		return $ini;
	}
	
	public function getPlatformFileExtensions($eccident) {
		if (!$eccident) return array();
		if (!$this->cachedPlatformIni) $this->getCompletePlatformData();
		return $this->cachedPlatformIni[$eccident]['EXTENSIONS'];
	}
	
	public function getPlatformName($eccident) {
		if (!$eccident) $eccident="null";
		if (!$this->cachedPlatformIni) $this->getCompletePlatformData();
		return $this->cachedPlatformIni[$eccident]['PLATFORM']['name'];
	}
	
	public function getPlatformCategories($eccIdents=false) {
		if ($eccIdents) {
			$this->ini = array();
			$this->cachedPlatformIni = array();
		}
		
		if (!$this->cachedPlatformIni) $this->getCompletePlatformData();
		
		$count = array();
		$countTotal = 0;
		
		foreach ($this->cachedPlatformIni as $eccident => $platform_data) {
			$currentCat = (@$platform_data['PLATFORM']['category']) ? $platform_data['PLATFORM']['category'] : "???";
			if ($currentCat == '???') continue;
			
			if ($eccIdents) {
				if (in_array($eccident, $eccIdents)) {
					if (!isset($count[$currentCat])) $count[$currentCat] = 1;
					else $count[$currentCat]++;				
					$countTotal++;
				}
			}
			else {
				if (!isset($count[$currentCat])) $count[$currentCat] = 1;
				else $count[$currentCat]++;				
				$countTotal++;
			}
		}
		$out[''] = "All Categories (".$countTotal.")";
		foreach ($this->cachedPlatformIni as $eccident => $platform_data) {
			if ($eccIdents) {
				if (@in_array($eccident, $eccIdents)) {
					$out[$platform_data['PLATFORM']['category']] = $platform_data['PLATFORM']['category']." (".$count[$platform_data['PLATFORM']['category']].")";;
				}
			}
			else {	
				if (isset($platform_data['PLATFORM']['category'])) {
					$out[$platform_data['PLATFORM']['category']] = $platform_data['PLATFORM']['category']." (".$count[$platform_data['PLATFORM']['category']].")";
				}
			}
		}
		## SORT ##
		natcasesort($out);
		return $out;
	}
	
	public function getLanguageFromI18Folders() {
		$languages = array();
		$dirHdl = opendir(ECC_BASEDIR.DIRECTORY_SEPARATOR."ecc-system/i18n/");
		if (!$dirHdl) return $languages;
		while ($file = readdir($dirHdl)) {
			if ($file == '.' || $file == '..') continue;
			$languages[] = $file;
		}
		return $languages;
	}
	
### ADD TO MANAGER FOR FILES ###
### ADD TO MANAGER FOR FILES ###
### ADD TO MANAGER FOR FILES ###

	# @todo better name, recursive param
	public function createDirectoryRecursive($strPath, $mode = 0777) {
		return is_dir($strPath) or ($this->createDirectoryRecursive(dirname($strPath), $mode) and mkdir($strPath, $mode) );
	}

	# @todo better name, recursive param	
	public function createFolder($user_folder) {
		return $this->createDirectoryRecursive($user_folder);
	}
	
	# @todo whats about cd-roms?????
	public function parentDirIsWriteable($path) {
		$parentPath = (substr($path, -1) == DIRECTORY_SEPARATOR) ? substr($path, 0, -1) : $path;
		$split = explode(DIRECTORY_SEPARATOR, $parentPath);
		array_pop($split);
		$parentPath = implode(DIRECTORY_SEPARATOR, $split);
		$parentPath = realpath($parentPath);
		if (!is_writable($parentPath)) $this->setDefaultEccBasePath();
		return true;
	}
	
### ADD TO MANAGER FOR FILES ###
### ADD TO MANAGER FOR FILES ###
### ADD TO MANAGER FOR FILES ###
	
}
?>
