<?php
/*
 * Created on 03.10.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 class Os {
	
	private $os_env = array();
	
	public function __construct() {}
 	
	/*
	* get_os
	* ermittelt das betriebsystem, auf dem das
	* programm ausgef�hrt wird.
	* @return string
	*/
	public function getOperatingSystemInfos()
	{
		$this->os_env['OS'] = PHP_OS;
		$this->os_env['TMP'] = ($_SERVER['TMP']) ? $_SERVER['TMP'] : $_SERVER['TEMP'];
		
		if ('WIN' == strtoupper(substr($this->os_env['OS'],0,3))) {
			$this->os_env['PLATFORM'] = 'WIN';
			$this->os_env['FONT'] = 'Arial';
		}
		else {
			$this->os_env['PLATFORM'] = 'UNKNOWN';
			$this->os_env['FONT'] = 'Helvetica';
		}
		return $this->os_env;
	}
	
	/** Opens the selected media in the assigned player
	*
	*/
	public function executeFileWithProgramm($exeFileSource, $param=false, $romFileSource = "", $fileNameEscape = false, $fileName8dot3 = false, $filenameOnly = false, $noExtension = false, $enableEccScript = false) {
		
		// win98 needs "player". Otherwise, the file isnt started
		$start_ident = ($this->os_env['OS'] == 'WINNT') ? '"player"' : "";
		
		if ($theEmuCommand = $this->getEmuCommand($exeFileSource, $param, $romFileSource, $fileNameEscape, $fileName8dot3, $filenameOnly, $noExtension, $enableEccScript)){
			$emuCommand = $theEmuCommand['command'];
			$chdirDestination = $theEmuCommand['chdir'];
		}
		else return false;
			
		// Compile start command
		$command = 'start '.$start_ident.' '.$emuCommand;
		
		#print $command."\n\n".$cwdBackup;
		
		// create an backup of the curren cwd
		$cwdBackup = getcwd();
		// change dir to the programs directory
		chdir($chdirDestination);
		// execute command
		pclose(popen($command, "r"));
		// change dir back to cwdBacup!
		chdir($cwdBackup);
		
		return true;		
	}
	
	public function getEmuCommand($exeFileSource, $param=false, $romFileSource = "", $fileNameEscape = false, $fileName8dot3 = false, $filenameOnly = false, $noExtension = false, $enableEccScript = false) {
		
		// if filenameOnly set, only use the basename (name.rom) without path!
		if ($filenameOnly) {
			$chdirDestination = dirname(realpath($romFileSource));
			$romFile = basename($romFileSource); 
			$exeFile = realpath($exeFileSource);
		}
		else {
			$chdirDestination = dirname(realpath($exeFileSource));
			$romFile = realpath($romFileSource);
			$exeFile = escapeshellcmd(basename($exeFileSource));
		}
		
		if (!$chdirDestination) return false;
		if (!$romFile) return false;
		if (!$exeFile) return false;
		
		$eccScriptExeFile = '';
		if ($enableEccScript) {
			$eccLoc = FACTORY::get('manager/Validator')->getEccCoreKey('eccHelpLocations');
			$scriptExtension = $eccLoc['ECC_SCRIPT_EXTENSION'];
			if ($eccScriptFile = realpath($exeFileSource.$scriptExtension)){
				$exeFile = $eccScriptFile;
				if ($eccScriptExeFile = realpath(ECC_BASEDIR.$eccLoc['ECC_EXE_SCRIPT'])){
					$eccScriptExeFile = '"'.$eccScriptExeFile.'"';
				}
			}
		}
		
		// start romfile with removed fileextension e.g. "aof.rom" will be "aof"
		if ($noExtension) {
			$romFile = basename($romFile, '.'.FACTORY::get('manager/FileIO')->get_ext_form_file($romFile)); 
		}
		
		// escape rompath or not
		if ($fileName8dot3 && !$filenameOnly) {
			if ($this->os_env['PLATFORM']=='WIN') $romFile = $this->getEightDotThreePath($romFile); 
		}

		// escape rompath or not
		if (!$fileNameEscape) {
			if ($this->os_env['PLATFORM']=='WIN') $romFile = str_replace("&", "^&", $romFile);
		}
		else $romFile = escapeshellarg($romFile);
		
		# eccScript dont support commandline-params at the beginning!
		if($enableEccScript){
			$param = '';
			$param2 = '';
		}
		else{
			if (!$param) $param = '';
			$param2 = '';
			if (FALSE !== $startPos = strpos($param, '%ROM%')){
				$param2 = trim(substr($param, $startPos+5));
				$param = trim(substr($param, 0, $startPos));
			}
		}
		
		if ($param) $param = ' '.$param;
		if ($param2) $param2 = ' '.$param2;

		// win98 needs "player". Otherwise, the file isnt started
		$start_ident = ($this->os_env['OS'] == 'WINNT') ? '"player"' : "";
		
		$emuCommand = trim($eccScriptExeFile.' "'.$exeFile.'"'.$param.' '.$romFile.$param2);
		
		return array('command' => $emuCommand, 'chdir' => $chdirDestination);
		
	}
	
	public function openChooseFolderDialog($path=false, $title=false) {
		switch($this->os_env['PLATFORM']) {
			case 'WIN':
			case 'WINNT':
				if (FACTORY::get('manager/IniFile')->getKey('EXPERIMENTAL', 'win32Dialogs')) {
					// win32std standard windows32 style
					return $this->openWin32ChooseFolderDialog($path, $title);
				}
				else {
					// gtk2 standard style
					return $this->openGtk2ChooseFsDialog($path, $title, false, Gtk::FILE_CHOOSER_ACTION_SELECT_FOLDER);
				}
				break;				
			default:
				print "openChooseFolderDialog for OS not implemented\n";
				break;
		}
	}
	
	/**
	 * Function opens a standard windows Choose path dialog
	 * Using the pecl extension win32std
	 */
	private function openWin32ChooseFolderDialog($path=false, $title=false) {
		if (!$path) $path = '%WINDIR%';
		if (!$title) $title = '';
		while (gtk::events_pending()) gtk::main_iteration();
		$result= win_browse_folder($path, $title);
		return ($result) ? $result : false;
	}

	public function openChooseFileDialog($path=false, $title=false, $filter=array(), $defaultFilename=false) {
		switch($this->os_env['PLATFORM']) {
			case 'WIN':
			case 'WINNT':
				if (FACTORY::get('manager/IniFile')->getKey('EXPERIMENTAL', 'win32Dialogs')) {
					// win32std standard windows32 style
					return $this->openWin32ChooseFileDialog($path, $filter, $defaultFilename);
				}
				else {
					// gtk2 standard style
					return $this->openGtk2ChooseFsDialog($path, $title, $filter, Gtk::FILE_CHOOSER_ACTION_OPEN);
				}
				break;				
			default:
				print "openChooseFolderDialog for OS not implemented\n";
				break;
		}
	}
	
	/**
	 * Function opens a standard windows Choose file dialog
	 * Using the pecl extension win32std
	 */
	private function openWin32ChooseFileDialog($path=false, $filter=array(), $defaultFilename=false) {
		if (!$path) $path = '%WINDIR%';
		if (!$defaultFilename) $defaultFilename = '';
	 	$result= win_browse_file(true, realpath($path), $defaultFilename, null, $filter);
	 	return ($result) ? $result : false;
	}
	
		/*
	*
	*/
	public function openGtk2ChooseFsDialog($path=false, $title=false, $extension_limit=false, $type=Gtk::FILE_CHOOSER_ACTION_SELECT_FOLDER) {
		$title = ($title) ? $title : I18N::get('popup', 'sys_filechooser_miss_title');
		$dialog = new GtkFileChooserDialog(
			$title,
			NULL,
			$type,
			array(
				Gtk::STOCK_CANCEL,
				Gtk::RESPONSE_CANCEL,
				Gtk::STOCK_OK,
				Gtk::RESPONSE_OK
			)
		);

		#$dialog->set_modal(true);
		$dialog->set_keep_above(true);
		#$dialog->present();
		
		$dialog->set_position(Gtk::WIN_POS_CENTER);
		
		if (!realpath($path)) {
			$path = (dirname($path)) ? dirname($path) : false;
		}
		
		if ($path) $dialog->set_filename($path);
		
		if (is_array($extension_limit) && count($extension_limit)) {
			foreach ($extension_limit as $filter_name => $filter_value) {
				$filter = new GtkFileFilter();
				$filter->set_name($filter_name);
				$filter->add_pattern($filter_value);
				$dialog->add_filter($filter);
			}
			$filter2 = new GtkFileFilter();
		}
		
		$response = $dialog->run();
		if ($response === Gtk::RESPONSE_OK) {
			$path = $dialog->get_filename();
			$dialog->destroy();
			return $path;
		}
		$dialog->destroy();
		return false;
	}
	
	/*
	*
	*/
	public function launch_file($filename) {
		win_shell_execute($filename);
		return true;
	}
	
	public function executeProgramDirect($applicationPath, $action=false, $arguments=false, $directory=false) {
		win_shell_execute($applicationPath, $action, $arguments, $directory);
		return true;		
	}
	
	/**
	 * Function uses com-api to create 8.3 Winpaths
	 * @return string string in 8.3 style
	 */
	private function getEightDotThreePath($filePath) {
		if (!file_exists($filePath)) return $filePath;
		$exFSO = new COM("Scripting.FileSystemObject");
		$exFile = $exFSO->GetFile($filePath);
		$filePath = $exFile->ShortPath;
		unset($exFSO);
		return $filePath;
	}
	
	
	/**
	 * Functions create relative paths from ecc-basepath, if possible
	 * Used by eccSetRelativeDir & eccSetRelativeFile
	 *
	 * @param unknown_type $path
	 * @return unknown
	 */
	public function eccSetPathRelative($path, $fromBasepath = true) {
		if ($path && realpath($path)) {
			if ($path!="" && strpos($path, ECC_BASEDIR) === 0) {
				$offset = ($fromBasepath) ? ECC_BASEDIR_OFFSET : '';
				$path = str_replace(ECC_BASEDIR, $offset, $path);
				$path = str_replace("\\", "/", $path);
			};
		}
		return $path;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $dir
	 * @return unknown
	 */
	public function eccSetRelativeDir($dir) {
		$dir = $this->eccSetPathRelative($dir);
		if ($dir && strpos($dir, -1) !== DIRECTORY_SEPARATOR) $dir = $dir.DIRECTORY_SEPARATOR;
		return $dir;
	}
	
	public function eccSetRelativeFile($file) {
		return $this->eccSetPathRelative($file);
	}
 	
 }
 
?>
