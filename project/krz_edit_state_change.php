<?php

define('MAV_ERP', true);

$value = mysql_result(dbquery("SELECT EDIT_STATE FROM okb_db_krz WHERE ID = " . $change_id), 0);

if ($value == 1) {
	dbquery("UPDATE okb_db_krz SET EDIT_STATE_DATE = NOW() WHERE ID = " . $change_id);
}

?>