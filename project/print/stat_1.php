<style>
div.VCD {
	display: block;
	-o-transform: rotate(270deg);
	-moz-transform: rotate(270deg);
	-webkit-transform: rotate(270deg);
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
			$xx = $str[strlen($str)-$i-1];
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


	echo "<h2>Отчёт по ресурсам / заказам за период</h2>";

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
   // Суммы работ по ресурсам/заказам
   // Заказы - которые были в СЗ

	$w_ids = Array();
	$w_zn_s = Array();
	$w_znf_s = Array();
	$w_zf_s = Array();
	$w_zak_ids = Array();

	$w_z_1_n = Array();
	$w_z_1_nf = Array();
	$w_z_1_f = Array();

	$w_z_2_n = Array();
	$w_z_2_nf = Array();
	$w_z_2_f = Array();

	$w_z_3_n = Array();
	$w_z_3_nf = Array();
	$w_z_3_f = Array();

	$w_z_all_n = Array();
	$w_z_all_nf = Array();
	$w_z_all_f = Array();

	$w_i_1_n = Array();
	$w_i_1_nf = Array();
	$w_i_1_f = Array();

	$w_i_2_n = Array();
	$w_i_2_nf = Array();
	$w_i_2_f = Array();

	$w_i_3_n = Array();
	$w_i_3_nf = Array();
	$w_i_3_f = Array();

	$w_i_all_n = Array();
	$w_i_all_nf = Array();
	$w_i_all_f = Array();

	$sm1d = Array();
	$sm2d = Array();
	$sm3d = Array();

	$ddd = Array();

	$sm1 = Array();
	$sm2 = Array();
	$sm3 = Array();

	$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE >= '".$pdate1."') and (DATE <= '".$pdate2."') and (EDIT_STATE='1') order by DATE");
	while ($zad = mysql_fetch_array($yyy)) {

		if (!in_array($zad["DATE"],$ddd)) $ddd[] = $zad["DATE"];

		if (!in_array($zad["ID_resurs"],$w_ids)) $w_ids[] = $zad["ID_resurs"];
		if (!in_array($zad["ID_zak"],$w_zak_ids)) $w_zak_ids[] = $zad["ID_zak"];

		$w_zn_s[$zad["ID_zak"]."|".$zad["ID_resurs"]] = $w_zn_s[$zad["ID_zak"]."|".$zad["ID_resurs"]]*1+$zad["NORM"]*1;
		$w_znf_s[$zad["ID_zak"]."|".$zad["ID_resurs"]] = $w_znf_s[$zad["ID_zak"]."|".$zad["ID_resurs"]]*1+$zad["NORM_FACT"]*1;
		$w_f_s[$zad["ID_zak"]."|".$zad["ID_resurs"]] = $w_f_s[$zad["ID_zak"]."|".$zad["ID_resurs"]]*1+$zad["FACT"]*1;

		$w_z_all_n[$zad["ID_zak"]] = $w_z_all_n[$zad["ID_zak"]]*1+$zad["NORM"]*1;
		$w_z_all_nf[$zad["ID_zak"]] = $w_z_all_nf[$zad["ID_zak"]]*1+$zad["NORM_FACT"]*1;
		$w_z_all_f[$zad["ID_zak"]] = $w_z_all_f[$zad["ID_zak"]]*1+$zad["FACT"]*1;

		$w_i_all_n[$zad["ID_resurs"]] = $w_i_all_n[$zad["ID_resurs"]]*1+$zad["NORM"]*1;
		$w_i_all_nf[$zad["ID_resurs"]] = $w_i_all_nf[$zad["ID_resurs"]]*1+$zad["NORM_FACT"]*1;
		$w_i_all_f[$zad["ID_resurs"]] = $w_i_all_f[$zad["ID_resurs"]]*1+$zad["FACT"]*1;

		if ($zad["SMEN"]*1==1) {
			$w_z_1_n[$zad["ID_zak"]] = $w_z_1_n[$zad["ID_zak"]]*1+$zad["NORM"]*1;
			$w_z_1_nf[$zad["ID_zak"]] = $w_z_1_nf[$zad["ID_zak"]]*1+$zad["NORM_FACT"]*1;
			$w_z_1_f[$zad["ID_zak"]] = $w_z_1_f[$zad["ID_zak"]]*1+$zad["FACT"]*1;

			$w_i_1_n[$zad["ID_resurs"]] = $w_i_1_n[$zad["ID_resurs"]]*1+$zad["NORM"]*1;
			$w_i_1_nf[$zad["ID_resurs"]] = $w_i_1_nf[$zad["ID_resurs"]]*1+$zad["NORM_FACT"]*1;
			$w_i_1_f[$zad["ID_resurs"]] = $w_i_1_f[$zad["ID_resurs"]]*1+$zad["FACT"]*1;

			$sm1d[$zad["ID_resurs"]."|".$zad["DATE"]] = 1;
		}

		if ($zad["SMEN"]*1==2) {
			$w_z_2_n[$zad["ID_zak"]] = $w_z_2_n[$zad["ID_zak"]]*1+$zad["NORM"]*1;
			$w_z_2_nf[$zad["ID_zak"]] = $w_z_2_nf[$zad["ID_zak"]]*1+$zad["NORM_FACT"]*1;
			$w_z_2_f[$zad["ID_zak"]] = $w_z_2_f[$zad["ID_zak"]]*1+$zad["FACT"]*1;

			$w_i_2_n[$zad["ID_resurs"]] = $w_i_2_n[$zad["ID_resurs"]]*1+$zad["NORM"]*1;
			$w_i_2_nf[$zad["ID_resurs"]] = $w_i_2_nf[$zad["ID_resurs"]]*1+$zad["NORM_FACT"]*1;
			$w_i_2_f[$zad["ID_resurs"]] = $w_i_2_f[$zad["ID_resurs"]]*1+$zad["FACT"]*1;

			$sm2d[$zad["ID_resurs"]."|".$zad["DATE"]] = 1;
		}

		if ($zad["SMEN"]*1==3) {
			$w_z_3_n[$zad["ID_zak"]] = $w_z_3_n[$zad["ID_zak"]]*1+$zad["NORM"]*1;
			$w_z_3_nf[$zad["ID_zak"]] = $w_z_3_nf[$zad["ID_zak"]]*1+$zad["NORM_FACT"]*1;
			$w_z_3_f[$zad["ID_zak"]] = $w_z_3_f[$zad["ID_zak"]]*1+$zad["FACT"]*1;

			$w_i_3_n[$zad["ID_resurs"]] = $w_i_3_n[$zad["ID_resurs"]]*1+$zad["NORM"]*1;
			$w_i_3_nf[$zad["ID_resurs"]] = $w_i_3_nf[$zad["ID_resurs"]]*1+$zad["NORM_FACT"]*1;
			$w_i_3_f[$zad["ID_resurs"]] = $w_i_3_f[$zad["ID_resurs"]]*1+$zad["FACT"]*1;

			$sm3d[$zad["ID_resurs"]."|".$zad["DATE"]] = 1;
		}
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

   // Сортировка заказов
	$z_ids = Array();
	$z_names = Array();
	$xxx = dbquery("SELECT ID, TID, NAME, DSE_NAME FROM ".$db_prefix."db_zak order by ORD");
	while ($yyy = mysql_fetch_array($xxx)) {
		if (in_array($yyy["ID"],$w_zak_ids)) {
			$z_ids[] = $yyy["ID"];
			$z_names[] = "<b>".FVal($yyy,"db_zak","TID")." ".$yyy["NAME"]."</b><br>".$yyy["DSE_NAME"];
		}
	}


   // Подсчёт смен явка
	for ($i=0;$i < count($ids);$i++) {
	for ($j=0;$j < count($ddd);$j++) {
		if ($sm1d[$ids[$i]."|".$ddd[$j]]*1 == 1) $sm1[$ids[$i]] = 1 + 1*$sm1[$ids[$i]];
		if ($sm2d[$ids[$i]."|".$ddd[$j]]*1 == 1) $sm2[$ids[$i]] = 1 + 1*$sm2[$ids[$i]];
		if ($sm3d[$ids[$i]."|".$ddd[$j]]*1 == 1) $sm3[$ids[$i]] = 1 + 1*$sm3[$ids[$i]];
	}
	}


   // Шапка

	echo "<h2>Отчёт по ресурсам / заказам за период</h2>";
	echo "<h3>".$date1." - ".$date2."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td>Заказ</td>";
	for ($i=0;$i < count($ids);$i++) {
		echo "<td style='vertical-align: top;'>".ConvertToVertical($names[$i])."</td>";
	}
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("Итого 1 смена:")."</b></td>";
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("Итого 2 смена:")."</b></td>";
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("Итого 3 смена:")."</b></td>";
	echo "<td style='vertical-align: top;'><b>".ConvertToVertical("ИТОГО:")."</b></td>";
	echo "</tr>\n";
	echo "	</thead>\n";

   // Явка 1 смена
	echo "<tr>\n";
	echo "<td class='Field'><b>Явка 1 смена:</b></td>\n";
	$summ = 0;
	for ($i=0;$i < count($ids);$i++) {
		echo "<td class='Field AC'>".$sm1[$ids[$i]]."</td>\n";
		$summ = $summ + $sm1[$ids[$i]]*1;
	}
	echo "<td class='Field AC'>".$summ."</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	for ($i=0;$i < count($ids);$i++) $summ = $summ + $sm2[$ids[$i]]*1;
	for ($i=0;$i < count($ids);$i++) $summ = $summ + $sm3[$ids[$i]]*1;
	echo "<td class='Field AC' rowspan='3' style='vertical-align: middle;'><b>".$summ."</b></td>\n";
	echo "</tr>\n";

   // Явка 2 смена
	echo "<tr>\n";
	echo "<td class='Field'><b>Явка 2 смена:</b></td>\n";
	$summ = 0;
	for ($i=0;$i < count($ids);$i++) {
		echo "<td class='Field AC'>".$sm2[$ids[$i]]."</td>\n";
		$summ = $summ + $sm2[$ids[$i]]*1;
	}
	echo "<td class='Field'></td>\n";
	echo "<td class='Field AC'>".$summ."</td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

   // Явка 3 смена
	echo "<tr>\n";
	echo "<td class='Field'><b>Явка 3 смена:</b></td>\n";
	$summ = 0;
	for ($i=0;$i < count($ids);$i++) {
		echo "<td class='Field AC'>".$sm3[$ids[$i]]."</td>\n";
		$summ = $summ + $sm3[$ids[$i]]*1;
	}
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field AC'>".$summ."</td>\n";
	echo "</tr>\n";

   // Вывод заказов
	for ($z=0;$z < count($z_ids);$z++) {
		echo "<tr>\n";
		echo "<td class='Field'>".$z_names[$z]."</td>\n";
		for ($i=0;$i < count($ids);$i++) {
			echo "<td class='Field'>".OutNF($w_zn_s[$z_ids[$z]."|".$ids[$i]],$w_znf_s[$z_ids[$z]."|".$ids[$i]],$w_f_s[$z_ids[$z]."|".$ids[$i]])."</td>\n";
		}
		echo "<td class='Field'>".OutNF($w_z_1_n[$z_ids[$z]],$w_z_1_nf[$z_ids[$z]],$w_z_1_f[$z_ids[$z]])."</td>\n";
		echo "<td class='Field'>".OutNF($w_z_2_n[$z_ids[$z]],$w_z_2_nf[$z_ids[$z]],$w_z_2_f[$z_ids[$z]])."</td>\n";
		echo "<td class='Field'>".OutNF($w_z_3_n[$z_ids[$z]],$w_z_3_nf[$z_ids[$z]],$w_z_3_f[$z_ids[$z]])."</td>\n";
		echo "<td class='Field'><b>".OutNF($w_z_all_n[$z_ids[$z]],$w_z_all_nf[$z_ids[$z]],$w_z_all_f[$z_ids[$z]])."</b></td>\n";
		echo "</tr>\n";
	}

   // Вывод итогов 1 смена
	echo "<tr>\n";
	echo "<td class='Field'><b>Итого 1 смена:</b></td>\n";
		$sn = 0;
		$snf = 0;
		$sf = 0;
	for ($i=0;$i < count($ids);$i++) {
		echo "<td class='Field'>".OutNF($w_i_1_n[$ids[$i]],$w_i_1_nf[$ids[$i]],$w_i_1_f[$ids[$i]])."</td>\n";
		$sn = $sn + $w_i_1_n[$ids[$i]];
		$snf = $snf + $w_i_1_nf[$ids[$i]];
		$sf = $sf + $w_i_1_f[$ids[$i]];
	}
			$coef = "~";
			if ($sf>0) $coef = FReal($snf/$sf);
	echo "<td class='Field AC' colspan='4'><table><tr><td class='AC'><b>".OutNF($sn,$snf,$sf)."</b></td><td class='AC'><b>".$coef."</b></td></tr></table></td>\n";
	echo "</tr>\n";

   // Вывод итогов 2 смена
	echo "<tr>\n";
	echo "<td class='Field'><b>Итого 2 смена:</b></td>\n";
		$sn = 0;
		$snf = 0;
		$sf = 0;
	for ($i=0;$i < count($ids);$i++) {
		echo "<td class='Field'>".OutNF($w_i_2_n[$ids[$i]],$w_i_2_nf[$ids[$i]],$w_i_2_f[$ids[$i]])."</td>\n";
		$sn = $sn + $w_i_2_n[$ids[$i]];
		$snf = $snf + $w_i_2_nf[$ids[$i]];
		$sf = $sf + $w_i_2_f[$ids[$i]];
	}
			$coef = "~";
			if ($sf>0) $coef = FReal($snf/$sf);
	echo "<td class='Field AC' colspan='4'><table><tr><td class='AC'><b>".OutNF($sn,$snf,$sf)."</b></td><td class='AC'><b>".$coef."</b></td></tr></table></td>\n";
	echo "</tr>\n";

   // Вывод итогов 3 смена
	echo "<tr>\n";
	echo "<td class='Field'><b>Итого 3 смена:</b></td>\n";
		$sn = 0;
		$snf = 0;
		$sf = 0;
	for ($i=0;$i < count($ids);$i++) {
		echo "<td class='Field'>".OutNF($w_i_3_n[$ids[$i]],$w_i_3_nf[$ids[$i]],$w_i_3_f[$ids[$i]])."</td>\n";
		$sn = $sn + $w_i_3_n[$ids[$i]];
		$snf = $snf + $w_i_3_nf[$ids[$i]];
		$sf = $sf + $w_i_3_f[$ids[$i]];
	}
			$coef = "~";
			if ($sf>0) $coef = FReal($snf/$sf);
	echo "<td class='Field AC' colspan='4'><table><tr><td class='AC'><b>".OutNF($sn,$snf,$sf)."</b></td><td class='AC'><b>".$coef."</b></td></tr></table></td>\n";
	echo "</tr>\n";

   // Вывод итогов
	echo "<tr>\n";
	echo "<td class='Field'><b>ИТОГО:</b></td>\n";
		$sn = 0;
		$snf = 0;
		$sf = 0;
	for ($i=0;$i < count($ids);$i++) {
		$xn = $w_i_1_n[$ids[$i]]+$w_i_2_n[$ids[$i]]+$w_i_3_n[$ids[$i]];
		$xnf = $w_i_1_nf[$ids[$i]]+$w_i_2_nf[$ids[$i]]+$w_i_3_nf[$ids[$i]];
		$xf = $w_i_1_f[$ids[$i]]+$w_i_2_f[$ids[$i]]+$w_i_3_f[$ids[$i]];

		echo "<td class='Field'><b>".OutNF($xn,$xnf,$xf)."</b></td>\n";
		$sn = $sn + $xn;
		$snf = $snf + $xnf;
		$sf = $sf + $xf;
	}
			$coef = "~";
			if ($sf>0) $coef = FReal($snf/$sf);
	echo "<td class='Field AC' colspan='4'><table><tr><td class='AC'><b>".OutNF($sn,$snf,$sf)."</b></td><td class='AC'><b>".$coef."</b></td></tr></table></td>\n";
	echo "</tr>\n";

   // Вывод коэффициента
	echo "<tr>\n";
	echo "<td class='Field AC'><div><b>Факт Н/Ч</b></div><div style='border-top: 1px solid black; margin: 2px 20px 2px 20px;'><b>Затр ч.</b></div></td>\n";
		$snf = 0;
		$sf = 0;
	for ($i=0;$i < count($ids);$i++) {
		$xnf = $w_i_1_nf[$ids[$i]]+$w_i_2_nf[$ids[$i]]+$w_i_3_nf[$ids[$i]];
		$xf = $w_i_1_f[$ids[$i]]+$w_i_2_f[$ids[$i]]+$w_i_3_f[$ids[$i]];

			$coef = "~";
			if ($xf>0) $coef = FReal($xnf/$xf);

		echo "<td class='Field'><b>".$coef."</b></td>\n";
		$snf = $snf + $xnf;
		$sf = $sf + $xf;
	}

			$coef = "~";
			if ($sf>0) $coef = FReal($snf/$sf);

	echo "<td class='Field AC' colspan='4'><b>".$coef."</b></td>\n";
	echo "</tr>\n";




	echo "</table>";


	echo "<br><br><b>* Формат данных:</b><br><br><div style='margin-left: 30px;'>План Н/ч<br>Факт Н/Ч<br>Затр. ч</div>";
}
?>