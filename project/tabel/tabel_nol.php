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

$list_page = 1;
$my = explode(".",$_GET["p0"]);
$YY = $my[2];
$MM = $my[1];

$DI_MM = $MM-1;
$DI_YY = $YY;

$date_start = $YY*10000+$MM*100+0;
$date_start2 = $YY*10000+$MM*100+15;
$date_end = $YY*10000+$MM*100+32;
$date_end2 = $YY*10000+$MM*100+16;
$dx = $YY*10000+$MM*100;

$DI_WName = Array('Пн','Вт','Ср','Чт','Пт','Сб','Вс', 'Пн');
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

	if ($_GET['p2']) {
	if ($_GET['p3']==1) { $p_3_1 = $date_start; $p_3_2 = $date_end2;}
	if ($_GET['p3']==2) { $p_3_1 = $date_start2; $p_3_2 = $date_end;}
	}else{
		$p_3_1 = $date_start;
		$p_3_2 = $date_end;
	}

	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (DATE>'".$p_3_1."') and (DATE<'".$p_3_2."') order by DATE");
	while($tab = mysql_fetch_array($xxx)) {


		$res_ids[] = $tab["ID_resurs"];

			$resfact = str_replace(",",".",$tab["FACT"])*1;
			$txt = "<b>".$resfact."</b><br>".$tab["SMEN"];
			if ($tab["TID"]*1>0) $txt = "<b>".FVal($tab,"db_tabel","TID")."</b>";
			if (($tab["TID"]>0) && ($resfact>0)) $txt = $txt."<br><b>".$resfact."</b>/".$tab["SMEN"];

		$res_txt[$tab["ID_resurs"]."x".$tab["DATE"]] = $txt;
		if ($tab["SMEN"]=="1") {
			$res_s1[$tab["ID_resurs"]] = $res_s1[$tab["ID_resurs"]]*1 + $resfact;
			if ($resfact>0) $num_s1[$tab["DATE"]] = $num_s1[$tab["DATE"]]*1 + 1;
			if ($resfact>0) $num_f1[$tab["DATE"]] = $num_f1[$tab["DATE"]]*1 + $resfact;
		}
		if ($tab["SMEN"]=="2") {
			$res_s2[$tab["ID_resurs"]] = $res_s2[$tab["ID_resurs"]]*1 + $resfact;
			if ($resfact>0) $num_s2[$tab["DATE"]] = $num_s2[$tab["DATE"]]*1 + 1;
			if ($resfact>0) $num_f2[$tab["DATE"]] = $num_f2[$tab["DATE"]]*1 + $resfact;
		}
		if ($tab["SMEN"]=="3") {
			$res_s3[$tab["ID_resurs"]] = $res_s3[$tab["ID_resurs"]]*1 + $resfact;
			if ($resfact>0) $num_s3[$tab["DATE"]] = $num_s3[$tab["DATE"]]*1 + 1;
			if ($resfact>0) $num_f3[$tab["DATE"]] = $num_f3[$tab["DATE"]]*1 + $resfact;
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

	function newpage($list_page) {
		global $DI_MM, $DI_YY, $DI_WName;

		if ($_GET['p2']) {
		if ($_GET['p3']==1) { $p_3_1 = 0; $p_3_2 = 15;}
		if ($_GET['p3']==2) { $p_3_1 = 15; $p_3_2 = DI_MNum($DI_MM,$DI_YY);}
			$wid_p = "900px";
		}else{
			$wid_p = "1250px";
			$p_3_1=0;
			$p_3_2 = DI_MNum($DI_MM,$DI_YY);
		}

		echo "</tbody>";
		echo "</table>";
		echo "</div>";

		echo "<div id='Printed' class='a4p' style='width:".$wid_p.";'><b style='float:left;'>Лист №".$list_page."</b><b style='float:right;'>Отчёт от ".date("d.m.Y H:i",mktime())."</b>";
		echo "<table class='rdtbl tbl' style='width:".$wid_p.";' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td rowspan='2'>Ресурс</td>";
			$weekday = DI_FirstDay($DI_MM,$DI_YY);
			for ($j=$p_3_1;$j < $p_3_2;$j++) {
				$cl = " style='padding: 2px; width: 25px;'";
				//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'";
				if (!$_GET['p3']) { if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
				echo "<td class='Field'".$cl.">".($j+1)."</td>";
			}
			echo "<td colspan='3'>По сменам, ч</td>";
			echo "<td rowspan='2'><b>Итого, ч</b></td>";
		echo "</tr>";
		echo "<tr class='first'>";
			$weekday = DI_FirstDay($DI_MM,$DI_YY);
			for ($j=$p_3_1;$j < $p_3_2;$j++) {
				$cl = " style='padding: 2px;'";
				if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px;'";
				if (even_week($j+1,$DI_MM,$DI_YY)) {}
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

	if ($_GET['p2']){
		$rownum = 25;
	}else{
		$rownum = 16;		
	}
		$nnn = 0;

		if ($_GET['p2']) {
			if ($my[1]<10) $my[1]="0".$my[1];
		if ($_GET['p3']==1) { $p_3_1 = 0; $p_3_2 = 15; $h2_titl = "<h2>Табель за 01-15.".$my[1].".".$YY."г</h2>";}
		if ($_GET['p3']==2) { $p_3_1 = 15; $p_3_2 = DI_MNum($DI_MM,$DI_YY); $h2_titl = "<h2>Табель за 16-".$p_3_2.".".$my[1].".".$YY."г</h2>";}
			$h2_titl2 = " месяца";
			$wid_p = "900px";
			$wid_p2 = "";
			$wid_p3 = "";
		}else{
			$h2_titl = "<h2>Табель за ".$MM." ".$YY."г</h2>";
			$wid_p = "1250px";
			$wid_p2 = "width=140px";
			$wid_p3 = "width=50px";
			$p_3_1=0;
			$p_3_2 = DI_MNum($DI_MM,$DI_YY);
		}
		
		echo "<div id='Printed' class='a4p' style='width:".$wid_p.";'><b style='float:left;'>Лист №".$list_page."</b><b style='float:right;'>Отчёт от ".date("d.m.Y H:i",mktime())."</b>";
		echo $h2_titl;
		//echo $p_3_1." = ".$p_3_2;

		echo "<table class='rdtbl tbl' style='width:".$wid_p.";' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td ".$wid_p2." rowspan='2'>Ресурс</td>";
			$weekday = DI_FirstDay($DI_MM,$DI_YY);
			for ($j=$p_3_1;$j < $p_3_2;$j++) {
				$cl = " style='padding: 2px; width: 25px;'";
				//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'";
				if ($_GET['p3']==1) { if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".($j+1)."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
				if ($_GET['p3']==2) { if ($p_3_2<31) { if ($weekday>3) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".($j+1)."</td>"; $weekday = $weekday + 1; if ($weekday>5) $weekday = -1;}}
				if ($_GET['p3']==2) { if ($p_3_2==31) { if (($weekday>3) and ($weekday<6)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".($j+1)."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}}
				if (!$_GET['p3']) { if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".($j+1)."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
			}
			echo "<td colspan='3'>По сменам, ч</td>";
			echo "<td ".$wid_p3." rowspan='2'><b>Итого, ч</b></td>";
		echo "</tr>";
		echo "<tr class='first'>";
			$weekday = DI_FirstDay($DI_MM,$DI_YY);
			for ($j=$p_3_1;$j < $p_3_2;$j++) {
				$cl = " style='padding: 2px;'";
				if ($_GET['p3']==1) { if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".$DI_WName[$weekday]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
				if ($_GET['p3']==2) { if ($p_3_2<31) { if ($weekday>3) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".$DI_WName[$weekday+1]."</td>"; $weekday = $weekday + 1; if ($weekday>5) $weekday = -1;}}
				if ($_GET['p3']==2) { if ($p_3_2==31) { if (($weekday>3) and ($weekday<6)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".$DI_WName[$weekday+1]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}}
				if (!$_GET['p3']) { if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field'".$cl.">".$DI_WName[$weekday]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
			}
			echo "<td style='width: 30px;'>I</td>";
			echo "<td style='width: 30px;'>II</td>";
			echo "<td style='width: 30px;'>III</td>";
		echo "</tr>";
		echo "</thead>";

		echo "<tbody>";			

		if ($_GET['p1']){
			$sel_res = explode("|",$_GET['p1']);
			$in_arr = $sel_res;
			$nalich_sel = 1;
		}
		if (!$_GET['p1']){
			$in_arr = $res_ids;
			$nalich_sel = 0;
		}
		
		$p_2 = $_GET['p2'];
		if ($p_2 == '2') {
		$nalich_sel = 1;
		if ($_GET['p3']==1) { $p_3_1 = 0; $p_3_2 = 15;}
		if ($_GET['p3']==2) { $p_3_1 = 15; $p_3_2 = DI_MNum($DI_MM,$DI_YY);}
		//$x1x = dbquery("SELECT ID FROM ".$db_prefix."db_resurs where ID_users='".$user['ID']."'");
		//$xr1 = mysql_fetch_array($x1x);
		//$x2x = dbquery("SELECT ID_otdel FROM ".$db_prefix."db_shtat where ID_resurs='".$xr1["ID"]."'");
		//$xr2 = mysql_fetch_array($x2x);
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where TID='0' and ID_tab='".$user['ID']."' order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (($res["ID"]!=='0')and(in_array($res["ID"],$in_arr))) {
			$summ = $res_s1[$res["ID"]] + $res_s2[$res["ID"]] + $res_s3[$res["ID"]];
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field'><b>".$res["NAME"]."</b></td>";
					//for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
					$weekday = DI_FirstDay($DI_MM,$DI_YY);
					for ($j=$p_3_1;$j < $p_3_2;$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						if ($_GET['p3']==1) { if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
						if ($_GET['p3']==2) { if ($p_3_2<31) { if ($weekday>3) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; $weekday = $weekday + 1; if ($weekday>5) $weekday = -1;}}
						if ($_GET['p3']==2) { if ($p_3_2==31) { if (($weekday>3) and ($weekday<6)) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}}
					}

					echo "<td class='Field AC'>".$res_s1[$res["ID"]]."</td>";
					echo "<td class='Field AC'>".$res_s2[$res["ID"]]."</td>";
					echo "<td class='Field AC'>".$res_s3[$res["ID"]]."</td>";
					echo "<td class='Field AC'><b>".$summ."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					if ($_GET['p2']){
						$rownum = 27;
					}else{
						$rownum = 18;		
					}
					$list_page = $list_page + 1;
					newpage($list_page);
					$nnn = 0;
				}
			}
		}
		}else{
		$xxx = dbquery("SELECT NAME, ID, TID FROM okb_db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (($res["ID"]!=='0')and(in_array($res["ID"],$in_arr))) {
			$summ = $res_s1[$res["ID"]] + $res_s2[$res["ID"]] + $res_s3[$res["ID"]];
			if (($res['TID']=='0')or(($res['TID']=='1')and($summ>0))){
				$xx2x = dbquery("SELECT ID_otdel FROM okb_db_shtat where ID_resurs=".$res["ID"]);
				$re2s = mysql_fetch_array($xx2x);
				if ($re2s['ID_otdel']!=="38"){
				
				$dd1 = explode(".",$_GET['p0']);
				$dd2 = (date("d")-1)*1;
				if ($dd2<10) $dd2 = "0".$dd2;
				$dd3 = $dd1[1]*1;
				if ($dd3<10) $dd3 = "0".$dd3;
				$dat_cur2 = $dd1[2].$dd3.$dd2;
				$dat_cur = 0;
				for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
					$date = $dx + $j + 1;
					$j2 = $j;
					if ($j2<10) $j2="0".$j2;
					$dat_cur3 = date("Ym").$j2;
					if (($dd1[2].$dd3)<date("Ym")){
					if ((strlen($res_txt[$res["ID"]."x".$date])>1) and (substr($res_txt[$res["ID"]."x".$date],3,1)=="0")) {
						$dat_cur = 1;
					}else{
						if ($dat_cur!==1) $dat_cur = 0;
					}}
						if (($dd1[2].$dd3)>=date("Ym")){
					if ((strlen($res_txt[$res["ID"]."x".$date])>1) and (substr($res_txt[$res["ID"]."x".$date],3,1)=="0") and ($dat_cur3<=$dat_cur2)) {
						$dat_cur = 1;
					}else{
						if ($dat_cur!==1) $dat_cur = 0;
					}}
				}
									
				if ($dat_cur == 1){
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field'><b>".$res["NAME"]."</b></td>";
					$weekday = DI_FirstDay($DI_MM,$DI_YY);
					
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$j2 = $j;
						if ($j2<10) $j2="0".$j2;
						$dat_cur3 = date("Ym").$j2;
						$cl = " style='padding: 2px;'";
						if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 
						if (($dd1[2].$dd3)>=date("Ym")){
						if ((substr($res_txt[$res["ID"]."x".$date],3,1)=="0") and (strlen($res_txt[$res["ID"]."x".$date])>1) and ($dat_cur3<=$dat_cur2)) {
							echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
						}else{
							echo "<td class='Field AC'".$cl."></td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
						}}
						if (($dd1[2].$dd3)<date("Ym")){
						if ((substr($res_txt[$res["ID"]."x".$date],3,1)=="0") and (strlen($res_txt[$res["ID"]."x".$date])>1)) {
							echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
						}else{
							echo "<td class='Field AC'".$cl."></td>"; $weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
						}}
					}

					echo "<td class='Field AC'>".$res_s1[$res["ID"]]."</td>";
					echo "<td class='Field AC'>".$res_s2[$res["ID"]]."</td>";
					echo "<td class='Field AC'>".$res_s3[$res["ID"]]."</td>";
					echo "<td class='Field AC'><b>".$summ."</b></td>";
				echo "</tr>";
				}
				
				if ($nnn>$rownum) {
					if ($_GET['p2']){
						$rownum = 27;
					}else{
						$rownum = 18;		
					}
					$list_page = $list_page + 1;
					newpage($list_page);
					$nnn = 0;
				}
			}}}
		}
		}
		

		/////////////////////////////////////////////////////////////////////////////////////////////


				// Вывод итого
		if ($_GET['p2']) {
		if ($_GET['p3']==1) { $p_3_1 = 0; $p_3_2 = 15;}
		if ($_GET['p3']==2) { $p_3_1 = 15; $p_3_2 = DI_MNum($DI_MM,$DI_YY);}
		}
		
			if ($nalich_sel == 0) {
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>Итого I смена</b></td>";
					$weekday = DI_FirstDay($DI_MM,$DI_YY);
					for ($j=$p_3_1;$j < $p_3_2;$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 
						echo "<td class='Field AC'".$cl."><b>".$num_f1[$date]."</b><br>".$num_s1[$date]."</td>";
						$summ_f = $summ_f + $num_f1[$date];
						$weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					if ($_GET['p2']){
						$rownum = 27;
					}else{
						$rownum = 18;		
					}
					newpage();
					$nnn = 0;
				}
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>Итого II смена</b></td>";
					$weekday = DI_FirstDay($DI_MM,$DI_YY);
					for ($j=$p_3_1;$j < $p_3_2;$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 
						echo "<td class='Field AC'".$cl."><b>".$num_f2[$date]."</b><br>".$num_s2[$date]."</td>";
						$summ_f = $summ_f + $num_f2[$date];
						$weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					if ($_GET['p2']){
						$rownum = 27;
					}else{
						$rownum = 18;		
					}
					newpage();
					$nnn = 0;
				}
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>Итого III смена</b></td>";
					$weekday = DI_FirstDay($DI_MM,$DI_YY);
					for ($j=$p_3_1;$j < $p_3_2;$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 
						echo "<td class='Field AC'".$cl."><b>".$num_f3[$date]."</b><br>".$num_s3[$date]."</td>";
						$summ_f = $summ_f + $num_f3[$date];
						$weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) {
					if ($_GET['p2']){
						$rownum = 27;
					}else{
						$rownum = 18;		
					}
					newpage();
					$nnn = 0;
				}
				$summ_f = 0;
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
					echo "<td class='Field' style='width: 120px;'><b>ИТОГО</b></td>";
					$weekday = DI_FirstDay($DI_MM,$DI_YY);
					for ($j=0;$j < DI_MNum($DI_MM,$DI_YY);$j++) {
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						//if (even_week($j+1,$DI_MM,$DI_YY)) $cl = " style='background: #ffeac8; padding: 2px;'";
						if ($weekday>4) $cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 
						$ss = $num_f1[$date] + $num_f2[$date] + $num_f3[$date];
						$ssn = $num_s1[$date] + $num_s2[$date] + $num_s3[$date];
						echo "<td class='Field AC'".$cl."><b>".$ss."</b><br>".$ssn."</td>";
						$summ_f = $summ_f + $ss;
						$weekday = $weekday + 1; if ($weekday>6) $weekday = 0;
					}
					echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
				echo "</tr>";
			}

		/////////////////////////////////////////////////////////////////////////////////////////////
		
		if ((!$_GET['p1']) and (!$_GET['p2'])) {
		$itr_res15 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE (ID_special='45')");
		$itr_res15_1 = mysql_fetch_array($itr_res15);
		
		echo "<tr></tr><tr>
		<td width='40px'>
		<td colspan='9' width='600px' style='font-size:13pt; text-align:left;'>Менеджер по персоналу</td>
		<td colspan='12' width='300px'>_________________________________________</td>
		<td colspan='7' width='350px' style='font-size:13pt; text-align:left;'>".$itr_res15_1['NAME']."</td>
		</tr>";
		}
		
		if ($_GET['p2']) {
		$x1x = dbquery("SELECT ID FROM ".$db_prefix."db_resurs where ID_users='".$user['ID']."'");
		$xr1 = mysql_fetch_array($x1x);
		$x2x = dbquery("SELECT ID_otdel FROM ".$db_prefix."db_shtat where ID_resurs='".$xr1["ID"]."'");
		$xr2 = mysql_fetch_array($x2x);
		$x3x = dbquery("SELECT NAME, ID_special, ID_otdel FROM ".$db_prefix."db_shtat where ID_otdel='".$xr2["ID_otdel"]."' AND BOSS='1' ");
		$xr3 = mysql_fetch_array($x3x);
		$x4x = dbquery("SELECT NAME FROM ".$db_prefix."db_special where ID='".$xr3["ID_special"]."'");
		$xr4 = mysql_fetch_array($x4x);
		$x5x = dbquery("SELECT NAME FROM ".$db_prefix."db_otdel where ID='".$xr3["ID_otdel"]."'");
		$xr5 = mysql_fetch_array($x5x);
		
		echo "<tr></tr><tr></tbody></table><table width=900px><tbody>
		<td width='40px'></td>
		<td style='font-size:13pt; text-align:left;'>".$xr5['NAME']."</td>
		</tr><tr>
		<td width='40px'>
		<td style='font-size:13pt; text-align:left;'>".$xr4['NAME']."</td>
		<td width='225px'>     _______________________________     </td>
		<td width='200px' style='font-size:13pt; text-align:left;'>".$xr3['NAME']."</td>
		</tr>";
		}
		
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		
		/*$list_page = $list_page + 1;
		echo "<div id='Printed' class='a4p'><b style='float:left;'>Лист №".$list_page."</b><b style='float:right;'>Табель за ".$MM." ".$YY."г</b>";
		echo "<table class='rdtbl tbl' style='width: 1250px;' cellpadding='0' cellspacing='0'>\n";

		$itr_res1 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='18') AND (BOSS='1'))");
		$itr_res1_1 = mysql_fetch_array($itr_res1);
		$itr_res2 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='19') AND (BOSS='1'))");
		$itr_res2_1 = mysql_fetch_array($itr_res2);
		$itr_res3 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='21') AND (BOSS='1'))");
		$itr_res3_1 = mysql_fetch_array($itr_res3);
		$itr_res4 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='22') AND (BOSS='1'))");
		$itr_res4_1 = mysql_fetch_array($itr_res4);
		$itr_res5 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='43') AND (BOSS='1'))");
		$itr_res5_1 = mysql_fetch_array($itr_res5);
		$itr_res6 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='9') AND (BOSS='1'))");
		$itr_res6_1 = mysql_fetch_array($itr_res6);
		$itr_res7 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='31') AND (BOSS='1'))");
		$itr_res7_1 = mysql_fetch_array($itr_res7);
		$itr_res8 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='23') AND (BOSS='1'))");
		$itr_res8_1 = mysql_fetch_array($itr_res8);
		$itr_res9 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='35') AND (BOSS='1'))");
		$itr_res9_1 = mysql_fetch_array($itr_res9);
		$itr_res10 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='33') AND (BOSS='1'))");
		$itr_res10_1 = mysql_fetch_array($itr_res10);
		$itr_res11 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='54') AND (BOSS='1'))");
		$itr_res11_1 = mysql_fetch_array($itr_res11);
		$itr_res12 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='45') AND (BOSS='1'))");
		$itr_res12_1 = mysql_fetch_array($itr_res12);
		$itr_res13 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='12') AND (BOSS='1'))");
		$itr_res13_1 = mysql_fetch_array($itr_res13);
		$itr_res14 = dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ((ID_otdel='8') AND (BOSS='1'))");
		$itr_res14_1 = mysql_fetch_array($itr_res14);
			
		echo "<tbody><tr></tr>
		<tr><td width='40px'><td style='font-size:13pt; text-align:left;'>Утверждаю</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='560px' style='font-size:13pt; text-align:left;'>Директор</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res7_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		</tr>
		<tr><td width='40px'><td style='font-size:13pt; text-align:left;'>Согласовано:</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Заместитель технического директора по подготвке производства</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res6_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Главный инженер</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res8_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Заместитель главного инженера по строительству</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res9_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Главный механик</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res10_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Главный бухгалтер</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res11_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Заместитель коммерческого директора</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res12_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Начальник ОМТС</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res13_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Начальник отдела информационных технологий</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res5_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Начальник ОТК</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res14_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Старший мастер</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res1_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Мастер</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res2_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Мастер</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res3_1['NAME']."</td>
		</tr><tr></tr>
		<tr>
		<td width='40px'>
		<td width='600px' style='font-size:13pt; text-align:left;'>Мастер</td>
		<td width='300px'>_________________________________________</td>
		<td width='350px' style='font-size:13pt; text-align:left;'>".$itr_res4_1['NAME']."</td>
		</tr>
		</tbody>";
		
		echo "</table>";
		echo "</div>";*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($_GET['p2']) echo "<script>
var tableses = document.getElementsByClassName('rdtbl tbl').length;
for (var a_m = 0; a_m < tableses; a_m++){
	var tbl_tds = document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td').length;
	for (var b_m = 0; b_m < tbl_tds; b_m++){
		document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td')[b_m].style.fontSize='125%';
		var td_bs = document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td')[b_m].getElementsByTagName('b').length;
		for (var c_m = 0; c_m < td_bs; c_m++){
			document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td')[b_m].getElementsByTagName('b')[c_m].style.fontSize='115%';
		}
	}
}
</script>";
?>
</center>