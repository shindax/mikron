<style>
table.view TD.DIW_TD {
	border: 1px solid black;
	padding: 10px;
	background: #98b8e2;
	text-align: center;
	font: bold 16px Arial;
}
table.view TD.DIWW_TD {
	border: 1px solid black;
	padding: 0px;
	margin: 0px;
	text-align: center;
}
SPAN.DI_DD {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #f5f5f5;
}
SPAN.DI_DD2 {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #ffeac8;
}
SPAN.DI_DDHL {
	display: block;
	padding: 5px;
	margin: 0px;
	font: bold 16px Arial;
	color: #555;
	background: #7ab4ff;
}
A.DD {
	background: none;
	padding-left: 14px;
	padding-right: 14px;
	margin: 0px;
	text-decoration: none;
	font: normal 11px Arial;	
}
A.DD:hover {
	background: #ff967a;
	text-decoration: none;	
}
A.DD2 {
	background: URL(project/zadan/hl.png) no-repeat;
	padding: 1px 14px 1px 14px;
	margin: 0px;
	text-decoration: none;
	font: normal 11px Arial;	
}
A.DD3 {
	background: URL(project/zadan/hl3.png) no-repeat;
	padding: 1px 14px 1px 14px;
	margin: 0px;
	text-decoration: none;
	font: normal 11px Arial;
	color: #fff;	
}

A.DD4 {
	background: URL(project/zadan/hl4.png) no-repeat;
	padding: 1px 14px 1px 14px;
	margin: 0px;
	text-decoration: none;
	font: normal 11px Arial;
	color: #fff;		
}
A.DD2:hover {
	background: #ff967a;
	text-decoration: none;	
}

A.DD3:hover {
	background: #ff967a;
	text-decoration: none;
	color: #23609e;
}

A.DD4:hover {
	background: #ff967a;
	text-decoration: none;
	color: #23609e;	
}
A.lnk {
	text-decoration: none;
	font: normal 16px "Lucida Console";
}
</style>
<?php

	$show_url = "index.php?do=show&formid=64&p0=";
	$page_url = "index.php?do=show&formid=63";

	$DI_WName = Array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');
	$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

	$today = TodayDate();
	$today = explode(".",$today);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$DI_Date = TodayDate();
	if (isset($_GET["p0"])) $DI_Date = $_GET["p0"];
	$DI_Date = explode(".",$DI_Date);

	$DI_YY = $DI_Date[2];
	$DI_LYY = $DI_YY;
	$DI_NYY = $DI_YY;

	$DI_MM = $DI_Date[1]-1;
	$DI_LMM = $DI_MM-1;
	if ($DI_LMM<0) $DI_LMM = 11;
	$DI_NMM = $DI_MM+1;
	if ($DI_NMM>11) $DI_NMM = 0;

	if ($DI_MM==0) $DI_LYY = $DI_YY-1;
	if ($DI_MM==11) $DI_NYY = $DI_YY+1;

	$DI_DD = $DI_Date[0];

	$lastM = $DI_MM-2;
	$yy = $DI_YY;
	if ($lastM<1) {
		$lastM = 12+$lastM;
		$yy = $yy - 1;
	}
	$lastM = $DI_DD.".".$lastM.".".$yy;

	$nextM = $DI_MM+4;
	$yy = $DI_YY;
	if ($nextM>12) {
		$nextM = $nextM-12;
		$yy = $yy + 1;
	}
	$nextM = $DI_DD.".".$nextM.".".$yy;

	$lastY = $DI_DD.".".($DI_MM+1).".".($DI_YY-1);
	$nextY = $DI_DD.".".($DI_MM+1).".".($DI_YY+1);





   // Функции
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

	function OutWW() {
		global $DI_WName;

		echo "<tr>\n";
		for ($j=0;$j < 7;$j++) echo "<td class='DIW_TD'>".$DI_WName[$j]."</td>\n";
		echo "</tr>\n";
	}

	function OutDD($MM,$YY) {
		global $db_prefix, $DI_MName, $show_url,$today;




		$dss = $YY*10000+($MM+1)*100;
		$dse = $dss + 32;
		$day_smen = Array();
		$edit_state = Array();
		$result = dbquery("SELECT ID, DATE, SMEN, EDIT_STATE FROM ".$db_prefix."db_zadan where (DATE>'".$dss."') and (DATE<'".$dse."')");
		while ($res = mysql_fetch_array($result)) {
			$day_smen[] = $res["DATE"]."|".$res["SMEN"];
			if ($res["EDIT_STATE"]*1==0) $edit_state[] = $res["DATE"]."|".$res["SMEN"];
		}




		echo "<H3>".$DI_MName[$MM]." ".$YY."</H3>";
		echo "<table style='width: 100%;' cellpadding='0' cellspacing='0'>\n";
		OutWW();

		$out_dd = false;
		$day = 0;
		$start_ww = DI_FirstDay($MM, $YY);
		$DI_DCount = DI_MNum($MM,$YY);
		while ($day<$DI_DCount) {
			echo "<tr>";
			for ($j=0;$j < 7;$j++) {
				if ($j==$start_ww) $out_dd = true;
				echo "<td class='DIWW_TD'>";
				if ($out_dd) {
					$day = $day + 1;
					$cl = "DI_DD";
					if (even_week($day,$MM,$YY)) $cl = "DI_DD2";
					$hl = $YY*10000+($MM+1)*100+$day;
					if ($today==$hl) $cl = "DI_DDHL";
					if ($day<=$DI_DCount) {
						echo "<span class='".$cl."'>".$day."<br>";
						$acl = "DD";
						if (in_array($hl."|1",$day_smen)) {
							$acl = "DD2";
							if ($hl<=$today) {
								$acl = "DD4";
								if (in_array($hl."|1",$edit_state)) $acl = "DD3";
							}
						}
						echo "<a href='".$show_url.$hl."&p1=1' class='".$acl."'>1</a>";
						$acl = "DD";
						if (in_array($hl."|2",$day_smen)) {
							$acl = "DD2";
							if ($hl<=$today) {
								$acl = "DD4";
								if (in_array($hl."|2",$edit_state)) $acl = "DD3";
							}
						}
						echo "<a href='".$show_url.$hl."&p1=2' class='".$acl."'>2</a>";
						$acl = "DD";
						if (in_array($hl."|3",$day_smen)) {
							$acl = "DD2";
							if ($hl<=$today) {
								$acl = "DD4";
								if (in_array($hl."|3",$edit_state)) $acl = "DD3";
							}
						}
						echo "<a href='".$show_url.$hl."&p1=3' class='".$acl."'>3</a></span>";
					}
				}
				echo "</td>";
			}
			echo "</tr>";		
		}
		echo "</table><br><br>";
	}

   


   //

	echo "<table style='width: 100%; margin-bottom: -20px;' cellpadding='0' cellspacing='0'><tr>";
		echo "<td style='text-align: left; width: 50%;'>\n";
			echo "<a class='lnk' href='".$page_url."&p0=".$lastM."'><--</a> Месяцы <a class='lnk' href='".$page_url."&p0=".$nextM."'>--></a>";
		echo "</td><td style='text-align: right;'>\n";
			echo "<a class='lnk' href='".$page_url."&p0=".$lastY."'><--</a> Годы <a class='lnk' href='".$page_url."&p0=".$nextY."'>--></a>";
		echo "</td>";
	echo "</tr></table><br>";

	OutDD($DI_LMM,$DI_LYY);
	OutDD($DI_MM,$DI_YY);
	OutDD($DI_NMM,$DI_NYY);


?>