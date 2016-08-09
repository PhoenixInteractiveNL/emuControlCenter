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
		return $this->gui->ecc_release['title']." ".$this->gui->ecc_release['local_release_version']." build ".$this->gui->ecc_release['release_build']." ".$this->gui->ecc_release['release_state']."";
	}
	
	public function createUserfolderIfNeeded($updateIfExists = false) {
		
		$user_folder = $this->gui->ini->getKey('USER_DATA', 'base_path');
		
		// is writeable directory?
		if (!$user_folder && !$this->gui->ini->parentDirIsWriteable($user_folder)) {
			print "not writeable!!!!\n\n";
			return false;
		}
		
		// if directory not found - create default!
		if (!is_dir($user_folder)) $this->gui->ini->setDefaultEccBasePath();
		
		if ((is_dir($user_folder) && !is_dir($user_folder.'null/')) || $updateIfExists === true) {
			try {
				$this->gui->ini->createFolder($user_folder);
				$this->rebuildEccUserFolder(false);
			}
			catch (Exception $e) {
				print "could not create user subfolder!!!!\n\n";
			}
		}
	}
	
	public function rebuildEccUserFolder($show_info_popup=true) {
		
		$this->gui->ini->flushIni();
		
		$user_path_subfolder = $this->gui->ini->getKey('USER_DATA', 'base_path_subfolder');
		$user_path_subfolder_array = (trim($user_path_subfolder)) ?  explode(",", trim($user_path_subfolder)) : array();
		$user_path_subfolder_merged = array_merge(array_flip($this->gui->user_path_subfolder_default), array_flip($user_path_subfolder_array));
		
		$nav_data = $this->gui->ini->getPlatformNavigation();
		
		$nav_data['#_GLOBAL'] = '#_GOBAL';
		foreach ($nav_data as $platform_eccident => $platform_name) {
			
			# convert first
			$this->gui->ini->convertPlatformFolder($platform_eccident);
			
			if ($platform_eccident == '#_GLOBAL') {
				
				# 20070810 refactoring userfolder
				$this->gui->ini->getUserFolder(false, '#_GLOBAL'.DIRECTORY_SEPARATOR.trim('emus'), true);
				
				$eccInfoText = "
".str_repeat('-', 80)."
- This is an emuControlCenter GLOBAL userfolder!
- (".$this->getEccVersionString().")
- You can get the latest version of ecc at http://www.camya.com/
- With ecc, you can manage your roms in an easier way!
".str_repeat('-', 80)."

This folder could be used as global folder for emulators used by more than one platform!
Please dont store your images, roms or datfiles in this folder!

Included subfolder: emus/

".str_repeat('-', 80)."
This folder is initial created with
[ECC_VERSION]
".$this->gui->ecc_release['local_release_version']."
[INITIAL_FOLDER_CREATE]
".date('Y-m-d H:i:s', time())."
".str_repeat('-', 80)."
				";
				
			}
			else {
				foreach ($user_path_subfolder_merged as $subpath => $void) {
					
					# 20070810 refactoring userfolder
					$this->gui->ini->getUserFolder($platform_eccident, DIRECTORY_SEPARATOR.trim($subpath), true);
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
			}
			
			# 20070810 refactoring userfolder
			$eccInfoFile = $this->gui->ini->getUserFolder($platform_eccident).DIRECTORY_SEPARATOR.'emuControlCenter.txt';
			file_put_contents($eccInfoFile, trim($eccInfoText));
			
			if ($platform_eccident != '#_GLOBAL') {
				$platformText = "
<html>
<head><title>emuControlCenter - '".htmlspecialchars($platform_name)." (".htmlspecialchars($platform_eccident).")'</title></head>
<body>
<h2>This package is an emuControlCenter eccUser folder for '".htmlspecialchars($platform_name)." (".htmlspecialchars($platform_eccident).")'</h2>
If you want use this package,<br />
<strong>download emuControlCenter (ecc) here <a href=\"http://www.camya.com/\">http://www.camya.com/</a></strong><br />
and add this folder to the eccUser Folder within the emuControlCenter installation.<br />
<br />
If you want know more about ecc, found any bugs or have good ideas for new features, you can visit the
emuControlCenter<br />
<strong>Forum/Board <a href=\"http://ecc.phoenixinteractive.mine.nu/\">http://ecc.phoenixinteractive.mine.nu/</a></strong><br />
<br />
If you <strong>search for metaData for '".htmlspecialchars($platform_name)."'</strong> generated by the users of emuControlCenter (Titles, Developers, Publishers, Years, Languages aso)
take a look at the <strong>online rom database eccdb <a href=\"http://www.camya.com/eccdb/get/?eccident=".htmlspecialchars($platform_eccident)."\">http://www.camya.com/eccdb/get/?eccident=".$platform_eccident."</a></strong>. You can easily add your informations to this online database from within ecc.
You also can get all meta-informations back from the eccdb using the 'import online datfile' feature!<br />
<br />
<a href=\"http://www.camya.com/\"><img style=\"border: none;\" src=\"http://www.camya.com/ecc/ecc_state.jpg\"></a><br />
<br />
website: <a href=\"http://www.camya.com/\">http://www.camya.com/</a><br />
eccdb: <a href=\"http://www.camya.com/eccdb/\">http://www.camya.com/eccdb/</a><br />
forum/board: <a href=\"http://ecc.phoenixinteractive.mine.nu/\">http://ecc.phoenixinteractive.mine.nu/</a><br />
<br />
<strong>If you like emuControlCenter, feel free to support this project!</strong><br />
<br />
<br />
<i>This folder contains user content, not generated or collected and not distributed by the emuControlCenter team!</i>
</body>
";
				$platfomNameCleaned = preg_replace('/[^a-zA-Z0-9 ]/', ' ', trim($platform_name));
				$platfomNameCleaned = trim($platform_eccident).' - '.trim($platfomNameCleaned);
				$platfomNameCleaned = preg_replace('/\s\s+/', ' ', $platfomNameCleaned);
				$platfomNameCleaned = str_replace(' ', '_', $platfomNameCleaned);
			
				$platfomFile = $this->gui->ini->getUserFolder($platform_eccident).DIRECTORY_SEPARATOR.$platfomNameCleaned.'.html';
				file_put_contents($platfomFile, trim($platformText));
			}

		}
		if ($show_info_popup) {
			$user_folder = $this->gui->ini->getKey('USER_DATA', 'base_path');
			$title = I18N::get('popup', 'conf_userfolder_created_title');
			$msg = sprintf(I18N::get('popup', 'conf_userfolder_created_msg%s%s'), '"'.implode('", "', array_keys($user_path_subfolder_merged)).'"', $user_folder);
			$choice = FACTORY::get('manager/Gui')->openDialogInfo($title, $msg);
		}
	}
	
	public function set_eccheader_image() {
		$img_path = ECC_DIR_SYSTEM.'/images/internal/ecc_header_small.png';
		if (!file_exists($img_path)) die ("missing ecc_header");
		$obj_pixbuff = $this->getPixbuf($img_path);
		$this->gui->img_ecc_header->set_from_pixbuf($obj_pixbuff);
	}
	
	public function open_splash_screen() {
		if (!file_exists("license.txt")) die ("missing license.txt");
		$dlg = new GtkAboutDialog();
		
		$dlg->set_modal(true);
		#$dlg->set_transient_for($this->gui->wdo_main);
		$dlg->set_keep_above(true);
		$dlg->present();
			
		$win_style_original = $dlg->get_style();
		$win_style_temp = $win_style_original->copy();
		$win_style_temp->bg[Gtk::STATE_NORMAL] = GdkColor::parse($this->gui->background_color);
		$dlg->set_style($win_style_temp);
		
		$dlg->set_icon($this->getPixbuf(ECC_DIR_SYSTEM.'/images/internal/ecc_icon_small.ico'));
		$dlg->set_logo($this->getPixbuf(ECC_DIR_SYSTEM.'/images/internal/ecc_teaser_small.png'));
		
		$version = $this->getEccVersionString();
		$website = $this->gui->ecc_release['website'];
		$email = $this->gui->ecc_release['email'];
		
		$dlg->set_translator_credits(trim(
			'
[EN] English translation
-------------------------------------- 
done by ecc himself (ecc@camya.com)

[DE] German translation 
--------------------------------------
done by blackering (aa@aa.de)

[FR] French translation 
--------------------------------------
done by cyrille (aa@aa.fr)
			'
		));
		
		$dlg->set_name("");
		$dlg->set_version($version);
		$dlg->set_copyright($this->gui->ecc_release['info_copyright']);
		$dlg->set_website($website);
		$dlg->set_comments("This is a early beta-version of ecc.\nPlease look for updates at camya.com or email ".$email."\n\nSpecial thanks goes out to PHOENIX for his support!");
		$dlg->set_license(file_get_contents("license.txt"));
		
		$dlg->run();
		$dlg->destroy();
		
		$this->gui->ini->storeHistoryKey('splashscreen_opened', true, false);
	}
	
	public function getPixbuf($imagePath, $width = false, $height = false, $aspectRatio = false, $maxWidth = false, $maxHeight = false) {
		if (!$imagePath || !is_file($imagePath)) return null;
		
		if ($maxWidth || $maxHeight) {
			if ($imageInfo = @getimagesize($imagePath)){
				if ($imageInfo[0] > $maxWidth){
					$width = $maxWidth;
					$aspectRatio = true;
				}
				if ($imageInfo[1] > $maxHeight){
					$height = $maxHeight;
					$aspectRatio = true;
				}
			}
		}
		
		if ($aspectRatio && $width && $height){
			try{
				return GdkPixbuf::new_from_file_at_size($imagePath, $width, $height);	
			}
			catch(PhpGtkGErrorException $e){
				return null;
			}
		}
		
		//if (!file_exists($imagePath)) return null;
		try {
			$oPixbuf = GdkPixbuf::new_from_file($imagePath);
		}
		catch (PhpGtkGErrorException $e) {
			return null;
		}
		
		// resizing
		if ($oPixbuf !== null && $width && $height) {
			$type = Gdk::INTERP_BILINEAR;
			$oPixbuf = $oPixbuf->scale_simple((int)$width, (int)$height, $type);
		}
		return  $oPixbuf;
	}
}

?>
