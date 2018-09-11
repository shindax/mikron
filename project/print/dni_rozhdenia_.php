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

	$step = 1;

	$date1 = $_GET["p1"];
	$pdate1 = substr($date1,5,2);

	if ($pdate1>0) $step = 2;

if ($step==1) {


	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Дни рождения персонала</h2>";

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>Параметр</td>";
	echo "<td>Значение</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>Выберите нужный месяц, любой день</b></td><td class='rwField ntabg'>";
	echo "<input name='p1' type='date'>";
	//Input("date","p1",TodayDate());
	echo "</td></tr>\n";
	
	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='Показать'></td></tr></table>";

}


if ($step==2) {


	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";
	echo "<input type='hidden' name='p1' value='".$_GET["p1"]."'>";
	echo "<input type='hidden' name='p2' value='".$_GET["p2"]."'>";


	echo "<h2>Дни рождения персонала</h2>";
		
		echo "<table class='rdtbl tbl' style='width: 500px;' cellpadding='0' cellspacing='0'><tbody><tr class='first'>
		<td>Дата рождения</td><td>ФИО</td><td>Сколько<br>исполняется</td>
		</tr>";
		
		$arr_dats = array();
		$yyy = dbquery("SELECT FF, II, OO, DATE, DATE_FROM FROM ".$db_prefix."db_resurs where TID='0' order by RIGHT(DATE,2),NAME");
		while ($xxx = mysql_fetch_array($yyy)) {
			if($pdate1==substr($xxx['DATE'],4,2)) {
				echo "<tr>
				<td width='100px' class='Field'>".substr($xxx['DATE'],6,2).".".substr($xxx['DATE'],4,2).".".substr($xxx['DATE'],0,4)."</td>
				<td width='250px' class='Field'>".$xxx['FF']." ".$xxx['II']." ".$xxx['OO']."</td>";
				$txt = " лет";
				if ((date("Y")-substr($xxx['DATE'],0,4)) > 9) {
					if ((substr((date("Y")-substr($xxx['DATE'],0,4)),1,1) == 3) or (substr((date("Y")-substr($xxx['DATE'],0,4)),1,1) == 2) or (substr((date("Y")-substr($xxx['DATE'],0,4)),1,1) == 4)) $txt = " года";
				}
				echo "<td width='50px' class='Field'>".(date("Y")-substr($xxx['DATE'],0,4)).$txt."</td>
				</tr>";
			}
		}
		
		echo "</tbody></table>";

}

?>
