<style>
table.shablon {
}
table.shablon td {
	padding: 0px;
	vertical-align: top;
	text-align: left;
}
table.shablon td div.swin {
	border: 1px solid #a2c0eb;
	background: #fff;
	padding: 10px 5px 10px 7px;
	margin: 5px;
}
table.view {
	width: 100%;
	margin: 0px;
	padding: 0px;
}
.tab_1 {
	margin-left: 20px;
}
.tab_2 {
	margin-left: 40px;
}
.tab_3 {
	margin-left: 60px;
}
.mlink {
	line-height: 150%;
}
</style>
<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


	if ($print_mode == "off") echo "\n\n<!-- form -->\n<form id='form2' method='post' action='".$pageurl."'>\n";
	echo "<script type='text/javascript'>location.href='index.php?do=show&formid=117'</script>";
	if ($print_mode == "off") echo "\n</form>\n\n";
	if ($print_mode == "off") {
		echo "	<script type='text/javascript'>\n	$(\".rdtbl\").Headers({ fixedOffset : ".($top_offset)." });\n	</script>\n";
	}



?>