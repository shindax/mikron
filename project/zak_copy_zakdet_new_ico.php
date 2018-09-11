<?php 
global $user;
$usrght = explode("|", $user['ID_rightgroups']);
if ((in_array("1", $usrght)) or (in_array("20", $usrght))) {
	$re_s2=dbquery("SELECT IZD_CORR FROM okb_db_zak where ID='".$_GET['id']."' ");
	$na_m2=mysql_fetch_array($re_s2);
	if ($na_m2['IZD_CORR']=='0') { 
		echo "<img src='uses/plus.png' alt='Копировать' style='cursor:pointer;' onclick=window.open('index.php?do=show&formid=208&p0=".$render_row['ID']."');>";
	}
}
echo "<b id='hash_tp_".$render_row['ID']."' style='display:none;'></b>";
?>