<?php

$NDS_val = 18;

$print_H = "<H1>������������ ������ ������</H1>";

set_time_limit(60*10);
$doform = false;
if (isset($_GET["p0"])) $doform = true;
include "header.php";


function RVal($Name,$Val) {
	global $doform, $print_mode;	

	if (($doform) && ($print_mode!=="on")) {
		echo "<input class='colored' type='text' style='width: 100%;' name='".$Name."' value='".$Val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$Name."', event)\">";
	} else {
		echo $Val;
	}
}

function IVal($Name,$Val) {
	global $doform, $print_mode;	

	if (($doform) && ($print_mode!=="on")) {
		echo "<input class='colored' type='text' style='width: 100%;' name='".$Name."' value='".$Val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"IPFilter(this.form, '".$Name."', event)\">";
	} else {
		echo $Val;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "res_ID_krz2det.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

$ID_krz2 = $krz["ID"];;

$price_all = $krz["PRICE"];
if (isset($_POST["price_all"])) $price_all = $_POST["price_all"];
$price_all_nds = $price_all;
$price_all = $price_all*(100/(100+$NDS_val));

include "calc2norm.php";

include "table_start2.php";

include "calc2.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

include "footer.php";
?>