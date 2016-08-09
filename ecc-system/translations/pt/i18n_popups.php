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
		"%s: Por favor, localize sue diretório de roms",
	'rom_add_parse_title%s' =>
		"Add novas roms para %s",
	'rom_add_parse_msg%s%s' =>
		"Adicionar novas roms para\n\n%s\n\ndo(s) diretório(s)\n\n%s ?",
	'rom_add_parse_done_title' =>
		"Verificação concluída",
	'rom_add_parse_done_msg%s' =>
		"Análise de novas roms para \n\n%s\n\nfoi realizada com sucesso!",
	'rom_remove_title%s' =>
		"APAGAR LISTA DE ROMS PARA %s",
	'rom_remove_msg%s' =>
		"VOCÊ DESEJA APAGAR AS ROMS PARA \n\%s\?\n\nEsta ação irá remover TODAS as roms da plataforma selecionada. Esta ação NÃO irá remover suas roms do disco rígido, nem as informações (datfile) das mesmas.",
	'rom_remove_done_title' =>
		"Remoção de roms concluída",
	'rom_remove_done_msg%s' =>
		"Todas as roms para %s foram apagadas da lista!",
	'rom_remove_single_title' =>
		"Remover rom da base de dados?",
	'rom_remove_single_msg%s' =>
		"Você deseja remover\n\n%s\n\nda base de dados do eCC?",
	'rom_remove_single_dupfound_title' =>
		"Rom(s) duplicadas encontradas!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ROM(S) DUPLICADAS ENCONTRADAS\n\nVocê deseja remover todas as roms duplicadas de\n\n%s\n\n da base de dados do eCC?\n\nDê uma olhada em AJUDA para mais informações!",
	'rom_optimize_title' =>
		"Otimizar base de dados",
	'rom_optimize_msg' =>
		"Você gostaria de otimizar a base de dados das roms?\n\nÉ recomendado otimizar a base de dados quando você mover ou remover arquivos do disco rígido.\nO eCC irá procurar automaticamente, na base de dados, por entradas que não são mais utilizadas e removê-las!\nEsta opção apenas edita a base de dados.",
	'rom_optimize_done_title' =>
		"Otimização concluída",
	'rom_optimize_done_msg%s' =>
		"A base de dados para a plataforma\n\n%s\n\nfoi otimizada com sucesso!",
	'rom_dup_remove_title' =>
		"Remover roms duplicadas da base de dados?",
	'rom_dup_remove_msg%s' =>
		"Você gostaria de remover todas as roms duplicadas para\n\n%s\n\nda base de dados?\n\nEsta operação só funciona com a base de dados do emuControlCenter...\n\nIsto NÃO irá remover arquivos do disco rígido!!!",
	'rom_dup_remove_done_title' =>
		"Remoção concluída",
	'rom_dup_remove_done_msg%s' =>
		"Todas as roms duplicadas para\n\n%s\n\nforam removidas com sucesso!",
	'rom_reorg_nocat_title' =>
		"Não há categoria!",
	'rom_reorg_nocat_msg%s' =>
		"Você não atribuiu alguma categoria para suas roms de\n\n%s! Por favor, utilize a função de edição para adicionar alguma categoria ou importe um ecc-datfile!",
	'rom_reorg_title' =>
		"Reorganizar suas roms no disco rígido?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------ESTA OPÇÃO IRÁ REORGANIZAR SUAS ROMS NO DISCO RÍGIDO !!! POR FAVOR, PRIMEIRO REMOVA QUALQUER ROM DUPLICADA DA BASE DE DADOS DO ECC !!!\nO MODO SELECIONADO É: #%s#\n------------------------------------------------------------------------------------------\n\nVocê gostaria de organizar suas roms de\n\n%s\n\npor categorias no diretório principal do eCC? O eCC irá organizar suas roms no ecc-user (pasta do usuário) em\n\n%s/roms/organized/\n\nPor favor, verifique se há espaço em disco.\n\nDESEJA CONTINUAR POR SUA CONTA E RISCO? :-)",
	'rom_reorg_done_title' =>
		"Reorganização concluída",
	'rom_reorg_done__msg%s' =>
		"Dê uma olhada no diretório do eCC em\n\n%s\n\npara confirmar que está tudo OK.",
	'db_optimize_title' =>
		"Sistema de otimização da base de dados",
	'db_optimize_msg' =>
		"Você gostaria de otimizar a base de dados?\nIsto irá diminuir o tamanho físico da base de dados do emuControlCenter. É recomendado realizar se você adiciona e remove roms regularmente com o emuControlCenter!\n\nEsta operação 'congelará' o programa por alguns segundos - Por favor, espere! :-)",
	'db_optimize_done_title' =>
		"Otimização da base de dados concluída",
	'db_optimize_done_msg' =>
		"A base de dados do eCC agora está otimizada!",
	'export_esearch_error_title' =>
		"Nenhuma opção do eSearch selecionada",
	'export_esearch_error_msg' =>
		"Você precisa usar o eSearch (extensão de pesquisa) para utilizar esta função de exportação. Esta operação exportará somente metadados das ROMs que se pode ver na lista!",
	'dat_export_filechooser_title%s' =>
		"Selecione o diretório para salvar o datfile do %s",	
	'dat_export_title%s' =>
		"Exportar datfile do %s",
	'dat_export_msg%s%s%s' =>
		"Você gostaria de exportar o datfile de %s para a plataforma\n\n%s\n\ndentro deste diretório?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"O eCC usará as definições feitas no eSearch na exportação!",
	'dat_export_done_title' =>
		"Exportação concluída",
	'dat_export_done_msg%s%s%s' =>
		"Exportação do datfile %s para\n\n%s\n\nno diretório\n\n%s\n\nconcluída com sucesso!",
	'dat_import_filechooser_title%s' =>
		"Importar - Selecione um datfile para %s",
	'rom_import_backup_title' =>
		"Criação de backup",
	'rom_import_backup_msg%s%s' =>
		"Você gostaria de criar uma cópia de segurança (backup) na sua pasta de usuário para\n\n%s (%s)\n\nantes de importar novos metadados?",
	'rom_import_title' =>
		"Importar datfile",
	'rom_import_msg%s%s%s' =>
		"Você realmente deseja importar metadados para a plataforma\n\n%s (%s)\n\ndo datfile\n\n%s ?",
	'rom_import_done_title' =>
		"Importação finalizada",
	'rom_import_done_msg%s' =>
		"Importação do datfile para\n\n%s\n\nconcluída!",
	'dat_clear_title%s' =>
		"APAGAR BASE DE DADOS DO %s",
	'dat_clear_msg%s%s' =>
		"VOCÊ DESEJA APAGAR TODOS OS METADADOS PARA\n\n%s (%s) ?\n\nEsta operação apagará todas as informações como título, gênero, ano, status, etc. da plataforma selecionada! No passo seguinte você poderá criar uma cópia de segurança (backup) para estes dados (que será salva automaticamente na sua pasta de usuário)!\n\nO último passo é fazer uma otimização da base de dados!",
	'dat_clear_backup_title%s' =>
		"Cópia de segurança %s",
	'dat_clear_backup_msg%s%s' =>
		"Você gostaria de criar um backup para a plataforma\n\n%s (%s) ?",
	'dat_clear_done_title%s' =>
		"Limpeza da base de dados finalizada",
	'dat_clear_done_msg%s%s' =>
		"Todas as informações para\n\n%s (%s)\n\nforam removidas da base de dados do eCC com sucesso!",
	'dat_clear_done_ifbackup_msg%s' =>
		"O eCC guardou o backup de seus dados no diretório 'ecc-user' -> %s",
	'emu_miss_title' =>
		"ERRO!",
	'emu_miss_notfound_msg%s' =>
		"O emulador atribuído não foi encontrado!\n\nPor favor, escolha um emulador em 'Emuladores' -> 'Configuração'",
	'emu_miss_notset_msg' =>
		"Você não atribuiu nenhum emulador válido para esta plataforma",
	'emu_miss_dir_msg%s' =>
		"O caminho atribuído é um diretório!",
	'img_overwrite_title' =>
		"Sobrescrever imagem",
	'img_overwrite_msg%s%s' =>
		"A imagem\n\n%s\n\njá existe!\n\nVocê realmente deseja substituir a imagem por\n\n%s ?",	
	'img_remove_title' =>
		"Remover imagem",
	'img_remove_msg%s' =>
		"Você realmente deseja remover a imagem %s ?",
	'img_remove_error_title' =>
		"ERRO",
	'img_remove_error_msg%s' =>
		"Não foi possível remover a imagem %s !",
	'conf_platform_update_title' =>
		"Atualizar ini da plataforma",
	'conf_platform_update_msg%s' =>
		"Você realmente deseja atualizar o INI da plataforma %s ?",
	'conf_platform_emu_filechooser_title%s' =>
		"Selecione um emulador para a extensão '%s'",
	'conf_userfolder_notset_title' =>
		"ERRO - Não foi possível encontrar diretório",
	'conf_userfolder_notset_msg%s' =>
		"Você alterou os caminhos de diretório no ecc_general.ini. Este diretório não foi criado por enquanto.\n\nVocê gostaria de criar o diretório\n\n%s\n\nagora?\n\nSe desejar escolher outro diretório, clique em NÃO e escolha \n'Opções' -> 'Configuração'\npara definir sua pasta do usuário!",
	'conf_userfolder_error_readonly_title' =>
		"ERRO - Não foi possível criar diretório!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"O diretório %s não pode ser criado porque você selecionou uma mídia que somente lê (read-only).\n\nSe desejar escolher outro diretório, clique em OK e escolha \n'Opções' -> 'Configuração'\npara definir sua pasta do usuário!",
	'conf_userfolder_created_title' =>
		"Pasta do usuário criada!",
	'conf_userfolder_created_msg%s%s' =>
		"As subpastas\n\n%s\n\nforam criadas na sua pasta de usuário\n\n%s",
	'conf_ecc_save_title' =>
		"Atualizar GLOBAL-INI do emuControlCenter",
	'conf_ecc_save_msg' =>
		"Esta operação irá escrever as mudanças nas definições para o ecc_global.ini.\n\nTambém irá criar a pasta de usuário e as subpastas necessárias.\n\nDeseja continuar?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Selecione o diretório para seus dados de usuário",
	'fav_remove_all_title' =>
		"Remoção de favoritos",
	'fav_remove_all_msg' =>
		"Você realmente deseja apagar todos os favoritos?",
	'maint_empty_history_title' =>
		"Limpar history.ini?",
	'maint_empty_history_msg' =>
		"Esta operação irá limpar o arquivo history.ini (histórico). Este arquivo armazena suas preferências do eCC, como opções (ex. esconder roms duplicadas) e caminhos de diretório selecionados!\n\nDeseja continuar?",
	'sys_dialog_info_miss_title' =>
		"?? FALTANDO TÍTULO ??",
	'sys_dialog_info_miss_msg' =>
		"?? FALTANDO MENSAGEM ??",
	'sys_filechooser_miss_title' =>
		"?? FALTANDO TÍTULO ??",
	'status_dialog_close' =>
		"\n\nGostaria de fechar a área de status agora?",
	'status_process_running_title' =>
		"Um processo já está em execução!",
	'status_process_running_msg' =>
		"Você somente pode iniciar um processo de cada vez como analisar/importar/exportar! Por favor, aguarde até o término do processo em execução!",
	'meta_rating_add_error_msg' =>
		"Você somente pode avaliar uma ROM que contenha metadados.\n\nPor favor, utilize a função de edição e add os dados necessários!",
	'maint_unset_ratings_title' =>
		"Remoção de avaliações dos jogos",
	'maint_unset_ratings_msg' =>
		"Esta operação removerá todas as avaliações da base de dados... deseja continuar?",
	'eccdb_title' =>
		"Banco de dados de games",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"gamedb - Estatísticas:\n\n%s adicionado(s)\n%s ainda em vigor\n%s erro(s)\n\n%s fichier(s) sets de dados processados!%s",
	'eccdb_webservice_post_msg' =>
		"gamedb - Banco de dados:\n\nPara ajudar a comunidade do emuControlCenter, você pode adicionar seus metadados (título, gênero, ano, etc.) no gameDB (banco de dados de games).\n\nO gamedb funciona como o conhecido CDDB (banco de dados musical).\n\nSe você confirmar esta operação, o eCC irá automaticamente transferir seus metadados para o gamedb!\n\nVocê precisa estar conectado com a internet para adicionar as informações!!!\n\nApós sets de 10 metadados processados, você precisa confirmar para adicionar mais!",
	'eccdb_error' =>
		"gamedb - Erros:\n\nTalvez você não esteja conectado à internet... Somente com uma conexão ativa na internet será possível adicionar dados no gamedb!",
	'eccdb_no_data' =>
		"gamedb - Nenhum dado adicional encontrado:\n\nVocê precisa editar mais seus metadados para adiciona-los no gamedb. Faça uma edição e tente novamente!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Esta opção irá procurar por ROMs duplicadas na base de dados e irá exibir as que encontrar.\n\nVocê poderá encontrar o arquivo de histórico (log) no seu diretório ecc-logs!",
	
	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Remoção de imagens",
	'img_remove_all_msg%s' =>
		"Esta opção irá remover todas as imagens da ROM selecionada!\n\nDeseja continuar com a remoção de imagens de\n\n%s ?",
	
	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"confirmar",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"Analisar este arquivo?",
	'parse_big_file_found_msg%s%s' =>
		"Arquivo ENORME encontrado!!!\n\nA rom\n\nNome: %s\nTamanho: %s\n\né bem grande! Pode ficar um bom tempo sem resposta do emuControlCenter.\n\nVocê deseja analisar esta rom?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Favorito adicionado",
	'bookmark_added_msg' =>
		"O favorito foi add com sucesso!",
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
		"Atualizar dados a partir da internet",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Você realmente deseja atualizar a plataforma\n\n%s\n\ncom informações do banco de dados online (gameDB) do emuControlCenter?\n\nUma conexão com a internet é necessária para realizar esta operação!",
	
	'eccdb_webservice_get_datfile_error_title' =>
		"Não foi possível importar datfile",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Você precisa estar conectado na internet. Por favor, conecte-se e tente novamente!",

	'romparser_fileext_problem_title%s' =>
		"Problema na extensão %s",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter encontrou mais de uma plataforma que utiliza a extensão %s em suas roms!\n\n%s\nVocê tem certeza de que somente roms de %s estão localizadas no diretório indicado %s\n\n<b>Ok</b>: Procurar por %s neste diretório!\n\n<b>CANCELAR</b>: Não procurar pela extensão %s neste diretório!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Procurar por roms duplicadas",
	'rom_dup_remove_done_title_preview' => 
		"Pesquisa concluída",
	'rom_dup_remove_done_msg_preview' =>
		"Dê uma olhada na área de status para mais informações!",
	'metaRemoveSingleTitle' =>
		"Remover metadados",
	'metaRemoveSingleMsg' =>
		"Você deseja remover os metadados desta rom?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Selecione um datfile do clrMAME",
	'importDatCMConfirmTitle' =>
		"Importar dat clrMAME",
	'importDatCMConfirmMsg%s%s%s' =>
		"Você realmente deseja importar dados para a plataforma\n\n%s (%s)\n\ndo datfile\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Atualizar informações",
	'romAuditReparseMsg%s' =>
		"Isto irá atualizar as informações de uma rom multiarquivo (se está completa ou não).\n\nDeseja continuar?",
	'romAuditInfoNotPossibelTitle' =>
		"Não está disponível",
	'romAuditInfoNotPossibelMsg' =>
		"Informações de auditoria são disponíveis somente para roms multiarquivos, como por exemplo as roms de arcade!",

	'romReparseAllTitle' =>
		"Analisar diretório de roms",
	'romReparseAllMsg%s' =>
		"Procurar por novas roms para a(s) seguinte(s) plataforma(s)?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Extensões multiplataforma",
	'parserUnsetExtMsg%s' =>
		"Como você selecionou '#Todas encontradas', o eCC tem que pular extensões duplicadas na pesquisa para prevenir atribuições incorretas na base de dados!\n\nO emuControlCenter não pesquisará por: %s\n\nPor favor, selecione a plataforma correta para analisar estas extensões!\n\n",

	'stateLabelDatExport%s%s' =>
		"Exportar datfile de %s para %s",
	'stateLabelDatImport%s' =>
		"Importar datfile para %s",

	'stateLabelOptimizeDB' =>
		"Otimizar base de dados",
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
		"Você realmente deseja cancelar o processo em execução?",
	'processDoneTitle' =>
		"Fim!",
	'processDoneMsg' =>
		"O processo foi concluído com sucesso!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"A cópia de segurança (backup) XML com seus dados foi criado em ecc-user/#_GLOBAL/ pasta\n\n%s\n\nDeseja ver o arquivo XML agora?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Esta tarefa pode levar algum tempo",
	'executePostShutdownTaskMessage%s' =>
		"\nAviso: <b>%s</b>\n\nVocê realmente deseja executar esta longa tarefa?",
	'postShutdownTaskTitle' =>
		"Executar tarefa selecionada",
	'postShutdownTaskMessage' =>
		"Você selecionou uma tarefa que só pode ser executada com o emuControlCenter fechado.\n\nApós esta tarefa, <b>o emuControlCenter irá reiniciar automaticamente!</b>\n\nIsto pode levar alguns segundos, alguns minutos ou algumas horas! Esta janela congelará! Respire fundo! :-)\n\n<b>Por favor, aguarde!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Arquivo não encontrado...",
	'startRomFileNotAvailableMessage' =>
		"Ei, acho que você não tem esta rom!\n\nTente novamente, mas antes selecione em 'Visão' => 'todas (que possuo)' :-)",
	'startRomWrongFilePathTitle' =>
		"Rom se encontra na base de dados mas o arquivo não foi encontrado!",
	'startRomWrongFilePathMessage' =>
		"Você removeu ou moveu de posição suas roms?\n\nPor favor, use a opção 'ROMS' -> 'Otimizar roms' para limpar a base de dados!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Baixar imagens",
	'waitForImageInjectMessage' =>
		"Esta tarefa pode levar um tempinho. Se forem encontradas imagens, esta janela fechará automaticamente e você verá as imagens na lista!\n\nSe não forem encontradas imagens, esta janela fechará automaticamente e você não verá imagens na lista... :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Really copy/move files by search result?",
	'copy_by_search_msg_waring%s%s%s' =>
		"This option will copy/rename ALL games found in your search result (Take care: If you dont have searched, all files are selected!)\n\nYou can select the destination in the next window.\n\nThere where found <b>%s games</b> in your searchresult\n\n<b>%s packed games</b> are skipped!\n\nDo you really want to copy/move these <b>%s</b> games to another location?",
	'copy_by_search_msg_error_noplatform' =>
		"You have to select a platform to use this feature. It is not possible to use this function for ALL FOUND!",
	'copy_by_search_msg_error_notfound%s' =>
		"No valid games are found in your searchresult. <b>%s packed games</b> skipped.",
	'searchTab' =>
		"Searchresult",
	'searchDescription' =>
		"Here you can copy or move files from their source folder to a specified one.\n<b>Source is your current search result.</b>\nIf you move, also the paths in your database are updated! Clean by checksum remove files that are 100% duplicate!",
	'searchHeadlineMain' =>
		"Introduction",
	'searchHeadlineOptionSameName' =>
		"same name",
	'searchRadioDuplicateAddNumber' =>
		"add number",
	'searchRadioDuplicateOverwrite' =>
		"overwrite",
	'searchCheckCleanup' =>
		"cleanup by checksum",

);
?>
