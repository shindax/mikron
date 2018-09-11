<?php

	function OutDates() {
		global $today, $days_count, $days;

		for ($j=0;$j < $days_count;$j++) {
			$date = $days[$j];
			$hg = "";
			if (WWDate($date)=="Вс") $hg = "style='background: #ff56bc URL(img/bgtop3.gif) repeat-x;'";
			if ($date==$today) $hg = "style='background: #ffd560 URL(img/bgtop2.gif) repeat-x;'";
			echo "<td class='GNT' $hg><span>".OutDate($date)."</span></td>";
		}
		echo "<td class='GNT'><span>&nbsp;</span></td>";
	}

echo "<tr>";
OutDates();
echo "</tr>";

?>