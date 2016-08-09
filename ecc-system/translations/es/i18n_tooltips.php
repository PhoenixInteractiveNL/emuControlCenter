<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	es (Spanish)
 * author:	Jarlaxe
 * date:	2013/01/14
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"Alterna búsqueda/actualización automática para la navegación",
	'opt_hide_nav_null' =>
		"Muestra/Oculta las platformas sin Roms",
	'opt_hide_dup' =>
		"Muestra/Oculta las Roms duplicadas",
	'opt_hide_img' =>
		"Muestra/Oculta las imágenes",
	'search_field_select' =>
		"¿Por que parámetro deseas buscar?",
	'search_operator' =>
		"Elije un operador de búsqueda. (= IGUAL) ( | O ) ( + MÁS)",
	'search_rating' =>
		"Sólo mostrar roms con CALIFICACIÓN igual o menor a la seleccionada",
	'optvis_mainlistmode' =>
		"Alterna entre listado detallado o simple",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"'Click' para añadir tu calificación a esta Rom",
	'nbMediaInfoNoteEvent' =>
		"Muestra las notas de esta Rom",
	'nbMediaInfoReviewEvent' =>
		"Muestra el análisis de este juego",
	'nbMediaInfoBookmarkEvent' =>
		"Añadir / Eliminar de favoritos",
	'nbMediaInfoAuditStateEvent' =>
		"Audita el estado de Roms multi-archivo",
	'nbMediaInfoMetaEvent' =>
		"Edita la Meta-información de este juego",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Muestra sólo el primer Disco",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Muestra todas las Roms",
	'optionContextOnlyDiskOne' =>
		"Mustra sólo el primer medio de la Rom",
	'optionContextOnlyDiskOnePlus' =>
		"Muestra el primer medio de la Rom, mas desconocidos",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Con esto agregarás roms a la plataforma seleccionada!",
	'mTopRomOptimizeTooltip' =>
		"Optimiza la base de datos de ECC para la plataforma seleccionada e.j. si mueves/eliminas archivos de tu disco duro",
	'mTopRomRemoveDupsTooltip' =>
		"Con esto eliminarás todas las roms duplicadas de tu base de datos de ECC",
	'mTopRomRemoveRomsTooltip' =>
		"Elimina todas las roms de la plataforma seleccionada de la base de datos de ECC",		
	'mTopDatImportRcTooltip' =>
		"Importa Romcenter Datfiles (*.dat) a ECC. Debes seleccionar la plataforma adecuada! los Datfiles contienen checksum y metainfos asignados al nombre del archivo. ECC extrae esa información y crea un fichero de Meta-Datos ECC automáticamente!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Cambia el emulador asignado de la plataforma seleccionada",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Importa emuControlCenter Datfiles (*.eccDat) a ECC. Si has seleccionado una plataforma, solo las roms de esta plataforma serán importadas! el formato de datos .ecc contiene información como Categorías, Desarrolladores, Estado, Idiomas...",
	'mTopDatImportCtrlMAMETooltip' =>
		"Importa CLR MAME Datfiles (*.dat) a ECC.",
	'mTopDatImportRcTooltip' =>
		"Importa Romcenter Datfiles (*.dat) a ECC. Debes seleccionar la plataforma adecuada! los Datfiles contienen checksum y metainfos asignados al nombre del archivo. ECC extrae esa información y crea un fichero de Meta-Datos ECC automáticamente!",		
	'mTopDatExportEccFullTooltip' =>
		"Esto exportará todos los Meta-Datos de la plataforma seleccionada a un fichero Datfile (texto plano).",
	'mTopDatExportEccUserTooltip' =>
		"Esto exportará solo los datos modificados por ti en la plataforma seleccionada a un fichero Datfile (texto plano).",
	'mTopDatExportEccEsearchTooltip' =>
		"Esto exportará solo los resultados de búsqueda con eSearch en la plataforma seleccionada a un fichero Datfile (texto plano).",
	'mTopDatClearTooltip' =>
		"Borra los datos en los ficheros DAT de la plataforma seleccionada!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Función interna para limpiar y reducir la base de datos.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"Esto creará las carpeta de usuario de ECC como, emus, roms, export... Usa esta opción si has creado una nueva plataforma!",
	'mTopOptionCleanHistoryTooltip' =>
		"Esto limpiará el history.ini. ECC almacena datos en este fichero .ini, como Directorios Seleccionados, Opciones...",
	'mTopOptionBackupUserdataTooltip' =>
		"Esto creará un copia de seguridad de todos tus datos de usuario en un fichero XML, como Notas, Records, Tiempo Jugado...",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"Esto creará accesos directos de ECC en el menú de inicio de Windows.",
	'mTopOptionConfigTooltip' =>
		"Esto abrirá la ventana de configuración de ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Selecciona varios temas GTK para usar con ECC. Puedes crear una bonita combinación si usas los temas ECC adecuados.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"Esto diagnosticará y mostrará información sobre tu instalación de ECC.",
	'mTopDatDFUTooltip' =>
		"Actualiza manualmente tus archivos de datos desde MAME DAT.",
	'mTopAutoIt3GUITooltip' =>
		"Esto abrirá KODA, donde puedes crear y exportar tu propia interfaz gráfica de usuario AutoIt3 para su uso con scripts, si fuese necesario.",
	'mTopImageIPCTooltip' =>
		"Crea packs de imágenes de tus plataformas, así podrás compartirlas fácilmente con nosotros.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"Esto abrirá un explorador SQL con el que podrás ver y editar la base de datos ECC (solo para expertos, asegúrate de crear una copia de seguridad de tus cambios, ya que podría sobrescribirse con una actualización de ECC!)",
	'mTopDeveloperGUITooltip' =>
		"Esto abrirá el editor GLADE GUI donde podrás editar y ajustar la interfáz gráfica de usuario de ECC (solo para expertos, asegúrate de crear una copia de seguridad de tus cambios, ya que podría sobrescribirse con una actualización de ECC!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Esto comprobará si hay actualizaciones disponibles para ECC.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Esto abrirá una ventana donde puedes introducir el código Kameleon para poder utilizar los servicios de ECC. (sólo miembros registrados del foro)",
	// # TOP-HELP
	'mTopHelpWebsiteTooltip' =>
		"Esto abrirá la página web de ECC en tu navegador de Internet.",
	'mTopHelpForumTooltip' =>
		"Esto abrirá el foro de soporte de ECC en tu navegador de Internet.",
	'mTopHelpDocOfflineTooltip' =>
		"Esto abrirá la documentación de ECC a nivel local.",
	'mTopHelpDocOnlineTooltip' =>
		"Esto abrirá el sitio de documentación de ECC en tu navegador de Internet.",
	'mTopHelpAboutTooltip' =>
		"Esto abrirá un pop-up con información sobre ECC",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"Esto abrirá una ventana donde puedes introducir tu código de EmuMovies para poder utilizar ese servicio. (sólo miembros registrados del foro)",
	
	/* 1.14 BUILD 4 */
	'mTopToolNotepadEditorTooltip' =>
		"This will open the notepad editor where you can edit text files and scripts if needed.",
	'mTopToolHexEditorTooltip' =>
		"This will open a HEX editor where you can edit binary files if needed.",
		
	);
?>