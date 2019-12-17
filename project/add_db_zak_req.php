<?php
// после успешного добавления идёт редирект на $pageurl
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }


$result = dbquery("SELECT * FROM ".$db_prefix."db_zak_req where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {

	// Подставляем новый номер ORD кратный 5

	$date = IntToDate($row["CDATE"]*1);
	$date = explode(".",$date);
	$ds = $date[1]*100+$date[2]*10000;
	$de = 32+$date[1]*100+$date[2]*10000;
	
	$name = "001".$date[1].".".$date[2];

	$res = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_zak_req where (CDATE > '".$ds."') and (CDATE < '".$de."') and (ID < '".$insert_id."') order by ID desc");
	if ($res = mysql_fetch_array($res)) {

		$xname = explode(".",$res["NAME"]);
		$xname = $xname[0]*1+1;
		$txt = $xname;
		if ($xname<100) $txt = "0".$txt;
		if ($xname<10) $txt = "0".$txt;
		$name = $txt.".".$date[1].".".$date[2];
	}

	dbquery("Update ".$db_prefix."db_zak_req Set NAME:='".$name."' where (ID='".$insert_id."')");

}

$result = mysql_fetch_assoc(dbquery("SELECT * FROM `okb_db_zak_req` WHERE `ID` = " . $insert_id . " LIMIT 1"));

$t = iconv('utf-8', 'windows-1251', 'Новая заявка на заказ');

dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 0, 39, NOW(), 0, 'Новая заявка на заказ', 'zakreq', 'comment' )");
dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 0, 88, NOW(), 0, 'Новая заявка на заказ', 'zakreq', 'comment' )");
dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 0, 145, NOW(), 0, 'Новая заявка на заказ', 'zakreq', 'comment' )");
dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 0, 4, NOW(), 0, 'Новая заявка на заказ', 'zakreq', 'comment' )");
dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 0, 216, NOW(), 0, 'Новая заявка на заказ', 'zakreq', 'comment' )");
dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 0, 206, NOW(), 0, 'Новая заявка на заказ', 'zakreq', 'comment' )");
// file_put_contents('1.txt', '123');
//dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", 1, 39, NOW(), 0, '" . $t . "', 'zakreq', 'comment' )");



?>