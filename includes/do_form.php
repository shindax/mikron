<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 ÃËÓ¯ÌËÍÓ‚ ¿.¬.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if ($user["ID"]=="1") {

$xxx = dbquery("SELECT * FROM ".$db_prefix."forms where (ID='".$id."')");
if ($item = mysql_fetch_array($xxx)) {


   // «¿√ŒÀŒ¬Œ  ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["ff1"]."</h2>\n";

	echo "<span class='line'><b>".$loc["ff2"].":</b> ".$item["NAME"]."</span><br>\n";
	echo "<span class='line'><b>formid:</b> ".$item["ID"]."</span><br><br>\n";

   // ‘Œ–Ã¿ ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

	echo "<table class='tbl' style='width: 1100px;' border='0' cellpadding='0' cellspacing='0'>\n";

   // ÿ¿œ ¿ “¿¡À»÷€ ///////////////////////////////////////////////////////////////
	echo "<tr class='first'>\n";
	echo "<td colspan='2'>".$loc["ff4"]."</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field first' width='250'>".$loc["ff5"]."</td>";
	Field($item,"forms","USEFILE",true,"","","");
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field first' width='250'>".$loc["ff7"]."</td>";
	Field($item,"forms","FILE",true,"","","");
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td colspan='2'>".$loc["ff6"]."</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	Field($item,"forms","PATTERN",true,"","","colspan='2'");
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td colspan='2'>".$loc["ff3"]."</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	Field($item,"forms","HLP",true,"","","colspan='2'");
	echo "</tr>\n";
	echo "</table>\n";

   // ‘Œ–Ã¿ ///////////////////////////////////////////////////////////////////////
	echo "</form>\n";

}
}

?>