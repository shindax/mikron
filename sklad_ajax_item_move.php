<?php

header('Content-type: text/plain; charset=windows-1251');

define('MAV_ERP', true);

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';	

include 'sklad_func.php';
	
switch ($_GET['show'])
{
	case 'sklad_items':
		$result = dbquery("SELECT NAME,ID FROM okb_db_sklades_item
								WHERE ID_sklad = " . (int) $_GET['ID_sklad'] . "
								ORDER BY ORD ASC");
	
		while ($row = mysql_fetch_assoc($result)){
			if (mysql_num_rows(dbquery("SELECT `ID` FROM `okb_db_sklades_yaruses` WHERE `ID_sklad_item` = " . $row['ID'])) > 0) {
				echo '<option value="' . $row['ID'] . '">' . htmlspecialchars($row['NAME']) . '</option>';
			}
		}
		break;
	case 'sklad_item_yaruses':
		$result = dbquery("SELECT ID,ORD FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . (int) $_GET['ID_sklad_item'] . " ORDER BY ORD ASC");

		while ($row = mysql_fetch_assoc($result)){
			echo '<option value="' . $row['ID'] . '">' . ($row['ORD'] == 0 ? iconv('utf-8', 'windows-1251', 'Пол') : $row['ORD']) . '</option>';
		}
		break;
}