<style>
div.VCD {
	display: block;
	-o-transform: rotate(90deg);
	-moz-transform: rotate(90deg);
	-webkit-transform: rotate(90deg);
	writing-mode: tb-rl;
	font-height: 16px;
	padding: 0;
	margin: 0;
	height: 12px;
	width: 12px;
}
b div.VCD {
	font-weight: bold;
}
</style>
<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$date1 = $_GET["p0"];
	$date2 = $_GET["p1"];
	$pdate1 = DateToInt($date1);
	$pdate2 = DateToInt($date2);

	if (($pdate1>0) && ($pdate2>=$pdate1)) $step = 2;


///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

	function ConvertToVertical($str) {

		$res = "";
		for ($i=0;$i < strlen($str);$i++) {
			$xx = $str[$i];
			if ($xx==" ") $xx = "<br>";
			$res = $res."<div class='VCD'>".$xx."</div>";
		}
		return $res;
	}

	function FReal($x) {
		$ret = number_format( $x, 2, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		return $ret;
	}

	// План Н/ч, Факт Н/Ч, Затр. ч
	function OutNF($norm,$pnorm,$fact) {
		$res = FReal($norm)."<br>".FReal($pnorm)."<br>".FReal($fact);
		if ($res == "0<br>0<br>0") $res = "";
		return $res;
	}

///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////



if ($step==1) {

	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Отчёт по выработке за период</h2>";

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

	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='Расчёт'></td></tr></table>";
}

if ($step==2) {


   // РЕСУРСЫ - кто работал
   // Суммы работ по ресурсам/датам/сменам
   // Даты - которые были в СЗ

	$w_ids = Array();
	$w_n_s = Array();
	$w_nf_s = Array();
	$w_f_s = Array();
	$w_dates = Array();

	$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE >= '".$pdate1."') and (DATE <= '".$pdate2."') and (EDIT_STATE='1') order by DATE");
	while ($zad = mysql_fetch_array($yyy)) {

		if (!in_array($zad["DATE"],$w_dates)) $w_dates[] = $zad["DATE"];
		if (!in_array($zad["ID_resurs"],$w_ids)) $w_ids[] = $zad["ID_resurs"];

			$key = $zad["DATE"]."|".$zad["SMEN"]."|".$zad["ID_resurs"];

		$w_n_s[$key] = $w_n_s[$key]*1+$zad["NORM"]*1;
		$w_nf_s[$key] = $w_nf_s[$key]*1+$zad["NORM_FACT"]*1;
		$w_f_s[$key] = $w_f_s[$key]*1+$zad["FACT"]*1;

	}

   // Сортировка ресурсов по ФИО
	$ids = Array();
	$names = Array();
		
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary (NAME)");
	while ($yyy = mysql_fetch_array($xxx)) {
		if (in_array($yyy["ID"],$w_ids)) {
			$ids[] = $yyy["ID"];
			$names[] = $yyy["NAME"];
		}
	}


   // Шапка

	echo "<h2>Отчёт по выработке за период</h2>";
	echo "<h3>".$date1." - ".$date2."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 800px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td rowspan='2'>№</td>";
	echo "<td rowspan='2'>Фамилия И.О.</td>";
	echo "<td colspan='4'>План Н/Ч, факт. Н/Ч, Затр. Ч</td>";
	echo "<td rowspan='2'><u>Факт. Н/Ч</u><br>Затр. Н/Ч</td></tr><tr class='first'>";
	echo "<td>1 см.</td>";
	echo "<td>2 см.</td>";
	echo "<td>3 см.</td>";
	echo "<td>Итого</td>";
	echo "</tr>\n";
	echo "	</thead>\n";

// Вывод дат
	for ($i=0;$i < count($ids);$i++) {
		echo "<tr>\n";
		echo "<td class='Field' style='text-align:center; vertical-align: middle;'>".($i+1)."</td>\n";
		echo "<td class='Field' style='font-size:160%; text-align:center; vertical-align: middle;'>".$names[$i]."</td>\n";
		
		// смена 1
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}		
		echo "<td class='Field' style='text-align:center;'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n_1 = $ss_n_1 + $s_n;
		$ss_nf_1 = $ss_nf_1 + $s_nf;
		$ss_f_1 = $ss_f_1 + $s_f;
		$s_n_1 = $s_n;
		$s_nf_1 = $s_nf;
		$s_f_1 = $s_f;
		
		// смена 2
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|2|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}
		echo "<td class='Field' style='text-align:center;'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n_2 = $ss_n_2 + $s_n;
		$ss_nf_2 = $ss_nf_2 + $s_nf;
		$ss_f_2 = $ss_f_2 + $s_f;
		$s_n_1 = $s_n_1 + $s_n;
		$s_nf_1 = $s_nf_1 + $s_nf;
		$s_f_1 = $s_f_1 + $s_f;
		
		// смена 3
		$s_n = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|3|".$ids[$i];
			$s_n = $s_n + $w_n_s[$key]*1;
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}
		echo "<td class='Field' style='text-align:center;'><b>".OutNF($s_n,$s_nf,$s_f)."</b></td>\n";
		$ss_n_3 = $ss_n_3 + $s_n;
		$ss_nf_3 = $ss_nf_3 + $s_nf;
		$ss_f_3 = $ss_f_3 + $s_f;
		$s_n_1 = $s_n_1 + $s_n;
		$s_nf_1 = $s_nf_1 + $s_nf;
		$s_f_1 = $s_f_1 + $s_f;
		
		echo "<td class='Field' style='text-align:center;'><b>".OutNF($s_n_1,$s_nf_1,$s_f_1)."</b></td>\n";

		// коэфф
		$ss_nf = 0;
		$ss_f = 0;
		$s_nf = 0;
		$s_f = 0;
		for ($z=0;$z < count($w_dates);$z++) {
			$key = $w_dates[$z]."|1|".$ids[$i];
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|2|".$ids[$i];
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
			$key = $w_dates[$z]."|3|".$ids[$i];
			$s_nf = $s_nf + $w_nf_s[$key]*1;
			$s_f = $s_f + $w_f_s[$key]*1;
		}

			$coef = "~";
			if ($s_f>0) $coef = FReal($s_nf/$s_f);

		echo "<td class='Field' style='text-align:center; vertical-align: middle;'><b style='font-size:150%;'>".$coef."</b></td>\n";		
		echo "</tr>\n";
	}
	
	$coef = "~";
	$ss_n = $ss_n_1 + $ss_n_2 + $ss_n_3;
	$ss_nf = $ss_nf_1 + $ss_nf_2 + $ss_nf_3;
	$ss_f = $ss_f_1 + $ss_f_2 + $ss_f_3;
	if ($ss_f>0) $coef = FReal($ss_nf/$ss_f);
	
	// Итого
	echo "<tr>";
	echo "<td class='Field' style='vertical-align: middle;' colspan='2'><b style='float:right;'>Итого</b></td><td style='text-align:center;' class='Field'><b>".OutNF($ss_n_1,$ss_nf_1,$ss_f_1)."</b></td>";
	echo "<td class='Field' style='text-align:center;'><b>".OutNF($ss_n_2,$ss_nf_2,$ss_f_2)."</b></td>";
	echo "<td class='Field' style='text-align:center;'><b>".OutNF($ss_n_3,$ss_nf_3,$ss_f_3)."</b></td>";
	echo "<td class='Field' style='text-align:center;'><b>".OutNF($ss_n,$ss_nf,$ss_f)."</b></td>";
	echo "<td class='Field' style='vertical-align: middle; text-align:center;'><b style='font-size:150%;'>".$coef."</b></td>";
	echo "</tr><tr><td colspan='2'></td>
	<td class='Field' style='vertical-align: middle; text-align:center;'><b style='font-size:150%;'>".round(($ss_nf_1/$ss_f_1),2)."</b></td>
	<td class='Field' style='vertical-align: middle; text-align:center;'><b style='font-size:150%;'>".round(($ss_nf_2/$ss_f_2),2)."</b></td>
	<td class='Field' style='vertical-align: middle; text-align:center;'><b style='font-size:150%;'>".round(($ss_nf_3/$ss_f_3),2)."</b></td>
	<td class='Field' style='vertical-align: middle; text-align:center;'><b style='font-size:150%;'>".$coef."</b></td>
	</tr>";
	echo "</table>";


	echo "<br><br><b>* Формат данных:</b><br><br><div style='margin-left: 30px;'>План Н/ч<br>Факт Н/Ч<br>Затр. ч</div>";
}
?>