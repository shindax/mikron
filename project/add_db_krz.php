<?php
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }


		function CopyIzdOperitems($from_id, $to_id) {
			global $db_prefix;

			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdetitems where (ID_krzdet = '".$from_id."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_krzdetitems (NAME, ID_krzdet, TID, PRICE, COUNT) VALUES ('".$res["NAME"]."', '".$to_id."', '".$res["TID"]."', '".$res["PRICE"]."', '".$res["COUNT"]."')");
			}
		}

		function CopyIzdIzd($from_id, $to_id, $id_krz) {
			global $db_prefix, $ID_zak, $NEW_ID_array;

			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where (PID = '".$from_id."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_krzdet (PID, NAME, ID_krz, OBOZ, COUNT, D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, VES) VALUES ('".$to_id."', '".$res["NAME"]."', '".$id_krz."', '".$res["OBOZ"]."', '".$res["COUNT"]."', '".$res["D1"]."', '".$res["D2"]."', '".$res["D3"]."', '".$res["D4"]."', '".$res["D5"]."', '".$res["D6"]."', '".$res["D7"]."', '".$res["D8"]."', '".$res["D9"]."', '".$res["D10"]."', '".$res["D11"]."', '".$res["VES"]."')");
				$newid = mysql_insert_id();
				CopyIzdOperitems($res["ID"], $newid);
				CopyIzdIzd($res["ID"], $newid, $id_krz);
			}
		}












$result = dbquery("SELECT ID, DATE_START FROM ".$db_prefix."db_krz where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {

	$numtxt = "001";


	$ddd = IntToDate($row["DATE_START"]);
	$ddd = explode(".",$ddd);
	$YY = $ddd[2]*1;
	$MM = $ddd[1]*1;
	$sdate = $YY*10000+$MM*100;
	$edate = $YY*10000+$MM*100+32;
	$result = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_krz where (DATE_START>'".$sdate."') and (DATE_START<'".$edate."') and (ID<'".$insert_id."') order by ID desc");
	if ($last = mysql_fetch_array($result)) {
		$num = $last["NAME"];
		$num = explode(".",$num);
		$num = $num[0]*1;
		$num = $num + 1;
		$numtxt = $num;
		if ($num<10) $numtxt = "0".$numtxt;
		if ($num<100) $numtxt = "0".$numtxt;
	}
	$MMtxt = $MM;
	if ($MM<10) $MMtxt = "0".$MMtxt;
	$oboz = $numtxt.".".$MMtxt.".".($YY-2000);
	dbquery("Update ".$db_prefix."db_krz Set NAME:='".$oboz."' where (ID='".$insert_id."')");
	dbquery("Update ".$db_prefix."db_krz Set NORM_PRICE:='1000' where (ID='".$insert_id."')");


	if (isset($_GET["docopy_id_krz"])) {
		$cid = $_GET["docopy_id_krz"];
		$result = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_krz where (ID='".$cid."') ");
		if ($copy = mysql_fetch_array($result)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where (PID='0') and (ID_krz='".$cid."') ");
			if ($copyIzd = mysql_fetch_array($result)) {
				$result = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where (PID='0') and (ID_krz='".$insert_id."') ");
				if ($toIzd = mysql_fetch_array($result)) {

					dbquery("Update ".$db_prefix."db_krzdet Set NAME:='".$copyIzd["NAME"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set OBOZ:='".$copyIzd["OBOZ"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set COUNT:='".$copyIzd["COUNT"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D1:='".$copyIzd["D1"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D2:='".$copyIzd["D2"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D3:='".$copyIzd["D3"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D4:='".$copyIzd["D4"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D5:='".$copyIzd["D5"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D6:='".$copyIzd["D6"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D7:='".$copyIzd["D7"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D8:='".$copyIzd["D8"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D9:='".$copyIzd["D9"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D10:='".$copyIzd["D10"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set D11:='".$copyIzd["D11"]."' where (ID='".$toIzd["ID"]."')");
					dbquery("Update ".$db_prefix."db_krzdet Set VES:='".$copyIzd["VES"]."' where (ID='".$toIzd["ID"]."')");

					CopyIzdOperitems($copyIzd["ID"], $toIzd["ID"]);
					CopyIzdIzd($copyIzd["ID"], $toIzd["ID"], $insert_id);
				}
			}
		}
	}
}


?>