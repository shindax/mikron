<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" ); 

// TYPE = 2: есть напряжение на станке (да, нет / 1, 0).
// TYPE = 3: задействована механика станка (да, нет / 1, 0).
// TYPE = 4: вращение шпинделя (да, нет / 1, 0). 

$power = (bool) $pdo->query("SELECT `value` FROM `machine_log` WHERE `type` = 2 ORDER BY id DESC LIMIT 1")->fetchColumn();
$mechanic = (bool) $pdo->query("SELECT `value` FROM `machine_log` WHERE `type` = 3 ORDER BY id DESC LIMIT 1")->fetchColumn();
$spindle = (bool) $pdo->query("SELECT `value` FROM `machine_log` WHERE `type` = 4 ORDER BY id DESC LIMIT 1")->fetchColumn();

$json = [];

//var_dump($power, $mechanic, $spindle );

if (!$power && !$spindle && !$mechanic) {
	$json['status_color'] = 'red'; // Красный
} else if (($power && !$mechanic && !$spindle) || ($power && $mechanic && !$spindle)) {
	$json['status_color'] = 'orange';
} else if ($power && $spindle) {
	$json['status_color'] = 'green'; // Синий 
}

echo json_encode($json);

?>