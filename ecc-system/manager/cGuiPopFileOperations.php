<?
define('TAB_RENAME', 0);
define('TAB_COPY', 1);
define('TAB_DELETE', 2);

class GuiPopFileOperations extends GladeXml {
	
	public $done = NULL;
	
	public function __construct($gui = false) {
		if ($gui) $this->mainGui = $gui;
		$this->prepareGui();
	}
	
	private function prepareGui() {
		parent::__construct(ECC_DIR_SYSTEM.'/gui/guiPopFileOperations.glade');
		$this->signal_autoconnect_instance($this);
		$this->guiFileOperations->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		$this->guiFileOperations->set_modal(true);
		
		$this->translateGui();
		
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
	
	
	public function openRenameDialog($hideOnFail = false) {
		$this->prepareDialogFor(TAB_RENAME);
	}

	public function openCopyDialog() {
		$this->prepareDialogFor(TAB_COPY);
	}

	public function openDeleteDialog() {
		$this->prepareDialogFor(TAB_DELETE);
	}
	
	
	private function prepareDialogFor($tabName) {
		
		$this->done = NULL;
		
		$this->selectNotebookTab($tabName);
		
		// rename

		// source
		$fileExtension = ".".FACTORY::get('manager/FileIO')->get_ext_form_file(basename($this->sourceFileName));
		
		$this->renameTxtSourcePath->set_text(dirname($this->sourceFileName));
		$this->renameTxtSourceFileName->set_text(basename($this->sourceFileName));
		
		if ($this->destinationFileName){
			$renameDestinationFileName = $this->destinationFileName;
		}
		else {
			$renameDestinationFileName = $this->sourceFileName;
			$renameDestinationFileName = basename(str_ireplace($fileExtension, '', $renameDestinationFileName));
		}
		$this->renameTxtDestinationFileName->set_text($renameDestinationFileName);
		
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
	}
	
	public function show() {
		$this->guiFileOperations->show();
	}
    
	public function onCopyChooseFolder() {
		$selectedPath = FACTORY::get('manager/Os')->openChooseFolderDialog($this->copyTxtSourcePath->get_text(), 'Please select destination', false);
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
    			if ($this->mainGui) $this->mainGui->onReloadRecord(false);
    		}
    		else {
    			FACTORY::get('manager/Gui')->openDialogInfo('Error', 'could not rename file in database!', false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
    		}
    	}
    	else {
    		FACTORY::get('manager/Gui')->openDialogInfo('Error', 'could not copy file!', false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
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
    			if ($this->mainGui) $this->mainGui->onReloadRecord(false);
    		}
    		else {
    			FACTORY::get('manager/Gui')->openDialogInfo('Error', 'could not remove file in database!', false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
    		}
    	}
    	else {
    		FACTORY::get('manager/Gui')->openDialogInfo('Error', 'could not remove file!', false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
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
    			if ($this->mainGui) $this->mainGui->onReloadRecord(false);
    		}
    		else {
    			$this->done = false;
    			FACTORY::get('manager/Gui')->openDialogInfo('Error', 'could not rename file in database!', false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
    		}
    	}
    	else {
    		$this->done = false;
    		FACTORY::get('manager/Gui')->openDialogInfo('Error', "could not rename file!\nFilename allready exists!", false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
    	}
    	$this->done = true;
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
    
    public function translateGui(){
    	
    	# WINDOW
    	$this->guiFileOperations->set_title(i18n::get('file', 'winTitle'));
    	
    	# RENAME
    	$this->tabLabelRename->set_label(i18n::get('global', 'rename'));
    	$this->tabRenameHl->set_markup('<b>'.i18n::get('file', 'tabRenameHl').'</b>');
    	$this->tabRenameDesc->set_text(i18n::get('file', 'tabRenameDesc'));
    	$this->lblPathRename->set_markup('<b>'.i18n::get('global', 'filePath').'</b>');
    	$this->lblOriginalFilenameRename->set_markup('<b>'.i18n::get('file', 'lblOriginalFilename').'</b>');
    	$this->lblNewFilenameRename->set_markup('<b>'.i18n::get('file', 'lblNewFilename').'</b>');
    	$this->btnDoRename->set_text(i18n::get('file', 'btnDoRename'));
    	$this->btnDoRenameCancel->set_text(i18n::get('global', 'cancel'));
    	
    	# COPY
    	$this->tabLabelCopy->set_label(i18n::get('global', 'copy'));
    	$this->tabCopyHl->set_markup('<b>'.i18n::get('file', 'tabCopyHl').'</b>');
    	$this->tabCopyDesc->set_text(i18n::get('file', 'tabCopyDesc'));
    	$this->lblPathCopy->set_markup('<b>'.i18n::get('global', 'filePath').'</b>');
    	$this->lblOriginalFilenameCopy->set_markup('<b>'.i18n::get('file', 'lblOriginalFilename').'</b>');
    	$this->lblNewLocation->set_markup('<b>'.i18n::get('file', 'lblNewLocation').'</b>');
    	$this->btnSelectFolder->set_label(i18n::get('global', 'selectFolder'));
    	$this->btnDoCopy->set_text(i18n::get('file', 'btnDoCopy'));
    	$this->btnDoCopyCancel->set_text(i18n::get('global', 'cancel'));
    	
    	# REMOVE
    	$this->tabLabelRemove->set_label(i18n::get('global', 'remove'));
    	$this->tabRemoveHl->set_markup('<b>'.i18n::get('file', 'tabRemoveHl').'</b>');
    	$this->tabRemoveDesc->set_text(i18n::get('file', 'tabRemoveDesc'));
    	$this->lblPathRemove->set_markup('<b>'.i18n::get('global', 'filePath').'</b>');
    	$this->lblFileToRemove->set_markup('<b>'.i18n::get('file', 'lblFileToRemove').'</b>');
    	$this->deleteCheckUserImages->set_label(i18n::get('file', 'deleteCheckUserImages'));
    	$this->btnDoRemove->set_text(i18n::get('file', 'btnDoRemove'));
    	$this->btnDoRemoveCancel->set_text(i18n::get('global', 'cancel'));
    	
    }
}
?>
