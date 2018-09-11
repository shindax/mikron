<?php
// после успешного добавления идёт редирект на $pageurl
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }




$result = dbquery("SELECT * FROM ".$db_prefix."db_inv_cat where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {
//////////////////////////////////////////////////////////////////

	if ($row["PID"]*1==0) {
	// Если не входящий

		$numtxt = "0100";

		$resxxx = dbquery("SELECT ID, PREFIX FROM ".$db_prefix."db_inv_cat where (PID='0') and (ID<'".$insert_id."') order by ID desc");
		if ($last = mysql_fetch_array($resxxx)) {
			$num = $last["PREFIX"]*1+100;
			$numtxt = $num;
			if ($num<1000) $numtxt = "0".$numtxt;
		}
		dbquery("Update ".$db_prefix."db_inv_cat Set PREFIX:='".$numtxt."' where (ID='".$insert_id."')");

	} else {
	// Если входящий

		$resxxx = dbquery("SELECT ID, PREFIX FROM ".$db_prefix."db_inv_cat where (ID='".$row["PID"]."')");
		if ($parent = mysql_fetch_array($resxxx)) {

			$numtxt = $parent["PREFIX"]*1+1;
			if ($numtxt<1000) $numtxt = "0".$numtxt;

			$resxxx = dbquery("SELECT ID, PREFIX FROM ".$db_prefix."db_inv_cat where (PID='".$row["PID"]."') and (ID<'".$insert_id."') order by ID desc");
			if ($last = mysql_fetch_array($resxxx)) {
				$num = $last["PREFIX"]*1+1;
				$numtxt = $num;
				if ($num<1000) $numtxt = "0".$numtxt;
			}
			dbquery("Update ".$db_prefix."db_inv_cat Set PREFIX:='".$numtxt."' where (ID='".$insert_id."')");
		}

	}

//////////////////////////////////////////////////////////////////
}


?>