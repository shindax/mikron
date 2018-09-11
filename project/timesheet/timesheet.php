<style type="text/css">
table.timesheet thead td {
padding:5px !important;
}

h1  {
	
}

table.timesheet {
	float:left;
}

#multi_apply {
	 
}

table.timesheet {
	table-layout:fixed !important;
}

table.timesheet tbody td:nth-child(1) {
	padding:6px !important;
}

table.timesheet tbody td:nth-child(2) {
	width:35px !important;
	height:35px !important;
}

table.timesheet tbody td {
	width:35px !important;
	height:35px !important;
}


table.timesheet td {
	border: 1px solid black;

    color: #444444;
	position:relative;
}

table.timesheet td {
	background-color:#fff;
	width:10px !important;
padding:0;
	vertical-align:middle !important;
}
table.timesheet thead th {
	background-color:#98b8e2;
	text-align:center;
	padding:4px;
	border:1px solid #000;
	cursor:pointer;
}
table.timesheet thead th:nth-child(1),table.timesheet thead th:nth-child(2),table.timesheet thead th:nth-last-child {
	
	cursor:default !important;
}

table.timesheet thead tr {
	padding:5px;
}
table.timesheet td:nth-child(1) {
	height:30px !important;
width:130px !important;

	vertical-align:middle !important;
}

table.timesheet tbody td {
	text-align:center;
	cursor:pointer;
}
table.timesheet tbody td:nth-child(1), table.timesheet tbody td:nth-child(2) {
	text-align:left !important;
	cursor:default;
}

table.timesheet tbody tr[data-department-id] td b {
	padding-left:50px;
}
table.timesheet tbody tr[data-department-id] td {
	background-color:#c8daf2;
}
table.timesheet tbody tr[data-department-id] td a {
	padding-left:50px;
}

table.timesheet tbody td.hl_red {
	color:#c91212 !important;
}
table.timesheet tbody td.hl_blue {
	color:blue;
}

table.timesheet tbody td.doc_not_issued {
	background-color:#F08080 !IMPORTANT;
}

a.prev_next_month {
	
}

div.month_selector
{
	float:left;
	margin-left:50px;
	margin-top:-6px;
}
div.month_selector span
{
	font-size:18pt;
	font-weight:bold;
}
div.month_selector a
{
	font-size:11pt;
	padding:0 20px 0 20px;
}

table.timesheet tbody td.medical_examination_1 {
	background-color:#afa;
}
table.timesheet tbody td.medical_examination_2 {
	background-color:#faa;
}
table.timesheet tbody td.medical_examination_3 {
	background-color:#f44;
}

table.timesheet td.holiday {
	background: rgb(255, 234, 200);
}

table.timesheet td.selected {
	background-color:#000!important;
	color:#fff !important;
}

div.timesheet_links {
	padding-top:5px;
	padding-right:30px;
	padding-bottom:10px;
float:right;
	}
div.timesheet_links a{
	padding-left:15px;
float:right;
	}
	
	td.today {background-color:rgb(122, 180, 255) !important;}
	
	b.plan {
		font-weight:normal !important;
	}
</style>
<?php

error_reporting(E_ERROR);
ini_set('display_errors', true);
setlocale(LC_TIME, 'ru_RU.UTF-8');

include_once($_SERVER['DOCUMENT_ROOT'] . '/db_mysql_pdo.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/project/timesheet/functions.php');

if (isset($_GET['month'])) {
	list($month, $year) = explode('.', $_GET['month']);
} else {
	$month = date('m');
	$year = date('Y');
}

$days_in_month = date('t', strtotime("$year-$month-01"));

$days = array();

$Users = array();

$stmt = $pdo->query("SELECT r.`ID` as `user_id`, `r`.`NAME` as `user_name`,
							`o`.`ID` as `DepartmentID`, `DATE_NMO`, `DATE_LMO`
								FROM `okb_db_resurs` `r`
								LEFT JOIN `okb_db_shtat` `s` ON `s`.`ID_resurs` = `r`.`ID`	
								LEFT JOIN `okb_db_otdel` `o` ON `o`.`ID` = `s`.`ID_otdel`
								WHERE `r`.`TID` = 0 AND `r`.`ID` != 0
								ORDER BY `s`.`BOSS` DESC, `r`.`NAME` ASC");

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$Users[$row['DepartmentID']][$row['user_id']]['user_name'] = $row['user_name'];
	$Users[$row['DepartmentID']][$row['user_id']]['DATE_NMO'] = $row['DATE_NMO'];
	$Users[$row['DepartmentID']][$row['user_id']]['DATE_LMO'] = $row['DATE_LMO'];
}

$Departments = array();

$stmt = $pdo->query("SELECT o.`ID` as `DepartmentID`, o.`PID` as `DepartmentPID`, o.`NAME` as `DepartmentName`
						FROM `okb_db_otdel` `o`
						ORDER BY `o`.`ID` ASC");

$i = 0;
						
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if (count($Users[$row['DepartmentID']]) > 0) {
		$Departments[$i]['DepartmentID'] = $row['DepartmentID'];
		$Departments[$i]['DepartmentPID'] = $row['DepartmentPID'];
		$Departments[$i]['DepartmentName'] = $row['DepartmentName'];
		
		++$i;
	}
}

$DepartmentsTree = buildTree($Departments);

$stmt = $pdo->query("SELECT t.`ID`,t.`DATE`,t.`SMEN`,t.`TID`,t.`ID_resurs`,
							t.`NFACT`,t.`FACT`,t.`SPEC`,t.`PLAN`,
							t.`OPOZD`,t.`NOTTAB`,
							t.`ETIME`,t.`EUSER`,
							t.`doc_issued`
								FROM `okb_db_tabel` `t`
								WHERE (t.`DATE` BETWEEN " . $year . $month . "00 AND " . $year . $month . "31)");

echo '<div class="timesheet_container" style="width:1325px">'
	,'<select style="float:left" id="select_department">';

foreach ($DepartmentsTree as $DepartmentData) {
	showDepartmentSelect($DepartmentData);
}

echo '
</select>
<div class="month_selector">
<a class="prev_next_month" href="' . $pageurl . '&month=' . sprintf('%02d', $month - 1) . '.' . $year . '">' . iconv('utf-8', 'cp1251', strftime('%B', mktime(0, 0, 0, $month - 1, 10))) . '</a>
<span>' . iconv('utf-8', 'cp1251', strftime('%B ', mktime(0, 0, 0, $month, 10))) . $year . '</span>
<a class="prev_next_month" href="' . $pageurl . '&month=' . sprintf('%02d', $month + 1) . '.' . $year . '">' . iconv('utf-8', 'cp1251', strftime('%B', mktime(0, 0, 0, $month + 1, 10))) . '</a>
</div>
<div class="timesheet_links">
Табель факт (печать своей службы):
<a href="' . $pageurl . '&action=first_half">первая половна мес.</a>
<a href="' . $pageurl . '&action=second_half">вторая половна мес.</a>
<a href="' . $pageurl . '&action=print">Табель факт (печать)</a>
</div>
<table class="timesheet" cellpadding="0" cellspacing="0" style="padding: 0px;">
<thead>
<tr  >
<th>Ресурс</th><th>М<br/>О</th>';

for ($i = 1; $i <= $days_in_month; ++$i) {
	$day_text = strftime('%a', strtotime("$year-$month-00 +$i days"));
	$day_id = strftime('%w', strtotime("$year-$month-00 +$i days"));

	if ($day_id == 0 /* Суббота */ || $day_id == 6 /* Воскресенье */) {
		$days["$year$month" . sprintf('%02d', $i)]['is_holiday'] = true;
	} else {
		$days["$year$month" . sprintf('%02d', $i)]['is_holiday'] = false;
	}
 	
	echo '<th data-day="' . $year . $month . sprintf('%02d', $i) . '"' . ($days["$year$month" . sprintf('%02d', $i)]['is_holiday'] ? ' class="holiday"' : '') . '>' . $i . '<br/><br/>' . iconv('utf-8', 'cp1251', $day_text) . '</th>';
}	

echo '<th>План<br/>Факт</th>'
	,'</tr>'
	,'</thead>'
	,'<tbody>';

$timesheet = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$timesheet[$row['ID_resurs']][$row['DATE']]['FACT'] = $row['FACT'];
	$timesheet[$row['ID_resurs']][$row['DATE']]['PLAN'] = $row['PLAN'];
	$timesheet[$row['ID_resurs']][$row['DATE']]['ID'] = $row['ID'];
	$timesheet[$row['ID_resurs']][$row['DATE']]['SMEN'] = $row['SMEN'];
	$timesheet[$row['ID_resurs']][$row['DATE']]['TID'] = $row['TID'];
	$timesheet[$row['ID_resurs']][$row['DATE']]['doc_issued'] = $row['doc_issued'];
	$timesheet[$row['ID_resurs']]['TOTAL_FACT'] += $row['FACT'];
	$timesheet[$row['ID_resurs']]['TOTAL_PLAN'] += $row['PLAN'];
}

$today = date('Ymd');

/*
echo '<pre>';
print_r($DepartmentsTree);
 */
function showDepartment($DepartmentData, $has_childrens, $is_children)
{
	global $Users, $timesheet, $days, $days_in_month, $today; 
	
	echo '<tr data-department-id="' . $DepartmentData['DepartmentID'] . '" data-department-pid="' . $DepartmentData['DepartmentPID'] . '">'
		,'<td colspan="' . ($days_in_month + 3) . '">'
		,'<a style="margin-left:' . $DepartmentData['DepartmentLevel'] * 2.75 . 'em" href="#" class="expand_collapse"><img src="/uses/expand.png"/></a> <b>' . $DepartmentData['DepartmentName'] . '</b>'
		,'<a href="' . $pageurl . '&department_plan=' . $DepartmentData['DepartmentID'] . '">План</a>'
		,'</td>'
		,'</tr>';

	foreach ($Users[$DepartmentData['DepartmentID']] as $user_id => $user_data) {
		echo '<tr data-user-id="' . $user_id . '">'
			,'<td>' . $user_data['user_name'] . '</td>'
			,'<td class="' . getMedicalExaminationClass($user_data['DATE_LMO'], $user_data['DATE_NMO']) . '"></td>';
			
		foreach ($days as $day => $day_data) {	
			$timesheet_user_data = $timesheet[$user_id];
					
			$day_text = '';
			
			$day_status = getDayStatus($timesheet_user_data[$day]);
					
			if ($timesheet_user_data[$day]['TID'] > 0) {
				$day_text = '<b>' . $day_status['text'] . '</b>';
			}
				 
			if ($day <= $today) {
				if ($timesheet_user_data[$day]['FACT'] > 0) {
					if ($timesheet_user_data[$day]['TID'] > 0) {
						$day_text .= '<br/><b>' . $timesheet_user_data[$day]['FACT'] . '</b>/' . $timesheet_user_data[$day]['SMEN'];
					} else {
						$day_text .= '<b>' . $timesheet_user_data[$day]['FACT'] . '</b><br/>' . $timesheet_user_data[$day]['SMEN'];
					}
				} else {
					if ($timesheet_user_data[$day]['TID'] == 0) {
						$day_text .= '<b>' . $timesheet_user_data[$day]['FACT'] . '</b><br/>' . $timesheet_user_data[$day]['SMEN'];
					}
				}
			} else {
				if ($timesheet_user_data[$day]['TID'] == 0) {
					$day_text .= '<b class="plan">' . $timesheet_user_data[$day]['PLAN'] . '</b><br/>' . $timesheet_user_data[$day]['SMEN'];
				}
			}
				
			echo '<td data-date="' . $day . '" class="' . ($today == $day ? 'today ' : '') . $day_status['class'] . ($day_data['is_holiday'] || $timesheet_user_data[$day]['TID'] == 7 ? ' holiday' : '') . '">'
			. $day_text . '</td>';
		}

			echo '<td>' . $timesheet_user_data['TOTAL_PLAN'] . '<br/><b>' . $timesheet_user_data['TOTAL_FACT'] . '</b></td></tr>';
			
			flush();
			ob_flush();
		}
		
	if (isset($DepartmentData['children'])) {
		foreach ($DepartmentData['children'] as $DepartmentChildrenData) {
			showDepartment($DepartmentChildrenData, isset($DepartmentChildrenData['children']), true);
		}
	}
}

function showDepartmentSelect($DepartmentData, $has_childrens, $is_children)
{
	echo '<option value="' . $DepartmentData['DepartmentID'] . '">';

	for ($i = 1; $i <= $DepartmentData['DepartmentLevel']; ++$i) {
		echo '&nbsp;&nbsp;';
	}
	
	echo $DepartmentData['DepartmentName'] . '</option>';

	if (isset($DepartmentData['children'])) {
		foreach ($DepartmentData['children'] as $DepartmentChildrenData) {
			showDepartmentSelect($DepartmentChildrenData, isset($DepartmentChildrenData['children']), true);
		}
	}
}


foreach ($DepartmentsTree as $DepartmentData) {
	showDepartment($DepartmentData);
}

echo '</tbody></table>';

?>
</div>
<style>
#form_day_data #tid_selector button {
	border:1px solid #000;
	background-color:#fff;
  
	padding:3px;
	width:30px;
	height:24px;
	cursor:pointer;
	
}

#form_day_data {
	border:1px solid #fff;
	padding:7px;
	display:none;margin-left:153px;
}

#form_day_data #tid_selector button.selected {
		background-color:#C9DECB;

}
#form_day_data #tid_selector {
	display:inline;
}
#form_day_data #apply {
	margin-left:8px;
	border:1px solid #000;
}

td.highlighted {
	background-color:#FFBDBD !important;
}
</style>
<div id='form_day_data'>
	<span style="font-weight:bold;padding-right:10px;">Выбранным:</span>
	<input type="text" name="work_hours" style="text-align:center" value="" placeholder="Часов" size="2"/>
	<select name="shift">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
	</select>
	<div id="tid_selector">
<?php

$stmt = $pdo->query("SELECT `day_type_id`,`day_type_description`,`day_type_short`
						FROM `okb_db_tabel_day_type`
						ORDER BY `day_type_id` ASC");

$i = 0;
						
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo '<button title="' . $row['day_type_description'] . '" data-tid="' . $row['day_type_id'] . '">' . $row['day_type_short'] . '</button>';
}

?> 
	</div>
	<button id="apply">Применить</button>
</div>
 
<script type="text/javascript">

function checkDaySelected (element)
{
	var td = element.closest("tr").prevAll("tr[data-department-id]").first().find("td").first();

	$("#form_day_data").detach().appendTo(td).css("display", "inline");

	if (element.hasClass("selected")) {
		element.removeClass("selected");
	 
		$("table.timesheet tbody td[data-date].selected").first().addClass("selected");
		td.append($("form_day_data"));
	} else {
		element.addClass("selected");
	}

	var selected = $("table.timesheet tbody td[data-date].selected");

	if (selected.length == 1) {
		$("#form_day_data span").hide();
			
		$.post("/project/timesheet/timesheet.ajax.php?action=getDayData",
			{
				'user_id' : selected.closest("tr").data("user-id"),
				'date' : selected.data("date")
			},
			function(answer) {  
				$("#form_day_data #tid_selector button.selected").removeClass("selected");

				$("#form_day_data input[name=work_hours]").val(answer.FACT);
				$("#form_day_data select[name=shift]").val(answer.SMEN);
				
				if (answer.TID > 0) {
					$("#form_day_data button[data-tid=" + answer.TID + "]").addClass("selected");
				} else {
					$("#form_day_data button[data-tid]").removeClass("selected");
				}
			}, 'json');
	} else if (selected.length > 1) {
		$("#form_day_data input[name=work_hours]").val("");
		$("#form_day_data select[name=shift]").val("1");
		$("#form_day_data button").removeClass("selected")
		$("#form_day_data span").show();
	} else {
		$("#form_day_data").css("display", "none");
	}
}

$("table.timesheet tbody td[data-date]").click(function () {
	checkDaySelected($(this));
});

$("table.timesheet tbody tr[data-department-id] a.expand_collapse").click(function () {
	var tr = $(this).closest("tr"), expanded = [];

	if (tr.data("status") != "expanded") {
		DepartmentShow(tr);
	} else {
		DepartmentHide(tr);
	} 
	
	$("table.timesheet tbody tr[data-department-id]").each(function () {
		if ($(this).data("status") == "collapsed") {
			expanded.push($(this).data("department-id"));
		}
	})

	localStorage.setItem("expanded", JSON.stringify(expanded));
});


function DepartmentHide(element)
{
	element.closest("tr").nextUntil("tr[data-department-id]").hide();
	element.data("status", "collapsed");
	element.find("a.expand_collapse img").attr("src", "/uses/collapse.png");
	
	DepartmentHideRecursive(element);
}

function DepartmentHideRecursive(element) {
	var children_tr = $("table.timesheet tbody tr[data-department-pid=" + element.data("department-id") + "]");
	
	if (children_tr.length) {
		children_tr.nextUntil("tr[data-department-id]").hide();
		//children_tr.hide();
		children_tr.data("status", "collapsed"); 
		children_tr.find("a.expand_collapse img").attr("src", "/uses/collapse.png"); 

		DepartmentHideRecursive(children_tr);
	}
}

function DepartmentShowRecursive (element) {
	var children_tr = $("table.timesheet tbody tr[data-department-pid=" + element.data("department-id") + "]");
	
	if (children_tr.length) {
		children_tr.nextUntil("tr[data-department-id]").show();
		//children_tr.show();
		children_tr.data("status", "expanded");
		children_tr.find("a.expand_collapse img").attr("src", "/uses/expand.png"); 

		DepartmentShowRecursive(children_tr);
	}
}

function DepartmentShow(element)
{
	element.closest("tr").nextUntil("tr[data-department-id]").show();
	element.data("status", "expanded");
	element.find("a.expand_collapse img").attr("src", "/uses/expand.png");
	
	DepartmentShowRecursive(element);
}

$(function () {
	$("table.timesheet tbody tr[data-department-id]").data("status", "expanded");

	var l = localStorage.getItem("expanded"), expanded;
	
	if (l) {
		expanded = JSON.parse(l);
		console.log(expanded);
		for (var i = 0; i < expanded.length; ++i) {
			DepartmentHide($("table.timesheet tbody tr[data-department-id=" + expanded[i] + "]"));
		}
	}

	$(document).on("click", "#form_day_data #apply", function (e) {
		e.preventDefault();
		
		var selected = $("table.timesheet tbody td[data-date].selected");
		
		var timesheet = [];
		
		selected.each(function () {
			timesheet.push({
				user_id : $(this).closest("tr[data-user-id]").data("user-id"),
				date : $(this).data("date")
			});
		});
		
		$.ajax({
			type: 'POST',
			url: "/project/timesheet/timesheet.ajax.php?action=setDayData",
			data: { 'json': JSON.stringify(timesheet),
					'work_hours' : $("#form_day_data input[name=work_hours]").val(),
					'tid' : $("#form_day_data #tid_selector button.selected").data("tid"),
					'shift' : $("#form_day_data select[name=shift]").val()
					},
			success: function(answer) {
				console.log(answer);
				$("table.timesheet tbody td[data-date].selected").removeClass("selected").addClass('highlighted');
	
				window.setTimeout(function() {
					$("table.timesheet tbody td[data-date].highlighted").removeClass('highlighted');
				}, 2000);
				
				$("#form_day_data").hide();
			}
		});
	});
 
	$(document).on("click", "#form_day_data #tid_selector button", function (e) {
		e.preventDefault(); 
		
		$("#form_day_data #tid_selector button.selected").removeClass("selected");
		
		$(this).addClass("selected");
	});

	$(document).on("change", "#select_department", function () {
		$("tr[data-department-id=" + $(this).val() + "]").get(0).scrollIntoView(true);
	});
	
	$(document).on("click", "table.timesheet thead th", function () {
		checkDaySelected ($("table.timesheet tbody td[data-date=" + $(this).data("day") + "]"));
	});
})

</script>