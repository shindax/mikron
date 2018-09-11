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

$xxx = dbquery("SELECT * FROM ".$db_prefix."formsitem where (ID='".$id."')");
if ($item = mysql_fetch_array($xxx)) {


   // «¿√ŒÀŒ¬Œ  ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["ffi1"]."</h2>\n";

	echo "<span class='line'><b>".$loc["ffi2"].":</b> ".$item["NAME"]."</span><br>\n";

	echo "<span class='line'><b>ID:</b> ".$item["ID"]."</span><br><br>\n";

   // ‘Œ–Ã¿ ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

	echo "<table class='tbl' style='width: 1100px;' border='0' cellpadding='0' cellspacing='0'>\n";

   // ÿ¿œ ¿ “¿¡À»÷€ ///////////////////////////////////////////////////////////////
	echo "<tr class='first'>\n";
	echo "<td colspan='2'>".$loc["ffi3"]."</td>\n";
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first' width='200'>".$loc["ffi4"]."</td>";
	Field($item,"formsitem","TID",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi12"]."</td>";
	Field($item,"formsitem","WIDTH",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi13"]."</td>";
	Field($item,"formsitem","COLSPAN",true,"","","");
	echo "</tr>\n";

//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi5"]."</td>";
	Field($item,"formsitem","HEADER",true,"","","");
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td colspan='2'>".$loc["ffi6"]."</td>\n";
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi8"]."</td>";
	Field($item,"formsitem","DB_TABLE_1",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi9"]."</td>";
	Field($item,"formsitem","FIELD_1",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi11"]."</td>";
	Field($item,"formsitem","OPENID_1",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi14"]."</td>";
	Field($item,"formsitem","SQL_1",true,"","","");
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td colspan='2'>".$loc["ffi7"]."</td>\n";
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi8"]."</td>";
	Field($item,"formsitem","DB_TABLE_2",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi10"]."</td>";
	Field($item,"formsitem","FIELD_2",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi11"]."</td>";
	Field($item,"formsitem","OPENID_2",true,"","","");
	echo "</tr>\n";
//
	echo "<tr>\n";
	echo "<td class='Field first'>".$loc["ffi14"]."</td>";
	Field($item,"formsitem","SQL_2",true,"","","");
	echo "</tr>\n";

	echo "</table>\n";

   // ‘Œ–Ã¿ ///////////////////////////////////////////////////////////////////////
	echo "</form>\n";

}
}

?>