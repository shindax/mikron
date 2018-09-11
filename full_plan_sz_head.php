<?php
	define("MAV_ERP", TRUE);
	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$id_res = $_GET['p1'];

$resu_2 = dbquery("SELECT NAME FROM okb_db_resurs WHERE (ID='".$id_res."') ");
$shtat_1 = mysql_fetch_array($resu_2);

$f_s_head = iconv("Windows-1251", "UTF-8", $shtat_1['NAME'])."<span>";
$resu_3 = dbquery("SELECT * FROM okb_db_shtat WHERE (ID_resurs='".$id_res."') ");
while ($shtat_2 = mysql_fetch_array($resu_3)){
	$resu_4 = dbquery("SELECT * FROM okb_db_special WHERE (ID='".$shtat_2['ID_special']."') ");
	$shtat_3 = mysql_fetch_array($resu_4);
	$resu_5 = dbquery("SELECT * FROM okb_db_speclvl WHERE (ID='".$shtat_2['ID_speclvl']."') ");
	$shtat_4 = mysql_fetch_array($resu_5);
	$f_s_head = $f_s_head."<br>".iconv("Windows-1251", "UTF-8", $shtat_3['NAME'])." ".iconv("Windows-1251", "UTF-8", $shtat_4['NAME']);
}
$f_s_head = $f_s_head."</span>";
echo $f_s_head;
?>