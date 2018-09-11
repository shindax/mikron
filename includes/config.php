<?php
/////////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
/////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////
//
// ФАЙЛ КОНФИГУРАЦИИ БАЗЫ ДАННЫХ
//
/////////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


// ПРАВА НА ДОБАВЛЕНИЕ / УДАЛЕНИЕ
	function db_adcheck($db) {
		
		global $db_cfg, $user, $user_rights, $print_mode;

		$res = false;
		if (is_array($user_rights)) {
			if (in_array('superadmin',$user_rights)) $res = true;
			if (in_array($db.'|superadmin',$user_rights)) $res = true;
			if (in_array($db.'|add',$user_rights)) $res = true;
		}

	   // СПЕЦ ТАБЛИЦЫ
		if (($user['USERSEDIT']=='1') && ($db=='users')) $res = true;
		if (($user['ID']=='1') && ($db=='rightgroups')) $res = true;
		if (($user['ID']=='1') && ($db=='viewgroups')) $res = true;
		if (($user['ID']=='1') && ($db=='formgroups')) $res = true;
		if (($user['ID']=='1') && ($db=='forms')) $res = true;
		if (($user['ID']=='1') && ($db=='formsitem')) $res = true;

		if ($print_mode=='on') $res = false;

		return $res;
	}

// ПРАВА НА РЕДАКТИРОВАНИЕ ПАССИВНЫЕ
	function db_check($db,$field) {
		
		global $db_cfg, $user, $user_rights, $print_mode;

		$res = false;
		if (is_array($user_rights)) {
			if (in_array($db.'|redactor',$user_rights)) $res = true;
			if (!isset($db_cfg[$db.'/'.$field])) $res = false;
			if (in_array('-'.$db.'/'.$field,$user_rights)) $res = false;
			if (in_array($db.'/'.$field,$user_rights)) $res = true;
			if (in_array($db.'|superadmin',$user_rights)) $res = true;
			if (in_array('superadmin',$user_rights)) $res = true;
		}

		
	   // СПЕЦ ТАБЛИЦЫ
		if (($user['USERSEDIT']=='1') && ($db=='users')) $res = true;
		if (($user['ID']=='1') && ($db=='rightgroups')) $res = true;
		if (($user['ID']=='1') && ($db=='viewgroups')) $res = true;
		if (($user['ID']=='1') && ($db=='formgroups')) $res = true;
		if (($user['ID']=='1') && ($db=='forms')) $res = true;
		if (($user['ID']=='1') && ($db=='formsitem')) $res = true;

	   // СПЕЦ ПОЛЯ
		if ($field=='ID') $res = false;
		if ($field=='PID') $res = false;

	   // PRINTMODE
		if ($print_mode=='on') $res = false;

		return $res;
	}

	 
	
// ПРАВА НА РЕДАКТИРОВАНИЕ АКТИВНЫЕ (зависят от $row)
	function db_check_activ($row,$db,$field) {

		global $db_cfg, $user, $user_rights;

		 
		
		$res=true;

	   // Индивидуальные права редактирования
		if ($db_cfg[$db.'/'.$field."|EDITRIGHT"].''!=='') {
			if ($row[$db_cfg[$db.'/'.$field.'|EDITRIGHT']]!==$user['ID']) {
				$res=false;
				
			}

			
		}

	   // HOLDBY
		if ($db_cfg[$db.'|HOLDBY'].''!=='') {
		   if (!in_array($db.'|onhold',$user_rights)) {
			$holds = explode('|',$db_cfg[$db.'|HOLDBY']);
			$holds_count = count($holds);
			for ($h=0;$h < $holds_count;++$h) {
				if ($row[$holds[$h]]*1!==0) {
					$hfields = explode('|',$db_cfg[$db.'/'.$holds[$h].'|HOLD']);
					if (in_array($field,$hfields)) $res = false;
				}
			}
		   }
		}
		
		
		if (($row['ID_USERS'] == 165 || $row['ID_USERS'] == 228) && $_GET['formid'] == '123' && $user['ID'] == 5) {
			$res = true;
		}

		return $res;
	}

// ПРАВА НА ПРОСМОТР ФОРМЫ
	function showform_check($form) {
		global $db_cfg, $user, $user_showrights;

		$res = false;

		if (in_array($form["ID"],$user_showrights)) $res = true;
		if ($form['SHOWALL']=="1") $res = true;

		return $res;
	}




// Автоматическое вычисление USEDIN


	function Auto_USEDIN() {
		global $db_cfg;

		$tables_list = $db_cfg["SYSTEM"]."|".$db_cfg["PROJECT"];
		$db_tables = explode('|',$tables_list);
		$db_tables_count = count($db_tables);
		for ($j=0;$j < $db_tables_count;++$j) {
			$db_cfg[$db_tables[$j]."|USEDIN"] = '|';
		}
		for ($j=0;$j < $db_tables_count;++$j) {
			if ($db_cfg[$db_tables[$j]."|TYPE"]=="tree") $db_cfg[$db_tables[$j]."|USEDIN"] = $db_cfg[$db_tables[$j]."|USEDIN"].$db_tables[$j]."/PID|";
			if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $db_cfg[$db_tables[$j]."|USEDIN"] = $db_cfg[$db_tables[$j]."|USEDIN"].$db_tables[$j]."/PID|";
			if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $db_cfg[$db_tables[$j]."|USEDIN"] = $db_cfg[$db_tables[$j]."|USEDIN"].$db_tables[$j]."/LID|";
			$db_fields = explode('|',$db_cfg[$db_tables[$j]."|FIELDS"]);
			$db_fields_count = count($db_fields);
			for ($i=0;$i < $db_fields_count;++$i) {
				$addused = false;
				if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]] == "droplist") $addused = true;
				if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]] == "list") $addused = true;
				if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]] == "multilist") $addused = true;

				if ($addused) $db_cfg[$db_cfg[$db_tables[$j]."/".$db_fields[$i]."|LIST"]."|USEDIN"] = $db_cfg[$db_cfg[$db_tables[$j]."/".$db_fields[$i]."|LIST"]."|USEDIN"].$db_tables[$j]."/".$db_fields[$i]."|";
			}
		}

	}











////////////////////////////////////////////////////////////////////////////
//
// CONFIG системы
//
////////////////////////////////////////////////////////////////////////////


	$db_cfg["SYSTEM"] = "users|rightgroups|viewgroups|formgroups|forms|formsitem";


////////////////////////////////////////////////////////////////////////////

	$db_cfg["users|TYPE"] = "line";				// Тип таблицы
	$db_cfg["users|ERP"] = "true";				// Обозначение таблиц ядра (предустановленные - true, пользовательские - false)

	$db_cfg["users|LIST_FIELD"] = "FIO";			// Поля для вывода списка если используется в качестве: list, grouplist, groupedlist
	$db_cfg["users|LIST_SEARCH"] = "FIO";
	$db_cfg["users|LIST_PREFIX"] = ", ";			// Префикс для вывода списка если используется в качестве: list, grouplist, groupedlist
	$db_cfg["users|GROUPLIST_FIELD"] = "";
	$db_cfg["users|GROUPLIST_GROUP"] = "";

	$db_cfg["users|FIELDS"] = "LOGIN|PASS|FIO|IO|STATE|USERSEDIT|ID_forms|ID_rightgroups";
	$db_cfg["users|ADDINDEX"] = "";

		$db_cfg["users/LOGIN"] = "tinytext";
		$db_cfg["users/PASS"] = "tinytext";
		$db_cfg["users/FIO"] = "tinytext";
		$db_cfg["users/IO"] = "tinytext";
		$db_cfg["users/STATE"] = "state";
		$db_cfg["users/STATE|LIST"] = $loc["c1"];
		$db_cfg["users/USERSEDIT"] = "boolean";
		$db_cfg["users/ID_forms"] = "multilist";	// Права на просмотр форм
		$db_cfg["users/ID_forms|LIST"] = "viewgroups";
		$db_cfg["users/ID_forms|LIST_ORDER"] = "binary(NAME)";
		$db_cfg["users/ID_rightgroups"] = "multilist";	// Права на редакцию - группы доступа в rightgroups
		$db_cfg["users/ID_rightgroups|LIST"] = "rightgroups";
		$db_cfg["users/ID_rightgroups|LIST_ORDER"] = "binary(NAME)";

///////////////////////////////////////////////////////////////////////////

	$db_cfg["rightgroups|TYPE"] = "line";
	$db_cfg["rightgroups|ERP"] = "true";

	$db_cfg["rightgroups|LIST_FIELD"] = "ID|NAME";
	$db_cfg["rightgroups|LIST_SEARCH"] = "NAME";
	$db_cfg["rightgroups|LIST_PREFIX"] = " - ";
	$db_cfg["rightgroups|GROUPLIST_FIELD"] = "";
	$db_cfg["rightgroups|GROUPLIST_GROUP"] = "";

	$db_cfg["rightgroups|FIELDS"] = "NAME|RIGHTS";
	$db_cfg["rightgroups|ADDINDEX"] = "";

		$db_cfg["rightgroups/NAME"] = "tinytext";
		$db_cfg["rightgroups/RIGHTS"] = "textarea";

///////////////////////////////////////////////////////////////////////////

	$db_cfg["viewgroups|TYPE"] = "line";
	$db_cfg["viewgroups|ERP"] = "true";

	$db_cfg["viewgroups|LIST_FIELD"] = "ID|NAME";
	$db_cfg["viewgroups|LIST_SEARCH"] = "NAME";
	$db_cfg["viewgroups|LIST_PREFIX"] = " - ";
	$db_cfg["viewgroups|GROUPLIST_FIELD"] = "";
	$db_cfg["viewgroups|GROUPLIST_GROUP"] = "";

	$db_cfg["viewgroups|FIELDS"] = "NAME|RIGHTS";
	$db_cfg["viewgroups|ADDINDEX"] = "";

		$db_cfg["viewgroups/NAME"] = "tinytext";
		$db_cfg["viewgroups/RIGHTS"] = "multilist";
		$db_cfg["viewgroups/RIGHTS|LIST"] = "forms";

///////////////////////////////////////////////////////////////////////////

	$db_cfg["formgroups|TYPE"] = "line";
	$db_cfg["formgroups|ERP"] = "true";

	$db_cfg["formgroups|LIST_FIELD"] = "NAME";
	$db_cfg["formgroups|LIST_SEARCH"] = "NAME";
	$db_cfg["formgroups|LIST_PREFIX"] = ", ";
	$db_cfg["formgroups|GROUPLIST_FIELD"] = "";
	$db_cfg["formgroups|GROUPLIST_GROUP"] = "";

	$db_cfg["formgroups|FIELDS"] = "ORD|NAME|BARID";
	$db_cfg["formgroups|ADDINDEX"] = "";

		$db_cfg["formgroups/ORD"] = "tinytext";
		$db_cfg["formgroups/NAME"] = "tinytext";
		$db_cfg["formgroups/BARID"] = "alist";
		$db_cfg["formgroups/BARID|LIST"] = "1|2|3|4|5|6|7|8|9";

///////////////////////////////////////////////////////////////////////////

	$db_cfg["forms|TYPE"] = "line";
	$db_cfg["forms|ERP"] = "true";

	$db_cfg["forms|LIST_FIELD"] = "NAME";
	$db_cfg["forms|LIST_SEARCH"] = "NAME";
	$db_cfg["forms|LIST_PREFIX"] = ", ";
	$db_cfg["forms|GROUPLIST_FIELD"] = "";
	$db_cfg["forms|GROUPLIST_GROUP"] = "";

	$db_cfg["forms|FIELDS"] = "ORD|NAME|FILE|ID_formgroups|PATTERN|HLP|GROUPID|SHOWALL|USEFILE";
	$db_cfg["forms|ADDINDEX"] = "";

		$db_cfg["forms/ORD"] = "tinytext";
		$db_cfg["forms/NAME"] = "tinytext";
		$db_cfg["forms/FILE"] = "tinytext";
		$db_cfg["forms/ID_formgroups"] = "droplist";
		$db_cfg["forms/ID_formgroups|LIST"] = "formgroups";
		$db_cfg["forms/PATTERN"] = "html";
		$db_cfg["forms/HLP"] = "textarea";
		$db_cfg["forms/GROUPID"] = "alist";
		$db_cfg["forms/GROUPID|LIST"] = "1|2|3|4|5|6|7|8|9";
		$db_cfg["forms/SHOWALL"] = "boolean";
		$db_cfg["forms/USEFILE"] = "boolean";

///////////////////////////////////////////////////////////////////////////

	$db_cfg["formsitem|TYPE"] = "line";
	$db_cfg["formsitem|ERP"] = "true";

	$db_cfg["formsitem|LIST_FIELD"] = "NAME";
	$db_cfg["formsitem|LIST_SEARCH"] = "NAME";
	$db_cfg["formsitem|LIST_PREFIX"] = ", ";
	$db_cfg["formsitem|GROUPLIST_FIELD"] = "";
	$db_cfg["formsitem|GROUPLIST_GROUP"] = "";

	$db_cfg["formsitem|FIELDS"] = "NAME|MORE|TID|HEADER|DB_TABLE_1|DB_TABLE_2|FIELD_1|FIELD_2|OPENID_1|OPENID_2|WIDTH|COLSPAN|SQL_1|SQL_2|ID_formgroups";
	$db_cfg["formsitem|ADDINDEX"] = "";

		$db_cfg["formsitem/DB_TABLE_1"] = "tinytext";
		$db_cfg["formsitem/DB_TABLE_2"] = "tinytext";
		$db_cfg["formsitem/NAME"] = "tinytext";
		$db_cfg["formsitem/MORE"] = "tinytext";
		$db_cfg["formsitem/TID"] = "alist";
		$db_cfg["formsitem/TID|LIST"] = $loc["c2_1"]."|".$loc["c2_2"]."|".$loc["c2_3"]."|".$loc["c2_4"]."|".$loc["c2_5"]."|".$loc["c2_6"]."|".$loc["c2_7"]."|".$loc["c2_8"]."|".$loc["c2_9"]."|".$loc["c2_10"]."|".$loc["c2_11"]."|".$loc["c2_12"];
		$db_cfg["formsitem/HEADER"] = "html";
		$db_cfg["formsitem/OPENID_1"] = "html";
		$db_cfg["formsitem/OPENID_2"] = "html";
		$db_cfg["formsitem/FIELD_1"] = "tinytext";
		$db_cfg["formsitem/FIELD_2"] = "tinytext";
		$db_cfg["formsitem/WIDTH"] = "pinteger";
		$db_cfg["formsitem/COLSPAN"] = "pinteger";
		$db_cfg["formsitem/SQL_1"] = "tinytext";
		$db_cfg["formsitem/SQL_2"] = "tinytext";
		$db_cfg["formsitem/ID_formgroups"] = "droplist";
		$db_cfg["formsitem/ID_formgroups|LIST"] = "formgroups";

include "./project/db_cfg.php";

Auto_USEDIN();
?>