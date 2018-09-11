<?php

include 'report_plan_functions.php';

setlocale(LC_TIME, 'russian');

function printTree($row, $has_childrens, $is_children)
{
	echo '<tr' . ($is_children ? ' class="hidden"' : '') . ' data-pid="' . $row['rp_pid'] . '" data-id="' . $row['rp_id'] . '" data-expanded="' . ($is_children ? '0' : '0') . '">'
		,'<td class="nbg"><b><a href="javascript:void(0)" class="add_row">+</a></td></td>'
		,'<td>' . ($has_childrens ? '<div class="collapse_wrapper"><img src="/project/report_plan/images/collapse.png" class="collapse"/></div>' : '<div class="dot">•</div>') 
		,'<div class="title">'. htmlspecialchars($row['rp_title']) . '</div></td>'
		,'<td>
		<div style="width:15px;float:left">' . (!$has_childrens ? '<a class="minus" href="javascript:void(0)">-</a>' : '') . '</div>
		<progress value="' . $row['rp_progress'] . '" max="100"></progress>
		<div style="width:15px;float:right">' . (!$has_childrens ? '<a class="plus" href="javascript:void(0)">+</a>' : '') . '</div>
		<div class="progress_count">' . $row['rp_progress'] . '</div>
		</td>'
		,'<td>' . ($row['rp_date_count'] != 0 && $row['rp_date_count'] > 0 ? $row['rp_date_count'] . ' ' . days($row['rp_date_count']) : '') . '</td>'
		,'<td data-type="start"><span>' . ($row['rp_date_start'] != '0' ? strftime('%a %d.%m.%Y', $row['rp_date_start']) : '') . '</span></td>'
		,'<td data-type="end"><span>' . ($row['rp_date_end'] != '0' ? strftime('%a %d.%m.%Y', $row['rp_date_end']) : '') . '</span></td>'
		,'<td><span>' . $row['resource_name'] . '</span></td>'
		,'<td><span>' . htmlspecialchars($row['rp_comment']). '</span></td>'
		,'<td><img src="/uses/del.png"/></td>'
		,'</tr>';

	if ($has_childrens) {
		foreach ($row['children'] as $row_children) {
			printTree($row_children, isset($row_children['children']), true);
		}
	}
}

if (isset($_GET['add_report'])) {
	dbquery("INSERT INTO `okb_db_report_plan` () VALUES ()");
	dbquery("INSERT INTO `okb_db_report_plan_items` (`rp_pid`, `rp_report_id`) VALUES (0, " . mysql_result(dbquery("SELECT MAX(`report_id`) FROM `okb_db_report_plan`"), 0) . ")");
	
	redirect($pageurl);
}

if (isset($_GET['add_report_item'])) {
	dbquery("INSERT INTO `okb_db_report_plan_items` (`rp_pid`, `rp_report_id`) VALUES (0, " . (int) $_GET['report_id'] . ")");
	
	redirect($pageurl . '&report_id=' . $_GET['report_id']);
}

echo '<style type="text/css">
@import url(/project/report_plan/styles.css);
</style>';

if (isset($_GET['report_id'])) {
	$report = mysql_fetch_assoc(dbquery("SELECT `report_title`, `okb_db_otdel`.`NAME` as `report_department_name` FROM `okb_db_report_plan`
											LEFT JOIN `okb_db_otdel` ON `okb_db_otdel`.`ID` = `okb_db_report_plan`.`report_department_id`
											WHERE `report_id` = " . (int) $_GET['report_id']));
											
	echo '<h2>План-отчет — ' . $report['report_department_name'] . ' — ' . $report['report_title'] . '</h2><br/>
	<a href="' . $pageurl . '">Выбрать другой план-отчет</a>
	<table class="rdtbl tbl report_table">
		<thead>
		<tr class="first">
			<td class="nbg"></td>
			<td>Название задачи</td>
			<td>% завершения</td>
			<td>Длительность</td>
			<td>Начало</td>
			<td>Окончание</td>
			<td>Ресурс</td>
			<td>Примечание</td>
			<td></td>
		</tr>
		</thead>
		<tbody>';
		
	$result = dbquery("SELECT `rp_id`, `rp_pid`, `rp_report_id`, `rp_title`, DATEDIFF(`rp_date_end`, `rp_date_start`) + 1 as `rp_date_count`, UNIX_TIMESTAMP(`rp_date_start`) as `rp_date_start`, UNIX_TIMESTAMP(`rp_date_end`) as `rp_date_end`, `rp_comment`, `rp_progress`, `rp_resource_id`, `rp_status`, `okb_db_resurs`.`NAME` as `resource_name`
							FROM `okb_db_report_plan_items`
							LEFT JOIN `okb_db_resurs` ON `okb_db_resurs`.`ID` = `okb_db_report_plan_items`.`rp_resource_id`
							WHERE `rp_report_id` = " . (int) $_GET['report_id'] . "
							ORDER BY `rp_id` ASC");

	$rp_array = array();

	$i = 0;
		
	while ($row = mysql_fetch_assoc($result)) {
		$rp_array[++$i] = $row;
	}

	$rows = buildTree($rp_array);

	foreach ($rows as $row) {
		printTree($row, isset($row['children']), false);
	}
} else {
	echo '<h2>План-отчет</h2><br/>
	<a href="' . $pageurl . '&add_report">Добавить план-отчет</a><br/><br/>
	<table class="rdtbl tbl report" style="width:600px;border:1px solid #000">
		<thead>
		<tr class="first">
			<td>Название отчета</td>
			<td>Отдел</td>
		</tr>
		</thead>
		<tbody>';
		
	$result = dbquery("SELECT `report_id`, `report_title`, `report_department_id`, `okb_db_otdel`.`NAME` as `report_department_name`
							FROM `okb_db_report_plan`
							LEFT JOIN `okb_db_otdel` ON `okb_db_otdel`.`ID` = `okb_db_report_plan`.`report_department_id`
							ORDER BY `okb_db_otdel`.`ID` DESC");
					
	while ($row = mysql_fetch_assoc($result)) {
		echo '<tr data-id="' . $row['report_id'] . '" data-department-id="' . $row['report_department_id'] . '">'
			,'<td class="ntabg"><span><a href="' . $pageurl . '&report_id=' . $row['report_id'] . '">' . $row['report_title']. '</a></span></td>'
			,'<td class="ntabg"><span>' . $row['report_department_name'] . '</span></td>'
			,'</tr>';
	}
}

?>

	</tbody>
</table>
<script type="text/javascript">
// Костыли
function HideChildrens(tr)
{
	tr.each(function () {
		var tr = $("tr[data-pid=" + $(this).data("id") + "]");

		if (tr.length > 0) {
			tr.each(function () {
				removeFromLocalStorage($(this).data("id"));
				HideChildrens($(this));
			});
		}
		removeFromLocalStorage($(this).data("id"));
		$(this).css("display", "none");
	});

	removeFromLocalStorage($(this).data("id"));
}

$(document).on("click", ".collapse", function (e) {
	var tr = $(this).closest("tr"), img = $(this), trs = $("tr[data-pid=" + tr.data("id") + "]");

	trs.each(function () {
		removeFromLocalStorage($(this).data("id"));

		var first_td = $(this).find("td:nth-child(2)"), last_tr = getClosestParent($(this));

		first_td.find("img").attr("src", "/project/report_plan/images/collapse.png");
		
		$("#input_title").remove();
		$("div.title").show();
		
		if (last_tr != null) {			
			if (last_tr.find(".first_level").length) {
				first_td.addClass("second_level");
			} else {
				first_td.addClass("first_level");
			}
			
			if (last_tr.find(".second_level").length) {
				first_td.removeClass("first_level").addClass("third_level");
			}
			
			if (last_tr.find(".third_level").length) {
				first_td.removeClass("second_level").addClass("fourth_level");
				$(this).find("td:nth-child(1) b").remove();
			}
		}
		
		if ($(this).css("display") == "none") {
			$(this).removeClass("hidden").css("display", "");
						
			if (e.originalEvent !== undefined) {
				tr.attr("data-expanded", "1");
		
			}
			
			img.attr("src", "/project/report_plan/images/expand.png");
			addToLocalStorage(tr.data("id"));
		} else {
			$(this).css("display", "none");
								
			if (e.originalEvent !== undefined) {
				tr.attr("data-expanded", "0");
			}
			
			img.attr("src", "/project/report_plan/images/collapse.png");
			removeFromLocalStorage(tr.data("id"));
		}
		
		HideChildrens($("tr[data-pid=" + $(this).data("id") + "]"));			
	});	
}).on("click", ".report_table tbody tr td:nth-child(7)", function (e) {
	e.stopPropagation();
	
	if ($(e.target).is("select,option")) return;
	
	HideInputs($(this));

	$(this).append("<select id='resource_select' size='10'></select").find("span").hide();
	
	$("#resource_select").load("/project/report_plan/report_plan_ajax.php?mode=get_resources&resource_name=" + encodeURIComponent($(this).find("span").text())).focus();
}).on("click", ".report tbody tr td:nth-child(2)", function (e) {
	e.stopPropagation();
	
	if ($(e.target).is("select,option")) return;
	
	HideInputs($(this));

	$(this).append("<select id='department_select' size='10'></select").find("span").hide();
	
	$("#department_select").load("/project/report_plan/report_plan_ajax.php?mode=get_departments&report_department_id=" + $(this).closest("tr").data("department-id")).focus();
}).on("click", ".report tbody tr td:nth-child(1)", function (e) {
	e.stopPropagation();
	
	if ($(e.target).is("input,a")) return;
	
	HideInputs($(this));

	var span = $(this).find("a");

	span.hide();
	
	$(this).append("<input type='text' id='input_report_title' name='report_title' value='" + span.text() + "'/>");
	
	$("#input_report_title").focus();
}).on("click", ".report_table tbody tr td:nth-child(6), .report_table tbody tr td:nth-child(5)", function (e) {
	e.stopPropagation();
	
	if ($(e.target).is("input")) return;
	
	HideInputs($(this));

	var span = $(this).find("span"), td = $(this).closest("td"), tr = $(this).closest("tr"), pid = tr.data("pid"), text = span.text(), date;

	span.hide();

	if (text != "") {
		var date_parts = text.split(" ")[1].split(".");

		date = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
	}

	var min = "", max = "";
	
	if (td.data("type") == "end" && tr.find("td[data-type=start]").find("span").text() != "") {
		var date_parts = tr.find("td[data-type=start]").find("span").text().split(" ")[1].split(".");

		min = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
	}
	
	var last_tr = getClosestParent(tr);

	if (last_tr != null) {
		if (last_tr.find("td[data-type=start] span").text() == "") {
			last_tr = getClosestParent(last_tr);

			if (last_tr.find("td[data-type=start] span").text() == "") {
				last_tr = getClosestParent(last_tr);

				if (last_tr.find("td[data-type=start] span").text() == "") {
					last_tr = getClosestParent(last_tr);
				}
			}
		}
		
		if (td.data("type") == "start" && last_tr.find("td[data-type=start]").find("span").text() != "") {
			var date_parts = last_tr.find("td[data-type=start]").find("span").text().split(" ")[1].split(".");

			min = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
			
			date = min;
			
			if (last_tr.find("td[data-type=end]").find("span").text() != "") {
				var date_parts = last_tr.find("td[data-type=end]").find("span").text().split(" ")[1].split(".");

				max = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
			}
		}
		
		if (td.data("type") == "end" && last_tr.find("td[data-type=end]").find("span").text() != "") {
			var date_parts = last_tr.find("td[data-type=end]").find("span").text().split(" ")[1].split(".");

			max = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
		}
	}

	$(this).append("<input type='date' id='input_date' name='" + (td.data("type") == "start" ? "rp_date_start" : "rp_date_end") + "' value='" + (date != null ? date : "") + "' min='" + min + "' max='" + max + "'/>");

	$("#input_date").focus();
}).on("click", ".report_table tbody tr td:nth-child(2)", function (e) {
	e.stopPropagation();
	
	if ($(e.target).is("img,input")) return;
	
	HideInputs($(this));

	var span = $(this).find("div.title");

	span.hide();
	
	$(this).append("<input type='text' id='input_title' name='rp_title' value='" + span.text() + "'/>");
	
	$("#input_title").focus();
}).on("click", ".report_table tbody tr td:nth-child(9) img", function (e) {
	var tr = $(this).closest("tr"), id = tr.data("id");
	
	e.stopPropagation();

	HideInputs($(this));

	var closest_tr = getClosestParent(tr);
	
	if (confirm("Вы действительно хотите удалить данную задачу?")) {
		$.post("/project/report_plan/report_plan_ajax.php?mode=remove_row", { rp_id : id }, function () {
			tr.remove();

			if (!$(".report_table tbody tr[data-pid=" + closest_tr.data("id") + "]").length) {
				closest_tr.find("div[style='width:15px;float:left']").empty().append('<a class="minus" href="javascript:void(0)">-</a>');
				closest_tr.find("div[style='width:15px;float:right']").empty().append('<a class="plus" href="javascript:void(0)">+</a>');
				closest_tr.find(".collapse_wrapper").remove();
				closest_tr.find("td:nth-child(2)").prepend('<div class="dot">•</div>');
			}

			removeFromLocalStorage(id);
			
			RecalculateProgress();
		});
	}
}).on("click", ".report_table tbody tr td:nth-child(8)", function (e) {
	e.stopPropagation();
	
	if ($(e.target).is("input")) return;
	
	HideInputs($(this));

	var span = $(this).find("span");

	span.hide();
	
	$(this).append("<input type='text' id='input_comment' name='rp_comment' value='" + span.text() + "'/>");
	
	$("#input_comment").focus();
}).on("click", "body, .report_table, .report_table tbody tr", function() {
	HideInputs($(this));
	
	$(".report_table tbody tr").removeClass("item_selected");
}).on("click", ".report_table tr td a.minus, .report_table tr td a.plus", function (e) {
	var tr = $(this).closest("tr"), progress = tr.find("progress"), progress_value = progress.val();
	
	if ($(e.target).attr("class") == "plus") {
		if (progress_value != 100) progress_value += 5;
	} else {
		if (progress_value != 0) progress_value -= 5;
	}

	$.post("/project/report_plan/report_plan_ajax.php?mode=update_progress", { rp_id : tr.data("id"), rp_progress : progress_value }, function () {
		progress.val(progress_value);
		tr.find(".progress_count").text(progress_value);;
		
		RecalculateProgress();	
	});	
}).on("click", ".report_table .add_row", function () {
	var tr = $(this).closest("tr"), id = tr.data("id"), next_tr = tr.next(), last_tr, new_tr;
	
	while(true) {
		if (next_tr.data("pid") == id) last_tr = next_tr;

		if (next_tr.next().length > 0) {
			next_tr = next_tr.next();
		} else {
			break;
		}
	}

	if (last_tr == null) last_tr = tr;
	
	$.post("/project/report_plan/report_plan_ajax.php?mode=add_row", { rp_report_id : <?php echo isset($_GET['report_id']) ? $_GET['report_id'] : 0 ?>, rp_pid : id }, function (data) {
		new_tr = last_tr.clone().insertAfter(last_tr).css("display", "none").fadeIn("fast");

		if (tr.find("td:nth-child(2)").hasClass("first_level")) {
			new_tr.find("td:nth-child(2)").addClass("second_level");
		} else {
			new_tr.find("td:nth-child(2)").addClass("first_level");
		}
		
		if (tr.find("td:nth-child(2)").hasClass("second_level")) {
			new_tr.find("td:nth-child(2)").removeClass("first_level").addClass("third_level");
		}
		
		if (tr.find("td:nth-child(2)").hasClass("third_level")) {
			new_tr.find("td:nth-child(2)").removeClass("second_level").addClass("fourth_level");
			new_tr.find("td:nth-child(1) b").remove();
		}
		
		new_tr.find(".collapse").remove();
		new_tr.find("div.title").empty();
		new_tr.find("progress").val(0);
		new_tr.find(".progress_count").html("0");
		new_tr.find("td:nth-child(2)").empty().append("<div class='dot'>•</div><div class='title'></div>");
		new_tr.find("td:nth-child(2)").trigger("click");
		
		tr.find("td:nth-child(2) div.dot").remove();
		tr.find(".minus, .plus").css("visibility", "hidden");
		
		if (!tr.find("td:nth-child(2) .collapse_wrapper").length) {
			tr.find("td:nth-child(2)").prepend('<div class="collapse_wrapper"><img src="/project/report_plan/images/expand.png" class="collapse"/></div>');
			
			addToLocalStorage(id);
		}
	
		new_tr.attr("data-id", data);
		new_tr.attr("data-pid", id);
		
		RecalculateProgress();
	});
}).on("change", "input[name=rp_date_start], input[name=rp_date_end]", function (e) {
	var tr = $(this).closest("tr"), td = $(this).closest("td"), pid = tr.data("pid"), value = $(this).val();

	var last_tr = getClosestParent(tr);

	if (last_tr != null) {
		if (td.data("type") == "start" && last_tr.find("td[data-type=start]").find("span").text() != "") {
			var text = last_tr.find("td[data-type=start]").find("span").text(), date_parts = text.split(" ")[1].split(".");

			min = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
			
			if (value < min && value != "") {
				alert("Дата начала задачи не может быть меньше родительской (" + text + ")");
				
				$(this).val(min);
			}
		}
		
		if (td.data("type") == "end" && last_tr.find("td[data-type=end]").find("span").text() != "") {
			var text = last_tr.find("td[data-type=end]").find("span").text(), date_parts = text.split(" ")[1].split(".");

			max = date_parts[2] + "-" + date_parts[1] + "-" + date_parts[0];
			
			if (value > max && value != "") {
				alert("Дата окончания задачи не может быть больше родительской (" + text + ")");
				
				$(this).val(max);
			}
		}
	}
	
	if ($(e.target).closest("td").data("type") == "start") {
		$.post("/project/report_plan/report_plan_ajax.php?mode=update_date_start", { rp_id : tr.data("id"), rp_date_start : $(this).val() }, function (data) {
			UpdateDate(tr, data);
		}, "json");	
	} else {
		$.post("/project/report_plan/report_plan_ajax.php?mode=update_date_end", { rp_id : tr.data("id"), rp_date_end : $(this).val() }, function (data) {
			UpdateDate(tr, data);
		}, "json");	
	}
}).on("keyup blur", "#input_title", function () {
	var tr = $(this).closest("tr"), input = $(this);
	
	$.post("/project/report_plan/report_plan_ajax.php?mode=update_title", { rp_id : tr.data("id"), rp_title : input.val() }, function () {
		input.closest("td").find("div.title").text(input.val());
	});	
}).on("keyup blur", "#input_comment", function () {
	var tr = $(this).closest("tr"), input = $(this);
	
	$.post("/project/report_plan/report_plan_ajax.php?mode=update_comment", { rp_id : tr.data("id"), rp_comment : input.val() }, function () {
		input.closest("td").find("span").text(input.val());
	});	
}).on("keyup blur", "#input_report_title", function () {
	var tr = $(this).closest("tr"), input = $(this);
	
	$.post("/project/report_plan/report_plan_ajax.php?mode=update_report_title", { report_id : tr.data("id"), report_title : input.val() }, function () {
		input.closest("td").find("a").text(input.val());
	});	
}).on("change", "#resource_select", function (e) {
	var tr = $(this).closest("tr"), select = $(this), value = select.val();
	
	if (value != "0") {
		$.post("/project/report_plan/report_plan_ajax.php?mode=update_resource", { rp_id : tr.data("id"), rp_resource_id : value }, function () {
			select.closest("td").find("span").text((value != "" ? select.find("option:selected").text() : ""));

			$("body").trigger("click");
		});
	}
}).on("change", "#department_select", function (e) {
	var tr = $(this).closest("tr"), select = $(this), value = select.val();
	
	if (value != "0") {
		$.post("/project/report_plan/report_plan_ajax.php?mode=update_department", { report_id : tr.data("id"), report_department_id : value }, function () {
			select.closest("td").find("span").text(select.find("option:selected").text());
			tr.data("department-id", value);
			$("body").trigger("click");
		});
	}
});

$(function () {
	RecalculateProgress();
	
	var l = localStorage.getItem("expanded_rows"), expanded_rows;
	
	if (l) {
		expanded_rows = JSON.parse(l);
		
		for (var i = 0; i < expanded_rows.length; ++i) {
			$(".report_table tbody tr[data-id=" + expanded_rows[i] + "] .collapse").trigger("click");
		}
	}
})

function UpdateDate(tr, data)
{
	if (tr == null) return;

	if (data.rp_date_end != "Чт 01.01.1970") tr.find("td:nth-child(4)").text(data.rp_date_count);
	tr.find("td:nth-child(5) span").text(data.rp_date_start);
	if (data.rp_date_end != "Чт 01.01.1970") tr.find("td:nth-child(6) span").text(data.rp_date_end);
}

function HideInputs(element)
{	
	$(".report_table tbody tr").removeClass("item_selected");
	element.closest(".report_table tbody tr").addClass("item_selected");
	$("#resource_select, #input_date, #input_title, #input_comment, #department_select, #input_report_title").remove();
	$(".report_table tbody tr td span, div.title, .report tbody tr td span, .report tbody tr td a").show();
}

function getClosestParent (tr)
{
	var prev_tr = tr.prev(), last_tr, pid = tr.data("pid");
	
	while(true) {
		if (prev_tr.data("id") == pid) last_tr = prev_tr;
			
		if (prev_tr.prev().length > 0) {
			prev_tr = prev_tr.prev();
		} else {
			break;
		}
	}
	
	return last_tr;
}

function RecalculateProgress()
{
	$.getJSON("/project/report_plan/report_plan_ajax.php?mode=row_recalculation&report_id=<?php echo (isset($_GET['report_id']) ? $_GET['report_id'] : '') ?>", function (data) {
		$.each(data, function (index, value) {
			var tr = $(".report_table tbody tr[data-id=" + index + "]");
			
			value = Math.floor(value);
			
			tr.find("progress").val(value);
			tr.find(".progress_count").text(value);
		});
	});	
}

function removeFromLocalStorage(id)
{	
	var expanded_rows = [], l = localStorage.getItem("expanded_rows");

	if (l) {
		expanded_rows = JSON.parse(l);
	
		for (var i = expanded_rows.length - 1; i >= 0; i--) {
			if (expanded_rows[i] === id) expanded_rows.splice(i, 1);
		}
	}

	localStorage.setItem("expanded_rows", JSON.stringify(expanded_rows));
}

function addToLocalStorage(id)
{
	var expanded_rows = [], l = localStorage.getItem("expanded_rows");

	if (l) {
		expanded_rows = JSON.parse(l);
	
		for (var i = expanded_rows.length - 1; i >= 0; i--) {
			if (expanded_rows[i] === id) expanded_rows.splice(i, 1);
		}
	}

	expanded_rows.push(id);
	
	localStorage.setItem("expanded_rows", JSON.stringify(expanded_rows));
}

<?php if (isset($_GET['report_id'])) { ?>
setInterval(function () {
	RecalculateProgress();
}, 3000);
<?php } ?>
</script>
