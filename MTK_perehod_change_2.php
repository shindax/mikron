<?php
define("MAV_ERP", TRUE);

include "config.php";
include "includes/database.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$per_id = $_GET['id'];
$per_val = $_GET['value'];
$per_fld = $_GET['field'];

function txt($text) {
	$text = stripslashes($text);
	$search = array("@%1@", "@%2@", "@%3@", "@%4@", "@%5@", "@%6@", "@%7@", "@%8@", "@%9@");
	$replace = array("&#39;", "&quot;", "(", ")", "\n", "&#38;", "#", "&#092;", "+");
	$text = str_replace($search, $replace, $text);
	return $text;
}
$per_val = txt($per_val);

dbquery("UPDATE okb_db_mtk_perehod SET ".$per_fld."='".$per_val."' WHERE ID='".$per_id."'");
?>