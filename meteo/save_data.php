<?php

date_default_timezone_set('Asia/Krasnoyarsk');

include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');

$last_data_date = $pdo->query("SELECT `meteo_date` FROM `okb_db_meteo` ORDER BY `meteo_date` DESC LIMIT 1")->fetchColumn();;

//file_put_contents('log.txt', print_r($_GET, true), FILE_APPEND);

// Записывать в базу только если с момента последней записи прошло более 10 минут (костыль, чтобы не перенастраивать метеостанцию и не лезть на мачту).
if ((time() - strtotime($last_data_date)) > 600) {
	$query = $pdo->query("INSERT INTO `okb_db_meteo`
					(`meteo_wind_speed_average`, `meteo_wind_speed_min`, `meteo_wind_speed_max`, `meteo_wind_direction`, `meteo_pressure`, 
					 `meteo_temp`, `meteo_temp_outer`, `meteo_humidity`, `meteo_voltage_inner`, `meteo_voltage_outer`, `meteo_date`)
					VALUES (" . $_GET['a'] . ", " . $_GET['m'] . ", " . $_GET['g'] . ", " . $_GET['d5'] . ", " . round($_GET['p']) . ",
							" . ceil($_GET['tp']) . ", " . ceil($_GET['te2']) . ", " . ceil($_GET['h']) . ", " . $_GET['b'] . ", " . $_GET['accum'] . ", NOW())");
}