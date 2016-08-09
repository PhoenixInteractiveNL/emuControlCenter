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
		"%s: Medien-Ordner ausw�hlen!",
	'rom_add_parse_title%s' =>
		"Neue ROMS hinzuf�gen f�r %s",
	'rom_add_parse_msg%s%s' =>
		"F�gt ROMS hinzu f�r die Platform:\n\n%s\n\naus dem Ordner:\n\n%s?",
	'rom_add_parse_done_title' =>
		"Vergleich beende!t",
	'rom_add_parse_done_msg%s' =>
		"Vergleich der neuen \n\n%s\n\nROMS ist beendet!",
	'rom_remove_title%s' =>
		"L�SCHE EINTR�GE VON %s",
	'rom_remove_msg%s' =>
		"Soll die Datenbank von\n\"%s\" Medien bereinigt werden?\n\nDiese Aktion entfernt alle Dateiinformationen aus der ECC-Datenbank.\nDie Dateien und Datenbankdaten auf der Fesplatte bleiben unversehrt!",
	'rom_remove_done_title' =>
		"Datenbank-L�schung beendet",
	'rom_remove_done_msg%s' =>
		"Alle Eintr�ge %s betreffend, sind erfolreich aus der Datenbank gel�scht wurden",
	'rom_remove_single_title' =>
		"ROM aus der Datenbank l�schen?",
	'rom_remove_single_msg%s' =>
		"Soll die Datei\n\n%s\n\naus der ECC Datenbank gel�scht werden?",
	'rom_remove_single_dupfound_title' =>
		"ROM Duplikate gefunden!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ROM DUPLIKATE GEFUNDEN\n\nSollen auch alle Duplikate von \n\n%s\n\n aus der ECC Datenbank gel�scht werden?\n\nWeitere Informationen hierzu, sind in der HILFE zu finden!",
	'rom_optimize_title' =>
		"Datenbank optimieren",
	'rom_optimize_msg' =>
		"Sollen die ROMS in der Datenbank optimiert werden?\n\nDie Datenbank sollte optimiert werden wenn viele Ver�nderungen in den ROM-Ordnern vorgenommen wurden!\nECC wird automatisch danach suchen und Eintr�ge und Lesezeichen ggf. l�schen.\nDie Dateien auf der Festplatte bleiben unversehrt!",
	'rom_optimize_done_title' =>
		"Optimierung fertiggestellt!",
	'rom_optimize_done_msg%s' =>
		"Die Datenbankeintr�ge f�r \n\n%s\n\nsind jetzt optimiert!",
	'rom_dup_remove_title' =>
		"ROM Duplikate l�schen (Nur ECC Datenbank betreffend)?",
	'rom_dup_remove_msg%s' =>
		"Sollen alle Duplikate von \n\n%s\n\naus der ECC Datenbank gel�scht werden?\n\nDie Dateien auf der Festplatte bleiben unversehrt!",
	'rom_dup_remove_done_title' =>
		"L�schvorgang erfolgreich!",
	'rom_dup_remove_done_msg%s' =>
		"Alle Duplikate von\n\n%s\n\nerfolgreich aus der Datenbank gel�scht",
	'rom_reorg_nocat_title' =>
		"Es sind keine Kategorien vorhanden!",
	'rom_reorg_nocat_msg%s' =>
		"Es wurde keine Kategorie f�r die ROMS:\n\n%s\n\ngew�hlt! Eine Bearbeitung der META-Informationen oder das aktualisieren durch eine Datenbank-Datei wird erfordert!",
	'rom_reorg_title' =>
		"ROMS auf dem Datentr�ger umstrukturieren?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------DIESE OPTION WIRD ALLE ROMS AUF DEM DATENTR�GER UMSTRUKTURIEREN!!!\n ------------------------------------------------------------------------------------------UM EINE REIBUNGSLOSE DURCHF�HRUNG ZU GEW�HREN BITTE ZUERST DUPLIKATE ENTFERNEN! \nDer Ausgew�hlte Modus ist: #%s#\n------------------------------------------------------------------------------------------\n\nSollen die ROMS nach ihrer Katerorie sortiert werden, f�r\n\n%s\n\nauf dem bestehenden Dateisystem? ECC ordnet die Dateien neu um, gespeichert werden sie anschlie�end unter\n\n%s/roms/organized/\n\nBitte kontrollieren sie zuvor die noch verf�gbare Speicherkapazit�t, dammit es zu keinen Problem kommen kann.\n\nDIE AUSF�HRUNG GESCHIEHT AUF EIGENE GEFAHR!!! :-)",
	'rom_reorg_done_title' =>
		"Umstrukturierung erfolgreich beendet!",
	'rom_reorg_done__msg%s' =>
		"Die Aufgabe wurde erfolgreich ausgef�hrt, es wird empfohlen selbst auch noch Kontrolle zu machen.\n\n%s",
	'db_optimize_title' =>
		"Optimiere Datenbank",
	'db_optimize_msg' =>
		"Soll die Datenbank optimiert werden?\nWenn h�ufiger in dem Index von ECC Ver�nderungen statt finden, entstehen L�cken!\nDie werden mit Hilfe der Optimierung geschlossen um den Speicherplatz zu minimieren.\n\nWarscheinlich wird die Anwendung w�rend der Ausf�hrung nicht reagieren, dieses Verhalten ist normal! :-)",
	'db_optimize_done_title' =>
		"Datenbank optimiert!",
	'db_optimize_done_msg' =>
		"Die Datenbank wurde erfolgreich optimiert!",
	'export_esearch_error_title' =>
		"Keine eSearch Optionen gew�hlt!",
	'export_esearch_error_msg' =>
		"You have to use the eSearch (extended search) to use this export-function. This will only export the search-result, you see in the mainview!",
	'dat_export_filechooser_title%s' =>
		"W�hle einen Pfad zur Speicherung der %s dat-file!",	
	'dat_export_title%s' =>
		"Exportierung der %s datfile",
	'dat_export_msg%s%s%s' =>
		"Soll eine Exportierung der %s datfile f�r die Platform\n\n%s\n\n in das Verzeichniss:\n%s\nerstellt werden?",
	'dat_export_esearch_msg_add' =>
		"\n\nBei der Exportierung werden die Esearch Eingaben ber�cksichtigt!",
	'dat_export_done_title' =>
		"Exportierung beendet!",
	'dat_export_done_msg%s%s%s' =>
		"Exportierung der %s datfile f�r\n\n%s\n\nin das Verzeichniss:\n\n%s\n\nfertigestellt!",
	'dat_import_filechooser_title%s' =>
		"Importierung: W�hle eine %s datfile!",
	'rom_import_backup_title' =>
		"Sicherung erstellen?",
	'rom_import_backup_msg%s%s' =>
		"Soll ein Sicherung erstellt werden: \n\n%s (%s)\n\nbevor die neuen META-Informationen integriert werden?",
	'rom_import_title' =>
		"Datfile importieren?",
	'rom_import_msg%s%s%s' =>
		"Sollen die Informationen aus der Datei\n\n%s f�r die Platform\n\n%s (%s)\n\n�bernommen werden?",
	'rom_import_done_title' =>
		"Importierung erfolgreich fertiggestellt!",
	'rom_import_done_msg%s' =>
		"Die Importierung der Datei: \n\n%s\n\nwurde erfolgreich beendet!",
	'dat_clear_title%s' =>
		"Datenbank von %s reinigen",
	'dat_clear_msg%s%s' =>
		"SOLLEN ALLE META INFORMATIONEN zu \n\n%s (%s)GEL�SCHT WERDEN?\n\nDiese Aufgabe l�scht ALLE Metainformationen(Kategorie, Sprache usw.) zu den ROMS, der gew�hlten Platform . Der n�chste Schritt erlaubt eine Sicherung der bereits eigentragen Informationen. (Die Datei wird automatisch in den User-Ordner gespeichert!)\n\nDer letzte Schritt ist die Optimierung der Datenbank!",
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
		"Der gew�hlte Emulator wurde nicht gefunden. Gr�n signalisiert g�ltige, Rot ung�ltige Emulator Pfadangaben.",
	'emu_miss_notset_msg' =>
		"Es wurde kein g�ltiger Emulator, der Platform zugewiesen!",
	'emu_miss_dir_msg%s' =>
		"Der gew�hlte Pfad ist ein Ordner!!!!",
	'rom_miss_title' =>
		"Fehler - Medien nicht gefunden!",
	'rom_miss_msg' =>
		"Die gew�hlte Datei wurde nicht gefunden!\n\nEine Optimierung der ROMS k�nnte das Problem l�sen.'\nWom�glich muss auch Platform spezifisch, die Einstellung 'escape' oder '8.3' ver�ndert werden!",
	'img_overwrite_title' =>
		"Bild �berschreiben?",
	'img_overwrite_msg%s%s' =>
		"Das Bild\n\n%s\n\nexistiert bereits\n\nSoll das Bild mit diesem ersetzt werden\n\n%s?",	
	'img_remove_title' =>
		"Bild l�schen?",
	'img_remove_msg%s' =>
		"Soll das Bild %s wirklich gel�scht werden?",
	'img_remove_error_title' =>
		"Fehler - Konnte Bilddatei nicht entfernen!",
	'img_remove_error_msg%s' =>
		"Bilddatei: %s konnte nicht gel�scht werden!",
	'conf_platform_update_title' =>
		"platform ini aktualisieren?",
	'conf_platform_update_msg%s' =>
		"Soll die Platform-ini wirklich aktualiesiert werden?%s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Bitte einen Emulator w�hlen, f�r die Dateiendung: '%s'",
	'conf_userfolder_notset_title' =>
		"Fehler: Konnte den User-Ordner nicht finden!!!",
	'conf_userfolder_notset_msg%s' =>
		"Der Basispfad wurde in der ecc_general.ini ge�ndert.Der angegebene Ordner ist im Moment noch nicht erstellt.\n\nSoll dieser jetzt automatisch erstellt werden?\n\n%s\n\nSollte bereits ein anderer Pfad gew�hlt wurden sein, klicken sie bitte auf Nein und gehen sie auf \n'options'->'configuration'\num die Einstellungen zu ver�ndern!",
	'conf_userfolder_error_readonly_title' =>
		"Fehler: Ordner konnte nicht erstellt werden!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"Die Ordner %s konnten NICHT erstellt werden, warscheinlich wurde ein Schreibgesch�tzter Datentr�ger gew�hlt.\n\nW�hlen sie einen anderen Speicherplatz bzw. ein anderes Speichermedium.\nUnter 'options'->'configuration' lassen sich die Einstellungen ver�ndern!",
	'conf_userfolder_created_title' =>
		"User-Ordner erstellt!",
	'conf_userfolder_created_msg%s%s' =>
		"Die Unterordner \n\n%s\n\nsind in dem zuvor gew�hlten User-Ordner erstellt wurden.\n\n%s",
	'conf_ecc_save_title' =>
		"Aktuallisiere emuControlCenter GLOBAL-INI?",
	'conf_ecc_save_msg' =>
		"Die Ver�nderungen werden in der ecc_global.ini gespeichert!\n\nAuch werden hierbei die gew�hlten User-Ordner und alle ben�tigten Unterordner erstellt!\n\nDennoch fortfahren?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Es muss ein User-Ordner gew�hlt werden",
	'fav_remove_all_title' =>
		"Alle Lesezeichen l�schen?",
	'fav_remove_all_msg' =>
		"SOLLEN WIRKLICH ALLE LESEZEICHEN GEL�SCHT WERDEN?",
	'maint_empty_history_title' =>
		'Zur�cksetzen der ECC history.ini?',
	'maint_empty_history_msg' =>
		'Diese Aufgabe wird die history.ini Datei leeren. In ihr werden Information bez�glich den genutzen Pfaden und dem Erscheinen von ECC gespeichert (z.B. Duplikate ausblenden). Soll die Datei zur�ck gesetzt werden?',
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
		"Ein anderer Prozess l�uft bereits.\nEs kann nur immer ein Prozess zeitgleich laufen, um Fehler zuvermeiden. Bitte warten sie bis der derzeitige Prozess beendet ist!",
	'meta_rating_add_error_msg' =>
		"Es k�nnen nur ROMS mit META-Informationen, bewertet werden.\n\nMETA-Informationen k�nnen unter 'ROM Informationen Bearbeiten' eingegeben werden!",
	'maint_unset_ratings_title' =>
		"Bewertungen einer Platform l�schen?",
	'maint_unset_ratings_msg' =>
		"Diese Aktion wird alle Bewertung aus der Datenbank l�schen, dennoch fortfahren?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistiken:\n\n%s hinzugef�gt\n%s bereits eingetragen\n%s Fehlers\n\n%s Datasets bearbeitet!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatenbank:\n\nUm die ECC-Community zu unterst�tzen, sollte man seine eigegebenen Informationen(Titel, Kategorie...) zu den ROMS ver�ffentlichen.\n\nDie ROMDB ist eine wachsende Datenbank mit dem Ziel, Informationen zu jedem ROM zu erfassen.\n\n�hnlich wie eine CD-Datenbank.\n\nF�r das Hochladen wird eine Internet-Verbindung  ben�tigt!!!\n\nNach 10 erfolgreichen Uploads wird eine erneute Best�tigung ben�tigt.",
	'eccdb_error' =>
		"eccdb - Fehler:\n\nECC konnte keine Verbindung zum Internet herstellen. Bitte die Verbindung zum Internet �berpr�fen und probier es danach noch Einmal probieren",
	'eccdb_no_data' =>
		"eccdb - Keine Daten gefunden:\n\nDu musst die Eintr�ge f�r die ROMS bearbeiten um sie hochladen zu k�nnen.",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"This option will search for duplicate roms in your database and will output the found roms\n\nYou will also find the logfile in your ecc-logs folder!",
);
?>