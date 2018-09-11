<?php

header('Content-type: text/plain; charset=windows-1251');

define('MAV_ERP', true);

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';

include 'sklad_func.php';
	
switch($_GET['mode'])
{
	case 'add_edit':
		if (!$can_edit_sklad) return;
	
		$_POST['NAME'] = iconv('utf-8', 'windows-1251', $_POST['NAME']);
		$_POST['KOMM'] = iconv('utf-8', 'windows-1251', $_POST['KOMM']);
		
		if (empty($_POST['ID_yarus_item'])) {
			dbquery("INSERT INTO okb_db_sklades_detitem (ID_sklades_yarus, NAME, KOMM, COUNT, ORD)
						SELECT " . (int) $_POST['ID_yarus'] . ",
						'" . mysql_real_escape_string($_POST['NAME']) . "',
						'" . mysql_real_escape_string($_POST['KOMM']) . "',
						" . (int) $_POST['COUNT'] . ",
						IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades_detitem
							WHERE ID_sklades_yarus = " . (int) $_POST['ID_yarus']);
		} else {
			dbquery("UPDATE okb_db_sklades_detitem SET NAME = '" . mysql_real_escape_string($_POST['NAME']) . "',
														KOMM = '" . mysql_real_escape_string($_POST['KOMM']) . "',
														COUNT = " . (int) $_POST['COUNT'] . "
															WHERE ID = " . (int) $_POST['ID_yarus_item'] . " AND OTK_STATUS != 1");
		}
					
		break;
	case 'move':
		if (!$can_edit_sklad) return;
		
		foreach ($_POST['ID_yarus_items'] as $yarus_item) {
			$ord = mysql_result(dbquery("SELECT MAX(ORD) FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . (int) $_POST['ID_yarus']), 0);
			
			dbquery("UPDATE okb_db_sklades_detitem SET ID_sklades_yarus = " . (int) $_POST['ID_yarus'] . ",
														ORD = " . ($ord + 1) . "
						WHERE ID = " . (int) $yarus_item . " AND OTK_STATUS != 1");
		}
		
		UpdateYarusItemORD($_POST['ID_yarus']);
		UpdateYarusItemORD($_POST['ID_yarus_from']);
		break;
	case 'remove':
		if (!$can_edit_sklad) return;
		
		foreach($_POST['ID_yarus_items'] as $yarus_item) 
		{
			dbquery("DELETE FROM okb_db_sklades_detitem WHERE ID = " . (int) $yarus_item . " AND OTK_STATUS != 1");
			
			dbquery("UPDATE okb_db_semifinished_store_invoices SET warehouse_item_id = 0 
			 WHERE warehouse_item_id = " . (int) $yarus_item );			
		}
		
		UpdateYarusItemORD($_POST['ID_yarus']);
		break;
	case 'otk_confirm':
		if (!$can_edit_otk) return;
		
		foreach($_POST['ID_yarus_items'] as $yarus_item) {
			dbquery("UPDATE okb_db_sklades_detitem SET OTK_STATUS = 1 WHERE ID = " . (int) $yarus_item);
		}
		break;
	case 'otk_confirm_remove':
		if (!$can_edit_otk) return;
	
		foreach($_POST['ID_yarus_items'] as $yarus_item) {
			dbquery("UPDATE okb_db_sklades_detitem SET OTK_STATUS = 0 WHERE ID = " . (int) $yarus_item);
		}
		break;
	case 'search_item':
		if (strlen($_GET['text']) < 3 || !$can_edit_sklad) return;

		$_GET['text'] = iconv('utf-8', 'windows-1251', urldecode($_GET['text']));
		
		$result = dbquery("SELECT NAME,OBOZ FROM okb_db_zakdet WHERE NAME LIKE '%" . $_GET['text'] . "%' OR OBOZ LIKE '%" . $_GET['text'] . "%'");
		
		while ($row = mysql_fetch_assoc($result)) {
			$name = htmlspecialchars($row['NAME']);
			$oboz = htmlspecialchars($row['OBOZ']);

			echo '<option value="' . $name . '  -  ' . $oboz . '">' . substr($name, 0, 20) . ' - ' .  substr($oboz, 0, 20) . '</option>';
		}
		break;
	case 'search_sklad':

		if (strlen($_GET['text']) < 3) return;

		$_GET['text'] = iconv('utf-8', 'windows-1251', urldecode($_GET['text']));
		
		$result = dbquery("SELECT sd.NAME,sd.KOMM,sy.ORD as Yarus,si.NAME as BoxName,s.ID as SkladName FROM okb_db_sklades_detitem sd
											LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID = sd.ID_sklades_yarus
											LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
											LEFT JOIN okb_db_sklades s ON s.ID = si.ID_sklad
								WHERE sd.NAME LIKE '%" . $_GET['text'] . "%' OR sd.KOMM LIKE '%" . $_GET['text'] . "%'");
		
		while ($row = mysql_fetch_assoc($result)) {
			$name = htmlspecialchars($row['NAME']);
			$oboz = htmlspecialchars($row['KOMM']);

			echo '<option value="' . $name . '  -  ' . $oboz . '">' . iconv('utf-8', 'windows-1251', 'Склад') . ': ' . $row['SkladName'] . ' (' . $row['BoxName'] . ' - ' . ($row['Yarus'] == 0 ? iconv('utf-8', 'windows-1251', 'Пол') : $row['Yarus']) . ') ' . substr($name, 0, 20) . ' - ' .  substr($oboz, 0, 20) . '</option>';
		}
		break;
}
