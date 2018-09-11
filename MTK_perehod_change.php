<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$per_id = $_GET['id'];
$user_ID = $_GET['p1'];

dbquery("UPDATE okb_db_mtk_perehod SET ETIME='".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."' WHERE ID='".$per_id."'");
dbquery("UPDATE okb_db_mtk_perehod SET EUSER='".$user_ID."' WHERE ID='".$per_id."' ");
?>