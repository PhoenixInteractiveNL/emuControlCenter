<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	nl (dutch)
 * author:	Sebastiaan Ebeltjes
 * date:	2006/06/26
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Selecteer je media folder!",
	'rom_add_parse_title%s' =>
		"Nieuwe ROMS toevoegen voor %s",
	'rom_add_parse_msg%s%s' =>
		"Nieuwe ROMS toevoegen voor\n\n%s\n\nuit folder\n\n%s?",
	'rom_add_parse_done_title' =>
		"Verwerking klaar",
	'rom_add_parse_done_msg%s' =>
		"Verwerking nieuwe \n\n%s\n\nROMS is voltooid!",
	'rom_remove_title%s' =>
		"MAAK DB LEEG VOOR %s",
	'rom_remove_msg%s' =>
		"WIL JE DE DATABASE LEEGMAKEN VOOR \n\"%s\"-MEDIA?\n\nDeze actie zal alle bestandsinformatie van de geselecteerde media verwijderen uit de ECC database. Dit zal GEEN DAT-bestandsinformatie wissen van je media op je hardeschijf.",
	'rom_remove_done_title' =>
		"DB LEEGMAKEN VOLTOOID!",
	'rom_remove_done_msg%s' =>
		"Alle gegevens voor %s zijn verwijderd uit de ECC database",
	'rom_remove_single_title' =>
		"ROM verwijderen uit database?",
	'rom_remove_single_msg%s' =>
		"Zal ik\n\n%s\n\nverwijderen ui de ECC database?",
	'rom_remove_single_dupfound_title' =>
		"Dubbele ROMS gevonden!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d DUBBELE ROMS GEVONDEN\n\nZal ik ook alle dubbele verwijderen\n\n%s\n\n uit de ECC database?\n\nZie HELP voor meer informatie!",
	'rom_optimize_title' =>
		"Optimaliseer database",
	'rom_optimize_msg' =>
		"Wil je je ROMS optimaliseren in de ECC database?\n\nJe moet de database optimaliseren als je bestanden hebt verplaatst of verwijderd van je hardeschijf\nECC zal automatisch zoeken naar deze database ingangen en favorieten en zal ze verwijderen uit de database!\nDeze optie zal alleen de database bewerken.",
	'rom_optimize_done_title' =>
		"Optimalisatie voltooid!",
	'rom_optimize_done_msg%s' =>
		"De database voor de platform\n\n%s\n\nis nu geoptimaliseerd!",
	'rom_dup_remove_title' =>
		"Verwijder dubbele ROMS uit de ECC database?",
	'rom_dup_remove_msg%s' =>
		"Wil je alle dubbele ROMS verwijderen voor\n\n%s\n\nuit de ECC database?\n\nDeze operatie werkt alleen in de emuControlCenter Database....\n\nDit zal GEEN bestadnen verwijderen van je hardeschijf!!",
	'rom_dup_remove_done_title' =>
		"Verwijdering voltooid",
	'rom_dup_remove_done_msg%s' =>
		"Alle dubbele ROMS voor\n\n%s\n\nzijn verwijderd uit de ECC database",
	'rom_reorg_nocat_title' =>
		"Er zijn geen categorien!",
	'rom_reorg_nocat_msg%s' =>
		"Je hebt geen categorie toegewezen aan je\n\n%s\n\nROMS! Gebruik de bewerk-functie om categorien toe te voegen of importeer een ECC DAT-bestand!",
	'rom_reorg_title' =>
		"Herorganiseer ROMS op hardeschijf?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------THIS OPTION WILL REORGANIZE YOUR ROMS AT YOUR HARDDRIVE !!! PLEASE FIRST REMOVE DUPLICATES FROM ECC-DB !!!\nYOUR SELECTED MODE IS: #%s#\n------------------------------------------------------------------------------------------\n\nDo you want to reorganize your roms by categories for\n\n%s\n\nat your filesystem? ecc will organize your roms in the ecc-userfolder under\n\n%s/roms/organized/\n\nPlease check the discspace of this harddrive, if there is space available\n\nDO YOU WANT THIS AT YOUR RISK? :-)",
	'rom_reorg_done_title' =>
		"Herorganiseren voltooid",
	'rom_reorg_done__msg%s' =>
		"Bekijk het bestand in folder\n\n%s\n\nom er zeker van de zijn",
	'db_optimize_title' =>
		"Optimaliseer database",
	'db_optimize_msg' =>
		"Wil je de database optimaliseren?\nDit zal de ECC database doen krimpen (bestand)!, Je zou de optie 'vacuum' moeten gebruiken, als je vaak media toevoegd of verwijder in emuControlCenter!\n\nDeze bewerking kan de ECC even later bevriezen voor enkele seconden - even wachten AUB!",
	'db_optimize_done_title' =>
		"Database geoptimaliseerd!",
	'db_optimize_done_msg' =>
		"Je ECC database in nu geoptimaliseerd!",
	'export_esearch_error_title' =>
		"Geen eSearch opties geselecteerd",
	'export_esearch_error_msg' =>
		"Je moet de eSearch (extended search) optie gebruiken voor deze export-functie. Dit exporteert alleen je zoekresultaat die je ziet in het hoofdvenster!",
	'dat_export_filechooser_title%s' =>
		"Selecteer een folder om het %s DAT-bestand op te slaan!",	
	'dat_export_title%s' =>
		"Exporteer %s DAT-bestand",
	'dat_export_msg%s%s%s' =>
		"Wil je een %s DAT-bestand exporteren voor platform\n\n%s\n\nnaar deze folder?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"\n\nECC gebruikt je 'eSearch' selectie om te exporteren!",
	'dat_export_done_title' =>
		"Exporteren voltooid",
	'dat_export_done_msg%s%s%s' =>
		"Exporteer %s DAT-bestand voor\n\n%s\n\nin pad\n\n%s\n\nvoltooid!",
	'dat_import_filechooser_title%s' =>
		"Importeer: Selecteer een %s DAT-bestand!",
	'rom_import_backup_title' =>
		"Backup maken?",
	'rom_import_backup_msg%s%s' =>
		"Zal ik een BACKUP maken in je gebruikersfolder voor\n\n%s (%s)\n\nvoordat je nieuwe meta-gegevens gaat importeren?",
	'rom_import_title' =>
		"Importeer DAT-bestand?",
	'rom_import_msg%s%s%s' =>
		"Weet je zeker dat je data wilt importeren voor platform\n\n%s (%s)\n\nvan DAT-bestand\n\n%s?",
	'rom_import_done_title' =>
		"Importeren voltooid!",
	'rom_import_done_msg%s' =>
		"Importeren van DAT-bestand voor\n\n%s\n\nvoltooid!",
	'dat_clear_title%s' =>
		"Database leegmaken voor %s",
	'dat_clear_msg%s%s' =>
		"WIL JE ALLE META-GEGEVENS LEEGMAKEN VOOR\n\n%s (%s)-DAT?\n\nDit zal alle meta-informatie verwijderen zoals categorie, status, talen ect. voor het geselecteerde platform uit de ECC-database!. In de volgende stap, HEB JE EEN MOGELIJKHEID EEN BACKUP CREEREN VOOR DEZE GEGEVENS. (deze zal automatisch worden opgeslagen in je ECC gebruikersfolder ('ecc-user'))\n\nDe laaste stap is het optimaliseren van de database!",
	'dat_clear_backup_title%s' =>
		"Backup %s",
	'dat_clear_backup_msg%s%s' =>
		"Zal ik een backup maken voor platform\n\n%s (%s)?",
	'dat_clear_done_title%s' =>
		"DB leegmaken voltooid",
	'dat_clear_done_msg%s%s' =>
		"Alle meta-informatie voor\n\n%s (%s)\n\nzijn verwijderd uit de ecc-database!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n ECC heeft je gegevens opgeslagen in de %s-gebruikers-folder",
	'emu_miss_title' =>
		"Fout! - Emulator niet gevonden!",
	'emu_miss_notfound_msg%s' =>
		"De toegewezen emulator is niet gevonden. Groen is gevonden, Rood is niet gevonden.",
	'emu_miss_notset_msg' =>
		"Je hebt geen emulator toegewezen vor dit platform",
	'emu_miss_dir_msg%s' =>
		"Het toegewezen pad is een folder!!",
	'rom_miss_title' =>
		"Fout! - Media niet gevonden!",
	'rom_miss_msg' =>
		"Het geselecteerde bestand is niet gevonden\n\nGebruik de optie 'ROMS -> optimaliseer roms in ecc'\nControleer ook je emulator waarden zoals 'aanhalingtekens' of '8.3'",
	'img_overwrite_title' =>
		"Overschijf plaatje?",
	'img_overwrite_msg%s%s' =>
		"Het plaatje\n\n%s\n\nbestaat al\n\nWil je dit plaatje overschijven met\n\n%s?",	
	'img_remove_title' =>
		"Verwijder plaatje?",
	'img_remove_msg%s' =>
		"Wil je echt dit plaatje verwijderen %s",
	'img_remove_error_title' =>
		"Fout! - Kan het plaatje niet verwijderen!",
	'img_remove_error_msg%s' =>
		"Plaatjes %s konden niet verwijderd worden!",
	'conf_platform_update_title' =>
		"Platform INI bijwerken?",
	'conf_platform_update_msg%s' =>
		"Wil je echt de platform INI bestanden bijwerken voor %s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Selecteer een emulator voor extensie '%s'",
	'conf_userfolder_notset_title' =>
		"FOUT: Kan de gebruikersfolder niet vinden!!",
	'conf_userfolder_notset_msg%s' =>
		"Je hebt de 'base_path' instelling veranderd in ecc_general.ini. Deze folder is nu niet gemaakt.\n\nZal ik de folder\n\n%s\n\n aanmaken voor je?\n\nAls je een andere folder wilt gebruiken, selecteer dan NEE en gebruik\n'opties'->'configuratie'\nom je gebruikers-folder te configureren!",
	'conf_userfolder_error_readonly_title' =>
		"FOUT: Kan de folder niet aanmaken!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"De folder %s kan niet aangemaakt worden omdat je een 'alleen-lezen' (CD?)\n\nAls je een andere folder wilt selecteren klik dan op 'OK' en kies \n'opties'->'configuratie'\nom je gebruikers-folder te configureren!",
	'conf_userfolder_created_title' =>
		"gebruikersfolder aangemaakt!",
	'conf_userfolder_created_msg%s%s' =>
		"De subfolders\n\n%s\n\nzijn aangemaakt in je geselecteerde gebruikersfolder\n\n%s",
	'conf_ecc_save_title' =>
		"emuControlCenter GLOBAL-INI bijwerken?",
	'conf_ecc_save_msg' =>
		"This will write your changes settings to the ecc_global.ini\n\nThis will also create the selected user-folder and all needed subfolders\n\nDo you really want to do this?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Select the Folder for your User-Data",
	'fav_remove_all_title' =>
		"Remove all bookmarks?",
	'fav_remove_all_msg' =>
		"Do you really want to remove ALL BOOKMARKS?",
	'maint_empty_history_title' =>
		'Reset ecc history.ini?',
	'maint_empty_history_msg' =>
		'This will empty the ecc history.ini file. This files stores your selections in ecc frontend like options (eg. Hide duplicate roms) and selected paths! Should i reset this file?',
	'sys_dialog_info_miss_title' =>
		"?? TITLE MISSING ??",
	'sys_dialog_info_miss_msg' =>
		"?? MESSAGE MISSING ??",
	'sys_filechooser_miss_title' =>
		"?? TITLE MISSING ??",
	'status_dialog_close' =>
		"\n\nShould i close the status detail area?",
	'status_process_running_title' =>
		"Process running",
	'status_process_running_msg' =>
		"Another process is running\nYou can only start one process like parsing/import/export! Please wait until the current running process is done!",
	'meta_rating_add_error_msg' =>
		"You can only rate a rom with metadata.\n\nPlease use EDIT and create theses metainformations!",
	'maint_unset_ratings_title' =>
		"Unset ratings for platform?",
	'maint_unset_ratings_msg' =>
		"This will reset all ratings in the database... should i do it?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistics:\n\n%s added\n%s allready inplace\n%s errors\n\n%s Datasets processed!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatabase:\n\nTo support the emuControlCenter community, you can add your modified metadata (title, category, languages aso.) into the ECCDB (Internet Database).\n\nThis works like the well known CDDB for CD-Tracks.\n\nIf you confirm this, ecc will automaticly transfer you data into the eccdb!\n\nYou have to be connected to the internet to add your content!!!\n\nAfter 10 processed Metadatasets, you have to confirm to add more!",
	'eccdb_error' =>
		"eccdb - Errors:\n\nMaybe you are not connected to the internet... only with an active internet connection, you can add data into the eccdb!",
	'eccdb_no_data' =>
		"eccdb - No data to add found:\n\nYou have to edit some of your metadata to add this into the eccdb. Use the edit button and try again!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Deze optie zal zoeken naar dubbele ROMS in je database, en zal de gevonden ROMS wegschrijven naar een bestand\n\nJe zal het LOG-betsand vinden in de 'ecc-logs' folder!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Alle plaatjes verwijderen?",
	'img_remove_all_msg%s' =>
		"Dit zal alle plaatjes verwijderen vor het geselecteerde spel!\n\nZouden de plaatjes voor\n\n%s?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"bevestig",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"Dit bestand echt verwerken?",
	'parse_big_file_found_msg%s%s' =>
		"GROOT BESTAND GEVONDEN!!!\n\nGevonden spel\n\nNaam: %s\nGrootte: %s\n\nis erg groot. Dit kan enige tijd in beslag nemen zonder dat ECC lijkt te reageren.\n\nWil je dit bestand verwerken?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Favoriet opgeslagen",
	'bookmark_added_msg' =>
		"Favoriet is toegevoegd!",
	'bookmark_removed_single_title' =>
		"Favoriet verwijderd",
	'bookmark_removed_single_msg' =>
		"Deze favoriet is verwijderd!",
	'bookmark_removed_all_title' =>
		"Alle favorieten verwijderd",
	'bookmark_removed_all_msg' =>
		"Alle favorieten zijn verwijderd!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Vernieuwen DAT-bestand via internet",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Wil je het platform bijwerkenn\n\n%s\n\nmet de gegevens van de online emuControlCenter romDB?\n\nEen internet connectie zal gelegd worden om de gegevnes te improteren!",

	'eccdb_webservice_get_datfile_error_title' =>
		"Kan het DAT-bestand niet importeren",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Je moet verbonden zijn met het internet, verbind aub en prober opnieuw!",

	'romparser_fileext_problem_title%s' =>
		"EXTENSIE %s PROBLEEM GEVONDEN",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"Er zijn meerdere platformen gevonden met dezelfde extensie %s voor ROMS!\n\n%s\nWeet je zeker dat alleen %s ROMS zich in de geselecteerde media folder bevinden%s\n\n<b>OK</b>: Zoek naar %s in deze folder / platform!\n\n<b>ANNULEREN</b>: Sla deze extensie %s over voor deze folder / platform!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Zoeken naar dubbele ROMS",
	'rom_dup_remove_done_title_preview' => 
		"Zoeken voltooid",
	'rom_dup_remove_done_msg_preview' =>
		"Voor details bekijk het status gebied!",
	'metaRemoveSingleTitle' =>
		"Verwijder metadata voor ROM",
	'metaRemoveSingleMsg' =>
		"Wil je de metadata voor deze ROM verwijderen?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Selecteer een CRL MAME DAT bestand!\n",
	'importDatCMConfirmTitle' =>
		"Importeer een CRL MAME DAT bestand!",
	'importDatCMConfirmMsg%s%s%s' =>
		"Wil je echt data importeren voor platform\n\n%s (%s)\n\nvan DAT-bestand\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"ROM controle informatie bijwerken",
	'romAuditReparseMsg%s' =>
		"Dit zal de opgeslagen informatie bijwerken, zoals de complete status van een ROM bestaande uit meerdere bestanden\n\nDeze gegevens bijwerken?",
	'romAuditInfoNotPossibelTitle' =>
		"Geen ROM controle informatie aanwezig",
	'romAuditInfoNotPossibelMsg' =>
		"Controle inforamtie is alleen aanwezig voor multirom platformen zoals de Arcade platformen!",

	'romReparseAllTitle' =>
		"Herverwerk ROM folder",
	'romReparseAllMsg%s' =>
		"Zoek naar nieuwe roms voor de geselecteerde platform(en)?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Deselecteer deze extensies",
	'parserUnsetExtMsg%s' =>
		"Omdat je '#All found' hebt geselecteerd, moet ECC alle dubbele extensies negeren van zoeken om verkeerde tenaamstellingen in de dataabse te voorkomen!\n\nemuControlCenter zoekt niet naar: %s\n\nSelecteer de juiste platform om deze extensies te ververken!\n\n",

	'stateLabelDatExport%s%s' =>
		"Exporteer %s DAT-bestand voor %s",
	'stateLabelDatImport%s' =>
		"Importeer DAT-bestand voor %s",

	'stateLabelOptimizeDB' =>
		"Optimaliseer database",
	'stateLabelVacuumDB' =>
		"Vacuum database",
	'stateLabelRemoveDupRoms' =>
		"Verwijder dubbele ROMS",
	'stateLabelRomDBAdd' =>
		"Toevoegen van informatie in romDB",
	'stateLabelParseRomsFor%s' =>
		"ROMS verwerken voor %s",
	'stateLabelConvertOldImages' =>
		"Bezig met het omzetten van plaatjes...",

	'processCancelConfirmTitle' =>
		"Annuleren huidig proces?",
	'processCancelConfirmMsg' =>
		"Wil je deze lopende proces echt afbreken?",
	'processDoneTitle' =>
		"Proces voltooid!",
	'processDoneMsg' =>
		"Het proces is voltooid!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"Het herstel bestand (XML) met je gebruikers-gegevens is aangemaakt in je ecc-user/#_GLOBAL/ folder\n\n%s\n\nWil je de ge-exporteerde gegevens (XML) nu bekijken met je internet browser?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Wil je werkelijk deze uitvoering starten in de achtergrond?",
	'executePostShutdownTaskMessage%s' =>
		"\nTaak: <b>%s</b>\n\nWil je echt deze langdurige taak uitvoeren in de achtergrond?",
	'postShutdownTaskTitle' =>
		"Uitvoeren geselecteede taak",
	'postShutdownTaskMessage' =>
		"Je hebt een taak geselecteerd waarbij ECC gesloten moet worden.\n\nNa deze taak, <b>Zal emuControlCenter zichzelf weer automatisch starten!</b>\n\nDit kan enkele seconden, minuten en soms uren duren! Dit venster kan dan 'bevriezen'! Dus geen paniek! :-)\n\n<b>wachten AUB!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"ROM bestand niet gevonden...",
	'startRomFileNotAvailableMessage' =>
		"Het lijkt erop dat je deze ROM niet hebt!\n\nJe kan het opnieuw proberen wanneer je de mode 'Alles wat ik heb' geselecteerd hebt :-)",
	'startRomWrongFilePathTitle' =>
		"ROM is opgeslagen in de database, maar het bestand is niet gevonden",
	'startRomWrongFilePathMessage' =>
		"Misschien heb je je ROMS verplaatst naar een andere lokatie of misschien heb je ze verwijderdt?\n\nGebruik de optie 'ROMS' -> 'Optimaliseer ROMS' om je database op te schonen!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Download plaatjes",
	'waitForImageInjectMessage' =>
		"Dit kan even duren... Als er plaatjes zijn gevonden, zal dit venster sluiten en kun je de plaatjes zien in de lijst!\n\nAls er geen plaatjes zijn gevonden, zal dit venster sluiten en de lijst zal niet bijgewerkt worden! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Werkelijk bestanden kopieren/verplaatsen met huidige zoekresultaten?",
	'copy_by_search_msg_waring%s%s%s' =>
		"Deze optie zal alle spellen kopieren/verplaatsen met de huidige zoekresultaten (Let wel: Als je niet hebt gezocht, dat zijn alle bestanden geselecteerd!)\n\nJe kan de bestamming opgeven in het volgende schermw.\n\nEr zijn <b>%s spellen gevonden</b> in je zoekresultaten\n\n<b>%s ingepakte spellen</b> zijn overgeslagen!\n\nWil je deze <b>%s</b> spellen echt kopieren/verplaatsen naar een andere locatie?",
	'copy_by_search_msg_error_noplatform' =>
		"Je moet een platform selecteren om deze funtie te gebruiken. Het is niet mogelijk deze functie te gebruiken voor ALL FOUND!",
	'copy_by_search_msg_error_notfound%s' =>
		"Er zijn geen geldige spellen gevonden in je zoekresultaten. <b>%s ingepakte spellen</b> zijn overgeslagen.",
	'searchTab' =>
		"Zoekresultaat",
	'searchDescription' =>
		"Hier kun je spellen kopieren of verplaatsen vanuit hun huidige locatie naar een andere locatie\n<b>De bron zijn je huidige zoekresultaten</b>\nAls je verplaatst, dan zijn ook de paden aangepast in de database! Opruimen doormiddel van checksum verwijderdt bestanden die 100% hetzelfde zijn!",
	'searchHeadlineMain' =>
		"Introductie",
	'searchHeadlineOptionSameName' =>
		"Dezelfde naam",
	'searchRadioDuplicateAddNumber' =>
		"nummer toevoegen",
	'searchRadioDuplicateOverwrite' =>
		"overschrijven",
	'searchCheckCleanup' =>
		"Opruimen doormiddel van checksum",

);
?>