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
	public function executeFileWithProgramm($filePathCommand, $filePathFile, $fileNameEscape=false, $fileName8dot3=false) {
		
		// ABS-PATH TO REL-PATH...
		$exePathFull = realpath($filePathCommand);
		if (!$exePathFull) return false;
		
		$filePathFile = realpath($filePathFile);
		if (!$filePathFile) return false;
		
		// get only the filename from the path
		$exeFileName = basename($filePathCommand);
		
		// escape rompath or not
		if ($this->os_env['PLATFORM']=='WIN' && $fileName8dot3) {
			$filePathFile = $this->getEightDotThreePath($filePathFile);
		}

		// escape rompath or not
		if (!$fileNameEscape) {
			if ($this->os_env['PLATFORM']=='WIN') {
				// escape special dos chars
				$filePathFile = str_replace("&", "^&", $filePathFile);
			}
		}
		else {
			$filePathFile = escapeshellarg($filePathFile);
		}

		// win98 needs "player". Otherwise, the file isnt started
		$start_ident = ($this->os_env['OS'] == 'WINNT') ? '"player"' : "";
		
		// Compile start command
		$command = 'start '.$start_ident.' '.escapeshellcmd($exeFileName).' '.($filePathFile);

		// create an backup of the curren cwd
		$cwdBackup = getcwd();
		// change dir to the programs directory
		chdir(dirname($exePathFull));
		// STANDARD WORKING ECC WAY!
		// execute command
		pclose(popen($command, "r"));
		// change dir back to cwdBacup!
		chdir($cwdBackup);
		
		// working faster, but not full tested!!!!
		// FACTORY::get('manager/Os')->executeProgramDirect($filePathCommand, 'open', $filePathFile);
		
		
		return true;		
	}
	
	public function openChooseFolderDialog($path=false, $title=false) {
		switch($this->os_env['PLATFORM']) {
			case 'WIN':
			case 'WINNT':
				if (FACTORY::get('manager/IniFile')->get_ecc_ini_key('EXPERIMENTAL', 'win32Dialogs')) {
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
				if (FACTORY::get('manager/IniFile')->get_ecc_ini_key('EXPERIMENTAL', 'win32Dialogs')) {
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
	private function eccSetPathRelative($path) {
		if ($path && realpath($path)) {
			//$path = realpath($path);
			if ($path!="" && strpos($path, ECC_BASEDIR) == 0) {
				$path = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $path);
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
