<?
define('TAB_IMPORT_ECC', 0);
define('TAB_COPY', 1);
define('TAB_DELETE', 2);

class GuiImageImporter extends GladeXml {
	
	# manager
	private $iniManager;
	
	private $hasErrors = false;
	
	public function __construct($gui = false) {
		if ($gui) $this->mainGui = $gui;
		
		$this->iniManager = FACTORY::get('manager/IniFile');
		
		$this->prepareGui();
	}
	
	private function prepareGui() {
		parent::__construct(ECC_BASEDIR.'/ecc-system/gui2/guiImageImporter.glade');
		$this->signal_autoconnect_instance($this);
		#$this->guiImageImporter->set_keep_above(true);
		$this->guiImageImporter->present();
	}
	
    
	public function openImportEccDialog() {
		$this->prepareDialogFor(TAB_IMPORT_ECC);
	}
	
	
	private function prepareDialogFor($tabName) {
		$this->selectNotebookTab($tabName);
		switch($tabName) {
			case TAB_IMPORT_ECC:
				
				$sourcePath = ($this->sourcePath) ? $this->sourcePath : realpath($this->iniManager->getKey('USER_DATA', 'base_path'));
				$this->eccEntryPathSource->set_text($sourcePath);
				
				$destinationPath = ($this->destinationPath) ? $this->destinationPath : '';
				$this->eccEntryPathDest->set_text($destinationPath);
				
				break;
		}
		$this->show();
	}
	
	public function setEccident($eccident) {
		if (!$eccident) return false;
		$this->eccident = $eccident;
	}
	
	public function setSourcePath($sourcePath) {
		if (!realpath($sourcePath)) $this->addError('Could not found source-path!');
		$this->sourcePath = $sourcePath;
	}

	public function setDestinationPath($destinationPath) {
		$this->destinationPath = $destinationPath;
	}
	
	public function onEccChooseFolderSource() {
		$selectedPath = FACTORY::get('manager/Os')->openChooseFolderDialog($this->eccEntryPathSource->get_text(), 'Please select destination');
		if ($selectedPath) $this->eccEntryPathSource->set_text($selectedPath);
	}
	
	public function onEccChooseFolderDest() {
		$selectedPath = FACTORY::get('manager/Os')->openChooseFolderDialog($this->eccEntryPathDest->get_text(), 'Please select destination');
		if ($selectedPath) $this->eccEntryPathDest->set_text($selectedPath);
	}

	

	
	public function updateProgress($counter) {
		print $counter.LF;
	}
	
	
	function onEccClickConvert() {
    	
		
		$this->hideErrors();
    	
    	$pathSource = $this->eccEntryPathSource->get_text();
        if (!is_dir($pathSource)) $this->addError('source dir not found!');
        
        $pathDest = $this->eccEntryPathDest->get_text();
        if (!is_dir($pathDest)) $this->addError('dest dir not found!');;
        
        if ($pathDest == realpath($this->iniManager->getKey('USER_DATA', 'base_path'))) $this->addError('source and destination not different!');;
        
        print $this->hasErrors." $pathSource -> $pathDest".LF;
    	
    	if (!$this->hasErrors) {
    		print "# all fine".LF;
    		FACTORY::get('manager/Image')->convertOldEccImages('gb', $pathDest, array($this, 'updateProgress'));

    	}
    	
//        $fileNameSource = $pathSource.DIRECTORY_SEPARATOR.$this->eccTxtSourceFileName->get_text();
//        $fileNameDestination = $this->eccTxtDestinationFileName->get_text();
//
//		if (!realpath($fileNameDestination)) $this->addError('destination path not found!');;
//		$fileNameDestination = realpath($fileNameDestination).DIRECTORY_SEPARATOR.basename($fileNameSource);
//    	
//    	$oFileOperations = FACTORY::get('manager/FileIO');
//    	if ($oFileOperations->copyFile($fileNameSource, $fileNameDestination)) {
//    		if (FACTORY::get('manager/TreeviewData')->updatePathById($eccident, $fileNameDestination)) {
//    			$this->hideWindow();
//    			$this->mainGui->onReloadRecord(false);
//    		}
//    		else {
//    			$this->addError('could not rename file in database!');
//    		}
//    	}
//    	else {
//    		$this->addError('could not copy file!');
//    	}
//        
//    	$this->eccTxtSourcePath->get_text();
        
    }

	private function selectNotebookTab($tabName) {
		$this->fileOperationsNotebook->set_current_page($tabName);
	}
	
	public function onNoteBookChanged($oNoteBook, $oUnknown) {
		//$currentPageId = $oNoteBook->get_current_page();
		//print "-> $currentPageId ".get_class($oNoteBook)." -> ".get_class($oUnknown)."\n\n";		
	}
    
	public function show() {
		$this->guiImageImporter->show();
	}

    
    public function hideWindow() {
    	$this->guiImageImporter->hide();
	}
	
	function onClickButtonCancel() {
		$this->hideWindow();
    }
    
    public function __get($widgedName) {
    	return self::get_widget($widgedName);
    }
    
    public function hideErrors() {
    	$this->hasErrors = false;
    	$this->errorOutput->hide();
    }
    
    public function addError($errorMessage) {
    	$this->hasErrors = true;
    	$this->errorOutput->show();
    	$this->errorOutput->set_markup('<span color="red"><b>'.$errorMessage.'</b></span>');
    }
}
?>
