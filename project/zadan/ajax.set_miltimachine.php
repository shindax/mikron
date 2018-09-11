<?php

 
include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');
 
$query = $pdo->query("UPDATE `okb_db_zadanres` SET `is_multimachine` = " . (int) $_GET['value'] . " WHERE `ID_resurs` = " . (int) $_GET['resource_id'] . " AND `DATE` = " . (int) $_GET['date']);


if (isset($_GET['multimachine_fact'])) {
 
	$query = $pdo->query("UPDATE `okb_db_zadanres` SET `is_multimachine` = " . (int) $_GET['value'] . ", `multimachine_fact` = '" . str_replace(',', '.', $_GET['multimachine_fact']) . "' WHERE `ID_resurs` = " . (int) $_GET['resource_id'] . " AND `DATE` = " . (int) $_GET['date']);
	
}
 
?>