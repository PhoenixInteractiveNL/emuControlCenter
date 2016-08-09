<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	pt (portuguese)
 * author:	traduzido por rodrigo 'namnam' almeida
 * date:	2009/01/23
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Por favor, localize o seu direct�rio de roms",
	'rom_add_parse_title%s' =>
		"Adicionar novas roms para %s",
	'rom_add_parse_msg%s%s' =>
		"Adicionar novas roms para\n\n%s\n\ndo(s) diret�rio(s)\n\n%s ?",
	'rom_add_parse_done_title' =>
		"Verifica��o conclu�da",
	'rom_add_parse_done_msg%s' =>
		"An�lise de novas roms para \n\n%s\n\nfoi realizada com sucesso!",
	'rom_remove_title%s' =>
		"APAGAR LISTA DE ROMS PARA %s",
	'rom_remove_msg%s' =>
		"DESEJA APAGAR AS ROMS PARA \n\%s\?\n\nEsta ac��o ir� remover TODAS as roms da plataforma seleccionada. Esta ac��o N�O ir� remover as suas roms do disco r�gido, nem as informa��es (ficheiro dat) das mesmas.",
	'rom_remove_done_title' =>
		"Remo��o de roms conclu�da",
	'rom_remove_done_msg%s' =>
		"Todas as roms para %s foram apagadas da lista!",
	'rom_remove_single_title' =>
		"Remover rom da base de dados?",
	'rom_remove_single_msg%s' =>
		"Deseja remover\n\n%s\n\nda base de dados do eCC?",
	'rom_remove_single_dupfound_title' =>
		"Rom(s) duplicadas encontradas!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ROM(S) DUPLICADAS ENCONTRADAS\n\nDeseja remover todas as roms duplicadas de\n\n%s\n\n da base de dados do eCC?\n\nD� uma olhada em AJUDA para mais informa��es!",
	'rom_optimize_title' =>
		"Optimizar base de dados",
	'rom_optimize_msg' =>
		"Voc� gostaria de optimizar a base de dados das roms?\n\n� recomendado optimizar a base de dados quando mover ou remover ficheiros do disco r�gido.\nO eCC ir� procurar automaticamente na base de dados por entradas que n�o s�o mais utilizadas e remov�-las!\nEsta op��o apenas edita a base de dados.",
	'rom_optimize_done_title' =>
		"Optimiza��o conclu�da",
	'rom_optimize_done_msg%s' =>
		"A base de dados para a plataforma\n\n%s\n\nfoi optimizada com sucesso!",
	'rom_dup_remove_title' =>
		"Remover roms duplicadas da base de dados?",
	'rom_dup_remove_msg%s' =>
		"Gostaria de remover todas as roms duplicadas para\n\n%s\n\nda base de dados?\n\nEsta opera��o s� funciona com a base de dados do emuControlCenter...\n\nIsto N�O ir� remover ficheiros do disco r�gido!!!",
	'rom_dup_remove_done_title' =>
		"Remo��o conclu�da",
	'rom_dup_remove_done_msg%s' =>
		"Todas as roms duplicadas para\n\n%s\n\nforam removidas com sucesso!",
	'rom_reorg_nocat_title' =>
		"N�o h� categoria!",
	'rom_reorg_nocat_msg%s' =>
		"N�o atribuiu alguma categoria para as roms de\n\n%s! Por favor, utilize a fun��o de edi��o para adicionar alguma categoria ou importe um ecc-datfile!",
	'rom_reorg_title' =>
		"Reorganizar roms no disco r�gido?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------ESTA OP��O IR� REORGANIZAR AS ROMS NO DISCO R�GIDO !!! POR FAVOR, PRIMEIRO REMOVA QUALQUER ROM DUPLICADA DA BASE DE DADOS DO ECC !!!\nO MODO SELECCIONADO �: #%s#\n------------------------------------------------------------------------------------------\n\nGostaria de organizar suas roms de\n\n%s\n\npor categorias no direct�rio principal do eCC? O eCC ir� organizar suas roms no ecc-user (pasta do utilizador) em\n\n%s/roms/organized/\n\nPor favor, verifique se h� espa�o em disco.\n\nDESEJA CONTINUAR POR SUA CONTA E RISCO? :-)",
	'rom_reorg_done_title' =>
		"Reorganiza��o conclu�da",
	'rom_reorg_done__msg%s' =>
		"Verifique o direct�rio do eCC em\n\n%s\n\npara confirmar que est� tudo OK.",
	'db_optimize_title' =>
		"Sistema de optimiza��o da base de dados",
	'db_optimize_msg' =>
		"Gostaria de optimizar a base de dados?\nIsto ir� diminuir o tamanho f�sico da base de dados do emuControlCenter. � recomendado fazer isto se adiciona e remove roms regularmente com o emuControlCenter!\n\nEsta opera��o 'congelar�' o programa por alguns segundos - Por favor, espere! :-)",
	'db_optimize_done_title' =>
		"Optimiza��o da base de dados conclu�da",
	'db_optimize_done_msg' =>
		"A base de dados do eCC agora est� optimizada!",
	'export_esearch_error_title' =>
		"Nenhuma op��o do eSearch seleccionada",
	'export_esearch_error_msg' =>
		"Precisa usar o eSearch (extens�o de pesquisa) para utilizar esta fun��o de exporta��o. Esta opera��o exportar� somente metadados das ROMs que se pode ver na lista!",
	'dat_export_filechooser_title%s' =>
		"Seleccione o direct�rio para salvar o ficheiro dat do %s",	
	'dat_export_title%s' =>
		"Exportar ficheiro dat do %s",
	'dat_export_msg%s%s%s' =>
		"Voc� gostaria de exportar o ficheiro dat de %s para a plataforma\n\n%s\n\ndentro deste direct�rio?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"O eCC usar� as defini��es feitas no eSearch na exporta��o!",
	'dat_export_done_title' =>
		"Exporta��o conclu�da",
	'dat_export_done_msg%s%s%s' =>
		"Exporta��o do ficheiro dat %s para\n\n%s\n\nno diret�rio\n\n%s\n\nconclu�da com sucesso!",
	'dat_import_filechooser_title%s' =>
		"Importar - Seleccione um ficheiro dat para %s",
	'rom_import_backup_title' =>
		"Cria��o de backup",
	'rom_import_backup_msg%s%s' =>
		"Gostaria de criar uma c�pia de seguran�a (backup) na sua pasta de utilizador para\n\n%s (%s)\n\nantes de importar novos metadados?",
	'rom_import_title' =>
		"Importar ficheiro dat",
	'rom_import_msg%s%s%s' =>
		"Deseja mesmo importar metadados para a plataforma\n\n%s (%s)\n\ndo datfile\n\n%s ?",
	'rom_import_done_title' =>
		"Importa��o conclu�da",
	'rom_import_done_msg%s' =>
		"Importa��o do ficheiro dat para\n\n%s\n\nconclu�da!",
	'dat_clear_title%s' =>
		"APAGAR BASE DE DADOS DO %s",
	'dat_clear_msg%s%s' =>
		"DESEJA APAGAR TODOS OS METADADOS PARA\n\n%s (%s) ?\n\nEsta opera��o apagar� todas as informa��es como t�tulo, g�nero, ano, estado, etc. da plataforma seleccionada! No passo seguinte poder� criar uma c�pia de seguran�a (backup) para estes dados (que ser� guardada automaticamente na sua pasta de utilizador)!\n\nO �ltimo passo � fazer uma optimiza��o da base de dados!",
	'dat_clear_backup_title%s' =>
		"C�pia de seguran�a %s",
	'dat_clear_backup_msg%s%s' =>
		"Gostaria de criar um backup para a plataforma\n\n%s (%s) ?",
	'dat_clear_done_title%s' =>
		"Limpeza da base de dados finalizada",
	'dat_clear_done_msg%s%s' =>
		"Todas as informa��es para\n\n%s (%s)\n\nforam removidas da base de dados do eCC com sucesso!",
	'dat_clear_done_ifbackup_msg%s' =>
		"O eCC guardou o backup de seus dados no direct�rio 'ecc-user' -> %s",
	'emu_miss_title' =>
		"ERRO!",
	'emu_miss_notfound_msg%s' =>
		"O emulador atribu�do n�o foi encontrado!\n\nPor favor, escolha um emulador em 'Emuladores' -> 'Configura��o'",
	'emu_miss_notset_msg' =>
		"N�o atribuiu nenhum emulador v�lido para esta plataforma",
	'emu_miss_dir_msg%s' =>
		"O destino atribu�do � um direct�rio!",
	'img_overwrite_title' =>
		"Sobrescrever imagem",
	'img_overwrite_msg%s%s' =>
		"A imagem\n\n%s\n\nj� existe!\n\nDeseja realmente substituir a imagem por\n\n%s ?",	
	'img_remove_title' =>
		"Remover imagem",
	'img_remove_msg%s' =>
		"Deseja realmente  remover a imagem %s ?",
	'img_remove_error_title' =>
		"ERRO",
	'img_remove_error_msg%s' =>
		"N�o foi poss�vel remover a imagem %s !",
	'conf_platform_update_title' =>
		"Actualizar ini da plataforma",
	'conf_platform_update_msg%s' =>
		"Deseja realmente actualizar o INI da plataforma %s ?",
	'conf_platform_emu_filechooser_title%s' =>
		"Seleccione um emulador para a extens�o '%s'",
	'conf_userfolder_notset_title' =>
		"ERRO - N�o foi poss�vel encontrar direct�rio",
	'conf_userfolder_notset_msg%s' =>
		"Voc� alterou os destinos do direct�rio no ecc_general.ini. Este direct�rio n�o foi criado por enquanto.\n\nGostaria de criar o direct�rio\n\n%s\n\nagora?\n\nSe desejar escolher outro direct�rio, clique em N�O e escolha \n'Op��es' -> 'Configura��o'\npara definir sua pasta de utilizador!",
	'conf_userfolder_error_readonly_title' =>
		"ERRO - N�o foi poss�vel criar direct�rio!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"O direct�rio %s n�o pode ser criado porque escolheu um s�tio somente de leitura(read-only).\n\nSe desejar escolher outro direct�rio, clique em OK e escolha \n'Op��es' -> 'Configura��o'\npara definir a sua pasta de utilizador!",
	'conf_userfolder_created_title' =>
		"Pasta de utilizador criada!",
	'conf_userfolder_created_msg%s%s' =>
		"As subpastas\n\n%s\n\nforam criadas na sua pasta de utilizar\n\n%s",
	'conf_ecc_save_title' =>
		"Actualizar GLOBAL-INI do emuControlCenter",
	'conf_ecc_save_msg' =>
		"Esta opera��o ir� escrever as mudan�as nas defini��es para o ecc_global.ini.\n\nTamb�m ir� criar a pasta de utilizador e as subpastas necess�rias.\n\nDeseja continuar?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Seleccione o direct�rio para seus dados de usu�rio",
	'fav_remove_all_title' =>
		"Remo��o de favoritos",
	'fav_remove_all_msg' =>
		"Voc� realmente deseja apagar todos os favoritos?",
	'maint_empty_history_title' =>
		"Limpar history.ini?",
	'maint_empty_history_msg' =>
		"Esta opera��o ir� limpar o arquivo history.ini (hist�rico). Este arquivo armazena as suas prefer�ncias do eCC, como op��es (ex. esconder roms duplicadas) e caminhos de direct�rio seleccionados!\n\nDeseja continuar?",
	'sys_dialog_info_miss_title' =>
		"?? FALTANDO T�TULO ??",
	'sys_dialog_info_miss_msg' =>
		"?? FALTANDO MENSAGEM ??",
	'sys_filechooser_miss_title' =>
		"?? FALTANDO T�TULO ??",
	'status_dialog_close' =>
		"\n\nGostaria de fechar a �rea de status agora?",
	'status_process_running_title' =>
		"Um processo j� est� em execu��o!",
	'status_process_running_msg' =>
		"S� pode iniciar um processo de cada vez como analisar/importar/exportar! Por favor, aguarde at� fechar o processo em execu��o!",
	'meta_rating_add_error_msg' =>
		"S� pode avaliar uma ROM que contenha metadados.\n\nPor favor, utilize a fun��o de edi��o e adicione os dados necess�rios!",
	'maint_unset_ratings_title' =>
		"Remo��o de avalia��es dos jogos",
	'maint_unset_ratings_msg' =>
		"Esta opera��o remover� todas as avalia��es da base de dados... deseja continuar?",
	'eccdb_title' =>
		"Banco de dados de jogos",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"gamedb - Estat�sticas:\n\n%s adicionado(s)\n%s ainda em vigor\n%s erro(s)\n\n%s fichier(s) sets de dados processados!%s",
	'eccdb_webservice_post_msg' =>
		"gamedb - Banco de dados:\n\nPara ajudar a comunidade do emuControlCenter, voc� pode adicionar os seus metadados (t�tulo, g�nero, ano, etc.) no gameDB (banco de dados de jogos).\n\nO gamedb funciona como o conhecido CDDB (banco de dados musical).\n\nSe voc� confirmar esta opera��o, o eCC ir� automaticamente transferir os seus metadados para o gamedb!\n\nPrecisa estar conectado � internet para adicionar as informa��es!!!\n\nAp�s sets de 10 metadados processados voc� precisa confirmar para adicionar mais!",
	'eccdb_error' =>
		"gamedb - Erros:\n\nTalvez n�o esteja conectado � internet... Somente com uma conex�o activa � internet ser� poss�vel adicionar dados � gamedb!",
	'eccdb_no_data' =>
		"gamedb - Nenhum dado adicional encontrado:\n\nPrecisa editar mais os seus metadados para adicion�-los no gamedb. Fa�a uma edi��o e tente novamente!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Esta op��o ir� procurar por ROMs duplicadas na base de dados e ir� exibir as que encontrar.\n\nPoder� encontrar o ficheiro de hist�rico (log) no seu direct�rio ecc-logs!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Remo��o de imagens",
	'img_remove_all_msg%s' =>
		"Esta op��o ir� remover todas as imagens da ROM selecionada!\n\nDeseja continuar com a remo��o de imagens de\n\n%s ?",
	
	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"confirmar",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"Analisar este arquivo?",
	'parse_big_file_found_msg%s%s' =>
		"Ficheiro GRANDE encontrado!!!\n\nA rom\n\nNome: %s\nTamanho: %s\n\n� muito grande! Pode ficar um bom tempo sem resposta do emuControlCenter.\n\nDeseja analisar esta rom?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Favorito adicionado",
	'bookmark_added_msg' =>
		"O favorito foi adicionado com sucesso!",
	'bookmark_removed_single_title' =>
		"Favorito removido",
	'bookmark_removed_single_msg' =>
		"Este favorito foi removido com sucesso!",
	'bookmark_removed_all_title' =>
		"Favoritos removidos",
	'bookmark_removed_all_msg' =>
		"Todos os favoritos foram removidos com sucesso!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Actualizar dados a partir da internet",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Deseja realmente actualizar a plataforma\n\n%s\n\ncom informa��es da base de dados online (gameDB) do emuControlCenter?\n\nUma conex�o com a internet � necess�ria para realizar esta opera��o!",
	
	'eccdb_webservice_get_datfile_error_title' =>
		"N�o foi poss�vel importar o ficheiro dat",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Precisa estar conectado � internet. Por favor, conecte-se e tente novamente!",

	'romparser_fileext_problem_title%s' =>
		"Problema na extens�o %s",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter encontrou mais de uma plataforma que utiliza a extens�o %s nas suas roms!\n\n%s\nTem certeza de que somente roms de %s est�o localizadas no direct�rio indicado %s\n\n<b>Ok</b>: Procurar por %s neste direct�rio!\n\n<b>CANCELAR</b>: N�o procurar pela extens�o %s neste direct�rio!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Procurar por roms duplicadas",
	'rom_dup_remove_done_title_preview' => 
		"Pesquisa conclu�da",
	'rom_dup_remove_done_msg_preview' =>
		"D� uma olhada na �rea de status para mais informa��es!",
	'metaRemoveSingleTitle' =>
		"Remover metadados",
	'metaRemoveSingleMsg' =>
		"Voc� deseja remover os metadados desta rom?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Selecione um ficheiro dat do ClrMAME",
	'importDatCMConfirmTitle' =>
		"Importar dat ClrMAME",
	'importDatCMConfirmMsg%s%s%s' =>
		"Deseja realmente importar dados para a plataforma\n\n%s (%s)\n\ndo datfile\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Actualizar informa��es",
	'romAuditReparseMsg%s' =>
		"Isto ir� actualizar as informa��es de uma rom multiarquivo (se est� completa ou n�o).\n\nDeseja continuar?",
	'romAuditInfoNotPossibelTitle' =>
		"N�o est� dispon�vel",
	'romAuditInfoNotPossibelMsg' =>
		"Informa��es de auditoria est�o dispon�veis somente para roms multiarquivos, como por exemplo as roms de arcade!",

	'romReparseAllTitle' =>
		"Analisar direct�rio de roms",
	'romReparseAllMsg%s' =>
		"Procurar por novas roms para a(s) seguinte(s) plataforma(s)?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Extens�es multiplataforma",
	'parserUnsetExtMsg%s' =>
		"Como voc� selecionou '#Todas encontradas', o eCC tem que saltar extens�es duplicadas na pesquisa para prevenir atribui��es incorrectas na base de dados!\n\nO emuControlCenter n�o pesquisar� por: %s\n\nPor favor, selecione a plataforma correcta para analisar estas extens�es!\n\n",

	'stateLabelDatExport%s%s' =>
		"Exportar ficheiro dat de %s para %s",
	'stateLabelDatImport%s' =>
		"Importar ficheiro dat para %s",

	'stateLabelOptimizeDB' =>
		"Optimizar base de dados",
	'stateLabelVacuumDB' =>
		"Limpar base de dados",
	'stateLabelRemoveDupRoms' =>
		"Remover roms duplicadas",
	'stateLabelRomDBAdd' =>
		"Adicionar dados no gameDB",
	'stateLabelParseRomsFor%s' =>
		"Analisando roms para %s",
	'stateLabelConvertOldImages' =>
		"Convertendo imagens...",

	'processCancelConfirmTitle' =>
		"Cancelar processo?",
	'processCancelConfirmMsg' =>
		"Deseja realmente cancelar o processo em execu��o?",
	'processDoneTitle' =>
		"Fim!",
	'processDoneMsg' =>
		"O processo foi conclu�do com sucesso!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"A c�pia de seguran�a (backup) XML com seus dados foi criado em ecc-user/#_GLOBAL/ pasta\n\n%s\n\nDeseja ver o ficheiro XML agora?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Esta tarefa pode levar algum tempo",
	'executePostShutdownTaskMessage%s' =>
		"\nAviso: <b>%s</b>\n\nDeseja realmente executar esta longa tarefa?",
	'postShutdownTaskTitle' =>
		"Executar tarefa selecionada",
	'postShutdownTaskMessage' =>
		"Selecionou uma tarefa que s� pode ser executada com o emuControlCenter fechado.\n\nAp�s esta tarefa, <b>o emuControlCenter ir� reiniciar automaticamente!</b>\n\nIsto pode levar alguns segundos, alguns minutos ou algumas horas! Esta janela congelar�! Respire fundo! :-)\n\n<b>Por favor, aguarde!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Ficheiro n�o encontrado...",
	'startRomFileNotAvailableMessage' =>
		"Ei, parece que  n�o tem esta rom!\n\nTente novamente, mas antes selecione em 'Vis�o' => 'todas (que possuo)' :-)",
	'startRomWrongFilePathTitle' =>
		"A Rom encontra-se na base de dados mas o ficheiro n�o foi encontrado!",
	'startRomWrongFilePathMessage' =>
		"Apagou ou moveu de posi��o as suas roms?\n\nPor favor, use a op��o 'ROMS' -> 'Optimizar roms' para limpar a base de dados!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Descarregar imagens",
	'waitForImageInjectMessage' =>
		"Esta tarefa pode levar algum tempo. Se forem encontradas imagens esta janela fechar� automaticamente e voc� ver� as imagens na lista!\n\nSe n�o forem encontradas imagens esta janela fechar� automaticamente e voc� n�o ver� imagens na lista... :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Quer realmente copiar/mover os ficheiros por resultados de pesquisa",
	'copy_by_search_msg_waring%s%s%s' =>
		"Esta op��o ir� copiar/renomear TODOS os jogos encontrandos nos resultados da sua pesquisa (Aten��o: se n�o tiver pesquisado, todos os ficheiros ser�o selecionados!)\n\nPode escolher o destino na pr�xima janela.\n\nForam encontrados <b>%s jogos</b> nos resultados da sua pesquisa\n\n<b>%s jogos comprimidos</b> s�o saltados!\n\nDeseja mesmo copiar/mover estes jogos <b>%s</b> para outra localiza��o?",
	'copy_by_search_msg_error_noplatform' =>
		"Tem que selecionar uma plataforma para usar esta capacidade. N�o � poss�vel usar esta fun��o para TODOS os ENCONTRADOS!",
	'copy_by_search_msg_error_notfound%s' =>
		"N�o foram encontrados jogos v�lidos no resultado da sua pesquisa</b> saltado(s).",
	'searchTab' =>
		"Resultado de pesquisa",
	'searchDescription' =>
		"Aqui pode copiar ou mover ficheiros do seu direct�rio original ou mover para outro especificado.\n<b>A fonte � o seu actual resultado de pesquisa.</b>\nSe mover, os destinos da sua base de dados ser�o actualizados! Limpo por checksum remove ficheiros qu s�o 100% duplicados!",
	'searchHeadlineMain' =>
		"Introduc�on",
	'searchHeadlineOptionSameName' =>
		"mesmo nome",
	'searchRadioDuplicateAddNumber' =>
		"adicionar n�mero",
	'searchRadioDuplicateOverwrite' =>
		"sobrescrever",
	'searchCheckCleanup' =>
		"limpar por checksum",

);
?>
