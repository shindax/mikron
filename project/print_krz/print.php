<script type="text/javascript"> 
if(window.jQuery==undefined) { 
document.write(unescape("%3Cscript src='/uses/jquery.js' type='text/javascript'%3E%3C/script%3E"));
} 
</script>


<?php
$print_H = "<H1>Коммерческий расчёт заказа</H1>";

set_time_limit(60*10);
$doform = false;
if (isset($_GET["p0"])) $doform = true;
include "header.php";


function RVal($Name,$Val) 
{
	global $doform, $print_mode;	

	if (( $doform ) && ( $print_mode !== "on" )) 
		echo "<input class='colored' type='text' style='width: 100%;' name='".$Name."' value='".$Val."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, '".$Name."', event)\">";
      else 
        echo $Val;
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

	include "res_ID_krzdet.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


if (isset($_POST["price"])) $price = $_POST["price"];

include "table_start.php";

include "calc.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

include "footer.php";
