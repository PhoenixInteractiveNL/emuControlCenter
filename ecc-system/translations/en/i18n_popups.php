<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	en (english)
 * author:	andreas scheibel
 * date:	2006/09/09
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Locate your media folder!",
	'rom_add_parse_title%s' =>
		"Add new Roms for %s",
	'rom_add_parse_msg%s%s' =>
		"Add new Roms for\n\n%s\n\nfrom directory\n\n%s?",
	'rom_add_parse_done_title' =>
		"Parsing done",
	'rom_add_parse_done_msg%s' =>
		"Parsing new \n\n%s\n\nROMS is done!",
	'rom_remove_title%s' =>
		"CLEAR DB FOR %s",
	'rom_remove_msg%s' =>
		"DO YOU WANT TO CLEAR THE DATABASE FOR \n\"%s\"MEDIA?\n\nThis action will remove all filedata of the selected media from the ecc database. This will NOT remove your datfile-information or your media from your hard drive!",
	'rom_remove_done_title' =>
		"DB Clear done",
	'rom_remove_done_msg%s' =>
		"All data for %s is removed from ecc-database",
	'rom_remove_single_title' =>
		"Remove ROM from database?",
	'rom_remove_single_msg%s' =>
		"Should i remove\n\n%s\n\nfrom the ecc database?",
	'rom_remove_single_dupfound_title' =>
		"Duplicate ROMS found!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d DUPLICATE ROMS FOUND\n\nShould i also remove all duplicates of\n\n%s\n\n from the ecc database?\n\nSee HELP for more informations!",
	'rom_optimize_title' =>
		"Optimize game database",
	'rom_optimize_msg' =>
		"Do you want to optimize your ROMS in ecc-database?\n\nYou should optimize the database, if you have moved or removed files from your harddrive\necc will automaticly search for this database-entries and bookmarks and will remove them from the database!\nThis options only edit the database.",
	'rom_optimize_done_title' =>
		"Optimization done!",
	'rom_optimize_done_msg%s' =>
		"The database for platform\n\n%s\n\nis now optimized!",
	'rom_dup_remove_title' =>
		"Remove duplicate ROMS from ecc-database?",
	'rom_dup_remove_msg%s' =>
		"Do you want to remove all duplicate ROMS for\n\n%s\n\nfrom the ecc-database?\n\nThis operation only works within the emuControlCenter Database....\n\nThis will NOT remove Files from your harddisk!!!",
	'rom_dup_remove_done_title' =>
		"Removing done",
	'rom_dup_remove_done_msg%s' =>
		"All duplicate ROMS for\n\n%s\n\nare removed from ecc-database",
	'rom_reorg_nocat_title' =>
		"There are no categories!",
	'rom_reorg_nocat_msg%s' =>
		"You haven´t assigned any category to your\n\n%s\n\nROMS! Please use the edit-function to add some categories or import a good ecc-datfile!",
	'rom_reorg_title' =>
		"Reorganize your ROMS on Harddisk?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------THIS OPTION WILL REORGANIZE YOUR ROMS AT YOUR HARDDRIVE !!! PLEASE FIRST REMOVE DUPLICATES FROM ECC-DB !!!\nYOUR SELECTED MODE IS: #%s#\n------------------------------------------------------------------------------------------\n\nDo you want to reorganize your roms by categories for\n\n%s\n\nat your filesystem? ecc will organize your roms in the ecc-userfolder under\n\n%s/roms/organized/\n\nPlease check the discspace of this harddrive, if there is space available\n\nDO YOU WANT THIS AT YOUR RISK? :-)",
	'rom_reorg_done_title' =>
		"Reorganization done",
	'rom_reorg_done__msg%s' =>
		"Take a look at your filesystem into folder\n\n%s\n\nto validate the copy",
	'db_optimize_title' =>
		"Optimize database system",
	'db_optimize_msg' =>
		"Do you want to optimize the database?\nThis will decrease the physical size of the emuControlCenter-DatabaseYou should use Vacuum, if you often Parsed and remove Media in emuControlCenter!\n\nThis operation will freeze the application for some seconds - please wait! :-)",
	'db_optimize_done_title' =>
		"Database optimized!",
	'db_optimize_done_msg' =>
		"Your ecc-database is now optimized!",
	'export_esearch_error_title' =>
		"No e-search options selected",
	'export_esearch_error_msg' =>
		"You have to use the e-search (extended search) to use this export-function. This will only export the search-result, you see in the mainview!",
	'dat_export_filechooser_title%s' =>
		"Select directory to save %s dat-file!",	
	'dat_export_title%s' =>
		"Export %s datfile",
	'dat_export_msg%s%s%s' =>
		"Do you want to export a %s datfile for platform\n\n%s\n\ninto this directory?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"\n\necc will use your e-search selection to export!",
	'dat_export_done_title' =>
		"Export done",
	'dat_export_done_msg%s%s%s' =>
		"Export %s datfile for\n\n%s\n\ninto path\n\n%s\n\ndone!",
	'dat_import_filechooser_title%s' =>
		"Import: Select a %s datfile!",
	'rom_import_backup_title' =>
		"Create backup?",
	'rom_import_backup_msg%s%s' =>
		"Should i create a BACKUP into your user-folder for\n\n%s (%s)\n\nbefore you import new meta-data?",
	'rom_import_title' =>
		"Import datfile?",
	'rom_import_msg%s%s%s' =>
		"Do you really want to import data for platform\n\n%s (%s)\n\nfrom datfile\n\n%s?",
	'rom_import_done_title' =>
		"Import done!",
	'rom_import_done_msg%s' =>
		"Import datfile for\n\n%s\n\ndone!",
	'dat_clear_title%s' =>
		"CLEAR DB FOR %s",
	'dat_clear_msg%s%s' =>
		"DO YOU WANT CLEAR ALL META-INFORMATIONS FOR\n\n%s (%s)-DAT?\n\nThis will remove all meta-informations like category, status, languages aso. for the selected platform from the ecc-database!. In the next step, YOU CAN CREATE A BACKUP FOR THIS INFORMATIONS. (Will be automaticly saved to your user-folder!)\n\nThe last step is a optimization of the database!",
	'dat_clear_backup_title%s' =>
		"Backup %s",
	'dat_clear_backup_msg%s%s' =>
		"Should i create a backup for platform\n\n%s (%s)?",
	'dat_clear_done_title%s' =>
		"DB Clear done",
	'dat_clear_done_msg%s%s' =>
		"All meta-informations for\n\n%s (%s)\n\nare removed from ecc-database!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n ecc have backuped your data to the %s-User-Folder",
	'emu_miss_title' =>
		"Error - Emulator not found!",
	'emu_miss_notfound_msg%s' =>
		"The assigned emulator was not found. Green indicates an valid, red an invalid Emulator location.",
	'emu_miss_notset_msg' =>
		"You havent assigned any valid emulator for this platform",
	'emu_miss_dir_msg%s' =>
		"The assigned Path is an directory!!!!",
	'img_overwrite_title' =>
		"Overwrite image?",
	'img_overwrite_msg%s%s' =>
		"The Image\n\n%s\n\nalready exists\n\nDo you really overwrite this image with\n\n%s?",	
	'img_remove_title' =>
		"Remove image?",
	'img_remove_msg%s' =>
		"Do you really want to remove the image %s",
	'img_remove_error_title' =>
		"Error - Could not remove image!",
	'img_remove_error_msg%s' =>
		"Image %s could not be deleted!",
	'conf_platform_update_title' =>
		"Update platform ini?",
	'conf_platform_update_msg%s' =>
		"Do you really want to update the platform-ini for %s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Select emulator for extension '%s'",
	'conf_userfolder_notset_title' =>
		"ERROR: Could not find userfolder!!!",
	'conf_userfolder_notset_msg%s' =>
		"You have altered the base_path in your ecc_general.ini. This folder isn´t created by now.\n\nShould i create the directory\n\n%s\n\nfor you?\n\nIf you want choose an other folder, click NO and use \n'options'->'configuration'\nto set your user-folder!",
	'conf_userfolder_error_readonly_title' =>
		"ERROR: Could not create folder!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"The folder %s could not be created because you have selected an readonly medium (CD?)\n\nIf you want choose an other folder, click OK and choose \n'options'->'configuration'\nto set your user-folder!",
	'conf_userfolder_created_title' =>
		"User-folder created!",
	'conf_userfolder_created_msg%s%s' =>
		"The subfolders\n\n%s\n\nare created in your selected user-folder\n\n%s",
	'conf_ecc_save_title' =>
		"Update emuControlCenter GLOBAL-INI?",
	'conf_ecc_save_msg' =>
		"This will write your changes settings to the ecc_global.ini\n\nThis will also create the selected user-folder and all needed subfolders\n\nDo you really want to do this?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Select the Folder for your User-Data",
	'fav_remove_all_title' =>
		"Remove all bookmarks?",
	'fav_remove_all_msg' =>
		"Do you really want to remove ALL BOOKMARKS?",
	'maint_empty_history_title' =>
		'Reset ecc history.ini?',
	'maint_empty_history_msg' =>
		'This will empty the ecc history.ini file. This files stores your selections in ecc frontend like options (eg. Hide duplicate roms) and selected paths! Should i reset this file?',
	'sys_dialog_info_miss_title' =>
		"?? TITLE MISSING ??",
	'sys_dialog_info_miss_msg' =>
		"?? MESSAGE MISSING ??",
	'sys_filechooser_miss_title' =>
		"?? TITLE MISSING ??",
	'status_dialog_close' =>
		"\n\nShould I close the status detail area?",
	'status_process_running_title' =>
		"Process running",
	'status_process_running_msg' =>
		"Another process is running\nYou can only start one process like parsing/import/export! Please wait until the current running process is done!",
	'meta_rating_add_error_msg' =>
		"You can only rate a rom with metadata.\n\nPlease use EDIT and create theses metainformations!",
	'maint_unset_ratings_title' =>
		"Unset ratings for platform?",
	'maint_unset_ratings_msg' =>
		"This will reset all ratings in the database... should i do it?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistics:\n\n%s added\n%s already inplace\n%s errors\n\n%s Datasets processed!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatabase:\n\nTo support the emuControlCenter community, you can add your modified metadata (title, category, languages aso.) into the ECCDB (Internet Database).\n\nThis works like the well known CDDB for CD-Tracks.\n\nIf you confirm this, ecc will automaticly transfer you data into the eccdb!\n\nYou have to be connected to the internet to add your content!!!\n\nAfter 10 processed Metadatasets, you have to confirm to add more!",
	'eccdb_error' =>
		"eccdb - Errors:\n\nMaybe you are not connected to the internet... only with an active internet connection, you can add data into the eccdb!",
	'eccdb_no_data' =>
		"eccdb - No data to add found:\n\nYou have to edit some of your metadata to add this into the eccdb. Use the edit button and try again!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"This option will search for duplicate roms in your database and will output the found roms\n\nYou will also find the logfile in your ecc-logs folder!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Remove all images?",
	'img_remove_all_msg%s' =>
		"This will remove all images for the selected Game!\n\nShould the images for\n\n%s?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"confirm",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"Really parse this file?",
	'parse_big_file_found_msg%s%s' =>
		"BIG FILE FOUND!!!\n\nThe found game\n\nName: %s\nSize: %s\n\nis very large. This can take a long time without direct feedback of emuControlCenter.\n\nDo you want parse this game?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Bookmark saved",
	'bookmark_added_msg' =>
		"The bookmark has been added!",
	'bookmark_removed_single_title' =>
		"Bookmark removed",
	'bookmark_removed_single_msg' =>
		"This bookmark has been removed!",
	'bookmark_removed_all_title' =>
		"All Bookmarks removed",
	'bookmark_removed_all_msg' =>
		"All bookmarks has been removed!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Update datfile from internet",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Do you really want to update the Platform\n\n%s\n\nwith the data from the online emuControlCenter romDB?\n\nAn internet connection has to be established for this feature",

	'eccdb_webservice_get_datfile_error_title' =>
		"Could not import datfile",
	'eccdb_webservice_get_datfile_error_msg' =>
		"You have to be connected with the internet. Please connect and try again!",

	'romparser_fileext_problem_title%s' =>
		"EXTENSION %s PROBLEM FOUND",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter found, that more than one platform uses the fileextension %s to search for roms!\n\n%s\nAre you shure, that only %s games  are located in the selected folder %s\n\n<b>OK</b>: Search for %s in this folder / platform!\n\n<b>CANCEL</b>: Skip the extension %s for this folder / platform!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Search for duplicate ROMS",
	'rom_dup_remove_done_title_preview' => 
		"Searching done",
	'rom_dup_remove_done_msg_preview' =>
		"Take a look at the status area for details!",
	'metaRemoveSingleTitle' =>
		"Remove metadata for rom",
	'metaRemoveSingleMsg' =>
		"Do you want to remove the metadata for this rom?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Select an CLR MAME DATfile!\n",
	'importDatCMConfirmTitle' =>
		"Import CLR MAME DATfile",
	'importDatCMConfirmMsg%s%s%s' =>
		"Do you really want to import data for platform\n\n%s (%s)\n\nfrom datfile\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Update rom audit informations",
	'romAuditReparseMsg%s' =>
		"This will update the stored informations like complete state of an multifile rom\n\nUpdate this data?",
	'romAuditInfoNotPossibelTitle' =>
		"No rom audit informations available",
	'romAuditInfoNotPossibelMsg' =>
		"Audit informations are only available for multirom platforms like the Arcade platforms!",

	'romReparseAllTitle' =>
		"Reparse your rom folder",
	'romReparseAllMsg%s' =>
		"Search for new roms for the selected platform(s)?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Unset these extensions",
	'parserUnsetExtMsg%s' =>
		"Because you have selected '#All found', ecc have to exclude duplicate extensions from search to prevent wrong assignment in the database!\n\nemuControlCenter do not search for: %s\n\nPlease select the right Platform to parse these extensions!\n\n",

	'stateLabelDatExport%s%s' =>
		"Export %s datfile for %s",
	'stateLabelDatImport%s' =>
		"Import datfile for %s",

	'stateLabelOptimizeDB' =>
		"Optimize database",
	'stateLabelVacuumDB' =>
		"Vacuum database",
	'stateLabelRemoveDupRoms' =>
		"Remove duplicate roms",
	'stateLabelRomDBAdd' =>
		"Add infos to romDB",
	'stateLabelParseRomsFor%s' =>
		"Parsing roms for %s",
	'stateLabelConvertOldImages' =>
		"Now converting images...",

	'processCancelConfirmTitle' =>
		"Cancel current process?",
	'processCancelConfirmMsg' =>
		"Do you really want to cancel this running process?",
	'processDoneTitle' =>
		"Process completed!",
	'processDoneMsg' =>
		"The process has been completed!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"The backup XML-File with your userdata has been created into your ecc-user/#_GLOBAL/ folder\n\n%s\n\nView the exported xml now in your xml browser?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Really execute this background task?",
	'executePostShutdownTaskMessage%s' =>
		"\nTask: <b>%s</b>\n\nDo you really want to execute this long running task?",
	'postShutdownTaskTitle' =>
		"Execute selected task",
	'postShutdownTaskMessage' =>
		"You have selected a task only executable if emuControlCenter closed.\n\nAfter this task, <b>emuControlCenter will restart automaticly!</b>\n\nThis can take some seconds, some minutes and sometimes hours! This popup will be freezed! No fear! :-)\n\n<b>Please wait!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Romfile not found...",
	'startRomFileNotAvailableMessage' =>
		"It looks, like you dont have this rom!\n\nMaybe you try again after selecting view mode 'All i have' :-)",
	'startRomWrongFilePathTitle' =>
		"Rom in database but file not found",
	'startRomWrongFilePathMessage' =>
		"Maybe you have moved your roms to another position or removed them?\n\nPlease use the option 'ROMS' -> 'Optimize roms' to cleanup your database!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Download images",
	'waitForImageInjectMessage' =>
		"This task could take a little bit. If images are found, this window closes automaticly and you can see the images in the list!\n\nIf no images are found, this popup closes and the mainlist is not updated! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Really copy/move files by search result?",
	'copy_by_search_msg_waring%s%s%s' =>
		"This option will copy/rename ALL games found in your search result (Take care: If you dont have searched, all files are selected!)\n\nYou can select the destination in the next window.\n\nThere where found <b>%s games</b> in your searchresult\n\n<b>%s packed games</b> are skipped!\n\nDo you really want to copy/move these <b>%s</b> games to another location?",
	'copy_by_search_msg_error_noplatform' =>
		"You have to select a platform to use this feature. It is not possible to use this function for ALL FOUND!",
	'copy_by_search_msg_error_notfound%s' =>
		"No valid games are found in your searchresult. <b>%s packed games</b> skipped.",
	'searchTab' =>
		"Searchresult",
	'searchDescription' =>
		"Here you can copy or move files from their source folder to a specified one.\n<b>Source is your current search result.</b>\nIf you move, also the paths in your database are updated! Clean by checksum remove files that are 100% duplicate!",
	'searchHeadlineMain' =>
		"Introduction",
	'searchHeadlineOptionSameName' =>
		"same name",
	'searchRadioDuplicateAddNumber' =>
		"add number",
	'searchRadioDuplicateOverwrite' =>
		"overwrite",
	'searchCheckCleanup' =>
		"cleanup by checksum",

);
?>