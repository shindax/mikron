<?php


	include "includes.php";

	define('MAV_ERP', true);

	

	$DI_WName = Array('','Пн','Вт','Ср','Чт','Пт','Сб','Вс');
	$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

	$today = TodayDate();
	$today = explode(".",$today);
	$today_DD = $today[0];
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$today_m = explode(".",TodayAddDays(-3));
	$today_m = $today_m[2]*10000+$today_m[1]*100+$today_m[0];

	$today_m8 = explode(".",TodayAddDays(-30));
	$today_m8 = $today_m8[2]*10000+$today_m8[1]*100+$today_m8[0];

	$DI_Date = TodayDate();
	if (isset($_GET["p0"])) $DI_Date = $_GET["p0"];
	$txtdd = $DI_Date;
	$DI_Date = explode(".",$DI_Date);

	$DI_YY = $DI_Date[2];
	$DI_LYY = $DI_YY;
	$DI_NYY = $DI_YY;
	$MY = $DI_Date[1].".".$DI_Date[2];

	$DI_MM = $DI_Date[1]-1;
	$DI_LMM = $DI_MM-1;
	if ($DI_LMM<0) $DI_LMM = 11;
	$DI_NMM = $DI_MM+1;
	if ($DI_NMM>11) $DI_NMM = 0;

	if ($DI_MM==0) $DI_LYY = $DI_YY-1;
	if ($DI_MM==11) $DI_NYY = $DI_YY+1;

	$DI_DD = 1;

	$lastM = $DI_MM;
	$yy = $DI_YY;
	if ($lastM<1) {
		$lastM = 12+$lastM;
		$yy = $yy - 1;
	}
	$lastM = $DI_DD.".".$lastM.".".$yy;

	$nextM = $DI_MM+2;
	$yy = $DI_YY;
	if ($nextM>12) {
		$nextM = $nextM-12;
		$yy = $yy + 1;
	}
	$nextM = $DI_DD.".".$nextM.".".$yy;

	$lastY = $DI_DD.".".($DI_MM+1).".".($DI_YY-1);
	$nextY = $DI_DD.".".($DI_MM+1).".".($DI_YY+1);
	

	$user_right_groups = explode('|', $user['ID_rightgroups']);

// Если есть доступ
if (in_array('43', $user_right_groups) || in_array('54', $user_right_groups) || in_array('1', $user_right_groups)) {
$_POST["resursIDS"] = json_decode(stripslashes($_POST["resursIDS"]));
	if (isset($_POST["variant"])) {
	$mk_tim_dat = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	   if (db_adcheck("db_tabel")) {

		$resursIDS_arr = $_POST["resursIDS"];
		$resursIDS = array();
		
		if (db_check("db_tabel","MEGA_REDACTOR")) {
			$resursIDS = $resursIDS_arr;
		}else{
			foreach($resursIDS_arr as $key_1 => $val_1) {
				$ch_res_query = dbquery("SELECT ID, ID_tab FROM okb_db_resurs where (ID_resurs='".$val_1."')");
				$ch_res_fetch = mysql_fetch_array($ch_res_query);
				if ($ch_res_fetch['ID_tab'] == $user_id){
					$resursIDS[] = $ch_res_fetch['ID'];
				}
			}
		}
		
		$variant = $_POST["variant"];

		$DD_0 = $_POST["firstday"];
		$DD_1 = $_POST["secondday"];

		$CP_0 = $_POST["firstcopy"];
		$CP_1 = $_POST["secondcopy"];

		$pdate_0 =  $DI_YY*10000+($DI_MM+1)*100+$DD_0;
		$pdate_1 =  $DI_YY*10000+($DI_MM+1)*100+$DD_1;

		$cpdate_0 =  $DI_YY*10000+($DI_MM+1)*100+$CP_0;
		$cpdate_1 =  $DI_YY*10000+($DI_MM+1)*100+$CP_1;

		$DD_x0 = $DD_0;
		if ($today>=$pdate_0) $DD_x0 = $today_DD + 1;






		// Простановка не с графиков работ
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($variant!=="by_st") {

		   // ЦИКЛ ПО РЕСУРСАМ
		   for ($j=0;$j < count($resursIDS);$j++) {

			// ЦИКЛ ПО ВСЕМ ДНЯМ
			for ($d=$DD_0;$d < $DD_1+1;$d++) {

				$xdate = $DI_YY*10000+($DI_MM+1)*100+$d;

				if ($xdate>$today) {

					//////////////////// P

					// WORK
					if ($variant=="work") {
						$var_smena = $_POST["var_smena"];
						$var_time = $_POST["var_time"];
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '".$var_smena."', '".$resursIDS[$j]."', '0', '".$var_time."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// CLEAR
					if ($variant=="clear") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}

					// OTPUSK
					if ($variant=="otpusk") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '1', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// ADMOTPUSK
					if ($variant=="admotpusk") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '2', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// KOMMAND
					if ($variant=="kommand") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '10', '8', '8', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// SEEK
					if ($variant=="seek") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '4', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					// FILED
					if ($variant=="filed") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '3', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}

					if ($variant=="v_7") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '7', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_15") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '15', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_8") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '8', '7', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_9") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '9', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_11") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '11', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_12") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '12', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_13") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '13', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
					if ($variant=="v_14") {
						dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '14', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}







					/////////////////////////////////


				} else {
				// A

					if ($variant == "nnn") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='5' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "nnpr") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='6' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "gosob") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='16' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "addopozd") {
						dbquery("Update ".$db_prefix."db_tabel Set OPOZD:='1' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "delopozd") {
						dbquery("Update ".$db_prefix."db_tabel Set OPOZD:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "otpusk") {
						$ch_dat_otp = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_otp_nam=mysql_fetch_array($ch_dat_otp);
						if (!$ch_dat_otp_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '1', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='1' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "admotpusk") {
						$ch_dat_otp = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_otp_nam=mysql_fetch_array($ch_dat_otp);
						if (!$ch_dat_otp_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '2', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='2' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "kommand") {
						$ch_dat_otp = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_otp_nam=mysql_fetch_array($ch_dat_otp);
						if (!$ch_dat_otp_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '10', '0', '8', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='10' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "seek") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '4', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='4' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "filed") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='3' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "work_f") {
						$var_smena_f = $_POST["var_smena_f"];
						$var_time_f = $_POST["var_time_f"];
						dbquery("Update ".$db_prefix."db_tabel Set TID:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set SMEN:='".$var_smena_f."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='".$var_time_f."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "inwork") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set SMEN:='1' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_7") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='7' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_15") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '15', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='15' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_8") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='7' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_9") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='9' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_11") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='11' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_12") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '12', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='12' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_13") {
						$ch_dat_sek = dbquery("SELECT * FROM okb_db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						$ch_dat_sek_nam=mysql_fetch_array($ch_dat_sek);
						if (!$ch_dat_sek_nam){
							dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$xdate."', '1', '".$resursIDS[$j]."', '13', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
						dbquery("Update ".$db_prefix."db_tabel Set TID:='13' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='0' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
					if ($variant == "v_14") {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='14' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set FACT:='8' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set EUSER:='".$user['ID']."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
						dbquery("Update ".$db_prefix."db_tabel Set ETIME:='".$mk_tim_dat."' where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$xdate."')");
					}
				}
			}// ЦИКЛ ПО ВСЕМ ДНЯМ

			if ($cpdate_1+1>$today) {

				// COPY
				if ($variant=="copy") {
					dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$cpdate_1."')");
					$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE='".$cpdate_0."')");
					if ($res = mysql_fetch_array($xxx)) {
						dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$cpdate_1."', '".$res["SMEN"]."', '".$resursIDS[$j]."', '".$res["TID"]."', '".$res["PLAN"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
					}
				}
			}
		   }// ЦИКЛ ПО РЕСУРСАМ
		}


		// Простановка с графиков работ
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////


		if ($variant=="by_st") {
			if (($today<$pdate_1+1) && ($pdate_1+1>$pdate_0)) {
				// ЦИКЛ ПО РЕСУРСАМ
				//////////////////////////////////////////////////////////////////////////////////////////
				for ($j=0;$j < count($resursIDS);$j++) {
					//$DD_x0..$DD_1

					$xdate0 = $DI_YY*10000+($DI_MM+1)*100+$DD_x0;
					$xdate1 = $DI_YY*10000+($DI_MM+1)*100+$DD_1;

					// Затираем чо было ??? надо ли затирать?
					dbquery("DELETE from ".$db_prefix."db_tabel where (ID_resurs='".$resursIDS[$j]."') and (DATE>='".$xdate0."') and (DATE<='".$xdate1."') and (TID!='1') and (TID!='2') and (TID!='12') and (TID!='13') ");

					// Записываем согласно графику работ сотрудника
					$xxx = dbquery("SELECT ID, ID_tab_st FROM ".$db_prefix."db_resurs where (ID='".$resursIDS[$j]."')");
					if ($resurs = mysql_fetch_array($xxx)) {
					    if ($resurs["ID_tab_st"]*1!==0) {
						$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tab_sti where (ID_tab_st='".$resurs["ID_tab_st"]."') and (DATE>='".$xdate0."') and (DATE<='".$xdate1."') order by DATE");
						while ($smn = mysql_fetch_array($xxx)) {
							if ($smn["TID"]*1==0) dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$smn["DATE"]."', '".$smn["SMEN"]."', '".$resursIDS[$j]."', '0', '".$smn["HOURS"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
							if ($smn["TID"]*1==1) dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$smn["DATE"]."', '0', '".$resursIDS[$j]."', '7', '0', '0', '".$user['ID']."', '".$mk_tim_dat."')");
							if ($smn["TID"]*1==2) dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT, EUSER, ETIME) VALUES ('".$smn["DATE"]."', '".$smn["SMEN"]."', '".$resursIDS[$j]."', '8', '".$smn["HOURS"]."', '0', '".$user['ID']."', '".$mk_tim_dat."')");
						}
					    }
					}
				}
				//////////////////////////////////////////////////////////////////////////////////////////
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	   }
	}



}


	function TodayDate() {
		return date("d.m.Y");
	}

	function GetMonday($dweek=0){
		return date("d.m.Y", strtotime("last Monday")+($dweek*604800));
	}

	function GetSunday($dweek=0){
		return date("d.m.Y", strtotime("Sunday")+($dweek*604800));
	}

	function TodayInt() {
		return date("Ymd")*1;
	}

	function NextYear() {
		$today = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		return date("Y",$today)+1;
	}

	function TodayAddDays($x) {
		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		return date("d.m.Y",$theday+($x*86400));
	}
	function db_check($db,$field) {
		global $db_cfg, $user, $user_rights, $print_mode;

		$res = false;
		if (is_array($user_rights)) {
			if (in_array($db."|redactor",$user_rights)) $res = true;
			if (!isset($db_cfg[$db."/".$field])) $res = false;
			if (in_array("-".$db."/".$field,$user_rights)) $res = false;
			if (in_array($db."/".$field,$user_rights)) $res = true;
			if (in_array($db."|superadmin",$user_rights)) $res = true;
			if (in_array("superadmin",$user_rights)) $res = true;
		}

	   // СПЕЦ ТАБЛИЦЫ
		if (($user["USERSEDIT"]=="1") && ($db=="users")) $res = true;
		if ((($user["ID"]=="1") or ($user["ID"]=="91")) && ($db=="rightgroups")) $res = true;
		if ((($user["ID"]=="1") or ($user["ID"]=="91")) && ($db=="viewgroups")) $res = true;
		if ((($user["ID"]=="1") or ($user["ID"]=="91")) && ($db=="formgroups")) $res = true;
		if ((($user["ID"]=="1") or ($user["ID"]=="91")) && ($db=="forms")) $res = true;
		if ((($user["ID"]=="1") or ($user["ID"]=="91")) && ($db=="formsitem")) $res = true;

	   // СПЕЦ ПОЛЯ
		if ($field=="ID") $res = false;
		if ($field=="PID") $res = false;

	   // PRINTMODE
		if ($print_mode=="on") $res = false;

		return $res;
	}

?>