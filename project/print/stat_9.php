<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$zak_IDs = $_GET["p0"];

	if (count($zak_IDs)>0) $step = 2;

	include "project/calc_zak.php";


///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

	function FReal($x) {
		$ret = number_format( $x, 2, ',', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
		return $ret;
	}

///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////



if ($step==1) {


	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Загрузка оборудования - выбор заказов</h2>";

	echo "<br><input type='submit' value='Расчёт'><span style='margin-left: 40px;'>";
	Input("boolean","calc_all",0);
	echo " - Полный перерасчёт указанных заказов (очень долго)</span><br><br>";

	render_item(80,false,false,false,false,"(EDIT_STATE='0') and (INSZ='1')","","order by ORD","");


}

if ($step==2) {

   // Шапка

	echo "<h2>Загрузка оборудования</h2>";

	$park_IDs = Array();
	$park_names = Array();

	$zakparkstr = Array();

	$s_norm_plan = Array();
	$s_norm_fact = Array();
	$s_fact = Array();
	$s_park = Array();

	$park_IDs[] = 0;
	$park_names[] = "Незаполнено";
	$xxx = dbquery("SELECT ID, MARK, TID FROM ".$db_prefix."db_park order by binary(MARK)");
	while($res = mysql_fetch_array($xxx)) {
		$park_IDs[] = $res["ID"]*1;
		$park_names[$res["ID"]*1] = "<b>".$res["MARK"]."</b> (".FVal($res,"db_park","TID").")";
	}

	function Open_zak_ID($zak_id) {
		global $db_prefix, $s_park, $s_fact, $s_norm_fact, $s_norm_plan, $park_IDs, $park_names, $zakparkstr, $summ_n, $summ_nf, $summ_f;

		if ($_GET["calc_all"]=="on") CalculateZakaz($zak_id);

		$norm_plan = Array();
		$norm_fact = Array();
		$fact = Array();
		$park = Array();

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$zak_id."')");
		$zak = mysql_fetch_array($xxx);

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$zak_id."') and (PID='0')");
		$zakdet = mysql_fetch_array($xxx);

		echo "<h4>".FVal($zak,"db_zak","TID")." ".FVal($zak,"db_zak","NAME")." ".$zakdet["NAME"]." - ".$zakdet["OBOZ"]."</h4>\n";
		$zakname = "<b>".FVal($zak,"db_zak","TID")." ".FVal($zak,"db_zak","NAME")."</b> ".$zakdet["NAME"]." - ".$zakdet["OBOZ"];

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zak='".$zak["ID"]."') order by ORD");
		while ($oper = mysql_fetch_array($xxx)) {
			if (!in_array($oper["ID_park"]*1,$park)) $park[] = $oper["ID_park"]*1;
			if (!in_array($oper["ID_park"]*1,$s_park)) $s_park[] = $oper["ID_park"]*1;
			$norm_plan[$oper["ID_park"]*1] = $norm_plan[$oper["ID_park"]*1]*1 + $oper["NORM_ZAK"]*1;
			$s_norm_plan[$oper["ID_park"]*1] = $s_norm_plan[$oper["ID_park"]*1]*1 + $oper["NORM_ZAK"]*1;
			$norm_fact[$oper["ID_park"]*1] = $norm_fact[$oper["ID_park"]*1]*1 + $oper["NORM_FACT"]*1;
			$s_norm_fact[$oper["ID_park"]*1] = $s_norm_fact[$oper["ID_park"]*1]*1 + $oper["NORM_FACT"]*1;
			$fact[$oper["ID_park"]*1] = $fact[$oper["ID_park"]*1]*1 + $oper["FACT"]*1;
			$s_fact[$oper["ID_park"]*1] = $s_fact[$oper["ID_park"]*1]*1 + $oper["FACT"]*1;
		}


		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "	<thead>\n";
		echo "<tr class='first'>\n";
		echo "<td>Оборудование</td>";
		echo "<td>План, Н/Ч</td>";
		echo "<td>Факт, Н/Ч</td>";
		echo "<td>Осталось, Н/Ч</td>";
		echo "<td>Затрачено, ч</td>";
		echo "<td>Затр. часы/Факт Н/Ч</td>";
		echo "</tr>\n";
		echo "	</thead>\n";

		$sx1 = 0;
		$sx2 = 0;
		$sx3 = 0;

		for ($x=0;$x < count($park_IDs); $x++) {
			if (in_array($park_IDs[$x]*1,$park)) {
				echo "<tr>\n";
				echo "<td class='Field AL'>".$park_names[$park_IDs[$x]*1]."</td>";
				echo "<td class='Field AC'>".FReal($norm_plan[$park_IDs[$x]*1])."</td>";
				echo "<td class='Field AC'>".FReal($norm_fact[$park_IDs[$x]*1])."</td>";
				echo "<td class='Field AC'>".FReal($norm_plan[$park_IDs[$x]*1]-$norm_fact[$park_IDs[$x]*1])."</td>";
				echo "<td class='Field AC'>".FReal($fact[$park_IDs[$x]*1])."</td>";
				$xxx = "~";
				if ($norm_fact[$park_IDs[$x]*1]*1>0) $xxx = FReal($fact[$park_IDs[$x]*1]/$norm_fact[$park_IDs[$x]*1]);
				echo "<td class='Field AC'>".$xxx."</td>";
				echo "</tr>\n";

				$txt = "";
				$txt = $txt."<tr>\n";
				$txt = $txt."<td class='Field AL' style='padding-left: 20px;'>".$zakname."</td>";
				$txt = $txt."<td class='Field AC'>".FReal($norm_plan[$park_IDs[$x]*1])."</td>";
				$txt = $txt."<td class='Field AC'>".FReal($norm_fact[$park_IDs[$x]*1])."</td>";
				$txt = $txt."<td class='Field AC'>".FReal($norm_plan[$park_IDs[$x]*1]-$norm_fact[$park_IDs[$x]*1])."</td>";
				$txt = $txt."<td class='Field AC'>".FReal($fact[$park_IDs[$x]*1])."</td>";
				$xxx = "~";
				if ($norm_fact[$park_IDs[$x]*1]*1>0) $xxx = FReal($fact[$park_IDs[$x]*1]/$norm_fact[$park_IDs[$x]*1]);
				$txt = $txt."<td class='Field AC'>".$xxx."</td>";
				$txt = $txt."</tr>\n";

				$zakparkstr[$zak_id."|".$park_IDs[$x]*1] = $txt;

				$sx1 = $sx1 + $norm_plan[$park_IDs[$x]*1];
				$sx2 = $sx2 + $norm_fact[$park_IDs[$x]*1];
				$sx3 = $sx3 + $fact[$park_IDs[$x]*1];
			}
		}

			echo "<tr style='background: #c8daf2;'>\n";
			echo "<td class='Field AL'><b>ИТОГО:</b></td>";
			echo "<td class='Field AC'><b>".FReal($sx1)."</b></td>";
			echo "<td class='Field AC'><b>".FReal($sx2)."</b></td>";
			echo "<td class='Field AC'><b>".FReal($sx1-$sx2)."</b></td>";
			echo "<td class='Field AC'><b>".FReal($sx3)."</b></td>";
			$xxx = "~";
			if ($sx2*1>0) $xxx = FReal($sx3/$sx2);
			echo "<td class='Field AC'><b>".$xxx."</b></td>";
			echo "</tr>\n";

		$summ_n = $summ_n + $sx1;
		$summ_nf = $summ_nf + $sx2;
		$summ_f = $summ_f + $sx3;

		echo "</table>";
	}

	$summ_n = 0;
	$summ_nf = 0;
	$summ_f = 0;

	for ($x=0;$x < count($zak_IDs); $x++) Open_zak_ID($zak_IDs[$x]);


	echo "<h2>Итого по заказам</h2>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td>Оборудование</td>";
	echo "<td>План, Н/Ч</td>";
	echo "<td>Факт, Н/Ч</td>";
	echo "<td>Осталось, Н/Ч</td>";
	echo "<td>Затрачено, ч</td>";
	echo "<td>Затр. часы/Факт Н/Ч</td>";
	echo "</tr>\n";
	echo "	</thead>\n";

	for ($x=0;$x < count($park_IDs); $x++) {
		if (in_array($park_IDs[$x]*1,$s_park)) {
			echo "<tr style='background: #c8daf2;'>\n";
				//  style='cursor: hand;' onClick='ShowHide(\"tdb_".($park_IDs[$x]*1)."\");'
			echo "<td class='Field AL'>".$park_names[$park_IDs[$x]*1]."</td>";
			echo "<td class='Field AC'><b>".FReal($s_norm_plan[$park_IDs[$x]*1])."</b></td>";
			echo "<td class='Field AC'><b>".FReal($s_norm_fact[$park_IDs[$x]*1])."</b></td>";
			echo "<td class='Field AC'><b>".FReal($s_norm_plan[$park_IDs[$x]*1]-$s_norm_fact[$park_IDs[$x]*1])."</b></td>";
			echo "<td class='Field AC'><b>".FReal($s_fact[$park_IDs[$x]*1])."</b></td>";
			$xxx = "~";
			if ($s_norm_fact[$park_IDs[$x]*1]*1>0) $xxx = FReal($s_fact[$park_IDs[$x]*1]/$s_norm_fact[$park_IDs[$x]*1]);
			echo "<td class='Field AC'><b>".$xxx."</b></td>";
			echo "</tr>\n";

			for ($z=0;$z < count($zak_IDs); $z++) {
				$str = "".$zakparkstr[$zak_IDs[$z]."|".$park_IDs[$x]*1];
				if ($str!=="") echo $str;
			}
		}
	}


			echo "<tr style='background: #98b8e2;'>\n";
			echo "<td class='Field AL'><b>ИТОГО:</b></td>";
			echo "<td class='Field AC'><b>".FReal($summ_n)."</b></td>";
			echo "<td class='Field AC'><b>".FReal($summ_nf)."</b></td>";
			echo "<td class='Field AC'><b>".FReal($summ_n-$summ_nf)."</b></td>";
			echo "<td class='Field AC'><b>".FReal($summ_f)."</b></td>";
			$xxx = "~";
			if ($summ_nf>0) $xxx = FReal($summ_f/$summ_nf);
			echo "<td class='Field AC'><b>".$xxx."</b></td>";
			echo "</tr>\n";


	echo "</table>";

	
	if ($_GET["calc_all"]=="on") echo "<br>* Посчитано с полным перерасчётом указанных заказов";
}
?>
