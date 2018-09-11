<?php
	define("MAV_ERP", TRUE);
	
include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$as = 0;
$cur_us = $_GET['p1'];
$p_2 = $_GET['p2'];
$result5 = dbquery("SELECT * FROM okb_db_online_chat_curid_users ORDER BY IO");
while ($name5 = mysql_fetch_array($result5)){
	if ($name5['ID_us']==$cur_us) { $as = 1; dbquery("Update okb_db_online_chat_curid_users Set ID_us2='".$cur_us."' WHERE ID_us='".$cur_us."' ");}
	echo $name5['IO']."<br>", PHP_EOL;
	if ($p_2 == '1') { dbquery("Update okb_users Set MINI_CHAT:='".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."' where (ID='".$cur_us."')");}
}
if ($as == 0){
	$res_4 = dbquery("SELECT * FROM okb_users where (ID='".$cur_us."') ");
	$res_5 = mysql_fetch_array($res_4);
	if ($cur_us=='1') { $res_us = "Исаков А.В.";}else{ $res_us=$res_5['IO'];}
	dbquery("INSERT INTO okb_db_online_chat_curid_users (IO, ID_us, ID_us2) VALUES ('".$res_us."','".$res_5['ID']."','".$res_5['ID']."')");
	echo $res_us, PHP_EOL;
}
?>