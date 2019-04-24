<?php

set_time_limit(0);
include_once('db_mysql_pdo.php');

$query = $pdo->query("SELECT `ID`
						FROM `okb_db_operitems`");
$query = $pdo->query("SELECT ID FROM okb_db_operitems WHERE ID_zakdet = 581794");

		
while($row = $query->fetch(PDO::FETCH_ASSOC)) { 
	$zadan_data = $pdo->query("SELECT SUM(`NUM_FACT`) as `zadan_count_sum`,SUM(`FACT`) as `zadan_norm_sum`
									FROM `okb_db_zadan` WHERE `ID_operitems` = " . $row['ID'])->fetch(PDO::FETCH_ASSOC);

	/*$operations_with_coop_dep_data = $pdo->query("SELECT SUM(`count`) as `count`,SUM(`norm_hours`) as `norm_hours`
									FROM `okb_db_operations_with_coop_dep` WHERE `oper_id` = " . $row['ID'])->fetch(PDO::FETCH_ASSOC);

	$count = $zadan_data['zadan_count_sum'] + $operations_with_coop_dep_data['count'];
	$norm_hours = $zadan_data['zadan_norm_sum'] + $operations_with_coop_dep_data['norm_hours'];*/
	

	$count = $zadan_data['zadan_count_sum'];
	$norm_hours = $zadan_data['zadan_norm_sum'];

	$pdo->exec("UPDATE `okb_db_operitems` SET `FACT2_NUM` = '" . $count . "', `FACT2_NORM` = '" . $norm_hours . "' WHERE `ID` = " . $row['ID']);
}
 