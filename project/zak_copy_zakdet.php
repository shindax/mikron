<?php


	if ((isset($_POST['copy_dse_from_zakdet'])) && (isset($_POST['copy_dse_to_zakdet']))) {

		$NEW_ID_array = array();

		function CopyIzdOperitems($from_zakdet_ID,$to_zakdet_ID, $us_id) {
			global $db_prefix;

			// Копируем МТК
			dbquery("DELETE from okb_db_operitems where (ID_zakdet='".$to_zakdet_ID."')");
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				$xxx4 = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$to_zakdet_ID."') order by ID");
				$res4 = mysql_fetch_array($xxx4);
				dbquery("INSERT INTO ".$db_prefix."db_operitems (ETIME, ID_user, ID_zak, ID_zakdet, ORD, ID_oper, ID_park, NORM, NORM_2, NORM_ZAK, MORE) VALUES ('".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$us_id."', '".$res4["ID_zak"]."', '".$to_zakdet_ID."', '".$res["ORD"]."', '".$res["ID_oper"]."', '".$res["ID_park"]."', '".$res["NORM"]."', '".$res["NORM_2"]."', '".$res["NORM_ZAK"]."', '".$res["MORE"]."')");
				$ins_msql_id = mysql_insert_id();
				$xxx5 = dbquery("SELECT * FROM okb_db_mtk_perehod where (ID_operitems = '".$res['ID']."') order by TID");
				while($res5 = mysql_fetch_array($xxx5)){
					dbquery("INSERT INTO okb_db_mtk_perehod (ETIME, EUSER, ID_zak, ID_zakdet, ID_operitems, TXT, INSTR_1, INSTR_2, INSTR_3, DIAM_SHIR, DLINA, R_O_S, R_O_N, R_O_V, R_O_TO, R_O_TP, TID) VALUES ('".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$us_id."', '".$res4['ID_zak']."','".$res4['ID']."','".$ins_msql_id."', '".$res5["TXT"]."', '".$res5['INSTR_1']."', '".$res5['INSTR_2']."', '".$res5['INSTR_3']."', '".$res5['DIAM_SHIR']."', '".$res5['DLINA']."', '".$res5['R_O_S']."', '".$res5['R_O_N']."', '".$res5['R_O_V']."', '".$res5['R_O_TO']."', '".$res5['R_O_TP']."', '".$res5['TID']."')");				
				}
			}

			// Копируем НР
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zn_zag where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_zn_zag (ID_zakdet, ID_mat, ID_sort, WW, HH, LL, RCOEF, KDZ, MORE, NORM, NORMZAK, RCOUNT, ID_user, ETIME) VALUES ('".$to_zakdet_ID."', '".$res["ID_mat"]."', '".$res["ID_sort"]."', '".$res["WW"]."', '".$res["HH"]."', '".$res["LL"]."', '".$res["RCOEF"]."', '".$res["KDZ"]."', '".$res["MORE"]."', '".$res["NORM"]."', '".$res["NORM_ZAK"]."', '".$res["RCOUNT"]."', '".$res["ID_user"]."', '".$res["ETIME"]."')");
			}
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zn_pok where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_zn_pok (ID_zakdet, ID_mat, WW, HH, LL, KDZ, MORE, NORM, NORMZAK, RCOUNT, ID_user, ETIME) VALUES ('".$to_zakdet_ID."', '".$res["ID_mat"]."', '".$res["WW"]."', '".$res["HH"]."', '".$res["LL"]."', '".$res["KDZ"]."', '".$res["MORE"]."', '".$res["NORM"]."', '".$res["NORM_ZAK"]."', '".$res["RCOUNT"]."', '".$res["ID_user"]."', '".$res["ETIME"]."')");
			}

		}

		function CopyIzdIzd($from_zakdet_ID,$to_zakdet_ID) {
			global $db_prefix, $to_zakdet, $NEW_ID_array;

			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID = '".$from_zakdet_ID."') order by ORD");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_zakdet (ID_zak, PID, NAME, ORD, OBOZ, COUNT, RCOUNT, TID, LID, MTK_OK, NORM_OK) VALUES ('".$to_zakdet["ID_zak"]."', '".$to_zakdet_ID."', '".$res["NAME"]."', '".$res["ORD"]."', '".$res["OBOZ"]."', '".$res["COUNT"]."', '".$res["RCOUNT"]."', '".$res["TID"]."', '".$res["LID"]."', '".$res["MTK_OK"]."', '".$res["NORM_OK"]."')");
				$new_zakdet_ID = mysql_insert_id();
				CopyIzdOperitems($res["ID"], $new_zakdet_ID);
				CopyIzdIzd($res["ID"], $new_zakdet_ID);
				$NEW_ID_array[$res["ID"]] = $new_zakdet_ID;
			}
		}

		function SetNewLID($dse_ID) {
			global $db_prefix, $NEW_ID_array;

			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID = '".$dse_ID."') order by ORD");
			while($res = mysql_fetch_array($xxx)) {
				if ($res["LID"]!=="0") dbquery("Update ".$db_prefix."db_zakdet Set LID:='".$NEW_ID_array[$res["LID"]]."' where (ID='".$res["ID"]."')");
				SetNewLID($res["ID"]);
			}
		}

		$from_id = $_POST['copy_dse_from_zakdet'];	// Откуда
		$to_id = $_POST['copy_dse_to_zakdet'];		// Куда
		if (db_adcheck("db_zakdet")) {
			// ПОЕХАЛИ
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID = '".$to_id."')");
			if ($to_zakdet = mysql_fetch_array($xxx)) {
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID = '".$from_id."')");
			if ($from_zakdet = mysql_fetch_array($xxx)) {

				// Копируем МТК основной сборки ////////////////////////////

				CopyIzdOperitems($from_id, $to_id, $user['ID']);

				// Копируем МТК_OK, NAME, OBOZ основной сборки /////////////

				dbquery("Update ".$db_prefix."db_zakdet Set MTK_OK:='".$from_zakdet["MTK_OK"]."' where (ID='".$to_id."')");
				dbquery("Update ".$db_prefix."db_zakdet Set NAME:='".$from_zakdet["NAME"]."' where (ID='".$to_id."')");
				dbquery("Update ".$db_prefix."db_zakdet Set OBOZ:='".$from_zakdet["OBOZ"]."' where (ID='".$to_id."')");

				// Копируем ДСЕ основной сборки ////////////////////////////

				CopyIzdIzd($from_id, $to_id);

				// Поправляем LID ссылок ///////////////////////////////////

				SetNewLID($to_id);

				////////////////////////////////////////////////////////////
			}
			}
		}

		redirect($page_url,"script");
		exit();
	}


?>