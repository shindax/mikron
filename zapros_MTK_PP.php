<?php
	define("MAV_ERP", TRUE);
	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "includes/cookie.php";
	include "includes/config.php";

$id_operitems = $_GET['p1'];
$id_dse = $_GET['p2'];
$id_zak = $_GET['p3'];

$res_0 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$res_0 = mysql_fetch_array($res_0);

$res_1 = dbquery("SELECT * FROM okb_db_operitems where (ID='".$id_operitems."') ");
$res_1 = mysql_fetch_array($res_1);

$res_1_1 = dbquery("SELECT * FROM okb_db_oper where (ID='".$res_1['ID_oper']."') ");
$res_1_1 = mysql_fetch_array($res_1_1);

$res_2 = dbquery("SELECT * FROM okb_db_zakdet where (ID='".$id_dse."') ");
$res_2 = mysql_fetch_array($res_2);

$res_3 = dbquery("SELECT * FROM okb_db_zak where (ID='".$id_zak."') ");
$res_3 = mysql_fetch_array($res_3);

$tip_oper = array(" ","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");
$tip_zak = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
$dat_plan = date("Y").date("m").date("d")+2;

$res_5 = dbquery("SELECT * FROM okb_db_shtat where (BOSS='1') AND (ID_otdel='7') ");
$res_5 = mysql_fetch_array($res_5);

$id_komu_zapros_pp = $res_5['ID_resurs']; //ID ресурса
#$id_komu_zapros_pp = 710; // Полюшкевич А. Н. 16.02.2018
#$id_komu_zapros_pp = 293; // Бормотов В.А. 18.01.2019
$id_komu_zapros_pp = 1027; // Ли М. В.
$txt_zapr = "Заказ: ".$tip_zak[$res_3['TID']]." | ".$res_3['NAME']."<br>ДСЕ: ".$res_2['NAME']."<br>Чертёж: ".$res_2['OBOZ']."<br>Операция: № МТК: ".$res_1['ORD']."<br>".$res_1_1['NAME']." - ".$tip_oper[$res_1_1['TID']]."<br>".$res_1['MSG_INFO'];

dbquery("INSERT INTO okb_db_zapros_all (DATE_PLAN, ID_users, ID_users3, CDATE, CTIME, TIME_PLAN, STATUS, SOGL, TIT_HEAD, ID_users2_plan, ID_itrzadan, TXT) VALUES ('".$dat_plan."', '".$res_0['ID']."', '0', '".date("Ymd")."', '".date("H:i:s")."', '17:00:00', 'Отправлен', '0', '0', '".$id_komu_zapros_pp."', '".$id_operitems."', '".$txt_zapr."')");
?>