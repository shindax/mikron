<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	include "project/calc_zak.php";
$_GET['id'] = str_replace('_', '', $_GET['id']);
$_GET['operitems'] = str_replace('_', '', $_GET['operitems']);

$arr_ids = explode("|",$_GET['id']); 
$arr_opers = explode("|",$_GET['operitems']);
foreach($arr_ids as $key_1 => $val_1){
if ($val_1 !== ''){
	$zadanres_id = $arr_ids[$key_1];
	$operitems_id = $arr_opers[$key_1];

	$db_prefix = "okb_";
	
	$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (ID='".$zadanres_id."')");
	$xxxzad = mysql_fetch_array($result);

	dbquery("Update ".$db_prefix."db_zadan Set EDIT_STATE:='0' where (ID='".$zadanres_id."')");
	dbquery("Update ".$db_prefix."db_zadan Set MULT_SEL:='0' where (ID='".$zadanres_id."')");

	$res1 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where  (ID='".$xxxzad['ID_operitems']."')");
	$res1_1 = mysql_fetch_array($res1);
	$res1_2 = $res1_1['KSZ_NUM'];
	$res1_3 = $res1_2 + $xxxzad['NUM'];
	$res1_4 = $res1_1['KSZ2_NUM'];
	$res1_5 = $res1_4 + $xxxzad['NORM'];
	dbquery("Update ".$db_prefix."db_operitems Set KSZ_NUM:='".$res1_3."' where (ID = '".$xxxzad['ID_operitems']."')");
	dbquery("Update ".$db_prefix."db_operitems Set KSZ2_NUM:='".$res1_5."' where (ID = '".$xxxzad['ID_operitems']."')");

	$res1 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where  (ID='".$xxxzad['ID_operitems']."')");
	$res1_1 = mysql_fetch_array($res1);
	$res1_2 = $res1_1['FACT2_NUM'];
	$res1_3 = $res1_2 - $xxxzad['NUM_FACT'];
	$res1_4 = $res1_1['FACT2_NORM'];
	$res1_5 = $res1_4 - $xxxzad['NORM_FACT'];
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM:='".$res1_3."' where (ID = '".$xxxzad['ID_operitems']."')");
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM:='".$res1_5."' where (ID = '".$xxxzad['ID_operitems']."')");

		   // пересчитали заказ
	CalculateOperitem($xxxzad["ID_operitems"]);
}}

?>