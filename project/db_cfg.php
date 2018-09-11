<?php

//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

/////////////////////////////////////////////////////////
//
// ФАЙЛ КОНФИГУРАЦИИ БАЗЫ ДАННЫХ
//
// Зарезервированные таблицы users|rightgroups|viewgroups|formgroups|forms|formsitem
// зарезервированные поля таблиц (ID, PID, LID)
// типы таблиц $db."|TYPE" (line, tree, ltree)
// типы содержимого поля $db."/".$field:
//
//	     ЧИСЛА
//
//		integer		- целое число
//		pinteger	- целое положительное число
//		real		- нецелое число
//		preal		- нецелое положительное число
//		money		- нецелое число, отображение как деньги
//		pmoney		- нецелое положительное число, отображение как деньги
//
//	     СТАТУСЫ
//
//		boolean		- галочка
//		state		- выбор статуса из выпадающего списка (ограничивает редактирование см. HOLDBY, HOLDDEL и т.д.)
//		alist		- выпадающий список константа
//
//	     ТЕКСТОВЫЕ
//
//		tinytext	- текст длиной до 255 символов (поле одна строка)
//		text		- текст длинный (поле одна строка)
//		textarea	- текст длинный (самораздвигающийся <teaxtarea>)
//		mediumtext	- текст длинный не выводится в формах (используется для пользовательских целей)
//
//	     ДАТА ВРЕМЯ
//
//		date		- дата
//		time		- время
//		dateplan	- дата план (запоминает историю)
//
//	     ФАЙЛЫ
//
//		file		- хранение файла
//
//	     СВЯЗИ С ДРУГИМИ ТАБЛИЦАМИ
//
//		droplist	- выпадающий список (привязка к одному ID)
//		list		- список с поиском (привязка к одному ID)
//		multilist	- выпадающий список (привязка ко многим ID)
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//	ПРИМЕР ОПИСАНИЯ ТАБЛИЦЫ
//
//	$table = "db_test";							// Имя таблицы в БД
//
//	$db_cfg[$table."|TYPE"] = "line";					// Тип таблицы - линейная
//	$db_cfg[$table."|ERP"] = "false";					// Не относится к ядру системы
//
//	$db_cfg[$table."|MORE"] = "false";					// Пояснение к таблице данных
//	$db_cfg[$table."|DELRIGHT"] = "ID_users";				// Права на удаление только у users/ID совпадающего с полем ID_users и у DELETE
//	$db_cfg[$table."|CREATEBY"] = "ID_users";				// Запоминать создателя элемента в это поле
//	$db_cfg[$table."|CREATEDATE"] = "DATE";					// Запоминать дату создания элемента в это поле
//	$db_cfg[$table."|EDITTIME"] = "ETIME";					// Запоминать дату время последнего изменения ч/з стандартный интерфейс
//	$db_cfg[$table."|EDITUSER"] = "EUSER";					// Запоминать user_id внёсшего последние изменения ч/з стандартный интерфейс
//	$db_cfg[$table."|HOLDBY"] = "SOGL";					// Поля типа state ограничивающие редактирование после установки статуса (либо поле LID для ltree например)
//	$db_cfg[$table."|HOLDDEL"] = "SOGL";					// Поля типа state ограничивающие удаление строки после установки статуса
//	$db_cfg[$table."|DELWITH"] = "";					// $db/$field Удалять вместе с элементом элементы из $db где $field=LIST_ID этого элемента
//	$db_cfg[$table."|ADDWITH"] = "";					// $db/$field Добавить вместе с элементом элемент в $db и записать в $field=LIST_ID этого элемента
//	$db_cfg[$table."|ONCREATE"] = "add_db_test.php";			// Файл php который будет использован после создания элемента (при автосоздании будет только для основного элемента)
//	$db_cfg[$table."|ONDELETE"] = "del_db_test.php";	//$delet_id (ИД удаляемого элемента)		// Файл php который будет использован после удаления элемента (при автосоздании будет только для основного элемента)
//	$db_cfg[$table."|BYPARENT"] = "VAL";					// Поля которые при создании будут скопированы с PID если таковой есть
//	$db_cfg[$table."|MAXDEEP"] = 2;						// Максимальная глубина для tree и ltree (если не определено то без ограничений)
//
//	$db_cfg[$table."|LIST_FIELD"] = "ID";					// Поля для отображения при выборе ссылки (связи) с других таблиц
//	$db_cfg[$table."|LIST_SEARCH"] = "NAME";				// Поля по которым производится поиск (для list, multilist и т.д.)
//	$db_cfg[$table."|LIST_PREFIX"] = ", ";
//	$db_cfg[$table."|ADDINDEX"] = "";
//	$db_cfg[$table."|LID_FIELD"] = "";					// Поля для отображения при выборе ссылки (связи) для ltree
//	$db_cfg[$table."|LID_SEARCH"] = "";					// Поля по которым идёт поиск для ltree
//
//	$db_cfg[$table."|FIELDS"] = "TXT|SOGL|ID_users|VAL";
//
//		$db_cfg[$table."/TXT"] = "textarea";
//		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";			// Права на редактирование только у users/ID совпадающего с полем ID_users
//		$db_cfg[$table."/ID_users"] = "list";
//		$db_cfg[$table."/ID_users|LIST"] = "users";
//		$db_cfg[$table."/SOGL"] = "state";
//		$db_cfg[$table."/SOGL|LIST"] = "согл.|откл.";
//		$db_cfg[$table."/SOGL|HOLD"] = "QWEST|ID_users|DATE|UNSW|SOGL";	// Ограничение на редактирование после установки статуса
//		$db_cfg[$table."/SOGL|USER"] = "ID_users";			// Записать ID пользователя изменившего статус
//		$db_cfg[$table."/SOGL|DATE"] = "DATE";				// Записать дату изменения статуса
//		$db_cfg[$table."/SOGL|ONCHANGE"] = "change_db_test_SOGL.php";	// Файл php который будет использован после изменения статуса
//		$db_cfg[$table."/VAL"] = "pinteger";
//		$db_cfg[$table."/DATE"] = "date";
//		$db_cfg[$table."/ETIME"] = "time";
//
//
//
//
//
//	!	Аккуратно с автодобавлением, возможна рекурсия !!!!
//
/////////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


	$db_cfg["PROJECT"] = "";




   // Конфигурация PROJECT
   //////////////////////////////////////////////////////////////////////////////////////////////////////


	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."db_specialization";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_clients";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krzdet";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krzdetitems";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_1_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_1";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_2";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_2_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_3";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz2";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz2det";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz2detitems";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_arrival_plan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_urface";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ktd_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ktd_izd";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ktd_files";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zak";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zakdet";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_park";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_oper";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_otdel";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_special";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_speclvl";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_shtat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_resurs";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_sti";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_st";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_pci";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_pc";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_operitems";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zadan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zadanrcp";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tabel";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zadanres";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mat_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_sort_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_sort";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zn_zag";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zn_pok";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_contacts";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_it_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_hr_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ogi_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tmc_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zak_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_koop_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_prog_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itrzadan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zn_instr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_places";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_clients_contacts";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_reference_tool";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_cat_tools";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_inout_files";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_inout_files_vidfails";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_vremitr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itr_vremitr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itrzadan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itrzadan_statuses";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_logistic_app";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_storage_areas";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_planzad";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zapros_all";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_online_chat_curid";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_online_chat_curid_users";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_protocols";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_v_mov_adr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_v_reference_tool";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_movements_tool";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_movements_tool_destination";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_stocks_doc";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_v_stocks_doc";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_safety_job";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_stocks_doc_inventory";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_s";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_s_subj_addr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_stock_doctype";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mtk_perehod";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mtk_perehod_img";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_movements_tool_transfer";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_inout_files_vrem";

   // Предустановки setup
   //////////////////////////////////////////////////////////////////////////////////////////////////////

	$db_cfg["SETUP"] = "db_edo_inout_files_vrem"; // в кавычки впишите название таблицы которую хотите удалить (если была такая) и пересоздать













//////////
//	//
//  1	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Виды деятельности
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_specialization";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Виды деятельности";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "";

	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";



//////////
//	//
//  2	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// БД Заказчиков
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_clients";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Заказчики";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_clients_contacts/ID_clients";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "";

	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_specialization|MORE|TEL|ADR|CONT|REKV|GOROD|PROCH|CODE|OBOZ|PZAK|PPOST";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_specialization"] = "list";
		$db_cfg[$table."/ID_specialization|LIST"] = "db_specialization";
		$db_cfg[$table."/MORE"] = "textarea";			// Примечание
		$db_cfg[$table."/TEL"] = "textarea";			// Телефон
		$db_cfg[$table."/ADR"] = "textarea";			// Адрес
		$db_cfg[$table."/CONT"] = "textarea";			// Контакты
		$db_cfg[$table."/REKV"] = "textarea";			// Реквизиты
		$db_cfg[$table."/GOROD"] = "tinytext";			// Город
		$db_cfg[$table."/PROCH"] = "textarea";			// Прочее
		$db_cfg[$table."/CODE"] = "tinytext";			// 1c код
		$db_cfg[$table."/OBOZ"] = "tinytext";			// Полное наименование
		$db_cfg[$table."/PZAK"] = "boolean";			// Признак - заказчик
		$db_cfg[$table."/PPOST"] = "boolean";			// Признак - поставщик







//////////
//	//
//  3	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Типовые КРЗ (1й круг)
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "КРЗ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|HOLDDEL"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "db_krzdet/ID_krz";
	$db_cfg[$table."|ADDWITH"] = "db_krzdet/ID_krz";
	$db_cfg[$table."|ONCREATE"] = "add_db_krz.php";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_clients|ID_postavshik|ID_users|DATE_START|DOGOVOR|SERIYA|DATE_PLAN|DOCS|MORE|MORE2|NORM_PRICE|EXPERT|MORE_EXPERT|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_postavshik"] = "alist";
		$db_cfg[$table."/ID_postavshik|LIST"] = "ОКБ Микрон|Заказчик";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ID_users|LIST_WHERE"] = "STATE='0'";
		$db_cfg[$table."/DATE_START"] = "date";			// Дата запуска
		$db_cfg[$table."/DOGOVOR"] = "tinytext";		// Номер договора
		$db_cfg[$table."/SERIYA"] = "tinytext";			// Перспектива серийности
		$db_cfg[$table."/DATE_PLAN"] = "tinytext";		// Необходимые сроки поставки
		$db_cfg[$table."/DOCS"] = "tinytext";			// Прилагаемые доп документы
		$db_cfg[$table."/NORM_PRICE"] = "preal";		// Цена Н/ч по заказу
		$db_cfg[$table."/EXPERT"] = "list";			// Эксперт
		$db_cfg[$table."/EXPERT|LIST"] = "users";
		$db_cfg[$table."/MORE_EXPERT"] = "textarea";		// Примечание эксперта
		$db_cfg[$table."/MORE"] = "textarea";			// Примечание длинное
		$db_cfg[$table."/MORE2"] = "tinytext";			// Примечание короткое не исп.
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Посчитано";
		$db_cfg[$table."/EDIT_STATE|ONCHANGE"] = "krz_edit_state_change.php";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|ID_clients|ID_postavshik|ID_users|DATE_START|DOGOVOR|SERIYA|DATE_PLAN|DOCS|MORE|NORM_PRICE|EXPERT|MORE_EXPERT";
		$db_cfg[$table."/EDIT_STATE|USER"] = "EXPERT";







//////////
//	//
//  4	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Дерево ДСЕ типовых КРЗ
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krzdet";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Спецификации к КРЗ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_krzdet/PID|db_krzdetitems/ID_krzdet";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_krz";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krz|OBOZ|COUNT|D1|D2|D3|D4|D5|D6|D7|D8|D9|D10|D11|VES";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/COUNT"] = "pinteger";

		$db_cfg[$table."/VES"] = "preal";
		$db_cfg[$table."/D1"] = "preal";
		$db_cfg[$table."/D2"] = "preal";
		$db_cfg[$table."/D3"] = "preal";
		$db_cfg[$table."/D4"] = "preal";
		$db_cfg[$table."/D5"] = "preal";
		$db_cfg[$table."/D6"] = "preal";
		$db_cfg[$table."/D7"] = "preal";
		$db_cfg[$table."/D8"] = "preal";
		$db_cfg[$table."/D9"] = "preal";
		$db_cfg[$table."/D10"] = "preal";
		$db_cfg[$table."/D11"] = "preal";






//////////
//	//
//  5	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Позиции ДСЕ типовых КРЗ
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krzdetitems";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Позиции к элементам спецификации КРЗ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krzdet|TID|PRICE|COUNT";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_krzdet"] = "list";
		$db_cfg[$table."/ID_krzdet|LIST"] = "db_krzdet";
		$db_cfg[$table."/COUNT"] = "preal";
		$db_cfg[$table."/PRICE"] = "preal";
		$db_cfg[$table."/TID"] = "pinteger";





//////////
//	//
//  6	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Каталог документов договоров
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_1_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Каталог документов договоров";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|STATUS";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/STATUS"] = "boolean";




//////////
//	//
//  7	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Документы договоров
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_1";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Документы договоров";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|KRZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|KRZ";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|KRZ|ID_clients|ID_files_1_cat|FILENAME|MORE|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/KRZ"] = "tinytext";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_files_1_cat"] = "list";
		$db_cfg[$table."/ID_files_1_cat|LIST"] = "db_files_1_cat";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Согл.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|KRZ|ID_clients|ID_files_1_cat|FILENAME|EDIT_STATE";






//////////
//	//
//  8	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Каталог документов предприятия
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_2_cat";

	$db_cfg[$table."|MORE"] = "Каталог документов предприятия";
	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|STATUS";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/STATUS"] = "boolean";






//////////
//	//
//  9	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Документы предприятия
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_2";

	$db_cfg[$table."|MORE"] = "Документы предприятия";
	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|TXT";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|TXT|MORE|ID_users|ID_files_2_cat|FILENAME|DATE|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/TXT"] = "tinytext";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ID_users|LIST_WHERE"] = "STATE='0'";
		$db_cfg[$table."/ID_files_2_cat"] = "list";
		$db_cfg[$table."/ID_files_2_cat|LIST"] = "db_files_2_cat";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Согл.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|TXT|MORE|ID_users|ID_files_2_cat|FILENAME|DATE|EDIT_STATE";






//////////
//	//
//  10	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// КРЗ (2й круг)
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz2";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "КРЗ второй круг";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE|EXPERT_STATE";
	$db_cfg[$table."|DELWITH"] = "db_krz2det/ID_krz2|db_arrival_plan/ID_krz2";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_krz2.php";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ZAKNUM|ID_clients|ID_postavshik|ID_users|DATE_START|SERIYA|DATE_PLAN|DOCS|MORE|MORE2|PRICE|EXPERT|MORE_EXPERT|ID_krz|D1|D2|D3|D4|D5|D6|D7|D8|D9|D10|D11|D12|D13|D15|D16|D17|EXPERT_STATE|NORM_PRICE|S1|S2|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ZAKNUM"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_postavshik"] = "alist";
		$db_cfg[$table."/ID_postavshik|LIST"] = "ОКБ Микрон|Заказчик";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE_START"] = "date";			// Дата запуска КРЗ
		$db_cfg[$table."/DATE_PLAN"] = "tinytext";		// Необходимые сроки поставки
		$db_cfg[$table."/PRICE"] = "preal";			// Цена итого по заказу
		$db_cfg[$table."/EXPERT"] = "list";			// Эксперт
		$db_cfg[$table."/EXPERT|LIST"] = "users";
		$db_cfg[$table."/MORE_EXPERT"] = "textarea";		// Примечание эксперта
		$db_cfg[$table."/MORE"] = "textarea";			// Примечание
		$db_cfg[$table."/MORE2"] = "textarea";			// Примечание
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/D1"] = "date";
		$db_cfg[$table."/D2"] = "date";
		$db_cfg[$table."/D3"] = "date";
		$db_cfg[$table."/D4"] = "date";
		$db_cfg[$table."/D5"] = "date";
		$db_cfg[$table."/D6"] = "date";
		$db_cfg[$table."/D7"] = "date";
		$db_cfg[$table."/D8"] = "date";
		$db_cfg[$table."/D9"] = "date";
		$db_cfg[$table."/D10"] = "date";
		$db_cfg[$table."/D11"] = "date";
		$db_cfg[$table."/D12"] = "date";
		$db_cfg[$table."/D13"] = "date";
		$db_cfg[$table."/D14"] = "date";
		$db_cfg[$table."/D15"] = "date";
		$db_cfg[$table."/D16"] = "date";
		$db_cfg[$table."/D17"] = "date";
		$db_cfg[$table."/EXPERT_STATE"] = "state";
		$db_cfg[$table."/EXPERT_STATE|LIST"] = "Актуально";
		$db_cfg[$table."/EXPERT_STATE|HOLD"] = "MORE_EXPERT|MORE";
		$db_cfg[$table."/EXPERT_STATE|USER"] = "EXPERT";
		$db_cfg[$table."/NORM_PRICE"] = "preal";
		$db_cfg[$table."/S1"] = "preal";
		$db_cfg[$table."/S2"] = "preal";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "HOLD";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = $db_cfg[$table."|FIELDS"];







//////////
//	//
//  11	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Дерево ДСЕ КРЗ 2й круг
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz2det";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Спецификации к КРЗ второй круг";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_krz2det/PID|db_krz2detitems/ID_krz2det";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_krz2";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krz2|OBOZ|COUNT|D1|D2|D3|D4|D5|D6|D7|D8|D9|D10|D11|VES";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/COUNT"] = "pinteger";

		$db_cfg[$table."/VES"] = "preal";
		$db_cfg[$table."/D1"] = "preal";
		$db_cfg[$table."/D2"] = "preal";
		$db_cfg[$table."/D3"] = "preal";
		$db_cfg[$table."/D4"] = "preal";
		$db_cfg[$table."/D5"] = "preal";
		$db_cfg[$table."/D6"] = "preal";
		$db_cfg[$table."/D7"] = "preal";
		$db_cfg[$table."/D8"] = "preal";
		$db_cfg[$table."/D9"] = "preal";
		$db_cfg[$table."/D10"] = "preal";
		$db_cfg[$table."/D11"] = "preal";









//////////
//	//
//  12	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Позиции ДСЕ КРЗ 2й круг
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz2detitems";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Позиции к элементам спецификации КРЗ второй круг";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krz2det|TID|PRICE|COUNT";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_krz2det"] = "list";
		$db_cfg[$table."/ID_krz2det|LIST"] = "db_krz2det";
		$db_cfg[$table."/COUNT"] = "preal";
		$db_cfg[$table."/PRICE"] = "preal";
		$db_cfg[$table."/TID"] = "pinteger";









//////////
//	//
//  13	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// План приходов по КРЗ 2й круг
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_arrival_plan";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "План приходов по КРЗ второй круг";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "PLAN";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_krz2|PLAN|DATE|S1|S2";

		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/PLAN"] = "preal";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/S1"] = "preal";
		$db_cfg[$table."/S2"] = "preal";






//////////
//	//
//  14	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Договора и спецификации
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_3";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Договора и спецификации";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|OBOZ|ID_clients";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|ID_clients|FILENAME|MORE|DATE|ARCH|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ARCH"] = "state";
		$db_cfg[$table."/ARCH|LIST"] = "Архив";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Согл.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|OBOZ|ID_clients|FILENAME";










//////////
//	//
//  15	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Каталог КТД
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_ktd_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Каталог КТД";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME";

		$db_cfg[$table."/NAME"] = "tinytext";







//////////
//	//
//  16	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Дерево ДСЕ в КТД
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_ktd_izd";

	$db_cfg[$table."|TYPE"] = "ltree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Дерево ДСЕ в КТД";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "LID";
	$db_cfg[$table."|DELWITH"] = "db_ktd_izd/PID";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_SEARCH"] = "NAME|OBOZ";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_ktd_cat|OBOZ|COUNT";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/ID_ktd_cat"] = "list";
		$db_cfg[$table."/ID_ktd_cat|LIST"] = "db_ktd_cat";
		$db_cfg[$table."/COUNT"] = "pinteger";
		$db_cfg[$table."/LID|HOLD"] = "NAME|OBOZ";







//////////
//	//
//  17	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Документы (файлы) прикреплённые к ДСЕ в КТД
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_ktd_files";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Документы (файлы) прикреплённые к ДСЕ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_SEARCH"] = "NAME|OBOZ";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|DOC|ID_ktd_izd";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/DOC"] = "file";
		$db_cfg[$table."/ID_ktd_izd"] = "list";
		$db_cfg[$table."/ID_ktd_izd|LIST"] = "db_ktd_izd";









//////////
//	//
//  18	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Заказы
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zak";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Заказы";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|HOLDDEL"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "db_zakdet/ID_zak";
	$db_cfg[$table."|ADDWITH"] = "db_zakdet/ID_zak";
	$db_cfg[$table."|ONCREATE"] = "add_db_zak.php";



	$db_cfg[$table."|LIST_FIELD"] = "TID|NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DSE_NAME|DSE_OBOZ|DSE_COUNT|CDATE|NAME|ORD|TID|VIDRABOT|VIDDOG|DOGDATE|SPECDATE|DATE|IZVESH|MAT|PREDOPL|END_DATE|ED|NORM_PRICE|NORM_PRICE_FACT|IZD_CORR|INSZ|MORE|UPAKOVKA|CONTROL_STATE|PRIOR|INGANT|DATE_PLAN|INSTNUM|PD1|PD2|PD3|PD4|PD5|PD6|PD7|PD8|PD9|PD10|PD11|PD12|PD13|PD14|PD15|PD16|PD17|ID_postavshik|KUR|ID_users|ID_users2|ID_clients|ID_krz2|EDIT_STATE|ID_RASPNUM|ID_SOGL|ID_DOGOVOR|ID_SPECIF|ID_SCHET|ID_INVEST|SUMM_N|SUMM_NV|SUMM_NO|SUMM_V";

		$db_cfg[$table."/CDATE"] = "date";
		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "ОЗ|КР|СП|БЗ|ХЗ|ВЗ";
		$db_cfg[$table."/VIDRABOT"] = "textarea";
		$db_cfg[$table."/VIDDOG"] = "alist";
		$db_cfg[$table."/VIDDOG|LIST"] = "Договор подряда|Договор поставки|Прочие договоры";
		$db_cfg[$table."/DOGDATE"] = "tinytext";
		$db_cfg[$table."/SPECDATE"] = "tinytext";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/IZVESH"] = "tinytext";
		$db_cfg[$table."/MAT"] = "boolean";
		$db_cfg[$table."/PREDOPL"] = "boolean";
		$db_cfg[$table."/END_DATE"] = "date";
		$db_cfg[$table."/ED"] = "boolean";
		$db_cfg[$table."/NORM_PRICE"] = "preal";
		$db_cfg[$table."/NORM_PRICE_FACT"] = "preal";
		$db_cfg[$table."/IZD_CORR"] = "boolean";
		$db_cfg[$table."/INSZ"] = "boolean";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/UPAKOVKA"] = "tinytext";
		$db_cfg[$table."/CONTROL_STATE"] = "boolean";
		$db_cfg[$table."/PRIOR"] = "pinteger";
		$db_cfg[$table."/INGANT"] = "boolean";
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/INSTNUM"] = "tinytext";
		$db_cfg[$table."/PD1"] = "dateplan";
		$db_cfg[$table."/PD2"] = "dateplan";
		$db_cfg[$table."/PD3"] = "dateplan";
		$db_cfg[$table."/PD4"] = "dateplan";
		$db_cfg[$table."/PD5"] = "dateplan";
		$db_cfg[$table."/PD6"] = "dateplan";
		$db_cfg[$table."/PD7"] = "dateplan";
		$db_cfg[$table."/PD8"] = "dateplan";
		$db_cfg[$table."/PD9"] = "dateplan";
		$db_cfg[$table."/PD10"] = "dateplan";
		$db_cfg[$table."/PD11"] = "dateplan";
		$db_cfg[$table."/PD12"] = "dateplan";
		$db_cfg[$table."/PD13"] = "dateplan";
		$db_cfg[$table."/PD14"] = "dateplan";
		$db_cfg[$table."/PD15"] = "date";
		$db_cfg[$table."/PD16"] = "date";
		$db_cfg[$table."/PD17"] = "date";
		$db_cfg[$table."/ID_postavshik"] = "alist";
		$db_cfg[$table."/ID_postavshik|LIST"] = "ОКБ Микрон|Заказчик";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Выполнен|Аннулирован|На складе";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "CDATE|NAME|ORD|TID|VIDRABOT|VIDDOG|DOGDATE|SPECDATE|DATE|IZVESH|MAT|PREDOPL|END_DATE|ED|NORM_PRICE|NORM_PRICE_FACT|IZD_CORR|INSZ|MORE|UPAKOVKA|CONTROL_STATE|PRIOR|INGANT|DATE_PLAN|INSTNUM|PD1|PD2|PD3|PD4|PD5|PD6|PD7|PD8|PD9|PD10|PD11|PD12|PD13|PD14|ID_postavshik|KUR|ID_users|ID_users2|ID_clients|ID_krz2|ID_RASPNUM|ID_SOGL|ID_DOGOVOR|ID_SPECIF|ID_SCHET|ID_INVEST|SUMM_N|SUMM_NV|SUMM_NO|SUMM_V";

	// Новое
		$db_cfg[$table."/DSE_NAME"] = "tinytext";
		$db_cfg[$table."/DSE_OBOZ"] = "tinytext";
		$db_cfg[$table."/DSE_COUNT"] = "pinteger";

	// Связи с тем что уже есть
		$db_cfg[$table."/KUR"] = "list";
		$db_cfg[$table."/KUR|LIST"] = "users";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ID_users2"] = "list";
		$db_cfg[$table."/ID_users2|LIST"] = "users";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";

	// Связи с КРЗ и документами
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/ID_RASPNUM"] = "list";
		$db_cfg[$table."/ID_RASPNUM|LIST"] = "db_files_1";
		$db_cfg[$table."/ID_SOGL"] = "list";
		$db_cfg[$table."/ID_SOGL|LIST"] = "db_files_1";
		$db_cfg[$table."/ID_DOGOVOR"] = "list";
		$db_cfg[$table."/ID_DOGOVOR|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_SPECIF"] = "list";
		$db_cfg[$table."/ID_SPECIF|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_SCHET"] = "list";
		$db_cfg[$table."/ID_SCHET|LIST"] = "db_files_1";
		$db_cfg[$table."/ID_INVEST"] = "list";
		$db_cfg[$table."/ID_INVEST|LIST"] = "db_files_1";

	// Итоговые суммы
		$db_cfg[$table."/SUMM_N"] = "preal";	// Объём Н/Ч
		$db_cfg[$table."/SUMM_NV"] = "preal";	// Выполнено Н/Ч
		$db_cfg[$table."/SUMM_NO"] = "preal";	// Осталось Н/Ч
		$db_cfg[$table."/SUMM_V"] = "preal";	// Выполнено %





//////////
//	//
//  19	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ДСЕ в заказах
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zakdet";

	$db_cfg[$table."|TYPE"] = "ltree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ДСЕ в заказах";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_zak";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_MASTER"] = "ID_zak";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_zak|OBOZ|COUNT|RCOUNT|ORD|TID|MTK_OK|NORM_OK|CONTROL_NUM|CLIENT|PRICE_PLAN|PRICE_FACT|WW|HH|LL|MASS|PERCENT|GANT_NP|GANT_NF|GANT_PS|GANT_PP";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/COUNT"] = "pinteger";
		$db_cfg[$table."/RCOUNT"] = "pinteger";
		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "Материал|Покупной|Поковка|Эл. оборуд.";
		$db_cfg[$table."/MTK_OK"] = "boolean";
		$db_cfg[$table."/NORM_OK"] = "boolean";
		$db_cfg[$table."/CONTROL_NUM"] = "pinteger";
		$db_cfg[$table."/PRICE_PLAN"] = "preal";
		$db_cfg[$table."/PRICE_FACT"] = "preal";
		$db_cfg[$table."/CLIENT"] = "tinytext";
		$db_cfg[$table."/WW"] = "preal";
		$db_cfg[$table."/HH"] = "preal";
		$db_cfg[$table."/LL"] = "preal";
		$db_cfg[$table."/MASS"] = "preal";
		$db_cfg[$table."/PERCENT"] = "tinytext";
		$db_cfg[$table."/GANT_NP"] = "preal";
		$db_cfg[$table."/GANT_NF"] = "preal";
		$db_cfg[$table."/GANT_PS"] = "preal";
		$db_cfg[$table."/GANT_PP"] = "preal";





//////////
//	//
//  20	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// БД ОБОРУДОВАНИЯ (СТАНОЧНЫЙ ПАРК)
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_park";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "БД оборудование - станочный парк";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "MARK";
	$db_cfg[$table."|LIST_SEARCH"] = "MARK|NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|SOST|MARK|MORE|TID";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/SOST"] = "alist";
		$db_cfg[$table."/SOST|LIST"] = "Рабочее|Ремонт|Консервация";
		$db_cfg[$table."/MARK"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "Заготовка|Сборка-сварка|Механообработка|Сборка|Термообработка|Упаковка|Окраска";





//////////
//	//
//  21	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ОПРЕДЕЛЕНИЯ ВАРИАНТОВ ОПЕРАЦИЙ
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_oper";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Определения вариантов операций";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "TID|NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|TID|CODE|VID";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/CODE"] = "tinytext";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "Заготовка|Сборка-сварка|Механообработка|Сборка|Термообработка|Упаковка|Окраска|Прочее";
		$db_cfg[$table."/VID"] = "alist";
		$db_cfg[$table."/VID|LIST"] = "Газовым пламенем|Давлением|Контроль|Механическая|Термообработка|Окраска|Вручную|Сварка";





//////////
//	//
//  22	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ОТДЕЛЫ В ЮРЛИЦАХ
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_otdel";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Отделы в Юр. лицах";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|MORE|INSZ";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/INSZ"] = "boolean";





//////////
//	//
//  23	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Специальности
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_special";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Специальности";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";





//////////
//	//
//  24	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Разряды
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_speclvl";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Разряды для штатного расписания";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";





//////////
//	//
//  25	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Штатное расписание
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_shtat";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Штатное расписание";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|ID_special|ID_resurs";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_otdel|ID_special|ID_speclvl|ID_resurs|MORE|BOSS|NOTTAB";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_otdel"] = "list";
		$db_cfg[$table."/ID_otdel|LIST"] = "db_otdel";
		$db_cfg[$table."/ID_special"] = "list";
		$db_cfg[$table."/ID_special|LIST"] = "db_special";
		$db_cfg[$table."/ID_special|ONCHANGE"] = "change_db_shtat_RESURS.php";
		$db_cfg[$table."/ID_speclvl"] = "list";
		$db_cfg[$table."/ID_speclvl|LIST"] = "db_speclvl";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_resurs|LIST_WHERE"] = "TID='0'";
		$db_cfg[$table."/ID_resurs|ONCHANGE"] = "change_db_shtat_RESURS.php";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/BOSS"] = "boolean";
		$db_cfg[$table."/NOTTAB"] = "boolean";





//////////
//	//
//  26	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Ресурсы
//
///////////////////////////////////////////////////////////////////////////

$table = "db_resurs";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Ресурсы";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";



$db_cfg[$table."|LIST_FIELD"] = "NAME";
$db_cfg[$table."|LIST_SEARCH"] = "NAME|FF|II|OO";
$db_cfg[$table."|LIST_PREFIX"] = " - ";
$db_cfg[$table."|ADDINDEX"] = "";
$db_cfg[$table."|LID_FIELD"] = "";
$db_cfg[$table."|LID_SEARCH"] = "";

$db_cfg[$table."|FIELDS"] = "DATE_FROM|DATE_TO|ID_JOB_TYPE|ID_special|NAME|TID|FF|II|OO|GENDER|PASPORT|ADR|FOTO|RAZMER|OPER_IDS|PARK_IDS|MORE|TEL|KADR|CHILDS|DATE|ID_tab|ID_users|DATE_LMO|DATE_NMO|ID_tab_st|EMAIL|KVALIF|ID_CARD|TIME_START|TIME_END|TIME_DELTA";

$db_cfg[$table."/ID_special"] = "integer";
$db_cfg[$table."/NAME"] = "tinytext";
$db_cfg[$table."/TID"] = "alist";
$db_cfg[$table."/TID|LIST"] = "Уволен";
$db_cfg[$table."/TID|ONCHANGE"] = "resurs_TID.php";
$db_cfg[$table."/FF"] = "tinytext";
$db_cfg[$table."/FF|ONCHANGE"] = "resurs_FIO.php";
$db_cfg[$table."/II"] = "tinytext";
$db_cfg[$table."/II|ONCHANGE"] = "resurs_FIO.php";
$db_cfg[$table."/OO"] = "tinytext";
$db_cfg[$table."/OO|ONCHANGE"] = "resurs_FIO.php";
$db_cfg[$table."/PASPORT"] = "textarea";
$db_cfg[$table."/ADR"] = "textarea";
$db_cfg[$table."/FOTO"] = "text";
$db_cfg[$table."/RAZMER"] = "tinytext";
$db_cfg[$table."/OPER_IDS"] = "multilist";
$db_cfg[$table."/OPER_IDS|LIST"] = "db_oper";
$db_cfg[$table."/PARK_IDS"] = "multilist";
$db_cfg[$table."/PARK_IDS|LIST"] = "db_park";
$db_cfg[$table."/MORE"] = "tinytext";
$db_cfg[$table."/TEL"] = "textarea";
$db_cfg[$table."/KADR"] = "textarea";
$db_cfg[$table."/CHILDS"] = "textarea";
$db_cfg[$table."/DATE"] = "date";
$db_cfg[$table."/ID_tab"] = "list";
$db_cfg[$table."/ID_tab|LIST"] = "users";
$db_cfg[$table."/ID_users"] = "list";
$db_cfg[$table."/ID_users|LIST"] = "users";
$db_cfg[$table."/ID_users|ONCHANGE"] = "change_db_RESURS_check_akk.php";
$db_cfg[$table."/DATE_LMO"] = "date";
$db_cfg[$table."/DATE_NMO"] = "date";
$db_cfg[$table."/ID_tab_st"] = "droplist";
$db_cfg[$table."/ID_tab_st|LIST"] = "db_tab_st";
$db_cfg[$table."/ID_tab_st|ONCHANGE"] = "change_db_tab_st.php";
$db_cfg[$table."/EMAIL"] = "tinytext";
$db_cfg[$table."/KVALIF"] = "preal";
$db_cfg[$table."/ID_JOB_TYPE"] = "state";  // служебное положение лица или род социально-значимой деятельности
$db_cfg[$table."/ID_JOB_TYPE|LIST"] = "Должность|Профессия";
$db_cfg[$table."/DATE_FROM"] = "date"; # Принят
$db_cfg[$table."/DATE_TO"] = "date"; # Уволен
$db_cfg[$table."/ID_CARD"] = "integer"; # Номер карты СКД
$db_cfg[$table."/TIME_START"] = "tinytext"; # Время начала рабочего дня
$db_cfg[$table."/TIME_END"] = "tinytext"; # Время окончания рабочего дня
$db_cfg[$table."/TIME_DELTA"] = "integer"; # Дельта на опоздания (влияет на время прихода)
$db_cfg[$table."/GENDER"] = "state";
$db_cfg[$table."/GENDER|LIST"] = "Мужской|Женский";




//////////
//	//
//  27	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ПРОИЗВОДСТВЕННЫЕ КАЛЕНДАРИ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_pc";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Производственные календари";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_tab_pci/ID_tab_pc";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";





//////////
//	//
//  28	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ЭЛЕМЕНТЫ ПРОИЗВОДСТВЕННЫХ КАЛЕНДАРЕЙ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_pci";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Элементы производственных календарей";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|HOURS|DATE|TID|ID_tab_pc";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/HOURS"] = "preal";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "В|ЛЧ";
		$db_cfg[$table."/ID_tab_pc"] = "list";
		$db_cfg[$table."/ID_tab_pc|LIST"] = "db_tab_pc";





//////////
//	//
//  29	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// СТАНДАРТНЫЕ ГРАФИКИ РАБОТ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_st";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Стандартные графики работ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE|ID_tab_pc|CICL|FDATE|NSMEN|SCICL|WD1|WD2|WD3|TS1|TS2|TS3|TE1|TE2|TE3|PLAN1|PLAN2|PLAN3|SNCICL";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ID_tab_pc"] = "list";
		$db_cfg[$table."/ID_tab_pc|LIST"] = "db_tab_pc";
		$db_cfg[$table."/CICL"] = "integer";
		$db_cfg[$table."/FDATE"] = "date";
		$db_cfg[$table."/NSMEN"] = "alist";
		$db_cfg[$table."/NSMEN|LIST"] = "1 смена|2 смены |3 смены";
		$db_cfg[$table."/SCICL"] = "alist";
		$db_cfg[$table."/SCICL|LIST"] = "По циклам 1, 2, 3|Внутри цикла 1, 2, 3";
		$db_cfg[$table."/SNCICL"] = "alist";
		$db_cfg[$table."/SNCICL|LIST"] = "1, 2, 3|1, 3, 2";
		$db_cfg[$table."/WD1"] = "integer";
		$db_cfg[$table."/WD2"] = "integer";
		$db_cfg[$table."/WD3"] = "integer";
		$db_cfg[$table."/TS1"] = "tinytext";
		$db_cfg[$table."/TS2"] = "tinytext";
		$db_cfg[$table."/TS3"] = "tinytext";
		$db_cfg[$table."/TE1"] = "tinytext";
		$db_cfg[$table."/TE2"] = "tinytext";
		$db_cfg[$table."/TE3"] = "tinytext";
		$db_cfg[$table."/PLAN1"] = "preal";
		$db_cfg[$table."/PLAN2"] = "preal";
		$db_cfg[$table."/PLAN3"] = "preal";





//////////
//	//
//  30	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ЭЛЕМЕНТЫ СТАНДАРТНЫХ ГРАФИКОВ РАБОТ (смены)
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_sti";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Элементы стандартных графиков работ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|HOURS|DATE|TID|SMEN|ID_tab_st";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/HOURS"] = "preal";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "В|ЛЧ";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN_LIST"] = "1|2|3";
		$db_cfg[$table."/ID_tab_st"] = "list";
		$db_cfg[$table."/ID_tab_st|LIST"] = "db_tab_st";





//////////
//	//
//  31	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// МТК
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_operitems";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "МТК в ДСЕ в заказах";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_operitems.php";
	$db_cfg[$table."|ONDELETE"] = "del_db_operitems.php";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "ID_oper";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_oper";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "FACT2_ID_2|FACT2_NORM_ID|FACT2_NORM|FACT2_ID_1|FACT2_NUM_ID|FACT2_NUM|KSZ_ID|KSZ_ID_NUM|KSZ_NUM|KSZ2_ID|KSZ2_ID_NUM|KSZ2_NUM|MSG_INFO|ID_zak|ID_zakdet|ID_oper|ID_park|ORD|NORM|NORM_2|NORM_ZAK|NORM_FACT|NUM_ZAK|NUM_FACT|NUM_ZADEL|FACT|MORE|STATE|ID_user|ETIME|BRAK|BRAK_MORE|CHANCEL";

		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_oper"] = "droplist";
		$db_cfg[$table."/ID_oper|LIST"] = "db_oper";
		$db_cfg[$table."/ID_park"] = "droplist";
		$db_cfg[$table."/ID_park|LIST"] = "db_park";
		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORM_2"] = "preal";
		$db_cfg[$table."/NORM_ZAK"] = "preal";
		$db_cfg[$table."/NORM_FACT"] = "preal";
		$db_cfg[$table."/NUM_ZAK"] = "pinteger";
		$db_cfg[$table."/NUM_FACT"] = "pinteger";
		$db_cfg[$table."/NUM_ZADEL"] = "pinteger";
		$db_cfg[$table."/FACT"] = "preal";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/STATE"] = "boolean";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";
		$db_cfg[$table."/BRAK"] = "state";
		$db_cfg[$table."/BRAK|LIST"] = "Испр. брака";
		$db_cfg[$table."/BRAK_MORE"] = "tinytext";
		$db_cfg[$table."/CHANCEL"] = "state";
		$db_cfg[$table."/CHANCEL|LIST"] = "Отмена";
		$db_cfg[$table."/CHANCEL|HOLD"] = $db_cfg[$table."|FIELDS"];
		$db_cfg[$table."/MSG_INFO"] = "tinytext";
		$db_cfg[$table."/KSZ_NUM"] = "tinytext";
		$db_cfg[$table."/KSZ_ID"] = "tinytext";
		$db_cfg[$table."/KSZ_ID_NUM"] = "tinytext";
		$db_cfg[$table."/KSZ2_NUM"] = "tinytext";
		$db_cfg[$table."/KSZ2_ID"] = "tinytext";
		$db_cfg[$table."/KSZ2_ID_NUM"] = "tinytext";
		$db_cfg[$table."/FACT2_NUM"] = "tinytext";
		$db_cfg[$table."/FACT2_NUM_ID"] = "tinytext";
		$db_cfg[$table."/FACT2_ID_1"] = "tinytext";
		$db_cfg[$table."/FACT2_NORM"] = "tinytext";
		$db_cfg[$table."/FACT2_NORM_ID"] = "tinytext";
		$db_cfg[$table."/FACT2_ID_2"] = "tinytext";





//////////
//	//
//  32	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Элементы сменных заданий
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zadan";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Элементы сменных заданий";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "ID_oper";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_oper";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "MULT_SEL|INICIATOR|ORD|SMEN|DATE|ID_zak|ID_zakdet|ID_operitems|ID_resurs|ID_park|NORM|NORM_FACT|FACT|NUM|NUM_FACT|CEH1|CEH2|MORE|OKDATE|SPEC|EDIT_STATE|ID_zadanrcp";

		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN|LIST"] = "1|2|3";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_operitems"] = "list";
		$db_cfg[$table."/ID_operitems|LIST"] = "db_operitems";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_park"] = "list";
		$db_cfg[$table."/ID_park|LIST"] = "db_park";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORM|ONCHANGE"] = "calc_zak_oper_norm.php";
		$db_cfg[$table."/NORM_FACT"] = "preal";
		$db_cfg[$table."/FACT"] = "preal";
		$db_cfg[$table."/NUM"] = "pinteger";
		$db_cfg[$table."/NUM|ONCHANGE"] = "calc_zak_oper_num.php";
		$db_cfg[$table."/NUM_FACT"] = "pinteger";
		$db_cfg[$table."/CEH1"] = "tinytext";
		$db_cfg[$table."/CEH2"] = "tinytext";
		$db_cfg[$table."/MORE"] = "text";
		$db_cfg[$table."/OKDATE"] = "pinteger";
		$db_cfg[$table."/MULT_SEL"] = "integer";
		$db_cfg[$table."/SPEC"] = "boolean";			// Спец задание
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Согл.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = $db_cfg[$table."|FIELDS"];
		$db_cfg[$table."/ID_zadanrcp"] = "droplist";
		$db_cfg[$table."/ID_zadanrcp|LIST"] = "db_zadanrcp";
		$db_cfg[$table."/INICIATOR"] = "list";
		$db_cfg[$table."/INICIATOR|LIST"] = "db_resurs";
		$db_cfg[$table."/INICIATOR|LIST_WHERE"] = "TID='0'";







//////////
//	//
//  33	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Причины невыполнения СЗ
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zadanrcp";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Причины невыполнения СЗ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "ID|NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  34	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Табель
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_tabel";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Табель";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "TID|SMEN";
	$db_cfg[$table."|LIST_SEARCH"] = "TID|SMEN";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "DATE|SMEN|TID|ID_resurs|NFACT|FACT|SPEC|PLAN|OPOZD|NOTTAB";

		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN|LIST"] = "1|2|3";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "ОТ|ДО|Х|Б|НН|ПР|В|ЛЧ|НВ|K|РП|У|ПК|НП|ВО|ГО| ";
		$db_cfg[$table."/PLAN"] = "preal";
		$db_cfg[$table."/FACT"] = "preal";
		$db_cfg[$table."/SPEC"] = "preal";
		$db_cfg[$table."/NFACT"] = "preal";
		$db_cfg[$table."/OPOZD"] = "pinteger";
		$db_cfg[$table."/NOTTAB"] = "pinteger";








//////////
//	//
//  35	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Ресурсы сменных заданий
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zadanres";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Ресурсы сменных заданий на дату";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "ORD|SMEN|DATE|ID_resurs";

		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN|LIST"] = "1|2|3";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";








//////////
//	//
//  36	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Каталог материалов
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_mat_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Каталог материалов";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  37	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Каталог сортамента
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_sort_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Каталог сортамента";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  38	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// БД материалов
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_mat";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "БД материалов";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "OBOZ|NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|GOST|COEF|ID_mat_cat";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/GOST"] = "tinytext";
		$db_cfg[$table."/COEF"] = "preal";
		$db_cfg[$table."/ID_mat_cat"] = "list";
		$db_cfg[$table."/ID_mat_cat|LIST"] = "db_mat_cat";








//////////
//	//
//  39	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// БД сортамента
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_sort";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "БД сортамента";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "OBOZ|GOST";
	$db_cfg[$table."|LIST_SEARCH"] = "OBOZ|NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|GOST|MASS|MT|ORD|ID_sort_cat";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/GOST"] = "tinytext";
		$db_cfg[$table."/MASS"] = "preal";
		$db_cfg[$table."/MT"] = "alist";
		$db_cfg[$table."/MT|LIST"] = "кг/м|кг/м2|м3/м|кг/м2 - S";
		$db_cfg[$table."/ORD"] = "tinytext";
		$db_cfg[$table."/ID_sort_cat"] = "list";
		$db_cfg[$table."/ID_sort_cat|LIST"] = "db_sort_cat";








//////////
//	//
//  40	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Ведомости заготовок в заказах
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zn_zag";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Ведомости заготовок в заказах";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_user";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "ID_mat|ID_sort";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_mat|ID_sort";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_zakdet|ID_mat|ID_sort|WW|HH|LL|RCOEF|KDZ|MORE|NORM|NORMZAK|RCOUNT|ID_user|ETIME";

		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_mat"] = "list";
		$db_cfg[$table."/ID_mat|LIST"] = "db_mat";
		$db_cfg[$table."/ID_sort"] = "list";
		$db_cfg[$table."/ID_sort|LIST"] = "db_sort";
		$db_cfg[$table."/WW"] = "preal";
		$db_cfg[$table."/HH"] = "preal";
		$db_cfg[$table."/LL"] = "preal";
		$db_cfg[$table."/RCOEF"] = "preal";
		$db_cfg[$table."/KDZ"] = "pinteger";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORMZAK"] = "preal";
		$db_cfg[$table."/RCOUNT"] = "pinteger";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";








//////////
//	//
//  41	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Ведомости поковок и отливок в заказах
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zn_pok";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Ведомости поковок и отливок в заказах";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_user";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "ID_mat";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_mat";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_zakdet|ID_mat|WW|HH|LL|KDZ|MORE|NORM|NORMZAK|RCOUNT|ID_user|ETIME";

		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_mat"] = "list";
		$db_cfg[$table."/ID_mat|LIST"] = "db_mat";
		$db_cfg[$table."/WW"] = "preal";
		$db_cfg[$table."/HH"] = "preal";
		$db_cfg[$table."/LL"] = "preal";
		$db_cfg[$table."/KDZ"] = "pinteger";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORMZAK"] = "preal";
		$db_cfg[$table."/RCOUNT"] = "pinteger";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";








//////////
//	//
//  42	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Ведомости инструмента в заказах
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zn_instr";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Ведомости инструмента в заказах";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_user";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_zakdet|NAME|COUNT|MORE|ID_user|ETIME";

		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/COUNT"] = "pinteger";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";








//////////
//	//
//  43	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Юр. лица
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_urface";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Юр. лица";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "OBOZ|NAME|MORE|ADDR|ADDRP|INN|KPP|OGRN|REGDATE|OKVED|OKPO";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ADDR"] = "textarea";
		$db_cfg[$table."/ADDRP"] = "textarea";
		$db_cfg[$table."/INN"] = "tinytext";
		$db_cfg[$table."/KPP"] = "tinytext";
		$db_cfg[$table."/OGRN"] = "tinytext";
		$db_cfg[$table."/REGDATE"] = "date";
		$db_cfg[$table."/OKVED"] = "tinytext";
		$db_cfg[$table."/OKPO"] = "tinytext";

















//////////
//	//
//  44	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Контакты Юр. лиц
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_contacts";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Контакты Юр. лиц";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "SPECIAL";
	$db_cfg[$table."|LIST_FIELD2"] = "FIO";
	$db_cfg[$table."|LIST_SEARCH"] = "SPECIAL|FIO";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_SPECIAL|ID_resurs|ID_urface|ID_shtat|SPECIAL|FIO|TEL|EMAIL";

		$db_cfg[$table."/ID_urface"] = "list";
		$db_cfg[$table."/ID_urface|LIST"] = "db_urface";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_resurs|LIST_EQUAL"] = "ID_special|ID_SPECIAL";
		$db_cfg[$table."/ID_resurs|ONCHANGE"] = "change_db_contacts.php";
		$db_cfg[$table."/ID_SPECIAL"] = "list";
		$db_cfg[$table."/ID_SPECIAL|LIST"] = "db_special";
		$db_cfg[$table."/ID_SPECIAL|ONCHANGE"] = "change_db_contacts.php";
		$db_cfg[$table."/ID_shtat"] = "list";
		$db_cfg[$table."/ID_shtat|LIST"] = "db_shtat";
		$db_cfg[$table."/ID_shtat|LIST_EQUAL"] = "ID_special|ID_SPECIAL";
		$db_cfg[$table."/ID_shtat|ONCHANGE"] = "change_db_contacts.php";
		$db_cfg[$table."/SPECIAL"] = "tinytext";
		$db_cfg[$table."/FIO"] = "tinytext";
		$db_cfg[$table."/TEL"] = "tinytext";
		$db_cfg[$table."/EMAIL"] = "tinytext";










//////////
//	//
//  45	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ В IT ОТДЕЛ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_it_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ В IT ОТДЕЛ";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// Номер = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|OTCHET|SOGL_USER|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";		// № заявки
		$db_cfg[$table."/QWEST"] = "textarea";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// Согл / Не Согл
		$db_cfg[$table."/SOGL|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL|HOLD"] = "DATE_PLAN|NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// Записать ID пользователя изменившего статус
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/OTCHET"] = "textarea";
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// Выполнено / Не выполнено
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Вып.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|SOGL_USER|OTCHET";







//////////
//	//
//  45	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ В ОТДЕЛ КАДРОВ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_hr_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ В ОТДЕЛ КАДРОВ";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// Номер = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|ONCREATE"] = "add_db_hr_req.php";

	$db_cfg[$table."|FIELDS"] = "NAME|QWEST|SOGL|ID_users|DATE|OTCHET|COUNT|SOGL_USER|EDIT_STATE|EDUCATION|POSITION|EXPERIENCE|FUNCTION|COMMENT_OK";

		$db_cfg[$table."/NAME"] = "tinytext";		// № заявки
		$db_cfg[$table."/QWEST"] = "textarea";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// Согл / Не Согл
		$db_cfg[$table."/SOGL|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL|HOLD"] = "DATE_PLAN|NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// Записать ID пользователя изменившего статус
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/OTCHET"] = "textarea";
		$db_cfg[$table."/COMMENT_OK"] = "textarea";
		$db_cfg[$table."/SOGL_USER"] = "list";
		
		$db_cfg[$table."/QWEST|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/GENDER"] = "alist";		// Назначение
		$db_cfg[$table."/GENDER|LIST"] = "Любой|Мужской|Женский";
		$db_cfg[$table."/GENDER|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/FUNCTION|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/AGE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/EXPERIENCE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/POSITION|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/EDUCATION|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/SALARY|EDITRIGHT"] = "ID_users";
		 
		$db_cfg[$table."/QWEST"] = "list";		// Заказ
		$db_cfg[$table."/QWEST|LIST"] = "db_otdel";
		
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/COUNT"] = "tinytext";
		$db_cfg[$table."/FUNCTION"] = "tinytext";
		$db_cfg[$table."/AGE"] = "tinytext";
		$db_cfg[$table."/SALARY"] = "tinytext";
		$db_cfg[$table."/EXPERIENCE"] = "tinytext";
		$db_cfg[$table."/POSITION"] = "tinytext";
		$db_cfg[$table."/EDUCATION"] = "tinytext";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// Выполнено / Не выполнено
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Вып.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|SOGL_USER|OTCHET|EDUCATION|POSITION|EXPERIENCE|FUNCTION";







//////////
//	//
//  46	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ В ОГИ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_ogi_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ В ОГИ";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// Номер = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";



	$db_cfg[$table."|FIELDS"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|ETIME|OTCHET|SOGL_USER|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";		// № заявки
		$db_cfg[$table."/QWEST"] = "textarea";		// Заявка
		$db_cfg[$table."/QWEST|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_users"] = "list";		// Инициатор
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// Согл / Не Согл
		$db_cfg[$table."/SOGL|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL|HOLD"] = "DATE_PLAN|NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// Записать ID пользователя изменившего статус
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/OTCHET"] = "textarea";
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// Выполнено / Не выполнено
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Вып.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|OTCHET|SOGL_USER";








//////////
//	//
//  47	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ НА ТМЦ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tmc_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ НА ТМЦ";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "STATE|SOGL1|SOGL2";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_tmc_req.php";		// Присваиваем номер

	$db_cfg[$table."|LIST_FIELD"] = "TXT|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|DATEPLAN|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2|STATE";

		$db_cfg[$table."/NAME"] = "tinytext";		// № заявки
		$db_cfg[$table."/CDATE"] = "date";		// Дата создания
		$db_cfg[$table."/TXT"] = "tinytext";		// Наименование
		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT"] = "pinteger";		// Количество
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/EDIZM"] = "tinytext";		// Ед. измерения
		$db_cfg[$table."/EDIZM|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATE"] = "date";		// Требуемый срок
		$db_cfg[$table."/DATE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATEPLAN"] = "dateplan";	// Плановая дата выполнения
		$db_cfg[$table."/ID_users"] = "list";		// Инициатор
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/NAZN"] = "alist";		// Назначение
		$db_cfg[$table."/NAZN|LIST"] = "Хоз. расходы|Служба ГИ|Канцелярия|Заказы|Оборудование|Расходники|СИЗ|Рубин|Инструменты|Стройка";
		$db_cfg[$table."/NAZN|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_zak"] = "list";		// Заказ
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/SOGL1"] = "state";
		$db_cfg[$table."/SOGL1|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL1|HOLD"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|ID_users|NAZN|ID_zak";
		$db_cfg[$table."/SOGL1|USER"] = "SOGLUSER1";	// Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL1|DATE"] = "SOGLDATE1";	// Записать дату изменения статуса
		$db_cfg[$table."/SOGLUSER1"] = "list";
		$db_cfg[$table."/SOGLUSER1|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE1"] = "date";
		$db_cfg[$table."/SOGL2"] = "state";
		$db_cfg[$table."/SOGL2|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL2|HOLD"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|ID_users|NAZN|ID_zak";
		$db_cfg[$table."/SOGL2|USER"] = "SOGLUSER2";	// Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL2|DATE"] = "SOGLDATE2";	// Записать дату изменения статуса
		$db_cfg[$table."/SOGLUSER2"] = "list";
		$db_cfg[$table."/SOGLUSER2|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE2"] = "date";
		$db_cfg[$table."/MORE"] = "textarea";		// Примечание
		$db_cfg[$table."/STATE"] = "state";		// Выполнено / Не выполнено
		$db_cfg[$table."/STATE|LIST"] = "Приост.|Аннул.";
		$db_cfg[$table."/STATE|HOLD"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|DATEPLAN|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2";








//////////
//	//
//  48	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ НА ЗАКАЗЫ
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_zak_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ НА ЗАКАЗЫ";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "STATE|SOGL1|SOGL2";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_zak_req.php";		// Присваиваем номер

	$db_cfg[$table."|LIST_FIELD"] = "TXT|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "KOMMENT|NAME|CDATE|TXT|COUNT|DATE|STATE|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2";

		$db_cfg[$table."/KOMMENT"] = "textarea";			// № заявки
		$db_cfg[$table."/NAME"] = "tinytext";			// № заявки
		$db_cfg[$table."/CDATE"] = "date";			// Дата создания
		$db_cfg[$table."/TXT"] = "tinytext";			// Наименование
		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT"] = "pinteger";			// Количество
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATE"] = "date";			// Требуемый срок
		$db_cfg[$table."/DATE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/STATE"] = "state";			// Выполнено / Не выполнено
		$db_cfg[$table."/STATE|LIST"] = "Выполн.|Аннул.";
		
		$db_cfg[$table."/EDIT_STATE|ONCHANGE"] = "zak_req_edit_state_change.php";
		$db_cfg[$table."/STATE|HOLD"] = "NAME|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2";
		$db_cfg[$table."/ID_users"] = "list";			// Инициатор
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/NAZN"] = "alist";			// Назначение
		$db_cfg[$table."/NAZN|LIST"] = "Заказ|Поставка|Склад|Рем. оборуд.|Хоз. нужды|Прочее|Оснастка";
		$db_cfg[$table."/NAZN|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_zak"] = "list";			// Заказ
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zak|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/SOGL1"] = "state";
		$db_cfg[$table."/SOGL1|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL1|HOLD"] = "NAME|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|MORE";
		$db_cfg[$table."/SOGL1|USER"] = "SOGLUSER1";		// Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL1|DATE"] = "SOGLDATE1";		// Записать дату изменения статуса
		$db_cfg[$table."/SOGLUSER1"] = "list";
		$db_cfg[$table."/SOGLUSER1|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE1"] = "date";
		$db_cfg[$table."/SOGL2"] = "state";
		$db_cfg[$table."/SOGL2|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL2|HOLD"] = "NAME|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|MORE";
		$db_cfg[$table."/SOGL2|USER"] = "SOGLUSER2";		// Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL2|DATE"] = "SOGLDATE2";		// Записать дату изменения статуса
		$db_cfg[$table."/SOGLUSER2"] = "list";
		$db_cfg[$table."/SOGLUSER2|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE2"] = "date";
		$db_cfg[$table."/MORE"] = "textarea";			// Примечание








//////////
//	//
//  49	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ НА РАБОТЫ ПО КООПЕРАЦИИ
//

	$table = "db_koop_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ НА РАБОТЫ ПО КООПЕРАЦИИ";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "STATE|SOGL1|SOGL2|SOGL3";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_koop_req.php";		// Присваиваем номер

	$db_cfg[$table."|LIST_FIELD"] = "TXT|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "SOGL3|REQ_TYPE|EFFECTN_ZERO|EFFECTN|CENA_FACT|STOIM_RAB|CENA_PLAN|PLAN_NCH|NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|STATE|PLAN_NORM|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2|VIDRABOT|ID_resurs|OPTIONS";

		$db_cfg[$table."/NAME"] = "tinytext";			// № заявки NNN.MM.YYYY
		$db_cfg[$table."/OBOZ"] = "tinytext";			// Чертёж
		$db_cfg[$table."/OBOZ|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DIRECT"] = "tinytext";			// Место расположения чертежей
		$db_cfg[$table."/DIRECT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/CDATE"] = "date";			// Дата создания
		$db_cfg[$table."/TXT"] = "tinytext";			// Наименование
		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT"] = "pinteger";			// Количество
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATE"] = "date";			// Требуемый срок
		$db_cfg[$table."/DATE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/STATE"] = "state";			// Выполнено / Не выполнено
		$db_cfg[$table."/STATE|LIST"] = "Выполн.|Аннул.";
		$db_cfg[$table."/STATE|HOLD"] = "NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2|VIDRABOT|ID_resurs";
		$db_cfg[$table."/ID_users"] = "list";			// Инициатор
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/NAZN"] = "alist";			// Назначение
		$db_cfg[$table."/NAZN|LIST"] = "Заказ|Поставка|Склад|Рем. оборуд.|Хоз. нужды|Прочее";
		$db_cfg[$table."/NAZN|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_zak"] = "list";			// Заказ
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zak|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/SOGL1"] = "state";
		$db_cfg[$table."/SOGL1|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/REQ_TYPE"] = "state";
		$db_cfg[$table."/REQ_TYPE|LIST"] = "Проработка|В работу";
		$db_cfg[$table."/SOGL1|HOLD"] = "NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|VIDRABOT|ID_resurs";
		$db_cfg[$table."/SOGL1|USER"] = "SOGLUSER1";		// Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL1|DATE"] = "SOGLDATE1";		// Записать дату изменения статуса
		$db_cfg[$table."/SOGLUSER1"] = "list";
		$db_cfg[$table."/SOGLUSER1|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE1"] = "date";
		$db_cfg[$table."/SOGL2"] = "state";
		$db_cfg[$table."/SOGL2|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL2|HOLD"] = "NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|VIDRABOT|ID_resurs";
		$db_cfg[$table."/SOGL2|USER"] = "SOGLUSER2";		// Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL2|DATE"] = "SOGLDATE2";		// Записать дату изменения статуса
		$db_cfg[$table."/SOGLUSER2"] = "list";
		$db_cfg[$table."/SOGLUSER2|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE2"] = "date";
		$db_cfg[$table."/VIDRABOT"] = "textarea";		// Вид работ
		$db_cfg[$table."/VIDRABOT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/MORE"] = "textarea";			// Коментарии ОВК
		$db_cfg[$table."/ID_resurs"] = "list";			// Ответственный за отгрузку
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/PLAN_NCH"] = "real";			// 
		$db_cfg[$table."/PLAN_NCH|EDITRIGHT"] = "ID_users";			// 
		$db_cfg[$table."/CENA_PLAN"] = "money";			// 
		$db_cfg[$table."/STOIM_RAB"] = "money";			//
		$db_cfg[$table."/CENA_FACT"] = "money";			//
		$db_cfg[$table."/EFFECTN"] = "money";			// 
		$db_cfg[$table."/EFFECTN_ZERO"] = "tinytext";	// 
		$db_cfg[$table."/PLAN_NORM"] = "tinytext";	// 
		$db_cfg[$table."/OPTIONS"] = "textarea";	// 
		$db_cfg[$table."/OPTIONS|EDITRIGHT"] = "ID_users";	
		$db_cfg[$table."/REQ_TYPE|EDITRIGHT"] = "ID_users";			// 

		$db_cfg[$table."/PLAN_NORM|EDITRIGHT"] = "ID_users";			// 
		$db_cfg[$table."/SOGL3"] = "state";
		$db_cfg[$table."/SOGL3|LIST"] = "Согл.";
		$db_cfg[$table."/SOGL3|HOLD"] = "PLAN_NCH|CENA_PLAN|STOIM_RAB|SOGL3";

		$db_cfg[$table."/RESP"] = "state";
		$db_cfg[$table."/RESP|LIST"] = "Веретенникова С.О. &#9742; 1016 &#9993; ovk@okbmikron.ru |Казаченко А.Л. &#9742; 1004 &#9993; kazachenko@okbmikron.ru|Богданов М. А. &#9742; 1016 &#9993; bma@okbmikron.ru";



//////////
//	//
//  50	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ предложения по сайту
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_prog_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАЯВКИ предложения по сайту";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// Номер = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "QWEST|SOGL|ID_users|DATE|UNSW|OTCHET|SOGL_USER|EDIT_STATE";

		$db_cfg[$table."/QWEST"] = "textarea";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// Согл / Не Согл
		$db_cfg[$table."/SOGL|LIST"] = "Согл.|Откл.";
		$db_cfg[$table."/SOGL|HOLD"] = "NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// Записать ID пользователя изменившего статус
		$db_cfg[$table."/UNSW"] = "textarea";		// Заключение согласующего
		$db_cfg[$table."/OTCHET"] = "textarea";		// Отчёт выполнившего
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// Выполнено / Не выполнено
		$db_cfg[$table."/EDIT_STATE|LIST"] = "Вып.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|QWEST|SOGL|ID_users|DATE|SOGL_USER|OTCHET";











//////////
//	//
//  51	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ЗАДАНИЯ ИТР (контроль исполнительской дисциплины)
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_itrzadan";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "ЗАДАНИЯ ИТР (контроль исполнительской дисциплины)";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|CREATETIME"] = "CTIME";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";


	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT|NAME|ID_users|ID_users2|ID_users3";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "TIME_PLAN|TIT_HEAD|TIP_JOB|TIP_FAIL|DOCISP|STARTTIME|STARTDATE|KOMM1|KOMM2|KOMM3|ID_zak|ID_users|ID_users2|ID_users3|CDATE|CTIME|TXT|ETIME|EUSER|DATE_PLAN|STATUS|ID_edo";

		$db_cfg[$table."/ID_users"] = "list";		// Инициатор
		$db_cfg[$table."/ID_users|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users2"] = "list";		// Ответственный
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users3"] = "list";		// Контроллер
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users3|LIST_WHERE"] = "TID='0'";	//
		$db_cfg[$table."/CDATE"] = "date";				// Дата создания задания
		$db_cfg[$table."/CTIME"] = "tinytext";			// время создания задания
		$db_cfg[$table."/STARTDATE"] = "date";			// Плановое начало выполнения задания
		$db_cfg[$table."/STARTTIME"] = "tinytext";		// Плановое начало выполнения задания
		$db_cfg[$table."/TXT"] = "tinytext";			// Cодержание задания
		$db_cfg[$table."/DOCISP"] = "tinytext";			// Документ, подтверждающий выполнение
		$db_cfg[$table."/KOMM1"] = "tinytext";			// Комментарий автора
		$db_cfg[$table."/KOMM2"] = "tinytext";			// Комментарий исполнителя
		$db_cfg[$table."/KOMM3"] = "tinytext";			// Комментарий контроллера
		$db_cfg[$table."/ETIME"] = "time";				// Время изменения чего-либо в задании
		$db_cfg[$table."/EUSER"] = "list";				// Кто создал задание
		$db_cfg[$table."/EUSER|LIST"] = "db_resurs";	//
		$db_cfg[$table."/DATE_PLAN"] = "date";			// Желаемая инициатором дата
		$db_cfg[$table."/TIME_PLAN"] = "tinytext";		// Желаемое инициатором время
		$db_cfg[$table."/STATUS"] = "tinytext";			// Текущий статус задания
		$db_cfg[$table."/TIP_FAIL"] = "tinytext";		// Тип файла для префикса к номеру задания
		$db_cfg[$table."/TIT_HEAD"] = "tinytext";		// Тип файла для префикса к номеру задания
		$db_cfg[$table."/TIP_JOB"] = "alist";			// Тип работы задания
		$db_cfg[$table."/TIP_JOB|LIST"] = "Директива|Информация|Директива (новый заказ)";
		$db_cfg[$table."/ID_edo"] = "list";				// № документа
		$db_cfg[$table."/ID_edo|LIST"] = "db_edo_inout_files";
		$db_cfg[$table."/ID_zak"] = "list";				// № заказа
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";


////////////////////////////////////////////////////////////////////////////
//
// введение изменение статусов для ИТР заданий
//
////////////////////////////////////////////////////////////////////////////


	$table = "db_itrzadan_statuses";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Хронология изменения статусов заданий";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|EDITTIME"] = "TIME";
	$db_cfg[$table."|CREATEDATE"] = "DATA";



	$db_cfg[$table."|LIST_FIELD"] = "DATA|TIME|STATUS|USER";
	$db_cfg[$table."|LIST_SEARCH"] = "DATA|TIME|STATUS|USER";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DATA|TIME|STATUS|USER|ID_edo";

		$db_cfg[$table."/DATA"] = "date";				// Дата изменения статуса
		$db_cfg[$table."/TIME"] = "time2";				// Время изменения статуса
		$db_cfg[$table."/STATUS"] = "tinytext";			// На какой был изменён статус
		$db_cfg[$table."/USER"] = "tinytext";			// Кем был изменён статус
		$db_cfg[$table."/ID_edo"] = "list";				// № заказа /документа
		$db_cfg[$table."/ID_edo|LIST"] = "db_itrzadan";







//////////
//	//
//  52	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Каталог инвентаризации оборудования
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_inv_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Каталог инвентаризации";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_inv_cat.php";	// Присваиваем номер
	$db_cfg[$table."|MAXDEEP"] = 2;



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ORD|PREFIX";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ORD"] = "tinytext";
		$db_cfg[$table."/PREFIX"] = "tinytext";








//////////
//	//
//  53	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Инвентаризация
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_inv";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Элементы в инвентаризации";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_inv_cat";
	$db_cfg[$table."|ONCREATE"] = "add_db_inv.php";		// Присваиваем номер
	$db_cfg[$table."|MAXDEEP"] = 2;



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_inv_cat|MODEL|ORD_NUM|YY|INV|ID_resurs|SOST|LASTDATE|POVDATE|MORE|ZAVNUM|KOMPL|DATEVID|USETP|TCLASS|SCALE|PRIOBR|ID_inv_places|OLD_INV";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_inv_cat"] = "list";
		$db_cfg[$table."/ID_inv_cat|LIST"] = "db_inv_cat";
		$db_cfg[$table."/MODEL"] = "tinytext";
		$db_cfg[$table."/ORD_NUM"] = "tinytext";		
		$db_cfg[$table."/YY"] = "pinteger";
		$db_cfg[$table."/INV"] = "tinytext";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/SOST"] = "alist";
		$db_cfg[$table."/SOST|LIST"] = "Рабочее|РЕМОНТ|Консервация|Поверка";
		$db_cfg[$table."/LASTDATE"] = "date";
		$db_cfg[$table."/POVDATE"] = "date";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ZAVNUM"] = "tinytext";
		$db_cfg[$table."/KOMPL"] = "tinytext";
		$db_cfg[$table."/DATEVID"] = "date";
		$db_cfg[$table."/USETP"] = "alist";
		$db_cfg[$table."/USETP|LIST"] = "Постоянное|Временное|Эталон";
		$db_cfg[$table."/TCLASS"] = "tinytext";
		$db_cfg[$table."/SCALE"] = "tinytext";
		$db_cfg[$table."/PRIOBR"] = "tinytext";
		$db_cfg[$table."/ID_inv_places"] = "droplist";
		$db_cfg[$table."/ID_inv_places|LIST"] = "db_inv_places";
		$db_cfg[$table."/OLD_INV"] = "tinytext";








//////////
//	//
//  54	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Места нахождения для инвентаризации
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_inv_places";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Места нахождения для инвентаризации";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  55	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// Контакты контрагентов
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_clients_contacts";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Контакты контрагентов";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "PROF";
	$db_cfg[$table."|LIST_FIELD2"] = "FIO";
	$db_cfg[$table."|LIST_SEARCH"] = "PROF|FIO";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "PROF|FIO|TEL|EMAIL|ID_clients|DATE";

		$db_cfg[$table."/PROF"] = "tinytext";
		$db_cfg[$table."/FIO"] = "tinytext";
		$db_cfg[$table."/TEL"] = "tinytext";
		$db_cfg[$table."/EMAIL"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/DATE"] = "date";





////////////
//
// Классификатор и справочник инструмента
//
///////////////////////////////////////////////////////////////////////////

$table = "db_inv_cat_tools";

 $db_cfg[$table."|TYPE"] = "tree";
 $db_cfg[$table."|ERP"] = "false";

 $db_cfg[$table."|MORE"] = "Классификатор и справочник инструмента";
 $db_cfg[$table."|DELRIGHT"] = "";
 $db_cfg[$table."|CREATEBY"] = "";
 $db_cfg[$table."|CREATEDATE"] = "";
 $db_cfg[$table."|HOLDBY"] = "";
 $db_cfg[$table."|DELWITH"] = "";
 $db_cfg[$table."|ADDWITH"] = "";
 $db_cfg[$table."|BYPARENT"] = "";
 $db_cfg[$table."|ONCREATE"] = "add_db_inv_cat_tools.php"; // Присваиваем номер
 $db_cfg[$table."|MAXDEEP"] = 2;



 $db_cfg[$table."|LIST_FIELD"] = "NAME";
 $db_cfg[$table."|LIST_SEARCH"] = "NAME";
 $db_cfg[$table."|LIST_PREFIX"] = ", ";
 $db_cfg[$table."|ADDINDEX"] = "";

 $db_cfg[$table."|FIELDS"] = "NAME|ORD|PREFIX";

  $db_cfg[$table."/NAME"] = "tinytext";
  $db_cfg[$table."/ORD"] = "tinytext";
  $db_cfg[$table."/PREFIX"] = "tinytext";



///////////////////////////////////////////////////////////////////////////
//
// Справочник инструмента
//
///////////////////////////////////////////////////////////////////////////

$table = "db_reference_tool";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Справочник инструмента";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";

$db_cfg[$table."|LIST_FIELD"] = "S_NAME";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "N_CODE_IN_GROUP|ID_inv_cat_tools|S_BARCODE|S_NAME|ID_MATERIAL|N_PARAM2|N_PARAM3|N_PARAM4";

$db_cfg[$table."/S_BARCODE"] = "text";
$db_cfg[$table."/S_NAME"] = "tinytext";
$db_cfg[$table."/ID_MATERIAL"] = "droplist";
$db_cfg[$table."/ID_MATERIAL|LIST"] = "db_mat";
$db_cfg[$table."/ID_MATERIAL|LIST_WHERE"] = "ID_MAT_CAT in ('765')";
$db_cfg[$table."/N_PARAM2"] = "tinytext";
$db_cfg[$table."/N_PARAM3"] = "tinytext";
$db_cfg[$table."/N_PARAM4"] = "tinytext";
$db_cfg[$table."/ID_inv_cat_tools"] = "list";
$db_cfg[$table."/ID_inv_cat_tools|LIST"] = "db_inv_cat_tools";
$db_cfg[$table."/N_CODE_IN_GROUP"] = "integer";


//////////////////////////////////////
//
// таблица ЭДО
//
//////////////////////////////////////


	$table = "db_edo_inout_files";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Входящие/Исходящие";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|EDITTIME"] = "EDITTIME";
	$db_cfg[$table."|EDITUSER"] = "ID_users";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	//$db_cfg[$table."|ONCREATE"] = "add_db_edo_files.php";


	$db_cfg[$table."|LIST_FIELD"] = "NAME_IN";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME_IN|TIP_FAIL";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "KOMM|CONTRAGENT|OTVET_INOUT|TIP_FAIL|ID_clients|ID_clients_contacts|ID_contacts|NAME_OUT|NAME_IN|DATA|DATE_START|VID_FAIL|MORE|ID_krz|ID_krz2|ID_zak|ID_files_3|ID_files_1|FILENAME|ID_resurs|EDITTIME|ID_users";

		$db_cfg[$table."/TIP_FAIL"] = "tinytext";
		$db_cfg[$table."/DATA"] = "date";
		$db_cfg[$table."/DATE_START"] = "date";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_clients|ONCHANGE"] = "edo_change_clients.php";
		$db_cfg[$table."/CONTRAGENT"] = "tinytext";
		$db_cfg[$table."/KOMM"] = "tinytext";
		$db_cfg[$table."/ID_clients_contacts"] = "list2";
		$db_cfg[$table."/ID_clients_contacts|LIST"] = "db_clients_contacts";
		$db_cfg[$table."/ID_clients_contacts|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_contacts"] = "list2";
		$db_cfg[$table."/ID_contacts|LIST"] = "db_contacts";
		$db_cfg[$table."/NAME_IN"] = "tinytext";
		$db_cfg[$table."/NAME_OUT"] = "tinytext";
		$db_cfg[$table."/VID_FAIL"] = "droplist";
		$db_cfg[$table."/VID_FAIL|LIST"] = "db_edo_inout_files_vidfails";
		$db_cfg[$table."/OTVET_INOUT"] = "tinytext";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_files_3"] = "list";
		$db_cfg[$table."/ID_files_3|LIST"] = "db_files_3";
		//$db_cfg[$table."/ID_files_3|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_files_1"] = "list";
		$db_cfg[$table."/ID_files_1|LIST"] = "db_files_3";
		//$db_cfg[$table."/ID_files_1|LIST_EQUAL"] = "PID|ID_files_3";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/EDITTIME"] = "time";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";

//////////////////////////////////////
//
// таблица ЭДО для подтверждения регистрации
//
//////////////////////////////////////


	$table = "db_edo_inout_files_vrem";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Входящие/Исходящие";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|EDITTIME"] = "EDITTIME";
	$db_cfg[$table."|EDITUSER"] = "ID_users";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_edo_files.php";


	$db_cfg[$table."|LIST_FIELD"] = "NAME_IN";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME_IN|TIP_FAIL";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "KOMM|CONTRAGENT|OTVET_INOUT|TIP_FAIL|ID_clients|ID_clients_contacts|ID_contacts|NAME_OUT|NAME_IN|DATA|DATE_START|VID_FAIL|MORE|ID_krz|ID_krz2|ID_zak|ID_files_3|ID_files_1|FILENAME|ID_resurs|EDITTIME|ID_users";

		$db_cfg[$table."/TIP_FAIL"] = "tinytext";
		$db_cfg[$table."/DATA"] = "date";
		$db_cfg[$table."/DATE_START"] = "date";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_clients|ONCHANGE"] = "edo_change_clients.php";
		$db_cfg[$table."/CONTRAGENT"] = "tinytext";
		$db_cfg[$table."/KOMM"] = "tinytext";
		$db_cfg[$table."/ID_clients_contacts"] = "list2";
		$db_cfg[$table."/ID_clients_contacts|LIST"] = "db_clients_contacts";
		$db_cfg[$table."/ID_clients_contacts|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_contacts"] = "list2";
		$db_cfg[$table."/ID_contacts|LIST"] = "db_contacts";
		$db_cfg[$table."/NAME_IN"] = "tinytext";
		$db_cfg[$table."/NAME_OUT"] = "tinytext";
		$db_cfg[$table."/VID_FAIL"] = "droplist";
		$db_cfg[$table."/VID_FAIL|LIST"] = "db_edo_inout_files_vidfails";
		$db_cfg[$table."/OTVET_INOUT"] = "tinytext";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_files_3"] = "list";
		$db_cfg[$table."/ID_files_3|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_files_3|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_files_1"] = "list";
		$db_cfg[$table."/ID_files_1|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_files_1|LIST_EQUAL"] = "PID|ID_files_3";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/EDITTIME"] = "time";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		
//////////////////////////////////////
//
// таблица видов файлов ЭДО
//
//////////////////////////////////////

	$table = "db_edo_inout_files_vidfails";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Виды документов ЭДО";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "VID";
	$db_cfg[$table."|LIST_SEARCH"] = "VID";
	$db_cfg[$table."|LIST_PREFIX"] = "";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "VID";

		$db_cfg[$table."/VID"] = "tinytext";

//////////////////////////////////////
//
// таблица временных заданий создаваемых из формы одного элемента таблицы db_edo_inout_files
//
//////////////////////////////////////

	$table = "db_edo_vremitr";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Временные задания из 1 элемента таблицы ЭДО";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "VID";
	$db_cfg[$table."|LIST_SEARCH"] = "VID";
	$db_cfg[$table."|LIST_PREFIX"] = "";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_users2|ID_users3|TXT|MORE|DATE_PLAN|ID_contacts";

		$db_cfg[$table."/ID_users2"] = "list";			// Ответственный
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_users3"] = "list";			// Контроллер
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_users3|LIST_WHERE"] = "TID='0'";
		$db_cfg[$table."/TXT"] = "tinytext";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/ID_contacts"] = "list";			// Ответственный
		$db_cfg[$table."/ID_contacts|LIST"] = "db_contacts";

//////////////////////////////////////
//
// таблица временных заданий создаваемых из формы одного элемента таблицы db_edo_inout_files
//
//////////////////////////////////////

	$table = "db_itr_vremitr";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Временные задания из 1 элемента таблицы ИТР";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";


	$db_cfg[$table."|LIST_FIELD"] = "VID";
	$db_cfg[$table."|LIST_SEARCH"] = "VID";
	$db_cfg[$table."|LIST_PREFIX"] = "";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_users|ID_users2|TIP_JOB|ID_zak|ID_users3|TXT|DATE_PLAN|TIME_PLAN|STARTDATE|STARTTIME|KOMM1";

		$db_cfg[$table."/ID_users"] = "tinytext";			// Ответственный
		$db_cfg[$table."/ID_users2"] = "list";			// Ответственный
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";
		$db_cfg[$table."/TIP_JOB"] = "alist";			// Тип работы задания
		$db_cfg[$table."/TIP_JOB|LIST"] = "Директива|Информация|Запрос|Запрос на подпись";
		$db_cfg[$table."/ID_zak"] = "list";				// № заказа
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zak|LIST_WHERE"] = "EDIT_STATE='0'";
		$db_cfg[$table."/ID_users3"] = "list";			// Контроллер
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_users3|LIST_WHERE"] = "TID='0'";
		$db_cfg[$table."/TXT"] = "textarea";
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/TIME_PLAN"] = "tinytext";
		$db_cfg[$table."/STARTDATE"] = "date";			// Плановое начало выполнения задания
		$db_cfg[$table."/STARTTIME"] = "tinytext";		// Плановое начало выполнения задания
		$db_cfg[$table."/KOMM1"] = "tinytext";			// Комментарий автора

////////////////////////////////////////////////////////////////////////////
//
// ЗАЯВКИ НА ЛОГИСТИКУ
//
////////////////////////////////////////////////////////////////////////////

$table = "db_logistic_app";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";
	$db_cfg[$table."|MORE"] = "ЗАЯВКИ НА ЛОГИСТИКУ";
	$db_cfg[$table."|DELRIGHT"] = "ID_USERS";
	$db_cfg[$table."|CREATEBY"] = "ID_USERS";
	$db_cfg[$table."|EDITTIME"] = "FINAL_DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|FINISH_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "zayavk_log_settime.php";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|CREATEDATE"] = "DATE_СREATE";
	
	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DATE_СREATE|TRANSFER_TIME|N_APPLICATION|ID_USERS|DATE|APPLICATION|QUANTITY|TRANSFER_DATE|TRANSFER_FROM|TRANSFER_TO|COMMENT|CONTRAGENT_CONTACT|SOGL|SOGL_USER|FINISH_STATE|FINAL_DATE";

		$db_cfg[$table."/N_APPLICATION"] = "tinytext";  // № заявки
		$db_cfg[$table."/ID_USERS"] = "list";  // Заявитель
		$db_cfg[$table."/ID_USERS|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "time";           // Дата, время заявки
		$db_cfg[$table."/APPLICATION"] = "textarea";  // Заявка
		$db_cfg[$table."/APPLICATION|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/QUANTITY"] = "pinteger";  // Количество ?!
		$db_cfg[$table."/QUANTITY|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_DATE"] = "date";           // Дата перевозки
		$db_cfg[$table."/TRANSFER_DATE|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_TIME"] = "tinytext";           // время перевозки
		$db_cfg[$table."/TRANSFER_TIME|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_FROM"] = "tinytext";  // Откуда
		$db_cfg[$table."/TRANSFER_FROM|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_TO"] = "textarea";  // Куда (пункт назначения)
		$db_cfg[$table."/TRANSFER_TO|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/COMMENT"] = "textarea";     // Комментарий
		$db_cfg[$table."/CONTRAGENT_CONTACT"] = "textarea";  // Контакт контрагента (+ проверку заполнения)
		$db_cfg[$table."/CONTRAGENT_CONTACT|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/SOGL"] = "state";  // Согл / Не Согл
		$db_cfg[$table."/SOGL|LIST"] = "Согласовано|Отклонено";
		$db_cfg[$table."/SOGL|HOLD"] = "APPLICATION|TRANSFER_TIME|N_APPLICATION|ID_USERS|DATE|QWEST|QUANTITY|TRANSFER_DATE|TRANSFER_FROM|TRANSFER_TO|CONTRAGENT_CONTACT";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER"; // Записать ID пользователя изменившего статус
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/FINISH_STATE"] = "state"; // Выполнено / Не выполнено
		$db_cfg[$table."/FINISH_STATE|LIST"] = "Выполнено";
		$db_cfg[$table."/FINISH_STATE|HOLD"] = "APPLICATION|TRANSFER_TIME|N_APPLICATION|ID_USERS|DATE|QWEST|QUANTITY|TRANSFER_DATE|TRANSFER_FROM|TRANSFER_TO|COMMENT|CONTRAGENT_CONTACT";
		$db_cfg[$table."/FINAL_DATE"] = "time";           // Время последней операции (выводить на форму)
		$db_cfg[$table."/DATE_СREATE"] = "date"; //Для архива

		$db_cfg[$table."/driver"] = "alist";
		$db_cfg[$table."/driver|LIST"] = "Бабич Андрей Владимирович|Калиниченко Александр|Павлович Анатолий Александрович|Понтяр Константин Евгеньевич";


///////////
//
// Справочник мест хранения
//
///////////////////////////////////////////////////////////////////////////

$table = "db_inv_storage_areas";

$db_cfg[$table."|TYPE"] = "tree";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Справочник мест хранения";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";
$db_cfg[$table."|MAXDEEP"] = 4;



$db_cfg[$table."|LIST_FIELD"] = "NAME";
$db_cfg[$table."|LIST_SEARCH"] = "NAME";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "NAME|N_CODE_IN_GROUP|PREFIX";

$db_cfg[$table."/NAME"] = "tinytext";
$db_cfg[$table."/PREFIX"] = "tinytext";
$db_cfg[$table."/N_CODE_IN_GROUP"] = "integer";








//////////
//	//
//  56	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// План ГАНТ
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_planzad";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "План ГАНТ";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DATE|ID_zak|ID_zakdet|ID_operitems|NORM";

		$db_cfg[$table."/PROF"] = "tinytext";
		$db_cfg[$table."/FIO"] = "tinytext";
		$db_cfg[$table."/TEL"] = "tinytext";
		$db_cfg[$table."/EMAIL"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/DATE"] = "date";


		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak_LIST"] = "db_zak";
		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_operitems"] = "list";
		$db_cfg[$table."/ID_operitems|LIST"] = "db_operitems";
		$db_cfg[$table."/NORM"] = "preal";



////////////////////////////////
//
//		Запросы
//
////////////////////////////////

	$table = "db_zapros_all";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Запросы";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|CREATETIME"] = "CTIME";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";


	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "TIP_ZAPR|TIME_FACT|DATE_FACT|SOGL|TIME_PLAN|TIT_HEAD|KOMM|ID_users|ID_users2|ID_users2_plan|ID_users3|CDATE|CTIME|TXT|DATE_PLAN|STATUS|ID_itrzadan";

		$db_cfg[$table."/ID_users"] = "list";		// Инициатор
		$db_cfg[$table."/ID_users|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users2"] = "list";		// Ответственный
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users2_plan"] = "list";		// Ответственный
		$db_cfg[$table."/ID_users2_plan|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users3"] = "list";		// Контроллер
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";	//
		$db_cfg[$table."/CDATE"] = "date";				// Дата создания задания
		$db_cfg[$table."/CTIME"] = "tinytext";			// время создания задания
		$db_cfg[$table."/DATE_FACT"] = "tinytext";		
		$db_cfg[$table."/TIME_FACT"] = "tinytext";		
		$db_cfg[$table."/TXT"] = "tinytext";			// Cодержание задания
		$db_cfg[$table."/KOMM"] = "tinytext";			// Комментарий автора
		$db_cfg[$table."/DATE_PLAN"] = "date";			// Желаемая инициатором дата
		$db_cfg[$table."/TIME_PLAN"] = "tinytext";		// Желаемое инициатором время
		$db_cfg[$table."/STATUS"] = "tinytext";			// Текущий статус задания
		$db_cfg[$table."/TIT_HEAD"] = "tinytext";		// Тип файла для префикса к номеру задания
		$db_cfg[$table."/ID_itrzadan"] = "integer";				// № документа
		$db_cfg[$table."/SOGL"] = "integer";				// № документа
		$db_cfg[$table."/TIP_ZAPR"] = "integer";				// № документа


////////////////////////////////
//
//		Онлайн чат    /// CUR_ID
//
////////////////////////////////

	$table = "db_online_chat_curid";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Онлайн чат / CUR_ID";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "CHTIME";


	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "CHTIME|NICK|WORDS";

		$db_cfg[$table."/CHTIME"] = "time";		// 
		$db_cfg[$table."/NICK"] = "tinytext";		// 
		$db_cfg[$table."/WORDS"] = "tinytext";		// 

////////////////////////////////
//
//		Онлайн чат    /// Пользователи
//
////////////////////////////////

	$table = "db_online_chat_curid_users";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Онлайн чат / CUR_ID";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "IO";

		$db_cfg[$table."/IO"] = "tinytext";		// 


///////////////////////////////
//
//		Протоколы - ЭДО
//
////////////////////////////////

	$table = "db_protocols";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Протоколы";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITUSER"] = "EUSER";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|ONCREATE"] = "add_db_protocols.php";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "EDIT_STATE|ETIME|EUSER|NUMBER|DATA|DATA_PLAN|ID_zaks|ID_users|ID_users2|ID_users3|ID_users4|NAME|TXT";

		$db_cfg[$table."/EDIT_STATE"] = "integer";			
		$db_cfg[$table."/ETIME"] = "time";			// время редактирования
		$db_cfg[$table."/EUSER"] = "list";			// кто редактировал
		$db_cfg[$table."/EUSER|LIST"] = "users";		
		$db_cfg[$table."/NUMBER"] = "tinytext";			// номер протокола
		$db_cfg[$table."/DATA"] = "date";			// дата совещания
		$db_cfg[$table."/DATA_PLAN"] = "text";		// Плановая дата задания
		$db_cfg[$table."/ID_zaks"] = "text";		// Заказы на момент формирования протокола
		$db_cfg[$table."/ID_users"] = "text";		// Автор совещания / задания
		$db_cfg[$table."/ID_users2"] = "text";		// исполнитель задания
		$db_cfg[$table."/ID_users3"] = "text";		// контролёр задания
		$db_cfg[$table."/ID_users4"] = "text";		// Секретарь совещания 
		$db_cfg[$table."/NAME"] = "tinytext";		// название совещания
		$db_cfg[$table."/TXT"] = "longtext";		// содержание задания



//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Представление - адресация мест перемещения
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_v_mov_adr";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Адресация мест перемещения";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";

$db_cfg[$table."|LIST_FIELD"] = "BARCODE|DESCRIPTION";
$db_cfg[$table."|LIST_SEARCH"] = "BARCODE|DESCRIPTION";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "BARCODE|DESCRIPTION|TYPE|STATUS";

$db_cfg[$table."/BARCODE"] = "tinytext";
$db_cfg[$table."/DESCRIPTION"] = "tinytext";
$db_cfg[$table."/TYPE"] = "pinteger";
$db_cfg[$table."/STATUS"] = "pinteger";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Места хранения с количеством
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_movements_tool_destination";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Места хранения с содержимым";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|EDITTIME"] = "";
$db_cfg[$table."|HOLDBY"] = "EXECUTE";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";

$db_cfg[$table."|LIST_FIELD"] = "";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "ID_MOV|ID_ADDR|N_QUANTITY|N_INPUT_TOTAL|N_OUTPUT_TOTAL|COUNT|DELETED|EXECUTE|S_EXECUTE";

$db_cfg[$table."/ID_MOV"] = "pinteger"; # Сссылка на операцию движения инструмента
$db_cfg[$table."/ID_ADDR"] = "list"; # Адрес списания
$db_cfg[$table."/ID_ADDR|LIST"] = "db_v_mov_adr";
$db_cfg[$table."/ID_ADDR|HOLD"] = "ID_ADDR";
$db_cfg[$table."/N_QUANTITY"] = "integer"; # Количество помещаемое в ячейку ("+" пополнение, "-" списание)
$db_cfg[$table."/N_INPUT_TOTAL"] = "integer"; # Входящий остаток в ячейке
$db_cfg[$table."/N_OUTPUT_TOTAL"] = "integer"; # Исходящий остаток в ячейке
$db_cfg[$table."/COUNT"] = "integer"; # Максимальное число участвующих в операции инструментов (сделано, без view - mybad :( )
$db_cfg[$table."/DELETED"] = "boolean"; # Из-за особенностей движка (наполнение данными идет через update а не во время insert), вот такой костыль с ограничением на количество проводок
$db_cfg[$table."/EXECUTE"] = "boolean";
#$db_cfg[$table."/EXECUTE|LIST"] = "STOP"; # Запрет изменения строки
$db_cfg[$table."/EXECUTE|HOLD"] = "ID_MOV|ID_ADDR|N_QUANTITY|N_INPUT_TOTAL|N_OUTPUT_TOTAL|COUNT|DELETED|EXECUTE|S_EXECUTE";
$db_cfg[$table."/S_EXECUTE"] = "tinytext";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
// Представление - выбор инструмента
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_v_reference_tool";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Справочник инструмента";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";

$db_cfg[$table."|LIST_FIELD"] = "S_NAME";
$db_cfg[$table."|LIST_SEARCH"] = "S_NAME";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "S_NAME";

$db_cfg[$table."/S_NAME"] = "tinytext";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Движение инструмента (проводки)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_movements_tool";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Движение инструмента";
$db_cfg[$table . "|DELRIGHT"] = "ID_USERS";
$db_cfg[$table . "|CREATEBY"] = "ID_USERS";
$db_cfg[$table . "|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table . "|EDITTIME"] = "DT_TIME";
$db_cfg[$table . "|HOLDBY"] = "ID_TOOL|EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "N_APPLICATION|ID_TOOL|DATE_CREATE|DT_TIME|ID_SIGN|N_QUANTITY|N_UNIT_PRICE|N_CAME_PRICE|N_INPUT_TOTAL|N_OUTPUT_TOTAL|N_PRICE_AFT_OPER|N_CUR_PRICE|EXECUTE|S_PLACE_AND_QANTITY|ID_STOCK_DOC|ID_USERS|S_EXECUTE";

$db_cfg[$table . "/N_APPLICATION"] = "integer";    # Порядковый номер документа, только имеющим родителя
$db_cfg[$table . "/ID_TOOL"] = "list"; # Сссылка на инструмент
$db_cfg[$table . "/ID_TOOL|LIST"] = "db_v_reference_tool";
$db_cfg[$table . "/ID_TOOL|HOLD"] = "ID_TOOL";
$db_cfg[$table . "/DATE_CREATE"] = "date"; # Для дерева - архива
$db_cfg[$table . "/DT_TIME"] = "time"; # Дата + время изменения
$db_cfg[$table . "/ID_SIGN"] = "state";  // Основная операция проводки
$db_cfg[$table . "/ID_SIGN|LIST"] = "Приход|Расход|Cклад-->Ресурс|Склад-->Склад|Ресурс-->Склад";
$db_cfg[$table . "/N_QUANTITY"] = "pinteger"; # Количество
$db_cfg[$table . "/N_UNIT_PRICE"] = "preal"; # Цена прихода
$db_cfg[$table . "/N_CAME_PRICE"] = "preal"; # Стоимость прихода
$db_cfg[$table . "/N_INPUT_TOTAL"] = "integer"; # Входящий остаток
$db_cfg[$table . "/N_OUTPUT_TOTAL"] = "integer"; # Исходящий остаток
$db_cfg[$table . "/N_PRICE_AFT_OPER"] = "preal"; # Цена после операции
$db_cfg[$table . "/N_CUR_PRICE"] = "preal"; # Текущая стоимость
$db_cfg[$table . "/S_PLACE_AND_QANTITY"] = "tinytext"; # Текстовое представление размещения и количества
$db_cfg[$table . "/EXECUTE"] = "boolean";
#$db_cfg[$table . "/EXECUTE|LIST"] = "ПРОВЕДЕНО"; # ВИЗИРОВАНИЕ - признак, того что проводка исполнена, а дочерние операции проведены.
$db_cfg[$table . "/EXECUTE|HOLD"] = "N_APPLICATION|ID_TOOL|DATE_CREATE|DT_TIME|ID_SIGN|N_QUANTITY|N_UNIT_PRICE|N_CAME_PRICE|N_INPUT_TOTAL|N_OUTPUT_TOTAL|N_PRICE_AFT_OPER|N_CUR_PRICE|EXECUTE|S_EXECUTE";
$db_cfg[$table . "/ID_STOCK_DOC"] = "pinteger"; # Ссылка на складской родительский документ
$db_cfg[$table . "/ID_USERS"] = "list";            // Автор документа
$db_cfg[$table . "/ID_USERS|LIST"] = "users";
$db_cfg[$table . "/S_EXECUTE"] = "tinytext";

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Отображение основных складских документов (для проводок)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_v_stocks_doc";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Отображение основных складских документов (для проводок)";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";


$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_DOCTYPE|S_DOC_NUMBER|DATE_CREATE";

$db_cfg[$table . "/ID_DOCTYPE"] = "state";  // Тип документа
$db_cfg[$table . "/ID_DOCTYPE|LIST"] = "Приходная накладная|Журнал перемещений|Акт списания|Акт инвентаризации";
$db_cfg[$table . "/ID_DOCTYPE|HOLD"] = "ID_DOCTYPE";
$db_cfg[$table . "/S_DOC_NUMBER"] = "tinytext";    # № Документа
$db_cfg[$table . "/DATE_CREATE"] = "date"; # Дата документа и для архива
$db_cfg[$table . "/N_SUM_CAME_PRICE"] = "preal"; #

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Виды складских документов
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_stock_doctype";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Виды складских документов";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "DESCRIPTION";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "DESCRIPTION";

$db_cfg[$table . "/DESCRIPTION"] = "tinytext";

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Складские документы
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_stocks_doc";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Основные складские документы";
$db_cfg[$table."|DELRIGHT"] = "ID_USERS";
$db_cfg[$table."|CREATEBY"] = "ID_USERS";
$db_cfg[$table."|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table."|EDITTIME"] = "DT_TIME";
$db_cfg[$table."|HOLDBY"] = "ID_DOCTYPE|EXECUTE";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";


$db_cfg[$table."|LIST_FIELD"] = "";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "ID_DOCTYPE|ID_OPERATION|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";

$db_cfg[$table."/ID_DOCTYPE"] = "droplist";  // Тип документа
$db_cfg[$table."/ID_DOCTYPE|LIST"] = "db_stock_doctype";
$db_cfg[$table."/ID_DOCTYPE|LIST_WHERE"] = "ID>'0'";
$db_cfg[$table."/ID_DOCTYPE|HOLD"] = "ID_DOCTYPE";
$db_cfg[$table."/S_DOC_NUMBER"] = "tinytext";	# № Документа
$db_cfg[$table."/DATE_CREATE"] = "date"; # Дата документа и для архива
$db_cfg[$table."/DT_TIME"] = "time"; # Дата + время изменения документа
$db_cfg[$table."/ID_USERS"] = "list";			// Автор документа
$db_cfg[$table."/ID_USERS|LIST"] = "users";
$db_cfg[$table."/EXECUTE"] = "boolean"; # Документ исполнен (только, если - все дочерние проводки проведены)
$db_cfg[$table."/EXECUTE|HOLD"] = "ID_DOCTYPE|ID_OPERATION|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Карточка учёта (охрана труда)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_safety_job";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Карточка учёта (охрана труда)";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";


$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_RESURS|A0_1|A0_2|A1_1|A1_2|A2_1|A2_2|A2_3|A3_1|A3_2|A3_3|A4_1|A4_2|A4_3|A5_1|A5_2|A5_3|A6_1|A6_2|A6_3|A7_1|A7_2|A7_3|A7_4|A8_1|A8_2|A9_1|A10_1|A10_2|A11_1|A12_1|A12_2|A12_3|A12_4|A13_1|A13_2|A14_1|A15_1|A15_2|B1_1|B1_2|B1_3|B2_1|B2_2|B2_3|B3_1|B3_2|B3_3|B4_1|B4_2|B4_3|B5_1|B5_2|B5_3|B5_4|B6_1|B6_2|B6_3|B6_4|B7_1|B7_2|B7_3|B7_4|C1_1|C1_2|D1_1|D1_2|D2_1|D2_2|D3_1|D3_2|D4_1|D4_2|D5_1|D5_2|E1_1|E1_2|E1_3|E1_4|E2_1|E2_2|E2_3|E2_4|F1_1|F1_2|F1_3|F1_4|F2_1|F2_2|F2_3|F2_4|F3_1|F3_2|F4_1|F4_2|F5_1|F5_2|G1_1|G1_2|G1_3|G1_4|G2_1|G2_2|G2_3|G2_4";

$db_cfg[$table . "/ID_RESURS"] = "list";
$db_cfg[$table . "/ID_RESURS|LIST"] = "db_resurs";
$db_cfg[$table . "/A0_1"] = "tinytext"; # Личная карточка по ОТ
$db_cfg[$table . "/A0_2"] = "tinytext"; # Лист ознакомления
################ Охрана труда
$db_cfg[$table . "/A1_1"] = "tinytext"; # Программа первичного инструктажа
$db_cfg[$table . "/A1_2"] = "date"; #
$db_cfg[$table . "/A2_1"] = "date"; # Инструктаж Вводный
$db_cfg[$table . "/A2_2"] = "pinteger"; #
$db_cfg[$table . "/A2_3"] = "pinteger"; #
$db_cfg[$table . "/A3_1"] = "date"; # И Первичный
$db_cfg[$table . "/A3_2"] = "pinteger"; #
$db_cfg[$table . "/A3_3"] = "pinteger"; #
$db_cfg[$table . "/A4_1"] = "date"; # И Повторный
$db_cfg[$table . "/A4_2"] = "pinteger"; #
$db_cfg[$table . "/A4_3"] = "pinteger"; #
$db_cfg[$table . "/A5_1"] = "date"; # И Внеплановый
$db_cfg[$table . "/A5_2"] = "pinteger"; #
$db_cfg[$table . "/A5_3"] = "pinteger"; #
$db_cfg[$table . "/A6_1"] = "date"; # И Целевой
$db_cfg[$table . "/A6_2"] = "pinteger"; #
$db_cfg[$table . "/A6_3"] = "pinteger"; #
$db_cfg[$table . "/A7_1"] = "date"; # Стажировка
$db_cfg[$table . "/A7_2"] = "date"; #
$db_cfg[$table . "/A7_3"] = "pinteger"; #
$db_cfg[$table . "/A7_4"] = "pinteger"; #
$db_cfg[$table . "/A8_1"] = "tinytext"; # Протокол
$db_cfg[$table . "/A8_2"] = "date"; #
$db_cfg[$table . "/A9_1"] = "date"; # Допуск к работе
$db_cfg[$table . "/A10_1"] = "tinytext"; # Удостоверение
$db_cfg[$table . "/A10_2"] = "date"; #
$db_cfg[$table . "/A11_1"] = "tinytext"; # 2 Профессия
$db_cfg[$table . "/A12_1"] = "date"; # Стажировка
$db_cfg[$table . "/A12_2"] = "date"; #
$db_cfg[$table . "/A12_3"] = "pinteger"; #
$db_cfg[$table . "/A12_4"] = "pinteger"; #
$db_cfg[$table . "/A13_1"] = "tinytext"; # Протокол
$db_cfg[$table . "/A13_2"] = "date"; #
$db_cfg[$table . "/A14_1"] = "date"; # Допуск к работе
$db_cfg[$table . "/A15_1"] = "tinytext"; # Удостоверение
$db_cfg[$table . "/A15_2"] = "date"; #
################ Пожарная безопасность
$db_cfg[$table . "/B1_1"] = "date"; # Инструктаж Вводный
$db_cfg[$table . "/B1_2"] = "pinteger"; #
$db_cfg[$table . "/B1_3"] = "pinteger"; #
$db_cfg[$table . "/B2_1"] = "date"; # И Первичный
$db_cfg[$table . "/B2_2"] = "pinteger"; #
$db_cfg[$table . "/B2_3"] = "pinteger"; #
$db_cfg[$table . "/B3_1"] = "date"; # И Повторный
$db_cfg[$table . "/B3_2"] = "pinteger"; #
$db_cfg[$table . "/B3_3"] = "pinteger"; #
$db_cfg[$table . "/B4_1"] = "date"; # И Внеплановый
$db_cfg[$table . "/B4_2"] = "pinteger"; #
$db_cfg[$table . "/B4_3"] = "pinteger"; #
$db_cfg[$table . "/B5_1"] = "date"; # Стажировка
$db_cfg[$table . "/B5_2"] = "date"; #
$db_cfg[$table . "/B5_3"] = "pinteger"; #
$db_cfg[$table . "/B5_4"] = "pinteger"; #
$db_cfg[$table . "/B6_1"] = "tinytext"; # Вкладыш
$db_cfg[$table . "/B6_2"] = "date"; #
$db_cfg[$table . "/B6_3"] = "pinteger"; #
$db_cfg[$table . "/B6_4"] = "pinteger"; #
$db_cfg[$table . "/B7_1"] = "tinytext"; # Протокол
$db_cfg[$table . "/B7_2"] = "date"; #
$db_cfg[$table . "/B7_3"] = "pinteger"; #
$db_cfg[$table . "/B7_4"] = "pinteger"; #
################ МОЛОКО
$db_cfg[$table . "/C1_1"] = "tinytext"; #
$db_cfg[$table . "/C1_2"] = "date"; #
################ Карта спец. оценки условий труда
$db_cfg[$table . "/D1_1"] = "tinytext"; # Ознакомление
$db_cfg[$table . "/D1_2"] = "date"; #
$db_cfg[$table . "/D2_1"] = "tinytext"; #
$db_cfg[$table . "/D2_2"] = "date"; #
$db_cfg[$table . "/D3_1"] = "tinytext"; #
$db_cfg[$table . "/D3_2"] = "date"; #
$db_cfg[$table . "/D4_1"] = "tinytext"; #
$db_cfg[$table . "/D4_2"] = "date"; #
$db_cfg[$table . "/D5_1"] = "tinytext"; #
$db_cfg[$table . "/D5_2"] = "date"; #
################ Медосмотр
$db_cfg[$table . "/E1_1"] = "tinytext"; # Первичный
$db_cfg[$table . "/E1_2"] = "date"; #
$db_cfg[$table . "/E1_3"] = "pinteger"; #
$db_cfg[$table . "/E1_4"] = "pinteger"; #
$db_cfg[$table . "/E2_1"] = "tinytext"; # Повторный
$db_cfg[$table . "/E2_2"] = "date"; #
$db_cfg[$table . "/E2_3"] = "pinteger"; #
$db_cfg[$table . "/E2_4"] = "pinteger"; #
################ Электробезопасность
$db_cfg[$table . "/F1_1"] = "date"; # Стажировка
$db_cfg[$table . "/F1_2"] = "date"; #
$db_cfg[$table . "/F1_3"] = "pinteger"; #
$db_cfg[$table . "/F1_4"] = "pinteger"; #
$db_cfg[$table . "/F2_1"] = "date"; # Дублирование
$db_cfg[$table . "/F2_2"] = "date"; #
$db_cfg[$table . "/F2_3"] = "pinteger"; #
$db_cfg[$table . "/F2_4"] = "pinteger"; #
$db_cfg[$table . "/F3_1"] = "tinytext"; # Протокол
$db_cfg[$table . "/F3_2"] = "date"; #
$db_cfg[$table . "/F4_1"] = "tinytext"; # Удостоверение
$db_cfg[$table . "/F4_2"] = "date"; #
$db_cfg[$table . "/F5_1"] = "tinytext"; # Группа
$db_cfg[$table . "/F5_2"] = "date"; #
################ СИЗ
$db_cfg[$table . "/G1_1"] = "tinytext"; # Размер обуви
$db_cfg[$table . "/G1_2"] = "date"; # Дата выдачи
$db_cfg[$table . "/G1_3"] = "date"; # Дата замены
$db_cfg[$table . "/G1_4"] = "date"; # Дата возврата
$db_cfg[$table . "/G2_1"] = "tinytext"; # Размер одежды
$db_cfg[$table . "/G2_2"] = "date"; # Дата выдачи
$db_cfg[$table . "/G2_3"] = "date"; # Дата замены
$db_cfg[$table . "/G2_4"] = "date"; # Дата возврата
$db_cfg[$table . "/height"] = "pinteger"; # Рост

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Инвентаризационная ведомость
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_stocks_doc_inventory";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Инвентаризационная ведомость";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "ID_USERS";
$db_cfg[$table . "|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table . "|EDITTIME"] = "DT_TIME";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_INV_CAT_TOOLS|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";

$db_cfg[$table . "/ID_INV_CAT_TOOLS"] = "droplist";  // Инструмент по которому проводится инвентаризация
$db_cfg[$table . "/ID_INV_CAT_TOOLS|LIST"] = "db_inv_cat_tools";	//
$db_cfg[$table . "/ID_INV_CAT_TOOLS|LIST_WHERE"] = "PID='0'";	//
$db_cfg[$table . "/S_DOC_NUMBER"] = "tinytext";    # № Документа
$db_cfg[$table . "/DATE_CREATE"] = "date"; # Дата создания документа
$db_cfg[$table . "/DT_TIME"] = "time"; # Дата + время изменения документа
$db_cfg[$table . "/ID_USERS"] = "list";            // Автор документа
$db_cfg[$table . "/ID_USERS|LIST"] = "users";
$db_cfg[$table . "/EXECUTE"] = "boolean"; # Документ исполнен (есть порождённый акт инвентаризации или в итоге нет расхождений по ведомости с реальными остатками)
$db_cfg[$table . "/EXECUTE|HOLD"] = "ID_INV_CAT_TOOLS|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Предметы инвентаризации
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_inv_s";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Предметы инвентаризации";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "N_APPLICATION|ID_DOC_INVENTORY|ID_TOOL|EXECUTE";

$db_cfg[$table . "/N_APPLICATION"] = "integer";    # Порядковый номер в ведомости
$db_cfg[$table . "/ID_DOC_INVENTORY"] = "integer";  # Инвентаризационная ведомость
$db_cfg[$table . "/ID_TOOL"] = "droplist"; # Сссылка на инструмент
$db_cfg[$table . "/ID_TOOL|LIST"] = "db_v_reference_tool";
$db_cfg[$table . "/EXECUTE"] = "boolean"; # Для запрета редактирования, после сверки
$db_cfg[$table . "/EXECUTE|HOLD"] = "N_APPLICATION|ID_INVENTORY|ID_TOOL|EXECUTE";



//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Расположение предметов во время инвентаризации (строка с адресом и количеством)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_inv_s_subj_addr";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "Расположение предметов во время инвентаризации";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_INVENTORY_SUBJ|ID_ADDR|N_COUNT|EXECUTE";

$db_cfg[$table."/ID_INVENTORY_SUBJ"] = "integer"; # Предмет инвентаризации
$db_cfg[$table."/ID_ADDR"] = "list"; # Адрес списания
$db_cfg[$table."/ID_ADDR|LIST"] = "db_v_mov_adr";
$db_cfg[$table . "/N_COUNT"] = "pinteger";    # Количество позиций в ячейке (зафиксированное инвентаризацией)
$db_cfg[$table . "/N_COUNT_REAL"] = "pinteger";    # Количество позиций в ячейке (на момент создания ведомости инвентаризации)
$db_cfg[$table . "/EXECUTE"] = "boolean"; # Для запрета редактирования, после сверки
$db_cfg[$table . "/EXECUTE|HOLD"] = "ID_INVENTORY_SUBJ|ID_ADDR|N_COUNT|EXECUTE";

////////////////////////////////
//
//		Переходы - МТК
//
////////////////////////////////

	$table = "db_mtk_perehod";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Переходы - МТК";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITUSER"] = "EUSER";
	$db_cfg[$table."|EDITTIME"] = "ETIME";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "TID|ETIME|EUSER|ID_zak|ID_zakdet|ID_operitems|TXT|INSTR_1|INSTR_2|INSTR_3|DIAM_SHIR|DLINA|R_O_S|R_O_N|R_O_V|R_O_TO|R_O_TP";

		$db_cfg[$table."/TID"] = "list";		// 
		$db_cfg[$table."/ETIME"] = "time";		// 
		$db_cfg[$table."/EUSER"] = "tinytext";		// 
		$db_cfg[$table."/TXT"] = "tinytext";		// 
		$db_cfg[$table."/INSTR_1"] = "tinytext";		// 
		$db_cfg[$table."/INSTR_2"] = "tinytext";		// 
		$db_cfg[$table."/INSTR_3"] = "tinytext";		// 
		$db_cfg[$table."/DIAM_SHIR"] = "tinytext";		// 
		$db_cfg[$table."/DLINA"] = "tinytext";		// 
		$db_cfg[$table."/R_O_S"] = "tinytext";		// 
		$db_cfg[$table."/R_O_N"] = "tinytext";		// 
		$db_cfg[$table."/R_O_V"] = "tinytext";		// 
		$db_cfg[$table."/R_O_TO"] = "tinytext";		// 
		$db_cfg[$table."/R_O_TP"] = "tinytext";		// 
		$db_cfg[$table."/ID_zak"] = "list";		// 
		$db_cfg[$table."/ID_zakdet"] = "list";		// 
		$db_cfg[$table."/ID_operitems"] = "list";		// 

////////////////////////////////
//
//		Переходы - МТК _ IMG
//
////////////////////////////////

	$table = "db_mtk_perehod_img";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "Переходы - МТК _ IMG";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITUSER"] = "EUSER";
	$db_cfg[$table."|EDITTIME"] = "ETIME";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ETIME|EUSER|ID_zak|ID_zakdet|ID_operitems|TID|IMG";

		$db_cfg[$table."/ETIME"] = "time";		// 
		$db_cfg[$table."/EUSER"] = "tinytext";		// 
		$db_cfg[$table."/TID"] = "list";		// 
		$db_cfg[$table."/IMG"] = "tinytext";		// 
		$db_cfg[$table."/ID_zak"] = "list";		// 
		$db_cfg[$table."/ID_zakdet"] = "list";		// 
		$db_cfg[$table."/ID_operitems"] = "list";		// 

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  Движение инструмента - журнал перемещения по складу
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_movements_tool_transfer";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "Движение инструмента - журнал перемещения по складу";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "ID_USERS";
$db_cfg[$table."|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table."|EDITTIME"] = "";
$db_cfg[$table."|HOLDBY"] = "EXECUTE|ID_TOOL|ID_ADDR_FROM";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";

$db_cfg[$table."|LIST_FIELD"] = "";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "N_APPLICATION|DATE_CREATE|ID_TOOL|N_QUANTITY|N_UNIT_PRICE|N_CUR_PRICE|ID_ADDR_FROM|ID_ADDR_TO|ID_STOCK_DOC|ID_MOVEMENTS_TOOL|ID_USERS|EXECUTE";

$db_cfg[$table."/N_APPLICATION"] = "integer";    # Порядковый номер строки в проводке (выч)
$db_cfg[$table."/DATE_CREATE"] = "date"; # Дата создания документа (выч)
$db_cfg[$table."/ID_TOOL"] = "list"; # Сссылка на инструмент
$db_cfg[$table."/ID_TOOL|LIST"] = "db_v_reference_tool";
$db_cfg[$table."/ID_TOOL|HOLD"] = "ID_TOOL";
$db_cfg[$table."/N_QUANTITY"] = "pinteger"; # Количество
$db_cfg[$table."/N_UNIT_PRICE"] = "preal"; # Цена прихода (выч)
$db_cfg[$table."/N_CUR_PRICE"] = "preal"; # Текущая стоимость (выч)
$db_cfg[$table."/ID_ADDR_FROM"] = "list"; # Адрес списания
$db_cfg[$table."/ID_ADDR_FROM|LIST"] = "db_v_mov_adr";
$db_cfg[$table."/ID_ADDR_FROM|HOLD"] = "ID_ADDR_FROM";
$db_cfg[$table."/ID_ADDR_FROM|LIST_WHERE"] = "ID!=125";
$db_cfg[$table."/ID_ADDR_TO"] = "list"; # Адрес зачисления
$db_cfg[$table."/ID_ADDR_TO|LIST"] = "db_v_mov_adr";
$db_cfg[$table."/ID_ADDR_TO|LIST_WHERE"] = "ID!=125";
$db_cfg[$table."/ID_STOCK_DOC"] = "pinteger"; # Ссылка на складской родительский документ
$db_cfg[$table."/ID_MOVEMENTS_TOOL"] = "pinteger"; # Ссылка на проводку
$db_cfg[$table."/ID_USERS"] = "list"; # Автор
$db_cfg[$table."/ID_USERS|LIST"] = "users";
$db_cfg[$table."/EXECUTE"] = "boolean"; # Документ исполнен
$db_cfg[$table."/EXECUTE|HOLD"] = "N_APPLICATION|DATE_CREATE|ID_TOOL|N_QUANTITY|N_CUR_PRICE|N_UNIT_PRICE|ID_ADDR_FROM|ID_ADDR_TO|ID_STOCK_DOC|ID_MOVEMENTS_TOOL|ID_USERS|EXECUTE";


?>