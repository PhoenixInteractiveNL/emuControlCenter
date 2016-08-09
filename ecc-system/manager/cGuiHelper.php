<?php
/*
 * Created on 03.10.2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GuiHelper {
	
	private $gui;
	
	public function __construct($gui) {
		$this->gui = $gui;
	}
	
	public function getEccVersionString() {
		return "emuControlCenter ".$this->gui->ecc_release['release_version']." ".$this->gui->ecc_release['release_build']." ".$this->gui->ecc_release['release_state']."";
	}
	
	public function createUserfolderIfNeeded() {
		$user_folder = $this->gui->ini->get_ecc_ini_key('USER_DATA', 'base_path');
		if (!is_dir($user_folder)) {
			$title = I18N::get('popup', 'conf_userfolder_notset_title');
			$msg = sprintf(I18N::get('popup', 'conf_userfolder_notset_msg%s'), $user_folder);
			$choice = $this->gui->open_window_confirm($title, $msg);
			if (!$choice) {
				#print ("\n\n!!! MISSING USER-FOLDER: EDIT conf/ecc_general.ini\n\n");
			} else {
				$this->gui->ini->create_folder($user_folder);
				$this->gui->rebuildEccUserFolder();
			}
		}
	}
	
	public function rebuildEccUserFolder($show_info_popup=true) {
		$this->gui->ini->reload();
		
		$user_path_subfolder = $this->gui->ini->get_ecc_ini_key('USER_DATA', 'base_path_subfolder');
		$user_path_subfolder_array = (trim($user_path_subfolder)) ?  explode(",", trim($user_path_subfolder)) : array();
		$user_path_subfolder_merged = array_merge(array_flip($this->gui->user_path_subfolder_default), array_flip($user_path_subfolder_array));
		
		$nav_data = $this->gui->ini->get_ecc_platform_navigation();
		foreach ($nav_data as $platform_eccident => $platform_name) {
			foreach ($user_path_subfolder_merged as $subpath => $void) {
				$this->gui->ini->get_ecc_ini_user_folder($platform_eccident.DIRECTORY_SEPARATOR.trim($subpath), true);
			}
		}
		if ($show_info_popup) {
			$user_folder = $this->gui->ini->get_ecc_ini_key('USER_DATA', 'base_path');
			$title = I18N::get('popup', 'conf_userfolder_created_title');
			$msg = sprintf(I18N::get('popup', 'conf_userfolder_created_msg%s%s'), '"'.implode('", "', array_keys($user_path_subfolder_merged)).'"', $user_folder);
			$choice = $this->gui->open_window_info($title, $msg);
		}
	}
	
	public function set_eccheader_image() {
		$img_path = ECC_BASEDIR.'/ecc-system/images/eccsys/internal/ecc_header_small.png';
		if (!file_exists($img_path)) die ("missing ecc_header");
		$this->gui->img_ecc_header->set_from_pixbuf(GdkPixbuf::new_from_file($img_path));
	}
	
	public function open_splash_screen() {
		if (!file_exists("license.txt")) die ("missing license.txt");
		$dlg = new GtkAboutDialog();
		
		$dlg->set_modal(true);
		$dlg->set_transient_for($this->gui->wdo_main);
			
		$win_style_original = $dlg->get_style();
		$win_style_temp = $win_style_original->copy();
		$win_style_temp->bg[Gtk::STATE_NORMAL] = GdkColor::parse($this->gui->background_color);
		$dlg->set_style($win_style_temp);
		
		$dlg->set_icon(GdkPixbuf::new_from_file(ECC_BASEDIR.'/ecc-system/images/eccsys/ecc_icon_camya.png'));
		$dlg->set_logo(GdkPixbuf::new_from_file(ECC_BASEDIR.'/ecc-system/images/eccsys/platform/ecc_ecc_teaser.png'));
		
		// ecc-informations from ini
		//$version = "".$this->gui->ecc_release['release_version']." ".$this->gui->ecc_release['release_build']." ".$this->gui->ecc_release['release_state']."";
		
		$version = $this->getEccVersionString();
		$website = $this->gui->ecc_release['website'];
		$email = $this->gui->ecc_release['email'];
		
//		$dlg->set_name("emuControlCenter");
		$dlg->set_name("");
		$dlg->set_version($version);
		$dlg->set_copyright("Copyright (c) 2006 Andreas Scheibel");
		$dlg->set_website($website);
		$dlg->set_comments("This is a early beta-version of ecc.\nPlease look for updates at camya.com or email ".$email."\n\nSpecial thanks goes out to PHOENIX for his support!");
		$dlg->set_license(file_get_contents("license.txt"));
		
		$dlg->run();
		$dlg->destroy();
		
		$this->gui->ini->write_ecc_histroy_ini('splashscreen_opened', true, false);
	}
	
	
	public function setBackgroundImage($window=false, $fileName=false, $mask=255) {
		if (!is_object($window)) return false;
		if (!$fileName) return false;
		
		$pixbuf  =GdkPixbuf::new_from_file($fileName);
		list($pixmap,$mask) = $pixbuf->render_pixmap_and_mask($mask);
		
		$style = $window->get_style();
		$style = $style->copy();
		
		$style->bg_pixmap[Gtk::STATE_NORMAL] = $pixmap;
		$window->set_style($style);
	}
	
}

?>
