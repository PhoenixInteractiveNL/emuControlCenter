<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	de (deutsch)
 * author:	franz schneider / andreas scheibel
 * date:	2007/05/13
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"Verzeichnis mit %s Spielen auswählen",
	'rom_add_parse_title%s' =>
		"Neue Spiele für %s hinzufügen",
	'rom_add_parse_msg%s%s' =>
		"Sollen alle Spiele für die Platform\n\n%s\n\naus dem Verzeichnis:\n\n%s\n\neingelesen werden?",
	'rom_add_parse_done_title' =>
		"Einlesen beendet!",
	'rom_add_parse_done_msg%s' =>
		"Einlesen der neuen \n\n%s\n\nSpiele ist beendet!",
	'rom_remove_title%s' =>
		"%s Datenbankeinträge löschen",
	'rom_remove_msg%s' =>
		"Sollen wirklich alle \n\"%s\"Spiele aus der Datenbank gelöscht werden?",
	'rom_remove_done_title' =>
		"Daten gelöscht",
	'rom_remove_done_msg%s' =>
		"Alle %s Spiele wurden aus der Datenbank entfernt!",
	'rom_remove_single_title' =>
		"Dieses Spiel aus der Datenbank löschen?",
	'rom_remove_single_msg%s' =>
		"Soll das ausgewählte Spiel\n\n%s\n\nwirklich aus der Datenbank gelöscht werden?",
	'rom_remove_single_dupfound_title' =>
		"Doppelte Spiele gefunden!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"Es wurden %d identische Versionen dieses Spiels\n\n%s\n\ngefunden\n\nSollen auch diese Duplikate gelöscht werde?",
	'rom_optimize_title' =>
		"Spiele Datenbank optimieren",
	'rom_optimize_msg' =>
		"Die Optimierung der Datenbank überprüft, ob die gespeicherten Datein und Verzeichnisse noch existieren und löscht die Datenbankeinträge, wenn nötig!\n\nOptimierung jetzt durchführen?",
	'rom_optimize_done_title' =>
		"Optimierung abgeschlossen!",
	'rom_optimize_done_msg%s' =>
		"Alle \n\n%sSpiele\n\n wurden überprüft und optimiert!",
	'rom_dup_remove_title' =>
		"Doppelte Spiel aus der Datenbank löschen",
	'rom_dup_remove_msg%s' =>
		"Alle doppelten Versionen von \n\n%s\n\naus der Datenbank löschen?",
	'rom_dup_remove_done_title' =>
		"Die doppelten Versionen wurden erfolgreich gelöscht!",
	'rom_dup_remove_done_msg%s' =>
		"Alle doppelten Versionen von\n\n%s\n\nwurden erfolgreich aus der Datenbank gelöscht",
	'rom_reorg_nocat_title' =>
		"Keine Kategorien vorhanden!",
	'rom_reorg_nocat_msg%s' =>
		"Um dieses Feature zu nutzen, müssen müssen deine\n\n%s\n\nSpiele kategorisiert sein.\n\nBearbeite die Metainformationen deiner Spiele, um Kategorien zuzuweisen",
	'rom_reorg_title' =>
		"Spiele auf der Festplatte nach Kategorien organisieren?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------ACHTUNG: Diese Option kopiert deine Spiele auf der Festplatte!!!\n ------------------------------------------------------------------------------------------UM EINE REIBUNGSLOSE DURCHFÜHRUNG ZU GEWÄHREN BITTE ZUERST DUPLIKATE ENTFERNEN! \nDer Ausgewählte Modus ist: #%s#\n------------------------------------------------------------------------------------------\n\nSollen die ROMS nach ihrer Katerorie sortiert werden, für\n\n%s\n\nauf dem bestehenden Dateisystem? ECC ordnet die Dateien neu um, gespeichert werden sie anschließend unter\n\n%s/roms/organized/\n\nBitte kontrollieren sie zuvor die noch verfügbare Speicherkapazität, dammit es zu keinen Problem kommen kann.\n\nDIE AUSFÜHRUNG GESCHIEHT AUF EIGENE GEFAHR!!! :-)",
	'rom_reorg_done_title' =>
		"Umstrukturierung erfolgreich beendet!",
	'rom_reorg_done__msg%s' =>
		"Umstruktuierung erfolgreich beendet. Bitte überprüfe das Ergebnis im Verzeichnis\n\n%s",
	'db_optimize_title' =>
		"Optimiere Datenbank System",
	'db_optimize_msg' =>
		"Soll die Datenbank optimiert werden?\nWenn häufiger in dem Index von ECC Veränderungen statt finden, entstehen Lücken!\nDiese werden mit Hilfe der Optimierung geschlossen um den Speicherplatz zu minimieren.\n\nWarscheinlich wird die Anwendung wärend der Ausführung nicht reagieren, dieses Verhalten ist normal! :-)",
	'db_optimize_done_title' =>
		"Datenbank optimiert!",
	'db_optimize_done_msg' =>
		"Die Datenbank wurde erfolgreich optimiert!",
	'export_esearch_error_title' =>
		"Keine eSearch Optionen gewählt!",
	'export_esearch_error_msg' =>
		"Du musst zuerst eSearch-Optionen auswählen um diese Funktion zu nutzen. Es wird dann nur das Ergebnis deiner eSearch Einstellung exportiert!",
	'dat_export_filechooser_title%s' =>
		"Wähle das Verzeichnis zur Speicherung der %s Datfiles!",
	'dat_export_title%s' =>
		"Exportiere %s datfile",
	'dat_export_msg%s%s%s' =>
		"%s Datfile für die Platform\n\n%s\n\n in das Verzeichniss\n\n%s\n\nerstellen?",
	'dat_export_esearch_msg_add' =>
		"\n\neSearch Einstellungen werden berücksichtigt!",
	'dat_export_done_title' =>
		"Export beendet!",
	'dat_export_done_msg%s%s%s' =>
		"Der Export des %s Datfiles für\n\n%s\n\nin das Verzeichniss:\n\n%s\n\nwurde fertigestellt!",
	'dat_import_filechooser_title%s' =>
		"Import: Wähle ein %s Datfile!",
	'rom_import_backup_title' =>
		"Sicherung erstellen?",
	'rom_import_backup_msg%s%s' =>
		"Soll for dem Import ein Sicherung erstellt werden, bevor die neuen META-Informationen integriert werden?\n\n%s (%s)",
	'rom_import_title' =>
		"Datfile importieren?",
	'rom_import_msg%s%s%s' =>
		"Sollen die Metainformation aus der Datei\n\n%s für die Platform\n\n%s (%s)\n\nübernommen werden?",
	'rom_import_done_title' =>
		"Importierung erfolgreich fertiggestellt!",
	'rom_import_done_msg%s' =>
		"Der Import der Datei: \n\n%s\n\nwurde erfolgreich beendet!",
	'dat_clear_title%s' =>
		"Alle %s Metainformationen löschen?",
	'dat_clear_msg%s%s' =>
		"Möchtest Du wirklich alle Metainformationen der Platform \n\n%s (%s)löschen?\n\n(Metainformationen sind z.B. Titel, Kategorien, Sprachen usw.)\n\nVor dem löschen kannst du automatisch eine Sicherung deiner Daten erstellen lassen. (Diese Sicherung wird automatisch in deinem ecc-user Verzeichnis gespeichert!)",
	'dat_clear_backup_title%s' =>
		"Sichern %s",
	'dat_clear_backup_msg%s%s' =>
		"Soll eine Sicherungsdatei Metainformationen angelegt werden?\n\n%s (%s)?",
	'dat_clear_done_title%s' =>
		" Metainformationen entfernt!",
	'dat_clear_done_msg%s%s' =>
		"Alle Metainformationen der Platform\n\n%s (%s)\n\wurden aus der Datenbank entfernt!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n Es wurde eine automatische Sicherungsdatei im ecc-user Verzeichnis der Platform %s erstellt",
	'emu_miss_title' =>
		"Fehler - Kein Emulator zugewiesen!",
	'emu_miss_notfound_msg%s' =>
		"Der gewählte Emulator wurde nicht gefunden. Grün signalisiert gültige, Rot ungültige Emulator Pfadangaben.",
	'emu_miss_notset_msg' =>
		"Es wurde kein gültiger Emulator zugewiesen!",
	'emu_miss_dir_msg%s' =>
		"Der gewählte Pfad ist ein Verzeichnis!!!!",
	'rom_miss_title' =>
		"Fehler - Spiel nicht gefunden!",
	'rom_miss_msg' =>
		"Das gewählte Spiel wurde nicht gefunden!\n\nEine Optimierung der Datenbank könnte das Problem lösen.'\nMöglicherweise müssen die Platformspezifisch Einstellung in der Emulator Konfiguration angepasst werden (z.B. 'escape' oder '8.3') werden!",
	'img_overwrite_title' =>
		"Bild überschreiben?",
	'img_overwrite_msg%s%s' =>
		"Das Bild\n\n%s\n\nexistiert bereits\n\nSoll das Bild durch das neue ersetzt werden\n\n%s?",
	'img_remove_title' =>
		"Bild löschen?",
	'img_remove_msg%s' =>
		"Soll das Bild %s wirklich gelöscht werden?",
	'img_remove_error_title' =>
		"Fehler - Konnte Bilddatei nicht entfernen!",
	'img_remove_error_msg%s' =>
		"Bilddatei: %s konnte nicht gelöscht werden!",
	'conf_platform_update_title' =>
		"Platform Konfiguration aktualisiern?",
	'conf_platform_update_msg%s' =>
		"Soll die Platform Konfiguration wirklich aktualisiern werden?%s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Bitte einen Emulator für die Dateiendung: '%s' zuweisen",
	'conf_userfolder_notset_title' =>
		"Fehler: Konnte das ecc-user Verzeichnis nicht finden!!!",
	'conf_userfolder_notset_msg%s' =>
		"Der Basispfad wurde in der ecc_general.ini geändert.Der angegebene Ordner ist im Moment noch nicht erstellt.\n\nSoll dieser jetzt automatisch erstellt werden?\n\n%s\n\nSollte bereits ein anderer Pfad gewählt wurden sein, klicken sie bitte auf Nein und gehen sie auf \n'options'->'configuration'\num die Einstellungen zu verändern!",
	'conf_userfolder_error_readonly_title' =>
		"Fehler: Ordner konnte nicht erstellt werden!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"Die Ordner %s konnten NICHT erstellt werden, warscheinlich wurde ein Schreibgeschützter Datenträger gewählt.\n\nWählen sie einen anderen Speicherplatz bzw. ein anderes Speichermedium.\nUnter 'options'->'configuration' lassen sich die Einstellungen verändern!",
	'conf_userfolder_created_title' =>
		"User Verzeichnis erstellt!",
	'conf_userfolder_created_msg%s%s' =>
		"Die Unterverzeichnisse\n\n%s\n\nwurden im ecc-user Verzeichnis %s erstellt.",
	'conf_ecc_save_title' =>
		"Aktuallisiere emuControlCenter GLOBAL-INI?",
	'conf_ecc_save_msg' =>
		"Änderungen speichern?\n\nHierbei werden automatisch alle im ecc-user Verzeichnis benötigen Unterverzeichnisse angelegt!",
	'conf_ecc_userfolder_filechooser_title' =>
		"Es muss ein ecc-user Verzeichnis angegeben werden",
	'fav_remove_all_title' =>
		"Alle Bookmarks entfernen?",
	'fav_remove_all_msg' =>
		"SOLLEN WIRKLICH ALLE BOOKMARKS GELÖSCHT WERDEN?",
	'maint_empty_history_title' =>
		'Zurücksetzen der history.ini?',
	'maint_empty_history_msg' =>
		'In der history.ini werden alle Usereinstellungen gespeichert. Soll dieses Datei wirklich geleert werden?',
	'sys_dialog_info_miss_title' =>
		"?? TITEL FEHLT ??",
	'sys_dialog_info_miss_msg' =>
		"?? NACHRICHT FEHLT ??",
	'sys_filechooser_miss_title' =>
		"?? TITEL FEHLT??",
	'status_dialog_close' =>
		"\n\nSoll die Detailübersicht geschlossen werden?",
	'status_process_running_title' =>
		"Prozess läuft bereits!",
	'status_process_running_msg' =>
		"Ein anderer Prozess läuft bereits.\nEs kann nur immer ein Prozess zeitgleich laufen, um Fehler zuvermeiden. Bitte warten, bis der derzeitige Prozess beendet ist!",
	'meta_rating_add_error_msg' =>
		"Es können nur Spiele mit Metainformationen bewertet werden.\n\nMetainformationen können unter 'Metainformationen bearbeiten' eingegeben werden!",
	'maint_unset_ratings_title' =>
		"Bewertungen einer Platform löschen?",
	'maint_unset_ratings_msg' =>
		"Sollen alle Bewertungen der gewählten Platform gelöscht werden?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistiken:\n\n%s hinzugefügt\n%s bereits eingetragen\n%s Fehlers\n\n%s Datasets bearbeitet!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatenbank:\n\nUm die ECC-Community zu unterstützen, sollte man seine eigegebenen Informationen (Titel, Kategorie...) zu den ROMS veröffentlichen.\n\nDie ROMDB ist eine wachsende Datenbank mit dem Ziel, Informationen zu jedem ROM zu erfassen.\n\nÄhnlich wie eine CD-Datenbank.\n\nFür das Hochladen wird eine Internet-Verbindung  benötigt!!!\n\nNach 10 erfolgreichen Uploads wird eine erneute Bestätigung benötigt.",
	'eccdb_error' =>
		"eccdb - Fehler:\n\nEs konnte keine Verbindung zum Internet herstellen. Bitte überprüfe, ob Du Online bist oder Deine Firewall die Verbindung untersagt!",
	'eccdb_no_data' =>
		"eccdb - Keine Daten gefunden:\n\nDu musst die Metainformationen deiner Spiele bearbeiten um sie hochladen zu können.",

	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Diese Option durchsucht die Datenbank nach doppelten Spielen. Diese werden dann im Statusbereich mit Pfadangaben ausgebenen. Wenn du das Logging aktiviert hast, wirst du eine Textdate in deinem Log-Verzeichnis finden.!",

	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Alle Bilder löschen?",
	'img_remove_all_msg%s' =>
		"Alle Bilder für das gewählte Spiel\n\n%s\n\nlöschen?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"Bestätige",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"Datei parsen?",
	'parse_big_file_found_msg%s%s' =>
		"GROßE DATEI GEFUNDEN!!!\n\nDas Spiel\n\nName: %s\nSize: %s\n\nist sehr groß. Das parsen kann einige Zeit in Anspruch nehmen, in der das emuControlCenter nicht reagiert.\n\nSoll das Spiel trotzdem geparst werden?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Bookmark gespeichert",
	'bookmark_added_msg' =>
		"Dieser Bookmark wurde hinzugefügt!",
	'bookmark_removed_single_title' =>
		"Bookmark entfernt",
	'bookmark_removed_single_msg' =>
		"Der Bookmark wurde entfernt!",
	'bookmark_removed_all_title' =>
		"Bookmarks entfernt!",
	'bookmark_removed_all_msg' =>
		"Alle Bookmarks wurden entfernt!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Datfile aus dem Internet importieren",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Möchtest du die Metainformationen der Platform\n\n%s\n\nwirklich mit den Daten aus der emuControlCenter romDB aktualisieren?\n\nDu musst mit dem Internet verbunden sein, um dieses Feature zu nutzen!",

	'eccdb_webservice_get_datfile_error_title' =>
		"Keine Daten gefunden!",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Du musst mit dem Internet verbunden sein! Bitte stelle eine Verbindung her und versuche es noch einmal.",

	'romparser_fileext_problem_title%s' =>
		"Problem mit Dateiendung %s gefunden",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"Die Dateiendung %s ist mehr als einer Platform zugewiesen!\n\n%s\nBist du wirklich sicher, das nur %s Spiele im gewählten Verzeichnis %s liegen?\n\n<b>OK</b>: Suche in diesem Verzeichnis nach %s Datein!\n\n<b>ABBRECHEN</b>: Überspringe die %s Dateien.\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Suche nach doppleten Spielen",
	'rom_dup_remove_done_title_preview' =>
		"Suche abgeschlossen",
	'rom_dup_remove_done_msg_preview' =>
		"Bitte beachte die Status-Area für Details!",
	'metaRemoveSingleTitle' =>
		"Entferne Metainformationen",
	'metaRemoveSingleMsg' =>
		"Möchtest du die Metainformationen für dieses Spiel wirklich entfernen?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Wähle ein CtrlMAME Dat-File!\n",
	'importDatCMConfirmTitle' =>
		"Importiere ctrlMAME dat",
	'importDatCMConfirmMsg%s%s%s' =>
		"Möchtest du wirklich neue Metainformationen für\n\n%s (%s)\n\naus dem Datfile\n\n%s\n\übernehmen?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Aktualisiere Romset-Infos",
	'romAuditReparseMsg%s' =>
		"Hiermit aktualisierst du die in der Datenbank gespeicherten Informationen über den Zustand des Romset (Fehlende files, false namen usw.)\n\nDaten jetzt updaten?",
	'romAuditInfoNotPossibelTitle' =>
		"Keine Romset-Infos verfügbar",
	'romAuditInfoNotPossibelMsg' =>
		"Romset-Infos stehen für Multifile-Platformen zur Verfügung (Arcade)!",

	'romReparseAllTitle' =>
		"Aktualisiere alle Rom-Verzeichnisse",
	'romReparseAllMsg%s' =>
		"Nach neuen Roms in den vorhandenen Verzeichnissen suchen? Suche für:\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Setze diese Dateierweiterungen zurück",
	'parserUnsetExtMsg%s' =>
		"Da Du '#All found' ausgewählt hast müssen einige Dateierweiterungen ausgeschlossen werden, um eine falsche Zuweisung in der Datenbank auszuschließen.\n\nemuControlCenter wird nicht nach diesen Erweiterungen suchen: %s\n\nBitte wähle die Gewünschte Platform, wenn du diese trotzdem hinzufügen möchtest!\n\n",

	'stateLabelDatExport%s%s' =>
		"Exportiere %s Datfile für %s",
	'stateLabelDatImport%s' =>
		"Importiere Datfile für%s",

	'stateLabelOptimizeDB' =>
		"Optimiere Datenbank",
	'stateLabelVacuumDB' =>
		"Vacuum Datenbank",
	'stateLabelRemoveDupRoms' =>
		"Entferne doppelte Spiele",
	'stateLabelRomDBAdd' =>
		"Füge Metainfos zur romDB hinzu",
	'stateLabelParseRomsFor%s' =>
		"Parse Spiele für %s",
	'stateLabelConvertOldImages' =>
		"Konvertiere nun die Bilder...",

	'processCancelConfirmTitle' =>
		"Vorgang abbrechen?",
	'processCancelConfirmMsg' =>
		"Soll der gerade laufende Vorgang wirklich beendet werden?",
	'processDoneTitle' =>
		"Vorgang abgeschlossen!",
	'processDoneMsg' =>
		"Der Vorgang wurde abgeschlossen!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"Die XML-Datei mit deinem Benutzerdaten wurden in das Verzeichnis ecc-user/#_GLOBAL/ gesichert.\n\n%s\n\nSoll die Datei nun in deinem XML-Browser angezeigt werden?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Soll diese Aufgabe durchgeführt werden?",
	'executePostShutdownTaskMessage%s' =>
		"\nAufgabe: <b>%s</b>\n\nSoll diese lange laufende Aufgabe nun durchgeführt werden?",
	'postShutdownTaskTitle' =>
		"Führe gewählte Aufgabe aus.",
	'postShutdownTaskMessage' =>
		"Du hast eine Aufgabe gewählt, die nur durchführbar ist, wenn das emuControlCenter geschlossen ist..\n\nNach der abarbeitung wird das <b>emuControlCenter automatisch neugestartet!</b>\n\nBis dahin können einige Sekunden, Minuten und manchmal auch Stunden vergehen! Diese Popup ist dabei oft eingefroren. Keine Angst! :-)\n\n<b>Bitte warten!</b>",


);
?>