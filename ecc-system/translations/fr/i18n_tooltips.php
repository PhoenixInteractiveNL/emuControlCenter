<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	fr (français)
 * author:	Scheibel Andreas - Traduit par Belin Cyrille
 * date:	2006/12/31 
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"Auto-chargement pendant la navigation",
	'opt_hide_nav_null' =>
		"Montrer/Cacher les plateformes sans ROMS",
	'opt_hide_dup' =>
		"Montrer/Cacher les ROMS en double",
	'opt_hide_img' =>
		"Montrer/Cacher les images",
	'search_field_select' =>
		"Choix du critère de recherche",
	'search_operator' =>
		"Selectionnez une opération de recherche ([ = EQUAL] [ | OR ] [ + AND])",
	'search_rating' =>
		"Montrer seulement les ROMS votées par égal ou plus basse sélection",
	'optvis_mainlistmode' =>
		"Basculer le mode d'affichage des jeux : par liste/avec images",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Noter cette ROM",
	'nbMediaInfoNoteEvent' =>
		"Montrer vos notes personnelles",
	'nbMediaInfoReviewEvent' =>
		"Montrer vos notes / critiques",
	'nbMediaInfoBookmarkEvent' =>
		"Ajouter / supprimer des favoris",
	'nbMediaInfoAuditStateEvent' =>
		"Montrer les infos d'audit de ROM multi-fichiers",
	'nbMediaInfoMetaEvent' =>
		"Editer les informations pour ce jeu",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Montrer seulement le disque principal",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Montrer toutes les ROMS",
	'optionContextOnlyDiskOne' =>
		"Montrer seulement le premier média de la ROM",
	'optionContextOnlyDiskOnePlus' =>
		"Montrer le premier média de la ROM et inconnus",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Ceci va ajouter des ROMS pour la plateforme sélectionnée !",
	'mTopRomOptimizeTooltip' =>
		"Optimiser la base de données d'ECC pour la plateforme sélectionnée, par exemple en effaçant les données liées à des fichiers déplacés ou supprimés du disque dur.",
	'mTopRomRemoveDupsTooltip' =>
		"Ceci va supprimer toutes les ROMS en double dans la base de données d'ECC.",
	'mTopRomRemoveRomsTooltip' =>
		"Supprimer toutes les données des ROMS sélectionnées de la base de données d'ECC",		
	'mTopDatImportRcTooltip' =>
		"Vous pouvez importer une base de données Romcenter (*.dat) dans ECC. Pour cela vous devez sélectionner la bonne plateforme ! Ces bases de données contiennent le nom de fichier, la somme de contrôle du fichier et les métadonnées assignées à ce nom de fichier. emuControlCenter va trouver automatiquement ces informations et les ajouter dans sa base de données !",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Changer d'émulateur assigné à cette plateforme",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Importer une base de données ECC (*.ecc) dans ecc. Si vous avez sélectionné une plateforme, seules les données de celle-ci seront importées! Le format ECC a des métadonnées étendues comme la catégorie, le développeur, le pays, la langue, etc.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Importer une base de données CTRL MAME (*.dat) dans ecc.",
	'mTopDatImportRcTooltip' =>
		"Importer une base de données Romcenter (*.dat) dans ecc. Vous devez sélectionner la bonne plateforme ! Ces bases de données contiennent le nom de fichier, la somme de contrôle du fichier et les métadonnées assignées à ce nom de fichier. emuControlCenter va trouver automatiquement ces informations et les ajouter dans sa base de données !",		
	'mTopDatExportEccFullTooltip' =>
		"Ceci va exporter toutes vos métadonnées de la plateforme sélectionnée dans un fichier (plaintext).",
	'mTopDatExportEccUserTooltip' =>
		"Ceci va exporter seulement les métadonnées que vous avez modifiées pour la plateforme sélectionnée dans un fichier (plaintext).",
	'mTopDatExportEccEsearchTooltip' =>
		"Ceci va exporter seulement les métadonnées du résultat de recherche avec eSearch pour la plateforme sélectionnée dans un fichier (plaintext).",
	'mTopDatClearTooltip' =>
		"Effacer les données issues de fichiers de base de données pour la plateforme sélectionnée !",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Fonction interne de nettoyage et compression de la base de données.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"Ceci va créer tous les sous-dossiers d'ecc comme emus, ROMS, exports, etc. Utilisez cette option si vous avez créé une nouvelle plateforme !",
	'mTopOptionCleanHistoryTooltip' =>
		"Ceci va nettoyer le fichier history.ini d'ecc. Ecc sauvegarde des données dans ce dossier, par exemple les dossiers sélectionnées, les options choisies, etc.",
	'mTopOptionBackupUserdataTooltip' =>
		"Ceci va sauvegarder toutes vos données uilisateur dans un fichier XML. Par exemple les notes, les scores, les temps de jeu, etc.",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"Ceci va créer des raccoucis ECC dans le menu Démarrer de Windows.",
	'mTopOptionConfigTooltip' =>
		"Ceci va ouvrir la fenêtre de configuration d'ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Sélectionner des thèmes GTK à utiliser avec ECC, vous pouvez créer de belles apparences si le thème convient bien à ECC.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"Ceci va diagnostiquer et vous donner les informations relatives à l'installation d'ECC.",
	'mTopDatDFUTooltip' =>
		"Mettre à jour manuellement votre base de données à partir de données MAME.",
	'mTopAutoIt3GUITooltip' =>
		"Ceci va ouvrir KODA qui vous permettra de créer et exporter vos propres interfaces GUI AutoIt3 si besoin d'une utilisation de script.",
	'mTopImageIPCTooltip' =>
		"Créer des packs d'images de vos plateformes, ainsi vous pourrez les partager plus facilement avec nous.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"Ceci va ouvrir un navigateur SQL qui vous permettra de visualiser et éditer la base de données d'ECC (uniquement pour les utilisateurs chevronnés, pensez à créer une sauvegarde de vos modifications car elles pourront être écrasées lors d'une mise à jour !)",
	'mTopDeveloperGUITooltip' =>
		"Ceci va ouvrir l'éditeur de GUI GLADE qui vous permettra de modifier et ajuster le GUI d'ECC (uniquement pour les utilisateurs chevronnés, pensez à créer une sauvegarde de vos modifications car elles pourront être écrasées lors d'une mise à jour !)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Ceci va vérifier si une mise à jour est disponible.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Ceci va ouvrir une fenêtre pour entrer le code Kameleon pour utiliser les services d'ECC. (membres enregistrées sur le forum d'ECC)",
	// # TOP-HELP
	'mTopHelpWebsiteTooltip' =>
		"Ceci va ouvrir le site Internet d'ECC dans votre navigateur par défaut.",
	'mTopHelpForumTooltip' =>
		"Ceci va ouvrir le forum de support pour ECC dans votre navigateur par défaut.",
	'mTopHelpDocOfflineTooltip' =>
		"Ceci va ouvrir la documentation d'ECC enregistrée sur votre ordinateur.",
	'mTopHelpDocOnlineTooltip' =>
		"Ceci va ouvrir la documentation en ligne d'ECC dans votre navigateur par défaut.",
	'mTopHelpAboutTooltip' =>
		"Ceci va afficher le pop-up de présentation d'ECC.",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"This will open a window where you can enter your EmuMovies account data to use their services. (registered forum members)",
);
?>