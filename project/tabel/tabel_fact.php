<STYLE>
	.first_half
	{
		table-layout: fixed ;
	}

	TD.Field 
	{
		vertical-align: middle;$
	}

	div.a4p 
	{
		width : 1250px;
		text-align: left;
		background: #fff;
		page-break-after:always;
	}

	.view div.a4p 
	{
		display: block;
		border: 1px solid #444;
		padding: 20px;
		box-shadow: 3px 4px 20px #555555;
		margin: 40px;
	}

	table.view 
	{
		width: 100%;
		margin: 0px;
		padding: 0px;
	}

	.viol_span
	{
		color : red;
		font-weight: bold;
	}

	@media print { .table_fix { width:100vw !important; } 

</style>
<center>
	<?php
	require_once( "classes/db.php" );
	require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

//error_reporting(E_ALL);
//ini_set('display_errors', true);

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

//////////////////////////////////////////////////////////////////////////////////////////////////	Получаем список ID
//////////////////////////////////////////////////////////////////////////////////////////////////

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

	$first_half = isset( $_GET['p4'] ) ? + $_GET['p4'] : 0 ;
	echo "<script>let first_half = $first_half</script>";

	if ($_GET['p2']) 
	{
		if ($_GET['p3']==1) 
		{ 
			$p_3_1 = $date_start; 
			$p_3_2 = $date_end2;
		}
		if ($_GET['p3']==2) 
		{ 
			$p_3_1 = $date_start2; 
			$p_3_2 = $date_end;
		}
	}
	else
	{
		$p_3_1 = $date_start;
		$p_3_2 = $date_end;
	}

// shindax
	if( $first_half )
		$p_3_2 = "20190616";

	$sql = "SELECT * FROM ".$db_prefix."db_tabel where (DATE>'".$p_3_1."') and (DATE<'".$p_3_2."') order by DATE" ;
	$xxx = dbquery( $sql );

	while($tab = mysql_fetch_array($xxx)) 
	{
		$cur_date = $tab["DATE"] ;
		$cur_fact = $tab["FACT"];
		$cur_plan = $tab["PLAN"];
		$tid = $tab["TID"];
		$id = $tab["ID_resurs"];

		$year = substr( $cur_date, 0, 4 );
		$month = substr( $cur_date, 4, 2 );
		$day = substr( $cur_date, 6, 2 );
		$date = "$year-$month-$day";
		$weekday = strftime("%w", strtotime($date));

		$res_ids[] = $tab["ID_resurs"];

		$resfact = str_replace(",",".",$cur_fact )*1;
		$txt = "<b  >".$resfact."</b><br>".$tab["SMEN"];

// TID "ОТ|ДО| Х| Б|НН|ПР| В|ЛЧ|НВ| K|РП| У|ПК|НП"
// TID " 1| 2| 3| 4| 5| 6| 7| 8| 9|10|11|12|13|14"

		if ($tab['TID'] == 7) 
			$tid_style = ' style="border-top:1px solid #000;border-bottom:1px solid #000"';
		else 
			$tid_style = '';

		if ($tab['TID'] == 1 || $tab['TID'] == 2 || $tab['TID'] == 3 || $tab['TID'] == 4 || $tab['TID'] == 5 || $tab['TID'] == 6) 
			if ($resfact == 0) 
				$tid_style = ' style="border:1px solid #000;padding:3px 0px 3px 3px"';
			else 
				$tid_style = ' style="border:1px solid #000;padding:0px 2px 1px 2px"';
			else 
				if ($tab['TID'] == 7) 
					$tid_style = ' style="border-top:1px solid #000;border-bottom:1px solid #000;padding-left:3px;padding-right:3px"';
				else 
					$tid_style = '';

				if ($tab["TID"]*1>0) 
					$txt = "<b" . $tid_style . ">".FVal($tab,"db_tabel","TID"). ($resfact>0 && $tab['TID'] > 0 ? '' : "&nbsp;") . "</b>";

				if (($tab["TID"]>0) && ($resfact>0)) 
					$txt = $txt."<br><b>".$resfact."</b>/".$tab["SMEN"];

				$res_txt[ $id."x".$cur_date] = $txt;

				if ($tab["SMEN"]=="1" || $tab["SMEN"]=="0") 
				{
					$res_s1[ $id ] = $res_s1[ $id ] * 1 + $resfact;

					if ($resfact>0) 
						$num_s1[ $cur_date ] = $num_s1[ $cur_date ]*1 + 1;

					if ($resfact>0) 
						$num_f1[ $cur_date ] = $num_f1[ $cur_date ]*1 + $resfact;
				}
				if ($tab["SMEN"]=="2") 
				{
					$res_s2[ $id ] = $res_s2[ $id ]*1 + $resfact;
					if ($resfact>0) 
						$num_s2[ $cur_date ] = $num_s2[ $cur_date ]*1 + 1;
					if ($resfact>0) 
						$num_f2[ $cur_date ] = $num_f2[ $cur_date ]*1 + $resfact;
				}
				if ($tab["SMEN"]=="3") 
				{
					$res_s3[ $id ] = $res_s3[ $id ]*1 + $resfact;
					if ($resfact>0) 
						$num_s3[ $cur_date ] = $num_s3[ $cur_date ]*1 + 1;
					if ($resfact>0) 
						$num_f3[ $cur_date ] = $num_f3[ $cur_date ]*1 + $resfact;
				}
			}

//////////////////////////////////////////////////////////////////////////////////////////////////	ФУНКЦИИ
//////////////////////////////////////////////////////////////////////////////////////////////////

			function DI_MNum($Mon, $Year) 
			{
				$nn = Array(31,28,31,30,31,30,31,31,30,31,30,31);
				$x = 28;
				$y = (Round($Year/4))*4;
				if ($y==$Year) 
					$x = 29;
				$ret = $nn[$Mon];
				if ($Mon==1) 
					$ret = $x;
				return $ret;
			}

			function DI_FirstDay($Mon,$Year) 
			{
				$x0 = 365;
				$Y = $Year-1;
				$days = $Y*$x0+floor($Y/4)+6;
				for ($j=0; $j<$Mon; $j=$j+1) 
					$days = $days+DI_MNum($j,$Year);

				$week = $days-(7*Round(($days/7)-0.5));
				return $week;
			}

			function DI_WeekDay($Day,$Mon,$Year) 
			{
				$res = DI_FirstDay($Mon,$Year);
				for ($j=1; $j<$Day; $j=$j+1) 
				{
					$res = $res + 1;
					if ($res>6) 
						$res=0;
				}
				return $res;
			}

			function even_week($Day,$Mon,$Year) 
			{
				$x0 = 365;
				$Y = $Year-1;
				$days = $Y*$x0+floor($Y/4)+6;

				for ($j=0; $j<$Mon; $j=$j+1) 
					$days = $days+DI_MNum($j,$Year);

				$days = $days + $Day;
				$weeks = ceil($days/7);

				$res = false;
				if (2*ceil($weeks/2) == $weeks) 
					$res = true;
				return $res;
			}

// *************************************************************************

			function newpage($list_page = null) 
			{
				global $DI_MM, $DI_YY, $DI_WName, $first_half;

				if ($_GET['p2']) 
				{
					if ($_GET['p3']==1) 
					{ 
						$p_3_1 = 0; 
						$p_3_2 = 15;
					}
					
					if ($_GET['p3']==2) 
					{ 
						$p_3_1 = 15; 
						$p_3_2 = DI_MNum($DI_MM,$DI_YY);
					}
					
					$wid_p = "900px";
				}
				else
				{
					$wid_p = "1250px";
					$p_3_1=0;
					$p_3_2 = DI_MNum($DI_MM,$DI_YY);
				}

				echo "</tbody>";
				echo "</table>";
				echo "</div>";

				echo "<div class='pagebreak' id='Printed' class='a4p' style='width:100%;'><b style='float:left;'>Лист №".$list_page."</b><b style='float:right;'>Отчёт от ".date("d.m.Y H:i",mktime())."</b>";
				
				echo "<br/><table class='rdtbl tbl' style='width:100%;table-layout:fixed;' cellpadding='0' cellspacing='0'>\n";

				echo "<thead>";
				echo "<tr class='first'>";
				echo "<td rowspan='2' style='width:68px;'>Ресурс</td>";
				$weekday = DI_FirstDay($DI_MM,$DI_YY);

//shindax
				if( $first_half )
					$p_3_2 = 15;

				$cl = " style='padding: 2px; width: 11px;'";

				for ($j=$p_3_1;$j < $p_3_2;$j++) 
				{
					if (!$_GET['p3']) 
					{ 
						if ($weekday>4) 
							if ( $weekday == 5 || $weekday == 6 ) 
								$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 11px;'";             

							$weekday = $weekday + 1; if ($weekday>6) $weekday = 0;}
							echo "<td class='Field'".$cl.">".($j+1)."</td>";
						}
						echo "<td colspan='3' style='width:55px;'>По сменам, ч</td>";
						echo "<td rowspan='2' style='width:23px;'><b>Итого, ч</b></td>";
						echo "</tr>";
						echo "<tr class='first'>";

						$date = "$DI_YY-".($DI_MM + 1 )."-".$p_3_1 ;
						$date = explode( "-", $date );
						$weekday  = date("w", mktime(0, 0, 0, $date[1], $date[2], $date[0]));


						for ( $j = $p_3_1; $j < $p_3_2; $j++ ) 
						{
							$cl = " style='padding: 2px;'";
							if ( $weekday > 4 ) 
							{
								if ( $weekday == 5 ) 
									$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px;'";

								if ( $weekday == 6 ) 
									$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px;'";
							}

							echo "<td class='Field'".$cl.">".$DI_WName[$weekday]."</td>";
							$weekday ++ ;
							if ($weekday > 6) 
								$weekday = 0;
						}

						echo "<td style='width: 30px;'>I</td>";
						echo "<td style='width: 30px;'>II</td>";
						echo "<td style='width: 30px;'>III</td>";
						echo "</tr>";
						echo "</thead>";

						echo "<tbody>";	

	} // function newpage($list_page = null) 

// *************************************************************************

//////////////////////////////////////////////////////////////////////////////////////////////////

	if ($_GET['p2'])
		$rownum = 25;
	else
		$rownum = 16;		
	$nnn = 0;

	if ($_GET['p2']) 
	{
		if ($_GET['p3']==1) 
		{ 
			$p_3_1 = 0; 
			$p_3_2 = 15; 
			$h2_titl = "<h2>Табель за 01-15.".$my[1].".".$YY."г</h2>";
		}
		if ($_GET['p3']==2) 
		{ 
			$p_3_1 = 15; 
			$p_3_2 = DI_MNum($DI_MM,$DI_YY); 
			$h2_titl = "<h2>Табель за 16-".$p_3_2.".".$my[1].".".$YY."г</h2>";
		}
		$h2_titl2 = " месяца";
		$wid_p = "900px";
		$wid_p2 = "";
		$wid_p3 = "";
	}
	else
	{
		$h2_titl = "<h2>Табель за ".$MM." ".$YY."г</h2>";
		$wid_p = "1250px";
		$wid_p2 = "width=140px";
		$wid_p3 = "width=50px";
		$p_3_1=0;
		$p_3_2 = DI_MNum($DI_MM,$DI_YY);
	}

	if( $first_half )
	{
		$wid_p2 = "width=25px";		
		$wid_p3 = "width=25px";
	}

	echo "<div id='Printed' class='a4p' style='width:".$wid_p.";'><b style='float:left;'>Лист №".$list_page."</b><b style='float:right;'>Отчёт от ".date("d.m.Y H:i",mktime())."</b>";
	echo $h2_titl;
	echo "<table class='rdtbl tbl ".( $first_half ? 'first_half' : '' )."' style='width:".$wid_p.";' cellpadding='0' cellspacing='0'>\n";

	if( $first_half )
	{
		echo "<col width='20%'>";
		for( $k = 0 ; $k < 20; $k ++ )
			echo "<col width='4%'>";
	}

	echo "<thead>";
	echo "<tr class='first'>";
	echo "<td ".$wid_p2." rowspan='2'>Ресурс</td>";
	$weekday = DI_FirstDay($DI_MM,$DI_YY);

// shindax
	if( $first_half )
		$p_3_2 = 15;

	for ( $j=$p_3_1; $j < $p_3_2; $j++ ) 
	{
		$cl = " style='padding: 2px; width: 25px;'";
		if ($_GET['p3']==1) 
		{ 
			if ($weekday>4) 
			{
				if ( $weekday == 5 ) 
					$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

				if ( $weekday == 6 ) 
					$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'"; 
			}

			echo "<td class='Field'".$cl.">".($j+1)."</td>"; $weekday = $weekday + 1; 
			if ($weekday>6) 
				$weekday = 0;
		}

		if ($_GET['p3']==2) 
		{ 
			if ( $p_3_2 < 31 ) 
			{ 
				if ($weekday > 3 ) 
					$cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 

				echo "<td class='Field'".$cl.">".($j+1)."</td>"; 
				$weekday = $weekday + 1; 

				if ( $weekday > 5 ) 
					$weekday = -1;
			}

			if ($p_3_2 == 31) 
			{ 
				if ( ( $weekday > 3 ) and ( $weekday < 6 ) ) 
					$cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; 

				echo "<td class='Field'".$cl.">".($j+1)."</td>"; 
				$weekday ++ ; 

				if ($weekday>6) 
					$weekday = 0;
			}          
		}

		if (!$_GET['p3']) 
		{ 
			if ($weekday>4) 
			{
				if ( $weekday == 5 ) 
					$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

				if ( $weekday == 6 ) 
					$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'"; 
			}
			echo "<td class='Field'".$cl.">".($j+1)."</td>"; 
			$weekday = $weekday + 1; 
			if ($weekday>6) 
				$weekday = 0;
		}
	}
	echo "<td colspan='3'>По сменам, ч</td>";
	echo "<td ".$wid_p3." rowspan='2'><b>Итого, ч</b></td>";
	echo "</tr>";
	echo "<tr class='first'>";

	$date = "$DI_YY-".($DI_MM + 1 )."-".$p_3_1 ;
	$date = explode( "-", $date );
	$weekday  = date("w", mktime(0, 0, 0, $date[1], $date[2], $date[0]));

	for ( $j = $p_3_1; $j < $p_3_2; $j++ ) 
	{
		$cl = " style='padding: 2px;'";
		if ( $weekday > 4 ) 
		{
			if ( $weekday == 5 ) 
				$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px;'";

			if ( $weekday == 6 ) 
				$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px;'";
		}

		echo "<td class='Field'".$cl.">".$DI_WName[$weekday]."</td>";
		$weekday ++ ;
		if ($weekday > 6) 
			$weekday = 0;
	}

	echo "<td style='width: 30px;'>I</td>";
	echo "<td style='width: 30px;'>II</td>";
	echo "<td style='width: 30px;'>III</td>";
	echo "</tr>";
	echo "</thead>";

	echo "<tbody>";	
	echo "<tbody>";			

	if ($_GET['p1'])
	{
		$sel_res = explode("|",$_GET['p1']);
		$in_arr = $sel_res;
		$nalich_sel = 1;
	}
	if (!$_GET['p1'])
	{
		$in_arr = $res_ids;
		$nalich_sel = 0;
	}

	$p_2 = $_GET['p2'];
	if ($p_2 == '2') 
	{
		$nalich_sel = 1;
		if ($_GET['p3']==1) 
		{ 
			$p_3_1 = 0; 
			$p_3_2 = 15;
		}

		if ($_GET['p3']==2) 
		{ 
			$p_3_1 = 15; 
			$p_3_2 = DI_MNum($DI_MM,$DI_YY);
		}

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where TID='0' and ID_tab='".$user['ID']."' order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) 
		{
			if (($res["ID"]!=='0')and(in_array($res["ID"],$in_arr))) 
			{
				$summ = $res_s1[$res["ID"]] + $res_s2[$res["ID"]] + $res_s3[$res["ID"]];
				$nnn = $nnn + 1;
				echo "<tr style='height: 30px;'>";
				echo "<td class='Field'><b>".$res["NAME"]."</b></td>";
				$weekday = DI_FirstDay($DI_MM,$DI_YY);

				for ($j=$p_3_1;$j < $p_3_2;$j++) 
				{
					$date = $dx + $j + 1;
					$cl = " style='padding: 2px;'";

					if ($_GET['p3']==1) 
					{ 
						if ($weekday>4) 
						{

							if ( $weekday == 5 ) 
								$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

							if ( $weekday == 6 ) 
								$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'"; 

						}

						echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; 
						$weekday = $weekday + 1; 

						if ($weekday>6) 
							$weekday = 0;
					}
					if ($_GET['p3']==2) 
					{ 
						if ($p_3_2<31) 
						{ 
							if ($weekday>3) 
								$cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; 
							$weekday = $weekday + 1; 

							if ($weekday>5) 
								$weekday = -1;
						}
					}
					if ($_GET['p3']==2) 
					{ 
						if ($p_3_2==31) 
						{ 
							if (($weekday>3) and ($weekday<6)) 
								$cl = " style='background: #ffeac8; padding: 2px; width: 25px;'"; echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; 
							$weekday = $weekday + 1; 
							if ($weekday>6) 
								$weekday = 0;
						}
					}
				}


				$shift1 = $res_s1[$res["ID"]];
				$shift2 = $res_s2[$res["ID"]];				

				if( 1 )
				{
					$el = new LaborRegulationsViolationItemByMonth( $pdo, $res['ID'], $DI_MM + 1, $DI_YY );
					$viol = $el -> GetViolationsByShift();

					if( $viol['shift1_minus'] ) 
					{
						if( $shift1 )
						{
								// $shift1 -= $viol['shift1_minus'];
								// $shift1 .= "<br><span class='viol_span'>(-{$viol['shift1_minus']})</span>";
							$shift1 .= "<br><span class='viol_span'>(-{$viol['shift_1']})</span>";								

						}
						else
								// $shift1	= "0<br><span class='viol_span'>(-{$viol['shift1_minus']})</span>";
							$shift1	= "0<br><span class='viol_span'>(-{$viol['shift_1']})</span>";							
					}

					if( $viol['shift2_minus'] ) 
					{
						if( $shift2 )
						{
								// $shift2 -= $viol['shift2_minus'];
								// $shift2 .= "<br><span class='viol_span'>(-{$viol['shift2_minus']})</span>";
							$shift2 .= "<br><span class='viol_span'>(-{$viol['shift_2']})</span>";								
						}
						else
								// $shift2	= "0<br><span class='viol_span'>(-{$viol['shift2_minus']})</span>";
							$shift2	= "0<br><span class='viol_span'>(-{$viol['shift_2']})</span>";							
					}

					$shift1 = $shift1 ? $shift1 : '' ;
					$shift2 = $shift2 ? $shift2 : '' ;					

					if( $viol['shift1_minus'] || $viol['shift2_minus'] )
					{
							// $summ -= $viol['shift1_minus'] + $viol['shift2_minus'];
							// $summ .= "<br><span class='viol_span'>(-".( $viol['shift1_minus'] + $viol['shift2_minus'] ).")</span>";
						$summ .= "<br><span class='viol_span'>(-".( $viol['shift_1'] + $viol['shift_2'] ).")</span>";							
					}
				}

				echo "<td class='Field AC'>$shift1</td>";
				echo "<td class='Field AC'>$shift2</td>";
				echo "<td class='Field AC'>".$res_s3[$res["ID"]]."</td>";
				echo "<td class='Field AC'><b>".$summ."</b></td>";
				echo "</tr>";
				if ($nnn>$rownum) 
				{
					if ($_GET['p2'])
						$rownum = 27;
					else
						$rownum = 18;		

					$list_page = $list_page + 1;
					newpage($list_page);
					$nnn = 0;
				}
			}
		} // while($res = mysql_fetch_array($xxx)) 
	} // if ($p_2 == '2') 
	else
	{
		$xxx = dbquery("SELECT NAME, ID, TID FROM okb_db_resurs WHERE DATE_TO = 0 order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) 
		{
			if (($res["ID"]!=='0')and(in_array($res["ID"],$in_arr))) 
			{
				$summ = $res_s1[$res["ID"]] + $res_s2[$res["ID"]] + $res_s3[$res["ID"]];
				if (($res['TID']=='0')or(($res['TID']=='1')and($summ>0)))
				{
					$xx2x = dbquery("SELECT ID_otdel FROM okb_db_shtat where ID_resurs=".$res["ID"]);
					$re2s = mysql_fetch_array($xx2x);

					$nnn = $nnn + 1;
					echo "<tr style='height: 30px;'>";
					echo "<td class='Field'><b>".$res["NAME"]."</b></td>";
					$weekday = DI_FirstDay($DI_MM,$DI_YY);


// shindax
					$day_count = DI_MNum($DI_MM,$DI_YY);
					
					if( $first_half )
						$day_count = 15 ;

					for ($j=0;$j < $day_count;$j++) 
					{
						$date = $dx + $j + 1;
						$cl = " style='padding: 2px;'";
						if ($weekday>4) 
						{
							if ( $weekday == 5 ) 
								$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

							if ( $weekday == 6 ) 
								$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'";             
						}

						echo "<td class='Field AC'".$cl.">".$res_txt[$res["ID"]."x".$date]."</td>"; 

						$weekday = $weekday + 1; 

						if ($weekday>6) 
							$weekday = 0;
					}

					$shift1 = $res_s1[$res["ID"]];
					$shift2 = $res_s2[$res["ID"]];				

					if( 1 )
					{
						$el = new LaborRegulationsViolationItemByMonth( $pdo, $res['ID'], $DI_MM + 1, $DI_YY );
						$viol = $el -> GetViolationsByShift();

						if( $viol['shift1_minus'] ) 
						{
							if( $shift1 )
							{
								// $shift1 -= $viol['shift1_minus'];
								// $shift1 .= "<br><span class='viol_span'>(-{$viol['shift1_minus']})</span>";
								$shift1 .= "<br><span class='viol_span'>(-{$viol['shift_1']})</span>";
							}
							else
								// $shift1	= "0<br><span class='viol_span'>(-{$viol['shift1_minus']})</span>";
								$shift1	= "0<br><span class='viol_span'>(-{$viol['shift_1']})</span>";							
						}

						if( $viol['shift2_minus'] ) 
						{
							if( $shift2 )
							{
								// $shift2 -= $viol['shift2_minus'];
								// $shift2 .= "<br><span class='viol_span'>(-{$viol['shift2_minus']})</span>";
								$shift2 .= "<br><span class='viol_span'>(-{$viol['shift_2']})</span>";								
							}
							else
								// $shift2	= "0<br><span class='viol_span'>(-{$viol['shift2_minus']})</span>";
								$shift2	= "0<br><span class='viol_span'>(-{$viol['shift_2']})</span>";
						}

						if( $viol['shift1_minus'] || $viol['shift2_minus'] )
						{
							// $summ -= $viol['shift1_minus'] + $viol['shift2_minus'];
							// $summ .= "<br><span class='viol_span'>(-".( $viol['shift1_minus'] + $viol['shift2_minus'] ).")</span>";
							$summ .= "<br><span class='viol_span'>(-".( $viol['shift_1'] + $viol['shift_2'] ).")</span>";
						}
						
						$shift1 = $shift1 ? $shift1 : '' ;
						$shift2 = $shift2 ? $shift2 : '' ;					
						$summ = $summ ? $summ : '' ;

					}

					echo "<td class='Field AC'>$shift1</td>";
					echo "<td class='Field AC'>$shift2</td>";
					echo "<td class='Field AC'>".$res_s3[$res["ID"]]."</td>";

					echo "<td class='Field AC'><b>".$summ."</b></td>";
					echo "</tr>";

					if ($nnn>$rownum) 
					{
						if ($_GET['p2'])
							$rownum = 27;
						else
							$rownum = 18;		

						$list_page = $list_page + 1;
						newpage($list_page);
						$nnn = 0;
					}
				}
			}
		} // while($res = mysql_fetch_array($xxx)) 
	} // if ($p_2 == '2') ... else

/////////////////////////////////////////////////////////////////////////////////////////////

				// Вывод итого
	if ($_GET['p2']) 
	{
		if ($_GET['p3']==1) 
		{ 
			$p_3_1 = 0; 
			$p_3_2 = 15;
		}

		if ($_GET['p3']==2) 
		{ 
			$p_3_1 = 15; 
			$p_3_2 = DI_MNum($DI_MM,$DI_YY);
		}
	}


	if ( $nalich_sel == 0 ) 
	{
		$summ_f = 0;
		$nnn = $nnn + 1;
		echo "<tr style='height: 30px;' class='final_table'>";
		echo "<td class='Field' style='width: 120px;'><b>Итого I смена</b></td>";
		$weekday = DI_FirstDay($DI_MM,$DI_YY);

		for ($j=$p_3_1;$j < $p_3_2;$j++) 
		{
			$date = $dx + $j + 1;
			$cl = " style='padding: 2px;'";
			if ($weekday>4) 
			{
				if ( $weekday == 5 ) 
					$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

				if ( $weekday == 6 ) 
					$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'";               
			}

			echo "<td class='Field AC'".$cl."><b>".$num_f1[$date]."</b><br>".$num_s1[$date]."</td>";
			$summ_f = $summ_f + $num_f1[$date];
			$weekday = $weekday + 1; 
			if ($weekday>6) 
				$weekday = 0;
		}
		echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
		echo "</tr>";

		if ($nnn>$rownum) 
		{
			if ($_GET['p2'])
				$rownum = 27;
			else
				$rownum = 18;		

			newpage();
			$nnn = 0;
		}

		$summ_f = 0;
		$nnn = $nnn + 1;
		echo "<tr style='height: 30px;'  class='final_table'>";
		echo "<td class='Field' style='width: 120px;'><b>Итого II смена</b></td>";
		$weekday = DI_FirstDay($DI_MM,$DI_YY);
		for ($j=$p_3_1;$j < $p_3_2;$j++) 
		{
			$date = $dx + $j + 1;
			$cl = " style='padding: 2px;'";

			if ($weekday>4) 
			{
				if ( $weekday == 5 ) 
					$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

				if ( $weekday == 6 ) 
					$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'"; 
			}

			echo "<td class='Field AC'".$cl."><b>".$num_f2[$date]."</b><br>".$num_s2[$date]."</td>";
			$summ_f = $summ_f + $num_f2[$date];
			$weekday = $weekday + 1; 
			if ($weekday>6) 
				$weekday = 0;
		}
		echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
		echo "</tr>";

		if ($nnn>$rownum) 
		{
			if ($_GET['p2'])
				$rownum = 27;
			else
				$rownum = 18;		

			newpage();
			$nnn = 0;
		}

		$summ_f = 0;
		$nnn = $nnn + 1;
		
		echo "<tr style='height: 30px;'  class='final_table'>";
		echo "<td class='Field' style='width: 120px;'><b>Итого III смена</b></td>";
		$weekday = DI_FirstDay($DI_MM,$DI_YY);
		for ($j=$p_3_1;$j < $p_3_2;$j++) 
		{
			$date = $dx + $j + 1;
			$cl = " style='padding: 2px;'";

			if ($weekday>4) 
			{
				if ( $weekday == 5 ) 
					$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 

				if ( $weekday == 6 )
					$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'"; 
			}

			echo "<td class='Field AC'".$cl."><b>".$num_f3[$date]."</b><br>".$num_s3[$date]."</td>";
			$summ_f = $summ_f + $num_f3[$date];
			$weekday = $weekday + 1; 
			if ($weekday>6) 
				$weekday = 0;
		}
		echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
		echo "</tr>";

		if ($nnn>$rownum) 
		{
			if ($_GET['p2'])
				$rownum = 27;
			else
				$rownum = 18;		

			newpage();
			$nnn = 0;
		}
		$summ_f = 0;
		$nnn = $nnn + 1;
		echo "<tr style='height: 30px;'>";
		echo "<td class='Field' style='width: 120px;'><b>ИТОГО</b></td>";


		$weekday = DI_FirstDay($DI_MM,$DI_YY);
		$day_count = DI_MNum($DI_MM,$DI_YY) ;

//shindax
		if( $first_half )
			$day_count = 15 ;

		for ($j=0;$j < $day_count ;$j++) 
		{
			$date = $dx + $j + 1;
			$cl = " style='padding: 2px;'";

			if ($weekday>4) 
			{
				if ( $weekday == 5 ) 
					$cl = " style='background: #ffeac8; border-left: 3px solid black; padding: 2px; width: 25px;'"; 
				if ( $weekday == 6 )
					$cl = " style='background: #ffeac8; border-right: 3px solid black; padding: 2px; width: 25px;'"; 
			}

			$ss = $num_f1[$date] + $num_f2[$date] + $num_f3[$date];
			$ssn = $num_s1[$date] + $num_s2[$date] + $num_s3[$date];
			echo "<td class='Field AC'".$cl."><b>".$ss."</b><br>".$ssn."</td>";
			$summ_f = $summ_f + $ss;
			$weekday = $weekday + 1; 
			if ($weekday>6) 
				$weekday = 0;
		}
		echo "<td class='Field AC' colspan='4'><b>".$summ_f."</b></td>";
		echo "</tr>";
	}

/////////////////////////////////////////////////////////////////////////////////////////////

	if ((!$_GET['p1']) and (!$_GET['p2'])) 
	{
		$x3x = dbquery("SELECT NAME, ID_special, ID_otdel FROM ".$db_prefix."db_shtat where ID_otdel='55' AND BOSS='1' ");
		$xr3 = mysql_fetch_array($x3x);
		// echo "<tr></tr><tr>
		// <td width='40px'>
		// <td colspan='8' width='600px' style='font-size:13pt; text-align:left;'>Начальник отдела кадров</td>
		// <td colspan='11' width='300px'>_________________________________________</td>
		// <td colspan='6' width='350px' style='font-size:13pt; text-align:left;'>".$xr3['NAME']."</td>
		// </tr>";
	}

	if ($_GET['p2']) 
	{
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

//////////////////////////////////////////////////////////////////////////////////////////////////

	if ($_GET['p2']) 
		echo "<script>
	var tableses = document.getElementsByClassName('rdtbl tbl').length;
	for (var a_m = 0; a_m < tableses; a_m++)
	{
		var tbl_tds = document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td').length;
		for (var b_m = 0; b_m < tbl_tds; b_m++)
		{
			document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td')[b_m].style.fontSize='125%';
			var td_bs = document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td')[b_m].getElementsByTagName('b').length;
			for (var c_m = 0; c_m < td_bs; c_m++)
			{
				document.getElementsByClassName('rdtbl tbl')[a_m].getElementsByTagName('td')[b_m].getElementsByTagName('b')[c_m].style.fontSize='115%';
			}
		}
	}
	</script>";

	function conv( $str )
	{
		global $dbpasswd;
		if( strlen( $dbpasswd ) )
			return $str;
		else
			return iconv("UTF-8", "Windows-1251", $str );
	}

	?>
</center>
<!-- $shift1 .= "<br><span class='viol_span'>(-{$viol['shift1_minus']})</span>"; -->
<script>
	$( function()
	{
		if( first_half )
		{
			let tr = $('.final_table')
			let table = $( tr ).closest('table')
			let page_break = $( tr ).closest('.pagebreak')
			
			$( page_break ).remove()
			$( table ).remove()
		}
	});
</script>