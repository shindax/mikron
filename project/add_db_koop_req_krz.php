<?php
// после успешного добавления идёт редирект на $pageurl
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }



$result = dbquery("SELECT * FROM ".$db_prefix."db_koop_req_krz where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {

	// Подставляем новый номер ORD кратный 5

	$date = IntToDate($row["CDATE"]*1);
	$date = explode(".",$date);
	$ds = $date[1]*100+$date[2]*10000;
	$de = 32+$date[1]*100+$date[2]*10000;
	
	$name = "001".$date[1].".".$date[2];

	$res = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_koop_req_krz where (CDATE > '".$ds."') and (CDATE < '".$de."') and (ID < '".$insert_id."') order by ID desc");
	if ($res = mysql_fetch_array($res)) {

		$xname = explode(".",$res["NAME"]);
		$xname = $xname[0]*1+1;
		$txt = $xname;
		if ($xname<100) $txt = "0".$txt;
		if ($xname<10) $txt = "0".$txt;
		$name = $txt.".".$date[1].".".$date[2];
	}

	dbquery("Update ".$db_prefix."db_koop_req_krz Set NAME:='".$name."' where (ID='".$insert_id."')");

}


?>