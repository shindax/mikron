<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

$det_array = [];
$count_array = [];

function OpenID($item,$count) 
{
	global $db_prefix, $det_array, $count_array;

	if ($count>0) 
	{
		$det_array[] = $item;
		$count_array[] = $count;

		$result = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where  (PID='".$item["ID"]."')");
		while($res = mysql_fetch_array($result)) 
		{
			OpenID($res,$count*$res["COUNT"]);
		}
	}
}


$ID = $_GET["id"];
$result = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where (ID='".$ID."')");
$det = mysql_fetch_array($result);
$count = $det["COUNT"];
if (isset($_POST["count"])) $count = $_POST["count"];
OpenID($det,$count);

$result = dbquery("SELECT * FROM ".$db_prefix."db_krz where (ID='".$det["ID_krz"]."')");
$krz = mysql_fetch_array($result);

$zaknum = $krz["NAME"];

$price = $krz["NORM_PRICE"];
if ($count==0) $count=1;
if ($count=="") $count=1;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>