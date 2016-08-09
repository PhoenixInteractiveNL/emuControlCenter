<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	en (english)
 * author:	andreas scheibel
 * date:	2006/12/31 
 * ------------------------------------------
 */
$i18n['popupConfig'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------

	/* ECC */
	'lbl_ecc_hdl' =>
		"Configure emuControlCenter",
	'lbl_ecc_userfolder' =>
		"User folder (for images and exports)",
	'lbl_ecc_userfolder_button' =>
		"Change folder",
	'title_ecc_userfolder_popup' =>
		"Select the new userfolder postion",
	/* ECC-OPTIONS */
	'lbl_ecc_otp_hdl' =>
		"Options",
	'lbl_ecc_opt_detail_pp' =>
		"Detail per page",
	'lbl_ecc_opt_list_pp' =>
		"List per page",
	/* ECC-COLOR&FONTS */
	'lbl_ecc_colfont_hdl' =>
		"Colors  and Fonts",
	'lbl_ecc_colfont_font_list' =>
		"List Font and Size",
	'title_ecc_colfont_font_list_popup' =>
		"Please select a Font for List/Detail view",
	'lbl_ecc_colfont_font_global' =>
		"GLOBAL Font and Size",
	'title_ecc_colfont_font_global' =>
		"Please select a global Font",
	/* ECC-STARTUP */
	'lbl_ecc_startup_hdl' =>
		"Startup",
	'btn_ecc_startup' =>
		"Open startup configuration",
	
	/* EMU-PLATFORM */
	'lbl_emu_hdl%s%s' =>
		"%s (%s)",
	'lbl_emu_platform_name' =>
		"Platform name",
	'lbl_emu_platform_category' =>
		"Platform category",
	/* EMU-ASSING */
	'lbl_emu_assign_hdl%s' =>
		"Emulator assignment (%s)",
	'lbl_emu_assign_path' =>
		"Path to emulator",
	'btn_emu_assign_path_select' =>
		"Select emulator",
	'title_emu_assign_path_select_popup%s' =>
		"Select emulator for %s",
	'lbl_emu_assign_parameter' =>
		"Commandline parameter",
	'lbl_emu_assign_escape' =>
		"escape path",
	'lbl_emu_assign_eightdotthree' =>
		"8.3 Filename",
	'lbl_emu_assign_nameonly' =>
		"filename only",
	'lbl_emu_assign_noextension' =>
		"no extension",
	
	/* DAT */
	'lbl_dat_hdl' =>
		"Configure datfile",
	'lbl_dat_author' =>
		"Author",
	'lbl_dat_website' =>
		"Website",
	'lbl_dat_email' =>
		"Email",
	'lbl_dat_comment' =>
		"Comment",
	/* DAT-OPTIONS */
	'lbl_dat_opt_hdl' =>
		"Options",
	'lbl_dat_opt_namestrip' =>
		"Clean romcenter datfiles",
		
	/* 0.9 FYEO 3 */
	'lbl_img_otp_list_hdl' =>
		"Options - Rom details",
	'lbl_img_otp_list_imagesize' =>
		"Imagesize",
	'lbl_img_otp_list_aspectratio' =>
		"Aspect ratio",
	/* 0.9 FYEO 4 */
	'lbl_img_otp_list_fastrefresh' =>
		"Fast refresh",
		
	/* 0.9 FYEO 9 */
	'confEccStatusLogCheck' =>
		"Activate logging",
	'confEccStatusLogOpen' =>
		"Show logfiles",
		
	/* 0.9.1 FYEO 5 */
	'tab_label_emulators' =>
		"Emulators",
	'tab_label_general' =>
		"General",
	'tab_label_datfiles' =>
		"DAT files",
	'tab_label_images' =>
		"Images",
	'tab_label_colorsandfonts' =>
		"Colors and Fonts",
	
	/* 0.9.2 FYEO 1 */
	'lbl_emu_tips' =>
		"Known emulator links and infos",
	'lbl_img_opt_conv' =>
		"Options - ImageConverter",
	'lbl_img_opt_conv_quality' =>
		"Thumb Quality",
	'lbl_img_opt_conv_quality_def%s' =>
		"(Default: %s)",
	'lbl_img_opt_conv_minsize' =>
		"Min original size",
	'lbl_img_opt_conv_minsize_def%s' =>
		"(Default: %s)",
	'lbl_col_opt_global' =>
		"Global",
	'lbl_col_opt_list' =>
		"List",
	'lbl_col_opt_options' =>
		"Options",

	/* 0.9.2 FYEO 3 */
	'lbl_emu_assign_use_eccscript' =>
		"eccScript",
	
	/* 0.9.2 FYEO 5 */
	'lbl_emu_assign_edit_eccscript' =>
		"Edit eccScript",	
	'lbl_emu_assign_edit_eccscript_error' =>
		"You can add script, if you have added an emulator!",	

	/* 0.9.2 FYEO 6 */
	'lbl_emu_assign_eccscript_hdl' =>
		"eccScript options",
	'lbl_emu_assign_delete_eccscript' =>
		"delete",
	'msg_emu_assign_delete_eccscript%s' =>
		"Remove the eccScript\n\n%s\n\nAre you sure?",

	/* 0.9.2 FYEO 8 */
	'tab_label_startup' =>
		"Startup",
	'startConfHdl' =>
		"Startup configuration",
	'startConfSoundHdl' =>
		"Play startup sound",
	'startConfOptHdl' =>
		"Options",
	'startConfUpdate' =>
		"Check for updates on startup",
	'startConfMinimize' =>
		"Minimize to tray",
	'startConfSoundSelect' =>
		"Select sound",
	
	/* 0.9.2 FYEO 9 */
	'lbl_preview_impossible' =>
		"Preview not possible. Missing or wrong settings!",

	/* 0.9.2 FYEO 10 */
	'lbl_emu_assign_edit_eccscript_error_notfound' =>
		"Could not find an emulator! Please choose an emulator first!",
	'lbl_emu_assign_create_eccscript' =>
		"Create eccScript",
	'emu_info_nodata' =>
		"No informations available yet...",
	'emu_info_footer%s' =>
		"Maybe you know an good emulator for this platform!\nYou can add your infos to the Forum/Board at\n%s",
	
	/* 0.9.2 FYEO 11 */
	'title_startup_select_sound' =>
		"Select a startup sound",

	/* 0.9.2 FYEO 14 */
	'title_emu_assign_found_eccscript' =>
		"eccScript found",
	'msg_emu_assign_found_eccscript%s' =>
		"A eccScript was found for the selected emulator!\n\nActivate this eccScript %s",
	'title_popup_save' =>
		"restart ecc",
	'msg_popup_save' =>
		"Restart emuControlCenter to see the changes?",
	
	/* 0.9.2 FYEO 15 */
	'title_emu_found_eccscript_preview' =>
		"Informations:",
	'title_emu_found_eccscript_nopreview' =>
		"No informations available!",

	/* 0.9.6 FYEO 05 */
	'lbl_emu_assign_executeinemufolder' =>
		"Start from Emufolder",
	'lbl_preview_selectEmuFirst' =>
		"You havent selected an emulator! Use the select emulator button above!",

	/* 0.9.6 FYEO 13 */


	'winTitleConfiguration' =>
		"Configuration",

	'colOptGlobalFont' =>
		"Font",

	'colOptListBg0' =>
		"Background",
	'colOptListBg1' =>
		"Background 1",
	'colOptListBg2' =>
		"Background 2",
	'colOptListBgHilight' =>
		"Background selected",
	'colOptListBgImage' =>
		"Image background",
	'colOptListText' =>
		"Text",
	'colOptListTextHilight' =>
		"Text selected",
	'colOptListFont' =>
		"Font",

	'colOptOptionsBg1' =>
		"Background 1",
	'colOptOptionsBgHilight' =>
		"Background selected",
	'colOptOptionsText' =>
		"Text",

	/* 0.9.6 FYEO 17 */
	'colImgSlotUnsetBg' =>
		"Background unused",
	'colImgSlotSetSelect' =>
		"Background unused selected",
	'colImgSlotSetBg' =>
		"Background used",
	'colImgSlotUnsetSelect' =>
		"Background used selected",
	'colImgSlotText' =>
		"Text",

	'colOptOptionsBg2' =>
		"Background 2",

	'tabEmuConfig' =>
		"Emulator config",
	'tabEmuPlatformSettings' =>
		"Platform settings",
	'tab_label_platforms' =>
		"Platforms / Emulators",
	
	/* 0.9.6 WIP 18 */
	'confEccSaveViewSettings' =>
		"Save View-Mode settings (only experts)",
	
	/* 0.9.6 WIP 19 */
	'tabEmuInfos' =>
		"Links & Infos",
		
	/* 0.9.6 WIP 20 */
	'startConfBugreportSend' =>
		"Send automatic bugreport at startup",
	'lbl_ecc_opt_language' =>
		"Language settings",
	'tab_label_language' =>
		"Language",
	
	/* 0.9.7 WIP 01 */
	'confEccSilentParsing' =>
		"Silent ROM parsing (no popup requests)",
		
	'emuAssignGlobalEnableEccScript' =>
		"activate eccScript",
	'emuAssignFileextLabel' =>
		"Emulator for fileextension",
	'emuAssignPreviewLabel' =>
		"Commandline preview",
	'emuAssignGlobalActive%s' =>
		"Start roms with fileextension '%s' with this emulator",
	'emuAssignGlobalActiveGlobal%s' =>
		"Activate catch all emulator '%s'",
	'lbl_emu_tips_ecc' =>
		"ecc links",	
	'emuPlatformActiveState' =>
		"activate platform",
	'tabGeneralHlListOptions' =>
		"List view options",	
		
	/* 0.9.7 WIP 04 */
	'tab_label_themes' =>
		"Themes",
	'lblThemeSelect' =>
		"Select theme",

	/* 0.9.7 WIP 10 */
	'emuAssignLabelZipUnpack' =>
		"ZIP auto unpack",
	'emuAssignGlobalCheckZipUnpackActive' =>
		"automaticly unpack ZIP files",
	'emuAssignGlobalCheckZipUnpackSkip' =>
		"Skip allready unpacked files (faster)",
	'emuAssignGlobalCheckZipUnpackClean' =>
		"Cleanup ecc-unpack folder for platform",
	'emuAssignGlobalCheckZipUnpackOpen' =>
		"Open ecc-unpack folder",

	/* 0.9.7 WIP 10 */
	'conEccSaveGuiSettings' =>
		"Save gui settings",

);
?>