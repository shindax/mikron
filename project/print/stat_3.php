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
</style>
<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$step = 1;

	$date1 = $_GET["p1"];
	$date2 = $_GET["p2"];
	$pdate1 = DateToInt($date1);
	$pdate2 = DateToInt($date2);

	if (($pdate1>0) && ($pdate2>=$pdate1)) $step = 2;

	if ($step==2) {
		$zak_IDs = $_GET["p0"];
		if (count($zak_IDs)>0) $step = 3;
	}



if ($step==1) {


	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";


	echo "<h2>Отчёт по заказам за период</h2>";

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>Параметр</td>";
	echo "<td>Значение</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>Дата начальная:</b></td><td class='rwField ntabg'>";
	Input("date","p1",TodayDate());
	echo "</td></tr>\n";

	echo "<tr><td class='Field first'><b>Дата конечная:</b></td><td class='rwField ntabg'>";
	Input("date","p2",TodayDate());
	echo "</td></tr>\n";

	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='Расчёт'></td></tr></table>";

}


if ($step==2) {

if ($user ['ID'] == 1)  {
	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";
	echo "<input type='hidden' name='p1' value='".$_GET["p1"]."'>";
	echo "<input type='hidden' name='p2' value='".$_GET["p2"]."'>";


	echo "<h2>Отчёт по заказам за период - выбор заказов</h2>";

	echo "<br><input type='submit' value='Расчёт'><span style='margin-left: 40px;'>";
	Input("boolean","p4",0);
	echo " - По плановой цене Н/Ч</span><br><br>";

		$where = "(ID='0')";
		
		$ZakIDs = Array();
		$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE <= '".$pdate2."') and (DATE >= '".$pdate1."')");
		while ($zakzad = mysql_fetch_array($yyy)) {
			$newID = $zakzad["ID_zak"];
			if (!in_array($newID,$ZakIDs)) {
				$ZakIDs[] = $newID;
			}
		}
		if (count($ZakIDs)>0) $where = "(ID='".implode("') or (ID='",$ZakIDs)."')";

	render_item(80,false,false,false,false,$where,"","order by ORD","");
} else {


	echo "</form><form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";
	echo "<input type='hidden' name='p1' value='".$_GET["p1"]."'>";
	echo "<input type='hidden' name='p2' value='".$_GET["p2"]."'>";


		echo "<h2>Отчёт по заказам за период - выбор заказов</h2>";
 
	echo '<input style="float:right" type="submit" value="Расчёт"><br/><br/><table class="rdtbl tbl" style="border-collapse: collapse; border: 0px solid black; text-align: left; color: rgb(0, 0, 0); width: 900px; padding: 0px;" border="1" cellpadding="0" cellspacing="0">
	<thead>
	<tr class="first">
		<td>Выбор<br><input type="checkbox" name="selectall" onclick="select_checkbox(\'p0[]\',this);"></td>
		<td>Заказ</td>
		<td>ДСЕ</td>
		<td  >Дата поставки</td>
		<td  style="width:200px;">Примечание </td>
	</tr>
	<tbody>';


		$where = "(ID='0')";
		
		$ZakIDs = Array();
		$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE <= '".$pdate2."') and (DATE >= '".$pdate1."')");
		while ($zakzad = mysql_fetch_array($yyy)) {
			$newID = $zakzad["ID_zak"];
			if (!in_array($newID,$ZakIDs)) {
				$ZakIDs[] = $newID;
			}
		}
		if (count($ZakIDs)>0) $where = "(ID='".implode("') or (ID='",$ZakIDs)."')";

		
		$query = dbquery("SELECT * FROM okb_db_zak WHERE ". "(ID='".implode("') or (ID='",$ZakIDs)."')");
		
		while ($row = mysql_fetch_assoc($query)) {
			
			
			echo '<tr><td class="Field"  style="text-align:center"><input type=\'checkbox\' name=\'p0[]\' value=\'' . $row['ID'] . '\'></td>
			
			<td class="Field"  nowrap="nowrap">'. mysql_result(dbquery("SELECT description FROM okb_db_zak_type WHERE id = " . $row['TID']), 0) . ' ' . $row['NAME'] . '</td>
			<td class="Field" nowrap="nowrap">' . $row['DSE_NAME'] . ' — ' . $row['DSE_OBOZ'] . '</td>
			<td class="Field"  nowrap="nowrap" >' . array_shift (explode(' ', end(explode('#', $row['PD11'])))) . '</td>
			<td class="Field" nowrap="nowrap" colspan=2  style="width:200px;"  > </td>
			
			</tr>
			';
		}

		
	}

}


if ($step==3) {


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
		$ret = number_format( ($x*1), 2, ',', '');
		if ($x==floor($x)) $ret = number_format($x, 0, ',', '');
		return $ret;
	}

	// План Н/ч, Факт Н/Ч, Затр. ч
	function OutNF($norm,$pnorm,$fact) {
		$res = FReal($norm)."<br>".FReal($pnorm)."<br>".FReal($fact);
		if ($res == "0<br>0<br>0") $res = "";
		return $res;
	}


	function DI_MNum($Mon, $Year) {
		$nn = Array(31,28,31,30,31,30,31,31,30,31,30,31);
		$x = 28;
		$y = (Round($Year/4))*4;
		if ($y==$Year) $x = 29;
		$ret = $nn[$Mon];
		if ($Mon==1) $ret = $x;
		return $ret;
	}


   // ДАТЫ
	$days = array();
	for ($i=$pdate1;$i < ($pdate2+1);$i++) {
		$dd = IntToDate($i);
		$dd = explode(".",$dd);
		if (($dd[0]<DI_MNum($dd[1]-1,$dd[2]*1)+1) && ($dd[0]!=="00")) $days[] = $i;
	}


   // Шапка

	echo "<h2>Отчёт по заказам за период:</h2>";
	if ($_GET["p4"]=="on") echo "<h4>(по плановой цене Н/Ч)</h4>";
	echo "<h3>".$date1." - ".$date2."</h3>";

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "<thead>";
	echo "<tr class='first'>";
	echo "<td>Заказ</td>";
	for ($d=0;$d < count($days);$d++) echo "<td width='40'>".ConvertToVertical(IntToDate($days[$d]))."</td>";
	echo "<td width='80'><b>ИТОГО:</b></td>";
	echo "</tr>";
	echo "</thead>";



		$szd_np = Array();
		$szd_nf = Array();
		$szd_f = Array();

		$sz_np = Array();
		$sz_nf = Array();
		$sz_f = Array();

		$sd_np = Array();
		$sd_nf = Array();
		$sd_f = Array();

		$s_np = 0;
		$s_nf = 0;
		$s_f = 0;

		$prices = Array();

		$where = "(ID='".implode("') or (ID='",$zak_IDs)."')";

		$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zak where ".$where);
		while ($zak = mysql_fetch_array($yyy)) {
			$prices[$zak["ID"]] = $zak["NORM_PRICE"]*1;
		}

		$where = " and ((ID_zak='".implode("') or (ID_zak='",$zak_IDs)."'))";

		$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE <= '".$pdate2."') and (DATE >= '".$pdate1."')".$where);
		while ($zakzad = mysql_fetch_array($yyy)) {

			$z = $zakzad["ID_zak"];
			$d = $zakzad["DATE"];
			$zd = $z."|".$d;

			$cc = 1;
			if ($_GET["p4"]=="on") $cc = $prices[$z]*1;

			$szd_np[$zd] = $szd_np[$zd]*1 + $zakzad["NORM"]*$cc;
			$sz_np[$z] = $sz_np[$z]*1 + $zakzad["NORM"]*$cc;
			$sd_np[$d] = $sd_np[$d]*1 + $zakzad["NORM"]*$cc;
			$s_np = $s_np + $zakzad["NORM"]*$cc;

			if ($zakzad["EDIT_STATE"]*1==1) {
				$szd_nf[$zd] = $szd_nf[$zd]*1 + $zakzad["NORM_FACT"]*$cc;
				$sz_nf[$z] = $sz_nf[$z]*1 + $zakzad["NORM_FACT"]*$cc;
				$sd_nf[$d] = $sd_nf[$d]*1 + $zakzad["NORM_FACT"]*$cc;
				$s_nf = $s_nf + $zakzad["NORM_FACT"]*$cc;

				$szd_f[$zd] = $szd_f[$zd]*1 + $zakzad["FACT"]*$cc;
				$sz_f[$z] = $sz_f[$z]*1 + $zakzad["FACT"]*$cc;
				$sd_f[$d] = $sd_f[$d]*1 + $zakzad["FACT"]*$cc;
				$s_f = $s_f + $zakzad["FACT"]*$cc;
			}
		}


	echo "<tbody>";
	for ($j=0;$j < count($zak_IDs);$j++) {
		echo "<tr>";
		
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$zak_IDs[$j]."')");
			$zak = mysql_fetch_array($xxx);

			echo "<td class='Field AL'><b>".FVal($zak,"db_zak","TID")." ".FVal($zak,"db_zak","NAME")."</b><br>".FVal($zak,"db_zak","DSE_NAME")." - ".FVal($zak,"db_zak","DSE_OBOZ")."</td>";

			$z = $zak_IDs[$j];
			for ($d=0;$d < count($days);$d++) {
				$zd = $z."|".$days[$d];
				echo "<td class='Field AC'>".OutNF($szd_np[$zd],$szd_nf[$zd],$szd_f[$zd])."</td>";
			}
			echo "<td class='Field AC'><b>".OutNF($sz_np[$z],$sz_nf[$z],$sz_f[$z])."</b></td>";

		echo "</tr>";
	}

			echo "<td class='Field AR'><b>ИТОГО:</b></td>";

			for ($d=0;$d < count($days);$d++) {
				$dd = $days[$d];
				echo "<td class='Field AC'><b>".OutNF($sd_np[$dd],$sd_nf[$dd],$sd_f[$dd])."</b></td>";
			}
			echo "<td class='Field AC'><b>".OutNF($s_np,$s_nf,$s_f)."</b></td>";


	echo "</tbody>";
	echo "</table>\n";

	if ($_GET["p4"]!=="on") echo "<br><br><b>* Формат данных:</b><br><br><div style='margin-left: 30px;'>План Н/ч<br>Факт Н/Ч<br>Факт ч.</div>";
	if ($_GET["p4"]=="on") echo "<br><br><b>* Формат данных:</b><br><br><div style='margin-left: 30px;'>План руб. ч/з Н/Ч<br>Факт руб. ч/з Н/Ч<br>Затр. руб. ч/з факт ч.</div>";

}
?>
