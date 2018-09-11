<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$cur_us = $_GET['user'];
$val = $_GET['value'];
$p_1 = $_GET['p1'];

$res_4 = dbquery("SELECT * FROM okb_users where (ID='".$cur_us."') ");
$res_5 = mysql_fetch_array($res_4);

dbquery("INSERT INTO okb_db_online_chat_curid (NICK, WORDS, CHTIME, ID_users, ID_users2) VALUES ('".$res_5['IO']."','".$val."','".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$res_5['ID']."', '".$p_1."')");
?>