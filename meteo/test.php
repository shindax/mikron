<?php

date_default_timezone_set('Asia/Krasnoyarsk');

header('Content-type: text/html; charset=utf-8');

include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');
 
$stmt = $pdo->query("SELECT * FROM `okb_db_meteo` ORDER BY `meteo_date` DESC LIMIT 500");

$last_wind_direction = $pdo->query("SELECT `meteo_wind_direction` FROM `okb_db_meteo` ORDER BY `meteo_date` DESC LIMIT 1")->fetchColumn();

echo 'Последние 500 записей. Период измерений: 10 минут.' . '<br/><br/>Скачать: <a href="get_csv.php">.csv</a><br/><br/>';

echo '
<style>
td { text-align:center; } body { font-family:arial; }
tbody tr:hover {background-color:#ccc;}
div.wind_direction_arrow {
	font-weight:700;
	font-size:20pt;
	color:blue;
    transform: rotate(' . round(($last_wind_direction / 1024) * 360) . 'deg);
}
</style>
<table width="100%" border="1">
<thead>
	<tr>
		<th rowspan="2">Дата</th>
		<th colspan="3">Скорость ветра, м/с</th>
		<th rowspan="2">Направление ветра, ° <div class="wind_direction_arrow">⇡</div></th>
		<th rowspan="2">Датчик температуры (внешний), °C</th>
		<th rowspan="2">Влажность воздуха, %</th>
		<th rowspan="2">Давление, мм рт. ст.</th> 
	</tr>
	<tr>
		<th>Средняя</th>
		<th>Минимальная</th>
		<th>Максимальная</th>
	</tr>
<tbody>
';

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$meteo_wind_speed_average = $row['meteo_wind_speed_average'] / 10;
	$meteo_wind_speed_min = $row['meteo_wind_speed_min'] / 10;
	$meteo_wind_speed_max = $row['meteo_wind_speed_max'] / 10;
	
	$meteo_wind_direction = ($row['meteo_wind_direction'] / 1024) * 360;
	
	$meteo_pressure = $row['meteo_pressure'] / 1.33;
	
	echo '<tr><td>' . $row['meteo_date'] . '</td><td>' . $meteo_wind_speed_average . '</td><td>' . $meteo_wind_speed_min . '</td><td>' . $meteo_wind_speed_max . '</td>' . 
		 '<td>' . round($meteo_wind_direction) . '</td><td>' . $row['meteo_temp_outer'] . '</td><td>' . $row['meteo_humidity'] . '</td><td>' . round($meteo_pressure) . '</td>';
}

echo '
	</tbody>
</table>';
