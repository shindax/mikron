<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if ($logged) {
if ($do=="show") {
if (showform_check($showed_form)) {

	include "rendering.php";

	$event_link = "<a href='".$pageurl."&event'  title='".$loc["11"]."'><img class='nav' src='style/refresh.png'></a>";

	if ($showed_form["USEFILE"]==1) {
		if ($print_mode == "off") echo "\n\n<!-- form -->\n<form id='form1' method='post' action='".$pageurl."'>\n";
		include "./project/".$showed_form["FILE"];
		if ($print_mode == "off") echo "\n</form>\n\n";
	}

	$pattern = $showed_form["PATTERN"];

	if ($print_mode == "off") echo "\n\n<!-- form -->\n<form id='form2' method='post' action='".$pageurl."'>\n";
	$pattern = OutReplacer($pattern);
	eval(" ?>".$pattern."<?php ");
	if ($print_mode == "off") echo "\n</form>\n\n";
	if ($print_mode == "off") {
		echo "	<script type='text/javascript'>\n	$(\".rdtbl\").Headers({ fixedOffset : ".($top_offset)." });\n	</script>\n";
	}

	echo "	<script type='text/javascript'>\n	document.title = \"".$title."\";\n	</script>\n";

	include "do_show_allpage.php";

}
}
}

?>