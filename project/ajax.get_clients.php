<?php

 
include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/project/timesheet/functions.php');

$query_text = "SELECT `ID`,`NAME`,`TEL`,`ADR`,`GOROD`
						FROM `okb_db_clients`
						WHERE `NAME` LIKE " . $pdo->quote('%' . $_GET['text'] . '%');

$query = $pdo->query($query_text);

$json = array();

$i = 0;
			
while($row = $query->fetch(PDO::FETCH_ASSOC)) { 
	$json[$i]['id'] = $row['ID'];
	$json[$i]['address'] = iconv('cp1251', 'utf8', $row['ADR']);
	$json[$i]['telephone'] = iconv('cp1251', 'utf8', $row['TEL']);
	$json[$i]['label'] = iconv('cp1251', 'utf8', $row['NAME']) . ' (' . iconv('cp1251', 'utf8', $row['ADR']) . ')';
	
	++$i;
}
 
echo json_encode($json);

?>