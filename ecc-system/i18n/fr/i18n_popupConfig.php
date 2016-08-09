<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	fr (français)
 * author:	Scheibel Andreas - Traduit par Belin Cyrille
 * date:	2006/12/31 
 * ------------------------------------------
 */
$i18n['popupConfig'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------

	/* ECC */
	'lbl_ecc_hdl' =>
		"Configuration d'emuControlCenter",
	'lbl_ecc_userfolder' =>
		"Chemin du dossier User (pour images et exports)",
	'lbl_ecc_userfolder_button' =>
		"Parcourir",
	'title_ecc_userfolder_popup' =>
		"Sélectionner le nouveau dossier User",
	/* ECC-OPTIONS */
	'lbl_ecc_otp_hdl' =>
		"Options",
	'lbl_ecc_opt_detail_pp' =>
		"Eléments par page (images)",
	'lbl_ecc_opt_list_pp' =>
		"Taille des images",
	'lbl_ecc_opt_language' =>
		"Langue",
	/* ECC-COLOR&FONTS */
	'lbl_ecc_colfont_hdl' =>
		"Couleurs et polices",
	'lbl_ecc_colfont_font_list' =>
		"Taille et police des listes",
	'title_ecc_colfont_font_list_popup' =>
		"Sélectionner une police pour les listes",
	'lbl_ecc_colfont_font_global' =>
		"Taille et police générales",
	'title_ecc_colfont_font_global' =>
		"Sélectionner une police globale",
	/* ECC-STARTUP */
	'lbl_ecc_startup_hdl' =>
		"Démarrage",
	'btn_ecc_startup' =>
		"Changer la configuration de démarrage",
	
	/* EMU-PLATFORM */
	'lbl_emu_hdl%s%s' =>
		"%s (%s)",
	'lbl_emu_platform_name' =>
		"Nom de la plateforme",
	'lbl_emu_platform_category' =>
		"Categorie de la plateforme",
	/* EMU-ASSING */
	'lbl_emu_assign_hdl%s' =>
		"Assignement d'émulateur (%s)",
	'lbl_emu_assign_path' =>
		"Chemin de l'émulateur",
	'btn_emu_assign_path_select' =>
		"Sélectionner l'émulateur",
	'title_emu_assign_path_select_popup%s' =>
		"Sélectionner l'émulateur pour %s",
	'lbl_emu_assign_parameter' =>
		"Ligne de commande",
	'lbl_emu_assign_escape' =>
		"Mode Escape path",
	'lbl_emu_assign_eightdotthree' =>
		"Mode 8.3 filename",
	'lbl_emu_assign_nameonly' =>
		"Nom de fichier seulement",
	'lbl_emu_assign_noextension' =>
		"Pas d'extension",
	
	/* DAT */
	'lbl_dat_hdl' =>
		"Configurer le fichier de données",
	'lbl_dat_author' =>
		"Auteur",
	'lbl_dat_website' =>
		"Site web",
	'lbl_dat_email' =>
		"E-mail",
	'lbl_dat_comment' =>
		"Commentaires",
	/* DAT-OPTIONS */
	'lbl_dat_opt_hdl' =>
		"Options",
	'lbl_dat_opt_namestrip' =>
		"Effacer les fichiers de données romcenter",
		
	/* 0.9 FYEO 3 */
	'lbl_img_otp_list_hdl' =>
		"Options - Détails de la ROM",
	'lbl_img_otp_list_imagesize' =>
		"Taille de l'image",
	'lbl_img_otp_list_aspectratio' =>
		"Aspect ratio",
	/* 0.9 FYEO 4 */
	'lbl_img_otp_list_fastrefresh' =>
		"Rafraîchissement rapide",
		
	/* 0.9 FYEO 9 */
	'confEccStatusLogCheck' =>
		"Enregistrer l'historique des tâches",
	'confEccStatusLogOpen' =>
		"Montrer l'historique",
		
	/* 0.9.1 FYEO 5 */
	'tab_label_emulators' =>
		"Emulateurs",
	'tab_label_general' =>
		"Général",
	'tab_label_datfiles' =>
		"Fichiers de données",
	'tab_label_images' =>
		"Images",
	'tab_label_colorsandfonts' =>
		"Couleurs et polices",
	
	/* 0.9.2 FYEO 1 */
	'lbl_emu_tips' =>
		"Liens vers émulateurs connus et infos",
	'lbl_img_opt_conv' =>
		"Conversion d'images en miniatures",
	'lbl_img_opt_conv_quality' =>
		"Qualité du fichier Thumb",
	'lbl_img_opt_conv_quality_def%s' =>
		"(Défaut: %s)",
	'lbl_img_opt_conv_minsize' =>
		"Taille minimale de l'originale",
	'lbl_img_opt_conv_minsize_def%s' =>
		"(Défaut: %s)",
	'lbl_col_opt_global' =>
		"Global",
	'lbl_col_opt_list' =>
		"Listes",
	'lbl_col_opt_options' =>
		"Options",
		
	/* 0.9.2 FYEO 3 */
	'lbl_emu_assign_use_eccscript' =>
		"eccScript",

	/* 0.9.2 FYEO 5 */
	'lbl_emu_assign_edit_eccscript' =>
		"Editer l'eccScript",	
	'lbl_emu_assign_edit_eccscript_error' =>
		"Vous pouvez ajouter des scripts, si vous avez ajouté des émulateurs !",

	/* 0.9.2 FYEO 6 */
	'lbl_emu_assign_eccscript_hdl' =>
		"Option des eccScript",
	'lbl_emu_assign_delete_eccscript' =>
		"Supprimer",
	'msg_emu_assign_delete_eccscript%s' =>
		"Supprmier le eccScript\n\n%s\n\n Voulez-vous vraiment le supprimer ?",

	/* 0.9.2 FYEO 8 */
	'tab_label_startup' =>
		"Démarrage",
	'startConfHdl' =>
		"Configuration de démarrage d'ecc",
	'startConfSoundHdl' =>
		"Jouer un son",
	'startConfOptHdl' =>
		"Options",
	'startConfUpdate' =>
		"Chercher des mises à jour au démarrage.",
	'startConfMinimize' =>
		"Réduire dans la barre des tâches",
	'startConfSoundSelect' =>
		"Sélectionner un son",

	/* 0.9.2 FYEO 9 */
	'lbl_preview_impossible' =>
		"Aperçu non possible. Configuration manquante ou inexacte !",

	/* 0.9.2 FYEO 10 */
	'lbl_emu_assign_edit_eccscript_error_notfound' =>
		"Impossible de trouver un émulateur ! SVP d'abord choisir un émulateur !",
	'lbl_emu_assign_create_eccscript' =>
		"Créer un eccScript",
	'emu_info_nodata' =>
		"Aucune information disponible pour le moment...",
	'emu_info_footer' =>
		"Peut-être connaissez-vous un bon émulateur pour cette plate-forme !\nVous pouvez ajouter vos infos dans le Forum à l'adresse\nhttp://ecc.phoenixinteractive.mine.nu/",
	
	/* 0.9.2 FYEO 10 */
	'title_startup_select_sound' =>
		"Sélectionner un son pour le démarrage",


	/* 0.9.2 FYEO 14 */
	'title_emu_assign_found_eccscript' =>
		"eccScript trouvé",
	'title_emu_assign_found_eccscript' =>
		"Un eccScript a été trouvé pour l'émulateur sélectionné !\n\nActiver ce eccScript %s",
	'title_popup_save' =>
		"Redémarrage d'ecc",
	'msg_popup_save' =>
		"Redémarrer emuControlCenter pour voir les changements ?",

	/* 0.9.2 FYEO 15 */
	'title_emu_found_eccscript_preview' =>
		"Informations:",
	'title_emu_found_eccscript_nopreview' =>
		"Auncune informations disponible !",

);
?>