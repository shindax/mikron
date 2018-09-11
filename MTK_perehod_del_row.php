<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$per_id = $_GET['id'];

$cur_tid = dbquery("SELECT * FROM okb_db_mtk_perehod WHERE (ID='".$per_id."') ");
$nam_cur_t = mysql_fetch_array($cur_tid);

$cur_tid_w = dbquery("SELECT * FROM okb_db_mtk_perehod WHERE ((ID_operitems='".$nam_cur_t['ID_operitems']."') AND (TID>'".$nam_cur_t['TID']."')) ");
while ($nam_cur_t_w = mysql_fetch_array($cur_tid_w)){
	dbquery("UPDATE okb_db_mtk_perehod SET TID='".($nam_cur_t_w['TID']-1)."' WHERE (ID='".$nam_cur_t_w['ID']."') ");
}

dbquery("DELETE FROM okb_db_mtk_perehod WHERE (ID='".$per_id."') ");
?>