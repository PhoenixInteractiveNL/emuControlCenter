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
		"DO YOU WANT CLEAR THE DATABASE FOR \n\"%s\"-MEDIA?\n\nThis action wil remove all filedata of the selected media from the ecc database. This will NOT remove your datfile-informations or your media from your harddisc.",
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
		"Optimize database",
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
		"Optimize database",
	'db_optimize_msg' =>
		"Do you want to optimize the database?\nThis will decrease the physical size of the emuControlCenter-DatabaseYou should use Vacuum, if you often Parsed and remove Media in emuControlCenter!\n\nThis operation will freeze the application for some seconds - please wait! :-)",
	'db_optimize_done_title' =>
		"Database optimized!",
	'db_optimize_done_msg' =>
		"Your ecc-database is now optimized!",
	'export_esearch_error_title' =>
		"No eSearch options selected",
	'export_esearch_error_msg' =>
		"You have to use the eSearch (extended search) to use this export-function. This will only export the search-result, you see in the mainview!",
	'dat_export_filechooser_title%s' =>
		"Select directory to save %s dat-file!",	
	'dat_export_title%s' =>
		"Export %s datfile",
	'dat_export_msg%s%s%s' =>
		"Do you want to export a %s datfile for platform\n\n%s\n\ninto this directory?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"\n\necc will use your eSearch selection to export!",
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
	'rom_miss_title' =>
		"Error - Media not found!",
	'rom_miss_msg' =>
		"The selected File was not found\n\nPlease use the option 'ROMS->optimize roms in ecc'\nPlease check also if you use options like 'escape' or '8.3' correct",
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
		"\n\nShould i close the status detail area?",
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
		"eccdb - Statistics:\n\n%s added\n%s allready inplace\n%s errors\n\n%s Datasets processed!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatabase:\n\nTo support the emuControlCenter community, you can add your modified metadata (title, category, languages aso.) into the ECCDB (Internet Database).\n\nThis works like the well known CDDB for CD-Tracks.\n\nIf you confirm this, ecc will automaticly transfer you data into the eccdb!\n\nYou have to be connected to the internet to add your content!!!\n\nAfter 10 processed Metadatasets, you have to confirm to add more!",
	'eccdb_error' =>
		"eccdb - Errors:\n\nMaybe you are not connected to the internet... only with an active internet connection, you can add data into the eccdb!",
	'eccdb_no_data' =>
		"eccdb - No data to add found:\n\nYou have to edit some of your metadata to add this into the eccdb. Use the edit button and try again!",
);
?>