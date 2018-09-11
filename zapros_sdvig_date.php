<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$ids_itrs = $_GET['p1'];
$newdp_itrs = $_GET['p2'];
$iduser_itr = $_GET['p3'];
$komms_itrs = $_GET['p4'];
$zapr_avt = $_GET['p5'];

if ($ids_itrs !== ''){
	
$ids_itrs_arr = explode("|", $ids_itrs);
$newdp_itrs_arr = explode("|", $newdp_itrs);
$iduser_itr_arr = explode("|", $iduser_itr);
$komms_itrs_arr = explode("|", $komms_itrs);

$cur_us = "";
$txt_zapr = "";
$komm_zapr = "";
$ins_id_zap = 0;
array_multisort($iduser_itr_arr, $ids_itrs_arr, $newdp_itrs_arr, $komms_itrs_arr);
foreach($iduser_itr_arr as $key_1 => $val_1){
	if (strlen($iduser_itr_arr[$key_1])>1){
		$int_to_dat = explode("-", $newdp_itrs_arr[$key_1]);
		$int_to_dat_ful = $int_to_dat[2].".".$int_to_dat[1].".".$int_to_dat[0];
		if ($val_1 == $cur_us){
			$txt_zapr = $txt_zapr."Задание № ".$ids_itrs_arr[$key_1]."&nbsp;&nbsp;&nbsp;&nbsp;Новая дата: ".$int_to_dat_ful."<br>";
			$komm_zapr = $komm_zapr."№ ".$ids_itrs_arr[$key_1].": ".$komms_itrs_arr[$key_1]."<br>";
			dbquery("UPDATE okb_db_zapros_all SET KOMM='".$komm_zapr."' WHERE ID='".$ins_id_zap."' ");
			dbquery("UPDATE okb_db_zapros_all SET TXT='".$txt_zapr."' WHERE ID='".$ins_id_zap."' ");
		}else{
			$res_1 = dbquery("SELECT ID FROM okb_db_resurs where (NAME='".$val_1."') AND (TID=0) ");
			$nam_1 = mysql_fetch_array($res_1);

			$txt_zapr = "Задание № ".$ids_itrs_arr[$key_1]."&nbsp;&nbsp;&nbsp;&nbsp;Новая дата: ".$int_to_dat_ful."<br>";
			$komm_zapr = $komms_itrs_arr[$key_1]."<br>";
			dbquery("INSERT INTO okb_db_zapros_all (DATE_PLAN, ID_users, CDATE, CTIME, TIME_PLAN, STATUS, SOGL, TIT_HEAD, ID_itrzadan, TIP_ZAPR, KOMM, TXT, ID_users2_plan) VALUES ('0', '".$zapr_avt."', '".date("Ymd")."', '".date("H:i:s")."', '00:00:00', 'Отправлен', '0', '0', '0', '1', '".$komm_zapr."', '".$txt_zapr."', '".$nam_1['ID']."')");
			$ins_id_zap = mysql_insert_id();
		}
		$cur_us = $val_1;
	}
}
}
?>