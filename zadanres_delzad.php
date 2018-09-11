<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);


$arr_ids = explode("|",$_GET['id']);
$arr_opers = explode("|",$_GET['operitems']);
foreach($arr_ids as $key_1 => $val_1){
if ($val_1 !== ''){
	$zadanres_id = $arr_ids[$key_1];
	$operitems_id = $arr_opers[$key_1];

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (ID='".$zadanres_id."')");
	$xxxzad = mysql_fetch_array($result);

	$res1 = dbquery("SELECT * FROM okb_db_operitems where  (ID='".$operitems_id."')");
	$res1_1 = mysql_fetch_array($res1);
	$res1_2 = $res1_1['KSZ_NUM'];
	$res1_3 = $res1_2 - $xxxzad['NUM'];
	$res1_4 = $res1_1['KSZ2_NUM'];
	$res1_5 = $res1_4 - $xxxzad['NORM'];
	dbquery("Update okb_db_operitems Set KSZ_NUM:='".$res1_3."' where (ID = '".$operitems_id."')");
	dbquery("Update okb_db_operitems Set KSZ2_NUM:='".$res1_5."' where (ID = '".$operitems_id."')");

	$res1 = dbquery("SELECT * FROM okb_db_operitems where  (ID='".$operitems_id."')");
	$res1_1 = mysql_fetch_array($res1);
	$res1_2 = $res1_1['FACT2_NUM'];
	$res1_3 = $res1_2 + $xxxzad['NUM_FACT'];
	$res1_4 = $res1_1['FACT2_NORM'];
	$res1_5 = $res1_4 + $xxxzad['NORM_FACT'];
	dbquery("Update okb_db_operitems Set FACT2_NUM:='".$res1_3."' where (ID = '".$operitems_id."')");
	dbquery("Update okb_db_operitems Set FACT2_NORM:='".$res1_5."' where (ID = '".$operitems_id."')");

	dbquery("DELETE from okb_db_zadan where (ID='".$zadanres_id."') and (EDIT_STATE='0')");
}}
?>