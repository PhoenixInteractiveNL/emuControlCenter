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
		
		$dataParser = FACTORY::get('manager/EccParserMedia', $path);
		
		// parse only eccident, if set. else parse everything found
		$wanted_extensions = $ini->getPlatformExtensionParser($eccident);
		$all_extensions = $ini->getPlatformExtensionParser();
		
		$directUnseted = array();
		foreach ($wanted_extensions as $fileExtension => $eccParser) {
			if (count($all_extensions[$fileExtension])>1) {
				$platformNames = "- ".implode("\n- ", $this->gui->ini->getPlatformsByFileExtension($fileExtension));
				if ($eccident) {
					$title = 'PROBLEM FOUND';
					$message = "";
					$message .= "##################################################\n";
					$message .= "$fileExtension EXTENSION PROBLEM FOUND!\n";
					$message .= "##################################################\n\n";
					$message .= "emuControlCenter found, that more than one platform uses the same fileextension *.".$fileExtension." to search for roms!\n\n";
					$message .= $platformNames."\n\n";
					$message .= "Your selected platform is: '".$this->gui->ecc_platform_name."'\n\n";
					$message .= "The selected path is:\n'".$path."'\n\n";
					$message .= "Are you really shure, that the selected folder contains Roms for the current selected platform?\n\n";
					$dispatcherState = ($useExtDispatcher) ? 'ENABLED' : 'DISABLED';
					$message .= "The ecc fileetension dispatcher is ".$dispatcherState."\n\n";
					$message .= "##################################################\n\n";
					$message .= "-> YES: Search for '*.".$fileExtension."' in this folder / platform!\n\n";
					$message .= "-> NO: Skip the extension '*.".$fileExtension."' for this folder / platform!\n";
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
			$removedExtensions = "*.".implode(", *.", $directUnseted)."";
			$title = 'UNSET EXTENSIONS!';
			$message = "";
			$message .= "##################################################\n";
			$message .= "EXTENSION PROBLEM FOUND!\n";
			$message .= "##################################################\n\n";
			$message .= "Because you have selected '#All found', ecc have to exclude duplicate extensions from search to prevent wrong assignment in the database!\n\n";
			$message .= "emuControlCenter do not search for: ";
			$message .= "".$removedExtensions."\n\n";
			$message .= "Please select the right Platform to parse these extensions!\n\n";
			$dispatcherState = ($useExtDispatcher) ? 'ENABLED' : 'DISABLED';
			$message .= "The ecc fileetension dispatcher is ".$dispatcherState."\n\n";
			$guiManager->openDialogInfo($title, $message, array('dhide_parser_unset_extension_info'));
		}
		
		if (is_dir($path) && count($wanted_extensions)) {
			
			// retrieve list from filesystem
			$fileList = new EccParserFileListDir($path, $wanted_extensions, $statusbar, $statusbar_lbl_bottom, $status_obj);
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
