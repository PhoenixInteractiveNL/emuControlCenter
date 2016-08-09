<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	de (german)
 * author:	franz schneider / andreas scheibel
 * date:	2007/05/13
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"Verzeichnis mit %s Spielen ausw�hlen",
	'rom_add_parse_title%s' =>
		"Neue Spiele f�r %s hinzuf�gen",
	'rom_add_parse_msg%s%s' =>
		"Sollen alle Spiele f�r die Platform\n\n%s\n\naus dem Verzeichnis:\n\n%s\n\neingelesen werden?",
	'rom_add_parse_done_title' =>
		"Einlesen beendet!",
	'rom_add_parse_done_msg%s' =>
		"Einlesen der neuen \n\n%s\n\nSpiele ist beendet!",
	'rom_remove_title%s' =>
		"%s Datenbankeintr�ge l�schen",
	'rom_remove_msg%s' =>
		"Sollen wirklich alle \n\"%s\"Spiele aus der Datenbank gel�scht werden?",
	'rom_remove_done_title' =>
		"Daten gel�scht",
	'rom_remove_done_msg%s' =>
		"Alle %s Spiele wurden aus der Datenbank entfernt!",
	'rom_remove_single_title' =>
		"Dieses Spiel aus der Datenbank l�schen?",
	'rom_remove_single_msg%s' =>
		"Soll das ausgew�hlte Spiel\n\n%s\n\nwirklich aus der Datenbank gel�scht werden?",
	'rom_remove_single_dupfound_title' =>
		"Doppelte Spiele gefunden!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"Es wurden %d identische Versionen dieses Spiels\n\n%s\n\ngefunden\n\nSollen auch diese Duplikate gel�scht werde?",
	'rom_optimize_title' =>
		"Datenbank optimieren",
	'rom_optimize_msg' =>
		"Die Optimierung der Datenbank �berpr�ft, ob die gespeicherten Datein und Verzeichnisse noch existieren und l�scht die Datenbankeintr�ge, wenn n�tig!\n\nOptimierung jetzt durchf�hren?",
	'rom_optimize_done_title' =>
		"Optimierung abgeschlossen!",
	'rom_optimize_done_msg%s' =>
		"Alle \n\n%sSpiele\n\n wurden �berpr�ft und optimiert!",
	'rom_dup_remove_title' =>
		"Doppelte Spiel aus der Datenbank l�schen",
	'rom_dup_remove_msg%s' =>
		"Alle doppelten Versionen von \n\n%s\n\naus der Datenbank l�schen?",
	'rom_dup_remove_done_title' =>
		"Die doppelten Versionen wurden erfolgreich gel�scht!",
	'rom_dup_remove_done_msg%s' =>
		"Alle doppelten Versionen von\n\n%s\n\nwurden erfolgreich aus der Datenbank gel�scht",
	'rom_reorg_nocat_title' =>
		"Keine Kategorien vorhanden!",
	'rom_reorg_nocat_msg%s' =>
		"Um dieses Feature zu nutzen, m�ssen m�ssen deine\n\n%s\n\nSpiele kategorisiert sein.\n\nBearbeite die Metainformationen deiner Spiele, um Kategorien zuzuweisen",
	'rom_reorg_title' =>
		"Spiele auf der Festplatte nach Kategorien organisieren?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------ACHTUNG: Diese Option kopiert deine Spiele auf der Festplatte!!!\n ------------------------------------------------------------------------------------------UM EINE REIBUNGSLOSE DURCHF�HRUNG ZU GEW�HREN BITTE ZUERST DUPLIKATE ENTFERNEN! \nDer Ausgew�hlte Modus ist: #%s#\n------------------------------------------------------------------------------------------\n\nSollen die ROMS nach ihrer Katerorie sortiert werden, f�r\n\n%s\n\nauf dem bestehenden Dateisystem? ECC ordnet die Dateien neu um, gespeichert werden sie anschlie�end unter\n\n%s/roms/organized/\n\nBitte kontrollieren sie zuvor die noch verf�gbare Speicherkapazit�t, dammit es zu keinen Problem kommen kann.\n\nDIE AUSF�HRUNG GESCHIEHT AUF EIGENE GEFAHR!!! :-)",
	'rom_reorg_done_title' =>
		"Umstrukturierung erfolgreich beendet!",
	'rom_reorg_done__msg%s' =>
		"Umstruktuierung erfolgreich beendet. Bitte �berpr�fe das Ergebnis im Verzeichnis\n\n%s",
	'db_optimize_title' =>
		"Optimiere Datenbank",
	'db_optimize_msg' =>
		"Soll die Datenbank optimiert werden?\nWenn h�ufiger in dem Index von ECC Ver�nderungen statt finden, entstehen L�cken!\nDiese werden mit Hilfe der Optimierung geschlossen um den Speicherplatz zu minimieren.\n\nWarscheinlich wird die Anwendung w�rend der Ausf�hrung nicht reagieren, dieses Verhalten ist normal! :-)",
	'db_optimize_done_title' =>
		"Datenbank optimiert!",
	'db_optimize_done_msg' =>
		"Die Datenbank wurde erfolgreich optimiert!",
	'export_esearch_error_title' =>
		"Keine eSearch Optionen gew�hlt!",
	'export_esearch_error_msg' =>
		"Du musst zuerst eSearch-Optionen ausw�hlen um diese Funktion zu nutzen. Es wird dann nur das Ergebnis deiner eSearch Einstellung exportiert!",
	'dat_export_filechooser_title%s' =>
		"W�hle das Verzeichnis zur Speicherung der %s Datfiles!",	
	'dat_export_title%s' =>
		"Export %s datfile",
	'dat_export_msg%s%s%s' =>
		"%s Datfile f�r die Platform\n\n%s\n\n in das Verzeichniss\n\n%s\n\nerstellen?",
	'dat_export_esearch_msg_add' =>
		"\n\neSearch Einstellungen werden ber�cksichtigt!",
	'dat_export_done_title' =>
		"Export beendet!",
	'dat_export_done_msg%s%s%s' =>
		"Der Export des %s Datfiles f�r\n\n%s\n\nin das Verzeichniss:\n\n%s\n\nwurde fertigestellt!",
	'dat_import_filechooser_title%s' =>
		"Import: W�hle ein %s Datfile!",
	'rom_import_backup_title' =>
		"Sicherung erstellen?",
	'rom_import_backup_msg%s%s' =>
		"Soll for dem Import ein Sicherung erstellt werden, bevor die neuen META-Informationen integriert werden?\n\n%s (%s)",
	'rom_import_title' =>
		"Datfile importieren?",
	'rom_import_msg%s%s%s' =>
		"Sollen die Metainformation aus der Datei\n\n%s f�r die Platform\n\n%s (%s)\n\n�bernommen werden?",
	'rom_import_done_title' =>
		"Importierung erfolgreich fertiggestellt!",
	'rom_import_done_msg%s' =>
		"Der Import der Datei: \n\n%s\n\nwurde erfolgreich beendet!",
	'dat_clear_title%s' =>
		"Alle %s Metainformationen l�schen?",
	'dat_clear_msg%s%s' =>
		"M�chtest Du wirklich alle Metainformationen der Platform \n\n%s (%s)l�schen?\n\n(Metainformationen sind z.B. Titel, Kategorien, Sprachen usw.)\n\nVor dem l�schen kannst du automatisch eine Sicherung deiner Daten erstellen lassen. (Diese Sicherung wird automatisch in deinem ecc-user Verzeichnis gespeichert!)",
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
		"Der gew�hlte Emulator wurde nicht gefunden. Gr�n signalisiert g�ltige, Rot ung�ltige Emulator Pfadangaben.",
	'emu_miss_notset_msg' =>
		"Es wurde kein g�ltiger Emulator zugewiesen!",
	'emu_miss_dir_msg%s' =>
		"Der gew�hlte Pfad ist ein Verzeichnis!!!!",
	'rom_miss_title' =>
		"Fehler - Spiel nicht gefunden!",
	'rom_miss_msg' =>
		"Das gew�hlte Spiel wurde nicht gefunden!\n\nEine Optimierung der Datenbank k�nnte das Problem l�sen.'\nM�glicherweise m�ssen die Platformspezifisch Einstellung in der Emulator Konfiguration angepasst werden (z.B. 'escape' oder '8.3') werden!",
	'img_overwrite_title' =>
		"Bild �berschreiben?",
	'img_overwrite_msg%s%s' =>
		"Das Bild\n\n%s\n\nexistiert bereits\n\nSoll das Bild durch das neue ersetzt werden\n\n%s?",	
	'img_remove_title' =>
		"Bild l�schen?",
	'img_remove_msg%s' =>
		"Soll das Bild %s wirklich gel�scht werden?",
	'img_remove_error_title' =>
		"Fehler - Konnte Bilddatei nicht entfernen!",
	'img_remove_error_msg%s' =>
		"Bilddatei: %s konnte nicht gel�scht werden!",
	'conf_platform_update_title' =>
		"Platform Konfiguration aktualisiern?",
	'conf_platform_update_msg%s' =>
		"Soll die Platform Konfiguration wirklich aktualisiern werden?%s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Bitte einen Emulator f�r die Dateiendung: '%s' zuweisen",
	'conf_userfolder_notset_title' =>
		"Fehler: Konnte das ecc-user Verzeichnis nicht finden!!!",
	'conf_userfolder_notset_msg%s' =>
		"Der Basispfad wurde in der ecc_general.ini ge�ndert.Der angegebene Ordner ist im Moment noch nicht erstellt.\n\nSoll dieser jetzt automatisch erstellt werden?\n\n%s\n\nSollte bereits ein anderer Pfad gew�hlt wurden sein, klicken sie bitte auf Nein und gehen sie auf \n'options'->'configuration'\num die Einstellungen zu ver�ndern!",
	'conf_userfolder_error_readonly_title' =>
		"Fehler: Ordner konnte nicht erstellt werden!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"Die Ordner %s konnten NICHT erstellt werden, warscheinlich wurde ein Schreibgesch�tzter Datentr�ger gew�hlt.\n\nW�hlen sie einen anderen Speicherplatz bzw. ein anderes Speichermedium.\nUnter 'options'->'configuration' lassen sich die Einstellungen ver�ndern!",
	'conf_userfolder_created_title' =>
		"User Verzeichnis erstellt!",
	'conf_userfolder_created_msg%s%s' =>
		"Die Unterverzeichnisse\n\n%s\n\nwurden im ecc-user Verzeichnis %s erstellt.",
	'conf_ecc_save_title' =>
		"Aktuallisiere emuControlCenter GLOBAL-INI?",
	'conf_ecc_save_msg' =>
		"�nderungen speichern?\n\nHierbei werden automatisch alle im ecc-user Verzeichnis ben�tigen Unterverzeichnisse angelegt!",
	'conf_ecc_userfolder_filechooser_title' =>
		"Es muss ein ecc-user Verzeichnis angegeben werden",
	'fav_remove_all_title' =>
		"Alle Bookmarks entfernen?",
	'fav_remove_all_msg' =>
		"SOLLEN WIRKLICH ALLE BOOKMARKS GEL�SCHT WERDEN?",
	'maint_empty_history_title' =>
		'Zur�cksetzen der history.ini?',
	'maint_empty_history_msg' =>
		'In der history.ini werden alle Usereinstellungen gespeichert. Soll dieses Datei wirklich geleert werden?',
	'sys_dialog_info_miss_title' =>
		"?? TITEL FEHLT ??",
	'sys_dialog_info_miss_msg' =>
		"?? NACHRICHT FEHLT ??",
	'sys_filechooser_miss_title' =>
		"?? TITEL FEHLT??",
	'status_dialog_close' =>
		"\n\nSoll die Detail�bersicht geschlossen werden?",
	'status_process_running_title' =>
		"Prozess l�uft bereits!",
	'status_process_running_msg' =>
		"Ein anderer Prozess l�uft bereits.\nEs kann nur immer ein Prozess zeitgleich laufen, um Fehler zuvermeiden. Bitte warten, bis der derzeitige Prozess beendet ist!",
	'meta_rating_add_error_msg' =>
		"Es k�nnen nur Spiele mit Metainformationen bewertet werden.\n\nMetainformationen k�nnen unter 'Metainformationen bearbeiten' eingegeben werden!",
	'maint_unset_ratings_title' =>
		"Bewertungen einer Platform l�schen?",
	'maint_unset_ratings_msg' =>
		"Sollen alle Bewertungen der gew�hlten Platform gel�scht werden?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistiken:\n\n%s hinzugef�gt\n%s bereits eingetragen\n%s Fehlers\n\n%s Datasets bearbeitet!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatenbank:\n\nUm die ECC-Community zu unterst�tzen, sollte man seine eigegebenen Informationen (Titel, Kategorie...) zu den ROMS ver�ffentlichen.\n\nDie ROMDB ist eine wachsende Datenbank mit dem Ziel, Informationen zu jedem ROM zu erfassen.\n\n�hnlich wie eine CD-Datenbank.\n\nF�r das Hochladen wird eine Internet-Verbindung  ben�tigt!!!\n\nNach 10 erfolgreichen Uploads wird eine erneute Best�tigung ben�tigt.",
	'eccdb_error' =>
		"eccdb - Fehler:\n\nEs konnte keine Verbindung zum Internet herstellen. Bitte �berpr�fe, ob Du Online bist oder Deine Firewall die Verbindung untersagt!",
	'eccdb_no_data' =>
		"eccdb - Keine Daten gefunden:\n\nDu musst die Metainformationen deiner Spiele bearbeiten um sie hochladen zu k�nnen.",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Diese Option durchsucht die Datenbank nach doppelten Spielen. Diese werden dann im Statusbereich mit Pfadangaben ausgebenen. Wenn du das Logging aktiviert hast, wirst du eine Textdate in deinem Log-Verzeichnis finden.!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Alle Bilder l�schen?",
	'img_remove_all_msg%s' =>
		"Alle Bilder f�r das gew�hlte Spiel\n\n%s\n\nl�schen?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"Best�tige",
	/* 0.9.2 WIP 11 */

	'parse_big_file_found_title' =>
		"Datei parsen?",
	'parse_big_file_found_msg%s%s' =>
		"GRO�E DATEI GEFUNDEN!!!\n\nDas Spiel\n\nName: %s\nSize: %s\n\nist sehr gro�. Das parsen kann einige Zeit in Anspruch nehmen, in der das emuControlCenter nicht reagiert.\n\nSoll das Spiel trotzdem geparst werden?",
	
	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Bookmark gespeichert",
	'bookmark_added_msg' =>
		"Dieser Bookmark wurde hinzugef�gt!",
	'bookmark_removed_single_title' =>
		"Bookmark entfernt",
	'bookmark_removed_single_msg' =>
		"Der Bookmark wurde entfernt!",
	'bookmark_removed_all_title' =>
		"Bookmarks entfernt!",
	'bookmark_removed_all_msg' =>
		"Alle Bookmarks wurden entfernt!",

);
?>