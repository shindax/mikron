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


// Вывод таблицы

	echo "<h2>".$loc["p1"]."</h2><br>\n";

	echo "<form method='post'>\n";
	echo "<input type='hidden' name='EditProfile' value='ok'>\n";
	echo "<table style='width: 700px; margin-left: 20px;' border='0' cellpadding='0' cellspacing='0'>\n";

	echo "<tr>\n";
		echo "<td style='text-align: left; width: 250px;'>".$loc["p2"]."</td>\n";
		echo "<td style='text-align: left;'><b>".$user["LOGIN"]."</b></td>\n";
	echo "</tr>\n";

	echo "<tr><td>&nbsp;</td><td></td></tr>\n";

	echo "<tr>\n";
		echo "<td style='text-align: left;'>".$loc["p3"]."</td>\n";
		echo "<td><input type='password' style='width: 100%;' name='LastPASS' value=''></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td style='text-align: left;'>".$loc["p4"]."</td>\n";
		echo "<td><input type='password' style='width: 100%;' name='NewPASS' value=''></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td style='text-align: left;'>".$loc["p5"]."</td>\n";
		echo "<td><input type='password' style='width: 100%;' name='NewPASS2' value=''></td>\n";
	echo "</tr>\n";

	echo "<tr><td>&nbsp;</td><td></td></tr>\n";

	$io = $user["IO"];
	if (isset($_POST["IO"])) $io = $_POST["IO"];

	echo "<tr>\n";
		echo "<td style='text-align: left;'>".$loc["p6"]."</td>\n";
		echo "<td><input type='text' style='width: 100%;' name='IO' value='".$io."'></td>\n";
	echo "</tr>\n";

	echo "<tr><td>&nbsp;</td><td></td></tr>\n";

	echo "<tr>\n";
		echo "<td style='text-align: left;'></td>\n";
		echo "<td style='text-align: left;'><input type='submit' name='ok' value='".$loc["p7"]."'></td>\n";
	echo "</tr>\n";

	echo "</table><br><br>\n";
	echo "</form>\n";

}

?>