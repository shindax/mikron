<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$start_time = microtime(true);
	include "includes.php";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$votes = "";

function ReloadRowID($id) {
	global $MM, $YY, $reloaded_ids, $db_prefix, $votes;

	$row_url = "vote_row.php?mm=".$MM."&yy=".$YY."&sel=i".$id;
	$svod_url = "vote_svodrow.php?mm=".$MM."&yy=".$YY."&sel=i".$id;

	$votes = $votes."reload_vote(\"R_i".$id."\",\"".$row_url."\");\n";
	$votes = $votes."reload_vote(\"S_i".$id."\",\"".$svod_url."\");\n";

	$xx = dbquery("SELECT ID, PID FROM ".$db_prefix."db_zakdet where (ID='".$id."')");
	if ($item = mysql_fetch_array($xx)) {
		if ($item["PID"]!=="0") ReloadRowID($item["PID"]);
	}
}

$calc_id = 0;
$calc_tp = "";
if (isset($_GET["sel"])) {
	$calc_tp = "i";
	if (substr_count($_GET["sel"], "o")>0) $calc_tp = "o";
	$calc_id = str_replace("i","",$_GET["sel"]);
	$calc_id = str_replace("o","",$calc_id);
}

if ($calc_tp=="o") {
	$xx = dbquery("SELECT ID, ID_zakdet FROM ".$db_prefix."db_operitems where (ID='".$calc_id."')");
	if ($res = mysql_fetch_array($xx)) {

		ReloadRowID($res["ID_zakdet"]);
		echo $votes;

	}
}


?>