<?php
// $insert_id - ID нового элемента

		$resxxx5 = dbquery("SELECT MAX(ID) FROM okb_db_protocols where ID!='".$insert_id."'");
		$last5 = mysql_fetch_row($resxxx5);
		$num13 = $last5[0];
		
		$rse_1 = dbquery("SELECT * FROM okb_db_shtat where ((BOSS='1') and (ID_otdel='31'))");
		$na_1m = mysql_fetch_array($rse_1);
		
		$resxxx = dbquery("SELECT NUMBER FROM okb_db_protocols where (ID='".$num13."') ");
		$last = mysql_fetch_array($resxxx);
		$last33 = $last['NUMBER'];
		$last33 = explode("-",$last33);
		$last33 = $last33[1]*1;
		$last33 = $last33 + 1;
			
		if ($last33<10) $last33 = "0".$last33;
		if ($last33<100) $last33 = "0".$last33;
		if ($last33<1000) $last33 = "0".$last33;
		
		$ids_zak = "";
		$ids_us2 = "";
		$ids_us3 = "";
		$ids_txt = "";
		$ids_dp = "";
		$res_1 = dbquery("SELECT * FROM okb_db_zak where EDIT_STATE=0 ORDER BY ORD");
		while ($nam_1 = mysql_fetch_array($res_1)){
			$ids_zak = $ids_zak.$nam_1['ID']."|";
			$ids_us2 = $ids_us2."|";
			$ids_us3 = $ids_us3.$na_1m['NAME']."|";
			$ids_txt = $ids_txt."|";
			$ids_dp = $ids_dp."|";
		}

		$oboz2 = date("Ymd");
		dbquery("Update okb_db_protocols Set EDIT_STATE='0' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set NAME='План-факт № ".date("y")."-".$last33." от ".date("d.m.Y")."г.' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set DATA='".$oboz2."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set NUMBER='".date("y")."-".$last33."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set ID_zaks='".$ids_zak."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set ID_users='".$na_1m['NAME']."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set ID_users2='".$ids_us2."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set ID_users3='".$ids_us3."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set TXT='".$ids_txt."' where (ID='".$insert_id."')");
		dbquery("Update okb_db_protocols Set DATA_PLAN='".$ids_dp."' where (ID='".$insert_id."')");
?>