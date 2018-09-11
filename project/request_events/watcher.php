<?php

define('MAV_ERP', true);
date_default_timezone_set('Asia/Krasnoyarsk');

//header('Content-type: text/plain; charset=utf-8');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/project/request_events/functions.php');

include '../../config.php';
include '../../includes/database.php';
	
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

switch ($_GET['mode'])
{
	case 'restartRequest':
		switch ($_GET['type'])
		{
			case 'it':
				$original_date = mysql_result(dbquery("SELECT `DATE` FROM `okb_db_it_req` WHERE `ID` = " . $_GET['pid']), 0);
				$username = mysql_result(dbquery("SELECT `FIO` FROM `okb_users` WHERE `ID` = " . $_GET['user_id_from']), 0);
			
				dbquery("UPDATE `okb_db_it_req` SET `EDIT_STATE` = 0, `DATE` = " . date('Ymd') . ", `OTCHET` = concat('\n<b><i>" . date('d.m.Y H:i:s') . "</i> - " . $username . '</b>: <span style="color:red">заявка перезапушена, оригинальное время заявки - ' . GetSplitDate($original_date) . ".</span><br/>', `OTCHET`) WHERE `ID` = " . $_GET['pid']);
				
						
				dbquery("INSERT INTO `okb_db_request_events` (`request_pid`, `request_user_id_from`, `request_user_id_to`, `request_datetime`, `request_status`, `request_text`, `request_type`, `request_event`)
						VALUES (" . (int) $_GET['pid'] . ", " . (int) $_GET['user_id_to'] . ", " . (int) $_GET['user_id_from'] . ", NOW(), 0, '', 'it', 'restart')");

	


				break;
			case 'ogi':
				$original_date = mysql_result(dbquery("SELECT `DATE` FROM `okb_db_ogi_req` WHERE `ID` = " . $_GET['pid']), 0);
				$username = mysql_result(dbquery("SELECT `FIO` FROM `okb_users` WHERE `ID` = " . $_GET['user_id']), 0);
			
				dbquery("UPDATE `okb_db_ogi_req` SET `EDIT_STATE` = 0, `DATE` = " . date('Ymd') . ", `OTCHET` = concat('\n<b><i>" . date('d.m.Y H:i:s') . "</i> - " . $username . '</b>: <span style="color:red">заявка перезапушена, оригинальное время заявки - ' . GetSplitDate($original_date) . ".</span><br/>', `OTCHET`) WHERE `ID` = " . $_GET['pid']);
				
						
				dbquery("INSERT INTO `okb_db_request_events` (`request_pid`, `request_user_id_from`, `request_user_id_to`, `request_datetime`, `request_status`, `request_text`, `request_type`, `request_event`)
						VALUES (" . (int) $_GET['pid'] . ", " . (int) $_GET['user_id_to'] . ", " . (int) $_GET['user_id_from'] . ", NOW(), 0, '', 'ogi', 'restart')");

	


				break;
				case 'tmc':
				$original_date = mysql_result(dbquery("SELECT `DATE` FROM `okb_db_tmc_req` WHERE `ID` = " . $_GET['pid']), 0);
				$username = mysql_result(dbquery("SELECT `FIO` FROM `okb_users` WHERE `ID` = " . $_GET['user_id']), 0);
			
				dbquery("UPDATE `okb_db_tmc_req` SET `EDIT_STATE` = 0, `DATE` = " . date('Ymd') . ", `MORE` = concat('\n<b><i>" . date('d.m.Y H:i:s') . "</i> - " . $username . '</b>: <span style="color:red">заявка перезапушена, оригинальное время заявки - ' . GetSplitDate($original_date) . ".</span><br/>', `OTCHET`) WHERE `ID` = " . $_GET['pid']);
				
						
				dbquery("INSERT INTO `okb_db_request_events` (`request_pid`, `request_user_id_from`, `request_user_id_to`, `request_datetime`, `request_status`, `request_text`, `request_type`, `request_event`)
						VALUES (" . (int) $_GET['pid'] . ", " . (int) $_GET['user_id_to'] . ", " . (int) $_GET['user_id_from'] . ", NOW(), 0, '', 'tmc', 'restart')");

	


				break;
				case 'logistic':
				$original_date = mysql_result(dbquery("SELECT `DATE_СREATE` FROM `okb_db_logistic_app` WHERE `ID` = " . $_GET['pid']), 0);
				$username = mysql_result(dbquery("SELECT `FIO` FROM `okb_users` WHERE `ID` = " . $_GET['user_id']), 0);
			
				dbquery("UPDATE `okb_db_logistic_app` SET `FINISH_STATE` = 0, `DATE` = " . time() . ", `DATE_СREATE` = " . date('Ymd') . ", `COMMENT` = concat('\n<b><i>" . date('d.m.Y H:i:s') . "</i> - " . $username . '</b>: <span style="color:red">заявка перезапушена, оригинальное время заявки - ' . GetSplitDate($original_date) . ".</span><br/>', `COMMENT`) WHERE `ID` = " . $_GET['pid']);
				
						
				dbquery("INSERT INTO `okb_db_request_events` (`request_pid`, `request_user_id_from`, `request_user_id_to`, `request_datetime`, `request_status`, `request_text`, `request_type`, `request_event`)
						VALUES (" . (int) $_GET['pid'] . ", " . (int) $_GET['user_id_to'] . ", " . (int) $_GET['user_id_from'] . ", NOW(), 0, '', 'logistic', 'restart')");

	


				break;

		}

		break;
	case 'getEventCount':
		$event_count = array();

		$event_count['all'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_status` = 0"), 0);
		$event_count['it'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'it' AND `request_status` = 0"), 0);
		$event_count['ogi'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'ogi' AND `request_status` = 0"), 0);
		$event_count['tmc'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'tmc' AND `request_status` = 0"), 0);
		$event_count['logistic'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'logistic' AND `request_status` = 0"), 0);
		$event_count['hr'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'hr' AND `request_status` = 0"), 0);
		$event_count['zak'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'zak' AND `request_status` = 0"), 0);
		$event_count['zakreq'] = mysql_result(dbquery("SELECT COUNT(`request_id`) FROM `okb_db_request_events` WHERE `request_user_id_to` = " . $_GET['user_id'] . " AND `request_user_id_from` != `request_user_id_to` AND `request_type` = 'zakreq' AND `request_status` = 0"), 0);

		echo json_encode($event_count);
		break;
	case 'change_status':
		dbquery("UPDATE `okb_db_request_events` SET `request_status` = " . $_POST['status'] . " WHERE `request_id` = " . $_POST['request_id']);
		break;
	case 'add_comment':
		if (empty($_POST['text'])) {	
			switch ($_POST['type'])
			{
				case 'it':
					echo mysql_result(dbquery("SELECT `OTCHET` FROM `okb_db_it_req` WHERE `ID` = " . $_POST['pid']), 0);
					break;
				case 'ogi':
					echo mysql_result(dbquery("SELECT `OTCHET` FROM `okb_db_ogi_req` WHERE `ID` = " . $_POST['pid']), 0);
					break;
				case 'tmc':
					echo mysql_result(dbquery("SELECT `MORE` FROM `okb_db_tmc_req` WHERE `ID` = " . $_POST['pid']), 0);
					break;
				case 'logistic':
					echo mysql_result(dbquery("SELECT `COMMENT` FROM `okb_db_logistic_app` WHERE `ID` = " . $_POST['pid']), 0);
					break;
			}

			return;
		}
		
		$_POST['text'] = mysql_real_escape_string($_POST['text']);
		$_POST['text'] = iconv('utf-8', 'cp1251', $_POST['text']);
		
	//	if ($user_id_from != $user_id_to) {
			dbquery("INSERT INTO `okb_db_request_events` (`request_pid`, `request_user_id_from`, `request_user_id_to`, `request_datetime`, `request_status`, `request_text`, `request_type`, `request_event`)
						VALUES (" . (int) $_POST['pid'] . ", " . (int) $_POST['user_id_from'] . ", " . (int) $_POST['user_id_to'] . ", NOW(), 0, '" . ($_POST['text']) . "', '" . mysql_real_escape_string($_POST['type']) . "', 'comment')");
	//	}

		$username = mysql_result(dbquery("SELECT `FIO` FROM `okb_users` WHERE `ID` = " . $_POST['user_id_from']), 0);
		
		switch ($_POST['type'])
		{
			case 'it':
				dbquery("UPDATE `okb_db_it_req` SET `OTCHET` = concat('\n<b><i>" . date('d.m.Y H:i:s') . '</i> - ' . $username . '</b>:  '. ($_POST['text']) . "<br/>', `OTCHET`) WHERE `ID` = " . $_POST['pid']);			
				echo mysql_result(dbquery("SELECT `OTCHET` FROM `okb_db_it_req` WHERE `ID` = " . $_POST['pid']), 0);
				break;
			case 'ogi':
				dbquery("UPDATE `okb_db_ogi_req` SET `OTCHET` = concat('\n<b><i>" . date('d.m.Y H:i:s') . '</i> - ' . $username . '</b>:  '. ($_POST['text']) . "<br/>', `OTCHET`) WHERE `ID` = " . $_POST['pid']);			
				echo mysql_result(dbquery("SELECT `OTCHET` FROM `okb_db_ogi_req` WHERE `ID` = " . $_POST['pid']), 0);
				break;
			case 'ogi':
				dbquery("UPDATE `okb_db_tmc_req` SET `OTCHET` = concat('\n<b><i>" . date('d.m.Y H:i:s') . '</i> - ' . $username . '</b>:  '. ($_POST['text']) . "<br/>', `OTCHET`) WHERE `ID` = " . $_POST['pid']);			
				echo mysql_result(dbquery("SELECT `OTCHET` FROM `okb_db_tmc_req` WHERE `ID` = " . $_POST['pid']), 0);
				break;
			case 'logistic':
				dbquery("UPDATE `okb_db_logistic_app` SET `COMMENT` = concat('\n<b><i>" . date('d.m.Y H:i:s') . '</i> - ' . $username . '</b>:  '. ($_POST['text']) . "<br/>', `COMMENT`) WHERE `ID` = " . $_POST['pid']);			
				echo mysql_result(dbquery("SELECT `COMMENT` FROM `okb_db_logistic_app` WHERE `ID` = " . $_POST['pid']), 0);
				break;
		}

		break;
	case 'add_event':
	
		$_POST['text'] = iconv('utf-8', 'cp1251', $_POST['text']);
		dbquery("INSERT INTO `okb_db_request_events` (`request_pid`, `request_user_id_from`, `request_user_id_to`, `request_datetime`, `request_status`, `request_text`, `request_type`, `request_event`)
						VALUES (" . (int) $_POST['pid'] . ", " . (int) $_POST['user_id_from'] . ", " . (int) $_POST['user_id_to'] . ", NOW(), 0, '" . mysql_real_escape_string($_POST['text']) . "', '" . mysql_real_escape_string($_POST['type']) . "', '" . mysql_real_escape_string($_POST['event']) . "')");
		break;
	case 'get_events':
		$result = dbquery("SELECT `request_pid` FROM `okb_db_request_events` WHERE `request_type` = '" . mysql_real_escape_string($_GET['type']) . "' GROUP BY `request_pid`");
		
		$json = array();
		
		while ($row = mysql_fetch_array($result)) {
			$json[]['pid'] = $row['request_pid'];
		}
		
		echo json_encode($json);
	case 'get_event':
		$result = dbquery("SELECT *,`okb_users`.`FIO` as `user_name`,DATE_FORMAT(`okb_db_request_events`.`request_datetime`, '%d.%m.%Y %H:%i:%s') as `datetime` FROM `okb_db_request_events`
						LEFT JOIN `okb_users` ON `okb_users`.`ID` = `okb_db_request_events`.`request_user_id_from`
						WHERE `request_pid` = " . (int) $_GET['pid'] . "
						ORDER BY `request_datetime` DESC");

		$html = '';
		
		while ($row = mysql_fetch_array($result)) {
			$request_event = '';
			
			switch ($row['request_event'])
			{
				case 'edit':
					$request_event = 'Редактирование';
					break;
				case 'ok':
					if ($row['request_text'] == 1) {
						$request_event = 'Согласовано';
					} else {
						$request_event = 'Отмена согласования';
					}
					break;
				case 'done':
					if ($row['request_text'] == 1) {
						$request_event = 'Выполнено';
					} else {
						$request_event = 'Отмена выполнения';
					}
					break;
				case 'comment':
					$request_event = 'Комментарий';
					break;
				case 'title':
					$request_event = 'Наименование';
					break;
			}
			
	
	
			$html .= '<tr class="event" style="text-decoration:italic"><td></td><td style="border-left:1px dashed red;background-color:#e5ffee" class="Field">' . $row['datetime'] . '</td><td class="Field" style=";background-color:#e5ffee">' . $row['user_name'] . '</td><td style="border-right:1px dashed red;background-color:#e5ffee" colspan="4" class="Field">' . $request_event . '</td></tr>';
			
			if ($row['request_text'] != '' && $row['request_event'] != 'ok' && $row['request_event'] != 'done') {
				$html .= '<tr class="event"><td></td><td style="border-left:1px dashed red;"></td><td class="Field" colspan="10" style="text-align:right;background-color:#e5ffee;border-right:1px dashed red">' . htmlspecialchars($row['request_text']) . '</td></tr>';
			}
		}
		
		echo $html;
}

