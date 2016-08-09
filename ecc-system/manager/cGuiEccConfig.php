<?
class GuiEccConfig {
	
	private $gui = false;
	private $ini_data = false;
	private $model_platform = false;
	private $error = false;
	private $languages = array();
	private $language_selected = false;
	/**
	 * 
	 */	
//	public function __construct($gui_obj=false) {
//		if (!$gui_obj) return false;
//		$this->gui = $gui_obj;
//		$this->init();
//		$this->connect_signals();
//		#$this->pf_tree_init();
//	}
	
	/**
	 * 
	 */
//	private function init() {
//		$this->gui->ini->flushIni();
//	}
	
	/**
	 * 
	 */
//	public function update() {
//		// get oriinal global ini
//		$this->ini_data = $this->gui->ini->getIniGlobalWithoutPlatforms();
////		$this->set_elements();
//	}
	
	/**
	 * 
	 */
//	private function connect_signals() {
//		$this->gui->gconf_btn_save->connect_simple('clicked', array($this, 'save_config'));
//	}

	/**
	 * 
	 */
//	private function set_elements() {
//
//		// USER_DAT_CREDITS
//		// ----------------
//		// gconf_dc_author
//		$dc_author = $this->gui->ini->getKey('USER_DAT_CREDITS', 'author');
//		$this->gui->gconf_dc_author->set_text($dc_author);
//		// gconf_dc_website
//		$dc_website = $this->gui->ini->getKey('USER_DAT_CREDITS', 'website');
//		$this->gui->gconf_dc_website->set_text($dc_website);
//		// gconf_dc_email
//		$dc_email = $this->gui->ini->getKey('USER_DAT_CREDITS', 'email');
//		$this->gui->gconf_dc_email->set_text($dc_email);
//		// gconf_dc_comment
//		$dc_comment = $this->gui->ini->getKey('USER_DAT_CREDITS', 'comment');
//		$this->gui->gconf_dc_comment->set_text($dc_comment);
//	}
	
//	public function set_selected_language($combobox) {
//		$this->language_selected = $this->languages[$combobox->get_active_text()];
//	}
//	
//	private function get_selected_language() {
//		return $this->language_selected;
//	}
	
//	public function get_i18n_folder() {
//		$languages = array();
//		$dirHdl = opendir(ECC_BASEDIR.DIRECTORY_SEPARATOR."ecc-system/i18n/");
//		if (!$dirHdl) return $languages;
//		while ($file = readdir($dirHdl)) {
//			if ($file == '.' || $file == '..') continue;
//			$languages[] = $file;
//		}
//		return $languages;
//	}

	/**
	 * 
	 */
//	private function ini_data_is_valid() {
//		$this->error = false;
//
//		$gconf_dc_author = $this->gui->ini->cleanIniString($this->gui->gconf_dc_author->get_text());
//		$this->ini_data['USER_DAT_CREDITS']['author'] = '"'.$gconf_dc_author.'"';
//		
//		$gconf_dc_website = $this->gui->ini->cleanIniString($this->gui->gconf_dc_website->get_text());
//		$this->ini_data['USER_DAT_CREDITS']['website'] = '"'.$gconf_dc_website.'"';
//		
//		$gconf_dc_email = $this->gui->ini->cleanIniString($this->gui->gconf_dc_email->get_text());
//		$this->ini_data['USER_DAT_CREDITS']['email'] = '"'.$gconf_dc_email.'"';
//		
//		$gconf_dc_comment = $this->gui->ini->cleanIniString($this->gui->gconf_dc_comment->get_text());
//		$this->ini_data['USER_DAT_CREDITS']['comment'] = '"'.$gconf_dc_comment.'"';
//		
//		if ($language = $this->get_selected_language()) {
//			$this->ini_data['USER_DATA']['language'] = '"'.$this->get_selected_language().'"';			
//		}
//		
//		return ($this->error) ? false : true;
//	}
	
	/**
	 * 
	 */
//	public function save_config() {
//		
//		$title = I18N::get('popup', 'conf_ecc_save_title');
//		$msg = I18N::get('popup', 'conf_ecc_save_msg');
//		if (!$this->gui->open_window_confirm($title, $msg)) return FALSE;
//
//		if ($this->ini_data_is_valid()) {
//			if ($this->gui->ini->backupIniGlobal() === FALSE) {
//				$msg = "!!!! COULD NOT CREATE BACKUP OF ecc_global.ini !!!!\n";
//				if ($this->gui->open_window_info("ERROR!", $msg)) return FALSE;
//			}
//			$this->save_ecc_global_ini();
//		}
//	}
	
	/**
	 * 
	 */
//	public function save_ecc_global_ini() {
//		unset($this->ini_data['NAVIGATION']);
//		$this->gui->ini->storeIniGlobal($this->ini_data);
//		$this->init();
//		$this->gui->ini->flushIni();
//		$this->gui->update_treeview_nav();
//		$this->gui->onReloadRecord(true, false);
//	}

	/**
	 * 
	 */
//	private function set_error($label) {
//		$label->set_markup('<span color="#ff0000"><b>'.$label->get_text().'</b></span>');
//		$this->error = true;
//	}
//	
//	/**
//	 * 
//	 */
//	private function set_warning($label) {
//		$this->set_error($label);
//		$this->error = false;
//	}
}
?>
