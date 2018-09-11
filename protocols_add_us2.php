<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$prot_id = $_GET['p2'];
$key_numb = $_GET['p1'];
$key_val_zak_id = $_GET['p3'];
$key_val_us3 = $_GET['p4'];

$res_1 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$prot_id."') ");
$name_1 = mysql_fetch_array($res_1);

$arr_count_zaks_expl = explode("|", $name_1['ID_zaks']);
$arr_count_us3_expl = explode("|", $name_1['ID_users3']);
$arr_count_us2_expl = explode("|", $name_1['ID_users2']);
$arr_count_txt_expl = explode("|", $name_1['TXT']);
$arr_count_dp_expl = explode("|", $name_1['DATA_PLAN']);
$arr_count_zaks = count($arr_count_zaks_expl)-1;

$new_arr_dp_id = "";
$new_arr_us2_id = "";
$new_arr_txt_id = "";
$new_arr_zak_id = "";
$new_arr_us3_id = "";

for ($c_f = 0; $c_f < ($key_numb+1); $c_f++){
	$new_arr_zak_id = $new_arr_zak_id.$arr_count_zaks_expl[$c_f]."|";
	$new_arr_us3_id = $new_arr_us3_id.$arr_count_us3_expl[$c_f]."|";
	$new_arr_us2_id = $new_arr_us2_id.$arr_count_us2_expl[$c_f]."|";
	$new_arr_txt_id = $new_arr_txt_id.$arr_count_txt_expl[$c_f]."|";
	$new_arr_dp_id = $new_arr_dp_id.$arr_count_dp_expl[$c_f]."|";
}
$new_arr_zak_id = $new_arr_zak_id.$key_val_zak_id."|";
$new_arr_us3_id = $new_arr_us3_id.$key_val_us3."|";
$new_arr_us2_id = $new_arr_us2_id."|";
$new_arr_txt_id = $new_arr_txt_id."|";
$new_arr_dp_id = $new_arr_dp_id."|";
for ($c_f = ($key_numb+1); $c_f < $arr_count_zaks; $c_f++){
	$new_arr_zak_id = $new_arr_zak_id.$arr_count_zaks_expl[$c_f]."|";
	$new_arr_us3_id = $new_arr_us3_id.$arr_count_us3_expl[$c_f]."|";
	$new_arr_us2_id = $new_arr_us2_id.$arr_count_us2_expl[$c_f]."|";
	$new_arr_txt_id = $new_arr_txt_id.$arr_count_txt_expl[$c_f]."|";
	$new_arr_dp_id = $new_arr_dp_id.$arr_count_dp_expl[$c_f]."|";
}

	dbquery("Update okb_db_protocols Set ID_zaks='".$new_arr_zak_id."' where (ID='".$prot_id."')");
	dbquery("Update okb_db_protocols Set ID_users2='".$new_arr_us2_id."' where (ID='".$prot_id."')");
	dbquery("Update okb_db_protocols Set ID_users3='".$new_arr_us3_id."' where (ID='".$prot_id."')");
	dbquery("Update okb_db_protocols Set TXT='".$new_arr_txt_id."' where (ID='".$prot_id."')");
	dbquery("Update okb_db_protocols Set DATA_PLAN='".$new_arr_dp_id."' where (ID='".$prot_id."')");
?>