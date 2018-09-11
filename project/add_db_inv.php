<?php
// после успешного добавления идёт редирект на $pageurl
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }




$result = dbquery("SELECT * FROM ".$db_prefix."db_inv where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {
//////////////////////////////////////////////////////////////////

/*	if ($row["PID"]*1==0) {
	// Если не входящий
*/

		$num = 0;

		$resxxx = dbquery("SELECT ID, PREFIX FROM ".$db_prefix."db_inv_cat where (ID='".$row["ID_inv_cat"]."')");
		if ($cat = mysql_fetch_array($resxxx)) {
			$num = $cat["PREFIX"]*10000+1;
		}

		$resxxx = dbquery("SELECT ID, INV FROM ".$db_prefix."db_inv where (ID_inv_cat='".$row["ID_inv_cat"]."') and (ID<'".$insert_id."') order by ID desc");
		if ($last = mysql_fetch_array($resxxx)) {
			$numx = $last["INV"]*1+1;
			if ($numx>$num) $num = $numx;
		}

		if ($num>0) {
			$numtxt = $num;
			if ($num<10000000) $numtxt = "0".$numtxt;
			dbquery("Update ".$db_prefix."db_inv Set INV:='".$numtxt."' where (ID='".$insert_id."')");
		}
/* отключено в связи с тем, что было принято решение о сквозной нумерации в инвентаризации, ну и работало криво (добавив где-до) дочерний
	} else {
	// Если входящий

		$resxxx = dbquery("SELECT ID, INV FROM ".$db_prefix."db_inv where (ID='".$row["PID"]."')");
		if ($parent = mysql_fetch_array($resxxx)) {

			$numtxt = "01";

			$resxxx = dbquery("SELECT ID, INV FROM ".$db_prefix."db_inv where (PID='".$row["PID"]."') and (ID<'".$insert_id."') order by ID desc");
			if ($last = mysql_fetch_array($resxxx)) {
				$num = $last["INV"];
				$num = explode("-",$num);
				$num = $num[1]*1;
				$num = $num + 1;
				$numtxt = $num;
				if ($num<10) $numtxt = "0".$numtxt;
			}

			dbquery("Update ".$db_prefix."db_inv Set INV:='".$parent["INV"]."-".$numtxt."' where (ID='".$insert_id."')");
		}

	}*/

//////////////////////////////////////////////////////////////////
}


?>