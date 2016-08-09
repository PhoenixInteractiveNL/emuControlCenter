<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:jp (japanese)
 * author:	Yoshi Matsu
 * date:	2006/09/09 
 * ------------------------------------------
 */
$i18n['menu'] = array(
	// -------------------------------------------------------------
	// context menu navigation
	// -------------------------------------------------------------
	'lbl_platform%s' =>
		"%s �̃I�v�V����",
	'lbl_roms_add%s' =>
		"%s �̐V����ROM��ǉ�",
	'lbl_roms_optimize%s' =>
		"ROM�̍œK��",
	'lbl_roms_remove%s' =>
		"ROM��폜",
	'lbl_roms_remove_dup%s' =>
		"ROM�̕�����폜",
	'lbl_emu_config' =>
		"�ҏW/�G�~�����[�^�̊��U��",
	'lbl_ecc_config' =>
		"�ݒ�",
	'lbl_dat_import_ecc' =>
		"emuControlCenter�̃f�[�^��C���|�[�g",
	'lbl_dat_import_rc' =>
		"Romcenter�̃f�[�^��C���|�[�g",
	'lbl_dat_export_ecc_full' =>
		"�S�Ă�ecc�f�[�^��G�N�X�|�[�g",
	'lbl_dat_export_ecc_user' =>
		"ecc�̃��[�U�[�f�[�^��G�N�X�|�[�g",
	'lbl_dat_export_ecc_esearch' =>
		"ecc Datfile eSearch��G�N�X�|�[�g",
	'lbl_dat_empty' =>
		"�f�[�^�t�@�C���̃f�[�^�x�[�X���ɂ���",
	'lbl_help' =>
		"�w���v",
	// -------------------------------------------------------------
	// context menu main
	// -------------------------------------------------------------
	'lbl_start' =>
		"ROM��N��",
	'lbl_fav_remove' =>
		"���̂��C�ɓ���폜",
	'lbl_fav_all_remove' =>
		"�S�Ă̂��C�ɓ���폜",
	'lbl_fav_add' =>
		"���C�ɓ��ɒǉ�",
	'lbl_image_popup' =>
		"�C���[�W�Z���^�[��J��",
	'lbl_img_reload' =>
		"�C���[�W�㊃��[�h����",
	'lbl_rom_remove' =>
		"DB����ROM���菜��",
	'lbl_meta_edit' =>
		"META�f�[�^�̕ҏW",
	'lbl_roms_initial_add%s%s' =>
		"No ROMS found for platform\n----------------------------------------\n%s (%s)\n----------------------------------------\nClick here to add new ROMS!",
	'lbl_meta_webservice_meta_get' =>
		"eccdb(�E�F�u)����f�[�^����",
	'lbl_meta_webservice_meta_set' =>
		"eccdb(�E�F�u)�ւ��Ȃ��̃f�[�^��ǉ�",
	// File operations
	'lbl_shellop_submenu' =>
		"�t�@�C������",
	'lbl_shellop_browse_dir' =>
		"ROM�f�B���N�g����{��",
	'lbl_shellop_file_rename' =>
		"�n�[�h�f�B�X�N�Ƀt�@�C������ύX",
	'lbl_shellop_file_copy' =>
		"�n�[�h�f�B�X�N�Ƀt�@�C����R�s�[",
	'lbl_shellop_file_unpack' =>
		"���̃t�@�C���͊J���܂���",
	'lbl_shellop_file_remove' =>
		"�n�[�h�f�B�X�N����t�@�C����폜",
	// Rating
	'lbl_rating_submenu' =>
		"ROM�̕]��",
	'lbl_import_submenu' =>
		"�f�[�^�t�@�C���̃C���|�[�g",
	'lbl_export_submenu' =>
		"�f�[�^�t�@�C���̃G�N�X�|�[�g",
	'lbl_rom_rescan_folder' =>
		"ROM�f�B���N�g��������",
	'lbl_meta_remove' =>
		"DB����META��폜",
	'lbl_rating_unset' =>
		"�]���ݒ薳��",
	
	/* 0.9 FYEO 9*/
	'lbl_roms_remove_dup_preview%s' =>
		"����ROM�����t����܂���",
	/* 0.9 FYEO 9*/
	'lbl_roms_dup' =>
		"����ROM",
	
	/* 0.9.1 FYEO 3*/
	'lbl_img_remove_all' =>
		"ROM�C���[�W��폜",
	/* 0.9.1 FYEO 4*/
	'lbl_meta_compare_left' =>
		"��r - ������I��",		
	'lbl_meta_compare_right%s' =>
		"\"%s\"���r",	

	/* 0.9.2 FYEO 2*/
	'lbl_start_with' =>
		"...��ROM��N��",
	'lbl_emu_config' =>
		"�G�~�����[�^�̐ݒ�",
	'lbl_quickfilter' =>
		"�N�C�b�N�t�B���^",
	'lbl_quickfilter_reset' =>
		"�N�C�b�N�t�B���^�̃��Z�b�g",

	/* 0.9.6 FYEO 1 */
	'lbl_dat_import_ecc_romdb' =>
		"Online Datfile import",

	/* 0.9.6 FYEO 8 */
	'lContextRomSelectionAddNewRoms%s' =>
		"Add new %s roms",
	'lContextRomSelectionRemoveRoms%s' =>
		"Remove all %s roms from Database",
	'lContextMetaRemove' =>
		"Remove ROM metadata",

	/* 0.9.6 FYEO 11 */
	'lbl_importDatCtrlMAME' =>
		"Import ClrMAME datfile",

	/* 0.9.6 FYEO 13 */
	'labelRomAuditInfo' =>
		"Show rom audit info",
	'labelRomAuditReparse' =>
		"Updated rom audit infos",
	'lbl_roms_rescan_all' =>
		"Rescan all rom folders",
	'lbl_roms_add' =>
		"Add new roms",
		
	/* 0.9.6 FYEO 11 */
	'lbl_open_eccuser_folder%s' =>
		"Open eccUser-Folder (%s)",
	'lbl_rom_remove_toplevel' =>
		"Remove rom(s)",
	'menuItemPersonalEditNote' =>
		"Edit notes",
	'menuItemPersonalEditReview' =>
		"Edit review",
		
	/* 0.9.6 FYEO 11 */
	'menuItemRomOptions' =>
		"Rom options",

	/* 0.9.7 FYEO 17 */
	'imagepackTopMenu' =>
		"imagepack helpers",
	'imagepackRemoveImagesWithoutRomFile' =>
		"Remove images for roms that i dont have in database",
	'imagepackRemoveEmptyFolder' =>
		"Remove empty folder",
	'imagepackCreateAllThumbnails' =>
		"Create thumbnails for faster access",
	'imagepackRemoveAllThumbnails' =>
		"Remove thumbnails for imagepack exchange",
	'imagepackConvertEccV1Images' =>
		"Convert flat images to new imagepack structure! (V1->V2)",

	/* 0.9.7 FYEO 17 */
	'onlineSearchForRom' =>
		"Search for rom in web",
	'onlineEccRomdbShowWebInfo' =>
		"Search for rom in romdb",

	/* 0.9.8 FYEO 04 */
	'lbl_meta_edit_top' =>
		"Edit meta",

	/* 0.9.9 FYEO 01 */
	'lblOpenAssetFolder' =>
		"Browse documents folder",

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