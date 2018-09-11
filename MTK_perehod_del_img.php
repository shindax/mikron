<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$oper_id = $_GET['id'];
$name_img = $_GET['p1'];
$full_path = "project/63gu88s920hb045e/db_mtk_perehod@IMAGES/".$_GET['p1'];

$cur_tid = dbquery("SELECT * FROM okb_db_mtk_perehod_img WHERE ((ID_operitems='".$oper_id."') AND (IMG='".$name_img."')) ");
$nam_cur_t = mysql_fetch_array($cur_tid);

$cur_tid_w = dbquery("SELECT * FROM okb_db_mtk_perehod_img WHERE ((ID_operitems='".$oper_id."') AND (TID>'".$nam_cur_t['TID']."')) ");
while ($nam_cur_t_w = mysql_fetch_array($cur_tid_w)){
	dbquery("UPDATE okb_db_mtk_perehod_img SET TID='".($nam_cur_t_w['TID']-1)."' WHERE (ID='".$nam_cur_t_w['ID']."') ");
}

unlink($full_path);
dbquery("DELETE FROM okb_db_mtk_perehod_img WHERE ((ID_operitems='".$oper_id."') AND (IMG='".$name_img."')) ");
?>