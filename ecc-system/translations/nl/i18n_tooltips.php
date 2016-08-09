<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	nl (dutch)
 * author:	Sebastiaan Ebeltjes
 * date:	2006/06/26
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"Toogle zoek-autoupdate voor navigatie",
	'opt_hide_nav_null' =>
		"Laat zien/verberg platvormen zonder ROMS",
	'opt_hide_dup' =>
		"Laat zien/verberg dubbele ROMS",
	'opt_hide_img' =>
		"Laat zien/verberg plaatjes",
	'search_field_select' =>
		"Waar wil je in zoeken?",
	'search_operator' =>
		"Selecteer een zoek functie. ([ = GELIJK] [ | OF ] [ + EN])",
	'search_rating' =>
		"Only show roms RATED equal or lower selection",
	'optvis_mainlistmode' =>
		"Wissel tussen detail en simpele lijst",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Klik hier om deze ROM te beoordelenm",
	'nbMediaInfoNoteEvent' =>
		"Laat notities zien voor deze ROMm",
	'nbMediaInfoReviewEvent' =>
		"Laat samenvatting zien voor dit spel",
	'nbMediaInfoBookmarkEvent' =>
		"Toevoegen / Verwijderen favoriet",
	'nbMediaInfoAuditStateEvent' =>
		"Beoordelings-status voor ROMS met meerdere bestanden",
	'nbMediaInfoMetaEvent' =>
		"Bewerk de META-informatie voor dit spel",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Laat alleen de eerste diskette zien",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Laat alle ROMS zien",
	'optionContextOnlyDiskOne' =>
		"Laat alleen media zien van de eerste ROM",
	'optionContextOnlyDiskOnePlus' =>
		"Laat eerste ROM media zien plus alle onbekenden",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Hiermee voeg je ROMS toe voor het geselecteerde platform!",
	'mTopRomOptimizeTooltip' =>
		"Optimalizeer de ECC database voor het geselecteerde platform (bijvoorbeeld als je bestanden (ROMS) hebt verplaatst of verwijderd.",
	'mTopRomRemoveDupsTooltip' =>
		"Deze optie zal alle dubbele ROMS uit je database verwijderen.",
	'mTopRomRemoveRomsTooltip' =>
		"Deze optie zal ALLE ROMS verwijderen uit de database",		
	'mTopDatImportRcTooltip' =>
		"Je kan RomCenter DAT bestanden importeren (*.dat) als je de juiste platform selecteerd! RomCenter DAT bestanden bevatten een bestandsnaam, checksum en meta-informatie verbonden aan de bestandsnaam. emuControlCenter zal deze informatie gebruiken en automatisch importeren als ecc-metadata!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Wijzig de emulator welke is gekoppeld aan dit platform.",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Importeer emuControlCenter DAT bestanden (*.eccDat) in ecc. Als je een platform geselecteerd hebt wordt alleen data van dit platform geimporteerd! Het ECC DAT bestands formaat heeft uitgebreide meta-informatie zoals categorieen, ontwikkelaars, staat, talen, etc.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Importeer CLR MAME DAT bestanden (*.dat) in ecc.",
	'mTopDatImportRcTooltip' =>
		"Importeer RomCenter DAT bestanden (*.dat) in ECC. Je moet het juiste platform selecteren! RomCenter DAT bestanden bevatten een bestandsnaam, checksum en meta-informatie verbonden aan de bestandsnaam. emuControlCenter zal deze informatie gebruiken en automatisch importeren als ecc-metadata!",
	'mTopDatExportEccFullTooltip' =>
		"Dit zal al je meta-data exporteren van het geselecteerde platform naar een DAT bestand (puur tekst).",
	'mTopDatExportEccUserTooltip' =>
		"Dit zal al je data exporteren welke is veranderd door jou van het geselecteerde platform naar een DAT bestand (puur tekst).",
	'mTopDatExportEccEsearchTooltip' =>
		"Dit zal alleen de data exporteren van de zoekresultaten (eSearch meta-gegevens) van het geselecteerde platform naar een DAT bestand (puur tekst).",
	'mTopDatClearTooltip' =>
		"Verwijder data van DATfiles van het geselecteerde platform!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Interne functie om de database op te schonen en te laten slinken.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"Dit zal ECC gebruikersfolders aanmaken, zoals emus, roms, exports, etc. Gebruik deze optie als je een nieuw platform gecreeerd hebt!",
	'mTopOptionCleanHistoryTooltip' =>
		"Deze optie zal de 'history.ini' opschonen. ECC slaat gegevens op in dit bestand zoals geselecteerde folders, opties, etc.",
	'mTopOptionBackupUserdataTooltip' =>
		"Dit zal een backup maken van alle gebruikersdata zoals notities, hoogste score en tijd gespeeld naar een XML bestand.",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"Dit zal startmenu snelkoppelingen maken.",
	'mTopOptionConfigTooltip' =>
		"Dit zal het configuratiescherm van ECC openen.",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Selecteer een van de GTK thema's, je kan een leuke combinatie maken als je gebruik maakt van de juiste ECC thema.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"Dit zal een diagnose rapport maken van je ECC installatie.",
	'mTopDatDFUTooltip' =>
		"Handmatig je DAT bestanden bijwerken met MAME(DAT).",
	'mTopAutoIt3GUITooltip' =>
		"Dit zal het programma KODA openen waarmee je Autoit3 GUI kunt maken voor je scripts als het nodig is.",
	'mTopImageIPCTooltip' =>
		"Creeer plaatjespakketten van platformen, zodat je het gemakkelijk met ons kunt delen.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"Dit zal een SQL browser openen waarmee je de ECC database kun bekijken en bewerken (alleen voor profs!, wees er altijd zeker van dat je een backup van je eigen veranderingen maakt!, want deze kunnen overschreven worden door veranderingen met ECC update!)",
	'mTopDeveloperGUITooltip' =>
		"Dit zal de GLADE GUI bewerker openen, waarmee je de GUI kan bewerken (alleen voor profs!, wees er altijd zeker van dat je een backup van je eigen veranderingen maakt!, want deze kunnen overschreven worden door veranderingen met ECC update!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Hiermee word gekeken of er aanvullende ECC updates aanwezig zijn.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Dit zal een venster openen waar je de Kameleon code kunt invoeren om gebruik te maken van diverse ECC diensten. (voor geregistreerde forum leden)",
	// # TOP-HELP
	'mTopHelpWebsiteTooltip' =>
		"Dit zal de ECC website openen in je internetbrowser.",
	'mTopHelpForumTooltip' =>
		"Dit zal de ECC hulp forum openen in je internetbrowser.",
	'mTopHelpDocOfflineTooltip' =>
		"Dit zal de lokale ECC documentatie openen.",
	'mTopHelpDocOnlineTooltip' =>
		"Dit zal de ECC documentatie openen in je internetbrowser.",
	'mTopHelpAboutTooltip' =>
		"Dit zal de 'over ECC' box laten zien.",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"Dit zal een venster openen waar je je EmuMovies account gegevens kunt invoeren om gebruik te maken van hun diensten. (voor geregistreerde forum leden)",
	
	/* 1.14 BUILD 4 */
	'mTopToolNotepadEditorTooltip' =>
		"Dit zal een notepad editor openen waarmee je tekatbestanden kan bewerken indien nodig.",
	'mTopToolHexEditorTooltip' =>
		"Dit zal een HEX editor openen waarmee je binaire bestanden kan bewerken indien nodig.",

	);
?>