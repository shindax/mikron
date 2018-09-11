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
	case 'remove':
		if (!$can_edit_sklad) return;
		
		foreach($_POST['ID_yaruses'] as $yarus) {
			if (mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . (int) $yarus)) == 0) {
				dbquery("DELETE FROM okb_db_sklades_yaruses WHERE ID = " . (int) $yarus);
			}
		}
		
		UpdateYarusORD($_POST['ID_box_item']);
		break;
}
