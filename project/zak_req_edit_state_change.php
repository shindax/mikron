<?php

define('MAV_ERP', true);

$value = mysql_result(dbquery("SELECT STATE FROM okb_db_zak_req WHERE ID = " . $change_id), 0);

if ($value == 1) {
	dbquery("UPDATE okb_db_request_events SET request_status = 1 WHERE request_pid = " . $change_id . " AND request_type = 'zakreq'");
}

?>