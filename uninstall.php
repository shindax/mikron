<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);

	include "config.php";
	include "locale/".$lang."/lang.php";
	include "includes/database.php";
	include "includes/config.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

function db_uninstall() {
	global $db_cfg, $db_prefix;

	if ($db_cfg["SETUP"]!=="") {
	$db_tables = explode("|",$db_cfg["SETUP"]);
	for ($j=0;$j < count($db_tables);$j++) {
		$sql = "DROP TABLE IF EXISTS ".$db_prefix.$db_tables[$j];
		dbquery($sql);
		echo "==========================================================<br>";
		echo "Delete table \"".$db_tables[$j]."\" - ok<br>";
		echo "==========================================================<br><br><br>";
	}
	echo "Uninstall complete!";
	}
}

db_uninstall();


?>