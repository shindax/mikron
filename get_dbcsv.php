<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);



// ПОЕХАЛИ


	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "includes/cookie.php";
	include "includes/config.php";
	include "db_func.php";




function FReal($x) {
	$res = 0;
	if ($x!==0) $res = number_format( $x, 2, ',', ' ');
	return $res;
}

function OutTXT($txt) {
	$res = $txt;
	if (($res*1).""==$txt) $res = FReal($res);
	$res = str_replace("&quot;","\"",$res);
	$res = str_replace(";","",$res);
	$res = str_replace("\n","",$res);
	$res = str_replace("\r","",$res);

	echo $res.";";
}

function OutBR() {
	echo "\n";
}

function OutRow($item) {
	global $db, $fields, $db_cfg;

	for ($i=0;$i < count($fields); $i++) {

		$text = FVal($item,$db,$fields[$i]);

		if ($db_cfg[$db."/".$fields[$i]] == "multilist") $text = str_replace("<br>",",",$text);
		if ($db_cfg[$db."/".$fields[$i]] == "boolean") $text = $item[$fields[$i]];
		if ($db_cfg[$db."/".$fields[$i]] == "file") $text = $item[$fields[$i]];
		if ($db_cfg[$db."/".$fields[$i]] == "dateplan") {
			$values = $item[$fields[$i]];
			if ($values=="") $values ="0|";
			$values = explode("|",$values);
			$lastval = $values[count($values)-1];
			if ($lastval=="") $lastval = "##";
			$lastval = explode("#",$lastval);
			$text = $lastval[2];
		}

		OutTXT($text);
	}
	OutBR();
}



if ($user["ID"]*1>0) {

	$db = $_GET["db"];

	if (db_adcheck($db)) {

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$db.' '.Date('d.m.Y [H.i]',mktime()).'.csv');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');

	$fields = explode("|",$db_cfg[$db."|FIELDS"]);

	echo str_replace("|",";",$db_cfg[$db."|FIELDS"]."|");
	OutBR();

	$result = dbquery("SELECT * FROM ".$db_prefix.$db." order by ID");
	while($res = mysql_fetch_array($result)) OutRow($res);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	} else {
		die("Access Denied");
	}

} else {
	die("Access Denied");
}



?>