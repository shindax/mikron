<?php
// после успешного добавления идёт редирект на $pageurl
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }



$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {

	// Подставляем новый номер ORD кратный 5

	$res = dbquery("SELECT ID, ORD FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$row["ID_zakdet"]."') and (ID<'".$insert_id."') order by ORD desc");
	$ord = 1;
	if ($res = mysql_fetch_array($res)) $ord = $res["ORD"];
	$ord = 5*(floor($ord/5)+1);
	dbquery("Update ".$db_prefix."db_operitems Set ORD:='".$ord."' where (ID='".$insert_id."')");


	$ress = dbquery("SELECT ID, RCOUNT, ID_zak FROM ".$db_prefix."db_zakdet where (ID = '".$row["ID_zakdet"]."')");
	if ($res = mysql_fetch_array($ress)) { 
		$result_3 = dbquery("SELECT MAX(TID) FROM okb_db_mtk_perehod WHERE (ID_operitems='".$row['ID']."') ");
		$name_3 = mysql_fetch_row($result_3);
		dbquery("Update ".$db_prefix."db_operitems Set NUM_ZAK:='".$res["RCOUNT"]."' where (ID='".$insert_id."')");
		dbquery("Update ".$db_prefix."db_operitems Set ID_zak:='".$res["ID_zak"]."' where (ID='".$insert_id."')");
		dbquery("INSERT INTO okb_db_mtk_perehod (ETIME, EUSER, ID_zak, ID_zakdet, ID_operitems, TID) VALUES ('".$row['ETIME']."', '".$row['ID_user']."', '".$res['ID_zak']."','".$row['ID_zakdet']."','".$row['ID']."', '".($name_3[0]+1)."')");
	}	
	
}


?>