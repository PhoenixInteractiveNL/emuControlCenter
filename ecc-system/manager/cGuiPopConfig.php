<?
define('TAB_EMU', 0);
define('TAB_ECC', 1);
define('TAB_DAT', 2);
define('TAB_IMG', 3);
define('TAB_GUI', 4);

define('TAB_STARTUP', 5);
define('TAB_TOOL', 6);

class GuiPopConfig extends GladeXml {
	
	# stores data for saving!
	private $dataStorage = array();
	
	private $platformSelectionPath = false;
	private $initialEccident = false;
	private $selectedEccident = false;
	
	private $initialRecordDone = false;

	private $eccDataInit = false;
	private $datDataInit = false;
	private $imgDataInit = false;
	private $startupDataInit = false;
	
	private $emuInfoBuffer = array();
	
	public function __construct($gui = false) {
		if ($gui) $this->mainGui = $gui;
		$this->prepareGui();
	}
	
	public function open($tab = 'EMU', $eccident = false, $errorMessage = false) {
		//if (!in_array($tab, array('EMU', 'ECC','TOOLS'))) return false;
		if ($eccident) $this->initialEccident = $eccident;
		
		switch ($tab) {
			case 'EMU': # USED
				$tab = TAB_EMU;
			break;
			case 'ECC': # USED
				$tab = TAB_ECC;
			break;
			case 'DAT': # USED
				$tab = TAB_DAT;
			break;
			case 'IMG': # USED
				$tab = TAB_DAT;
			break;
			case 'GUI': # USED
				$tab = TAB_GUI;
			break;
			case 'STARTUP': # USED
				$tab = TAB_STARTUP;
			break;
			case 'TOOL':
				$tab = TAB_TOOL;
			break;
		}
		$this->cfgNotepad->set_current_page($tab);
		
		# refresh ini!
		$iniManager = FACTORY::get('manager/IniFile');
		$iniManager->flushIni();
		
		$this->platformIni = array();
		if ($eccident) $this->platformIni = $this->mainGui->ini->getPlatformIni($eccident);
		
		$this->selectedEccident = false;
		$this->emuSelectedExtension = false;
		$this->dataStorage = array();
		$this->platformExtensions = array();
		
		$this->initialRecordDone = false;
		
		$this->updateEmulatorData();
		$this->createExtensionTable();	
		
		# init treeview
		$this->fillPlatformTreeview();
		$this->setPlatformListSelection();
		
		if ($errorMessage) $this->configErrorLabel->set_markup('<b>'.$errorMessage.'</b>');
		
		# finaly show!!!!
		$this->show();
	}
	
	public function setPlatformListSelection() {
		# only connect once!
		if ($this->platformSelectionPath === false) {
			$this->platformSelectionObject = $this->emuTreeView->get_selection();
			$this->platformSelectionObject->connect('changed', array($this, 'emuTreeViewChanged'));
		}
		# get path for selected eccident
		if ($this->initialEccident) $this->listStore->foreach(array($this, 'getSelectedListstorePath'));
		$path = ($this->platformSelectionPath) ? $this->platformSelectionPath : 0;
		$this->platformSelectionObject->select_path($path);
	}
	
	public function getSelectedListstorePath($store, $path, $iter) {
		if ($this->initialEccident == $store->get_value($iter, 0)) {
			$this->platformSelectionPath = $store->get_path($iter);
		}
   } 
	
	private function prepareGui() {
		# get gui!
		parent::__construct(ECC_BASEDIR.'ecc-system/gui2/guipopupconfig.glade');
		$this->signal_autoconnect_instance($this);
		
		$this->guiPopConfig->set_modal(true);
		#$this->guiPopConfig->set_keep_above(true);
		$this->guiPopConfig->present();
		
		$this->gui = $this->guiPopConfig;

		# connect signals
		$this->initPlatformTreeview();
		$this->emuPathButton->connect_simple_after('clicked', array($this, 'onButtonChooseEmulator'), $this->emuAssignGlobalPath);
		$this->buttonSave->connect_simple_after('clicked', array($this, 'onButtonSave'));
		$this->buttonCancel->connect_simple_after('clicked', array($this, 'onButtonCancel'));
		$this->cfgNotepad->connect('switch-page', array($this, 'onChangeTab'));
		
		$this->emuAssignGlobalEditEccScript->connect_simple_after('clicked', array($this, 'openEccScriptEditor'));
		$this->emuAssignGlobalDeleteEccScript->connect_simple_after('clicked', array($this, 'deleteEccScript'));
		$this->emuAssignGlobalEnableEccScript->connect_simple_after('toggled', array($this, 'updateEccScriptState'));

		$this->lbl_emu_platform_name->set_text(I18N::get('popupConfig', 'lbl_emu_platform_name'));
		$this->lbl_emu_platform_category->set_text(I18N::get('popupConfig', 'lbl_emu_platform_category'));
		$this->lbl_emu_platform_category->set_text(I18N::get('popupConfig', 'lbl_emu_platform_category'));
		$this->lbl_emu_assign_path->set_text(I18N::get('popupConfig', 'lbl_emu_assign_path'));
		$this->emuPathButton->set_label(I18N::get('popupConfig', 'btn_emu_assign_path_select'));
		$this->lbl_emu_assign_parameter->set_text(I18N::get('popupConfig', 'lbl_emu_assign_parameter'));
		$this->emuAssignGlobalEscape->set_label(I18N::get('popupConfig', 'lbl_emu_assign_escape'));
		$this->emuAssignGlobalEightDotThree->set_label(I18N::get('popupConfig', 'lbl_emu_assign_eightdotthree'));
		$this->emuAssignGlobalFilenameOnly->set_label(I18N::get('popupConfig', 'lbl_emu_assign_nameonly'));
		$this->emuAssignGlobalNoExtension->set_label(I18N::get('popupConfig', 'lbl_emu_assign_noextension'));
		$this->emuAssignLabelEccScript->set_markup('<b>'.I18N::get('popupConfig', 'lbl_emu_assign_use_eccscript').'</b>');
		$this->emuAssignGlobalEditEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_create_eccscript'));
		$this->emuAssignGlobalDeleteEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_delete_eccscript'));
		
		$this->tab_label_emulators->set_label(I18N::get('popupConfig', 'tab_label_emulators'));
		$this->tab_label_general->set_label(I18N::get('popupConfig', 'tab_label_general'));
		$this->tab_label_datfiles->set_label(I18N::get('popupConfig', 'tab_label_datfiles'));
		$this->tab_label_images->set_label(I18N::get('popupConfig', 'tab_label_images'));
		$this->tab_label_colorsandfonts->set_label(I18N::get('popupConfig', 'tab_label_colorsandfonts'));
		$this->tab_label_startup->set_label(I18N::get('popupConfig', 'tab_label_startup'));
		
		$this->lbl_emu_tips->set_markup('<b>'.I18N::get('popupConfig', 'lbl_emu_tips').'</b>');
		$this->lbl_img_opt_conv->set_markup('<b>'.I18N::get('popupConfig', 'lbl_img_opt_conv').'</b>');
		
		$this->lbl_col_opt_global->set_markup('<b>'.I18N::get('popupConfig', 'lbl_col_opt_global').'</b>');
		$this->lbl_col_opt_list->set_markup('<b>'.I18N::get('popupConfig', 'lbl_col_opt_list').'</b>');
		$this->lbl_col_opt_options->set_markup('<b>'.I18N::get('popupConfig', 'lbl_col_opt_options').'</b>');
		
		$this->lbl_img_opt_conv_quality->set_label(I18N::get('popupConfig', 'lbl_img_opt_conv_quality'));
		$this->lbl_img_opt_conv_quality_def->set_markup('<b>%</b> '.sprintf(I18N::get('popupConfig', 'lbl_img_opt_conv_quality_def%s'), '80'));
		$this->lbl_img_opt_conv_minsize->set_label(I18N::get('popupConfig', 'lbl_img_opt_conv_minsize'));
		$this->lbl_img_opt_conv_minsize_def->set_markup('<b>bytes</b> '.sprintf(I18N::get('popupConfig', 'lbl_img_opt_conv_minsize_def%s'), '30000'));
		
		$this->lbl_img_otp_list_fastrefresh->set_label(I18N::get('popupConfig', 'lbl_img_otp_list_fastrefresh'));
		
		$this->emuAssignGlobalEscape->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalFilenameOnly->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalEightDotThree->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalNoExtension->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalParam->connect_simple_after('changed', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalPath->connect_simple_after('changed', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalEnableEccScript->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		
		$this->initEccData();
		$this->initDatData();
		$this->initImgData();
		$this->initGuiData();
		$this->initStartupData();
	}
	
	public function updateEmuPreview(){
		
		$emuPath = $this->emuAssignGlobalPath->get_text();
		
		$emuState = (trim($emuPath) && realpath($emuPath));
		if (!$emuState) $this->emuAssignGlobalEnableEccScript->set_active(false);
		$this->emuAssignGlobalEnableEccScript->set_sensitive($emuState);
		
		$emuParameter = $this->emuAssignGlobalParam->get_text();
		
		$romPath = 'game.rom';
		
		$emuEscape = $this->emuAssignGlobalEscape->get_active();
		$filenameOnly = $this->emuAssignGlobalFilenameOnly->get_active();
		$emuWin8char = $this->emuAssignGlobalEightDotThree->get_active();
		$noExtension = $this->emuAssignGlobalNoExtension->get_active();
		
		$enableEccScript = $this->emuAssignGlobalEnableEccScript->get_active();
		
		$this->emuAssignGlobalParam->set_sensitive(!$enableEccScript);
		
		$emuCommand = I18N::get('popupConfig', 'lbl_preview_impossible');
		if ($theEmuCommand = FACTORY::get('manager/Os')->getEmuCommand($emuPath, $emuParameter, $romPath, $emuEscape, $emuWin8char, $filenameOnly, $noExtension, $enableEccScript)){
			$emuCommand = $theEmuCommand['command'];
			$toolsFolder = realpath('../ecc-tools/')."\\";
			$emuCommand = str_replace($toolsFolder, '', $emuCommand);
			$emuFolder = dirname($emuPath)."\\";
			$emuCommand = str_replace($emuFolder, '', $emuCommand);
		}
		$this->lbl_emu_assign_parameter_preview->set_markup('<span size="small" weight="ultralight">'.$emuCommand.'</span>');
	}
	
	public function openEccScriptEditor(){
		$path = $this->emuAssignGlobalPath->get_text();
		if (realpath($path)){
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');
			FACTORY::get('manager/Os')->executeProgramDirect(ECC_BASEDIR.$eccLoc['ECC_EXE_SCRIPT_EDITOR'], false, '"'.$path.$eccLoc['ECC_SCRIPT_EXTENSION'].'"');
		}
		elseif(!trim($path)) FACTORY::get('manager/Gui')->openDialogInfo('ERROR', I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript_error'));
		else FACTORY::get('manager/Gui')->openDialogInfo('ERROR', I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript_error_notfound'));
	}
	
	public function deleteEccScript(){
		$path = $this->emuAssignGlobalPath->get_text();
		if ($this->eccScriptExists($path)){
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');
			$msg = sprintf(I18N::get('popupConfig', 'msg_emu_assign_delete_eccscript%s'), basename($path).$eccLoc['ECC_SCRIPT_EXTENSION']);
			if (FACTORY::get('manager/Gui')->openDialogConfirm('', $msg)){
				unlink($path.$eccLoc['ECC_SCRIPT_EXTENSION']);
				$this->updateEccScriptState();
			}
		}
	}
	
	public function eccScriptExists($path){
		if (!trim($path)) return false;
		$mngrValidator = FACTORY::get('manager/Validator');
		$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');
		return realpath($path.$eccLoc['ECC_SCRIPT_EXTENSION']);
	}
	
	public function activateEccScript($state){
		$state = $this->emuAssignGlobalEnableEccScript->set_active($state);
		$this->updateEccScriptState();
	}
	
	public function updateEccScriptState(){
		$state = $this->emuAssignGlobalEnableEccScript->get_active();
		$this->emuAssignLabelEccScript->set_sensitive($state);
		$this->emuAssignGlobalEditEccScript->set_sensitive($state);
		$this->emuAssignGlobalDeleteEccScript->set_sensitive($state);
		
		$this->emuAssignGlobalEditEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_create_eccscript'));
		$this->emuAssignGlobalDeleteEccScript->hide();
		if ($this->eccScriptExists($this->emuAssignGlobalPath->get_text())) {
			$this->emuAssignGlobalEditEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript'));
			$this->emuAssignGlobalDeleteEccScript->show();
		}
	}

	private function initPlatformTreeview() {
		# init store
		$this->listStore = new GtkListStore(Gtk::TYPE_STRING,Gtk::TYPE_STRING);
		# used renderer
		$rendererText = new GtkCellRendererText();
		# set index (invisible)
		$cIndex = new GtkTreeViewColumn('index', $rendererText, 'text', 0);
		$cIndex->set_visible(false);
		# platform name
		$cPlatform = new GtkTreeViewColumn('platform', $rendererText, 'text', 1);
		# add
		$this->emuTreeView->set_model($this->listStore);
		
		$this->emuTreeView->append_column($cIndex);
		$this->emuTreeView->append_column($cPlatform);
		
	}
	
	private function fillPlatformTreeview() {
		$this->listStore->clear();
		$platforms = $this->mainGui->ini->getPlatformNavigation(false, false, true);
		foreach ($platforms as $index => $imagePath) {
			if ($index == 'null') continue;
			$this->listStore->append(array($index, $imagePath));
		}
	}
	
	public function emuTreeViewChanged($objSelection) {
		$this->storeTempEmulatorData();
		$this->emuSelectedExtension = false;
		$platformEccident = false;
		$platformName = false;
		list($model, $iter) = $objSelection->get_selected();
		if ($iter) {
			# store path for selection!
			$this->platformSelectionPath = $model->get_path($iter);
			$platformEccident = $model->get_value($iter, 0);
			$platformName = $model->get_value($iter, 1);
			if ($platformEccident) {
				$this->selectedEccident = $platformEccident;
				$this->platformIni = $this->mainGui->ini->getPlatformIni($this->selectedEccident);
				$this->platformExtensions = $this->getPlatformExtensions($this->selectedEccident);
				
				// update
				$this->updateEmulatorData();
				$this->createExtensionTable();
				$this->updateEmuPreview();	
			}
		}
	}
	
	public function getPlatformExtensions($eccident) {
		
		$platformIni = $this->mainGui->ini->getPlatformIni($eccident);
		$extensions = $platformIni['EXTENSIONS'];
		
		$fileExtLabels = array();
		foreach ($extensions as $fileExt => $state) {
			if ($state) $fileExtLabels[] = $fileExt;
		}
		sort($fileExtLabels);
		array_unshift($fileExtLabels, 'ALT2');
		array_unshift($fileExtLabels, 'ALT1');
		array_unshift($fileExtLabels, 'GLOBAL');
		return $fileExtLabels;
	}
	
	public function updateEmulatorData() {
		
		$this->configErrorLabel->set_text('');
		
		# get needed idents
		$eccident = $this->selectedEccident;
		if (!$eccident) return false;
		$fileExt = $this->emuSelectedExtension;
		if (!$fileExt) $fileExt = 'GLOBAL';
		
		# Platform data
		$storagePlatform = @$this->dataStorage[$eccident]['PLATFORM'];
		$ini = (isset($this->platformIni)) ? $this->platformIni : false;
		
		# get data
		$platformActive = (!isset($ini['PLATFORM']['active'])) ? true : $ini['PLATFORM']['active'];
		$activePlatform = ($ini && !isset($storagePlatform['active'])) ? $platformActive : $storagePlatform['active'];
		$name = ($ini && !isset($storagePlatform['name'])) ? @$ini['PLATFORM']['name'] : $storagePlatform['name'];
		$category = ($ini && !isset($storagePlatform['category'])) ? @$ini['PLATFORM']['category'] : $storagePlatform['category'];

		# set data to fields
		$this->emuPlatformLabel->set_markup('<b>'.sprintf(I18N::get('popupConfig', 'lbl_emu_hdl%s%s'), $name, $eccident).'</b>');
		
		$this->emuPlatformActiveState->set_active($activePlatform);
		$this->emuPlatformName->set_text($name);
		$this->emuPlatformCategory->set_text($category);
		
		# Emulator data
		# get ini for current fileextension
		$storageEmu = false;
		if (isset($this->dataStorage[$eccident]['EMU'][$fileExt]) && count($this->dataStorage[$eccident]['EMU'][$fileExt])) {
			$storageEmu = $this->dataStorage[$eccident]['EMU'][$fileExt];
		}
		$iniEmu = (isset($this->platformIni['EMU.'.$fileExt])) ? $this->platformIni['EMU.'.$fileExt] : false;
		
		# get data
		if ($fileExt == 'GLOBAL') {
			$activeEmu = true;
			$this->emuAssignGlobalActive->set_sensitive(false);
		}
		else {
			$activeEmu = ($iniEmu && !isset($storageEmu['active'])) ? @$iniEmu['active'] : $storageEmu['active'];
			$this->emuAssignGlobalActive->set_sensitive(true);
		}
		
		$path = ($iniEmu && !isset($storageEmu['path'])) ? @$iniEmu['path'] : $storageEmu['path'];
		$param = ($iniEmu && !isset($storageEmu['param'])) ? @$iniEmu['param'] : $storageEmu['param'];
		if (!trim($param)) $param = "%ROM%";
		
		# set default on!
		$escapeState = (!isset($iniEmu['escape'])) ? true : $iniEmu['escape'];
		$escape = (!isset($storageEmu['escape'])) ? $escapeState : $storageEmu['escape'];
		
		# set default off!
		$eightDotThreeState = (!isset($iniEmu['win8char'])) ? false : $iniEmu['win8char'];
		$eightDotThree = (!isset($storageEmu['win8char'])) ? $eightDotThreeState : $storageEmu['win8char'];
		
		# set default off!
		$filenameOnlyState = (!isset($iniEmu['filenameOnly'])) ? false : $iniEmu['filenameOnly'];
		$filenameOnly = (!isset($storageEmu['filenameOnly'])) ? $filenameOnlyState : $storageEmu['filenameOnly'];
		
		# set default off!
		$noExtensionState = (!isset($iniEmu['noExtension'])) ? false : $iniEmu['noExtension'];
		$noExtension = (!isset($storageEmu['noExtension'])) ? $noExtensionState : $storageEmu['noExtension'];

		# set default off!
		$enableEccScriptState = (!isset($iniEmu['enableEccScript'])) ? false : $iniEmu['enableEccScript'];
		$enableEccScript = (!isset($storageEmu['enableEccScript'])) ? $enableEccScriptState : $storageEmu['enableEccScript'];
		
		
		# set data to fields
		//$this->emuAssignLabel->set_markup('<b>Emulator assignment ('.$fileExt.')</b>');
		$this->emuAssignLabel->set_markup('<b>'.sprintf(I18N::get('popupConfig', 'lbl_emu_assign_hdl%s'), $fileExt).'</b>');
		
		if (!isset($this->emuInfoBuffer[$eccident])) {
			$spacer = str_repeat('-', 80)."\n";
			$buffer = new GtkTextBuffer();
			if (file_exists('infos/ecc_platform_'.$eccident.'_emu.ini')) {
				$iniManager = FACTORY::get('manager/IniFile');
				$emuData = $iniManager->parse_ini_file_quotes_safe('infos/ecc_platform_'.$eccident.'_emu.ini');
				$text = '';
				foreach($emuData as $section => $sectionData) {
					$text .= trim($sectionData['name'])."\n";
					$text .= $spacer;
					foreach($sectionData as $key => $value) {
						if ($key == 'name') continue;
						if (trim($value)) $text .= ucfirst($key).": ".trim($value)."\n";
					}
					$text .= "\n";
				}
			}
			else {
				$text = I18N::get('popupConfig', 'emu_info_nodata')."\n";
			}
			$text .= $spacer.I18N::get('popupConfig', 'emu_info_footer')."\n";

			$buffer->set_text($text);
			$this->emuInfoBuffer[$eccident] = $buffer;
		}
		$this->emuInfo->set_buffer($this->emuInfoBuffer[$eccident]);
		
		$this->emuAssignGlobalActive->set_active($activeEmu);
		$this->emuAssignGlobalPath->set_text($path);
		$this->emuAssignGlobalParam->set_text($param);
		$this->emuAssignGlobalEscape->set_active($escape);
		$this->emuAssignGlobalEightDotThree->set_active($eightDotThree);
		$this->emuAssignGlobalFilenameOnly->set_active($filenameOnly);
		$this->emuAssignGlobalNoExtension->set_active($noExtension);
		$this->emuAssignGlobalEnableEccScript->set_active($enableEccScript);
		
		$this->updateEccScriptState();
	}
	
	public function storeTempEmulatorData($onlyReturn = false) {
		
		# get needed idents
		$eccident = $this->selectedEccident;
		if (!$eccident) return false;
		$fileExt = $this->emuSelectedExtension;
		if (!$fileExt) $fileExt = 'GLOBAL';
				
		if (!isset($this->dataStorage[$eccident]['EMU'])) {
			$availableExtensions = $this->getPlatformExtensions($eccident);
			foreach ($availableExtensions as $extension) {
				if (!isset($this->dataStorage[$eccident]['EMU'][$extension])) $this->dataStorage[$eccident]['EMU'][$extension] = array();
			}
		}
		
		# store platform
		$this->dataStorage[$eccident]['PLATFORM']['active'] = $this->emuPlatformActiveState->get_active();
		$this->dataStorage[$eccident]['PLATFORM']['eccident'] = $eccident;
		$this->dataStorage[$eccident]['PLATFORM']['name'] = $this->emuPlatformName->get_text();
		$this->dataStorage[$eccident]['PLATFORM']['category'] = $this->emuPlatformCategory->get_text();

		# store emulator
		$this->dataStorage[$eccident]['EMU'][$fileExt]['active'] = $this->emuAssignGlobalActive->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['path'] = $this->emuAssignGlobalPath->get_text();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['param'] = $this->emuAssignGlobalParam->get_text();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['escape'] = $this->emuAssignGlobalEscape->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['win8char'] = $this->emuAssignGlobalEightDotThree->get_active();
		
		$this->dataStorage[$eccident]['EMU'][$fileExt]['filenameOnly'] = $this->emuAssignGlobalFilenameOnly->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['noExtension'] = $this->emuAssignGlobalNoExtension->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['enableEccScript'] = $this->emuAssignGlobalEnableEccScript->get_active();
		
		
		
		
		# only needed for the initial checksum
		if ($onlyReturn) return $this->dataStorage;
	}
	
	public function getInitialEmulatorData() {
		return $this->storeTempEmulatorData(true);
	}
	
	private function createExtensionTable() {
		# get needed idents
		$eccident = $this->selectedEccident;
		if (!$eccident) return false;
		$extensions = $this->platformExtensions;
		
		# how many extensions in one row?
		$resultsPerRow = 10;
		
		$frameChild = $this->emuExtensionSelector->child;
		if ($frameChild) $this->emuExtensionSelector->remove($frameChild);
		
		$table = new GtkTable();
		$this->emuExtensionSelector->add($table);
		if (!count($extensions)) return $table;

		$cntTotal = count($extensions);
		if ($cntTotal) {
			$cntRow = ceil($cntTotal/$resultsPerRow);
			$currentIndex = 0;
			for ($row=0; $row<$cntRow; $row++) {
				for ($col=0; $col<$resultsPerRow; $col++) {
					$fileExt = (isset($extensions[$currentIndex])) ? $extensions[$currentIndex] : '';
					
					$data = false;
					if (isset($this->dataStorage[$eccident]['EMU'][$fileExt]) && count($this->dataStorage[$eccident]['EMU'][$fileExt])) {
						$data = $this->dataStorage[$eccident]['EMU'][$fileExt];
					}
					elseif (isset($this->platformIni['EMU.'.$fileExt])) {
						$data = $this->platformIni['EMU.'.$fileExt];
					}
					
					# current selection
					$fileExtLabel = '<b>'.$fileExt.'</b>';
					
					$hilight = '';
					if ((!$this->emuSelectedExtension && $fileExt == 'GLOBAL') || ($fileExt && $this->emuSelectedExtension == $fileExt)) {
						$hilight = 'underline="single"';
					}
					if ($fileExt == 'GLOBAL') $data['active'] = true;

					# show state
					$bgColor = '#D6DCF4';
					if (!$data || !@$data['active']) {
						$fileExtBold = $fileExt;
						if ($data && (isset($data['path']) && trim($data['path']))) {
							$fileExtLabel = '<span color="#999999" '.$hilight.'><b>'.$fileExt.'</b></span>';
						}
						else {
							$fileExtLabel = '<span color="#999999" '.$hilight.'>'.$fileExt.'</span>';
						}
						$bgColor = '#eeeeee';
					}
					else {
						if (@$data['path'] && file_exists(realpath($data['path']))) {
							$fileExtLabel = '<span color="#005500" '.$hilight.'><b>'.$fileExt.'</b></span>';
							$bgColor = '#D7F4D6';
						}
						elseif (@$data['path'] && !file_exists(realpath($data['path']))) {
							$fileExtLabel = '<span color="#770000" '.$hilight.'><b>'.$fileExt.'</b></span>';
							$bgColor = '#F4D6D6';
						}
					}
					
					$widged = new GtkLabel();
					$widged->set_markup($fileExtLabel);
					
					if ($fileExt) {
						$oEvent = new GtkEventBox();
						$oEvent->set_size_request(50, 21);
						$oEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($bgColor));
						$oEvent->connect_simple_after('button-press-event', array($this, 'onFileExtSelect'), $fileExt);
						$oEvent->add($widged);
						$widged = $oEvent;
					}
					$table->attach($widged, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);
					$currentIndex++;
				}
			}
		}
		$table->set_homogeneous(true);
		$table->set_row_spacings(5);
		$table->set_col_spacings(5);
		
		$this->emuExtensionSelector->show_all();
		
	}
	
	public function onFileExtSelect($emuExtension) {
		# save data to temp storage
		$this->storeTempEmulatorData();
		$this->emuSelectedExtension = $emuExtension;
		$this->createExtensionTable();
		$this->updateEmulatorData();
	}
	
	public function onButtonChooseEmulator($gtkEntry) {
		$iniManager = FACTORY::get('manager/IniFile');
		
		# get path from filesystem
		$path = $gtkEntry->get_text();
		if ($path && realpath($path)) {
			$path = realpath($path);
		}
		else {
			$path = realpath($iniManager->getHistoryKey('path_emuconfig_last'));
		}
		$title = sprintf(I18N::get('popupConfig', 'title_emu_assign_path_select_popup%s'), $this->selectedEccident);
		$newPath = FACTORY::get('manager/Os')->openChooseFileDialog($path, $title);
		
		if ($newPath && realpath($newPath)) {
			$gtkEntry->set_text($newPath);
			$this->emuAssignGlobalActive->set_active(true);
			$iniManager->storeHistoryKey('path_emuconfig_last', realpath($newPath));
			
			# eccScript available?
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');
			if($eccScriptFile = realpath($newPath.$eccLoc['ECC_SCRIPT_EXTENSION'])){
				$title = I18N::get('popupConfig', 'title_emu_assign_found_eccscript');
				$msg = sprintf(I18N::get('popupConfig', 'msg_emu_assign_found_eccscript%s'), basename($eccScriptFile));
				
				$test = file($eccScriptFile);
				$preview = array();
				foreach($test as $index => $line){
					if ($index >= 15 || substr(trim($line), 0, 1) != ';') break; # only read first 10 lines
					if (!isset($hilight) && trim($line) != ';') {
						$hilight = true;
						$preview[] = '<b>'.trim(substr(trim($line), 1)).'</b>';
					}
					else $preview[] = trim(substr(trim($line), 1));
				}
				$previewString = join("\n", $preview);
				
				if ($previewString) $msg .= "\n\n<b>".I18N::get('popupConfig', 'title_emu_found_eccscript_preview')."</b>\n".$previewString;
				else $msg .= "\n\n<b>".I18N::get('popupConfig', 'title_emu_found_eccscript_nopreview')."</b>";				
			
				$this->activateEccScript(FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg));
			}
			
			$this->storeTempEmulatorData();
			$this->createExtensionTable();
		}
	}
	
	public function onButtonSave() {
		$this->saveData();
		
		$originalMd5 = md5(print_r($this->globalIni, true));
		
		$this->storeEccData();
		$this->storeDatData();
		$this->storeImgData();
		$this->storeGuiData();
		$this->storeStartupData();
		
		$newMd5 = md5(print_r($this->globalIni, true));
		
		if ($originalMd5 !== $newMd5) {
			$iniManager = FACTORY::get('manager/IniFile');
			$iniManager->storeIniGlobal($this->globalIni);
			$iniManager->storeGlobalFont($this->globalIni['GUI_COLOR']['global_font_type']);
			
			$title = I18N::get('popupConfig', 'title_popup_save');
			$msg = I18N::get('popupConfig', 'msg_popup_save');
			if (FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg)) {
				FACTORY::get('manager/Os')->executeProgramDirect(dirname(__FILE__).'/../../ecc.exe', 'open', '/fastload');
				Gtk::main_quit();
			}
		}
	}
	
	public function onButtonCancel() {
		$this->dataStorage = array();
		$this->hide();
	}
	
	public function saveData($hidePopup = true) {
		$this->storeTempEmulatorData();
		if (!$this->writePlatformIni()) return false;

		$iniManager = FACTORY::get('manager/IniFile');
		$iniManager->flushIni();
		$this->mainGui->update_treeview_nav();
		$categories = $iniManager->getPlatformCategories();
		$this->mainGui->dd_pf_categories->fill($categories, 0);
		
		if ($hidePopup) $this->hide();
	}
	
	
	public function createPlatformIni($platformData) {
		$config = array();
		# create platform section
		foreach($platformData['PLATFORM'] as $key => $value) {
			$config['PLATFORM'][$key] = '"'.$value.'"';
		}
		# create emu section
		foreach($platformData['EMU'] as $fileExt => $value) {
			if (count($value)) {
				foreach($value as $key => $value) $config['EMU.'.$fileExt][$key] = '"'.$value.'"';
			}
			else {
				$config['EMU.'.$fileExt] = array();
			}
		}
		return $config;
	}
	
	public function writePlatformIni() {
		# check and save changes
		$iniManager = FACTORY::get('manager/IniFile');
		foreach($this->dataStorage as $eccident => $platformData) {
			if (!$eccident) continue;
			
			$ini = $this->mainGui->ini->getPlatformIni($eccident);			
			$iniExt = $this->getPlatformExtensions($eccident);
			foreach ($iniExt as $test => $ext) {
				if (isset($platformData['EMU'][$ext]) && count($platformData['EMU'][$ext])) {
					# all fine
				}
				elseif (isset($ini['EMU.'.$ext])) {
					$platformData['EMU'][$ext] = $ini['EMU.'.$ext];
				}
				else {
					$platformData['EMU'][$ext] = array();
				}
			}
			
			$platformIni = $this->createPlatformIni($platformData);
			if (!$platformIni) return false;
			$iniManager->storeIniPlatformUser($eccident, $platformIni);
			
		}
		return true;
	}

	public function onChangeTab($notepad, $pointer, $tabId) {
	}
	
	public function initEccData() {
		if (!$this->eccDataInit) $this->eccDataInit = true;
		else return true;
		
		// set i18n wordings
	
		$this->lbl_ecc_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_hdl').'</b>');
		$this->lbl_ecc_userfolder->set_text(I18N::get('popupConfig', 'lbl_ecc_userfolder'));
		$this->confEccUserPathButton->set_label(I18N::get('popupConfig', 'lbl_ecc_userfolder_button'));
		
		$this->lbl_ecc_otp_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_otp_hdl').'</b>');
		$this->lbl_ecc_opt_detail_pp->set_text(I18N::get('popupConfig', 'lbl_ecc_opt_detail_pp'));
		$this->lbl_ecc_opt_list_pp->set_text(I18N::get('popupConfig', 'lbl_ecc_opt_list_pp'));
		$this->lbl_ecc_opt_language->set_text(I18N::get('popupConfig', 'lbl_ecc_opt_language'));
		
		$this->confEccStatusLogCheck->set_label(I18N::get('popupConfig', 'confEccStatusLogCheck'));
		$this->confEccStatusLogOpen->set_label(I18N::get('popupConfig', 'confEccStatusLogOpen'));
		
		#$this->lbl_ecc_startup_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_startup_hdl').'</b>');
		#$this->cfgEccStartupConf->set_label(I18N::get('popupConfig', 'btn_ecc_startup'));
		
		$iniManager = FACTORY::get('manager/IniFile');
		$this->globalIni = $iniManager->getIniGlobalWithoutPlatforms();
		unset($this->globalIni['NAVIGATION']);
		
		$user_folder = $iniManager->getKey('USER_DATA', 'base_path');
		if (!$user_folder || !realpath($user_folder)) {
			$this->configErrorLabel->set_markup('<b>Userfolder not valid!!!</b>');
		}
		$this->confEccUserPath->set_text($user_folder);
		
		$this->languages = $iniManager->getLanguageFromI18Folders();
		$languages = I18n::translateArray('languages', $this->languages, true);
		
		$selectedLanguage =  $iniManager->getKey('USER_DATA', 'language');
		$languageId =  array_search($selectedLanguage, $this->languages);
		$void = new IndexedCombobox($this->confEccLanguage, false, $languages, false, $languageId);

		$this->confEccUserPathButton->connect_simple('clicked', array($this, 'onSelectUserPath'));
		
		$this->perPageDetail = array(
			'10',
			'25',
			'50',
			'100',
		);
		$selected =  $iniManager->getKey('USER_SWITCHES', 'show_media_pp');
		if ($selected > 100) $selected = 100;
		$index =  array_search($selected, $this->perPageDetail);
		$void = new IndexedCombobox($this->cfgEccDetailPerPage, false, $this->perPageDetail, false, $index);
		
		$this->perPage = array(
			'10',
			'25',
			'50',
			'100',
			'250',
			'500',
			'1000',
		);
		$selected =  $iniManager->getKey('USER_SWITCHES', 'media_perpage_list');
		$index =  array_search($selected, $this->perPage);
		$void = new IndexedCombobox($this->cfgEccListPerPage, false, $this->perPage, false, $index);

		$mngrValidator = FACTORY::get('manager/Validator');
		$eccHelpLocations = $mngrValidator->getEccCoreKey('eccHelpLocations');
		#$this->cfgEccStartupConf->connect_simple('clicked', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), ECC_BASEDIR.$eccHelpLocations['ECC_EXE_START'], false, '/config');
		
		$logDetails = $iniManager->getKey('USER_SWITCHES', 'log_details');
		$this->confEccStatusLogCheck->set_active($logDetails);
		
		$logFileDir = ECC_BASEDIR.$eccHelpLocations['LOG_DIR'];
		if (is_dir($logFileDir)) {
			$this->confEccStatusLogOpen->connect_simple('clicked', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $logFileDir, false);
		}
		else {
			$this->confEccStatusLogOpen->set_sensitive(false);
		}
	}

			
	public function storeEccData($hidePopup = true) {
		
		# USER_DATA
		$this->globalIni['USER_DATA']['base_path'] = $this->confEccUserPath->get_text();
		#$this->globalIni['USER_DATA']['language'] = $this->languages[$this->confEccLanguage->get_active_text()];
		$this->globalIni['USER_DATA']['language'] = $this->languages[$this->confEccLanguage->get_active()];
		
		# USER_SWITCHES
		$this->globalIni['USER_SWITCHES']['show_media_pp'] = $this->perPageDetail[$this->cfgEccDetailPerPage->get_active_text()];
		$this->globalIni['USER_SWITCHES']['media_perpage_list'] = $this->perPage[$this->cfgEccListPerPage->get_active_text()];
		$this->globalIni['USER_SWITCHES']['log_details'] = $this->confEccStatusLogCheck->get_active();

		
	}
	
	
	public function initGuiData() {
		if (!$this->guiDataInit) $this->guiDataInit = true;
		else return true;
		
		$this->lbl_ecc_colfont_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_colfont_hdl').'</b>');
		$this->lbl_ecc_colfont_font_list->set_text(I18N::get('popupConfig', 'lbl_ecc_colfont_font_list'));
		$this->cfgEccColorListFont->set_title(I18N::get('popupConfig', 'title_ecc_colfont_font_list_popup'));
		#$this->lbl_ecc_colfont_font_global->set_text(I18N::get('popupConfig', 'lbl_ecc_colfont_font_global'));
		$this->cfgEccColorListFontGlobal->set_title(I18N::get('popupConfig', 'title_ecc_colfont_font_global'));
		
		$iniManager = FACTORY::get('manager/IniFile');
		
		$colorBg = $iniManager->getKey('GUI_COLOR', 'treeview_color_bg');
		if (!$colorBg) $colorBg = '#FFFFFF';
		$this->cfgEccColorListBg->set_color(GdkColor::parse($colorBg));

		$colorBg1 = $iniManager->getKey('GUI_COLOR', 'treeview_color_row1');
		if (!$colorBg1) $colorBg1 = '#FFFFFF';
		$this->cfgEccColorListBg1->set_color(GdkColor::parse($colorBg1));

		$colorBg2 = $iniManager->getKey('GUI_COLOR', 'treeview_color_row2');
		if (!$colorBg2) $colorBg2 = '#EEEEEE';
		$this->cfgEccColorListBg2->set_color(GdkColor::parse($colorBg2));			

		$colorBgImages = $iniManager->getKey('GUI_COLOR', 'treeview_color_bg_images');
		if (!$colorBgImages) $colorBgImages = '#FFFFFF';
		$this->cfgEccColorListBgImages->set_color(GdkColor::parse($colorBgImages));
		
		$colorText = $iniManager->getKey('GUI_COLOR', 'treeview_color_text');
		if (!$colorText) $colorText = '#000000';
		$this->cfgEccColorListText->set_color(GdkColor::parse($colorText));	
		
		$colorText = $iniManager->getKey('GUI_COLOR', 'treeview_color_bg_selection');
		if (!$colorText) $colorText = '#aabbcc';
		$this->cfgEccColorListSelectionBg->set_color(GdkColor::parse($colorText));	
		
		$colorText = $iniManager->getKey('GUI_COLOR', 'treeview_color_fg_selection');
		if (!$colorText) $colorText = '#000000';
		$this->cfgEccColorListSelectionText->set_color(GdkColor::parse($colorText));	
		
		$font = $iniManager->getKey('GUI_COLOR', 'treeview_font_type');
		if (!$font) $font = 'Arial 10';
		$this->cfgEccColorListFont->set_font_name($font);

		$font = $iniManager->getKey('GUI_COLOR', 'global_font_type');
		if (!$font) $font = 'Arial 10';
		$this->cfgEccColorListFontGlobal->set_font_name($font);
		
		$colorText = $iniManager->getKey('GUI_COLOR', 'option_select_bg_1');
		if (!$colorText) $colorText = '#CCDDEE';
		$this->cfgEccColorOptSelectBg1->set_color(GdkColor::parse($colorText));	

		$colorText = $iniManager->getKey('GUI_COLOR', 'option_select_bg_2');
		if (!$colorText) $colorText = '#DDEEFF';
		$this->cfgEccColorOptSelectBg2->set_color(GdkColor::parse($colorText));

		$colorText = $iniManager->getKey('GUI_COLOR', 'option_select_bg_active');
		if (!$colorText) $colorText = '#00BB00';
		$this->cfgEccColorOptSelectBgActive->set_color(GdkColor::parse($colorText));
		
		$colorText = $iniManager->getKey('GUI_COLOR', 'option_select_text');
		if (!$colorText) $colorText = '#000000';
		$this->cfgEccColorOptSelectText->set_color(GdkColor::parse($colorText));
		
		
	}
	public function storeGuiData($hidePopup = true) {
		
		# treeview
		$this->globalIni['GUI_COLOR']['treeview_color_bg'] = $this->getGdkColorHex($this->cfgEccColorListBg->get_color());
		$this->globalIni['GUI_COLOR']['treeview_color_row1'] = $this->getGdkColorHex($this->cfgEccColorListBg1->get_color());
		$this->globalIni['GUI_COLOR']['treeview_color_row2'] = $this->getGdkColorHex($this->cfgEccColorListBg2->get_color());
		$this->globalIni['GUI_COLOR']['treeview_color_bg_images'] = $this->getGdkColorHex($this->cfgEccColorListBgImages->get_color());
		
		
		$this->globalIni['GUI_COLOR']['treeview_color_text'] = $this->getGdkColorHex($this->cfgEccColorListText->get_color());
		
		$this->globalIni['GUI_COLOR']['treeview_color_bg_selection'] = $this->getGdkColorHex($this->cfgEccColorListSelectionBg->get_color());
		$this->globalIni['GUI_COLOR']['treeview_color_fg_selection'] = $this->getGdkColorHex($this->cfgEccColorListSelectionText->get_color());
		
		# option
		$this->globalIni['GUI_COLOR']['option_select_bg_1'] = $this->getGdkColorHex($this->cfgEccColorOptSelectBg1->get_color());
		$this->globalIni['GUI_COLOR']['option_select_bg_2'] = $this->getGdkColorHex($this->cfgEccColorOptSelectBg2->get_color());
		$this->globalIni['GUI_COLOR']['option_select_bg_active'] = $this->getGdkColorHex($this->cfgEccColorOptSelectBgActive->get_color());
		$this->globalIni['GUI_COLOR']['option_select_text'] = $this->getGdkColorHex($this->cfgEccColorOptSelectText->get_color());
		
		# fonts
		$this->globalIni['GUI_COLOR']['treeview_font_type'] = $this->cfgEccColorListFont->get_font_name();
		$this->globalIni['GUI_COLOR']['global_font_type'] = $this->cfgEccColorListFontGlobal->get_font_name();
	}
	
	public function getGdkColorHex($colorObject) {
		$r = sprintf("%02s", dechex((int)($colorObject->red * 255 / 65535)));
		$g = sprintf("%02s", dechex((int)($colorObject->green * 255 / 65535)));
		$b = sprintf("%02s", dechex((int)($colorObject->blue * 255 / 65535)));
		return strtoupper('#'.$r.$g.$b);
	}
	
	
	public function initImgData() {
		if (!$this->imgDataInit) $this->imgDataInit = true;
		else return true;
		
		$this->lbl_img_otp_list_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_img_otp_list_hdl').'</b>');
		$this->lbl_img_opt_list_imagesize->set_text(I18N::get('popupConfig', 'lbl_img_otp_list_imagesize'));
		$this->lbl_img_opt_list_aspectratio->set_text(I18N::get('popupConfig', 'lbl_img_otp_list_aspectratio'));
		
		$iniManager = FACTORY::get('manager/IniFile');
		
		$this->imageSizes = array(
			'30x20',
			'60x40',
			'120x80',
			'240x160',
		);
		$selected =  $iniManager->getKey('USER_SWITCHES', 'image_mainview_size');
		$index =  array_search($selected, $this->imageSizes);
		$void = new IndexedCombobox($this->cfgImgDetailImageSize, false, $this->imageSizes, false, $index);
		
		$imgAspectRatio = $iniManager->getKey('USER_SWITCHES', 'image_aspect_ratio');
		$this->cfgImgDetailImageAspectRatio->set_active($imgAspectRatio);
		
		$imgFastRefresh = $iniManager->getKey('USER_SWITCHES', 'image_fast_refresh');
		$this->cfgImgDetailImageFastRefresh->set_active($imgFastRefresh);
		
		$this->thumbQuality = array(
			'90',
			'80',
			'70',
			'60',
			'50',
			'40',
			'30',
			'30',
			'10',
		);
		$selected =  $iniManager->getKey('USER_SWITCHES', 'image_thumb_quality');
		if (!$selected) $selected = '80';
		$index =  array_search($selected, $this->thumbQuality);
		$void = new IndexedCombobox($this->cfgImgThumbQuality, false, $this->thumbQuality, false, $index);
		
		$originalMinSize =  $iniManager->getKey('USER_SWITCHES', 'image_thumb_original_min_size');
		if (!$originalMinSize) $originalMinSize = 30000;
		$this->cfgImgThumbMinBytes->set_value($originalMinSize);
		
	}
	public function storeImgData($hidePopup = true) {
		$this->globalIni['USER_SWITCHES']['image_mainview_size'] = $this->imageSizes[$this->cfgImgDetailImageSize->get_active_text()];
		$this->globalIni['USER_SWITCHES']['image_aspect_ratio'] = (int)$this->cfgImgDetailImageAspectRatio->get_active();
		$this->globalIni['USER_SWITCHES']['image_fast_refresh'] = (int)$this->cfgImgDetailImageFastRefresh->get_active();
		$this->globalIni['USER_SWITCHES']['image_thumb_quality'] = $this->thumbQuality[$this->cfgImgThumbQuality->get_active_text()];
		$this->globalIni['USER_SWITCHES']['image_thumb_original_min_size'] = (int)$this->cfgImgThumbMinBytes->get_value();
	}
	
	
	public function onSelectUserPath() {
		$oOs = FACTORY::get('manager/Os');
		$path = realpath($this->confEccUserPath->get_text());
		$title = I18N::get('popupConfig', 'title_ecc_userfolder_popup');
		$path_new = $oOs->openChooseFolderDialog($path, $title);
		$path_new = $oOs->eccSetRelativeDir($path_new);
		if ($path_new) $this->confEccUserPath->set_text($path_new);
	}
	
	public function initDatData() {
		if (!$this->datDataInit) $this->datDataInit = true;
		else return true;
		
		// replace i18n wordings!		
		$this->lbl_dat_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_dat_hdl').'</b>');
		$this->lbl_dat_author->set_text(I18N::get('popupConfig', 'lbl_dat_author'));
		$this->lbl_dat_website->set_text(I18N::get('popupConfig', 'lbl_dat_website'));
		$this->lbl_dat_email->set_text(I18N::get('popupConfig', 'lbl_dat_email'));
		$this->lbl_dat_comment->set_text(I18N::get('popupConfig', 'lbl_dat_comment'));
		$this->lbl_dat_opt_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_dat_opt_hdl').'</b>');
		$this->confEccDatNameStrip->set_label(I18N::get('popupConfig', 'lbl_dat_opt_namestrip'));
		
		$iniManager = FACTORY::get('manager/IniFile');
		$this->globalIni = $iniManager->getIniGlobalWithoutPlatforms();
		unset($this->globalIni['NAVIGATION']);
		
		$datComment = $iniManager->getKey('USER_DAT_CREDITS', 'author');
		$this->confEccDatAuthor->set_text($datComment);
		$datComment = $iniManager->getKey('USER_DAT_CREDITS', 'website');
		$this->confEccDatWebsite->set_text($datComment);
		$datComment = $iniManager->getKey('USER_DAT_CREDITS', 'email');
		$this->confEccDatEmail->set_text($datComment);
		$datComment = $iniManager->getKey('USER_DAT_CREDITS', 'comment');
		$this->confEccDatComment->set_text($datComment);
		$datNameStrip = $iniManager->getKey('USER_SWITCHES', 'dat_import_rc_namestrip');
		$this->confEccDatNameStrip->set_active($datNameStrip);
	}
			
	public function storeDatData($hidePopup = true) {
		$this->globalIni['USER_DAT_CREDITS']['author'] = trim($this->confEccDatAuthor->get_text());
		$this->globalIni['USER_DAT_CREDITS']['website'] = trim($this->confEccDatWebsite->get_text());
		$this->globalIni['USER_DAT_CREDITS']['email'] = trim($this->confEccDatEmail->get_text());
		$this->globalIni['USER_DAT_CREDITS']['comment'] = trim($this->confEccDatComment->get_text());
		$this->globalIni['USER_SWITCHES']['dat_import_rc_namestrip'] = (int)$this->confEccDatNameStrip->get_active();
	}
	
	public function initStartupData() {
		if (!$this->startupDataInit) $this->startupDataInit = true;
		else return true;
		
		$this->startConfSoundSelect->connect_simple_after('clicked', array($this, 'onButtonChooseSound'), $this->startConfSoundPath);
		$this->startConfSoundCheck->connect_simple_after('toggled', array($this, 'updateEccSoundState'));
		
		$this->startConfHdl->set_markup('<b>'.I18N::get('popupConfig', 'startConfHdl').'</b>');
		$this->startConfSoundHdl->set_label(I18N::get('popupConfig', 'startConfSoundHdl'));
		$this->startConfOptHdl->set_markup('<b>'.I18N::get('popupConfig', 'startConfOptHdl').'</b>');
		$this->startConfUpdate->set_label(I18N::get('popupConfig', 'startConfUpdate'));
		$this->startConfMinimize->set_label(I18N::get('popupConfig', 'startConfMinimize'));
		$this->startConfSoundSelect->set_label(I18N::get('popupConfig', 'startConfSoundSelect'));
		
		$iniManager = FACTORY::get('manager/IniFile');

		$sectionExists = $iniManager->getKey('ECC_STARTUP');
		
		$optSound = $iniManager->getKey('ECC_STARTUP', 'startup_sound');
		$optSound = (!$sectionExists) ? 'ecc-system\sound\ecc_sound_startup_low_volume.mp3' : FACTORY::get('manager/Os')->eccSetPathRelative($optSound, false);
		$this->startConfSoundPath->set_text($optSound);
		
		$optUpdate = $iniManager->getKey('ECC_STARTUP', 'startup_update_check');
		$optUpdate = (!$sectionExists) ? true : $optUpdate;
		$this->startConfUpdate->set_active($optUpdate);
		
		$optMinimize = $iniManager->getKey('ECC_STARTUP', 'minimize_to_tray');
		$optMinimize = (!$sectionExists) ? true : $optMinimize;
		$this->startConfMinimize->set_active($optMinimize);
		
		$this->updateEccSoundState(true);
	}
	
	public function storeStartupData($hidePopup = true) {
		$this->globalIni['ECC_STARTUP']['startup_sound'] = trim($this->startConfSoundPath->get_text());
		$this->globalIni['ECC_STARTUP']['startup_update_check'] = (int)$this->startConfUpdate->get_active();
		$this->globalIni['ECC_STARTUP']['minimize_to_tray'] = (int)$this->startConfMinimize->get_active();
	}
	
	public function updateEccSoundState($init=false){
		if ($init){
			$state = (trim($this->startConfSoundPath->get_text())) ? true : false;
			$this->startConfSoundCheck->set_active($state);
		}
		else $state = $this->startConfSoundCheck->get_active();

		$this->startConfSoundPath->set_sensitive($state);
		$this->startConfSoundSelect->set_sensitive($state);
		if(!$init && !$state) $this->startConfSoundPath->set_text('');
	}
	
	public function onButtonChooseSound($gtkEntry) {
		$iniManager = FACTORY::get('manager/IniFile');
		$path = realpath($gtkEntry->get_text());
		$title = sprintf(I18N::get('popupConfig', 'title_startup_select_sound'));
		$newPath = FACTORY::get('manager/Os')->openChooseFileDialog($path, $title, array('wav (*.wav)' => '*.wav', 'mp3 (*.mp3)' => '*.mp3'));
		if ($newPath && realpath($newPath)) {
			$this->startConfSoundCheck->set_active(true);
			$gtkEntry->set_text(FACTORY::get('manager/Os')->eccSetPathRelative($newPath, false));
		}
	}
	
	
	public function show() {
		$this->gui->show();
	}

    public function hide() {
    	$this->gui->hide();
	}

    public function addError($errorMessage) {
    	print $errorMessage;
    	$this->gui->set_sensitive(false);
    }
    
    public function __get($widgedName) {
    	return self::get_widget($widgedName);
    }
 
}
?>
