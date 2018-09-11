<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$smena = $_GET["p1"];
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);
$curindidres = 0;
$row_lines = 0;
$ind_list = 1;

	$plan_n1_s = 0;
	$plan_n2_s = 0;
	$plan_n3_s = 0;
	$plan_n4_s = 0;
	$plan_n5_s = 0;
	$plan_n6_s = 0;
	$plan_n7_s = 0;
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<h2>Сменные задания на ".$smena." смена ".$date."</h2>";

echo "<table style='page-break-after:always;' class='rdtbl tbl' width='1300px'><thead>";
echo "<tr class='first'>\n";
echo "<td rowspan='2' width='25'>№</td>\n";
echo "<td colspan='2'><b style='float:left;'>Лист №".$ind_list."</b>Операция</td>\n";
echo "<td rowspan='2' width='80'>Оборуд.</td>\n";
echo "<td colspan='2'>План</td>\n";
echo "<td colspan='3'>На заказ</td>\n";
echo "<td rowspan='2' width='50'>№ склада заг.</td>\n";
echo "<td rowspan='2' width='50'>№ склада изд.</td>\n";
echo "</tr>\n";

echo "<tr class='first'>\n";
echo "<td width='25'>№</td>\n";
echo "<td>Наименование</td>\n";
echo "<td width='30'>Кол-во</td>\n";
echo "<td width='30'>Н/Ч</td>\n";
echo "<td width='50'>Осталось</td>\n";
echo "<td width='50'>Всего<br>Н/Ч (шт)</td>\n";
echo "<td width='50'>Норма<br>на ед.</td>\n";
echo "</tr></thead>\n";

$coef_count = 0;
$coef_itog = 0;
$zzz = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary (NAME)");
while ($resurs = mysql_fetch_array($zzz)) {
	
	$plan_n1 = 0;
	$plan_n2 = 0;
	$plan_n3 = 0;
	$plan_n4 = 0;
	$plan_n5 = 0;
	$plan_n6 = 0;
	$plan_n7 = 0;
	$isstarted = false;
	
	$coef_plan = 0;
	$zadan_coef_sql = dbquery("SELECT PLAN FROM ".$db_prefix."db_tabel where (DATE = '".$pdate."') and (ID_resurs = '".$resurs["ID"]."') and (SMEN = '".$smena."')");
	$zadan_coef_txt = mysql_fetch_row($zadan_coef_sql);

	$zadanresults = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$resurs["ID"]."') and (SMEN = '".$smena."') order by ORD");
	$zadan_counts = dbquery("SELECT COUNT(ID) FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$resurs["ID"]."') and (SMEN = '".$smena."') order by ORD");
	$zadan_c_t = mysql_fetch_row($zadan_counts);
	while ($zadan = mysql_fetch_array($zadanresults)) {
		
		if ($zadan['ID_resurs'] !== $curindidres) {
			$coef_count = $coef_count + 1;
			$row_lines = $row_lines + 2;
		if ($row_lines>33){
			echo "</table>";
			$ind_list = $ind_list + 1;
			echo "<table style='page-break-after:always;' class='rdtbl tbl' width='1300px'><thead>";
			echo "<tr class='first'>\n";
			echo "<td rowspan='2' width='25'>№</td>\n";
			echo "<td colspan='2'><b style='float:left;'>Лист №".$ind_list."</b>Операция</td>\n";
			echo "<td rowspan='2' width='80'>Оборуд.</td>\n";
			echo "<td colspan='2'>План</td>\n";
			echo "<td colspan='3'>На заказ</td>\n";
			echo "<td rowspan='2' width='50'>№ склада заг.</td>\n";
			echo "<td rowspan='2' width='50'>№ склада изд.</td>\n";
			echo "</tr>\n";

			echo "<tr class='first'>\n";
			echo "<td width='25'>№</td>\n";
			echo "<td>Наименование</td>\n";
			echo "<td width='30'>Кол-во</td>\n";
			echo "<td width='30'>Н/Ч</td>\n";
			echo "<td width='50'>Осталось</td>\n";
			echo "<td width='50'>Всего<br>Н/Ч (шт)</td>\n";
			echo "<td width='50'>Норма<br>на ед.</td>\n";
			echo "</tr></thead>\n";
			$row_lines = 2;
		}
			echo "<tr><td style='padding-top:14px; padding-bottom:4px; text-align:center; font-size:24px;' colspan=11>".$resurs['NAME']."</td></tr>";
		}
		
		$row_lines = $row_lines + 2;
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$zadan["ID_zak"]."')");
		$zak = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$zadan["ID_zakdet"]."')");
		$izd = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$zadan["ID_zak"]."') and (PID = '0')");
		$first_dse = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$zadan["ID_operitems"]."')");
		$oper = mysql_fetch_array($result);

		if ($row_lines>33){
			echo "</table>";
			$ind_list = $ind_list + 1;
			echo "<table style='page-break-after:always;' class='rdtbl tbl' width='1300px'><thead>";
			echo "<tr class='first'>\n";
			echo "<td rowspan='2' width='25'>№</td>\n";
			echo "<td colspan='2'><b style='float:left;'>Лист №".$ind_list."</b>Операция</td>\n";
			echo "<td rowspan='2' width='80'>Оборуд.</td>\n";
			echo "<td colspan='2'>План</td>\n";
			echo "<td colspan='3'>На заказ</td>\n";
			echo "<td rowspan='2' width='50'>№ склада заг.</td>\n";
			echo "<td rowspan='2' width='50'>№ склада изд.</td>\n";
			echo "</tr>\n";

			echo "<tr class='first'>\n";
			echo "<td width='25'>№</td>\n";
			echo "<td>Наименование</td>\n";
			echo "<td width='30'>Кол-во</td>\n";
			echo "<td width='30'>Н/Ч</td>\n";
			echo "<td width='50'>Осталось</td>\n";
			echo "<td width='50'>Всего<br>Н/Ч (шт)</td>\n";
			echo "<td width='50'>Норма<br>на ед.</td>\n";
			echo "</tr></thead>\n";
			$row_lines = 2;
		}
		
		$zname = $zak["YY"];
		$tid = FVal($zak,"db_zak","TID");
		if ($zak["PID"]!=="0") {
			$yyyy = dbquery("SELECT * FROM ".$db_prefix."db_zak where  (ID='".$zak["PID"]."')");
			$parent = mysql_fetch_array($yyyy);
			$zname = $zname."-".$parent["NAME"];
				if ($parent["TID"]==1) $tid = "ВОЗ";
				if ($parent["TID"]==2) $tid = "ВКР";
				if ($parent["TID"]==3) $tid = "ВСП";
		}
		$zname = $tid." ".$zname." ".$zak["NAME"];

		$zak_nam1 = $first_dse["NAME"];
		$zak_nam2 = $izd["NAME"];
		if (strlen($zak_nam1)>60) $zak_nam1 = substr($first_dse["NAME"],0,60)."...";
		if (strlen($zak_nam2)>60) $zak_nam2 = substr($izd["NAME"],0,60)."...";
		$zak_zakdet = "<b>".$zname."</b> ".$zak_nam1." <b>".$izd["OBOZ"]." ".$zak_nam2."</b>";
		
		echo "<tr>\n";
		echo "<td class='Field' style='width:25px; border-bottom:1px solid #fff;'>".$zadan["ORD"]."</td>\n";
		echo "<td class='Field' colspan='10' style='width:25px; background: #cbdef4;'>".$zak_zakdet."</td>\n";
		echo "</tr>\n";

		$ost = 0;
		if ($oper["NORM_ZAK"]>0) $ost = $izd["RCOUNT"]*(($oper["NORM_ZAK"]-$oper["NORM_FACT"])/$oper["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		$ost_2 = $ost;
		$ost = "<b>".($oper["NORM_ZAK"]-$oper["NORM_FACT"])." (".$ost.")</b></td>
		<td class='Field' style='width:100px; text-align: center;'>".$oper["NORM_ZAK"]." (".$izd["RCOUNT"].")</td>
		<td class='Field' style='width:70px; text-align: center;'>".round(($oper["NORM"])/(60),2);

		echo "<tr><td class='Field' style='border-top:1px solid #fff;'></td>\n";
		echo "<td class='Field'>".FVal($oper,"db_operitems","ORD")."</td>\n";
		echo "<td class='Field'>".FVal($zadan,"db_zadan","ID_operitems");
		if (FVal($zadan,"db_zadan","MORE")!=="") echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Примечание: </b> ".FVal($zadan,"db_zadan","MORE");
		if (strlen(FVal($zadan,"db_zadan","MORE"))>35) $row_lines = $row_lines + 1;
		if (strlen(FVal($zadan,"db_zadan","MORE"))>105) $row_lines = $row_lines + 1;
		echo "</td>\n";
		echo "<td class='Field' style='width:100px;'>".FVal($zadan,"db_zadan","ID_park")."</td>\n";
		echo "<td class='Field' style='width:50px;'>".FVal($zadan,"db_zadan","NUM")."</td>\n";
		echo "<td class='Field' style='width:50px;'>".FVal($zadan,"db_zadan","NORM")."</td>\n";
		echo "<td class='Field' style='width:80px; text-align: center;'>$ost</td>\n";
		echo "<td class='Field' style='width:50px;'></td>\n";
		echo "<td class='Field' style='width:50px;'></td>\n";
		echo "</tr>\n";
		
		$plan_n1 = $plan_n1 + (1*$zadan["NORM"]);
		$plan_n2 = $plan_n2 + (1*FVal($zadan,"db_zadan","NUM"));
		$plan_n3 = $plan_n3 + (1*($oper["NORM_ZAK"]-$oper["NORM_FACT"]));
		$plan_n4 = $plan_n4 + (1*$ost_2);
		$plan_n5 = $plan_n5 + (1*$oper["NORM_ZAK"]);
		$plan_n6 = $plan_n6 + (1*$izd["RCOUNT"]);
		$plan_n7 = $plan_n7 + (1*round(($oper["NORM"])/(60),2));
		
		$plan_n1_s = $plan_n1_s + (1*$zadan["NORM"]);
		$plan_n2_s = $plan_n2_s + (1*FVal($zadan,"db_zadan","NUM"));
		$plan_n3_s = $plan_n3_s + (1*($oper["NORM_ZAK"]-$oper["NORM_FACT"]));
		$plan_n4_s = $plan_n4_s + (1*$ost_2);
		$plan_n5_s = $plan_n5_s + (1*$oper["NORM_ZAK"]);
		$plan_n6_s = $plan_n6_s + (1*$izd["RCOUNT"]);
		$plan_n7_s = $plan_n7_s + (1*round(($oper["NORM"])/(60),2));
		
		$curindidres = $zadan['ID_resurs'];
	}
	
	if (($zadan_coef_txt[0]) and ($zadan_coef_txt[0]!=="0") and ($zadan_coef_txt[0]!==0) and ($zadan_coef_txt[0]!=="")){
		$coef_plan = round($plan_n1/$zadan_coef_txt[0],2);
		$coef_itog = $coef_itog + round($plan_n1/$zadan_coef_txt[0],2);
	}else{
		$coef_plan = "нет табеля";
	}
	
	if ($zadan_c_t[0] !== "0") {
		echo "<tr><td class='Field' colspan='4' style='background:#ccc;'><b style='float:right;'>Итого:</b></td>
		<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n2."</b></td>
		<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n1."</b></td>
		<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n3." (".$plan_n4.")</b></td>
		<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n5." (".$plan_n6.")</b></td>
		<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n7."</b></td>
		<td class='Field' style='background:#ccc;' colspan='2'><b>Коэф: </b> <b style='float:right;'> ".$coef_plan."</b></td></tr>";
		$row_lines = $row_lines + 1;
	}
}

echo "<tr><td class='Field' colspan='4' style='background:#ccc;'><b style='font-size:125%; color:red; float:right;'>ИТОГО:</b></td>
	<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n2_s."</b></td>
	<td class='Field' style='background:#ccc; text-align:center;'><b>".$plan_n1_s."</b></td>
	<td class='Field' style='width:80px; background:#ccc; text-align:center;'><b>".$plan_n3_s." (".$plan_n4_s.")</b></td>
	<td class='Field' style='width:100px; background:#ccc; text-align:center;'><b>".$plan_n5_s." (".$plan_n6_s.")</b></td>
	<td class='Field' style='width:70px; background:#ccc; text-align:center;'><b>".$plan_n7_s."</b></td>
	<td class='Field' style='background:#ccc;' colspan='2'><b>Коэф: </b> <b style='float:right;'> ".round($coef_itog/$coef_count,2)."</b></td></tr>";
echo "</table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


?>

</center>