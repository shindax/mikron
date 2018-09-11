<?php

// онеуюкх

	define("MAV_ERP", TRUE);

	include "../config.php";
	include "../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$re_s1 = dbquery("SELECT ID_krz, ID FROM okb_db_edo_inout_files where ID='".$_GET['p2']."' ");
	$na_m1 = mysql_fetch_array($re_s1);
	$new_ids_krz = "";
	$arr_ids_krs = explode("|", $na_m1['ID_krz']);
	foreach ($arr_ids_krs as $kk1 => $vv1){
		if ((intval($vv1)!==intval($_GET['p1'])) and ($vv1!=='')){
	
			
			$new_ids_krz .= $vv1."|";
			
		}
	} 
	print_r($new_ids_krz);
	dbquery("UPDATE okb_db_edo_inout_files SET ID_krz='".$new_ids_krz."' WHERE ID='".$_GET['p2']."'");
?>