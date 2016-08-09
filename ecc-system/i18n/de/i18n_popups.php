<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	de (german)
 * author:	andreas scheibel
 * date:	2006/09/09 
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"ROM-Verzeichnis auswählen für: %s",
	'rom_add_parse_title%s' =>
		"%s ROMS hinzufügen?",
	'rom_add_parse_msg%s%s' =>
		"Sollen alle ROMS für\n\n%s\n\naus dem Verzeichnis\n\n%s\n\nhinzugefügt werden?",
	'rom_add_parse_done_title' =>
		"Fertig!",
	'rom_add_parse_done_msg%s' =>
		"Alle gefundenen ROMS für\n\n%s\n\nwurden in die ecc-Datenbank eingetragen!",
	'rom_remove_title%s' =>
		"ROMS für %s entfernen",
	'rom_remove_msg%s' =>
		"Sollen wirklich alle ROMS für \n\n\"%s\"\n\n aus der Datenbank entfernt werden?\n\nEs werden nur Daten aus der Datenbank entfernt! Die ROMS bleiben weiterhin auf der Festplatte! :-)",
	'rom_remove_done_title' =>
		"Fertig!",
	'rom_remove_done_msg%s' =>
		"Alle %s ROMS wurden aus der Datenbank entfernt!",
	'rom_remove_single_title' =>
		"ROM entfernen?",
	'rom_remove_single_msg%s' =>
		"Soll das ROM\n\n%s\n\nwirklich aus der Datenbank entfernt werden?",
	'rom_remove_single_dupfound_title' =>
		"Doppelte ROMS gefunden!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"ecc hat %d ROM(S) mit der gleichen Checksumme gefunden. Soll\n\n%s\n\nauch aus der Datenbank entfernt werden?",
	'rom_optimize_title' =>
		"ROMS optimieren?",
	'rom_optimize_msg' =>
		"Soll ecc die ROMS in der Datenbank optimieren?\n\nDu solltest die Datenbank zum Beispiel dann optimieren, wenn du ROMS auf deiner Festplatte gelöscht oder umbewegt hast.\necc schaut auf der Festplatte nach, ob die ROMS noch an der indizierten stelle vorhanden sind und entfernt sie gegebenenfalls aus seiner Datenbank\nDas optimieren löscht nur Daten aus der Datenbank!",
	'rom_optimize_done_title' =>
		"Optimierung durchgeführt!",
	'rom_optimize_done_msg%s' =>
		"Die Datenbank für\n\n%s\n\nwurde optimiert!",
	'rom_dup_remove_title' =>
		"Doppelte ROMS aus der Datenbank löschen?",
	'rom_dup_remove_msg%s' =>
		"Sollen alle doppelten\n\n%s\n\nROMS aus der ecc-Datenbank entfernt werden?\n\nDiese Operation löscht nur Daten aus der Datenbank! Die ROMS auf der Festplatte bleiben unberührt!",
	'rom_dup_remove_done_title' =>
		"Vorgang abgeschlossen!",
	'rom_dup_remove_done_msg%s' =>
		"Alle doppelten ROMS für\n\n%s\n\nwurden aus der ecc-Datenbank entfernt!",
	'rom_reorg_nocat_title' =>
		"Keine Kategorien!",
	'rom_reorg_nocat_msg%s' =>
		"Es wurden keine Kategorien in den Metainformationen deiner \n\n%s\n\nROMS gefunden! Du kannst deine ROMS über die EDIT-Funktion kategoriesieren oder ein gutes ecc-Datfile importieren!",
	'rom_reorg_title' =>
		"ROMS organisieren?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------BITTE FÜHRE ALS ERSTES DAS PREVIEW DURCH!!!!!\n\nDIESE OPTION ORGANISIERT DEINE ROMS AUF DER FESTPLATTE !!! BITTE ENTFERNE ZUERST ALLE DOPPELTEN ROMS AUS DER DATENBANK !!!\nDER AUSGEWÄHLTE MODUS IST: #%s#\n------------------------------------------------------------------------------------------\n\nWillst du wirklich, das ecc deine ROMS für die Platform\n\n%s\n\nnach den zugwiesenen Kategorien auf deiner Festplatte organisiert? ecc wird hierzu ein Verzeichnis in deinem User-Verzeichnis anlegen in dem die Kategorien abgelegt werden!\n\n%s/roms/organized/\n\nBitte vergewissere dich vorher, das du genügend Platz auf der Festplatte zur Verfügung hast!\n\nMÖCHTEST DU DIES AUF DEINE EIGENE VERANTWORTUNG TUN? :-)",
	'rom_reorg_done_title' =>
		"Vorgang beendet!",
	'rom_reorg_done__msg%s' =>
		"Alle kategorisierten ROMS wurden unter\n\n%s\n\norganisiert. Die Datenbank-Einträge wurden geändert!",
	'db_optimize_title' =>
		"Datenbank optimieren?",
	'db_optimize_msg' =>
		"Soll die Datenbank wirklich optimiert werden\nDieser Vorgang kann die größe der emuControlCenter-Datenbank dramatisch verkleinern!\n\nDiese Operation wird einige Sekunden in Anspruch nehmen! :-)",
	'db_optimize_done_title' =>
		"Vorgang abgeschlossen!",
	'db_optimize_done_msg' =>
		"Die Datenbank ist nun optimiert!",
	'export_esearch_error_title' =>
		"Keine Optionen ausgewählt!",
	'export_esearch_error_msg' =>
		"Um diese Art von Export durchzuführen muß eine Erweiterte Suche (eSearch) durchgeführt werden\nDadurch werden nur die gefundenen Suchergebnisse exportiert die im Hauptbereich zu sehen sind!",
	'dat_export_filechooser_title%s' =>
		"Speicherort füf %s datfile auswählen!",	
	'dat_export_title%s' =>
		"%s datfile exportieren",
	'dat_export_msg%s%s%s' =>
		"Soll wirklich ein %s datfile für die Platform\n\n%s\n\nin dieses Verzeichnis generiert werden?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"\n\necc berücksichtigt deine eSearch Einstellungen für den export!",
	'dat_export_done_title' =>
		"Export durchgeführt",
	'dat_export_done_msg%s%s%s' =>
		"Das %s datfile für die Platform\n\n%s\n\nwurde in die Datei\n\n%s\n\ngespeichert!",
	'dat_import_filechooser_title%s' =>
		"Import: Wähle ein %s datfile!",
	'rom_import_backup_title' =>
		"Backup anlegen?",
	'rom_import_backup_msg%s%s' =>
		"Soll vor dem Datfile-Import für\n\n%s (%s)\n\nein Backup der aktuellen Metadaten im Userfolder abgelegt werden?",
	'rom_import_title' =>
		"Datfile importieren?",
	'rom_import_msg%s%s%s' =>
		"Sollen die Daten für die Plattform\n\n%s (%s)\n\nwirklich aus dem Datfile\n\n%s\n\nimportiert werden?",
	'rom_import_done_title' =>
		"Import durchgeführt!",
	'rom_import_done_msg%s' =>
		"Import des Datfiles für \n\n%s\n\nabgeschlossen!",
	'dat_clear_title%s' =>
		"Metadaten für %s entfernen",
	'dat_clear_msg%s%s' =>
		"Sollen wirklich alle Metainformationenen für\n\n%s (%s)\n\naus der Datenbank entfernt werden?\n\nIm nächsten Schritt kann ecc automatisch ein Backup der vorhandenen Metainformationen erstellen!",
	'dat_clear_backup_title%s' =>
		"Sicherheitskopie für %s anlegen?",
	'dat_clear_backup_msg%s%s' =>
		"Soll eine Sicherheitskopie der Metadaten für \n\n%s (%s)\n\n angelegt werden??",
	'dat_clear_done_title%s' =>
		"Vorgang abegschlossen!",
	'dat_clear_done_msg%s%s' =>
		"Alle Metadaten für die Platform\n\n%s (%s)\n\nwurden aus der Datenbank entfernt!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\nDas Backup wurde in das Userverzeichnis für %s abgelegt!",
	'emu_miss_title' =>
		"Fehler - Emulator nicht gefunden!",
	'emu_miss_notfound_msg%s' =>
		"Der eingetragene Emulator\n\n%s\n\nkonnte nicht gefunden werden!\nBitte überprüfe, ob der Pfad richtig gesetzt wurde!",
	'emu_miss_notset_msg' =>
		"Es wurde noch kein Emulator für diese Platform/Fileextension zugewiesen!",
	'rom_miss_title' =>
		"Fehler - ROM nicht gefunden!",
	'rom_miss_msg' =>
		"Das ausgewählte ROM kann nicht gestartet werden, da der Pfad nicht richtig ist!\n\nEin Grund kann das Löschen oder Umbewegen von ROMS auf der Festplatte sein.\nBitte führe die Option 'ROMS->optimize roms in ecc' aus!",		
	'img_overwrite_title' =>
		"Bild überschreiben?",
	'img_overwrite_msg%s%s' =>
		"Das Bild\n\n%s\n\nexistiert bereits\n\nSoll dieses wirklich durch das Bild\n\n%s\n\nüberschrieben werden?",	
	'img_remove_title' =>
		"Bild löschen?",
	'img_remove_msg%s' =>
		"Soll das Bild %s wirklich gelöscht werden?",
	'img_remove_error_title' =>
		"Fehler - Bild konnte nicht gelöscht werden!",
	'img_remove_error_msg%s' =>
		"Das Bild %s konnte nicht gelöscht werden!",
	'conf_platform_update_title' =>
		"Konfiguration speichern?",
	'conf_platform_update_msg%s' =>
		"Soll die neue Konfiguration für %s wirklich gespeichert werden?",
	'conf_platform_emu_filechooser_title%s' =>
		"Wähle den Emulator für die Fileextension '%s'",
	'conf_userfolder_notset_title' =>
		"Fehler: User-Verzeichnis nicht gefunden!!!",
	'conf_userfolder_notset_msg%s' =>
		"Der Pfad zu deinem User-Verzeichnis wurde geändert! Dieser Pfad ist noch nicht vorhanden!\n\nSoll das Verzeichnis\n\n%s\n\nangelegt werden?",
	'conf_userfolder_created_title' =>
		"Verzeichnisse angelegt!",
	'conf_userfolder_created_msg%s%s' =>
		"Die Unterverzeichnisse\n\n%s\n\nwurden in deinem Userfolder\n\n%s\n\nangelegt!",
	'conf_ecc_save_title' =>
		"emuControlCenter GLOBAL-INI speichern?",
	'conf_ecc_save_msg' =>
		"Sollen diese Einstellungen in gepeichert werden?\n\nEs werden automatisch alle Unterverzeichnisse für das gewählte Userverzeichnis erstellt!\n\nWillst du wirklich speichern?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Select the Folder for your User-Data",
	'fav_remove_all_title' =>
		"Bookmarks löschen?",
	'fav_remove_all_msg' =>
		"Sollen wirklich alle Bookmarks gelöscht werden?",
	'sys_dialog_info_miss_title' =>
		"?? TITLE FEHLT ??",
	'sys_dialog_info_miss_msg' =>
		"?? MESSAGE FEHLT ??",
	'sys_filechooser_miss_title' =>
		"?? TITEL FEHLT ??",
	'status_dialog_close' =>
		"\n\nSollen die Processdetails ausgeblendet werden?",
	'status_process_running_title' =>
		"Process running",
	'status_process_running_msg' =>
		"Ein anderer Prozess läuft.\nEs kann nur ein Prozess zur Zeit abgearbeitet werden. Bitte warte, bis der laufende Prozess abgeschlossen ist, oder breche diesen ab!",
);
?>