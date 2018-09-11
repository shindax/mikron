<?php
define('MAV_ERP', TRUE);
	
setlocale(LC_TIME, 'russian');

header('Content-type: text/plain; charset=windows-1251');

include '../../config.php';
include '../../includes/database.php';
include 'report_plan_functions.php';
	
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$children_progress = array();
		
function CalcProgress($row, $id)
{
	global $children_progress;

	if (isset($row['children'])) {
		foreach ($row['children'] as $children_id => $row_children) {
			CalcProgress($row_children, $children_id);

			@$children_progress[$id] += (($row_children['rp_progress'] + $children_progress[$children_id]) / count($row['children']));
		}
	}
}

switch ($_GET['mode'])
{
	case 'row_recalculation':
	$result = dbquery("SELECT `rp_id`, `rp_pid`, `rp_progress`
							FROM `okb_db_report_plan_items`
							WHERE `rp_report_id` = " . (int) $_GET['report_id']);

	$rp_array = array();		

	$i = 0;
		
	while ($row = mysql_fetch_assoc($result)) {
		$rp_array[++$i] = array(	'rp_pid'		=> $row['rp_pid'],
									'rp_id'			=> $row['rp_id'],
									'rp_progress'	=> $row['rp_progress']
						);
	}
	
	$rows = buildTree($rp_array);

	foreach ($rows as $id => $row) {
		CalcProgress($row, $id);
	}

	echo json_encode($children_progress);
	
	break;
	case 'add_row':
	dbquery("INSERT INTO `okb_db_report_plan_items` (`rp_report_id`, `rp_pid`) VALUES (" . (int) $_POST['rp_report_id'] . ", " . (int) $_POST['rp_pid'] . ")");
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_progress` = 0 WHERE `rp_id` = " . (int) $_POST['rp_pid']);
	
	echo mysql_result(dbquery("SELECT MAX(`rp_id`) FROM `okb_db_report_plan_items`"), 0);
	
	break;
	case 'update_title':
	$_POST['rp_title'] = iconv('utf-8', 'windows-1251', $_POST['rp_title']);

	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_title` = '" . mysql_real_escape_string($_POST['rp_title']) . "'
				WHERE `rp_id` = " . (int) $_POST['rp_id']);

	break;
	case 'update_comment':
	$_POST['rp_comment'] = iconv('utf-8', 'windows-1251', $_POST['rp_comment']);
	
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_comment` = '" . mysql_real_escape_string($_POST['rp_comment']) . "'
				WHERE `rp_id` = " . (int) $_POST['rp_id']);
			
	break;
	case 'remove_row':
	dbquery("DELETE FROM `okb_db_report_plan_items` WHERE `rp_id` = " . (int) $_POST['rp_id'] . " OR `rp_pid` = " . (int) $_POST['rp_id']);
			
	break;
	case 'update_progress':
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_progress` = " . (int) $_POST['rp_progress'] . " WHERE `rp_id` = " . (int) $_POST['rp_id']);
	
	break;	
	case 'update_resource':
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_resource_id` = " . (int) $_POST['rp_resource_id'] . " WHERE `rp_id` = " . (int) $_POST['rp_id']);
	
	break;
	case 'update_department':
	dbquery("UPDATE `okb_db_report_plan` SET `report_department_id` = " . (int) $_POST['report_department_id'] . " WHERE `report_id` = " . (int) $_POST['report_id']);
	
	break;
	case 'update_report_title':
	dbquery("UPDATE `okb_db_report_plan` SET `report_title` = '" . mysql_real_escape_string(iconv('utf-8', 'windows-1251', $_POST['report_title'])) . "' WHERE `report_id` = " . (int) $_POST['report_id']);
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_title` = '" . mysql_real_escape_string(iconv('utf-8', 'windows-1251', $_POST['report_title'])) . "' WHERE `rp_pid` = 0 AND `rp_report_id` = " . (int) $_POST['report_id']);
	
	break;
	case 'get_departments':
	$result = dbquery("SELECT `ID`, `NAME` FROM `okb_db_otdel` ORDER BY `ID` ASC");
	
	while ($row = mysql_fetch_assoc($result)) {
		echo '<option value="' . $row['ID'] . '"' . ($_GET['report_department_id'] == $row['ID'] ? ' selected="selected"' : '') . '>' . $row['NAME'] . '</option>';
	}
	
	break;
	case 'update_date_start':
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_date_start` = '" . mysql_real_escape_string($_POST['rp_date_start']) . "' WHERE `rp_id` = " . (int) $_POST['rp_id']);
		
	$row = mysql_fetch_assoc(dbquery("SELECT DATEDIFF(`rp_date_end`, `rp_date_start`) + 1 as `rp_date_count`, UNIX_TIMESTAMP(`rp_date_start`) as `rp_date_start`, UNIX_TIMESTAMP(`rp_date_end`) as `rp_date_end`
										FROM `okb_db_report_plan_items`
										WHERE `rp_id` = " .  (int) $_POST['rp_id']));
		
	echo json_encode(array(	'rp_date_start' => fixJSON(strftime('%a %d.%m.%Y', $row['rp_date_start'])),
							'rp_date_end'	=> fixJSON(strftime('%a %d.%m.%Y', $row['rp_date_end'])),
							'rp_date_count' => ($row['rp_date_count'] != 0 && $row['rp_date_count'] > 0 ? fixJSON($row['rp_date_count'] . ' ' . days($row['rp_date_count'])) : '')
						)
					);

	break;
	case 'update_date_end':
	dbquery("UPDATE `okb_db_report_plan_items` SET `rp_date_end` = '" . mysql_real_escape_string($_POST['rp_date_end']) . "' WHERE `rp_id` = " . (int) $_POST['rp_id']);
		
	$row = mysql_fetch_assoc(dbquery("SELECT DATEDIFF(`rp_date_end`, `rp_date_start`) + 1 as `rp_date_count`, UNIX_TIMESTAMP(`rp_date_start`) as `rp_date_start`, UNIX_TIMESTAMP(`rp_date_end`) as `rp_date_end`
										FROM `okb_db_report_plan_items`
										WHERE `rp_id` = " .  (int) $_POST['rp_id']));
		
	echo json_encode(array(	'rp_date_start' => fixJSON(strftime('%a %d.%m.%Y', $row['rp_date_start'])),
							'rp_date_end'	=> fixJSON(strftime('%a %d.%m.%Y', $row['rp_date_end'])),
							'rp_date_count' => ($row['rp_date_count'] != 0 && $row['rp_date_count'] > 0 ? fixJSON($row['rp_date_count'] . ' ' . days($row['rp_date_count'])) : '')
						)
					);
					
	break;
	case 'get_resources':
	$query = dbquery("SELECT okb_db_resurs.`NAME`, okb_db_resurs.`ID` FROM okb_db_shtat
							LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_shtat.ID_resurs
							WHERE ((ID_resurs != '0') and ((ID_otdel = '18') or (ID_otdel = '19') or (ID_otdel = '21') or (ID_otdel = '22')))");

	$fruits_1 = array();
								
	while($row = mysql_fetch_assoc($query)){
		$fruits_1[$row["ID"]] = $row["NAME"];
	}

	$query = dbquery("SELECT okb_db_resurs.`NAME`, okb_db_resurs.`ID` FROM okb_db_shtat 
							LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_shtat.ID_resurs
							WHERE ((ID_resurs != '0') and (ID_otdel != '18') and (ID_otdel != '19') and (ID_otdel != '21') and (ID_otdel != '22'))");

	$fruits_2 = array();
			
	while($row = mysql_fetch_assoc($query)){
		$fruits_2[$row['ID']] = $row['NAME'];
	}
									
	asort($fruits_1);
	asort($fruits_2);
			
	$_GET['resource_name'] = iconv('utf-8', 'windows-1251', $_GET['resource_name']);

	echo "<option style='color:red;' value='0' name>--- (производство)</option>";
			
	foreach ($fruits_1 as $key => $value) {
		echo "<option value='". $key ."'" . ($_GET['resource_name'] == $value ? ' selected="selected"' : '') . ">" . $value . '</option>';
	}
	
	echo "<option style='color:red;' value='0'>--- (остальной персонал)</option>";
	
	foreach ($fruits_2 as $key => $value) {
		echo "<option value='". $key ."'" . ($_GET['resource_name'] == $value ? ' selected="selected"' : '') . ">" . $value . '</option>';
	}
	
	break;
}

//  остыль дл€ PHP 5.2
function fixJSON($str)
{
	$str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
	
	return iconv('cp1251', 'utf-8', $str);
}