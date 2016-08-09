<?php
// interfaces
require_once('iFileParser.php');
require_once('iFileList.php');

// classes
require_once('cEccParserFileListDir.php');
require_once('cFileIO.php');
require_once('cEccParserDataProzessor.php');

class EccParser {
	
	public $gui;
	
	public $connectedMetaFilesizeCheck = false;
	
	public function __construct($eccident=false, $ini, $path, $statusbar, $statusbar_lbl_bottom, $status_obj, $gui, $connectedMetaFilesizeCheck = false) {
		
		$guiManager = FACTORY::get('manager/Gui');
		
		$this->connectedMetaFilesizeCheck = $connectedMetaFilesizeCheck;
		
		$log = '';
		
		$this->gui = $gui;
		
		if ($status_obj->is_canceled()) return false;

		
		$useExtDispatcher = $ini->getKey('USER_SWITCHES', 'useExtensionDispatcher');
		
		$dataParser = FACTORY::get('manager/EccParserMedia', dirname($path[0]));
		
		// parse only eccident, if set. else parse everything found
		$wanted_extensions = $ini->getPlatformExtensionParser($eccident);

		$all_extensions = $ini->getAllPlatformExtensionParser();
		
		clearstatcache();
		# check paths
		# unset invalid ones
		foreach($path as $idx => $aPath){
			if (!is_dir($path[$idx])) unset($path[$idx]);
		}
		
		# store paths for global reparse feature like in mame (F5)
		$dataParser->storeSelectedBasePaths($eccident, $path);
		
		$directUnseted = array();
		foreach ($wanted_extensions as $fileExtension => $void) {
			
			if (count($all_extensions[$fileExtension])>1) {

				$platformNames = join(" | ", $this->gui->ini->getPlatformsByFileExtension($fileExtension))."\n";
				
				if ($eccident) {
					$fileExtensionOutput = '*.'.$fileExtension;
					$title = sprintf(I18N::get('popup', 'romparser_fileext_problem_title%s'), '"<b>'.$fileExtensionOutput.'</b>"');
					$message = sprintf(I18N::get('popup', 'romparser_fileext_problem_msg%s%s%s%s%s%s'), '"<b>'.$fileExtensionOutput.'</b>"', '<span color="#6C6C6C">'.$platformNames.'</span>', '"<b>'.$this->gui->ecc_platform_name.'</b>"', '"'.join("\n", $path).'"', $fileExtensionOutput, $fileExtensionOutput);
					
					if (!$guiManager->openDialogConfirm($title, $message)) {
						unset($wanted_extensions[$fileExtension]);
					}
				}
				else {
					unset($wanted_extensions[$fileExtension]);
				}
				$directUnseted[] = $fileExtension;
			}
		}

		if (!$eccident && count($directUnseted)) {
			$directUnseted[] = 'zip';
			$removedExtensions = "*.".implode(", *.", $directUnseted)."";
			$title = i18n::get('popup', 'parserUnsetExtTitle');
			$message = sprintf(i18n::get('popup', 'parserUnsetExtMsg%s'), $removedExtensions);
			$guiManager->openDialogInfo($title, $message, array('dhide_parser_unset_extension_info'));
		}
		
		if (count($path) && count($wanted_extensions)) {

			// retrieve list from filesystem
			$excludedZipExtensions = false;
			if ($eccident) {
				$parserOptions = $ini->getParserOptions($eccident);
				$excludedZipExtensions = @$parserOptions['excludeExtensions'];
				if ($excludedZipExtensions){
					$excludedZipExtensions = explode(',', $excludedZipExtensions);
					foreach($excludedZipExtensions as $key => $ext) $excludedZipExtensions[$key] = trim($ext);
				}
				else $excludedZipExtensions = false;
			}
			$fileList = new EccParserFileListDir($path, $wanted_extensions, $statusbar, $statusbar_lbl_bottom, $status_obj, $excludedZipExtensions);
			$file_stats = $fileList->get_stats();
			
			if (!$useExtDispatcher) $directUnseted = array();
			
			$dbms = FACTORY::getDbms();
			
			$dbms->query('BEGIN TRANSACTION;');
			
			// parse files and write them to the dab
			$dataProzessor = new EccParserDataProzessor($dataParser, $fileList, $directUnseted, $this->gui);
			
			if ($eccident) {
				$parserOptions = $ini->getParserOptions($eccident);
				if (@$parserOptions['connectedMetaOnly']) $dataProzessor->setConnectedMetaOnlyEccident($eccident);
				if ($this->connectedMetaFilesizeCheck) $dataProzessor->setConnectedMetaFilesizeCheck($this->connectedMetaFilesizeCheck);
			}
			
			$log = $dataProzessor->parse();
			$parser_stats = $dataProzessor->get_stats();
			
			// validate older files... are all in place?
			$dataParser->optimize();
			
			$dbms->query('COMMIT TRANSACTION;');
		}
		else {
			#print "pfad oder keine extensions angegeben\n";
		}
		
		$this->log = $log;
	}
	
	public function getLog() {
		return $this->log;
	}
}
?>
