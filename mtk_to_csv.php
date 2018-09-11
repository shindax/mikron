<?php



	define("MAV_ERP", TRUE);
	

	include 'config.php';
	include 'includes/database.php';
		header('Content-type: text/plain; charset=windows-1251');

	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
$str = iconv('utf-8', 'cp1251', 'Наименование ДСЕ;№ чертежа ДСЕ;Кол-во на ед.;Кол-во на заказ;Вып. %;Наличие МТК' . "\n");

	
$id = 1830;
	
	
$query = dbquery("SELECT * FROM `okb_db_zakdet` WHERE `ID_zak` = " . $id . " AND PID = 0");
	
while ($row = mysql_fetch_assoc($query)) {

	
		$str .=   $row['NAME'] . ';' . $row['OBOZ'] .  "\n";
	
	
	getChild($row['ID']);

}
// Наименование ДСЕ;№ чертежа ДСЕ;Кол-во на ед.;Кол-во на заказ;Вып. %;Наличие МТК

	
function getChild($pid) {
	global $id; 
	global $str;
	$query = dbquery("SELECT * FROM `okb_db_zakdet` 
					WHERE  ID_zak = " . $id . " AND `PID` = " . $pid);
	
	while ($row = mysql_fetch_assoc($query)) {
		
		
		if (!empty($row['LID'])) {
			$row = mysql_fetch_assoc(dbquery("SELECT * FROM `okb_db_zakdet` WHERE   ID_zak = " . $id . " AND  `ID` = " . $row['LID']));
		}
		
		
		
		$have_mtk = mysql_result(dbquery("SELECT ID FROM okb_db_mtk_perehod WHERE ID_zakdet = " . $row['ID']  . " LIMIT 1"), 0); 
 
		$str .=   $row['NAME'] . ';' . str_replace(';', '', $row['OBOZ']) . ';' . $row['COUNT'] . ';' . $row['RCOUNT'] . ';' . $row['PERCENT'] . ';' . (!empty($have_mtk) ? '+' : '-')  . "\n";
		
		getChild($row['ID']);
	}
}

file_put_contents($id . '.csv', $str);