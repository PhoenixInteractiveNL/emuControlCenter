<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	es (Spanish)
 * author:	Jarlaxe
 * date:	2009/07/09
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Localiza tu carpeta de imágenes!",
	'rom_add_parse_title%s' =>
		"Añade nuevas Roms para %s",
	'rom_add_parse_msg%s%s' =>
		"¿Añadir nuevas Roms para\n\n%s\n\ndesde el directorio\n\n%s?",
	'rom_add_parse_done_title' =>
		"Análisis completado",
	'rom_add_parse_done_msg%s' =>
		"Análisis de nuevas ROMS \n\n%s\n\ncompletado!",
	'rom_remove_title%s' =>
		"LIMPIARDB DE %s",
	'rom_remove_msg%s' =>
		"¿QUIERES LIMPIAR LA BASE DE DATOS DE \n\"%s\"MEDIA?\n\nEsta acción eliminará todos los datos seleccionados de la base de datos ecc. Esta acción NO eliminará your los datos o medios de tu disco duro!",
	'rom_remove_done_title' =>
		"Limpieza deDB completada",
	'rom_remove_done_msg%s' =>
		"Todos los datos de %s han sido eliminados en ecc-database",
	'rom_remove_single_title' =>
		"¿Eliminar ROMs de la base de datos?",
	'rom_remove_single_msg%s' =>
		"¿Debo eliminar\n\n%s\n\nde la base de datos ecc?",
	'rom_remove_single_dupfound_title' =>
		"Encontradas ROMs duplicadas!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ENCONTRADAS ROMS DUPLICADAS\n\n¿También debería eliminar todos los duplicados de\n\n%s\n\n de la base de datos ecc?\n\nMirar AYUDA para más información!",
	'rom_optimize_title' =>
		"Optimizar la base de datos",
	'rom_optimize_msg' =>
		"¿Deseas optimizar tus ROMs la base de datos ecc?\n\nDeberías optimizar la base de datos si has movido o eliminado archivos de tu disco duro\necc busca automáticamente entradas en favoritos y la base de datos para poder eliminarlos!\nEsta opción sólo modifíca la base de datos.",
	'rom_optimize_done_title' =>
		"Optimización completada!",
	'rom_optimize_done_msg%s' =>
		"La base de datos de la plataforma\n\n%s\n\nahora está actualizada!",
	'rom_dup_remove_title' =>
		"¿Eliminar ROMs duplicadas de ecc-database?",
	'rom_dup_remove_msg%s' =>
		"¿Deseas eliminar todas las ROMs duplicadas de\n\n%s\n\nen ecc-database?\n\nEsta función sólo edita la base de datos de emuControlCenter....\n\nEsto NO eliminará los archivos de tu disco duro!!!",
	'rom_dup_remove_done_title' =>
		"Eliminación realizada",
	'rom_dup_remove_done_msg%s' =>
		"Todas las ROMs de\n\n%s\n\nhan sido eliminadas de ecc-database",
	'rom_reorg_nocat_title' =>
		"No hay categorías!",
	'rom_reorg_nocat_msg%s' =>
		"No has asignado una categoría para tus ROMs de\n\n%s\n\n Por favor, usa la opción editar para añadir categorías o impórtalas desde un archivo ecc correcto!",
	'rom_reorg_title' =>
		"¿Reorganizar tus ROMs en el disco duro?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------ESTA OPCIÓN, PUEDE REORGANIZAR TUS ROMS EN EL DISCO DURO !!! POR FAVOR, PRIMERO ELIMINA LOS DUPLICADOS EN ECC-DB !!!\nTU MODO SELECCIONADO ES: #%s#\n------------------------------------------------------------------------------------------\n\nDo you want to reorganize your roms by categories for\n\n%s\n\nat your filesystem? ecc will organize your roms in the ecc-userfolder under\n\n%s/roms/organized/\n\nPlease check the discspace of this harddrive, if there is space available\n\nDO YOU WANT THIS AT YOUR RISK? :-)",
	'rom_reorg_done_title' =>
		"Reorganización completada",
	'rom_reorg_done__msg%s' =>
		"Echa un vistazo a tus archivos de la carpeta\n\n%s\n\npara validar la copia",
	'db_optimize_title' =>
		"Optimizar sistema de base de datos",
	'db_optimize_msg' =>
		"¿Deseas optimizar la base de datos?\nEsto disminuirá el tamaño físico de la base de datos. Deberías usarlo si analizas y eliminas medios a menudo!\n\nEsta operación congelará durante unos segundos la aplicación - por favor, espera! :-)",
	'db_optimize_done_title' =>
		"Base de datos optimizada!",
	'db_optimize_done_msg' =>
		"Ahora, tu base de datos está optimizada!",
	'export_esearch_error_title' =>
		"No hay seleccionadas opciones de búsqueda",
	'export_esearch_error_msg' =>
		"Debes usar la búsqueda extendida para usar esta función de exportación. Esto sólo exportará el resultado de las búsquedas, pdrás verlo en la vista principal!",
	'dat_export_filechooser_title%s' =>
		"Elige na ruta para guardar el archivo de datos %s!",	
	'dat_export_title%s' =>
		"Exportar archivo de datos de %s",
	'dat_export_msg%s%s%s' =>
		"¿Deseas exportar el archivo de datos %s para la plataforma\n\n%s\n\ndentro de este directorio?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"\n\necc utilizará tu selección de búsqueda para exportar!",
	'dat_export_done_title' =>
		"Exportación realizada",
	'dat_export_done_msg%s%s%s' =>
		"Exportación del archivo de datos%s para\n\n%s\n\en el directorio\n\n%s\n\nfinalizada!",
	'dat_import_filechooser_title%s' =>
		"Importar: Elige un archivo de datos de %s!",
	'rom_import_backup_title' =>
		"¿Crear copia de seguridad?",
	'rom_import_backup_msg%s%s' =>
		"Deberías crear una copia de seguridad en tu carpeta de usuario\n\n%s (%s)\n\nantes de importar nuevos Meta-datos?",
	'rom_import_title' =>
		"¿Importar archivo de datos?",
	'rom_import_msg%s%s%s' =>
		"¿Seguro de que deseas importar los datos de la plataforma\n\n%s (%s)\n\ndesde el archivo de datos\n\n%s?",
	'rom_import_done_title' =>
		"Importación finalizada!",
	'rom_import_done_msg%s' =>
		"Importación desde\n\n%s\n\nfinalizada!",
	'dat_clear_title%s' =>
		"DESPEJAR BD PARA %s",
	'dat_clear_msg%s%s' =>
		"¿QUIERES DESPEJAR TODOS LOS DATOS META-INFORMACIÓN DE\n\n%s (%s)?\n\nEsto eliminará todos los datos de meta-información, como categoría, estado, idiomas, etc. de la plataforma seleccionada!. En el siguiente paso, PUEDES CREAR UNA COPIA DE SEGURIDAD DE ESTA INFORMACIÓN. (se guardará automáticamente en tu carpeta de usuario!)\n\nEl último paso, es optimizar la base de datos!",
	'dat_clear_backup_title%s' =>
		"Copia de seguridad %s",
	'dat_clear_backup_msg%s%s' =>
		"¿Debería crear una copia de seguridad para la plataforma\n\n%s (%s)?",
	'dat_clear_done_title%s' =>
		"BD despejada",
	'dat_clear_done_msg%s%s' =>
		"Toda la meta-información para\n\n%s (%s)\n\nha sido eliminada de la base de datos de ECC!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n ECC dispone de una copia de seguridad en tu carpeta de usuario %s",
	'emu_miss_title' =>
		"Error - Emulador no encontrado!",
	'emu_miss_notfound_msg%s' =>
		"El emulador asignado no fue encontrado. Verde indica la ubicación válida de un emulador, y roja, la de una no válida.",
	'emu_miss_notset_msg' =>
		"No has asignado ningún emulador válido para esta plataforma",
	'emu_miss_dir_msg%s' =>
		"La Ruta asignada es un directorio!!!!",
	'img_overwrite_title' =>
		"¿Sobrescribir imagen?",
	'img_overwrite_msg%s%s' =>
		"La imagen\n\n%s\n\nya existe\n\n¿Realmente deseas sobreescribir la imagen\n\n%s?",	
	'img_remove_title' =>
		"¿Eliminar imagen?",
	'img_remove_msg%s' =>
		"¿Realmente deseas eliminar la imagen %s?",
	'img_remove_error_title' =>
		"Error - No es posible eliminar la imagen!",
	'img_remove_error_msg%s' =>
		"La imagen %s no pudo ser eliminada!",
	'conf_platform_update_title' =>
		"¿Actualizar inicio de plataforma?",
	'conf_platform_update_msg%s' =>
		"¿Realmente deseas actualizar el inicio para %s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Elige un emulador para la extensión '%s'",
	'conf_userfolder_notset_title' =>
		"ERROR: No se pudo encontrar la carpeta de usuario!!!",
	'conf_userfolder_notset_msg%s' =>
		"Se ha alterado 'base_path' en tu 'ecc_general.ini'. Esta carpeta no se puede crear por ahora.\n\n¿Debería crear el directorio\n\n%s\n\n?\n\nSi deseas elegir otra carpeta, haz 'click' en NO y usa \n'opciones'->'configuración'\npara configurar tu carpeta de usuario!",
	'conf_userfolder_error_readonly_title' =>
		"ERROR: No se pudo crear la carpeta!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"La carpeta %s no se pudo crear debido a que es un medio de sólo lectura (CD?)\n\nIf si deseas elegir otra carpeta, haz 'click' en OK y ve a \n'ociones'->'configuración'\npara configurar tu carpeta de usuario!",
	'conf_userfolder_created_title' =>
		"Carpeta de usuario creada!",
	'conf_userfolder_created_msg%s%s' =>
		"Las subcarpetas\n\n%s\n\nson creadas en tu carpeta de usuario seleccionada\n\n%s",
	'conf_ecc_save_title' =>
		"¿Actualizar el INICIO GLOBAL de ECC?",
	'conf_ecc_save_msg' =>
		"Esto escribirá tus cambios de configuración en ecc_global.ini\n\nTambíen creará la carpeta usuario y sus subcarpetas necesarias\n\n¿Realmente quieres hacer esto?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Selecciona la carpeta de tus Datos de Usuario",
	'fav_remove_all_title' =>
		"¿Eliminar todos los favoritos?",
	'fav_remove_all_msg' =>
		"¿Realmente deseas eliminar TODOS LOS FAVORITOS?",
	'maint_empty_history_title' =>
		'Reiniciar ecc history.ini?',
	'maint_empty_history_msg' =>
		'Esto vaciará el archivo history.ini. Este, almacena tus opciones de ECC (ej. ocultar Roms duplicadas) y rutas seleccionadas! ¿Debo reiniciar este archivo?',
	'sys_dialog_info_miss_title' =>
		"¿¿ TÍTULO EXTRAVIADO ??",
	'sys_dialog_info_miss_msg' =>
		"¿¿ MENSAJE EXTRAVIADO ??",
	'sys_filechooser_miss_title' =>
		"¿¿ TÍTULO EXTRAVIADO ??",
	'status_dialog_close' =>
		"\n\nDebería cerrar el area de detalle de estado?",
	'status_process_running_title' =>
		"Proceso en ejecución",
	'status_process_running_msg' =>
		"Otro proceso se está ejecutando\nSólo puedes iniciar un proceso a la vez, como analizar/importar/exportar! Por favor, espera hasta que el proceso en ejecución finalice!",
	'meta_rating_add_error_msg' =>
		"Sólo se puede puntuar una rom con meta-datos.\n\nPor favor, usa EDITAR y créa esta meta-información!",
	'maint_unset_ratings_title' =>
		"¿Eliminar calificaciones de esta plataforma?",
	'maint_unset_ratings_msg' =>
		"Esto reiniciará todas las calificaciones en la base de datos... ¿Debo hacerlo?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Estadísticas:\n\n%s añadidos\n%s ya en existentes\n%s errores\n\n%s set de datos procesados!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Meta-datos:\n\nPara apoyar la comunidad ECC, puedes añadir o modificar metadatos (título, categoría, idiomas, etc.) en ECCDB (Base de datos en Internet).\n\nEsto funciona como el conocido 'CDDB' para pistas o 'tracks' de CD.\n\nSi das tu autorización, ECC transferirá automáticamentetus datos a 'eccdb'!\n\nTienes que estar conectado a Internet para agregar tus contenidos!!!\n\nCada 10 Meta-datos procesados, se te pedirá confirmación para añadir más!",
	'eccdb_error' =>
		"eccdb - Errores:\n\nQuizás no estás conectado a Internet... sólo con una conexión activa podrás añadir tu información a 'eccdb'!",
	'eccdb_no_data' =>
		"eccdb - No encontrados datos a añadir:\n\nDebes modificar algo para poder añadirlo a 'eccdb'. Usa el botón EDITAR y prueba de nuevo!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Esta opción busca Roms duplicadas en tu base de datos\n\nTambién encontrará el 'log' en tu carpeta de registros, etc!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"¿Eliminar todas las imágenes?",
	'img_remove_all_msg%s' =>
		"Esto eliminará todas las imágenes del juego seleccionado!\n\n¿Debo eliminar las imágnes de\n\n%s?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"confirmar",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"¿Analizar realmente este archivo?",
	'parse_big_file_found_msg%s%s' =>
		"ARCHIVO GRANDE ENCONTRADO!!!\n\nEl juego encontrado\n\nNombre: %s\nTamañi: %s\n\nes muy grande. Esto puede llevar mucho tiempo sin información directa de emuControlCenter.\n\n¿Quieres analizar este juego?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Favorito guardado",
	'bookmark_added_msg' =>
		"El favorito ha sido añadido!",
	'bookmark_removed_single_title' =>
		"Favorito eliminado",
	'bookmark_removed_single_msg' =>
		"Este favorito ha sido eliminado!",
	'bookmark_removed_all_title' =>
		"Eliminados todos los favoritos",
	'bookmark_removed_all_msg' =>
		"Todos los favoritos han sido eliminados!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Archivo de datos actualizado desde Internet",
	'eccdb_webservice_get_datfile_msg%s' =>
		"¿Realmente quieres actualizar la plataforma\n\n%s\n\ncon los datos en línea de romDB?\n\nPara esta función, se ha de disponer de conexión a Internet",

	'eccdb_webservice_get_datfile_error_title' =>
		"No se ha podido importar el archivo de datos",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Tienes que estar conectado a Internet. Por favor, conecta y prueba de nuevo!",

	'romparser_fileext_problem_title%s' =>
		"PROBLEMA ENCONTRADO EN  LA EXTENSIÓN %s",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter a encontrado que más de una plataforma usa esta extensión %s para buscar roms!\n\n%s\n¿Estás seguro de que sólo hay juegos %s en la carpeta seleccionada? %s\n\n<b>OK</b>: Buscar %s para esta carpeta / platforma!\n\n<b>CANCELAR</b>: Omitir la extensión %s para esta carpeta / platforma!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Buscar ROMs duplicadas",
	'rom_dup_remove_done_title_preview' => 
		"Búsqueda realizada",
	'rom_dup_remove_done_msg_preview' =>
		"Echa un vistazo en el area de estado para más detalles!",
	'metaRemoveSingleTitle' =>
		"Eliminar Meta-datos de la Rom",
	'metaRemoveSingleMsg' =>
		"¿Quieres eliminar los Meta-datos de esta Rom?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Elegir un archivo 'dat' de CtrlMAME!\n",
	'importDatCMConfirmTitle' =>
		"Importar 'dat' de ctrlMAME",
	'importDatCMConfirmMsg%s%s%s' =>
		"¿Quieres realmente importar datos para la plataforma\n\n%s (%s)\n\ndesde el archivo 'dat'\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Actualizar información de Roms auditadas",
	'romAuditReparseMsg%s' =>
		"Esto actualizará la información almacenada, así como el estado completo de una Rom multi-archivo\n\n¿Actualizar este dato?",
	'romAuditInfoNotPossibelTitle' =>
		"No disponible información de auditoría de Rom",
	'romAuditInfoNotPossibelMsg' =>
		"La información de auditoría de Roms, sólo está disponible para plataformas multi-rom (ej. plataformas Arcade)",

	'romReparseAllTitle' =>
		"Volver a analizar tu carpeta de Roms",
	'romReparseAllMsg%s' =>
		"¿Buscar nuevas Roms para la(s) plataforma(s) seleccionadas?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Desmarcar estas extensiones",
	'parserUnsetExtMsg%s' =>
		"Debido a que has seleccionado '#All found', ecc extensiones duplicadas de búsqueda para prevenir asignaciones erroneas en la base de datos!\n\nemuControlCenter no buscará: %s\n\nPor favor, seleccione la plataforma de la derecha para analizar estas extensiones!\n\n",

	'stateLabelDatExport%s%s' =>
		"Exportar archivo %s para %s",
	'stateLabelDatImport%s' =>
		"Importar archivo para %s",

	'stateLabelOptimizeDB' =>
		"Optimizar base de datos",
	'stateLabelVacuumDB' =>
		"Vaciar base de datos",
	'stateLabelRemoveDupRoms' =>
		"Eliminar Roms duplicadas",
	'stateLabelRomDBAdd' =>
		"Añadir información a 'romDB'",
	'stateLabelParseRomsFor%s' =>
		"Analizando Roms de %s",
	'stateLabelConvertOldImages' =>
		"Convirtiendo imágenes...",

	'processCancelConfirmTitle' =>
		"¿Cancelar proceso actual?",
	'processCancelConfirmMsg' =>
		"¿Deseas realmente cancelar este proceso?",
	'processDoneTitle' =>
		"Proceso completado!",
	'processDoneMsg' =>
		"El proceso se ha completado!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"La copia de seguridad con extensión XML se ha creado en tu carpeta de usuario /#_GLOBAL/ \n\n%s\n\n¿Ver ahora el archibo XML exportado con tu navegador?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"¿Quieres ejecutar realmente esta tarea?",
	'executePostShutdownTaskMessage%s' =>
		"\nTarea: <b>%s</b>\n\n¿Realmente quieres ejecutar esta larga tarea?",
	'postShutdownTaskTitle' =>
		"Ejecutar tarea seleccionada",
	'postShutdownTaskMessage' =>
		"Has seleccionado una tarea solo ejecutable con emuControlCenter cerrado.\n\nDespués de esta tarea, <b>emuControlCenter se reiniciará automáticamente!</b>\n\nEsto puede tardar unos segundos, unos minutos o algunas horas! Este ventana quedará congelada! No te asustes! :-)\n\n<b>Por favor, espera!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Archivo Rom no encontrado...",
	'startRomFileNotAvailableMessage' =>
		"Parece ser que no tienes esta Rom!\n\nInténtalo de nuevo tras seleccionar el modo de visualización 'Todas (las que tengo)' :-)",
	'startRomWrongFilePathTitle' =>
		"Rom existente en la base de datos, pero archivo no encontrado",
	'startRomWrongFilePathMessage' =>
		"¿Tal vez has movido tus Roms a otra posición o las has eliminado?\n\nPor favor, usa la opción 'ROMS' -> 'Optimizar ROMs' para limpiar tu base de datos!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Descargar imágenes",
	'waitForImageInjectMessage' =>
		"Esta tarea podría tardar un poco. Si se encuentran imágenes, esta ventana se cerrará automáticamente y podrás verlas en la lista!\n\nSi no encuentra imágenes, la ventana se cerrará pero no habrán cambios en la lista! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"¿Copiar/mover archivos por resultado de búsqueda?",
	'copy_by_search_msg_waring%s%s%s' =>
		"Esta opción copia/renombra TODOS los juegos encontrados en tu resultado de búsqueda (Ten cuidado: Si no has buscado, se seleccionarán todos los archivos!)\n\nPuedes selccionar el destino en la siguiente ventana.\n\nHay que encontrar <b>%s juegos</b> en tu resultado de búsqueda\n\n<b>%s juegos comprimidos</b> se saltarán!\n\n¿Realmente quieres copiar/mover estos <b>%s</b> juegos a otra ubicación?",
	'copy_by_search_msg_error_noplatform' =>
		"Tienes que seleccionar una plataforma para poder utilizar esta función. No es posible usar esta función para TODO LO ENCONTRADO!",
	'copy_by_search_msg_error_notfound%s' =>
		"Se han encontrado juegos no válidos en tu resultado de búsqueda. <b>%s juegos comprimidos</b> saltados.",
	'searchTab' =>
		"Resultado de la búsqueda",
	'searchDescription' =>
		"Here you can copy or move files from their source folder to a specified one.\n<b>Source is your current search result.</b>\nIf you move, also the paths in your database are updated! Clean by checksum remove files that are 100% duplicate!",
	'searchHeadlineMain' =>
		"Introducción",
	'searchHeadlineOptionSameName' =>
		"mismo nombre",
	'searchRadioDuplicateAddNumber' =>
		"añadir número",
	'searchRadioDuplicateOverwrite' =>
		"sobrescribir",
	'searchCheckCleanup' =>
		"limpiar por checksum",

);
?>