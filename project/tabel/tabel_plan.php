<STYLE>
TD.Field {
	vertical-align: middle;
}

div.a4p {
	width : 1250px;
	text-align: left;
	background: #fff;
	page-break-after:always;
}

.view div.a4p {
	display: block;
	border: 1px solid #444;
	padding: 20px;
	box-shadow: 3px 4px 20px #555555;
	margin: 40px;
}

table.view {
	width: 100%;
	margin: 0px;
	padding: 0px;
}


-->
</style>
<center>
<?php

$otdel = $_GET["p1"];		// ID отдела db_otdel
$my = explode(".",$_GET["p0"]);
$childs = false;
if ($_GET["p2"]=="1") $childs = true;


//////////////////////////////////////////////////////////////////////

$YY = $my[2];
$MM = $my[1];

$DI_MM = $MM-1;
$DI_YY = $YY;

$date_start = $YY*10000+$MM*100+0;
$date_end = $YY*10000+$MM*100+32;
$dx = $YY*10000+$MM*100;

$DI_WName = Array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');
$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

$MM = $DI_MName[$MM-1];




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Получаем список ID
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$res_ids = Array();
	$res_txt = Array();
	$res_s1 = Array();
	$res_s2 = Array();
	$res_s3 = Array();
	$num_s1 = Array();
	$num_s2 = Array();
	$num_s3 = Array();
	$num_f1 = Array();
	$num_f2 = Array();
	$num_f3 = Array();


	function openid($item) {
		global $db_prefix, $res_ids, $childs;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel='".$item["ID"]."')");
		while ($shtat = mysql_fetch_array($xxx)) {
			$res_ids[] = $shtat["ID_resurs"];
		}

		if ($childs) {
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$item["ID"]."')");
			while ($otd = mysql_fetch_array($xxx)) openid($otd);
		}
	}


	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (ID='".$otdel."')");
	if ($otdel = mysql_fetch_array($xxx)) openid($otdel);


	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (DATE>'".$date_start."') and (DATE<'".$date_end."') order by DATE");
	while($tab = mysql_fetch_array($xxx)) {

	    if (in_array($tab["ID_resurs"],$res_ids)) {
	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$resplan = str_replace(",",".",$tab["PLAN"])*1;
			$txt = "<b>".$resplan."</b><br>".$tab["SMEN"];
			if ($tab["TID"]*1>0) $txt = "<b>".FVal($tab,"db_tabel","TID")."</b>";
			if (($tab["TID"]>0) && ($resplan>0)) $txt = $txt."<br><b>".$resplan."</b>/".$tab["SMEN"];

		$res_txt[$tab["ID_resurs"]."x".$tab["DATE"]] = $txt;
		if ($tab["SMEN"]=="1") {
			$res_s1[$tab["ID_resurs"]] = $res_s1[$tab["ID_resurs"]]*1 + $resplan;
			if ($resplan>0) $num_s1[$tab["DATE"]] = $num_s1[$tab["DATE"]]*1 + 1;
			if ($resplan>0) $num_f1[$tab["DATE"]] = $num_f1[$tab["DATE"]]*1 + $resplan;
		}
		if ($tab["SMEN"]=="2") {
			$res_s2[$tab["ID_resurs"]] = $res_s2[$tab["ID_resurs"]]*1 + $resplan;
			if ($resplan>0) $num_s2[$tab["DATE"]] = $num_s2[$tab["DATE"]]*1 + 1;
			if ($resplan>0) $num_f2[$tab["DATE"]] = $num_f2[$tab["DATE"]]*1 + $resplan;
		}
		if ($tab["SMEN"]=="3") {
			$res_s3[$tab["ID_resurs"]] = $res_s3[$tab["ID_resurs"]]*1 + $resplan;
			if ($resplan>0) $num_s3[$tab["DATE"]] = $num_s3[$tab["DATE"]]*1 + 1;
			if ($resplan>0) $num_f3[$tab["DATE"]] = $num_f3[$tab["DATE"]]*1 + $resplan;
		}

	    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    }
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	ФУНКЦИИ
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function DI_MNum($Mon, $Year) {
		$nn = Array(31,28,31,30,31,30,31,31,30,31,30,31);
		$x = 28;
		$y = (Round($Year/4))*4;
		if ($y==$Year) $x = 29;
		$ret = $nn[$Mon];
		if ($Mon==1) $ret = $x;
		return $ret;
	}

	function DI_FirstDay($Mon,$Year) {
		$x0 = 365;
		$Y = $Year-1;
		$days = $Y*$x0+floor($Y/4)+6;
		for ($j=0; $j<$Mon; $j=$j+1) {
			$days = $days+DI_MNum($j,$Year);
		}
		$week = $days-(7*Round(($days/7)-0.5));
		return $week;
	}

	function DI_WeekDay($Day,$Mon,$Year) {
		$res = DI_FirstDay($Mon,$Year);
		for ($j=1; $j<$Day; $j=$j+1) {
			$res = $res + 1;
			if ($res>6) $res=0;
		}
		return $res;
	}

	function even_week($Day,$Mon,$Year) {
		$x0 = 365;
		$Y = $Year-1;
		$days = $Y*$x0+floor($Y/4)+6;
		for ($j=0; $j<$Mon; $j=$j+1) {
			$days = $days+DI_MNum($j,$Year);
		}
		$days = $days + $Day;
		$weeks = ceil($days/7);

		$res = false;
		if (2*ceil($weeks/2) == $weeks) $res = true;
		return $res;
	}

	function newpage() {
		global $DI_MM, $DI_YY, $DI_WName;


		echo "</tbody>";
		echo "</table>";
		echo "</div>";


		echo "<div id='Printed' class='a4p'>Отчёт от ".date("d.m.Y H:i",mktime());
		echo "<table class='rdtbl tbl' style='width: 1250px;' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td rowspan='2'>Ресурс</td>";
			for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
				$cl = " style='padding: 2px; width: 25px;'";
				if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'";
				echo "<td class='Field'".$cl.">".($j+1)."</td>";
			}
			echo "<td colspan='3'>По сменам, ч</td>";
			echo "<td rowspan='2'><b>Итого, ч</b></td>";
		echo "</tr>";
		echo "<tr class='first'>";
			$weekday = DI_FirstDay($DI_MM,$DI_YY);
			for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
				$cl = " style='padding: 2px;'";
				if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
				echo "<td class='Field'".$cl.">".$DI_WName[$weekday]."</td>";
				$weekday = $weekday + 1;
				if ($weekday>6) $weekday = 0;
			}
			echo "<td style='width: 30px;'>I</td>";
			echo "<td style='width: 30px;'>II</td>";
			echo "<td style='width: 30px;'>III</td>";
		echo "</tr>";
		echo "</thead>";

		echo "<tbody>";	

	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$rownum = 15;
		$nnn = 0;

		echo "<div id='Printed' class='a4p'>Отчёт от ".date("d.m.Y H:i",mktime());
		echo "<h2>График работ на ".$MM." ".$YY."г</h2>";

		echo "<center><b>Отдел: ".$otdel["NAME"]."</b></center>";

		echo "<table class='rdtbl tbl' style='width: 1250px;' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td rowspan='2'>Ресурс</td>";
			for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
				$cl = " style='padding: 2px; width: 25px;'";
				if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'";
				echo "<td class='Field'".$cl.">".($j+1)."</td>";
			}
			echo "<td colspan='3'>По сменам, ч</td>";
			echo "<td rowspan='2'><b>Итого, ч</b></td>";
		echo "</tr>";
		echo "<tr class='first'>";
			$weekday = DI_FirstDay($DI_MM,$DI_YY);
			for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
				$cl = " style='padding: 2px;'";
				if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
				echo "<td class='Field'".$cl.">".$DI_WName[$weekday]."</td>";
				$weekday = $weekday + 1;
				if ($weekday>6) $weekday = 0;
			}
			echo "<td style='width: 30px;'>I</td>";
			echo "<td style='width: 30px;'>II</td>";
			echo "<td style='width: 30px;'>III</td>";
		echo "</tr>";
		echo "</thead>";

		echo "<tbody>";			
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$res_ids)) {
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field'>".$res["NAME"]."</td>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>";
					}

					$summ = $res_s1[$res["ID"]] + $res_s2[$res["ID"]] + $res_s3[$res["ID"]];
					echo "<td class='Field AC'>".$res_s1[$res["ID"]]."</td>";
					echo "<td class='Field AC'>".$res_s2[$res["ID"]]."</td>";
					echo "<td class='Field AC'>".$res_s3[$res["ID"]]."</td>";
					echo "<td class='Field AC'><b>".$summ."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					$rownum = 18;
					newpage();
					$nnn = 0;
				}
			}
		}

		/////////////////////////////////////////////////////////////////////////////////////////////


				// Вывод итого
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>Итого I смена</b></td>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						echo "<td class='Field AC'".$cl."><b>".$num_f1[$date]."</b><br>".$num_s1[$date]."</td>";
						$summ_f = $summ_f + $num_f1[$date];
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					$rownum = 18;
					newpage();
					$nnn = 0;
				}
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>Итого II смена</b></td>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						echo "<td class='Field AC'".$cl."><b>".$num_f2[$date]."</b><br>".$num_s2[$date]."</td>";
						$summ_f = $summ_f + $num_f2[$date];
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					$rownum = 18;
					newpage();
					$nnn = 0;
				}
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>Итого III смена</b></td>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						echo "<td class='Field AC'".$cl."><b>".$num_f3[$date]."</b><br>".$num_s3[$date]."</td>";
						$summ_f = $summ_f + $num_f3[$date];
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					$rownum = 18;
					newpage();
					$nnn = 0;
				}
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>ИТОГО</b></td>";
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						$ss = $num_f1[$date] + $num_f2[$date] + $num_f3[$date];
						$ssn = $num_s1[$date] + $num_s2[$date] + $num_s3[$date];
						echo "<td class='Field AC'".$cl."><b>".$ss."</b><br>".$ssn."</td>";
						$summ_f = $summ_f + $ss;
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";

		/////////////////////////////////////////////////////////////////////////////////////////////

		echo "</tbody>";
		echo "</table>";
		echo "</div>"

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
</center>