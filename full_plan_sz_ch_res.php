<?php
	define("MAV_ERP", TRUE);
	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$dat_sz = $_GET['p1'];
$smen_sz = $_GET['p2'];

$res_arr_f = array();
$res_0 = dbquery("SELECT ID_resurs FROM okb_db_zadanres where (DATE='".$dat_sz."') AND (SMEN='".$smen_sz."')");
while($res_1 = mysql_fetch_array($res_0)){
	$res_3 = dbquery("SELECT ID, NAME FROM okb_db_resurs where (ID='".$res_1['ID_resurs']."')");
	$res_4 = mysql_fetch_array($res_3);
	$res_arr_f[$res_4['ID']] = $res_4['NAME'];
}
asort($res_arr_f);
foreach ($res_arr_f as $key_1 => $val_1) {
    $sel_div_html = $sel_div_html."<option style='width:150px;' value='".$key_1."'>".$val_1."</option>";
}
echo $sel_div_html;
?>