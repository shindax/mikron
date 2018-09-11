<?php
define("MAV_ERP", TRUE);

include "../config.php";
include "../includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$p_1 = $_GET['p1'];
$p_2 = $_GET['p2'];
		
dbquery("UPDATE okb_db_zadan SET ID_park='".$p_2."' where (ID='".$p_1."') ");
//dbquery("UPDATE okb_db_zadan SET STATUS='".$p_2."' where (ID='".$v_1."') ");

//echo $p_1." = ".iconv("Windows-1251", "UTF-8", $p_2);
//dbquery("INSERT INTO okb_db_online_chat_curid (NICK, WORDS, CHTIME, ID_users, ID_users2) VALUES ('".$res_5['IO']."','".$val."','".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$res_5['ID']."', '".$p_1."')");
?>