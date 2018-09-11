<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	echo "
	<style>
		td.HAND {cursor: hand;}
	</style>
	";

	function OutTableRows($n, $table) {
		global $db_cfg, $loc;

		echo "<tr>";
		echo "<td class='Field AL HAND' onClick='ShowHide(\"".$n.$table."\");'>".($n+1)."</td>";
		echo "<td class='Field AL HAND' onClick='ShowHide(\"".$n.$table."\");'>".$table."</td>";
		echo "<td class='Field AL'><a href='get_dbcsv.php?db=".$table."' target='_blank'><img src='uses/ftypes/csv.png'></a></td>";
		echo "<td class='Field AL HAND' onClick='ShowHide(\"".$n.$table."\");'>".$db_cfg[$table."|TYPE"]."</td>";
		echo "<td class='Field AL'>".$db_cfg[$table."|MORE"]."<div id='".$n.$table."' style='display: none;'><br><div style='padding: 0px; margin: 0px; width: 100%; border-top:1px solid black;'></div><table style='width: 100%;'><tr><td style='vertical-align: top;'>";

			echo "<br>".$loc["dbconf8"].":<br><br><table style='margin-left: 30px;'>";
			echo "<tr><td width='120'>ID</td><td></td></tr>";
			if ($db_cfg[$table."|TYPE"]=="tree") echo "<tr><td>PID</td><td> => ".$table.".ID</td></tr>";
			if ($db_cfg[$table."|TYPE"]=="ltree") echo "<tr><td>PID</td><td> => ".$table.".ID</td></tr>";
			if ($db_cfg[$table."|TYPE"]=="ltree") echo "<tr><td>LID</td><td> => ".$table.".ID</td></tr>";
			echo "\n";

			$db_fields = explode("|",$db_cfg[$table."|FIELDS"]);
			for ($i=0;$i < count($db_fields);$i++) {
				echo "<tr><td>".$db_fields[$i]."</td><td>".$db_cfg[$table."/".$db_fields[$i]];
				if ($db_cfg[$table."/".$db_fields[$i]] == "list") echo " => ".$db_cfg[$table."/".$db_fields[$i]."|LIST"].".ID";
				if ($db_cfg[$table."/".$db_fields[$i]] == "droplist") echo " => ".$db_cfg[$table."/".$db_fields[$i]."|LIST"].".ID";
				if ($db_cfg[$table."/".$db_fields[$i]] == "multilist") echo " => ".$db_cfg[$table."/".$db_fields[$i]."|LIST"].".ID";
				echo "</td></tr>";
			}

			echo "</table>";

		echo "</td><td style='width: 50%; vertical-align: top;'>";

			echo "<br>".$loc["dbconf10"].": <pre>".str_replace("|","\n          ",$db_cfg[$table."|USEDIN"])."</pre>";

		echo "</td></tr></table></div></td>";
		echo "</tr>";
	}

////////////////////////////////////////////////////////////////////////////

	echo "<h2>".$loc["dbconf3"]."</h2>";

	echo "<h3>".$loc["dbconf1"]."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td width='30'>¹</td>";
	echo "<td width='250'>".$loc["dbconf4"]."</td>";
	echo "<td width='30'>".$loc["dbconf7"]."</td>";
	echo "<td width='60'>".$loc["dbconf5"]."</td>";
	echo "<td>".$loc["dbconf6"]."</td>";
	echo "</tr>\n";
	echo "	</thead>\n";


/////////////////////////////////////////////////////////////////////////////

	$tables = explode("|",$db_cfg["SYSTEM"]);
	for ($j=0;$j < count($tables);$j++) OutTableRows($j, $tables[$j]);

/////////////////////////////////////////////////////////////////////////////

	echo "</table>";

	echo "<h3>".$loc["dbconf2"]."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td width='30'>¹</td>";
	echo "<td width='250'>".$loc["dbconf4"]."</td>";
	echo "<td width='30'>".$loc["dbconf7"]."</td>";
	echo "<td width='60'>".$loc["dbconf5"]."</td>";
	echo "<td>".$loc["dbconf6"]."</td>";
	echo "</tr>\n";
	echo "	</thead>\n";


/////////////////////////////////////////////////////////////////////////////

	$tables = explode("|",$db_cfg["PROJECT"]);
	for ($j=0;$j < count($tables);$j++) OutTableRows($j, $tables[$j]);

/////////////////////////////////////////////////////////////////////////////

	echo "</table>";
?>
