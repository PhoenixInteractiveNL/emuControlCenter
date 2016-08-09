<?
/**
 * emuControlCenter language system file
 * ------------------------------------------
 * language:	ru (russian)
 * author:	ALLiGaToR
 * date:	2009/07/09
 * ------------------------------------------
 */
$i18n['popup'] = array(
	'rom_add_filechooser_title%s' =>
		"%s: Укажите вашу папку медиа!",
	'rom_add_parse_title%s' =>
		"Добавить новые РОМы для %s",
	'rom_add_parse_msg%s%s' =>
		"Добавить новые РОМы для\n\n%s\n\nиз папки\n\n%s?",
	'rom_add_parse_done_title' =>
		"Анализ завершен",
	'rom_add_parse_done_msg%s' =>
		"Анализирование новых \n\n%s\n\nРОМов завершено!",
	'rom_remove_title%s' =>
		"Очистить базу данных для %s",
	'rom_remove_msg%s' =>
		"Вы хотите очистить базу данных для \n\"%s\"?\n\nЭто действие удалит все файлы выбранной игры из ECC базы, но не удалит файл информации и файлы с вашего HDD.",
	'rom_remove_done_title' =>
		"База данных очищена",
	'rom_remove_done_msg%s' =>
		"Вся информация для %s удалена из базы ECC",
	'rom_remove_single_title' =>
		"Убрать РОМ из базы ECC?",
	'rom_remove_single_msg%s' =>
		"Можно убрать \n\n%s\n\nиз ECC базы?",
	'rom_remove_single_dupfound_title' =>
		"Найдены одинаковые РОМы!!!",
	'rom_remove_single_dupfound_msg%d%s' =>
		"%d найдено одинаковых РОМов\n\nНужно удалять все найденные дубликаты для \n\n%s\n\n из базы данных ECC?\n\nСмотрите файл справки подробнее!",
	'rom_optimize_title' =>
		"Оптимизировать базу данных игр",
	'rom_optimize_msg' =>
		"Вы хотите оптимизировать ваши РОМы в базе ECC?\n\nВам необходимо оптимизировать базы в том случае, если вы перемещали или удаляли файлы с вашего жесткого диска.\nECC автоматически найдет и удалит такие записи из базы!\nЭта опция изменяется только в базе.",
	'rom_optimize_done_title' =>
		"Оптимизация завершена!",
	'rom_optimize_done_msg%s' =>
		"База данных для \n\n%s\n\nоптимизирована!",
	'rom_dup_remove_title' =>
		"Убрать одинаковые РОМы из базы ECC?",
	'rom_dup_remove_msg%s' =>
		"Вы хотите убрать все одинаковые РОМы для\n\n%s\n\nиз базы данных ECC?\n\nЭта операция работает только без emuControlCenter Database....\n\nВаши файлы на жестком диске не удалятся!",
	'rom_dup_remove_done_title' =>
		"Удаление завершено!",
	'rom_dup_remove_done_msg%s' =>
		"Все одинаковые РОМы для\n\n%s\n\nбыли удалены из базы",
	'rom_reorg_nocat_title' =>
		"Нет категорий!",
	'rom_reorg_nocat_msg%s' =>
		"Вы не задали ни одной категории для РОМов\n\n%s\n\n! Пожалуйста, отредактируйте и добавьте несколько категорий или добавьте нормальный файл базы данных ECC!",
	'rom_reorg_title' =>
		"Систематизировать РОМы на жестком диске?",
	'rom_reorg_msg%s%s%s' =>
		"------------------------------------------------------------------------------------------Эта опция систематизирует ваши РОМы на жестком диске! Пожалуйста, сначала удалите одинаковые РОМы из базы ECC!\nВыбранный режим: #%s#\n------------------------------------------------------------------------------------------\n\nВы хотите систематизировать РОМы по категории для\n\n%s\n\nна жестком диске? ECC систематизирует РОМы в папке пользователя ECC\n\n%s/roms/organized/\n\nПожалуйста, проверьте наличие свободного места на жестком диске\n\nЕсли что случится, авторы и переводчик тут не при чем :-)",
	'rom_reorg_done_title' =>
		"Систематизировано!",
	'rom_reorg_done__msg%s' =>
		"Посмотрите в папке\n\n%s\n\nдля достоверности",
	'db_optimize_title' =>
		"Оптимизировать систему базы данных",
	'db_optimize_msg' =>
		"Вы хотите оптимизировать базу данных?\nЭто позволит уменьшить размер самой базы. \n\nПрограмма ненадолго зависнет, подождите пожалуйста! :-)",
	'db_optimize_done_title' =>
		"База оптимизирована!",
	'db_optimize_done_msg' =>
		"Ваша ECC база данных теперь оптимизирована!",
	'export_esearch_error_title' =>
		"Опция eSearch выбрана",
	'export_esearch_error_msg' =>
		"Вы хотите использовать eSearch (расширенный поиск), чтобы использовать эту функцию экспорта. Сохранятся только результаты поиска!",
	'dat_export_filechooser_title%s' =>
		"Укажите папку куда сохранить %s файл базы!",
	'dat_export_title%s' =>
		"Экспорт %s файла базы",
	'dat_export_msg%s%s%s' =>
		"Вы хотите экспортировать %s файл базы для\n\n%s\n\nв эту папку?\n\n%s",
	'dat_export_esearch_msg_add' =>
		"\n\nECC будет использовать результаты eSearch для экспорта!",
	'dat_export_done_title' =>
		"Экспорт завершен",
	'dat_export_done_msg%s%s%s' =>
		"Экспорт %s файла базы для\n\n%s\n\nв папку\n\n%s\n\nзавершен!",
	'dat_import_filechooser_title%s' =>
		"Импорт: Выберите %s файл базы!",
	'rom_import_backup_title' =>
		"Создать бекап?",
	'rom_import_backup_msg%s%s' =>
		"Создать бекап в вашу папку пользователя для\n\n%s (%s)\n\nдо импортирования новых данных?",
	'rom_import_title' =>
		"Импортировать файл базы?",
	'rom_import_msg%s%s%s' =>
		"Вы точно хотите импортировать данные для \n\n%s (%s)\n\nиз файла базы\n\n%s?",
	'rom_import_done_title' =>
		"Импортирование завершено!",
	'rom_import_done_msg%s' =>
		"Импортирование файла базы для\n\n%s\n\nзавершено!",
	'dat_clear_title%s' =>
		"Очистить файл базы для %s",
	'dat_clear_msg%s%s' =>
		"Вы точно хотите очистить всю информацию для\n\n%s (%s)-базы?\n\nУдлится вся информация: и категории, и статут, и язык. Вы также можете сделать бекап этой информации. (автоматически создастся в вашей папке пользователя!)\n\nСледующим этапом будет оптимизация базы данных!",
	'dat_clear_backup_title%s' =>
		"Бекап %s",
	'dat_clear_backup_msg%s%s' =>
		"Создать бекап для \n\n%s (%s)?",
	'dat_clear_done_title%s' =>
		"Очистка базы завершена!",
	'dat_clear_done_msg%s%s' =>
		"Все метаданные для\n\n%s (%s)\n\nудалены из базы данных ECC!",
	'dat_clear_done_ifbackup_msg%s' =>
		"\n\n ECC зарезервировал ваши данные в %s-папку пользователя",
	'emu_miss_title' =>
		"Ошибка - эмулятор не найден!",
	'emu_miss_notfound_msg%s' =>
		"Назначенный эмулятор не найден. Зеленый индикатор показывает что эмулятор найден, красный показывает неправильно указанный путь к эмулятору.",
	'emu_miss_notset_msg' =>
		"Вы не назначили ни одного эмулятора для этой системы",
	'emu_miss_dir_msg%s' =>
		"Назначают папку!",
	'img_overwrite_title' =>
		"Перезаписать изображение?",
	'img_overwrite_msg%s%s' =>
		"Изображение\n\n%s\n\nуже существует\n\nВы хотите перезаписать его с\n\n%s?",
	'img_remove_title' =>
		"Удалить изображение?",
	'img_remove_msg%s' =>
		"Вы точно хотите удалить изображение %s",
	'img_remove_error_title' =>
		"Ошибка - Не найдено изображение!",
	'img_remove_error_msg%s' =>
		"Изображение %s не может быть удалено!",
	'conf_platform_update_title' =>
		"Обновить файл настроек систем?",
	'conf_platform_update_msg%s' =>
		"Вы точно хотите обновить файл настроек для %s?",
	'conf_platform_emu_filechooser_title%s' =>
		"Выберите эмулятор для файлов с расширением '%s'",
	'conf_userfolder_notset_title' =>
		"Ошибка: не найдена папка!",
	'conf_userfolder_notset_msg%s' =>
		"Вы хотите изменить путь к базам в файле ecc_general.ini. эта папка еще не создана.\n\nСоздать папку\n\n%s\n\n?\n\nЕсли вы хотите выбрать другую папку, нажмите НЕТ и используйте\n'Опции'->'Настройка'\nчтобы назначить ваши папку пользователя!",
	'conf_userfolder_error_readonly_title' =>
		"Ошибка: невозможно создать папку!",
	'conf_userfolder_error_readonly_msg%s' =>
		"Папку %s нельзя создать потому, что вы выбрали носитель информации только для чтения (CD?)\n\nЕсли вы хотите выбрать другую папку, нажмите ОК и выберите \n'Опции'->'Настройка'\nчтобы назначить вашу папку пользователя!",
	'conf_userfolder_created_title' =>
		"Папка пользователя создана!",
	'conf_userfolder_created_msg%s%s' =>
		"Подпапка\n\n%s\n\nсоздана в вашей папке пользователя\n\n%s",
	'conf_ecc_save_title' =>
		"Обновить файл ECC GLOBAL-INI?",
	'conf_ecc_save_msg' =>
		"Эта опция запишет все ваши настройки в файл ecc_global.ini\n\nТакже создаст папку пользователя и все необходимые подпапки\n\nВы точно хотите обновить?",
	'conf_ecc_userfolder_filechooser_title' =>
		"Выберите папку для пользовательской информации",
	'fav_remove_all_title' =>
		"Удалить все закладки?",
	'fav_remove_all_msg' =>
		"Вы точно хотите удалить все закладки?",
	'maint_empty_history_title' =>
		'Очистить ecc history.ini?',
	'maint_empty_history_msg' =>
		'Эта опция очистит файл истории ecc history.ini. Очистить этот файл?',
	'sys_dialog_info_miss_title' =>
		"?? НАЗВАНИЕ ПОТЕРЯНО ??",
	'sys_dialog_info_miss_msg' =>
		"?? СООБЩЕНИЕ ПОТЕРЯНО ??",
	'sys_filechooser_miss_title' =>
		"?? НАЗВАНИЕ ПОТЕРЯНО ??",
	'status_dialog_close' =>
		"\n\nЗакрыть детальный статус?",
	'status_process_running_title' =>
		"Процесс идет",
	'status_process_running_msg' =>
		"Еще один процесс запущен\nВы можете начать только один процесс типа импорта, экспорта, анализа! Пожалуйста, подождите пока завершится текущий процесс!",
	'meta_rating_add_error_msg' =>
		"Вы можете изменить рейтинг только у РОМа с метеданными.\n\nПожалуйста измените и создайте эти метаданные!",
	'maint_unset_ratings_title' =>
		"Убрать рейтинги для этой системы?",
	'maint_unset_ratings_msg' =>
		"Это сбросит все рейтинги в базе данных. Продолжить?",
	'eccdb_title' =>
		"eccdb/romdb",
	'eccdb_statistics_msg%s%s%s%s%s' =>
		"eccdb - Статистика:\n\n%s добавлено\n%s allready inplace\n%s ошибок\n\n%s Datasets processed!%s",
	'eccdb_webservice_post_msg' =>
		"eccdb/romdb - Metadatabase:\n\nTo support the emuControlCenter community, you can add your modified metadata (title, category, languages aso.) into the ECCDB (Internet Database).\n\nThis works like the well known CDDB for CD-Tracks.\n\nIf you confirm this, ecc will automaticly transfer you data into the eccdb!\n\nYou have to be connected to the internet to add your content!!!\n\nAfter 10 processed Metadatasets, you have to confirm to add more!",
	'eccdb_error' =>
		"eccdb - Ошибки:\n\nВозможно вы не соединены с интернетом... Только в онлайн-режиме вы можете добавить информацию в базу данных!",
	'eccdb_no_data' =>
		"eccdb - Не найдена информация:\n\nВы должны изменить немного информации, чтобы добавить в базу. Используйте кнопку изменить и попытайтесь снова!",

	/* 0.9 FYEO 9 */
	'rom_dup_remove_msg_preview%s' =>
		"Эта опция находит одинаковые РОМы в вашей базе данных и удалит найденные РОМы\n\nВы можете также посмотреть логфайл в папке ecc-logs!",

	/* 0.9.1 FYEO 3 */
	'img_remove_all_title' =>
		"Удалить все изображения?",
	'img_remove_all_msg%s' =>
		"Эта опция удалит все изображения для выбранной игры!\n\nУдалить изображения для\n\n%s?",

	/* 0.9.1 FYEO 6 */
	'sys_dialog_miss_title' =>
		"подтвердить",
	/* 0.9.2 WIP 11 */
	'parse_big_file_found_title' =>
		"Проанализировать этот файл??",
	'parse_big_file_found_msg%s%s' =>
		"Найден большой файл!!!\n\nThe found game\n\nName: %s\nSize: %s\n\nis very large. This can take a long time without direct feedback of emuControlCenter.\n\nDo you want parse this game?",

	/* 0.9.5 WIP 19 */
	'bookmark_added_title' =>
		"Сохранение закладок",
	'bookmark_added_msg' =>
		"Закладка была добавлена!",
	'bookmark_removed_single_title' =>
		"Удаление закладок",
	'bookmark_removed_single_msg' =>
		"Закладка была удалена!",
	'bookmark_removed_all_title' =>
		"Все закладки удалены",
	'bookmark_removed_all_msg' =>
		"Все закладки были удалены!",

	/* 0.9.6 FYEO 1 */
	'eccdb_webservice_get_datfile_title' =>
		"Обновить файл базы через интернет",
	'eccdb_webservice_get_datfile_msg%s' =>
		"Вы точно хотите обновить систему\n\n%s\n\nс данными через emuControlCenter romDB?\n\nТребуется стабильное интернет соединение",

	'eccdb_webservice_get_datfile_error_title' =>
		"Невозможно импортировать файл базы",
	'eccdb_webservice_get_datfile_error_msg' =>
		"У вас должен быть включен интернет. Пожалуйста, соединитесь и попробуйте еще раз!",

	'romparser_fileext_problem_title%s' =>
		"Проблема с расширением %s",
	'romparser_fileext_problem_msg%s%s%s%s%s%s' =>
		"emuControlCenter определил, что более одной системы использует расширение %s для запуска РОМов!\n\n%s\nВы уверены, что находятся только %s игры для этой системы в папке РОМов %s\n\n<b>OK</b>: Поиск для %s в этой папке / системе!\n\n<b>Отмена</b>: Отменить ассоциацию %s для этой папки / системы!\n",

	/* 0.9.6 FYEO 8 */
	'rom_dup_remove_title_preview' =>
		"Поиск одинаковых РОМов",
	'rom_dup_remove_done_title_preview' =>
		"Поиск завершен",
	'rom_dup_remove_done_msg_preview' =>
		"Детали вы увидите в поле статуса!",
	'metaRemoveSingleTitle' =>
		"Удалить метаданные РОМа",
	'metaRemoveSingleMsg' =>
		"Вы точно хотите удалить метаданные этого РОМа?",

	/* 0.9.6 FYEO 11 */

	'importDatCMFilechooseTitle%s' =>
		"Выберите ClrMAME датфайл!\n",
	'importDatCMConfirmTitle' =>
		"Импорт ClrMAME датфала",
	'importDatCMConfirmMsg%s%s%s' =>
		"Вы точно хотите импортировать данные для системы\n\n%s (%s)\n\nиз файла данных\n\n%s?",

	/* 0.9.6 FYEO 13 */
	'romAuditReparseTitle' =>
		"Обновить информацию проверки РОМов",
	'romAuditReparseMsg%s' =>
		"Это обновит сохраненную информацию\n\nОбновить?",
	'romAuditInfoNotPossibelTitle' =>
		"Нет информации по проверке РОМов",
	'romAuditInfoNotPossibelMsg' =>
		"Информация о проверке возможна только для систем с РОМами, состоящими из нескольких файлов, например Аркад!",

	'romReparseAllTitle' =>
		"Проанализировать папку РОМов",
	'romReparseAllMsg%s' =>
		"Найти новые РОМы для выбранных систем?\n\n%s",

	/* 0.9.6 FYEO 15 */
	'parserUnsetExtTitle' =>
		"Отменить эти ассоциации",
	'parserUnsetExtMsg%s' =>
		"Так как вы выбрали режим '#Все найденные', ECC не должен допустить одинаковых расширений!\n\nemuControlCenter не смог найти: %s\n\nПожалуйста, выберите правильную систему для ассоциации файлов!\n\n",

	'stateLabelDatExport%s%s' =>
		"Экспорт %s датфайла for %s",
	'stateLabelDatImport%s' =>
		"Импорт датфайла для %s",

	'stateLabelOptimizeDB' =>
		"Оптимизировать базу данных",
	'stateLabelVacuumDB' =>
		"Очистить базу данных",
	'stateLabelRemoveDupRoms' =>
		"Убрать одинаковые РОМы",
	'stateLabelRomDBAdd' =>
		"Добавить информацию к базе",
	'stateLabelParseRomsFor%s' =>
		"Анализировать РОМы для %s",
	'stateLabelConvertOldImages' =>
		"Конвертирование изображений...",

	'processCancelConfirmTitle' =>
		"Cancel current process?",
	'processCancelConfirmMsg' =>
		"Do you really want to cancel this running process?",
	'processDoneTitle' =>
		"Process completed!",
	'processDoneMsg' =>
		"The process has been completed!",

	/* 0.9.7 FYEO 11 */
	'userdata_backuped_in%s' =>
		"Файл бекапа XML с вашими данными был создан в вашей /#_GLOBAL/ папке\n\n%s\n\nПросмотреть этот файл сейчас в браузере?",

	/* 0.9.7 FYEO 17 */
	'executePostShutdownTaskTitle' =>
		"Точно хотите запустить этот процесс в фоновом режиме?",
	'executePostShutdownTaskMessage%s' =>
		"\nПроцесс: <b>%s</b>\n\nВы точно хотите запустить этот долгий процесс?",
	'postShutdownTaskTitle' =>
		"Запустить выбранный процесс",
	'postShutdownTaskMessage' =>
		"Вы выбрали процесс, который запустится только при закрытии emuControlCenter.\n\nПосле этого процесса <b>emuControlCenter сам запустится!</b>\n\nЭто займет всего несколько секунд, возможно минут и даже часов! Это сообщение пока зависнет, но не бойтесь! :-)\n\n<b>Пожалуйста, подождите!</b>",

	/* 0.9.8 FYEO 02 */
	'startRomFileNotAvailableTitle' =>
		"Файл РОМа не найден...",
	'startRomFileNotAvailableMessage' =>
		"Кажется, у вас нет этого РОМа!\n\nМожет вы попытаетесь еще раз после выбора режима просмотра 'Все (которые имеются)' :-)",
	'startRomWrongFilePathTitle' =>
		"РОМ в базе данных не найден",
	'startRomWrongFilePathMessage' =>
		"Может вы переместили РОМы в другую папку или удалили их?\n\nПожалуйста, выберите опцию 'РОМы' -> 'Оптимизировать РОМы' для очистки базы данных!",

	/* 0.9.8 FYEO 05 */
	'waitForImageInjectTitle' =>
		"Скачать изображения",
	'waitForImageInjectMessage' =>
		"Этот процесс займет немного времени. Если изображения найдутся, это окно закроется автоматически и вы увидите список изображений!\n\nЕсли изображений нет, это окно закроется и главный лист не обновится! :-)",

	/* 1.0.0 FYEO 02 */
	'copy_by_search_title' =>
		"Точно хотите скопировать/переместить файлы по результатам поиска?",
	'copy_by_search_msg_waring%s%s%s' =>
		"Эта опция скопирует/переместит все найденные игры (внимание: если вы не использовали поиск, то выберутся все файлы!)\n\nВы можете выбрать адрес в следующем окне.\n\nНайдено <b>%s игр</b> по результатам поиска\n\n<b>%s запакованных игр</b> пропущено!\n\nВы точно хотите скопировать/переместить эти <b>%s</b> игры в другую папку",
	'copy_by_search_msg_error_noplatform' =>
		"Вы должны выбрать систему, чтобы использовать эту функцию. Кстати, ее невозможно применить для всех найденных игр!",
	'copy_by_search_msg_error_notfound%s' =>
		"Ни одной правильной игры не найдено через поиск. <b>%s запакованных игр</b> пропущено.",
	'searchTab' =>
		"Результат поиска",
	'searchDescription' =>
		"Здесь вы можете скопировать или переместить файлы из их первоначальной папки в другую.\n<b>Первоначальная папка - та которая нашлась через поиск.</b>\nЕсли вы переместите файлы, то все новые пути автоматически запишутся в базе данных! Очистка по контрольной сумме удаляет файлы-дубликаты на 100%!",
	'searchHeadlineMain' =>
		"Введение",
	'searchHeadlineOptionSameName' =>
		"то же имя",
	'searchRadioDuplicateAddNumber' =>
		"добавить номер",
	'searchRadioDuplicateOverwrite' =>
		"перезаписать",
	'searchCheckCleanup' =>
		"очищено по контрольной сумме",

);
?>