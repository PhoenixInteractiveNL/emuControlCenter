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
		"%s: Por favor, localize o seu directório de roms",
	'rom_add_parse_title%s' =>
		"Adicionar novas roms para %s",
	'rom_add_parse_msg%s%s' =>
		"Adicionar novas roms para\n\n%s\n\ndo(s) diretório(s)\n\n%s ?",
	'rom_add_parse_done_title' =>
		"Verificação concluída",
	'rom_add_parse_done_msg%s' =>
		"Análise de novas roms para \n\n%s\n\nfoi realizada com sucesso!",
	'rom_remove_title%s' =>
		"APAGAR LISTA DE ROMS PARA %s",
	'rom_remove_msg%s' =>
		"DESEJA APAGAR AS ROMS PARA \n\%s\?\n\nEsta acção irá remover TODAS as roms da plataforma seleccionada. Esta acção NÃO irá remover as suas roms do disco rígido, nem as informações (ficheiro dat) das mesmas.",
	'rom_remove_done_title' =>
		"Remoção de roms concluída",
	'rom_remove_done_msg%s' =>
		"Todas as roms para %s foram apagadas da lista!",
	'rom_remove_single_title' =>
		"Remover rom da base de dados?",
	'rom_remove_single_msg%s' =>
		"Deseja remover\n\n%s\n\nda base de dados do eCC?",
	'rom_remove_single_dupfound_title' =>
		"Rom(s) duplicadas encontradas!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d ROM(S) DUPLICADAS ENCONTRADAS\n\nDeseja remover todas as roms duplicadas de\n\n%s\n\n da base de dados do eCC?\n\nDê uma olhada em AJUDA para mais informações!",
	'rom_optimize_title' =>
		"Optimizar base de dados",
	'rom_optimize_msg' =>
		"Você gostaria de optimizar a base de dados das roms?\n\nÉ recomendado optimizar a base de dados quando mover ou remover ficheiros do disco rígido.\nO eCC irá procurar automaticamente na base de dados por entradas que não são mais utilizadas e removê-las!\nEsta opção apenas edita a base de dados.",
	'rom_optimize_done_title' =>
		"Optimização concluída",
	'rom_optimize_done_msg%s' =>
		"A base de dados para a plataforma\n\n%s\n\nfoi optimizada com sucesso!",
	'rom_dup_remove_title' =>
		"Remover roms duplicadas da base de dados?",
	'rom_dup_remove_msg%s' =>
		"Gostaria de remover todas as roms duplicadas para\n\n%s\n\nda base de dados?\n\nEsta operação só funciona com a base de dados do emuControlCenter...\n\nIsto NÃO irá remover ficheiros do disco rígido!!!",
	'rom_dup_remove_done_title' =>
		"Remoção concluída",
	'rom_dup_remove_done_msg%s' =>
		"Todas as roms duplicadas para\n\n%s\n\nforam removidas com sucesso!",
	'rom_reorg_nocat_title' =>
		"Não há categoria!",
	'rom_reorg_nocat_msg%s' =>
		"Não atribuiu alguma categoria para as roms de\n\n%s! Por favor, utilize a função de edição para adicionar alguma categoria ou importe um ecc-datfile!",
	'rom_reorg_title' =>
		"Reorganizar roms no disco rígido?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------ESTA OPÇÃO IRÁ REORGANIZAR AS ROMS NO DISCO RÍGIDO !!! POR FAVOR, PRIMEIRO REMOVA QUALQUER ROM DUPLICADA DA BASE DE DADOS DO ECC !!!\nO MODO SELECCIONADO É: #%s#\n------------------------------------------------------------------------------------------\n\nGostaria de organizar suas roms de\n\n%s\n\npor categorias no directório principal do eCC? O eCC irá organizar suas roms no ecc-user (pasta do utilizador) em\n\n%s/roms/organized/\n\nPor favor, verifique se há espaço em disco.\n\nDESEJA CONTINUAR POR SUA CONTA E RISCO? :-)",
	'rom_reorg_done_title' =>
		"Reorganização concluída",
	'rom_reorg_done__msg%s' =>
		"Verifique o directório do eCC em\n\n%s\n\npara confirmar que está tudo OK.",
	'db_optimize_title' =>
		"Sistema de optimização da base de dados",
	'db_optimize_msg' =>
		"Gostaria de optimizar a base de dados?\nIsto irá diminuir o tamanho físico da base de dados do emuControlCenter. É recomendado fazer isto se adiciona e remove roms regularmente com o emuControlCenter!\n\nEsta operação 'congelará' o programa por alguns segundos - Por favor, espere! :-)",
	'db_optimize_done_title' =>
		"Optimização da base de dados concluída",
	'db_optimize_done_msg' =>
		"A base de dados do eCC agora está optimizada!",
	'export_esearch_error_title' =>
		"Nenhuma opção do eSearch seleccionada",
	'export_esearch_error_msg' =>
		"Precisa usar o eSearch (extensão de pesquisa) para utilizar esta função de exportação. Esta operação exportará somente metadados das ROMs que se pode ver na lista!",
	'dat_export_filechooser_title%s' =>
		"Seleccione o directório para salvar o ficheiro dat do %s",	
	'dat_export_title%s' =>
		"Exportar ficheiro dat do %s",
	'dat_export_msg%s%s%s' =>
		"Você gostaria de exportar o ficheiro dat de %s para a plataforma\n\n%s\n\ndentro deste directório?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"O eCC usará as definições feitas no eSearch na exportação!",
	'dat_export_done_title' =>
		"Exportação concluída",
	'dat_export_done_msg%s%s%s' =>
		"Exportação do ficheiro dat %s para\n\n%s\n\nno diretório\n\n%s\n\nconcluída com sucesso!",
	'dat_import_filechooser_title%s' =>
		"Importar - Seleccione um ficheiro dat para %s",
	'rom_import_backup_title' =>
		"Criação de backup",
	'rom_import_backup_msg%s%s' =>
		"Gostaria de criar uma cópia de segurança (backup) na sua pasta de utilizador para\n\n%s (%s)\n\nantes de importar novos metadados?",
	'rom_import_title' =>
		"Importar ficheiro dat",
	'rom_import_msg%s%s%s' =>
		"Deseja mesmo importar metadados para a plataforma\n\n%s (%s)\n\ndo datfile\n\n%s ?",
	'rom_import_done_title' =>
		"Importação concluída",
	'rom_import_done_msg%s' =>
		"Importação do ficheiro dat para\n\n%s\n\nconcluída!",
	'dat_clear_title%s' =>
		"APAGAR BASE DE DADOS DO %s",
	'dat_clear_msg%s%s' =>
		"DESEJA APAGAR TODOS OS METADADOS PARA\n\n%s (%s) ?\n\nEsta operação apagará todas as informações como título, género, ano, estado, etc. da plataforma seleccionada! No passo seguinte poderá criar uma cópia de segurança (backup) para estes dados (que será guardada automaticamente na sua pasta de utilizador)!\n\nO último passo é fazer uma optimização da base de dados!",
	'dat_clear_backup_title%s' =>
		"Cópia de segurança %s",
	'dat_clear_backup_msg%s%s' =>
		"Gostaria de criar um backup para a plataforma\n\n%s (%s) ?",
	'dat_clear_done_title%s' =>
		"Limpeza da base de dados finalizada",
	'dat_clear_done_msg%s%s' =>
		"Todas as informações para\n\n%s (%s)\n\nforam removidas da base de dados do eCC com sucesso!",
	'dat_clear_done_ifbackup_msg%s' =>
		"O eCC guardou o backup de seus dados no directório 'ecc-user' -> %s",
	'emu_miss_title' =>
		"ERRO!",
	'emu_miss_notfound_msg%s' =>
		"O emulador atribuído não foi encontrado!\n\nPor favor, escolha um emulador em 'Emuladores' -> 'Configuração'",
	'emu_miss_notset_msg' =>
		"Não atribuiu nenhum emulador válido para esta plataforma",
	'emu_miss_dir_msg%s' =>
		"O destino atribuído é um directório!",
	'img_overwrite_title' =>
		"Sobrescrever imagem",
	'img_overwrite_msg%s%s' =>
		"A imagem\n\n%s\n\njá existe!\n\nDeseja realmente substituir a imagem por\n\n%s ?",	
	'img_remove_title' =>
		"Remover imagem",
	'img_remove_msg%s' =>
		"Deseja realmente  remover a imagem %s ?",
	'img_remove_error_title' =>
		"ERRO",
	'img_remove_error_msg%s' =>
		"Não foi possível remover a imagem %s !",
	'conf_platform_update_title' =>
		"Actualizar ini da plataforma",
	'conf_platform_update_msg%s' =>
		"Deseja realmente actualizar o INI da plataforma %s ?",
	'conf_platform_emu_filechooser_title%s' =>
		"Seleccione um emulador para a extensão '%s'",
	'conf_userfolder_notset_title' =>
		"ERRO - Não foi possível encontrar directório",
	'conf_userfolder_notset_msg%s' =>
		"Você alterou os destinos do directório no ecc_general.ini. Este directório não foi criado por enquanto.\n\nGostaria de criar o directório\n\n%s\n\nagora?\n\nSe desejar escolher outro directório, clique em NÃO e escolha \n'Opções' -> 'Configuração'\npara definir sua pasta de utilizador!",
	'conf_userfolder_error_readonly_title' =>
		"ERRO - Não foi possível criar directório!!!",
	'conf_userfolder_error_readonly_msg%s' =>
		"O directório %s não pode ser criado porque escolheu um sítio somente de leitura(read-only).\n\nSe desejar escolher outro directório, clique em OK e escolha \n'Opções' -> 'Configuração'\npara definir a sua pasta de utilizador!",
	'conf_userfolder_created_title' =>
		"Pasta de utilizador criada!",
	'conf_userfolder_created_msg%s%s' =>
		"As subpastas\n\n%s\n\nforam criadas na sua pasta de utilizar\n\n%s",
	'conf_ecc_save_title' =>
		"Actualizar GLOBAL-INI do emuControlCenter",
	'conf_ecc_save_msg' =>
		"Esta operação irá escrever as mudanças nas definições para o ecc_global.ini.\n\nTambém irá criar a pasta de utilizador e as subpastas necessárias.\n\nDeseja continuar?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Seleccione o directório para seus dados de usuário",
	'fav_remove_all_title' =>
		"Remoção de favoritos",
	'fav_remove_all_msg' =>
		"Você realmente deseja apagar todos os favoritos?",
	'maint_empty_history_title' =>
		"Limpar history.ini?",
	'maint_empty_history_msg' =>
		"Esta operação irá limpar o arquivo history.ini (histórico). Este arquivo armazena as suas preferências do eCC, como opções (ex. esconder roms duplicadas) e caminhos de directório seleccionados!\n\nDeseja continuar?",
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
		"Só pode iniciar um processo de cada vez como analisar/importar/exportar! Por favor, aguarde até fechar o processo em execução!",
	'meta_rating_add_error_msg' =>
		"Só pode avaliar uma ROM que contenha metadados.\n\nPor favor, utilize a função de edição e adicione os dados necessários!",
	'maint_unset_ratings_title' =>
		"Remoção de avaliações dos jogos",
	'maint_unset_ratings_msg' =>
		"Esta operação removerá todas as avaliações da base de dados... deseja continuar?",
	'eccdb_title' =>
		"Banco de dados de jogos",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"gamedb - Estatísticas:\n\n%s adicionado(s)\n%s ainda em vigor\n%s erro(s)\n\n%s fichier(s) sets de dados processados!%s",
	'eccdb_webservice_post_msg' =>
		"gamedb - Banco de dados:\n\nPara ajudar a comunidade do emuControlCenter, você pode adicionar os seus metadados (título, género, ano, etc.) no gameDB (banco de dados de jogos).\n\nO gamedb funciona como o conhecido CDDB (banco de dados musical).\n\nSe você confirmar esta operação, o eCC irá automaticamente transferir os seus metadados para o gamedb!\n\nPrecisa estar conectado à internet para adicionar as informações!!!\n\nApós sets de 10 metadados processados você precisa confirmar para adicionar mais!",
	'eccdb_error' =>
		"gamedb - Erros:\n\nTalvez não esteja conectado à internet... Somente com uma conexão activa à internet será possível adicionar dados à gamedb!",
	'eccdb_no_data' =>
		"gamedb - Nenhum dado adicional encontrado:\n\nPrecisa editar mais os seus metadados para adicioná-los no gamedb. Faça uma edição e tente novamente!",
		
	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Esta opção irá procurar por ROMs duplicadas na base de dados e irá exibir as que encontrar.\n\nPoderá encontrar o ficheiro de histórico (log) no seu directório ecc-logs!",
	
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
		"Ficheiro GRANDE encontrado!!!\n\nA rom\n\nNome: %s\nTamanho: %s\n\né muito grande! Pode ficar um bom tempo sem resposta do emuControlCenter.\n\nDeseja analisar esta rom?",

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
		"Deseja realmente actualizar a plataforma\n\n%s\n\ncom informações da base de dados online (gameDB) do emuControlCenter?\n\nUma conexão com a internet é necessária para realizar esta operação!",
	
	'eccdb_webservice_get_datfile_error_title' =>
		"Não foi possível importar o ficheiro dat",
	'eccdb_webservice_get_datfile_error_msg' =>
		"Precisa estar conectado à internet. Por favor, conecte-se e tente novamente!",

	'romparser_fileext_problem_title%s' =>
		"Problema na extensão %s",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter encontrou mais de uma plataforma que utiliza a extensão %s nas suas roms!\n\n%s\nTem certeza de que somente roms de %s estão localizadas no directório indicado %s\n\n<b>Ok</b>: Procurar por %s neste directório!\n\n<b>CANCELAR</b>: Não procurar pela extensão %s neste directório!\n",

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
		"Selecione um ficheiro dat do ClrMAME",
	'importDatCMConfirmTitle' =>
		"Importar dat ClrMAME",
	'importDatCMConfirmMsg%s%s%s' =>
		"Deseja realmente importar dados para a plataforma\n\n%s (%s)\n\ndo datfile\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Actualizar informações",
	'romAuditReparseMsg%s' =>
		"Isto irá actualizar as informações de uma rom multiarquivo (se está completa ou não).\n\nDeseja continuar?",
	'romAuditInfoNotPossibelTitle' =>
		"Não está disponível",
	'romAuditInfoNotPossibelMsg' =>
		"Informações de auditoria estão disponíveis somente para roms multiarquivos, como por exemplo as roms de arcade!",

	'romReparseAllTitle' =>
		"Analisar directório de roms",
	'romReparseAllMsg%s' =>
		"Procurar por novas roms para a(s) seguinte(s) plataforma(s)?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Extensões multiplataforma",
	'parserUnsetExtMsg%s' =>
		"Como você selecionou '#Todas encontradas', o eCC tem que saltar extensões duplicadas na pesquisa para prevenir atribuições incorrectas na base de dados!\n\nO emuControlCenter não pesquisará por: %s\n\nPor favor, selecione a plataforma correcta para analisar estas extensões!\n\n",

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
		"Deseja realmente cancelar o processo em execução?",
	'processDoneTitle' =>
		"Fim!",
	'processDoneMsg' =>
		"O processo foi concluído com sucesso!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"A cópia de segurança (backup) XML com seus dados foi criado em ecc-user/#_GLOBAL/ pasta\n\n%s\n\nDeseja ver o ficheiro XML agora?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Esta tarefa pode levar algum tempo",
	'executePostShutdownTaskMessage%s' =>
		"\nAviso: <b>%s</b>\n\nDeseja realmente executar esta longa tarefa?",
	'postShutdownTaskTitle' =>
		"Executar tarefa selecionada",
	'postShutdownTaskMessage' =>
		"Selecionou uma tarefa que só pode ser executada com o emuControlCenter fechado.\n\nApós esta tarefa, <b>o emuControlCenter irá reiniciar automaticamente!</b>\n\nIsto pode levar alguns segundos, alguns minutos ou algumas horas! Esta janela congelará! Respire fundo! :-)\n\n<b>Por favor, aguarde!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Ficheiro não encontrado...",
	'startRomFileNotAvailableMessage' =>
		"Ei, parece que  não tem esta rom!\n\nTente novamente, mas antes selecione em 'Visão' => 'todas (que possuo)' :-)",
	'startRomWrongFilePathTitle' =>
		"A Rom encontra-se na base de dados mas o ficheiro não foi encontrado!",
	'startRomWrongFilePathMessage' =>
		"Apagou ou moveu de posição as suas roms?\n\nPor favor, use a opção 'ROMS' -> 'Optimizar roms' para limpar a base de dados!",
	
	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Descarregar imagens",
	'waitForImageInjectMessage' =>
		"Esta tarefa pode levar algum tempo. Se forem encontradas imagens esta janela fechará automaticamente e você verá as imagens na lista!\n\nSe não forem encontradas imagens esta janela fechará automaticamente e você não verá imagens na lista... :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Quer realmente copiar/mover os ficheiros por resultados de pesquisa",
	'copy_by_search_msg_waring%s%s%s' =>
		"Esta opção irá copiar/renomear TODOS os jogos encontrandos nos resultados da sua pesquisa (Atenção: se não tiver pesquisado, todos os ficheiros serão selecionados!)\n\nPode escolher o destino na próxima janela.\n\nForam encontrados <b>%s jogos</b> nos resultados da sua pesquisa\n\n<b>%s jogos comprimidos</b> são saltados!\n\nDeseja mesmo copiar/mover estes jogos <b>%s</b> para outra localização?",
	'copy_by_search_msg_error_noplatform' =>
		"Tem que selecionar uma plataforma para usar esta capacidade. Não é possível usar esta função para TODOS os ENCONTRADOS!",
	'copy_by_search_msg_error_notfound%s' =>
		"Não foram encontrados jogos válidos no resultado da sua pesquisa</b> saltado(s).",
	'searchTab' =>
		"Resultado de pesquisa",
	'searchDescription' =>
		"Aqui pode copiar ou mover ficheiros do seu directório original ou mover para outro especificado.\n<b>A fonte é o seu actual resultado de pesquisa.</b>\nSe mover, os destinos da sua base de dados serão actualizados! Limpo por checksum remove ficheiros qu são 100% duplicados!",
	'searchHeadlineMain' =>
		"Introducãon",
	'searchHeadlineOptionSameName' =>
		"mesmo nome",
	'searchRadioDuplicateAddNumber' =>
		"adicionar número",
	'searchRadioDuplicateOverwrite' =>
		"sobrescrever",
	'searchCheckCleanup' =>
		"limpar por checksum",

);
?>
