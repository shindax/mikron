<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

	$date = $_GET[p0];
	$date2 = $_GET[p1];
	$truncated_data = isset( $_GET[p2] ) ? false : true ;

	$pdate = DateToInt($date);
	$pdate2 = DateToInt($date2);

	$step = 1;

	if (($pdate>0) && ($pdate2>=$pdate)) $step = 2;

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

	echo "<h2>Отчёт по производству изделий за период</h2>";

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>Параметр</td>";
	echo "<td>Значение</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>Дата начала:</b></td><td class='rwField ntabg'>";
	Input("date","p0",TodayDate());
	echo "</td></tr>\n";

	echo "<tr><td class='Field first'><b>Дата окончания:</b></td><td class='rwField ntabg'>";
	Input("date","p1",TodayDate());
	echo "</td></tr>\n";

	echo "</table>\n";

	$prturl = str_replace ("index.php","print.php", $pageurl);
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input type='submit' value='Расчёт'></td></tr></table>";

}

if ($step==2) {







////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Таблица
////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	// Берём выполненые

		$zad_ID = Array();
		$zad_ID_0 = Array();
		$zad_ID_1 = Array();
		$zad_ID_2 = Array();
		$zad_ID_s = Array();


		$zak_IDs = Array();
		$resurs_IDs = Array();
		$operitems_IDs = Array();
		$izd_IDs = Array();

		$zadan_idz = Array();
		$zadan_idzd = Array();
		$zadan_ido = Array();
		$zadan_idr = Array();

		$w_zad_num = Array();
		$w_zad_norm = Array();
		$w_zad_fact = Array();
		$w_zad_date = Array();
		$w_zad_smen = Array();

		$summ_norm = 0;
		$summ_fact = 0;

		$yyy = dbquery("
			SELECT
			ID, ID_zak, ID_resurs, ID_operitems, ID_zakdet, NUM_FACT, NORM_FACT, FACT, DATE, SMEN
			FROM ".$db_prefix."db_zadan
			where (DATE >= '".$pdate."') and (DATE <= '".$pdate2."') and (EDIT_STATE = '1')
			order by DATE, SMEN, ID
		");
		while ($zad = mysql_fetch_array($yyy)) {

			if (!in_array($zad["ID_zak"],$zak_IDs)) $zak_IDs[] = $zad["ID_zak"];
			if (!in_array($zad["ID_resurs"],$resurs_IDs)) $resurs_IDs[] = $zad["ID_resurs"];
			if (!in_array($zad["ID_operitems"],$operitems_IDs)) $operitems_IDs[] = $zad["ID_operitems"];
			if (!in_array($zad["ID_zakdet"],$izd_IDs)) $izd_IDs[] = $zad["ID_zakdet"];

			$zad_ID[] = $zad["ID"];
			$zadan_idz[$zad["ID"]] = $zad["ID_zak"];
			$zadan_idzd[$zad["ID"]] = $zad["ID_zakdet"];
			$zadan_ido[$zad["ID"]] = $zad["ID_operitems"];
			$zadan_idr[$zad["ID"]] = $zad["ID_resurs"];

			$w_zad_num[$zad["ID"]] = $zad["NUM_FACT"]*1;
			$w_zad_norm[$zad["ID"]] = FReal($zad["NORM_FACT"]*1);
			$w_zad_fact[$zad["ID"]] = FReal($zad["FACT"]*1);
			$w_zad_date[$zad["ID"]] = $zad["DATE"];
			$w_zad_smen[$zad["ID"]] = $zad["SMEN"];

			$summ_norm = $summ_norm + $zad["NORM_FACT"]*1;
			$summ_fact = $summ_fact + $zad["FACT"]*1;
		}

	// Сортируем и запоминаем чего как называется

		$czad = count($zad_ID);

	  // db_resurs ///////////////////////////////////////////////////////////////////

		$w_resurs_name = Array();

		$xxx = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$resurs_IDs)) {
				for ($x=0;$x < $czad; $x++) {
					$id = $zad_ID[$x];
					if ($zadan_idr[$id]==$res["ID"]) $zad_ID_0[] = $id;
				}
				$w_resurs_name[$res["ID"]] = $res["NAME"];
			}
		}

	  // db_operitems ///////////////////////////////////////////////////////////////////

		$w_operitems_ord = Array();
		$w_operitems_oper = Array();

		$xxx = dbquery("SELECT ID, ORD, ID_oper FROM ".$db_prefix."db_operitems order by ORD");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$operitems_IDs)) {
				for ($x=0;$x < $czad; $x++) {
					$id = $zad_ID_0[$x];
					if ($zadan_ido[$id]==$res["ID"]) $zad_ID_1[] = $id;
				}
				$w_operitems_ord[$res["ID"]] = $res["ORD"];
				$w_operitems_oper[$res["ID"]] = $res["ID_oper"];
			}
		}

	  // db_zakdet ///////////////////////////////////////////////////////////////////

		$w_zakdet_name = Array();
		$w_zakdet_oboz = Array();

		$xxx = dbquery("SELECT ID, NAME, OBOZ FROM ".$db_prefix."db_zakdet order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$izd_IDs)) {
				for ($x=0;$x < $czad; $x++) {
					$id = $zad_ID_1[$x];
					if ($zadan_idzd[$id]==$res["ID"]) $zad_ID_2[] = $id;
				}
				$w_zakdet_name[$res["ID"]] = $res["NAME"];
				$w_zakdet_oboz[$res["ID"]] = $res["OBOZ"];
			}
		}

	  // db_zak ///////////////////////////////////////////////////////////////////

		$w_zak_tid = Array();
		$w_zak_name = Array();

		$xxx = dbquery("SELECT ID, TID, NAME FROM ".$db_prefix."db_zak order by ORD");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$zak_IDs)) {
				for ($x=0;$x < $czad; $x++) {
					$id = $zad_ID_2[$x];
					if ($zadan_idz[$id]==$res["ID"]) $zad_ID_s[] = $id;
				}
				$w_zak_tid[$res["ID"]] = FVal($res,"db_zak","TID");
				$w_zak_name[$res["ID"]] = $res["NAME"];
			}
		}

	  // db_resurs ///////////////////////////////////////////////////////////////////

		$w_dboper_name = Array();

		$xxx = dbquery("SELECT ID, NAME, TID FROM ".$db_prefix."db_oper order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			$w_dboper_name[$res["ID"]] = "<b>".FVal($res,"db_oper","TID")."</b> ".FVal($res,"db_oper","NAME");
		}


   // Шапка

	if( $truncated_data )
	{
		echo "<h2>Отчёт по производству изделий за период ( без нулевых данных )</h2>";
		echo "<h3>".$date." - ".$date2."</h3>";

		echo "<a href='index.php?do=show&formid=92&p0=$date&p1=$date2'>Полные данные</a><br><br>";
	}
		else
		{
			echo "<h2>Отчёт по производству изделий за период</h2>";
			echo "<h3>".$date." - ".$date2."</h3>";

			echo "<a href='index.php?do=show&formid=92&p0=$date&p1=$date2&p2'>Сокращенные данные</a><br><br>";
		}

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "	<thead>\n";
	echo "<tr class='first'>\n";
	echo "<td colspan='2'>Заказ</td>";
	echo "<td rowspan='2' width='200'>Наименование ДСЕ</td>";
	echo "<td rowspan='2'>Чертёж</td>";
	echo "<td colspan='2'>Операция</td>";
	echo "<td rowspan='2'>Кол-во, шт</td>";
	echo "<td rowspan='2'>Н/Ч</td>";
	echo "<td rowspan='2'>Затр. часы</td>";
	echo "<td rowspan='2'>Исполнитель</td>";
	echo "<td rowspan='2'>Дата</td>";
	echo "<td rowspan='2'>Смена</td>";
	echo "</tr>\n";
	echo "<tr class='first'>\n";
	echo "<td>Вид</td>";
	echo "<td width='100'>Номер</td>";
	echo "<td>Номер</td>";
	echo "<td>Наименование</td>";
	echo "</tr>\n";
	echo "	</thead>\n";

	$count = count($zad_ID_s);

	for ( $x = 0 ; $x < $count ; $x ++ ) {
			$id = $zad_ID_s[$x];

			$id_z = $zadan_idz[$id];
			$id_zd = $zadan_idzd[$id];
			$id_o = $zadan_ido[$id];
			$id_r = $zadan_idr[$id];

			// ID_zak|ID_zakdet|ID_operitems|ID_resurs
			//////////////////////////////////////////////////////////////////////

				$zad_num = $w_zad_num[$id];
				$zad_norm = $w_zad_norm[$id];
				$zad_fact = $w_zad_fact[$id];

				$out = $truncated_data && $zad_num == 0 && $zad_norm == 0 && $zad_fact == 0 ? false : true ;

				$tr = "";

				$tr .= "<tr data-d1='$truncated_data'
							data-d2='$zad_num'
							data-d3='$zad_norm'
							data-d4='$zad_fact'
							data-td='$out'>";
				$tr .= "<td class='Field'>".$w_zak_tid[$id_z]."</td>";
				$tr .= "<td class='Field'>".$w_zak_name[$id_z]."</td>";
				$tr .= "<td class='Field'>".$w_zakdet_name[$id_zd]."</td>";
				$tr .= "<td class='Field'>".$w_zakdet_oboz[$id_zd]."</td>";
				$tr .= "<td class='Field'>".$w_operitems_ord[$id_o]."</td>";
				$tr .= "<td class='Field'>".$w_dboper_name[$w_operitems_oper[$id_o]]."</td>";
				$tr .= "<td class='Field'>$zad_num</td>";
				$tr .= "<td class='Field'>$zad_norm</td>";
				$tr .= "<td class='Field'>$zad_fact</td>";

				$tr .= "<td class='Field'>".$w_resurs_name[$id_r]."</td>";
				$tr .= "<td class='Field'>".IntToDate($w_zad_date[$id])."</td>";
				$tr .= "<td class='Field'>".$w_zad_smen[$id]."</td>";
				$tr .= "</tr>";

				if( $out )
					echo $tr;

			//////////////////////////////////////////////////////////////////////
	}


	echo "<tr>";
	echo "<td class='Field AR' colspan='7'><b>ИТОГО:</b></td>";
	echo "<td class='Field'><b>".FReal($summ_norm)."</b></td>";
	echo "<td class='Field'><b>".FReal($summ_fact)."</b></td>";
	echo "<td class='Field' colspan='3'></td>";
	echo "</tr>";



	echo "</table>";

		$zak_IDs = Array();
		$resurs1_IDs = Array();
		$resurs2_IDs = Array();
		$resurs3_IDs = Array();

		$yyy = dbquery("SELECT ID, ID_zak, ID_resurs, SMEN FROM ".$db_prefix."db_zadan where (DATE = '".$pdate2."') and (EDIT_STATE = '1') order by ID");
		while ($zad = mysql_fetch_array($yyy)) {
			if (!in_array($zad["ID_zak"],$zak_IDs)) $zak_IDs[] = $zad["ID_zak"];
			if (($zad["SMEN"]*1==1) && (!in_array($zad["ID_resurs"],$resurs1_IDs))) $resurs1_IDs[] = $zad["ID_resurs"];
			if (($zad["SMEN"]*1==2) && (!in_array($zad["ID_resurs"],$resurs2_IDs))) $resurs2_IDs[] = $zad["ID_resurs"];
			if (($zad["SMEN"]*1==3) && (!in_array($zad["ID_resurs"],$resurs3_IDs))) $resurs3_IDs[] = $zad["ID_resurs"];
		}

		$znum = count($zak_IDs);
		$r1num = count($resurs1_IDs);
		$r2num = count($resurs2_IDs);
		$r3num = count($resurs3_IDs);

	echo "<br><br><table><tr><td>Сводные данные на $date2:<br><br>Заказов в работе: $znum<br>Рабочих 1 смены: $r1num<br>Рабочих 2 смены: $r2num<br>Рабочих 3 смены: $r3num</td></tr>";

}
?>