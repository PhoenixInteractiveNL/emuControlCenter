<?php
chdir(dirname(__FILE__));
define("MY_MASK", Gdk::BUTTON_PRESS_MASK);

// static class for generating comboboxes
require_once('manager/cIndexedCombobox.php');
// old singleton required for parsing process
#require_once('manager/cSingleton.php');
// new singleton factory
require_once('manager/cFactory.php');
// static class for translation
require_once('manager/ci18n.php');

// i18n - not working for glade files :-()
// using gettext extension!!!!!!
// msgfmt -o glade_strings.mo glade_strings.po
//$language = 'de_DE';
//putenv("LANG=$language"); 
//setlocale(LC_ALL, $language);
//bindtextdomain('glade_strings', 'i18n/locale/');
//textdomain('glade_strings');
//echo _("Import emuControlCenter Datfile")."\n";
//echo _("<b>eSearch - more search options</b>")."\n";

/*
*
*/
class App extends GladeXml {
	
	public $ecc_release = array(
		'release_version' => 'v0.5',
		'release_build' => 'build 887',
		'release_state' => '(beta)',
		'website' => 'http://www.camya.de/ecc/',
		'email' => 'ecc@camya.de',
		'eccdat_version' => '0.95',
		'eccdat_state' => 'beta',
	);
	
	public $ini = false;
	
	public $os_env = "";
	
	private $dbms = false;
	private $_fileView = false;
	
	private $nav_inactive_hidden = false;
	private $nav_autoupdate = false;
	
	private $_result_offset = 0;
	private $_results_per_page = 10;
	private $_eccident = false;
	private $file_list_count = 0;
	
	private $_search_active = array();
	private $_search_word = "";
	private $_search_word_last = "";
	private $_search_word_like_pre = false;
	private $_search_word_like_post = false;
	private $_search_language = false;
	private $_search_category = false;
	private $ext_search_selected = array();
	
	// caches versions of pixbufs
	// $this->pixbuf_tank[type][ident] = pixbuf
	private $pixbuf_tank = array();
	
	// default sizes for mainview images
	// could be configured in ecc_general.ini
	// set by set_ecc_image_size_from_ini at startup
	private $_pixbuf_width = 120;
	private $_pixbuf_height = 80;
	
	private $_img_show_pos = 0;
	private $_img_show_count = 0;

	private $images_inactiv = false;
	private $images_unsaved_only = false;
	private $image_tank = array();
	private $currentImageTank = array();
	
	
	public $list_nav = array();
	public $model_navigation = false;
	
	public $view_mode = 'MEDIA';
	
	public $data_available = false;
	
	public $user_path_subfolder_default = array(
		'emus',
		'exports',
		'images',
		'roms',
	);
	
	private $supported_images = array(
		'jpg' => 1,
		'png' => 1,
		'gif' => 1,
	);
	
	public $cbox_yesno = array(
		'*',
		'no',
		'no?',
		'yes',
		'ye?',
		'?',
	);
	
	public $image_type = array(
		'ingame_title' => 'Ingame - Title',
		'ingame_play_01' => 'Ingame - Play 1',
		'ingame_play_02' => 'Ingame - Play 2',
		'ingame_play_03' => 'Ingame - Play 3',
		'cover_front' => 'Cover-Art: Front',
		'cover_back' => 'Cover-Art: Back',
		'booklet_page_01' => 'Booklet: Page 01',
		'booklet_page_02' => 'Booklet: Page 02',
		'booklet_page_03' => 'Booklet: Page 03',
		'booklet_page_04' => 'Booklet: Page 04',
		'booklet_page_05' => 'Booklet: Page 05',
		'booklet_page_06' => 'Booklet: Page 06',
		'booklet_page_07' => 'Booklet: Page 07',
		'booklet_page_08' => 'Booklet: Page 08',
		'booklet_page_09' => 'Booklet: Page 09',
		'booklet_page_10' => 'Booklet: Page 10',
	);
	public $image_type_selected = false;
	
	/*
	*
	*/
	public $media_language = array(
		'J' => '[[japanese]]',
		'E' => '[[english]]',
		'G' => '[[german]]',
		'F' => '[[french]]',
		'I' => '[[italian]]',
		'S' => '[[spanish]]',
		'C' => '[[chinese]]',
		'KOR' => '[[korean]]',
		'P' => '[[portugese]]',
		'PL' => '[[polish]]',
		'DK' => '[[danish]]',
		'NL' => '[[dutch]]',
		'NO' => '[[norway]]',
		'SW' => '[[swedish]]',
		'FN' => '[[finnish]]',
	);
	
	/**
	 * only append data!!!!
	 */
	public $media_category = array(
		"",
		"Action",
		"Adventure",
		"Arcade",
		"Beat'em Up",
		"Board",
		"Card Game",
		"Casino",
		"Compilation",
		"Demo",
		"Dictionary",
		"Educational",
		"Fighting",
		"Fishing",
		"Flight Sim",
		"FPS",
		"Hardware",
		"Hunting",
		"Intro",
		"Logical",
		"Mahjong",
		"Mini-Games",
		"Music",
		"Party",
		"Pinball",
		"Jump'n Run",
		"Puzzle",
		"Racing",
		"RPG",
		"Shoot'em Up",
		"Shooting",
		"Simulation",
		"Slot Machine",
		"Sports",
		"Strategy",
		"Tool",
		"Video",
		"XXX",
	);
	
	private	$ext_search_combos = array(
		'scb_running',
		'scb_multiplayer',
		'scb_trainer',
		'scb_intro',
		'scb_freeware',
		'scb_usermod',
		'scb_bugs',
		'scb_netplay',
	);
	
	public $fs_path_for_parser = false;
	
	public $toggle_show_files_only = false;
	public $toggle_show_metaless_roms_only = false;
	public $toggle_show_doublettes = false;
	
	// Colors
	public $background_color='#ffffff';
	
	public $nb_main_page_selected = 0;
	
	private $media_edit_is_opened = false;
	
	public $currentPlatformCategory = false;
	
	public function create_combo_lanugages()
	{
		$combobox = new IndexedCombobox();
		
		$data = array(
			'indent' => array(
				'renderer' => 'text',
				'visible' => false,
			),
			'icon' => array(
				'renderer' => 'pixbuf',
				'visible' => true,
			),
			'label' => array(
				'renderer' => 'text',
				'visible' => true,
			),
		);
		$combobox->init_combobox($this->cb_search_language, $data);
		
		// insert data
		$lang = array();
		$lang[] = array(
			false,
			GdkPixbuf::new_from_file(dirname(__FILE__)."/"."images/eccsys/languages/ecc_lang_unknown.png"),
			'ALL',
		);
		foreach($this->media_language as $indent => $label) {
			
			$img_path = dirname(__FILE__)."/".'images/eccsys/languages/ecc_lang_'.strtolower($indent).'.png';
			if (!file_exists($img_path)) $img_path = dirname(__FILE__)."/".'images/eccsys/languages/ecc_lang_unknown.png';
			
			$lang[] = array(
				$indent,
				GdkPixbuf::new_from_file($img_path),
				$label,
			);
		} 
		
		$combobox->fill($lang);
		$this->cb_search_language->connect("changed", array($this, 'set_search_language_from_combobox'));
	}
	
	public function set_search_language_from_combobox($combobox) {
		
		$this->nb_main->set_current_page(0);
		
		$this->_search_language = $combobox->get_active_text();
		
		$state = ($this->_search_language) ? true : false;
		$this->set_search_state('language', $state);
		
		if ($this->nav_autoupdate) $this->update_treeview_nav();
		
		$this->onInitialRecord();
	}
	
	public function set_search_category_from_combobox($combobox) {
		
		$this->nb_main->set_current_page(0);
		
		$this->_search_category = $this->get_category_id($combobox->get_active_text());
		$state = ($combobox->get_active_text()) ? true : false;
		$this->set_search_state('category', $state);
		
		if ($this->nav_autoupdate) $this->update_treeview_nav();
		
		$this->onInitialRecord();
	}
	
	public function set_search_state($ident, $state) {
		$this->_search_active[$ident] = $state;
		$this->search_input_reset->set_sensitive($this->get_search_state());
	}
	
	public function get_search_state() {
		
		foreach ($this->_search_active as $ident => $state) {
			if ($state === true) {
				return true;
			}
		}
		$this->reset_search_state();
		return false;
	}
	
	public function reset_search_state() {
		$this->search_input_reset->set_sensitive(false);
		$this->_search_active = array();;
	}
	
	/*
	* ext_search
	* functionen for ext search
	*/
	public function dispatcher_ext_search($obj) {
		
		$this->nb_main->set_current_page(0);
		
		$state = $obj->get_active_text();
		$this->ext_search_selected[$obj->get_name()] = $state;
		$state = $this->ini->write_ecc_histroy_ini($obj->get_name(), $state, false);
		$this->ext_search_reset->set_sensitive(true);
		
		// now control, if any dropdown is selected.
		// if all set to item 0, reset
		if (!$this->get_ext_search_state()) $this->reset_ext_search_state();
		
		if ($this->nav_autoupdate) $this->update_treeview_nav();
		
		$this->onInitialRecord();
	}
	
	public function get_ext_search_state() {
		foreach ($this->ext_search_selected as $ident => $state) {
			if ($state) {
				$this->ext_search_expander_lbl->set_markup('<b>eSearch - more search options</b> <span color="#cc0000">(eSearch active!!!)</span>');
				return true;
			}
		}
		return false;
	}
	public function reset_ext_search_state() {
		foreach ($this->ext_search_selected as $ident => $state) {
			$this->$ident->set_active(0);
		}
		$this->ext_search_reset->set_sensitive(false);
		
		$this->ext_search_expander_lbl->set_markup('<b>eSearch - more search options</b>');
		
		$this->ext_search_selected = array();
	}
	
	public function update_inline_help($textview, $filenames=false) {
		if (!$textview) return false;
		if (!is_array($filenames)) return false;
		
		$text = "";
		foreach ($filenames as $file) {
			if (file_exists($file)) {
				$text .= file_get_contents($file)."\n\n";
			}
			else {
				$text .= '\n### Missing inline-help-file "'.$file.'" ###\n';
			}
		}
		$buffer = new GtkTextBuffer();
		$buffer->set_text(trim($text));
		$textview->set_buffer($buffer);
	}
	
	/**
	 * get user-switch from ini and setup image-size for mainview
	 * If the user-switch is missing, use the default values set in
	 * member-vars
	 * 
	 */	
	private function set_ecc_image_size_from_ini() {
		$image_size = $this->ini->get_ecc_ini_key('USER_SWITCHES', 'image_mainview_size');
		
		// check, if valid
		if (!$image_size | !strpos($image_size, 'x')) return FALSE;
		$split = explode("x", $image_size);	
		if (count($split)!=2) return FALSE;
		
		// all right, set new values
		$this->_pixbuf_width = (int)$split[0];
		$this->_pixbuf_height = (int)$split[1];
	}
	
	/*
	*
	*/
	public function __construct()
	{
		// ----------------------------------------------------------------
		// ABS-PATH TO REL-PATH...
		// ----------------------------------------------------------------
		define('ECC_BASEDIR_OFFSET', "..");
		define('ECC_BASEDIR', realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.ECC_BASEDIR_OFFSET));

		// ----------------------------------------------------------------
		// INI get ecc main ini-file
		// ----------------------------------------------------------------
		$this->ini = FACTORY::get('manager/IniFile', 'conf/');
		if ($this->ini === false) die();
		
		// ----------------------------------------------------------------
		// DBMS connect to database and fill FACTORY with dbms
		// ----------------------------------------------------------------
		$dbms = FACTORY::get('manager/DbmsSqlite2');
		$dbms->setConnectionPath('database/eccdb');
		$dbms->setConnectionMode('0666');
		$this->dbms = $dbms->connect();
		// INITIAL SET FACTORY DBMS
		// so all classes created by FACTORY::get()
		// which having a method setDbms() implemented gets
		// automaticly a dbms object assigned
		FACTORY::setDbms($dbms);

		// ----------------------------------------------------------------
		// I18N Initialize 
		// ----------------------------------------------------------------
		$language = $this->ini->get_ecc_ini_key('USER_DATA', 'language');
		I18N::set($language);
		// translate "language"-dropdown array!
		

		// ----------------------------------------------------------------
		// GUI/GLADE get gui from glade-file
		// ----------------------------------------------------------------
		parent::__construct(ECC_BASEDIR.'/ecc-system/gui2/gui.glade');
		$this->wdo_main->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->background_color));

		// ----------------------------------------------------------------
		// Get current operating system
		// ----------------------------------------------------------------
		$this->os_env = FACTORY::get('manager/Os')->getOperatingSystemInfos();
		
		// ----------------------------------------------------------------
		// get helper object
		// ----------------------------------------------------------------
		$oHelper = FACTORY::get('manager/GuiHelper', $this);
		// change background image
//		$oHelper->setBackgroundImage($this->media_nb_info_fixed, dirname(__FILE__)."/".'images/eccsys/testimages/bgtrans.png');
		// get ecc header image
		$oHelper->set_eccheader_image();
		// get operating system informations
		// show splashscreen only the first time!
		if (!$this->ini->read_ecc_histroy_ini('splashscreen_opened')) $oHelper->open_splash_screen();
		// create userfolder, if these are not in place!
		$oHelper->createUserfolderIfNeeded();
		// set title of the main window!
		$this->wdo_main->set_title("emuControlCenter");
		
		// ----------------------------
		// SET USER_SWITCHES FROM INI
		// ----------------------------
		// fast list refresh activated? 
		$this->fastListRefresh = $this->ini->get_ecc_ini_key('USER_SWITCHES', 'fast_list_refresh');
		// get size from the inifile!
		$this->set_ecc_image_size_from_ini();
		
		// ----------------------------
		// GuiImagePopup init
		// ----------------------------
		$this->oGuiImagePopup = FACTORY::get('manager/GuiImagePopup', $this);
		$this->image_preview_ebox->connect_simple('button-press-event', array($this, 'openImagePopup'), false);

		// ----------------------------
		// GuiEccConfig init
		// ----------------------------
		$this->oGuiEccConfig = FACTORY::get('manager/GuiEccConfig', $this);
		$this->oGuiEccConfig->update();

		// ----------------------------
		// GuiStatus init
		// ----------------------------		
		$this->status_obj = FACTORY::get('manager/GuiStatus', $this);

		// ----------------------------
		// HELP init
		// ----------------------------
		$this->update_inline_help($this->textview3, array('help/inline/general.txt'));
		
		// ----------------------------
		// CONNECT TOP MENU SIGNALS
		// ----------------------------
		$this->connectSignalsForTopMenu();

		// ----------------------------
		// EVENTBOXES CONNECT
		// ----------------------------
		
		$this->img_ecc_header_ebox->connect_simple('button-press-event', array(FACTORY::get('manager/GuiHelper'), 'open_splash_screen'));
		$this->img_plattform_ebox->connect_simple('button-press-event', array($this, 'setNotebookPage'), $this->nb_main, 1);
		
		// ----------------------------
		// init main bombo for languages
		// ----------------------------
		$this->media_language = I18n::translateArray('dropdown_lang', $this->media_language);
		$this->create_combo_lanugages();
		
		// ----------------------------
		// get saved data from hist ini
		// ----------------------------
		$this->images_inactiv = $this->ini->read_ecc_histroy_ini('images_inactiv');
		$this->nav_inactive_hidden = $this->ini->read_ecc_histroy_ini('nav_inactive_hidden');
		$this->nav_autoupdate = $this->ini->read_ecc_histroy_ini('nav_autoupdate');
		$pp = $this->ini->get_ecc_ini_key('USER_SWITCHES', 'show_media_pp');
		if ($pp) $this->_results_per_page = $pp;
		
		// ----------------------------
		// INIT search options
		// ----------------------------
		// language  dropdown
		$this->init_treeview_languages($this->test_language);
		// category dropdown
		if (!$this->obj_category1) $this->obj_category1 = new IndexedCombobox($this->cb_search_category, false, $this->media_category, 4);
		$this->cb_search_category->connect("changed", array($this, 'set_search_category_from_combobox'));
		
		// ----------------------------
		// extended search
		// ----------------------------
		foreach($this->ext_search_combos as $key => $name) {
			$obj_name = "o".$name;
			$state =  $this->ini->read_ecc_histroy_ini($name);
			$this->ext_search_selected[$name] = $state;
			if (!$this->$obj_name) $this->$obj_name = new IndexedCombobox($this->$name, false, $this->cbox_yesno, 1, $state);
			$this->$name->connect("changed", array($this, 'dispatcher_ext_search'));
		}
		$state = $this->get_ext_search_state();
		$this->ext_search_reset->set_sensitive($state);
		$this->ext_search_reset->connect_simple("clicked", array($this, 'reset_ext_search_state'));
		$this->ext_search_expander->set_expanded(false);
		
		// ----------------------------
		// TreeviewData init
		// ----------------------------		
		$this->_fileView = FACTORY::get('manager/TreeviewData');
		$this->init_treeview_nav();
		$treeview_nav_selection = $this->treeview1->get_selection();
		
		// ----------------------------
		// navigation_last / index
		// ----------------------------
		// navigation_last_index for treeview
		$selected_platform = $this->ini->read_ecc_histroy_ini('navigation_last_index');
		if (isset($selected_platform)) {
			$treeview_nav_selection->select_path((int)$selected_platform);
		}
		$treeview_nav_selection->set_mode(Gtk::SELECTION_BROWSE);
		$treeview_nav_selection->connect('changed', array($this, 'get_treeview_nav_selection'));
		// navigation_last for database
		$this->_eccident = $this->ini->read_ecc_histroy_ini('navigation_last');
		$ident = ($this->_eccident) ? $this->_eccident : 'null';
		$platform_name = $this->ini->get_ecc_platform_navigation($ident);
		$this->setEccident($this->_eccident, false);
		// set also platform name
		$this->setPlatformName($platform_name);
		$txt = '<b>'.htmlspecialchars($this->ecc_platform_name).'</b>';
		$this->nb_main_lbl_media->set_markup($txt);

		// ----------------------------
		// platform context menu init
		// ----------------------------		
		$this->treeview1->connect('button-release-event', array($this, 'show_popup_menu_platform'));

		// ----------------------------
		// Init main view with roms!
		// ----------------------------
		$this->init_treeview_main();
		
		// ----------------------------
		// TreeviewPager Init
		// ----------------------------		
		$this->media_treeview_pager = FACTORY::get('manager/TreeviewPager');
		
		// ----------------------------
		// INIT Category dropdown!
		// ----------------------------	
		$platformCategories = $this->ini->get_ecc_platform_categories();
		$this->dd_pf_categories = new IndexedCombobox($this->cbPlatformCategories, false, $platformCategories);
		$this->cbPlatformCategories->connect("changed", array($this, 'changePlatformCategory'));
		
		// ----------------------------
		// INIT NOTEBOCKS visibility
		// ----------------------------	
		$this->set_notebook_page_visiblility($this->nb_main, 0, true); // media
		$this->set_notebook_page_visiblility($this->nb_main, 1, $this->view_mode); // factsheet
		$this->set_notebook_page_visiblility($this->nb_main, 2, $this->_eccident); // config-emu
		$this->set_notebook_page_visiblility($this->nb_main, 3, !$this->_eccident); // config-ecc
		$this->set_notebook_page_visiblility($this->nb_main, 4, true); // help
		
		// ----------------------------
		// Update notebook pages
		// ----------------------------	
		$this->update_platform_edit($ident);
		$this->update_platform_info($ident);

		// ----------------------------
		// Special navigation beyond
		// normal platform navigation
		// ----------------------------			
		// bookmarks
		$this->btn_bookmarks->connect_simple('clicked', array($this, 'get_media_bookmarks'));
		// last launched
		$this->btn_last_launched->connect_simple('clicked', array($this, 'get_media_last_launched'));
		
		// ----------------------------
		// MEDIA-EDIT POPUP - signals
		// ----------------------------	
		$this->media_edit_btn_save->connect_simple('clicked', array($this, 'edit_media_save'));
		$this->media_edit_btn_cancel->connect_simple('clicked', array($this, 'media_edit_hide'));
		$this->media_nb_info_edit->connect_simple('clicked', array($this, 'edit_media'), false);
		$this->media_edit_btn_start->connect("clicked", array($this, 'open_media_with_player'));
		$this->media_nb_info_edit->hide();
//		#$this->viewport1->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->background_color));
		
		// ----------------------------
		// ROM-SEARCH
		// ----------------------------
		$this->search_input_reset->connect('clicked', array($this, 'onResetSearch'));
		$this->search_input_reset->set_sensitive(false);
		// Input search
//		#$this->search_input_txt->connect('key-press-event', array($this, 'quick_search'));
		$this->search_input_txt->connect('key-release-event', array($this, 'quick_search'));
		$this->search_input_pre->connect('clicked', array($this, 'quick_search'));
		$this->search_input_post->connect('clicked', array($this, 'quick_search'));

		// ----------------------------
		// ROM-NAV BUTTONS NXT-PREV aso
		// ----------------------------
		$this->media_pager_next->connect_simple('clicked', array($this, 'onNextRecord'));
		$this->media_pager_prev->connect_simple('clicked', array($this, 'onPrevRecord'));
		$this->media_pager_first->connect_simple('clicked', array($this, 'onFirstRecord'));
		$this->media_pager_last->connect_simple('clicked', array($this, 'onLastRecord'));
		
		// ----------------------------
		// ROM-ORDER ASC/DESC
		// ----------------------------
		$this->search_order_asc1->connect_simple("toggled", array($this, 'onReloadRecord'), false);
		
		// ----------------------------
		// SETUP Imagepreview placeholder
		// ----------------------------		
		$obj_pixbuff = GdkPixbuf::new_from_file(dirname(__FILE__)."/".'images/eccsys/platform/ecc_ecc_teaser.png');
		$obj_pixbuff = $obj_pixbuff->scale_simple(240, 160, Gdk::INTERP_BILINEAR);
		$this->media_img->set_from_pixbuf($obj_pixbuff);
		
		// ----------------------------
		// Romlist get_selection
		// ----------------------------			
		// normal selection (Mouse or keyboard)
		$selection = $this->sw_mainlist_tree->get_selection(); 
		$selection->set_mode(Gtk::SELECTION_BROWSE);
//		#$selection->set_mode(Gtk::SELECTION_MULTIPLE); 
		$selection->connect('changed', array($this, 'show_media_info'));
		// return key (keyboard)
		$this->sw_mainlist_tree->connect("select-cursor-row", array($this, 'open_media_with_player'));
//		#$this->sw_mainlist_tree->connect("expand-collapse-cursor-row", array($this, 'open_media_with_player'));
//		#$this->sw_mainlist_tree->connect("row-activated", array($this, 'open_media_with_player'));
		// right mouse key (context menu)
		$this->sw_mainlist_tree->connect('button-press-event', array($this, 'show_popup_menu'));
//		#$this->sw_mainlist_tree->connect('button-release-event', array($this, 'show_popup_menu'));		

		// ----------------------------
		// MEDIA-INFOS
		// ----------------------------
		// button start media
		$this->btn_start_media->connect("clicked", array($this, 'open_media_with_player'));
		$this->btn_start_media->hide();
		// button add to bookmarks
		$this->btn_add_bookmark->connect("clicked", array($this, 'add_bookmark_by_id'));
		$this->btn_add_bookmark->hide();
		
		// ----------------------------
		// MEDIA-INFOS Image init
		// ----------------------------
		$this->media_img_btn_next->connect_simple('clicked', array($this, 'set_image_show_pos'), 'next');
		$this->media_img_btn_prev->connect_simple('clicked', array($this, 'set_image_show_pos'), 'prev');
		$this->img_media_btn_delete->connect('clicked', array($this, 'remove_image'));
		$this->img_media_btn_save->connect('clicked', array($this, 'save_image'));
		// image popup, if you click into the preview image
		$this->img_media_btn_count->connect_simple('clicked', array($this, 'openImagePopup'), false);
		// hide all buttons, not needed at startup
		$this->media_img_btn_next->set_sensitive(false);
		$this->media_img_btn_prev->set_sensitive(false);
		$this->img_media_btn_count->set_sensitive(false);
		$this->img_media_btn_delete->set_sensitive(false);
		$this->img_media_btn_save->set_sensitive(false);
		$this->img_media_btn_show_unsaved->connect('clicked', array($this, 'on_image_toggle_unsaved'));
		// change image order
		$this->image_type_selected = key($this->image_type);
		if (!$this->obj_image_type) $this->obj_image_type = new IndexedCombobox($this->cb_image_type, false, $this->image_type, 2);
		$this->cb_image_type->connect("changed", array($this, 'image_type_order'));

		// ----------------------------		
		// INLINE HELP PARSER BUTTON
		// ----------------------------
		$this->btn_parser_path_inline_help->connect_simple('clicked', array($this, 'parseMedia'));
		
		// ----------------------------
		// standard windows close connect
		// ----------------------------
		$this->wdo_main->connect_simple('destroy', array($this, 'quit'));
		
		// ----------------------------
		// PRINT OUT DEBUG INFORMATIONS
		// ABOUT ALL CLASSES BUILD BY
		// FACTORY IN THIS CONSTRUCTOR!
		// ----------------------------	
		//FACTORY::status();
		
		// ----------------------------
		// INITIAL GET ALL DATA FOR
		// SELECTED PLATFORM!!!!!!!
		// HERE ECC GET ALL ROMS!!!!!
		// ----------------------------		
		$this->onInitialRecord();
		
		// ----------------------------		
		// START GTK!
		// ----------------------------		
		//$this->wdo_main->show_all();
		Gtk::Main();
	}
	
	public function connectSignalsForTopMenu() {
		
		// ----------------------------
		// ROMS
		// ----------------------------
		$this->add_new_roms_to_ecc->connect_simple('activate', array($this, 'parseMedia'));
		$this->edit_assign_emulator->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT');
		$this->optimize_roms_in_ecc->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_OPTIMIZE');
		$this->remove_roms_in_ecc->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_MEDIA');
		
		// ----------------------------
		// DATFILE
		// ----------------------------
		$this->import_romcenter_datfile->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_RC');
		$this->import_emuControlCenter_datfile->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_ECC');
		$this->export_ecc_datfile_full->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT');
		$this->export_ecc_datfile_user->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_USER');
		$this->export_ecc_datfile_esearch->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_ESEARCH');
		$this->empty_datfile_database->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_DAT');

		// ----------------------------
		// FILES
		// ----------------------------
		$this->menubar_filesys_organize_roms_preview->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_FS_ORGANIZE_PREVIEW');
		$this->menubar_filesys_organize_roms->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_FS_ORGANIZE');
		
		// ----------------------------
		// MAINTENANCE
		// ----------------------------	
		// maint_create_use_folder
		$this->maint_create_use_folder->connect_simple('activate', array(FACTORY::get('manager/GuiHelper'), 'rebuildEccUserFolder'));
		// vacuum database
		$this->menubar_maint_db_vacuum->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_VACUUM');
		// remove duplicate roms
		$this->menubar_maint_duplicate_remove_all->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DUPLICATE_REMOVE_ALL');

		// ----------------------------
		// OPTIONS
		// ----------------------------		
		// nav_autoupdate
		$this->nav_autoupdate = $this->ini->read_ecc_histroy_ini('nav_autoupdate');
		$this->navigation_autoupdate->set_active($this->nav_autoupdate);
		$this->navigation_autoupdate->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'NAVIGATION_TOGGLE_AUTOUPDATE');
		
		// nav_inactive_hidden
		$this->nav_inactive_hidden = $this->ini->read_ecc_histroy_ini('nav_inactive_hidden');
		$this->navigation_hide_empty->set_active($this->nav_inactive_hidden);
		$this->navigation_hide_empty->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_TOGGLE_INACTIVE');
		
		// images_inactiv
		$this->images_inactiv = $this->ini->read_ecc_histroy_ini('images_inactiv');
		$this->navigation_show_images->set_active($this->images_inactiv);
		$this->navigation_show_images->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMG_TOGGLE');
		
		// mainview display-mode
		$this->testRadio1->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_ALL');
		$this->testRadio2->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY');
		$this->testRadio3->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_METALESS');
		
		// toggle_show_doublettes
		$this->toggle_show_doublettes = $this->ini->read_ecc_histroy_ini('toggle_show_doublettes');
		$this->hide_duplicate_roms->set_active($this->toggle_show_doublettes);
		$this->hide_duplicate_roms->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DOUBLETTES');
		
		// configuration
		$this->menubar_config_ecc_config->connect_simple('activate', array($this, 'show_nb_ecc_configuration'));
		
		// ----------------------------
		// ABOUT
		// ----------------------------	
		$this->about->connect_simple('activate', array(FACTORY::get('manager/GuiHelper'), 'open_splash_screen'));
	}
	
	/**
	 * Opens the fullscreen imagepopup
	 * This function uses the oGuiImagePopup-object, that handles
	 * all the imagepopup functions like show and update! 
	 * @return boolean true|false
	 **/
	public function openImagePopup($onlyShowIfOpened=false) {
		// dont do anything, if $onlyShowIfOpened is activated
		// and the window isnt openend!
		if ($onlyShowIfOpened && !$this->oGuiImagePopup->is_opened()) return false;
		if (count($this->currentImageTank)) {
			$pos = (isset($this->_img_show_pos)) ? $this->_img_show_pos : 0;
			$this->oGuiImagePopup->show($this->currentImageTank, $this->current_media_info, $pos);
		}
		return true;
	}
	
	/*
	*
	*/
	public function DatFileExport($user_only=false, $userfoder_path=true, $verbose=true, $use_esearch=false)
	{
		if ($this->status_obj->init()) {
			if (!isset($platfom)) $platfom = strtoupper($this->ecc_platform_name);
			
			$history_key = ($user_only) ? 'eccMediaDat_export_user' : 'eccMediaDat_export_complete';
			
			$user_only_strg = ($user_only) ? 'USER' : 'COMPLETE';
			
			if ($userfoder_path==true) {
				// get path from history
				$path_history = $this->ini->read_ecc_histroy_ini($history_key);
				$title = sprintf(I18N::get('popup', 'dat_export_filechooser_title%s'), $user_only_strg);
				
				
				#$path = $this->openFileChooserDialog($title, $path_history, false, Gtk::FILE_CHOOSER_ACTION_SELECT_FOLDER);
				$path = FACTORY::get('manager/Os')->openChooseFolderDialog($path_history, $title);
				
				if ($path === false) {
					$this->status_obj->reset1();
					return false;
				}
				$title = sprintf(I18N::get('popup', 'dat_export_title%s'), $user_only_strg);
				$msg = sprintf(I18N::get('popup', 'dat_export_msg%s%s%s'), $user_only_strg, $platfom, $path);
				if ($use_esearch) $msg .= I18N::get('popup', 'dat_export_esearch_msg_add');
				if (!$this->open_window_confirm($title, $msg)) {
					$this->status_obj->reset1();
					return false;
				}
			}
			else {
				$path = false;
			}
			
			$this->status_obj->set_label("Export ".$user_only_strg." datfile for ".$platfom."");
			$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			if ($userfoder_path==true) $this->ini->write_ecc_histroy_ini($history_key, $path, true);
			
			require_once('manager/cDatFileExport.php');
			$export = new DatFileExport($this->ini, $this->status_obj, $this->ecc_release);
			$export->setDbms($this->dbms);
//			# FACTORY!

			$export->set_eccident($this->_eccident);
			$export->export_user_only($user_only);
			
			$ext_search_snipplet = $this->_fileView->get_search_ext_snipplet($this->ext_search_selected);
			if ($use_esearch) $export->set_sqlsnipplet_esearch($ext_search_snipplet);
			
			$result = $export->export_data($path);
			$title = I18N::get('popup', 'dat_export_done_title');
			$msg = sprintf(I18N::get('popup', 'dat_export_done_msg%s%s%s'), $user_only_strg, strtoupper($this->ecc_platform_name), $path);
			$this->status_obj->open_popup_complete($title, $msg);
		}
		return true;
	}
	
	
	private function fileOrganizer($process=false) {
		
		$process_type = $this->ini->get_ecc_ini_key('USER_SWITCHES','fs_rom_reorganization_type');
		
		require_once('manager/cFileOrganizer.php');
		$oFileOrga = new FileOrganizer($this->_eccident, $this->ini, $this->status_obj);
		$oFileOrga->setDbms($this->dbms);
//		# FACTORY
		
		if (!$oFileOrga->categories_exists()) {
			$title = I18N::get('popup', 'rom_reorg_nocat_title');
			$msg = sprintf(I18N::get('popup', 'rom_reorg_nocat_msg%s'), strtoupper($this->ecc_platform_name));
			$this->open_window_info($title, $msg);
			return false;
		}
		
		if ($process) {
			$title = I18N::get('popup', 'rom_reorg_title');
			$msg = sprintf(I18N::get('popup', 'rom_reorg_msg%s%s%s'), $process_type, strtoupper($this->ecc_platform_name), $this->_eccident);
			if (!$this->open_window_confirm($title, $msg)) return false; 
		}
		
		if ($this->status_obj->init()) {
			
			if ($process) {
				$this->status_obj->set_label("Organize ROMS");
			}
			else {
				$this->status_obj->set_label("PREVIEW Organize ROMS");
			}
			$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			$oFileOrga->set_skip_unknown_category(true);
			$oFileOrga->set_categories($this->media_category);
			
			$path = $oFileOrga->get_destination_path();
			if ($statistics = $oFileOrga->get_preview_statistics()) {
				
				$msg = "";
				if (!$process) {
					$msg = "THIS IS ONLY A PREVIEW!!!! NOTHING WILL BE PROCESSED AT ALL!!!\n";
				}
				else {
					$msg = "FILES COPIED TO NEW LOCATION!!!\n";
				}
				
				$msg .= "Selected process mode: \"$process_type\"\n";
				$msg .= "Destination folder: \"$path\"\n";
				
				if (isset($statistics['DONE']) && count($statistics['DONE'])) {
					$msg .= "----------------------------------------\n";
					$msg .= "NEW STRUCTURE PREVIEW:\n";
					$msg .= "----------------------------------------\n";
					foreach ($statistics['DONE'] as $category => $value) {
						$msg .= "$category\n";
						foreach ($value as $id => $filename) {
							$msg .= "\t".$filename."\n";
						}
					}
				}
				$msg .= "\n";
				
				if (isset($statistics['ISSET']) && count($statistics['ISSET'])) {
					$msg .= "----------------------------------------\n";
					$msg .= "ALLREADY IN DESINATION FOLDER\n";
					$msg .= "----------------------------------------\n";
					foreach ($statistics['ISSET'] as $category => $value) {
						$msg .= "$category\n";
						foreach ($value as $id => $filename) {
							$msg .= "\t".$filename."\n";
						}
					}
				}
				$msg .= "\n";
				
				if (isset($statistics['MISSING']) && count($statistics['MISSING'])) {
					$msg .= "----------------------------------------\n";
					$msg .= "SOURCE FILE MISSING\n";
					$msg .= "----------------------------------------\n";
					foreach ($statistics['MISSING'] as $category => $value) {
						$msg .= "$category\n";
						foreach ($value as $id => $filename) {
							$msg .= "\t".$filename."\n";
						}
					}
				}
				$msg .= "\n";
				
				if (isset($statistics['INVALID_SOURCE']) && count($statistics['INVALID_SOURCE'])) {
					$msg .= "----------------------------------------\n";
					$msg .= "INVALID SOURCE FILE\n";
					$msg .= "----------------------------------------\n";
					foreach ($statistics['INVALID_SOURCE'] as $category => $value) {
						$msg .= "$category\n";
						foreach ($value as $id => $filename) {
							$msg .= "\t".$filename."\n";
						}
					}
				}
				$msg .= "\n";
				
				$this->status_obj->update_message($msg);
				
				if ($process) {
					if (!$process_type) return false;
					$oFileOrga->set_reorganize_mode($process_type);
					$oFileOrga->process();
				}
			}
			$this->status_obj->update_progressbar(1, "reorganization DONE");
			
			if ($process) {
				$title = I18N::get('popup', 'rom_reorg_done_title');
				$msg = sprintf(I18N::get('popup', 'rom_reorg_done__msg%s'), $path);
				$this->status_obj->open_popup_complete($title, $msg);
			}
			else {
				$this->status_obj->reset1();
			}
		}
		return true;
		
		
	}
	
	/*
	*
	*/
	public function DatFileImport($extension_limit=false)	{
		
		if (!isset($platfom)) $platfom = strtoupper($this->ecc_platform_name);
		
		$title = I18N::get('popup', 'rom_import_backup_title');
		$msg = sprintf(I18N::get('popup', 'rom_import_backup_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
		$backup_state = $this->open_window_confirm($title, $msg);
		if ($backup_state) $this->DatFileExport(false, false, false);
		
		if ($this->status_obj->init()) {
			
			// get path from history
			$path_history = $this->ini->read_ecc_histroy_ini('eccMediaDat_import');
			
			$title = sprintf(I18N::get('popup', 'dat_import_filechooser_title%s'), $platfom);
			
			#$path = $this->openFileChooserDialog($title, $path_history, $extension_limit, Gtk::FILE_CHOOSER_ACTION_OPEN);
			$path = FACTORY::get('manager/Os')->openChooseFileDialog($path_history, $title, $extension_limit);
			
			if ($path === false) {
				$this->status_obj->reset1();
				return false;
			}
			$title = I18N::get('popup', 'rom_import_title');
			$msg = sprintf(I18N::get('popup', 'rom_import_msg%s%s%s'), $platfom, $this->_eccident, basename($path));
			if (!$this->open_window_confirm($title, $msg)) {
				$this->status_obj->reset1();
				return false;
			}
			
			$this->status_obj->set_label('Import datfile for "'.$platfom.'"');
			$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			// write path to history
			$this->ini->write_ecc_histroy_ini('eccMediaDat_import', $path, true);
			require_once('manager/cDatFileImport.php');
			$import = new DatFileImport($this->_eccident, $this->status_obj, $this->ini);
			$import->setDbms($this->dbms);
//			# FACTORY!
			
			$import->parse($path);
			
			$title = I18N::get('popup', 'rom_import_done_title');
			$msg = sprintf(I18N::get('popup', 'rom_import_done_msg%s'), strtoupper($this->ecc_platform_name));
			$this->status_obj->open_popup_complete($title, $msg);
			
			$this->onInitialRecord();
		}
	}
	
	/*
	*
	*/
	public function MediaMaintDb($function)
	{
		$maint = FACTORY::get('manager/PlattformMaintenance', $this->status_obj);
		$maint->set_eccident($this->_eccident);
		
		switch ($function) {
			case 'OPTIMIZE':
				if ($this->status_obj->init()) {
					$this->status_obj->set_label("Optimize database");
					$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
					$this->status_obj->show_main();
					$this->status_obj->show_output();
					
					$maint->db_optimize();
					$this->update_treeview_nav();
					$this->onInitialRecord();
					
					$title = I18N::get('popup', 'rom_optimize_done_title');
					$msg = sprintf(I18N::get('popup', 'rom_optimize_done_msg%s'), strtoupper($this->ecc_platform_name));
					$this->status_obj->open_popup_complete($title, $msg);
				}
				break;
			case 'CLEAR_MEDIA':
				
				$msg = "";
				
				$media_type = ($this->_eccident) ? $this->_eccident : 'all' ;
				
				$title = sprintf(I18N::get('popup', 'rom_remove_title%s'), $media_type);
				$msg = sprintf(I18N::get('popup', 'rom_remove_msg%s'), strtoupper($this->ecc_platform_name));
				$choice = $this->open_window_confirm($title, $msg);
				if (!$choice) return false;
				
				$txt = $maint->db_clear();
				$this->update_treeview_nav();
				$this->onInitialRecord();
				
				$title = sprintf(I18N::get('popup', 'rom_remove_done_title'), $media_type);
				$msg = sprintf(I18N::get('popup', 'rom_remove_done_msg%s'), strtoupper($this->ecc_platform_name));
				$this->open_window_info($title, $msg);
				
				break;
			case 'CLEAR_DAT':
				
				$msg = "";
				
				$media_type = ($this->_eccident) ? $this->_eccident : 'all' ;
				
				$title = sprintf(I18N::get('popup', 'dat_clear_title%s'), $media_type);
				$msg = sprintf(I18N::get('popup', 'dat_clear_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
				$choice = $this->open_window_confirm($title, $msg);
				if (!$choice) return false;
				
				$title = sprintf(I18N::get('popup', 'dat_clear_backup_title%s'), $media_type);
				$msg = sprintf(I18N::get('popup', 'dat_clear_backup_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
				$backup_state = $this->open_window_confirm($title, $msg);
				if ($backup_state) $this->DatFileExport(false, false, false);
				
				if ($this->status_obj->init()) {
					
					$this->status_obj->set_label("Optimize database");
					$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
					$this->status_obj->show_main();
					$this->status_obj->show_output();
					
					$txt = $maint->db_clear_dat();
					$this->update_treeview_nav();
					$this->onInitialRecord();

					$title = sprintf(I18N::get('popup', 'dat_clear_done_title%s'), $media_type);
					$msg = sprintf(I18N::get('popup', 'dat_clear_done_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
					if ($backup_state) $msg.= sprintf(I18N::get('popup', 'dat_clear_done_ifbackup_msg%s'), $this->_eccident);
					$this->status_obj->open_popup_complete($title, $msg);
				}
				break;
			case 'default':
				print "UNKNOWN FUNCTION\n";
				break;
		}
	}
	
	/*
	*
	*/
	public function quick_search($test)
	{
		$this->nb_main->set_current_page(0);
		
		$this->_search_word_like_pre = $this->search_input_pre->get_active();
		$this->_search_word_like_post = $this->search_input_post->get_active();
		$this->_search_word = trim($this->search_input_txt->get_text());
		
		$state = ($this->_search_word_like_pre) ? true : false;
		$this->set_search_state('quick_pre', $state);
		
		$state = ($this->_search_word_like_post) ? true : false;
		$this->set_search_state('quick_post', $state);
		
		$state = ($this->_search_word) ? true : false;
		$this->set_search_state('quick', $state);
		
		if (
			get_class($test) != 'GtkToggleButton' &&
			$this->_search_word != "" &&
			$this->_search_word_last == $this->_search_word
		) {
			//print "wurde schon eingegeben\n";
		}
		else {
			$this->_search_word_last = $this->_search_word;
			$this->onInitialRecord();
		}
	}
	
	public function on_image_toggle() {
		$this->images_inactiv = ($this->images_inactiv) ? false : true ;
		#$this->ini->write_ecc_histroy_ini('images_inactiv', $this->images_inactiv, false);
		$this->onInitialRecord();
		return true;
	}
	
	public function on_image_toggle_unsaved($obj) {
		$this->images_unsaved_only = $obj->get_active();
		$label = ($this->images_unsaved_only) ? "unsaved" : "saved" ;
		$obj->set_label($label);
		$this->onReloadRecord();
	}
	
	public function on_toggle_state(&$observed_var, $write_histroy=false) {
		$observed_var = ($observed_var) ? false : true ;
		if ($write_histroy) $this->ini->write_ecc_histroy_ini($write_histroy, $observed_var, false);
		$this->onInitialRecord();
		$this->update_treeview_nav();
		return true;
	}
	
	/*
	*
	*/
	public function onResetSearch($reload=false)
	{
		$this->_search_word_like_pre = $this->search_input_pre->set_active(false);
		$this->_search_word_like_post = $this->search_input_post->set_active(false);
		$this->_search_word = $this->search_input_txt->set_text('');
		
		$this->cb_search_language->set_active(0);
		$this->cb_search_category->set_active(0);

		$this->reset_search_state();
		
		if ($reload) {
			$this->onInitialRecord();
		}
	}
	
	/** Opens the selected media in the assigned player
	*
	*/
	public function open_media_with_player($obj) {
		if (!$this->current_media_info['id']) return false;
		
		$media_name = ($this->current_media_info['path_pack']) ? $this->current_media_info['path_pack'] : $this->current_media_info['path'];
		$ext = strtolower($this->get_ext_form_file($media_name));
		$ini_player = $this->ini->get_ecc_ini_key('ECC_PLATFORM', strtolower($this->current_media_info['fd_eccident']));
		$ini_player = (isset($ini_player['EMU.'.$ext])) ? $ini_player['EMU.'.$ext] : false;
		
		$emu = (isset($ini_player['path'])) ? $ini_player['path'] : "";
		if (!$emu || !realpath($emu)) {
			$title = I18N::get('popup', 'emu_miss_title');
			if ($emu) {
				$msg = sprintf(I18N::get('popup', 'emu_miss_notfound_msg%s'), $emu);
			}
			else {
				$msg = I18N::get('popup', 'emu_miss_notset_msg');
			}
			$this->open_window_info($title, $msg);
			return false;
		}
		
		$path = $this->current_media_info['path'];
		if (!realpath($path)) {
			$title = I18N::get('popup', 'rom_miss_title');
			$msg = I18N::get('popup', 'rom_miss_msg');
			$this->open_window_info($title, $msg);
			return false;
		}
		
		$emu_escape = (isset($ini_player['escape'])) ? $ini_player['escape'] : 0 ;
		$emu_win8char = (isset($ini_player['win8char'])) ? $ini_player['win8char'] : 0 ;
		
		// execute the file with the assigned emulator		
		$oOs = FACTORY::get('manager/Os');
		if ($oOs->executeFileWithProgramm($emu, $path, $emu_escape, $emu_win8char)){
//			print "launched file $emu, $path, $emu_escape, $emu_win8char\n";<
			$this->_fileView->update_launch_time($this->current_media_info['id']);	
		}
		else {
			
		}
	}
	
	/*
	*
	*/
	public function set_style($text_obj, $size=14000, $color="#cc0000")
	{
		$font = new PangoFontDescription();
		$font->set_size($size);
		$font->set_family($this->os_env['FONT']);
		$font->set_style(Pango::STYLE_ITALIC);
		$font->set_weight(Pango::WEIGHT_HEAVY);
		$text_obj->modify_font($font);
	}
	
	
	public function extract_composite_ids($composite_id) {
		if (false === strpos($composite_id, "|")) return false;
		
		$ret = array();
		$split = explode("|", $composite_id);
		$ret['fdata_id'] = $split[0];
		$ret['mdata_id'] = $split[1];
		return $ret;
	}
	
	
	/*
	*
	*/
	public function show_media_info($obj)
	{
		// Durch den Interator ermitteln,
		// welche media_id ausgew�hlt wurde
		list($model, $iter) = $obj->get_selected();
		if ($iter) {
			
			$file_id = $model->get_value($iter, 3);
			$mdata_id = $model->get_value($iter, 4);
			$composite_id = $model->get_value($iter, 5);
			
			// edit-button anzeigen
			$this->media_nb_info_edit->show();
			$this->btn_start_media->show();
			$this->btn_add_bookmark->show();
			
			$coposite_id_array = $this->extract_composite_ids($composite_id);
			
			if ($coposite_id_array['fdata_id']) {
				$file_list = $this->_fileView->get_file_data_TEST_META(false, "fd.id='".(int)$coposite_id_array['fdata_id']."'", array(0, 1), false, "", $this->_search_language, $this->_search_category, false, $this->toggle_show_files_only);
			}
			else {
				$file_list = $this->_fileView->get_file_data_TEST_META(false, "md.id='".(int)$coposite_id_array['mdata_id']."'", array(0, 1), false, "", $this->_search_language, $this->_search_category, false, $this->toggle_show_files_only);
			}
			
			$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
			$info = (isset($file_list['data'][$composite_id])) ? $file_list['data'][$composite_id] : false ;
			
			if ($info) {
				$btn_sensitive_bool = ($coposite_id_array['fdata_id']) ? true : false;
				$this->btn_start_media->set_sensitive($btn_sensitive_bool);
				$this->btn_add_bookmark->set_sensitive($btn_sensitive_bool);
				
				$title = ($info['md_name']) ? $info['md_name'] : basename($info['path']);

				$eccident = ($info['fd_eccident']) ? $info['fd_eccident'] : $info['md_eccident'];
				$plattform = strtolower($eccident);
				
				$this->set_style($this->media_nb_info_plattform, 10000);
				$this->media_nb_info_plattform->set_text($plattform);
				
				$packed = ($info['path_pack']) ? 'YES' : 'NO';
				
				$this->set_style($this->media_nb_info_title, 10000);
				$this->media_nb_info_title->set_text($title);
				
				$info_title = ($info['md_name']) ? $info['md_name'] : "--";
				
				$info_data = ($info['md_info']) ? str_replace('|', ' ', $info['md_info']) : "--";
				$this->media_nb_info_infos->set_markup('<span color="#334455">'.htmlspecialchars($info_data).'</span>');
				
				$info_id = ($info['md_info_id']) ? $info['md_info_id'] : "--";
				
				// KB
				$filesize_kb = round($info['size']/1024);
				// MB
				$filesize_mb = round($info['size']/1024/1024, 1);
				$filesize_mb_strg = ($filesize_mb > 0) ? "$filesize_mb MB /" : "";
				//Mbit
				$filesize_mbit = round($info['size']/1024/1024*8, 1);
				$filesize_mbit_strg = ($filesize_mbit > 0) ? "$filesize_mbit Mbit /" : "";
				
				$size = ($info['size']) ? " $filesize_mbit_strg $filesize_mb_strg $filesize_kb KB" : " -- ";
				$this->media_nb_info_file_size->set_markup('<span color="#334455">'.htmlspecialchars($size).'</span>');
				
				$crc32 = ($info['crc32']) ? $info['crc32'] : " -- ";
				$this->media_nb_info_file_crc32->set_markup('<span color="#334455">'.htmlspecialchars($crc32).'</span>');
				
				$path = ($info['path_pack']) ? $info['path_pack'] : $info['path'];
				$this->media_nb_info_file_name->set_markup('<span color="#334455">'.htmlspecialchars(basename($path)).'</span>');
				
				$path_pack = ($info['path_pack']) ? basename($info['path']) : "NO";
				$this->media_nb_info_file_name_pack->set_markup('<span color="#334455">'.htmlspecialchars($path_pack).'</span>');
				
				$this->media_nb_info_file_path->set_markup('<span color="#334455">'.htmlspecialchars(dirname(realpath($info['path']))).'</span>');
				
				$this->media_nb_info_running->set_text($this->get_dropdown_string($info['md_running']));
				$this->media_nb_info_bugs->set_text($this->get_dropdown_string($info['md_bugs']));
				$this->media_nb_info_trainer->set_text($this->get_dropdown_string($info['md_trainer']));
				$this->media_nb_info_intro->set_text($this->get_dropdown_string($info['md_intro']));
				$this->media_nb_info_usermod->set_text($this->get_dropdown_string($info['md_usermod']));
				$this->media_nb_info_freeware->set_text($this->get_dropdown_string($info['md_freeware']));
				
				$this->media_nb_info_multiplayer->set_text($this->get_dropdown_string($info['md_multiplayer']));
				$this->media_nb_info_netplay->set_text($this->get_dropdown_string($info['md_netplay']));
				
				$this->media_nb_info_category->set_text($this->get_category($info['md_category']));
				
				$year = ($info['md_year']) ? $info['md_year'] : '?';
				$this->media_nb_info_year->set_text($year);
				
				$usk = (isset($info['md_usk']) && $info['md_usk'] != 'NULL') ? $info['md_usk'] : '?';
				$this->media_nb_info_usk->set_text($usk);
				
				$creator = (isset($info['md_creator'])) ? $info['md_creator'] : '?';
				$this->media_nb_info_creator->set_text($creator);
				
				$this->current_media_info = $info;
				$this->set_image_for_show(0);
				
				$languages = "?";
				if ($lang_data = array_keys($this->_fileView->get_language_by_mdata_id($info['md_id']))) {
					$languages = "(".implode(") (",$lang_data).")";
				}
				$this->media_nb_info_language->set_text($languages);
				
				// ecc-informations from ini
				$version = "".$this->ecc_release['release_version']." ".$this->ecc_release['release_build']." ".$this->ecc_release['release_state']."";
				$website = $this->ecc_release['website'];
				$email = $this->ecc_release['email'];
				
				// create ecc header
				$ecc_header = "# Generated by emuControlCenter (ecc)\n";
				$ecc_header .= "# Version ".$version."\n";
				$ecc_header .= "# Visit ".$website." for more informations and updates\n";
				$ecc_header .= "# Contact ".$email."\n";
				
				$spacer = str_repeat("#", 80)."\n";;
				
				$text = "";
				$text .= $spacer;
				$text .= $ecc_header;
				$text .= $spacer;
				$text .= "\n";
				
				$text .= "[FILE_INFO]\n";
				$text .= "NAME:\t".basename($info['path'])."\n";
				$text .= "PATH:\t".$info['path']."\n";
				$text .= "PACKED:\t".$packed."\n";
				$text .= "PACKED_NAME:\t".basename($info['path_pack'])."\n";
				$text .= "PLATFORM:\t".$plattform."\n";
				$text .= "SIZE:\t".$size."\n";
				$text .= "CRC32:\t".$crc32."\n";
				
				if (isset($info) && count($info)) {
					$text .= "\n";
					$text .= "[DAT_INFO]\n";
					if ($info['md_id']) {
						foreach($info as $key => $value) {
							if (false !== strpos($key, "md_")) {
								
								if ($key == 'md_category') {
									$text .= "CATEGORY:\t".$this->get_category($value, false);
									$text .= ($value) ? " (".$value.")" : "";
									$text .= "\n";
								}
								else {
									$text .= strtoupper(str_replace("md_", "", $key)).":\t".$value."\n";
								}
							}
						}
					}
				}
				if ($lang_data = array_keys($this->_fileView->get_language_by_mdata_id($info['md_id']))) {
					$text .= "LANGUAGES:\t(".implode(") (",$lang_data).")\n";
				}
				$text .= "\n";
				
				if (isset($info['fd_mdata'])) {
					$text .= "[HEADER_INFO]\n";
					$mdata = unserialize(base64_decode($info['fd_mdata']));
					if (isset($mdata) && count($mdata)) {
						foreach ($mdata as $name => $value) {
							$value = ($value) ? $value : '???';
							$text .= trim($name).":\t".trim($value)."\n";
						}
					}
					$text .= "\n";
				}
				
				$text .= $spacer;
				$text_buf = new GtkTextBuffer();
				$text_buf->set_text(trim($text));
				$this->textview1->set_buffer($text_buf);
			}
		}
		
		// this will update
		// the imagepopup on the fly
		$this->openImagePopup(true);
		
		// this will update
		// the mediaedit popup on the fly
		// if the popup is opened
		$this->edit_media(true);
		
	}
	
	/*
	*
	*/
	public function set_image_for_show($pos=false) {
		
		$this->_img_show_pos = ($pos !== false) ? $pos : $this->_img_show_pos ;
		$info = $this->current_media_info;
		
		$eccident = ($info['fd_eccident']) ? $info['fd_eccident'] : $info['md_eccident'];
		$eccident = strtolower($eccident);
		
		$path = dirname($info['path']);
		$name_file = $this->get_plain_filename($info['path']);
		$name_packed = ($info['path_pack']) ? $this->get_plain_filename($info['path_pack']) : false;
		$name_dat = ($info['md_name']) ? $info['md_name'] : false;
		$extension = ($info['path_pack']) ? $this->get_ext_form_file($info['path_pack']) : $this->get_ext_form_file($info['path']);
		$media = $this->image_search($eccident, $info['crc32'], $path, $extension, $name_file, $name_packed, $name_dat, false);
		
		$this->_img_show_count = count($media);
		if ($this->_img_show_pos < 1) {
			$this->_img_show_pos = 0;
		}
		elseif ($this->_img_show_pos > $this->_img_show_count-1) {
			$this->_img_show_pos = $this->_img_show_count-1;
		}
		
		// message
		if ($this->_img_show_count > 1) {
			$msg_img_show_status = "(".($this->_img_show_pos+1)."/".$this->_img_show_count.")";
			$this->media_img_btn_next->set_sensitive(true);
			$this->media_img_btn_prev->set_sensitive(true);
			$this->img_media_btn_count->set_sensitive(true);
			$this->img_media_btn_save->set_sensitive(true);
			$this->img_media_btn_delete->set_sensitive(true);
			$this->img_media_btn_count->set_sensitive(true);
		}
		else {
			if ($this->_img_show_count == 1) {
				$msg_img_show_status = "(1/1)";
				$this->img_media_btn_save->set_sensitive(true);
				$this->img_media_btn_delete->set_sensitive(true);
				$this->img_media_btn_count->set_sensitive(true);
			}
			else {
				$msg_img_show_status = "(0/0)";
				$this->img_media_btn_save->set_sensitive(false);
				$this->img_media_btn_delete->set_sensitive(false);
				$this->img_media_btn_count->set_sensitive(false);
			}
			$this->media_img_btn_next->set_sensitive(false);
			$this->media_img_btn_prev->set_sensitive(false);
		}
		
		$this->img_media_btn_count->set_label($msg_img_show_status);
		
		$pix_data = $this->get_pixbuf($info['path'], $media, $this->_img_show_pos, 240, 160, $eccident);
		$this->media_img->set_from_pixbuf($pix_data);
		
		$msg = "";
		if (isset($media[$this->_img_show_pos])) {
			$msg .= basename($media[$this->_img_show_pos]);
		}
		else {
			$msg .= 'Capture a screen from your emu first to add screenshots!';
		}
		$this->img_media_lbl_filename->set_text($msg);
		
		$this->currentImageTank = $media;
		
		unset($info);
		unset($pix_data);
		unset($media);
	}
	
	/*
	*
	*/
	public function remove_image()
	{
		
		$info = $this->current_media_info;
		
		$eccident = ($info['fd_eccident']) ? $info['fd_eccident'] : $info['md_eccident'];
		$eccident = strtolower($eccident);
		
		$path = dirname($info['path']);
		$name_file = $this->get_plain_filename($info['path']);
		$name_packed = ($info['path_pack']) ? $this->get_plain_filename($info['path_pack']) : false;
		$name_dat = ($info['md_name']) ? $info['md_name'] : false;
		$extension = ($info['path_pack']) ? $this->get_ext_form_file($info['path_pack']) : $this->get_ext_form_file($info['path']);
		$media = $this->image_search($eccident, $info['crc32'], $path, $extension, $name_file, $name_packed, $name_dat);

		if (isset($media[$this->_img_show_pos])) {
			$file = realpath($media[$this->_img_show_pos]);
			
			$title = I18N::get('popup', 'img_remove_title');
			$msg = sprintf(I18N::get('popup', 'img_remove_msg%s'), $file);
			if (!$this->open_window_confirm($title, $msg)) return false;
			
			if (file_exists($file)) {
				unlink($file);
				$this->onReloadRecord();
				$this->set_image_for_show($this->_img_show_pos-1);
			}
			else {
				
				$title = I18N::get('popup', 'img_remove_error_title');
				$msg = sprintf(I18N::get('popup', 'img_remove_error_msg%s'), $file);
				if (!$this->open_window_confirm($title, $msg)) return false;
			}
		}
	}
	
	/* 
	* Kopiert bilder in ein Userverzeichnis (ecc.ini)
	* Die Bilder werden nach folgedem Format gespeichert, um wieder auffindbar zu sein.
	* extension_crc32_count.imagesuffix
	* gba_5555715F_001.png
	*/
	public function save_image() {
		
		// convert to jpg or direct copy?
		$convert_images = $this->ini->get_ecc_ini_key('USER_SWITCHES', 'image_convert_to_jpg');
		
		$gui_label = $this->img_media_lbl_filename;
		
		$info = $this->current_media_info;
		
		$eccident = ($info['fd_eccident']) ? $info['fd_eccident'] : $info['md_eccident'];
		$eccident = strtolower($eccident);
		
		$crc32 = $info['crc32'];
		$title = $info['title'];
		
		$user_folder_images = $this->ini->get_ecc_ini_user_folder($eccident.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR, true);
		if ($user_folder_images===false) return false;
		
		$path = dirname($info['path']);
		$name_file = $this->get_plain_filename($info['path']);
		$name_packed = ($info['path_pack']) ? $this->get_plain_filename($info['path_pack']) : false;
		$name_dat = ($info['md_name']) ? $info['md_name'] : false;
		$extension = ($info['path_pack']) ? $this->get_ext_form_file($info['path_pack']) : $this->get_ext_form_file($info['path']);
		$media = $this->image_search($eccident, $info['crc32'], $path, $extension, $name_file, $name_packed, $name_dat);
		
		if (isset($media[$this->_img_show_pos]) && $user_folder_images) {
			
			$img_source = $media[$this->_img_show_pos];
			
			$type = $this->cb_image_type->get_active_text();
			
			if ($convert_images) {
				$img_extension = 'jpg';
				$img_destination = $user_folder_images.DIRECTORY_SEPARATOR."ecc_".$eccident."_".$crc32."_".$type.".".$img_extension;
			}
			else {
				$img_extension = $this->get_ext_form_file(basename($img_source));
				$img_destination = $user_folder_images.DIRECTORY_SEPARATOR."ecc_".$eccident."_".$crc32."_".$type.".".$img_extension;
			}
			
			if (basename($img_source) == basename($img_destination)) {
				$msg = "Image ".basename($img_source)." exists!";
				$gui_label->set_text($msg);
				return false;
			}
			
			foreach($this->supported_images as $supported_img_ext => $active) {
				if ($active) {
					$img_destination_exists = dirname($img_destination).DIRECTORY_SEPARATOR.$this->get_plain_filename($img_destination).".".$supported_img_ext;
					if(file_exists($img_destination_exists)) {
						$title = I18N::get('popup', 'img_overwrite_title');
						$msg = sprintf(I18N::get('popup', 'img_overwrite_msg%s%s'), basename($img_destination_exists), basename($img_source));
						if (true !== $this->open_window_confirm($title, $msg)) return false;
					}
				}
			}
			
			if (file_exists($img_source)) {
				
				if ($convert_images) {
					// convert image and move
					$this->image_convert_and_copy($img_source, $img_destination);
				}
				else {
					// only move image
					$this->image_copy($img_source, $img_destination);
				}
				
				// update gui
				$msg = "SAVED to\n".basename($img_destination);
				$gui_label->set_text($msg);
				
				// reload treeview
				$this->onReloadRecord();
			}
		}
	}

	public function image_copy($img_source, $img_destination) {
		@unlink($img_destination);
		@rename($img_source, $img_destination);
	}
	
	public function image_convert_and_copy($img_source, $img_destination) {
		
		$ext = strtolower($this->get_ext_form_file($img_source));
		switch($ext) {
			case 'gif':
				$im = imagecreatefromgif($img_source);
				$state = imagejpeg($im , $img_destination, 75);
				if ($state===true) unlink($img_source);
				break;
			case 'png':
				$im = imagecreatefrompng($img_source);
				$state = imagejpeg($im , $img_destination, 75);
				if ($state===true) unlink($img_source);
				break;
			case 'jpg':
			case 'jpeg':
				// right format... only unlink old destination and move files
				@unlink($img_destination);
				@rename($img_source, $img_destination);
				break;
		}
	}
	
	public function image_type_order($obj) {
		$needle = $obj->get_active_text();
		$this->image_type_selected = $needle;
		$temp[$needle] = $this->image_type[$needle];
		unset($this->image_type[$needle]);
		$this->image_type = array_merge($temp, $this->image_type);
		$this->onReloadRecord();
	}
	
	/*
	*
	*/
	public function show_popup_menu_platform($obj, $event)
	{
		if ($event->button == 3 || ($event->button == 1 && $event->type == 5)) {
			
			$menu = new GtkMenu();
			
			$platform_name = $this->ecc_platform_name;
			$itm_header = new GtkMenuItem(sprintf(I18N::get('menu', 'lbl_platform%s'), $platform_name));
			$itm_header->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_INFO');
			$menu->append($itm_header);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$itm_add_new = new GtkMenuItem(I18N::get('menu', 'lbl_roms_add'));
			$itm_add_new->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'ADD_NEW');
			$menu->append($itm_add_new);
			
			$itm_maint_db_optimize = new GtkMenuItem(I18N::get('menu', 'lbl_roms_optimize'));
			$itm_maint_db_optimize->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_OPTIMIZE');
			$menu->append($itm_maint_db_optimize);
			
			$itm_maint_db_clear_media = new GtkMenuItem(I18N::get('menu', 'lbl_roms_remove'));
			$itm_maint_db_clear_media->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_MEDIA');
			$menu->append($itm_maint_db_clear_media);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$itm_platform_edit = new GtkMenuItem(I18N::get('menu', 'lbl_emu_config'));
			$itm_platform_edit->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT');
			$menu->append($itm_platform_edit);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$itm_import = new GtkMenuItem(I18N::get('menu', 'lbl_dat_import_ecc'));
			$itm_import->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_ECC');
			$menu->append($itm_import);

			$itm_import = new GtkMenuItem(I18N::get('menu', 'lbl_dat_import_rc'));
			$itm_import->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_RC');
			$menu->append($itm_import);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$itm_export = new GtkMenuItem(I18N::get('menu', 'lbl_dat_export_ecc_full'));
			$itm_export->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT');
			$menu->append($itm_export);
			
			$itm_export_user = new GtkMenuItem(I18N::get('menu', 'lbl_dat_export_ecc_user'));
			$itm_export_user->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_USER');
			$menu->append($itm_export_user);
			
			$itm_export_esearch = new GtkMenuItem(I18N::get('menu', 'lbl_dat_export_ecc_esearch'));
			$itm_export_esearch->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_ESEARCH');
			$menu->append($itm_export_esearch);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$itm_maint_db_clear_dat = new GtkMenuItem(I18N::get('menu', 'lbl_dat_empty'));
			$itm_maint_db_clear_dat->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_DAT');
			$menu->append($itm_maint_db_clear_dat);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$itm_help = new GtkMenuItem(I18N::get('menu', 'lbl_help'));
			$itm_help->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'HELP');
			$menu->append($itm_help);
			
			$menu->show_all();
			$menu->popup();
		}
	}
	
	public function dispatch_menu_context_platform($obj, $test=false) {
		
		$name = (is_string($obj)) ? $obj : get_class($obj);
		
		switch($name) {
			case 'ADD_NEW':
				$this->parseMedia();
				break;
			case 'IMG_TOGGLE':
				$this->on_image_toggle();
				break;
			case 'RELOAD_IMG':
				$this->onReloadRecord();
				break;
			case 'MAINT_DB_OPTIMIZE':
				$title = I18N::get('popup', 'rom_optimize_title');
				$msg = I18N::get('popup', 'rom_optimize_msg');
				if (!$this->open_window_confirm($title, $msg)) return false; 
				$this->MediaMaintDb('OPTIMIZE');
				break;
			case 'MAINT_DB_VACUUM':
				$title = I18N::get('popup', 'db_optimize_title');
				$msg = I18N::get('popup', 'db_optimize_msg');
				if (!$this->open_window_confirm($title, $msg)) return false; 

				if ($this->status_obj->init()) {
					$this->status_obj->set_label("Vacuum database");
					$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
					$this->status_obj->show_main();
					$this->status_obj->show_output();
					
					$this->_fileView->vacuum_database();
					
					$msg = "";
					$this->status_obj->update_progressbar(1, "removing DONE");
					$this->status_obj->update_message("Database is now optimized by vacuum!");
					
					$title = I18N::get('popup', 'db_optimize_done_title');
					$msg = I18N::get('popup', 'db_optimize_done_msg');
					$this->status_obj->open_popup_complete($title, $msg);
				}
				break;
			case 'MAINT_DB_CLEAR_MEDIA':
				$this->MediaMaintDb('CLEAR_MEDIA');
				break;
			case 'PLATFORM_INFO':
				$this->nb_main->set_current_page(1);
				break;
			case 'PLATFORM_EDIT':
				$this->nb_main->set_current_page(2);
				break;
			case 'IMPORT_RC':
				$this->DatFileImport(array('romcenter datfiles (*.dat)'=>'*.dat', 'emuControlCenter datfiles (*.ecc)'=>'*.ecc'));
				break;
			case 'IMPORT_ECC':
				$this->DatFileImport(array('emuControlCenter datfiles (*.ecc)'=>'*.ecc'));
				break;
			case 'EXPORT':
				$this->DatFileExport();
				break;
			case 'EXPORT_USER':
				$this->DatFileExport(true);
				break;
			case 'EXPORT_ESEARCH':
				if (!$this->get_ext_search_state()) {
					$title = I18N::get('popup', 'export_esearch_error_title');
					$msg = I18N::get('popup', 'export_esearch_error_msg');
					return $this->open_window_info($title, $msg); 
				}
				$this->DatFileExport(false, true, true, true);
				break;
			case 'MAINT_DB_CLEAR_DAT':
				$this->MediaMaintDb('CLEAR_DAT');
				break;
			
			case 'PLATFORM_TOGGLE_INACTIVE':
				$this->nav_inactive_hidden = ($this->nav_inactive_hidden) ? false : true;
				$this->update_treeview_nav();
				$this->ini->write_ecc_histroy_ini('nav_inactive_hidden', $this->nav_inactive_hidden, false);
				break;
			case 'NAVIGATION_TOGGLE_AUTOUPDATE':
				$this->nav_autoupdate = ($this->nav_autoupdate) ? false : true;
				$this->update_treeview_nav();
				$this->ini->write_ecc_histroy_ini('nav_autoupdate', $this->nav_autoupdate, false);
				break;
			case 'TOGGLE_MAINVIEV_DOUBLETTES':
				$this->on_toggle_state($this->toggle_show_doublettes, "toggle_show_doublettes");
				break;			
			
			// radio buttons in top navigation
			case 'TOGGLE_MAINVIEV_ALL':
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->onInitialRecord();
				$this->update_treeview_nav();
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY':
//				$this->on_toggle_state($this->toggle_show_files_only, "toggle_show_files_only");
				$this->toggle_show_files_only = true;
				$this->toggle_show_metaless_roms_only = false;
				$this->onInitialRecord();
				$this->update_treeview_nav();
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_METALESS':
//				$this->on_toggle_state($this->toggle_show_metaless_roms_only, "toggle_show_metaless_roms_only");
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = true;
				$this->onInitialRecord();
				$this->update_treeview_nav();
				break;
								
			case 'MAINT_DUPLICATE_REMOVE_ALL':
				$this->duplicate_remove_all($this->eccident);
				break;
			case 'MAINT_FS_ORGANIZE_PREVIEW':
				$this->fileOrganizer();
				break;
			case 'MAINT_FS_ORGANIZE':
				$this->fileOrganizer(true);
				break;	
			case 'HELP':
				$this->nb_main->set_current_page(4);
				break;
			default:
				// do nothing
		}
	}
	
	
	private function duplicate_remove_all() {
		$title = I18N::get('popup', 'rom_dup_remove_title');
		$msg = sprintf(I18N::get('popup', 'rom_dup_remove_msg%s'), strtoupper($this->ecc_platform_name));
		if (!$this->open_window_confirm($title, $msg)) return false; 

		if ($this->status_obj->init()) {
			
			$this->status_obj->set_label("Remove duplicate ROMS");
			$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			$stats_duplicate = array();
			$stats_duplicate = $this->_fileView->get_duplicates_all($this->_eccident);
			
			$this->update_treeview_nav();
			$this->onInitialRecord();
			
			$msg = "";
			$this->status_obj->update_progressbar(1, "removing DONE");
			if (count($stats_duplicate)) {
				$msg .= "All found duplicate ROMS for ".$this->ecc_platform_name." removed from database\n\n";
				$msg .= "Statistics:\n";
				foreach ($stats_duplicate as $eccident => $count) {
					$msg .= "Removed: ".$count." Roms\t".$eccident." - ".$this->ini->get_ecc_platform_name_by_eccident($eccident)."\n";
				}
			}
			else {
				$msg .= "No duplicate ROMS for ".$this->ecc_platform_name." found";
			}
			$this->status_obj->update_message($msg);
			
			$title = I18N::get('popup', 'rom_dup_remove_done_title');
			$msg = sprintf(I18N::get('popup', 'rom_dup_remove_done_msg%s'), strtoupper($this->ecc_platform_name));
			$this->status_obj->open_popup_complete($title, $msg);
		}
		return true;
	}
	
	/*
	*
	*/
	public function show_popup_menu($obj, $event)
	{
		if ($this->data_available && $this->data_available>0) {
			//Check if it was the right mouse button (button 3)
			if ($event->button == 1 && $event->type == 5) {
				$this->open_media_with_player($obj, $event);
			}
			elseif ($event->button == 3) {
				
				//popup the menu
				$menu = new GtkMenu();
				
				if ($this->current_media_info['id']) {
					$echo1 = new GtkMenuItem(I18N::get('menu', 'lbl_start'));
					$echo1->connect_simple('activate', array($this, 'dispatch_menu_context'), $obj);
					$menu->append($echo1);
					$menu->append(new GtkSeparatorMenuItem());
				}
				
				if ($this->view_mode == 'BOOKMARK') {
					$itm_bookmark_rem_single = new GtkMenuItem(I18N::get('menu', 'lbl_fav_remove'));
					$itm_bookmark_rem_single->connect_simple('activate', array($this, 'dispatch_menu_context'), 'REMOVE_BOOKMARK_SINGLE');
					$menu->append($itm_bookmark_rem_single);
					
					$itm_bookmark_rem_all = new GtkMenuItem(I18N::get('menu', 'lbl_fav_all_remove'));
					$itm_bookmark_rem_all->connect_simple('activate', array($this, 'dispatch_menu_context'), 'REMOVE_BOOKMARK_ALL');
					$menu->append($itm_bookmark_rem_all);
					
					$menu->append(new GtkSeparatorMenuItem());
				}
				else {
					if ($this->current_media_info['id']) {
						$echo2 = new GtkMenuItem(I18N::get('menu', 'lbl_fav_add'));
						$echo2->connect_simple('activate', array($this, 'dispatch_menu_context'), 'ADD_BOOKMARK');
						$menu->append($echo2);
						$menu->append(new GtkSeparatorMenuItem());
					}
				}
				
				if (count($this->currentImageTank)) {
					$image_popup = new GtkMenuItem(I18N::get('menu', 'lbl_image_popup'));
					$image_popup->connect_simple('activate', array($this, 'openImagePopup'), false);
					$menu->append($image_popup);
				}
				
				$echo4 = new GtkMenuItem(I18N::get('menu', 'lbl_img_reload'));
				$echo4->connect_simple('activate', array($this, 'dispatch_menu_context'), 'RELOAD');
				$menu->append($echo4);
				$menu->append(new GtkSeparatorMenuItem());
								
				if ($this->view_mode == 'MEDIA') {
					if ($this->current_media_info['id']) {
						$echo4 = new GtkMenuItem(I18N::get('menu', 'lbl_rom_remove'));
						$echo4->connect_simple('activate', array($this, 'dispatch_menu_context'), 'REMOVE_MEDIA');
						$menu->append($echo4);
						$menu->append(new GtkSeparatorMenuItem());

					}
				}
				
				$echo4 = new GtkMenuItem(I18N::get('menu', 'lbl_meta_webservice_meta_get'));
				$echo4->connect_simple('activate', array($this, 'dispatch_menu_context'), 'WEBSERVICE', 'GET');
				$menu->append($echo4);
				$menu->show_all();
				$menu->popup();
				
				$echo4 = new GtkMenuItem(I18N::get('menu', 'lbl_meta_webservice_meta_set'));
				$echo4->connect_simple('activate', array($this, 'dispatch_menu_context'), 'WEBSERVICE', 'SET');
				$menu->append($echo4);
				$menu->show_all();
				$menu->popup();
				
				$menu->append(new GtkSeparatorMenuItem());
				
				$echo4 = new GtkMenuItem(I18N::get('menu', 'lbl_meta_edit'));
				$echo4->connect_simple('activate', array($this, 'dispatch_menu_context'), 'EDIT');
				$menu->append($echo4);
				$menu->show_all();
				$menu->popup();
			}
		}
		else {
			
			if ($this->view_mode != 'MEDIA') return false;
			
			if ($event->button == 3) {
				$menu = new GtkMenu();
				$label = sprintf(I18N::get('menu', 'lbl_roms_initial_add%s%s'), $this->ecc_platform_name, $this->_eccident);
				$itm_add_new = new GtkMenuItem($label);
				$itm_add_new->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'ADD_NEW');
				$menu->append($itm_add_new);
				$menu->show_all();
				$menu->popup();
			}
		}
	}
	
	/*
	*
	*/
	public function dispatch_menu_context($obj, $parameter=false) {
		
		$name = (is_string($obj)) ? $obj : get_class($obj);
		
		switch($name) {
			case 'RELOAD':
				$this->onReloadRecord();
				break;
			case 'ADD_BOOKMARK':
				$this->add_bookmark_by_id();
				break;
			case 'REMOVE_BOOKMARK_SINGLE':
				$this->remove_bookmark_by_id();
				break;
			case 'REMOVE_BOOKMARK_ALL':
				$this->remove_bookmark_all();
				break;
			case 'GtkTreeView':
				$this->open_media_with_player($obj);
				break;
			case 'REMOVE_MEDIA':
				$this->remove_media_from_fdata($obj);
				break;
				
			case 'WEBSERVICE':
				$oWebServices = FACTORY::get('manager/WebServices');
				$oWebServices->setServiceUrl('http://www.camya.com/service.php');
				if ($parameter == 'GET') {
					$response = $oWebServices->get();
					
					print str_repeat("#",80)."\nWebservices DEMO ONLY!!! #GET#\n".str_repeat("#",80)."\n<pre>";
					print_r($response);
					print "</pre>\n";
					
					$this->open_window_info("Webservices DEMO ONLY!!! #GET#", print_r($response, true));
				}
				elseif($parameter == 'SET') {
				
					$media = $this->current_media_info;
					$data['md_eccident'] = $media['md_eccident'];
					$data['md_name'] = $media['md_name'];
					$data['md_info'] = $media['md_info'];
					$data['md_info_id'] = $media['md_info_id'];
					$data['md_running'] = $media['md_running'];
					$data['md_bugs'] = $media['md_bugs'];
					$data['md_intro'] = $media['md_intro'];
					$data['md_usermod'] = $media['md_usermod'];
					$data['md_freeware'] = $media['md_freeware'];
					$data['md_multiplayer'] = $media['md_multiplayer'];
					$data['md_netplay'] = $media['md_netplay'];
					$data['md_year'] = $media['md_year'];
					$data['md_usk'] = $media['md_usk'];
					$data['md_category'] = $media['md_category'];
					$data['md_creator'] = $media['md_creator'];
					$data['fd_title'] = $media['title'];
					$data['fd_crc32'] = $media['crc32'];
					$data['fd_size'] = $media['size'];
					$data['fd_mdata'] = $media['fd_mdata'];
					
					$response = $oWebServices->set($data);
					
					$this->open_window_info("Webservices DEMO ONLY!!! #SEND.PACKED#", print_r($data, true)."\n\n".$response);
				}
				
				break;
			case 'EDIT':
				$this->edit_media(false);
				break;
			default:
				// do nothing
		}
	}
	
	function languages_set_selected($store, $path, $iter, $mdat_id) {
		$state = false;
		if ($mdat_id && $lang_id = $store->get_value($iter, 1)) {
			$state = $this->_fileView->get_language_status($mdat_id, $lang_id);
		}
		if ($state===true) {
			$icon = $this->model_languages->get_value($iter, 4);
			$this->model_languages->set($iter, 2, $icon);
		}
		else {
			$icon = $this->model_languages->get_value($iter, 5);
			$this->model_languages->set($iter, 2, $icon);
		}
		$store->set($iter, 0, $state);
	}
	
	/*
	*
	*/
	public function edit_media($onlyShowIfOpened=false)
	{
		if ($onlyShowIfOpened && !$this->media_edit_is_opened) return false;
		
		$composite_id = $this->current_media_info['composite_id'];
		$coposite_id_array = $this->extract_composite_ids($composite_id);
		
		if ($coposite_id_array['fdata_id']) {
			$mdata_array = $this->_fileView->get_file_data_TEST_META(false, "fd.id='".(int)$coposite_id_array['fdata_id']."'", array(0, 1), false, "", $this->_search_language, $this->_search_category, false, $this->toggle_show_files_only);
		}
		else {
			$mdata_array = $this->_fileView->get_file_data_TEST_META(false, "md.id='".(int)$coposite_id_array['mdata_id']."'", array(0, 1), false, "", $this->_search_language, $this->_search_category, false, $this->toggle_show_files_only);
		}
		$mdata = $mdata_array['data'][$composite_id];
		
		$check_data = array(
			'md_name' => $mdata['md_name'],
			'md_info' => $mdata['md_info'],
			'md_info_id' => $mdata['md_info_id'],
			'md_running' => $mdata['md_running'],
			'md_bugs' => $mdata['md_bugs'],
			'md_trainer' => $mdata['md_trainer'],
			'md_intro' => $mdata['md_intro'],
			'md_usermod' => $mdata['md_usermod'],
			'md_freeware' => $mdata['md_freeware'],
			'md_multiplayer' => $mdata['md_multiplayer'],
			'md_netplay' => $mdata['md_netplay'],
			'md_category' => $mdata['md_category'],
			'md_year' => $mdata['md_year'],
			'md_usk' => $mdata['md_usk'],
			'md_creator' => $mdata['md_creator'],
		);
		$mdata['edit_checksum'] = $this->create_mdata_checksum($check_data);
		
		$this->media_edit_filename->set_text($this->get_plain_filename($mdata['path']));
		$this->media_edit_filename_packed->set_text($this->get_plain_filename($mdata['path_pack']));
		
		$this->media_edit_title->set_text($mdata['md_name']);
		$this->media_edit_info->set_text($mdata['md_info']);
		$this->media_edit_info_id->set_text($mdata['md_info_id']);
		
		// insert data
		$state = array(
			0 => '',
			1	=> 'NO',
			2	=> 'YES',
		);
		
		// insert data
		$state_count = array(
			0 	=> '',
			1	=> 'NO',
			2	=> 1,
			3	=> 2,
			4	=> 3,
			5	=> 4,
			6	=> 5,
			7	=> 6,
			8	=> 7,
			9	=> 8,
			10	=> 9,
			11	=> 10,
		);
		
		if (!$this->obj_running) $this->obj_running = new IndexedCombobox($this->cb_running_new, false, $state);
		$this->cb_running_new->set_active($this->set_dropdown_bool($mdata['md_running']));
		
		if (!$this->obj_bugs) $this->obj_bugs = new IndexedCombobox($this->cb_bugs_new, false, $state);
		$this->cb_bugs_new->set_active($this->set_dropdown_bool($mdata['md_bugs']));
		
		if (!$this->obj_trainer) $this->obj_trainer = new IndexedCombobox($this->cb_trainer_new, false, $state_count);
		$this->cb_trainer_new->set_active($this->set_dropdown_bool($mdata['md_trainer']));
		
		if (!$this->obj_intro) $this->obj_intro = new IndexedCombobox($this->cb_intro, false, $state);
		$this->cb_intro->set_active($this->set_dropdown_bool($mdata['md_intro']));
		
		if (!$this->obj_usermod) $this->obj_usermod = new IndexedCombobox($this->cb_usermod, false, $state);
		$this->cb_usermod->set_active($this->set_dropdown_bool($mdata['md_usermod']));
		
		if (!$this->obj_freeware) $this->obj_freeware = new IndexedCombobox($this->cb_freeware, false, $state);
		$this->cb_freeware->set_active($this->set_dropdown_bool($mdata['md_freeware']));
		
		if (!$this->obj_multiplayer) $this->obj_multiplayer = new IndexedCombobox($this->cb_multiplayer, false, $state_count);
		$this->cb_multiplayer->set_active($this->set_dropdown_bool($mdata['md_multiplayer']));
		
		if (!$this->obj_netplay) $this->obj_netplay = new IndexedCombobox($this->cb_netplay, false, $state);
		$this->cb_netplay->set_active($this->set_dropdown_bool($mdata['md_netplay']));
		
		if (!$this->obj_category) $this->obj_category = new IndexedCombobox($this->cbe_category, false, $this->media_category, 4);
		$this->cbe_category->set_active($this->set_dropdown_bool($mdata['md_category']));
		
		// other entries
		
		$this->cbe_year->set_text($mdata['md_year']);
		$this->cbe_usk->set_text($mdata['md_usk']);
		$this->cbe_creator->set_text($mdata['md_creator']);
		
		$this->model_languages->foreach(array($this, 'languages_set_selected'), $mdata['md_id']);
		
		$this->edit_mdata = $mdata;
		unset($mdata);

		$this->win_media_edit->show();
		$this->media_edit_is_opened = true;
		$this->win_media_edit->set_keep_above(true);
		$this->win_media_edit->present();
	}
	
	public function create_mdata_checksum($data=false) {
		if (!$data || !is_array($data)) return false;
		$check = "";
		foreach ($data as $key => $value) {
			$check .= (!$value || $value=="NULL") ? "NULL" : $value ;
		}
		return md5($check);
	}
	
	/*
	*
	*/
	public function get_dropdown_bool($running)
	{
		$running -= 1;
		if ($running < 0) {
			$ret = "NULL";
		}
		elseif($running == 0) {
			$ret = 0;
		}
		else {
			$ret = $running;
		}
		return $ret;
	}
	
	/*
	*
	*/
	public function set_dropdown_bool($running)
	{
		if (!isset($running)) {
			$ret = 0;
		}
		else {
			$ret = $running+1;
		}
		return $ret;
	}
	
	/*
	*
	*/
	public function get_dropdown_string($value)
	{
		if (!isset($value)) {
			$ret = "?";
		}
		elseif($value == 0) {
			$ret = "NO";
		}
		elseif($value == 1) {
			$ret = "YES";
		}
		else {
			$ret = $value;
		}
		return $ret;
	}
	
	/*
	*
	*/
	public function get_category($value, $return_questionmark = true)
	{
		if (!isset($value)) {
			$ret = ($return_questionmark) ? "?" : "";
		}
		else {
			$ret = $this->media_category[(int)$value+1];
		}
		return $ret;
	}
	
	public function get_category_id($value)
	{
		if (!isset($value)) {
			$ret = "";
		}
		else {
			$ret = (int)$value-1;
		}
		return $ret;
	}
	
	/*
	* And now show what we've got in the store
	*/
	public $languages_get_selected_array = array();
	function languages_get_selected($store, $path, $iter) {
		if ($store->get_value($iter, 0)) {
			$id = $store->get_value($iter, 1);
			$label = $store->get_value($iter, 3);
			$this->languages_get_selected_array[$id] = true;
		}
	}

	/*
	*
	*/
	public function edit_media_save($validate_title=true)
	{
		$data['id'] = $this->current_media_info['md_id'];
		$data['crc32'] = $this->edit_mdata['crc32'];
		$data['eccident'] = strtolower($this->edit_mdata['fd_eccident']);
		
		$path = ($this->edit_mdata['path_pack']) ? $this->edit_mdata['path_pack'] : $this->edit_mdata['path'];
		$data['extension'] = ".".$this->get_ext_form_file($path);
		
		// ; is not allowed in user input and will removed now
		$data['name'] = trim(str_replace(";", "", $this->media_edit_title->get_text()));
		$data['info'] = trim(str_replace(";", "", $this->media_edit_info->get_text()));
		$data['info_id'] = trim(str_replace(";", "", $this->media_edit_info_id->get_text()));
		$data['year'] = trim(str_replace(";", "", $this->cbe_year->get_text()));
		$data['usk'] = trim(str_replace(";", "", $this->cbe_usk->get_text()));
		$data['creator'] = trim(str_replace(";", "", $this->cbe_creator->get_text()));
		
		// new
		$data['running'] = $this->get_dropdown_bool($this->cb_running_new->get_active());
		$data['bugs'] = $this->get_dropdown_bool($this->cb_bugs_new->get_active());
		$data['trainer'] = $this->get_dropdown_bool($this->cb_trainer_new->get_active());
		$data['intro'] = $this->get_dropdown_bool($this->cb_intro->get_active());
		$data['usermod'] = $this->get_dropdown_bool($this->cb_usermod->get_active());
		$data['freeware'] = $this->get_dropdown_bool($this->cb_freeware->get_active());
		$data['multiplayer'] = $this->get_dropdown_bool($this->cb_multiplayer->get_active());
		$data['netplay'] = $this->get_dropdown_bool($this->cb_netplay->get_active());
		
		$data['category'] = $this->get_dropdown_bool($this->cbe_category->get_active());
		
		$this->languages_get_selected_array = array();
		$this->model_languages->foreach(array($this, 'languages_get_selected'));
		$data['languages'] = $this->languages_get_selected_array; 
		
		if ($data['name']) {
			
			$check_data = array(
				'md_name' => $data['name'],
				'md_info' => $data['info'],
				'md_info_id' => $data['info_id'],
				'md_running' => $data['running'],
				'md_bugs' => $data['bugs'],
				'md_trainer' => $data['trainer'],
				'md_intro' => $data['intro'],
				'md_usermod' => $data['usermod'],
				'md_freeware' => $data['freeware'],
				'md_multiplayer' => $data['multiplayer'],
				'md_netplay' => $data['netplay'],
				'md_category' => $data['category'],
				'md_year' => $data['year'],
				'md_usk' => $data['usk'],
				'md_creator' => $data['creator'],
			);
			$check = $this->create_mdata_checksum($check_data);
			
			$modified = !($check == $this->edit_mdata['edit_checksum']);
			
			if ($data['id']) {
				$this->_fileView->update_file_info($data, $modified);
				$status = "Metadata updated!";
			}
			else {
				$this->current_media_info['md_id'] = $this->_fileView->insert_file_info($data);
				$data['id'] = $this->current_media_info['md_id'];
				$status = "Metadata inserted!";
			}
			$this->_fileView->save_language($data);
			
			$this->media_edit_help->set_markup("<span foreground='#00aa00'><b>$status</b></span>");
		}
		// Set new composite id
		// for updated media_edit data		
		$this->current_media_info['composite_id'] = $this->current_media_info['id']."|".$this->current_media_info['md_id'];
		$this->onReloadRecord();
	}

	public function media_edit_hide() {
		$this->win_media_edit->hide();
		$this->media_edit_is_opened = false;
	}
	
	/*
	*
	*/
	public function set_image_show_pos($action)
	{
		switch ($action) {
			case 'next':
				$this->_img_show_pos++;
				break;
			case 'prev':
				$this->_img_show_pos--;
				break;
			default:
				
		}
		$this->set_image_for_show();
	}
	
	/*
	*
	*/
	public function init_treeview_main()
	{
		// main model
		$this->model = new GtkListStore(Gtk::TYPE_OBJECT, Gtk::TYPE_OBJECT, Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING);
		
		// IMAGE
		$renderer_0 = new GtkCellRendererPixbuf();
		$column_0 = new GtkTreeViewColumn('IMAGE', $renderer_0, 'pixbuf', 0);
		
		// IMAGE
		$renderer_1 = new GtkCellRendererPixbuf();
		$column_1 = new GtkTreeViewColumn('IMAGE', $renderer_1, 'pixbuf', 1);
		
		// TEXT INFO
		$renderer_2 = new GtkCellRendererText();
		$renderer_2->set_property('family',  'Verdana');
		$renderer_2->set_property("yalign", 0);
		$renderer_2->set_property('size-points',  '8');
		$renderer_2->set_property('foreground', '#ffffff');
		$renderer_2->set_property('cell-background', '#394D59');
		$column_2 = new GtkTreeViewColumn('TITLE', $renderer_2, 'text', 2);
		$column_2->set_cell_data_func($renderer_2, array($this, "format_col"));
		
		// hidden file-id
		$renderer_file_id = new GtkCellRendererText();
		$col_file_id = new GtkTreeViewColumn('ID', $renderer_file_id, 'text', 3);
		$col_file_id->set_visible(false);
		
		// hidden mdata-id
		$renderer_mdata_id = new GtkCellRendererText();
		$col_mdata_id = new GtkTreeViewColumn('MDATA_ID', $renderer_mdata_id, 'text', 4);
		$col_mdata_id->set_visible(false);
		
		// hidden mdata-id
		$renderer_composite_id = new GtkCellRendererText();
		$col_composite_id = new GtkTreeViewColumn('COMPOSITE_ID', $renderer_composite_id, 'text', 5);
		$col_composite_id->set_visible(false);
		
		// add model to GtkTreeView
		$this->sw_mainlist_tree->set_model($this->model);
		$this->sw_mainlist_tree->modify_base(Gtk::STATE_NORMAL, GdkColor::parse('#445566'));
		$this->sw_mainlist_tree->modify_base(Gtk::STATE_SELECTED, GdkColor::parse('#aabbcc'));
		$this->sw_mainlist_tree->modify_base(Gtk::STATE_ACTIVE, GdkColor::parse('#aabbcc'));
		$this->sw_mainlist_tree->modify_text(Gtk::STATE_SELECTED, GdkColor::parse('#000000'));
		
		$this->sw_mainlist_tree->append_column($column_0);
		$this->sw_mainlist_tree->append_column($column_1);
		$this->sw_mainlist_tree->append_column($column_2);
		$this->sw_mainlist_tree->append_column($col_file_id);
		$this->sw_mainlist_tree->append_column($col_mdata_id);
		$this->sw_mainlist_tree->append_column($col_composite_id);
		
		
	}
	
	public function get_toggle_status($treeview)
	{
		
		
		list($m, $iter) = $treeview->get_selected();
		if (!$iter) return false;
		// toggle radio
		$state = ($this->model_languages->get_value($iter, 0)) ? false : true;
		$this->model_languages->set($iter, 0, $state);
		
		// switch images
		if ($state===true) {
			$icon = $this->model_languages->get_value($iter, 4);
			$this->model_languages->set($iter, 2, $icon);
		}
		else {
			$icon = $this->model_languages->get_value($iter, 5);
			$this->model_languages->set($iter, 2, $icon);
		}
	}
	
	public function set_search_language($combobox) {
		if ($string = $combobox->get_active_text()) {
			$string = trim(substr($string, 0, strpos($string, "|")));
		}
		$this->_search_language = $string;
		$this->onInitialRecord();
	}
	
	public function init_dropdown_languages($combobox)
	{
		$combobox->connect("changed", array($this, 'set_search_language'));
		$combobox->append_text("");
		foreach ($this->media_language as $id => $label) {
			$combobox->append_text($id."\t| ".$label);
		}
	}
	
	public function init_treeview_languages($treeview)
	{
		$this->model_languages = new GtkListStore(Gtk::TYPE_BOOLEAN, Gtk::TYPE_STRING, Gtk::TYPE_OBJECT, Gtk::TYPE_STRING, Gtk::TYPE_OBJECT, Gtk::TYPE_OBJECT);
		
		// id
		$renderer = new GtkCellRendererToggle();
		$column = new GtkTreeViewColumn('', $renderer, 'active',0);
		
		// id
		$renderer_0 = new GtkCellRendererText();
		$column_0 = new GtkTreeViewColumn('', $renderer_0, 'text',1);
		$column_0->set_visible(false);
		
		
		// icon
		$renderer_1 = new GtkCellRendererPixbuf();
		$column_1 = new GtkTreeViewColumn('', $renderer_1, 'pixbuf',2);
		
		// label
		$renderer_2 = new GtkCellRendererText();
		
		$renderer_2->set_property('height', 20);
		$renderer_2->set_property('family',  'Arial');
		$renderer_2->set_property("yalign",0);
		$renderer_2->set_property('size-points',  '9');
		$renderer_2->set_property('foreground', '#ffffff');
		$renderer_2->set_property('cell-background', '#394D59');
		
		$column_2 = new GtkTreeViewColumn('CATEGORY', $renderer_2, 'text', 3);
		
		
		// image 2 inactive
		$renderer_3 = new GtkCellRendererPixbuf();
		$column_3 = new GtkTreeViewColumn('', $renderer_3, 'pixbuf',4);
		$column_3->set_visible(false);
		
		// image 2 inactive
		$renderer_tmp = new GtkCellRendererPixbuf();
		$column_tmp = new GtkTreeViewColumn('', $renderer_tmp, 'pixbuf',5);
		$column_tmp->set_visible(false);
		
		$treeview->set_model($this->model_languages);
		$treeview->append_column($column);
		$treeview->append_column($column_0);
		$treeview->append_column($column_1);
		$treeview->append_column($column_2);
		$treeview->append_column($column_3);
		$treeview->append_column($column_tmp);
		
		foreach ($this->media_language as $id => $label) {
			
			$base_path = dirname(__FILE__)."/"."images/eccsys/languages/";
			
			// status active
			$path_a = $base_path.'ecc_lang_'.strtolower($id).'.png';
			if (!file_exists($path_a)) $path_a =  $base_path.'ecc_lang_unknown.png';
			// status inactive
			$path_i =  $base_path.'ecc_lang_'.strtolower($id).'_i.png';
			if (!file_exists($path_i)) $path_i =  $base_path.'ecc_lang_unknown_i.png';
			
			/// acive image
			$pixbuf_icon_active = GdkPixbuf::new_from_file($path_a);
			$obj_icon_active = $pixbuf_icon_active->scale_simple(30, 20, Gdk::INTERP_BILINEAR);
			
			// inacive image
			$pixbuf_icon_inactive = GdkPixbuf::new_from_file($path_i);
			$obj_icon_inactive = $pixbuf_icon_inactive->scale_simple(30, 20, Gdk::INTERP_BILINEAR);
			
			/// current image
			$obj_icon_current = $pixbuf_icon_inactive;
			
			$this->model_languages->append(array(false, $id, $obj_icon_current, $label, $obj_icon_active, $obj_icon_inactive));
		}
		
		$test = $treeview->get_selection(); 
		$test->set_mode(Gtk::SELECTION_BROWSE); 
		$test->connect('changed', array($this, 'get_toggle_status'));
	}
	
	/*
	*
	*/
	public function init_treeview_nav()
	{
		$this->model_navigation = new GtkListStore(Gtk::TYPE_OBJECT, Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING, Gtk::TYPE_STRING);
		
		$renderer_0 = new GtkCellRendererPixbuf();
		$column_0 = new GtkTreeViewColumn('Image', $renderer_0, 'pixbuf',0);
		
		$renderer_1 = new GtkCellRendererText();
		$column_1 = new GtkTreeViewColumn('ID', $renderer_1, 'text',1);
		$column_1->set_visible(false);
		
		$renderer_2 = new GtkCellRendererText();
		$renderer_2->set_property('height', 20);
		$renderer_2->set_property('family',  'Arial');
		$renderer_2->set_property('size-points',  '9');
		$renderer_2->set_property('foreground', '#ffffff');
		$column_2 = new GtkTreeViewColumn('CATEGORY', $renderer_2, 'text', 2);
		$column_2->set_cell_data_func($renderer_2, array($this, "format_col"));
		
		$renderer_3 = new GtkCellRendererText();
		$column_3 = new GtkTreeViewColumn('TYPE', $renderer_3, 'text',3);
		$column_3->set_visible(false);
		
		$renderer_count = new GtkCellRendererText();
		$column_count = new GtkTreeViewColumn('TITLE_SIMPLE', $renderer_count, 'text', 4);
		$column_count->set_visible(false);
		
		$this->treeview1->set_model($this->model_navigation);
		
		$this->treeview1->modify_base(Gtk::STATE_NORMAL, GdkColor::parse('#445566'));
		$this->treeview1->modify_base(Gtk::STATE_SELECTED, GdkColor::parse('#aabbcc'));
		$this->treeview1->modify_base(Gtk::STATE_ACTIVE, GdkColor::parse('#aabbcc'));
		$this->treeview1->modify_text(Gtk::STATE_SELECTED, GdkColor::parse('#000000'));
				
		$this->treeview1->append_column($column_0);
		$this->treeview1->append_column($column_1);
		$this->treeview1->append_column($column_2);
		$this->treeview1->append_column($column_3);
		$this->treeview1->append_column($column_count);
		
		$this->update_treeview_nav();
	}
	
	// self-defined function to display alternate row color
	function format_col($column, $cell, $model, $iter) {
		
		#$color_list_background_a = $this->ini->get_ecc_ini_key('USER_SWITCHES', 'color_list_background_a');
		#$color_list_background_a = ($color_list_background_a) ? "#".$color_list_background_a : "#445566";
		#$color_list_background_b = $this->ini->get_ecc_ini_key('USER_SWITCHES', 'color_list_background_b');
		#$color_list_background_b = ($color_list_background_b) ? "#".$color_list_background_b : "#556677";
		
		$path = $model->get_path($iter); // get the current path
		$row_num = $path[0]; // note 2
		$row_color = ($row_num%2==1) ? "#445566" : "#556677"; // sets the row color for odd and even rows
		$cell->set_property('cell-background', $row_color); // sets the background color!
	} 
	
	public function update_treeview_nav()
	{
		$model = $this->model_navigation;
		$model->clear();
		$nav_data = $this->ini->get_ecc_platform_navigation(false, $this->currentPlatformCategory);
		
		foreach ($nav_data as $eccident => $title) {
			
			// ini-file does not support false. null from ini-file
			// is translated to false.
			if (strtolower($eccident) == 'null') $eccident = false;
			// First entry is default selected plattform
			if ($this->_eccident===false) $this->_eccident = $eccident;
			
			if ($this->nav_autoupdate) {
				$media_count = $this->_fileView->get_media_count_for_eccident_search($eccident, $this->toggle_show_doublettes, $this->_search_language, $this->_search_category, $this->ext_search_selected, $this->toggle_show_metaless_roms_only);
			} else {
				$media_count = $this->_fileView->get_media_count_for_eccident($eccident, $this->toggle_show_doublettes, false, false, false, $this->toggle_show_metaless_roms_only);
			}
			
			// hide this navigation-itme, if there is no media parsed
			// for this platform, the navigation of these is set to hidden
			// and a eccident is set. (so, eccident==false #all found will not be hidden)
			if ($media_count == 0 && $this->nav_inactive_hidden && $eccident) continue;
			
			$title_and_count = $title." (".$media_count.")";
			
			// read specified file
			$img_path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_'.$eccident.'_nav.png';
			if (!file_exists($img_path)) $img_path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_unknown_nav.png';
			
			$obj_pixbuff = GdkPixbuf::new_from_file($img_path);
			$obj_pixbuff = $obj_pixbuff->scale_simple(34, 22, Gdk::INTERP_BILINEAR);
			
			$model->append(array($obj_pixbuff, $eccident, $title_and_count, $eccident, $title));
		}
	}
	
	public function changePlatformCategory($obj) {
		$this->currentPlatformCategory = $obj->get_active_text();
		$this->update_treeview_nav();
	}

	
	/*
	*
	*/
	public function get_treeview_nav_selection($obj)
	{
		$this->nb_main_page_selected = $this->nb_main->get_current_page();
		list($model, $iter) = $obj->get_selected();
		if ($iter) {
			
			// read last selected platform from history
			$path = $model->get_path($iter);
			$this->ini->write_ecc_histroy_ini('navigation_last_index', current($path), false);
			
			// for twmain_data_dispatcher
			$this->view_mode = 'MEDIA';
			
			$this->setEccident($model->get_value($iter, 3));
			$this->setPlatformName($model->get_value($iter, 4));
			
			$platform_name = $this->ecc_platform_name;
			$eccident = $this->_eccident;
			
			$txt = '<b>'.htmlspecialchars($platform_name).'</b>';
			$this->nb_main_lbl_media->set_markup($txt);
			
			$this->set_notebook_page_visiblility($this->nb_main, 0, true); // media
			$this->set_notebook_page_visiblility($this->nb_main, 1, $this->view_mode); // factsheet
			$this->set_notebook_page_visiblility($this->nb_main, 2, $eccident); // config-emu
			$this->set_notebook_page_visiblility($this->nb_main, 3, !$eccident); // config-ecc
			$this->set_notebook_page_visiblility($this->nb_main, 4, true); // help
			
			$this->update_platform_edit($eccident);
			$this->update_platform_info($eccident);
		}
	}

	
	public function set_notebook_page_visiblility($notebook=false, $page_no=0, $control=true) {
		if (!$notebook) return false;
		$page = $notebook->get_nth_page($page_no);
		if ($control) {
			$page->show();
		}
		else {
			$page->hide();
		}
	}
	
	public function setNotebookPage($notebook, $pageId) {
		$notebook->set_current_page($pageId);
	}
	
	public function show_nb_ecc_configuration() {
		$notebook = $this->nb_main;
		$count = $notebook->get_n_pages();
		for ($page_no=0; $page_no<$count; $page_no++) {
			$page = $notebook->get_nth_page($page_no);
			$page->hide();
		}
		$page = $notebook->get_nth_page(3);
		$page->show();
	}
	
	
	public function update_platform_info($eccident) {
		$pini = $this->ini->get_ecc_platform_info_data($eccident);
		
		$txt = '<b>'.htmlspecialchars($pini['GENERAL']['name']).'</b>';
		$this->pf_info_name->set_markup($txt);
		
		$this->pf_info_manufacturer->set_text($pini['GENERAL']['manufacturer']);
		
		$year_start = ($pini['GENERAL']['year_start']) ? $pini['GENERAL']['year_start'] : '????';
		$year_end = ($pini['GENERAL']['year_end']) ? $pini['GENERAL']['year_end'] : '????';
		$year_range = $year_start." - ".$year_end;
		$this->pf_info_year->set_text($year_range);
		
		$this->pf_info_type->set_text($pini['GENERAL']['type']);
		
		$text_desc = $pini['GENERAL']['description'];
		$buffer = new GtkTextBuffer();
		$buffer->set_text(trim($text_desc));
		$this->pf_info_description->set_buffer($buffer);
		
		$text_res = $pini['RESOURCES']['web'];
		$buffer = new GtkTextBuffer();
		$buffer->set_text(trim($text_res));
		$this->pf_info_resources->set_buffer($buffer);
	}
	
	private $inicfg_emu_btn_connected = array();
	private $platform_edit_emu = array();
	private $platform_ini = array();
	public function update_platform_edit($eccident) {
		
		if (!$eccident) return false;
		
		$pini = $this->ini->get_ecc_platform_ini($eccident);
		
		$helpfiles = array();
		$file = 'help/inline/edit_platform_'.$eccident.'.txt';
		if (file_exists($file)) $helpfiles[] = $file;
		$helpfiles[] = 'help/inline/edit_platform.txt';
		$this->update_inline_help($this->nb_main_config_ihelp_txt, $helpfiles);
		
		$this->inicfg_gen_eccident->set_label($pini['GENERAL']['eccident']);
		$this->inicfg_gen_title->set_text($pini['GENERAL']['navigation']);
		$this->inicfg_gen_category->set_text($pini['GENERAL']['category']);
		
		for ($i=1; $i<=5; $i++) {
			$row_box = "inicfg_emu_row_".$i;
			$this->$row_box->hide();
		}
		
		if (isset($pini['EXTENSIONS'])) {
			$cnt = 1;
			foreach($pini['EXTENSIONS'] as $extension => $state) {
				if ($state) {
					
					// row count
					$row_box = "inicfg_emu_row_".$cnt;
					$this->$row_box->show();
					
					// file-extension
					$inicfg_emu_ext = "inicfg_emu_row_".$cnt."_ext";
					$this->$inicfg_emu_ext->set_label($extension);
					
					// path
					$inicfg_emu_path = "inicfg_emu_row_".$cnt."_path";
					$path = (isset($pini["EMU.".$extension]['path'])) ? $pini["EMU.".$extension]['path'] : "" ;
					$this->$inicfg_emu_path->set_text($path);
					// escape the path?
					$inicfg_emu_escape = "inicfg_emu_row_".$cnt."_escape";
					$emu_escape = (isset($pini["EMU.".$extension]['escape'])) ? (int)$pini["EMU.".$extension]['escape'] : 1 ;
					$this->$inicfg_emu_escape->set_active($emu_escape);
					// only show testte~1
					$inicfg_emu_win8char = "inicfg_emu_row_".$cnt."_win8char";
					$emu_win8char = (isset($pini["EMU.".$extension]['win8char'])) ? (int)$pini["EMU.".$extension]['win8char'] : 0 ;
					$this->$inicfg_emu_win8char->set_active($emu_win8char);
					// is this emu-path valid
					$inicfg_emu_path_state = "inicfg_emu_row_".$cnt."_path_state";
					
					if (is_dir(dirname($path))) {
						$path_state_color = '#00CC00';
						$path_state_label = 'DIR';
					} else {
						$path_state_color = '#CC0000';
						$path_state_label = 'DIR';
					}
					
					$markup='<span foreground="'.$path_state_color.'"><b>'.htmlspecialchars($path_state_label).'</b></span>';
					$this->$inicfg_emu_path_state->set_markup($markup);
					
					$this->platform_edit_emu[$eccident][$cnt] = array(
						'extension' => $this->$inicfg_emu_ext,
						'path' => $this->$inicfg_emu_path,
						'escape' => $this->$inicfg_emu_escape,
						'win8char' => $this->$inicfg_emu_win8char,
					);
					$this->set_platform_edit_path_settings($eccident, $extension);
					
					$inicfg_emu_btn = "inicfg_emu_row_btn_".$cnt."";
					if (!isset($this->inicfg_emu_btn_connected[$inicfg_emu_btn])) {
						$this->$inicfg_emu_btn->connect('clicked', array($this, 'get_platform_edit_path'));
						$this->inicfg_emu_btn_connected[$inicfg_emu_btn] = true;
					}
				}
				$cnt++;
			}
		}
		
		if (!isset($this->inicfg_emu_btn_connected['inicfg_emu_btn_save'])) {
			$this->inicfg_emu_btn_save->connect_simple('clicked', array($this, 'write_platform_ini'), $eccident);
			$this->inicfg_emu_btn_connected['inicfg_emu_btn_save'] = true;
		}
		
		$this->platform_ini[$eccident] = $pini;
	}
	public function set_platform_edit_path_settings($eccident, $extension) {
		$this->pedit_eccident = $eccident;
		$this->pedit_extension = $extension;
	}
	public function get_platform_edit_path($obj) {
		// dirty way to get the row to change! :-)
		$pos = (int)substr($obj->get_name(), -1);
		
		$path_obj = $this->platform_edit_emu[$this->pedit_eccident][$pos]['path'];
		$path = realpath($path_obj->get_text()); // set path for popup
		
		$ext_obj = $this->platform_edit_emu[$this->pedit_eccident][$pos]['extension'];
		$ext = trim($ext_obj->get_text()); // set path for popup
		
		// open file-chooser
		$title = sprintf(I18N::get('popup', 'conf_platform_emu_filechooser_title%s'), $ext);
		
		#$path = $this->openFileChooserDialog($title, $path, false, Gtk::FILE_CHOOSER_ACTION_OPEN);
		$path = FACTORY::get('manager/Os')->openChooseFileDialog($path, $title);
		
		
		if ($path !== false) {
			$path_obj->set_text($path);
			$this->platform_ini[$this->pedit_eccident]['EMU.'.$ext] = $path;
		}
	}
	public function write_platform_ini() {
		// prepare var for autocreate relative paths... if possible!
		$ecc_user_path = realpath(dirname(__FILE__)."/../");
		
		$this->platform_ini[$this->pedit_eccident]['GENERAL']['navigation'] = '"'.$this->inicfg_gen_title->get_text().'"';
		$this->platform_ini[$this->pedit_eccident]['GENERAL']['category'] = '"'.$this->inicfg_gen_category->get_text().'"';
		foreach ($this->platform_edit_emu[$this->pedit_eccident] as $pos => $obj) {
			$ext = $obj['extension']->get_text();
			$path = trim($obj['path']->get_text());
			$escape = trim($obj['escape']->get_active());
			$win8char = trim($obj['win8char']->get_active());
			
			if ($path) {
				// ABS-PATH TO REL-PATH...
				$path_test = realpath($path);
				if ($path_test!="" && strpos($path_test, ECC_BASEDIR) == 0) {
					$path = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $path_test);
				};
			}
			
			$this->platform_ini[$this->pedit_eccident]['EMU.'.$ext] = array(
				'path' => '"'.$path.'"',
				'escape' => '"'.(int)$escape.'"',
				'win8char' => '"'.(int)$win8char.'"',
			);
						
		}
		
		$title = I18N::get('popup', 'conf_platform_update_title');
		$msg = sprintf(I18N::get('popup', 'conf_platform_update_msg%s'), $this->platform_ini[$this->pedit_eccident]['GENERAL']['navigation']);
		if (!$this->open_window_confirm($title, $msg)) return false;
		
		$state = $this->ini->write_ecc_platform_ini($this->platform_ini[$this->pedit_eccident]);
		$this->update_platform_edit($this->pedit_eccident);
		$this->ini->reload();
		$this->update_treeview_nav();
		
		// update also model in ecc-config
		$this->oGuiEccConfig->update();
		
		$categories = $this->ini->get_ecc_platform_categories(true);
		$this->dd_pf_categories->fill($categories, 0);
	}
	
	/**
	*
	*/
	public function setEccident($extension=false, $reload=true)
	{
		// set extension
		$this->_eccident = $extension;
		// unset found-images

		// unset search-words (quick-search)
		$this->_search_word = "";
		$this->search_input_txt->set_text('');
		
		$this->search_order_asc1->set_active(true);
		
		// get Data from database
		$img_path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_'.strtolower($extension).'_teaser.png';
		if (!file_exists($img_path)) $img_path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_unknown_teaser.png';
		
		$obj_pixbuff = GdkPixbuf::new_from_file($img_path);
		$obj_pixbuff = $obj_pixbuff->scale_simple(240, 160, Gdk::INTERP_BILINEAR);
		$this->img_plattform->set_from_pixbuf($obj_pixbuff);
		
		if ($reload===true) {
			$this->onInitialRecord();
		}
		
		$this->updateMenuBar();
	}
	
	
	private function updateMenuBar() {
		$state = ($this->_eccident) ? true : false;
		// Only works, if a eccident is selected!
		$this->edit_assign_emulator->set_sensitive($state);
		$this->import_romcenter_datfile->set_sensitive($state);
		$this->menubar_filesys_organize_roms_preview->set_sensitive($state);
		$this->menubar_filesys_organize_roms->set_sensitive($state);
	}
	
	public function setPlatformName($platform_name=false)
	{
		if (is_array($platform_name)) {
			$this->ecc_platform_name = "UNKNOWN";
			return;
		}
		$this->ecc_platform_name = (isset($platform_name) && $platform_name) ? htmlspecialchars($platform_name) : " ";
	}
	
	/*
	*
	*/
	public function get_ext_form_file($file) {
		if (false !== strpos($file, ".")) {
			$split = explode(".", $file);
			return array_pop($split);
		}
		return "";
	}
	
	/*
	*
	*/
	public function get_plain_filename($file) {
		$file = basename($file);
		if (false !== strpos($file, ".")) {
			$split = explode(".", $file);
			return array_shift($split);
		}
		return "";
	}
	
	
	/* ------------------------------------------------------------------------
	*	This function search for pre-definde images in the user-folder
	*	For the main-treewiew the parameter $only_first_found=true is used,
	*	to find only the first valid image to optimize the reaction-time of ecc.
	*/
	public function get_images_from_user($eccident, $crc32, $only_first_found = true)
	{
		$image_type = $this->image_type;
		
		if (!$only_first_found) {
			$this->image_tank[$eccident][$crc32]['USER'] = array();
		}
		else {
			if (isset($this->image_tank[$eccident][$crc32]['USER'])) {
				return $this->image_tank[$eccident][$crc32]['USER'];
			}
			else {
				$this->image_tank[$eccident][$crc32]['USER'] = array();
			}
		}
		$user_folder_images = $this->ini->get_ecc_ini_user_folder($eccident.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR, false);
		if ($user_folder_images === false) return array();
		
		foreach ($image_type as $ident => $void) {
			$img_base_name = $user_folder_images.DIRECTORY_SEPARATOR."ecc_".$eccident."_".$crc32."_".$ident.".";
			// check for all supported image-types
			foreach($this->supported_images as $supported_img_ext => $active) {
				if ($active) {
					if (file_exists($img_base_name.$supported_img_ext)){
						$this->image_tank[$eccident][$crc32]['USER'][] = $img_base_name.$supported_img_ext;
						if ($only_first_found) return $this->image_tank[$eccident][$crc32]['USER'];
					}
				}
			}
		}
		
		return $this->image_tank[$eccident][$crc32]['USER'];
	}
	
	/* ------------------------------------------------------------------------
	*
	*/
	public function get_images_from_emu_2($eccident=false, $crc32=false, $path=false, $file_ext=false, $name_file=false, $name_packed=false, $name_dat=false, $only_first_found = true)
	{
		if (isset($this->image_tank[$eccident][$crc32]['EMU'])) {
			return $this->image_tank[$eccident][$crc32]['EMU'];
		}
		else {
			$this->image_tank[$eccident][$crc32]['EMU'] = array();
		}
		$hdl = @opendir($path);
		if ($hdl) {
			while($file_name=readdir($hdl)) {
				if ($file_name == "." || $file_name == ".." || is_dir($path.DIRECTORY_SEPARATOR.$file_name)) continue;
				$valid_file = $this->get_valid_image_by_filename_2($path.DIRECTORY_SEPARATOR.$file_name, $name_file, $name_packed, $name_dat);
				if ($valid_file) $this->image_tank[$eccident][$crc32]['EMU'][] = $valid_file;
			}
		}
		
		if ($this->images_unsaved_only) {
			
			$image_folders = array(
				'/',
				'/screenshots',
				'/screenshot',
				'/Shots',
				'/Screenshots',
			);

			$ini_player = $this->ini->get_ecc_ini_key('ECC_PLATFORM', $eccident);
			$ini_player = (isset($ini_player['EMU.'.strtolower($file_ext)])) ? $ini_player['EMU.'.strtolower($file_ext)] : false;
			$file_player = (isset($ini_player['path'])) ? $ini_player['path'] : "";
			if ($file_player) {
				foreach ($image_folders as $key => $subfolder) {
					$path = dirname($file_player);
					if (!$path = realpath($path.$subfolder)) continue;
					$hdl = @opendir($path);
					if ($hdl) {
						while($file_name=readdir($hdl)) {
							if ($file_name == "." || $file_name == ".." || is_dir($path.DIRECTORY_SEPARATOR.$file_name)) continue;
							$valid_file = $this->get_valid_image_by_filename_2($path.DIRECTORY_SEPARATOR.$file_name, $name_file, $name_packed, $name_dat);
							if ($valid_file) $this->image_tank[$eccident][$crc32]['EMU'][] = $valid_file;
						}
					}
				}
			}
		}
		return $this->image_tank[$eccident][$crc32]['EMU'];
	}
	
	public function get_valid_image_by_filename_2($file_name, $name_file, $name_packed, $name_dat) {
		
		$ext = strtolower($this->get_ext_form_file($file_name));
		if (isset($this->supported_images[$ext]) && $this->supported_images[$ext]) {
			// DIRTY HACK F�R N64 PROJECT 64
			// Dieser emu speichert seine grafiken nach
			// dem bekannten ecc-system, unterschl�gt aber [!]					
			if (
				false !== strpos($file_name, $name_file) ||
				false !== strpos($file_name, $name_packed) ||
				false !== strpos($file_name, $name_dat)
			) {
				return realpath($file_name);
			}
		}
		return false;
	}
	
	public function image_search($eccident=false, $crc32=false, $path=false, $file_ext=false, $name_file=false, $name_packed=false, $name_dat=false, $only_first_found = true) {
		
		$out = array();
		if ($this->images_inactiv) return $out;
		$out1 = array();
		$out2 = array();
		if ($this->images_unsaved_only) {
			$out2 = $this->get_images_from_emu_2($eccident, $crc32, $path, $file_ext, $name_file, $name_packed, $name_dat, $only_first_found);
		}
		else {
			$out1 = $this->get_images_from_user($eccident, $crc32, $only_first_found);
		}
		$out3 = array_merge($out1, $out2);
		return $out3;
	}
	
	/*
	*
	*/
	public function get_pixbuf($path, $media, $pos=false, $width=false, $height=false, $media_name='unknown') {
		
		if ($pos>0) {
			$file_path = $media[$pos];
			$ext = strtolower($this->get_ext_form_file($file_path));
			if (isset($this->supported_images[$ext]) && $this->supported_images[$ext]) {
				if (file_exists($file_path)) {
					$obj_pixbuff = GdkPixbuf::new_from_file($file_path);
					$obj_pixbuff = $obj_pixbuff->scale_simple($width, $height, Gdk::INTERP_BILINEAR);
					return $obj_pixbuff;
				}
			}
		}
		
		// 
		$width = ($width) ? $width : $this->_pixbuf_width;
		$height = ($height) ? $height :$this->_pixbuf_height;
		
		$obj_pixbuff = null;
		foreach ($media as $file_path) {
			
			$ext = strtolower($this->get_ext_form_file($file_path));
			if (isset($this->supported_images[$ext]) && $this->supported_images[$ext]) {
				if (file_exists($file_path)) {
					$obj_pixbuff = GdkPixbuf::new_from_file($file_path);
					$obj_pixbuff = $obj_pixbuff->scale_simple($width, $height, Gdk::INTERP_BILINEAR);
					return $obj_pixbuff;
				}
			}
		}
		
		// Placeholder-image
		$active_state = ($path) ? 'a' : 'i';
		$img_ident = $media_name.'_'.$active_state;
		$img_ident_size = $width.'x'.$height;
		
		if (isset($this->pixbuf_tank['maincell'][$img_ident."-".$img_ident_size])) {
			return $this->pixbuf_tank['maincell'][$img_ident."-".$img_ident_size];
		}
		else {
			$img_path = dirname(__FILE__)."/".'images/eccsys/media/ecc_ph_media_'.$img_ident.'.png';
			if (!file_exists($img_path)) $img_path = dirname(__FILE__)."/".'images/eccsys/media/ecc_ph_media_unknown_'.$active_state.'.png';
			
			$obj_pixbuff = GdkPixbuf::new_from_file($img_path);
			$obj_pixbuff = $obj_pixbuff->scale_simple($width, $height, Gdk::INTERP_BILINEAR);
			$this->pixbuf_tank['maincell'][$img_ident."-".$img_ident_size] = $obj_pixbuff;
			return $obj_pixbuff;
		}
	}
	
	public $cell_ident_pixbuf = array();
	public function get_pixbuf_eccident($eccident)
	{
		if (isset($this->cell_ident_pixbuf[$eccident])) return $this->cell_ident_pixbuf[$eccident];
		
		// Get path
		$path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_'.$eccident.'_cell.png';
		if (!file_exists($path)) $path = dirname(__FILE__)."/".'images/eccsys/platform/ecc__cell.png';
		
		// create pixbuf and return
		$obj = GdkPixbuf::new_from_file($path);
		$obj->scale_simple(20, 80, Gdk::INTERP_BILINEAR);
		$this->cell_ident_pixbuf[$eccident] = $obj;
		return $obj;
	}
	
	/*
	*
	*/
	function add_fileinfo_to_cell($file_list)
	{
		if ($file_list['count']!=0) {
			foreach ($file_list['data'] as $id => $data) {
				
				// fast refresh activated?
				if ($this->fastListRefresh) while (gtk::events_pending()) gtk::main_iteration();
				
				$eccident = ($data['fd_eccident']) ? $data['fd_eccident'] : $data['md_eccident'];
				$eccident = strtolower($eccident);
				
				$path = dirname($data['path']);
				$name_file = $this->get_plain_filename($data['path']);
				$name_packed = ($data['path_pack']) ? $this->get_plain_filename($data['path_pack']) : false;
				$name_dat = ($data['md_name']) ? $data['md_name'] : false;
				$extension = ($data['path_pack']) ? $this->get_ext_form_file($data['path_pack']) : $this->get_ext_form_file($data['path']);
				$media = $this->image_search($eccident, $data['crc32'], $path, $extension, $name_file, $name_packed, $name_dat);
				
				$obj_pixbuff = $this->get_pixbuf($data['path'], $media, false, false, false, strtolower($eccident));
				
				// get pixbuf
				$pixbuf_eccident = $this->get_pixbuf_eccident($eccident);
				
				// info
				$info_strg = "";
				$info_strg .= ($data['md_info']) ? "\t\t\t\t\t|*[INFOS: ".$data['md_info']."]*|" : '';;
				
				$media_name = "";
				
				if ($data['md_name']) {
					$media_name .= "NAME: ".$data['md_name'];
					$media_name .= "\n";
					$media_name .= str_repeat("-", 57);;
					$media_name .= "\n";
					$year = ($data['md_year']) ? $data['md_year'] : "?????";
					$media_name .= "YEAR: ".$year;
					$media_name .= "\t\t";
					$media_name .= "CATEGORY: ".$this->get_category($data['md_category']);
					$media_name .= "\n";
					
					$languages = "?";
					if ($lang_data = array_keys($this->_fileView->get_language_by_mdata_id($data['md_id']))) {
						$languages = "(".implode(") (",$lang_data).")";
					}
					$media_name .= "LANGUAGES: \t\t".$languages;
					$media_name .= "\n";
					
					$media_name .= "TRAINER: ".$this->get_dropdown_string($data['md_trainer']);
					$media_name .= "\t\t";
					$media_name .= "MULTIPLAYER: ".$this->get_dropdown_string($data['md_multiplayer']);
				}
				else {
					$media_name .= basename($data['path']);
					$media_name .= "\n";
					$media_name .= str_repeat("-", 57);;
					$media_name .= "\n";
					$media_name .= "No informations available - use EDIT";
				}
				
				// create model array for cell output
				$item = array();
				$item[] = $pixbuf_eccident;
				$item[] = $obj_pixbuff;
				$item[] = iconv('ISO-8859-1', 'UTF-8', $media_name);
				$item[] = $data['id'];
				$item[] = $data['md_id'];
				$item[] = $id;
				$this->model->append($item);
				
				unset($media);
				unset($media_name);
				unset($obj_pixbuff);
				unset($item);
			}
		}
	}
	
	/*
	*
	*/
	public function get_media_last_launched()
	{
		$this->view_mode = 'HISTORY';
		$this->setPlatformName('HISTORY');
		
		$this->set_notebook_page_visiblility($this->nb_main, 0, true); // media
		$this->set_notebook_page_visiblility($this->nb_main, 1, false);
		$this->set_notebook_page_visiblility($this->nb_main, 2, false);
		$this->set_notebook_page_visiblility($this->nb_main, 3, false);
		$this->set_notebook_page_visiblility($this->nb_main, 4, false);
		
		$placeholder_path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_history_teaser.png';
		$obj_pixbuff = GdkPixbuf::new_from_file($placeholder_path);
		$obj_pixbuff = $obj_pixbuff->scale_simple(240, 160, Gdk::INTERP_BILINEAR);
		$this->img_plattform->set_from_pixbuf($obj_pixbuff);
		
		$txt = '<b>'.htmlspecialchars($this->ecc_platform_name).'</b>';
		$this->nb_main_lbl_media->set_markup($txt);
		
		$this->onInitialRecord();
	}
	
	/*
	*
	*/
	public function get_media_bookmarks()
	{
		$this->view_mode = 'BOOKMARK';
		$this->setPlatformName('BOOKMARK');
		
		$this->set_notebook_page_visiblility($this->nb_main, 0, true); // media
		$this->set_notebook_page_visiblility($this->nb_main, 1, false);
		$this->set_notebook_page_visiblility($this->nb_main, 2, false);
		$this->set_notebook_page_visiblility($this->nb_main, 3, false);
		$this->set_notebook_page_visiblility($this->nb_main, 4, false);
		
		$txt = '<b>'.htmlspecialchars($this->ecc_platform_name).'</b>';
		$this->nb_main_lbl_media->set_markup($txt);

		$placeholder_path = dirname(__FILE__)."/".'images/eccsys/platform/ecc_bookmark_teaser.png';
		$obj_pixbuff = GdkPixbuf::new_from_file($placeholder_path);
		$obj_pixbuff = $obj_pixbuff->scale_simple(240, 160, Gdk::INTERP_BILINEAR);
		$this->img_plattform->set_from_pixbuf($obj_pixbuff);
		
		$this->onInitialRecord();
	}
	
	/*
	*
	*/
	public function add_bookmark_by_id() {
		if (!$this->current_media_info['id']) return false;
		$this->_fileView->add_bookmark_by_id($this->current_media_info['id']);
	}
	
	/*
	*
	*/
	public function remove_media_from_fdata()
	{
		if (!$this->current_media_info['id']) return false;
		
		$id = $this->current_media_info['id'];
		$eccident = $this->current_media_info['fd_eccident'];
		$crc32 = $this->current_media_info['crc32'];
		$rom_title = $this->current_media_info['title'];
		
		$title = I18N::get('popup', 'rom_remove_single_title');
		$msg = sprintf(I18N::get('popup', 'rom_remove_single_msg%s'), $rom_title);
		if (!$this->open_window_confirm($title, $msg)) return false;		
		$status = $this->_fileView->remove_media_from_fdata($id, $eccident, $crc32);
		
		$duplicates = $this->_fileView->get_duplicates($eccident, $crc32);
		if (count($duplicates)) {
			$title = I18N::get('popup', 'rom_remove_single_dupfound_title');
			$msg = sprintf(I18N::get('popup', 'rom_remove_single_dupfound_msg%d%s'), count($duplicates), $rom_title);
			if ($this->open_window_confirm($title, $msg)) $this->_fileView->remove_media_duplicates($eccident, $crc32);
		}
		if ($status === true) {
			$this->model->clear();
			$this->update_treeview_nav();
			$this->onReloadRecord(false);
		}
	}
	
	/*
	*
	*/
	public function remove_bookmark_by_id() {
		if (!$this->current_media_info['id']) return false;
		$this->_fileView->remove_bookmark_by_id($this->current_media_info['id']);
		$this->get_media_bookmarks();
	}
	
	/*
	*
	*/
	public function remove_bookmark_all() {
		$title = I18N::get('popup', 'fav_remove_all_title');
		$msg = I18N::get('popup', 'fav_remove_all_msg');
		if (!$this->open_window_confirm($title, $msg)) return false;

		$this->_fileView->remove_bookmark_all();
		$this->get_media_bookmarks();
	}
	
	/*
	* dispatcher
	*/
	public function filelist_data_dispatcher($eccident, $search_like, $limit, $test, $order_by, $search_lang_strg, $search_cat_id, $search_ext=false) {
		$view_mode = $this->view_mode;
		switch($view_mode) {
			case 'MEDIA':
				return $this->_fileView->get_file_data_TEST_META($eccident, $search_like, $limit, $test, $order_by, $search_lang_strg, $search_cat_id, $search_ext, $this->toggle_show_files_only, $this->toggle_show_doublettes, $this->toggle_show_metaless_roms_only);
				break;
			case 'BOOKMARK':
				return $this->_fileView->get_bookmarks(false, $search_like, $limit, $test, $order_by, $search_lang_strg, $search_cat_id, $search_ext, $this->toggle_show_files_only, $this->toggle_show_doublettes, $this->toggle_show_metaless_roms_only);
				break;
			case 'HISTORY':
				return $this->_fileView->get_last_launched(false, $search_like, $limit, $test, $order_by, $search_lang_strg, $search_cat_id, $search_ext, $this->toggle_show_files_only, $this->toggle_show_doublettes, $this->toggle_show_metaless_roms_only);
				break;
		}
		return false;
	}
	
	/*
	*
	*/
	public function onReloadRecord($reload_images=true, $switch_notebook_page=true)
	{
		if ($reload_images===true) {
			$this->image_tank = array();
		}
		
		if ($switch_notebook_page) $this->nb_main->set_current_page(0);
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';
		
		$search_like = "";
		if ($this->_search_word) {
			$like_pre = (!$this->_search_word_like_pre) ? '%' : '';
			$like_post = (!$this->_search_word_like_post) ? '%' : '';
			$search_like = "
				
				(title like '".$like_pre.sqlite_escape_string($this->_search_word.$like_post)."' OR
				name like '".$like_pre.sqlite_escape_string($this->_search_word).$like_post."')
			";
		}
		
		$pager_data = $this->media_treeview_pager->reload();
		
		$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		
		$limit = array($pager_data->_res_offset, $pager_data->_pp);
		
		$file_list = $this->filelist_data_dispatcher($this->_eccident, $search_like, $limit, true, $order_by, $this->_search_language, $this->_search_category, $this->ext_search_selected);
		
		$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
		
		$this->model->clear();		
		if (isset($file_list) && $file_list['count'] > 0) {
			$this->add_fileinfo_to_cell($file_list);
		}
	}
	
	/*
	*
	*/
	public function onInitialRecord()
	{
		$this->model->clear();
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';
		
		$search_like = "";
		if ($this->_search_word) {
			$like_pre = (!$this->_search_word_like_pre) ? '%' : '';
			$like_post = (!$this->_search_word_like_post) ? '%' : '';
			$search_like = "
				
				(title like '".$like_pre.sqlite_escape_string($this->_search_word.$like_post)."' OR
				name like '".$like_pre.sqlite_escape_string($this->_search_word).$like_post."')
			";
		}
		
		$limit = array(0, $this->_results_per_page);
		
		
		$file_list = $this->filelist_data_dispatcher($this->_eccident, $search_like, $limit, true, $order_by, $this->_search_language, $this->_search_category, $this->ext_search_selected);
		
		$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
		$this->data_available = $file_list['count'];
		
		$pager_data = $this->media_treeview_pager->init($file_list['count'], 0, $this->_results_per_page);
		
		if ($pager_data->_pt > 0) {
			$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		}
		else {
			$pager_txt = '<span foreground="#cc0000"><b>NO DATA FOUND!</b></span>';
			$this->media_pager_label->set_markup($pager_txt);
		}
		
		$this->media_pager_first->set_sensitive(true);
		$this->media_pager_prev->set_sensitive(true);
		$this->media_pager_last->set_sensitive(true);
		$this->media_pager_next->set_sensitive(true);
		if ($pager_data->_pfirst) {
			$this->media_pager_first->set_sensitive(false);
			$this->media_pager_prev->set_sensitive(false);
		}
		if ($pager_data->_plast) {
			$this->media_pager_last->set_sensitive(false);
			$this->media_pager_next->set_sensitive(false);
		}
		
		
		if (isset($file_list) && $file_list['count'] > 0) {
			$this->add_fileinfo_to_cell($file_list);
		}
	}
	
	/*
	*
	*/
	public function onNextRecord()
	{
		
		$this->nb_main->set_current_page(0);
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';
		
		$search_like = "";
		if ($this->_search_word) {
			$like_pre = (!$this->_search_word_like_pre) ? '%' : '';
			$like_post = (!$this->_search_word_like_post) ? '%' : '';
			$search_like = "
				
				(title like '".$like_pre.sqlite_escape_string($this->_search_word.$like_post)."' OR
				name like '".$like_pre.sqlite_escape_string($this->_search_word).$like_post."')
			";
		}
		
		$pager_data = $this->media_treeview_pager->next();
		
		$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		
		$this->media_pager_first->set_sensitive(true);
		$this->media_pager_prev->set_sensitive(true);
		$this->media_pager_last->set_sensitive(true);
		$this->media_pager_next->set_sensitive(true);
		if ($pager_data->_plast) {
			$this->media_pager_last->set_sensitive(false);
			$this->media_pager_next->set_sensitive(false);
		}
		
		$limit = array($pager_data->_res_offset, $pager_data->_pp);
		
		$file_list = $this->filelist_data_dispatcher($this->_eccident, $search_like, $limit, true, $order_by, $this->_search_language, $this->_search_category, $this->ext_search_selected);
		
		$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
		
		if (isset($file_list) && $file_list['count'] > 0) {
			$this->model->clear();
			$this->add_fileinfo_to_cell($file_list);
		}
	}
	
	public function set_pager_position_label($gui_label, $page_current, $page_total, $count_total) {
		$pager_txt = $page_current." / ".$page_total." (".$count_total.")";
		$pager_txt = "<b>".$pager_txt."</b>";
		$gui_label->set_markup($pager_txt);
	}
	
	/*
	*
	*/
	public function onPrevRecord()
	{
		$this->nb_main->set_current_page(0);
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';
		
		$search_like = "";
		if ($this->_search_word) {
			$like_pre = (!$this->_search_word_like_pre) ? '%' : '';
			$like_post = (!$this->_search_word_like_post) ? '%' : '';
			$search_like = "
				
				(title like '".$like_pre.sqlite_escape_string($this->_search_word.$like_post)."' OR
				name like '".$like_pre.sqlite_escape_string($this->_search_word).$like_post."')
			";
		}
		
		$pager_data = $this->media_treeview_pager->prev();
		
		$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		
		$this->media_pager_first->set_sensitive(true);
		$this->media_pager_prev->set_sensitive(true);
		$this->media_pager_last->set_sensitive(true);
		$this->media_pager_next->set_sensitive(true);
		if ($pager_data->_pfirst) {
			$this->media_pager_first->set_sensitive(false);
			$this->media_pager_prev->set_sensitive(false);
		}
		
		$limit = array($pager_data->_res_offset, $pager_data->_pp);
		
		$file_list = $this->filelist_data_dispatcher($this->_eccident, $search_like, $limit, true, $order_by, $this->_search_language, $this->_search_category, $this->ext_search_selected);
		
		$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
		
		if (isset($file_list) && $file_list['count'] > 0) {
			$this->model->clear();
			$this->add_fileinfo_to_cell($file_list);
		}
	}
	
	/*
	*
	*/
	public function onLastRecord()
	{
		$this->nb_main->set_current_page(0);
		
		$pager_data = $this->media_treeview_pager->last();
		
		$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		
		$this->media_pager_first->set_sensitive(true);
		$this->media_pager_prev->set_sensitive(true);
		$this->media_pager_last->set_sensitive(false);
		$this->media_pager_next->set_sensitive(false);
		
		$this->onReloadRecord(false);
	}
	
	/*
	*
	*/
	public function onFirstRecord()
	{
		$this->nb_main->set_current_page(0);
		
		$pager_data = $this->media_treeview_pager->first();
		
		$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		
		$this->media_pager_first->set_sensitive(false);
		$this->media_pager_prev->set_sensitive(false);
		$this->media_pager_last->set_sensitive(true);
		$this->media_pager_next->set_sensitive(true);
		
		$this->onReloadRecord(false);
	}
	
	/*
	*
	*/
	public function open_window_info($title=false, $msg=false)
	{
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$msg = ($msg) ? $msg : I18N::get('popup', 'sys_dialog_miss_msg');
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_OK, $msg);
		
		if ($title) $dialog->set_title($title);
		$response = $dialog->run();
		
		$dialog->destroy();
		
		if ($dialog->get_transient_for()) {
			$dialog->get_transient_for()->present();
		}
		
		if ($response == Gtk::RESPONSE_OK) {
			return true;
		}
		return false;
	}
	
	/*
	*
	*/
	public function open_window_confirm($title=false, $msg=false)
	{
		
		#print "#\n";
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$msg = ($msg) ? $msg : I18N::get('popup', 'sys_dialog_miss_msg');
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_YES_NO, $msg);
		
		if ($title) $dialog->set_title($title);
		$response = $dialog->run();
		$dialog->destroy();
		
		#if ($dialog->get_transient_for()) {
		#	$dialog->get_transient_for()->present();
		#}
		
		if ($response == Gtk::RESPONSE_YES) {
			return true;
		}
		
		return false;
	}
	
//	/*
//	*
//	*/
//	public function openFileChooserDialog($title=false, $path=false, $extension_limit=false, $type=Gtk::FILE_CHOOSER_ACTION_SELECT_FOLDER) {
//		$title = ($title) ? $title : I18N::get('popup', 'sys_filechooser_miss_title');
//		$dialog = new GtkFileChooserDialog(
//			$title,
//			NULL,
//			$type,
//			array(
//				Gtk::STOCK_CANCEL,
//				Gtk::RESPONSE_CANCEL,
//				Gtk::STOCK_OK,
//				Gtk::RESPONSE_OK
//			)
//		);
//		
//		if (!realpath($path)) {
//			$path = (dirname($path)) ? dirname($path) : false;
//		}
//		
//		if ($path) $dialog->set_filename($path);
//		
//		if (is_array($extension_limit) && count($extension_limit)) {
//			foreach ($extension_limit as $filter_name => $filter_value) {
//				$filter = new GtkFileFilter();
//				$filter->set_name($filter_name);
//				$filter->add_pattern($filter_value);
//				$dialog->add_filter($filter);
//			}
//			$filter2 = new GtkFileFilter();
//		}
//		
//		$response = $dialog->run();
//		if ($response === Gtk::RESPONSE_OK) {
//			$path = $dialog->get_filename();
//			$dialog->destroy();
//			return $path;
//		}
//		$dialog->destroy();
//		return false;
//	}
	
	/*
	*
	*/
	public function parseMedia() {
		if ($this->status_obj->init()) {
			if (!isset($platfom)) $platfom = strtoupper($this->ecc_platform_name);
			if (!$this->setPathForEccParser($platfom)) {
				$this->status_obj->reset1();
				return false;
			}
			$title = sprintf(I18N::get('popup', 'rom_add_parse_title%s'), $platfom);
			$msg = sprintf(I18N::get('popup', 'rom_add_parse_msg%s%s'), $platfom, $this->fs_path_for_parser);
			if (!$this->open_window_confirm($title, $msg)) {
				$this->status_obj->reset1();
				return false;
			}
			$this->status_obj->set_label('Parse ROMS for "'.$platfom.'"');
			$this->status_obj->set_popup_cancel_msg("Process canceled", "Do you really want to cancel this?");
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			require_once('manager/cEccParser.php');
			$eccparser = new EccParser($this->_eccident, $this->ini, $this->fs_path_for_parser, $this->pbar_parser, $this->statusbar_lbl_bottom, $this->status_obj, $this);
//			# FACTORY!
			
			$this->update_treeview_nav();
			$this->onInitialRecord();
			
			$title = I18N::get('popup', 'rom_add_parse_done_title');
			$msg = sprintf(I18N::get('popup', 'rom_add_parse_done_msg%s'), strtoupper($this->ecc_platform_name));
			$this->status_obj->open_popup_complete($title, $msg);
		}
		
	}
	
	/*
	*
	*/
	public function setPathForEccParser($platfom)
	{
		// get path from history
		$path_history = $this->ini->read_ecc_histroy_ini('eccparser');
		$title = sprintf(I18N::get('popup', 'rom_add_filechooser_title%s'), $platfom);

		#$path = $this->openFileChooserDialog($title, $path_history);
		$path = FACTORY::get('manager/Os')->openChooseFolderDialog($path_history, $title);
		
		if ($path !== false) {
			// write path to history
			$this->ini->write_ecc_histroy_ini('eccparser', $path, true);
			$this->fs_path_for_parser = $path;
			return true;
		}
		return false;
	}
	
	public function hide($obj)
	{
		$obj->hide();
	}
	
	public function delete($obj)
	{
		$obj->delete();
	}
	
	public function quit() {
		$this->ini->write_ecc_histroy_ini('navigation_last', $this->_eccident, false);
		gtk::main_quit();
	}
	
	/*
	* internal function for glade
	*/
	private function __get($property) {
		return parent::get_widget($property);
	}
}
$obj_test = new App();
?>
