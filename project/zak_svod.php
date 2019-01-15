<?php
// Расчёт сводной таблицы по заказу


	if (!defined("MAV_ERP")) { die("Access Denied"); }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function FReal($x) 
	{
		$ret = number_format( $x, 2, ",", " ");
		
		if ( $x == floor($x)) 
			$ret = number_format($x, 0, ",", " ");
		
		if ($x==0) 
			$ret = "";
		
		return $ret;
	}

	function FDReal($x,$d) 
	{
		$ret = "~";
		if ( $d * 1 > 0 ) 
			$ret = FReal(($x*1)/($d*1));
		return $ret;
	}

	if ($print_mode == "on") 
		echo "Отчёт от ".date("d.m.Y H:i",mktime());

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$id."')");
	if ($zak = mysql_fetch_array($result)) 
	{

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$id."') and (PID='0')");
		$zakdet = mysql_fetch_array($result);

		//$count = $zakdet["COUNT"]*1;
		$count = $zak["DSE_COUNT"]*1;

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 900px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td width='160' rowspan='2'>Вид работ</td>\n";
		echo "<td colspan='2'>ПЛАН, Н/Ч</td>\n";
		echo "<td colspan='2'>ВЫПОЛНЕНО, Н/Ч</td>\n";
		echo "<td colspan='3'>КООПЕРАЦИЯ<br>Н/Ч</td>\n";
		echo "<td colspan='2'>ОСТАЛОСЬ, Н/Ч</td>\n";
		echo "<td colspan='2'>ФАКТ, ч</td>\n";
		echo "<td rowspan='2'>Коэффициент<br>(факт/план)</td>\n";
		echo "</tr>\n";
		echo "<tr class='first'>\n";
		echo "<td>На заказ</td>\n";
		echo "<td>На единицу</td>\n";
		echo "<td>На заказ</td>\n";
		echo "<td>На единицу</td>\n";
		echo "<td>Шт.</td>\n";
		echo "<td>На заказ</td>\n";
		echo "<td>На единицу</td>\n";
		echo "<td>На заказ</td>\n";
		echo "<td>На единицу</td>\n";
		echo "<td>На заказ</td>\n";
		echo "<td>На единицу</td>\n";
		echo "</tr>\n";
		echo "</thead>";


	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		echo "<tbody>";


	   ////////////////////////////////////////////////////////////////////////////////////////////////////////


		$oper_N_arr = [];
		$oper_NF_arr = [];
		$oper_F_arr = [];

		$coop_norm_hours = [];
		$coop_count = [];

		$operitems = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zak='".$id."')");
		while ($oper = mysql_fetch_array($operitems)) 
		{
			$oper_N_arr[$oper["ID_oper"]] += $oper["NORM_ZAK"];
			$oper_NF_arr[$oper["ID_oper"]] += $oper["NORM_FACT"];
			$oper_F_arr[$oper["ID_oper"]] = $oper_F_arr[$oper["ID_oper"]]*1 + $oper["FACT"]*1;

			$query = "SELECT
			oper_id,
			SUM(count) count,
			SUM( norm_hours ) norm_hours
			FROM `okb_db_operations_with_coop_dep` WHERE oper_id = ".$oper["ID"];

			$coop = mysql_fetch_assoc(dbquery( $query ));
			
			if( isset( $coop_norm_hours[ $oper["ID_oper"] ] ) )
				$coop_norm_hours[ $oper["ID_oper"] ] +=  $coop['norm_hours'];
					else
						$coop_norm_hours[ $oper["ID_oper"] ] =  $coop['norm_hours'];

			if( isset( $coop_count[ $oper["ID_oper"] ] ) )
				$coop_count[ $oper["ID_oper"] ] +=  strlen( $coop['count'] ) ? $coop['count'] : 0 ;
					else
						$coop_count[ $oper["ID_oper"] ] =  strlen( $coop['count'] ) ? $coop['count'] : 0 ;
		}

		$summ_N = [];
		$summ_NF = [];
		$summ_F = [];
		
		$summ_coop_count = [];
		$summ_coop_by_order = [];
		$summ_coop_by_unit = [];

		$opers = dbquery("SELECT * FROM ".$db_prefix."db_oper");
		while ($oper = mysql_fetch_array($opers)) 
		{
			$summ_N[$oper["TID"]] = $summ_N[$oper["TID"]]*1 + $oper_N_arr[$oper["ID"]]*1;
			$summ_NF[$oper["TID"]] = $summ_NF[$oper["TID"]]*1 + $oper_NF_arr[$oper["ID"]]*1;
			$summ_F[$oper["TID"]] = $summ_F[$oper["TID"]]*1 + $oper_F_arr[$oper["ID"]]*1;
			
			if( isset( $summ_coop_by_order[$oper["TID"]] ) )
				$summ_coop_by_order[$oper["TID"]] += $coop_norm_hours[$oper["ID"]];
				 else
					$summ_coop_by_order[$oper["TID"]] = $coop_norm_hours[$oper["ID"]];

			if( isset( $summ_coop_by_unit[$oper["TID"]] ) )
				$summ_coop_by_unit[$oper["TID"]] += $coop_norm_hours[$oper["ID"]] / ( $coop_count[$oper["ID"]] ? $coop_count[$oper["ID"]] : 1 );
				 else
					$summ_coop_by_unit[$oper["TID"]] = $coop_norm_hours[$oper["ID"]] / ( $coop_count[$oper["ID"]] ? $coop_count[$oper["ID"]] : 1 );

			if( isset( $summ_coop_count[$oper["TID"]] ) )
				$summ_coop_count[$oper["TID"]] += $coop_count[$oper["ID"]] ;
				 else
					$summ_coop_count[$oper["TID"]] = $coop_count[$oper["ID"]] ;


		}

		$tids = explode("|","|".$db_cfg["db_oper/TID|LIST"]);
		$tids[0] = "Не указано";

		$sn = 0;
		$snf = 0;
		$sf = 0;
		$coop_total_by_order = 0;
		$coop_total_by_unit = 0;
		$coop_total_count = 0;
		$tids_count = count($tids);

		for ($i=0;$i < $tids_count;$i++) 
		{
			/////////////////////////////////////////////////////////////////////////////////

				$coop_total_by_order += $summ_coop_by_order[ $i ] ;
				$coop_total_by_unit += $summ_coop_by_unit[ $i ] ;
				$coop_total_count += $summ_coop_count[ $i ] ;

				$summ_coop_by_order[ $i ] = $summ_coop_by_order[ $i ] ? number_format( $summ_coop_by_order[ $i ], 2, ".", " ") : '';
				$summ_coop_by_unit[ $i ] = $summ_coop_by_unit[ $i ] ? number_format( $summ_coop_by_unit[ $i ], 2, ".", " ") : '';


				$sn += $summ_N[$i]*1;
				$snf += $summ_NF[$i]*1;
				$sf += $summ_F[$i]*1;

			echo "<tr>\n";
				echo "<td class='Field'>".$tids[$i]."</td>\n";
				echo "<td class='Field AC'>".FReal($summ_N[$i])."</td>\n";
				echo "<td class='Field AC'>".FDReal($summ_N[$i],$count)."</td>\n";
				echo "<td class='Field AC'>".FReal($summ_NF[$i])."</td>\n";
				echo "<td class='Field AC'>".FDReal($summ_NF[$i],$count)."</td>\n";

				echo "<td class='Field AC'>".( $summ_coop_count[ $i ] ? $summ_coop_count[ $i ] : '')."</td>\n";
				echo "<td class='Field AC'>".$summ_coop_by_order[ $i ]."</td>\n";
				echo "<td class='Field AC'>".$summ_coop_by_unit[ $i ]."</td>\n";

				echo "<td class='Field AC'>".FReal(($summ_N[$i] - $summ_NF[$i]) - $summ_coop_by_order[ $i ])."</td>\n";

				$val = FDReal( $summ_N[$i] - $summ_NF[$i] - $summ_coop_by_unit[ $i ] * 10, $count );
				$val = $val == "0,00" ? "" : $val ;
				
				echo "<td class='Field AC'>$val</td>\n";

				echo "<td class='Field AC'>".FReal($summ_F[$i])."</td>\n";
				echo "<td class='Field AC'>".FDReal($summ_F[$i],$count)."</td>\n";

				echo "<td class='Field AC'>".FDReal ($summ_F[$i] + $summ_coop_by_order[ $i ] ,$summ_N[$i])."</td>\n";
			echo "</tr>\n";
			////////////////////////////////////////////////////////////////////////////////
		}

			echo "<tr>\n";
				echo "<td class='Field'><b>ИТОГО:</b></td>\n";
				echo "<td class='Field AC'><b>".FReal($sn)."</b></td>\n";
				echo "<td class='Field AC'><b>".FDReal($sn,$count)."</b></td>\n";
				echo "<td class='Field AC'><b>".FReal($snf)."</b></td>\n";
				echo "<td class='Field AC'><b>".FDReal($snf,$count)."</b></td>\n";

				echo "<td class='Field AC'><b>$coop_total_count</b></td>\n";
				
				echo "<td class='Field AC'><b>".number_format( $coop_total_by_order, 2, ".", " " )."</b></td>\n";
				echo "<td class='Field AC'><b>".number_format( $coop_total_by_unit, 2, ".", " " )."</b></td>\n";
			
				echo "<td class='Field AC'><b>".FReal($sn - $snf - $coop_total_by_order)."</b></td>\n";
				echo "<td class='Field AC'><b>".FDReal($sn - $snf - 10 * $coop_total_by_unit,$count)."</b></td>\n";

				echo "<td class='Field AC'><b>".FReal($sf)."</b></td>\n";
				echo "<td class='Field AC'><b>".FDReal($sf,$count)."</b></td>\n";

				echo "<td class='Field AC'><b>".FDReal( $sf + $coop_total_by_order ,$sn)."</b></td>\n";
			echo "</tr>\n";

	   ////////////////////////////////////////////////////////////////////////////////////////////////////////


		echo "</tbody>";
		echo "</table>\n";
	   ///////////////////////////////////////////////////////////////////////////////
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
