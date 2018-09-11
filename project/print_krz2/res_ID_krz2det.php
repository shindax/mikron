<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

$det_array = [];
$count_array = [];

function OpenID($item,$count) {
	global $db_prefix, $det_array, $count_array;

	if ($count>0) {
		$det_array[] = $item;
		$count_array[] = $count;

		$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where  (PID='".$item["ID"]."')");
		while($res = mysql_fetch_array($result)) {
			OpenID($res,$count*$res["COUNT"]);
		}
	}
}


$ID = $_GET["id"];

$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where (ID='".$ID."')");
$krz = mysql_fetch_array($result);
$zaknum = $krz["NAME"];

$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where (ID_krz2='".$ID."') and (PID='0')");
$det = mysql_fetch_array($result);
$count = $det["COUNT"];
if (isset($_POST["count"])) $count = $_POST["count"];
OpenID($det,$count);



if ($count==0) $count=1;
if ($count=="") $count=1;



//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>