<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$user_rights = explode('|', $user['ID_rightgroups']);

$can_edit_otk = (bool) (in_array('70', $user_rights) || in_array('1', $user_rights));
$can_edit_sklad = (bool) (in_array('69', $user_rights) || in_array('1', $user_rights));
$is_admin = (bool) (in_array('1', $user_rights) || $user['ID'] == 182);

function hasItemsInBox($ID_item)
{
	return (bool) (mysql_num_rows(dbquery("SELECT 1 FROM okb_db_sklades_detitem sd
											LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID = sd.ID_sklades_yarus
											LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
												WHERE si.ID = " . $ID_item . " LIMIT 1")) != 0);
}

function hasFloor ($yarus_id)
{
	return (bool) mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . (int) $yarus_id . " AND ORD = 0")) > 0;
}

function UpdateYarusItemORD($yarus_id)
{
	$result = dbquery("SELECT ID FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . (int) $yarus_id . " ORDER BY ORD ASC");
	
	$i = 1;
	
	while ($row = mysql_fetch_assoc($result)) {
		dbquery("UPDATE okb_db_sklades_detitem SET ORD = " . $i . " WHERE ID = " . $row['ID']);

		++$i;
	}
}

function UpdateYarusORD($sklad_id)
{
	$result = dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . (int) $sklad_id . " AND ORD != 0 ORDER BY ORD ASC");
	
	$i = 1;
	
	while ($row = mysql_fetch_assoc($result)) {
		dbquery("UPDATE okb_db_sklades_yaruses SET ORD = " . $i . " WHERE ID = " . $row['ID'] . " AND ORD != 0");

		++$i;
	}
}

