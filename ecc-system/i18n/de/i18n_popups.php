<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	de (german)
 * author:	franz schneider
 * date:	2007/03/04
 * comment: this was a very difficult work to translate this text. if you find any mistake, don't hesitate to call me. 
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Medien-Ordner auswählen!",
	'rom_add_parse_title%s' =>
		"Neue ROMS hinzufügen für %s",
	'rom_add_parse_msg%s%s' =>
		"Fügt ROMS hinzu für die Platform:\n\n%s\n\naus dem Ordner:\n\n%s?",
	'rom_add_parse_done_title' =>
		"Vergleich beende!t",
	'rom_add_parse_done_msg%s' =>
		"Vergleich der neuen \n\n%s\n\nROMS ist beendet!",
	'rom_remove_title%s' =>
		"LÖSCHE EINTRÄGE VON %s",
	'rom_remove_msg%s' =>
		"Soll die Datenbank von\n\"%s\" Medien bereinigt werden?\n\nDiese Aktion entfernt alle Dateiinformationen aus der ECC-Datenbank.\nDie Dateien und Datenbankdaten auf der Fesplatte bleiben unversehrt!",
	'rom_remove_done_title' =>
		"Datenbank-Löschung beendet",
	'rom_remove_done_msg%s' =>
		"Alle Einträge %s betreffend, sind erfolreich aus der Datenbank gelöscht wurden",
	'rom_remove_single_title' =>
		"ROM aus der Datenbank löschen?",
	'rom_remove_single_msg%s' =>
		"Soll die Datei\n\n%s\n\naus der ECC Datenbank gelöscht werden?",
	'rom_remove_single_dupfound_title' =>
		"ROM Duplikate gefunden!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ROM DUPLIKATE GEFUNDEN\n\nSollen auch alle Duplikate von \n\n%s\n\n aus der ECC Datenbank gelöscht werden?\n\nWeitere Informationen hierzu, sind in der HILFE zu finden!",
	'rom_optimize_title' =>
		"Datenbank optimieren",
	'rom_optimize_msg' =>
		"Sollen die ROMS in der Datenbank optimiert werden?\n\nDie Datenbank sollte optimiert werden wenn viele Veränderungen in den ROM-Ordnern vorgenommen wurden!\nECC wird automatisch danach suchen und Einträge und Lesezeichen ggf. löschen.\nDie Dateien auf der Festplatte bleiben unversehrt!",
	'rom_optimize_done_title' =>
		"Optimierung fertiggestellt!",
	'rom_optimize_done_msg%s' =>
		"Die Datenbankeinträge für \n\n%s\n\nsind jetzt optimiert!",
	'rom_dup_remove_title' =>
		"ROM Duplikate löschen (Nur ECC Datenbank betreffend)?",
	'rom_dup_remove_msg%s' =>
		"Sollen alle Duplikate von \n\n%s\n\naus der ECC Datenbank gelöscht werden?\n\nDie Dateien auf der Festplatte bleiben unversehrt!",
	'rom_dup_remove_done_title' =>
		"Löschvorgang erfolgreich!",
	'rom_dup_remove_done_msg%s' =>
		"Alle Duplikate von\n\n%s\n\nerfolgreich aus der Datenbank gelöscht",
	'rom_reorg_nocat_title' =>
		"Es sind keine Kategorien vorhanden!",
	'rom_reorg_nocat_msg%s' =>
		"Es wurde keine Kategorie für die ROMS:\n\n%s\n\ngewählt! Eine Bearbeitung der META-Informationen oder das aktualisieren durch eine Datenbank-Datei wird erfordert!",
	'rom_reorg_title' =>
		"ROMS auf dem Datenträger umstrukturieren?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------DIESE OPTION WIRD ALLE ROMS AUF DEM DATENTRÄGER UMSTRUKTURIEREN!!!\n ------------------------------------------------------------------------------------------UM EINE REIBUNGSLOSE DURCHFÜHRUNG ZU GEWÄHREN BITTE ZUERST DUPLIKATE ENTFERNEN! \nDer Ausgewählte Modus ist: #%s#\n------------------------------------------------------------------------------------------\n\nSollen die ROMS nach ihrer Katerorie sortiert werden, für\n\n%s\n\nauf dem bestehenden Dateisystem? ECC ordnet die Dateien neu um, gespeichert werden sie anschließend unter\n\n%s/roms/organized/\n\nBitte kontrollieren sie zuvor die noch verfügbare Speicherkapazität, dammit es zu keinen Problem kommen kann.\n\nDIE AUSFÜHRUNG GESCHIEHT AUF EIGENE GEFAHR!!! :-)",
	'rom_reorg_done_title' =>
		"Umstrukturierung erfolgreich beendet!",
	'rom_reorg_done__msg%s' =>
		"Die Aufgabe wurde erfolgreich ausgeführt, es wird empfohlen selbst auch noch Kontrolle zu machen.\n\n%s",
	'db_optimize_title' =>
		"Optimiere Datenbank",
	'db_optimize_msg' =>
		"Soll die Datenbank optimiert werden?\nWenn häufiger in dem Index von ECC Veränderungen statt finden, entstehen Lücken!\nDie werden mit Hilfe der Optimierung geschlossen um den Speicherplatz zu minimieren.\n\nWarscheinlich wird die Anwendung wärend der Ausführung nicht reagieren, dieses Verhalten ist normal! :-)",
	'db_optimize_done_title' =>
		"Datenbank optimiert!",
	'db_optimize_done_msg' =>
		"Die Datenbank wurde erfolgreich optimiert!",
	'export_esearch_error_title' =>
		"Keine eSearch Optionen gewählt!",
	'export_esearch_error_msg' =>
		"You have to use the eSearch (extended search) to use this export-function. This will only export the search-result, you see in the mainview!",
	'dat_export_filechooser_title%s' =>
		"Wähle einen Pfad zur Speicherung der %s dat-file!",	
	'dat_export_title%s' =>
		"Exportierung der %s datfile",
	'dat_export_msg%s%s%s' =>
		"Soll eine Exportierung der %s datfile für die Platform\n\n%s\n\n in das Verzeichniss:\n%s\nerstellt werden?",
	'dat_export_esearch_msg_add' =>
		"\n\nBei der Exportierung werden die Esearch Eingaben berücksichtigt!",
	'dat_export_done_title' =>
		"Exportierung beendet!",
	'dat_export_done_msg%s%s%s' =>
		"Exportierung der %s datfile für\n\n%s\n\nin das Verzeichniss:\n\n%s\n\nfertigestellt!",
	'dat_import_filechooser_title%s' =>
		"Importierung: Wähle eine %s datfile!",
	'rom_import_backup_title' =>
		"Sicherung erstellen?",
	'rom_import_backup_msg%s%s' =>
		"Soll ein Sicherung erstellt werden: \n\n%s (%s)\n\nbevor die neuen META-Informationen integriert werden?",
	'rom_import_title' =>
		"Datfile importieren?",
	'rom_import_msg%s%s%s' =>
		"Sollen die Informationen aus der Datei\n\n%s für die Platform\n\n%s (%s)\n\nübernommen werden?",
	'rom_import_done_title' =>
		"Importierung erfolgreich fertiggestellt!",
	'rom_import_done_msg%s' =>
		"Die Importierung der Datei: \n\n%s\n\nwurde erfolgreich beendet!",
	'dat_clear_title%s' =>
		"Datenbank von %s reinigen",
	'dat_clear_msg%s%s' =>
		"SOLLEN ALLE META INFORMATIONEN zu \n\n%s (%s)GELÖSCHT WERDEN?\n\nDiese Aufgabe löscht ALLE Metainformationen(Kategorie, Sprache usw.) zu den ROMS, der gewählten Platform . Der nächste Schritt erlaubt eine Sicherung der bereits eigentragen Informationen. (Die Datei wird automatisch in den User-Ordner gespeichert!)\n\nDer letzte Schritt ist die Optimierung der Datenbank!",
	'dat_clear_backup_title%s' =>
		"Sichern %s",
	'dat_clear_backup_msg%s%s' =>
		"Soll eine Sicherung angelegt werden zu \n\n%s (%s)?",
	'dat_clear_done_title%s' =>
		"Datenbank Leerung war erfolgreich!",
	'dat_clear_done_msg%s%s' =>
		"Alle Meta-Informationen zu \n\n%s (%s)\n\wurden aus der ECC Datenbank entfernt!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n Es wurde eine Automatische Sicherungsdatei erstellt in dem %s-User-Ordner",
	'emu_miss_title' =>
		"Fehler - Emulator nicht gefunden!",
	'emu_miss_notfound_msg%s' =>
		"Der gewählte Emulator wurde nicht gefunden. Grün signalisiert gültige, Rot ungültige Emulator Pfadangaben.",
	'emu_miss_notset_msg' =>
		"Es wurde kein gültiger Emulator, der Platform zugewiesen!",
	'emu_miss_dir_msg%s' =>
		"Der gewählte Pfad ist ein Ordner!!!!",
	'rom_miss_title' =>
		"Fehler - Medien nicht gefunden!",
	'rom_miss_msg' =>
		"Die gewählte Datei wurde nicht gefunden!\n\nEine Optimierung der ROMS könnte das Problem lösen.'\nWomöglich muss auch Platform spezifisch, die Einstellung 'escape' oder '8.3' verändert werden!",
	'img_overwrite_title' =>
		"Bild überschreiben?",
	'img_overwrite_msg%s%s' =>
		"Das Bild\n\n%s\n\nexistiert bereits\n\nSoll das Bild mit diesem ersetzt werden\n\n%s?",	
	'img_remove_title' =>
		"Bild löschen?",
	'img_remove_msg%s' =>
		"Soll das Bild %s wirklich gelöscht werden?",
	'img_remove_error_title' =>
		"Fehler - Konnte Bilddatei nicht entfernen!",
	'img_remove_error_msg%s' =>
		"Bilddatei: %s konnte nicht gelöscht werden!",
	'conf_platform_update_title' =>
		"platform ini aktualisieren?",
	'conf_platform_update_msg%s' =>
		"Soll die Platform-ini wirklich aktualiesiert werden?%s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Bitte einen Emulator wählen, für die Dateiendung: '%s'",
	'conf_userfolder_notset_title' =>
		"Fehler: Konnte den User-Ordner nicht finden!!!",
	'conf_userfolder_notset_msg%s' =>
		"Der Basispfad wurde in der ecc_general.ini geändert.Der angegebene Ordner ist im Moment noch nicht erstellt.\n\nSoll dieser jetzt automatisch erstellt werden?\n\n%s\n\nSollte bereits ein anderer Pfad gewählt wurden sein, klicken sie bitte auf Nein und gehen sie auf \n'options'->'configuration'\num die Einstellungen zu verändern!",
	'conf_userfolder_error_readonly_title' =>
		"Fehler: Ordner konnte nicht erstellt werden!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"Die Ordner %s konnten NICHT erstellt werden, warscheinlich wurde ein Schreibgeschützter Datenträger gewählt.\n\nWählen sie einen anderen Speicherplatz bzw. ein anderes Speichermedium.\nUnter 'options'->'configuration' lassen sich die Einstellungen verändern!",
	'conf_userfolder_created_title' =>
		"User-Ordner erstellt!",
	'conf_userfolder_created_msg%s%s' =>
		"Die Unterordner \n\n%s\n\nsind in dem zuvor gewählten User-Ordner erstellt wurden.\n\n%s",
	'conf_ecc_save_title' =>
		"Aktuallisiere emuControlCenter GLOBAL-INI?",
	'conf_ecc_save_msg' =>
		"Die Veränderungen werden in der ecc_global.ini gespeichert!\n\nAuch werden hierbei die gewählten User-Ordner und alle benötigten Unterordner erstellt!\n\nDennoch fortfahren?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Es muss ein User-Ordner gewählt werden",
	'fav_remove_all_title' =>
		"Alle Lesezeichen löschen?",
	'fav_remove_all_msg' =>
		"SOLLEN WIRKLICH ALLE LESEZEICHEN GELÖSCHT WERDEN?",
	'maint_empty_history_title' =>
		'Zurücksetzen der ECC history.ini?',
	'maint_empty_history_msg' =>
		'Diese Aufgabe wird die history.ini Datei leeren. In ihr werden Information bezüglich den genutzen Pfaden und dem Erscheinen von ECC gespeichert (z.B. Duplikate ausblenden). Soll die Datei zurück gesetzt werden?',
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
		"Ein anderer Prozess läuft bereits.\nEs kann nur immer ein Prozess zeitgleich laufen, um Fehler zuvermeiden. Bitte warten sie bis der derzeitige Prozess beendet ist!",
	'meta_rating_add_error_msg' =>
		"Es können nur ROMS mit META-Informationen, bewertet werden.\n\nMETA-Informationen können unter 'ROM Informationen Bearbeiten' eingegeben werden!",
	'maint_unset_ratings_title' =>
		"Bewertungen einer Platform löschen?",
	'maint_unset_ratings_msg' =>
		"Diese Aktion wird alle Bewertung aus der Datenbank löschen, dennoch fortfahren?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistiken:\n\n%s hinzugefügt\n%s bereits eingetragen\n%s Fehlers\n\n%s Datasets bearbeitet!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatenbank:\n\nUm die ECC-Community zu unterstützen, sollte man seine eigegebenen Informationen(Titel, Kategorie...) zu den ROMS veröffentlichen.\n\nDie ROMDB ist eine wachsende Datenbank mit dem Ziel, Informationen zu jedem ROM zu erfassen.\n\nÄhnlich wie eine CD-Datenbank.\n\nFür das Hochladen wird eine Internet-Verbindung  benötigt!!!\n\nNach 10 erfolgreichen Uploads wird eine erneute Bestätigung benötigt.",
	'eccdb_error' =>
		"eccdb - Fehler:\n\nECC konnte keine Verbindung zum Internet herstellen. Bitte die Verbindung zum Internet überprüfen und probier es danach noch Einmal probieren",
	'eccdb_no_data' =>
		"eccdb - Keine Daten gefunden:\n\nDu musst die Einträge für die ROMS bearbeiten um sie hochladen zu können.",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"This option will search for duplicate roms in your database and will output the found roms\n\nYou will also find the logfile in your ecc-logs folder!",
);
?>