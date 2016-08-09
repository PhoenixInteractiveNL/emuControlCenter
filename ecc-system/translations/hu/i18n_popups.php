<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	hu (hungarian)
 * author:	Gruby & Delirious
 * date:	2009/03/20
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Média mappád helye!",
	'rom_add_parse_title%s' =>
		"Új Romok hozzáadása %s platformhoz",
	'rom_add_parse_msg%s%s' =>
		"Új Romok hozzáadása \n\n%s\n\nplatformhoz \n\n%smappából?",
	'rom_add_parse_done_title' =>
		"Elemzés kész",
	'rom_add_parse_done_msg%s' =>
		"Új elemzés \n\n%s\n\nROMokhoz kész!",
	'rom_remove_title%s' =>
		"DB törlés %s platformról",
	'rom_remove_msg%s' =>
		"Törölni akarod az adatbázisból a \n\"%s\"-Médiát?\n\nAz eljárás töröl minden fájl adatot a választott médiáról az ecc adatbázisból. Nem törli az adatfájl információkat vagy a médiát a merevlemezrõl.",
	'rom_remove_done_title' =>
		"DB törlés kész",
	'rom_remove_done_msg%s' =>
		"Minden %s adat törölve az ecc-adatabázisból",
	'rom_remove_single_title' =>
		"Töröljem a ROMot az adatabázisból?",
	'rom_remove_single_msg%s' =>
		"Töröljem\n\n%s\n\naz ecc adatbázisból?",
	'rom_remove_single_dupfound_title' =>
		"Dupla ROMokat találtam!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d Dupla ROMokat találtam\n\nTöröljek minden dupla romot\n\n%s platformból\n\n az ecc adatbázisból?\n\nTovábbi információkért nézd meg a Súgót!",
	'rom_optimize_title' =>
		"Játék adatbázis optimalizálása",
	'rom_optimize_msg' =>
		"Optimalizáljam a ROM fájljaidat az ecc-adatabázisban?\n\nOptimalizálni kell az adatbázist, ha töröltél fájlokat a merevlemezedrõl\naz ecc automatikusan megkeresi ezeket az adatabázis-bejegyzéseket és könyvjelzõket valamint törli ezeket az adatbázisból!\nEz az opció csak az adatbázist javítja.",
	'rom_optimize_done_title' =>
		"Optimalizálás kész!",
	'rom_optimize_done_msg%s' =>
		"Az adatabázis\n\n%s platformhoz\n\noptimalizálva!",
	'rom_dup_remove_title' =>
		"Töröljem a dupla romokat az ecc-adatabázisból?",
	'rom_dup_remove_msg%s' =>
		"Töröljem a\n\n%s dupla romokat\n\naz ecc-adatabázisból?\n\nEz a mûvelet csak az emuControlCenter adatbázisban tevékenykedik....\n\nNem törli a fájlokat a merevlemezedrõl!!!",
	'rom_dup_remove_done_title' =>
		"Törlés kész",
	'rom_dup_remove_done_msg%s' =>
		"Minden \n\n%s dupla rom\n\ntörölve az ecc-adatabázisból",
	'rom_reorg_nocat_title' =>
		"Nincsenek kategóriák!",
	'rom_reorg_nocat_msg%s' =>
		"Nem rendeltél kategóriát a te\n\n%s\n\nromjaidhoz! Használd a javítás funkciót a kategória hozzáadáshoz vagy importálj egy jó ecc adatfájlt!",
	'rom_reorg_title' =>
		"Átrendezzem a romokat a merevlemezeden?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------Az opció átrendezi a romokat a merevlemezeden!!! Elõszõr töröld a dupla romokat az ECC-DB-bõl !!!\nAz általad választott mód: #%s#\n------------------------------------------------------------------------------------------\n\nAkarod hogy átrendezzem a romjaid kategóriák szerint?\n\n%s\n\naz ecc rendezi a romokat a felhasználói-mappa\n\n%s/roms/organized/ helyére\n\nEllenõrizd van-e elég hely a merevlemezen\n\nEzt akarod a saját kockázatodra? :-)",
	'rom_reorg_done_title' =>
		"Átrendezés kész",
	'rom_reorg_done__msg%s' =>
		"Nézd meg a fájljaidat a\n\n%s mappában\n\nhogy ellenõrizd a másolást",
	'db_optimize_title' =>
		"Adatbázis rendszer optimalizálása",
	'db_optimize_msg' =>
		"Optimalizálod az adatbázist?\nEz csökkenti az emuControlCenter-Adatabázis méretét!\n\nA mûvelet lefagyasztja az alkalmazást pár másodpercre - kis türelmet kérek! :-)",
	'db_optimize_done_title' =>
		"Adatabázis optimalizálva!",
	'db_optimize_done_msg' =>
		"Az ecc-adatabázisod most optimalizált!",
	'export_esearch_error_title' =>
		"Nincs eSearch opció kiválasztva",
	'export_esearch_error_msg' =>
		"Az eSearch (bõvített keresés) export funkciót használod. Ez a funkció csak a keresési találatokat exportálja amit a fõablakban nézhetsz meg!",
	'dat_export_filechooser_title%s' =>
		"Válassz mappát a(z) %s adatfájl mentéséhez!",	
	'dat_export_title%s' =>
		"%s adatfájl export",
	'dat_export_msg%s%s%s' =>
		"Exportáljam a %s adatfájlt\n\n%s\n\nplatformról, a\n\n%s mappába?",
	'dat_export_esearch_msg_add' =>
		"\n\naz ecc a te eSearch beállításaidat használja az exporthoz!",
	'dat_export_done_title' =>
		"Export kész",
	'dat_export_done_msg%s%s%s' =>
		"%s adatfile export\n\n%s\n\nplatformról, a\n\n%s mappába\n\nelkészült!",
	'dat_import_filechooser_title%s' =>
		"Import: Válassz egy %s adatfájlt!",
	'rom_import_backup_title' =>
		"Készítsek biztonsági mentést?",
	'rom_import_backup_msg%s%s' =>
		"Készítsek egy biztonsági mentést a felhasználói mappádba\n\n%s (%s) platformról\n\nmielõtt importálod az új meta-adatot?",
	'rom_import_title' =>
		"Importáljam az adatfájlt?",
	'rom_import_msg%s%s%s' =>
		"Biztosan importálod az adatokat\n\n%s (%s)platformhoz\n\n a\n\n%s adatfájlból?",
	'rom_import_done_title' =>
		"Import kész!",
	'rom_import_done_msg%s' =>
		"Adatfájl import\n\n%s\n\nadatfájlból elkészült!",
	'dat_clear_title%s' =>
		"DB törlés %s platformról",
	'dat_clear_msg%s%s' =>
		"Törölni akarod az összes meta-információt a\n\n%s (%s) adatairól?\n\nEz törli a platform összes meta-információit pl. kategória, státusz, nyelvek stb. az ecc-adatabázisból!. A következõ lépésben készíthetsz biztonsági mentést ezekrõl az információkról. (Automatikusan mentõdik a felhasználói-mappádba!)\n\nVégül optimalizáld az adatbázist!",
	'dat_clear_backup_title%s' =>
		"%s biztonsági mentése",
	'dat_clear_backup_msg%s%s' =>
		"Készítsek biztonsági mentést\n\n%s (%s) platformról?",
	'dat_clear_done_title%s' =>
		"DB törlés kész",
	'dat_clear_done_msg%s%s' =>
		"Minden meta-információ\n\n%s (%s) platformról\n\ntörölve az ecc-adatabázisból!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n ecc menti az adataidat a %s-felhasználói-mappába",
	'emu_miss_title' =>
		"Hiba - Emulátor nem található!",
	'emu_miss_notfound_msg%s' =>
		"A beállított emulátor nem található. Zöld jelzi az érvényes, piros az érvénytelen emulátor helymeghatározást.",
	'emu_miss_notset_msg' =>
		"Nem rendeltél megfelelõ emulátort ehhez a platformhoz",
	'emu_miss_dir_msg%s' =>
		"A hozzárendelt elérés egy mappa!!!!",
	'img_overwrite_title' =>
		"Felülírjam a képet?",
	'img_overwrite_msg%s%s' =>
		"A\n\n%s kép\n\nmár létezik\n\nBiztosan felülírod a\n\n%s képet?",	
	'img_remove_title' =>
		"Töröljem a képet?",
	'img_remove_msg%s' =>
		"Biztosan töröljem a %s képet?",
	'img_remove_error_title' =>
		"Hiba - Nem tudom törölni a képet!",
	'img_remove_error_msg%s' =>
		"%s kép nincs törölve!",
	'conf_platform_update_title' =>
		"Platform ini fájl frissítése?",
	'conf_platform_update_msg%s' =>
		"Biztosan frissítsem a %s platform ini fájlt?",
	'conf_platform_emu_filechooser_title%s' =>
		"Válassz emulátort a '%s' kiterjesztéshez",
	'conf_userfolder_notset_title' =>
		"HIBA: Felhasználói mappa nem található!!!",
	'conf_userfolder_notset_msg%s' =>
		"Eltérõ a base_path bejegyzés az ecc_general.ini fájlodban. A mappa most nem készült el.\n\nKészítsem el a\n\n%s mappát\n\nneked?\n\nHa másik mappát akarsz klikkelj a Nem-re és használd a\n'Beállítások'->'konfigurációk'\nmenüpontot a felhasználói mappád választásához!",
	'conf_userfolder_error_readonly_title' =>
		"HIBA: Mappa nem készült el!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"A %s mappa nem készült el mert csak olvasható a médium (CD?)\n\nVálassz egy másik mappát, klikk az OK-ra és válaszd az \n'opciók'->'beállítások' menüpontot\na felhasználói mappád kiválasztásához!",
	'conf_userfolder_created_title' =>
		"Felhasználói mappa elkészült!",
	'conf_userfolder_created_msg%s%s' =>
		"Az alkönyvtárak\n\n%s\n\na választott\n\n%s felhasználói mappádba kerültek",
	'conf_ecc_save_title' =>
		"Frissíted az emuControlCenter GLOBAL-INI fájlt?",
	'conf_ecc_save_msg' =>
		"Beírja a változtatott beállításaid az ecc_global.ini fájlba\n\nValamint elkészíti a kiválasztott felhasználói-mappádat és minden szükséges alkönyvtárt\n\nBiztos hogy ezt akarod?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Válassz mappát a felhasználói adataidnak",
	'fav_remove_all_title' =>
		"Töröljek minden könyvjelzõt?",
	'fav_remove_all_msg' =>
		"Biztosan töröljem az összes könyvjelzõt?",
	'maint_empty_history_title' =>
		'Töröljem az ecc history.ini fájl tartalmát?',
	'maint_empty_history_msg' =>
		'Ez kiüríti az ecc history.ini fájl tartalmát. Ezek tárolják a beállításaidat az ecc frontend opciókról (pl. Dupla romok elrejtése) és a választott eléréseket! Kiürítsem a fájlt?',
	'sys_dialog_info_miss_title' =>
		"?? Hiányzó cím ??",
	'sys_dialog_info_miss_msg' =>
		"?? Üzenet hiányzik ??",
	'sys_filechooser_miss_title' =>
		"?? Hiányzó cím ??",
	'status_dialog_close' =>
		"\n\nBezárjam az állapot részletezõ ablakot?",
	'status_process_running_title' =>
		"Folyamat fut",
	'status_process_running_msg' =>
		"Másik folyamat is fut\nEgyszerre csak egy folyamatot indíts pl. import/export! Várj amíg a futó folyamat elkészül!",
	'meta_rating_add_error_msg' =>
		"Csak meta-adattal értékelheted a romot.\n\nHasználd a Javítást és készítsd el a meta-információkat!",
	'maint_unset_ratings_title' =>
		"Töröljem az értékeléseket a platformhoz?",
	'maint_unset_ratings_msg' =>
		"Ez töröl minden értékelést az adatbázisból... ezt akarod?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statisztikák:\n\n%s hozzáadva\n%s már a helyén\n%s hibák\n\n%s Adatkészlet feldolgozva!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Meta-adatbázis:\n\nHogy segítsd az emuControlCenter közösségét, add hozzá a te meta-adataidat (cím, kategória, nyelvek stb.) az ECCDB (Internet Adatabázishoz).\n\nÚgy mûködik mint az ismert CDDB a CD-számokkal.\n\nHa beleegyezel, az ecc automatikusan elküldi az adataidat az eccdb-be!\n\nCsatlakozz az internethez a tartalmad hozzáadásához!!!\n\n10 elfogadott meta-adatkészlet után, hozzáadhatsz többet is!",
	'eccdb_error' =>
		"eccdb - Hibák:\n\nTalán nem csatlakoztál az internetre... csak aktív internet kapcsolat esetén adhatod az adataidat az eccdb-hez!",
	'eccdb_no_data' =>
		"Nem található hozzáadott eccdb adat:\n\nSzerkessz néhány saját meta-adatot hogy hozzáadhasd azokat az eccdbhez. Használd a javítás gombot és próbáld újra!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Ez az opció megkeresi a dupla romokat az adatbázisodban és naplózza a talált romokat\n\nA naplófájlt megtalálod az ecc-logs mappádban!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Töröljek minden képet?",
	'img_remove_all_msg%s' =>
		"Ez töröl minden képet a kiválasztott játékról!\n\nKellenek a képek\n\n%s játékhoz?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"megerõsít",
	/* 0.9.2 WIP 11 - Ez a rész valszeg változni fog !! most elég xarúl kezeli ezt a részt */
	'parse_big_file_found_title' =>
		"Biztosan ellenõrizzem a fájlt?",
	'parse_big_file_found_msg%s%s' =>
		"Nagy fájlt találtam!!!\n\nTalált játék\n\nNév: %s\nMéret: %s\n\nez igen nagy. Sok idõ szükséges a mûvelethez, az  emuControlCenter addig nem használható.\n\nEllenõrizzem a játékot?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Könyvjezõ mentve",
	'bookmark_added_msg' =>
		"A könyvjelzõ hozzáadva!",
	'bookmark_removed_single_title' =>
		"Könyvjelzõ törölve",
	'bookmark_removed_single_msg' =>
		"Ez a könyvjelzö törölve!",
	'bookmark_removed_all_title' =>
		"Minden könyvjelzõ törölve",
	'bookmark_removed_all_msg' =>
		"Minden könyvjelzõ törölve!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Adatfájl frissítése az internetrõl",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Akarod hogy frissítsem\n\n%s platformot\n\naz online emuControlCenter romDB-bõl?\n\nStabil internetkapcsolat szükséges az opcióhoz",

	'eccdb_webservice_get_datfile_error_title' =>
		"Adatfájl importálás sikertelen",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Kapcsolódnod kell az internethez. Kapcsolódj és próbáld meg újra!",

	'romparser_fileext_problem_title%s' =>
		"%s kiterjesztés problémát találtam",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter infó: több platform használja a %s kiterjesztést a rom kereséshez!\n\n%s\nBiztosan csak %s játékok vannak a választott %s mappában\n\n<b>OK</b>: %s keresése a mappában / platformhoz!\n\n<b>Mégse</b>: Kiterjesztés átugrása a %s mappában / platformhoz!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Dupla ROMok keresése",
	'rom_dup_remove_done_title_preview' => 
		"Keresés kész",
	'rom_dup_remove_done_msg_preview' =>
		"Nézd meg a állapot részletezõ ablakot!",
	'metaRemoveSingleTitle' =>
		"ROM meta-adat törlése",
	'metaRemoveSingleMsg' =>
		"Letörlöd a rom meta-adatait?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Válassz egy ClrMamePro adatfájlt!\n",
	'importDatCMConfirmTitle' =>
		"ClrMamePro adatfájl importálás",
	'importDatCMConfirmMsg%s%s%s' =>
		"Biztosan importálod az adatokat\n\n%s (%s)\n\nplatformhoz\n\n%s adatfájlból?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"ROM ellenõrzés infók frissítése",
	'romAuditReparseMsg%s' =>
		"Ez frissíti a tárolt információkat pl. komplett státusz egy többfájlos romról\n\nFrissítsem az adatot?",
	'romAuditInfoNotPossibelTitle' =>
		"Nincs ellenõrzési információ",
	'romAuditInfoNotPossibelMsg' =>
		"Ellenõrzési információk csak többromos platformokhoz elérhetõk, pl. Arcade platformok!",

	'romReparseAllTitle' =>
		"Rom mappa újraellenõrzése",
	'romReparseAllMsg%s' =>
		"Keressek új romokat a választott\n\n%s platformhoz?",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Ne alkalmazza ezeket a kiterjesztéseket",
	'parserUnsetExtMsg%s' =>
		"Azért mert az '#All found' platformot választottad, az ecc dupla kiterjesztéseket talált kereséskor. A hibás adatbázis  hozzáférés megelõzéséhez\n\naz emuControlCenter nem keresi ezt : %s\n\nVálassz egy létezõ platformot a kiterjesztés ellenõrzéséhez!\n\n",

	'stateLabelDatExport%s%s' =>
		"%s %s exportálása",
	'stateLabelDatImport%s' =>
		"%s adatfájl importálás",

	'stateLabelOptimizeDB' =>
		"Adatbázis optimalizálás",
	'stateLabelVacuumDB' =>
		"Adatbázis Vákum",
	'stateLabelRemoveDupRoms' =>
		"Dupla romok törlése",
	'stateLabelRomDBAdd' =>
		"Információk hozzáadása a romDB-hez",
	'stateLabelParseRomsFor%s' =>
		"%s romok ellenõrzése",
	'stateLabelConvertOldImages' =>
		"Most átalakítom a képeket...",

	'processCancelConfirmTitle' =>
		"Leállítsam a futó folyamatot?",
	'processCancelConfirmMsg' =>
		"Biztosan megszakítod a futó folyamatot?",
	'processDoneTitle' =>
		"Folyamat elvégezve!",
	'processDoneMsg' =>
		"A folyamat sikeresen elvégezve!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"A biztonsági XML-fájl mentés a felhasználói adataidról elkészült a te ecc-user/#_GLOBAL/ mappádban\n\n%s\n\nMegnézed az elkészült xml fájlt az xml böngészõddel?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Biztos futtatod a háttér alkalmazást?",
	'executePostShutdownTaskMessage%s' =>
		"\nTask: <b>%s</b>\n\nBiztos futtatod ezt a sokáig tartó folyamatot?",
	'postShutdownTaskTitle' =>
		"Választott alkalmazás futtatása",
	'postShutdownTaskMessage' =>
		"A választott alkalmazás csak akkor futtatható ha az emuControlCenter nem mûködik.\n\nHa végzett ez az alkalmazás, <b>az emuControlCenter automatikusan újraindul!</b>\n\nEz eltarthat néhány másodpercig, néhány percig, de néha órákig is! Ez az ablak lefagyhat! Ne aggódj! :-)\n\n<b>Várj türelmesen!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Romfájl nem található...",
	'startRomFileNotAvailableMessage' =>
		"Úgy néz ki nincs meg neked ez a rom!\n\nTalán próbáld újra 'Mind (megvan)' nézetben :-)",
	'startRomWrongFilePathTitle' =>
		"Rom szerepel az adatabázisban, de a fájl nem található",
	'startRomWrongFilePathMessage' =>
		"Talán áthelyezted a romjaid más helyre vagy törölted õket?\n\nVálaszd a 'Romok' -> 'Romok optimalizálása' opciót az adatbázisod megtisztításához!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Képek letöltése",
	'waitForImageInjectMessage' =>
		"A folyamat rövid ideig tart. Ha képeket talál, az ablak automatikusan bezárul és megnézheted a képeket a listában!\n\nHa nem talál képeket, az ablak bázárul és a fõ lista nem változik! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Biztos másoljam/mozgassam a keresõben talált fájlokat?",
	'copy_by_search_msg_waring%s%s%s' =>
		"Az opció átmásol/átmozgat minden a keresési jelentésedben talált játékot (Vigyázz ha nem akarsz minden keresett fájlt kiválasztani!)\n\nKiválaszthatod a célt a következõ ablakban.\n\nTalálva <b>%s játék</b> a keresésedben\n\n<b>%s tömörített játékok</b> átugorva!\n\nBiztosan átmásolod/áthelyezed ezeket a <b>%s</b> játékokat másik helyre?",
	'copy_by_search_msg_error_noplatform' =>
		"Válassz egy platformot a funkcióhoz. A funkció nem használható ALL FOUND platformhoz!",
	'copy_by_search_msg_error_notfound%s' =>
		"Nem található megfelelõ játék a keresési találataidban. <b>%s tömörített játék</b> kihagyva.",
	'searchTab' =>
		"Keresési találatok",
	'searchDescription' =>
		"Itt átmásolod vagy áthelyezed a fájlokat a forrás mappából a választottba.\n<b>A Forrás a jelenlegi keresési listád.</b>\nHa átmozgatod, a mappa is frissítve lesz az adatabázisban! Megtisztítja az ellenõrzõösszeget és törli a 100%-ban egyforma fájlokat!",
	'searchHeadlineMain' =>
		"Bemutatás",
	'searchHeadlineOptionSameName' =>
		"egyforma név",
	'searchRadioDuplicateAddNumber' =>
		"szám hozzáadása",
	'searchRadioDuplicateOverwrite' =>
		"felülír",
	'searchCheckCleanup' =>
		"Ellenõrzõösszeg tisztítása",

);
?>