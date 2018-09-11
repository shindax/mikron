<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$prot_id = $_GET['p2'];
$val = $_GET['value'];

$res_1 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$prot_id."') ");
$name_1 = mysql_fetch_array($res_1);

$arr_us = explode("|", $name_1['ID_users3']);
$new_us = "";
for ($c_f = 0; $c_f < (count($arr_us)-1); $c_f++){
	$new_us = $new_us.$val."|";
}

dbquery("UPDATE okb_db_protocols SET ID_users='".$val."' WHERE ID='".$prot_id."'");
dbquery("UPDATE okb_db_protocols SET ID_users3='".$new_us."' WHERE ID='".$prot_id."'");
?>