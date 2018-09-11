<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 ÃËÓ¯ÌËÍÓ‚ ¿.¬.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if ($user["USERSEDIT"]=="1") {

$xxx = dbquery("SELECT * FROM ".$db_prefix."users where (ID='".$id."')");
if ($item = mysql_fetch_array($xxx)) {


   // «¿√ŒÀŒ¬Œ  ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["r1"]."</h2>\n";

	echo "<span class='line'><h4>".$loc["r2"].": ".$item["LOGIN"]." - ".$item["FIO"]."</h4></span><br><br>";


   // ‘Œ–Ã¿ ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

   // ÿ¿œ ¿ “¿¡À»÷€ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 800px;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>".$loc["r3"]."</td>\n";
	echo "<td>".$loc["r4"]."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	Field($item,"users","ID_forms",true,"","","");
	Field($item,"users","ID_rightgroups",true,"","","");
	echo "</tr>\n";
	echo "</table>\n";

   // ‘Œ–Ã¿ ///////////////////////////////////////////////////////////////////////
	echo "</form><br><br>\n";

}
}

?>