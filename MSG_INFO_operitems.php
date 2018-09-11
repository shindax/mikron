<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$per_id = $_GET['id'];
$per_val = $_GET['value'];

dbquery("UPDATE okb_db_operitems SET MSG_INFO='".$per_val."' WHERE ID='".$per_id."'");
?>