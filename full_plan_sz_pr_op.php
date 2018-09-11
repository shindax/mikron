<?php
	define("MAV_ERP", TRUE);
	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$id_res = $_GET['p3'];

$resu_2 = dbquery("SELECT * FROM okb_db_resurs WHERE (ID='".$id_res."') ");
$shtat_1 = mysql_fetch_array($resu_2);

$arr_exp_ids_res = explode("|", $shtat_1['OPER_IDS']);
$arr_pr_op = array();

foreach($arr_exp_ids_res as $key_6 => $val_6){
		$arr_pr_op[] = $val_6;
}

echo implode("|",$arr_pr_op);
?>