<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$ids_itrs = $_GET['p1'];
$newdp_itrs = $_GET['p2'];
$txt_itrs = $_GET['p3'];
$komms_itrs = $_GET['p4'];
$itr_avt = $_GET['p5'];
$itr_us2 = $_GET['p6'];
$itr_us3 = $_GET['p7'];
$ids_zaks = $_GET['p8'];

if ($ids_itrs !== ''){
	
$ids_itrs_arr = explode("|", $ids_itrs);
$newdp_itrs_arr = explode("|", $newdp_itrs);
$txt_itrs_arr = explode("|", $txt_itrs);
$komms_itrs_arr = explode("|", $komms_itrs);
$us2_itrs_arr = explode("|", $itr_us2);
$us3_itrs_arr = explode("|", $itr_us3);
$zak_itrs_arr = explode("|", $ids_zaks);

foreach($ids_itrs_arr as $key_1 => $val_1){
	if (strlen($us2_itrs_arr[$key_1]) > 1){
		$int_to_dat_ful = substr($newdp_itrs_arr[$key_1], 0, 4).substr($newdp_itrs_arr[$key_1], 5, 2).substr($newdp_itrs_arr[$key_1], 8, 2);
		
			$res_1 = dbquery("SELECT ID FROM okb_db_resurs where (NAME='".$us2_itrs_arr[$key_1]."') AND (TID=0) ");
			$nam_1 = mysql_fetch_array($res_1);
			$res_2 = dbquery("SELECT ID FROM okb_db_resurs where (NAME='".$us3_itrs_arr[$key_1]."') AND (TID=0) ");
			$nam_2 = mysql_fetch_array($res_2);
			dbquery("Update okb_db_itrzadan Set KOMM1='Исполнитель уволен' where (ID='".$val_1."')");
			dbquery("Update okb_db_itrzadan Set STATUS='Аннулировано' where (ID='".$val_1."')");
			dbquery("INSERT INTO okb_db_itrzadan (ID_zak, TIP_JOB, TIP_FAIL, STARTTIME, STARTDATE, EUSER, ETIME, DATE_PLAN, ID_users, CDATE, CTIME, TIME_PLAN, STATUS, TIT_HEAD, KOMM1, TXT, ID_users2, ID_users3) VALUES ('".$zak_itrs_arr[$key_1]."', '1', '9', '".date("H:i:s")."', '".date("Ymd")."', '".$itr_avt."', '".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$int_to_dat_ful."', '".$itr_avt."', '".date("Ymd")."', '".date("H:i:s")."', '17:00:00', 'Новое', '', '".$komms_itrs_arr[$key_1]."', '".$txt_itrs_arr[$key_1]."', '".$nam_1['ID']."', '".$nam_2['ID']."')");
	}
}
}
?>