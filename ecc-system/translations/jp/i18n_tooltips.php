<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	jp (japanese)
 * author:	Yoshi Matsu
 * date:	2006/12/31 
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"ナビゲーション用の「Toogle(文字)」自動検索",
	'opt_hide_nav_null' =>
		"ROMの無いプラットフォームを表示／隠す",
	'opt_hide_dup' =>
		"複製ROMを表示／隠す",
	'opt_hide_img' =>
		"画像を表示／隠す",
	'search_field_select' =>
		"どこを検索しますか？",
	'search_operator' =>
		"検索方法を選択 ([ = EQUAL] [ | OR ] [ + AND])",
	'search_rating' =>
		"ROMがEQUALまたはそれ以下の選択を表示する",
	'optvis_mainlistmode' =>
		"詳細とリスト表示を切換える",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Click to add your own rating for this rom",
	'nbMediaInfoNoteEvent' =>
		"Show notes for this rom",
	'nbMediaInfoReviewEvent' =>
		"Show review for this game",
	'nbMediaInfoBookmarkEvent' =>
		"Add / Remove bookmark",
	'nbMediaInfoAuditStateEvent' =>
		"Audit state for multifile roms",
	'nbMediaInfoMetaEvent' =>
		"Edit the metainformations for this game",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Show only the first Disk",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Show all roms",
	'optionContextOnlyDiskOne' =>
		"Show only first rom media",
	'optionContextOnlyDiskOnePlus' =>
		"Show first rom media plus unknown",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"This will add roms for the selected platform!",
	'mTopRomOptimizeTooltip' =>
		"Optimize the ecc-Database for the selected platform e.g. if you move/remove files at your harddrive",
	'mTopRomRemoveDupsTooltip' =>
		"This will remove all duplicate roms from your ecc database",
	'mTopRomRemoveRomsTooltip' =>
		"Remove all roms of the selected platform from the ecc-database",		
	'mTopDatImportRcTooltip' =>
		"You can import Romcenter Datfiles (*.dat) into ecc. You have to select the right platform! RC-Datfiles contain the filename, checksum and metainfos assigned to the filename. emuControlCenter will strip this informations and automaticlly create ecc-metadata!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Change the emulator assigned to the selected platform",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Import emuControlCenter Datfiles (*.eccDat) into ecc. If you have selected a platform, only roms for this platform will be imported! ecc-datfile-format has extended metainformations like categories, developer, state, languages aso.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Import CLR MAME Datfiles (*.dat) into ecc.",
	'mTopDatImportRcTooltip' =>
		"Import Romcenter Datfiles (*.dat) into ecc. You have to selected the right platform! RC-Datfiles contains the filename, checksum and metainfos assigned to the filename. emuControlCenter will strip this informations and automaticlly create ecc-metadata!",		
	'mTopDatExportEccFullTooltip' =>
		"This will export all your meta-data of the selected platform to a Datfile (plaintext).",
	'mTopDatExportEccUserTooltip' =>
		"This will export only the data modified by you of the selected platform to a Datfile (plaintext).",
	'mTopDatExportEccEsearchTooltip' =>
		"This will export only the search result of eSearch meta-data of the selected platform to a Datfile (plaintext).",
	'mTopDatClearTooltip' =>
		"Clear data from DATfiles of the selected platform!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Internal function to cleanup and shrink the database.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"This will create all ecc user-folders like emus, roms, exports aso. Use this option, if you have created a new platform!",
	'mTopOptionCleanHistoryTooltip' =>
		"This will clean up the ecc history.ini. Ecc stores data like selected Directories, selected Options aso. in this file.",
	'mTopOptionBackupUserdataTooltip' =>
		"This will backup all your userdata like notes, highscore and time played to an XML file",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"This will create ECC shortcuts in the windows startmenu",
	'mTopOptionConfigTooltip' =>
		"This will open the configuration window of ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Select various GTK themes to use with ECC, you can make a nice combination when used with proper ECC themes.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"This will diagnose and give you information about your ECC installation.",
	'mTopDatDFUTooltip' =>
		"Manually update your DATfiles from MAME DAT.",
	'mTopAutoIt3GUITooltip' =>
		"This will open KODA where you can create end export your own AutoIt3 GUI for use with scripts if needed.",
	'mTopImageIPCTooltip' =>
		"Create imagepacks of your platforms, so you can share it easily with us.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"This will open a SQL browser wich you can use to view and edit the ECC database (for experts only, make sure you create a backup of your changes bacause it can be overwritten with a ECC update!)",
	'mTopDeveloperGUITooltip' =>
		"This will open the GLADE GUI editor where you can edit and adjust the ECC GUI (for experts only, make sure you create a backup of your changes bacause it can be overwritten with a ECC update!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"This will check if there are updates available for ECC.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"This will open a window where you can enter the kameleon code to use ECC services. (registered forum members)",
	// # TOP-HELP
	'mTopHelpWebsiteTooltip' =>
		"This will open the ECC website in your internetbrowser.",
	'mTopHelpForumTooltip' =>
		"This will open the ECC support forum in your internetbrowser.",
	'mTopHelpDocOfflineTooltip' =>
		"This will open the ECC documentation locally.",
	'mTopHelpDocOnlineTooltip' =>
		"This will open the ECC documentation site in your internetbrowser.",
	'mTopHelpAboutTooltip' =>
		"This will pop-up the ECC about box.",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"This will open a window where you can enter your EmuMovies account data to use their services. (registered forum members)",
	
	/* 1.14 BUILD 4 */
	'mTopToolNotepadEditorTooltip' =>
		"This will open the notepad editor where you can edit text files and scripts if needed.",
	'mTopToolHexEditorTooltip' =>
		"This will open a HEX editor where you can edit binary files if needed.",
		
	);
?>