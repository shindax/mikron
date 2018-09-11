<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$start_time = microtime(true);
	include "includes.php";

   // Используем функции ГАНТ
	include "includes/gant.php";

// Строка таблицы справа без редактора
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


$oper_plan_s = array();
$oper_plan_p = array();

	function CalcOperPlan() {
		global $db_prefix, $today, $oper_plan_s, $oper_plan_p;

		$xxx = dbquery("SELECT NORM, ID_operitems FROM ".$db_prefix."db_zadan where (DATE>'".$today."') order by ID");
		while($zad = mysql_fetch_array($xxx)) {
			$oper_plan_s[$zad["ID_operitems"]] = ($oper_plan_s[$zad["ID_operitems"]]*1) + ($zad["NORM"]*1);
		}

		$xxx = dbquery("SELECT NORM, ID_operitems FROM ".$db_prefix."db_planzad where (DATE>'".$today."') order by ID");
		while($zad = mysql_fetch_array($xxx)) {
			$oper_plan_p[$zad["ID_operitems"]] = ($oper_plan_p[$zad["ID_operitems"]]*1) + ($zad["NORM"]*1);
		}
	}

	function ID_oper($item) {
		global $db_prefix, $oper_plan_s, $oper_plan_p, $span_inebg;

			$norm_all = $item["NORM_ZAK"]*1;
			$norm_fact = $item["NORM_FACT"]*1;
			$norm_splan = $oper_plan_s[$item["ID"]]*1;
			$norm_plan = $oper_plan_p[$item["ID"]]*1;

		$xx = dbquery("SELECT ID, RCOUNT FROM ".$db_prefix."db_zakdet where  (ID='".$item["ID_zakdet"]."')");
		if ($res = mysql_fetch_array($xx)) {
			$num_all = $res["RCOUNT"]*1;
		}
			$num_fact = 0;

		$alert = "";
		$err = $norm_all-$norm_fact-$norm_plan-$norm_splan;
		if (abs($err)>0.1) {
			$alert = "style='background: #".$span_inebg."; color: #fff; cursor: hand;'";
			$norm_plan = "<a title='".utftxt("Несоответствие: ".$err." Н/Ч")."'>".$norm_plan."</a>";
		}

		echo "<td class='GNTt'><span><b>".$num_all."</span></b></td>";
		echo "<td class='GNTt'><span>".$num_fact."</span></td>";
		echo "<td class='GNTt'><span><b>".$norm_all."</b></span></td>";
		echo "<td class='GNTt'><span>".$norm_fact."</span></td>";
		echo "<td class='GNTt'><span>".$norm_splan."</span></td>";
		echo "<td class='GNTt'><span $alert>".$norm_plan."</span></td>";
	}

	function ID_zakdet($item) {
		global $db_prefix, $span_inebg;

			$num_all = $item["RCOUNT"]*1;
			$norm_all = $item["GANT_NP"]*1;
			$norm_fact = $item["GANT_NF"]*1;
			$norm_splan = $item["GANT_PS"]*1;
			$norm_plan = $item["GANT_PP"]*1;
			$num_fact = 0;

		$alert = "";
		$err = $norm_all-$norm_fact-$norm_plan-$norm_splan;
		if (abs($err)>0.1) {
			$alert = "style='background: #".$span_inebg."; color: #fff; cursor: hand;'";
			$norm_plan = "<a title='".utftxt("Несоответствие: ".$err." Н/Ч")."'>".$norm_plan."</a>";
		}

	   // Вывод
		echo "<td class='GNTt'><span><b>".$num_all."</span></b></td>";
		echo "<td class='GNTt'><span>".$num_fact."</span></td>";
		echo "<td class='GNTt'><span><b>".$norm_all."</b></span></td>";
		echo "<td class='GNTt'><span>".$norm_fact."</span></td>";
		echo "<td class='GNTt'><span>".$norm_splan."</span></td>";
		echo "<td class='GNTt'><span $alert>".$norm_plan."</span></td>";
	}



////////////////////////////////////////////////////////////////////////////////////
// Расчёт данных по операциям
////////////////////////////////////////////////////////////////////////////////////


	CalcOperPlan();


////////////////////////////////////////////////////////////////////////////////////
// Вывод данных
////////////////////////////////////////////////////////////////////////////////////

$calc_id = 0;
$calc_tp = "";
if (isset($_GET["sel"])) {
	$calc_tp = "i";
	if (substr_count($_GET["sel"], "o")>0) $calc_tp = "o";
	$calc_id = str_replace("i","",$_GET["sel"]);
	$calc_id = str_replace("o","",$calc_id);
}

if ($calc_tp=="i") {
	$xx = dbquery("SELECT ID, RCOUNT, GANT_NP, GANT_NF, GANT_PS, GANT_PP FROM ".$db_prefix."db_zakdet where  (ID='".$calc_id."')");
	if ($res = mysql_fetch_array($xx)) ID_zakdet($res);
}

if ($calc_tp=="o") {
	$xx = dbquery("SELECT ID, NORM_ZAK, NORM_FACT, ID_zakdet FROM ".$db_prefix."db_operitems where (ID='".$calc_id."')");
	if ($res = mysql_fetch_array($xx)) ID_oper($res);
}


?>