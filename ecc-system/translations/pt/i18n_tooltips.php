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
		"Actualiza��o autom�tica enquanto navega",
	'opt_hide_nav_null' =>
		"Mostrar/Esconder plataformas sem roms",
	'opt_hide_dup' =>
		"Mostrar/Esconder roms duplicadas",
	'opt_hide_img' =>
		"Mostrar/Esconder imagens",
	'search_field_select' =>
		"Escolha um crit�rio de pesquisa",
	'search_operator' =>
		"Escolha um operador de pesquisa ([ = EQUAL] [ | OR ] [ + AND])",
	'search_rating' =>
		"Mostrar somente roms avaliadas abaixo ou igualmente a selec��o",
	'optvis_mainlistmode' =>
		"Trocar entre lista detalhada (imagens) e lista simples",
		
	/* 0.9.7 WIP 01 */

	'nbMediaInfoStateRatingEvent' =>
		"Clique para adicionar a sua avalia��o pessoal do jogo",
	'nbMediaInfoNoteEvent' =>
		"Mostrar anota��es pessoais para este jogo",
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
		"Mostrar apenas a primeira vers�o da rom",
	'optionContextOnlyDiskOnePlus' =>
		"Mostrar primeira vers�o + vers�es desconhecidas",

	/* 1.11 BUILD 8 */
	// # TOP-ROM
	'menuTopRomAddNewRomTooltip' =>
		"Isto adicionar� roms para a primeira plataforma seleccionada!",
	'mTopRomOptimizeTooltip' =>
		"Optimize a ecc-Database para a plataforma seleccionada e.g. Se mover/remover ficheiros no seu disco r�gido",
	'mTopRomRemoveDupsTooltip' =>
		"Isto ir� remover todas as roms duplicadas da ecc-database",
	'mTopRomRemoveRomsTooltip' =>
		"Remover todas as roms da plataforma seleccionada da ecc-database",		
	'mTopDatImportRcTooltip' =>
		"Pode importar ficheiros dat do Romcenter (*.dat) para o ecc. Voc� tem que seleccionar a plataforma certa! Os ficheiros dat RC cont�m o nome do ficheiro, checksum e metadados atribu�dos ao nome do ficheiro. O emuControlCenter ir� retirar estas informa��es e automaticamente gerar metadados ecc!",
	// # TOP-EMU
	'mTopEmuConfigTooltip' =>
		"Mudar o emulador atribu�do � plataforma seleccionada.",
	// # TOP-DAT
	'mTopDatImportEccTooltip' =>
		"Importar ficheiros dat do emuControlCenter (*.eccDat) ao ecc. Se seleccionou uma plataforma, s� roms para esta plataforma ser�o importados! O ficheiro dat do formato ecc extendeu as metainforma��es, como categorias, criador, estado, l�nguas etc.",
	'mTopDatImportCtrlMAMETooltip' =>
		"Importar ficheiros CLR MAME (*.dat) para o ecc.",
	'mTopDatImportRcTooltip' =>
		"Importar ficheiros dat do Romcenter (*.dat) para o ecc. Tem que seleccionar a plataforma correcta! Os ficheiros dat RC cont�m o nome do ficheiro, checksum e metadados atribu�dos ao nome do ficheiro. O emuControlCenter ir� retirar estas informa��es e automaticamente criar metadados ecc!",		
	'mTopDatExportEccFullTooltip' =>
		"Isto ir� exportar todos os metadados da plataforma seleccionada para um ficheiro Dat (apenas texto).",
	'mTopDatExportEccUserTooltip' =>
		"Isto ir� exportar apenas os dados modificados por si da plataforma seleccionada para um ficheiro Dat (apenas texto).",
	'mTopDatExportEccEsearchTooltip' =>
		"Isto ir� exportar apenas o resultado da pesquisa dos metadados no eSearch da plataforma seleccionada para um ficheiro Dat (apenas texto).",
	'mTopDatClearTooltip' =>
		"Limpar dados dos ficheiros Dat da plataforma seleccionada!",
	// # TOP-OPTIONS
	'mTopOptionDbVacuumTooltip' =>
		"Fun��o interna para limpar e encolher a base de dados.",	
	'mTopOptionCreateUserFolderTooltip' =>
		"Isto ir� criar todos os direct�rios do utilizador ecc, tais como emus, roms, exporta��es etc. Use esta op��o se tiver criado uma nova plataforma!",
	'mTopOptionCleanHistoryTooltip' =>
		"Isto ir� limpar o history.ini do ecc. O Ecc armazena dados, como direct�rios seleccionados, op��es, etc neste ficheiro.",
	'mTopOptionBackupUserdataTooltip' =>
		"Isto ir� guardar todos os dados de utilizador, como notas, melhor pontua��o e tempo jogado num ficheiro XML",
	'mTopOptionCreateStartmenuShortcutsTooltip' =>
		"Isto ir� criar atalhos do ECC no menu Iniciar do Windows",
	'mTopOptionConfigTooltip' =>
		"Isto ir� abrir a janela de configura��o do ECC",
	// # TOP-TOOLS
	'mTopToolEccGtktsTooltip' =>
		"Escolhe variados temas GTK para usar no ECC, podes fazer uma bonita combina��o quando usado com adequados temas ECC.",	
	'mTopToolEccDiagnosticsTooltip' =>
		"Isto ir� diagnosticar e dar-lhe informa��o sobre a sua instala��o ECC.",
	'mTopDatDFUTooltip' =>
		"Actualizar manualmente os seus ficheiros Dat do DAT MAME.",
	'mTopAutoIt3GUITooltip' =>
		"Isto ir� abrir o KODA onde voc� pode criar e exportar os seus pr�prios AutoIt3 GUI para usar, se necess�rio, com scripts.",
	'mTopImageIPCTooltip' =>
		"Create imagepacks of your platforms, so you can share it easily with us.",
	// # TOP-DEVELOPER
	'mTopDeveloperSQLTooltip' =>
		"Isto ir� abrir um explorador SQL que poder� usar para ver e editar a base de dados ECC (s� para peritos, certifique-se que faz uma c�pia de seguran�a das suas defini��es porque podem ser reescritas com uma actualiza��o do ECC!)",
	'mTopDeveloperGUITooltip' =>
		"Isto ir� abrir o editor GLADE GUI onde poder� editar e ajustar o ECC GUI (s� para peritos, certifique-se que faz uma c�pia de seguran�a das suas defini��es porque podem ser reescritas com uma actualiza��o do ECC!)",
	// # TOP-UPDATE
	'mTopUpdateEccLiveTooltip' =>
		"Isto ir� verificar se h� actualiza��es dipon�veis para o ECC.",
	// # TOP-SERVICES
	'mTopServicesKameleonCodeTooltip' =>
		"Isto ir� abrir uma janela onde poder� digitar o c�digo kameleon para usar os servicos do ECC. (membros registados do f�rum)",
	// # TOP-HELP
	'mTopSocialWebsiteECCTooltip' =>
		"Isto ir� abrir o site do ECC no seu navegador de internet.",
	'mTopSocialForumTooltip' =>
		"Isto ir� abrir o forum de suporte ECC no seu navegador de internet your internetbrowser.",
	'mTopHelpDocOfflineTooltip' =>
		"Isto ir� abrir a documenta��o ECC local.",
	'mTopHelpDocOnlineTooltip' =>
		"Isto ir� abrir a documenta��o ECC no seu navegador de internet.",
	'mTopHelpAboutTooltip' =>
		"Isto ir� abrir uma janela com os cr�ditos doECC.",

	/* 1.13 BUILD 8 */
	'mTopServicesEmuMoviesADTooltip' =>
		"Isto ir� abrir uma janela onde pode inserir os dados da sua conta do EmuMovies e usar os seus servi�os. (membros registados do f�rum)",
	
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