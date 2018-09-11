<?php
// ЗАПРЕТ КЭШИРОВАНИЯ

	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
	Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
	Header("Pragma: no-cache"); // HTTP/1.1 
	Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");


// ПОЕХАЛИ

	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "../../includes/cookie.php";


// ПРАВА НА ДОБАВЛЕНИЕ / УДАЛЕНИЕ
	function db_adcheck($db) {
		global $db_cfg, $user, $user_rights, $print_mode;

		$res = false;
		if (is_array($user_rights)) {
			if (in_array("superadmin",$user_rights)) $res = true;
			if (in_array($db."|superadmin",$user_rights)) $res = true;
			if (in_array($db."|add",$user_rights)) $res = true;
		}

	   // СПЕЦ ТАБЛИЦЫ
		if (($user["USERSEDIT"]=="1") && ($db=="users")) $res = true;
		if (($user["ID"]=="1") && ($db=="rightgroups")) $res = true;
		if (($user["ID"]=="1") && ($db=="viewgroups")) $res = true;
		if (($user["ID"]=="1") && ($db=="formgroups")) $res = true;
		if (($user["ID"]=="1") && ($db=="forms")) $res = true;
		if (($user["ID"]=="1") && ($db=="formsitem")) $res = true;

		if ($print_mode=="on") $res = false;

		return $res;
	}








	$ddd = $_GET['date'];
	$ID_tab_pc = $_GET['ID_tab_pc'];
	$val = $_GET['value']*1;

$edit_right = db_adcheck("db_tab_pci");
if ($edit_right) {

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   if (!isset($_GET["tid"])) {

	$tab_pci = dbquery("SELECT * FROM ".$db_prefix."db_tab_pci where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
	// Смотрим есть ли запись

	if ($tab_pci = mysql_fetch_array($tab_pci)) {
	// Если есть

		if ($val>0) {
		// Изменить
			dbquery("Update ".$db_prefix."db_tab_pci Set HOURS:='$val' where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
			if ($tab_pci["TID"]*1==1) dbquery("Update ".$db_prefix."db_tab_pci Set TID:='0' where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
		} else {
		// Удалить
			dbquery("DELETE from ".$db_prefix."db_tab_pci where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
		}

	} else {
	// Если нет

		if ($val>0) {
		// Создать
			dbquery("INSERT INTO ".$db_prefix."db_tab_pci (DATE, ID_tab_pc, HOURS, TID) VALUES ('".$ddd."', '".$ID_tab_pc."', '".$val."', '0')");
		}

	}
   }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   if (isset($_GET["tid"])) {

	$tab_pci = dbquery("SELECT * FROM ".$db_prefix."db_tab_pci where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
	// Смотрим есть ли запись

	if ($tab_pci = mysql_fetch_array($tab_pci)) {
	// Если есть

		if ($val==0) {
			if ($tab_pci["HOURS"]*1==0) {
			// Удалить
				dbquery("DELETE from ".$db_prefix."db_tab_pci where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
			} else {
			// Изменить
				dbquery("Update ".$db_prefix."db_tab_pci Set TID:='0' where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");

			}
		}
		if ($val==1) {
			// Изменить
				dbquery("Update ".$db_prefix."db_tab_pci Set HOURS:='0' where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
				dbquery("Update ".$db_prefix."db_tab_pci Set TID:='1' where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
		}
		if ($val==2) {
			// Изменить
				dbquery("Update ".$db_prefix."db_tab_pci Set TID:='2' where (ID_tab_pc='$ID_tab_pc') and (DATE='$ddd')");
		}

	} else {
	// Если нет

		if ($val>0) {
		// Создать
			dbquery("INSERT INTO ".$db_prefix."db_tab_pci (DATE, ID_tab_pc, HOURS, TID) VALUES ('".$ddd."', '".$ID_tab_pc."', '0', '".$val."')");
		}

	}
   }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}


// ЛОГ
if (!$edit_right) echo "<b>Доступ закрыт</b><br>";
echo "date = $ddd<br>";
echo "ID_tab_pc = $ID_tab_pc<br>";
echo "Value = \"".$val."\"<br>";
?>