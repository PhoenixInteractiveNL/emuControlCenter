<?
define('TAB_RENAME', 0);
define('TAB_COPY', 1);
define('TAB_DELETE', 2);

class GuiPopFileOperations extends GladeXml {
	
	public function __construct($gui = false) {
		if ($gui) $this->mainGui = $gui;
		$this->prepareGui();
	}
	
	private function prepareGui() {
		parent::__construct(ECC_BASEDIR.'/ecc-system/gui2/guiPopFileOperations.glade');
		$this->signal_autoconnect_instance($this);
		
		$this->guiFileOperations->set_modal(true);
		#$this->guiFileOperations->set_keep_above(true);
		$this->guiFileOperations->present();
	}
	
	public function setFdataId($fdataId) {
		if (!$fdataId) return false;
		$this->fdataId = $fdataId;
	}
	
	public function setSourceFileName($sourceFileName) {
		if (!realpath($sourceFileName)) $this->addError('Could not found source-path!');
		$this->sourceFileName = $sourceFileName;
	}

	public function setDestinationFileName($destinationFileName) {
		$this->destinationFileName = $destinationFileName;
	}
	
	
	public function openRenameDialog() {
		$this->prepareDialogFor(TAB_RENAME);
	}

	public function openCopyDialog() {
		$this->prepareDialogFor(TAB_COPY);
	}

	public function openDeleteDialog() {
		$this->prepareDialogFor(TAB_DELETE);
	}
	
	
	private function prepareDialogFor($tabName) {

		$this->selectNotebookTab($tabName);
		
		// rename

		// source
		$fileExtension = ".".FACTORY::get('manager/FileIO')->get_ext_form_file(basename($this->sourceFileName));
		
		$this->renameTxtSourcePath->set_text(dirname($this->sourceFileName));
		$this->renameTxtSourceFileName->set_text(basename($this->sourceFileName));
		
		//$renameDestinationFileName = ($this->destinationFileName) ? $this->destinationFileName : $this->sourceFileName;
		$renameDestinationFileName = $this->sourceFileName;
		$renameDestinationFileName = str_ireplace($fileExtension, '', $renameDestinationFileName);
		$this->renameTxtDestinationFileName->set_text(basename($renameDestinationFileName));
		
		// destination
		$this->renameTxtDestinationExtension->set_text(strtolower($fileExtension));
		
		// copy
		$this->copyTxtSourcePath->set_text(dirname($this->sourceFileName));
		$this->copyTxtSourceFileName->set_text(basename($this->sourceFileName));
		$copyDestinationFileName = (file_exists($this->destinationFileName)) ? $this->destinationFileName : $this->sourceFileName;
		$this->copyTxtDestinationFileName->set_text(dirname($copyDestinationFileName));
		
		// delete
		$this->removeTxtSourcePath->set_text(dirname($this->sourceFileName));
		$this->deleteTxtSourceFileName->set_text(basename($this->sourceFileName));

		$this->show();
	}
	
	private function selectNotebookTab($tabName) {
		$this->fileOperationsNotebook->set_current_page($tabName);
	}
	
	public function onNoteBookChanged($oNoteBook, $oUnknown) {
		//$currentPageId = $oNoteBook->get_current_page();
		//print "-> $currentPageId ".get_class($oNoteBook)." -> ".get_class($oUnknown)."\n\n";		
	}
	
	public function show() {
		$this->guiFileOperations->show();
	}
    
	public function onCopyChooseFolder() {
		$selectedPath = FACTORY::get('manager/Os')->openChooseFolderDialog($this->copyTxtSourcePath->get_text(), 'Please select destination');
		if ($selectedPath) $this->copyTxtDestinationFileName->set_text($selectedPath);
	}
	
    function onClickButtonCopy() {
    	
    	if (!$this->fdataId) $this->addError('fdataId missing!');
    	$fdataId = $this->fdataId;
    	
        $pathSource = $this->copyTxtSourcePath->get_text();
        if (!file_exists($pathSource)) $this->addError('source file not found!');;
        
    	$fileNameSource = $pathSource.DIRECTORY_SEPARATOR.$this->copyTxtSourceFileName->get_text();
    	$fileNameDestination = $this->copyTxtDestinationFileName->get_text();

		if (!realpath($fileNameDestination)) $this->addError('destination path not found!');;
		$fileNameDestination = realpath($fileNameDestination).DIRECTORY_SEPARATOR.basename($fileNameSource);
    	
    	$oFileOperations = FACTORY::get('manager/FileIO');
    	if ($oFileOperations->copyFile($fileNameSource, $fileNameDestination)) {
    		
    		if(LOGGER::$active) LOGGER::add('files', "file copy: ".$fileNameSource." -> ".$fileNameDestination, 0);
    		
    	    if (FACTORY::get('manager/TreeviewData')->updatePathById($fdataId, $fileNameDestination)) {
    			$this->hideWindow();
    			$this->mainGui->onReloadRecord(false);
    		}
    		else {
    			$this->addError('could not rename file in database!');
    		}
    	}
    	else {
    		$this->addError('could not copy file!');
    	}
        
        $this->copyTxtSourcePath->get_text();
        
    }
    
    function onClickButtonDelete() {
    	
    	if (!$this->fdataId) $this->addError('fdataId missing!');
    	$fdataId = $this->fdataId;
    	
    	$pathSource = $this->removeTxtSourcePath->get_text();
    	$fileName = $this->deleteTxtSourceFileName->get_text();
    	$oFileOperations = FACTORY::get('manager/FileIO');
    	if ($oFileOperations->deleteFileByFilename($pathSource.DIRECTORY_SEPARATOR.$fileName)) {
    	   	if (FACTORY::get('manager/TreeviewData')->deleteFdataById($fdataId)) {
				
    	   		if(LOGGER::$active) LOGGER::add('files', "file remove: ".$pathSource.DIRECTORY_SEPARATOR.$fileName, 0);
    	   		
    	   		# remove images from ecc-user folder
				$removeUserImages = $this->deleteCheckUserImages->get_active();
		    	if ($removeUserImages) {
		    		$fileData = FACTORY::get('manager/TreeviewData')->getFdataById($fdataId);
		    		if ($fileData['eccident'] && $fileData['crc32']) {
		    			FACTORY::get('manager/Image')->removeUserImageFolder($fileData['eccident'], $fileData['crc32']);
		    			
		    			if(LOGGER::$active) LOGGER::add('files', "-> img remove: ".$fileData['eccident']." -> ".$fileData['crc32'], 0);
		    			
		    		}
		    	}
    	   		
    			$this->hideWindow();
    			$this->mainGui->onReloadRecord(false);
    		}
    		else {
    			$this->addError('could not remove file in database!');
    		}
    	}
    	else {
    		$this->addError('could not remove file!');
    	}
    }
    
    function onClickButtonRename() {
    	
    	if (!$this->fdataId) $this->addError('fdataId missing!');
    	$fdataId = $this->fdataId;
    	
    	$pathSource = $this->renameTxtSourcePath->get_text();
    	$fileNameSource = $pathSource.DIRECTORY_SEPARATOR.$this->renameTxtSourceFileName->get_text();
    	$fileNameDestination = $pathSource.DIRECTORY_SEPARATOR.basename($this->renameTxtDestinationFileName->get_text());
    	
    	// remove double extension from filename
    	$fileExtension = $this->renameTxtDestinationExtension->get_text();
        if  ($fileExtension == substr($fileNameDestination, strlen($fileExtension)*-1)) {
    		$fileNameDestination = trim(substr($fileNameDestination, 0, strlen($fileNameDestination)-strlen($fileExtension)));
    	}
    	
    	$fileNameDestination = $fileNameDestination.$fileExtension;
    	
    	$oFileOperations = FACTORY::get('manager/FileIO');
    	if ($oFileOperations->renameFile($fileNameSource, $fileNameDestination)) {
    		if (FACTORY::get('manager/TreeviewData')->updatePathById($fdataId, $fileNameDestination)) {
    			
    			if(LOGGER::$active) LOGGER::add('files', "file rename: ".$fileNameSource." -> ".$fileNameDestination, 0);
    			
    			$this->hideWindow();
    			$this->mainGui->onReloadRecord(false);
    		}
    		else {
    			$this->addError('could not rename file in database!');
    		}
    	}
    	else {
    		$this->addError('could not rename file!');
    	}
    }
    
    public function hideWindow() {
    	$this->guiFileOperations->hide();
	}
	
	function onClickButtonCancel() {
		$this->hideWindow();
    }
    
    public function __get($widgedName) {
    	return self::get_widget($widgedName);
    }
    
    public function addError($errorMessage) {
    	print $errorMessage;
    	$this->guiFileOperations->set_sensitive(false);
    }
}
?>
