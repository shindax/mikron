<?php
// Говнокод 

require_once($_SERVER['DOCUMENT_ROOT'] . '/project/request_events/functions.php');

echo '<style type="text/css">
@import url(/project/request_events/styles.css);
</style><h2>Заявки — Уведомления</h2>
<div class="links"><a href="' . $pageurl . '&me">Мне</a> <a href="' . $pageurl . '&from_me">От меня</a> <!-- <a href="' . $pageurl . '">Все</a> --></div>
<br/><br/>
	<table class="rdtbl tbl" id="request_table">
		<thead>
		<tr class="first">
			<td class="nbg" style="width:25px;"></td>
			<td style="width:70px;" style="text-align:center;">Заявка</td>
			<td style="width:400px;">Наименование</td>
			<td style="width:100px;">Дата</td>
			<td style="width:110px;">От</td>
			' . (!isset($_GET['me']) ? '<td style="width:110px;">Кому</td>' : '') . '
			<td style="width:120px;">Действие</td>
			<td style="width:100px;">Статус</td>
			<td style="width:20px;"></td>
		</tr>
		</thead>
		<tbody>';

	if (isset($_GET['me'])) {
		$where = 'WHERE `request_user_id_to` = ' . $user['ID'];
	} else if (isset($_GET['from_me'])) {
		$where = 'WHERE `request_user_id_from` = ' . $user['ID'];
	} else {
		$where = 'WHERE `request_user_id_to` = ' . $user['ID'];
	}

	$result = dbquery("SELECT *,`okb_users`.`FIO` as `user_name`, t.`FIO` as `user_name_to`,UNIX_TIMESTAMP(`request_datetime`) as `datetime` FROM `okb_db_request_events`
						LEFT JOIN `okb_users` ON `okb_users`.`ID` = `okb_db_request_events`.`request_user_id_from`
						LEFT JOIN `okb_users` t ON t.`ID` = `okb_db_request_events`.`request_user_id_to`
						" . $where . "
						ORDER BY `request_id` DESC
						LIMIT 100");

	$i = 0;

	while ($row = mysql_fetch_assoc($result)) {
		$request_name = '';
		
		switch ($row['request_type'])
		{
			case 'tmc':
				$request_name = '<a href="/index.php?do=show&formid=87">ТМЦ</a>';
				$request_title = '<a href="/index.php?do=show&formid=87&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `TXT` FROM `okb_db_tmc_req` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 87;
				break;
			case 'it':
				$request_name = '<a href="/index.php?do=show&formid=82">Отдел ИТ</a>';
				$request_title = '<a href="/index.php?do=show&formid=82&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `QWEST` FROM `okb_db_it_req` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 82;
				break;
			case 'ogi':
				$request_name = '<a href="/index.php?do=show&formid=86">СГИ</a>';
				$request_title = '<a href="/index.php?do=show&formid=86&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `QWEST` FROM `okb_db_ogi_req` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 86;
				break;
			case 'koop':
				$request_name = '<a href="/index.php?do=show&formid=89">Кооперация</a>';
				$request_title = '<a href="/index.php?do=show&formid=89&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `APPLICATION` FROM `okb_db_koop_req` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 89;
				break;
			case 'zak':
				$request_name = '<a href="/index.php?do=show&formid=39">Заказы</a>';
				$request_title = '<a href="/index.php?do=show&formid=39&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `TXT` FROM `okb_db_zak_req` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 39;
				break;
			case 'logistic':
				$request_name = '<a href="/index.php?do=show&formid=123">Логистика</a>';
				$request_title = '<a href="/index.php?do=show&formid=123&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `APPLICATION` FROM `okb_db_logistic_app` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 123;
				break;
			case 'hr':
				$request_name = '<a href="/index.php?do=show&formid=237">Отдел кадров</a>';
				$request_title = '<a href="/index.php?do=show&formid=237&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . mysql_result(dbquery("SELECT `POSITION` FROM `okb_db_hr_req` WHERE `ID` = " . $row['request_pid']), 0) . '</a>';
				$formid = 237;
				break; 
			case 'zakreq':
				$request_name = '<a href="/index.php?do=show&formid=88">Заявка на заказ</a>';
				$request_title = '<a href="/index.php?do=show&formid=88&id=' . $row['request_pid'] .'"><img src="/uses/view.gif"/>' . $row['request_text'];
				$formid = 88;
				break;
		}

		$request_event = '';
		
			switch ($row['request_event'])
			{
				case 'edit':
					$request_event = 'Редактирование';
					break;
				case 'ok':
					if ($row['request_text'] == 1) {
						$request_event = '<span style="color:green">Согласовано</span>';
					} else {
						$request_event = 'Отмена согласования';
					}
					break;
				case 'done':
					if ($row['request_text'] == 1) {
						$request_event = '<span style="color:green;font-weight:700">Выполнено</span>';
					} else {
						$request_event = 'Отмена выполнения';
					}
					break;
				case 'comment':
					$request_event = 'Комментарий';
					break;
				case 'title':
					$request_event = 'Заголовок';
					break;
				case 'restart':
					$request_event = '<span style="color:red">Перезапуск</span>';
					break;
			}
		
		$have_comment = ($row['request_text'] != '' && $row['request_event'] != 'ok' && $row['request_event'] != 'done');

		if ($row['request_user_id_to'] == $user['ID']) {
			if ($row['request_status'] == 1) {
				$bg_color = 'background-color: rgb(102, 170, 255)';
				$event = 'Прочитано';
			} else {
				$bg_color = 'background-color: rgb(255, 116, 116)';
				$event = 'Не прочитано';
			}
			
		//	$request_event = '';
		} else {
			$bg_color = '';
		}
		
			if ($row['request_status'] == 1) {
				$event = 'Прочитано';
			} else {
				$event = 'Не прочитано';
			}
					
			if ($row['request_user_id_from'] == $row['request_user_id_to']) {
				$bg_color = '';
			}
			
			
			
			if ($row['request_user_id_to'] == $row['request_user_id_from']) {
				$event = '';
			}

		
			echo '<tr' . ($i % 2 == 0 ? ' class="even"' : '') . ' data-id="' . $row['request_id'] . '" data-pid="' . $row['request_pid'] . '" data-type="' . $row['request_type'] . '" data-user-id-from="' . $row['request_user_id_from'] . '" data-formid="' . $formid . '" data-type="' . $row['request_type'] . '">'
			,'<td class="nbg"' . ($have_comment ? ' style="border-bottom:0"' : '') . '></td>'
			,'<td class="Field" style="text-align:center;">' . $request_name . '</td>'
			,'<td class="Field" style="width:420px;">' . $request_title . '</td>'
			,'<td class="Field" style="text-align:center;">' . showDate($row['datetime']) . '</td>'
			,'<td class="Field" style="text-align:center;">' . $row['user_name'] . '</td>'
			. (!isset($_GET['me']) ? '<td class="Field" style="text-align:center;">' . ($row['request_user_id_from'] != $row['request_user_id_to'] ? $row['user_name_to'] : '') . '</td>' : '') .
			'<td class="Field" style="text-align:center;">' . $request_event . '</td>'
			,'<td class="Field" ' . ($row['request_user_id_to'] == $user['ID'] && $row['request_user_id_to'] != 0 ? 'id="status"' : '') .' style="' . ($row['request_user_id_to'] == $user['ID'] && $row['request_user_id_from'] != $row['request_user_id_to'] && $row['request_user_id_to'] != 0 ? 'cursor:pointer;' : '') . ';text-align:center;vertical-align:middle;' . $bg_color . '">' . ($row['request_user_id_to'] != 0 ? $event : '') . '</td>'
			,'<td class="Field">' . ($row['request_event'] == 'done' && $row['request_text'] == 1 && $row['request_user_id_to'] == $user['ID'] ? '<img style="cursor:pointer" title="Перезапустить" src="/style/refresh.png"/>' : '') . '</td>'
			,'</tr>';
		
		if ($have_comment && $row['request_event'] != 'title') {
			echo '<tr' . ($i % 2 == 0 ? ' class="even"' : '') . '' . $row['request_id'] . '><td class="nbg" style="border-top:0"></td><td class="Field" ' . ($have_comment ? ' style="border-top:0"' : '') . ' colspan="10">>>> ' . htmlspecialchars($row['request_text']) . '<br/><br/><br/></td></tr>';
		}

		++$i;
	}

?>

	</tbody>
</table>
<script type="text/javascript">
$(document).on("click", "#request_table #status", function () {
	var td = $(this);
	var status = (td.text() == "Прочитано" ? 0 : 1)

	$.post("/project/request_events/watcher.php?mode=change_status", { request_id : $(this).closest("tr").data("id"), "status" : status }, function () {
		if (status == 1) {
			td.css("background-color", "rgb(102, 170, 255)");
			td.text("Прочитано");
		} else {
			td.css("background-color", "rgb(255, 116, 116)");
			td.text("Не прочитано");
		}
	})
});

$(function () {
	setInterval(function ()
	{
		$.getJSON("/project/request_events/watcher.php?mode=getEventCount&user_id=" + user_id, function (data) {
			if (data.all != 0) {
				$("#request_events_it_menu span").text("(" + data.it + ") ");
				$("#request_events_menu span:nth-child(1)").text("(" + data.all + ") ").parent().css("border", "1px dotted red");
			} else {
				$("#request_events_it_menu span").text("");
				$("#request_events_menu span:nth-child(1)").text("").parent().css("border", "0");
			}
		})
	}, 10000);
});

$(document).on("click", "img[title='Перезапустить']", function () {
	var tr = $(this).closest("tr");

	var formid = tr.data("formid"), pid = tr.data("pid"), user_id_from = tr.data("user-id-from");

	$.post("/project/request_events/watcher.php?mode=restartRequest&pid=" + pid + "&type=" + tr.data("type") + "&user_id_from=" + user_id + "&user_id_to=" + user_id_from, function () {
		window.location.href = "/index.php?do=show&formid=" + formid + "&id=" + pid;
	});
});

</script>














