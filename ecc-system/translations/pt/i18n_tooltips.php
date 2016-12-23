<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	pt (portuguese)
 * author:	traduzido por rodrigo 'namnam' almeida
 * date:	2009/01/23
 * ------------------------------------------
 */
$i18n['tooltips'] = array(
	// -------------------------------------------------------------
	// tooltips
	// -------------------------------------------------------------
	'opt_auto_nav' =>
		"Actualizaчуo automсtica enquanto navega",
	'opt_hide_nav_null' =>
		"Mostrar/Esconder plataformas sem roms",
	'opt_hide_dup' =>
		"Mostrar/Esconder roms duplicadas",
	'opt_hide_img' =>
		"Mostrar/Esconder imagens",
	'search_field_select' =>
		"Escolha um critщrio de pesquisa",
	'search_operator' =>
		"Escolha um operador de pesquisa ([ = EQUAL] [ | OR ] [ + AND])",
	'search_rating' =>
		"Mostrar somente roms avaliadas abaixo ou igualmente a selecчуo",
	'optvis_mainlistmode' =>
		"Trocar entre lista detalhada (imagens) e lista simples",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Clique para adicionar a sua avaliaчуo pessoal do jogo",
	'nbMediaInfoNoteEvent' =>
		"Mostrar anotaчѕes pessoais para este jogo",
	'nbMediaInfoReviewEvent' =>
		"Mostrar resenha para este jogo",
	'nbMediaInfoBookmarkEvent' =>
		"Add / Remover dos favoritos",
	'nbMediaInfoAuditStateEvent' =>
		"Mostrar auditoria para roms multiarquivos",
	'nbMediaInfoMetaEvent' =>
		"Editar metadados para este jogo",

	/* 0.9.7 WIP 14 */

	'opt_only_disk' =>
		"Mostrar apenas o media principal",

	/* 0.9.7 WIP 16 */
	'optionContextOnlyDiskAll' =>
		"Mostrar todas as roms",
	'optionContextOnlyDiskOne' =>
		"Mostrar apenas a primeira versуo da rom",
	'optionContextOnlyDiskOnePlus' =>
		"Mostrar primeira versуo + versѕes desconhecidas",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Isto adicionarс roms para a primeira plataforma seleccionada!",
	'mTopRomOptimizeTooltip' =>
		"Optimize a ecc-Database para a plataforma seleccionada e.g. Se mover/remover ficheiros no seu disco rэgido",
	'mTopRomRemoveDupsTooltip' =>
		"Isto irс remover todas as roms duplicadas da ecc-database",
	'mTopRomRemoveRomsTooltip' =>
		"Remover todas as roms da plataforma seleccionada da ecc-database",		
	'mTopDatImportRcTooltip' =>
		"Pode importar ficheiros dat do Romcenter (*.dat) para o ecc. Vocъ tem que seleccionar a plataforma certa! Os ficheiros dat RC contщm o nome do ficheiro, checksum e metadados atribuэdos ao nome do ficheiro. O emuControlCenter irс retirar estas informaчѕes e automaticamente gerar metadados ecc!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Mudar o emulador atribuэdo р plataforma seleccionada.",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Importar ficheiros dat do emuControlCenter (*.eccDat) ao ecc. Se seleccionou uma plataforma, sѓ roms para esta plataforma serуo importados! O ficheiro dat do formato ecc extendeu as metainformaчѕes, como categorias, criador, estado, lэnguas etc.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Importar ficheiros CLR MAME (*.dat) para o ecc.",
	'mTopDatImportRcTooltip' =>
		"Importar ficheiros dat do Romcenter (*.dat) para o ecc. Tem que seleccionar a plataforma correcta! Os ficheiros dat RC contщm o nome do ficheiro, checksum e metadados atribuэdos ao nome do ficheiro. O emuControlCenter irс retirar estas informaчѕes e automaticamente criar metadados ecc!",		
	'mTopDatExportEccFullTooltip' =>
		"Isto irс exportar todos os metadados da plataforma seleccionada para um ficheiro Dat (apenas texto).",
	'mTopDatExportEccUserTooltip' =>
		"Isto irс exportar apenas os dados modificados por si da plataforma seleccionada para um ficheiro Dat (apenas texto).",
	'mTopDatExportEccEsearchTooltip' =>
		"Isto irс exportar apenas o resultado da pesquisa dos metadados no eSearch da plataforma seleccionada para um ficheiro Dat (apenas texto).",
	'mTopDatClearTooltip' =>
		"Limpar dados dos ficheiros Dat da plataforma seleccionada!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Funчуo interna para limpar e encolher a base de dados.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"Isto irс criar todos os directѓrios do utilizador ecc, tais como emus, roms, exportaчѕes etc. Use esta opчуo se tiver criado uma nova plataforma!",
	'mTopOptionCleanHistoryTooltip' =>
		"Isto irс limpar o history.ini do ecc. O Ecc armazena dados, como directѓrios seleccionados, opчѕes, etc neste ficheiro.",
	'mTopOptionBackupUserdataTooltip' =>
		"Isto irс guardar todos os dados de utilizador, como notas, melhor pontuaчуo e tempo jogado num ficheiro XML",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"Isto irс criar atalhos do ECC no menu Iniciar do Windows",
	'mTopOptionConfigTooltip' =>
		"Isto irс abrir a janela de configuraчуo do ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Escolhe variados temas GTK para usar no ECC, podes fazer uma bonita combinaчуo quando usado com adequados temas ECC.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"Isto irс diagnosticar e dar-lhe informaчуo sobre a sua instalaчуo ECC.",
	'mTopDatDFUTooltip' =>
		"Actualizar manualmente os seus ficheiros Dat do DAT MAME.",
	'mTopAutoIt3GUITooltip' =>
		"Isto irс abrir o KODA onde vocъ pode criar e exportar os seus prѓprios AutoIt3 GUI para usar, se necessсrio, com scripts.",
	'mTopImageIPCTooltip' =>
		"Create imagepacks of your platforms, so you can share it easily with us.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"Isto irс abrir um explorador SQL que poderс usar para ver e editar a base de dados ECC (sѓ para peritos, certifique-se que faz uma cѓpia de seguranчa das suas definiчѕes porque podem ser reescritas com uma actualizaчуo do ECC!)",
	'mTopDeveloperGUITooltip' =>
		"Isto irс abrir o editor GLADE GUI onde poderс editar e ajustar o ECC GUI (sѓ para peritos, certifique-se que faz uma cѓpia de seguranчa das suas definiчѕes porque podem ser reescritas com uma actualizaчуo do ECC!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Isto irс verificar se hс actualizaчѕes diponэveis para o ECC.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Isto irс abrir uma janela onde poderс digitar o cѓdigo kameleon para usar os servicos do ECC. (membros registados do fѓrum)",
	// # TOP-HELP
	'mTopSocialWebsiteECCTooltip' =>
		"Isto irс abrir o site do ECC no seu navegador de internet.",
	'mTopSocialForumTooltip' =>
		"Isto irс abrir o forum de suporte ECC no seu navegador de internet your internetbrowser.",
	'mTopHelpDocOfflineTooltip' =>
		"Isto irс abrir a documentaчуo ECC local.",
	'mTopHelpDocOnlineTooltip' =>
		"Isto irс abrir a documentaчуo ECC no seu navegador de internet.",
	'mTopHelpAboutTooltip' =>
		"Isto irс abrir uma janela com os crщditos doECC.",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"Isto irс abrir uma janela onde pode inserir os dados da sua conta do EmuMovies e usar os seus serviчos. (membros registados do fѓrum)",
	
	/* 1.14 BUILD 4 */
	'mTopToolNotepadEditorTooltip' =>
		"This will open the notepad editor where you can edit text files and scripts if needed.",
	'mTopToolHexEditorTooltip' =>
		"This will open a HEX editor where you can edit binary files if needed.",

	/* 1.22 BUILD 1 */
	'mTopEmuDownloadTooltip' =>
		"Download, install and configure emulators with emuDownloadCenter!",
	'mTopSocialWebsiteEDCTooltip' =>
		"Open the EDC Project page, and see how you can help to collect emulators!",
	'mTopSocialFacebookTooltip' =>
		"Visit the ECC facebook page and like it to get the latest news!",
	);
?>