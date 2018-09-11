<?php
define("MAV_ERP", TRUE);

include "../config.php";
include "../includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$p_1 = explode("|", $_GET['p1']);
$p_2 = $_GET['p2'];
$p_3 = $_GET['p3'];

foreach($p_1 as $k_1 => $v_1){
if (($v_1 !== "") and ($v_1 !=="0")){
	$rt5 = dbquery("SELECT ID_zapr FROM okb_db_itrzadan where ID='".$v_1."' ");
	$ne5 = mysql_fetch_array($rt5);
	if ($ne5['ID_zapr']!=='0') {
		dbquery("UPDATE okb_db_zapros_all SET STATUS='Выполнено' where (ID='".$ne5['ID_zapr']."') ");
		dbquery("UPDATE okb_db_zapros_all SET DATE_FACT='".date("Ymd")."' where (ID='".$ne5['ID_zapr']."') ");
		dbquery("UPDATE okb_db_zapros_all SET TIME_FACT='".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."' where (ID='".$ne5['ID_zapr']."') ");
	}
		
	$rt7 = dbquery("SELECT ID FROM okb_db_resurs where NAME='".$p_3."' AND TID=0");
	$ne7 = mysql_fetch_array($rt7);
	dbquery("UPDATE okb_db_itrzadan SET STATUS='".$p_2."' where (ID='".$v_1."') ");
	dbquery("INSERT INTO okb_db_itrzadan_statuses (DATA, TIME, STATUS, USER, ID_edo) VALUES ('".date("Ymd")."','".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$p_2."', '".$ne7['ID']."', '".$v_1."')");
}}

//echo $p_1." = ".iconv("Windows-1251", "UTF-8", $p_2);
//dbquery("INSERT INTO okb_db_online_chat_curid (NICK, WORDS, CHTIME, ID_users, ID_users2) VALUES ('".$res_5['IO']."','".$val."','".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$res_5['ID']."', '".$p_1."')");
?>