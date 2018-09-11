<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$prot_id = $_GET['p2'];
$edit_state = $_GET['p1'];

$res_1 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$prot_id."') ");
$name_1 = mysql_fetch_array($res_1);

dbquery("UPDATE okb_db_protocols SET EDIT_STATE='".$edit_state."' WHERE ID='".$prot_id."'");

$arr_zaks = explode("|", $name_1['ID_zaks']);
$arr_txt = explode("|", $name_1['TXT']);
$arr_us2 = explode("|", $name_1['ID_users2']);
$arr_us3 = explode("|", $name_1['ID_users3']);
$arr_dp = explode("|", $name_1['DATA_PLAN']);

foreach($arr_zaks as $key_1 => $val_1) {
	$dat_dp = explode("-",$arr_dp[$key_1]);
	if ((count($dat_dp)==3) and ($val_1 !=='0') and ($val_1 !=='') and (strlen($arr_txt[$key_1])>1) and (strlen($arr_us2[$key_1])>1)){
		$res_2 = dbquery("SELECT * FROM okb_db_shtat where (NAME='".$arr_us2[$key_1]."') ");
		$name_2 = mysql_fetch_array($res_2);
		$res_3 = dbquery("SELECT * FROM okb_db_shtat where (NAME='".$arr_us3[$key_1]."') ");
		$name_3 = mysql_fetch_array($res_3);
		$res_4 = dbquery("SELECT * FROM okb_db_shtat where (NAME='".$name_1['ID_users']."') ");
		$name_4 = mysql_fetch_array($res_4);
		$plan_date = $dat_dp[0].$dat_dp[1].$dat_dp[2];
		
		dbquery("INSERT INTO okb_db_itrzadan (TIME_PLAN,TIT_HEAD,TIP_JOB,TIP_FAIL,DOCISP,STARTTIME,STARTDATE,KOMM1,KOMM2,KOMM3,ID_zak,ID_users,ID_users2,ID_users3,CDATE,CTIME,TXT,ETIME,EUSER,DATE_PLAN,STATUS,ID_edo,ID_zapr) 
		VALUES ('17:00:00','', '1','2','','".date("H:i:s")."','".date("Ymd")."','','','','".$val_1."','".$name_4['ID_resurs']."','".$name_2['ID_resurs']."', '".$name_3['ID_resurs']."', '".date("Ymd")."', '".date("H:i:s")."', '".$arr_txt[$key_1]."', '".$name_1['ETIME']."', '".$name_1['EUSER']."', '".$plan_date."', 'Новое', '".$prot_id."', '0')");
	}
}
?>