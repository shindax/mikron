<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$prot_id = $_GET['p2'];
$key_dat = $_GET['p1'];
$val = $_GET['value'];

$res_1 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$prot_id."') ");
$name_1 = mysql_fetch_array($res_1);

$arr_dp = explode("|", $name_1['TXT']);
$arr_dp[$key_dat] = $val;

$new_arr = "";
for ($c_f = 0; $c_f < (count($arr_dp)-1); $c_f++){
	$new_arr = $new_arr.$arr_dp[$c_f]."|";
}

dbquery("UPDATE okb_db_protocols SET TXT='".$new_arr."' WHERE ID='".$prot_id."'");
?>