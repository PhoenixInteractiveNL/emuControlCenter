<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	fr (français)
 * author:	Scheibel Andreas - Traduit par Belin Cyrille
 * date:	2006/09/09
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s : localisation du chemin des ROMS",
	'rom_add_parse_title%s' =>
		"\nAJOUT DE ROMS DE %s\n",
	'rom_add_parse_msg%s%s' =>
		"Ajouter des ROMS de\n\n%s\n\ndu dossier et des sous-dossier(s)\n\n%s ?",
	'rom_add_parse_done_title' =>
		"\nSCAN TERMINE\n",
	'rom_add_parse_done_msg%s' =>
		"Scan de nouvelles ROMS de\n\n%s\n\neffectué !",
	'rom_remove_title%s' =>
		"\nEFFACEMENT DE LA LISTE DES ROMS DE %s\n",
	'rom_remove_msg%s' =>
		"Voulez-vous effacer la liste de ROMS de \n\"%s\" ?\n\nCette action supprimera toutes les ROMS sélectionnées de ecc. Ceci ne supprimera PAS les données ou la/les ROMS du disque dur.\n",
	'rom_remove_done_title' =>
		"\nEFFACEMENT DE LA LISTE DES ROMS TERMINE\n",
	'rom_remove_done_msg%s' =>
		"Toutes les ROMS de %s sont supprimées de la liste.",
	'rom_remove_single_title' =>
		"\nSUPPRESSION DE LA ROM DE LA LISTE\n",
	'rom_remove_single_msg%s' =>
		"Voulez-vous supprimer de la liste\n\n%s ?\n\nCeci ne supprimera pas les données de la ROM.\n",
	'rom_remove_single_dupfound_title' =>
		"\nROM(S) EN DOUBLE TROUVEES !!!\n",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ROM(S) en double trouvée(s)\n\nVoulez-vous supprimer tous les doublons de\n\n%s\n\n de la liste ?\n\nVoir l'aide pour plus d'informations !\n",
	'rom_optimize_title' =>
		"\nNETTOYAGE DE LA BASE DE DONNEES\n",
	'rom_optimize_msg' =>
		"Voulez-vous nettoyer la base de données des ROMS de ecc ?\n\nVous devez nettoyer la base de données si vous avez déplacé ou supprimé des ROMS de votre disque dur.\necc cherchera alors automatiquement leurs données et les favoris associés, et les supprimera de la base de données !\nCes options modifient seulement la base de données.\n",
	'rom_optimize_done_title' =>
		"\nNETTOYAGE TERMINE\n",
	'rom_optimize_done_msg%s' =>
		"La base de données de la plateforme\n\n%s\n\nest maintenant nettoyée !",
	'rom_dup_remove_title' =>
		"\nSUPPRESSION DES ROMS EN DOUBLE DE LA LISTE\n",
	'rom_dup_remove_msg%s' =>
		"Voulez-vous supprimer toutes les ROMS en double de\n\n%s\n\nde la liste ?\n\nCette opération travaille seulement dans le cadre de la liste des ROMS....\n\nCeci ne supprimera AUCUN fichier du disque dur !!!",
	'rom_dup_remove_done_title' =>
		"\nSUPPRESSION TERMINEE\n",
	'rom_dup_remove_done_msg%s' =>
		"Tous les doublons de ROMS de\n\n%s\n\nont été supprimés de la liste avec succès.",
	'rom_reorg_nocat_title' =>
		"\nIL N'Y A AUCUNE CATEGORIE !\n",
	'rom_reorg_nocat_msg%s' =>
		"Vous avez assigné aucune catégorie aux ROMS de\n\n%s !\n\nSVP utilisez la fonction Editer pour ajouter des catégories ou importer des données !\n",
	'rom_reorg_title' =>
		"\nREORGANISATION DE VOS ROMS SUR LE DISQUE DUR\n",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------Cette option réorganisera vos ROMS sur le disque dur !!!\nSVP SUPPRIMEZ D'ABORD LES ROMS EN DOUBLE DE LA LISTE !!!\nLE MODE SELECTIONNE EST : #%s#\n------------------------------------------------------------------------------------------\n\nVoulez-vous réorganiser vos ROMS de\n\n%s\n\npar cétégorie dans le dossier principal d'ecc ? ecc organisera vos ROMS dans le dossier ecc sous\n\n%s/roms/organized/\n\nSVP vérifiez s'il y a assez d'espace libre sur votre disque dur.\nVOULEZ-VOUS CONTINUER ? (A VOS RISQUES  :-) )\n",
	'rom_reorg_done_title' =>
		"\nREORGANISATION TERMINEE\n",
	'rom_reorg_done__msg%s' =>
		"Regardez le dossier d'ecc au bout du chemin \n\n%s\n\npour valider la copie.",
	'db_optimize_title' =>
		"\nOPTIMISATION DE LA BASE DE DONNEES\n",
	'db_optimize_msg' =>
		"Voulez-vous optimiser la base de données ?\n\nCeci diminuera la taille physique de la base de données d'emuControlCenter. Vous devriez le faire si vous scannez et supprimez souvent des ROMS avec emuControlCenter !\n\nCette opération gèlera l'application quelques secondes - SVP attendez ! :-)\n",
	'db_optimize_done_title' =>
		"\nOPTIMISATION DE LA BASE DE DONNEES TERMINE\n",
	'db_optimize_done_msg' =>
		"La base de données d'ecc est nettoyée !",
	'export_esearch_error_title' =>
		"\nAUNCUNE OPTION ESEARCH SELECTIONNEE\n",
	'export_esearch_error_msg' =>
		"Vous devez utiliser la recherche étendue eSearch pour pouvoir utiliser cette fonction d'exportation. Ceci exportera les données des toutes les données des ROMS issues du résultat de cette recherche, qui sera affiché dans la vue principale seulement !\n",
	'dat_export_filechooser_title%s' =>
		"Choix du dossier pour sauvegarder le fichier de données %s",	
	'dat_export_title%s' =>
		"\nEXPORTATION DU FICHIER DE DONNEES %s\n",
	'dat_export_msg%s%s%s' =>
		"Voulez-vous exporter un fichier de données %s de la plateforme\n\n%s\n\ndans ce dossier ?\n\n%s\n",
	'dat_export_esearch_msg_add' =>
		"\nCeci exportera les données des ROMS issues du résultat de votre recherche étendue eSearch !\n",
	'dat_export_done_title' =>
		"\nEXPORTATION TERMINEE\n",
	'dat_export_done_msg%s%s%s' =>
		"Exportation du fichier de données %s pour\n\n%s\n\ndans la cible\n\n%s\n\neffectuée !",
	'dat_import_filechooser_title%s' =>
		"Importation d'un fichier de données %s",
	'rom_import_backup_title' =>
		"\nCREATION D'UNE SAUVEGARDE\n",
	'rom_import_backup_msg%s%s' =>
		"Voulez-vous créer une sauvegarde dans le dossier ecc-user de\n\n%s (%s)\n\navant que vous importiez de nouvelles données ?\n",
	'rom_import_title' =>
		"\nIMPORTATION DU FICHIER DE DONNEES\n",
	'rom_import_msg%s%s%s' =>
		"Voulez-vous vraiment importer les données de la plateforme\n\n%s (%s)\n\ndu fichier de données\n\n%s ?\n",
	'rom_import_done_title' =>
		"\nIMPORTATION TERMINEE\n",
	'rom_import_done_msg%s' =>
		"Importation du fichier de données de\n\n%s\n\neffectuée !",
	'dat_clear_title%s' =>
		"\nEFFACEMENT DE LA BASE DE DONNEES POUR %s\n",
	'dat_clear_msg%s%s' =>
		"Voulez-vous effacer toutes les données des ROMS de\n%s (%s) ?\n\nCeci effacera toutes les informations de la base de données de ecc comme la categorie, le statut, les langues, etc... pour la plateforme sélectionnée !\nA l'étape suivante, VOUS POURREZ CREER UNE SAUVERGARDE DE CES INFORMATIONS (qui sera automatiquement enregistrée dans le dossier ecc-user).\n\nLa base de données sera automatiquement optimisée !\n",
	'dat_clear_backup_title%s' =>
		"\nSAUVEGARDE DE %s\n",
	'dat_clear_backup_msg%s%s' =>
		"Voulez-vous créer une sauvegarde de la plateforme\n\n%s (%s) ?\n",
	'dat_clear_done_title%s' =>
		"\nEFFACEMENT DE LA BASE DE DONNEES TERMINE\n",
	'dat_clear_done_msg%s%s' =>
		"Toutes les données des ROMS de\n\n%s (%s)\n\nsont supprimées de la base de données d'ecc !",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\nVos données sont sauvagardées dans le dossier %s-User.",
	'emu_miss_title' =>
		"\nERREUR - EMULATEUR NON TROUVE\n",
	'emu_miss_notfound_msg%s' =>
		"L'émulateur assigné n'a pas été trouvé !\n\nSVP Choisissez le chemin dans Menu/Emulateurs/Configuration.\n",
	'emu_miss_notset_msg' =>
		"Emulateur manquant !\n\nSVP ajoutez un émulateur pour cette plateforme/extension ! SVP choisissez le chemin dans Menu/Emulateurs/Configuration.",
	'emu_miss_dir_msg%s' =>
		"Pas d'émulateur ! (Seul un dossier est enregistré !)\n\nSVP ajoutez un émulateur pour cette plateforme/extension !\n\nSVP localisez l'exécutable de l'émulateur (exe, bat, jar, etc...). Un chemin de dossier seul ne suffit pas !",
	'rom_miss_title' =>
		"\nERREUR - AUCUNE ROM TROUVEE\n",
	'rom_miss_msg' =>
		"Le fichier sélectionné n'a pas été trouvé !\n\nSVP rescannez le dossier des ROMS ou nettoyez la base de données.\nSVP choisissez aussi si vous utilisez des options comme 'Mode Escape' ou 'Mode 8.3 filename' conformes.\n",
	'img_overwrite_title' =>
		"\nREMPLACEMENT DE L'IMAGE\n",
	'img_overwrite_msg%s%s' =>
		"L'image\n\n%s\n\nexiste déjà.\n\nVoulez-vous vraiment remplacer cette image par\n\n%s ?\n",	
	'img_remove_title' =>
		"\nSUPPRESSION DE L'IMAGE\n",
	'img_remove_msg%s' =>
		"Voulez-vous réellement supprimer l'image %s ?\n",
	'img_remove_error_title' =>
		"\nERREUR - SUPPRESSION DE L'IMAGE IMPOSSIBLE\n",
	'img_remove_error_msg%s' =>
		"L'image %s ne peut pas être effacée !\n",
	'conf_platform_update_title' =>
		"\nSAUVEGARDE DE L'INI DE LA PLATEFORME\n",
	'conf_platform_update_msg%s' =>
		"Voulez-vous vraiment enregistrer les modifications de l'INI de la plateforme %s ?\n",
	'conf_platform_emu_filechooser_title%s' =>
		"Selectionnez un émulateur pour l'extension '%s'.",
	'conf_userfolder_notset_title' =>
		"\nERREUR - DOSSIER USER INTROUVABLE\n",
	'conf_userfolder_notset_msg%s' =>
		"Vous avez altéré la liste des chemins dans votre ecc_general.ini. Ce dossier n'existe pas.\n\nDois-je créer le dossier\n\n%s\n\npour vous ?\n\nSi vous voulez choisir un autre chemin, cliquez NON et utilisez \n'Options'->'Configuration'\npour créer votre dossier User !\n",
	'conf_userfolder_error_readonly_title' =>
		"\nERREUR - IMPOSSIBLE DE CREER LE DOSSIER\n",
	'conf_userfolder_error_readonly_msg%s' =>
		"Le dossier %s ne peux pas être créé parce que vous avez sélectionné un chemin en lecture seule (CD ?).\n\nSi vous voulez choisir un autre chemin, cliquez OK et choisissez \n'Options'->'Configuration'\npour choisir votre dossier user !\n",
	'conf_userfolder_created_title' =>
		"\nDOSSIER USER CREE\n",
	'conf_userfolder_created_msg%s%s' =>
		"Les sous-dossiers\n\n%s\n\nsont créés dans votre dossier User sélectionné\n\n%s\n",
	'conf_ecc_save_title' =>
		"\nSAUVEGARDE DU GLOBAL-INI\n",
	'conf_ecc_save_msg' =>
		"Ceci enregistrera vos changements dans ecc_global.ini.\n\nCeci créera aussi le dossier user choisi et tous les sous-dossiers.\n\nVoulez-vous continuer ?\n",
	'conf_ecc_userfolder_filechooser_title' =>
		"Sélectionnez le chemin pour vos données User",
	'fav_remove_all_title' =>
		"\nSUPPRESSION DE TOUS LES FAVORIS\n",
	'fav_remove_all_msg' =>
		"Voulez-vous vraiment supprimer TOUS les favoris ?\n",
	'maint_empty_history_title' =>
		"\nREINITIALISATION DE ecc_history.ini\n",
	'maint_empty_history_msg' =>
		"Ceci videra le fichier ecc history.ini. Ce fichier enregistre toutes vos sélections dans ecc comme les options (ex : Cacher les ROMS en double) et les chemins sélectionnés. Voulez-vous réinitialiser ce fichier ?\n",
	'sys_dialog_info_miss_title' =>
		"\n?? TITRE MANQUANT ??\n",
	'sys_dialog_info_miss_msg' =>
		"\n?? MESSAGE MANQUANT ??\n",
	'sys_filechooser_miss_title' =>
		"\n?? TITRE MANQUANT ??\n",
	'status_dialog_close' =>
		"\n\nVoulez-vous fermer le panneau d'affichage de l'état d'avancement du processus ?\n",
	'status_process_running_title' =>
		"\nPROCESSUS EN COURS D'EXECUTION\n",
	'status_process_running_msg' =>
		"Un autre processus est en cours d'exécution.\nVous pouvez seulement démarrer un autre processus comme scan/importation/exportation ! SVP attendez que le processus en cours se termine !\n",
	'meta_rating_add_error_msg' =>
		"Vous pouvez seulement voter une ROM qui a des données enregistrées.\n\nSVP utilisez la fonction Editer et enregistrez ces informations !\n",
	'maint_unset_ratings_title' =>
		"\nSUPPRESSION DES VOTES DE LA PLATEFORME\n",
	'maint_unset_ratings_msg' =>
		"Ceci réinitialisera tous les votes dans la base de données... Voulez-vous continuer ?\n",
	'eccdb_title' =>
		"\nECCDB / ROMDB\n",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Statistiques :\n\n%s ajoutée(s),\n%s toujours en place,\n%s erreur(s),\n\n%s fichier(s) de données traité(s) !%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Fichier de base de données :\n\nPour supporter la communauté d'emuControlCenter, vous pouvez ajouter vos données (titre, categorie, langues etc...) dans eccdb (Internet Database).\n\nCeci fonctionne comme le CDDB (CD DataBase) pour les CD de musique.\n\nSi vous confirmez ceci, ecc transférera automatiquement vos données vers eccdb !\n\nVous devez être connecté sur Internet pour envoyer vos données !!!\n\nAprès 10 envois de données, vous devez confirmer pour en transmettre plus !\n",
	'eccdb_error' =>
		"eccdb - Erreur :\n\nVous n'êtes peut-être pas connecté sur Internet... vous pouvez ajouter des données dans eccdb seulement avec une connection Internet active !\n",
	'eccdb_no_data' =>
		"eccdb - Aucune information pouvant être ajoutée trouvée :\n\nVous devez éditer des données pour les ajouter dans eccdb. Utilisez le bouton Editer et esseyez de nouveau !\n",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Cette option cherchera les ROMS en double dans votre base de données et les affichera.\n\nVous pourrez ainsi les retrouver dans l'historique des tâches (si activé) dans le dossier ecc-logs !",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"\nSUPPRESSION DES IMAGES\n",
	'img_remove_all_msg%s' =>
		"Ceci supprimera toutes les images de la ROM sélectionnée !\n\nSupprimer toutes les images de la ROM\n\n%s ?\n",
	
	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"\nCONFIRMATION\n",
		
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"\nFICHIER ENORME TROUVE !!!\n",
	'parse_big_file_found_msg%s%s' =>
		"Voulez-vous analyser ce fichier ?\nLe jeu trouvé\n\nNom : %s\nTaille : %s\n\nest vraiment volumineux. Ceci peut prendre beaucoup de temps sans réponse d'emuControlCenter.\n\nVoulez-vous vraiment analyser ce fichier ?\n",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"\nFAVORI AJOUTE\n",
	'bookmark_added_msg' =>
		"Ce média a été ajouté aux favoris !",
	'bookmark_removed_single_title' =>
		"\nSUPPRESSION DU FAVORI TERMINEE\n",
	'bookmark_removed_single_msg' =>
		"Ce favori a été supprimé !\n",
	'bookmark_removed_all_title' =>
		"\nSUPPRESSION DES FAVORIS TERMINEE\n",
	'bookmark_removed_all_msg' =>
		"Tous les favoris ont été supprimés !\n",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"\nMISE A JOUR DES DONNEES A PARTIR D'INTERNET\n",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Voulez-vous vraiment mettre à jour la plateforme\n\n%s\n\navec les données en ligne emuControlCenter romDB ?\n\nUne connection à Internet est nécessaire pour cette requête !\n",
	
	'eccdb_webservice_get_datfile_error_title' =>
		"\nIMPORTATION DES DONNEES IMPOSSIBLE\n",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Vous devez être connectés à Internet. SVP connectez-vous et réessayez !\n",

	'romparser_fileext_problem_title%s' =>
		"\nPROBLEME D'EXTENSION %s\n",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter a remarqué que plus d'une plateforme utilise l'extension %s pour trouver des ROMS !\n\nVoici la liste des plateformes :\n%s\nEtes-vous sûrs qu'il n'y ait que des jeux de %s dans le dossier spécifié %s.\n\n<b>Ok</b> : Rechercher des %s dans ce dossier.\n\n<b>Non</b> : Ne pas rechercher des %s.\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"\n RECHERCHE DE ROMS EN DOUBLE\n",
	'rom_dup_remove_done_title_preview' => 
		"\n RECHERCHE DE ROMS EN DOUBLE TERMINEE\n",
	'rom_dup_remove_done_msg_preview' =>
		"Jetez un coup d'oeil au panneau d'affichage pour plus de détails !",
	'metaRemoveSingleTitle' =>
		"\nSUPPRESSION DES DONNEES DE LA ROMS\n",
	'metaRemoveSingleMsg' =>
		"Voulez vous vraiment supprimer toutes les données de cette ROM ?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Sélectionner un fichier CtrlMAME",
	'importDatCMConfirmTitle' =>
		"\nIMPORTATION D'UN FICHIER CtrlMAME\n",
	'importDatCMConfirmMsg%s%s%s' =>
		"Voulez-vous vraiment importer les données pour la plateforme \n\n%s (%s)\n\nà partir du fichier de données\n\n%s ?\n",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"\nACTUALISER LES INFORMATIONS DE L'AUDIT DE LA ROM\n",
	'romAuditReparseMsg%s' =>
		"Ceci va mettre à jour les informations d'état d'une ROM multi-fichiers (complète ou non).\n\nVoulez-vou continuez ?",
	'romAuditInfoNotPossibelTitle' =>
		"\nAUCUNE INFORMATION D'AUDIT DISPONIBLE\n",
	'romAuditInfoNotPossibelMsg' =>
		"Les informations d'audit d'une ROM sont seulement disponibles pour des ROMS composées de plusieurs fichiers, comme par exemple les ROMS des plateformes d'arcade !\n",

	'romReparseAllTitle' =>
		"\nSCAN DE VOS DOSSIERS DE ROMS\n",
	'romReparseAllMsg%s' =>
		"Rechercher de nouvelles ROMS pour la plateforme\n\n%s\n\ndans les dossiers contenant les ROMS de cette plateforme ?\n",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"\nEXTENSION MULTI-PLATEFORMES\n",
	'parserUnsetExtMsg%s' =>
		"Comme vous avez sélectionné '#All found', ECC doit exclure de la recherche les extensions présentes sur plusieurs plateformes pour éviter de mauvais assignements dans la base de données !\n\nemuControlCenter ne cherchera pas : %s\n\nSVP sélectionnez chaque plateforme utilisant ces extensions une par une pour pouvoir les rechercher !\n",

	'stateLabelDatExport%s%s' =>
		"Exportation d'un fichier de données %s de %s",
	'stateLabelDatImport%s' =>
		"Importation d'un fichier de données de %s",

	'stateLabelOptimizeDB' =>
		"Optimisation de la base de données",
	'stateLabelVacuumDB' =>
		"Nettoyage de la base de données",
	'stateLabelRemoveDupRoms' =>
		"Suppression des ROMS en double",
	'stateLabelRomDBAdd' =>
		"Ajouter des infos à la base de données des ROMS",
	'stateLabelParseRomsFor%s' =>
		"Recherche de ROMS de %s",
	'stateLabelConvertOldImages' =>
		"Conversion d'images...",

	'processCancelConfirmTitle' =>
		"\nARRET DU PROCESSUS EN COURS\n",
	'processCancelConfirmMsg' =>
		"Voulez-vous vraiment arrêter le processus en cours ?\n",
	'processDoneTitle' =>
		"\nPROCESSUS TERMINE\n",
	'processDoneMsg' =>
		"Le processus est terminé !\n",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"Le fichier backup XML de vos données utilisateur a été créé dans le dossier ecc-user/#_GLOBAL/ folder\n\n%s\n\nVoulez-vous visualiser maintenant le fichier XML exporté ?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"\nEXECUTION D'UNE TACHE DE FOND\n",
	'executePostShutdownTaskMessage%s' =>
		"\nTâche: <b>%s</b>\n\nVoulez-vous vraiment exécuter cette tâche de fond ?",
	'postShutdownTaskTitle' =>
		"\nEXECUTER LES TACHES SELECTIONNEES\n",
	'postShutdownTaskMessage' =>
		"Vous avez sélectionné une tâche exécutable seulement si emuControlCenter est fermé.\n\nA la fin de cette tâche, <b>emuControlCenter va redémarrer automatiquement !</b>\n\nCeci peut prendre quelques secondes, quelques minutes ou parfois des heures ! Ce popup sera bloqué, donc aucune crainte ! :-)\n\n<b>SVP attendez !</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"\nFICHIER NON TROUVE\n",
	'startRomFileNotAvailableMessage' =>
		"Il semble que vous n'ayez pas cette ROM !\n\nPeut-être devriez-vous esseyer à nouveau avec le mode d'affichage 'ROMS possédées' :-)",
	'startRomWrongFilePathTitle' =>
		"\nROM DANS LA BASE DE DONNEES MAIS FICHIER NON TROUVE\n",
	'startRomWrongFilePathMessage' =>
		"Peut-être avez-vous déplacé ou supprimmer votre ROM ?\n\nSVP nettoyez votre base de données avec l'assistant de nettoyage situé dans le sous-menu 'Options' !",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"\nTELECHARGEMENT D'IMAGE(S)\n",
	'waitForImageInjectMessage' =>
		"Cette tâche peut prendre un peu de temps. Si des images sont trouvées, cette fenêtre se fermera automatiquement et vous pourrez voir les images dans la liste !\n\nSi aucune image n'est trouvée, cette fenêtre se fermera et la liste ne sera pas mise à jour ! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"\nCOPIER/DEPLACER LES FICHIERS RESULTATS DE LA RECHERCHE\n",
	'copy_by_search_msg_waring%s%s%s' =>
		"cette option va copier/déplacer TOUS les fichiers de la recherche. (Attention ! Si vous n'avez pas fait de recherche, tous les fichiers sont sélectionnés!)\n\nVous pourrez sélectionner le dossier de destination dans la fenêtre suivante.\n\nOnt été trouvés <b>%s games</b> dans votre résultat de recherche.\n\n<b>%s packed games</b> ne sont pas pris en compte !\n\nVoulez-vous vraiment copier/déplacer ces <b>%s</b> jeux dans un autre dossier ?",
	'copy_by_search_msg_error_noplatform' =>
		"You have to select a platform to use this feature. It is not possible to use this function for ALL FOUND!",
	'copy_by_search_msg_error_notfound%s' =>
		"Des jeux non valides ont été trouvés dans le résultat de la recherche. <b>%s packed games</b> n'ont pas été pris en compte.",
	'searchTab' =>
		"Résultat de la recherche",
	'searchDescription' =>
		"Ici vous pouvez copier ou déplacer des fichiers de leur dossier d'origine vers un autre spécifié.\n<b>La source est le résultat de la recherche.</b>\nSi vous les déplacer, les chemins dans votre base de données seront également modifiés ! Un nettoyage par somme de contrôle supprime les fichiers qui sont une duplication parfaite !",
	'searchHeadlineMain' =>
		"Introduction",
	'searchHeadlineOptionSameName' =>
		"Même nom",
	'searchRadioDuplicateAddNumber' =>
		"Ajouter un nombre",
	'searchRadioDuplicateOverwrite' =>
		"Remplacer",
	'searchCheckCleanup' =>
		"Nettoyage par somme de contrôle",

);
?>
