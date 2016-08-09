<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:gr (greek)
 * author:	Alkis30 (ecc forum member)
 * date:	2006/09/09 
 * ------------------------------------------
 */
$i18n['menu'] = array(
	// -------------------------------------------------------------
	// context menu navigation
	// -------------------------------------------------------------
	'lbl_platform%s' =>
		"%s επιλογές",
	'lbl_roms_add%s' =>
		"Προσθήκη νέων ROMS για %s",
	'lbl_roms_optimize%s' =>
		"Βελτιστοποίηση ROMS",
	'lbl_roms_remove%s' =>
		"Αφαίρεση ROMS",
	'lbl_roms_remove_dup%s' =>
		"Αφαίρεση διπλών ROMS",
	'lbl_emu_config' =>
		"Επεξεργασία/Καταχώρηση εξομειωτή",
	'lbl_ecc_config' =>
		"Ρυθμίσεις",
	'lbl_dat_import_ecc' =>
		"Εισαγωγή emuControlCenter Datfile",
	'lbl_dat_import_rc' =>
		"Εισαγωγή Romcenter Datfile",
	'lbl_dat_export_ecc_full' =>
		"Εξαγωγή ecc Datfile γεμάτο",
	'lbl_dat_export_ecc_user' =>
		"Εξαγωγή ecc Datfile χρήστη",
	'lbl_dat_export_ecc_esearch' =>
		"Εξαγωγή ecc Datfile eSearch",
	'lbl_dat_empty' =>
		"Αδειασμα Datfile database",
	'lbl_help' =>
		"Βοήθεια",
	// -------------------------------------------------------------
	// context menu main
	// -------------------------------------------------------------
	'lbl_start' =>
		"Εναρξη ROM",
	'lbl_fav_remove' =>
		"Αφαίρεση αυτού του bookmark",
	'lbl_fav_all_remove' =>
		"Αφαίρεση ΟΛΩΝ των bookmarks",
	'lbl_fav_add' =>
		"Προσθήκη σε bookmark",
	'lbl_image_popup' =>
		"Ανοιγμα του imageCenter",
	'lbl_img_reload' =>
		"Επαναφόρωση εικόνων",
	'lbl_rom_remove' =>
		"Αφαίρεση ROM από Βάση Δεδομένων",
	'lbl_meta_edit' =>
		"ΕΠΕΞΕΡΓΑΣΙΑ METADATA",
	'lbl_roms_initial_add%s%s' =>
		"Δεν βρέθηκαν ROMS για πλατφόρμα\n----------------------------------------\n%s (%s)\n----------------------------------------\nΚλικ εδώ να προσθέσετε νέα ROMS!",
	'lbl_meta_webservice_meta_get' =>
		"Λάβετε πληροφορίες από eccdb (Internet)",
	'lbl_meta_webservice_meta_set' =>
		"Προσθέστε τα δεδομένα σας στο eccdb (Internet)",
	// File operations
	'lbl_shellop_submenu' =>
		"Λειτουργίες αρχείου",
	'lbl_shellop_browse_dir' =>
		"Browse ROM κατάλογο",
	'lbl_shellop_file_rename' =>
		"Μετονομασία αρχείου σε σκληρό δίσκο",
	'lbl_shellop_file_copy' =>
		"Αντιγραφή αρχείου σε σκληρό δίσκο",
	'lbl_shellop_file_unpack' =>
		"Unpack αυτό το αρχείο",
	'lbl_shellop_file_remove' =>
		"Αφαίρεση αρχείου από σκληρό δίσκο",
	// Rating
	'lbl_rating_submenu' =>
		"Βαθμολογήστε το ROM",
	'lbl_import_submenu' =>
		"Εισαγωγή Datfile",
	'lbl_export_submenu' =>
		"Εξαγωγή Datfile",
	'lbl_rom_rescan_folder' =>
		"(Επανα)διαχωρισμός ROM-Directory",
	'lbl_meta_remove' =>
		"Αφαίρεση META από Βάση Δεδομένων",
	'lbl_rating_unset' =>
		"Αναίρεση βαθμολογιών",
	
	/* 0.9 FYEO 9*/
	'lbl_roms_remove_dup_preview%s' =>
		"Εύρεση διπλών ROMS",
	/* 0.9 FYEO 9*/
	'lbl_roms_dup' =>
		"Διπλά ROMS",
	
	/* 0.9.1 FYEO 3*/
	'lbl_img_remove_all' =>
		"Αφαίρεση ROM εικόνων ",
	/* 0.9.1 FYEO 4*/
	'lbl_meta_compare_left' =>
		"ΣΥΓΚΡΙΣΗ - Επιλέξτε αριστερή πλευρά",		
	'lbl_meta_compare_right%s' =>
		"ΣΥΓΚΡΙΣΗ με \"%s\"",	

	/* 0.9.2 FYEO 2*/
	'lbl_start_with' =>
		"Εναρξη ROM με...",
	'lbl_emu_config' =>
		"Ρύθμιση εξομειωτή",
	'lbl_quickfilter' =>
		"quickFilter",
	'lbl_quickfilter_reset' =>
		"quickFilter entfernen",

	/* 0.9.6 FYEO 1 */
	'lbl_dat_import_ecc_romdb' =>
		"Εισαγωγή romDB Datfile (internet)",

	/* 0.9.6 FYEO 8 */
	'lContextRomSelectionAddNewRoms%s' =>
		"Προσθήκη νέων %s roms",
	'lContextRomSelectionRemoveRoms%s' =>
		"Αφαίρεση %s roms",
	'lContextMetaRemove' =>
		"Αφαίρεση ROM metadata",

	/* 0.9.6 FYEO 11 */
	'lbl_importDatCtrlMAME' =>
		"Εισαγωγή ClrMAME datfile",

	/* 0.9.6 FYEO 13 */
	'labelRomAuditInfo' =>
		"Προβολή rom audit πληροφοριών",
	'labelRomAuditReparse' =>
		"Αναβάθμιση  rom audit πληροφοριών",
	'lbl_roms_rescan_all' =>
		"Ανίχνευση ξανά όλων των rom φακέλων",
	'lbl_roms_add' =>
		"Προσθήκη νέων roms",
		
	/* 0.9.6 FYEO 11 */
	'lbl_open_eccuser_folder%s' =>
		"Ανοιγμα eccUser-Φακέλου (%s)",
	'lbl_rom_remove_toplevel' =>
		"Αφαίρεση rom(s)",
	'menuItemPersonalEditNote' =>
		"Επεξεργασία σημειώσεων",
	'menuItemPersonalEditReview' =>
		"Επεξεργασία review",
		
	/* 0.9.6 FYEO 11 */
	'menuItemRomOptions' =>
		"Rom επιλογές",

	/* 0.9.7 FYEO 17 */
	'imagepackTopMenu' =>
		"imagepack βοηθήματα",
	'imagepackRemoveImagesWithoutRomFile' =>
		"Αφαίρεση εικόνων από roms που δεν έχω στη βάση δεδομένων",
	'imagepackRemoveEmptyFolder' =>
		"Αφαίρεση άδειου φακέλου",
	'imagepackCreateAllThumbnails' =>
		"Δημιουργία thumbnails για γρηγορότερη πρόσβαση",
	'imagepackRemoveAllThumbnails' =>
		"Αφαίρεση thumbnails",
	'imagepackConvertEccV1Images' =>
		"Μετατροπή εικόνων σε νέο imagepack structure! (V1->V2)",

	/* 0.9.7 FYEO 17 */
	'onlineSearchForRom' =>
		"Αναζήτηση για rom σε web",
	'onlineEccRomdbShowWebInfo' =>
		"Αναζήτηση για rom στη βάση δεδομένωn-romdb",

	/* 0.9.8 FYEO 04 */
	'lbl_meta_edit_top' =>
		"Επεξεργασία meta",

	/* 0.9.9 FYEO 01 */
	'lblOpenAssetFolder' =>
		"Browse φακέλου εγγράφων",

	/* 1.12 BUILD 06 */
	'lbl_image_platform' =>
		"Platform images",	

	'lbl_image_platform_import_online' =>
		"Import platform images online (kameleon code needed)",	

	'lbl_image_platform_import_local' =>
		"Import platform images from local folder (non ecc, like no-intro)",

	'lbl_image_platform_export_local' =>
		"Create platform imagepack (ecc, no-intro, emumovies)",
		
	/* 1.13 BUILD 02 */
	'lbl_emulator' =>
		"Platform emulators",
		
	'lbl_emulator_import_online' =>
		"Download emulators online (kameleon code needed)",	
		
	'lbl_emulator_information' =>
		"Open info file where to get emulators",

	/* 1.13 BUILD 03 */
	'lbl_image_platform_import_emumovies' =>
		"Download platform images online from EmuMovies (forum account needed)",

	/* 1.13 BUILD 04 */
	'lbl_rom_content' =>
		"ROM Content/Media",	
	'lbl_image_inject' =>
		"Download images online from ICC server (kameleon code needed)",
	'lbl_rom_video_add' =>
		"Add videofile",
	'lbl_rom_video_delete' =>
		"Delete videofile(s)",	
);
?>