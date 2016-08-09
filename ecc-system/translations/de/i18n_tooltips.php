<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	de (deutsch)
 * author:	franz schneider / andreas scheibel
 * date:	2007/12/27
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"Navigation automatisch aktualisieren",
	'opt_hide_nav_null' =>
		"Alle Platformen ohne Spiele ausblenden",
	'opt_hide_dup' =>
		"Doppelte Spiele ausblenden",
	'opt_hide_img' =>
		"Alle Bilder ausblenden",
	'search_field_select' =>
		"Wähle das Suchfeld aus",
	'search_operator' =>
		"Wie soll die Suche verknüpft sein? [ '=' GLEICH, '|' ODER, '+' UND ]",
	'search_rating' =>
		"Alle ROMs nach Bewertung sortieren",
	'optvis_mainlistmode' =>
		"Zwischen Detail- und Listenansicht wechseln",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Klicke hier um deine Bewertung abzugeben.",
	'nbMediaInfoNoteEvent' =>
		"Zeige Notizen für dieses Spiel",
	'nbMediaInfoReviewEvent' =>
		"Zeige das Review für dieses Spiel",
	'nbMediaInfoBookmarkEvent' =>
		"Bookmark hinzufügen / entfernen",
	'nbMediaInfoAuditStateEvent' =>
		"Audit status für Mehrdatei-Spiele",
	'nbMediaInfoMetaEvent' =>
		"Bearbeite die Metainformationen für dieses Rom",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Zeige nur die erste Diskette",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Zeige alle roms",
	'optionContextOnlyDiskOne' =>
		"Zeige nur das erste Medium",
	'optionContextOnlyDiskOnePlus' =>
		"Zeige das erste Medium + unbekannte",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Es werden roms für die ausgewählte Plattform hinzugefügt!",
	'mTopRomOptimizeTooltip' =>
		"Optimiert die ECC-Datenbank für die ausgewählte Plattform z.b. wenn du Dateien auf deiner Festplatte verschiebst/entfernst",
	'mTopRomRemoveDupsTooltip' =>
		"Dies wird alle doppelten roms von deiner ECC Datenbank entfernen",
	'mTopRomRemoveRomsTooltip' =>
		"Entferne alle roms der gewählten Plattform von der ECC-Datenbank",		
	'mTopDatImportRcTooltip' =>
		"Du kannst Romcenter Datfiles (*.dat) in eCC importieren. Du musst die richtige Plattform auswählen! RC-Datfiles enthalten den Dateinamen, Prüfsumme und Metainfos zum jeweiligen rom. emuControlCenter wird diese Informationen filtern und daraus automatisch die ecc-metadaten erstellen!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Ändere den Emulator der ausgewählen Plattform",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Importiere emuControlCenter Datfiles (*.eccDat) in ecc. Wenn du eine Plattform ausgewählt hast, werden nur roms für diese Plattform importiert werden! Das ecc-datfile-format hat erweiterte Metainformationen wie z.b. Kategorien, Entwickler, Land, Sprache usw.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Importiere CLR MAME DATfiles (*.dat) in ecc.",
	'mTopDatImportRcTooltip' =>
		"Importiere Romcenter DATfiles (*.dat) in ecc. Du musst die richtige Plattform auswählen! RC-Datfiles enthalten den Dateinamen, Prüfsumme und Metainfos zum jeweiligen rom. emuControlCenter wird diese Informationen filtern und daraus automatisch die ecc-metadaten erstellen!",		
	'mTopDatExportEccFullTooltip' =>
		"Dies wird alle Metadaten der ausgewählten Plattform in ein Datfile exportieren (plaintext).",
	'mTopDatExportEccUserTooltip' =>
		"Dies wird nur die von dir geänderten Daten einer ausgewählten Plattform in ein Datfile exportieren (plaintext).",
	'mTopDatExportEccEsearchTooltip' =>
		"Dies wird nur das Ergebniss der eSearch-Funktion der ausgewählten Plattform in ein Datfile exportieren (plaintext).",
	'mTopDatClearTooltip' =>
		"Lösche Daten vom Datfile der ausgewählten Plattform!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Interne Funktion zur Bereinigung und Verkleinerung der Datenbank.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"Dies erstellt alle ecc user Ordner wie emus, roms, exports usw. Benutze diese Option, wenn du eine neue Plattform erstellt hast!",
	'mTopOptionCleanHistoryTooltip' =>
		"Dies reinigt die ecc history.ini. ECC speichert Daten wie Verzeichnisse, ausgewählte Optionen usw. in dieser Datei.",
	'mTopOptionBackupUserdataTooltip' =>
		"Dies wird alle deine Benutzerdaten wie z.b. Notizen, Highscore, wie oft gespielt, in einer XML-Datei speichern",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"Dies wird ECC Verknüpfungen im Windows Startmenü erstellen",
	'mTopOptionConfigTooltip' =>
		"Dies öffnet das Konfigurationsfenster von ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Wähle verschiedene GTK themes die du mit ECC verwenden kannst. Du kannst schöne Kombinationen mit den richtigen ECC themes machen.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"Dies wird deine ECC-Installation diagnostizieren und dir Informationen über deine ECC-Installation mitteilen.",
	'mTopDatDFUTooltip' =>
		"Manuelles Aktualisieren deiner DATfiles vom MAME DAT.",
	'mTopAutoIt3GUITooltip' =>
		"Dies öffnet KODA. Damit erstellst oder exportierst du deine eigene AutoIt3 GUI um diese, wenn nötig, mit scripts zu verwenden.",
	'mTopImageIPCTooltip' =>
		"Erstelle Imagepacks deiner Plattform um diese ganz einfach mit uns teilen zu können.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"Dies öffnet einen SQL-Browser, den du benutzen kannst, um die ECC-Datenbank einzusehen und zu bearbeiten (nur für Fortgeschrittene. Erstelle eine Sicherungskopie deiner Änderungen, da diese bei einem ECC-Update überschrieben werden könnten!)",
	'mTopDeveloperGUITooltip' =>
		"Dies öffnet den GLADE GUI Editor. Hier kannst du die ECC GUI bearbeiten und einstellen. (nur für Fortgeschrittene. Erstelle eine Sicherungskopie deiner Änderungen, da diese bei einem ECC-Update überschrieben werden könnten!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Dies prüft, ob Updates für ECC vorhanden sind.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Es öffnet sich ein Fenster, wo du den Kameleon-Code eingeben kannst, um ECC-Dienste zu nutzen. (für registrierte Forum-Mitglieder)",
	// # TOP-HELP
	'mTopHelpWebsiteTooltip' =>
		"Dies öffnet die ECC-Website in deinem Internetbrowser.",
	'mTopHelpForumTooltip' =>
		"Dies öffnet das ECC Support-Forum in deinem Internetbrowser.",
	'mTopHelpDocOfflineTooltip' =>
		"Dies öffnet die ECC-Dokumentation offline/lokal.",
	'mTopHelpDocOnlineTooltip' =>
		"Dies öffnet die ECC-Dokumentationseite in deinem Internetbrowser.",
	'mTopHelpAboutTooltip' =>
		"Es öffnet sich das pop-up ...über ECC.",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"This will open a window where you can enter your EmuMovies account data to use their services. (registered forum members)",
);
?>