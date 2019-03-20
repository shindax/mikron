<?php
// ïîñëå óñïåøíîãî äîáàâëåíèÿ èä¸ò ðåäèðåêò íà $pageurl
// $insert_id - ID íîâîãî ýëåìåíòà

	if (!defined("MAV_ERP")) { die("Access Denied"); }


$DI_MName = Array('', 'ßÍÂÀÐÜ','ÔÅÂÐÀËÜ','ÌÀÐÒ','ÀÏÐÅËÜ','ÌÀÉ','ÈÞÍÜ','ÈÞËÜ','ÀÂÃÓÑÒ','ÑÅÍÒßÁÐÜ','ÎÊÒßÁÐÜ','ÍÎßÁÐÜ','ÄÅÊÀÁÐÜ');

$result = dbquery("SELECT ID, CDATE, PID, ID_krz2, TID FROM ".$db_prefix."db_zak where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {

	if ($row["PID"]*1==0) {
	// Åñëè íå âõîäÿùèé

		$numtxt = "001";

		$ddd = IntToDate($row["CDATE"]);
		$ddd = explode(".",$ddd);
		$MM = $ddd[1]*1;
		$YY = $ddd[2]*1;
		$sdate = $YY*10000 + 100;
		$edate = $YY*10000 + 1232;
		$YY = $YY-2000;

		$resxxx = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_zak where (CDATE>'".$sdate."') and (CDATE<'".$edate."') and (ID<'".$insert_id."') and (PID='0') order by ID desc");
		if ($last = mysql_fetch_array($resxxx)) {
			$num = $last["NAME"];
			$num = explode("-",$num);
			$num = $num[1]*1;
			$num = $num + 1;
			$numtxt = $num;
			if ($num<10) $numtxt = "0".$numtxt;
			if ($num<100) $numtxt = "0".$numtxt;
		}
		$ord = $YY*1000000+$numtxt*1000;
		dbquery("Update ".$db_prefix."db_zak Set NAME:='".$YY."-".$numtxt."' where (ID='".$insert_id."')");
		dbquery("Update ".$db_prefix."db_zak Set ORD:='".$ord."' where (ID='".$insert_id."')");

		if ($row["TID"]*1==4) dbquery("Update ".$db_prefix."db_zak Set DSE_NAME:='ÁËÈÖ ".$DI_MName[$MM]."' where (ID='".$insert_id."')");
		if ($row["TID"]*1==5) dbquery("Update ".$db_prefix."db_zak Set DSE_NAME:='ÕÎÇ. ÇÀÊÀÇ ".$DI_MName[$MM]."' where (ID='".$insert_id."')");

		if ($row["TID"]*1==5) {  // Åñëè ÕÇ
			$resxxx = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_zakdet where (ID_zak='".$insert_id."') and (PID='0')");
			if ($dse = mysql_fetch_array($resxxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_operitems (ID_zak, ID_zakdet, ID_oper) VALUES ('".$insert_id."', '".$dse["ID"]."', '83')");
			}
		}

	} else {
	// Åñëè âõîäÿùèé

		$resxxx = dbquery("SELECT ID, NAME, ORD FROM ".$db_prefix."db_zak where (ID='".$row["PID"]."')");
		if ($parent = mysql_fetch_array($resxxx)) {

			$numtxt = "001";

			$resxxx = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_zak where (PID='".$row["PID"]."') order by ORD desc");
			if ($last = mysql_fetch_array($resxxx)) {
				$num = $last["NAME"];
				$num = explode("-",$num);
				$num = $num[2]*1;
				$num = $num + 1;
				$numtxt = $num;
				if ($num<10) $numtxt = "0".$numtxt;
				if ($num<100) $numtxt = "0".$numtxt;
			}

			$ord = $parent["ORD"]*1 + $numtxt*1;
			dbquery("Update ".$db_prefix."db_zak Set NAME:='".$parent["NAME"]."-".$numtxt."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set ORD:='".$ord."' where (ID='".$insert_id."')");
		}
	}

	if ($row["ID_krz2"]*1>0) {
	// Åñëè äîáàâëÿëè èç ÊÐÇ2


		function setplandate($ddd) {
			global $today_0;

			$res = "";
			if ($ddd*1!==0) $res = "0|".$today_0."#0#".IntToDate($ddd);
			return $res;
		}




		$krz2res = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where (ID='".$row["ID_krz2"]."') ");
		if ($krz2 = mysql_fetch_array($krz2res)) {

			$pd1 = setplandate($krz2["D1"]);
			$pd2 = setplandate($krz2["D2"]);
			$pd3 = setplandate($krz2["D3"]);
			$pd13 = setplandate($krz2["D4"]);
			$pd4 = setplandate($krz2["D5"]);
			$pd5 = setplandate($krz2["D6"]);
			$pd6 = setplandate($krz2["D7"]);
			$pd7 = setplandate($krz2["D8"]);
			$pd14 = setplandate($krz2["D9"]);
			$pd12 = setplandate($krz2["D10"]);
			$pd8 = setplandate($krz2["D11"]);
			$pd9 = setplandate($krz2["D12"]);
			$pd10 = setplandate($krz2["D13"]);
			$pd11 = setplandate($krz2["D14"]);
			$pd15 = setplandate($krz2["D15"]);
			$pd17 = setplandate($krz2["D17"]);

			$price = round(100*$krz2["NORM_PRICE"])/100;
			

			dbquery("Update ".$db_prefix."db_zak Set TID:='1' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set ID_clients:='".$krz2["ID_clients"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set NORM_PRICE:='".$price."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD1:='".$pd1."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD2:='".$pd2."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD3:='".$pd3."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD4:='".$pd4."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD5:='".$pd5."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD6:='".$pd6."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD7:='".$pd7."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD8:='".$pd8."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD9:='".$pd9."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD10:='".$pd10."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD11:='".$pd11."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD12:='".$pd12."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD13:='".$pd13."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD14:='".$pd14."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD_coop1:='".$pd15."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD_coop2:='".$pd17."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD15:='".$krz2["D15"]."' where (ID='".$insert_id."')");
			//dbquery("Update ".$db_prefix."db_zak Set PD16:='".$krz2["D16"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set PD17:='".$krz2["D17"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_zak Set ID_krz2:='".$krz2["ID"]."' where (ID='".$insert_id."')");

			// Ïèøåì â ÊÐÇ2
			dbquery("Update ".$db_prefix."db_krz2 Set EDIT_STATE:='1' where (ID='".$krz2["ID"]."')");
			dbquery("Update ".$db_prefix."db_krz2 Set ZAKNUM:='".$insert_id."' where (ID='".$krz2["ID"]."')");

			// Äîáàâëÿåì ÈÒÐ çàäàíèÿ ïî îòêðûòîìó çàêàçó
			$itr_res = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='9') AND (BOSS='1'))");
			$itr_res_1 = mysql_fetch_array($itr_res);
			
			$itr_res2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='17') AND (BOSS='1'))");
			$itr_res2_1 = mysql_fetch_array($itr_res2);

			$itr_res4 = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='12') AND (BOSS='1'))");
			$itr_res4_1 = mysql_fetch_array($itr_res4);

			$itr_res5 = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='11') AND (BOSS='1'))");
			$itr_res5_1 = mysql_fetch_array($itr_res5);

			$itr_res6 = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='31') AND (BOSS='1'))");
			$itr_res6_1 = mysql_fetch_array($itr_res6);

			//$itr_res7 = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='16') AND (BOSS='1'))");
			//$itr_res7_1 = mysql_fetch_array($itr_res7);

			$itr_res8 = dbquery("SELECT * FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='45') AND (BOSS='1'))");
			$itr_res8_1 = mysql_fetch_array($itr_res8);

			$itr_res3 = dbquery("SELECT * FROM ".$db_prefix."db_resurs WHERE (ID='".$user['ID']."') ");
			$itr_res3_1 = mysql_fetch_array($itr_res3);

dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res_1['ID_resurs']."', '".$itr_res2_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ðàçðàáîòàòü ÊÄ', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D1"]."', 'Íîâîå', '0', '0')");
dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res_1['ID_resurs']."', '".$itr_res4_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ðàçðàáîòàòü íîðìû ðàñõîäà è ìàòåðèàëîâ', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D2"]."', 'Íîâîå', '0', '0')");
dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res_1['ID_resurs']."', '".$itr_res2_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ðàçðàáîòàòü ÌÒÊ', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D3"]."', 'Íîâîå', '0', '0')");

dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res4_1['ID_resurs']."', '".$itr_res_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ïðîðàáîòàòü ïîñòàâêó ìàòåðèàëîâ', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D5"]."', 'Íîâîå', '0', '0')");
dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res4_1['ID_resurs']."', '".$itr_res2_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ïîñòàâèòü ìàòåðèàëû è ÏÊÈ', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D8"]."', 'Íîâîå', '0', '0')");

if ($krz2["D15"] !== '0') { dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res5_1['ID_resurs']."', '".$itr_res6_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ïðîðàáîòàòü âîçìîæíîñòü âûïîëíåíèÿ ðàáîò ïî êîîïåðàöèè', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D15"]."', 'Íîâîå', '0', '0')");}
//dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
//VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res5_1['ID_resurs']."', '".$itr_res6_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ñîãëàñîâàòü ñðîêè âûïîëíåíèÿ ðàáîò ïî êîîïåðàöèè ñ Èñïîëíèòåëåì è Äèðåêòîðîì ÎÊÁ Ìèêðîí', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D16"]."', 'Íîâîå', '0', '0')");
if ($krz2["D17"] !== '0') { dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res5_1['ID_resurs']."', '".$itr_res6_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Ïîñòàâèòü êîìïëåêòóþùèå èçäåëèÿ, âûïîëíåííûå ïî êîîïåðàöèè', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D17"]."', 'Íîâîå', '0', '0')");}

dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res6_1['ID_resurs']."', '".$itr_res8_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Îáåñïå÷èòü çàïóñê ïðîèçâîäñòâà èçäåëèé', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D10"]."', 'Íîâîå', '0', '0')");
dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res6_1['ID_resurs']."', '".$itr_res8_1['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', 'Îáåñïå÷èòü çàâåðøåíèå ïðîèçâîäñòâà èçäåëèé', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D11"]."', 'Íîâîå', '0', '0')");

dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res8_1['ID_resurs']."', '13', '".date("Ymd")."', '".date("H:i:s")."', 'Îáåñïå÷èòü ïîñòàâêó èçäåëèé', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D14"]."', 'Íîâîå', '0', '0')");
dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
VALUES ('17:00:00','', '1','9','','".date("H:i:s")."','".date("Ymd")."','','','','".$insert_id."','13','".$itr_res8_1['ID_resurs']."', '13', '".date("Ymd")."', '".date("H:i:s")."', 'Ïðîèçâåñòè îêîí÷àòåëüíûé ðàñ÷åò ñ ïðåäîñòàâëåíèåì íåîáõîäèìûõ äîêóìåíòîâ', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$itr_res3_1['ID']."', '".$krz2["D13"]."', 'Íîâîå', '0', '0')");

		}

	}

	// Ðåäèðåêò íà çàêàç
	$pageurl="index.php?do=show&formid=39&id=".$insert_id;

} 

dbquery("INSERT INTO `okb_db_request_events` VALUES (null, " . $insert_id . ", " . mysql_result(dbquery("SELECT `ID` FROM `okb_db_resurs` WHERE `ID_users` = " . $user['ID']), 0) . ", 13, NOW(), 0, 'Íîâûé çàêàç — " . iconv('windows-1251', 'utf-8', mysql_result(dbquery("SELECT `NAME` FROM `okb_db_zak` WHERE `ID` = " . $insert_id), 0)) . " (" .  mysql_result(dbquery("SELECT concat_ws(' - ', `NAME`, OBOZ) FROM `okb_db_krz2det` WHERE `ID_krz2` = " . mysql_result(dbquery("SELECT ID_krz2 from okb_db_zak WHERE ID = " . $insert_id), 0)), 0) . ")', 'zak', 'comment')");











function SendMail( $recipients, $theme, $description )
{
              $mail=new PHPMailer();
            $mail->CharSet = 'UTF-8';

 
            $mail->IsSMTP();
            $mail->Host       = 'smtp.yandex.com';

            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->SMTPDebug  = 2;
            $mail->SMTPAuth   = true;

            $mail->Username   = 'notice@okbmikron.ru';
            $mail->Password   = 'wIMkFw8i2q9sE4nGhEXp';

            $mail->isHTML(true);

            $mail->SetFrom('notice@okbmikron.ru', iconv('windows-1251', 'utf-8', 'Óâåäîìëåíèå ñ ñàéòà ÊÈÑ ÎÊÁ Ìèêðîí'));
            $mail->Subject = $theme;
            $mail->MsgHTML($description );

			
			foreach($recipients as $recipient) {
				$mail->AddAddress( $recipient, $recipient);
			}
				$mail->AddAddress( 'ray@okbmikron.ru', 'ray@okbmikron.ru');
				$mail->AddAddress( 'pimenov.r.a@okbmikron.ru', 'pimenov.r.a@okbmikron.ru');

            $mail->send();
}

require_once( "/var/www/okbmikron/www/includes/phpmailer/PHPMailerAutoload.php" );
require_once('/var/www/okbmikron/www/db_mysql_pdo.php');

	SendMail($emails, iconv('windows-1251', 'utf-8', 'Óâåäîìëåíèå ñ ñàéòà ÊÈÑ ÎÊÁ Ìèêðîí — Îòêðûò íîâûé çàêàç'), '
	' . iconv('windows-1251', 'utf-8', mysql_result(dbquery("SELECT `NAME` FROM `okb_db_zak` WHERE `ID` = " . $insert_id), 0)) . " (" .  mysql_result(dbquery("SELECT concat_ws(' - ', `NAME`, OBOZ) FROM `okb_db_krz2det` WHERE `ID_krz2` = " . mysql_result(dbquery("SELECT ID_krz2 from okb_db_zak WHERE ID = " . $insert_id), 0)), 0) . ")");
  



?>