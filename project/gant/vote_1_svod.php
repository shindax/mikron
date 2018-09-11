<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "includes.php";
	$opened = explode("|",$opened);

	$start_time = microtime(true);

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

	function ID_oper($item,$num_all) {
		global $oper_plan_s, $oper_plan_p, $span_inebg;

			$norm_all = $item["NORM_ZAK"]*1;
			$norm_fact = $item["NORM_FACT"]*1;
			$norm_splan = $oper_plan_s[$item["ID"]]*1;
			$norm_plan = $oper_plan_p[$item["ID"]]*1;
			$num_fact = 0;

		$alert = "";
		$err = $norm_all-$norm_fact-$norm_plan-$norm_splan;
		if (abs($err)>0.1) {
			$alert = "style='background: #".$span_inebg."; color: #fff; cursor: hand;'";
			$norm_plan = "<a title='".utftxt("Несоответствие: ".$err." Н/Ч")."'>".$norm_plan."</a>";
		}

		echo "<tr id='S_o".$item["ID"]."' class='GNT OPER_TR' onClick=\"select('o".$item["ID"]."',1);\">";
		echo "<td class='GNTt'><span><b>".$num_all."</span></b></td>";
		echo "<td class='GNTt'><span>".$num_fact."</span></td>";
		echo "<td class='GNTt'><span><b>".$norm_all."</b></span></td>";
		echo "<td class='GNTt'><span>".$norm_fact."</span></td>";
		echo "<td class='GNTt'><span>".$norm_splan."</span></td>";
		echo "<td class='GNTt'><span $alert>".$norm_plan."</span></td>";
		echo "</tr>";
	}

	function ID_zakdet($item) {
		global $db_prefix, $opened, $span_inebg;

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

		$doopen = is_opened("i".$item["ID"],$opened);
		if (is_opened("zak".$item["ID_zak"],$opened)) $doopen = true;

	   // Вывод
		echo "<tr id='S_i".$item["ID"]."' class='GNT IZD_TR' onClick=\"select('i".$item["ID"]."',0);\">";
		echo "<td class='GNTt'><span><b>".$num_all."</span></b></td>";
		echo "<td class='GNTt'><span>".$num_fact."</span></td>";
		echo "<td class='GNTt'><span><b>".$norm_all."</b></span></td>";
		echo "<td class='GNTt'><span>".$norm_fact."</span></td>";
		echo "<td class='GNTt'><span>".$norm_splan."</span></td>";
		echo "<td class='GNTt'><span $alert>".$norm_plan."</span></td>";
		echo "</tr>";

		if ($doopen) {
			$xx = dbquery("SELECT ID, ID_oper, NORM_ZAK, NORM_FACT FROM ".$db_prefix."db_operitems where (ID_zakdet='".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_oper($res,$num_all);
			}

			$xx = dbquery("SELECT ID, OBOZ, NAME, ID_zak, RCOUNT, GANT_NP, GANT_NF, GANT_PS, GANT_PP FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_zakdet($res);
			}
		}
	}

	function ID_zak($item) {
		global $db_prefix, $opened, $span_inebg;

		$xxx = dbquery("SELECT ID, OBOZ, NAME, RCOUNT, GANT_NP, GANT_NF, GANT_PS, GANT_PP FROM ".$db_prefix."db_zakdet where  (ID_zak='".$item["ID"]."') and (PID='0')");
		$izd = mysql_fetch_array($xxx);

			$num_all = $item["DSE_COUNT"]*1;
			$norm_all = $izd["GANT_NP"]*1;
			$norm_fact = $izd["GANT_NF"]*1;
			$norm_splan = $izd["GANT_PS"]*1;
			$norm_plan = $izd["GANT_PP"]*1;
			$num_fact = 0;

		$alert = "";
		$err = $norm_all-$norm_fact-$norm_plan-$norm_splan;
		if (abs($err)>0.1) {
			$alert = "style='background: #".$span_inebg."; color: #fff; cursor: hand;'";
			$norm_plan = "<a title='".utftxt("Несоответствие: ".$err." Н/Ч")."'>".$norm_plan."</a>";
		}

		$doopen = is_opened("i".$izd["ID"],$opened);
		if (is_opened("zak".$item["ID"],$opened)) $doopen = true;

	   // Вывод
		echo "<tr id='S_i".$izd["ID"]."' class='GNT ZAK_TR' onClick=\"select('i".$izd["ID"]."',0);\">";
		echo "<td class='GNTt'><span><b>".$num_all."</span></b></td>";
		echo "<td class='GNTt'><span>".$num_fact."</span></td>";
		echo "<td class='GNTt'><span><b>".$norm_all."</b></span></td>";
		echo "<td class='GNTt'><span>".$norm_fact."</span></td>";
		echo "<td class='GNTt'><span>".$norm_splan."</span></td>";
		echo "<td class='GNTt'><span $alert>".$norm_plan."</span></td>";
		echo "</tr>";

		if ($doopen) {
			$xx = dbquery("SELECT ID, ID_oper, NORM_ZAK, NORM_FACT FROM ".$db_prefix."db_operitems where  (ID_zakdet='".$izd["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_oper($res,$num_all);
			}

			$xx = dbquery("SELECT ID, OBOZ, NAME, ID_zak, RCOUNT, GANT_NP, GANT_NF, GANT_PS, GANT_PP FROM ".$db_prefix."db_zakdet where  (PID='".$izd["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_zakdet($res);
			}	
		}
	}



////////////////////////////////////////////////////////////////////////////////////
// Расчёт данных по операциям
////////////////////////////////////////////////////////////////////////////////////


	CalcOperPlan();


////////////////////////////////////////////////////////////////////////////////////
// Вывод данных
////////////////////////////////////////////////////////////////////////////////////


	echo "<table style='width: ".$svod_width."px; height: 20px;'>";
	$xxx = dbquery("SELECT ID, TID, NAME, DSE_COUNT FROM ".$db_prefix."db_zak where (EDIT_STATE='0') and (INGANT='1') order by PRIOR, ID");
	while($res = mysql_fetch_array($xxx)) {
		ID_zak($res);
	}
	echo "<tr class='GNT' style='height: 50px;'><td style='border-bottom: 0px solid black;'>&nbsp;</td></tr>";
	echo "</table>";





///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  STATTREE       ////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<div id='svod' style='display: none;'>";
	$mem_usage = memory_get_peak_usage(true)/1024;
	$exec_time = microtime(true) - $start_time;
echo utftxt("Время, сек: ".number_format($exec_time, 3, ',', ' ')." &nbsp; Память, кБ: ".number_format($mem_usage, 0, ',', ' ')." &nbsp; Запросов к БД: ".$dbquery_index."");
echo "</div>";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>