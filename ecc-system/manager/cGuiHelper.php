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
		return $this->gui->ecc_release['title']." ".$this->gui->ecc_release['release_version']." ".$this->gui->ecc_release['release_build']." ".$this->gui->ecc_release['release_state']."";
	}
	
	public function createUserfolderIfNeeded() {

		$user_folder = $this->gui->ini->get_ecc_ini_key('USER_DATA', 'base_path');
		
		// is writeable directory?
		if (!$user_folder && !$this->gui->ini->parentIsWritable($user_folder)) {
			print "not writeable!!!!\n\n";
			return false;
		}
		
		// if directory not found - create default!
		if (!is_dir($user_folder) || !is_dir($user_folder.'null/')) {
			$this->gui->ini->setDefaultEccBasePath();
			try {
				$this->gui->ini->create_folder($user_folder);
				$this->rebuildEccUserFolder(false);
			}
			catch (Exception $e) {
				print "could not create user subfolder!!!!\n\n";
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

			
$eccInfoSubfolder = implode("/\n", array_flip($user_path_subfolder_merged))."/";
$eccInfoText = "
".str_repeat('-', 80)."
- This is an emuControlCenter userfolder!
- (".$this->getEccVersionString().")
- You can get the latest version of ecc at http://www.camya.com/
- With ecc, you can manage your roms in an easier way!
".str_repeat('-', 80)."

This folder contains data for
Platform: $platform_name ($platform_eccident)

Included subfolder:
".$eccInfoSubfolder."

".str_repeat('-', 80)."
This folder is initial created with
[ECC_VERSION]
".$this->gui->ecc_release['local_release_version']."
[INITIAL_FOLDER_CREATE]
".date('Y-m-d H:i:s', time())."
".str_repeat('-', 80)."
";
			
			$eccInfoFile = $this->gui->ini->get_ecc_ini_user_folder($platform_eccident).DIRECTORY_SEPARATOR.'emuControlCenter.txt';
			file_put_contents($eccInfoFile, trim($eccInfoText));

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
	
		public function setEccSupportImage() {
		$img_path = ECC_BASEDIR.'/ecc-system/images/eccsys/internal/ecc_logo_support.png';
		if (!file_exists($img_path)) die ("missing ecc_header");
		$this->gui->eccImageSupport->set_from_pixbuf(GdkPixbuf::new_from_file($img_path));
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
		
		
		$version = $this->getEccVersionString();
		$website = $this->gui->ecc_release['website'];
		$email = $this->gui->ecc_release['email'];
		
		$dlg->set_name("");
		$dlg->set_version($version);
		$dlg->set_copyright($this->gui->ecc_release['info_copyright']);
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
