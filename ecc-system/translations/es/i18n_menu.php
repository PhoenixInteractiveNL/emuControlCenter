<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:es (Spanish)
 * author:	Jarlaxe
 * date:	2012/07/09 
 * ------------------------------------------
 */
$i18n['menu'] = array(
	// -------------------------------------------------------------
	// context menu navigation
	// -------------------------------------------------------------
	'lbl_platform%s' =>
		"%s opciones",
	'lbl_roms_add%s' =>
		"Agregar nuevas Roms de %s",
	'lbl_roms_optimize%s' =>
		"Optimizar ROMs",
	'lbl_roms_remove%s' =>
		"Eliminar ROMs",
	'lbl_roms_remove_dup%s' =>
		"Eliminar ROMs duplicadas",
	'lbl_emu_config' =>
		"Editar/Asignar Emulador",
	'lbl_ecc_config' =>
		"Configuración",
	'lbl_dat_import_ecc' =>
		"Importar Datos de EmuControlCenter",
	'lbl_dat_import_rc' =>
		"Importar Datos de Romcenter",
	'lbl_dat_export_ecc_full' =>
		"Exportar datos ECC completos",
	'lbl_dat_export_ecc_user' =>
		"Exportar datos ECC de usuario",
	'lbl_dat_export_ecc_esearch' =>
		"Exportar datos ECC de búsqueda",
	'lbl_dat_empty' =>
		"Base de datos vacía",
	'lbl_help' =>
		"Ayuda",
	// -------------------------------------------------------------
	// context menu main
	// -------------------------------------------------------------
	'lbl_start' =>
		"Iniciar Rom",
	'lbl_fav_remove' =>
		"Eliminar este favorito",
	'lbl_fav_all_remove' =>
		"Eliminar TODOS los favoritos",
	'lbl_fav_add' =>
		"Agegar a favoritos",
	'lbl_image_popup' =>
		"Abrir 'imageCenter'",
	'lbl_img_reload' =>
		"Recargar imágenes",
	'lbl_rom_remove' =>
		"Eliminar ROM de 'DB'",
	'lbl_meta_edit' =>
		"Eitar Meta-datos",
	'lbl_roms_initial_add%s%s' =>
		"No encontradas ROMS de\n----------------------------------------\n%s (%s)\n----------------------------------------\n'Click' para agregar nuevas ROMS!",
	'lbl_meta_webservice_meta_get' =>
		"Obtener datos de 'eccdb' (Internet)",
	'lbl_meta_webservice_meta_set' =>
		"Agregar tus datos a 'eccdb' (Internet)",
	// File operations
	'lbl_shellop_submenu' =>
		"Gestión de archivos",
	'lbl_shellop_browse_dir' =>
		"Buscar directorio de ROMs",
	'lbl_shellop_file_rename' =>
		"Renombrar archivo en el disco duro",
	'lbl_shellop_file_copy' =>
		"Copiar archivo al disco duro",
	'lbl_shellop_file_unpack' =>
		"Descomprimir archivo",
	'lbl_shellop_file_remove' =>
		"Eliminar archivo del disco duro",
	// Rating
	'lbl_rating_submenu' =>
		"Calificar ROM",
	'lbl_import_submenu' =>
		"Importar Datos",
	'lbl_export_submenu' =>
		"Exportar Datos",
	'lbl_rom_rescan_folder' =>
		"(Re)escanear la carpeta de ROMs",
	'lbl_meta_remove' =>
		"Eliminar Meta-datos desde 'DB'",
	'lbl_rating_unset' =>
		"Eliminar calificaciones",
	
	/* 0.9 FYEO 9*/
	'lbl_roms_remove_dup_preview%s' =>
		"Buscar ROMs duplicadas",
	/* 0.9 FYEO 9*/
	'lbl_roms_dup' =>
		"ROMs duplicadas",
	
	/* 0.9.1 FYEO 3*/
	'lbl_img_remove_all' =>
		"Eliminar imágenes de las ROMs",
	/* 0.9.1 FYEO 4*/
	'lbl_meta_compare_left' =>
		"Comparar (Selecionar a la izquierda)",		
	'lbl_meta_compare_right%s' =>
		"Comparar con \"%s\"",	

	/* 0.9.2 FYEO 2*/
	'lbl_start_with' =>
		"Iniciar ROM con...",
	'lbl_emu_config' =>
		"Configurar Emulador",
	'lbl_quickfilter' =>
		"Filtrado rápido",
	'lbl_quickfilter_reset' =>
		"Eliminar filtrado rápido",

	/* 0.9.6 FYEO 1 */
	'lbl_dat_import_ecc_romdb' =>
		"Importar archivo desde 'romDB' (internet)",

	/* 0.9.6 FYEO 8 */
	'lContextRomSelectionAddNewRoms%s' =>
		"Agregar nuevas ROMs de %s",
	'lContextRomSelectionRemoveRoms%s' =>
		"Eliminar todas las ROMs de %s",
	'lContextMetaRemove' =>
		"Eliminar Meta-datos de las ROMs",

	/* 0.9.6 FYEO 11 */
	'lbl_importDatCtrlMAME' =>
		"Importar datos de CRL MAME",

	/* 0.9.6 FYEO 13 */
	'labelRomAuditInfo' =>
		"Mostrar información de la auditoría de ROMs",
	'labelRomAuditReparse' =>
		"Actualizar información de auditoría de ROMs",
	'lbl_roms_rescan_all' =>
		"(Re) analizar las carpetas de ROMs",
	'lbl_roms_add' =>
		"Agregar nuevas ROMs",
		
	/* 0.9.6 FYEO 11 */
	'lbl_open_eccuser_folder%s' =>
		"Abrir la carpeta 'eccUser' (%s)",
	'lbl_rom_remove_toplevel' =>
		"Eliminar ROM(s)",
	'menuItemPersonalEditNote' =>
		"Editar notas",
	'menuItemPersonalEditReview' =>
		"Editar análisis",
		
	/* 0.9.6 FYEO 11 */
	'menuItemRomOptions' =>
		"Opciones de ROMs",

	/* 0.9.7 FYEO 17 */
	'imagepackTopMenu' =>
		"Ayudante 'imagepack'",
	'imagepackRemoveImagesWithoutRomFile' =>
		"Eliminar imágenes de las ROMs que no estén en la base de datos",
	'imagepackRemoveEmptyFolder' =>
		"Eliminar carpetas vacías",
	'imagepackCreateAllThumbnails' =>
		"Crear miniaturas para un acceso más rápido",
	'imagepackRemoveAllThumbnails' =>
		"Eliminar miniaturas para intercambio de 'imagepacks'",
	'imagepackConvertEccV1Images' =>
		"Convertir imágenes planas a la nueva estructura 'imagepack'! (V1->V2)",

	/* 0.9.7 FYEO 17 */
	'onlineSearchForRom' =>
		"Buscar ROM en la web",
	'onlineEccRomdbShowWebInfo' =>
		"Buscar ROM en 'romdb'",

	/* 0.9.8 FYEO 04 */
	'lbl_meta_edit_top' =>
		"Editar Meta-Datos",

	/* 0.9.9 FYEO 01 */
	'lblOpenAssetFolder' =>
		"Buscar carpeta de Documentos",
	
	/* 0.9.8 FYEO 05 */
	'lbl_image_inject' =>
		"Descargar imágenes",

	/* 1.12 BUILD 06 */
	'lbl_image_platform' =>
		"Imágenes de la plataforma",	

	'lbl_image_platform_import_online' =>
		"Importar imágenes online para esta plataforma (necesita código kameleon)",	

	'lbl_image_platform_import_local' =>
		"Importar imágenes para esta plataforma desde una carpeta local (no ecc, como no-intro)",

	'lbl_image_platform_export_local' =>
		"Crear un 'imagepack' de esta plataforma (ecc, no-intro, emumovies)",
);
?>