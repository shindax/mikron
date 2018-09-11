<?php

error_reporting(0);

header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: Binary'); 
header('Content-disposition: attachment; filename=okbmikron-meteo-' . date('Y-m-d') .'.csv');
 
include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');
 
$stmt = $pdo->query("SELECT * FROM `okb_db_meteo` ORDER BY `meteo_date` DESC");

echo iconv('utf8', 'cp1251', 'Дата;Скорость ветра (средняя, м/с);Скорость ветра (минимальная, м/с);Скорость ветра (максимальная, м/с);Направление в градусах;Датчик температуры (внешний), °C);Влажность воздуха, %;Давление (мм рт. ст.)' . "\n");

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$meteo_wind_speed_average = $row['meteo_wind_speed_average'] / 10;
	$meteo_wind_speed_min = $row['meteo_wind_speed_min'] / 10;
	$meteo_wind_speed_max = $row['meteo_wind_speed_max'] / 10;
	
	$meteo_wind_direction = ($row['meteo_wind_direction'] / 1024) * 360;
	
	$meteo_pressure = $row['meteo_pressure'] / 1.33;
	
	echo $row['meteo_date'] . ';' . $meteo_wind_speed_average . ';' . $meteo_wind_speed_min . ';' . $meteo_wind_speed_max . ';' . 
		 round($meteo_wind_direction) . ';' . $row['meteo_temp_outer'] . ';' . $row['meteo_humidity'] . ';' . $meteo_pressure . "\n";
}
 