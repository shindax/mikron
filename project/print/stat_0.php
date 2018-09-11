<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$date1 = $_GET["p0"];
	$date2 = $_GET["p1"];
	$pdate1 = DateToInt($date1);
	$pdate2 = DateToInt($date2);

	if (($pdate1>0) && ($pdate2>=$pdate1)) $step = 2;

	include "project/calc_zak.php";



if ($step==1) {


	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>План / факт расчёта Н/Ч в КРЗ 2 и заказах за период</h2>";

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>Параметр</td>";
	echo "<td>Значение</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>Дата начальная:</b></td><td class='rwField ntabg'>";
	Input("date","p0",TodayDate());
	echo "</td></tr>\n";

	echo "<tr><td class='Field first'><b>Дата конечная:</b></td><td class='rwField ntabg'>";
	Input("date","p1",TodayDate());
	echo "</td></tr>\n";


	echo "<tr><td class='Field first'><b>Полный перерасчёт (очень долго):</b></td><td class='rwField ntabg'>";
	Input("boolean","p2",0);
	echo "</td></tr>\n";

	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='Расчёт'></td></tr></table>";

}

if ($step==2) {

   // Шапка

	echo "<h2>План / факт расчёта Н/Ч в КРЗ 2 и заказах открытых в период:</h2>";
	echo "<h3>".$date1." - ".$date2."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td width='35' rowspan='2'>№</td>";
	echo "<td rowspan='2'>Заказ</td>";
	echo "<td width='70' rowspan='2'>План Н/Ч<br>в МТК</td>";
	echo "<td colspan='2'>Из СЗ</td>";
	echo "<td colspan='2'>Коэффициенты</td>";
	echo "<td width='80' rowspan='2'>№ КРЗ 2</td>";
	echo "<td width='70' rowspan='2'>План Н/Ч<br>в КРЗ 2</td>";
	echo "<td colspan='2'>Коэффициенты</td>";
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td width='70'>Факт Н/Ч</td>";
	echo "<td width='70'>Факт, ч</td>";
	echo "<td width='70'>План Н/Ч /<br> Факт ч</td>";
	echo "<td width='70'>Факт Н/Ч /<br> Факт ч</td>";
	echo "<td width='100'>План Н/Ч КРЗ. /<br> План Н/Ч зак.</td>";
	echo "<td width='100'>План Н/Ч КРЗ /<br> Факт ч зак.</td>";
	echo "</tr>\n";
	echo "	</thead>\n";

   // Расчёт

	$date1 = $pdate1;
	$date2 = $pdate2;

	$det_array = array();
	$count_array = array();

	function FReal($x) {
		$ret = number_format( $x, 2, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		return $ret;
	}

	function OpenCalcID($item,$count) {
		global $db_prefix, $det_array, $count_array;

		if ($count>0) {
			$det_array[] = $item;
			$count_array[] = $count;

			$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where  (PID='".$item["ID"]."')");
			while($res = mysql_fetch_array($result)) {
				OpenCalcID($res,$count*$res["COUNT"]);
			}
		}
	}

	function Summ($field) {
		global $det_array, $count_array;

		$res = 0;
		for ($j=0;$j < count($count_array);$j++) {
			$res = $res + ($det_array[$j][$field]*$count_array[$j]);
		}
		return $res;
	}

	function SummL($field) {
		global $det_array, $count_array;

		$res = 0;
		for ($j=0;$j < count($count_array);$j++) {
			$res = $res + $det_array[$j][$field];
		}
		return $res;
	}


	function GetKRZ2Norm($idkrz2) {
		global $db_prefix, $count_array, $det_array;

		$det_array = array();
		$count_array = array();

		$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where (PID='0') and (ID_krz2='".$idkrz2."')");
		if ($det = mysql_fetch_array($result)) {
			$count = $det["COUNT"]*1;
			OpenCalcID($det,$count);
		}

		$D1_1 = SummL("D1");
		$D1_2 = SummL("D2");
		$D1 = $D1_1+$D1_2;

		$D2_1 = Summ("D3");
		$D2_2 = Summ("D4");
		$D2_3 = Summ("D5");
		$D2_4 = Summ("D6");
		$D2_5 = Summ("D7");
		$D2_6 = Summ("D8");
		$D2_7 = Summ("D9");
		$D2_8 = Summ("D10");
		$D2_9 = SummL("D11");
		$D2 = $D2_1+$D2_2+$D2_3+$D2_4+$D2_5+$D2_6+$D2_7+$D2_8+$D2_9;

		return $D1+$D2;
	}

	$num = 0;


	//////////////////////////////////////////////////////////////////////////////////////////
	// Основной цикл
	//////////////////////////////////////////////////////////////////////////////////////////

	$s1 = 0;
	$s2 = 0;
	$s3 = 0;
	$s4 = 0;

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where  (ID_krz2>'0') and (CDATE>='".$date1."') and (CDATE<='".$date2."') order by ORD");
	while($zak = mysql_fetch_array($result)) {

		$num = $num+1;

		$krz_norm = GetKRZ2Norm($zak["ID_krz2"]);

		// Полный пересчёт заказа (очень долго)
		if ($_GET["p2"]=="on") CalculateZakaz($zak["ID"]);

		$zak_norm = $zak["SUMM_N"];
		$fact_norm = $zak["SUMM_NV"];
		$fact = $zak["SUMM_F"];

		$s1 = $s1 + $zak_norm;
		$s2 = $s2 + $fact_norm;
		$s3 = $s3 + $fact;
		$s4 = $s4 + $krz_norm;

		$coef_1 = "-";
		$coef_2 = "-";
		$coef_3 = "-";
		$coef_4 = "-";

		if ($fact>0) $coef_1 = FReal($zak_norm/$fact);
		if ($fact>0) $coef_2 = FReal($fact_norm/$fact);
		if ($zak_norm>0) $coef_3 = FReal($krz_norm/$zak_norm);
		if ($fact>0) $coef_4 = FReal($krz_norm/$fact);

	//////////////////////////////////////////////////////////////////////////////////////////

		echo "<tr>\n";
		echo "<td class='Field AC'>".$num."</td>";
		echo "<td class='Field'><b>".FVal($zak,"db_zak","TID")." ".FVal($zak,"db_zak","NAME")."</b><br>".$zak["DSE_NAME"]." - ".$zak["DSE_OBOZ"]."</td>";
		echo "<td class='Field AC'>".FReal($zak_norm)."</td>";
		echo "<td class='Field AC'>".FReal($fact_norm)."</td>";
		echo "<td class='Field AC'>".FReal($fact)."</td>";
		echo "<td class='Field AC'>".$coef_1."</td>";
		echo "<td class='Field AC'>".$coef_2."</td>";
		echo "<td class='Field'><b>".FVal($zak,"db_zak","ID_krz2")."</b></td>";
		echo "<td class='Field AC'>".FReal($krz_norm)."</td>";
		echo "<td class='Field AC'>".$coef_3."</td>";
		echo "<td class='Field AC'>".$coef_4."</td>";
		echo "</tr>\n";

	//////////////////////////////////////////////////////////////////////////////////////////

	}


   // Итого
		$coef_1 = "-";
		$coef_2 = "-";
		$coef_3 = "-";
		$coef_4 = "-";

		if ($s3>0) $coef_1 = FReal($s1/$s3);
		if ($s3>0) $coef_2 = FReal($s2/$s3);
		if ($s1>0) $coef_3 = FReal($s4/$s1);
		if ($s3>0) $coef_4 = FReal($s4/$s3);

		echo "<tr style='background: #eee;'>\n";
		echo "<td class='Field AR' colspan='2'><b>ИТОГО:</b></td>";
		echo "<td class='Field AC'><b>".FReal($s1)."</b></td>";
		echo "<td class='Field AC'><b>".FReal($s2)."</b></td>";
		echo "<td class='Field AC'><b>".FReal($s3)."</b></td>";
		echo "<td class='Field AC'><b>".$coef_1."</b></td>";
		echo "<td class='Field AC'><b>".$coef_2."</b></td>";
		echo "<td class='Field'></td>";
		echo "<td class='Field AC'><b>".FReal($s4)."</b></td>";
		echo "<td class='Field AC'><b>".$coef_3."</b></td>";
		echo "<td class='Field AC'><b>".$coef_4."</b></td>";
		echo "</tr>\n";

   // Конец
	
	echo "</table>\n";

	if ($_GET["p2"]=="on") echo "<br>* Посчитано с полным перерасчётом указанных заказов";
}
?>
