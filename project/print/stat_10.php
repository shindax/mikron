<?php

echo "<H2>Коммерческие заказы в работе</H2>";


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Вывод списка ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function OpenZakID($item,$n) {
		global $db_prefix;
		
		echo "<tr>";
		echo "<td class='Field AL'>".$n."</td>";

	   // Вид заказа
		echo "<td class='Field AL'>".FVal($item,"db_zak","TID")."</td>";

	   // № заказа
		echo "<td class='Field AL'>".$item["NAME"]."</td>";

	   // Наименование ДСЕ
		echo "<td class='Field AL'>".$item["DSE_NAME"]."</td>";

	   // Заказчик
		echo "<td class='Field AL'>".FVal($item,"db_zak","ID_clients")."</td>";

	   // Всего Н/Ч
		echo "<td class='Field'>".FVal($item,"db_zak","SUMM_N")."</td>";
		$norms = $norms+$dd;

	   // Остаток Н/Ч
		echo "<td class='Field'>".FVal($item,"db_zak","SUMM_NO")."</td>";

	   // Выполнено %
		echo "<td class='Field'>".FVal($item,"db_zak","SUMM_V")."</td>";

	   // Дата запуска
		echo "<td class='Field'>".FVal($item,"db_zak","DATE")."</td>";

	   // Дата окончания пр-ва
		$values = explode("|",$item["PD8"]);
		$numval = count($values)-1;
		$lastval = $values[$numval];
		if ($lastval=="") $lastval = "##";
		$lastval = explode("#",$lastval);

		echo "<td class='Field'>".$lastval[0]."</td>";

	   // Дата поставки
		echo "<td class='Field'><b>".FVal($item,"db_zak","DATE_PLAN")."</b></td>";

	   // Ответственный
		echo "<td class='Field'>".FVal($item,"db_zak","ID_users2")."</td>";


		echo "</tr>\n";
	}

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "<thead>";
	echo "<tr class='first'>\n";
	echo "<td width='35'>№</td>\n";
	echo "<td width='35'>Вид<br>заказа</td>\n";
	echo "<td width='80'>№ заказа</td>\n";
	echo "<td>Наимен.<br>заказа</td>\n";
	echo "<td>Заказчик</td>\n";
	echo "<td>Объём<br>Н/Ч</td>\n";
	echo "<td>Ост.<br>Н/Ч</td>\n";
	echo "<td>Вып.<br>%</td>\n";
	echo "<td>Дата запуска</td>\n";
	echo "<td>Дата окончания пр-ва</td>\n";
	echo "<td><b>Дата поставки</b></td>\n";
	echo "<td width='120'>Ответственный</td>\n";
	echo "</tr>\n";
	echo "</thead>";

	echo "<tbody>";

	$nn = 1;
   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (EDIT_STATE='0') and (ID_clients<>'23') order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenZakID($res,$nn);
		$nn = $nn + 1;
	}

	echo "</tbody>";
	echo "</table>";
?>