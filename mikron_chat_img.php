<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$p_1 = $_GET['p1'];

$url = $p_1;
/*if (substr($url, 0, 5)=="https"){
	$url="http".substr($url, 5);
}*/

if (@fopen($url, "r")) {
	if ((substr($url, strlen($url)-3, 3)=="bmp") or (substr($url, strlen($url)-3, 3)=="pdf") or (substr($url, strlen($url)-3, 3)=="jpg") or (substr($url, strlen($url)-3, 3)=="png") or (substr($url, strlen($url)-3, 3)=="gif")){
		echo "1";
	}else{
		echo "3";
	}
}else{
	echo "2";
}
?>