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
	public function __construct($gui_obj=false) {
		if (!$gui_obj) return false;
		$this->gui = $gui_obj;
		$this->init();
		$this->connect_signals();
		$this->pf_tree_init();
	}
	
	/**
	 * 
	 */
	private function init() {
		$this->gui->ini->reload();
		$this->gui->gconf_ud_folder_label->set_text('Folder');
	}
	
	/**
	 * 
	 */
	public function update() {
		// get oriinal global ini
		$this->ini_data = $this->gui->ini->get_ecc_global_ini();
		$this->set_elements();
	}
	
	/**
	 * 
	 */
	private function connect_signals() {
		$this->gui->gconf_btn_save->connect_simple('clicked', array($this, 'save_config'));
		$this->gui->gconf_ud_folder_btn->connect_simple('clicked', array($this, 'get_path'));
	}
	
	/**
	 * 
	 */
	public function create_user_folder() {
		$user_folder = $this->gui->gconf_ud_folder->get_text();
		if ($this->gui->ini->create_folder($user_folder)) {
			FACTORY::get('manager/GuiHelper')->rebuildEccUserFolder(false);
			#$this->gui->rebuild_user_folder(false);
			$this->init();
		}
	}

	/**
	 * 
	 */
	private function set_elements() {
		$this->pf_tree_fill();
		// USER_DATA
		// ----------------
		// gconf_ud_folder
		$user_folder = $this->gui->ini->get_ecc_ini_key('USER_DATA', 'base_path');
		if (!$user_folder || !realpath($user_folder)) {
			$this->set_error($this->gui->gconf_ud_folder_label);
		}
		$this->gui->gconf_ud_folder->set_text($user_folder);
		// gconf_ud_folder

		// USER_DAT_CREDITS
		// ----------------
		// gconf_dc_author
		$dc_author = $this->gui->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'author');
		$this->gui->gconf_dc_author->set_text($dc_author);
		// gconf_dc_website
		$dc_website = $this->gui->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'website');
		$this->gui->gconf_dc_website->set_text($dc_website);
		// gconf_dc_email
		$dc_email = $this->gui->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'email');
		$this->gui->gconf_dc_email->set_text($dc_email);
		// gconf_dc_comment
		$dc_comment = $this->gui->ini->get_ecc_ini_key('USER_DAT_CREDITS', 'comment');
		$this->gui->gconf_dc_comment->set_text($dc_comment);
		
		$this->languages = $this->get_i18n_folder();
		$language_selected =  $this->gui->ini->get_ecc_ini_key('USER_DATA', 'language');
		$index =  array_search($language_selected, $this->languages);
		$this->dropdown_language = new IndexedCombobox($this->gui->gconf_ud_language, false, $this->languages, false, $index);
		$this->gui->gconf_ud_language->connect("changed", array($this, 'set_selected_language'));
	}
	
	public function set_selected_language($combobox) {
		$this->language_selected = $this->languages[$combobox->get_active_text()];
	}
	
	private function get_selected_language() {
		return $this->language_selected;
	}
	
	public function get_i18n_folder() {
		$languages = array();
		$dirHdl = opendir(ECC_BASEDIR.DIRECTORY_SEPARATOR."ecc-system/i18n/");
		if (!$dirHdl) return $languages;
		while ($file = readdir($dirHdl)) {
			if ($file == '.' || $file == '..') continue;
			$languages[] = $file;
		}
		return $languages;
	}

	/**
	 * 
	 */
	private function ini_data_is_valid() {
		$this->error = false;
		
		// get data from treeview model
		$this->model_platform->foreach(array($this, 'pf_tree_get_all_data'));

		$gconf_ud_folder = $this->gui->gconf_ud_folder->get_text();
		if (!$gconf_ud_folder || !realpath($gconf_ud_folder)) {
			$this->set_warning($this->gui->gconf_ud_folder_label);
		}
		else {
			$this->init();
		}
		$this->ini_data['USER_DATA']['base_path'] = '"'.$gconf_ud_folder.'"';
		
		$gconf_dc_author = $this->gui->ini->strip_danger_chars($this->gui->gconf_dc_author->get_text());
		$this->ini_data['USER_DAT_CREDITS']['author'] = '"'.$gconf_dc_author.'"';
		
		$gconf_dc_website = $this->gui->ini->strip_danger_chars($this->gui->gconf_dc_website->get_text());
		$this->ini_data['USER_DAT_CREDITS']['website'] = '"'.$gconf_dc_website.'"';
		
		$gconf_dc_email = $this->gui->ini->strip_danger_chars($this->gui->gconf_dc_email->get_text());
		$this->ini_data['USER_DAT_CREDITS']['email'] = '"'.$gconf_dc_email.'"';
		
		$gconf_dc_comment = $this->gui->ini->strip_danger_chars($this->gui->gconf_dc_comment->get_text());
		$this->ini_data['USER_DAT_CREDITS']['comment'] = '"'.$gconf_dc_comment.'"';
		
		if ($language = $this->get_selected_language()) {
			$this->ini_data['USER_DATA']['language'] = '"'.$this->get_selected_language().'"';			
		}
		
		return ($this->error) ? false : true;
	}
	
	/**
	 * 
	 */
	public function save_config() {
		
		$title = I18N::get('popup', 'conf_ecc_save_title');
		$msg = I18N::get('popup', 'conf_ecc_save_msg');
		if (!$this->gui->open_window_confirm($title, $msg)) return FALSE;

		if ($this->ini_data_is_valid()) {
			if ($this->gui->ini->backup_ini_global() === FALSE) {
				$msg = "!!!! COULD NOT CREATE BACKUP OF ecc_global.ini !!!!\n";
				if ($this->gui->open_window_info("ERROR!", $msg)) return FALSE;
			}
			$this->save_ecc_global_ini();
		}
	}
	
	/**
	 * 
	 */
	public function get_path() {
		
		$oOs = FACTORY::get('manager/Os');
		
		// get path from textarea
		$path = realpath($this->gui->gconf_ud_folder->get_text());
		$title = I18N::get('popup', 'conf_ecc_userfolder_filechooser_title');
		
		#$path_new = $this->gui->openFileChooserDialog($title, $path, false, Gtk::FILE_CHOOSER_ACTION_SELECT_FOLDER);
		$path_new = $oOs->openChooseFolderDialog($path, $title);

//		// ABS-PATH TO REL-PATH...		
//		if ($path_new) {
//			$path_new = realpath($path_new);
//			if ($path_new!="" && strpos($path_new, ECC_BASEDIR) == 0) {
//				$path_new = str_replace(ECC_BASEDIR, ECC_BASEDIR_OFFSET, $path_new);
//			};
//		}
//		if ($path_new && strpos($path_new, -1) !== DIRECTORY_SEPARATOR) $path_new = $path_new.DIRECTORY_SEPARATOR;

		// ABS-PATH TO REL-PATH...
		// 20061116 as
		$path_new = $oOs->eccSetRelativeDir($path_new);
		
		// set new text
		if ($path_new) $this->gui->gconf_ud_folder->set_text($path_new);
	}
	
	/**
	 * 
	 */
	public function save_ecc_global_ini() {
		$this->gui->ini->write_ini_global($this->ini_data);
		$this->init();
		$this->create_user_folder();
		$this->gui->ini->reload();
		$this->gui->update_treeview_nav();
		$this->gui->onReloadRecord(true, false);
	}
	
	/**
	 * 
	 */
	private function pf_tree_init() {
		$model = new GtkListStore(
			Gtk::TYPE_BOOLEAN,
			Gtk::TYPE_STRING,
			Gtk::TYPE_STRING,
			Gtk::TYPE_STRING
		);
		$view = $this->gui->gconf_pf_tree;
		
		#$model->connect("rows-reordered", array($this, 'pf_tree_change_order'));
		
		$view->set_model($model);
		
		// TOGGLE COLUMS
		$renderer_toggle = new GtkCellRendererToggle();
		$renderer_toggle->set_property('activatable', true);
		$renderer_toggle->connect('toggled', array($this, "pf_tree_toggle_state"), $model);
		
		// pf_state_toggle
		$column_pf_state_toggle = new GtkTreeViewColumn('ID', $renderer_toggle, 'active',0);
		$view->append_column($column_pf_state_toggle);
		
		// pf_name
		$renderer_text_1 = new GtkCellRendererText();
		$column_pf_eccident = new GtkTreeViewColumn('ID', $renderer_text_1, 'text',1);
		$view->append_column($column_pf_eccident);

		// pf_state
		$renderer_text_2 = new GtkCellRendererText();
		$column_pf_name = new GtkTreeViewColumn('ID', $renderer_text_2, 'text',2);
		$view->append_column($column_pf_name);
		
		// pf_state
		$renderer_text_3 = new GtkCellRendererText();
		$column_pf_extensions = new GtkTreeViewColumn('ID', $renderer_text_3, 'text',3);
		//$column_pf_extensions->set_sizing(Gtk::TREE_VIEW_COLUMN_FIXED);
		$view->append_column($column_pf_extensions);

		
		$this->model_platform = $model;
	}
	
	
	function pf_tree_change_order($store, $path, $iter) {
		
		#$test = $this->gui->gconf_pf_tree->get_selection();
		#list ($m, $i) = $test->get_selected();
		
		#$path_source = $store->get_iter($m->get_path($i));
		#$path_destination = $store->get_iter($store->get_path($iter));
		
		#print "$path_source -> $path_destination\n";
		
		#$store->insert_after($path_source, $path_destination);
	
		#print "1 ".get_class($store)."\n";
		#print "2 ".get_class($path)."\n";
		#print "3 ".get_class($iter)."\n";
		
	}
	
	/**
	 * 
	 */
	public function pf_tree_toggle_state($renderer, $row, $store) {
		$iter = $store->get_iter($row);
	    $store->set(
	        $iter,
	        0,
	        !$store->get_value($iter, 0)
	    );
	}
	
	/**
	 * 
	 */
	public function pf_tree_get_all_data($store, $path, $iter) {
	    if ($iter) {
	    	$state = $store->get_value($iter, 0);
	    	$eccident = $store->get_value($iter, 1);
	    	$this->ini_data['NAVIGATION'][$eccident] = (int)$state;
	    }
	}
	
	/**
	 * 
	 */
	private function pf_tree_fill() {
		$this->model_platform->clear();
		$test = $this->gui->ini->get_ecc_platform_navigation(false, false, true);
		foreach ($test as $eccident => $pf_name) {
			if ($eccident!='null') {
				$platform_data = $this->gui->ini->get_ecc_platform_ini($eccident);
				
				$pf_extensions = "";
				if (count($platform_data['EXTENSIONS'])) {
					$pf_extensions = "*.".implode(",*.", array_keys($platform_data['EXTENSIONS']));
				}
				$state = ($this->ini_data['NAVIGATION'][$eccident]) ? true : false;
				$this->model_platform->append(
					array(
						$state,
						$eccident,
						$pf_name,
						$pf_extensions,
					)
				);
			}
		}
	}

	/**
	 * 
	 */
	private function set_error($label) {
		$label->set_markup('<span color="#ff0000"><b>'.$label->get_text().'</b></span>');
		$this->error = true;
	}
	
	/**
	 * 
	 */
	private function set_warning($label) {
		$this->set_error($label);
		$this->error = false;
	}
}
?>
