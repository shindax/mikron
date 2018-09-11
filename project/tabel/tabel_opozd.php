<?php

$my = explode(".",$_GET["p0"]);
$YY = $my[2];
$MM = $my[1];
$date_start = $YY*10000+$MM*100+0;
$date_end = $YY*10000+$MM*100+32;

$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

$MM = $DI_MName[$MM-1];

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Получаем список ID
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$opozd_ids = Array();
	$opozd_dates = Array();
	$opozd_count = Array();
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (OPOZD='1') and (DATE>'".$date_start."') and (DATE<'".$date_end."') order by DATE");
	while($tab = mysql_fetch_array($xxx)) {
		$opozd_ids[] = $tab["ID_resurs"];

		$dates = $opozd_dates[$tab["ID_resurs"]]."";
		if ($dates == "") {
			$dates = IntToDate($tab["DATE"]);
		} else {
			$dates = $dates.", ".IntToDate($tab["DATE"]);
		}

		$opozd_dates[$tab["ID_resurs"]] = $dates;
		$opozd_count[$tab["ID_resurs"]] = $opozd_count[$tab["ID_resurs"]]*1+1;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if ($print_mode == "on") echo "Отчёт от ".date("d.m.Y H:i",mktime());

		echo "<h2>Опоздавшие</h2><h4>".$MM." ".$YY." г</h4>";

		echo "<table class='rdtbl tbl' style='width: 900px;' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td width='200'>Ресурс</td>";
			echo "<td width='100'>Всего<br>опозданий</td>";
			echo "<td>Даты опозданий</td>";
		echo "</tr>";
		echo "</thead>";

		echo "<tbody>";

			
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$opozd_ids)) {
				echo "<tr>";
					echo "<td class='Field'>".$res["NAME"]."</td>";
					echo "<td class='Field'>".$opozd_count[$res["ID"]]."</td>";
					echo "<td class='Field'>".$opozd_dates[$res["ID"]]."</td>";
				echo "</tr>";
			}
		}


		echo "</tbody>";
		echo "</table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>