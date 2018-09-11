<?php
define("MAV_ERP", TRUE);

include "../config.php";
include "../includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$p_1 = $_GET['p1'];
$p_2 = $_GET['p2'];

dbquery("UPDATE okb_db_itrzadan SET PRIORZADAN='".$p_2."' where (ID='".$p_1."') ");
?>