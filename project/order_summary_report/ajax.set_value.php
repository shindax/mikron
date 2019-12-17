<?php

require_once('/var/www/test.okbmikron/www/db_mysql_pdo.php');

$order_id = (int) $_POST['order_id'];

$value = $pdo->quote($_POST['value']);
$type = $_POST['type'];

$pdo->exec("INSERT INTO `okb_db_order_summary_report` (" . $type . ", order_id) VALUES (" . $value . ", " . $order_id . ")
				ON DUPLICATE KEY UPDATE `" . $type . "` = " . $value);


?>