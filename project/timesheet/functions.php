<?php

function getMedicalExaminationClass($DATE_LMO, $DATE_NMO)
{
	if ($DATE_NMO * 1 < date('Ymd', time()) || $DATE_LMO * 1 == 0 || $DATE_NMO * 1 == 0) {
		$class = ' medical_examination_3';
	} else 	if ($DATE_NMO * 1 < date('Ymd', strtotime('+30 days'))) {
		$class = ' medical_examination_2';
	} else {
		$class = ' medical_examination_1';
	}

	return $class;
}

function buildTree(array $elements, $parentId = 0,  $level = 0)
{ 
	$branch = array();

	foreach ($elements as $element) {
		if ($element['DepartmentPID'] == $parentId) {
			$children = buildTree($elements, $element['DepartmentID'], $level + 1);
				
			if ($children) {
				$element['DepartmentPID'] = $parentId;
				$element['DepartmentLevel'] = $level;
				$element['children'] = $children;
			}
			
			$branch[$element['DepartmentID']] = $element;
			$branch[$element['DepartmentID']]['DepartmentPID'] = $parentId;
			$branch[$element['DepartmentID']]['DepartmentLevel'] = $level;
		}
	}

	return $branch;
}



function getDayStatus($row)
{ 
	$day_status = array();
	
	switch ($row['TID'])
	{
		case 1:
			$day_status['text'] = 'нр';

			$day_status['class'] = ' hl_blue';
			break;
		case 2:
			$day_status['text'] = 'дн';
			
			if ($row['doc_issued'] == 0) {
				$day_status['class'] .= ' doc_not_issued';
			}
			break;
		case 3:
			$day_status['text'] = 'у';
			break;
		case 4:
			$day_status['text'] = 'а';
			
			$day_status['class'] = ' hl_blue';
			break;
		case 5:
			$day_status['text'] = 'мм';
			
			$day_status['class'] = ' hl_red';
			break;
		case 6:
			$day_status['text'] = 'оп';
			break;
		case 7:
			$day_status['text'] = 'б';
			break;
		case 8:
			$day_status['text'] = 'кв';
			break;
		case 9:
			$day_status['text'] = 'мб';
			break;
		case 10:
			$day_status['text'] = 'й';
			break;
		case 11:
			$day_status['text'] = 'по';
			break;
		case 12:
			$day_status['text'] = 'с';
			break;
		case 13:
			$day_status['text'] = 'ой';
			break;
		case 14:
			$day_status['text'] = 'мо';
			break;
	}
	
	return $day_status;
}

