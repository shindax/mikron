<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "includes.php";
	$opened = explode("|",$opened);

	$start_time = microtime(true);


   // Используем функции ГАНТ
	include "includes/gant.php";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////





    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Расчёт и вывод по операции
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function ID_oper($item,$n) {
		global $db_prefix, $gant_start, $gant_end, $gant_p_num, $gant_f_num;


		ClearARR();
		CalcOPS($item["ID"]);

	   // Вывод
		echo "<tr id='R_o".$item["ID"]."' class='GNT OPER_TR' onClick=\"select('o".$item["ID"]."',1);\">";
		OutDates();
		echo "</tr>";
	}



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Расчёт и вывод по ДСЕ
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function ID_zakdet($item,$n) {
		global $db_prefix, $opened, $gant_p_num, $gant_f_num, $gant_start, $gant_end;


		ClearARR();
		CalcIZD($item["ID"]);

	   // Вывод
		echo "<tr id='R_i".$item["ID"]."' class='GNT IZD_TR' onClick=\"select('i".$item["ID"]."',0);\">";
		OutDates();
		echo "</tr>";

		$doopen = is_opened("i".$item["ID"],$opened);
		if (is_opened("zak".$item["ID_zak"],$opened)) $doopen = true;

		if ($doopen) {
			$xx = dbquery("SELECT ID, ID_oper FROM ".$db_prefix."db_operitems where (ID_zakdet='".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_oper($res,$n+1);
			}

			$xx = dbquery("SELECT ID, OBOZ, NAME, ID_zak FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_zakdet($res,$n+1);
			}
		}
	}



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Расчёт и вывод по заказу
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function ID_zak($item,$n) {
		global $db_prefix, $opened;

		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_zakdet where  (ID_zak='".$item["ID"]."') and (PID='0')");
		$izd = mysql_fetch_array($xxx);

		ClearARR();
		CalcZAK($item["ID"]);

	   // Вывод
		echo "<tr id='R_i".$izd["ID"]."' class='GNT ZAK_TR' onClick=\"select('i".$izd["ID"]."',0);\">";
		OutDates();
		echo "</tr>";

		$doopen = is_opened("i".$izd["ID"],$opened);
		if (is_opened("zak".$item["ID"],$opened)) $doopen = true;

		if ($doopen) {
			$xx = dbquery("SELECT ID, ID_oper FROM ".$db_prefix."db_operitems where  (ID_zakdet='".$izd["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_oper($res,$n+1);
			}

			$xx = dbquery("SELECT ID, OBOZ, NAME, ID_zak FROM ".$db_prefix."db_zakdet where  (PID='".$izd["ID"]."') order by ORD");
			while($res = mysql_fetch_array($xx)) {
				ID_zakdet($res,$n+1);
			}
		}
	}







ClearARR();
echo "<table style='height: 20px;'>";
$xxx = dbquery("SELECT ID, TID, NAME FROM ".$db_prefix."db_zak where (EDIT_STATE='0') and (INGANT='1') order by PRIOR, ID");
while($res = mysql_fetch_array($xxx)) {
	ID_zak($res,0);
}
echo "</table>";







///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  STAT       ////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<div id='stat' style='display: none;'>";
	$mem_usage = memory_get_peak_usage(true)/1024;
	$exec_time = microtime(true) - $start_time;
//echo implode("|",$opened)."</div>";
echo utftxt("Время, сек: ".number_format($exec_time, 3, ',', ' ')." &nbsp; Память, кБ: ".number_format($mem_usage, 0, ',', ' ')." &nbsp; Запросов к БД: ".$dbquery_index."");
echo "</div>";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>