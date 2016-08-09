<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	de (german)
 * author:	franz schneider / andreas scheibel
 * date:	2007/05/13
 * ------------------------------------------
 */
$i18n['popupConfig'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------

	/* ECC */
	'lbl_ecc_hdl' =>
		"Konfiguration",
	'lbl_ecc_userfolder' =>
		"Userverzeichnis (f�r Bilder und Exportdateien)",
	'lbl_ecc_userfolder_button' =>
		"Ordner �ndern",
	'title_ecc_userfolder_popup' =>
		"Verzeichnis w�hlen",
	/* ECC-OPTIONS */
	'lbl_ecc_otp_hdl' =>
		"Optionen",
	'lbl_ecc_opt_detail_pp' =>
		"Details pro Seite",
	'lbl_ecc_opt_list_pp' =>
		"Dateien pro Listenseite",
	'lbl_ecc_opt_language' =>
		"Sprache",
	/* ECC-COLOR&FONTS */
	'lbl_ecc_colfont_hdl' =>
		"Farben und Schriftarten",
	'lbl_ecc_colfont_font_list' =>
		"Listen Schriftart",
	'title_ecc_colfont_font_list_popup' =>
		"Bitte w�hle eine Schriftart f�r die Listen/Detailansicht.",
	'lbl_ecc_colfont_font_global' =>
		"GLOBALE Schriftart",
	'title_ecc_colfont_font_global' =>
		"Bitte w�hle eine GLOBALE Schriftart ",
	/* ECC-STARTUP */
	'lbl_ecc_startup_hdl' =>
		"Startup Konfiguration",
	'btn_ecc_startup' =>
		"Konfiguration �ffnen",
	
	/* EMU-PLATFORM */
	'lbl_emu_hdl%s%s' =>
		"%s (%s)",
	'lbl_emu_platform_name' =>
		"Platform Name",
	'lbl_emu_platform_category' =>
		"Platform Kategorie",
	/* EMU-ASSING */
	'lbl_emu_assign_hdl%s' =>
		"Emulator zuweisen (%s)",
	'lbl_emu_assign_path' =>
		"Ordner -> Emulator",
	'btn_emu_assign_path_select' =>
		"Emulator ausw�hlen",
	'title_emu_assign_path_select_popup%s' =>
		"Emulator ausw�hlen f�r %s",
	'lbl_emu_assign_parameter' =>
		"Komandozeilen-Parameter",
	'lbl_emu_assign_escape' =>
		"Pfad escapen",
	'lbl_emu_assign_eightdotthree' =>
		"8.3 Dateinamen",
	'lbl_emu_assign_nameonly' =>
		"Nur Dateinamen",
	'lbl_emu_assign_noextension' =>
		"Keine Dateierweiterungen",
	
	/* DAT */
	'lbl_dat_hdl' =>
		"Datfile Konfiguration",
	'lbl_dat_author' =>
		"Autor",
	'lbl_dat_website' =>
		"Website",
	'lbl_dat_email' =>
		"Email",
	'lbl_dat_comment' =>
		"Kommentar",
	/* DAT-OPTIONS */
	'lbl_dat_opt_hdl' =>
		"Optionen",
	'lbl_dat_opt_namestrip' =>
		"Bereinige Romcenter Dateien",
		
	/* 0.9 FYEO 3 */
	'lbl_img_otp_list_hdl' =>
		"Option - Rom Details",
	'lbl_img_otp_list_imagesize' =>
		"Bildgr��e",
	'lbl_img_otp_list_aspectratio' =>
		"Originalformat",
	/* 0.9 FYEO 4 */
	'lbl_img_otp_list_fastrefresh' =>
		"Listen schnell aufbauen",
		
	/* 0.9 FYEO 9 */
	'confEccStatusLogCheck' =>
		"Aktiviere logging",
	'confEccStatusLogOpen' =>
		"�ffne Logfiles",
		
	/* 0.9.1 FYEO 5 */
	'tab_label_emulators' =>
		"Emulatoren",
	'tab_label_general' =>
		"General",
	'tab_label_datfiles' =>
		"Datfiles",
	'tab_label_images' =>
		"Bilder",
	'tab_label_colorsandfonts' =>
		"Farben und Schrift",
	
	/* 0.9.2 FYEO 1 */
	'lbl_emu_tips' =>
		"Emulatoren Tips / Tricks / Downloads",
	'lbl_img_opt_conv' =>
		"Bildkonvertierung",
	'lbl_img_opt_conv_quality' =>
		"Thumb Qualit�t",
	'lbl_img_opt_conv_quality_def%s' =>
		"(Voreinstellung: %s)",
	'lbl_img_opt_conv_minsize' =>
		"Min. Originalgr��e",
	'lbl_img_opt_conv_minsize_def%s' =>
		"(Voreinstellung: %s)",
	'lbl_col_opt_global' =>
		"Global",
	'lbl_col_opt_list' =>
		"Liste",
	'lbl_col_opt_options' =>
		"Optionen",

	/* 0.9.2 FYEO 3 */
	'lbl_emu_assign_use_eccscript' =>
		"eccScript",

	/* 0.9.2 FYEO 5 */
	'lbl_emu_assign_edit_eccscript' =>
		"eccScript �ndern",
	'lbl_emu_assign_edit_eccscript_error' =>
		"Du kannst erst dann ein eccScript anlegen, wenn du bereits ein Emulator ausgew�hlt hast!",

	/* 0.9.2 FYEO 6 */
	'lbl_emu_assign_eccscript_hdl' =>
		"eccScript Optionen",
	'lbl_emu_assign_delete_eccscript' =>
		"l�schen",
	'msg_emu_assign_delete_eccscript%s' =>
		"Willst Du das eccScript\n\n%s\n\nwirklich l�schen?",

	/* 0.9.2 FYEO 8 */
	'tab_label_startup' =>
		"Start",
	'startConfHdl' =>
		"Startkonfiguration",
	'startConfSoundHdl' =>
		"Startsound abspielen",
	'startConfOptHdl' =>
		"Optionen",
	'startConfUpdate' =>
		"Beim starten nach updates suchen",
	'startConfMinimize' =>
		"Minimieren in die Schnellstartleiste",
	'startConfSoundSelect' =>
		"Sound ausw�hlen",

	/* 0.9.2 FYEO 9 */
	'lbl_preview_impossible' =>
		"Vorschau nicht m�glich. Fehlende oder falsche Einstellungen!",

	/* 0.9.2 FYEO 10 */
	'lbl_emu_assign_edit_eccscript_error_notfound' =>
		"Es wurde kein Emulator gefunden! Bitte zuerst einen Emulator ausw�hlen!",
	'lbl_emu_assign_create_eccscript' =>
		"Neues eccScript",
	'emu_info_nodata' =>
		"Zur Zeit sind noch keine informationen verf�gbar ...",
	'emu_info_footer' =>
		"Vielleicht kennst du einige gute Emulatoren f�r diese Platform!\nDu kannst deine Informationen in das\nemuControlCenter-Forum http://ecc.phoenixinteractive.mine.nu/\nposten!",
	
	/* 0.9.2 FYEO 11 */
	'title_startup_select_sound' =>
		"Select a startup sound",

	/* 0.9.2 FYEO 14 */
	'title_emu_assign_found_eccscript' =>
		"eccScript gefunden",
	'msg_emu_assign_found_eccscript%s' =>
		"Ein eccScript wurde f�r den gew�hlten Emulator gefunden!\n\nSoll dieses eccScript (%s) aktiviert werden?",
	'title_popup_save' =>
		"Neustart",
	'msg_popup_save' =>
		"Das emuControlCenter muss neu gestartet werden, damit die �nderungen wirksam werden!",

	/* 0.9.2 FYEO 15 */
	'title_emu_found_eccscript_preview' =>
		"Informationen:",
	'title_emu_found_eccscript_nopreview' =>
		"Keine Informationen verf�gbar!",
);
?>