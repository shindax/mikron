<?php

define('MAV_ERP', true);
date_default_timezone_set('Asia/Krasnoyarsk');



include '../config.php';
include '../includes/database.php';
	

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$krzs = json_decode($_POST['krzs']);

	$arr = array();

foreach ($krzs as $krz) {
	$result = mysql_query("SELECT `FILENAME` FROM `okb_db_edo_inout_files` WHERE `ID_krz` LIKE '%" . $krz . "%'");
				

		while ($row = mysql_fetch_assoc($result)) {
		$arr[$krz][] = '<a target="_blank" href="/get_file.php?filename=db_edo_inout_files@FILENAME/' . $row['FILENAME'] . '"><img src="uses/ftypes/' . (strpos($row['FILENAME'], '.pdf') === false ? 'jpg' : 'pdf') . '.png"/></a>';
	}
}

echo json_encode($arr);
				
?>