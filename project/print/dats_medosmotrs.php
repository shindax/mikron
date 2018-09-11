<style>
div.VCD {
display: block;
-o-transform: rotate(90deg);
-moz-transform: rotate(90deg);
-webkit-transform: rotate(90deg);
font-height: 16px;
padding: 0;
margin: 0;
height: 12px;
width: 12px;
}
</style>
<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";
	echo "<input type='hidden' name='p1' value='".$_GET["p1"]."'>";
	echo "<input type='hidden' name='p2' value='".$_GET["p2"]."'>";


	echo "<h2>Даты следующего медосмотра</h2>";
		
		echo "<table class='rdtbl tbl' style='width: 500px;' cellpadding='0' cellspacing='0'><tbody><tr class='first'>
		<td>ФИО</td><td>Дата<br>приёма</td><td>Дата послед.<br>медосмотра</td><td>Дата след.<br>медосмотра</td>
		</tr>";
		
		$arr_dats = array();
		$yyy = dbquery("SELECT NAME, DATE_LMO, DATE_NMO, DATE_FROM FROM ".$db_prefix."db_resurs where TID='0' order by DATE_NMO, NAME");
		while ($xxx = mysql_fetch_array($yyy)) {
				echo "<tr>
				<td width='250px' class='Field'>".$xxx['NAME']."</td>
				<td width='100px' class='Field'>".substr($xxx['DATE_FROM'],6,2).".".substr($xxx['DATE_FROM'],4,2).".".substr($xxx['DATE_FROM'],0,4)."</td>
				<td width='100px' class='Field'>".substr($xxx['DATE_LMO'],6,2).".".substr($xxx['DATE_LMO'],4,2).".".substr($xxx['DATE_LMO'],0,4)."</td>";
				$color = "";
				$YYYY = "";
				if (substr($xxx['DATE_NMO'],0,4) < date("Y")) $color = "style='color:#ff0000;font-size:130%;'";
				if ((substr($xxx['DATE_NMO'],0,4) == date("Y")) and (substr($xxx['DATE_NMO'],4,2) < date("m"))) $color = "style='color:#ff0000;font-size:130%;'";
				if ((substr($xxx['DATE_NMO'],0,4) == date("Y")) and (substr($xxx['DATE_NMO'],4,2) == date("m")) and (substr($xxx['DATE_NMO'],6,2) < date("d"))) $color = "style='color:#ff0000;font-size:130%;'";
				echo "<td width='100px' ".$color." class='Field'>".substr($xxx['DATE_NMO'],6,2).".".substr($xxx['DATE_NMO'],4,2).".".substr($xxx['DATE_NMO'],0,4).$YYYY."</td>
				</tr>";
		}
		
		echo "</tbody></table>";

?>
