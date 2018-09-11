<?php

// онеуюкх

	define("MAV_ERP", TRUE);

	include "../config.php";
	include "../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$re_s1 = dbquery("SELECT ID_krz, ID FROM okb_db_edo_inout_files where ID='".$_GET['p2']."' ");
	$na_m1 = mysql_fetch_array($re_s1);

	$krz_list = $na_m1['ID_krz'].$_GET['p1'];
	$krz_arr = explode('|', $krz_list);

	foreach ( $krz_arr as $key => $value) 
	{
		if( $value[0] == '0' )
			$krz_arr[ $key ] = substr( $value, 1 );
	}
	
	$krz_list = join('|', $krz_arr );

	dbquery("UPDATE okb_db_edo_inout_files SET ID_krz='$krz_list' WHERE ID='".$_GET['p2']."'");

	if (mysql_result(dbquery("SELECT TIP_FAIL FROM okb_db_edo_inout_files where ID='".$_GET['p2']."' "), 0) == 1) {
		$in_id = mysql_result(dbquery("SELECT ID FROM okb_db_edo_inout_files WHERE ID_krz LIKE '%".$na_m1['ID_krz'].$_GET['p1']."%' AND TIP_FAIL = 0 LIMIT 1"), 0);
		
		dbquery("UPDATE okb_db_edo_inout_files SET OTVET_INOUT = " . $_GET['p2'] . " WHERE ID = " . $in_id );

		dbquery("UPDATE okb_db_edo_inout_files SET OTVET_INOUT = '" . $in_id . "' WHERE ID = " . $_GET['p2'] );
		
	} else {

	
	}

	
?>