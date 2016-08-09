<?php

define('LF', "\n");

error_reporting(E_ALL);

chdir(dirname(__FILE__));
define("MY_MASK", Gdk::BUTTON_PRESS_MASK);
if(!defined('DEBUG')) define('DEBUG', true);

if(!defined('ECC_DIR_OFFSET')) define('ECC_DIR_OFFSET', "..".DIRECTORY_SEPARATOR); # needed for relative paths
if(!defined('ECC_DIR')) define('ECC_DIR', realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.ECC_DIR_OFFSET)); # contains basepath of ecc
if(!defined('ECC_DIR_SYSTEM')) define('ECC_DIR_SYSTEM', ECC_DIR.'/ecc-system/'); # contains ecc-system dir

# write ini for external ecc tools
include(ECC_DIR_SYSTEM.'/manager/fStartupHelper.php');
EccExtHelper::writeLocalHostInfo(ECC_DIR_SYSTEM.'/infos/ecc_local_host_info.ini');

# static class for generating comboboxes
require_once('manager/cIndexedCombobox.php');
# new singleton factory
require_once('manager/cFactory.php');
# static class for translation
require_once('manager/ci18n.php');
# static class for translation
require_once('manager/cValid.php');
# needed for char enconding converting and detecting tasks
require_once('manager/cMultiByte.php');
# loggs to the logs folder!
require_once('manager/cLogger.php');

/**
 * emuControlCenter Main class.
 * 
 * @autor Andreas Scheibel <ecc@camya.com>
 * 
 */
class App extends GladeXml {
	
	private $comletionData = array();
	
	public $optVisMainListMode = false;
	
	public $ini = false;
	
	public $os_env = "";
	
	private $dbms = false;
	private $_fileView = false;
	
	private $nav_inactive_hidden = false;
	
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
	private $searchRating = false;
	private $searchFreeformType = 'NAME';
	private $searchFreeformOperator = 'AND';
	private $ext_search_selected = array();
	
	// caches versions of pixbufs
	// $this->pixbuf_tank[type][ident] = pixbuf
	private $pixbuf_tank = array();
	
	// default sizes for mainview images
	// could be configured in ecc_general.ini
	// set by set_ecc_image_size_from_ini at startup
	private $_pixbuf_width = 120;
	private $_pixbuf_height = 80;
	
	private $imagesAspectRatio = false;
	
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
	
	private $ratingChar = '* ';
	
	public $image_type_selected = false;

	public $fs_path_for_parser = false;
	
	public $toggle_show_files_only = false;
	public $toggle_show_metaless_roms_only = false;
	public $toggle_show_doublettes = false;
	public $toggle_only_disk = false;

	// Colors
	public $background_color='#ffffff';
	
	public $nb_main_page_selected = 0;
	
	private $media_edit_is_opened = false;
	
	public $currentPlatformCategory = false;
	
	private $sessionKey = false;
	
	private $objTooltips;
	private $objLinkCursor;
	
	private $visibleNavigation = true;
	private $visibleMedia = true;
	private $visibleSearch = true;
	
	# if set, dont update data
	private $breakSearchReset = false;
	
	#
	private $selectedEccidentBreak;
	
	// manager
	private $oHelper = false;
	
	public function create_combo_lanugages($widget)
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
		$combobox->init_combobox($widget, $data);

		$lang = array();
		$lang[] = array(
			false,
			$this->oHelper->getPixbuf(dirname(__FILE__)."/"."images/eccsys/languages/ecc_lang_unknown.png"),
			strtoupper(i18n::get('global', 'all')),
		);
		foreach($this->media_language as $indent => $label) {
			$img_path = dirname(__FILE__)."/".'images/eccsys/languages/ecc_lang_'.strtolower($indent).'.png';
			if (!file_exists($img_path)) $img_path = dirname(__FILE__)."/".'images/eccsys/languages/ecc_lang_unknown.png';
			$lang[] = array(
				$indent,
				$this->oHelper->getPixbuf($img_path),
				$label,
			);
		} 
		
		$combobox->fill($lang);
		$widget->connect("changed", array($this, 'set_search_language_from_combobox'));
	}
	
	public function set_search_language_from_combobox($combobox) {
		
		$this->nb_main->set_current_page(0);
		
		$this->_search_language = $combobox->get_active_text();
		
		$state = ($this->_search_language) ? true : false;
		$this->set_search_state('language', $state);
		
		$this->update_treeview_nav();
		
		$this->onInitialRecord();
	}
	
	public function set_search_state($ident, $state) {
		$this->_search_active[$ident] = $state;
		
		$image = ($state) ? 'box_hilight' : 'box';

		$imageObject = FACTORY::get('manager/Image');
		$imageObject->setWidgetBackground($this->eventbox1, 'background/'.$image.'.png');
		
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
		$this->_search_active = array();
	}
	
	/*
	* ext_search
	* functionen for ext search
	*/
	public function dispatcher_ext_search($obj) {
		
		$this->nb_main->set_current_page(0);
		
		$state = $obj->get_active_text();
		$this->ext_search_selected[$obj->get_name()] = $state;
		$state = $this->ini->storeHistoryKey($obj->get_name(), $state, false);
		$this->ext_search_reset->set_sensitive(true);
		
		// now control, if any dropdown is selected.
		// if all set to item 0, reset
		if (!$this->get_ext_search_state()) $this->reset_ext_search_state();
		
		$this->update_treeview_nav();

		$this->onInitialRecord();
	}
	
	public function get_ext_search_state() {
		foreach ($this->ext_search_selected as $ident => $state) {
			if ($state) {
				//$this->ext_search_expander_lbl->set_markup('<b>eSearch - more search options</b> <span color="#cc0000">(eSearch active!!!)</span>');
				$this->infoEsearchLbl->set_markup('<span color="#cc0000"><b>'.strtoupper(i18n::get('mainGui', 'romDetailTabESearch')).'</b></span>');
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
		$this->infoEsearchLbl->set_markup(strtoupper(i18n::get('mainGui', 'romDetailTabESearch')));
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

		# DISABLED FOR JAPANESE TEST
		if (i18n::getLanguageIdent() != 'jp') $textview->modify_font(new PangoFontDescription($this->os_env['FONT']." 10"));

		$textview->set_wrap_mode(Gtk::WRAP_WORD);
	}
	
	function indexedComboChanged($combo){
		$key = FACTORY::get('manager/IndexedCombo')->getKey($combo);
		$value = FACTORY::get('manager/IndexedCombo')->getValue($combo);
		echo 'Selected: ' . $key . ' => ' . $value . "\r\n";
	}
	
	public function setSearchCategoryMain($combo) {
		$key = FACTORY::get('manager/IndexedCombo')->getKey($combo);
		$this->_search_category = $key;
		$state = ($key) ? true : false;
		$this->set_search_state('category', $state);
		
		$this->update_treeview_nav();
		
		$this->nb_main->set_current_page(0);
		$this->onInitialRecord(false);
	}
	
	public function dispatchSearchFfType($object, $event) {
		if ($event) {
			$menuRating = new GtkMenu();
			$menuItemRating = new GtkMenuItem(I18N::get('menu', 'lbl_rating_submenu'));
			
			$miRating = new GtkMenuItem(I18N::get('global', 'searchField'));
			$miRating->set_sensitive(false);
			$menuRating->append($miRating);
			$menuRating->append(new GtkSeparatorMenuItem());
			
			foreach ($this->freeformSearchFields as $key => $label) {
				if ($this->searchFreeformType == $key) $label = "[#] ".strtoupper($label)."";
				$miRating = new GtkMenuItem($label);
				$miRating->connect_simple_after('activate', array($this, 'setSearchFfType'), $key);
				$menuRating->append($miRating);
			}
		}
		$menuRating->show_all();
		$menuRating->popup();
	}
	public function setSearchFfType($type, $reload=true) {

		#$this->searchSelectorFfTypeLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>'.$type[0].$type[1].'</b></span>');
		$this->searchSelectorFfTypeLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>'.i18n::get('dropdown_search_fields', '[['.strtolower($type).']]').'</b></span>');
		
		switch($type) {
			case 'YEAR':
				$field = 'year';
				break;
			case 'DEVELOPER':
				$field = 'creator';
				break;
			case 'PUBLISHER':
				$field = 'publisher';
				break;
			case 'PROGRAMMER':
				$field = 'programmer';
				break;
			case 'MUSICAN':
				$field = 'musican';
				break;
			case 'GRAPHICS':
				$field = 'graphics';
				break;
			default:
				$field = false;
		}
		
		$autoCompletion = FACTORY::get('manager/AutoCompletion');
		$data = FACTORY::get('manager/TreeviewData')->getAutoCompleteData($field);
		$autoCompletion->connect($this->search_input_txt, $data);
		
		$color =  ($type == 'NAME') ? $this->colEventOptionSelect1 : $this->colEventOptionActive;
		$this->searchSelectorFfType->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($color));
		
		$this->searchFreeformType = $type;
		if ($reload) $this->onInitialRecord();

		if ($reload) $this->update_treeview_nav();
	}
	
	public function dispatchSearchFfOperator($object, $event) {
		if ($event) {
			$menuRating = new GtkMenu();
			$menuItemRating = new GtkMenuItem(I18N::get('menu', 'lbl_rating_submenu'));
			
			$miRating = new GtkMenuItem(I18N::get('global', 'searchOperator'));
			$miRating->set_sensitive(false);
			$menuRating->append($miRating);
			
			$menuRating->append(new GtkSeparatorMenuItem());
			
			foreach ($this->freeformSearchOperators as $key => $label) {
				
				if ($this->searchFreeformType == $key) {
					$label = "[#] ".strtoupper($label)."";
				}
				
				$miRating = new GtkMenuItem($label);
				$miRating->connect_simple('activate', array($this, 'setSearchFfOperator'), $key, $label);
				$menuRating->append($miRating);
			}
		}
		$menuRating->show_all();
		$menuRating->popup();
	}
	
	public function setSearchFfOperator($key, $label, $reload=true) {
		
		$this->searchSelectorOperatorLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>'.$label.'</b></span>');
		
		$color =  ($key == 'AND') ? $this->colEventOptionSelect1 : $this->colEventOptionActive;
		$this->searchSelectorOperator->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($color));
		
		$this->searchFreeformOperator = $key;
		
		if ($reload) $this->onInitialRecord();
		if ($reload) $this->update_treeview_nav();
	}
	
	
	
	public function dispatchSearchSelectory($object, $event) {
		if ($event) {
			$menuRating = new GtkMenu();
			$menuItemRating = new GtkMenuItem(I18N::get('menu', 'lbl_rating_submenu'));
			
			$miRating = new GtkMenuItem(I18N::get('global', 'searchRatings'));
			$miRating->set_sensitive(false);
			$menuRating->append($miRating);
			
			$menuRating->append(new GtkSeparatorMenuItem());
			
			for ($i=0; $i<=6; $i++) {
				$ratingString = str_repeat($this->ratingChar, $i);
				$miRating = new GtkMenuItem($ratingString);
				$miRating->connect_simple('activate', array($this, 'setSearchRating'), $i);
				$menuRating->append($miRating);
			}
		}
		$menuRating->show_all();
		$menuRating->popup();
	}
	
	public function setSearchRating($rate, $reload=true) {
		$this->searchSelectorRatingLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>'.$rate.'*</b></span>');
		$color =  (!$rate) ? $this->colEventOptionSelect1 : $this->colEventOptionActive;
		$this->searchSelectorRating->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($color));
		
		
		$this->searchRating = $rate;
		
		$state = ($rate) ? true : false;
		$this->set_search_state('rating', $state);
		if (!$this->breakSearchReset && $reload) $this->onInitialRecord();
	}
	
//	public function directRating($object, $event) {
//		if ($event) {
//			$menuRating = new GtkMenu();
//			$menuItemRating = new GtkMenuItem(I18N::get('menu', 'lbl_rating_submenu'));
//			
//			$miRating = new GtkMenuItem(I18N::get('global', 'rateRom'));
//			$miRating->set_sensitive(false);
//			$menuRating->append($miRating);
//			
//			$menuRating->append(new GtkSeparatorMenuItem());
//			
//			for ($i=6; $i>=0; $i--) {
//				$ratingString = str_repeat($this->ratingChar, $i);
//				$miRating = new GtkMenuItem($ratingString);
//				$miRating->connect_simple('activate', array($this, 'dispatch_menu_context'), 'RATING', $i);
//				$menuRating->append($miRating);
//			}
//		}
//		$menuRating->show_all();
//		$menuRating->popup();
//	}
	
	public function simpleContextMenu($title=false, $dataArray=array(), $callback, $field, $param=false) {
			if (!$title) $title = I18N::get('global', 'change');
			$menu = new GtkMenu();
			$menuItem = new GtkMenuItem($title);
			$menuItem->set_sensitive(false);
			$menu->append($menuItem);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			foreach ($dataArray as $key => $value) {
				$menuItem = new GtkMenuItem($value);
				$menuItem->connect_simple_after('activate', array($this, $callback), $dataArray, $key, $field, $param);
				$menu->append($menuItem);
			}
		$menu->show_all();
		$menu->popup();
	}
	
	public function contextViewMode($i18nKeyAndCallbackParam=array(), $i18nCategory = 'menuTop'){
		
		$menu = new GtkMenu();
		$menuItem = new GtkMenuItem(I18N::get('mainGui', 'contextViewModeSelectHeader'));
		$menuItem->set_sensitive(false);
		$menu->append($menuItem);

		$menu->append(new GtkSeparatorMenuItem());
		
		foreach($i18nKeyAndCallbackParam as $i18nKey => $callbackParam) {
			$menuItem = new GtkMenuItem(I18N::get($i18nCategory, $i18nKey));
			$menuItem->connect_simple_after('activate', array($this, 'dispatch_menu_context_platform'), $callbackParam);
			$menu->append($menuItem);
		}
		
		$menu->show_all();
		$menu->popup();
	}
	
	public function simpleMetaUpdate($dataArray, $key, $field, $dontUseBool = false) {
		
		if (!isset($this->current_media_info)) return false;
		
		if ($dontUseBool) {
			$this->current_media_info[$field] = $key;
		} else {
			$this->current_media_info[$field] = $this->get_dropdown_bool($key);
		}
		
		$id = $this->_fileView->saveMetaData($this->current_media_info);
		// only, if new dataset
		if ($id) $this->current_media_info['md_id'] = $id;
		
		$this->directMediaEdit = true;
		$this->show_media_info();
		$this->onReloadRecord(false);
		$this->directMediaEdit = false;	
	}

	
	
	public function updateMediaInfoFlags($selectedLanguages, $resultsPerRow = 5) {
		
		$frameChild = $this->frameMediaInfoEvent->child;
		if ($frameChild) $this->frameMediaInfoEvent->remove($frameChild);
		
		$this->frameMediaInfoEvent->connect_simple_after('button-press-event', array($this, 'edit_media'), false, 0);
		$this->frameMediaInfoEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse('#ffffff'));

		$table = new GtkTable();
		$this->frameMediaInfoEvent->add($table);
		
		//counts
		$cntTotal = count($selectedLanguages);
		if ($cntTotal) {
			
			$cntRow = ceil($cntTotal/$resultsPerRow);
			
			// build table
			$languagePosition = 0;
			for ($row=0; $row<$cntRow; $row++) {
				for ($col=0; $col<$resultsPerRow; $col++) {
					if (isset($selectedLanguages[$languagePosition])) {

						// get pixbuf
						$base_path = dirname(__FILE__)."/"."images/eccsys/languages/";
						$path_a = $base_path.'ecc_lang_'.$selectedLanguages[$languagePosition].'.png';
						if (!file_exists($path_a)) $path_a =  $base_path.'ecc_lang_unknown.png';

						$pixbuf_icon_active = $this->oHelper->getPixbuf($path_a);

						// set pixbuf to image
						$image = new GtkImage();
						$image->set_from_pixbuf($pixbuf_icon_active);
						
						$table->attach($image, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
					}
					$languagePosition++;
				}
			}
		}
		else {
			$imgPathEditButton = dirname(__FILE__).'/images/eccsys/languages/btn_edit.png';
			$pixbufEditButton = $this->oHelper->getPixbuf($imgPathEditButton);
	
			// set pixbuf to image
			$image = new GtkImage();
			$image->set_from_pixbuf($pixbufEditButton);
			
			//$button = new GtkButton($row." - ".$col);
			$table->attach($image, 0, 1, 0, 1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
		}
		$this->frameMediaInfoEvent->show_all();
	}
	
	
	public function createEccOptBtnBar($updateOnly=false) {
		
		$frameChild = $this->eccOptBtnBar->child;
		if ($frameChild) $this->eccOptBtnBar->remove($frameChild);
		
		$table = new GtkTable();
		$this->eccOptBtnBar->add($table);
		
		$col = 0;
		$row = 0;
		
		#if ($fixedType && $fixedType == '') 
		
		$state = ($this->optVisMainListMode) ? 'a' : 'i'; 
		$imageFile = dirname(__FILE__).'/images/eccsys/options/ecc_optvis_listmode_'.$state.'.png';
		
		$pixbuf = $this->oHelper->getPixbuf($imageFile);
		$oImage = new GtkImage();
		$oImage->set_from_pixbuf($pixbuf);	

		$oEvent = new GtkEventBox();
		$this->objTooltips->set_tip($oEvent, I18N::get('tooltips', 'optvis_mainlistmode'));
		$oEvent->connect_simple_after('button-press-event', array($this, 'updateEccOptBtnBar'), 'optVisMainListMode', 'toggleMailListMode');
		$oEvent->add($oImage);
		
		$table->attach($oEvent, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
		
		$col++;
		//$row++;
		
		$imageFile = dirname(__FILE__).'/images/eccsys/options/ecc_optvis_spacer.png';
		$pixbuf = $this->oHelper->getPixbuf($imageFile);
		$oImage = new GtkImage();
		$oImage->set_from_pixbuf($pixbuf);	
		$table->attach($oImage, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);
		
		$col++;
		//$row++;
		
		$state = ($this->nav_inactive_hidden) ? 'a' : 'i'; 
		$imageFile = dirname(__FILE__).'/images/eccsys/options/ecc_opt_hide_nav_null_'.$state.'.png';
		$pixbuf = $this->oHelper->getPixbuf($imageFile);
		$oImage = new GtkImage();
		$oImage->set_from_pixbuf($pixbuf);
		
		$oEvent = new GtkEventBox();
		$this->objTooltips->set_tip($oEvent, I18N::get('tooltips', 'opt_hide_nav_null'));
		$oEvent->connect_simple_after('button-press-event', array($this, 'updateEccOptBtnBar'), 'nav_inactive_hidden', 'dispatch_menu_context_platform', 'PLATFORM_TOGGLE_INACTIVE');
		$oEvent->add($oImage);
		
		$table->attach($oEvent, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
		
		$col++;
		//$row++;

		$state = ($this->toggle_show_doublettes) ? 'a' : 'i'; 
		$imageFile = dirname(__FILE__).'/images/eccsys/options/ecc_opt_hide_dup_'.$state.'.png';
		$pixbuf = $this->oHelper->getPixbuf($imageFile);
		$oImage = new GtkImage();
		$oImage->set_from_pixbuf($pixbuf);

		$oEvent = new GtkEventBox();
		$this->objTooltips->set_tip($oEvent, I18N::get('tooltips', 'opt_hide_dup'));
		$oEvent->connect_simple_after('button-press-event', array($this, 'updateEccOptBtnBar'), 'toggle_show_doublettes', 'dispatch_menu_context_platform', 'TOGGLE_MAINVIEV_DOUBLETTES');
		$oEvent->add($oImage);
		
		$table->attach($oEvent, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
		
		$col++;
		//$row++;
		
		$state = (!$this->toggle_only_disk || !in_array($this->toggle_only_disk, array('all', 'one', 'one_plus'))) ? 'all' : $this->toggle_only_disk; 
		$imageFile = dirname(__FILE__).'/images/eccsys/options/ecc_opt_hide_disk_'.$state.'.png';
		$pixbuf = $this->oHelper->getPixbuf($imageFile);
		$oImage = new GtkImage();
		$oImage->set_from_pixbuf($pixbuf);	

		$oEvent = new GtkEventBox();
		$this->objTooltips->set_tip($oEvent, I18N::get('tooltips', 'opt_only_disk'));

		#$oEvent->connect_simple_after('button-press-event', array($this, 'updateEccOptBtnBar'), 'toggle_only_disk', 'dispatch_menu_context_platform', 'ONLY_DISK');
		
		# context menu for main navigation button ROMS
		$contextRoms = array(
			'optionContextOnlyDiskAll' => 'TOGGLE_VIEWMODE_DISK_ALL',
			'optionContextOnlyDiskOne' => 'TOGGLE_VIEWMODE_DISK_ONE',
			'optionContextOnlyDiskOnePlus' => 'TOGGLE_VIEWMODE_DISK_ONE_PLUS',
		);
		$oEvent->connect_simple_after('button-press-event', array($this, 'contextViewMode'), $contextRoms, 'tooltips');
		
		$oEvent->add($oImage);
		
		$table->attach($oEvent, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
		
		$col++;
		//$row++;
				
		$state = ($this->images_inactiv) ? 'a' : 'i'; 
		$imageFile = dirname(__FILE__).'/images/eccsys/options/ecc_opt_hide_img_'.$state.'.png';
		$pixbuf = $this->oHelper->getPixbuf($imageFile);
		$oImage = new GtkImage();
		$oImage->set_from_pixbuf($pixbuf);	

		$oEvent = new GtkEventBox();
		$this->objTooltips->set_tip($oEvent, I18N::get('tooltips', 'opt_hide_img'));
		$oEvent->connect_simple_after('button-press-event', array($this, 'updateEccOptBtnBar'), 'images_inactiv', 'dispatch_menu_context_platform', 'IMG_TOGGLE');
		$oEvent->add($oImage);
		
		$table->attach($oEvent, $col, $col+1, $row, $row+1, Gtk::SHRINK, Gtk::SHRINK, 0, 0);	
		
		$col++;
		//$row++;
		
		$table->show_all();
	}
	
	public function updateEccOptBtnBar($var, $callback=false, $callbackParam=false) {
		if ($callback) $this->$callback($callbackParam);
		while (gtk::events_pending()) gtk::main_iteration();		
		$this->createEccOptBtnBar(true);
	}
	
	public function toggleMailListMode() {
		$this->optVisMainListMode = !$this->optVisMainListMode;
		$this->ini->storeHistoryKey('optVisMainListMode', $this->optVisMainListMode);
		
		if ($this->optVisMainListMode) $this->mTopViewListSimple->set_active(true);
		else $this->mTopViewListDetail->set_active(true);
		
		$this->initGameList();
		$this->onReloadRecord();
	}
	
	
	/*
	*
	*/
	public function __construct()
	{
		
		// ----------------------------------------------------------------
		// Get current operating system
		// ----------------------------------------------------------------
		$this->os_env = FACTORY::get('manager/Os')->getOperatingSystemInfos();
		
		$this->writeLocalReleaseInfo();
		$this->writeEnviromentInfo();
		
		// ----------------------------------------------------------------
		// Sort media category array!
		// ----------------------------------------------------------------
		asort($this->media_category);
		
		// ----------------------------------------------------------------
		// image-manager
		// ----------------------------------------------------------------
		$this->imageManager = FACTORY::get('manager/Image');
		
		// ----------------------------------------------------------------
		// INI get ecc main ini-file
		// ----------------------------------------------------------------
		$this->ini = FACTORY::get('manager/IniFile');
		if ($this->ini === false) die('miss ini');
		
		FACTORY::get('manager/IniFile')->setThemColors(FACTORY::get('manager/GuiTheme')->getColorIniPath());
		
		# initialize logger to get status reports		
		LOGGER::setActiveState($this->ini->getKey('USER_SWITCHES', 'log_details'));
		
		$this->set_ecc_image_size_from_ini();
		
		// ----------------------------------------------------------------
		// Init colors
		// ----------------------------------------------------------------

		// TREEVIEWS:
		// treeview background
		$this->treeviewBgColor = $this->ini->getKey('GUI_COLOR', 'treeview_color_bg');
		if (!$this->treeviewBgColor || !Valid::color($this->treeviewBgColor)) $this->treeviewBgColor = "#445566";
		
		// text color
		$this->treeviewFgColor = $this->ini->getKey('GUI_COLOR', 'treeview_color_text');
		if (!$this->treeviewFgColor || !Valid::color($this->treeviewFgColor)) $this->treeviewFgColor = "#FFFFFF";
		
		// color swap
		$this->treeviewBgColor1 = $this->ini->getKey('GUI_COLOR', 'treeview_color_row1');
		if (!$this->treeviewBgColor1 || !Valid::color($this->treeviewBgColor1)) $this->treeviewBgColor1 = "#445566";
		
		$this->treeviewBgColor2 = $this->ini->getKey('GUI_COLOR', 'treeview_color_row2');
		if (!$this->treeviewBgColor2 || !Valid::color($this->treeviewBgColor2)) $this->treeviewBgColor2 = "#556677";
		
		$this->treeviewBgColor2 = $this->ini->getKey('GUI_COLOR', 'treeview_color_row2');
		if (!$this->treeviewBgColor2 || !Valid::color($this->treeviewBgColor2)) $this->treeviewBgColor2 = "#556677";

		$this->treeviewBgColorImages = $this->ini->getKey('GUI_COLOR', 'treeview_color_bg_images');
		if (!$this->treeviewBgColorImages || !Valid::color($this->treeviewBgColorImages)) $this->treeviewBgColorImages = "#FFFFFF";
		
		
		$this->colEventOptionSelect1 = $this->ini->getKey('GUI_COLOR', 'option_select_bg_1');
		if (!$this->colEventOptionSelect1 || !Valid::color($this->colEventOptionSelect1)) $this->colEventOptionSelect1 = "#EDEDED";

		$this->colEventOptionSelect2 = $this->ini->getKey('GUI_COLOR', 'option_select_bg_2');
		if (!$this->colEventOptionSelect2 || !Valid::color($this->colEventOptionSelect2)) $this->colEventOptionSelect2 = "#F4F4F4";

		$this->colEventOptionActive = $this->ini->getKey('GUI_COLOR', 'option_select_bg_active');
		if (!$this->colEventOptionActive || !Valid::color($this->colEventOptionActive)) $this->colEventOptionActive = "#CFE0C9";
		
		$this->colEventOptionText = $this->ini->getKey('GUI_COLOR', 'option_select_text');
		if (!$this->colEventOptionText || !Valid::color($this->colEventOptionText)) $this->colEventOptionText = "#000000";		
		
		$this->treeviewSelectedBgColor = $this->ini->getKey('GUI_COLOR', 'treeview_color_bg_selection');
		if (!$this->treeviewSelectedBgColor || !Valid::color($this->treeviewSelectedBgColor)) $this->treeviewSelectedBgColor = "#aabbcc";		
		
		$this->treeviewSelectedFgColor = $this->ini->getKey('GUI_COLOR', 'treeview_color_fg_selection');
		if (!$this->treeviewSelectedFgColor || !Valid::color($this->treeviewSelectedFgColor)) $this->treeviewSelectedFgColor = "#000000";		
		
		// font family
		$this->treeviewFontType = $this->ini->getKey('GUI_COLOR', 'treeview_font_type');
		if (!$this->treeviewFontType) $this->treeviewFontType = "arial 10";
		
		$databaseFile = 'database/eccdb';
		if (!file_exists($databaseFile)) copy($databaseFile.'.empty', $databaseFile);
		
		// ----------------------------------------------------------------
		// DBMS connect to database and fill FACTORY with dbms
		// ----------------------------------------------------------------
		$dbms = FACTORY::get('manager/DbmsSqlite2');
		$dbms->setConnectionPath($databaseFile);
		$dbms->setConnectionMode('0666');
		$this->dbms = $dbms->connect();
		// INITIAL SET FACTORY DBMS
		// so all classes created by FACTORY::get()
		// which having a method setDbms() implemented gets
		// automaticly a dbms object assigned
		FACTORY::setDbms($dbms);

		$mngrEccUpdate = FACTORY::get('manager/EccUpdate');
		# max release 99 is allowed! :-(
		$release = $this->ecc_release['local_release_version'].(int)$this->ecc_release['release_build'];
		
		if (!$mngrEccUpdate->updateSystem($release)) {}
		
		// ----------------------------------------------------------------
		// I18N Initialize 
		// ----------------------------------------------------------------
		$language = $this->ini->getKey('USER_DATA', 'language');
		I18N::set($language);

		// ----------------------------------------------------------------
		// GUI/GLADE get gui from glade-file
		// ----------------------------------------------------------------
		parent::__construct(ECC_DIR_SYSTEM.'/gui2/gui.glade');
		
		# !!!!!!
		# the window is default invisible! $wdo_main->show() is called add end of constructor!
		# !!!!!!
		
		$this->wdo_main->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->background_color));
		
		$this->wdo_main->connect('key-press-event', array($this, 'handleShortcuts'));
		
//// TEST
//print "1";
//			$html = new GtkHTML();
//			$html->set_title("HTML Test!");
//
//			$url = 'http://www.camya.com/ecc/';
//			$markup = file_get_contents($url);
////			$markup = str_ireplace('img src="', 'img src="'.$url, $markup);
//			
//			print $markup;
//			
//			$html->image_preload('http://www.camya.com/ecc/images/20060728_ecc_pinfo_412x309.jpg');
//			$html->load_from_string($markup);
//			
//
//			
//			$html->set_editable(true);
//			$this->htmlScrolledWin->add($html);
//			$html->show();
//		
//print "2";
//// TEST

		// ----------------------------------------------------------------
		// get helper object
		// ----------------------------------------------------------------
		$this->oHelper = FACTORY::get('manager/GuiHelper', $this);
		$this->guiManager = FACTORY::get('manager/Gui');
		
		// get ecc header image
		$this->oHelper->set_eccheader_image();
		#$this->oHelper->setEccSupportImage();

		$this->oHelper->createUserfolderIfNeeded();
		
		FACTORY::get('manager/GuiTheme');
		$this->translateGui();
		$this->guiInit();
		
		// ----------------------------
		// is this an initialized history ini?
		// use defaults if init-ini!
		// ----------------------------		
		
		$initialHistroyIni = (count($this->ini->getHistoryKey()) <= 1);
		$this->objTooltips = new GtkTooltips();
		$this->objLinkCursor = new GdkCursor(Gdk::DRAFT_SMALL); // note 1
		
		// ----------------------------
		// get saved data from hist ini
		// ----------------------------
		$this->images_inactiv = $this->ini->getHistoryKey('images_inactiv');
		$this->nav_inactive_hidden = $this->ini->getHistoryKey('nav_inactive_hidden');
		
		$this->toggle_show_doublettes = $this->ini->getHistoryKey('toggle_show_doublettes');
		
		$this->toggle_only_disk = $this->ini->getHistoryKey('toggle_only_disk');
		
		
		// show images in aspect ratio?
		$aspectRatio = $this->ini->getKey('USER_SWITCHES', 'image_aspect_ratio');
		$this->imagesAspectRatio = ($aspectRatio == 1) ? true : false;
		
		$pp = $this->ini->getKey('USER_SWITCHES', 'show_media_pp');
		if ($pp) $this->_results_per_page = $pp;
		
		// 20060108 hack for simle mediaview
		if ($this->ini->getHistoryKey('optVisMainListMode')) {
			$this->optVisMainListMode = true;
			$pp = $this->ini->getKey('USER_SWITCHES', 'media_perpage_list');
			$this->_results_per_page = ($pp) ? $pp : 100;
		}
		
		# context menu for main navigation button ROMS
		$contextRoms = array(
			'mTopViewModeRomHave' => 'TOGGLE_MAINVIEV_ALL',
			'mTopViewModeRomDontHave' => 'TOGGLE_VIEWMODE_DONTHAVE',
			'mTopViewModeRomAll' => 'TOGGLE_MAINVIEV_DISPLAY',
			'mTopViewModeRomNoMeta' => 'TOGGLE_MAINVIEV_DISPLAY_METALESS',
			'mTopViewModeRomPersonal' => 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL',
			'mTopViewModeRomPersonalReviews' => 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL_REVIEW',
		);
		$this->btnMainShowAllRomsButton->connect_simple_after('pressed', array($this, 'contextViewMode'), $contextRoms);
		
		# context menu for main navigation button HISTORY
		$contextHistory = array(
			'mTopViewModeRomPlayed' => 'TOGGLE_MAINVIEV_DISPLAY_PLAYED',
			'mTopViewModeRomMostPlayed' => 'TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED',
			'mTopViewModeRomNotPlayed' => 'TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED',
		);
		$this->btnMainShowLaunchedRomsButton->connect_simple_after('pressed', array($this, 'contextViewMode'), $contextHistory);		
		
		$this->createEccOptBtnBar();

		# left
		$this->nbMediaInfoStateRunningEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_running').'?', $this->dropdownStateYesNo, 'simpleMetaUpdate', 'md_running');
		$this->nbMediaInfoStateRunningEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));

		$this->nbMediaInfoStateUsermodEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_usermod').'?', $this->dropdownStateYesNo, 'simpleMetaUpdate', 'md_usermod');
		$this->nbMediaInfoStateUsermodEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect2));
		
		$this->nbMediaInfoStateFreewareEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_freeware').'?', $this->dropdownStateYesNo, 'simpleMetaUpdate', 'md_freeware');
		$this->nbMediaInfoStateFreewareEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));
				
		$this->nbMediaInfoStateBuggyEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_buggy').'?', $this->dropdownStateYesNo, 'simpleMetaUpdate', 'md_bugs');
		$this->nbMediaInfoStateBuggyEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect2));
		
		# right

		$this->nbMediaInfoStateMultiplayerEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_multiplay').'?', $this->dropdownStateCount, 'simpleMetaUpdate', 'md_multiplayer');
		$this->nbMediaInfoStateMultiplayerEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));
		
		$this->nbMediaInfoStateTrainerEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_trainer').'?', $this->dropdownStateCount, 'simpleMetaUpdate', 'md_trainer');
		$this->nbMediaInfoStateTrainerEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect2));

		$this->nbMediaInfoStateNetplayEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_netplay').'?', $this->dropdownStateYesNo, 'simpleMetaUpdate', 'md_netplay');
		$this->nbMediaInfoStateNetplayEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));
		
		$this->nbMediaInfoStateIntroEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_intro').'?', $this->dropdownStateYesNo, 'simpleMetaUpdate', 'md_intro');
		$this->nbMediaInfoStateIntroEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect2));

		# storage
		$this->dropdownStorage = I18n::translateArray('dropdown_meta_storage', $this->dropdownStorage);
		$this->nbMediaInfoStateStorageEvent->connect_simple_after('button-press-event', array($this, 'simpleContextMenu'), I18N::get('meta', 'lbl_storage').'?', $this->dropdownStorage, 'simpleMetaUpdate', 'md_storage', true);
		$this->nbMediaInfoStateStorageEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));
		
		// region
		$this->dropdownRegion = I18n::translateArray('dropdown_meta_region', $this->dropdownRegion);
		
		# icons for rating, reviews, bookmarks and notes
		$this->nbMediaInfoStateRatingEvent->connect_simple('button-press-event', array($this, 'edit_media'), false, 1);
		$this->objTooltips->set_tip($this->nbMediaInfoStateRatingEvent, I18N::get('tooltips', 'nbMediaInfoStateRatingEvent'));
		
		$this->nbMediaInfoMetaEvent->connect_simple('button-press-event', array($this, 'edit_media'), false, 0);
		$this->objTooltips->set_tip($this->nbMediaInfoMetaEvent, I18N::get('tooltips', 'nbMediaInfoMetaEvent'));
		
		$this->nbMediaInfoNoteEvent->connect_simple('button-press-event', array($this, 'edit_media'), false, 2);
		$this->objTooltips->set_tip($this->nbMediaInfoNoteEvent, I18N::get('tooltips', 'nbMediaInfoNoteEvent'));
		
		$this->nbMediaInfoReviewEvent->connect_simple('button-press-event', array($this, 'edit_media'), false, 1);
		$this->objTooltips->set_tip($this->nbMediaInfoReviewEvent, I18N::get('tooltips', 'nbMediaInfoReviewEvent'));
		
		$this->nbMediaInfoBookmarkEvent->connect('button-press-event', array($this, 'add_bookmark_by_id'));
		$this->objTooltips->set_tip($this->nbMediaInfoBookmarkEvent, I18N::get('tooltips', 'nbMediaInfoBookmarkEvent'));

		$this->nbMediaInfoAuditStateEvent->connect_simple('button-press-event', array($this, 'openRomAuditPopup'));
		$this->objTooltips->set_tip($this->nbMediaInfoAuditStateEvent, I18N::get('tooltips', 'nbMediaInfoAuditStateEvent'));
		
		$this->nbMediaInfoEditEvent->connect_simple('button-press-event', array($this, 'edit_media'), false, 0);
		
		#$this->nbMediaInfoStateRatingEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect2));

		// ----------------------------------------------------------------
		// Fill dropdown for category search!
		// ----------------------------------------------------------------
		$combo = FACTORY::get('manager/IndexedCombo')->set($this->cb_search_category, $this->media_category, 4);
		$combo->connect('changed', array($this, 'setSearchCategoryMain'));
		
		$combo = FACTORY::get('manager/IndexedCombo')->set($this->cb_search_mameDriver, array('' => 'ALL DRIVER'), 1);
		$combo->connect('changed', array($this, 'setSearchMameDriver'));
		$combo->set_visible(false);
		
		$this->searchSelectorFfType->connect('button-press-event', array($this, 'dispatchSearchFfType'));
		$this->searchSelectorFfType->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));

		$first = key($this->freeformSearchFields);
		$this->freeformSearchFields = I18n::translateArray('dropdown_search_fields', $this->freeformSearchFields);
		#$this->searchSelectorFfTypeLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>'.$first[0].$first[1].'</b></span>');
		$this->searchSelectorFfTypeLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>'.$first.'</b></span>');
		$this->objTooltips->set_tip($this->searchSelectorFfType, I18N::get('tooltips', 'search_field_select'));

		$this->searchSelectorRating->connect('button-press-event', array($this, 'dispatchSearchSelectory'));
		$this->searchSelectorRating->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));
		$this->searchSelectorRatingLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>0*</b></span>');
		$this->objTooltips->set_tip($this->searchSelectorRating, I18N::get('tooltips', 'search_rating'));
		
		$this->searchSelectorOperator->connect('button-press-event', array($this, 'dispatchSearchFfOperator'));
		$this->searchSelectorOperator->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($this->colEventOptionSelect1));
		$this->searchSelectorOperatorLbl->set_markup('<span color="'.$this->colEventOptionText.'"><b>+</b></span>');
		$this->objTooltips->set_tip($this->searchSelectorOperator, I18N::get('tooltips', 'search_operator'));
		
		// set title of the main window!
		$this->wdo_main->set_title('.oO('.$this->ecc_release['title'].')Oo.');
		
		// ----------------------------
		// SET USER_SWITCHES FROM INI
		// ----------------------------
		// fast list refresh activated? 
		$this->fastListRefresh = $this->ini->getKey('USER_SWITCHES', 'image_fast_refresh');
		// get size from the inifile!
		
		// ----------------------------
		// GuiImagePopup init
		// ----------------------------
		$this->oGuiImagePopup = FACTORY::get('manager/GuiImagePopup', $this);
		$this->image_preview_ebox->connect_simple('button-press-event', array($this, 'openImagePopup'), false, true);

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

		# init popup menus
		$this->initPopupMetaEdit();
		$this->initPopupImageCenter();
		
		// ----------------------------
		// EVENTBOXES CONNECT
		// ----------------------------
		$this->img_ecc_header_ebox->connect_simple('button-press-event', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $this->eccHelpLocations['ECC_WEBSITE'], 'open');
		$this->img_plattform_ebox->connect_simple('button-press-event', array($this, 'setNotebookPage'), $this->nb_main, 1);
		#$this->eccImageSupportEvent->connect_simple('button-press-event', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $this->eccHelpLocations['ECC_SUPPORT'], 'open');
		
		// ----------------------------
		// init preselected imagetype
		// ----------------------------
				// ----------------------------
		// MEDIA-INFOS Image init
		// ----------------------------

		$this->infoImageEditBtn->connect_simple('clicked', array($this, 'openImagePopup'), false);
		
		$this->infoImageBtnMatchImageType->connect_simple('clicked', array($this, 'setMatchImageType'));
		
		$this->media_img_btn_next->connect_simple('clicked', array($this, 'set_image_show_pos'), 'next');
		$this->media_img_btn_prev->connect_simple('clicked', array($this, 'set_image_show_pos'), 'prev');
		$this->media_img_btn_next->set_sensitive(false);
		$this->media_img_btn_prev->set_sensitive(false);

		// change image order
		$userSelectedImageType = $this->ini->getHistoryKey('imageTypeSelected');
		$imageIndex = 0;
		if ($userSelectedImageType) {
			foreach ($this->image_type as $name => $void) {
				if ($userSelectedImageType == $name) break;
				$imageIndex++;
			}
		}
		$this->image_type_selected = ($userSelectedImageType) ? $userSelectedImageType : key($this->image_type);
		if (!$this->obj_image_type) $this->obj_image_type = new IndexedCombobox($this->cb_image_type, false, $this->image_type, 2, $imageIndex);
		$this->cb_image_type->connect_after("changed", array($this, 'image_type_order'));
		// set current selected imageindex
		$this->image_type_order(false, $this->image_type_selected);
		$this->cb_image_type->set_sensitive(false);
		$this->infoImageBtnMatchImageType->set_sensitive(false);
		$this->infoImageEditBtn->set_sensitive(false);
		
		// ----------------------------
		// init main bombo for languages
		// ----------------------------
		$this->media_language = I18n::translateArray('dropdown_lang', $this->media_language);
		$this->create_combo_lanugages($this->cb_search_language);
		
		// ----------------------------
		// INIT search options
		// ----------------------------
		// language  dropdown
		$this->init_treeview_languages($this->test_language);
		
		// ----------------------------
		// extended search
		// ----------------------------
		foreach($this->ext_search_combos as $key => $name) {
			$obj_name = "o".$name;
			$state =  $this->ini->getHistoryKey($name);
			$this->ext_search_selected[$name] = $state;
			if (!$this->$obj_name) $this->$obj_name = new IndexedCombobox($this->$name, false, $this->cbox_yesno, 1, $state);
			$this->$name->connect("changed", array($this, 'dispatcher_ext_search'));
		}
		$state = $this->get_ext_search_state();
		$this->ext_search_reset->set_sensitive($state);
		$this->ext_search_reset->connect_simple("clicked", array($this, 'reset_ext_search_state'));
		//$this->ext_search_expander->set_expanded(false);
		
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
		$selected_platform = $this->ini->getHistoryKey('navigation_last_index');
		if (isset($selected_platform)) {
			$treeview_nav_selection->select_path((int)$selected_platform);
		}
		$treeview_nav_selection->set_mode(Gtk::SELECTION_BROWSE);
		$treeview_nav_selection->connect('changed', array($this, 'get_treeview_nav_selection'));
		
		// navigation_last for database
		$this->_eccident = $this->ini->getHistoryKey('navigation_last');
		$ident = ($this->_eccident) ? $this->_eccident : 'null';
		# rem
		$platform_name = $this->ini->getPlatformName($ident);
		$this->setEccident($this->_eccident, false);
		// set also platform name
		$this->setPlatformName($platform_name);
		$txt = '<b>'.htmlspecialchars($this->ecc_platform_name).'</b>';
		$this->nb_main_lbl_media->set_markup($txt);
		
		// ----------------------------
		// platform context menu init
		// ----------------------------		
		
		$this->treeview1->connect('button-press-event', array($this, 'show_popup_menu_platform_doubleclick'));
		$this->treeview1->connect('button-release-event', array($this, 'show_popup_menu_platform'));
		

		// ----------------------------
		// Init main view with roms!
		// ----------------------------
//		$this->init_treeview_main();
		
		// ----------------------------
		// TreeviewPager Init
		// ----------------------------		
		$this->media_treeview_pager = FACTORY::get('manager/TreeviewPager');
		
		// ----------------------------
		// INIT Category dropdown!
		// ----------------------------	
		$this->cbPlatformCategories->connect("changed", array($this, 'changePlatformCategory'));
		$availableCategories = $this->ini->getPlatformCategories();
		$this->dd_pf_categories = new IndexedCombobox($this->cbPlatformCategories, false, $availableCategories);
		
		// ----------------------------
		// INIT NOTEBOCKS visibility
		// ----------------------------	
		$this->set_notebook_page_visiblility($this->nb_main, 0, true); // media
		$this->set_notebook_page_visiblility($this->nb_main, 1, $this->view_mode); // factsheet
		$this->set_notebook_page_visiblility($this->nb_main, 2, true); // help
		
		// ----------------------------
		// Update notebook pages
		// ----------------------------	
		# 20070127
		#$this->update_platform_edit($ident);
		$this->update_platform_info($ident);

		// ----------------------------
		// Special navigation beyond
		// normal platform navigation
		// ----------------------------			

		
		$this->btnMainShowAllRomsButton->connect_simple('clicked', array($this, 'selectViewModeAllAvailable'));
		// bookmarks
		$this->btnMainShowBookmarkedRomsButton->connect_simple('clicked', array($this, 'selectViewModeBookmarks'));
		// last launched
		$this->btnMainShowLaunchedRomsButton->connect_simple('clicked', array($this, 'selectViewModePlayedHistory'));
		
		// ----------------------------
		// MEDIA-EDIT POPUP - signals
		// ----------------------------	
		#$this->media_edit_btn_save->connect_simple('clicked', array($this, 'edit_media_save'));
		$this->media_edit_btn_save_bottom->connect_simple('clicked', array($this, 'edit_media_save'));
		$this->media_edit_btn_save_bottom->set_label(i18n::get('global', 'save'));
		
		$this->media_edit_btn_saveandclose_bottom->connect_simple('clicked', array($this, 'edit_media_save'), false, true);
		$this->media_edit_btn_saveandclose_bottom->set_label(i18n::get('global', 'saveAndClose'));
		
		$this->media_edit_btn_cancel->connect_simple('clicked', array($this, 'media_edit_hide'));
		$this->media_edit_btn_cancel->set_label(i18n::get('global', 'close'));
		
		$this->media_nb_info_edit->connect_simple('clicked', array($this, 'edit_media'), false, 0);
		$this->media_edit_btn_start->connect("clicked", array($this, 'startRom'));
		$this->infotab_button_area->set_sensitive(false);
		
		
		// Webservices eccdb
		$this->paneInfoEccDbAddButton->connect_simple('clicked', array($this, 'dispatch_menu_context'), 'WEBSERVICE', 'SET');
		#$this->paneInfoEccDbGetButton->connect_simple('clicked', array($this, 'openRomdbGetUrl'));
		$this->paneInfoEccDbGetDatfileButton->connect_simple('clicked', array($this, 'dispatch_menu_context'), 'WEBSERVICE', 'GET_ROMDB_DATFILE');
		
		$this->media_nb_info_eccdb_info->connect_simple('button-press-event', array($this, 'setNotebookPage'), $this->media_nb, 3);
		
		#$this->media_nb_info_eccdb_info->connect_simple('clicked', array($this, 'dispatch_menu_context'), 'WEBSERVICE', 'SET');

		// ----------------------------
		// ROM-SEARCH
		// ----------------------------
		$this->search_input_reset->connect('clicked', array($this, 'onResetSearch'));
		$this->search_input_reset->set_sensitive(false);

		// Input search
		$this->search_input_txt->connect_after('changed', array($this, 'quickSearchFilter'));
		$style = new PangoFontDescription();
		$style->set_weight(Pango::WEIGHT_HEAVY);
		$this->search_input_txt->modify_font($style);
		
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
		$obj_pixbuff = $this->oHelper->getPixbuf(dirname(__FILE__)."/".'images/eccsys/platform/ecc_ecc_teaser.png', 240, 160);
		$this->media_img->set_from_pixbuf($obj_pixbuff);
		
		/**
		 * Initialize the main treeview for games
		 */
		$this->initGameList(false);

		// ----------------------------
		// MEDIA-INFOS
		// ----------------------------
		// button start media
		$this->btn_start_media->connect("clicked", array($this, 'startRom'));
		$this->btn_start_media->set_sensitive(false);
		// button add to bookmarks
		$this->btn_add_bookmark->connect("clicked", array($this, 'add_bookmark_by_id'));
		$this->btn_add_bookmark->set_sensitive(false);
		
		// ----------------------------		
		// INLINE HELP PARSER BUTTON
		// ----------------------------
//		$this->btn_parser_path_inline_help->connect_simple('clicked', array($this, 'parseMedia'));
		
		// ----------------------------
		// standard windows close connect
		// ----------------------------
		#$this->wdo_main->connect_simple('destroy', array($this, 'eccShutdown'));
		$this->wdo_main->connect_simple('delete-event', array($this, 'eccShutdown'));
		$this->wdo_main->connect('window-state-event', array($this, 'onStateChange'));
		
		// ----------------------------
		// PRINT OUT DEBUG INFORMATIONS
		// ABOUT ALL CLASSES BUILD BY
		// FACTORY IN THIS CONSTRUCTOR!
		// ----------------------------	
		#FACTORY::status();
		
		// ----------------------------
		// INITIAL GET ALL DATA FOR
		// SELECTED PLATFORM!!!!!!!
		// HERE ECC GET ALL ROMS!!!!!
		// ----------------------------	

		$confEccSaveViewSettings = $this->ini->getKey('USER_SWITCHES', 'confEccSaveViewSettings');
		if ($confEccSaveViewSettings && false !== $showListDataMode = $this->ini->getHistoryKey('showListDataMode')){
			$this->showListDataMode = $showListDataMode;
			$this->view_mode = $this->ini->getHistoryKey('showListDataType');
			$this->dispatch_menu_context_platform($showListDataMode);
		}
		else{
			$this->view_mode = 'MEDIA';
			$this->showListDataMode = 'TOGGLE_MAINVIEV_ALL';
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_ALL');
			#$this->onInitialRecord(true);
		}
		
		# now saved by default!
		$leftPanelState = $this->ini->getHistoryKey('vis_hide_panel_nav');
		$this->visibleNavigation = $leftPanelState;
		$this->toogleNavPanel();
		
		$rightPanelState = $this->ini->getHistoryKey('vis_hide_panel_info');
		$this->visibleMedia = $rightPanelState;
		$this->toogleInfoPanel();
		
		$searchPanelState = $this->ini->getHistoryKey('vis_hide_panel_search');
		$this->visibleSearch = $searchPanelState;
		$this->toogleSearchPanel();
		
		$navigationWidth = $this->ini->getHistoryKey('vis_navigation_width');
		if($navigationWidth) $this->hpaned1->set_position($navigationWidth);
		
		# get the ids of the last selected game!
		$lastSelectedGame = $this->ini->getHistoryKey('last_selected_game');
		if($lastSelectedGame) $this->show_media_info(false, $lastSelectedGame);

		# get last state of the window before it closes!
		$guiState = $this->ini->getHistoryKey('gui_main_state');
		if($guiState == 4){
			$this->wdo_main->maximize();
		}
//		elseif($guiState == 16){
//			$this->wdo_main->fullscreen();
//		}
		else{
			# get the ids of the last selected game!
			$guiSize = $this->ini->getHistoryKey('gui_main_size');
			if($guiSize){
				list ($width, $height) = explode('x', $guiSize);
				if($width && $height) $this->wdo_main->resize((int)$width, (int)$height);
				#$this->navPanelMainSize = array((int)$width, (int)$height);		
			}
			
			# move the gui to the last stored coordinates
			$guiPosition = $this->ini->getHistoryKey('gui_main_position');
			if ($guiPosition) {
				list($width, $height) = explode('x', $guiPosition);
				if ($width && $height) $this->wdo_main->move($width, $height);
			}
		}
		
		
//		$this->wdo_main->connect('expose_event', array($this, 'onRedrawRequest'));
		
		$this->wdo_main->show();
		
		
		Gtk::Main();
	}
	

//	public function onRedrawRequest($widget, $event){
//		list($widgetWidth, $widgetHeight) = $this->wdo_main->get_size();
//		
//		print "$widgetWidth<pre>".LF;
//		print_r($widgetHeight).LF;
//		print "</pre>".LF;
//		
//	}
	
	public function onExposeEvent($widget, $event, $pixbuf){
		if($pixbuf){
			
    		if(get_class($widget) == 'GtkEventBox'){
    			$widgetWidth = $widget->allocation->width;
				$widgetHeight = $widget->allocation->heigth; 
    		}
    		elseif(get_class($widget) == 'GtkWindow'){
    			list($widgetWidth, $widgetHeight) = $widget->get_size(); 
    		}

			$pixbufWidth = $pixbuf->get_width();
    		$pixbufHeight = $pixbuf->get_height();
    		
    		$widthRepeat = ceil($widgetWidth/$pixbufWidth);
    		$heightRepeat = ceil($widgetHeight/$pixbufHeight);
    		
    		$gObjectNormaState = Gtk::STATE_NORMAL;
    		
    		for($i=0; $i<$widthRepeat; $i++){
    			for($j=0; $j<$heightRepeat; $j++){
    				$widget->window->draw_pixbuf($widget->style->bg_gc[$gObjectNormaState], $pixbuf, 0, 0, $pixbufWidth*$i, $pixbufHeight*$j, $pixbufWidth, $pixbufHeight);	
    			}
    		}
    		
	    	if($widget->get_child() != null) $widget->propagate_expose($widget->get_child(), $event);
	    	return true;
    		
		}
		return false;
	}
	
	public function mainListDragAndDropInit($treeView){
		$treeView->drag_source_set(Gdk::BUTTON1_MASK, array( array( 'text/plain', 0, 0)), Gdk::ACTION_COPY|Gdk::ACTION_MOVE);
		$treeView->drag_dest_set(Gtk::DEST_DEFAULT_ALL, array( array( 'text/plain', 0, 0)), Gdk::ACTION_COPY|Gdk::ACTION_MOVE);
		$treeView->connect('drag-data-get', array($this, 'mailListDragAndDropOnDrag')); // note 2
		$treeView->connect('drag-data-received', array($this, 'mainListDragAndDropOnDrop')); // note 2
	}
	
	public function mailListDragAndDropOnDrag($widget, $context, $data, $info, $time){
		$data->set_text('empty');
	}
	
	public function mainListDragAndDropOnDrop($widget, $context, $x, $y, $data, $info, $time){
		
		$selection = $widget->get_selection();

		# get the source
		list($model, $iter) = $selection->get_selected();
		if ($iter === null) return false;
		#$sourceFileId = $model->get_value($iter, 3);
		$sourceCompoundId = $model->get_value($iter, 5);
		
		# get the destination
		$pathData = $widget->get_path_at_pos($x, $y);
		
		# hotfix "ECC 0.9.6 WIP21 list comparing bug"
		# the coordinates are wrong in the listview, so
		# the next entry is selected! path -1 selects the
		#  right one in list mode
		if($this->optVisMainListMode){
//			$path = reset($pathData[0])-1;
//			if($path < 1) $path = 0;
			$path = $pathData[0][0]-1;
			if($path < 1) $path = 0;
		}
		else{
			$path = reset($pathData[0]);
		}
		
		#$path = reset($pathData[0]);
		
		$selection->select_path($path);
		list($model, $iter) = $selection->get_selected();
		if ($iter === null) return false;
		#$destFileId = $model->get_value($iter, 3);
		$destCompoundId = $model->get_value($iter, 5);
		
		if ($sourceCompoundId != $destCompoundId){
			$dataCombiner = FACTORY::get('manager/GuiDataCombiner', $this);
			$dataCombiner->getCompareData($sourceCompoundId, $destCompoundId);
			$dataCombiner->show();
		}
	}
	
	public function setupCompare(){
		if ($this->compareLeftId) $this->compareRightId = $this->current_media_info['composite_id'];
		else {
			$this->compareLeftId = $this->current_media_info['composite_id'];
			$this->compareLeftName = ($this->current_media_info['md_name']) ? $this->current_media_info['md_name'] : $this->current_media_info['title'];
		} 
		if ($this->compareLeftId && $this->compareRightId) {
			
			if ($this->compareLeftId == $this->compareRightId){
				$this->compareRightId = false;
				return false;
			}
			
			$test = FACTORY::get('manager/GuiDataCombiner', $this);
			$test->getCompareData($this->compareLeftId, $this->compareRightId);
			$test->show();
			$this->compareLeftId = false;
			$this->compareRightId = false;
		}
	}
	
	public function connectSignalsForTopMenu() {
		
		// ----------------------------
		// ROMS
		// ----------------------------
		$this->menuTopRomAddNewRom->connect_simple('activate', array($this, 'parseMedia'));
		$this->mTopEmuConfig->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT');
		$this->mTopRomOptimize->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_OPTIMIZE');
		$this->mMenuReparseFolder->connect_simple('activate', array($this, 'dispatch_menu_context'), 'ROM_RESCAN_FOLDER');
		$this->mMenuReparseFolderAll->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'ROM_RESCAN_ALL');
		
		#$this->mMenuReparseFolder->set_sensitive(false);
		// remove duplicate roms
		$this->mTopRomRemoveDups->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DUPLICATE_REMOVE_ALL');
		$this->mTopRomRemoveRoms->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_MEDIA');
		
		// ----------------------------
		// DATFILE
		// ----------------------------
		
		$this->mTopDatImportOnlineRomdb->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_ECC_ROMDB');
		#$this->mTopDatImportOnlineRomdb->set_sensitive(false);
		
		$this->mTopDatExportOnlineRomdb->connect_simple('activate', array($this, 'dispatch_menu_context'), 'WEBSERVICE', 'SET');
		
		$this->mTopDatImportRc->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_RC');
		$this->mTopDatImportCtrlMAME->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_CONTROLMAME');
		$this->mTopDatImportEcc->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_ECC');
		
		$this->mTopDatExportEccFull->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT');
		$this->mTopDatExportEccUser->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_USER');
		$this->mTopDatExportEccEsearch->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_ESEARCH');
		$this->mTopDatClear->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_DAT');
		$this->mTopDatConfig->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT', 'DAT');
		
		// ----------------------------
		// FILES
		// ----------------------------
		
		$this->mTopRomAuditShow->connect_simple('activate', array($this, 'openRomAuditPopup'));
		
		// ----------------------------
		// FILES
		// ----------------------------
		
		$this->mTopFileRename->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_RENAME');
		$this->mTopFileRename->set_sensitive(false);

		$this->mTopFileCopy->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_COPY');
		$this->mTopFileCopy->set_sensitive(false);
		
		$this->mTopFileRemove->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_REMOVE');
		$this->mTopFileRemove->set_sensitive(false);

# 20070628 deactivated	
# $this->menubar_filesys_organize_roms_preview->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_FS_ORGANIZE_PREVIEW');
# $this->menubar_filesys_organize_roms->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_FS_ORGANIZE');
		
		// ----------------------------
		// MAINTENANCE
		// ----------------------------	
		// mTopOptionCreateUserFolder
		$this->mTopOptionCreateUserFolder->connect_simple('activate', array(FACTORY::get('manager/GuiHelper'), 'rebuildEccUserFolder'));
		// vacuum database
		$this->mTopOptionDbVacuum->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_VACUUM');
		// clear ecc history
		$this->mTopOptionCleanHistory->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_CLEAN_HISTORY');
		// backup userdata to xml file!
		$this->mTopOptionBackupUserdata->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_BACKUP_USERDATA');
		
		// ----------------------------
		// OPTIONS
		// ----------------------------		
		
		// mainview display-mode
		$this->mTopViewModeRomHave->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_ALL');
		$this->mTopViewModeRomDontHave->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_VIEWMODE_DONTHAVE');
		$this->mTopViewModeRomAll->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY');
		$this->mTopViewModeRomNoMeta->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_METALESS');
		$this->mTopViewModeRomPersonal->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL');
		$this->mTopViewModeRomPlayed->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_PLAYED');
		$this->mTopViewModeRomMostPlayed->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED');
		$this->mTopViewModeRomNotPlayed->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED');
		$this->mTopViewModeRomBookmarks->connect_simple("button-press-event", array($this, 'dispatch_menu_context_platform'), 'TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS');		
		
		# List Detail / Simple
		# First init selected state
		if ($this->optVisMainListMode) $this->mTopViewListSimple->set_active(true);
		else $this->mTopViewListDetail->set_active(true);
		# connect the signals
		$this->mTopViewListDetail->connect_simple("button-press-event", array($this, 'updateEccOptBtnBar'), 'optVisMainListMode', 'toggleMailListMode');
		$this->mTopViewListSimple->connect_simple("button-press-event", array($this, 'updateEccOptBtnBar'), 'optVisMainListMode', 'toggleMailListMode');
		
		// configuration
		$this->mTopOptionConfig->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT', 'ECC');
		
		// ----------------------------
		// Startup
		// ----------------------------		
		$this->mTopUpdateEccLive->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeFileWithProgramm'), realpath(ECC_DIR.'/ecc-tools/'.$this->eccHelpLocations['ECC_EXE_LIVE']));
		
		$this->mTopToolEccTheme->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeFileWithProgramm'), realpath(ECC_DIR.'/ecc-tools/'.$this->eccHelpLocations['ECC_EXE_THEME']));
		$this->mTopToolEccBugreport->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeFileWithProgramm'), realpath(ECC_DIR.'/ecc-tools/'.$this->eccHelpLocations['ECC_EXE_BUGREPORT']));
		$this->mTopImageConvert->connect_simple('activate', array($this, 'convertEccV1Images'));
		
		// View
		
		$this->mTopViewRandomGame->connect_simple('activate', array($this, 'presentRandomGame'));
		$this->mTopViewReload->connect_simple('activate', array($this, 'onReloadRecord'));
		
		$this->mTopViewToggleLeft->connect_simple('activate', array($this, 'toogleNavPanel'));
		$this->mTopViewToggleRight->connect_simple('activate', array($this, 'toogleInfoPanel'));
		$this->mTopViewToggleSearch->connect_simple('activate', array($this, 'toogleSearchPanel'));
		
		$this->mTopViewOnlyRoms->connect_simple("button-press-event", array($this, 'selectViewModeAllAvailable'));
		$this->mTopViewOnlyBookmarks->connect_simple("button-press-event", array($this, 'selectViewModeBookmarks'));
		$this->mTopViewOnlyPlayed->connect_simple("button-press-event", array($this, 'selectViewModePlayedHistory'));
		
		$this->mTopHelpDocOffline->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), 'file:///'.realpath(ECC_DIR.'/'.$this->eccHelpLocations['ECC_DOC_OFFLINE']));
		$this->mTopHelpDocOnline->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $this->eccHelpLocations['ECC_DOC_ONLINE'], 'open');
		$this->mTopHelpWebsite->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $this->eccHelpLocations['ECC_WEBSITE'], 'open');
		$this->mTopHelpForum->connect_simple('activate', array(FACTORY::get('manager/Os'), 'executeProgramDirect'), $this->eccHelpLocations['ECC_FORUM'], 'open');
		$this->mTopHelpAbout->connect_simple('activate', array(FACTORY::get('manager/GuiHelper'), 'open_splash_screen'));
	}
	
	public function convertEccV1Images() {
		
		$data = $this->imageManager->convertAllOldEccImages(false);
		if (!in_array(1, $data)) return $this->guiManager->openDialogInfo('DONE', 'Found no old ecc images!');
		$out = array();
		foreach($data as $eccident => $state){
			if ($state) $out[] = $eccident;
		}

		if (FACTORY::get('manager/Gui')->openDialogConfirm('Confirm', "Found some old emuControlCenter images for...\n\n\"".join('", "', $out)."\"\n\nshould i convert them?")){
		
			if ($this->status_obj->init()) {

				$this->status_obj->set_label(i18n::get('popup', 'stateLabelConvertOldImages'));
				$this->status_obj->set_popup_cancel_msg();
				$this->status_obj->show_main();
				$this->status_obj->show_output();
	
				$res = $this->imageManager->convertAllOldEccImages(true, $this->status_obj);
				
				$out = "";
				foreach($res as $eccident => $count){
					if ($count) {
						# rem
						$platformName = $this->ini->getPlatformName($eccident);
						if (is_array($platformName)) $platformName = '';
						$out .= "$count images for $platformName ($eccident)".LF;
					}
				}
				
				$log = "Converted images statistic:\n\n".$out."";
				$this->status_obj->update_message($log);
				$this->status_obj->update_progressbar(1, 'DONE');
				$title = 'DONE';
				$msg = "All found images converted";
				$this->status_obj->open_popup_complete($title, $msg);
			}
		}
	}
	
	/*
	*
	*/
	public function DatFileExport($user_only=false, $userfoder_path=true, $verbose=true, $use_esearch=false)
	{
		if ($this->status_obj->init()) {
			
			$eccident = $this->_eccident;
			
			if (!isset($platfom)) $platfom = strtoupper($this->ecc_platform_name);
			
			$history_key = ($user_only) ? 'eccMediaDat_export_user' : 'eccMediaDat_export_complete';
			
			$user_only_strg = ($user_only) ? 'USER' : 'COMPLETE';
			
			if ($userfoder_path==true) {
				// get path from history
				$path_history = $this->ini->getHistoryKey($history_key);
				$title = sprintf(I18N::get('popup', 'dat_export_filechooser_title%s'), $user_only_strg);
				
				$shorcutFolder = $this->ini->getShortcutPaths($eccident);
				$path = FACTORY::get('manager/Os')->openChooseFolderDialog($path_history, $title, false, $shorcutFolder);
				if ($path === false) {
					$this->status_obj->reset1();
					return false;
				}
				$title = sprintf(I18N::get('popup', 'dat_export_title%s'), $user_only_strg);
				$msg = sprintf(I18N::get('popup', 'dat_export_msg%s%s%s'), $user_only_strg, $platfom, $path);
				if ($use_esearch) $msg .= I18N::get('popup', 'dat_export_esearch_msg_add');
				if (!$this->guiManager->openDialogConfirm($title, $msg)) {
					$this->status_obj->reset1();
					return false;
				}
			}
			else {
				$path = false;
			}

			$this->status_obj->set_label(sprintf(i18n::get('popup', 'stateLabelDatExport%s%s'), $user_only_strg, $platfom));
			$this->status_obj->set_popup_cancel_msg();
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			if ($userfoder_path==true) $this->ini->storeHistoryKey($history_key, $path, true);
			
			require_once('manager/cDatFileExport.php');
			$export = new DatFileExport($this->ini, $this->status_obj, $this->ecc_release);
			$export->setDbms($this->dbms);

			$export->set_eccident($eccident);
			$export->export_user_only($user_only);
			
			$ext_search_snipplet = SqlHelper::createSqlExtSearch($this->ext_search_selected);
			if ($use_esearch) $export->set_sqlsnipplet_esearch($ext_search_snipplet);
			
			$result = $export->export_data($path);
			$title = I18N::get('popup', 'dat_export_done_title');
			if (!$path) $path = 'ecc-user/'.$this->ini->getPlatformFolderName($eccident).'/exports/';
			$msg = sprintf(I18N::get('popup', 'dat_export_done_msg%s%s%s'), $user_only_strg, strtoupper($this->ecc_platform_name), $path);
			$this->status_obj->open_popup_complete($title, $msg);
		}
		return true;
	}
	
	
//	private function fileOrganizer($process=false) {
//		
//		$process_type = $this->ini->getKey('USER_SWITCHES','fs_rom_reorganization_type');
//		
//		require_once('manager/cFileOrganizer.php');
//		$oFileOrga = new FileOrganizer($this->_eccident, $this->ini, $this->status_obj);
//		$oFileOrga->setDbms($this->dbms);
//		
//		if (!$oFileOrga->categories_exists()) {
//			$title = I18N::get('popup', 'rom_reorg_nocat_title');
//			$msg = sprintf(I18N::get('popup', 'rom_reorg_nocat_msg%s'), strtoupper($this->ecc_platform_name));
//			$this->guiManager->openDialogInfo($title, $msg);
//			return false;
//		}
//		
//		if ($process) {
//			$title = I18N::get('popup', 'rom_reorg_title');
//			$msg = sprintf(I18N::get('popup', 'rom_reorg_msg%s%s%s'), $process_type, strtoupper($this->ecc_platform_name), $this->_eccident);
//			if (!$this->guiManager->openDialogConfirm($title, $msg)) return false; 
//		}
//		
//		if ($this->status_obj->init()) {
//			
//			if ($process) {
//				$this->status_obj->set_label("Organize ROMS");
//			}
//			else {
//				$this->status_obj->set_label("PREVIEW Organize ROMS");
//			}
//			$this->status_obj->set_popup_cancel_msg();
//			$this->status_obj->show_main();
//			$this->status_obj->show_output();
//			
//			$oFileOrga->set_skip_unknown_category(true);
//			$oFileOrga->set_categories($this->media_category);
//			
//			$path = $oFileOrga->get_destination_path();
//			if ($statistics = $oFileOrga->get_preview_statistics()) {
//				
//				$msg = "";
//				if (!$process) {
//					$msg = "THIS IS ONLY A PREVIEW!!!! NOTHING WILL BE PROCESSED AT ALL!!!\n";
//				}
//				else {
//					$msg = "FILES COPIED TO NEW LOCATION!!!\n";
//				}
//				
//				$msg .= "Selected process mode: \"$process_type\"\n";
//				$msg .= "Destination folder: \"$path\"\n\n";
//				
//				if (isset($statistics['ISSET']) && count($statistics['ISSET'])) {
//					$msg .= "########################################\n";
//					$msg .= "# CONFLICT!!!!\n";
//					$msg .= "# Rom with same name allready in folder!\n";
//					$msg .= "########################################\n";
//					foreach ($statistics['ISSET'] as $category => $value) {
//						$msg .= "$category\n";
//						foreach ($value as $id => $filename) {
//							$msg .= "\t".$filename."\n";
//						}
//					}
//					$msg .= "\n";
//				}
//				
//				if (isset($statistics['MISSING']) && count($statistics['MISSING'])) {
//					$msg .= "----------------------------------------\n";
//					$msg .= "- SOURCE FILE MISSING\n";
//					$msg .= "----------------------------------------\n";
//					foreach ($statistics['MISSING'] as $category => $value) {
//						$msg .= "$category\n";
//						foreach ($value as $id => $filename) {
//							$msg .= "\t".$filename."\n";
//						}
//					}
//					$msg .= "\n";
//				}
//				
//				if (isset($statistics['INVALID_SOURCE']) && count($statistics['INVALID_SOURCE'])) {
//					$msg .= "----------------------------------------\n";
//					$msg .= "- INVALID SOURCE FILE\n";
//					$msg .= "----------------------------------------\n";
//					foreach ($statistics['INVALID_SOURCE'] as $category => $value) {
//						$msg .= "$category\n";
//						foreach ($value as $id => $filename) {
//							$msg .= "\t".$filename."\n";
//						}
//					}
//					$msg .= "\n";
//				}
//
//				
//				if (isset($statistics['DONE']) && count($statistics['DONE'])) {
//					$msg .= "########################################\n";
//					$msg .= "# NEW STRUCTURE PREVIEW:\n";
//					$msg .= "########################################\n";
//					foreach ($statistics['DONE'] as $category => $value) {
//						$msg .= "$category\n";
//						foreach ($value as $id => $filename) {
//							$msg .= "\t".$filename."\n";
//						}
//					}
//					$msg .= "\n";
//				}
//				
//				$this->status_obj->update_message($msg);
//				
//				if ($process) {
//					if (!$process_type) return false;
//					$oFileOrga->set_reorganize_mode($process_type);
//					$oFileOrga->process();
//				}
//			}
//			$this->status_obj->update_progressbar(1, "reorganization DONE");
//			
//			if ($process) {
//				$title = I18N::get('popup', 'rom_reorg_done_title');
//				$msg = sprintf(I18N::get('popup', 'rom_reorg_done__msg%s'), $path);
//				$this->status_obj->open_popup_complete($title, $msg);
//			}
//			else {
//				$this->status_obj->reset1();
//			}
//		}
//		return true;
//	}
	
	public function importDatControlMame($eccident, $extensionFilter, $auditRoms = false){
		
		$status = $this->status_obj;
		
		if ($status->init()) {
			# rem
			$platfom = $this->ini->getPlatformName($eccident);
			$lastSelected = $this->ini->getHistoryKey('ImportDatCmLast_'.$eccident);
			if (!$lastSelected) $lastSelected = realpath(ECC_DIR_SYSTEM.'/datfile/'.strtolower($eccident).'.dat');
			
			$shorcutFolder = $this->ini->getShortcutPaths($eccident);
			$title = sprintf(I18N::get('popup', 'importDatCMFilechooseTitle%s'), $platfom);
			$path = FACTORY::get('manager/Os')->openChooseFileDialog($lastSelected, $title, $extensionFilter, false, false, $shorcutFolder);
			
			if ($path === false) return $status->reset1();
			
			$title = I18N::get('popup', 'importDatCMConfirmTitle');
			$msg = sprintf(I18N::get('popup', 'importDatCMConfirmMsg%s%s%s'), $platfom, $eccident, basename($path));
			if (!$this->guiManager->openDialogConfirm($title, $msg)) return $status->reset1();
			
			$status->set_label(sprintf(i18n::get('popup', 'stateLabelDatImport%s'), $platfom));
			$status->set_popup_cancel_msg();
			$status->show_main();
			$status->show_output();
			
			// write path to history
			$this->ini->storeHistoryKey('ImportDatCmLast_'.$eccident, $path, true);

			$managerImportCM = FACTORY::get('manager/ImportDatControlMame');

			$managerImportCM->setStatusHandler($status);
			
			$managerImportCM->setFromFile($path);
			$managerImportCM->prepareData();
			
			#$datHeader = $managerImportCM->getHeader();
			
			if ($auditRoms) $managerImportCM->importCompleteRoms($eccident);	
			else $managerImportCM->importAllData($eccident);
			
			$status->update_message('import done');
			
			$title = I18N::get('popup', 'rom_import_done_title');
			$msg = sprintf(I18N::get('popup', 'rom_import_done_msg%s'), strtoupper($this->ecc_platform_name));
			$status->open_popup_complete($title, $msg);
			
			$this->onInitialRecord();
			
			return true;
		}
		return false;
	}
	
	/*
	*
	*/
	public function DatFileImport($extension_limit=false, $eccDatfileData=false){
		
		$platfom = strtoupper($this->ecc_platform_name);
		
		$title = I18N::get('popup', 'rom_import_backup_title');
		$msg = sprintf(I18N::get('popup', 'rom_import_backup_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
		$backup_state = $this->guiManager->openDialogConfirm($title, $msg);
		if ($backup_state) $this->DatFileExport(false, false, false);
		
		if ($this->status_obj->init()) {
			
			if (!$eccDatfileData) {
				$path_history = $this->ini->getHistoryKey('eccMediaDat_import');
				$title = sprintf(I18N::get('popup', 'dat_import_filechooser_title%s'), $platfom);
				
				$shorcutFolder = $this->ini->getShortcutPaths($this->_eccident);
				$path = FACTORY::get('manager/Os')->openChooseFileDialog($path_history, $title, $extension_limit, false, false, $shorcutFolder);
				
				if ($path === false) {
					$this->status_obj->reset1();
					return false;
				}
				$title = I18N::get('popup', 'rom_import_title');
				$msg = sprintf(I18N::get('popup', 'rom_import_msg%s%s%s'), $platfom, $this->_eccident, basename($path));
				if (!$this->guiManager->openDialogConfirm($title, $msg)) {
					$this->status_obj->reset1();
					return false;
				}
			}
			else $path = false;
			
			$this->status_obj->set_label(sprintf(i18n::get('popup', 'stateLabelDatImport%s'), $platfom));
			$this->status_obj->set_popup_cancel_msg();
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			// write path to history
			$this->ini->storeHistoryKey('eccMediaDat_import', $path, true);
			require_once('manager/cDatFileImport.php');
			$import = new DatFileImport($this->_eccident, $this->status_obj, $this->ini);
			$import->setDbms($this->dbms);

			# direct add internet datfile
			if($eccDatfileData) $import->setDirectDatfileContent($eccDatfileData);
			
			$import->parse($path);
			$this->status_obj->update_message($import->getLog());
			
			$title = I18N::get('popup', 'rom_import_done_title');
			$msg = sprintf(I18N::get('popup', 'rom_import_done_msg%s'), strtoupper($this->ecc_platform_name));
			$this->status_obj->open_popup_complete($title, $msg);
			
			$this->onInitialRecord();
		}
		return true;
	}
	
	/*
	*
	*/
	public function MediaMaintDb($function, $media_type = false, $showPopup = true)
	{
		$maint = FACTORY::get('manager/PlattformMaintenance', $this->status_obj);
		$maint->set_eccident($this->_eccident);
		
		switch ($function) {
			case 'OPTIMIZE':
				if ($this->status_obj->init()) {

					$this->status_obj->set_label(i18n::get('popup', 'stateLabelOptimizeDB'));
					$this->status_obj->set_popup_cancel_msg();
					
					if ($showPopup){
						$this->status_obj->show_main();
						$this->status_obj->show_output();
					}
					
					$maint->set_eccident($media_type);
					$maint->optimizeDbForCurrenEccident();
					
					$this->update_treeview_nav();
					$this->onInitialRecord();
					if ($showPopup){
						$title = I18N::get('popup', 'rom_optimize_done_title');
						$msg = sprintf(I18N::get('popup', 'rom_optimize_done_msg%s'), strtoupper($this->ecc_platform_name));
						$this->status_obj->open_popup_complete($title, $msg);
					}
					else $this->status_obj->reset1();
				}
				break;
			case 'CLEAR_MEDIA':
				
				if (!$media_type) $media_type = $this->_eccident;
				
				# re,
				#$platformName = ($media_type) ? $this->ini->getPlatformNavigation($media_type) : strtoupper(i18n::get('global', 'allFound'));
				$platformName = $this->ini->getPlatformName($media_type);
				
				$title = sprintf(I18N::get('popup', 'rom_remove_title%s'), $platformName);
				$msg = sprintf(I18N::get('popup', 'rom_remove_msg%s'), $platformName);
				$choice = $this->guiManager->openDialogConfirm($title, $msg);
				if (!$choice) return false;
				
				$maint->set_eccident($media_type);
				$maint->removeRomsForCurrentEccident();
				
				$this->update_treeview_nav();
				$this->onInitialRecord();
				
				$title = sprintf(I18N::get('popup', 'rom_remove_done_title'), $platformName);
				$msg = sprintf(I18N::get('popup', 'rom_remove_done_msg%s'), $platformName);
				$this->guiManager->openDialogInfo($title, $msg);
				
				break;
			case 'CLEAR_DAT':
				
				$msg = "";
				
				$media_type = ($this->_eccident) ? $this->_eccident : 'all' ;
				
				$title = sprintf(I18N::get('popup', 'dat_clear_title%s'), $media_type);
				$msg = sprintf(I18N::get('popup', 'dat_clear_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
				$choice = $this->guiManager->openDialogConfirm($title, $msg);
				if (!$choice) return false;
				
				$title = sprintf(I18N::get('popup', 'dat_clear_backup_title%s'), $media_type);
				$msg = sprintf(I18N::get('popup', 'dat_clear_backup_msg%s%s'), strtoupper($this->ecc_platform_name), $this->_eccident);
				$backup_state = $this->guiManager->openDialogConfirm($title, $msg);
				if ($backup_state) $this->DatFileExport(false, false, false);
				
				if ($this->status_obj->init()) {

					$this->status_obj->set_label(i18n::get('popup', 'stateLabelOptimizeDB'));
					$this->status_obj->set_popup_cancel_msg();
					$this->status_obj->show_main();
					$this->status_obj->show_output();
					
					$maint->removeDatForCurrentEccident();
					
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
		return true;
	}
	
	/**
	 * Filter the keystrokes to prevent to
	 * many sql-queries!
	 *
	 * @param unknown_type $test
	 */
	public function quickSearchFilter($test = false) {
		#if (trim($this->search_input_txt->get_text())) 
		Gtk::timeout_add(450, array($this, 'quick_search'), $test);	
	}
	
	/**
	 * Set current freeform search word
	 */
	public function quick_search($test)	{
		$this->nb_main->set_current_page(0);
		
		$this->_search_word_like_pre = $this->search_input_pre->get_active();
		$this->_search_word_like_post = $this->search_input_post->get_active();
		$this->_search_word = trim($this->search_input_txt->get_text());
		
		if (false !== strpos($this->_search_word, '*')) {
			$this->_search_word = str_replace('*', '%', $this->_search_word);
		}
		
		$state = ($this->_search_word_like_pre) ? true : false;
		$this->set_search_state('quick_pre', $state);
		
		$state = ($this->_search_word_like_post) ? true : false;
		$this->set_search_state('quick_post', $state);
		
		$state = ($this->_search_word) ? true : false;
		$this->set_search_state('quick', $state);
		
		if (get_class($test) != 'GtkToggleButton' && $this->_search_word != "" && $this->_search_word_last == $this->_search_word) {
			//print "wurde schon eingegeben\n";
		}
		else {
			$this->_search_word_last = $this->_search_word;
			$this->onInitialRecord();
			$this->update_treeview_nav();
		}
		return false;
	}
	
	public function on_toggle_state(&$observed_var, $write_histroy=false, $typeCast = true) {
		if($typeCast) {
			$observed_var = ($observed_var) ? false : true ;
		}
		if ($write_histroy) {
			$this->ini->storeHistoryKey($write_histroy, $observed_var, false);
			//print "write: $observed_var -- $write_histroy";
		}
		$this->onInitialRecord();
		
		$this->update_treeview_nav();
		return true;
	}
	
	/*
	*
	*/
	public function onResetSearch()
	{
		$this->breakSearchReset = true;
		
		$this->_search_word_like_pre = $this->search_input_pre->set_active(false);
		$this->_search_word_like_post = $this->search_input_post->set_active(false);
		$this->_search_word = $this->search_input_txt->set_text('');
		
		$this->cb_search_language->set_active(0);
		$this->cb_search_category->set_active(0);
		$this->cb_search_mameDriver->set_active(0);
		
		$this->_search_category = false;
		$this->_search_mameDriver = false;
		
		$this->setSearchRating(0, false);
		$this->setSearchFfOperator(current(array_keys($this->freeformSearchOperators)), reset($this->freeformSearchOperators), false);
		$this->setSearchFfType(current(array_keys($this->freeformSearchFields)), false);

		$this->update_treeview_nav();
		$this->reset_search_state();
		
		#$this->onInitialRecord();
		
		$this->breakSearchReset = false;
	}
	
	/** Opens the selected media in the assigned player
	*
	*/
	public function startRom($alternateEmuName = false) {

		if (!$this->current_media_info['id']) return false;
		
		$eccident = strtolower($this->current_media_info['fd_eccident']);
		
		$romPath = $this->current_media_info['path'];
		
		$romName = ($this->current_media_info['path_pack']) ? $this->current_media_info['path_pack'] : $this->current_media_info['path'];
		$romFileExtension = strtolower($this->get_ext_form_file($romName));

		$emuConfig = $this->ini->getKey('ECC_PLATFORM', $eccident);
	
		# global emulator used as overall fallback!
		$emuGlobal = $emuConfig['EMU.GLOBAL'];
		
		# if there is a emulator for the fileextesion of the rom, use this.
		# otherwise use global... + test for alternate emulator
		if (isset($emuConfig['EMU.'.$romFileExtension]) && $emuConfig['EMU.'.$romFileExtension]['active'] == 1) {
			$emuExtesion = $emuConfig['EMU.'.$romFileExtension];
			if (!trim($emuExtesion['path'])) $emuExtesion['path'] = $emuGlobal['path'];
			$usedEmu = $emuExtesion;
		}
		else {
			$usedEmu = $emuGlobal;
		}
		# if there is an alternate emulator selected, use this one.
		# if there is no path assigned, use the path form usedEmu
		if ($alternateEmuName && isset($emuConfig['EMU.'.$alternateEmuName])) {
			$emuAlternate = $emuConfig['EMU.'.$alternateEmuName];
			if (!trim($emuAlternate['path'])) $emuAlternate['path'] = $usedEmu['path'];
			$usedEmu = $emuAlternate;
		}
		
		# if packed, get configuration and unpack, if needed!
		if(@$usedEmu['enableZipUnpackActive'] && $this->current_media_info['path_pack']){

			# get folder to unpack
			$unpackDestination = $this->ini->getUnpackFolder($eccident, true);
			
			# if upack skip is activated, this file is only create the first time!
			$unpackFileNeeded = true;
			if(@$usedEmu['enableZipUnpackSkip']){
				if(file_exists($unpackDestination.'/'.$romName)) $unpackFileNeeded = false;
				$romPath = $unpackDestination.'/'.$romName; 
			}
			if($unpackFileNeeded){
				FileIO::extractZip($this->current_media_info['path'], $this->current_media_info['path_pack'], $unpackDestination);
				$romPath = $unpackDestination.'/'.$romName;
			}
		}
		

		
		$emuPath = $usedEmu['path'];
		$emuParameter = $usedEmu['param'];
		$emuEscape = (int)$usedEmu['escape'];
		$emuWin8char = (int)$usedEmu['win8char'];
		$filenameOnly = (int)@$usedEmu['filenameOnly'];
		$noExtension = (int)@$usedEmu['noExtension'];
		$enableEccScript = (int)@$usedEmu['enableEccScript'];
		$executeInEmuFolder = (int)@$usedEmu['executeInEmuFolder'];
		
		# search for some errors
		$errorMessage = false;
		if (!$emuPath) $errorMessage = I18N::get('popup', 'emu_miss_notset_msg');
		elseif (!realpath($emuPath)) $errorMessage = I18N::get('popup', 'emu_miss_notfound_msg%s');
		elseif (is_dir($emuPath)) $errorMessage = I18N::get('popup', 'emu_miss_dir_msg%s');
		// if error, open popup
		if ($errorMessage) {
			$this->openGuiConfig('EMU', $eccident, $errorMessage);
			return false;
		}
		
		if (!realpath($romPath)) {
			$this->guiManager->openDialogInfo(I18N::get('popup', 'rom_miss_title'), I18N::get('popup', 'rom_miss_msg'));
			return false;
		}

		// execute the file with the assigned emulator		
		$osManager = FACTORY::get('manager/Os');
		if ($osManager->executeFileWithProgramm($emuPath, $emuParameter, $romPath, $emuEscape, $emuWin8char, $filenameOnly, $noExtension, $enableEccScript, $executeInEmuFolder)){
			$this->_fileView->update_launch_time($this->current_media_info['id']);	
		}
		return true;
	}
	
	public function openGuiConfig($type = false, $eccident = false, $errorMessage = false) {
		// $type EMU|???
		$this->oGuiConfig = FACTORY::get('manager/GuiPopConfig', $this);
		$this->oGuiConfig->open($type, $eccident, $errorMessage);
	}
	
	/*
	*
	*/
	public function set_style($text_obj, $size=14000, $color="#cc0000")
	{
		$font = new PangoFontDescription();
		$font->set_size($size);

# DISABLED FOR JAPANESE TEST
if (i18n::getLanguageIdent() != 'jp') {
	$font->set_family($this->os_env['FONT']);
}

		#$font->set_style(Pango::STYLE_ITALIC);	

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
	
	
	public function presentRandomGame() {
		$this->randomGame = true;
		$this->onReloadRecord();
		if (count($this->the_file_list) === 1) {
			$combinedId = key($this->the_file_list);
			if ($combinedId) $this->show_media_info(false, $combinedId);
		}
		$this->randomGame = false;
	}
	
	public function handleShortcuts($widged, $event) {

		# only for debug output
//		print "$event->keyval && $event->state".LF;
//		return true;
		
		switch ($event->keyval){
			
			case '65535': # DEL
				
				# only delete, if main treeview is focused and data is selected!
				$searchFocusState = $this->newTreeView->is_focus();
				if(!$searchFocusState || !$this->current_media_info) return false;
				 
				switch ($event->state){
					case '0': # DIRECT
						# remove rom from database
						#$this->remove_media_from_fdata();
						$this->dispatch_menu_context('REMOVE_MEDIA');
						return true;
						break;
					case '1': # SHIFT
						# remove from disk
						$this->dispatch_menu_context('SHELLOP', 'FILE_REMOVE');
						return true;
						break;
					case '4': # STRG
						# remove metadata for selected rom
						$this->dispatch_menu_context('REMOVE_META_SINGLE');
						return true;
						break;
					case '8': # ALT
						# remove all images from this rom
						$this->dispatch_menu_context('IMG_REMOVE_ALL');
						return true;
						break;
				}
				break;
		}
		
		# STRG+F - fullscreen
		if ($event->keyval == '102' && $event->state == '4') {
			if($this->wdo_main->window->get_state() != 16) $this->wdo_main->fullscreen();
			else $this->wdo_main->unfullscreen();
			return true;
		}
		
		// DETAIL LIST
		// F1
		if ($event->keyval == '65471' && $event->state == '0') {
			$this->optVisMainListMode = false;
			$this->updateEccOptBtnBar('optVisMainListMode', 'toggleMailListMode');
			return true;
		}
		// SIMPLE LIST
		// F2
		if ($event->keyval == '65470' && $event->state == '0') {
			$this->optVisMainListMode = true;
			$this->updateEccOptBtnBar('optVisMainListMode', 'toggleMailListMode');
			return true;
		}
		
		// reparse
		// strg / alt F5
		if ($event->keyval == '65474' && $event->state == '8') {
			$this->dispatch_menu_context_platform('ROM_RESCAN_ALL');
			$this->onReloadRecord();
			return true;
		}
		
		if ($event->keyval == '65474' && $event->state == '0') {
			$this->onReloadRecord();
			return true;
		}
		
		# F4 select random games
		if ($event->keyval == '65473' && $event->state == '0') {
			$this->presentRandomGame();
		}
		
		// add rom
		// strg A
		if ($event->keyval == '97' && $event->state == '8') {
			$this->parseMedia();
			return true;
		}
		// add rom
		// Einfg
		if ($event->keyval == '65379' && ($event->state == '4' || $event->state == '8')) {
			$this->parseMedia();
			return true;
		}
		
		// add rom 
		// ALT+R
		if ($event->keyval == '114' && $event->state == '8') {
			$this->dispatch_menu_context('ROM_RESCAN_FOLDER');
			return true;
		}
		
		// bookmark
		// strg B
		if ($event->keyval == '98' && $event->state == '8') {
			$this->add_bookmark_by_id();
			return true;
		}
		
		//edit meta
		// strg E
		if ($event->keyval == '101' && $event->state == '8') {
			$this->edit_media(false, 0);
			return true;
		}

		if ($event->keyval == '105' && $event->state == '8') {
			$this->openRomAuditPopup();
			return true;
		}
		
		//search
		// strg S
		if ($event->keyval == '102' && ($event->state == '4' || $event->state == '8')) {
			$this->search_input_txt->grab_focus();
			return true;
		}
		
		// F6 show media
		if ($event->keyval == '65475' && $event->state == '0') {
			return $this->selectViewModeAllAvailable();
		}
		
		// F7 show bookmark
		if ($event->keyval == '65476' && $event->state == '0') {
			$this->selectViewModeBookmarks();
		}

		// F8 show history
		if ($event->keyval == '65477' && $event->state == '0') {
			return $this->selectViewModePlayedHistory();
		}
		
		// F9 hide search panel
		if ($event->keyval == '65478' && $event->state == '0') {
			return $this->toogleSearchPanel();
		}
		
		// F11 hide navigation
		if ($event->keyval == '65480' && $event->state == '0') {
			return $this->toogleNavPanel();
		}
		
		// F12 hide mediainfo
		if ($event->keyval == '65481' && $event->state == '0') {
			return $this->toogleInfoPanel();
		}
		
		// STRG+F1
		if ($event->keyval == '65470' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_ALL');
		}
		// STRG+F2
		if ($event->keyval == '65471' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_VIEWMODE_DONTHAVE');
		}
		// STRG+F3
		if ($event->keyval == '65472' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY');
		}
		// STRG+F4
		if ($event->keyval == '65473' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_METALESS');
		}
		// STRG+F5
		if ($event->keyval == '65474' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_PERSONAL');
		}
		// STRG+F6
		if ($event->keyval == '65475' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_PLAYED');
		}
		// STRG+F7
		if ($event->keyval == '65476' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED');
		}
		
		// STRG+F8
		if ($event->keyval == '65477' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED');
		}
		// STRG+F9
		if ($event->keyval == '65478' && $event->state == '4') {
			$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS');
		}
	}
	
	public function selectViewModeAllAvailable(){
		$this->view_mode = 'MEDIA';
		$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_ALL');
		return true;
	}
	
	##TEst
	public function selectViewModeBookmarks(){
		$this->view_mode = 'BOOKMARK';
		$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS');
		return true;
	}
	
	public function selectViewModePlayedHistory(){
		$this->view_mode = 'HISTORY';
		$this->dispatch_menu_context_platform('TOGGLE_MAINVIEV_DISPLAY_PLAYED');
		return true;
	}
	
	public function updateMainButtonHilights($selectedType, $selectedMode){
		
		if(!$selectedType) $selectedType = 'MEDIA';
		
		switch($selectedMode){
			case 'TOGGLE_MAINVIEV_ALL':
			case 'TOGGLE_MAINVIEV_DISPLAY_PLAYED':
			case 'TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS':
				$hilightColor = '#000000';
				$hilightMarkupStart = '<b>';
				$hilightMarkupEnd = '</b>';
				break;
			case 'TOGGLE_VIEWMODE_DONTHAVE':
			case 'TOGGLE_MAINVIEV_DISPLAY':
			case 'TOGGLE_MAINVIEV_DISPLAY_METALESS':
			case 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL':
			case 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL_REVIEW':
			case 'TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED':
			case 'TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED':
				$hilightColor = '#333333';
				$hilightMarkupStart = '<b><i>';
				$hilightMarkupEnd = ' *</i></b>';
				break;
			default:
				$hilightColor = '#000000';
				$hilightMarkupStart = '<b>';
				$hilightMarkupEnd = '</b>';
		}
		
		$buttons = array(
			'MEDIA' => 'btnMainShowAllRomsLabel',
			'BOOKMARK' => 'btnMainShowBookmarkedRomsLabel',
			'HISTORY' => 'btnMainShowLaunchedRomsLabel',
		);

		foreach($buttons as $type => $widgetAndI18nKey){
			if($type == $selectedType) $this->$widgetAndI18nKey->set_markup($hilightMarkupStart.'<span color="'.$hilightColor.'">'.I18N::get('mainGui', $widgetAndI18nKey).'</span>'.$hilightMarkupEnd);
			else $this->$widgetAndI18nKey->set_label(I18N::get('mainGui', $widgetAndI18nKey));
		}
	}

	public function toogleNavPanel() {
		
		if ($this->visibleNavigation) {
			
//			#$navPanelMainSizeBeforeToggle = $this->wdo_main->get_size();
//			if($this->navPanelMainSize && $this->wdo_main->window->get_state() != 16 && $this->wdo_main->window->get_state() != 4){
//				list ($width, $height) = $this->navPanelMainSize;
//				$this->wdo_main->resize($width, $height);
//				$this->navPanelMainSize = false;
//			}
			
			#$modeBefore = $this->wdo_main->window->get_state();
			
//			print __FUNCTION__.'<pre>';
//			print_r($modeBefore);
//			print '</pre>'."\n";
			
			$this->vbox_nav->hide();
			$this->mTopViewToggleLeft->set_active(false);
			$this->visibleNavigation = false;
			
//			switch($modeBefore){
//				case 16:
//					$this->wdo_main->hide();
//					$this->wdo_main->fullscreen();
//					$this->wdo_main->show();
//					break;
//				case 4:
//					$this->wdo_main->maximize();
//					break;
//			}
			
		}
		else {
			
//			$this->navPanelMainSize = $this->wdo_main->get_size();
			
			#$modeBefore = $this->wdo_main->window->get_state();
			
			$this->vbox_nav->show();
			$this->mTopViewToggleLeft->set_active(true);
			$this->visibleNavigation = true;
			
//			switch($modeBefore){
//				case 16:
//					$this->wdo_main->hide();
//					$this->wdo_main->fullscreen();
//					$this->wdo_main->show();
//					break;
//				case 4:
//					$this->wdo_main->maximize();
//					break;
//			}
			
		}
		
		# store this information to history ini
		$this->ini->storeHistoryKey('vis_hide_panel_nav', !$this->visibleNavigation, false);
		
		return true;
	}
	
	public function toogleInfoPanel() {
		if ($this->visibleMedia) {
			
			#$this->vbox_media->hide();
			$this->scrolledwindow41->hide();
			$this->mTopViewToggleRight->set_active(false);
			$this->visibleMedia = false;
		}
		else {

			#$this->vbox_media->show();
			$this->scrolledwindow41->show();
			$this->mTopViewToggleRight->set_active(true);
			$this->visibleMedia = true;
		}
		
		# store this information to history ini
		$this->ini->storeHistoryKey('vis_hide_panel_info', !$this->visibleMedia, false);
		
		return true;
	}
	
	public function toogleSearchPanel() {
		if ($this->visibleSearch) {
			$this->frame26->hide();
			$this->mTopViewToggleSearch->set_active(false);
			$this->visibleSearch = false;
		}
		else {
			$this->frame26->show();
			$this->mTopViewToggleSearch->set_active(true);
			$this->visibleSearch = true;
		}
		
		# store this information to history ini
		$this->ini->storeHistoryKey('vis_hide_panel_seach', !$this->visibleSearch, false);
		
		return true;
	}
	
	public function onMainlistCursorNavigation($widged, $event, $selection) {
		switch($event->keyval) {
			case Gdk::KEY_Right:
				if (!$event->state) {
					$this->onNextRecord();
				}
				else {
					switch($event->state) {
						case '4': // strg
							$this->onNextRecord(10);
						break;
						case '8': // alt
							$this->onLastRecord();
						break;
					}
				}
			break;
			case Gdk::KEY_Left:
				if (!$event->state) {
					$this->onPrevRecord();
				}
				else {
					switch($event->state) {
						case '4': // strg
							$this->onPrevRecord(10);
						break;
						case '8': // alt
							$this->onFirstRecord();
						break;
					}			
				}
			break;
		}
	}
	
	public function openRomAuditPopup($composite_id = false){
		$guiRomAudit = FACTORY::get('manager/GuiRomAudit', $this);
		if (!$composite_id){
			$file_id = $this->current_media_info['id'];
			$mdata_id = $this->current_media_info['md_id'];
			$composite_id = $file_id."|".$mdata_id;
		}
		
		if (!$this->current_media_info['fd_isMultiFile'] && !$guiRomAudit->isVisible()){
			$title = I18N::get('popup', 'romAuditInfoNotPossibelTitle');
			$msg = I18N::get('popup', 'romAuditInfoNotPossibelMsg');
			$this->guiManager->openDialogInfo($title, $msg);
			return false;
		}
		else {
			$guiRomAudit->show($composite_id);	
		}
	}
	
	public function romAuditReparse($eccident = false){
		if (!$eccident) $eccident = $this->_eccident;
		
		$platformName = $this->ecc_platform_name;
		$title = I18N::get('popup', 'romAuditReparseTitle');
		$msg = I18N::get('popup', 'romAuditReparseMsg%s', $platformName);
		if (!$this->guiManager->openDialogConfirm($title, $msg)) return false; 
		
		$managerImportCM = FACTORY::get('manager/ImportDatControlMame');
		
		if (!$managerImportCM->auditBySystemDat($eccident)){

			# rem
			$platfom = $this->ini->getPlatformName($eccident);
			$lastSelected = $this->ini->getHistoryKey('ImportDatCmLast_'.$eccident);
			$shorcutFolder = $this->ini->getShortcutPaths($eccident);
			$title = sprintf(I18N::get('popup', 'importDatCMFilechooseTitle%s'), $platfom);
			$path = FACTORY::get('manager/Os')->openChooseFileDialog($lastSelected, $title, array('Control MAME (CM) datfiles (*.dat)'=>'*.dat'), false, false, $shorcutFolder);
			
		}
		$managerImportCM->prepareData();
		$managerImportCM->importCompleteRoms($eccident);
		$title = I18N::get('global', 'done_title');
		$msg = I18N::get('global', 'done_msg');
		$this->guiManager->openDialogInfo($title, $msg);
	}
	
	public function setRatingImage($widget, $rating = 0){
		$pixbuf = $this->oHelper->getPixbuf(dirname(__FILE__)."/".'images/eccsys/rating/ecc_rating_stars_'.(int)$rating.'.png');
		$widget->set_from_pixbuf($pixbuf);
	}
	
	/*
	*
	*/
	public function show_media_info($obj=false, $singleRomCompositeId = false)
	{	

		$composite_id = false;
		
		if ($singleRomCompositeId) {
			$composite_id = $singleRomCompositeId;
		}
		elseif ($this->directMediaEdit && isset($this->current_media_info)) {
			$file_id = $this->current_media_info['id'];
			$mdata_id = $this->current_media_info['md_id'];
			$composite_id = $file_id."|".$mdata_id;
		}
		else {
			// Durch den Interator ermitteln,
			// welche media_id ausgew�hlt wurde
			list($model, $iter) = $obj->get_selected();
			if ($iter) {
				$file_id = $model->get_value($iter, 3);
				$mdata_id = $model->get_value($iter, 4);
				$composite_id = $model->get_value($iter, 5);
			}
		}
		
		if ($composite_id) {
			
			if (FACTORY::get('manager/GuiRomAudit', $this)->isVisible()){
				$this->openRomAuditPopup($composite_id);
			}
			
			// edit-button anzeigen
			$this->infotab_button_area->set_sensitive(true);
			$this->btn_start_media->set_sensitive(true);
			$this->btn_add_bookmark->set_sensitive(true);
			
			$this->cb_image_type->set_sensitive(true);
			$this->infoImageBtnMatchImageType->set_sensitive(true);
			$this->infoImageEditBtn->set_sensitive(true);
			
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

				// ------------
				// update also the top menus for files
				// ------------
				$topMenuFilesState = $info['id'] && file_exists($info['path']);
				$this->mTopFileRename->set_sensitive($topMenuFilesState);
				$this->mTopFileCopy->set_sensitive($topMenuFilesState);
				$this->mTopFileRemove->set_sensitive($topMenuFilesState);
				#$this->mMenuReparseFolder->set_sensitive($topMenuFilesState);
				// ------------
				// ------------
				
				$btn_sensitive_bool = ($coposite_id_array['fdata_id']) ? true : false;
				$this->btn_start_media->set_sensitive($btn_sensitive_bool);
				$this->btn_add_bookmark->set_sensitive($btn_sensitive_bool);
				
				$title = ($info['md_name']) ? $info['md_name'] : basename($info['path']);
				$title = MultiByte::convertToUtf8($title);

				$eccident = ($info['fd_eccident']) ? $info['fd_eccident'] : $info['md_eccident'];
				
				if ($eccident == 'mame') {
					$platformName = 'MAME ('.$info['fa_mameDriver'].')';
				}
				else {
					# rem
					$platformName = $this->ini->getPlatformName(strtolower($eccident)).' ('.strtolower($eccident).')';	
				}
				
				if (!trim($platformName)) $platformName = "emuControlCenter";

				#$this->set_style($this->media_nb_info_plattform, 8000);
				$this->media_nb_info_plattform->set_markup('<b><span size="small">'.htmlspecialchars($platformName).'</span></b>');
				
				$packed = ($info['path_pack']) ? 'YES' : 'NO';

				$this->set_style($this->media_nb_info_title, 10000);
				
				$this->media_nb_info_title->set_text($title);
				
				$info_title = ($info['md_name']) ? $info['md_name'] : '';
					
				$info_data = ($info['md_info']) ? str_replace('|', ' ', $info['md_info']) : '';
				$this->media_nb_info_infos->set_markup('<span color="#334455">'.htmlspecialchars($info_data).'</span>');
				
				$info_id = ($info['md_info_id']) ? $info['md_info_id'] : '';
				
				// KB
				$filesize_kb = round($info['size']/1024);
				// MB
				$filesize_mb = round($info['size']/1024/1024, 1);
				$filesize_mb_strg = ($filesize_mb > 0) ? "$filesize_mb MB /" : '';
				//Mbit
				$filesize_mbit = round($info['size']/1024/1024*8, 1);
				$filesize_mbit_strg = ($filesize_mbit > 0) ? "$filesize_mbit Mbit /" : '';
				
				$size = ($info['size']) ? "$filesize_mbit_strg $filesize_mb_strg $filesize_kb KB" : '';
				$this->media_nb_info_file_size->set_markup('<span size="small" color="#334455">'.htmlspecialchars($size).'</span>');
				
				$crc32 = ($info['crc32']) ? $info['crc32'] : $info['md_crc32'];
				$this->media_nb_info_file_crc32->set_markup('<span size="small" color="#334455">'.htmlspecialchars($crc32).'</span>');

				# get icon, if available
				if ($iconPath = $this->imageManager->getImageByType($eccident, $info['crc32'], 'media_icon', false)){
					$iconPath = reset($iconPath); # because its an array
				}
				else $iconPath = dirname(__FILE__)."/".'images/ecc_icon_small.ico';
				# create image with max width/height
				$this->media_nb_info_icon->set_from_pixbuf($this->oHelper->getPixbuf($iconPath, false, false, false, 46, 46));
								
						
				$path = ($info['path_pack']) ? $info['path_pack'] : $info['path'];
				$path = MultiByte::convertToUtf8($path);
				
				$this->media_nb_info_file_name->set_markup('<span size="small" color="#334455">'.htmlspecialchars(basename($path)).'</span>');
				
				$path_pack = ($info['path_pack']) ? basename($info['path']) : "NO";
				$path_pack = MultiByte::convertToUtf8($path_pack);
				
				if ($info['fd_isMultiFile']) $path_pack = 'ROMSET ZIP';
				
				$this->media_nb_info_file_name_pack->set_markup('<span size="small" color="#334455">'.htmlspecialchars($path_pack).'</span>');
				$this->media_nb_info_file_path->set_markup('<span size="small" color="#334455">'.htmlspecialchars(MultiByte::convertToUtf8(dirname(realpath($info['path'])))).'</span>');

				$this->media_nb_info_running->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_running']).'</span></b>');
				$this->media_nb_info_bugs->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_bugs']).'</span></b>');
				$this->media_nb_info_trainer->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_trainer']).'</span></b>');
				$this->media_nb_info_intro->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_intro']).'</span></b>');
				$this->media_nb_info_usermod->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_usermod']).'</span></b>');
				$this->media_nb_info_freeware->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_freeware']).'</span></b>');
				$this->media_nb_info_multiplayer->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_multiplayer']).'</span></b>');
				$this->media_nb_info_netplay->set_markup('<b><span size="small" color="'.$this->colEventOptionText.'">'.$this->get_dropdown_string($info['md_netplay']).'</span></b>');
				
				$md_storage = (!$info['md_storage'] || $info['md_storage'] === 'NULL') ? 0 : $info['md_storage'];
				$this->media_nb_info_storage->set_markup('<span color="'.$this->colEventOptionText.'">'.$this->dropdownStorage[$md_storage].'</span>');
				
				$category = (isset($this->media_category[$info['md_category']])) ? $this->media_category[$info['md_category']] : '';
				$this->media_nb_info_category->set_markup('<b>'.htmlspecialchars($category).'</b>');
				
				$year = ($info['md_year']) ? $info['md_year'] : '';
				$this->media_nb_info_year->set_text($year);
				
				$creator = (isset($info['md_creator'])) ? $info['md_creator'] : '';
				$this->media_nb_info_creator->set_text($creator);

				$publisher = (isset($info['md_publisher'])) ? $info['md_publisher'] : '';
				$this->media_nb_info_publisher->set_text($publisher);
				
//				$programmer = (isset($info['md_programmer'])) ? $info['md_programmer'] : '';
//				$this->media_nb_info_programmer->set_text($programmer);
//				$musican = (isset($info['md_musican'])) ? $info['md_musican'] : '';
//				$this->media_nb_info_musican->set_text($musican);
//				$graphics = (isset($info['md_graphics'])) ? $info['md_graphics'] : '';
//				$this->media_nb_info_graphics->set_text($graphics);
				
				$rating = (isset($info['md_rating'])) ? $info['md_rating'] : 0;
				$this->setRatingImage($this->mInfoRatingImage, $rating);
				
				$imageAuditStateImagePath = FACTORY::get('manager/GuiRomAudit', $this)->getAuditStateIconFilename(
					$info['fa_fDataId'],
					$info['fd_isMultiFile'],
					$info['fa_isMatch'],
					$info['fa_isValidMergedSet'],	
					$info['fa_isValidNonMergedSet'],
					$info['fa_isValidSplitSet'],
					$info['fa_cloneOf'],
					$info['id']
				);
				$this->nbMediaInfoAuditStateImage->set_from_pixbuf($this->oHelper->getPixbuf($imageAuditStateImagePath));
				
				$this->current_media_info = $info;
				$this->set_image_for_show(0);
				
				$this->updateMediaInfoFlags(array_keys($this->_fileView->get_language_by_mdata_id($info['md_id'])));

				$this->updateTabPersonal($info, $info['fd_eccident'], $info['crc32']);
				$this->updatePaneInfoHeader($info);
				
				# romDb tab
				$this->paneInfoEccDbGetDatfileText->set_markup(sprintf(i18n::get('mainGui', 'paneInfoEccDbGetDatfileText%s'), '<b>'.$platformName.'</b>'));
				
//				$this->updatePaneInfoData($info, $platformName, $crc32, $size);
					
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
	
	private function updateTabPersonal($info, $eccIdent, $crc32) {
		
		// PLAY COUNT
		$count = ($info['fd_launchcnt']) ? '<span foreground="#000000">'.$info['fd_launchcnt'].'</span>' : '<span foreground="#aaaaaa">0</span>';
		$this->media_nb_pers_played_count->set_markup($count);
		
		// PLAY TIME
		$date = ($info['fd_launchtime']) ? '<span foreground="#000000">'.date('Y-m-d H:i', $info['fd_launchtime']).'</span>' : '<span foreground="#aaaaaa">never</span>';
		$this->media_nb_pers_played_time->set_markup($date);

		// BOOKMARKED
		$hasBookmark = $this->_fileView->hasBookmark($info['id']);
		$bookmarked = ($hasBookmark) ? '<span foreground="#000000">YES</span>' : '<span foreground="#aaaaaa">NO</span>';
		$this->media_nb_pers_bookmarked->set_markup($bookmarked);
		
		// META CHANGED
		$metaChangeDate = ($info['md_cdate']) ? '<span foreground="#000000">'.date('Y-m-d H:i', $info['md_cdate']).'</span>' : '<span foreground="#aaaaaa">not changed</span>';
		$this->media_nb_pers_metachange->set_markup($metaChangeDate);

		// ROMDB EXPORT
		if($info['md_cdate']){
			$romdbExport = ($info['md_uexport']) ? '<span foreground="#000000">'.date('Y-m-d H:i', $info['md_uexport']).'</span>' : 'possible';
			$this->media_nb_pers_romdb->set_markup($romdbExport);
		}
		else{
			$this->media_nb_pers_romdb->set_markup('');
		}
		
		// implement later--- needs new database table!!!!
		//print "$eccIdent, $crc32".LF;
		//$personalData = $this->_fileView->getRomPersonalData($eccIdent, $crc32);
		
		$mngrUserData = FACTORY::get('manager/UserData');
		$userData = $mngrUserData->getUserdata($eccIdent, $crc32);
		
		$this->userDataId = ($userData['id']) ? $userData['id'] : false;
		
		if ($this->userDataId) {
			$this->infoPersonalLbl->set_markup('<span color="#008800">'.strtoupper(I18N::get('mainGui', 'romDetailTabPersonal')).'</span>');
		}
		else {
			$this->infoPersonalLbl->set_markup(strtoupper(I18N::get('mainGui', 'romDetailTabPersonal')));
		}
		
		$this->userDataEccident = $eccIdent;
		$this->userDataCrc32 = $crc32;

		$textBuffer = new GtkTextBuffer();
		$textBuffer->set_text(trim($userData['notes']));
		$this->media_nb_pers_note->set_buffer($textBuffer);
		
		$this->media_nb_pers_hiscore->set_text(trim($userData['hiscore']));
		
		$state = ($info['md_id']) ? '_active' : '';
		$this->nbMediaInfoMetaImage->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/ecc_edit_yellow'.$state.'.png', true)));
		
		$state = (trim($userData['review_title']) || trim($userData['review_body'])) ? '_active' : '';
		$this->nbMediaInfoReviewImage->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/ecc_edit_green'.$state.'.png', true)));

		$state = (trim($userData['hiscore']) || trim($userData['notes'])) ? '_active' : '';
		$this->nbMediaInfoNoteImage->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/ecc_edit_blue'.$state.'.png', true)));

		$state = ($info['id'] && $this->_fileView->hasBookmark($info['id'])) ? '' : '_add' ;
		$this->nbMediaInfoBookmarkImage->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/heart'.$state.'.png', true)));
		$this->nbMediaInfoBookmarkImage->set_sensitive(!$state);
		
		if (!$this->media_nb_pers_save_connected) {
			
			#$this->iPanePersonalEvent->connect_simple('clicked', array($this, 'edit_media'), false, 2);
			
			$this->media_nb_pers_save->connect_simple('clicked', array($this, 'edit_media'), false, 2);
			$this->media_nb_pers_save_label->set_label(I18N::get('global', 'edit'));
			$this->media_nb_pers_save_connected = true;
		}
	}
	
	private function updatePaneInfoHeader($info){
		
		$isJad = false;		
		
		$dataText = "";
		if (isset($info['fd_mdata']) && $info['fd_mdata']){;
			$mdata = unserialize(base64_decode($info['fd_mdata']));
			if (is_array($mdata) && count($mdata)){
				
				if (isset($mdata['MIDlet-1'])){
					
					$isJad = true;
					
					if ($info['path'] && realpath($info['path'])){
						$mdata['MIDlet-Jar-Size'] = $info['size'];
						$mdata['MIDlet-Jar-URL'] = basename(MultiByte::convertToUtf8($info['path']));
						if (!isset($mdata['Nokia-MIDlet-Category'])) $mdata['Nokia-MIDlet-Category'] = 'Game';
					}
				}
				
				foreach ($mdata as $name => $value) {

					$name = trim($name);
					$value = trim($value);
					
					if (!$name && !$value) continue;
					
					$value = ($value) ? $value : '???';
					$dataText .= "<b>".htmlspecialchars($name)."</b>: ".htmlspecialchars($value)."\n";
				}
			}
		}
		
		$active = true;
		if (!$dataText){
			$active = false;
			$dataText = "<b>".i18n::get('global', 'noInformationsAvailable')."</b>\n";
		}
		
		$text = ($info['path']) ? "<b>".i18n::get('global', 'fileNameShort')."</b>:\n".htmlspecialchars(basename(MultiByte::convertToUtf8($info['path'])))."\n\n" : '';
		$text .= $dataText;
		
		$version = $this->ecc_release['local_release_version']." ".$this->ecc_release['release_build']." ".$this->ecc_release['release_state'];
		$text .= "\n".i18n::get('global', 'generatedBy')." ".$this->ecc_release['title']." ".$version."\n";
		
		try{
			@$this->paneInfoHeaderText->set_markup($text);
		}
		catch(PhpGtkGErrorException $e){
			$this->paneInfoHeaderText->set_markup(i18n::get('global', 'invalidDataEncodingError'));			
		}
		
		$this->media_nb_header_lbl->set_sensitive($active);
		
		$tabLabel = ($isJad) ? I18N::get('mainGui', 'romDetailTabRomHeaderJad') : I18N::get('mainGui', 'romDetailTabRomHeader');
		$this->media_nb_header_lbl->set_text(strtoupper($tabLabel));
	}
	
//	private function updatePaneInfoData($info, $platformName, $crc32, $size){
//		
//		$packed = ($info['path_pack']) ? 'YES' : 'NO';
//		
//		$text = "";
//		
//		$text .= "<b><u>".i18n::get('global', 'fileInfos').":</u></b>\n\n";
//		
//		$text .= "<b>".i18n::get('global', 'name').":</b> ".htmlspecialchars(basename(MultiByte::convertToUtf8($info['path'])))."\n";
//		$text .= "<b>".i18n::get('global', 'platform').":</b> ".htmlspecialchars($platformName)."\n\n";
//		
//		$text .= "<b>".i18n::get('global', 'fileNameShort').":</b> ".htmlspecialchars(basename(MultiByte::convertToUtf8($info['path_pack'])))."\n";
//		$text .= "<b>".i18n::get('global', 'filePathShort').":</b> ".htmlspecialchars(MultiByte::convertToUtf8($info['path']))."\n";
//		$text .= "<b>".i18n::get('global', 'packed').":</b> ".$packed."\n";
//
//		$text .= "<b>".i18n::get('global', 'size').":</b> ".$size."\n";
//		$text .= "<b>".i18n::get('global', 'crc32').":</b> ".$crc32."\n";
//		
//			
////		$text .= "\n";
////		$text .= "<b><u>".i18n::get('global', 'metaInfos').":</u></b>\n\n";
////
////		$dataText = '';
////		if ($info['md_id']) {
////			foreach($info as $key => $value) {
////				if (false !== strpos($key, "md_")) {
////					if ($key == 'md_category') {
////						$category = (isset($this->media_category[$value])) ? $this->media_category[$value] : '';
////						$dataText .= "<b>".i18n::get('global', 'category').":</b> ".htmlspecialchars($category)." ";
////						$dataText .= ($value) ? " (".htmlspecialchars($value).")" : "";
////						$dataText .= "\n";
////					}
////					else {
////						$dataText .= "<b>".htmlspecialchars(strtoupper(str_replace("md_", "", $key)))."</b>: ".htmlspecialchars($value)."\n";
////					}
////				}
////			}
////		}
////		if (!$dataText) $dataText = "<b>".i18n::get('global', 'noInformationsAvailable')."</b>\n";
////		$text .= $dataText;
//		
////		$version = $this->ecc_release['local_release_version']." ".$this->ecc_release['release_build']." ".$this->ecc_release['release_state'];
////		$text .= "\n".i18n::get('global', 'generatedBy')." ".$this->ecc_release['title']." ".$version."\n";
////
////		try{
////			@$this->paneInfoDataText->set_markup($text);
////		}
////		catch(PhpGtkGErrorException $e){
////			$this->paneInfoDataText->set_markup(i18n::get('global', 'invalidDataEncodingError'));			
////		}
//	}
	
//	public function saveUserData() {
//		
//		$mngrUserData = FACTORY::get('manager/UserData');
//		
//		$textBuffer = $this->media_nb_pers_note->get_buffer();
//		$notes = $textBuffer->get_text($textBuffer->get_start_iter(), $textBuffer->get_end_iter());
//		
//		if (!trim($notes)) {
//			$mngrUserData->deleteNotesByRomident($this->userDataEccident, $this->userDataCrc32);
//			$msg = i18n::get('global', 'dataRemoved');
//		}
//		elseif ($this->userDataId) {
//			$mngrUserData->updateNotesById($this->userDataId, $notes);
//			$msg = i18n::get('global', 'dataUpdated');
//		}
//		else {
//			$this->userDataId = $mngrUserData->insertNotesByRomident($this->userDataEccident, $this->userDataCrc32, $notes);
//			$msg = i18n::get('global', 'dataAdded');
//		}
//		$this->guiManager->openDialogInfo(i18n::get('global', 'dataSaved'), $msg);
//	}

	
	public function show_popup_menu_platform_doubleclick($obj, $event){
		
		# not possible for all found!
		if (!$this->_eccident) return false;

		if ($event->button == 1 && $event->type == 5) {
			$this->dispatch_menu_context_platform('ADD_NEW');
		}
	}
	
	/*
	*
	*/
	public function show_popup_menu_platform($obj, $event)
	{
		if ($event->button == 3) {

			$menu = new GtkMenu();
			
			$platform_name = $this->ecc_platform_name;
			
			$menuItem = $this->createImageMenuItem('<b>'.sprintf(I18N::get('menu', 'lbl_platform%s'), $platform_name).'</b>', false);
			#$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_INFO');
			$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT');
			$menu->append($menuItem);
			#$menuItem->set_sensitive(false);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_roms_add'), $this->getThemeFolder('icon/ecc_add.png'));
			$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'ADD_NEW');
			$menuItem->set_sensitive($this->_eccident);
			$menu->append($menuItem);

			$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_emu_config'), $this->getThemeFolder('icon/ecc_settings.png'));
			$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'PLATFORM_EDIT');
			$menu->append($menuItem);
			$menuItem->set_sensitive($this->_eccident);
			
			$menu->append(new GtkSeparatorMenuItem());

			$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_roms_rescan_all'), $this->getThemeFolder('icon/ecc_reload.png'));
			$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'ROM_RESCAN_ALL');
			$menu->append($menuItem);
			
			$itm_maint_db_optimize = $this->createImageMenuItem(sprintf(I18N::get('menu', 'lbl_roms_optimize%s'), $platform_name), $this->getThemeFolder('icon/ecc_optimize.png'));
			$itm_maint_db_optimize->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_OPTIMIZE');
			$menu->append($itm_maint_db_optimize);

			$menuRomDup = new GtkMenu();
			$menuItemRomDup = $this->createImageMenuItem(I18N::get('menu', 'lbl_roms_dup'), $this->getThemeFolder('icon/ecc_duplicate.png'));
			$menuItemRomDup->set_submenu($menuRomDup);
			$menu->append($menuItemRomDup);

			$itm_maint_db_clear_media = new GtkMenuItem(sprintf(I18N::get('menu', 'lbl_roms_remove_dup_preview%s'), $platform_name));
			$itm_maint_db_clear_media->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DUPLICATE_REMOVE_ALL_PREVIEW');
			#$menu->append($itm_maint_db_clear_media);
			$menuRomDup->append($itm_maint_db_clear_media);
			
			$itm_maint_db_clear_media = new GtkMenuItem(sprintf(I18N::get('menu', 'lbl_roms_remove_dup%s'), $platform_name));
			$itm_maint_db_clear_media->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DUPLICATE_REMOVE_ALL');
			#$menu->append($itm_maint_db_clear_media);
			$menuRomDup->append($itm_maint_db_clear_media);
			
			$menuItem = $this->createImageMenuItem(sprintf(I18N::get('menu', 'lbl_roms_remove%s'), $platform_name), $this->getThemeFolder('icon/ecc_remove.png'));
			$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_MEDIA');
			$menu->append($menuItem);
			
			$menu->append(new GtkSeparatorMenuItem());

			// ----------------------------------------------------------------
			// images
			// ----------------------------------------------------------------
			
			$menuTop = new GtkMenu();
			$menuTopItem = $this->createImageMenuItem(I18N::get('menu', 'imagepackTopMenu'), $this->getThemeFolder('icon/ecc_image.png'));
			$menuTopItem->set_submenu($menuTop);
			$menu->append($menuTopItem);
			
			$menuSubItem = new GtkMenuItem(I18N::get('menu', 'imagepackCreateAllThumbnails'));
			$menuSubItem->connect_simple('activate', array($this, 'setShutdownTask'), array('imagepackCreateAllThumbnails', $this->_eccident));
			$menuTop->append($menuSubItem);
			
			$menuSubItem = new GtkMenuItem(I18N::get('menu', 'imagepackRemoveImagesWithoutRomFile'));
			$menuSubItem->connect_simple('activate', array($this, 'setShutdownTask'), array('imagepackRemoveImagesWithoutRomFile', $this->_eccident));
			$menuTop->append($menuSubItem);

			$menuSubItem = new GtkMenuItem(I18N::get('menu', 'imagepackRemoveEmptyFolder'));
			$menuSubItem->connect_simple('activate', array($this, 'setShutdownTask'), array('imagepackRemoveEmptyFolder', $this->_eccident));
			$menuTop->append($menuSubItem);	

			$menuSubItem = new GtkMenuItem(I18N::get('menu', 'imagepackRemoveAllThumbnails'));
			$menuSubItem->connect_simple('activate', array($this, 'setShutdownTask'), array('imagepackRemoveAllThumbnails', $this->_eccident));
			$menuTop->append($menuSubItem);	

			$menuSubItem = new GtkMenuItem(I18N::get('menu', 'imagepackConvertEccV1Images'));
			$menuSubItem->connect_simple('activate', array($this, 'convertEccV1Images'));
			$menuTop->append($menuSubItem);				
			
			$menu->append(new GtkSeparatorMenuItem());
			
//			convertEccV1Images
			
			// ----------------------------------------------------------------
			// Import
			// ----------------------------------------------------------------
			
			$menuImport = new GtkMenu();
			$menuItemImport = $this->createImageMenuItem(I18N::get('menu', 'lbl_import_submenu'), $this->getThemeFolder('icon/ecc_import.png'));
			$menuItemImport->set_submenu($menuImport);
			$menu->append($menuItemImport);
			
			$itmImportEccRomdb = new GtkMenuItem(I18N::get('menu', 'lbl_dat_import_ecc_romdb'));
			$itmImportEccRomdb->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_ECC_ROMDB');
			$menuImport->append($itmImportEccRomdb);		
			
			$itmImportEcc = new GtkMenuItem(I18N::get('menu', 'lbl_dat_import_ecc'));
			$itmImportEcc->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_ECC');
			$menuImport->append($itmImportEcc);
				
			$itmImportState = ($this->_eccident) ? true : false;
			
			#$isMultiRomPlatform = $this->ini->isMultiRomPlatform($this->_eccident);
			$itmImportControlMame = new GtkMenuItem(I18N::get('menu', 'lbl_importDatCtrlMAME'));
			$itmImportControlMame->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_CONTROLMAME', false);
			$menuImport->append($itmImportControlMame);
			$itmImportControlMame->set_sensitive($itmImportState);
			
			$itmImportRc = new GtkMenuItem(I18N::get('menu', 'lbl_dat_import_rc'));
			$itmImportRc->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'IMPORT_RC');
			$menuImport->append($itmImportRc);
			$itmImportRc->set_sensitive($itmImportState);
			
			// ----------------------------------------------------------------
			// Export
			// ----------------------------------------------------------------
			
			$menuExport = new GtkMenu();
			$menuItemExport = $this->createImageMenuItem(I18N::get('menu', 'lbl_export_submenu'), $this->getThemeFolder('icon/ecc_export.png'));
			$menuItemExport->set_submenu($menuExport);
			$menu->append($menuItemExport);
			
			$itm_export = new GtkMenuItem(I18N::get('menu', 'lbl_dat_export_ecc_full'));
			$itm_export->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT');
			$menuExport->append($itm_export);
			
			$itm_export_user = new GtkMenuItem(I18N::get('menu', 'lbl_dat_export_ecc_user'));
			$itm_export_user->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_USER');
			$menuExport->append($itm_export_user);
			
			$itm_export_esearch = new GtkMenuItem(I18N::get('menu', 'lbl_dat_export_ecc_esearch'));
			$itm_export_esearch->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'EXPORT_ESEARCH');
			$menuExport->append($itm_export_esearch);

			$itm_maint_db_clear_dat = $this->createImageMenuItem(I18N::get('menu', 'lbl_dat_empty'), $this->getThemeFolder('icon/ecc_clear.png'));
			$itm_maint_db_clear_dat->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_DB_CLEAR_DAT');
			$menu->append($itm_maint_db_clear_dat);
			
			$menu->append(new GtkSeparatorMenuItem());
			
			// ----------------------------------------------------------------
			// Other
			// ----------------------------------------------------------------
			
//			$itm_maint_db_clear_dat = new GtkMenuItem(I18N::get('menu', 'lbl_rating_unset'));
//			$itm_maint_db_clear_dat->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'MAINT_UNSET_RATINGS');
//			$menu->append($itm_maint_db_clear_dat);
//			$menu->append(new GtkSeparatorMenuItem());
			
			$mItemUserFolder = $this->createImageMenuItem(sprintf(I18N::get('menu', 'lbl_open_eccuser_folder%s'), $this->_eccident), $this->getThemeFolder('icon/ecc_folder.png'));
			$mItemUserFolder->connect_simple('activate', array($this, 'dispatch_menu_context_platform'), 'OPEN_ECCUSER_FOLDER', $this->_eccident);
			$mItemUserFolder->set_sensitive($this->_eccident);
			$menu->append($mItemUserFolder);
			
			$menu->show_all();
			$menu->popup();
		}
	}
	
	public function dispatch_menu_context_platform($obj, $test=false) {
		
		$name = (is_string($obj)) ? $obj : get_class($obj);
		
		switch($name) {
			case 'OPEN_ECCUSER_FOLDER':
				FACTORY::get('manager/Os')->launch_file($this->ini->getUserFolder($test));
				break;
			case 'ADD_NEW':
				$this->parseMedia();
				break;
			case 'IMG_TOGGLE':
				$this->on_image_toggle();
				$this->createEccOptBtnBar();
				break;
			case 'RELOAD_IMG':
				$this->onReloadRecord();
				break;
			case 'MAINT_CLEAN_HISTORY':
				$title = I18N::get('popup', 'maint_empty_history_title');
				$msg = I18N::get('popup', 'maint_empty_history_msg');
				if (!$this->guiManager->openDialogConfirm($title, $msg)) return false; 
				if ($this->ini->clearHistoryIni()) {
					FACTORY::get('manager/Os')->executeProgramDirect(dirname(__FILE__).'/../ecc.exe', 'open', '/fastload');
					#Gtk::main_quit();
					$this->eccShutdown();
				}
				break;
			case 'MAINT_BACKUP_USERDATA':
				if($filename = FACTORY::get('manager/UserData')->exportXml()){
					if($this->guiManager->openDialogConfirm(I18N::get('global', 'done_title'), sprintf(I18N::get('popup', 'userdata_backuped_in%s'), basename($filename)))){
						FACTORY::get('manager/Os')->executeProgramDirect($filename, 'open');
					}
				}
				break;				
			case 'MAINT_UNSET_RATINGS':
				$title = I18N::get('popup', 'maint_unset_ratings_title');
				$msg = I18N::get('popup', 'maint_unset_ratings_msg');
				if (!$this->guiManager->openDialogConfirm($title, $msg)) return false;
				if (FACTORY::get('manager/TreeviewData')->unsetRatingsByEccident($this->_eccident)) {
					$title = I18N::get('global', 'done_title');
					$msg = I18N::get('global', 'done_msg');
					$this->guiManager->openDialogInfo($title, $msg);
					$this->onReloadRecord(false);
				}
				break;				
			case 'MAINT_DB_OPTIMIZE':
				$title = I18N::get('popup', 'rom_optimize_title');
				$msg = I18N::get('popup', 'rom_optimize_msg');
				if (!$this->guiManager->openDialogConfirm($title, $msg)) return false; 
				$this->MediaMaintDb('OPTIMIZE');
				$this->dispatch_menu_context_platform('MAINT_DB_VACUUM');
				break;
			case 'MAINT_DB_VACUUM':
				$title = I18N::get('popup', 'db_optimize_title');
				$msg = I18N::get('popup', 'db_optimize_msg');
				if (!$this->guiManager->openDialogConfirm($title, $msg)) return false; 

				if ($this->status_obj->init()) {
					$this->status_obj->set_label(i18n::get('popup', 'stateLabelVacuumDB'));
					$this->status_obj->set_popup_cancel_msg();
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
				$this->oGuiConfig = FACTORY::get('manager/GuiPopConfig', $this);
				if (!$test) $test = 'EMU';
				$this->oGuiConfig->open($test, $this->_eccident);
				break;
			case 'IMPORT_ECC_ROMDB':
				$this->dispatch_menu_context('WEBSERVICE', 'GET_ROMDB_DATFILE');
				break;	
			case 'IMPORT_CONTROLMAME':
				$isMultiRomPlatform = $this->ini->isMultiRomPlatform($this->_eccident);
				$this->importDatControlMame($this->_eccident, array('Control MAME (CM) datfiles (*.dat)'=>'*.dat'), $isMultiRomPlatform);
				break;
//			case 'IMPORT_CONTROLMAME_MULTIROM':
//				$this->importDatControlMame($this->_eccident, array('Control MAME (CM) datfiles (*.dat)'=>'*.dat'), true);
//				break;
			case 'IMPORT_RC':
				$this->DatFileImport(array('romcenter datfiles (*.dat)'=>'*.dat', $this->ecc_release['title'].' datfiles (*.eccDat)'=>'*.eccDat'));
				break;
			case 'IMPORT_ECC':
				$this->DatFileImport(array($this->ecc_release['title'].' datfiles (*.eccDat)'=>'*.eccDat'));
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
					return $this->guiManager->openDialogInfo($title, $msg); 
				}
				$this->DatFileExport(false, true, true, true);
				break;
			case 'MAINT_DB_CLEAR_DAT':
				$this->MediaMaintDb('CLEAR_DAT');
				break;
			
			case 'PLATFORM_TOGGLE_INACTIVE':
				$this->nav_inactive_hidden = ($this->nav_inactive_hidden) ? false : true;
				$tmpCat = $this->currentPlatformCategory;
				$this->currentPlatformCategory = false;
				$this->update_treeview_nav();
				$this->currentPlatformCategory = $tmpCat;
				$this->ini->storeHistoryKey('nav_inactive_hidden', $this->nav_inactive_hidden, false);
				$this->createEccOptBtnBar();
				break;
			case 'TOGGLE_MAINVIEV_DOUBLETTES':
				$this->on_toggle_state($this->toggle_show_doublettes, "toggle_show_doublettes");
				$this->createEccOptBtnBar();
				break;			
			// radio buttons in top navigation
			case 'TOGGLE_MAINVIEV_ALL':
				
				$this->view_mode = 'MEDIA';
				
				$this->mTopViewModeRomHave->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_ALL';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_ALL', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);

				$this->mTopViewOnlyRoms->set_active(true);
				
				break;
			// radio buttons in top navigation
			case 'TOGGLE_VIEWMODE_DONTHAVE':
				
				$this->view_mode = 'MEDIA';
				
				$this->mTopViewModeRomDontHave->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(true);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_VIEWMODE_DONTHAVE';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_VIEWMODE_DONTHAVE', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyRoms->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY':
				
				$this->view_mode = 'MEDIA';
				
				$this->mTopViewModeRomAll->set_active(true);
				$this->toggle_show_files_only = true;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);

				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyRoms->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_METALESS':
				
				$this->view_mode = 'MEDIA';
				
				$this->mTopViewModeRomNoMeta->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = true;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_METALESS';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_METALESS', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyRoms->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL':
				
				$this->view_mode = 'MEDIA';

				$this->_fileView->setPersonalMode('notes');
				
				$this->mTopViewModeRomPersonal->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(true);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyRoms->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL_REVIEW':
				
				$this->view_mode = 'MEDIA';

				$this->_fileView->setPersonalMode('review');
				
				$this->mTopViewModeRomPersonal->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(true);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL_REVIEW';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_PERSONAL_REVIEW', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyRoms->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_PLAYED':
				
				$this->view_mode = 'HISTORY';
				
				$this->mTopViewModeRomPlayed->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(true);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_PLAYED';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_PLAYED', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyPlayed->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED':
				
				$this->view_mode = 'HISTORY';
				
				$this->mTopViewModeRomMostPlayed->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(true);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_MOSTPLAYED', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyPlayed->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED':
				
				$this->view_mode = 'HISTORY';
				
				$this->mTopViewModeRomNotPlayed->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(true);
				$this->_fileView->showOnlyBookmarks(false);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_NOTPLAYED', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyPlayed->set_active(true);
				
				break;
			case 'TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS':
				
				$this->view_mode = 'BOOKMARK';
				
				$this->mTopViewModeRomBookmarks->set_active(true);
				$this->toggle_show_files_only = false;
				$this->toggle_show_metaless_roms_only = false;
				$this->_fileView->showOnlyPersonal(false);
				$this->_fileView->showOnlyDontHave(false);
				
				$this->_fileView->showOnlyPlayed(false);
				$this->_fileView->showOnlyMostPlayed(false);
				$this->_fileView->showOnlyNotPlayed(false);
				$this->_fileView->showOnlyBookmarks(true);
				
				$this->onInitialRecord();
				$this->update_treeview_nav();
				
				$this->showListDataMode = 'TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS';
				$this->ini->storeHistoryKey('showListDataMode', 'TOGGLE_MAINVIEV_DISPLAY_BOOKMARKS', false);
				$this->ini->storeHistoryKey('showListDataType', $this->view_mode, false);
				
				$this->mTopViewOnlyBookmarks->set_active(true);
				
				break;
				
			case 'TOGGLE_VIEWMODE_DISK_ALL':
				$this->toggle_only_disk = false;
				$this->on_toggle_state($this->toggle_only_disk, "toggle_only_disk", false);
				$this->createEccOptBtnBar(true);
				break;
				
			case 'TOGGLE_VIEWMODE_DISK_ONE':
				$this->toggle_only_disk = 'one';
				$this->on_toggle_state($this->toggle_only_disk, "toggle_only_disk", false);
				$this->createEccOptBtnBar(true);
				break;
				
			case 'TOGGLE_VIEWMODE_DISK_ONE_PLUS':
				$this->toggle_only_disk = 'one_plus';
				$this->on_toggle_state($this->toggle_only_disk, "toggle_only_disk", false);
				$this->createEccOptBtnBar(true);
				break;
				
			case 'MAINT_DUPLICATE_REMOVE_ALL_PREVIEW':
				$this->duplicate_remove_all(false);
				break;
			case 'MAINT_DUPLICATE_REMOVE_ALL':
				$this->duplicate_remove_all(true);
				break;
//			case 'MAINT_FS_ORGANIZE_PREVIEW':
//				$this->fileOrganizer();
//				break;
//			case 'MAINT_FS_ORGANIZE':
//				$this->fileOrganizer(true);
//				break;	
			case 'HELP':
				$this->nb_main->set_current_page(2);
				break;
				
			case 'ROM_RESCAN_ALL':
				$eccident = $this->_eccident;
				
				if ($eccident) {
					$reparsePaths = $this->_fileView->getReparsePathsByEccident($eccident);
					if (count($reparsePaths)) {
						
						$title = I18N::get('popup', 'romReparseAllTitle');
						# rem
						$msg = sprintf(I18N::get('popup', 'romReparseAllMsg%s'), $this->ini->getPlatformName($eccident))."\n";
						if (!FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg)) return false;
						
						$this->parseMedia($eccident, $reparsePaths);
						$this->MediaMaintDb('OPTIMIZE', $eccident, false);
					}
				}
				else {
					
					$silentReparse = $this->ini->getKey('USER_SWITCHES', 'confEccSilentParsing');
					
					$allReparsePaths = $this->_fileView->getReparsePathsAll();
					if (count($allReparsePaths)) {
						
						$platformNames = array();
						# rem
						foreach($allReparsePaths as $key => $void) $platformNames[] = $this->ini->getPlatformName($key);
						
						if (!$silentReparse){
							$title = I18N::get('popup', 'romReparseAllTitle');
							$msg = sprintf(I18N::get('popup', 'romReparseAllMsg%s'), join("\n", $platformNames))."\n";
							if (!FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg)) return false;
						}
						foreach($allReparsePaths as $aEccident => $paths) {
							$this->parseMedia($aEccident, $paths);
						}
						$this->MediaMaintDb('OPTIMIZE', false, false);
					}
				}
			break;
				
			default:
				// do nothing
		}
		$this->updateMainButtonHilights($this->view_mode, $name);
	}
	
	
	private function duplicate_remove_all($remove = false) {
		$title = I18N::get('popup', 'rom_dup_remove_title');
		
		if ($remove){
			$title = I18N::get('popup', 'rom_dup_remove_title');
			$msg = sprintf(I18N::get('popup', 'rom_dup_remove_msg%s'), strtoupper($this->ecc_platform_name));
		}
		else{
			$title = I18N::get('popup', 'rom_dup_remove_title_preview');
			$msg = sprintf(I18N::get('popup', 'rom_dup_remove_msg_preview%s'), strtoupper($this->ecc_platform_name));
		}
		
		if (!$this->guiManager->openDialogConfirm($title, $msg, array('dhide_rom_remove_duplicate'))) return false; 

		if ($this->status_obj->init()) {
			
			$this->status_obj->set_label(i18n::get('popup', 'stateLabelRemoveDupRoms'));
			$this->status_obj->set_popup_cancel_msg();
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			$stats_duplicate = array();
			$msg = $this->_fileView->get_duplicates_all($this->_eccident, $remove);
			$this->status_obj->update_progressbar(1, "removing DONE");
			$this->status_obj->update_message($msg);
			
			$this->update_treeview_nav();
			$this->onInitialRecord();
			
			if ($remove){
				$title = I18N::get('popup', 'rom_dup_remove_done_title');
				$msg = sprintf(I18N::get('popup', 'rom_dup_remove_done_msg%s'), strtoupper($this->ecc_platform_name));
			}
			else {
				$title = I18N::get('popup', 'rom_dup_remove_done_title_preview');
				$msg = sprintf(I18N::get('popup', 'rom_dup_remove_done_msg_preview'), strtoupper($this->ecc_platform_name));
			}
			
				$this->status_obj->open_popup_complete($title, $msg);
		}
		return true;
	}
	
	
	public function directMatchSearch($key, $searchWord) {
		$this->_search_word = trim($searchWord);
		$this->search_input_txt->set_text(trim($searchWord));
		$this->setSearchFfType($key, false);
	}
	
	/*
	*
	*/
	public function show_popup_menu($obj, $event)
	{
		if ($this->data_available && $this->data_available>0) {
			//Check if it was the right mouse button (button 3)
			if ($event->button == 1 && $event->type == 5) {
				$this->startRom();
			}
			elseif ($event->button == 3) {
				
				$platformValid = false;
				$eccident = strtolower($this->current_media_info['fd_eccident']);
				$platformIni = $this->ini->getKey('ECC_PLATFORM', $eccident);
				if ($platformIni) $platformValid = true;
				
				//popup the menu
				$menu = new GtkMenu();
				
				$menuItem = $this->createImageMenuItem('<b>'.I18N::get('menu', 'menuItemRomOptions').'</b>', false);
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'EDIT');
				$menu->append($menuItem);
				
				$menu->append(new GtkSeparatorMenuItem());
				
				# start with default emulator!
				#$menuItemLabel = I18N::get('menu', 'lbl_start');
				#$menuItem = new GtkMenuItem($menuItemLabel);
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_start'), $this->getThemeFolder('icon/ecc_run.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'START_ROM',  false);
				$menuItemActive = ($platformValid && $this->current_media_info['id']) ? true : false;
				$menuItem->set_sensitive($menuItemActive);
				$menu->append($menuItem);
				
				# start with alternate emulator!
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_start_with'), $this->getThemeFolder('icon/ecc_run_opt.png'));
				$menuItemActive = ($platformValid && $this->current_media_info['id']) ? true : false;
				$menuItem->set_sensitive($menuItemActive);
				
				if ($platformIni) {
					$menuSub = new GtkMenu();
					$menuItem->set_submenu($menuSub);
					foreach ($platformIni as $key => $value) {
						if (substr($key, 0, 4) !== 'EMU.') continue;
						$alternateEmuIdent = substr($key, 4);
						if (!$alternateEmuIdent) continue;

						$emuNotFound = (trim($value['path']) && !file_exists(@$value['path']));
						if (@$value['active'] && !$emuNotFound) {

							$emulator = (trim($value['path'])) ? $this->get_plain_filename($value['path']) : '--';
							
							$emuDesc = array();
							
							$emuDesc[] = (trim($value['param'])) ? $value['param'] : '--';
							$emuDesc[] = (trim($value['escape'])) ? 'escape' : '--';
							$emuDesc[] = (trim($value['win8char'])) ? '8.3' : '--';
							$emuDescription = join(' | ', $emuDesc);
							
							$emuNotFoundMessage = ($emuNotFound) ? ' [!'.I18N::get('global', 'emuNotFound').'!] ' : '';							
							$menuSubItem = new GtkMenuItem($emulator.'  ['.I18N::get('global', 'options').': '.$emuDescription.' ] ('.$alternateEmuIdent.')'.' '.$emuNotFoundMessage);
							
							$menuSubItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'START_ROM',  $alternateEmuIdent);
							$menuSub->append($menuSubItem);
						}
					}
				}
				$menu->append($menuItem);
				
				# start with default emulator!
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_emu_config'), $this->getThemeFolder('icon/ecc_settings.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'OPEN_CONFIG',  'EMU', strtolower($this->current_media_info['fd_eccident']));
				#$menuItemActive = ($platformValid) ? true : false;
				#$menuItem->set_sensitive($menuItemActive);
				$menu->append($menuItem);
				
				$menu->append(new GtkSeparatorMenuItem());

				// ----------------------------------------------------------------
				// Rating submenu
				// ----------------------------------------------------------------
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_rating_submenu'), $this->getThemeFolder('icon/ecc_rating.png'));
				$menuItem->connect_simple('activate', array($this, 'edit_media'), false, 1);
				$menu->append($menuItem);	

				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_meta_edit'), $this->getThemeFolder('icon/ecc_edit_yellow.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'EDIT');
				$menu->append($menuItem);
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'menuItemPersonalEditNote'), $this->getThemeFolder('icon/ecc_edit_blue.png'));
				$menuItem->connect_simple('activate', array($this, 'edit_media'), false, 2);
				$menu->append($menuItem);	

				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'menuItemPersonalEditReview'), $this->getThemeFolder('icon/ecc_edit_green.png'));
				$menuItem->connect_simple('activate', array($this, 'edit_media'), false, 1);
				$menu->append($menuItem);

				# lbl_fav_add
				if($this->current_media_info['id'] && $this->_fileView->hasBookmark($this->current_media_info['id'])){
					$label = 'lbl_fav_remove';
					$file = 'heart_delete';
					$action = 'REMOVE_BOOKMARK_SINGLE';
				}
				else{
					$label = 'lbl_fav_add';
					$file = 'heart_add';
					$action = 'ADD_BOOKMARK';
				}
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', $label), $this->getThemeFolder('icon/'.$file.'.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), $action);
				$menu->append($menuItem);
				
				$menu->append(new GtkSeparatorMenuItem());

//				$echo4 = new GtkMenuItem('Get Images from web');
//				$echo4->connect_simple('activate', array($this, 'dispatch_menu_context'), 'GET_IMAGE');
//				$menu->append($echo4);
//				$echo4->set_sensitive(false);
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_image_popup'), $this->getThemeFolder('icon/ecc_image.png'));
				$menuItem->connect_simple('activate', array($this, 'openImagePopup'), false);
				$menu->append($menuItem);
				$imagePopupState = ($this->current_media_info) ? true : false;
				$menuItem->set_sensitive($imagePopupState);
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_img_reload'), $this->getThemeFolder('icon/ecc_reload.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'RELOAD');
				$menu->append($menuItem);
				
				$menu->append(new GtkSeparatorMenuItem());
				
				/**
				 * Simple filter
				 */
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_quickfilter'), $this->getThemeFolder('icon/ecc_filter.png'));
				$menuItemActive = ($this->current_media_info) ? true : false;
				$menuItem->set_sensitive($menuItemActive);
				
				if ($menuItemActive) {
				
					$menuSub = new GtkMenu();
					$menuItem->set_submenu($menuSub);
					
					$menuSubItem = new GtkMenuItem(I18N::get('menu', 'lbl_quickfilter_reset'));
					$menuSubItem->connect_simple('activate', array($this, 'onResetSearch'));
					$menuSub->append($menuSubItem);
					$menuSub->append(new GtkSeparatorMenuItem());
					
	    			$searchFields = array(
	    				'NAME' => 'md_name',
	    				'YEAR' => 'md_year',
	    				'DEVELOPER' => 'md_creator',
	    				'PUBLISHER' => 'md_publisher',
	    				'PROGRAMMER' => 'md_programmer',
	    				'MUSICAN' => 'md_musican',
	    				'GRAPHICS' => 'md_graphics',
	    				'INFO' => 'md_info',
	    				'ECCIDENT' => 'fd_eccident',
	    				'CRC32' => 'crc32',
	    			);

					foreach ($this->freeformSearchFields as $key => $label) {
						$value = false;
						$searchField = (isset($searchFields[$key])) ? $searchFields[$key] : false;
						if (!$searchField) continue;
						$value = $this->current_media_info[$searchField];
						if (!$value) continue;
						$labelValue = '"'.$value.'"';
						$itemLabel = $label.' = '.$labelValue;
						$menuSubItem = new GtkMenuItem($itemLabel);
						$menuSubItem->connect_simple('activate', array($this, 'directMatchSearch'), $key, $value);
						$menuSub->append($menuSubItem);
					}
				}
				$menu->append($menuItem);
				
				$menu->append(new GtkSeparatorMenuItem());
				
				// ----------------------------------------------------------------
				// File operations submenu
				// ----------------------------------------------------------------

				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_shellop_browse_dir'), $this->getThemeFolder('icon/ecc_folder.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'BROWSE_DIR');
				$menu->append($menuItem);
				
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_rom_rescan_folder'), $this->getThemeFolder('icon/ecc_reload.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'ROM_RESCAN_FOLDER');
				$menu->append($menuItem);
				
				$eccAndFileState = $this->current_media_info['fd_eccident'] &&	file_exists($this->current_media_info['path']);

				$menu->append(new GtkSeparatorMenuItem());
				
				# rem
				$menuItem = $this->createImageMenuItem(sprintf(I18N::get('menu', 'lContextRomSelectionAddNewRoms%s'), $this->ini->getPlatformName($this->current_media_info['fd_eccident'])), $this->getThemeFolder('icon/ecc_add.png'));
				$menuItem->connect_simple('activate', array($this, 'dispatch_menu_context'), 'ROM_SELECTION_ADD_NEW');
				$menu->append($menuItem);
				
				$subMenu = new GtkMenu();
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_rom_remove_toplevel'), $this->getThemeFolder('icon/ecc_remove.png'));
				$menuItem->set_submenu($subMenu);
				$menu->append($menuItem);

				$menuItemRemRom = new GtkMenuItem(I18N::get('menu', 'lbl_rom_remove'));
				$menuItemRemRom->connect_simple('activate', array($this, 'dispatch_menu_context'), 'REMOVE_MEDIA');
				$menuItemRemRom->set_sensitive($this->current_media_info['id']);
				$subMenu->append($menuItemRemRom);
				
				$menuItemRemMeta = new GtkMenuItem(I18N::get('menu', 'lContextMetaRemove'));
				$menuItemRemMeta->connect_simple('activate', array($this, 'dispatch_menu_context'), 'REMOVE_META_SINGLE');
				$menuItemRemMeta->set_sensitive($this->current_media_info['md_id']);
				$subMenu->append($menuItemRemMeta);

				$menuItemRemImages = new GtkMenuItem(I18N::get('menu', 'lbl_img_remove_all'));
				$menuItemRemImages->connect_simple('activate', array($this, 'dispatch_menu_context'), 'IMG_REMOVE_ALL');
				$subMenu->append($menuItemRemImages);
				
				$subMenu->append(new GtkSeparatorMenuItem());
				
				$menuItemRemRomsForPlatform = new GtkMenuItem(sprintf(I18N::get('menu', 'lContextRomSelectionRemoveRoms%s'), $this->ini->getPlatformName($this->current_media_info['fd_eccident'])));
				$menuItemRemRomsForPlatform->connect_simple('activate', array($this, 'MediaMaintDb'), 'CLEAR_MEDIA', $this->current_media_info['fd_eccident']);
				$menuItemRemRomsForPlatform->set_sensitive($this->current_media_info['fd_eccident']);
				$subMenu->append($menuItemRemRomsForPlatform);
				
				$menu->append(new GtkSeparatorMenuItem());
				
				
				$menuShellOperations = new GtkMenu();
				$menuItem = $this->createImageMenuItem(I18N::get('menu', 'lbl_shellop_submenu'), $this->getThemeFolder('icon/ecc_save.png'));
				$menuItem->set_submenu($menuShellOperations);
				$menu->append($menuItem);
				
				if (!$this->current_media_info['id'] || !file_exists($this->current_media_info['path'])) {
					$menuItem->set_sensitive(false);
				}
				else {
					$menuItem->set_sensitive(true);
					
					$miFileRename = new GtkMenuItem(I18N::get('menu', 'lbl_shellop_file_rename'));
					$miFileRename->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_RENAME');
					$menuShellOperations->append($miFileRename);
					
					//$miFileRename->set_sensitive(file_exists($this->current_media_info['path']));

					$miFileCopy = new GtkMenuItem(I18N::get('menu', 'lbl_shellop_file_copy'));
					$miFileCopy->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_COPY');
					$menuShellOperations->append($miFileCopy);
					//$miFileCopy->set_sensitive(file_exists($this->current_media_info['path']));
					
					$miFileUnpack = new GtkMenuItem(I18N::get('menu', 'lbl_shellop_file_unpack'));
					$miFileUnpack->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_UNPACK');
					$menuShellOperations->append($miFileUnpack);
					$miFileUnpack->set_sensitive(false);
										
					$menuShellOperations->append(new GtkSeparatorMenuItem());
					
					$miFileRemove = new GtkMenuItem(I18N::get('menu', 'lbl_shellop_file_remove'));
					$miFileRemove->connect_simple('activate', array($this, 'dispatch_menu_context'), 'SHELLOP', 'FILE_REMOVE');
					$menuShellOperations->append($miFileRemove);
					//$miFileRemove->set_sensitive(file_exists($this->current_media_info['path']));
				}
				
				$menu->append(new GtkSeparatorMenuItem());
				
				$eccident = ($this->current_media_info['fd_eccident']) ? $this->current_media_info['fd_eccident'] : $this->current_media_info['md_eccident'];
				$isMultiRomPlatform = $this->ini->isMultiRomPlatform($eccident);
				
				if ($isMultiRomPlatform){

					$echo4 = new GtkMenuItem(I18N::get('menu', 'labelRomAuditInfo'));
					$echo4->connect_simple('activate', array($this, 'openRomAuditPopup'));
					$menu->append($echo4);
					
					$echo4 = new GtkMenuItem(I18N::get('menu', 'labelRomAuditReparse'));
					$echo4->connect_simple('activate', array($this, 'romAuditReparse'), $eccident);
					$menu->append($echo4);
					
					$menu->append(new GtkSeparatorMenuItem());
				}
				
				$label = (!$this->compareLeftId) ? I18N::get('menu', 'lbl_meta_compare_left') : sprintf(I18N::get('menu', 'lbl_meta_compare_right%s'), $this->compareLeftName);
				$menuItem = $this->createImageMenuItem($label, $this->getThemeFolder('icon/ecc_equal.png'));
				$menuItem->connect_simple('activate', array($this, 'setupCompare'));
				$menu->append($menuItem);
				
				
				$menu->show_all();
				$menu->popup();
			}
		}
		else {
			
			if ($this->view_mode != 'MEDIA') return false;
			
			if ($this->_eccident && $event->button == 3) {
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
	
	public function createImageMenuItem($label, $imagePath, $width = false, $height = false, $aspectRatio = false){
		
		$menuItem = new GtkMenuItem($label);
		$labelWidget = $menuItem->child;
		$menuItem->remove($labelWidget);

		$hBox = new GtkHBox();
		#$image = GtkImage::new_from_file($imagePath);
		
		$gtkLabel = new GtkLabel();
		$gtkLabel->set_markup($label);
		$gtkLabel->set_alignment(0, 0.5);

		if($imagePath){
			$image = new GtkImage();
			$image->set_from_pixbuf($this->oHelper->getPixbuf($imagePath, $width, $height, $aspectRatio));
			$hBox->pack_start($image, 0);	
		}
		
		$menuItem->add($hBox);
		
		$hBox->pack_start($gtkLabel);

		return $menuItem;
	}
	

	
	
	private $directMediaEdit = false;
	
	/*
	*
	*/
	public function dispatch_menu_context($obj, $parameter=false, $parameter2 = false) {
		
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
//			case 'REMOVE_BOOKMARK_ALL':
//				$this->remove_bookmark_all();
//				break;
			case 'OPEN_CONFIG':
				$this->openGuiConfig($parameter, $parameter2);
				break;				
			case 'START_ROM':
				$this->startRom($parameter);
				break;
			case 'REMOVE_MEDIA':
				if($this->remove_media_from_fdata($obj)){
					$this->dispatch_menu_context('REMOVE_META_SINGLE', true);
					$this->dispatch_menu_context('IMG_REMOVE_ALL');
				}
				break;
			case 'REMOVE_META_SINGLE':
					
					# is there metadata?
					$metaId = $this->current_media_info['md_id'];
					if (!$metaId) return false;
					
					$title = I18N::get('popup', 'metaRemoveSingleTitle');
					$msg = I18N::get('popup', 'metaRemoveSingleMsg');
					if (!$this->guiManager->openDialogConfirm($title, $msg, array('dhide_remove_meta_single'))) return false;
					$this->_fileView->removeSingleMetaData($metaId);
					
					$this->onReloadRecord();
					$this->show_media_info(false, $this->current_media_info['id'].'|');
					
				break;
			case 'GET_IMAGE':
				$this->getImagesByEccInject();
				break;
			case 'IMG_REMOVE_ALL':
				$eccident = ($this->current_media_info['fd_eccident']) ? $this->current_media_info['fd_eccident'] : $this->current_media_info['md_eccident'];
				$crc32 = ($this->current_media_info['crc32']) ? $this->current_media_info['crc32'] : $this->current_media_info['md_crc32'];
				$title = ($this->current_media_info['md_name']) ? $this->current_media_info['md_name'] : $this->current_media_info['title'];
				$this->removeAllImageFromSelection($eccident, $crc32, $title);
				break;
			case 'WEBSERVICE':
				
				$oWebServices = FACTORY::get('manager/WebServices');
				
				if ($parameter == 'GET') {
				}
				elseif($parameter == 'GET_ROMDB_DATFILE'){
					
					$title = I18N::get('popup', 'eccdb_webservice_get_datfile_title');
					# rem
					$msg = sprintf(I18N::get('popup', 'eccdb_webservice_get_datfile_msg%s'), '<b>'.$this->ini->getPlatformName($this->_eccident).'</b>');
					if (!$this->guiManager->openDialogConfirm($title, $msg)) return false;
					
					$oWebServices->setServiceUrl($this->eccdb['META_DATFILE_URL']);
					$data = $oWebServices->getRomdbDatfile();

					if (!$data || !count($data)){
						$title = I18N::get('popup', 'eccdb_webservice_get_datfile_error_title');
						$msg = I18N::get('popup', 'eccdb_webservice_get_datfile_error_msg');
						$this->guiManager->openDialogInfo($title, $msg);
					}
					else{
						$this->DatFileImport(false, $data);
					}
				}
				elseif($parameter == 'SET') {
					
					if (!in_array(i18n::getLanguageIdent(), array('en', 'fr', 'de', 'nl')))	{					
						$this->guiManager->openDialogInfo('Disabled...', "Hi...\nPlease dont try to add metadata with this '".i18n::getLanguageIdent()."' version!\nThis is disabled until a stable UTF-8 romDB is build!");
						return false;
					}
					
					# save before add
					if ($parameter2) $this->edit_media_save(true);
					
					$title = I18N::get('popup', 'eccdb_title');
					$msg = sprintf(I18N::get('popup', 'eccdb_webservice_post_msg'));
					if (!$this->guiManager->openDialogConfirm($title, $msg, array('dhide_romdb_add_info'))) return false;
					
					if ($this->status_obj->init()) {
						$this->status_obj->set_label(i18n::get('popup', 'stateLabelRomDBAdd'));
						$this->status_obj->set_popup_cancel_msg();
						$this->status_obj->show_main();
						$this->status_obj->show_output();
						
						$perRun = 50;
						$oWebServices->setServiceUrl($this->eccdb['META_ADD_URL']);
						$eccVersion = $this->ecc_release['local_release_version'];
						$oWebServices->setStateObject($this->status_obj);
						
						while(true) {
							
							$count = $oWebServices->getModifiedUserDataCount();
							if (!$count) {
								$msg = sprintf(I18N::get('popup', 'eccdb_no_data'));
								$this->guiManager->openDialogInfo($title, $msg);
								break;
							}
							
							$status = $oWebServices->eccdbAddMetaData($perRun, $eccVersion, $this->sessionTime, $this->cs);
							if ($status['error'] == $status['total']) {
								$msg = sprintf(I18N::get('popup', 'eccdb_error'));
								$this->guiManager->openDialogInfo($title, $msg);
								break;
							}
							
							$dataAvailable = $count-$perRun > 0;
							$availableRecords = ($count-$perRun < $perRun) ? $count-$perRun : $perRun;
							$addMoreMsg = ($dataAvailable) ? "\n\nShould ecc transfer the next ".$availableRecords." records? (Total found recods: ".($count-$perRun).")" : "";
							$msg = sprintf(I18N::get('popup', 'eccdb_statistics_msg%s%s%s%s%s'), $status['added'], $status['inplace'], $status['error'], $status['total'], $addMoreMsg);
							
							if ($dataAvailable) {
								if (!$this->guiManager->openDialogConfirm($title, $msg)) {
									break;
								}
							}
							else {
								$this->guiManager->openDialogInfo($title, $msg);
								break;
							}
						}
						$this->status_obj->open_popup_complete("DONE", "eccdb/romdb updated!");
					}
				}
				
				break;
			case 'EDIT':
				$this->edit_media(false, 0);
				break;
			case 'RATING':
				#if (!$this->current_media_info['md_id']) {
				#	$this->guiManager->openDialogInfo(I18N::get('global', 'error_title'), I18N::get('popup', 'meta_rating_add_error_msg'));
					$this->edit_media(false, 1);
				#}
//				if ($this->_fileView->addRatingByMdataId($this->current_media_info['md_id'], $parameter)) {
//					$this->directMediaEdit = true;
//					$this->show_media_info();
//					$this->onReloadRecord(false);
//					$this->directMediaEdit = false;					
//				}
				break;
			case 'ROM_SELECTION_ADD_NEW':
				$this->parseMedia($this->current_media_info['fd_eccident'], dirname($this->current_media_info['path']), true);
			break;
			case 'ROM_RESCAN_FOLDER':
				$this->MediaMaintDb('OPTIMIZE', $this->current_media_info['fd_eccident'], false);
				$this->parseMedia($this->current_media_info['fd_eccident'], dirname($this->current_media_info['path']));
			break;
			case 'SHELLOP':
				
				switch ($parameter) {
					case 'BROWSE_DIR':
						$filePath = realpath($this->current_media_info['path']);
						if (!$filePath) {
							$this->guiManager->openDialogInfo(I18N::get('global', 'error_title'), "No valid directoy found!");
						}
						else {
							FACTORY::get('manager/Os')->launch_file(dirname($filePath));	
						}
						break;
					case 'FILE_RENAME':
						$pGuiFileOp = FACTORY::create('manager/GuiPopFileOperations', $this);
						$pGuiFileOp->setFdataId($this->current_media_info['id']);
						$pGuiFileOp->setSourceFileName($this->current_media_info['path']);
						#$pGuiFileOp->setDestinationFileName($this->current_media_info['path']);
						$pGuiFileOp->openRenameDialog();
						break;
					case 'FILE_COPY':
						$pGuiFileOp = FACTORY::create('manager/GuiPopFileOperations', $this);
						$pGuiFileOp->setFdataId($this->current_media_info['id']);
						$pGuiFileOp->setSourceFileName($this->current_media_info['path']);
						$pGuiFileOp->setDestinationFileName(false);
						$pGuiFileOp->openCopyDialog();
						break;
					case 'FILE_REMOVE':
						$pGuiFileOp = FACTORY::create('manager/GuiPopFileOperations', $this);
						$pGuiFileOp->setFdataId($this->current_media_info['id']);
						$pGuiFileOp->setSourceFileName($this->current_media_info['path']);
						$pGuiFileOp->setDestinationFileName(false);
						$pGuiFileOp->openDeleteDialog();
						break;
					case 'FILE_UNPACK':
						print "NOT IMPLEMENTED!!! FILE_UNPACK\n";
						break;
				}

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
	
	public function removeAllImageFromSelection($eccident, $crc32, $romTitle){
		if (!$eccident || !$crc32) return false;
		
		$title = I18N::get('popup', 'img_remove_all_title');
		$msg = sprintf(I18N::get('popup', 'img_remove_all_msg%s'), $romTitle);
		if (!$this->guiManager->openDialogConfirm($title, $msg, array('dhide_remove_rom_images'))) return false;

		$this->imageManager->removeUserImageFolder($eccident, $crc32);

		$this->imageManager->resetCachedImages($eccident, $crc32);
		$this->onReloadRecord();
	}

	public function openTabMediaEditRating(){
		$this->mEditUserNotebook->set_current_page(1);
	}
	
	public function initPopupImageCenter(){
		$this->win_imagePopup->connect('delete-event', array($this->oGuiImagePopup, 'hidePopup'));
	}
	
	public function initPopupMetaEdit(){
		
		# windows
		$this->win_media_edit->connect('delete-event', array($this, 'media_edit_hide'));
		
		# rating
		$this->mEditUserRatingFunScale->connect('value-changed', array($this, 'processChangedUserRating'));
		$this->mEditUserRatingGameplayScale->connect('value-changed', array($this, 'processChangedUserRating'));
		$this->mEditUserRatingGraphicsScale->connect('value-changed', array($this, 'processChangedUserRating'));
		$this->mEditUserRatingMusicScale->connect('value-changed', array($this, 'processChangedUserRating'));
	}
	
	public $lastRating;
	public function processChangedUserRating(){
		$rating_fun = $this->mEditUserRatingFunScale->get_value();
		$rating_gameplay = $this->mEditUserRatingGameplayScale->get_value();
		$rating_graphics = $this->mEditUserRatingGraphicsScale->get_value();
		$rating_music = $this->mEditUserRatingMusicScale->get_value();
		$rating = $this->getRating($rating_fun, $rating_gameplay, $rating_graphics, $rating_music);
		$eccRating = $this->getEccRatingIntegerByPercent($rating);
		if($this->lastRating != $eccRating){
			$this->setRatingImage($this->mEditUserRatingSumImage, $eccRating);
			$this->setRatingImage($this->mediaEditMetaRatingLink, $eccRating);
			$this->lastRating = $eccRating;
		}
		$this->mEditUserRatingSum->set_markup('<b>'.i18n::get('global', 'total').': <span color="#00CC00">'.(int)$rating.'</span> %</b>');
	}
	
	public function getRating($rating_fun, $rating_gameplay, $rating_graphics, $rating_music){
		return round(($rating_fun*4 + $rating_gameplay*2 + $rating_graphics + $rating_music) / 8);
	}
	
	public function getEccRatingIntegerByPercent($rating){
		return round((int)$rating*6/100);
	}
	
	/*
	*
	*/
	#public $lastMetaEditChecksum = array();
	public function edit_media($onlyShowIfOpened=false, $openTab = false)
	{
		
		# open tab
		if($openTab !== false) $this->mEditUserNotebook->set_current_page($openTab);
		
		if ($onlyShowIfOpened && !$this->media_edit_is_opened) return false;
		
		$composite_id = $this->current_media_info['composite_id'];
		$coposite_id_array = $this->extract_composite_ids($composite_id);
		
		if ($coposite_id_array['fdata_id']) {
			$mdata_array = $this->_fileView->getRecordByFileId($coposite_id_array['fdata_id']);
		}
		else {
			
			$mdata_array = $this->_fileView->getRecordByMetaId($coposite_id_array['mdata_id']);
		}
		$mdata = @$mdata_array['data'][$composite_id];
		
		if (!$mdata) return false;
		
		$mdata['path'] = MultiByte::convertToUtf8($mdata['path']);
		
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
			'md_publisher' => $mdata['md_publisher'],
			'md_programmer' => $mdata['md_programmer'],
			'md_musican' => $mdata['md_musican'],
			'md_graphics' => $mdata['md_graphics'],
			'md_media_type' => $mdata['md_media_type'],
			'md_media_current' => $mdata['md_media_current'],
			'md_media_count' => $mdata['md_media_count'],
			'md_storage' => $mdata['md_storage'],
		);
		
		$mdata['edit_checksum'] = $this->create_mdata_checksum($check_data);
		
		$fileNamePlain = ($mdata['path_pack']) ? $this->get_plain_filename($mdata['path_pack']) : $this->get_plain_filename($mdata['path']);
		$this->media_edit_filename->set_text($fileNamePlain);
		
		//$name = ($mdata['md_name']) ? $mdata['md_name'] : $fileNamePlain;
		$name = $mdata['md_name'];
		
		$this->media_edit_title->set_text($name);
		$label = i18n::get('global', 'title').'*';
		$this->medit_lbl_title->set_markup("<span foreground='#000000'><b>".$label."</b></span>");
		
		# reset text
		$this->media_edit_help->set_text('');
		$this->media_edit_help->set_visible(false);
		
		$this->mediaEditTabMeta->set_markup(i18n::get('metaEdit', 'mediaEditTabMeta'));
		$this->mediaEditTabRating->set_markup(i18n::get('metaEdit', 'mediaEditTabRating'));
		$this->mediaEditTabPersonal->set_markup(i18n::get('metaEdit', 'mediaEditTabPersonal'));
		$this->mediaEditTabFile->set_markup(i18n::get('metaEdit', 'mediaEditTabFile'));
		
		$this->mediaEditMetaButtonStartRom->set_markup(i18n::get('global', 'startRom'));
		
		
		# rating preview image
		$this->setRatingImage($this->mediaEditMetaRatingLink, $mdata['md_rating']);
		$this->mediaEditMetaRatingLinkEvent->connect('button-press-event', array($this, 'openTabMediaEditRating'));
		
		$this->labelMetaEditYear->set_markup(i18n::get('meta', 'lbl_year'));
		$this->labelMetaEditUsk->set_markup(i18n::get('meta', 'lbl_usk'));
		$this->labelMetaEditCategory->set_markup(i18n::get('meta', 'lbl_category'));
		$this->labelMetaEditDeveloper->set_markup(i18n::get('meta', 'lbl_developer'));
		$this->labelMetaEditPublisher->set_markup(i18n::get('meta', 'lbl_publisher'));
		
		$this->labelMetaEditProgrammer->set_markup(i18n::get('meta', 'lbl_programmer'));
		$this->labelMetaEditGraphicArtist->set_markup(i18n::get('meta', 'lbl_graphics'));
		$this->labelMetaEditMusican->set_markup(i18n::get('meta', 'lbl_musican'));
		$this->labelMetaEditMedium->set_markup(i18n::get('meta', 'lbl_medium'));
		$this->labelMetaEditMediumOf->set_markup(i18n::get('global', 'of'));
		
		# meta
		$this->mediaEditMetaFrameLanguages->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameLanguages').'</b>');
		$this->mediaEditMetaFrameRegion->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameRegion').'</b>');
		
		$this->mediaEditMetaFrameBaseData->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameBaseData').'</b>');
		$this->mediaEditMetaFrameFeatures->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameFeatures').'</b>');
		$this->mediaEditMetaFrameFileInfos->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameFileInfos').'</b>');
		$this->mediaEditMetaFrameAdditionalInfos->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameAdditionalInfos').'</b>');
		$this->mediaEditMetaFrameLinks->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditMetaFrameLinks').'</b>');
		# rating
		$this->mediaEditReviewFrameRating->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditReviewFrameRating').'</b>');
		$this->mediaEditReviewFrameReview->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditReviewFrameReview').'</b>');
		$this->mediaEditReviewFrameMoreSettings->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditReviewFrameMoreSettings').'</b>');
		$this->mEditUserRatingFunScaleLabel->set_label(i18n::get('metaEdit', 'mEditUserRatingFunScaleLabel'));
		$this->mEditUserRatingGameplayScaleLabel->set_label(i18n::get('metaEdit', 'mEditUserRatingGameplayScaleLabel'));
		$this->mEditUserRatingGraphicsScaleLabel->set_label(i18n::get('metaEdit', 'mEditUserRatingGraphicsScaleLabel'));
		$this->mEditUserRatingMusicScaleLabel->set_label(i18n::get('metaEdit', 'mEditUserRatingMusicScaleLabel'));
		$this->mEditUserRatingDifficultyScaleLabel->set_label(i18n::get('metaEdit', 'mEditUserRatingDifficultyScaleLabel'));
		$this->mEditUserReviewExportAllow->set_label(i18n::get('metaEdit', 'mEditUserReviewExportAllow'));
		# personal
		$this->mediaEditPersonalFrameNotes->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditPersonalFrameNotes').'</b>');
		$this->mediaEditPersonalFrameMoreSettings->set_markup('<b>'.i18n::get('metaEdit', 'mediaEditPersonalFrameMoreSettings').'</b>');
		$this->mEditUserPersonalHiscoresLabel->set_label(i18n::get('metaEdit', 'mEditUserPersonalHiscoresLabel'));
		
		
		$this->labelMetaEditStorage->set_markup(i18n::get('meta', 'lbl_storage'));
		$this->labelMetaEditInfoString->set_markup(i18n::get('meta', 'lbl_info'));
		$this->labelMetaEditInfoId->set_markup(i18n::get('meta', 'lbl_infoid'));
		
		$this->media_edit_info->set_text($mdata['md_info']);
		$this->media_edit_info_id->set_text($mdata['md_info_id']);

		$this->metaEditFeatureGoodDumpLabel->set_text(i18n::get('meta', 'lbl_running'));
		if (!$this->obj_running) $this->obj_running = new IndexedCombobox($this->metaEditFeatureGoodDumpDropdown, false, $this->dropdownStateYesNo);
		$this->metaEditFeatureGoodDumpDropdown->set_active($this->set_dropdown_bool($mdata['md_running']));

		$this->metaEditFeatureBugsLabel->set_text(i18n::get('meta', 'lbl_buggy'));
		if (!$this->obj_bugs) $this->obj_bugs = new IndexedCombobox($this->metaEditFeatureBugsDropdown, false, $this->dropdownStateYesNo);
		$this->metaEditFeatureBugsDropdown->set_active($this->set_dropdown_bool($mdata['md_bugs']));

		$this->metaEditFeatureMultiplayLabel->set_text(i18n::get('meta', 'lbl_multiplay'));
		if (!$this->obj_multiplayer) $this->obj_multiplayer = new IndexedCombobox($this->metaEditFeatureMultiplayDropdown, false, $this->dropdownStateCount);
		$this->metaEditFeatureMultiplayDropdown->set_active($this->set_dropdown_bool($mdata['md_multiplayer']));
		
		$this->metaEditFeatureTrainerLabel->set_text(i18n::get('meta', 'lbl_trainer'));
		if (!$this->obj_trainer) $this->obj_trainer = new IndexedCombobox($this->metaEditFeatureTrainerDropdown, false, $this->dropdownStateCount);
		$this->metaEditFeatureTrainerDropdown->set_active($this->set_dropdown_bool($mdata['md_trainer']));

		$this->metaEditFeatureModifiedLabel->set_text(i18n::get('meta', 'lbl_usermod'));
		if (!$this->obj_usermod) $this->obj_usermod = new IndexedCombobox($this->metaEditFeatureModifiedDropdown, false, $this->dropdownStateYesNo);
		$this->metaEditFeatureModifiedDropdown->set_active($this->set_dropdown_bool($mdata['md_usermod']));

		$this->metaEditFeatureNetplayLabel->set_text(i18n::get('meta', 'lbl_netplay'));
		if (!$this->obj_netplay) $this->obj_netplay = new IndexedCombobox($this->metaEditFeatureNetplayDropdown, false, $this->dropdownStateYesNo);
		$this->metaEditFeatureNetplayDropdown->set_active($this->set_dropdown_bool($mdata['md_netplay']));

		$this->metaEditFeatureFreewareLabel->set_text(i18n::get('meta', 'lbl_freeware'));
		if (!$this->obj_freeware) $this->obj_freeware = new IndexedCombobox($this->metaEditFeatureFreewareDropdown, false, $this->dropdownStateYesNo);
		$this->metaEditFeatureFreewareDropdown->set_active($this->set_dropdown_bool($mdata['md_freeware']));

		$this->metaEditFeatureIntroLabel->set_text(i18n::get('meta', 'lbl_intro'));
		if (!$this->obj_intro) $this->obj_intro = new IndexedCombobox($this->metaEditFeatureIntroDropdown, false, $this->dropdownStateYesNo);
		$this->metaEditFeatureIntroDropdown->set_active($this->set_dropdown_bool($mdata['md_intro']));
		
		if (!$this->obj_storage) $this->obj_storage = new IndexedCombobox($this->cb_storage, false, $this->dropdownStorage);
		if ($mdata['md_storage'] === null) $mdata['md_storage'] = 0;
		$this->cb_storage->set_active($mdata['md_storage']);

		if (!$this->obj_region) $this->obj_region = new IndexedCombobox($this->cb_region, false, $this->dropdownRegion);
		if ($mdata['md_region'] === null) $mdata['md_region'] = 0;
		$this->cb_region->set_active($mdata['md_region']);
		
		# media type / current / count
		if (!$this->obj_media_type){
			$this->dropdownMediaType = I18n::translateArray('dropdownMedium', $this->dropdownMediaType);
			$this->obj_media_type = new IndexedCombobox($this->cb_media_type, false, $this->dropdownMediaType);
		}
		if ($mdata['md_media_type'] === null) $mdata['md_media_type'] = 0;
		
		$this->cb_media_type->set_active($mdata['md_media_type']);
		$this->cbe_media_current->set_text($mdata['md_media_current']);
		$this->cbe_media_count->set_text($mdata['md_media_count']);
		
		if (!$this->obj_category) $this->obj_category = FACTORY::get('manager/IndexedCombo')->set($this->cbe_category, $this->media_category, 4);
		FACTORY::get('manager/IndexedCombo')->set_active_key($this->cbe_category, $mdata['md_category']);
		
		// other entries
		$this->cbe_year->set_text($mdata['md_year']);
		$this->cbe_usk->set_text($mdata['md_usk']);
		$this->cbe_creator->set_text($mdata['md_creator']);
		$this->cbe_publisher->set_text($mdata['md_publisher']);
		
		$this->cbe_programmer->set_text($mdata['md_programmer']);
		$this->cbe_musican->set_text($mdata['md_musican']);
		$this->cbe_graphics->set_text($mdata['md_graphics']);

		# set autocompletion
		$autoCompletion = FACTORY::get('manager/AutoCompletion');
		# creator
		$field = 'creator';
		if (!isset($this->comletionData[$field]) || !$this->comletionData[$field]) {
			$this->comletionData[$field] = FACTORY::get('manager/TreeviewData')->getAutoCompleteData($field, false);
			$autoCompletion->connect($this->cbe_creator, $this->comletionData[$field]);
		}
		# publisher
		$field = 'publisher';
		if (!isset($this->comletionData[$field]) || !$this->comletionData[$field]) {
			$this->comletionData[$field] = FACTORY::get('manager/TreeviewData')->getAutoCompleteData($field, false);
			$autoCompletion->connect($this->cbe_publisher, $this->comletionData[$field]);
		}
		# programmer
		$field = 'programmer';
		if (!isset($this->comletionData[$field]) || !$this->comletionData[$field]) {
			$this->comletionData[$field] = FACTORY::get('manager/TreeviewData')->getAutoCompleteData($field, false);
			$autoCompletion->connect($this->cbe_programmer, $this->comletionData[$field]);
		}
		# musican
		$field = 'musican';
		if (!isset($this->comletionData[$field]) || !$this->comletionData[$field]) {
			$this->comletionData[$field] = FACTORY::get('manager/TreeviewData')->getAutoCompleteData($field, false);
			$autoCompletion->connect($this->cbe_musican, $this->comletionData[$field]);
		}
		# graphics
		$field = 'graphics';
		if (!isset($this->comletionData[$field]) || !$this->comletionData[$field]) {
			$this->comletionData[$field] = FACTORY::get('manager/TreeviewData')->getAutoCompleteData($field, false);
			$autoCompletion->connect($this->cbe_graphics, $this->comletionData[$field]);
		}
		
		
		$this->model_languages->foreach(array($this, 'languages_set_selected'), $mdata['md_id']);
		
		// only some rom output!!
		$eccident = ($mdata['md_eccident']) ? $mdata['md_eccident'] : $mdata['fd_eccident'];
		$name = ($mdata['md_name']) ? $mdata['md_name'] : '???';
		$title = basename(MultiByte::convertToUtf8($mdata['path']));
		$infoString = '<b>'.i18n::get('global', 'platform').':</b> '.$eccident.' | <b>'.i18n::get('global', 'crc32').':</b> '.$mdata['crc32'].' | <b>'.i18n::get('global', 'fileNameShort').':</b> '.htmlspecialchars($title).'';
		$this->metaEditFileinfo->set_markup($infoString);
		
		$this->edit_mdata = $mdata;
		
		# now handle user data
		$eccident = ($this->current_media_info['fd_eccident']) ? $this->current_media_info['fd_eccident'] : $this->current_media_info['md_eccident'];
		$crc32 = ($this->current_media_info['crc32']) ? $this->current_media_info['crc32'] : $this->current_media_info['md_crc32'];
		$userData = FACTORY::get('manager/UserData')->getUserdata($eccident, $crc32);
		
		if($userData){
			
			# fill widgets with data
			
			$eccRating = $this->getEccRatingIntegerByPercent((int)$userData['rating']);
			$this->setRatingImage($this->mEditUserRatingSumImage, $eccRating);
			
			$this->mEditUserRatingSum->set_markup('<b>'.i18n::get('global', 'total').': <span color="#00CC00">'.(int)$userData['rating'].'</span> %</b>');
			
			$this->mEditUserRatingFunScale->set_value((int)$userData['rating_fun']);
			$this->mEditUserRatingGameplayScale->set_value((int)$userData['rating_gameplay']);
			$this->mEditUserRatingGraphicsScale->set_value((int)$userData['rating_graphics']);
			$this->mEditUserRatingMusicScale->set_value((int)$userData['rating_music']);
			$this->mEditUserRatingDifficultyScale->set_value((int)$userData['difficulty']);
			$this->mEditUserReviewTitle->set_text($userData['review_title']);

			$textBuffer = new GtkTextBuffer();
			$textBuffer->set_text(trim($userData['review_body']));
			$this->mEditUserReviewBody->set_buffer($textBuffer);
			$this->mEditUserReviewExportAllow->set_active($userData['review_export_allowed']);
			
			$textBuffer = new GtkTextBuffer();
			$textBuffer->set_text(trim($userData['notes']));
			$this->mEditUserPersonalNotes->set_buffer($textBuffer);
			
			$this->mEditUserPersonalHiscores->set_text($userData['hiscore']);
		}
		else{
			
			# clear all entries

			$this->setRatingImage($this->mEditUserRatingSumImage, 0);
			
			$this->mEditUserRatingSum->set_text('');
			$this->mEditUserRatingFunScale->set_value(0);
			$this->mEditUserRatingGameplayScale->set_value(0);
			$this->mEditUserRatingGraphicsScale->set_value(0);
			$this->mEditUserRatingMusicScale->set_value(0);
			$this->mEditUserRatingDifficultyScale->set_value(0);
			
			$this->mEditUserReviewTitle->set_text('');
			$textBuffer = new GtkTextBuffer();
			$textBuffer->set_text('');
			$this->mEditUserReviewBody->set_buffer($textBuffer);
			
			$textBuffer = new GtkTextBuffer();
			$textBuffer->set_text(trim($userData['notes']));
			$this->mEditUserPersonalNotes->set_buffer($textBuffer);
			
			$this->mEditUserPersonalHiscores->set_text('');
		}

		$this->win_media_edit->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		
		$imageObject = FACTORY::get('manager/Image');
		$imageObject->setWidgetBackground($this->win_media_edit, 'background/main.png');
		
		$this->win_media_edit->show();
		
		$this->media_edit_is_opened = true;
		$this->win_media_edit->set_keep_above(true);
		#$this->win_media_edit->present();
		$this->win_media_edit->set_position(Gtk::WIN_POS_CENTER);
		
		unset($mdata);
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
	public function edit_media_save($validate_title=false, $hideWindow = false)
	{
		
		$data['id'] = $this->current_media_info['md_id'];
		$data['crc32'] = ($this->edit_mdata['md_crc32']) ? $this->edit_mdata['md_crc32'] : $this->edit_mdata['crc32'];
		$data['eccident'] = ($this->edit_mdata['md_eccident']) ? strtolower($this->edit_mdata['md_eccident']) : strtolower($this->edit_mdata['fd_eccident']);
		
		# get rating and review data!
		
		$mEditUserReviewBodyBuffer = $this->mEditUserReviewBody->get_buffer();
		$mEditUserReviewBody = $mEditUserReviewBodyBuffer->get_text($mEditUserReviewBodyBuffer->get_start_iter(), $mEditUserReviewBodyBuffer->get_end_iter());

		$mEditUserPersonalNotesBuffer = $this->mEditUserPersonalNotes->get_buffer();
		$mEditUserPersonalNotes = $mEditUserPersonalNotesBuffer->get_text($mEditUserPersonalNotesBuffer->get_start_iter(), $mEditUserPersonalNotesBuffer->get_end_iter());
		
		$rating_fun = $this->mEditUserRatingFunScale->get_value();
		$rating_gameplay = $this->mEditUserRatingGameplayScale->get_value();
		$rating_graphics = $this->mEditUserRatingGraphicsScale->get_value();
		$rating_music = $this->mEditUserRatingMusicScale->get_value();
		$rating = $this->getRating($rating_fun, $rating_gameplay, $rating_graphics, $rating_music);
		$userData = array(
			# general data
			'eccident' => $data['eccident'],
			'crc32' => $data['crc32'],
			# rating
			'rating' => $rating, 
			'rating_fun' => $rating_fun, 
			'rating_gameplay' => $rating_gameplay,
			'rating_graphics' => $rating_graphics,
			'rating_music' => $rating_music,
			'difficulty' => $this->mEditUserRatingDifficultyScale->get_value(),
			# review
			'review_title' => $this->mEditUserReviewTitle->get_text(),
			'review_body' => $mEditUserReviewBody,
			'review_export_allowed' => (int)$this->mEditUserReviewExportAllow->get_active(),
			# personal tab
			'hiscore' => $this->mEditUserPersonalHiscores->get_text(),
			'notes' => $mEditUserPersonalNotes,
		);
		
		FACTORY::get('manager/UserData')->updateFullUserData($userData);
		
		$path = ($this->edit_mdata['path_pack']) ? $this->edit_mdata['path_pack'] : $this->edit_mdata['path'];
		$data['extension'] = ".".$this->get_ext_form_file($path);
		
		// ; is not allowed in user input and will removed now
		$data['name'] = trim(str_replace(";", "", $this->media_edit_title->get_text()));
		
		
		if (!trim($data['name'])) {
			$label = i18n::get('global', 'title').'*';
			$error = i18n::get('global', 'error_title');
			$this->medit_lbl_title->set_markup("<span foreground='#aa0000'><b>".$label." (".$error.")</b></span>");
			
			$errorString = i18n::get('metaEdit', 'mEditUserMissingTitle');
			$this->media_edit_help->set_markup("<span foreground='#aa0000'><b>$errorString</b></span>");
			$this->mediaEditTabMeta->set_markup('<b><span foreground="#aa0000">'.i18n::get('metaEdit', 'mediaEditTabMeta').'</span></b>');
			$this->media_edit_help->set_visible(true);
			return false;
		}
		
		$data['info'] = trim(str_replace(";", "", $this->media_edit_info->get_text()));
		$data['info_id'] = trim(str_replace(";", "", $this->media_edit_info_id->get_text()));

		$data['media_type'] = $this->cb_media_type->get_active();
		$data['media_current'] = $this->cbe_media_current->get_text();
		$data['media_count'] = $this->cbe_media_count->get_text();
		
		
//		if(!$data['media_current'] && !$data['media_count']){
//			$data['media_current'] = 1;
//			$data['media_count'] = 1;
//		}
		
		if (
			($data['media_count'] && $data['media_current'] > $data['media_count'])
			|| ($data['media_current'] && !$data['media_count'])
			|| (!$data['media_current'] && $data['media_count'])
		) {
			$label = i18n::get('meta', 'lbl_medium').'*';
			$error = i18n::get('global', 'error_title');
			$this->labelMetaEditMedium->set_markup("<span foreground='#aa0000'><b>".$label." (".$error.")</b></span>");
			$errorString = i18n::get('metaEdit', 'mEditMediaCountsWrong');
			$this->media_edit_help->set_markup("<span foreground='#aa0000'><b>$errorString</b></span>");
			$this->mediaEditTabMeta->set_markup('<b><span foreground="#aa0000">'.i18n::get('metaEdit', 'mediaEditTabMeta').'</span></b>');
			$this->media_edit_help->set_visible(true);
			return false;
		}

		if($data['media_type'] == 0) $data['media_type'] = "NULL";
		if($data['media_current'] == '') $data['media_current'] = "NULL";
		if($data['media_count'] == '') $data['media_count'] = "NULL";
		
		$data['year'] = trim(str_replace(";", "", $this->cbe_year->get_text()));
		$data['usk'] = trim(str_replace(";", "", $this->cbe_usk->get_text()));
		$data['creator'] = trim(str_replace(";", "", $this->cbe_creator->get_text()));
		$data['publisher'] = trim(str_replace(";", "", $this->cbe_publisher->get_text()));
		$data['programmer'] = trim(str_replace(";", "", $this->cbe_programmer->get_text()));
		$data['musican'] = trim(str_replace(";", "", $this->cbe_musican->get_text()));
		$data['graphics'] = trim(str_replace(";", "", $this->cbe_graphics->get_text()));
		
		$data['running'] = $this->get_dropdown_bool($this->metaEditFeatureGoodDumpDropdown->get_active());
		$data['bugs'] = $this->get_dropdown_bool($this->metaEditFeatureBugsDropdown->get_active());
		$data['multiplayer'] = $this->get_dropdown_bool($this->metaEditFeatureMultiplayDropdown->get_active());
		$data['netplay'] = $this->get_dropdown_bool($this->metaEditFeatureNetplayDropdown->get_active());		
		$data['trainer'] = $this->get_dropdown_bool($this->metaEditFeatureTrainerDropdown->get_active());
		$data['freeware'] = $this->get_dropdown_bool($this->metaEditFeatureFreewareDropdown->get_active());
		$data['usermod'] = $this->get_dropdown_bool($this->metaEditFeatureModifiedDropdown->get_active());
		$data['intro'] = $this->get_dropdown_bool($this->metaEditFeatureIntroDropdown->get_active());

		$data['storage'] = $this->cb_storage->get_active();
		$data['region'] = $this->cb_region->get_active();
		
		$data['category'] = FACTORY::get('manager/IndexedCombo')->getKey($this->cbe_category);
		
		$this->languages_get_selected_array = array();
		$this->model_languages->foreach(array($this, 'languages_get_selected'));
		$data['languages'] = $this->languages_get_selected_array; 
		
		// convert incoming userdata to utf-8
		$data['name'] = MultiByte::convertToUtf8($data['name']);
		$data['creator'] = MultiByte::convertToUtf8($data['creator']);
		$data['publisher'] = MultiByte::convertToUtf8($data['publisher']);
		$data['programmer'] = MultiByte::convertToUtf8($data['programmer']);
		$data['musican'] = MultiByte::convertToUtf8($data['musican']);
		$data['graphics'] = MultiByte::convertToUtf8($data['graphics']);
		$data['year'] = MultiByte::convertToUtf8($data['year']);
		$data['usk'] = MultiByte::convertToUtf8($data['usk']);
		
		#if ($data['name']) {
			
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
			'md_publisher' => $data['publisher'],
			'md_programmer' => $data['programmer'],
			'md_musican' => $data['musican'],
			'md_graphics' => $data['graphics'],
			'md_media_type' => $data['media_type'],
			'md_media_current' => $data['media_current'],
			'md_media_count' => $data['media_count'],
			'md_storage' => $data['storage'],
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
		$this->media_edit_help->set_visible(true);
					
		#}
		// Set new composite id
		// for updated media_edit data		
		$this->current_media_info['composite_id'] = $this->current_media_info['id']."|".$this->current_media_info['md_id'];
		//$this->onReloadRecord();

		# save rating from selection
		$eccRating = $this->getEccRatingIntegerByPercent((int)$userData['rating']);
		$this->_fileView->addRatingByMdataId($this->current_media_info['md_id'], $eccRating);
		
		$this->directMediaEdit = true;
		$this->show_media_info();
		$this->onReloadRecord(false);
		$this->directMediaEdit = false;	
		
		$this->comletionData = array();
		
		if ($hideWindow) $this->media_edit_hide();
		
	}

	public function media_edit_hide() {
		$this->win_media_edit->hide();
		$this->media_edit_is_opened = false;
		return true;
	}
	
	public function hideWindow($widget){
		$widget->hide();
		return true;
	}
	
	/*
	*
	*/
	public function init_treeview_main($resetMode = false)
	{
		// 20060108 hack for simle mediaview
		if ($this->optVisMainListMode) {
			$this->initTreeviewMainSimple();
		}
		else {
			$this->initTreeviewMainDetail();
		}
		
		return true; 
	}
	
	public function initTreeviewMainDetail() {
		
		$pp = $this->ini->getKey('USER_SWITCHES', 'show_media_pp');
		if ($pp) $this->_results_per_page = $pp;
		
		// main model
		$this->model = new GtkListStore(GObject::TYPE_OBJECT, GObject::TYPE_OBJECT, GObject::TYPE_STRING, GObject::TYPE_STRING, GObject::TYPE_STRING, GObject::TYPE_STRING, GObject::TYPE_OBJECT);
		
		// INIT $pixbufRenderer
		$pixbufRenderer = new GtkCellRendererPixbuf();
		
		// INIT $textRenderer
		$textRenderer = new GtkCellRendererText();
		
		$textRendererDetail = new GtkCellRendererText();

		# DISABLED FOR JAPANESE TEST
		if (i18n::getLanguageIdent() != 'jp') {
			$textRenderer->set_property('font',  $this->treeviewFontType);
			$textRendererDetail->set_property('font',  $this->treeviewFontType);
		}

		$textRenderer->set_property("yalign", 0);
		$textRenderer->set_property('foreground', $this->treeviewFgColor);
		
		$column_0 = new GtkTreeViewColumn('IMAGE', $pixbufRenderer, 'pixbuf', 0);
		$column_0->set_expand(false);
		
		$column_1 = new GtkTreeViewColumn('IMAGE', $pixbufRenderer, 'pixbuf', 1);
		$column_1->set_expand(false);

		$cPixbufRating = new GtkTreeViewColumn('IMAGE', $pixbufRenderer, 'pixbuf', 6);
		$cPixbufRating->set_expand(false);
		$cPixbufRating->set_cell_data_func($pixbufRenderer, array($this, "format_col_front"));

		
		$column_2 = new GtkTreeViewColumn('TITLE', $textRendererDetail, 'text', 2);
		#$column_2->set_cell_data_func($textRenderer, array($this, "format_col"));
		$column_2->set_sizing(Gtk::TREE_VIEW_COLUMN_FIXED);
		$column_2->set_cell_data_func($textRendererDetail, array($this, "formatCellDetail"), 2);
		
		// hidden file-id
		$col_file_id = new GtkTreeViewColumn('ID', $textRenderer, 'text', 3);
		$col_file_id->set_visible(false);
		
		// hidden mdata-id
		$col_mdata_id = new GtkTreeViewColumn('MDATA_ID', $textRenderer, 'text', 4);
		$col_mdata_id->set_visible(false);
		
		// hidden mdata-id
		$col_composite_id = new GtkTreeViewColumn('COMPOSITE_ID', $textRenderer, 'text', 5);
		$col_composite_id->set_visible(false);
		
		$treeView = FACTORY::get('manager/Treeview');
		
		$test = $treeView->setModel($this->model);
		
		$test->set_headers_visible(false);
		$this->setModifiedTreeviewColors($test);
		
		$test->append_column($column_0);
		$test->append_column($cPixbufRating);
		$test->append_column($column_1);
		$test->append_column($column_2);
		$test->append_column($col_file_id);
		$test->append_column($col_mdata_id);
		$test->append_column($col_composite_id);
			
	}
	
	public function on_tooltip($widget, $x, $y, $keyboard_mode, $tooltip) {
		$path = $widget->get_path_at_pos($x, $y); // note 2
		if ($path==null) return false;
	
		$col_title = $path[1]->get_title(); // note 3
		$path2 = $path[0][0]-1;
		if ($path2<0) return false; // note 4

		$widget->set_tooltip_cell($tooltip, $path2, $path[1], null); // note 5
		$text = "this is <i>treeview tooltip!</i> <b>path=$path2</b> col=$col_title";
	    
	    $tooltip->set_markup($text); // note 6
		return true;
	}
	
	public function formatCellDetail($column, $cell, $model, $iter, $colNum){

		$path = $model->get_path($iter);
		$row_num = $path[0];
		$row_color = ($row_num%2==1) ? $this->treeviewBgColor1 : $this->treeviewBgColor2;
		$cell->set_property('cell-background', $row_color);
		
		$value = htmlspecialchars($model->get_value($iter, $colNum));
		
		$split = explode("\n", $value);
		$split[0] = '<b>'.$split[0].'</b>';
		$value = join("\n", $split);
		
		$cell->set_property('markup', $value);
	}
	
	
	// 20060108 hack for simle mediaview
	public function initTreeviewMainSimple()
	{
		$pp = $this->ini->getKey('USER_SWITCHES', 'media_perpage_list');
		$this->_results_per_page = ($pp) ? $pp : 100;
		
		// main model

		# repository fix
		$this->model = new GtkListStore(
			GObject::TYPE_STRING,
			GObject::TYPE_OBJECT,
			GObject::TYPE_OBJECT,
			GObject::TYPE_STRING,
			GObject::TYPE_STRING,
			GObject::TYPE_STRING,
			GObject::TYPE_STRING,
			GObject::TYPE_STRING,
			GObject::TYPE_STRING,
			GObject::TYPE_STRING
		);
		
		$rendererText = new GtkCellRendererText();
		$rendererText->set_property("yalign", 0);
		$rendererText->set_property('foreground', $this->treeviewFgColor);

# DISABLED FOR JAPANESE TEST
if (i18n::getLanguageIdent() != 'jp') {
	$rendererText->set_property('font', $this->treeviewFontType);
	#$textRenderer->set_property('font',  $this->treeviewFontType);
}

		$labelState = '';
		$rendererTextState = new GtkCellRendererText();
		$col0 = new GtkTreeViewColumn($labelState, $rendererTextState, 'text', 0);
		$col0->set_sizing(Gtk::TREE_VIEW_COLUMN_FIXED);
		$col0->set_fixed_width(3);
		$col0->set_sort_column_id(0);
		$col0->set_cell_data_func($rendererTextState, array($this, 'updateCellMetaState'), 0);

		$pixbufRenderer = new GtkCellRendererPixbuf();
		$col9 = new GtkTreeViewColumn('', $pixbufRenderer, 'pixbuf', 2);
		$col9->set_expand(false);
		
		$pixbufRenderer = new GtkCellRendererPixbuf();
		$col1 = new GtkTreeViewColumn('', $pixbufRenderer, 'pixbuf', 1);
		$col1->set_expand(false);
		
//		$col1 = new GtkTreeViewColumn('', $rendererText, 'text', 2);
//		$col1->set_sort_indicator(false);
//		$col1->set_sort_column_id(2);
//		$col1->set_sizing(Gtk::TREE_VIEW_COLUMN_AUTOSIZE);
		
		$col3 = new GtkTreeViewColumn('fileId', $rendererText, 'text', 3);
		$col3->set_visible(false);
		
		$col4 = new GtkTreeViewColumn('metaId', $rendererText, 'text', 4);
		$col4->set_visible(false);
		
		$col5 = new GtkTreeViewColumn('combinedId', $rendererText, 'text', 5);
		$col5->set_visible(false);
		
		#$rendererTextState = new GtkCellRendererText();
		$labelTitle = I18N::get('mainList', 'simpleHeadTitle');
		$col2 = new GtkTreeViewColumn($labelTitle, $rendererText, 'text', 6);
		$col2->set_resizable(true);
		$col2->set_sizing(Gtk::TREE_VIEW_COLUMN_GROW_ONLY);
		$col2->set_sort_indicator(false);
		$col2->set_sort_column_id(6);
		#$col2->set_cell_data_func($rendererText, array($this, 'updateCellMetaState'), 6);
		
		$labelYear = I18N::get('mainList', 'simpleHeadYear');
		$col6 = new GtkTreeViewColumn($labelYear, $rendererText, 'text', 7);
		$col6->set_sort_indicator(false);
		$col6->set_sort_column_id(7);
		
		$labelCategory = I18N::get('mainList', 'simpleHeadCategory');
		$col7 = new GtkTreeViewColumn($labelCategory, $rendererText, 'text', 8);
		$col7->set_sort_indicator(false);
		$col7->set_sort_column_id(8);
		
		$labelDeveloper = I18N::get('mainList', 'simpleHeadDeveloper');
		$col8 = new GtkTreeViewColumn($labelDeveloper, $rendererText, 'text', 9);
		$col8->set_sort_indicator(false);
		$col8->set_sort_column_id(9);
		
		$col8->set_cell_data_func($rendererText, array($this, 'updateCellMetaState'), false);
		#$col8->set_cell_data_func($rendererText, array($this, "format_col"));
		
		$treeView = FACTORY::get('manager/Treeview');
		$test = $treeView->setModel($this->model);
		
		$this->setModifiedTreeviewColors($test);
		
		$test->append_column($col0);
		$test->append_column($col1);
		$test->append_column($col9);
		$test->append_column($col3);
		$test->append_column($col4);
		$test->append_column($col5);
		$test->append_column($col2);
		$test->append_column($col6);
		$test->append_column($col7);
		$test->append_column($col8);
		
//		$test->set_property('has-tooltip', true); // note 1
//	    $test->connect('query-tooltip', array($this, 'on_tooltip')); // note 1
		
	}
	
	// 20060108 hack for simle mediaview
	public function fillMediaListSimple($file_list) {
		
		if ($file_list['count']!=0) {
			
			$tree = FACTORY::get('manager/Treeview')->getTreeView();
			$tree->freeze_child_notify();
			
			#while (gtk::events_pending()) gtk::main_iteration();
			
			foreach ($file_list['data'] as $id => $data) {
				
				$eccident = ($data['fd_eccident']) ? $data['fd_eccident'] : $data['md_eccident'];
				$eccident = strtolower($eccident);
				if (!$eccident) $eccident = '?';
				
				$path = MultiByte::convertToUtf8($data['path']);
				
				$media_name = basename($path);
				$media_name = ($data['md_name']) ? $data['md_name'] : $media_name;
				
				if($data['md_media_current'] && $data['md_media_count']){
					$media_name .= ' (';
					//if($data['md_media_type']) $media_name .= $this->dropdownMediaType[(int)$data['md_media_type']].' ';
					$media_name .= (int)$data['md_media_current'].'/'.(int)$data['md_media_count'];
					$media_name .= ')';
				}
				
				
				$year = ($data['md_year']) ? $data['md_year'] : '';
				$category = @$this->media_category[$data['md_category']];
				$developer = (isset($data['md_creator'])) ? $data['md_creator'] : '';
				
				# implement caching!
				$filename = FACTORY::get('manager/GuiRomAudit', $this)->getAuditStateIconFilename(
					$data['fa_fDataId'],
					$data['fd_isMultiFile'],
					$data['fa_isMatch'],
					$data['fa_isValidMergedSet'],	
					$data['fa_isValidNonMergedSet'],
					$data['fa_isValidSplitSet'],
					$data['fa_cloneOf'],
					$data['id']
				);
				if (!isset($auditStateIcon[$filename])) $auditStateIcon[$filename] = $this->oHelper->getPixbuf($filename);
				
				$state = ($data['id']) ? '.' : '';
				
				$item = array();
				$item[] = $state;
				$item[] = $this->getCachedPixbuff('images/eccsys/platform/', 'ecc_'.$eccident.'_nav.png', 'ecc_unknown_nav.gif');
				$item[] = $auditStateIcon[$filename];
				$item[] = $data['id'];
				$item[] = $data['md_id'];
				$item[] = $id;
				$item[] = $media_name;
				$item[] = $year;
				$item[] = $category;
				$item[] = $developer;
				$this->model->append($item);
			}
			$tree->thaw_child_notify();
		}
	}
	
	public function updateCellMetaState($column, $cell, $model, $iter, $colNumber){

		$path = $model->get_path($iter);
		$row_num = $path[0];
		$row_color = ($row_num%2==1) ? $this->treeviewBgColor1 : $this->treeviewBgColor2;
		$cell->set_property('cell-background', $row_color);
		
		$value = $model->get_value($iter, $colNumber);
		if ($colNumber === 0){
	        $color = ($value) ? '#00BB00' : '#BB0000';
	        $cell->set_property('cell-background', $color);
	        $cell->set_property('foreground', $color);
		}
		elseif ($colNumber === 2){
			if (!$model->get_value($iter, 0)){
				$cell->set_property('markup', '<span color="#777777">'.htmlspecialchars($value).'</span>');
			}

		}
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
		$this->model_languages = new GtkListStore(GObject::TYPE_BOOLEAN, GObject::TYPE_STRING, GObject::TYPE_OBJECT, GObject::TYPE_STRING, GObject::TYPE_OBJECT, GObject::TYPE_OBJECT);
		
		$pixbufRenderer = new GtkCellRendererPixbuf();
		
		$textRenderer = new GtkCellRendererText();
		$textRenderer->set_property('height', 20);

# DISABLED FOR JAPANESE TEST
if (i18n::getLanguageIdent() != 'jp') $textRenderer->set_property('font',  $this->treeviewFontType);

		$textRenderer->set_property("yalign",0);
		$textRenderer->set_property('foreground', '#ffffff');
		$textRenderer->set_property('cell-background', '#394D59');
		
		$renderer = new GtkCellRendererToggle();
		$column = new GtkTreeViewColumn('', $renderer, 'active',0);

		$column_0 = new GtkTreeViewColumn('', $textRenderer, 'text',1);
		$column_0->set_visible(false);
		
		$column_1 = new GtkTreeViewColumn('', $pixbufRenderer, 'pixbuf',2);
		
		$column_2 = new GtkTreeViewColumn('CATEGORY', $textRenderer, 'text', 3);
		
		$column_3 = new GtkTreeViewColumn('', $pixbufRenderer, 'pixbuf',4);
		$column_3->set_visible(false);
		
		$column_tmp = new GtkTreeViewColumn('', $pixbufRenderer, 'pixbuf',5);
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
			
			/// acive image
			// status active
			$path_a = $base_path.'ecc_lang_'.strtolower($id).'.png';
			if (!file_exists($path_a)) $path_a =  $base_path.'ecc_lang_unknown.png';
			$pixbuf_icon_active = $this->oHelper->getPixbuf($path_a);

			// inacive image
			// status inactive
			$path_i =  $base_path.'ecc_lang_'.strtolower($id).'_i.png';
			if (!file_exists($path_i)) $path_i =  $base_path.'ecc_lang_unknown_i.png';
			$pixbuf_icon_inactive = $this->oHelper->getPixbuf($path_i);

			/// current image
			$obj_icon_current = $pixbuf_icon_inactive;
			$this->model_languages->append(array(false, $id, $obj_icon_current, $label, $pixbuf_icon_active, $pixbuf_icon_inactive));
		}
		
		$test = $treeview->get_selection(); 
		$test->set_mode(Gtk::SELECTION_BROWSE);
		
		$test->connect('changed', array($this, 'get_toggle_status'));
	}
	
	/*
	*
	*/
	public function init_treeview_nav() {
		
		$this->model_navigation = new GtkListStore(GObject::TYPE_OBJECT, GObject::TYPE_STRING, GObject::TYPE_STRING, GObject::TYPE_STRING, GObject::TYPE_STRING);
		
		$pixbufRenderer = new GtkCellRendererPixbuf();
		
		$textRenderer = new GtkCellRendererText();

# DISABLED FOR JAPANESE TEST
if (i18n::getLanguageIdent() != 'jp') $textRenderer->set_property('font',  $this->treeviewFontType);

		$textRenderer->set_property('foreground', $this->treeviewFgColor);		
		
		$column_0 = new GtkTreeViewColumn('Image', $pixbufRenderer, 'pixbuf',0);
		$column_0->set_cell_data_func($pixbufRenderer, array($this, "format_col"));
		
		$column_1 = new GtkTreeViewColumn('ID', $textRenderer, 'text',1);
		$column_1->set_visible(false);
		
		$column_2 = new GtkTreeViewColumn('CATEGORY', $textRenderer, 'text', 2);
		$column_2->set_cell_data_func($textRenderer, array($this, "format_col"));
		
		$column_3 = new GtkTreeViewColumn('TYPE', $textRenderer, 'text',3);
		$column_3->set_visible(false);
		
		$column_count = new GtkTreeViewColumn('TITLE_SIMPLE', $textRenderer, 'text', 4);
		$column_count->set_visible(false);
		
		$this->treeview1->set_model($this->model_navigation);
		
		# change colors to user-selected values
		$this->setModifiedTreeviewColors($this->treeview1);
		
		$this->treeview1->append_column($column_0);
		$this->treeview1->append_column($column_1);
		$this->treeview1->append_column($column_2);
		$this->treeview1->append_column($column_3);
		$this->treeview1->append_column($column_count);
		
		# 20070310 - removed double call!
		$this->updateBreak = true;
		$this->update_treeview_nav(true);
	}
	
	public function setModifiedTreeviewColors($treeview1) {
		//$this->treeview1->modify_base(Gtk::STATE_NORMAL, GdkColor::parse('#445566'));
		$treeview1->modify_base(Gtk::STATE_NORMAL, GdkColor::parse($this->treeviewBgColor));
		$treeview1->modify_base(Gtk::STATE_SELECTED, GdkColor::parse($this->treeviewSelectedBgColor));
		$treeview1->modify_base(Gtk::STATE_ACTIVE, GdkColor::parse($this->treeviewSelectedBgColor));
		
		$treeview1->modify_text(Gtk::STATE_NORMAL, GdkColor::parse($this->treeviewFgColor));
		$treeview1->modify_text(Gtk::STATE_SELECTED, GdkColor::parse($this->treeviewSelectedFgColor));
		$treeview1->modify_text(Gtk::STATE_ACTIVE, GdkColor::parse($this->treeviewSelectedFgColor));
	}
	
	
	// self-defined function to display alternate row color
	function format_col($column, $cell, $model, $iter) {
		$path = $model->get_path($iter); // get the current path
		$row_num = $path[0]; // note 2
		$row_color = ($row_num%2==1) ? $this->treeviewBgColor1 : $this->treeviewBgColor2; // sets the row color for odd and even rows
		$cell->set_property('cell-background', $row_color); // sets the background color!
	} 

	function format_col_front($column, $cell, $model, $iter) {
		$cell->set_property('cell-background', $this->treeviewBgColorImages); // sets the background color!
	}
	
	
	
	
	public function update_treeview_nav($updateCategories=true)
	{
		
		$model = $this->model_navigation;
		$model->clear();

		//$nav_data = $this->ini->getPlatformNavigation(false);
		$nav_data = $this->ini->getPlatformNavigation(false, $this->currentPlatformCategory);
		
		$eccIdentForCategories = array();
		
		$sqlLike = $this->createSearchSqlLike();
		$platformCounts = $this->_fileView->getNavPlatformCounts(array_keys($nav_data), $this->toggle_show_doublettes, $this->_search_language, $this->_search_category, $this->ext_search_selected, $this->toggle_show_metaless_roms_only, $sqlLike, $this->toggle_show_files_only, $updateCategories);
		
		$overallCount = 0;
		foreach ($nav_data as $eccident => $title) {
			if ($eccident && strtolower($eccident) != 'null' && @$platformCounts[$eccident]) $overallCount += $platformCounts[$eccident];
		}

		foreach ($nav_data as $eccident => $title) {
			// ini-file does not support false. null from ini-file
			// is translated to false.
			if (strtolower($eccident) == 'null') $eccident = false;
			if ($this->_eccident===false) $this->_eccident = $eccident;

			if ($eccident === false) $media_count = $overallCount;
			else $media_count = (isset($platformCounts[$eccident])) ? $platformCounts[$eccident] : 0;
			
			if ($media_count == 0 && $this->nav_inactive_hidden && $eccident) continue;

			$eccIdentForCategories[] = $eccident;
			$title_and_count = $title." (".$media_count.")";
			
			$model->append(array($this->getCachedPixbuff('images/eccsys/platform/', 'ecc_'.$eccident.'_nav.png', 'ecc_unknown_nav.png'), $eccident, $title_and_count, $eccident, $title));
		}
		
		if ($eccIdentForCategories && $updateCategories) Gtk::timeout_add(500, array($this, 'updateCategorieDropdown'), $eccIdentForCategories, $this->currentPlatformCategory);
	}
	
	public $cachedSystemPixbuff = array();
	private function &getCachedPixbuff($imagepath, $imageFile, $imageFileError){
		$imageFullPath = $imagepath.$imageFile;
		
		if(isset($this->cachedSystemPixbuff[$imageFullPath])){
			#print "getCachedPixbuff $imageFullPath\n";
			return $this->cachedSystemPixbuff[$imageFullPath];
		}

		$basepath = dirname(__FILE__);
		$img_path = $basepath."/".$imageFullPath;
		if (!file_exists($img_path)) $img_path = $basepath."/".$imagepath.$imageFileError;
		$this->cachedSystemPixbuff[$imageFullPath] = $this->oHelper->getPixbuf($img_path);
		return $this->cachedSystemPixbuff[$imageFullPath];
	}
	

			
	public function updateCategorieDropdown($eccIdentForCategories, $currentCat=false) {
		
		while (gtk::events_pending()) gtk::main_iteration();
		$availableCategories = $this->ini->getPlatformCategories($eccIdentForCategories);
		if ($currentCat && !isset($availableCategories[$currentCat])) {
			$this->updateBreak = true;
			$this->update_treeview_nav(false);
			$this->updateBreak = false;
		}
		else {
			// todo hack for problems with clear in indexcombobox
			// on update, the changed is automaticlly emitted!
			$this->updateBreak = true;
			//$this->dd_pf_categories = new IndexedCombobox($this->cbPlatformCategories, false, $availableCategories);
			
			$pos = 0;
			$activeCategoryId = 0;
			foreach ($availableCategories as $category => $categoryName) {
				if ($category == $currentCat) {
					$activeCategoryId = $pos;
					break;
				}
				$pos++;
			}
			$this->dd_pf_categories->fill($availableCategories, $activeCategoryId);
			//$this->cbPlatformCategories->set_active($activeCategoryId);
			$this->updateBreak = false;
		}
	}
	
	
	public function changePlatformCategory($obj) {
		$this->currentPlatformCategory = $obj->get_active_text();
		if (!$this->updateBreak) $this->update_treeview_nav(false);
		$this->updateBreak = false;
	}
	
	/*
	*
	*/
	public function get_treeview_nav_selection($obj)
	{
		$this->nb_main_page_selected = $this->nb_main->get_current_page();
		list($model, $iter) = $obj->get_selected();
		
		if ($iter) {
			
			$this->mTopViewOnlyRoms->set_active(true);
			
			// read last selected platform from history
			$path = $model->get_path($iter);
			$this->ini->storeHistoryKey('navigation_last_index', current($path), false);
			
			// for twmain_data_dispatcher
			#$this->view_mode = 'MEDIA';
			
			$eccident = $model->get_value($iter, 3);
			
			# update break!!!!!
			# dont reset view, if allready selected this platorm!
			if (!is_null($this->selectedEccidentBreak) && $this->selectedEccidentBreak == $eccident) return false;
			else $this->selectedEccidentBreak = $eccident;

			$this->setEccident($eccident);
			$this->setPlatformName($model->get_value($iter, 4));
			
			$platform_name = $this->ecc_platform_name;
			$eccident = $this->_eccident;
			
			$txt = '<b>'.htmlspecialchars($platform_name).'</b>';
			$this->nb_main_lbl_media->set_markup($txt);
			
			$this->set_notebook_page_visiblility($this->nb_main, 0, true); // media
			$this->set_notebook_page_visiblility($this->nb_main, 1, $this->view_mode); // factsheet
			$this->set_notebook_page_visiblility($this->nb_main, 2, true); // help
			
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
	
	public function show_nb_ecc_configuration($page) {
		if (!$page) $page = 'ECC';
		$this->openGuiConfig($page);
	}
	
	
	public function update_platform_info($eccident) {
		$pini = $this->ini->getPlatformInfo($eccident);
		
		$name = (isset($pini['GENERAL']['name'])) ? $pini['GENERAL']['name'] : '';
		$txt = '<b>'.@htmlspecialchars($name).'</b>';
		$this->pf_info_name->set_markup($txt);

		$manufacturer = (isset($pini['GENERAL']['manufacturer'])) ? $pini['GENERAL']['manufacturer'] : '';
		$this->pf_info_manufacturer->set_text($manufacturer);
		
		$year_start = (isset($pini['GENERAL']['year_start'])) ? $pini['GENERAL']['year_start'] : '????';
		$year_end = (isset($pini['GENERAL']['year_end'])) ? $pini['GENERAL']['year_end'] : '????';
		$year_range = $year_start." - ".$year_end;
		$this->pf_info_year->set_text($year_range);
		
		$type = (isset($pini['GENERAL']['type'])) ? $pini['GENERAL']['type'] : '';
		$this->pf_info_type->set_text($type);
		
		$text_desc = (isset($pini['GENERAL']['description'])) ? $pini['GENERAL']['description'] : '';
		$buffer = new GtkTextBuffer();
		$buffer->set_text(trim($text_desc));
		$this->pf_info_description->set_buffer($buffer);

# DISABLED FOR JAPANESE TEST
if (i18n::getLanguageIdent() != 'jp') $this->pf_info_description->modify_font(new PangoFontDescription($this->os_env['FONT']." 10"));

		$this->pf_info_description->set_wrap_mode(Gtk::WRAP_WORD);

		$text_res = (isset($pini['RESOURCES']['web'])) ? $pini['RESOURCES']['web'] : '';
		$buffer = new GtkTextBuffer();
		$buffer->set_text(trim($text_res));
		$this->pf_info_resources->set_buffer($buffer);

# DISABLED FOR JAPANESE TEST
if (i18n::getLanguageIdent() != 'jp') $this->pf_info_resources->modify_font(new PangoFontDescription($this->os_env['FONT']." 10"));

		$this->pf_info_resources->set_wrap_mode(Gtk::WRAP_WORD);
		
	}
	
	/**
	*
	*/
	public function setEccident($extension=false, $reload=true)
	{
		$this->_eccident = $extension;
		$this->search_order_asc1->set_active(true);
		
		$this->img_plattform->set_from_pixbuf($this->getCachedPixbuff('images/eccsys/platform/', 'ecc_'.strtolower($extension).'_teaser.png', 'ecc_unknown_teaser.png'));
		
		if ($reload===true) $this->onInitialRecord(true);
		$this->updateMenuBar();
	}
	
	
	private function updateMenuBar() {
		
		$state = false;
		#$platformName = '';
		if($this->_eccident){
			$state = true;
			#$platformName = $this->ini->getPlatformNavigation($this->_eccident);	
		}
		# rem
		$platformName = $this->ini->getPlatformName($this->_eccident);
		
		// Only works, if a eccident is selected!
		$this->mTopEmuConfig->set_sensitive($state);
		$this->mTopDatImportRc->set_sensitive($state);
		$this->mTopDatImportCtrlMAME->set_sensitive($state);
		#$this->mTopDatImportOnlineRomdb->set_sensitive($state);

		$isMultiRomPlatform = $this->ini->isMultiRomPlatform($this->_eccident);
		$this->mTopRomAuditShow->set_sensitive($isMultiRomPlatform);
		
# 20070628 deactivated
# $this->menubar_filesys_organize_roms_preview->set_sensitive($state);
# $this->menubar_filesys_organize_roms->set_sensitive($state);

		# ROM
		
		$this->menuTopRomAddNewRom->set_sensitive($state);
		if ($state) $this->menuTopRomAddNewRom->get_child()->set_text(sprintf(I18N::get('menuTop', 'romAddNewRom%s'), $platformName));
		else $this->menuTopRomAddNewRom->get_child()->set_text(I18N::get('menuTop', 'romAddNewRomUnselected'));
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
			array_pop($split);
			return join(".", $split);
		}
		return "";
	}
	
	public function updateCategoryDropdown($categories){
		if (!count($categories)) return false;
		$cats = array();
		$countAll = 0;
		foreach($categories as $id => $count){
			$countAll += $count;
			if (!$id) continue;
			$cats[(int)$id] = $this->media_category[(int)$id].' ('.$count.')';
		}
		natsort($cats);
		$cats = array('ALL ('.$countAll.')') + $cats;
		
		$this->cb_search_category->clear();
		$combo = FACTORY::get('manager/IndexedCombo')->set($this->cb_search_category, $cats, 4);
	}
	
	public function updateMameDriverDropdown(){
		
		#return false;
		
		$reset = false;
		
		$eccident = $this->_eccident;
		if ($eccident != 'mame') {
			$reset = true;
			$this->cb_search_mameDriver->set_visible(false);
			$this->_fileView->setSearchMameDriver(false);
			return false;
		}

		$availableMameDrivers = $this->_fileView->getMameDriver($this->get_search_state(), $reset);
		
		if (count($availableMameDrivers) <= 2 && !$this->_fileView->getSearchMameDriver() && !$this->get_search_state()) {
			$this->cb_search_mameDriver->set_visible(false);
			$this->_fileView->setSearchMameDriver(false);
			return false;
		}

		$this->cb_search_mameDriver->set_visible(true);
		$this->cb_search_mameDriver->clear();
		
		# get mame driver to realname translation
		$translate = $this->ini->getDriverTranslation('mame');
		$driverNames = $translate['names'];
		$driverUnset = $translate['unset'];
		
		# unset drivers used for multi driver queries
		foreach($driverUnset as $unset) {
			unset($availableMameDrivers[$unset]);
		}
		
		# now try to translate some entries
		$mameDriver = array();
		foreach($availableMameDrivers as $driverName => $driverCount) {
			if (!$driverName) continue;
			if (isset($driverNames[$driverName])){
				$mameDriver[$driverName] = $driverNames[$driverName].' ('.$driverCount.')';
				unset($availableMameDrivers[$driverName]);
			}
			else $availableMameDrivers[$driverName] = $driverName.' ('.$driverCount.')';
		}
		asort($mameDriver);

		if ($this->get_search_state()) {
			$mameDriver = array('' => 'ALL DRIVER') + $mameDriver + $availableMameDrivers;	
		}
		else {
			$mameDriver = array('' => 'ALL DRIVER') + $mameDriver;	
		}
		
		$combo = FACTORY::get('manager/IndexedCombo')->set($this->cb_search_mameDriver, $mameDriver, 1);
		$this->_fileView->setSearchMameDriver(false);
	}
	
	public function setSearchMameDriver($combo) {
		$key = FACTORY::get('manager/IndexedCombo')->getKey($combo);
		$value = FACTORY::get('manager/IndexedCombo')->getValue($combo);
		
		# get mame driver to realname translation
		$translate = $this->ini->getDriverTranslation('mame');
		
		if (!$key) $selectedQuery = '';
		else $selectedQuery = (isset($translate['query'][$key])) ? $translate['query'][$key] : '"'.$key.'"';
		
		$this->_fileView->setSearchMameDriver($selectedQuery);
		
		#FACTORY::get('manager/IndexedCombo')->set_active_key($combo, $key);
		
		$this->update_treeview_nav();
		
		$this->nb_main->set_current_page(0);
		$this->onReloadRecord(false);
	} 
	
	/*
	*
	*/
	function add_fileinfo_to_cell($file_list)
	{
		// 20060108 hack for simle mediaview
		if ($this->optVisMainListMode) {
			$this->fillMediaListSimple($file_list);
			return true;
		}
		
		if ($file_list['count']!=0) {
			
			#FACTORY::get('manager/Treeview')->getTreeView()->freeze_child_notify();
			
			foreach ($file_list['data'] as $id => $data) {
				
//				$validKeys = array(
//					'%NAME_NAME%' => 'md_name',
//					'%META_INFO_STRING%' => 'md_info',
//					'%META_INFO_ID%' => 'md_info_id',
//					'%META_MEDIA_TYPE%' => 'md_media_type',
//					'%META_MEDIA_COUNT_CURRENT%' => 'md_media_current',
//					'%META_MEDIA_COUNT_TOTAL%' => 'md_media_count',
//				);
				
				// fast refresh activated?
				if ($this->fastListRefresh) while (gtk::events_pending()) gtk::main_iteration();
				
				$eccident = ($data['fd_eccident']) ? $data['fd_eccident'] : $data['md_eccident'];
				$eccident = strtolower($eccident);
				
				$crc32 = ($data['crc32']) ? $data['crc32'] : $data['md_crc32'];
				
				$path = dirname($data['path']);
				$name_file = $this->get_plain_filename($data['path']);
				$name_packed = ($data['path_pack']) ? $this->get_plain_filename($data['path_pack']) : false;
				$name_dat = ($data['md_name']) ? $data['md_name'] : false;
				$extension = ($data['path_pack']) ? $this->get_ext_form_file($data['path_pack']) : $this->get_ext_form_file($data['path']);
				
				# only search for the first found image!!!
				$searchNames = array($name_file, $name_packed, $name_dat);
				$media = $this->searchForImages($eccident, $crc32, $path, $extension, $searchNames, true);
				
				$obj_pixbuff = $this->get_pixbuf($data['path'], $media, false, false, false, strtolower($eccident));
				$pixbuf_eccident = $this->get_pixbuf_eccident($eccident);
				
				$rating = (isset($data['md_rating'])) ? $data['md_rating'] : 0;
				$ratingPixbuff = $this->getPixbufForRatingImage($rating);
				
				// info
				$info_strg = "";
				$info_strg .= ($data['md_info']) ? "\t\t\t\t\t|*[INFOS: ".$data['md_info']."]*|" : '';;

				$name = ($data['md_name']) ? $data['md_name'] : basename(MultiByte::convertToUtf8($data['path']));

				# create main detail input for cell
				$media_name = $name;
				
				if($data['md_media_current'] && $data['md_media_count']){
					$media_name .= ' (';
					//if($data['md_media_type']) $media_name .= $this->dropdownMediaType[(int)$data['md_media_type']].' ';
					$media_name .= (int)$data['md_media_current'].'/'.(int)$data['md_media_count'];
					$media_name .= ')';
				}
				
				$media_name .= "\n\n";
				
				if ($data['md_name']) {
					$year = ($data['md_year']) ? $data['md_year'] : '';
					$freeware = ($data['md_freeware']) ? '(PD)' : '';
					$category = (isset($this->media_category[$data['md_category']])) ? $this->media_category[$data['md_category']] : '';
					if (trim($category) == '-') $category = '';
					
					$creator = array();
					if ($data['md_creator']) $creator[] = $data['md_creator'];
					if ($data['md_publisher']) $creator[] = $data['md_publisher'];
					$creator = trim(join(' / ', $creator));
					if ($creator && $year) $creator .= ', ';
					
					$media_name .= "$category $freeware\n";
					$media_name .= $creator.$year."\n";
				}
				else{
					$media_name .= I18N::get('mainList', 'detailNoMetaAvailable')."\n";
				}

				$launchCount = (int)$data['fd_launchcnt'];
				$dateFormat = I18N::get('mainList', 'detailDateFormat');
				$launchTime = '('.date($dateFormat, $data['fd_launchtime']).')';
				if ($launchCount) $media_name .= sprintf(I18N::get('mainList', 'detailPlayInfos%s%s'), $launchCount, $launchTime);
				
				// create model array for cell output
				$item = array();
				$item[] = $pixbuf_eccident;
				$item[] = $obj_pixbuff;
				$item[] = $media_name;
				$item[] = $data['id'];
				$item[] = $data['md_id'];
				$item[] = $id;
				$item[] = $ratingPixbuff;

				try{
					$this->model->append($item);
				}
				catch(PhpGtkGErrorException $e){
					print $e."\n";
				}
				
				unset($media);
				unset($media_name);
				unset($obj_pixbuff);
				unset($item);
			}
			#FACTORY::get('manager/Treeview')->getTreeView()->thaw_child_notify();
		}
	}
	
	/*
	*
	*/
	public function add_bookmark_by_id() {
		
		if (!$this->current_media_info['id']) return false;
		
		if($this->_fileView->hasBookmark($this->current_media_info['id'])){
			$this->remove_bookmark_by_id();
		}
		else{
			$this->_fileView->add_bookmark_by_id($this->current_media_info['id']);
			$title = I18N::get('popup', 'bookmark_added_title');
			$msg = I18N::get('popup', 'bookmark_added_msg');
			$this->guiManager->openDialogInfo($title, $msg, array('dhide_finish_bookmark_add'));
		}
		$this->directMediaEdit = true;
		$this->show_media_info();
		$this->onReloadRecord(false);
		$this->directMediaEdit = false;
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
		if (!$this->guiManager->openDialogConfirm($title, $msg, array('dhide_remove_rom_from_db'))) return false;		
		$status = $this->_fileView->remove_media_from_fdata($id, $eccident, $crc32);
		
		$duplicates = $this->_fileView->get_duplicates($eccident, $crc32);
		if (count($duplicates)) {
			$title = I18N::get('popup', 'rom_remove_single_dupfound_title');
			$msg = sprintf(I18N::get('popup', 'rom_remove_single_dupfound_msg%d%s'), count($duplicates), $rom_title);
			if ($this->guiManager->openDialogConfirm($title, $msg)) $this->_fileView->remove_media_duplicates($eccident, $crc32);
		}
		if ($status === true) {
			$this->model->clear();
			$this->update_treeview_nav();
			$this->onReloadRecord(false);
		}
		return true;
	}
	
	/*
	*
	*/
	public function remove_bookmark_by_id() {
		if (!$this->current_media_info['id']) return false;
		$this->_fileView->remove_bookmark_by_id($this->current_media_info['id']);
		
		#$this->selectViewModeBookmarks();
		
		$title = I18N::get('popup', 'bookmark_removed_single_title');
		$msg = I18N::get('popup', 'bookmark_removed_single_msg');
		$this->guiManager->openDialogInfo($title, $msg, array('dhide_finish_bookmark_removed_single'));
		
		$this->directMediaEdit = true;
		$this->show_media_info();
		$this->onReloadRecord(false);
		$this->directMediaEdit = false;
	}
	
//	/*
//	*
//	*/
//	public function remove_bookmark_all() {
//		$title = I18N::get('popup', 'fav_remove_all_title');
//		$msg = I18N::get('popup', 'fav_remove_all_msg');
//		if (!$this->guiManager->openDialogConfirm($title, $msg)) return false;
//		$this->_fileView->remove_bookmark_all();
//
//		$this->selectViewModeBookmarks();
//		
//		$title = I18N::get('popup', 'bookmark_removed_all_title');
//		$msg = I18N::get('popup', 'bookmark_removed_all_msg');
//		$this->guiManager->openDialogInfo($title, $msg, array('dhide_finish_bookmark_removed_all'));
//		
//	}
	
	/*
	* dispatcher
	*/
	public function filelist_data_dispatcher($eccident, $search_like, $limit, $test, $order_by, $search_lang_strg, $search_cat_id, $search_ext=false, $updateCategories=true) {
		
		$this->_fileView->setShowOnlyDisk($this->toggle_only_disk);
		
		return $this->_fileView->get_file_data_TEST_META($eccident, $search_like, $limit, $test, $order_by, $search_lang_strg, $search_cat_id, $search_ext, $this->toggle_show_files_only, $this->toggle_show_doublettes, $this->toggle_show_metaless_roms_only, $this->searchRating, $this->randomGame, $updateCategories);
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
		
		// is freeform search selected?
		// get sql-snipplet
		$search_like = $this->createSearchSqlLike();
		
		$pager_data = $this->media_treeview_pager->reload();
		
		$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
		
		# 20070620 - hotfix
		#$limit = array($pager_data->_res_offset, $pager_data->_pp);
		$limit = array($pager_data->_res_offset, $this->_results_per_page);
		
		$file_list = $this->filelist_data_dispatcher($this->_eccident, $search_like, $limit, true, $order_by, $this->_search_language, $this->_search_category, $this->ext_search_selected);
		
		#$this->updateMameDriverDropdown();
		
		$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
		
		$this->model->clear();
		if (isset($file_list) && $file_list['count'] > 0) {
			$this->add_fileinfo_to_cell($file_list);
		}
	}
	
	/**
	 * Function builds an sql snipplet for the freeform search.
	 * 
	 */
	private function createSearchSqlLike() {
		if (!$this->_search_word) return '';
		
		// defalault search for all other :-)
		$like_pre = (!$this->_search_word_like_pre) ? '%' : '';
		$like_post = (!$this->_search_word_like_post) ? '%' : '';
		$searchString = "";
		
		// $this->searchFreeformType contains types
		// see $this->freeformSearchFields
		switch($this->searchFreeformType) {
			case 'NAME':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "(title like '%1\$s' OR name like '%1\$s')", $this->searchFreeformOperator);
				break;
			case 'YEAR':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.year like '%s'", $this->searchFreeformOperator);
				break;
			case 'DEVELOPER':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.creator like '%s'", $this->searchFreeformOperator);
				break;
			case 'PUBLISHER':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.publisher like '%s'", $this->searchFreeformOperator);
				break;
			case 'PROGRAMMER':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.programmer like '%s'", $this->searchFreeformOperator);
				break;
			case 'MUSICAN':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.musican like '%s'", $this->searchFreeformOperator);
				break;
			case 'GRAPHICS':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.graphics like '%s'", $this->searchFreeformOperator);
				break;
			case 'INFO':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.info like '%s'", $this->searchFreeformOperator);
				break;
			case 'EXTENSION':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "md.extension like '%s'", $this->searchFreeformOperator);
				break;
			case 'ECCIDENT':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "fd.eccident like '%s'", $this->searchFreeformOperator);
				break;
			case 'CRC32':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "fd.crc32 like '%s'", $this->searchFreeformOperator);
				break;	
			case 'PATH':
				$searchString = $this->createPseudoFuzzySearch($this->_search_word, $like_pre, $like_post, "(fd.path like '%1\$s' OR fd.path_pack like '%1\$s')", $this->searchFreeformOperator);
				break;
		}
		return $searchString;
	}
	
	private function createPseudoFuzzySearch($searchWords, $like_pre, $like_post, $sqlString, $type='AND') {
		
		if (!trim($searchWords)) return '';
		if (!in_array($type, array('', 'OR', 'AND'))) $type = 'AND';
		
		if (!$type) {
			return sprintf($sqlString, $like_pre.sqlite_escape_string($searchWords).$like_post);
		}
		
		// fake fuzzy search for name! :-)
		$fuzzySearch = explode(' ', $searchWords);
		
		# only use the first 10 words for this mode... remove duplicate words
		array_splice($fuzzySearch, 15);
		$fuzzySearch = array_unique($fuzzySearch);
		
		# dont use or, because i think, thats not, what the user want!
		# otherwise, the user searches for an impossible combination
		if ((!$like_pre || !$like_post) && $type == 'AND' && count($fuzzySearch) > 1) {
			$fuzzySearch = array($searchWords);
		}
		
		$search = array();
		foreach ($fuzzySearch as $searchWordAtom) {
			$searchWordAtom = $like_pre.sqlite_escape_string($searchWordAtom).$like_post;
			$search[] = sprintf($sqlString, $searchWordAtom);
		}
		
		$query = "(".implode(' '.$type.' ', $search).")";
		return $query;
	}
	
	/*
	*
	*/
	public function onInitialRecord($updateCategories=false)
	{
		
		# get last selected page from history!
		$page = 0;
		$lastSelectedPage = $this->ini->getHistoryKey('last_selected_page');
		# if there was an last selected page, reset this page now!
		if($lastSelectedPage){
			$page = $lastSelectedPage-1;
			$this->ini->storeHistoryKey('last_selected_page', false, false);
		}
		
		$this->model->clear();
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';
		
		// is freeform search selected?
		// get sql-snipplet
		$search_like = $this->createSearchSqlLike();
		
		// 20060108 hack for simle mediaview
		if (!$this->_results_per_page) {
			$limit = false;
		}
		else {
			$limit = array($page*$this->_results_per_page, $this->_results_per_page);
			#$limit = array(0, $this->_results_per_page);
		}
		
		$file_list = $this->filelist_data_dispatcher($this->_eccident, $search_like, $limit, true, $order_by, $this->_search_language, $this->_search_category, $this->ext_search_selected, $updateCategories);

		$this->updateMameDriverDropdown();
		
		if (isset($file_list['cat'])) $this->updateCategoryDropdown($file_list['cat']);
		
		$this->the_file_list = isset($file_list['data']) ? $file_list['data'] : array();
		$this->data_available = $file_list['count'];
		
		// 20060108 hack for simle mediaview
		if ($this->_results_per_page) {
			
			# init pager
			$pager_data = $this->media_treeview_pager->init($file_list['count'], $page, $this->_results_per_page);
			
			if ($pager_data->_pt > 0) {
				$this->set_pager_position_label($this->media_pager_label, $pager_data->_p, $pager_data->_pt, $pager_data->_res_total);
			}
			else {
				$pager_txt = '<span foreground="#cc0000"><b>NO DATA!</b></span>';
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
		}
		
		if (isset($file_list) && $file_list['count'] > 0) {
			$this->add_fileinfo_to_cell($file_list);
		}
	}
	
	/*
	*
	*/
	public function onNextRecord($offset=false)
	{
		$this->nb_main->set_current_page(0);
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';
		
		// is freeform search selected?
		// get sql-snipplet
		$search_like = $this->createSearchSqlLike();
		
		$pager_data = $this->media_treeview_pager->next($offset);
		
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
	public function onPrevRecord($offset=false)
	{
		$this->nb_main->set_current_page(0);
		
		$order_by = ($this->search_order_asc1->get_active()) ? 'ASC' : 'DESC';

		// is freeform search selected?
		// get sql-snipplet
		$search_like = $this->createSearchSqlLike();
		
		$pager_data = $this->media_treeview_pager->prev($offset);
		
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
	public function parseMedia($directEccIdent = false, $directParseDirectory = false, $openFileChooser = false) {
		
		$eccIdent = $this->_eccident;
		
		if ($this->status_obj->init()) {
			
			if ($directEccIdent && $directParseDirectory) {
				$eccIdent = $directEccIdent;
				# rem
				$platfom = $this->ini->getPlatformName($directEccIdent);
				if ($openFileChooser){
					if (!$this->setPathForEccParser($platfom, $directParseDirectory)) {
						$this->status_obj->reset1();
						return false;
					}
					$parseDirectory = $this->fs_path_for_parser;
				}
				else{
					if (is_array($directParseDirectory)) $parseDirectory = $directParseDirectory;
					else $parseDirectory = array($directParseDirectory);
				}
			}
			else {
				$platfom = $this->ini->getPlatformName($eccIdent);
				if (!$this->setPathForEccParser($platfom, false, $eccIdent)) {
					$this->status_obj->reset1();
					return false;
				}
				$parseDirectory = $this->fs_path_for_parser;
			}

			$managerOs = FACTORY::get('manager/Os');
			$parseDirectoryFixed = array();
			foreach($parseDirectory as $index => $path){
				$fixedPath = $managerOs->eccSetRelativeDir($path);
				if($fixedPath) $parseDirectoryFixed[$fixedPath] = $fixedPath;
			}
			
			$silentReparse = $this->ini->getKey('USER_SWITCHES', 'confEccSilentParsing');
			
			if(!$silentReparse){
				$title = sprintf(I18N::get('popup', 'rom_add_parse_title%s'), $platfom);
				$msg = sprintf(I18N::get('popup', 'rom_add_parse_msg%s%s'), $platfom, join("\n", $parseDirectoryFixed));
				if (!$this->guiManager->openDialogConfirm($title, $msg, array('dhide_parser_platform_info'.$eccIdent))) {
					$this->status_obj->reset1();
					return false;
				}
			}
			
			$this->status_obj->set_label(sprintf(i18n::get('popup', 'stateLabelParseRomsFor%s'), $platfom));
			$this->status_obj->set_popup_cancel_msg();
			$this->status_obj->show_main();
			$this->status_obj->show_output();
			
			require_once('manager/cEccParser.php');
			$connectedMetaFilesizeCheck = false;
			
			$eccparser = new EccParser($eccIdent, $this->ini, $parseDirectoryFixed, $this->pbar_parser, $this->statusbar_lbl_bottom, $this->status_obj, $this, $connectedMetaFilesizeCheck);
			
			# only for multirom platforms!
			if ($this->ini->isMultiRomPlatform($eccIdent) && !$this->status_obj->is_canceled()) FACTORY::get('manager/ImportDatControlMame')->updateCloneState($eccIdent);
			
			if ($eccIdent) $this->status_obj->update_message($eccparser->getLog());
			
			$this->update_treeview_nav();
			$this->onInitialRecord();
			
			if(!$silentReparse){
				$title = I18N::get('popup', 'rom_add_parse_done_title');
				$msg = sprintf(I18N::get('popup', 'rom_add_parse_done_msg%s'), strtoupper($platfom));
				$this->status_obj->open_popup_complete($title, $msg, array('dhide_parser_status_close'));
			}
			else{
				$this->status_obj->reset1();
				$this->status_obj->hide_main();
				return false;
			}
		}
	}
	
	/*
	*
	*/
	public function setPathForEccParser($platfom, $path = false, $eccident = false)
	{
		// get path from history
		$historyKey = 'eccparser_'.$eccident;
	
		if (!$path || !realpath($path)) $path = $this->ini->getHistoryKey($historyKey);
		
		# if no path is given, try to get the last selected path
		if(!$path || !realpath($path)) $path = FACTORY::get('manager/IniFile')->getUserFolder($eccident, 'roms');
		
		$title = sprintf(I18N::get('popup', 'rom_add_filechooser_title%s'), $platfom);
		
		# used for assigned emulators
		$shorcutFolder = $this->ini->getShortcutPaths($eccident);
		$paths = FACTORY::get('manager/Os')->openChooseFolderDialog($path, $title, true, $shorcutFolder);
		if ($paths && count($paths)) {
			$this->ini->storeHistoryKey($historyKey, $paths[0], true);
			$this->fs_path_for_parser = $paths;
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
	
	/*
	* internal function for glade
	*/
	private function __get($property) {
		return parent::get_widget($property);
	}
	
	private function loadEccConfig() {
		$mngrValidator = FACTORY::get('manager/Validator');
		$this->ecc_release = $mngrValidator->getEccCoreKey('ecc_release');
		$this->user_path_subfolder_default = $mngrValidator->getEccCoreKey('user_path_subfolder_default');
		$this->supported_images = $mngrValidator->getEccCoreKey('supported_images');
		$this->cbox_yesno = $mngrValidator->getEccCoreKey('cbox_yesno');
		$this->image_type = $mngrValidator->getEccCoreKey('image_type');
		$this->media_language = $mngrValidator->getEccCoreKey('media_language');
		$this->media_category = $mngrValidator->getEccCoreKey('media_category');
		$this->ext_search_combos = $mngrValidator->getEccCoreKey('ext_search_combos');
		$this->freeformSearchFields = $mngrValidator->getEccCoreKey('freeformSearchFields');
		$this->freeformSearchOperators = $mngrValidator->getEccCoreKey('freeformSearchOperators');
		$this->dropdownStateYesNo = $mngrValidator->getEccCoreKey('dropdownStateYesNo');
		$this->dropdownStateCount = $mngrValidator->getEccCoreKey('dropdownStateCount');
		$this->dropdownStorage = $mngrValidator->getEccCoreKey('dropdownStorage');
		$this->dropdownRegion = $mngrValidator->getEccCoreKey('dropdownRegion');
		$this->dropdownMediaType = $mngrValidator->getEccCoreKey('dropdownMediaType'); # meta -> dropdownMedium
		$this->eccHelpLocations = $mngrValidator->getEccCoreKey('eccHelpLocations');
		$this->eccdb = $mngrValidator->getEccCoreKey('eccdb');
		$this->cs = $mngrValidator->getEccCoreKey('cs');
		$this->sessionTime = time();
		
		$this->cleanupConfigsIfCopied();
	}
	
	private function writeLocalReleaseInfo() {
		$this->loadEccConfig();
		$versionInfos = '
[GENERAL]
current_version="'.$this->ecc_release["local_release_version"].'"
date_build="'.$this->ecc_release['local_release_date'].'"
current_build="'.$this->ecc_release['release_build'].'"
';
		file_put_contents(ECC_DIR_SYSTEM.'/infos/ecc_local_version_info.ini', trim($versionInfos));
	}
	
	private function writeEnviromentInfo(){
		#file_put_contents(ECC_DIR_SYSTEM.'/infos/ecc_local_envment_info.ini', 'NOT IMPLEMENTED');
	}
	
	private function cleanupConfigsIfCopied() {
		$ciString = @$_SERVER['USERDOMAIN']."|".@$_SERVER['TEMP']."|".@$_SERVER['TMP']."|".@$_SERVER['APPDATA']."|".@$_SERVER['COMPUTERNAME']."|".@$_SERVER['HOMEPATH'];
		$ciCheck = sprintf('%08X', crc32($ciString));
		$ciDatPath = ECC_DIR.'/'.$this->cs['cicheckdat'];
		if (file_exists($ciDatPath)) {
			$ciCheckFound = @file_get_contents($ciDatPath);
			if ($ciCheckFound != $ciCheck) {
				@unlink($ciDatPath);
				@unlink(ECC_DIR.'/'.$this->cs['cscheckdat']);
			}
		}
		@file_put_contents($ciDatPath, $ciCheck);
	}
	
	public function openRomdbGetUrl() {
		$eccIdent = (@$this->current_media_info['md_eccident']) ? $this->current_media_info['md_eccident'] : $this->current_media_info['fd_eccident'];
		$crc32 = @$this->current_media_info['crc32'];
		$getParam = ($eccIdent && $crc32) ? '?mid='.$eccIdent.'.'.$crc32 : '';
		FACTORY::get('manager/Os')->executeProgramDirect($this->eccdb['META_GET_URL'].$getParam, 'open');
	}
	
	
	/* ---------------------------------------------------
	* IMAGE METHODS
	* ----------------------------------------------------
	*/

	public function setMatchImageType() {
		$this->imageManager->setMatchImageType($this->infoImageBtnMatchImageType->get_active());
		$this->imageManager->resetCachedImages($this->_eccident);
		$this->onReloadRecord();
	}
	
	public function searchForImages($eccident = false, $crc32 = false, $filePath = false, $fileExtension = false, $searchNames = array(), $onlyFirstFound = true) {
		if ($this->images_inactiv) return array();
		$mode = 'SAVED';
		$imageType = $this->cb_image_type->get_active_text();
		$this->imageManager->setMatchImageType($this->infoImageBtnMatchImageType->get_active());
		$images = $this->imageManager->getCachedImages($eccident, $crc32);
		if (!$images) $images = $this->imageManager->searchForRomImages($mode, $eccident, $crc32, $filePath, $fileExtension, $searchNames, $imageType, $onlyFirstFound);
		return $images;
	}
	
	/**
	 * Opens the fullscreen imagepopup
	 * This function uses the oGuiImagePopup-object, that handles
	 * all the imagepopup functions like show and update! 
	 * @return boolean true|false
	 **/
	public function openImagePopup($onlyShowIfOpened = false, $openWebsite = false) {
		if (!$this->current_media_info){
			if($openWebsite) FACTORY::get('manager/Os')->executeProgramDirect($this->eccHelpLocations['ECC_WEBSITE'], 'open');
			return false;
		}
		if ($onlyShowIfOpened && !$this->oGuiImagePopup->is_opened()) return false;
		$this->oGuiImagePopup->show($this->current_media_info, $this->image_type_selected);
	}
	
	/*
	*
	*/
	public function set_image_for_show($pos=false, $directPath = false) {
		
		$this->_img_show_pos = ($pos !== false) ? $pos : $this->_img_show_pos ;
		$info = $this->current_media_info;
		
		$eccident = ($info['fd_eccident']) ? $info['fd_eccident'] : $info['md_eccident'];
		$eccident = strtolower($eccident);
		
		$path = dirname($info['path']);
		$name_file = $this->get_plain_filename($info['path']);
		$name_packed = ($info['path_pack']) ? $this->get_plain_filename($info['path_pack']) : false;
		$name_dat = ($info['md_name']) ? $info['md_name'] : false;
		$extension = ($info['path_pack']) ? $this->get_ext_form_file($info['path_pack']) : $this->get_ext_form_file($info['path']);
		
		$searchNames = array($name_file, $name_packed, $name_dat);
		
		$crc32 = ($info['crc32']) ? $info['crc32'] : $info['md_crc32'];
		$this->imageManager->resetCachedImages($eccident, $crc32);
		$media1 = $this->searchForImages($eccident, $crc32, $path, $extension, $searchNames, false);
		
		// quickhack to get an indexed array
		$media = array();
		if (count($media1)) foreach($media1 as $path) $media[] = $path;
		
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
			
			if ($this->_img_show_pos+1 >= $this->_img_show_count) {
				$this->media_img_btn_next->set_sensitive(false);
			}
			if ($this->_img_show_pos == 0) {
				$this->media_img_btn_prev->set_sensitive(false);
			}
		}
		else {
			if ($this->_img_show_count == 1) {
				$msg_img_show_status = "(1/1)";
			}
			else {
				$msg_img_show_status = "(0/0)";
			}
			$this->media_img_btn_next->set_sensitive(false);
			$this->media_img_btn_prev->set_sensitive(false);
		}
		
		$pix_data = $this->get_pixbuf($info['path'], $media, $this->_img_show_pos, 240, 160, $eccident);
		$this->media_img->set_from_pixbuf($pix_data);
		
		$msg = "";
		if (isset($media[$this->_img_show_pos])) {
			$msg .= basename($media[$this->_img_show_pos]);
		}
		else {
			$msg .= '--';
		}
		#$this->img_media_lbl_filename->set_text($msg);
		
		$this->currentImageTank = $media1;
		
		unset($info);
		unset($pix_data);
		unset($media);
	}
	
	public function image_type_order_preview($obj){
		$imageType = $obj->get_active_text();
		
		$eccident = ($this->current_media_info['fd_eccident']) ? $this->current_media_info['fd_eccident'] : $this->current_media_info['md_eccident'];
		$crc32 = ($this->current_media_info['crc32']) ? $this->current_media_info['crc32'] : $this->current_media_info['md_crc32'];
		
		if($eccident && $crc32){
			$images = $this->imageManager->getCachedImages($eccident, $crc32);
			$imagePath = (isset($images[$imageType]) && file_exists($images[$imageType])) ? $images[$imageType] : false;
			$this->set_image_for_show(false, $imagePath);
		}
		else{
			print "missing current_media_info\n";
		}
		
		

		
	}
	
	public function image_type_order($obj=false, $needle=false) {
		if (!$needle) $needle = $obj->get_active_text();
		$this->image_type_selected = $needle;
		
		$this->ini->storeHistoryKey('imageTypeSelected', $this->image_type_selected);
		
		$temp[$needle] = $this->image_type[$needle];
		unset($this->image_type[$needle]);
		$this->image_type = array_merge($temp, $this->image_type);
		
		# only reset current eccident images
		$this->imageManager->resetCachedImages($this->_eccident);
		
		if ($obj) $this->onReloadRecord();
	}
	
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
	
	/**
	 * get user-switch from ini and setup image-size for mainview
	 * If the user-switch is missing, use the default values set in
	 * member-vars
	 * 
	 */	
	private function set_ecc_image_size_from_ini() {
		$image_size = $this->ini->getKey('USER_SWITCHES', 'image_mainview_size');
		
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
	public function get_pixbuf($path, $media, $pos=false, $width=false, $height=false, $media_name='unknown') {
		
		if (!count($media)) $media = array();
		
		if ($pos>0) {
			$filename = $media[$pos];
			$ext = strtolower($this->get_ext_form_file($filename));
			if (isset($this->supported_images[$ext]) && $this->supported_images[$ext]) {
				
				// use thumbnail, if available
				$thumbName = $this->imageManager->getImageThumbFile($filename);
				if (is_file($thumbName)) $filename = $thumbName;
				
				return $this->oHelper->getPixbuf($filename, $width, $height, $this->imagesAspectRatio);
			}
		}
		
		$width = ($width) ? $width : $this->_pixbuf_width;
		$height = ($height) ? $height :$this->_pixbuf_height;
		
		$obj_pixbuff = null;
		foreach ($media as $file_path) {
			$ext = strtolower($this->get_ext_form_file($file_path));
			if (isset($this->supported_images[$ext])) {
				
				// use thumbnail, if available
				$thumbName = $this->imageManager->getImageThumbFile($file_path);
				if (is_file($thumbName)) $file_path = $thumbName;
				
				$obj_pixbuff = $this->oHelper->getPixbuf($file_path, $width, $height, $this->imagesAspectRatio);
				
				# if pixbuf not found, return the default pixbuf for platform
				if($obj_pixbuff !== null) return $obj_pixbuff;
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
			$obj_pixbuff = $this->oHelper->getPixbuf($img_path, $width, $height);
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

		$obj_pixbuff = $this->oHelper->getPixbuf($path);
		$this->cell_ident_pixbuf[$eccident] = $obj_pixbuff;
		return $obj_pixbuff;
	}
	
	public $cellRatingPixbufTank = array();
	
	public function getPixbufForRatingImage($rating) {
		
		// cached copy
		if (isset($this->cellRatingPixbufTank[$rating])) return $this->cellRatingPixbufTank[$rating];

		// get new
		$path = dirname(__FILE__)."/".'images/eccsys/rating/ecc_rating_'.$rating.'.png';
		if (!file_exists($path)) $path = dirname(__FILE__)."/".'images/eccsys/rating/ecc_rating_0.png';
		$obj_pixbuff = $this->oHelper->getPixbuf($path);
		//if ($obj_pixbuff !== null) $obj_pixbuff = $obj_pixbuff->scale_simple(5, 80, Gdk::INTERP_BILINEAR);
		$this->cellRatingPixbufTank[$rating] = $obj_pixbuff;
		return $obj_pixbuff;
	}
	
	public function getImagesByEccInject() {
		if (!$this->current_media_info) return false;
		$eccident = ($this->current_media_info['fd_eccident']) ? $this->current_media_info['fd_eccident'] : $this->current_media_info['md_eccident'];
		$crc32 = $this->current_media_info['crc32'];
		$parameter = '/images /'.$this->ini->getPlatformFolderName($eccident).' /'.$crc32.'';
		$exe = ECC_DIR.'/ecc-tools/'.$this->eccHelpLocations['ECC_INJECT_START'];
		//print $exe.$parameter.LF;
		FACTORY::get('manager/Os')->executeProgramDirect($exe, false, $parameter);
	}
	
	public function on_image_toggle() {
		$this->images_inactiv = ($this->images_inactiv) ? false : true ;
		$this->ini->storeHistoryKey('images_inactiv', $this->images_inactiv, false);
		$this->onInitialRecord();
		return true;
	}
	
	private function translateGuiTopMenu(){
		
		# TOP-ROM
		$this->mTopRom->get_child()->set_text(I18N::get('menuTop', 'mTopRom'));
		$this->mTopRomOptimize->get_child()->set_text(I18N::get('menuTop', 'mTopRomOptimize'));
		$this->mMenuReparseFolder->get_child()->set_text(I18N::get('menuTop', 'mMenuReparseFolder'));
		$this->mMenuReparseFolderAll->get_child()->set_text(I18N::get('menuTop', 'mMenuReparseFolderAll'));
		$this->mTopRomRemoveDups->get_child()->set_text(I18N::get('menuTop', 'mTopRomRemoveDups'));
		$this->mTopRomRemoveRoms->get_child()->set_text(I18N::get('menuTop', 'mTopRomRemoveRoms'));
		
		# TOP-EMU
		$this->mTopEmu->get_child()->set_text(I18N::get('menuTop', 'mTopEmu'));
		$this->mTopEmuConfig->get_child()->set_text(I18N::get('menuTop', 'mTopEmuConfig'));

		# TOP-DAT
		$this->mTopDat->get_child()->set_text(I18N::get('menuTop', 'mTopDat'));
		
		$this->mTopDatImport->get_child()->set_text(I18N::get('menuTop', 'mTopDatImport'));
		$this->mTopDatImportEcc->get_child()->set_text(I18N::get('menuTop', 'mTopDatImportEcc'));
		$this->mTopDatImportCtrlMAME->get_child()->set_text(I18N::get('menuTop', 'mTopDatImportCtrlMAME'));
		$this->mTopDatImportRc->get_child()->set_text(I18N::get('menuTop', 'mTopDatImportRc'));
		
		$this->mTopDatExport->get_child()->set_text(I18N::get('menuTop', 'mTopDatExport'));
		$this->mTopDatExportEccFull->get_child()->set_text(I18N::get('menuTop', 'mTopDatExportEccFull'));
		$this->mTopDatExportEccUser->get_child()->set_text(I18N::get('menuTop', 'mTopDatExportEccUser'));
		$this->mTopDatExportEccEsearch->get_child()->set_text(I18N::get('menuTop', 'mTopDatExportEccEsearch'));
		$this->mTopDatClear->get_child()->set_text(I18N::get('menuTop', 'mTopDatClear'));
		$this->mTopDatConfig->get_child()->set_text(I18N::get('menuTop', 'mTopDatConfig'));		
		
		# ROMDB
		$this->mTopRomDB->get_child()->set_text(I18N::get('menuTop', 'mTopRomDB'));	
		$this->mTopDatImportOnlineRomdb->get_child()->set_text(I18N::get('menuTop', 'mTopDatImportOnlineRomdb'));
		$this->mTopDatExportOnlineRomdb->get_child()->set_text(I18N::get('menuTop', 'mTopDatExportOnlineRomdb'));
				
		# ROM-AUDIT
		$this->mTopRomAuditShow->get_child()->set_text(I18N::get('menuTop', 'mTopRomAuditShow'));
		
		# TOP-IMG
		$this->mTopImage->get_child()->set_text(I18N::get('menuTop', 'mTopImage'));
		$this->mTopImageConvert->get_child()->set_text(I18N::get('menuTop', 'mTopImageConvert'));

		# TOP-FIES
		$this->mTopFile->get_child()->set_text(I18N::get('menuTop', 'mTopFile'));
		$this->mTopFileRename->get_child()->set_text(I18N::get('menuTop', 'mTopFileRename'));
		$this->mTopFileCopy->get_child()->set_text(I18N::get('menuTop', 'mTopFileCopy'));
		$this->mTopFileRemove->get_child()->set_text(I18N::get('menuTop', 'mTopFileRemove'));
		
		# VIEW

		$this->mTopView->get_child()->set_text(I18N::get('menuTop', 'mTopView'));
		$this->mTopViewModeRomHave->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomHave'));
		$this->mTopViewModeRomDontHave->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomDontHave'));
		$this->mTopViewModeRomAll->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomAll'));
		$this->mTopViewModeRomNoMeta->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomNoMeta'));
		$this->mTopViewModeRomPersonal->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomPersonal'));
		$this->mTopViewModeRomPlayed->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomPlayed'));
		$this->mTopViewModeRomMostPlayed->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomMostPlayed'));
		$this->mTopViewModeRomNotPlayed->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomNotPlayed'));
		$this->mTopViewModeRomBookmarks->get_child()->set_text(I18N::get('menuTop', 'mTopViewModeRomBookmarks'));
		
		$this->mTopViewListDetail->get_child()->set_text(I18N::get('menuTop', 'mTopViewListDetail'));
		$this->mTopViewListSimple->get_child()->set_text(I18N::get('menuTop', 'mTopViewListSimple'));
		
		$this->mTopViewRandomGame->get_child()->set_text(I18N::get('menuTop', 'mTopViewRandomGame'));
		$this->mTopViewReload->get_child()->set_text(I18N::get('menuTop', 'mTopViewReload'));
		
		$this->mTopViewOnlyRoms->get_child()->set_text(I18N::get('menuTop', 'mTopViewOnlyRoms'));
		$this->mTopViewOnlyBookmarks->get_child()->set_text(I18N::get('menuTop', 'mTopViewOnlyBookmarks'));
		$this->mTopViewOnlyPlayed->get_child()->set_text(I18N::get('menuTop', 'mTopViewOnlyPlayed'));
		
		$this->mTopViewToggleLeft->get_child()->set_text(I18N::get('menuTop', 'mTopViewToggleLeft'));
		$this->mTopViewToggleRight->get_child()->set_text(I18N::get('menuTop', 'mTopViewToggleRight'));
		$this->mTopViewToggleSearch->get_child()->set_text(I18N::get('menuTop', 'mTopViewToggleSearch'));
		
		# TOP-OPT
		$this->mTopOption->get_child()->set_text(I18N::get('menuTop', 'mTopOption'));
		$this->mTopOptionDbVacuum->get_child()->set_text(I18N::get('menuTop', 'mTopOptionDbVacuum'));
		$this->mTopOptionCreateUserFolder->get_child()->set_text(I18N::get('menuTop', 'mTopOptionCreateUserFolder'));
		$this->mTopOptionCleanHistory->get_child()->set_text(I18N::get('menuTop', 'mTopOptionCleanHistory'));
		$this->mTopOptionConfig->get_child()->set_text(I18N::get('menuTop', 'mTopOptionConfig'));
		
		$this->mTopOptionBackupUserdata->get_child()->set_text(I18N::get('menuTop', 'mTopOptionBackupUserdata'));
		
		# TOP-TOOL
		$this->mTopTool->get_child()->set_text(I18N::get('menuTop', 'mTopTool'));
		#$this->mTopToolEccRomId->get_child()->set_text(I18N::get('menuTop', 'mTopToolEccRomId'));
		$this->mTopToolEccTheme->get_child()->set_text(I18N::get('menuTop', 'mTopToolEccTheme'));
		$this->mTopToolEccBugreport->get_child()->set_text(I18N::get('menuTop', 'mTopToolEccBugreport'));
		
		# TOP-HELP
		$this->mTopHelp->get_child()->set_text(I18N::get('menuTop', 'mTopHelp'));
		$this->mTopHelpWebsite->get_child()->set_text(I18N::get('menuTop', 'mTopHelpWebsite'));
		$this->mTopHelpForum->get_child()->set_text(I18N::get('menuTop', 'mTopHelpForum'));
		$this->mTopHelpDocOffline->get_child()->set_text(I18N::get('menuTop', 'mTopHelpDocOffline'));
		$this->mTopHelpDocOnline->get_child()->set_text(I18N::get('menuTop', 'mTopHelpDocOnline'));
		$this->mTopHelpAbout->get_child()->set_text(I18N::get('menuTop', 'mTopHelpAbout'));

		# TOP-UPDATE
		$this->mTopUpdate->get_child()->set_text(I18N::get('menuTop', 'mTopUpdate'));
		$this->mTopUpdateEccLive->get_child()->set_text(I18N::get('menuTop', 'mTopUpdateEccLive'));
		
	}
	
	private function guiInit(){
		
		$imageObject = FACTORY::get('manager/Image');
		$imageObject->setWidgetBackground($this->wdo_main, 'background/main.png');
		$imageObject->setWidgetBackground($this->eventbox1, 'background/box.png');
		$imageObject->setWidgetBackground($this->eventbox2, 'background/box.png');
		$imageObject->setWidgetBackground($this->statusAreaBackground, 'background/box_hilight.png');
		$imageObject->setWidgetBackground($this->scrolledwindow1, 'background/box.png');
		
		#setup icons for rom/bookmark/history buttons
		$this->btnMainShowAllRomsIcon->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/controller.png', true)));
		$this->btnMainShowBookmarkedRomsIcon->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/heart.png', true)));
		$this->btnMainShowLaunchedRomsIcon->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/clock.png', true)));
		$this->statusAreaIcon->set_from_pixbuf($this->oHelper->getPixbuf($this->getThemeFolder('icon/ecc_working.png', true)));
	}
	
	public function getThemeFolder($subfolder = '', $important = false){
		
		$defaultTheme = 'default';
		$theme = $this->ini->getKey('ECC_THEME', 'ecc-theme');
		
		if($theme == 'none' && !$important) return false;
		
		$basePath = ECC_DIR.'/ecc-themes/';

		$fullPath = $basePath.$theme.'/'.$subfolder;
		
		if($theme != $defaultTheme){
			if(!file_exists($fullPath)) $fullPath = $basePath.$defaultTheme.'/'.$subfolder;;
		}
		
		return $fullPath;
	}
	
	private function translateGui(){
		
		$this->translateGuiTopMenu();
		
		
		# romdetail tabs
		$this->media_nb_info_lbl->set_label(strtoupper(I18N::get('mainGui', 'romDetailTabInfo')));
		$this->infoPersonalLbl->set_label(strtoupper(I18N::get('mainGui', 'romDetailTabPersonal')));
		$this->infoEsearchLbl->set_label(strtoupper(I18N::get('mainGui', 'romDetailTabESearch')));
		$this->infoRomDBLbl->set_label(strtoupper(I18N::get('mainGui', 'romDetailTabRomDB')));
//		$this->media_nb_mdata_lbl->set_label(strtoupper(I18N::get('mainGui', 'romDetailTabRomData')));
		$this->media_nb_header_lbl->set_label(strtoupper(I18N::get('mainGui', 'romDetailTabRomHeader')));
		
		# main gui buttons
		$this->gui_main_btn_rom_start->set_text(I18N::get('mainGui', 'gui_main_btn_rom_start'));
		$this->gui_main_btn_rom_bookmark->set_text(I18N::get('mainGui', 'gui_main_btn_rom_bookmark'));

		$this->btnMainShowAllRomsLabel->set_label(I18N::get('mainGui', 'btnMainShowAllRomsLabel'));
		$this->btnMainShowBookmarkedRomsLabel->set_label(I18N::get('mainGui', 'btnMainShowBookmarkedRomsLabel'));
		$this->btnMainShowLaunchedRomsLabel->set_label(I18N::get('mainGui', 'btnMainShowLaunchedRomsLabel'));
		
		$this->media_nb_info_edit->set_label(I18N::get('mainGui', 'media_nb_info_edit'));
		
		$this->mainlist_tab_factsheet->set_label(I18N::get('mainGui', 'mainlist_tab_factsheet'));
		$this->mainlist_tab_help->set_label(I18N::get('mainGui', 'mainlist_tab_help'));
		
		# images		
		$this->infoImageBtnMatchImageType->set_label(I18N::get('mainGui', 'infoImageBtnMatchImageType'));
		$this->infoImageEditBtn->set_label(I18N::get('mainGui', 'infoImageEditBtn'));
		
		# romdb
		$this->media_nb_info_eccdb_info->set_label(I18N::get('mainGui', 'media_nb_info_eccdb_info'));	
		$this->paneInfoEccDbAddTitle->set_markup('<b>'.i18n::get('mainGui', 'paneInfoEccDbAddTitle').'</b>');
		$this->paneInfoEccDbAddText->set_text(i18n::get('mainGui', 'paneInfoEccDbAddText'));
		$this->paneInfoEccDbAddButton->set_label(i18n::get('mainGui', 'paneInfoEccDbAddButton'));
//		$this->paneInfoEccDbGetTitle->set_markup('<b>'.i18n::get('mainGui', 'paneInfoEccDbGetTitle').'</b>');
//		$this->paneInfoEccDbGetText->set_text(i18n::get('mainGui', 'paneInfoEccDbGetText'));
//		$this->paneInfoEccDbGetButton->set_label(i18n::get('mainGui', 'paneInfoEccDbGetButton'));	

		$this->paneInfoEccDbGetDatfileTitle->set_markup('<b>'.i18n::get('mainGui', 'paneInfoEccDbGetDatfileTitle').'</b>');
		$this->paneInfoEccDbGetDatfileButton->set_label(i18n::get('mainGui', 'paneInfoEccDbGetDatfileButton'));
		
		# metaoptions
		$this->infotab_lbl_category->set_markup('<b>'.I18N::get('meta', 'lbl_category').'</b>');
		$this->infotab_lbl_developer->set_markup('<b>'.I18N::get('meta', 'lbl_developer').'</b>');
		$this->infotab_lbl_publisher->set_markup('<b>'.I18N::get('meta', 'lbl_publisher').'</b>');
		$this->infotab_lbl_year->set_markup('<b>'.I18N::get('meta', 'lbl_year').'</b>');
		#$this->infotab_lbl_usk->set_markup('<b>'.I18N::get('meta', 'lbl_usk').'</b>');
		$this->infotab_lbl_info->set_markup('<b>'.I18N::get('meta', 'lbl_info').'</b>');
		
		$this->infotab_lbl_languages->set_markup('<b>'.I18N::get('meta', 'lbl_languages').'</b>');
		
		$this->infotab_lbl_storage->set_markup('<b>'.I18N::get('meta', 'lbl_storage').'</b>');
		$this->infotab_lbl_running->set_markup('<b>'.I18N::get('meta', 'lbl_running').'</b>');
		$this->infotab_lbl_buggy->set_markup('<b>'.I18N::get('meta', 'lbl_buggy').'</b>');
		$this->infotab_lbl_trainer->set_markup('<b>'.I18N::get('meta', 'lbl_trainer').'</b>');
		$this->infotab_lbl_intro->set_markup('<b>'.I18N::get('meta', 'lbl_intro').'</b>');
		$this->infotab_lbl_usermod->set_markup('<b>'.I18N::get('meta', 'lbl_usermod').'</b>');
		$this->infotab_lbl_freeware->set_markup('<b>'.I18N::get('meta', 'lbl_freeware').'</b>');
		$this->infotab_lbl_multiplay->set_markup('<b>'.I18N::get('meta', 'lbl_multiplay').'</b>');
		$this->infotab_lbl_netplay->set_markup('<b>'.I18N::get('meta', 'lbl_netplay').'</b>');
		
		# Fileinfos
		$this->infotab_frame_fileinfo->set_markup('<b>'.I18N::get('global', 'fileInfo').'</b>');
		$this->infotab_lbl_filename->set_markup('<b><span size="small">'.I18N::get('meta', 'lbl_filename_short').':</span></b>');
		$this->infotab_lbl_directory->set_markup('<b><span size="small">'.I18N::get('meta', 'lbl_directory_short').':</span></b>');
		$this->infotab_lbl_filesize->set_markup('<b><span size="small">'.I18N::get('meta', 'lbl_filesize_short').':</span></b>');
		$this->infotab_lbl_zip->set_markup('<b><span size="small">'.I18N::get('global', 'zip').':</span></b>');
		$this->infotab_lbl_crc32->set_markup('<b><span size="small">'.I18N::get('global', 'crc32').':</span></b>');

		# PERSONAL
		$this->iPanePersHeadlineLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersHeadlineLbl').':</b>');
		$this->iPanePersTimesPlayedLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersTimesPlayedLbl').':</b>');
		$this->iPanePersLastPlayedLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersLastPlayedLbl').':</b>');
		$this->iPanePersBookmarkedLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersBookmarkedLbl').':</b>');
		$this->iPanePersMetaEditedLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersMetaEditedLbl').':</b>');
		$this->iPanePersMetaExportLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersMetaExportLbl').':</b>');
		$this->iPanePersMetaHiscoreLbl->set_markup('<b>'.I18N::get('global', 'hiscore').':</b>');
		
		$this->iPanePersMetaNotesLbl->set_markup('<b>'.I18N::get('infoPane', 'iPanePersMetaNotesLbl').':</b>');

		# ESEARCH
		$this->iPaneEsearchHeadlineLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchHeadlineLbl').':</b>');
		$this->iPaneEsearchIntroTxt->set_text(I18N::get('infoPane', 'iPaneEsearchIntroTxt'));
		$this->iPaneEsearchOptRunningLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptRunningLbl').':</b>');
		$this->iPaneEsearchOptMultiplayLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptMultiplayLbl').':</b>');
		$this->iPaneEsearchOptFreewareLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptFreewareLbl').':</b>');
		$this->iPaneEsearchOptTrainerLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptTrainerLbl').':</b>');		
		$this->iPaneEsearchOptIntroLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptIntroLbl').':</b>');
		$this->iPaneEsearchOptBugsLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptBugsLbl').':</b>');
		$this->iPaneEsearchOptUsermodLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptUsermodLbl').':</b>');
		$this->iPaneEsearchOptNetplayLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchOptNetplayLbl').':</b>');
		$this->iPaneEsearchOptResetBtn->set_markup(' <b>'.I18N::get('infoPane', 'iPaneEsearchOptResetBtn').'</b>');
		$this->iPaneEsearchHelpLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneEsearchHelpLbl').':</b>');
		
		# DATA
//		$this->iPaneDataLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneDataLbl').':</b>');
		
		# HEADER
		$this->iPaneHeadLbl->set_markup('<b>'.I18N::get('infoPane', 'iPaneHeadLbl').':</b>');
		
		# SEARCH AREA
		$this->search_input_reset_label->set_label(i18n::get('global', 'reset'));
		
	}

	/**
	 * Initialize the main game list
	 * 
	 * connect all needed signal handler
	 *
	 */
	private function initGameList($reload = true){

		# init treeview
		$treeView = FACTORY::get('manager/Treeview');
		$this->newTreeView = $treeView->init($this->gameListScroll);
		
		# configuration
		$this->newTreeView->set_enable_search(false);
		
		# connect selection
		$selection = $treeView->getSelection();
		$selection->connect('changed', array($this, 'show_media_info'));

		# handle left-right arrow keys
		$treeView->connect('key-press-event', array($this, 'onMainlistCursorNavigation'), $selection);
		
		# start selected rom
		$treeView->connect('row-activated', array($this, 'startRom'));
		
		# update metaInformations
		$treeView->connect('button-release-event', array($this, 'show_popup_menu'));

		# init drag-n-drop
		$this->mainListDragAndDropInit($this->newTreeView);
		
		# init the model and assign it to the treeview!
		$this->init_treeview_main();
		
		if ($reload) $this->onInitialRecord();
	}
	
	private function onEccStartup(){
		
	}

	public function eccShutdown($restart = false) {
		
		$title = I18N::get('popup', 'executePostShutdownTaskTitle');
		$task = $this->getShutdownTask();
		if($task){
			$typeTranslated = I18N::get('menu', $task[0]);
			$message = sprintf(I18N::get('popup', 'executePostShutdownTaskMessage%s'), $typeTranslated);
			if (!$this->guiManager->openDialogConfirm($title, $message)){
				$this->unsetShutdownTask();
				return false;
			}			
		}

		
		
		# store state
		
		//GdkWindowState
		//
		//Specifies the state of a toplevel window.
		//Value
		//	
		//Symbolic name
		//	
		//Description
		//  1	Gdk::STATE_WITHDRAWN	The window is not shown.
		//  2	Gdk::STATE_ICONIFIED	The window is minimized.
		//  4	Gdk::STATE_MAXIMIZED	The window is maximized.
		//  8	Gdk::WINDOW_STATE_STICKY	The window is sticky.
		//  16	Gdk::WINDOW_STATE_FULLSCREEN	The window is maximized without decorations
		//  32	Gdk::WINDOW_STATE_ABOVE	The window is kept above other windows.
		//  64	Gdk::WINDOW_STATE_BELOW	The window is kept below other windows.
		
		$guiState = $this->wdo_main->window->get_state();
		if(!in_array($guiState, array(4))) $guiState = 0; # only save maximied
		$this->ini->storeHistoryKey('gui_main_state', $guiState, false);
		
//		print __FUNCTION__.'<pre>';
//		print_r($this->wdo_main->window->get_state());
//		print '</pre>'."\n";
		
		# store the last gui size setup
		$guiSize = $this->wdo_main->get_size();
		$this->ini->storeHistoryKey('gui_main_size', $guiSize[0].'x'.$guiSize[1], false);
		
		# store the last gui position setup
		$guiPosition = $this->wdo_main->get_position();
		list($width, $height) = $guiPosition;
		$this->ini->storeHistoryKey('gui_main_position', $width.'x'.$height);

		# hide main gui first
		# hide here, because otherwise the wrong widow position is returned!
		$this->wdo_main->hide();
		
		# store the with of the navigation area
		$this->ini->storeHistoryKey('vis_navigation_width', $this->hpaned1->get_position(), false);
		
		# store the last selected platform
		$this->ini->storeHistoryKey('navigation_last', $this->_eccident, false);
		
		# store the last selected game!
		if($this->current_media_info['id'] || $this->current_media_info['md_id']){
			$key = $this->current_media_info['id'].'|'.$this->current_media_info['md_id'];
			$this->ini->storeHistoryKey('last_selected_game', $key, false);			
		}
		
		# store the last selected page
		if($this->media_treeview_pager->_p && !$this->get_search_state()){
			$this->ini->storeHistoryKey('last_selected_page', $this->media_treeview_pager->_p, false);	
		}
		
		# now stop the gtk2 application
		gtk::main_quit();
		
		# execute task after exiting the gtk2 app.
		$this->executePostShutdownTasks();
		
		if ($restart) FACTORY::get('manager/Os')->executeProgramDirect(dirname(__FILE__).'/../ecc.exe', 'open', '/fastload');
		
		return true;
		
	}
	
	public $postShutdownTask;
	public function executePostShutdownTasks(){
		if(!$this->postShutdownTask) return false;
		$type = $this->postShutdownTask[0];
		$params = array_slice($this->postShutdownTask, 1);
		$this->dispatchPostShutdownTasks($type, $params);
		return true;
	}
	
	public function setShutdownTask($task){
		$this->postShutdownTask = $task;
		$this->eccShutdown($restart = true);
	}
	
	public function getShutdownTask(){
		return $this->postShutdownTask;
	}
	public function unsetShutdownTask(){
		$this->postShutdownTask = false;
	}
	
	public function dispatchPostShutdownTasks($type, $param = false){
		
		switch ($type){
			case 'imagepackCreateAllThumbnails':
				$dialog = $this->openWaitSplashscreen();
				$systems = $this->getSystemsWithImagepacks(@$param[0]);
				foreach ($systems as $systemIdent => $systemName) {
					$this->updateWaitSplashscreen($dialog, $systemName);
					FACTORY::get('ImagePack')->createAllThumbnails($systemIdent);
				}
			break;
			case 'imagepackRemoveAllThumbnails':
				$dialog = $this->openWaitSplashscreen();
				$systems = $this->getSystemsWithImagepacks(@$param[0]);
				foreach ($systems as $systemIdent => $systemName) {
					$this->updateWaitSplashscreen($dialog, $systemName);
					FACTORY::get('ImagePack')->removeAllThumbnails($systemIdent);
				}
			break;
			case 'imagepackRemoveImagesWithoutRomFile':
				$dialog = $this->openWaitSplashscreen();
				$systems = $this->getSystemsWithImagepacks(@$param[0]);
				foreach ($systems as $systemIdent => $systemName) {
					$availableCrc32 = FACTORY::get('TreeviewData')->getAllCrc32ForSystem($systemIdent);
					if(!$availableCrc32) continue;
					$this->updateWaitSplashscreen($dialog, $systemName);
					FACTORY::get('ImagePack')->removeImagesWithoutRomFile($systemIdent, $availableCrc32);
				}
			break;
			case 'imagepackRemoveEmptyFolder':
				$dialog = $this->openWaitSplashscreen();
				$systems = $this->getSystemsWithImagepacks(@$param[0]);
				foreach ($systems as $systemIdent => $systemName) {
					$this->updateWaitSplashscreen($dialog, $systemName);
					FACTORY::get('ImagePack')->removeEmptyFolder($systemIdent);
				}
			break;
		}
	}
	
	public function getSystemsWithImagepacks($systemIdent = false){
		
		$imagePackManager = FACTORY::get('ImagePack');
		$iniManager = FACTORY::get('manager/IniFile');
		
		$systems = array();
		if($systemIdent) $systems[$systemIdent] = $iniManager->getPlatformName($systemIdent);
		else $systems = $iniManager->getPlatformNavigation(false, false, true);
		
		$out = array();
		foreach ($systems as $systemIdent => $systemName) {
			if(stripos($systemIdent, 'null') === 0 || !$imagePackManager->hasImagePack($systemIdent)) continue;
			while (gtk::events_pending()) gtk::main_iteration();
			$out[$systemIdent] = $systemName;
		}
		return $out;
	}
	
	public function openWaitSplashscreen(){
		$title = I18N::get('popup', 'postShutdownTaskTitle');
		$message = I18N::get('popup', 'postShutdownTaskMessage');
		return $this->guiManager->openDialogWait($title, $message);
	}
	public function updateWaitSplashscreen($dialog, $platformName){
		$title = I18N::get('popup', 'postShutdownTaskTitle');
		$dialog->title->set_markup('<b>'.$title.' ('.$platformName.')</b>');
		while (gtk::events_pending()) gtk::main_iteration();
	}
	
	public static $fileList;
	public static $level = 0;
	public static function readDirRecursive($currentDir) {
		$d = opendir($currentDir);
		while(($currentFilename = readdir($d)) !== false) {
			if ($currentFilename == '.' || $currentFilename == '..') continue;
			$currentPath = realpath($currentDir.DIRECTORY_SEPARATOR.$currentFilename);
			
			if(!$currentPath) continue;
			if (is_dir($currentPath)){
				self::$level++;
				
				self::readDirRecursive($currentPath);
				self::$level--;
			}
			else self::$fileList[] = $currentPath;
			
			if(self::$level == 3) print self::$level." - ".$currentPath."\n";
			
		}
		return self::$fileList;
	}
	
	public function onStateChange($widget, $stateObject){
//		print __FUNCTION__.'<pre>';
//		print_r($stateObject);
//		print '</pre>'."\n";
	}
	
}
$obj_test = new App();
?>
