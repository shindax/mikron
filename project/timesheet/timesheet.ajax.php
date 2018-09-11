<?php

define('MAV_ERP', true);

include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/project/timesheet/functions.php');

switch ($_GET['action'])
{
	case 'setDayData':
		$timesheet_array = json_decode($_POST['json'], true);

		foreach ($timesheet_array as $timesheet_data) {
			$ID_tab = $pdo->query("SELECT `ID_tab` FROM `okb_db_resurs` WHERE `ID` = " . $timesheet_data['user_id']);
			
			//if ($ID_tab != $user['ID']) continue;
			
			$sql = " UPDATE `okb_db_tabel` SET  `FACT` = " . $_POST['work_hours'] . ",
												`TID`  = " . (empty($_POST['tid']) ? 0 : $_POST['tid']) . ",
												`SMEN`  = " . $_POST['shift'] . ",
													WHERE `ID_resurs` = " . $timesheet_data['user_id'] . " AND `DATE` = " . $timesheet_data['date'] . "
												\n";
								
			echo $sql;
			/*$pdo->query("SELECT r.`ID` as `user_id`, `r`.`NAME` as `user_name`,
							`o`.`ID` as `DepartmentID`, `DATE_NMO`, `DATE_LMO`
								FROM `okb_db_resurs` `r`
								LEFT JOIN `okb_db_shtat` `s` ON `s`.`ID_resurs` = `r`.`ID`	
								LEFT JOIN `okb_db_otdel` `o` ON `o`.`ID` = `s`.`ID_otdel`
								WHERE `r`.`TID` = 0 AND `r`.`ID` != 0
								ORDER BY `s`.`BOSS` DESC, `r`.`NAME` ASC")*/
		}
		break;
	case 'getDayData':
		header('Content-type: application/javascript');

		echo json_encode($pdo->query("SELECT `FACT`,`SMEN`,`TID`
										FROM `okb_db_tabel`
											WHERE `ID_resurs` = " . $_POST['user_id'] . " AND
												  `DATE` = " . $_POST['date'])->fetch(PDO::FETCH_ASSOC));
		
		break;
	case 'getDayData':
		header('Content-type: application/javascript');

		echo json_encode($pdo->query("SELECT `FACT`,`SMEN`,`TID`
										FROM `okb_db_tabel`
											WHERE `ID_resurs` = " . $_POST['user_id'] . " AND
												  `DATE` = " . $_POST['date'])->fetch(PDO::FETCH_ASSOC));
		
		break;
}

?>