<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	hu (hungarian)
 * author:	Gruby & Delirious
 * date:	2012/07/22
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"Keres�s bekapcsol�sa az automata friss�t�shez a navig�ci�ban",
	'opt_hide_nav_null' =>
		"ROM n�lk�li platformok megjelen�t�se/rejt�se",
	'opt_hide_dup' =>
		"Dupla ROMok megjelen�t�se/rejt�se",
	'opt_hide_img' =>
		"K�pek megjelen�t�se/rejt�se",
	'search_field_select' =>
		"Hol akarsz keresni?",
	'search_operator' =>
		"V�lassz keres�si oper�tort. ([ = EGYENL�] [ | VAGY ] [ + �S])",
	'search_rating' =>
		"Csak azon romok megjelen�t�se, melyeknek �rt�kel�se egyezik vagy kisebb a v�lasztottn�l",
	'optvis_mainlistmode' =>
		"R�szletes �s listan�zet csere",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Klikk - �rt�kel�sed hozz�ad�sa a romhoz",
	'nbMediaInfoNoteEvent' =>
		"Rom notesz megtekint�se",
	'nbMediaInfoReviewEvent' =>
		"A j�t�k ismert� megtekint�se",
	'nbMediaInfoBookmarkEvent' =>
		"K�nyvjelz� hozz�ad�sa / t�rl�se",
	'nbMediaInfoAuditStateEvent' =>
		"Vizsg�lati �llapot t�bbf�jlos romokhoz",
	'nbMediaInfoMetaEvent' =>
		"Meta-inform�ci� szerkeszt�se ehhez a j�t�khoz",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Csak az els� lemez l�tszik",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Minden rom l�tszik",
	'optionContextOnlyDiskOne' =>
		"Csak az els� rom m�dia l�tszik",
	'optionContextOnlyDiskOnePlus' =>
		"Az els� rom m�dia �s az ismeretlen romok l�tszanak",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Ez hozz�adja a ROMokat a kiv�lasztott platformhoz!",
	'mTopRomOptimizeTooltip' =>
		"Ecc-Adatab�zis optimaliz�l�sa a kiv�lasztott platformhoz pl. ha mozgatt�l/t�r�lt�l f�jlokat a merevlemezeden",
	'mTopRomRemoveDupsTooltip' =>
		"Ez t�r�l minden duplik�lt romot az ecc adatb�zisb�l",
	'mTopRomRemoveRomsTooltip' =>
		"A kiv�lasztott platform �sszes romj�t t�rli az ecc-adatb�zisb�l",		
	'mTopDatImportRcTooltip' =>
		"You can import Romcenter Datfiles (*.dat) into ecc. You have to select the right platform! RC-Datfiles contain the filename, checksum and metainfos assigned to the filename. emuControlCenter will strip this informations and automaticlly create ecc-metadata!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Change the emulator assigned to the selected platform",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Import emuControlCenter Datfiles (*.ecc) into ecc. If you have selected a platform, only roms for this platform will be imported! ecc-datfile-format has extended metainformations like categories, developer, state, languages aso.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Import CLR MAME Datfiles (*.dat) into ecc.",
	'mTopDatImportRcTooltip' =>
		"Import Romcenter Datfiles (*.dat) into ecc. You have to selected the right platform! RC-Datfiles contains the filename, checksum and metainfos assigned to the filename. emuControlCenter will strip this informations and automaticlly create ecc-metadata!",		
	'mTopDatExportEccFullTooltip' =>
		"This will export all your meta-data of the selected platform to a Datfile (plaintext).",
	'mTopDatExportEccUserTooltip' =>
		"This will export only the data modified by you of the selected platform to a Datfile (plaintext).",
	'mTopDatExportEccEsearchTooltip' =>
		"This will export only the search result of eSearch meta-data of the selected platform to a Datfile (plaintext).",
	'mTopDatClearTooltip' =>
		"Clear data from DATfiles of the selected platform!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Internal function to cleanup and shrink the database.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"This will create all ecc user-folders like emus, roms, exports aso. Use this option, if you have created a new platform!",
	'mTopOptionCleanHistoryTooltip' =>
		"This will clean up the ecc history.ini. Ecc stores data like selected Directories, selected Options aso. in this file.",
	'mTopOptionBackupUserdataTooltip' =>
		"This will backup all your userdata like notes, highscore and time played to an XML file",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"This will create ECC shortcuts in the windows startmenu",
	'mTopOptionConfigTooltip' =>
		"This will open the configuration window of ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Select various GTK themes to use with ECC, you can make a nice combination when used with proper ECC themes.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"This will diagnose and give you information about your ECC installation.",
	'mTopDatDFUTooltip' =>
		"Manually update your DATfiles from MAME DAT.",
	'mTopAutoIt3GUITooltip' =>
		"This will open KODA where you can create end export your own AutoIt3 GUI for use with scripts if needed.",
	'mTopImageIPCTooltip' =>
		"Create imagepacks of your platforms, so you can share it easily with us.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"This will open a SQL browser wich you can use to view and edit the ECC database (for experts only, make sure you create a backup of your changes bacause it can be overwritten with a ECC update!)",
	'mTopDeveloperGUITooltip' =>
		"This will open the GLADE GUI editor where you can edit and adjust the ECC GUI (for experts only, make sure you create a backup of your changes bacause it can be overwritten with a ECC update!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Ellen�rzi hogy el�rhet�ek e friss�t�sek az ECC-hez.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Ezzel megny�lik egy ablak ahol megadhatod a kameleon k�dot az ECC szolg�ltat�sok haszn�lat�hoz. (regisztr�lt f�rum tagoknak)",
	// # TOP-HELP
	'mTopHelpWebsiteTooltip' =>
		"Ez megnyitja az ECC honlapj�t az internetb�ng�sz�dben.",
	'mTopHelpForumTooltip' =>
		"Ez megnyitja az ECC t�mogat�i f�rum�t az internetb�ng�sz�dben.",
	'mTopHelpDocOfflineTooltip' =>
		"Ez megnyitja az ECC helyi dokument�ci�j�t.",
	'mTopHelpDocOnlineTooltip' =>
		"Ez megnyitja az ECC dokument�ci�s oldal�t az internetb�ng�sz�dben.",
	'mTopHelpAboutTooltip' =>
		"Ezzel felugrik az ECC n�vjegy box.",
);
?>