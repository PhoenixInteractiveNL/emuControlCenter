<?
define('TAB_EMU', 0);
define('TAB_ECC', 1);
define('TAB_DAT', 2);
define('TAB_IMG', 3);
define('TAB_GUI', 4);

define('TAB_STARTUP', 5);
define('TAB_TOOL', 6);
define('TAB_THEME', 7);

require_once 'cGuiTheme.php';

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
			case 'THEME':
				$tab = TAB_THEME;
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

		if ($errorMessage) {
			#FACTORY::get('manager/Gui')->openDialogInfo(i18n::get('global', 'error_title'), $errorMessage);
			#$this->configErrorLabel->set_markup('<b>'.$errorMessage.'</b>');
		}

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
		parent::__construct(ECC_DIR_SYSTEM.'/gui/guipopupconfig.glade');
		$this->signal_autoconnect_instance($this);

		$this->guiPopConfig->connect('delete-event', array($this, 'onButtonCancel'));

		$this->guiPopConfig->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		$this->guiPopConfig->set_modal(true);
		#$this->guiPopConfig->set_keep_above(true);
		$this->gui = $this->guiPopConfig;

		# connect signals
		$this->initPlatformTreeview();
		$this->emuPathButton->connect_simple_after('clicked', array($this, 'onButtonChooseEmulator'), $this->emuAssignGlobalPath);
		$this->buttonSave->connect_simple_after('clicked', array($this, 'onButtonSave'));
		$this->buttonCancel->connect_simple_after('clicked', array($this, 'onButtonCancel'));
		$this->cfgNotepad->connect('switch-page', array($this, 'onChangeTab'));

		$this->confEccUserPathButton->connect_simple('clicked', array($this, 'onSelectUserPath'));
		$this->extProgDaemontoolsButton->connect_simple('clicked', array($this, 'extProgDaemontoolsFind'));
		$this->emuAssignGlobalEditEccScript->connect_simple_after('clicked', array($this, 'openEccScriptEditor'));
		$this->emuAssignGlobalEccScriptOptions->connect_simple_after('clicked', array($this, 'openEccScriptOptions'));
		$this->emuAssignGlobalEccScriptRefresh->connect_simple_after('clicked', array($this, 'updateEccScriptState'));
		$this->emuAssignGlobalDeleteEccScript->connect_simple_after('clicked', array($this, 'deleteEccScript'));
		$this->emuAssignGlobalEnableEccScript->connect_simple_after('toggled', array($this, 'updateEccScriptState'));

		# zip auto unpack
		$this->emuAssignGlobalCheckZipUnpackOpen->connect_simple_after('clicked', array($this, 'openUnpackFolder'));
		$this->emuAssignGlobalCheckZipUnpackActive->connect_simple_after('toggled', array($this, 'updateUnpackState'));
		$this->emuAssignGlobalCheckZipUnpackSkip->set_sensitive(false);
		$this->emuAssignGlobalCheckZipUnpackAll->set_sensitive(false);
		$this->emuAssignLabelZipUnpack->set_sensitive(false);

		$this->emuStartButton->connect_simple_after('clicked', array($this, 'startEmulator'));

		$this->guiPopConfig->set_title(I18N::get('popupConfig', 'winTitleConfiguration'));

		$this->buttonSave->set_label(I18N::get('global', 'save'));
		$this->buttonCancel->set_label(I18N::get('global', 'cancel'));

		$this->lbl_emu_platform_name->set_text(I18N::get('popupConfig', 'lbl_emu_platform_name'));
		$this->lbl_emu_platform_category->set_text(I18N::get('popupConfig', 'lbl_emu_platform_category'));
		$this->emuPlatformActiveState->set_label(I18N::get('popupConfig', 'emuPlatformActiveState'));

		$this->lbl_emu_assign_path->set_text(I18N::get('popupConfig', 'lbl_emu_assign_path'));
		$this->emuStartButton->set_label(I18N::get('global', 'start').' '.I18N::get('global', 'emulator'));
		$this->emuPathButton->set_label(I18N::get('popupConfig', 'btn_emu_assign_path_select'));
		$this->lbl_emu_assign_parameter->set_text(I18N::get('popupConfig', 'lbl_emu_assign_parameter'));
		$this->emuAssignGlobalEscape->set_label(I18N::get('popupConfig', 'lbl_emu_assign_escape'));
		$this->emuAssignGlobalEightDotThree->set_label(I18N::get('popupConfig', 'lbl_emu_assign_eightdotthree'));
		$this->emuAssignGlobalUseCueFile->set_label(I18N::get('popupConfig', 'lbl_emu_assign_usecuefile'));
		$this->emuAssignGlobalFilenameOnly->set_label(I18N::get('popupConfig', 'lbl_emu_assign_nameonly'));
		$this->emuAssignGlobalNoExtension->set_label(I18N::get('popupConfig', 'lbl_emu_assign_noextension'));
		$this->emuAssignGlobalExecuteInEmuFolder->set_label(I18N::get('popupConfig', 'lbl_emu_assign_executeinemufolder'));

		$this->emuAssignLabelEccScript->set_markup('<b>'.I18N::get('popupConfig', 'lbl_emu_assign_use_eccscript').'</b>');
		$this->emuAssignGlobalEditEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_create_eccscript'));
		$this->emuAssignGlobalEccScriptOptions->set_label(I18N::get('popupConfig', 'emuAssignGlobalEccScriptOptions'));
		$this->emuAssignGlobalEccScriptRefresh->set_label(I18N::get('popupConfig', 'lbl_emu_assign_refresh_eccscript'));
		$this->emuAssignGlobalDeleteEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_delete_eccscript'));
		$this->emuAssignGlobalEnableEccScript->set_label(I18N::get('popupConfig', 'emuAssignGlobalEnableEccScript'));

		# zip unpack
		$this->emuAssignLabelZipUnpack->set_label(I18N::get('popupConfig', 'emuAssignLabelZipUnpack'));
		$this->emuAssignGlobalCheckZipUnpackActive->set_label(I18N::get('popupConfig', 'emuAssignGlobalCheckZipUnpackActive'));
		$this->emuAssignGlobalCheckZipUnpackSkip->set_label(I18N::get('popupConfig', 'emuAssignGlobalCheckZipUnpackSkip'));
		$this->emuAssignGlobalCheckZipUnpackOpen->set_label(I18N::get('popupConfig', 'emuAssignGlobalCheckZipUnpackOpen'));

		$this->emuAssignGlobalCheckZipUnpackAll->set_label(I18N::get('popupConfig', 'emuAssignGlobalCheckZipUnpackAll'));
		$this->emuUnpackNotelabel->set_label(I18N::get('popupConfig', 'emuUnpackNotelabel'));

		$this->emuAssignFileextLabel->set_markup('<b>'.I18N::get('popupConfig', 'emuAssignFileextLabel').'</b>');
		$this->emuAssignPreviewLabel->set_markup('<b>'.I18N::get('popupConfig', 'emuAssignPreviewLabel').'</b>');

		$this->tab_label_platforms->set_label(I18N::get('popupConfig', 'tab_label_platforms'));
		$this->tab_label_general->set_label(I18N::get('popupConfig', 'tab_label_general'));
		$this->tab_label_datfiles->set_label(I18N::get('popupConfig', 'tab_label_datfiles'));
		$this->tab_label_images->set_label(I18N::get('popupConfig', 'tab_label_images'));
		$this->tab_label_colorsandfonts->set_label(I18N::get('popupConfig', 'tab_label_colorsandfonts'));
		$this->tab_label_startup->set_label(I18N::get('popupConfig', 'tab_label_startup'));
		/////$this->tab_label_language->set_label(I18N::get('popupConfig', 'tab_label_language'));
		$this->tab_label_themes->set_label(I18N::get('popupConfig', 'tab_label_themes'));

		$this->tabEmuConfig->set_label(I18N::get('popupConfig', 'tabEmuConfig'));
		$this->tabEmuPlatformSettings->set_label(I18N::get('popupConfig', 'tabEmuPlatformSettings'));
		$this->tabEmuInfos->set_label(I18N::get('popupConfig', 'tabEmuInfos'));

		$this->lbl_emu_tips->set_markup('<b>'.I18N::get('popupConfig', 'lbl_emu_tips').'</b>');
		$this->lbl_emu_tips_ecc->set_markup('<b>'.I18N::get('popupConfig', 'lbl_emu_tips_ecc').'</b>');
		$this->lbl_img_opt_conv->set_markup('<b>'.I18N::get('popupConfig', 'lbl_img_opt_conv').'</b>');

		$this->lbl_col_opt_global->set_markup('<b>'.I18N::get('popupConfig', 'lbl_col_opt_global').'</b>');
		$this->lbl_col_opt_list->set_markup('<b>'.I18N::get('popupConfig', 'lbl_col_opt_list').'</b>');
		$this->lbl_col_opt_options->set_markup('<b>'.I18N::get('popupConfig', 'lbl_col_opt_options').'</b>');

		$this->lbl_img_opt_conv_quality->set_label(I18N::get('popupConfig', 'lbl_img_opt_conv_quality'));
		$this->lbl_img_opt_conv_quality_def->set_markup('<b>%</b> '.sprintf(I18N::get('popupConfig', 'lbl_img_opt_conv_quality_def%s'), '80'));
		$this->lbl_img_opt_conv_minsize->set_label(I18N::get('popupConfig', 'lbl_img_opt_conv_minsize'));
		$this->lbl_img_opt_conv_minsize_def->set_markup('<b>bytes</b> '.sprintf(I18N::get('popupConfig', 'lbl_img_opt_conv_minsize_def%s'), '30000'));

		$this->emuAssignGlobalEscape->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalFilenameOnly->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalEightDotThree->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalUseCueFile->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalNoExtension->connect_simple_after('toggled', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalExecuteInEmuFolder->connect_simple_after('toggled', array($this, 'updateEmuPreview'));

		$this->emuAssignGlobalParam->connect_simple_after('changed', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalPath->connect_simple_after('changed', array($this, 'updateEmuPreview'));
		$this->emuAssignGlobalEnableEccScript->connect_simple_after('toggled', array($this, 'updateEmuPreview'));

		$this->colOptGlobalFont->set_text(I18N::get('popupConfig', 'colOptGlobalFont'));
		$this->colOptListBg0->set_text(I18N::get('popupConfig', 'colOptListBg0'));
		$this->colOptListBg1->set_text(I18N::get('popupConfig', 'colOptListBg1'));
		$this->colOptListBg2->set_text(I18N::get('popupConfig', 'colOptListBg2'));
		$this->colOptListBgHilight->set_text(I18N::get('popupConfig', 'colOptListBgHilight'));
		$this->colOptListBgImage->set_text(I18N::get('popupConfig', 'colOptListBgImage'));
		$this->colOptListText->set_text(I18N::get('popupConfig', 'colOptListText'));
		$this->colOptListTextHilight->set_text(I18N::get('popupConfig', 'colOptListTextHilight'));
		$this->colOptListFont->set_text(I18N::get('popupConfig', 'colOptListFont'));
		$this->colOptOptionsBg1->set_text(I18N::get('popupConfig', 'colOptOptionsBg1'));
		$this->colOptOptionsBg2->set_text(I18N::get('popupConfig', 'colOptOptionsBg2'));
		$this->colOptOptionsBgHilight->set_text(I18N::get('popupConfig', 'colOptOptionsBgHilight'));
		$this->colOptOptionsText->set_text(I18N::get('popupConfig', 'colOptOptionsText'));

		$this->colImgSlotUnsetBg->set_text(I18N::get('popupConfig', 'colImgSlotUnsetBg'));
		$this->colImgSlotSetSelect->set_text(I18N::get('popupConfig', 'colImgSlotSetSelect'));
		$this->colImgSlotSetBg->set_text(I18N::get('popupConfig', 'colImgSlotSetBg'));
		$this->colImgSlotUnsetSelect->set_text(I18N::get('popupConfig', 'colImgSlotUnsetSelect'));
		$this->colImgSlotText->set_text(I18N::get('popupConfig', 'colImgSlotText'));

		# themes
		$this->lblThemeSelect->set_markup(''.I18N::get('popupConfig', 'lblThemeSelect'));
		#$this->lblThemeInfo->set_markup(I18N::get('global', 'informations'));
		$this->lblThemePreview->set_markup(I18N::get('global', 'preview'));
		$this->lblThemeAuthor->set_text(I18N::get('global', 'author'));
		$this->lblThemeName->set_markup('<b>'.I18N::get('global', 'name').'</b>');
		$this->lblThemeContact->set_text(I18N::get('global', 'contact'));
		$this->lblThemeWebsite->set_text(I18N::get('global', 'website'));
		$this->lblThemeDate->set_text(I18N::get('global', 'date'));

		$this->initEccData();
		$this->initDatData();
		$this->initImgData();
		$this->initGuiData();
		$this->initStartupData();
		$this->initThemeData();

		$this->guiPopConfig->present();

		// Images, added 2012-11-25 (ECC v1.13 build 12)
		// TAB General
		$this->confEccLanguageImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_language.png'));
		$this->confEccUserPathImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_user_folder.png'));
		$this->confEccSilentParsingImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_no_popups.png'));
		$this->tabGeneralListViewItemsImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_image_page.png'));
		$this->confEccSaveViewSettingsImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_viewmode.png'));
		$this->confEccStatusLogCheckImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_logging.png'));
		$this->tabGeneralImageTabTcuttImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_text_cuttoff.png'));
		$this->tabGeneralParsingTriggerImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_trigger.png'));
		$this->tabGeneralUnpackGUITriggerImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_trigger.png'));
		$this->eccVideoPlayer_enableImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_videoplayer.png'));
		$this->eccVideoPlayer_soundImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_sound.png'));
		$this->eccVideoPlayer_soundvolumeImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_sound_volume.png'));
		$this->eccVideoPlayer_loopImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_loop.png'));
		$this->eccVideoPlayer_resolutionImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_resolution.png'));
		$this->eccVideoPlayer_paddingImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_padding.png'));
		// TAB DAT files
		$this->confEccDatNameStripImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_clean.png'));
		// TAB Images
		$this->cfgImgDetailImageSizeImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_image_size.png'));
		$this->cfgImgDetailImageAspectRatioImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_aspect_ratio.png'));
		$this->cfgImgDetailImageFastRefreshImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_list_refresh.png'));
		$this->cfgImgThumbQualityImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_quality.png'));
		$this->cfgImgThumbMinBytesImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_min_filesize.png'));
		// TAB Color and Fonts
		$this->cfgEccColorListFontGlobalImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_font.png'));
		$this->imgUseThemeColors->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_color_picker.png'));	
		// TAB Startup
		$this->startConfSoundImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_sound.png'));
		$this->startConfUpdateImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_update.png'));
		$this->startConfBugreportSendImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_bugreport.png'));
		$this->startConfMinimizeImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_minimize.png'));
		$this->startConfDeleteUnpackedImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_clean.png'));
		$this->startConfThirdPartyXpadderImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_xpadder.png'));
		// TAB External programs
		$this->extProgDaemontoolsImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_daemontools.png'));
		// TAB Themes
		$this->ThemeSelectImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_theme.png'));
		// --->
	}

	public function updateEmuPreview(){

		$emuPath = $this->emuAssignGlobalPath->get_text();

		$emuState = (trim($emuPath) && realpath($emuPath));
		if (!$emuState) $this->emuAssignGlobalEnableEccScript->set_active(false);

		if ($emuState) {
			#$this->emuStartButton->set_label(I18N::get('global', 'start').' '.FileIO::get_plain_filename($emuPath));
			$this->emuStartButton->set_label(I18N::get('global', 'start'));
			$this->emuStartButton->set_sensitive(true);
		}
		else {
			#$this->emuStartButton->set_label(I18N::get('global', 'start').' '.I18N::get('global', 'emulator'));
			$this->emuStartButton->set_label(I18N::get('global', 'start'));
			$this->emuStartButton->set_sensitive(false);
		}

		$emuEscape = $this->emuAssignGlobalEscape->set_sensitive($emuState);
		$filenameOnly = $this->emuAssignGlobalFilenameOnly->set_sensitive($emuState);
		$emuWin8char = $this->emuAssignGlobalEightDotThree->set_sensitive($emuState);
		$useCueFile = $this->emuAssignGlobalUseCueFile->set_sensitive($emuState);
		$noExtension = $this->emuAssignGlobalNoExtension->set_sensitive($emuState);
		$executeInEmuFolder = $this->emuAssignGlobalExecuteInEmuFolder->set_sensitive($emuState);

		$this->emuAssignGlobalEnableEccScript->set_sensitive($emuState);

		# make unneeded options invisible
		$this->emuStartButton->set_visible($emuState);
		$this->emuCliParamArea->set_visible($emuState);
		$this->emuPreviewArea->set_visible($emuState);
		$this->emuScriptArea->set_visible($emuState);
		$this->emuParamArea->set_visible($emuState);
		$this->emuUnpackArea->set_visible($emuState);
		$this->emuAssignGlobalActive->set_visible($emuState);
		$this->emuAssignGlobalParam->set_sensitive($emuState);
		$this->emuAssignGlobalCheckZipVBox->set_sensitive($emuState);

		if (!$emuState){
			$emuCommand = I18N::get('popupConfig', 'lbl_preview_selectEmuFirst');
			$this->lbl_emu_assign_parameter_preview->set_markup('<span size="small" color="#FF0000" weight="ultralight">'.$emuCommand.'</span>');
			return false;
		}

		$romPath = 'game.rom';
		$emuParameter = $this->emuAssignGlobalParam->get_text();
		$emuEscape = $this->emuAssignGlobalEscape->get_active();
		$filenameOnly = $this->emuAssignGlobalFilenameOnly->get_active();
		$emuWin8char = $this->emuAssignGlobalEightDotThree->get_active();
		$useCueFile = $this->emuAssignGlobalUseCueFile->get_active();
		$noExtension = $this->emuAssignGlobalNoExtension->get_active();
		$executeInEmuFolder = $this->emuAssignGlobalExecuteInEmuFolder->get_active();
		# only activate, if filename only is selected
		$this->emuAssignGlobalExecuteInEmuFolder->set_sensitive($filenameOnly);
		$this->emuAssignGlobalEightDotThree->set_sensitive(!$filenameOnly && !$noExtension);
		$this->emuAssignGlobalUseCueFile->set_sensitive(!$noExtension);

		if ($noExtension) $this->emuAssignGlobalUseCueFile->set_active(false);
		if ($useCueFile) $this->emuAssignGlobalFilenameOnly->set_active(false);
		if ($useCueFile) $this->emuAssignGlobalNoExtension->set_active(false);
		if ($emuWin8char) $this->emuAssignGlobalEscape->set_active(false);

		if (!$filenameOnly) $this->emuAssignGlobalExecuteInEmuFolder->set_active(false);
		else $this->emuAssignGlobalEightDotThree->set_active(false);

		$this->emuAssignGlobalFilenameOnly->set_sensitive(!$emuWin8char && !$useCueFile);
		$this->emuAssignGlobalNoExtension->set_sensitive(!$emuWin8char && !$useCueFile);

		$enableEccScript = $this->emuAssignGlobalEnableEccScript->get_active();

		#$this->emuAssignGlobalParam->set_sensitive(!$enableEccScript);

		$emuCommand = I18N::get('popupConfig', 'lbl_preview_impossible');
		if ($theEmuCommand = FACTORY::get('manager/Os')->getEmuCommand($emuPath, $emuParameter, $romPath, $emuEscape, $emuWin8char, $filenameOnly, $noExtension, $enableEccScript, $executeInEmuFolder, $this->selectedEccident, $useCueFile)){

			$emuCommand = $theEmuCommand['command'];
			$toolsFolder = getcwd()."\\";
			$emuCommand = str_replace($toolsFolder, DIRECTORY_SEPARATOR.'gamepath'.DIRECTORY_SEPARATOR, $emuCommand);

			$emuFolder = dirname($emuPath)."\\";
			$emuCommand = str_replace($emuFolder, DIRECTORY_SEPARATOR.'emupath'.DIRECTORY_SEPARATOR, $emuCommand);
			$emuCommand = str_replace('ecc-core\\thirdparty\\autoit\\', '', $emuCommand);
			$emuCommand = str_replace(ECC_DIR, '', $emuCommand);
		}
		$this->lbl_emu_assign_parameter_preview->set_markup('<span size="small" weight="ultralight">'.$emuCommand.'</span>');
	}

	public function openEccScriptEditor(){

		$path = realpath($this->emuAssignGlobalPath->get_text());
		if ($path){
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');

			$eccScriptFile = '../ecc-script/'.$this->selectedEccident.'/'.FACTORY::get('manager/FileIO')->get_plain_filename($path).$eccLoc['ECC_SCRIPT_EXTENSION'];
			if(!is_dir(dirname($eccScriptFile))) {
				mkdir(dirname($eccScriptFile));
			}

			if (!file_exists($eccScriptFile)) {
				$this->emuAssignGlobalEccScriptRefresh->set_sensitive(true);
				$this->emuAssignGlobalEditEccScript->set_sensitive(false);

				$eccScriptTemplateFile = '../ecc-script/eccScriptTemplate.eccscript';
				if (file_exists($eccScriptTemplateFile)) {
					$eccScriptTemplate = file_get_contents($eccScriptTemplateFile);
					file_put_contents($eccScriptFile, $eccScriptTemplate);
				}
			}

			FACTORY::get('manager/Os')->executeProgramDirect(ECC_DIR.'/'.$eccLoc['ECC_EXE_SCRIPT_EDITOR'], false, '"'.$eccScriptFile.'"');

		}
		elseif(!trim($path)) {
			FACTORY::get('manager/Gui')->openDialogInfo('ERROR', I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript_error'), false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
		}
		else {
			FACTORY::get('manager/Gui')->openDialogInfo('ERROR', I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript_error_notfound'), false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
		}
	}

	public function openEccScriptOptions(){
		$path = realpath($this->emuAssignGlobalPath->get_text());
		if ($path){
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');
			$eccScriptFile = '../ecc-script/'.$this->selectedEccident.'/'.FACTORY::get('manager/FileIO')->get_plain_filename($path).$eccLoc['ECC_SCRIPT_EXTENSION'];
			if (!file_exists($eccScriptFile)) return false;

			print 'execute: '.ECC_DIR.'/'.$eccLoc['ECC_EXE_SCRIPT']."\n";
			print 'eccScriptFile: '.$eccScriptFile."\n";
			FACTORY::get('manager/Os')->executeProgramDirect(ECC_DIR.'/'.$eccLoc['ECC_EXE_SCRIPT'], 'open', '"'.$eccScriptFile.'" /fastload');

		}
		elseif(!trim($path)) FACTORY::get('manager/Gui')->openDialogInfo('ERROR', I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript_error'), false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
		else FACTORY::get('manager/Gui')->openDialogInfo('ERROR', I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript_error_notfound'), false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
	}

	public function deleteEccScript(){
		$path = $this->emuAssignGlobalPath->get_text();
		if ($this->eccScriptExists($path)){
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');

			$eccScriptFile = '../ecc-script/'.$this->selectedEccident.'/'.FACTORY::get('manager/FileIO')->get_plain_filename($path).$eccLoc['ECC_SCRIPT_EXTENSION'];

			$msg = sprintf(I18N::get('popupConfig', 'msg_emu_assign_delete_eccscript%s'), $eccScriptFile);
			if (FACTORY::get('manager/Gui')->openDialogConfirm('', $msg)){
				unlink($eccScriptFile);
				$this->updateEccScriptState();
			}
		}
	}

	public function eccScriptExists($path){
		if (!trim($path)) return false;
		$mngrValidator = FACTORY::get('manager/Validator');
		$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');

		$eccScriptFile = '../ecc-script/'.$this->selectedEccident.'/'.FACTORY::get('manager/FileIO')->get_plain_filename($path).$eccLoc['ECC_SCRIPT_EXTENSION'];

		return realpath($eccScriptFile);
	}

	public function activateEccScript($state){
		$state = $this->emuAssignGlobalEnableEccScript->set_active($state);
		$this->updateEccScriptState();
	}

	public function updateEccScriptState(){

		$state = $this->emuAssignGlobalEnableEccScript->get_active();
		$this->emuAssignLabelEccScript->set_sensitive($state);
		$this->emuAssignGlobalEditEccScript->set_visible(true);
		$this->emuAssignGlobalEditEccScript->set_sensitive($state);
		$this->emuAssignGlobalDeleteEccScript->set_sensitive(false);
		$this->emuAssignGlobalEccScriptOptions->set_sensitive(false);
		$this->emuAssignGlobalEccScriptRefresh->set_sensitive(false);
		$this->emuAssignGlobalEditEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_create_eccscript'));

		if ($this->eccScriptExists($this->emuAssignGlobalPath->get_text())) {
			$this->emuAssignGlobalEditEccScript->set_label(I18N::get('popupConfig', 'lbl_emu_assign_edit_eccscript'));
			$this->emuAssignGlobalDeleteEccScript->set_sensitive($state);
			$this->emuAssignGlobalEccScriptOptions->set_sensitive($state);
		}
	}

	public function startEmulator(){
		$path = realpath($this->emuAssignGlobalPath->get_text());
		if ($path) FACTORY::get('manager/Os')->executeFileWithProgramm($path);
	}

	private function initPlatformTreeview() {
		$this->listStore = new GtkListStore(GObject::TYPE_STRING, GObject::TYPE_STRING); // init store
		$rendererText = new GtkCellRendererText(); // used renderer
		$cIndex = new GtkTreeViewColumn('index', $rendererText, 'text', 0); // set index (invisible)
		$cIndex->set_visible(false);
		$cPlatform = new GtkTreeViewColumn('platform', $rendererText, 'text', 1); // platform name
		$this->emuTreeView->set_model($this->listStore); // add
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

				# enable/diable show unpack folder button
				$this->emuAssignGlobalCheckZipUnpackOpen->set_sensitive(FACTORY::get('manager/IniFile')->getUnpackFolder($this->selectedEccident));
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

		#$this->configErrorLabel->set_text('');

		# get needed idents
		$eccident = $this->selectedEccident;
		if (!$eccident) return false;
		$fileExt = $this->emuSelectedExtension;
		if (!$fileExt) $fileExt = 'GLOBAL';

		# platform data
		if (isset($this->dataStorage[$eccident]['PLATFORM'])) $storagePlatform = $this->dataStorage[$eccident]['PLATFORM'];
		$ini = (isset($this->platformIni)) ? $this->platformIni : false;

		# get data
		$platformActive = (!isset($ini['PLATFORM']['active'])) ? true : $ini['PLATFORM']['active'];
		$activePlatform = ($ini && !isset($storagePlatform['active'])) ? $platformActive : $storagePlatform['active'];
		$name = ($ini && !isset($storagePlatform['name'])) ? @$ini['PLATFORM']['name'] : $storagePlatform['name'];
		$category = ($ini && !isset($storagePlatform['category'])) ? @$ini['PLATFORM']['category'] : $storagePlatform['category'];

		# set data to fields
		$platformNameString = I18N::get('global', 'platform').' '.$name;

		$this->emuPlatformLabel->set_markup('<b>'.sprintf(I18N::get('popupConfig', 'lbl_emu_hdl%s%s'), $platformNameString, $eccident).'</b>');
		$this->emuPlatformActiveState->set_active($activePlatform);
		$this->emuPlatformName->set_text($name);
		$this->emuPlatformCategory->set_text($category);

		# emulator data
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

		if($fileExt == 'GLOBAL' || $fileExt == 'ALT1' || $fileExt == 'ALT2'){
			$this->emuAssignGlobalActive->set_label(sprintf(I18N::get('popupConfig', 'emuAssignGlobalActiveGlobal%s'), $fileExt));
		}
		else {
			$this->emuAssignGlobalActive->set_label(sprintf(I18N::get('popupConfig', 'emuAssignGlobalActive%s'), $fileExt));
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
		$useCueFileState = (!isset($iniEmu['useCueFile'])) ? false : $iniEmu['useCueFile'];
		$useCueFile = (!isset($storageEmu['useCueFile'])) ? $useCueFileState : $storageEmu['useCueFile'];

		# set default off!
		$filenameOnlyState = (!isset($iniEmu['filenameOnly'])) ? false : $iniEmu['filenameOnly'];
		$filenameOnly = (!isset($storageEmu['filenameOnly'])) ? $filenameOnlyState : $storageEmu['filenameOnly'];

		# set default off!
		$noExtensionState = (!isset($iniEmu['noExtension'])) ? false : $iniEmu['noExtension'];
		$noExtension = (!isset($storageEmu['noExtension'])) ? $noExtensionState : $storageEmu['noExtension'];

		# set default off!
		$executeInEmuFolderState = (!isset($iniEmu['executeInEmuFolder'])) ? false : $iniEmu['executeInEmuFolder'];
		$executeInEmuFolder = (!isset($storageEmu['executeInEmuFolder'])) ? $executeInEmuFolderState : $storageEmu['executeInEmuFolder'];

		# set default off!
		$enableEccScriptState = (!isset($iniEmu['enableEccScript'])) ? false : $iniEmu['enableEccScript'];
		$enableEccScript = (!isset($storageEmu['enableEccScript'])) ? $enableEccScriptState : $storageEmu['enableEccScript'];

		# set default off!
		$enableZipUnpackActiveState = (!isset($iniEmu['enableZipUnpackActive'])) ? false : $iniEmu['enableZipUnpackActive'];
		$enableZipUnpackActive = (!isset($storageEmu['enableZipUnpackActive'])) ? $enableZipUnpackActiveState : $storageEmu['enableZipUnpackActive'];

		# set default off!
		$enableZipUnpackAllState = (!isset($iniEmu['enableZipUnpackAll'])) ? false : $iniEmu['enableZipUnpackAll'];
		$enableZipUnpackAll = (!isset($storageEmu['enableZipUnpackAll'])) ? $enableZipUnpackAllState : $storageEmu['enableZipUnpackAll'];

		# set default off!
		$enableZipUnpackSkipState = (!isset($iniEmu['enableZipUnpackSkip'])) ? true : $iniEmu['enableZipUnpackSkip'];
		$enableZipUnpackSkip = (!isset($storageEmu['enableZipUnpackSkip'])) ? $enableZipUnpackSkipState : $storageEmu['enableZipUnpackSkip'];

		# set data to fields
		//$this->emuAssignLabel->set_markup('<b>Emulator assignment ('.$fileExt.')</b>');
		$this->emuAssignLabel->set_markup('<b>'.$name.'</b> - '.sprintf(I18N::get('popupConfig', 'lbl_emu_assign_hdl%s'), '<b>'.$fileExt.'</b>').'');

		if (!isset($this->emuInfoBuffer[$eccident])) {
			$spacer = str_repeat('-', 80)."\n";
			$buffer = new GtkTextBuffer();
			if (file_exists('system/ecc_'.$eccident.'_emu.ini')) {
				$iniManager = FACTORY::get('manager/IniFile');
				$emuData = $iniManager->parse_ini_file_quotes_safe('system/ecc_'.$eccident.'_emu.ini');
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
			#$text .= $spacer.I18N::get('popupConfig', 'emu_info_footer')."\n";

			$buffer->set_text($text);
			$this->emuInfoBuffer[$eccident] = $buffer;
		}
		$this->emuInfo->set_buffer($this->emuInfoBuffer[$eccident]);
		$this->emuInfoForum->set_markup('<b>'.sprintf(I18N::get('popupConfig', 'emu_info_footer%s'), '</b>http://ecc.phoenixinteractive.nl/<b>').'</b>');

		$this->emuAssignGlobalActive->set_active($activeEmu);
		$this->emuAssignGlobalPath->set_text($path);
		$this->emuAssignGlobalParam->set_text($param);
		$this->emuAssignGlobalEscape->set_active($escape);
		$this->emuAssignGlobalEightDotThree->set_active($eightDotThree);
		$this->emuAssignGlobalUseCueFile->set_active($useCueFile);
		$this->emuAssignGlobalFilenameOnly->set_active($filenameOnly);
		$this->emuAssignGlobalNoExtension->set_active($noExtension);
		$this->emuAssignGlobalExecuteInEmuFolder->set_active($executeInEmuFolder);
		$this->emuAssignGlobalEnableEccScript->set_active($enableEccScript);

		# zip unpack
		$this->emuAssignGlobalCheckZipUnpackActive->set_active($enableZipUnpackActive);
		$this->emuAssignGlobalCheckZipUnpackAll->set_active($enableZipUnpackAll);
		$this->emuAssignGlobalCheckZipUnpackSkip->set_active($enableZipUnpackSkip);

		$this->updateEccScriptState();
		return true;
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
		$this->dataStorage[$eccident]['EMU'][$fileExt]['useCueFile'] = $this->emuAssignGlobalUseCueFile->get_active();

		$this->dataStorage[$eccident]['EMU'][$fileExt]['filenameOnly'] = $this->emuAssignGlobalFilenameOnly->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['noExtension'] = $this->emuAssignGlobalNoExtension->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['executeInEmuFolder'] = $this->emuAssignGlobalExecuteInEmuFolder->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['enableEccScript'] = $this->emuAssignGlobalEnableEccScript->get_active();

		# zip unpack
		$this->dataStorage[$eccident]['EMU'][$fileExt]['enableZipUnpackActive'] = $this->emuAssignGlobalCheckZipUnpackActive->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['enableZipUnpackAll'] = $this->emuAssignGlobalCheckZipUnpackAll->get_active();
		$this->dataStorage[$eccident]['EMU'][$fileExt]['enableZipUnpackSkip'] = $this->emuAssignGlobalCheckZipUnpackSkip->get_active();

		# only needed for the initial checksum
		if ($onlyReturn) return $this->dataStorage;
		return true;
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
			$path = $iniManager->getHistoryKey('path_emuconfig_last');
			if($path) $path = realpath($path);
		}

		$title = sprintf(I18N::get('popupConfig', 'title_emu_assign_path_select_popup%s'), $this->selectedEccident);

		$shorcutFolder = $iniManager->getShortcutPaths($this->selectedEccident);
		$newPath = FACTORY::get('manager/Os')->openChooseFileDialog($path, $title, false, false, false, $shorcutFolder);

		if ($newPath && realpath($newPath)) {

			$relativePath = FACTORY::get('manager/Os')->eccSetRelativeFile($newPath);
			$gtkEntry->set_text($relativePath);

			$this->emuAssignGlobalActive->set_active(true);
			$iniManager->storeHistoryKey('path_emuconfig_last', realpath($newPath));

			# eccScript available?
			$mngrValidator = FACTORY::get('manager/Validator');
			$eccLoc = $mngrValidator->getEccCoreKey('eccHelpLocations');

			// new script path
			$eccScriptFile = '../ecc-script/'.$this->selectedEccident.'/'.FACTORY::get('manager/FileIO')->get_plain_filename($newPath).$eccLoc['ECC_SCRIPT_EXTENSION'];

			if(file_exists($eccScriptFile)){
				$title = I18N::get('popupConfig', 'title_emu_assign_found_eccscript');
				$msg = sprintf(I18N::get('popupConfig', 'msg_emu_assign_found_eccscript%s'), basename($eccScriptFile));

				$test = file($eccScriptFile);

				$preview = array();
				foreach($test as $index => $line){
					if ($index >= 20 || substr(trim($line), 0, 1) != ';') break; # only read first 20 lines
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
			else{
				$this->activateEccScript(false);
			}

			$this->storeTempEmulatorData();
			$this->createExtensionTable();
		}
	}

	public function openUnpackFolder() {
		$folder = FACTORY::get('manager/IniFile')->getUnpackFolder($this->selectedEccident);
		FACTORY::get('manager/Os')->executeProgramDirect($folder, false);
	}

	public function updateUnpackState(){
		$state = ($this->emuAssignGlobalCheckZipUnpackActive->get_active());
		$this->emuAssignGlobalCheckZipUnpackSkip->set_sensitive($state);
		$this->emuAssignGlobalCheckZipUnpackAll->set_sensitive($state);
		$this->emuAssignLabelZipUnpack->set_sensitive($state);
	}

	public function onButtonSave() {
		$this->saveData();
		$originalMd5 = md5(print_r($this->globalIni, true));

		$this->storeEccData();
		$this->storeDatData();
		$this->storeImgData();
		$this->storeGuiData();
		$this->storeStartupData();
		$this->storeThemeData();

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
		return true;
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

		// Set I18N translation
		$this->lbl_ecc_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_hdl').'</b>');
		$this->lbl_ecc_userfolder->set_text(I18N::get('popupConfig', 'lbl_ecc_userfolder'));
		$this->confEccUserPathButton->set_label(I18N::get('popupConfig', 'lbl_ecc_userfolder_button'));
		$this->lbl_ecc_opt_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_opt_hdl').'</b>');
		$this->tabGeneralHlListOptions->set_markup('<b>'.I18N::get('popupConfig', 'tabGeneralHlListOptions').'</b>');
		$this->lbl_ecc_opt_detail_pp->set_text(I18N::get('popupConfig', 'lbl_ecc_opt_detail_pp'));
		$this->lbl_ecc_opt_list_pp->set_text(I18N::get('popupConfig', 'lbl_ecc_opt_list_pp'));
		$this->lbl_ecc_opt_language->set_text(I18N::get('popupConfig', 'lbl_ecc_opt_language'));
		$this->confEccStatusLogCheckLabel->set_label(I18N::get('popupConfig', 'confEccStatusLogCheck'));
		$this->confEccStatusLogOpen->set_label(I18N::get('popupConfig', 'confEccStatusLogOpen'));
		$this->confEccSaveViewSettingsLabel->set_label(I18N::get('popupConfig', 'confEccSaveViewSettings'));
		$this->confEccSilentParsingLabel->set_label(I18N::get('popupConfig', 'confEccSilentParsing'));

		// ECC v1.13 Build 4-8
		$this->eccVideoPlayer_enableLabel->set_label(I18N::get('popupConfig', 'eccVideoPlayer_enable'));
		$this->eccVideoPlayer_soundLabel->set_label(I18N::get('popupConfig', 'eccVideoPlayer_sound'));
		$this->eccVideoPlayer_soundvolumeLabel->set_label(I18N::get('popupConfig', 'eccVideoPlayer_soundvolume'));
		$this->eccVideoPlayer_loopLabel->set_label(I18N::get('popupConfig', 'eccVideoPlayer_loop'));
		$this->eccVideoPlayer_resolutionLabel->set_label(I18N::get('popupConfig', 'eccVideoPlayer_resolution'));
		$this->eccVideoPlayer_paddingLabel->set_label(I18N::get('popupConfig', 'eccVideoPlayer_padding'));

		// ECC v1.13 Build 12
		$this->tabGeneralImageTabOptions->set_markup('<b>'.I18N::get('popupConfig', 'tabGeneralImageTabOptions').'</b>');
		$this->tabGeneralImageTabTcuttLabel->set_text(I18N::get('popupConfig', 'tabGeneralImageTabTcuttLabel'));
		$this->tabGeneralParsingUnpackingOptions->set_markup('<b>'.I18N::get('popupConfig', 'tabGeneralParsingUnpackingOptions').'</b>');
		$this->tabGeneralParsingTriggerLabel->set_text(I18N::get('popupConfig', 'tabGeneralParsingTriggerLabel'));
		$this->tabGeneralParsingTriggerNoteLabel->set_text(I18N::get('popupConfig', 'tabGeneralParsingTriggerNoteLabel'));
		$this->ThemeSelectLabel->set_text(I18N::get('popupConfig', 'ThemeSelectLabel'));

		// ECC v1.152 Build 05
		$this->tabGeneralUnpackGUITriggerLabel->set_text(I18N::get('popupConfig', 'tabGeneralUnpackGUITriggerLabel'));
		$this->tabGeneralUnpackGUITriggerNoteLabel->set_text(I18N::get('popupConfig', 'tabGeneralUnpackGUITriggerNoteLabel'));

		// ECC v1.152 Build 06
		$this->lblUseThemeColors->set_text(I18N::get('popupConfig', 'lblUseThemeColors'));
		
		#$this->lbl_ecc_startup_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_startup_hdl').'</b>');
		#$this->cfgEccStartupConf->set_label(I18N::get('popupConfig', 'btn_ecc_startup'));

		$iniManager = FACTORY::get('manager/IniFile');
		$this->globalIni = $iniManager->getIniGlobalWithoutPlatforms();
		unset($this->globalIni['NAVIGATION']);

		$user_folder = $iniManager->getKey('USER_DATA', 'base_path');
		if (!$user_folder || !realpath($user_folder)) {
			FACTORY::get('manager/Gui')->openDialogInfo(i18n::get('global', 'error_title'), '<b>Userfolder not valid!!!</b>', false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
			#$this->configErrorLabel->set_markup('<b>Userfolder not valid!!!</b>');
		}
		$this->confEccUserPath->set_text($user_folder);

		// ECC v1.13 Build 12
		$this->extProgDaemontoolsTextbox->set_text($iniManager->getKey('DAEMONTOOLS', 'daemontools_exe'));

		$text_cuttoff = $iniManager->getKey('USER_SWITCHES', 'text_cuttoff');
		if ($text_cuttoff < 0 or $text_cuttoff > 100 or $text_cuttoff == "" or !is_numeric($text_cuttoff)) { //set default
			$this->tabGeneralImageTabTcuttValue->set_text("50"); //Default value
		} else{
				$this->tabGeneralImageTabTcuttValue->set_text($text_cuttoff);
		}

		$ExtParserTriggerSize_MB = $iniManager->getKey('USER_SWITCHES', 'ext_parser_trigger_size');
		if ($ExtParserTriggerSize_MB < 1 or $ExtParserTriggerSize_MB > 99999 or $ExtParserTriggerSize_MB == "" or !is_numeric($ExtParserTriggerSize_MB)) { //set default
			$this->tabGeneralParsingTriggerValue->set_text("100"); //Default value
		} else{
			$this->tabGeneralParsingTriggerValue->set_text($ExtParserTriggerSize_MB);
		}

		// ECC v1.152 Build 05	
		$UnpackGuiTriggerSize_MB = $iniManager->getKey('USER_SWITCHES', 'unpack_gui_trigger_size');
		if ($UnpackGuiTriggerSize_MB < 1 or $UnpackGuiTriggerSize_MB > 99999 or $UnpackGuiTriggerSize_MB == "" or !is_numeric($UnpackGuiTriggerSize_MB)) { //set default
			$this->tabGeneralUnpackGUITriggerValue->set_text("50"); //Default value
		} else{
			$this->tabGeneralUnpackGUITriggerValue->set_text($UnpackGuiTriggerSize_MB);
		}		
		
		// ECC v1.152 Build 06
		$cfgUseThemeColors = $iniManager->getKey('ECC_THEME', 'use_theme_colors');
		$this->cfgUseThemeColors->set_active($cfgUseThemeColors);
		
		$this->languages = $iniManager->getLanguageFromI18Folders();
		$languages = I18n::translateArray('languages', $this->languages, true);
		$selectedLanguage =  $iniManager->getKey('USER_DATA', 'language');
		$languageId =  array_search($selectedLanguage, $this->languages);
		$void = new IndexedCombobox($this->confEccLanguage, false, $languages, false, $languageId);

		$this->perPageDetail = array(
			'10',
			'25',
			'50',
			'100',
			'500',
			'1000',
		);
		$selected =  $iniManager->getKey('USER_SWITCHES', 'show_media_pp');
		#if ($selected > 100) $selected = 100;
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
			'10000',
			'100000',
		);
		$selected =  $iniManager->getKey('USER_SWITCHES', 'media_perpage_list');
		$index =  array_search($selected, $this->perPage);
		$void = new IndexedCombobox($this->cfgEccListPerPage, false, $this->perPage, false, $index);

		$mngrValidator = FACTORY::get('manager/Validator');
		$eccHelpLocations = $mngrValidator->getEccCoreKey('eccHelpLocations');

		$logDetails = $iniManager->getKey('USER_SWITCHES', 'log_details');
		$this->confEccStatusLogCheck->set_active($logDetails);

		$confEccSaveViewSettings = $iniManager->getKey('USER_SWITCHES', 'confEccSaveViewSettings');
		$this->confEccSaveViewSettings->set_active($confEccSaveViewSettings);

		$confEccSilentParsing = $iniManager->getKey('USER_SWITCHES', 'confEccSilentParsing');
		$this->confEccSilentParsing->set_active($confEccSilentParsing);

		// *** Video Player settings ***
		// ENABLE
		$eccVideoPlayer_enable = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_enable');
		$this->eccVideoPlayer_enable->set_active($eccVideoPlayer_enable);

		// SOUND
		$eccVideoPlayer_sound = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_sound');
		$this->eccVideoPlayer_sound->set_active($eccVideoPlayer_sound);

		$eccVideoPlayer_soundvolume = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_soundvolume');
		if($eccVideoPlayer_soundvolume == "" or !is_numeric($eccVideoPlayer_soundvolume)){
			$this->eccVideoPlayer_soundvolume->set_text("70"); //Default value
		}
		else{
			if($eccVideoPlayer_soundvolume < 200){
				$this->eccVideoPlayer_soundvolume->set_text($eccVideoPlayer_soundvolume);
			}
			else{
				$this->eccVideoPlayer_soundvolume->set_text("200"); //MAX value
			}
		}

		// LOOP
		$eccVideoPlayer_loop = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_loop');
		$this->eccVideoPlayer_loop->set_active($eccVideoPlayer_loop);

		// RESOLUTION
		$eccVideoPlayer_resx = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_resx');
		if($eccVideoPlayer_resx == "" or $eccVideoPlayer_resx == "0" or !is_numeric($eccVideoPlayer_resx)){
			$this->eccVideoPlayer_resx->set_text("300"); //Default value
		}
		else{
			$this->eccVideoPlayer_resx->set_text($eccVideoPlayer_resx);
		}

		$eccVideoPlayer_resy = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_resy');
		if($eccVideoPlayer_resy == "" or $eccVideoPlayer_resy == "0" or !is_numeric($eccVideoPlayer_resy)){
			$this->eccVideoPlayer_resy->set_text("300"); //Default value
		}
		else{
			$this->eccVideoPlayer_resy->set_text($eccVideoPlayer_resy);
		}

		// PADDING
		$eccVideoPlayer_padx = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_padx');
		if($eccVideoPlayer_padx == "" or $eccVideoPlayer_padx == "0" or !is_numeric($eccVideoPlayer_padx)){
			$this->eccVideoPlayer_padx->set_text("30"); //Default value
		}
		else{
			$this->eccVideoPlayer_padx->set_text($eccVideoPlayer_padx);
		}

		$eccVideoPlayer_pady = $iniManager->getKey('VIDEOPLAYER', 'eccVideoPlayer_pady');
		if($eccVideoPlayer_pady == "" or $eccVideoPlayer_pady == "0" or !is_numeric($eccVideoPlayer_pady)){
			$this->eccVideoPlayer_pady->set_text("20"); //Default value
		}
		else{
			$this->eccVideoPlayer_pady->set_text($eccVideoPlayer_pady);
		}

		$logFileDir = ECC_DIR.'/'.$eccHelpLocations['LOG_DIR'];
		if (is_dir($logFileDir)) {
			$this->confEccStatusLogOpen->connect_simple('clicked', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $logFileDir, false);
		}
		else {
			mkdir($logFileDir);
			$this->confEccStatusLogOpen->set_sensitive(false);
		}
	}


	public function storeEccData($hidePopup = true) {
		# USER_DATA
		$this->globalIni['USER_DATA']['base_path'] = $this->confEccUserPath->get_text();
		$this->globalIni['USER_DATA']['language'] = $this->languages[$this->confEccLanguage->get_active()];
		$this->globalIni['ECC_THEME']['use_theme_colors'] = $this->cfgUseThemeColors->get_active();

		# USER_SWITCHES
		$this->globalIni['USER_SWITCHES']['show_media_pp'] = $this->perPageDetail[$this->cfgEccDetailPerPage->get_active_text()];
		$this->globalIni['USER_SWITCHES']['media_perpage_list'] = $this->perPage[$this->cfgEccListPerPage->get_active_text()];
		$this->globalIni['USER_SWITCHES']['log_details'] = $this->confEccStatusLogCheck->get_active();
		$this->globalIni['USER_SWITCHES']['text_cuttoff'] = $this->tabGeneralImageTabTcuttValue->get_text();
		$this->globalIni['USER_SWITCHES']['ext_parser_trigger_size'] = $this->tabGeneralParsingTriggerValue->get_text();
		$this->globalIni['USER_SWITCHES']['unpack_gui_trigger_size'] = $this->tabGeneralUnpackGUITriggerValue->get_text();
		$this->globalIni['USER_SWITCHES']['confEccSaveViewSettings'] = $this->confEccSaveViewSettings->get_active();
		$this->globalIni['USER_SWITCHES']['confEccSilentParsing'] = $this->confEccSilentParsing->get_active();

		# External Programs
		$this->globalIni['DAEMONTOOLS']['daemontools_exe'] = $this->extProgDaemontoolsTextbox->get_text();

		# Video settings
		$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_enable'] = $this->eccVideoPlayer_enable->get_active();
		$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_sound'] = $this->eccVideoPlayer_sound->get_active();

		$eccVideoPlayer_soundvolume_i = $this->eccVideoPlayer_soundvolume->get_text(); //Replace a invalid value with the default one!
		if($eccVideoPlayer_soundvolume_i == "" or !is_numeric($eccVideoPlayer_soundvolume_i)){
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_soundvolume'] = "70"; //Default value
		}
		else{
			if($eccVideoPlayer_soundvolume_i < 200){
				$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_soundvolume'] = trim($this->eccVideoPlayer_soundvolume->get_text());
			}
			else{
				$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_soundvolume'] = "200"; //MAX value;
			}
		}

		$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_loop'] = $this->eccVideoPlayer_loop->get_active();

		$eccVideoPlayer_resx_i = $this->eccVideoPlayer_resx->get_text(); //Replace a invalid value with the default one!
		if($eccVideoPlayer_resx_i == "" or $eccVideoPlayer_resx_i == "0" or !is_numeric($eccVideoPlayer_resx_i)){
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_resx'] = "300"; //Default value
		}
		else{
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_resx'] = trim($this->eccVideoPlayer_resx->get_text());
		}

		$eccVideoPlayer_resy_i = $this->eccVideoPlayer_resy->get_text(); //Replace a invalid value with the default one!
		if($eccVideoPlayer_resy_i == "" or $eccVideoPlayer_resy_i == "0" or !is_numeric($eccVideoPlayer_resy_i)){
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_resy'] = "300"; //Default value
		}
		else{
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_resy'] = trim($this->eccVideoPlayer_resy->get_text());
		}

		$eccVideoPlayer_padx_i = $this->eccVideoPlayer_padx->get_text(); //Replace a invalid value with the default one!
		if($eccVideoPlayer_padx_i == "" or $eccVideoPlayer_padx_i == "0" or !is_numeric($eccVideoPlayer_padx_i)){
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_padx'] = "30"; //Default value
		}
		else{
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_padx'] = trim($this->eccVideoPlayer_padx->get_text());
		}

		$eccVideoPlayer_pady_i = $this->eccVideoPlayer_pady->get_text(); //Replace a invalid value with the default one!
		if($eccVideoPlayer_pady_i == "" or $eccVideoPlayer_pady_i == "0" or !is_numeric($eccVideoPlayer_pady_i)){
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_pady'] = "20"; //Default value
		}
		else{
			$this->globalIni['VIDEOPLAYER']['eccVideoPlayer_pady'] = trim($this->eccVideoPlayer_pady->get_text());
		}
	}


	public function initGuiData() {
		if (!$this->guiDataInit) $this->guiDataInit = true;
		else return true;

		#$this->lbl_ecc_colfont_hdl->set_markup('<b>'.I18N::get('popupConfig', 'lbl_ecc_colfont_hdl').'</b>');
		#$this->lbl_ecc_colfont_font_list->set_text(I18N::get('popupConfig', 'lbl_ecc_colfont_font_list'));
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

		# imageCenter
		$color = $iniManager->getKey('GUI_COLOR', 'colImgSlotUnsetBgChooser');
		if (!$color) $color = '#EAEAEA';
		$this->colImgSlotUnsetBgChooser->set_color(GdkColor::parse($color));

		$color = $iniManager->getKey('GUI_COLOR', 'colImgSlotSetSelectChooser');
		if (!$color) $color = '#CECECE';
		$this->colImgSlotSetSelectChooser->set_color(GdkColor::parse($color));

		$color = $iniManager->getKey('GUI_COLOR', 'colImgSlotSetBgChooser');
		if (!$color) $color = '#CCDDC6';
		$this->colImgSlotSetBgChooser->set_color(GdkColor::parse($color));

		$color = $iniManager->getKey('GUI_COLOR', 'colImgSlotUnsetSelectChooser');
		if (!$color) $color = '#A2AF9D';
		$this->colImgSlotUnsetSelectChooser->set_color(GdkColor::parse($color));

		$color = $iniManager->getKey('GUI_COLOR', 'colImgSlotTextChooser');
		if (!$color) $color = '#000000';
		$this->colImgSlotTextChooser->set_color(GdkColor::parse($color));
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

		# imageCenter
		$this->globalIni['GUI_COLOR']['colImgSlotUnsetBgChooser'] = $this->getGdkColorHex($this->colImgSlotUnsetBgChooser->get_color());
		$this->globalIni['GUI_COLOR']['colImgSlotSetSelectChooser'] = $this->getGdkColorHex($this->colImgSlotSetSelectChooser->get_color());
		$this->globalIni['GUI_COLOR']['colImgSlotSetBgChooser'] = $this->getGdkColorHex($this->colImgSlotSetBgChooser->get_color());
		$this->globalIni['GUI_COLOR']['colImgSlotUnsetSelectChooser'] = $this->getGdkColorHex($this->colImgSlotUnsetSelectChooser->get_color());
		$this->globalIni['GUI_COLOR']['colImgSlotTextChooser'] = $this->getGdkColorHex($this->colImgSlotTextChooser->get_color());

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
		$this->lbl_img_otp_list_imagesize_default->set_text('('.I18N::get('popupConfig', 'lbl_img_otp_list_imagesize_default').')');
		$this->lbl_img_opt_list_aspectratio->set_text(I18N::get('popupConfig', 'lbl_img_otp_list_aspectratio'));
		$this->lbl_img_otp_list_aspectratio_default->set_text('('.I18N::get('popupConfig', 'lbl_img_otp_list_aspectratio_default').')');
		$this->lbl_img_otp_list_fastrefresh->set_label(I18N::get('popupConfig', 'lbl_img_otp_list_fastrefresh'));
		$this->lbl_img_otp_list_fastrefresh_default->set_label('('.I18N::get('popupConfig', 'lbl_img_otp_list_fastrefresh_default').')');

		$iniManager = FACTORY::get('manager/IniFile');

		$this->imageSizes = array(
			'120x80',
			'180x120',
			'240x160',
			'300x200',
			'360x240',
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
			'20',
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
		$path_new = $oOs->openChooseFolderDialog($path, $title, false);
		$path_new = $oOs->eccSetRelativeDir($path_new);
		if ($path_new) $this->confEccUserPath->set_text($path_new);
	}

	public function extProgDaemontoolsFind() {
		$oOs = FACTORY::get('manager/Os');
		$path = realpath($this->extProgDaemontoolsTextbox->get_text());
		//$title = I18N::get('popupConfig', 'title_ecc_userfolder_popup');
		$path_new = $oOs->openChooseFileDialog($path, "Please locate Daemontools", array('exe (*.exe)' => '*.exe'));
		if ($path_new) $this->extProgDaemontoolsTextbox->set_text($path_new);
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
		$this->confEccDatNameStripLabel->set_label(I18N::get('popupConfig', 'lbl_dat_opt_namestrip'));

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
		$this->startConfSoundPreview->connect_simple_after('clicked', array($this, 'onButtonPreviewSound'));
		$this->startConfSoundCheck->connect_simple_after('toggled', array($this, 'updateEccSoundState'));

		$this->startConfSoundHdl->set_label(I18N::get('popupConfig', 'startConfSoundHdl'));
		$this->startConfOptHdl->set_markup('<b>'.I18N::get('popupConfig', 'startConfOptHdl').'</b>');
		$this->startConfUpdateLabel->set_label(I18N::get('popupConfig', 'startConfUpdate'));
		$this->startConfMinimizeLabel->set_label(I18N::get('popupConfig', 'startConfMinimize'));
		$this->startConfDeleteUnpackedLabel->set_label(I18N::get('popupConfig', 'startConfDeleteUnpacked'));
		$this->startConfBugreportSendLabel->set_label(I18N::get('popupConfig', 'startConfBugreportSend'));
		$this->startConfSoundSelect->set_label(I18N::get('popupConfig', 'startConfSoundSelect'));
		$this->startConfSoundPreview->set_label(I18N::get('global', 'preview'));

		// Third Party
		$this->startConfThirdPartyHdl->set_markup('<b>'.I18N::get('popupConfig', 'startConfThirdPartyHdl').'</b>');
		$this->startConfThirdPartyXpadderLabel->set_label(I18N::get('popupConfig', 'startConfThirdPartyXpadder'));
		$iniManager = FACTORY::get('manager/IniFile');

		$sectionExists = $iniManager->getKey('ECC_STARTUP');

		$optSound = $iniManager->getKey('ECC_STARTUP', 'startup_sound');

		$optSound = (!$sectionExists) ? 'ecc-system/sound/ecc_sound_startup_low_volume.mp3' : $optSound;

		$this->startConfSoundPath->set_text($optSound);

		$optUpdate = $iniManager->getKey('ECC_STARTUP', 'startup_update_check');
		$optUpdate = (!$sectionExists) ? true : $optUpdate;
		$this->startConfUpdate->set_active($optUpdate);

		$optMinimize = $iniManager->getKey('ECC_STARTUP', 'minimize_to_tray');
		$optMinimize = (!$sectionExists) ? true : $optMinimize;
		$this->startConfMinimize->set_active($optMinimize);

		$OptDeleteUnpacked = $iniManager->getKey('ECC_STARTUP', 'delete_unpacked');
		$OptDeleteUnpacked = (!$sectionExists) ? true : $OptDeleteUnpacked;
		$this->startConfDeleteUnpacked->set_active($OptDeleteUnpacked);

		# send automatic bugreport on startup
		$optBugreportSend = $iniManager->getKey('ECC_STARTUP', 'startup_bugreport_check');
		$optBugreportSend = (!$sectionExists) ? true : $optBugreportSend;
		$this->startConfBugreportSend->set_active($optBugreportSend);

		// Third Party
		$optStartExpadder = $iniManager->getKey('ECC_STARTUP', 'startup_xpadder');
		$optStartExpadder = ($optStartExpadder === false || !$sectionExists) ? true : $optStartExpadder;
		$this->startConfThirdPartyXpadder->set_active($optStartExpadder);


		$this->updateEccSoundState(true);
	}

	public function storeStartupData($hidePopup = true) {
		$this->globalIni['ECC_STARTUP']['startup_sound'] = trim($this->startConfSoundPath->get_text());
		$this->globalIni['ECC_STARTUP']['startup_update_check'] = (int)$this->startConfUpdate->get_active();
		$this->globalIni['ECC_STARTUP']['minimize_to_tray'] = (int)$this->startConfMinimize->get_active();
		$this->globalIni['ECC_STARTUP']['delete_unpacked'] = (int)$this->startConfDeleteUnpacked->get_active();
		$this->globalIni['ECC_STARTUP']['startup_bugreport_check'] = (int)$this->startConfBugreportSend->get_active();
		$this->globalIni['ECC_STARTUP']['startup_xpadder'] = (int)$this->startConfThirdPartyXpadder->get_active();
	}

	public function updateEccSoundState($init=false){
		if ($init){
			$state = (trim($this->startConfSoundPath->get_text())) ? true : false;
			$this->startConfSoundCheck->set_active($state);
		}
		else $state = $this->startConfSoundCheck->get_active();

		$this->startConfSoundSelect->set_sensitive($state);
		if(!$init && !$state) $this->startConfSoundPath->set_text('');

//		$previewState = (realpath($this->startConfSoundPath->get_text())) ? true : false;
//		$this->startConfSoundPreview->set_sensitive($previewState);
	}

	public function onButtonChooseSound($gtkEntry) {
		$iniManager = FACTORY::get('manager/IniFile');
		$path = realpath($gtkEntry->get_text());
		$title = sprintf(I18N::get('popupConfig', 'title_startup_select_sound'));

		$shorcutFolder = $iniManager->getShortcutPaths();
		$newPath = FACTORY::get('manager/Os')->openChooseFileDialog($path, $title, array('wav (*.wav)' => '*.wav', 'mp3 (*.mp3)' => '*.mp3'), false, false, $shorcutFolder);

		if ($newPath && realpath($newPath)) {
			$this->startConfSoundCheck->set_active(true);
			$gtkEntry->set_text(FACTORY::get('manager/Os')->eccSetPathRelative($newPath, false));
		}

//		$previewState = (realpath($this->startConfSoundPath->get_text())) ? true : false;
//		$this->startConfSoundPreview->set_sensitive($previewState);
	}

	public function onButtonPreviewSound(){
		$eccLoc = FACTORY::get('manager/Validator')->getEccCoreKey('eccHelpLocations');

		$eccStartExe = realpath(ECC_DIR.'/'.$eccLoc['ECC_EXE_START']);
		$soundFile = $this->startConfSoundPath->get_text();
		FACTORY::get('manager/Os')->executeProgramDirect($eccStartExe, 'open', '/sndprev "'.$soundFile.'"');
	}


	public function initThemeData() {

		$iniManager = FACTORY::get('manager/IniFile');
		$themeManager = FACTORY::get('manager/GuiTheme');

		$selectedEccTheme =  $iniManager->getKey('ECC_THEME', 'ecc-theme');
		$this->availableEccThemes = $themeManager->getAvailableEccThemes();
		$themeId =  array_search($selectedEccTheme, $this->availableEccThemes);
		$void = new IndexedCombobox($this->comboEccTheme, false, $this->availableEccThemes, false, $themeId);

		# initial update preview
		$this->updateThemePreview($selectedEccTheme);

		$this->comboEccTheme->connect_after("changed", array($this, 'updateThemePreview'));


	}

	public function storeThemeData($hidePopup = true) {
		$this->globalIni['ECC_THEME']['ecc-theme'] = $this->availableEccThemes[$this->comboEccTheme->get_active()];
	}

	public function updateThemePreview($object){

		# if this is an selection of the combobox
		if(is_object($object) && $object->get_name() == 'comboEccTheme'){
			$selectedTheme = $this->availableEccThemes[$object->get_active_text()];
		}
		else{
			# if direct call
			$selectedTheme = $object;
		}

		$themeManager = FACTORY::get('manager/GuiTheme');

		#$selectedTheme = $this->availableEccThemes[$object->get_active_text()];
		if($path = $themeManager->getEccThemePreviewPath($selectedTheme)){
			$this->themePreviewImage->set_from_pixbuf(FACTORY::get('manager/GuiHelper')->getPixbuf($path));
		}

		$imageObject = FACTORY::get('manager/Image');
		$imageObject->setWidgetBackground($this->guiPopConfig, 'background/main.png', $selectedTheme);
		$imageObject->setWidgetBackground($this->themeInfoPreview, 'background/box.png', $selectedTheme);

		$themeInfo = $themeManager->getEccThemeInfo($selectedTheme);

		$this->tableThemeInfo->set_visible($themeInfo['has_info']);

		$this->textThemeName->set_markup('<b>'.$themeInfo['name'].'</b>');
		$this->textThemeAuthor->set_text($themeInfo['author']);
		$this->textThemeContact->set_text($themeInfo['contact']);
		$this->textThemeWebsite->set_text($themeInfo['website']);

		$date = ($themeInfo['date']) ? date('Y.m.d', strtotime($themeInfo['date'])) : '';
		$this->textThemeDate->set_text($date);

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