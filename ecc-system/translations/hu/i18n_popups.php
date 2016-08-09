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
		"%s: M�dia mapp�d helye!",
	'rom_add_parse_title%s' =>
		"�j Romok hozz�ad�sa %s platformhoz",
	'rom_add_parse_msg%s%s' =>
		"�j Romok hozz�ad�sa \n\n%s\n\nplatformhoz \n\n%smapp�b�l?",
	'rom_add_parse_done_title' =>
		"Elemz�s k�sz",
	'rom_add_parse_done_msg%s' =>
		"�j elemz�s \n\n%s\n\nROMokhoz k�sz!",
	'rom_remove_title%s' =>
		"DB t�rl�s %s platformr�l",
	'rom_remove_msg%s' =>
		"T�r�lni akarod az adatb�zisb�l a \n\"%s\"-M�di�t?\n\nAz elj�r�s t�r�l minden f�jl adatot a v�lasztott m�di�r�l az ecc adatb�zisb�l. Nem t�rli az adatf�jl inform�ci�kat vagy a m�di�t a merevlemezr�l.",
	'rom_remove_done_title' =>
		"DB t�rl�s k�sz",
	'rom_remove_done_msg%s' =>
		"Minden %s adat t�r�lve az ecc-adatab�zisb�l",
	'rom_remove_single_title' =>
		"T�r�ljem a ROMot az adatab�zisb�l?",
	'rom_remove_single_msg%s' =>
		"T�r�ljem\n\n%s\n\naz ecc adatb�zisb�l?",
	'rom_remove_single_dupfound_title' =>
		"Dupla ROMokat tal�ltam!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d Dupla ROMokat tal�ltam\n\nT�r�ljek minden dupla romot\n\n%s platformb�l\n\n az ecc adatb�zisb�l?\n\nTov�bbi inform�ci�k�rt n�zd meg a S�g�t!",
	'rom_optimize_title' =>
		"J�t�k adatb�zis optimaliz�l�sa",
	'rom_optimize_msg' =>
		"Optimaliz�ljam a ROM f�jljaidat az ecc-adatab�zisban?\n\nOptimaliz�lni kell az adatb�zist, ha t�r�lt�l f�jlokat a merevlemezedr�l\naz ecc automatikusan megkeresi ezeket az adatab�zis-bejegyz�seket �s k�nyvjelz�ket valamint t�rli ezeket az adatb�zisb�l!\nEz az opci� csak az adatb�zist jav�tja.",
	'rom_optimize_done_title' =>
		"Optimaliz�l�s k�sz!",
	'rom_optimize_done_msg%s' =>
		"Az adatab�zis\n\n%s platformhoz\n\noptimaliz�lva!",
	'rom_dup_remove_title' =>
		"T�r�ljem a dupla romokat az ecc-adatab�zisb�l?",
	'rom_dup_remove_msg%s' =>
		"T�r�ljem a\n\n%s dupla romokat\n\naz ecc-adatab�zisb�l?\n\nEz a m�velet csak az emuControlCenter adatb�zisban tev�kenykedik....\n\nNem t�rli a f�jlokat a merevlemezedr�l!!!",
	'rom_dup_remove_done_title' =>
		"T�rl�s k�sz",
	'rom_dup_remove_done_msg%s' =>
		"Minden \n\n%s dupla rom\n\nt�r�lve az ecc-adatab�zisb�l",
	'rom_reorg_nocat_title' =>
		"Nincsenek kateg�ri�k!",
	'rom_reorg_nocat_msg%s' =>
		"Nem rendelt�l kateg�ri�t a te\n\n%s\n\nromjaidhoz! Haszn�ld a jav�t�s funkci�t a kateg�ria hozz�ad�shoz vagy import�lj egy j� ecc adatf�jlt!",
	'rom_reorg_title' =>
		"�trendezzem a romokat a merevlemezeden?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------Az opci� �trendezi a romokat a merevlemezeden!!! El�sz�r t�r�ld a dupla romokat az ECC-DB-b�l !!!\nAz �ltalad v�lasztott m�d: #%s#\n------------------------------------------------------------------------------------------\n\nAkarod hogy �trendezzem a romjaid kateg�ri�k szerint?\n\n%s\n\naz ecc rendezi a romokat a felhaszn�l�i-mappa\n\n%s/roms/organized/ hely�re\n\nEllen�rizd van-e el�g hely a merevlemezen\n\nEzt akarod a saj�t kock�zatodra? :-)",
	'rom_reorg_done_title' =>
		"�trendez�s k�sz",
	'rom_reorg_done__msg%s' =>
		"N�zd meg a f�jljaidat a\n\n%s mapp�ban\n\nhogy ellen�rizd a m�sol�st",
	'db_optimize_title' =>
		"Adatb�zis rendszer optimaliz�l�sa",
	'db_optimize_msg' =>
		"Optimaliz�lod az adatb�zist?\nEz cs�kkenti az emuControlCenter-Adatab�zis m�ret�t!\n\nA m�velet lefagyasztja az alkalmaz�st p�r m�sodpercre - kis t�relmet k�rek! :-)",
	'db_optimize_done_title' =>
		"Adatab�zis optimaliz�lva!",
	'db_optimize_done_msg' =>
		"Az ecc-adatab�zisod most optimaliz�lt!",
	'export_esearch_error_title' =>
		"Nincs eSearch opci� kiv�lasztva",
	'export_esearch_error_msg' =>
		"Az eSearch (b�v�tett keres�s) export funkci�t haszn�lod. Ez a funkci� csak a keres�si tal�latokat export�lja amit a f�ablakban n�zhetsz meg!",
	'dat_export_filechooser_title%s' =>
		"V�lassz mapp�t a(z) %s adatf�jl ment�s�hez!",	
	'dat_export_title%s' =>
		"%s adatf�jl export",
	'dat_export_msg%s%s%s' =>
		"Export�ljam a %s adatf�jlt\n\n%s\n\nplatformr�l, a\n\n%s mapp�ba?",
	'dat_export_esearch_msg_add' =>
		"\n\naz ecc a te eSearch be�ll�t�saidat haszn�lja az exporthoz!",
	'dat_export_done_title' =>
		"Export k�sz",
	'dat_export_done_msg%s%s%s' =>
		"%s adatfile export\n\n%s\n\nplatformr�l, a\n\n%s mapp�ba\n\nelk�sz�lt!",
	'dat_import_filechooser_title%s' =>
		"Import: V�lassz egy %s adatf�jlt!",
	'rom_import_backup_title' =>
		"K�sz�tsek biztons�gi ment�st?",
	'rom_import_backup_msg%s%s' =>
		"K�sz�tsek egy biztons�gi ment�st a felhaszn�l�i mapp�dba\n\n%s (%s) platformr�l\n\nmiel�tt import�lod az �j meta-adatot?",
	'rom_import_title' =>
		"Import�ljam az adatf�jlt?",
	'rom_import_msg%s%s%s' =>
		"Biztosan import�lod az adatokat\n\n%s (%s)platformhoz\n\n a\n\n%s adatf�jlb�l?",
	'rom_import_done_title' =>
		"Import k�sz!",
	'rom_import_done_msg%s' =>
		"Adatf�jl import\n\n%s\n\nadatf�jlb�l elk�sz�lt!",
	'dat_clear_title%s' =>
		"DB t�rl�s %s platformr�l",
	'dat_clear_msg%s%s' =>
		"T�r�lni akarod az �sszes meta-inform�ci�t a\n\n%s (%s) adatair�l?\n\nEz t�rli a platform �sszes meta-inform�ci�it pl. kateg�ria, st�tusz, nyelvek stb. az ecc-adatab�zisb�l!. A k�vetkez� l�p�sben k�sz�thetsz biztons�gi ment�st ezekr�l az inform�ci�kr�l. (Automatikusan ment�dik a felhaszn�l�i-mapp�dba!)\n\nV�g�l optimaliz�ld az adatb�zist!",
	'dat_clear_backup_title%s' =>
		"%s biztons�gi ment�se",
	'dat_clear_backup_msg%s%s' =>
		"K�sz�tsek biztons�gi ment�st\n\n%s (%s) platformr�l?",
	'dat_clear_done_title%s' =>
		"DB t�rl�s k�sz",
	'dat_clear_done_msg%s%s' =>
		"Minden meta-inform�ci�\n\n%s (%s) platformr�l\n\nt�r�lve az ecc-adatab�zisb�l!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n ecc menti az adataidat a %s-felhaszn�l�i-mapp�ba",
	'emu_miss_title' =>
		"Hiba - Emul�tor nem tal�lhat�!",
	'emu_miss_notfound_msg%s' =>
		"A be�ll�tott emul�tor nem tal�lhat�. Z�ld jelzi az �rv�nyes, piros az �rv�nytelen emul�tor helymeghat�roz�st.",
	'emu_miss_notset_msg' =>
		"Nem rendelt�l megfelel� emul�tort ehhez a platformhoz",
	'emu_miss_dir_msg%s' =>
		"A hozz�rendelt el�r�s egy mappa!!!!",
	'img_overwrite_title' =>
		"Fel�l�rjam a k�pet?",
	'img_overwrite_msg%s%s' =>
		"A\n\n%s k�p\n\nm�r l�tezik\n\nBiztosan fel�l�rod a\n\n%s k�pet?",	
	'img_remove_title' =>
		"T�r�ljem a k�pet?",
	'img_remove_msg%s' =>
		"Biztosan t�r�ljem a %s k�pet?",
	'img_remove_error_title' =>
		"Hiba - Nem tudom t�r�lni a k�pet!",
	'img_remove_error_msg%s' =>
		"%s k�p nincs t�r�lve!",
	'conf_platform_update_title' =>
		"Platform ini f�jl friss�t�se?",
	'conf_platform_update_msg%s' =>
		"Biztosan friss�tsem a %s platform ini f�jlt?",
	'conf_platform_emu_filechooser_title%s' =>
		"V�lassz emul�tort a '%s' kiterjeszt�shez",
	'conf_userfolder_notset_title' =>
		"HIBA: Felhaszn�l�i mappa nem tal�lhat�!!!",
	'conf_userfolder_notset_msg%s' =>
		"Elt�r� a base_path bejegyz�s az ecc_general.ini f�jlodban. A mappa most nem k�sz�lt el.\n\nK�sz�tsem el a\n\n%s mapp�t\n\nneked?\n\nHa m�sik mapp�t akarsz klikkelj a Nem-re �s haszn�ld a\n'Be�ll�t�sok'->'konfigur�ci�k'\nmen�pontot a felhaszn�l�i mapp�d v�laszt�s�hoz!",
	'conf_userfolder_error_readonly_title' =>
		"HIBA: Mappa nem k�sz�lt el!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"A %s mappa nem k�sz�lt el mert csak olvashat� a m�dium (CD?)\n\nV�lassz egy m�sik mapp�t, klikk az OK-ra �s v�laszd az \n'opci�k'->'be�ll�t�sok' men�pontot\na felhaszn�l�i mapp�d kiv�laszt�s�hoz!",
	'conf_userfolder_created_title' =>
		"Felhaszn�l�i mappa elk�sz�lt!",
	'conf_userfolder_created_msg%s%s' =>
		"Az alk�nyvt�rak\n\n%s\n\na v�lasztott\n\n%s felhaszn�l�i mapp�dba ker�ltek",
	'conf_ecc_save_title' =>
		"Friss�ted az emuControlCenter GLOBAL-INI f�jlt?",
	'conf_ecc_save_msg' =>
		"Be�rja a v�ltoztatott be�ll�t�said az ecc_global.ini f�jlba\n\nValamint elk�sz�ti a kiv�lasztott felhaszn�l�i-mapp�dat �s minden sz�ks�ges alk�nyvt�rt\n\nBiztos hogy ezt akarod?",
	'conf_ecc_userfolder_filechooser_title' =>
		"V�lassz mapp�t a felhaszn�l�i adataidnak",
	'fav_remove_all_title' =>
		"T�r�ljek minden k�nyvjelz�t?",
	'fav_remove_all_msg' =>
		"Biztosan t�r�ljem az �sszes k�nyvjelz�t?",
	'maint_empty_history_title' =>
		'T�r�ljem az ecc history.ini f�jl tartalm�t?',
	'maint_empty_history_msg' =>
		'Ez ki�r�ti az ecc history.ini f�jl tartalm�t. Ezek t�rolj�k a be�ll�t�saidat az ecc frontend opci�kr�l (pl. Dupla romok elrejt�se) �s a v�lasztott el�r�seket! Ki�r�tsem a f�jlt?',
	'sys_dialog_info_miss_title' =>
		"?? Hi�nyz� c�m ??",
	'sys_dialog_info_miss_msg' =>
		"?? �zenet hi�nyzik ??",
	'sys_filechooser_miss_title' =>
		"?? Hi�nyz� c�m ??",
	'status_dialog_close' =>
		"\n\nBez�rjam az �llapot r�szletez� ablakot?",
	'status_process_running_title' =>
		"Folyamat fut",
	'status_process_running_msg' =>
		"M�sik folyamat is fut\nEgyszerre csak egy folyamatot ind�ts pl. import/export! V�rj am�g a fut� folyamat elk�sz�l!",
	'meta_rating_add_error_msg' =>
		"Csak meta-adattal �rt�kelheted a romot.\n\nHaszn�ld a Jav�t�st �s k�sz�tsd el a meta-inform�ci�kat!",
	'maint_unset_ratings_title' =>
		"T�r�ljem az �rt�kel�seket a platformhoz?",
	'maint_unset_ratings_msg' =>
		"Ez t�r�l minden �rt�kel�st az adatb�zisb�l... ezt akarod?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statisztik�k:\n\n%s hozz�adva\n%s m�r a hely�n\n%s hib�k\n\n%s Adatk�szlet feldolgozva!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Meta-adatb�zis:\n\nHogy seg�tsd az emuControlCenter k�z�ss�g�t, add hozz� a te meta-adataidat (c�m, kateg�ria, nyelvek stb.) az ECCDB (Internet Adatab�zishoz).\n\n�gy m�k�dik mint az ismert CDDB a CD-sz�mokkal.\n\nHa beleegyezel, az ecc automatikusan elk�ldi az adataidat az eccdb-be!\n\nCsatlakozz az internethez a tartalmad hozz�ad�s�hoz!!!\n\n10 elfogadott meta-adatk�szlet ut�n, hozz�adhatsz t�bbet is!",
	'eccdb_error' =>
		"eccdb - Hib�k:\n\nTal�n nem csatlakozt�l az internetre... csak akt�v internet kapcsolat eset�n adhatod az adataidat az eccdb-hez!",
	'eccdb_no_data' =>
		"Nem tal�lhat� hozz�adott eccdb adat:\n\nSzerkessz n�h�ny saj�t meta-adatot hogy hozz�adhasd azokat az eccdbhez. Haszn�ld a jav�t�s gombot �s pr�b�ld �jra!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Ez az opci� megkeresi a dupla romokat az adatb�zisodban �s napl�zza a tal�lt romokat\n\nA napl�f�jlt megtal�lod az ecc-logs mapp�dban!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"T�r�ljek minden k�pet?",
	'img_remove_all_msg%s' =>
		"Ez t�r�l minden k�pet a kiv�lasztott j�t�kr�l!\n\nKellenek a k�pek\n\n%s j�t�khoz?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"meger�s�t",
	/* 0.9.2 WIP 11 - Ez a r�sz valszeg v�ltozni fog !! most el�g xar�l kezeli ezt a r�szt */
	'parse_big_file_found_title' =>
		"Biztosan ellen�rizzem a f�jlt?",
	'parse_big_file_found_msg%s%s' =>
		"Nagy f�jlt tal�ltam!!!\n\nTal�lt j�t�k\n\nN�v: %s\nM�ret: %s\n\nez igen nagy. Sok id� sz�ks�ges a m�velethez, az  emuControlCenter addig nem haszn�lhat�.\n\nEllen�rizzem a j�t�kot?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"K�nyvjez� mentve",
	'bookmark_added_msg' =>
		"A k�nyvjelz� hozz�adva!",
	'bookmark_removed_single_title' =>
		"K�nyvjelz� t�r�lve",
	'bookmark_removed_single_msg' =>
		"Ez a k�nyvjelz� t�r�lve!",
	'bookmark_removed_all_title' =>
		"Minden k�nyvjelz� t�r�lve",
	'bookmark_removed_all_msg' =>
		"Minden k�nyvjelz� t�r�lve!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Adatf�jl friss�t�se az internetr�l",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Akarod hogy friss�tsem\n\n%s platformot\n\naz online emuControlCenter romDB-b�l?\n\nStabil internetkapcsolat sz�ks�ges az opci�hoz",

	'eccdb_webservice_get_datfile_error_title' =>
		"Adatf�jl import�l�s sikertelen",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Kapcsol�dnod kell az internethez. Kapcsol�dj �s pr�b�ld meg �jra!",

	'romparser_fileext_problem_title%s' =>
		"%s kiterjeszt�s probl�m�t tal�ltam",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter inf�: t�bb platform haszn�lja a %s kiterjeszt�st a rom keres�shez!\n\n%s\nBiztosan csak %s j�t�kok vannak a v�lasztott %s mapp�ban\n\n<b>OK</b>: %s keres�se a mapp�ban / platformhoz!\n\n<b>M�gse</b>: Kiterjeszt�s �tugr�sa a %s mapp�ban / platformhoz!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Dupla ROMok keres�se",
	'rom_dup_remove_done_title_preview' => 
		"Keres�s k�sz",
	'rom_dup_remove_done_msg_preview' =>
		"N�zd meg a �llapot r�szletez� ablakot!",
	'metaRemoveSingleTitle' =>
		"ROM meta-adat t�rl�se",
	'metaRemoveSingleMsg' =>
		"Let�rl�d a rom meta-adatait?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"V�lassz egy ClrMamePro adatf�jlt!\n",
	'importDatCMConfirmTitle' =>
		"ClrMamePro adatf�jl import�l�s",
	'importDatCMConfirmMsg%s%s%s' =>
		"Biztosan import�lod az adatokat\n\n%s (%s)\n\nplatformhoz\n\n%s adatf�jlb�l?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"ROM ellen�rz�s inf�k friss�t�se",
	'romAuditReparseMsg%s' =>
		"Ez friss�ti a t�rolt inform�ci�kat pl. komplett st�tusz egy t�bbf�jlos romr�l\n\nFriss�tsem az adatot?",
	'romAuditInfoNotPossibelTitle' =>
		"Nincs ellen�rz�si inform�ci�",
	'romAuditInfoNotPossibelMsg' =>
		"Ellen�rz�si inform�ci�k csak t�bbromos platformokhoz el�rhet�k, pl. Arcade platformok!",

	'romReparseAllTitle' =>
		"Rom mappa �jraellen�rz�se",
	'romReparseAllMsg%s' =>
		"Keressek �j romokat a v�lasztott\n\n%s platformhoz?",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Ne alkalmazza ezeket a kiterjeszt�seket",
	'parserUnsetExtMsg%s' =>
		"Az�rt mert az '#All found' platformot v�lasztottad, az ecc dupla kiterjeszt�seket tal�lt keres�skor. A hib�s adatb�zis  hozz�f�r�s megel�z�s�hez\n\naz emuControlCenter nem keresi ezt : %s\n\nV�lassz egy l�tez� platformot a kiterjeszt�s ellen�rz�s�hez!\n\n",

	'stateLabelDatExport%s%s' =>
		"%s %s export�l�sa",
	'stateLabelDatImport%s' =>
		"%s adatf�jl import�l�s",

	'stateLabelOptimizeDB' =>
		"Adatb�zis optimaliz�l�s",
	'stateLabelVacuumDB' =>
		"Adatb�zis V�kum",
	'stateLabelRemoveDupRoms' =>
		"Dupla romok t�rl�se",
	'stateLabelRomDBAdd' =>
		"Inform�ci�k hozz�ad�sa a romDB-hez",
	'stateLabelParseRomsFor%s' =>
		"%s romok ellen�rz�se",
	'stateLabelConvertOldImages' =>
		"Most �talak�tom a k�peket...",

	'processCancelConfirmTitle' =>
		"Le�ll�tsam a fut� folyamatot?",
	'processCancelConfirmMsg' =>
		"Biztosan megszak�tod a fut� folyamatot?",
	'processDoneTitle' =>
		"Folyamat elv�gezve!",
	'processDoneMsg' =>
		"A folyamat sikeresen elv�gezve!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"A biztons�gi XML-f�jl ment�s a felhaszn�l�i adataidr�l elk�sz�lt a te ecc-user/#_GLOBAL/ mapp�dban\n\n%s\n\nMegn�zed az elk�sz�lt xml f�jlt az xml b�ng�sz�ddel?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Biztos futtatod a h�tt�r alkalmaz�st?",
	'executePostShutdownTaskMessage%s' =>
		"\nTask: <b>%s</b>\n\nBiztos futtatod ezt a sok�ig tart� folyamatot?",
	'postShutdownTaskTitle' =>
		"V�lasztott alkalmaz�s futtat�sa",
	'postShutdownTaskMessage' =>
		"A v�lasztott alkalmaz�s csak akkor futtathat� ha az emuControlCenter nem m�k�dik.\n\nHa v�gzett ez az alkalmaz�s, <b>az emuControlCenter automatikusan �jraindul!</b>\n\nEz eltarthat n�h�ny m�sodpercig, n�h�ny percig, de n�ha �r�kig is! Ez az ablak lefagyhat! Ne agg�dj! :-)\n\n<b>V�rj t�relmesen!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Romf�jl nem tal�lhat�...",
	'startRomFileNotAvailableMessage' =>
		"�gy n�z ki nincs meg neked ez a rom!\n\nTal�n pr�b�ld �jra 'Mind (megvan)' n�zetben :-)",
	'startRomWrongFilePathTitle' =>
		"Rom szerepel az adatab�zisban, de a f�jl nem tal�lhat�",
	'startRomWrongFilePathMessage' =>
		"Tal�n �thelyezted a romjaid m�s helyre vagy t�r�lted �ket?\n\nV�laszd a 'Romok' -> 'Romok optimaliz�l�sa' opci�t az adatb�zisod megtiszt�t�s�hoz!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"K�pek let�lt�se",
	'waitForImageInjectMessage' =>
		"A folyamat r�vid ideig tart. Ha k�peket tal�l, az ablak automatikusan bez�rul �s megn�zheted a k�peket a list�ban!\n\nHa nem tal�l k�peket, az ablak b�z�rul �s a f� lista nem v�ltozik! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Biztos m�soljam/mozgassam a keres�ben tal�lt f�jlokat?",
	'copy_by_search_msg_waring%s%s%s' =>
		"Az opci� �tm�sol/�tmozgat minden a keres�si jelent�sedben tal�lt j�t�kot (Vigy�zz ha nem akarsz minden keresett f�jlt kiv�lasztani!)\n\nKiv�laszthatod a c�lt a k�vetkez� ablakban.\n\nTal�lva <b>%s j�t�k</b> a keres�sedben\n\n<b>%s t�m�r�tett j�t�kok</b> �tugorva!\n\nBiztosan �tm�solod/�thelyezed ezeket a <b>%s</b> j�t�kokat m�sik helyre?",
	'copy_by_search_msg_error_noplatform' =>
		"V�lassz egy platformot a funkci�hoz. A funkci� nem haszn�lhat� ALL FOUND platformhoz!",
	'copy_by_search_msg_error_notfound%s' =>
		"Nem tal�lhat� megfelel� j�t�k a keres�si tal�lataidban. <b>%s t�m�r�tett j�t�k</b> kihagyva.",
	'searchTab' =>
		"Keres�si tal�latok",
	'searchDescription' =>
		"Itt �tm�solod vagy �thelyezed a f�jlokat a forr�s mapp�b�l a v�lasztottba.\n<b>A Forr�s a jelenlegi keres�si list�d.</b>\nHa �tmozgatod, a mappa is friss�tve lesz az adatab�zisban! Megtiszt�tja az ellen�rz��sszeget �s t�rli a 100%-ban egyforma f�jlokat!",
	'searchHeadlineMain' =>
		"Bemutat�s",
	'searchHeadlineOptionSameName' =>
		"egyforma n�v",
	'searchRadioDuplicateAddNumber' =>
		"sz�m hozz�ad�sa",
	'searchRadioDuplicateOverwrite' =>
		"fel�l�r",
	'searchCheckCleanup' =>
		"Ellen�rz��sszeg tiszt�t�sa",

);
?>