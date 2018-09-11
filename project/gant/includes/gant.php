<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


$gant_start = 0;
$gant_end = 0;
$gant_p_num = array();
$gant_f_num = array();
$gant_inzad = array();

$gant_pl_start = 0;
$gant_pl_end = 0;
$gant_pl_p_num = array();
$gant_pl_f_num = array();
$gant_pl_inzad = array();

$redact_oper_id = 0;


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // форматирование данных
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function FormatReal($num,$x) {
		$ret = number_format( $x, $num, '.', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, '.', ' ');
		return $ret;
	}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Очистка данных по ганту
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function ClearARR() {
		global $gant_inzad, $gant_p_num, $gant_f_num, $gant_start, $gant_end, $days_count, $days, $gant_pl_inzad, $gant_pl_p_num, $gant_pl_f_num, $gant_pl_start, $gant_pl_end;

		$gant_start = 0;
		$gant_end = 0;
		for ($d=0;$d < $days_count;$d++) {
			$gant_p_num[$days[$d]]=0;
			$gant_f_num[$days[$d]]=0;
			$gant_inzad[$days[$d]]=0;
		}
		$gant_pl_start = 0;
		$gant_pl_end = 0;
		for ($d=0;$d < $days_count;$d++) {
			$gant_pl_p_num[$days[$d]]=0;
			$gant_pl_inzad[$days[$d]]=0;
		}
	}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Расчёт ганта для операции
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function CalcOPS($id) {
		global $db_prefix, $gant_inzad, $gant_p_num, $gant_f_num, $gant_start, $gant_end, $days_count, $days, $gant_pl_inzad, $gant_pl_p_num, $gant_pl_start, $gant_pl_end;

		$result = dbquery("SELECT DATE, NORM, NORM_FACT FROM ".$db_prefix."db_zadan where (ID_operitems = '".$id."') order by DATE");
		while ($zadx = mysql_fetch_array($result)) {
			$dt = $zadx["DATE"];
			if ($gant_start == 0) $gant_start = $dt;
			if (($gant_start > $dt) && ($dt>0)) {
				$gant_start = $dt;
			}
			if ($gant_end < $dt) {
				$gant_end = $dt;
			}
			
			if (($dt>=$days[0]) && ($dt<=$days[$days_count-1])) {
				$gant_p_num[$zadx["DATE"]] = $gant_p_num[$zadx["DATE"]] + $zadx["NORM"]*1;
				$gant_f_num[$zadx["DATE"]] = $gant_f_num[$zadx["DATE"]] + $zadx["NORM_FACT"]*1;
				$gant_inzad[$zadx["DATE"]] = 1;
			}
		}

		$result = dbquery("SELECT DATE, NORM FROM ".$db_prefix."db_planzad where (ID_operitems = '".$id."') order by DATE");
		while ($zadx = mysql_fetch_array($result)) {
			$dt = $zadx["DATE"];
			if ($gant_pl_start == 0) $gant_pl_start = $dt;
			if (($gant_pl_start > $dt) && ($dt>0)) {
				$gant_pl_start = $dt;
			}
			if ($gant_pl_end < $dt) {
				$gant_pl_end = $dt;
			}
			
			if (($dt>=$days[0]) && ($dt<=$days[$days_count-1])) {
				$gant_pl_p_num[$zadx["DATE"]] = $gant_pl_p_num[$zadx["DATE"]] + $zadx["NORM"]*1;
				$gant_pl_inzad[$zadx["DATE"]] = 1;
			}
		}

		if ($gant_start>0) {
			if ($gant_pl_start>$gant_start) $gant_pl_start = $gant_start;
		}
	}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Расчёт ганта для ДСЕ
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function CalcIZD($id) {
		global $db_prefix;

		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$id."') order by ID");
		while($res = mysql_fetch_array($xxx)) CalcOPS($res["ID"]);

		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_zakdet where (PID = '".$id."') order by ID");
		while($res = mysql_fetch_array($xxx)) CalcIZD($res["ID"]);
	}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Расчёт ганта для заказа (ускоренно)
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function CalcZAK($id) {
		global $db_prefix, $gant_inzad, $gant_p_num, $gant_f_num, $gant_start, $gant_end, $days_count, $days, $gant_pl_inzad, $gant_pl_p_num, $gant_pl_start, $gant_pl_end;

		$result = dbquery("SELECT DATE, NORM, NORM_FACT FROM ".$db_prefix."db_zadan where (ID_zak = '".$id."') order by DATE");
		while ($zadx = mysql_fetch_array($result)) {
			$dt = $zadx["DATE"];
			if ($gant_start == 0) $gant_start = $dt;
			if (($gant_start > $dt) && ($dt>0)) {
				$gant_start = $dt;
			}
			if ($gant_end < $dt) {
				$gant_end = $dt;
			}
			
			if (($dt>=$days[0]) && ($dt<=$days[$days_count-1])) {
				$gant_p_num[$zadx["DATE"]] = $gant_p_num[$zadx["DATE"]] + $zadx["NORM"]*1;
				$gant_f_num[$zadx["DATE"]] = $gant_f_num[$zadx["DATE"]] + $zadx["NORM_FACT"]*1;
				$gant_inzad[$zadx["DATE"]] = 1;
			}
		}

		$result = dbquery("SELECT DATE, NORM FROM ".$db_prefix."db_planzad where (ID_zak = '".$id."') order by DATE");
		while ($zadx = mysql_fetch_array($result)) {
			$dt = $zadx["DATE"];
			if ($gant_pl_start == 0) $gant_pl_start = $dt;
			if (($gant_pl_start > $dt) && ($dt>0)) {
				$gant_pl_start = $dt;
			}
			if ($gant_pl_end < $dt) {
				$gant_pl_end = $dt;
			}
			
			if (($dt>=$days[0]) && ($dt<=$days[$days_count-1])) {
				$gant_pl_p_num[$zadx["DATE"]] = $gant_pl_p_num[$zadx["DATE"]] + $zadx["NORM"]*1;
				$gant_pl_inzad[$zadx["DATE"]] = 1;
			}
		}

		if ($gant_start>0) {
			if ($gant_pl_start>$gant_start) $gant_pl_start = $gant_start;
		}
	}



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Вывод строки с датами (данные по ганту должны быть посчитаны до вывода)
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function OutDates() {
		global $days_count, $today, $days, $gant_start, $gant_end, $gant_p_num, $gant_f_num, $gant_inzad, $gant_pl_inzad, $gant_pl_p_num, $gant_pl_start, $gant_pl_end, $span_inebg;

		for ($d=0;$d < $days_count;$d++) {
			$date = $days[$d];
			$hg = "";
			if ($date==$today) $hg = " TODAY";
			$inwork = "";
			$txt = " ";
			$inerror = "";

			if (($date>=$gant_pl_start) && ($date<=$gant_pl_end)) {
				$inwork = " INP";
			}
			if (($date>=$gant_start) && ($date<=$gant_end)) {
				$inwork = " INW";
				$txt = FormatReal(1,$gant_f_num[$date]);
				if ($date>$today) $txt = FormatReal(1,$gant_p_num[$date]);
				if ($gant_inzad[$date]==0) {
					if ($txt=="0") $txt=" ";
				}
			}
			if ($date>$today) {

				$pl_1 = FormatReal(1,$gant_pl_p_num[$date]);	// ЗАПЛАНИРОВАННО
				$pl_2 = FormatReal(1,$gant_p_num[$date]);	// В СЗ
				$plantitle = utftxt("Запланированно: ".$pl_1."\nВ сменных заданиях: ".$pl_2);

				$txt = $plantxt;
				if (($pl_1>0) && ($pl_2==0)) {
					$inerror = " style='background: #".$span_inebg.";'";
					$txt = "<a title='".$plantitle."'>".$pl_1." *</a>";
				}
				if (($pl_1>0) && ($pl_2>0)) {
					$inerror = " style='background: #".$span_inebg.";'";
					$txt = "<a title='".$plantitle."'>".$pl_2."</a>";
				}
				if (($pl_1==0) && ($pl_2>0)) {
					$inerror = "";
					$txt = "<a title='".$plantitle."'>".$pl_2."</a>";
				}
				if (($pl_1==0) and ($pl_2==0)) {
					$txt="0";
					if ($gant_inzad[$date]==0) $txt=" ";
				}
			}
			echo "<td class='GNT$hg$inwork'><span".$inerror.">$txt</span></td>";
		}
	}



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // REDACTOR  Вывод строки с датами (данные по ганту должны быть посчитаны до вывода)
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function OutDatesRedact() {
		global $days_count, $today, $days, $gant_start, $gant_end, $gant_p_num, $gant_f_num, $gant_inzad, $gant_pl_inzad, $gant_pl_p_num, $gant_pl_start, $gant_pl_end, $redact_oper_id, $span_inebg;

	   // Если есть доступ
		if (db_adcheck("db_planzad")) {


		for ($d=0;$d < $days_count;$d++) {
			$date = $days[$d];
			$hg = "";
			if ($date==$today) $hg = " TODAY";
			$inwork = "";
			$txt = " ";
			$plantxt = "";
			$redact = "";
			$inerror = "";

			if (($date>=$gant_pl_start) && ($date<=$gant_pl_end)) {
				$inwork = " INP";
			}
			if (($date>=$gant_start) && ($date<=$gant_end)) {
				$inwork = " INW";
				$percent = "#";
				if ($gant_p_num[$date]>0) $percent = FormatReal(2,$gant_f_num[$date]/$gant_p_num[$date]);
				$txt = "<b>".FormatReal(1,$gant_f_num[$date])."</b><br>".$percent."<br><b>".FormatReal(1,$gant_p_num[$date])."</b>";
				if ($date>$today) $txt = FormatReal(1,$gant_p_num[$date]);
				if ($gant_inzad[$date]==0) {
					if ($txt=="<b>0</b><br>#<br><b>0</b>") $txt=" ";
				}
			}
			if ($date>$today) {
			// Сам редактор

				$pl_1 = FormatReal(1,$gant_pl_p_num[$date]);
				$pl_2 = FormatReal(1,$gant_p_num[$date]);

				$URL = "edit.php?oid=".$redact_oper_id."&date=".$days[$d]."&val=";
				$redact = "<input type='text' class='inp' name='inp_".$d."' value='".$pl_1."' onkeydown=\"KeyDown(this.value, event)\" onkeyup=\"FPFilter(this.form, 'inp_".$d."', event)\" onchange=\"vredact(this , '$URL'+this.value)\"><br>";
				$txt = "<b>".$pl_2."</b>";

				if (($pl_1>0)) $inerror = " style='background: #".$span_inebg.";'";
			}
			echo "<td class='GNT$hg$inwork'><span class='REDACT'".$inerror.">".$redact.$txt."</span></td>";
		}

		} else {
			OutDates();
		}
	}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>