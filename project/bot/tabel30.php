<?php


// ПОЕХАЛИ

	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$start_time = microtime(true);
	$dbquery_index = 0;

$DAY_NUM = 30;

$hour = date("H")*1;
echo "tabel bot report ".date("d.m.Y")." - ".date("H").":".date("i").":".date("s")."<br><br>";

// По дням месяца
for ($day=1;$day < $DAY_NUM+1;$day++) {

	$date = 1*date("Ymd",mktime (0,0,0,date("m") ,date("d")*1-$day ,date("Y")));


		$ids = Array();
		$fact = Array();
		$smen = Array();

	    // По заданиям к заказам
		$result = dbquery("SELECT ID, ID_resurs, FACT, SMEN, EDIT_STATE FROM ".$db_prefix."db_zadan where (DATE = '".$date."')");
		while ($zad = mysql_fetch_array($result)) {
			if (!in_array($zad["ID_resurs"],$ids)) {
				$ids[] = $zad["ID_resurs"];
				$fact[$zad["ID_resurs"]] = 0;
			}
			if ($zad["EDIT_STATE"]*1==1) $fact[$zad["ID_resurs"]] = $fact[$zad["ID_resurs"]]*1 + $zad["FACT"];
			$smen[$zad["ID_resurs"]] = $zad["SMEN"];
		}

	    // Вписываем факт в табель
		for ($j=0;$j < count($ids);$j++) {
			$xxx = dbquery("SELECT ID, TID, PLAN FROM ".$db_prefix."db_tabel where (ID_resurs='".$ids[$j]."') and (DATE='".$date."')");
			if ($xxx = mysql_fetch_array($xxx)) {
				dbquery("Update ".$db_prefix."db_tabel Set FACT:='".$fact[$ids[$j]]."' where (ID='".$xxx["ID"]."')");
				dbquery("Update ".$db_prefix."db_tabel Set SMEN:='".$smen[$ids[$j]]."' where (ID='".$xxx["ID"]."')");

				// Проставляем "Н" ресурсам там где есть план в СЗ на работу, но факт полностью нулевой
				// И проставляем "работал" у кого "Н" но есть факт
				//////////////////////////////////////////////////////////////////////////////////////////////////

					if (($fact[$ids[$j]]*1==0) &&  ($xxx["TID"]==0)) {
						if (($smen[$ids[$j]]==1) && (($hour>6) or ($day>1))) {
							dbquery("Update ".$db_prefix."db_tabel Set TID:='6' where (ID='".$xxx["ID"]."')");
						}
						if (($smen[$ids[$j]]==2) && (($hour>15) or ($day>1))) {
							dbquery("Update ".$db_prefix."db_tabel Set TID:='6' where (ID='".$xxx["ID"]."')");
						}
						if (($smen[$ids[$j]]==3) && (($hour>15) or ($day>1))) {
							dbquery("Update ".$db_prefix."db_tabel Set TID:='6' where (ID='".$xxx["ID"]."')");
						}
					}
					if (($fact[$ids[$j]]*1>0) && ($xxx["TID"]==6)) {
						dbquery("Update ".$db_prefix."db_tabel Set TID:='0' where (ID='".$xxx["ID"]."')");
					}

				//////////////////////////////////////////////////////////////////////////////////////////////////

			} else {
				dbquery("INSERT INTO ".$db_prefix."db_tabel (DATE, SMEN, ID_resurs, TID, PLAN, FACT) VALUES ('".$date."', '".$smen[$ids[$j]]."', '".$ids[$j]."', '0', '0', '".$fact[$ids[$j]]."')");
			}
		}

	    // Отчёт
		echo $date." - complete<br>";
}

	$mem_usage = memory_get_peak_usage(true)/1024;
	$exec_time = microtime(true) - $start_time;
echo "<br><br><br>
Время, сек: ".number_format($exec_time, 3, ',', ' ')." &nbsp; 
Память, кБ: ".number_format($mem_usage, 0, ',', ' ')." &nbsp; 
Запросов к БД: ".$dbquery_index;
?>