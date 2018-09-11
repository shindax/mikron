<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$ids_itrs = $_GET['p1'];
$newdp_itrs = $_GET['p2'];
$zapr_id = $_GET['p3'];
$status = $_GET['p4'];

if ($ids_itrs !== ''){
if ($status == 1){
	
$ids_itrs_arr = explode("|", $ids_itrs);
$newdp_itrs_arr = explode("|", $newdp_itrs);

	foreach($ids_itrs_arr as $key_1 => $val_1){
		dbquery("UPDATE okb_db_itrzadan SET DATE_PLAN='".$newdp_itrs_arr[$key_1]."' WHERE ID='".$val_1."' ");
		dbquery("UPDATE okb_db_zapros_all SET STATUS='Выполнено' WHERE ID='".$zapr_id."' ");
	}
}
}
if ($status == 0){
	dbquery("UPDATE okb_db_zapros_all SET STATUS='Отклонено' WHERE ID='".$zapr_id."' ");
}
?>