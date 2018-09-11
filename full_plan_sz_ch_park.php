<?php
	define("MAV_ERP", TRUE);
	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
dbquery('SET NAMES cp1251');
$id_res = $_GET['p1'];

$res_0 = dbquery("SELECT PARK_IDS FROM okb_db_resurs where (ID='".$id_res."')");
$res_1 = mysql_fetch_array($res_0);
$res_2 = explode("|",$res_1['PARK_IDS']);

foreach ($res_2 as $key_1 => $val_1) {
	if($val_1!==""){
		$res_3 = dbquery("SELECT ID, NAME, MARK FROM okb_db_park where (ID='".$val_1."')");
		$res_4 = mysql_fetch_array($res_3);
		$sel_div_html = $sel_div_html."<option style='width:150px;' value='".$res_4['ID']."'>".$res_4['NAME']." - ".$res_4['MARK']."</option>";
	}
}
echo $sel_div_html;
?>