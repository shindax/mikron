<?php
	define("MAV_ERP", TRUE);

	include "../config.php";
	include "../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	
$re_s2 = dbquery("SELECT ID_krz FROM okb_db_krz where ID='".$_GET['p0']."' ");
$na_m2 = mysql_fetch_array($re_s2);

$arr_cur_krz = array();
$arr_krz_ids = explode("|", $na_m2['ID_krz']);

foreach($arr_krz_ids as $key_2 => $val_2) {
if (($val_2 !=='') and ($val_2 !=='0')){
	$arr_cur_krz[$val_2*1]=$val_2*1;
}}

$arr_krzdet_nam = array();
$arr_krzdet_obz = array();
$re_s3 = dbquery("SELECT okb_db_krzdet.ID_krz, okb_db_krzdet.NAME, okb_db_krzdet.OBOZ FROM okb_db_krzdet where okb_db_krzdet.PID=0");
while ($na_m3 = mysql_fetch_row($re_s3)){
	$arr_krzdet_nam[$na_m3[0]]= $na_m3[1];
	$arr_krzdet_obz[$na_m3[0]]= $na_m3[2];
}

$resp_txt = "";
$re_s1 = dbquery("SELECT okb_db_krz.NAME, okb_db_krz.ID FROM okb_db_krz order by okb_db_krz.ID desc");
while ($na_m1 = mysql_fetch_row($re_s1)){
	if (!$arr_cur_krz[$na_m1[1]]) {
		$resp_txt .= "<option name='nam_sel_cur_krz' id='id_sel_cur_krz_".$na_m1[1]."' name2='0' onclick='sel_cur_krs(this);'>".$na_m1[0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr_krzdet_nam[$na_m1[1]]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$arr_krzdet_obz[$na_m1[1]];
	}
}

echo $resp_txt;
?>
