<?php
// $insert_id - ID нового элемента

	if ($pageurl == "index.php?do=show&formid=54") {
		$resxxx5 = dbquery("SELECT MAX(ID) FROM ".$db_prefix."db_edo_inout_files where (TIP_FAIL='0') ");
		$last5 = mysql_fetch_row($resxxx5);
		$num13 = $last5[0];
		
		$resxxx = dbquery("SELECT NAME_IN FROM ".$db_prefix."db_edo_inout_files where (ID='".$num13."') ");
		$last = mysql_fetch_array($resxxx);
		$last33 = $last['NAME_IN'];
		$last33 = explode("-",$last33);
		$last33 = $last33[1]*1;
		$last33 += 1;
			
		if ($last33<10) $last33 = "0".$last33;
		if ($last33<100) $last33 = "0".$last33;
		if ($last33<1000) $last33 = "0".$last33;
		
		$oboz2 = date("Ymd");
		//dbquery("Update ".$db_prefix."db_edo_inout_files_vrem Set DATA:='".$oboz2."' where (ID='".$insert_id."')");
		dbquery("Update ".$db_prefix."db_edo_inout_files_vrem Set NAME_IN:='".date("y")."-".$last33."' where (ID='".$insert_id."')");
		
		//dbquery("Update ".$db_prefix."db_edo_inout_files_vrem Set TIP_FAIL:='0' where (ID='".$insert_id."')");
		//$pageurl="index.php?do=show&formid=108&id=".$insert_id."&addnew=db_edo_vremitr&addf=ID_contacts&addv=".$insert_id;
		$pageurl="index.php?do=show&formid=108&id=".$insert_id;
	}
	if ($pageurl == "index.php?do=show&formid=55") {
		$resxxx5 = dbquery("SELECT MAX(ID) FROM ".$db_prefix."db_edo_inout_files where (TIP_FAIL='1') ");
		$last5 = mysql_fetch_row($resxxx5);
		$num13 = $last5[0];
		
		$resxxx = dbquery("SELECT NAME_IN FROM ".$db_prefix."db_edo_inout_files where (ID='".$num13."') ");
		$last = mysql_fetch_array($resxxx);
		$last33 = $last['NAME_IN'];
		$last33 = explode("-",$last33);
		$last33 = $last33[1]*1;
		$last33 += 1;
			
		if ($last33<10) $last33 = "0".$last33;
		if ($last33<100) $last33 = "0".$last33;
		if ($last33<1000) $last33 = "0".$last33;
		
		$oboz2 = date("Ymd");
		//dbquery("Update ".$db_prefix."db_edo_inout_files_vrem Set DATA:='".$oboz2."' where (ID='".$insert_id."')");
		dbquery("Update ".$db_prefix."db_edo_inout_files_vrem Set NAME_IN:='".date("y")."-".$last33."' where (ID='".$insert_id."')");
		
		//dbquery("Update ".$db_prefix."db_edo_inout_files_vrem Set TIP_FAIL:='1' where (ID='".$insert_id."')");
		//$pageurl="index.php?do=show&formid=109&id=".$insert_id."&addnew=db_edo_vremitr&addf=ID_contacts&addv=".$insert_id;
		$pageurl="index.php?do=show&formid=109&id=".$insert_id;
	}
?>