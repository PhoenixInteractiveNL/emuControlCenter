<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	nl (dutch)
 * author:	Sebastiaan Ebeltjes
 * date:	2006/06/26
 * ------------------------------------------
 */
$i18n['menu'] = array(
	// -------------------------------------------------------------
	// context menu navigation
	// -------------------------------------------------------------
	'lbl_platform%s' =>
		"%s opties",
	'lbl_roms_add%s' =>
		"Nieuwe ROMs toevoegen voor %s",
	'lbl_roms_optimize%s' =>
		"Optimaliseer ROMS",
	'lbl_roms_remove%s' =>
		"Verwijder ROMS",
	'lbl_roms_remove_dup%s' =>
		"Verwijder dubbele ROMS",
	'lbl_emu_config' =>
		"Edit/Assign emulator",
	'lbl_ecc_config' =>
		"Configuratie",
	'lbl_dat_import_ecc' =>
		"Importeer emuControlCenter DAT-bestand",
	'lbl_dat_import_rc' =>
		"Importeer Romcenter DAT-bestand",
	'lbl_dat_export_ecc_full' =>
		"Exporteer volledig ECC DAT-bestand",
	'lbl_dat_export_ecc_user' =>
		"Exporteer ECC-user DAT-bestand",
	'lbl_dat_export_ecc_esearch' =>
		"Exporteer ECC-eSearch DAT-bestand",
	'lbl_dat_empty' =>
		"Maak DAT-bestand database leeg",
	'lbl_help' =>
		"Help",
	// -------------------------------------------------------------
	// context menu main
	// -------------------------------------------------------------
	'lbl_start' =>
		"Start ROM",
	'lbl_fav_remove' =>
		"Verwijder deze favoriet",
	'lbl_fav_all_remove' =>
		"Verwijder alle favorieten",
	'lbl_fav_add' =>
		"Toevoegen aan favorieten",
	'lbl_image_popup' =>
		"Open imageCenter",
	'lbl_img_reload' =>
		"Herlaad plaatjes",
	'lbl_rom_remove' =>
		"Verwijder ROM uit DB",
	'lbl_meta_edit' =>
		"BEWERK METADATA",
	'lbl_roms_initial_add%s%s' =>
		"Geen ROMS gevonden voor platform\n----------------------------------------\n%s (%s)\n----------------------------------------\nKlik hier om nieuwe ROMS toe te voegen!",
	'lbl_meta_webservice_meta_get' =>
		"Haal data op van ECCdb (Internet)",
	'lbl_meta_webservice_meta_set' =>
		"Voeg je gegevens toe aan ECCdb (Internet)",
	// File operations
	'lbl_shellop_submenu' =>
		"Bestand operaties",
	'lbl_shellop_browse_dir' =>
		"Verken ROM folder",
	'lbl_shellop_file_rename' =>
		"Hernoem bestand op hardeschijf",
	'lbl_shellop_file_copy' =>
		"Kopieer bestand op hardeschijf",
	'lbl_shellop_file_unpack' =>
		"Dit bestand uitpakken",
	'lbl_shellop_file_remove' =>
		"Verwijder bestand van hardeschijf",
	// Rating
	'lbl_rating_submenu' =>
		"Beoordeel ROM",
	'lbl_import_submenu' =>
		"Importeer DAT-bestand",
	'lbl_export_submenu' =>
		"Exporteer DAT-bestand",
	'lbl_rom_rescan_folder' =>
		"(Her)parse ROM folder",
	'lbl_meta_remove' =>
		"Verwijder META van DB",
	'lbl_rating_unset' =>
		"Verwijder beoordeling",
	
	/* 0.9 FYEO 9*/
	'lbl_roms_remove_dup_preview%s' =>
		"Zoek dubbele ROMS",
	/* 0.9 FYEO 9*/
	'lbl_roms_dup' =>
		"Dubbele ROMS",
	
	/* 0.9.1 FYEO 3*/
	'lbl_img_remove_all' =>
		"Verwijder ROM plaatjes",
	/* 0.9.1 FYEO 4*/
	'lbl_meta_compare_left' =>
		"VERGELIJK - Selecteer linker zijde",		
	'lbl_meta_compare_right%s' =>
		"VERGELIJK naar \"%s\"",	

	/* 0.9.2 FYEO 2*/
	'lbl_start_with' =>
		"Start ROM met...",
	'lbl_emu_config' =>
		"Configureer emulator",
	'lbl_quickfilter' =>
		"Snelfilter",
	'lbl_quickfilter_reset' =>
		"Snelfilter uit",

	/* 0.9.6 FYEO 1 */
	'lbl_dat_import_ecc_romdb' =>
		"Online DAT-bestand importeren",

	/* 0.9.6 FYEO 8 */
	'lContextRomSelectionAddNewRoms%s' =>
		"Voeg nieuwe %s ROMS toe",
	'lContextRomSelectionRemoveRoms%s' =>
		"Verwijder alle %s ROMS uit de database",
	'lContextMetaRemove' =>
		"Verwijder ROM metadata",

	/* 0.9.6 FYEO 11 */
	'lbl_importDatCtrlMAME' =>
		"Importeer CtrlMAME DAT-bestand",

	/* 0.9.6 FYEO 13 */
	'labelRomAuditInfo' =>
		"Bekijk ROM controle info",
	'labelRomAuditReparse' =>
		"ROM controle info is bijgewerkt",
	'lbl_roms_rescan_all' =>
		"Herscan alle ROM folders",
	'lbl_roms_add' =>
		"Nieuwe ROMS toevoegen",
		
	/* 0.9.6 FYEO 11 */
	'lbl_open_eccuser_folder%s' =>
		"Open ECC gebruikers folder (%s)",
	'lbl_rom_remove_toplevel' =>
		"Verwijder ROM(S)",
	'menuItemPersonalEditNote' =>
		"Bewerk notities",
	'menuItemPersonalEditReview' =>
		"Bewerk samenvatting",
		
	/* 0.9.6 FYEO 11 */
	'menuItemRomOptions' =>
		"ROM opties",

	/* 0.9.7 FYEO 17 */
	'imagepackTopMenu' =>
		"Instrumenten plaatjespakketten",
	'imagepackRemoveImagesWithoutRomFile' =>
		"Verwijder plaatjes van ROMS die ik niet in mijn database heb",
	'imagepackRemoveEmptyFolder' =>
		"Verwijder lege folder(s)",
	'imagepackCreateAllThumbnails' =>
		"Maak kleine plaatjes aan voor snellere toegangstijden",
	'imagepackRemoveAllThumbnails' =>
		"Verwijder alle kleine plaatjes om plaatjepakketten uit te wisselen",
	'imagepackConvertEccV1Images' =>
		"Converteer oude plaatjes van ECC <0.9.1 naar de nieuwe structuur! (V1->V2)",

		
);
?>