<style type="text/css">
@import url(/project/sklad/styles.css);
</style>
<?php

include 'sklad_func.php';

if (isset($_GET['addsklad'])) {
	if (!$is_admin) return;
	
	dbquery("INSERT INTO okb_db_sklades (NAME, KOMM, ORD)
				SELECT concat('Склад №', IFNULL(MAX(ORD) + 1, 1)), '', IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades");
	
	redirect($pageurl);
}

if (isset($_GET['additem'])) {
	if (!$is_admin) return;
	
	dbquery("INSERT INTO okb_db_sklades_item (ID_sklad, NAME, YARUS, KOMM, ORD)
				SELECT " . (int) $_GET['ID_sklad'] . ", '', '', '', IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades_item
					WHERE ID_sklad = " . (int) $_GET['ID_sklad']);
	
	redirect($pageurl . '&ID_sklad=' . $_GET['ID_sklad']);
}

if (isset($_POST['edit_item'])) {
	if (!$can_edit_sklad) return;
	
	if ($_POST['floor'] == 'on' && !hasFloor($_POST['ID_item'])) {
		dbquery("INSERT INTO okb_db_sklades_yaruses (ID_sklad_item, ORD)
					VALUES (" . (int) $_POST['ID_item'] . ", 0)");		
	} else {
		if (!isset($_POST['floor'])) {
			$floor_id = mysql_result(dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . (int) $_POST['ID_item'] . " AND ORD = 0"), 0);

			if (mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . $floor_id)) == 0) {
				dbquery("DELETE FROM okb_db_sklades_yaruses WHERE ORD = 0 AND ID_sklad_item = " . (int) $_POST['ID_item']);
				
				UpdateYarusORD($_POST['ID_item']);
			}
		}
	}
	
	dbquery("UPDATE okb_db_sklades_item SET NAME = '" . mysql_real_escape_string($_POST['NAME']) . "',
											YARUS = " . (int) $_POST['YARUS'] . ",
											KOMM = '" . mysql_real_escape_string($_POST['KOMM']) . "'
				WHERE ID = " . (int) $_POST['ID_item']);
	
	if ($_POST['YARUS'] > 0) {
		dbquery("UPDATE okb_db_sklades_yaruses SET ORD = ORD + " . (int) $_POST['YARUS'] . " WHERE ORD != 0 AND ORD > " . $_POST['YARUS_from'] . " AND ID_sklad_item = " . (int) $_POST['ID_item']);

		for ($i = 0; $i < (int) $_POST['YARUS']; ++$i) {
			$new = (hasFloor($_POST['ID_item']) ? ($_POST['YARUS_from'] + $i)  : ($_POST['YARUS_from'] + $i)) + 1;
			
			dbquery("INSERT INTO okb_db_sklades_yaruses (ID_sklad_item, ORD) VALUES (" . (int) $_POST['ID_item'] . ", " . $new . ")");
		}
		
		UpdateYarusORD($_POST['ID_item']);
	}

	redirect($pageurl . '&ID_sklad=' . $_GET['ID_sklad']);
}

if (!isset($_GET['ID_sklad'])) {
	echo '<h2 style="float:left">Выберите склад</h2>'
		. ($is_admin ? '<a class="top_menu" href="' . $pageurl . '&addsklad">Добавить</a>' : '') . '<br style="clear:left"/>';

	$result = dbquery("SELECT ID,NAME,KOMM FROM okb_db_sklades
							ORDER BY ID ASC");
							
	while ($row = mysql_fetch_assoc($result)) {
		echo '<a href="/' . $pageurl . '&ID_sklad=' . $row['ID'] . '">'
				,'<div class="box">'
					,'<h3>' . ($row['NAME']) . '</h3>'
				,'</div>'
			,'</a>';
	}

	
		$result = dbquery("SELECT `KOMM` FROM `okb_db_sklades_detitem`");
		
		$zakaz_array = array();
		
		while ($row = mysql_fetch_assoc($result)) {
			preg_match_all('#[\d]{2}-[\d]{3}#Us', $row['KOMM'], $out);
				
				
			foreach ($out[0] as $zak) {
				$zakaz_array[] = $zak;
			}
		}
		
		
			$zakaz_array = array_unique($zakaz_array);
			arsort($zakaz_array);
			//print_r($zakaz_array);
			
		
		echo '<br/><br/><br/><br/><table class="rdtbl tbl" align=left>
				<thead>
				<tr class="First">
				<td>№</td>
				<td>Наименование</td>
				<td>Количество</td>
				<td>Местоположение</td>
				</tr>
				
				</thead>
				<tbody>
				';
		
		$ids = array();
		
		foreach ($zakaz_array as $zakaz) {
			
			echo '<tr><td class="Field" style="padding:5px;text-align:center;" colspan="4"><b>' . $zakaz . ' — ' . mysql_result(dbquery("SELECT `DSE_NAME` FROM `okb_db_zak` WHERE `NAME` LIKE '%" . $zakaz . "%'"), 0) . '</b></td></tr>';
			
			$result = dbquery("SELECT sd.NAME, sd.COUNT, sd.ID FROM `okb_db_sklades_detitem` `sd`
						LEFT JOIN `okb_db_sklades_yaruses` `sy` ON sy.ID_sklad_item = sd.ID_sklades_yarus
						LEFT JOIN `okb_db_sklades_item` `si` ON si.ID = sy.ID_sklad_item
						WHERE sd.`KOMM` LIKE '%" . $zakaz . "%'
					
						GROUP BY `sd`.ID 	ORDER BY sd.NAME ASC");
						
			$i = 1;
			
			while ($row = mysql_fetch_assoc($result)) {
				
					$position = mysql_fetch_assoc(dbquery("SELECT sd.NAME,sd.KOMM,sy.ORD as Yarus,si.NAME as BoxName,s.ID as SkladName FROM okb_db_sklades_detitem sd
													LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID = sd.ID_sklades_yarus
													LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
													LEFT JOIN okb_db_sklades s ON s.ID = si.ID_sklad
										WHERE sd.ID = " . $row['ID']));
		
					
					$name = ($position['NAME']);
					$oboz = ($position['KOMM']);

					$pos =  'Склад: ' . $position['SkladName'] . ' (' . $position['BoxName'] . ' - ' . ($position['Yarus'] == 0 ? 'Пол' : $position['Yarus']) . ') ';

						
				$ids[] = $row['ID'];
				
				echo '<tr>
				<td class="Field"  style="text-align:center;">' . $i . '</td><td class="Field">' . $row['NAME'] . '</td><td class="Field" style="text-align:center;">' . $row['COUNT']	. "</td><td class='Field'>" . $pos . "</td></tr>";
				
				++$i;
			}
			
			echo '<tr><td class="Field" colspan="4"></td></tr>';

		}
		
		echo '</tbody>
		</table>';
	
			echo '<br/><br/><br/><br/><table class="rdtbl tbl" style="margin-left:40px;" align=left>
				<thead>
				<tr class="First">
				<td>№</td>
				<td>Наименование</td>
				<td>Количество</td>
				<td>Местоположение</td>
				</tr>
				
				</thead>
				<tbody>
				<tr><td class="Field" style="padding:5px;text-align:center;" colspan="4"><b>Без привязки к заказу</b></td></tr>';
			
	
						$result = dbquery("SELECT sd.NAME, sd.COUNT, sd.ID FROM `okb_db_sklades_detitem` `sd`
						LEFT JOIN `okb_db_sklades_yaruses` `sy` ON sy.ID_sklad_item = sd.ID_sklades_yarus
						LEFT JOIN `okb_db_sklades_item` `si` ON si.ID = sy.ID_sklad_item
						WHERE si.ID_sklad != 4
						
						GROUP BY `sd`.ID 	ORDER BY sd.NAME ASC");
						
			$i = 1;
			
			while ($row = mysql_fetch_assoc($result)) {
				if (in_array($row['ID'], $ids))
					continue;
				
					$position = mysql_fetch_assoc(dbquery("SELECT sd.NAME,sd.KOMM,sy.ORD as Yarus,si.NAME as BoxName,s.ID as SkladName FROM okb_db_sklades_detitem sd
													LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID = sd.ID_sklades_yarus
													LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
													LEFT JOIN okb_db_sklades s ON s.ID = si.ID_sklad
										WHERE sd.ID = " . $row['ID']));
		
					
					$name = ($position['NAME']);
					$oboz = ($position['KOMM']);

					
				
					$pos =  'Склад: ' . $position['SkladName'] . ' (' . $position['BoxName'] . ' - ' . ($position['Yarus'] == 0 ? 'Пол' : $position['Yarus']) . ') ';

						
				$ids[] = $row['ID'];
				
				echo '<tr>
				<td class="Field"  style="text-align:center;">' . $i . '</td><td class="Field">' . $row['NAME'] . '</td><td class="Field" style="text-align:center;">' . $row['COUNT']	. "</td><td class='Field'>" . $pos . "</td></tr>";
				
				++$i;
			}
						
	
} else {
	$sklad_name = mysql_result(dbquery("SELECT NAME FROM okb_db_sklades WHERE ID = " . (int) $_GET['ID_sklad']), 0);

	echo '<h2 style="float:left;">' . ($sklad_name) . '</h2>'
		,'<a href="' . $pageurl . '" class="top_menu">Выбрать другой склад</a>'
		,'<a href="/print.php?do=show&formid=224&p0=' . $_GET['ID_sklad'] . '" class="top_menu">Печать</a>'
		. ($is_admin ? '<a href="' . $pageurl . '&additem&ID_sklad=' . $_GET['ID_sklad'] . '" class="top_menu">Добавить ячейку</a>' : '') .
		'<div style="position:relative">
		<input style="float:right;margin-top:20px;width:300px;" type="text" id="search_sklad"/>
		<select style="float:right;margin-top:40px;width:300px;margin-right:-300px;display:none;position:absolute;right:300px;z-index:9999;" size="12" id="search_sklad_autocomplete"></select>
		</div><br style="clear:left"/>';

		
		
	$result = dbquery("SELECT si.NAME,YARUS,KOMM,si.ID,COUNT(sy.ID) as YarusCount FROM okb_db_sklades_item si
							LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID_sklad_item = si.ID
								WHERE si.ID_sklad = " . (int) $_GET['ID_sklad'] . " GROUP BY si.ID ORDER BY si.ORD ASC");

	if (mysql_num_rows($result) > 0) {
		echo '<div id="box">';

		$i = 1;
		
		while ($row = mysql_fetch_assoc($result)) {
			$has_floor = (bool) mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . $row['ID'] . " AND ORD = 0")) > 0;

			echo '<div class="item"' . (hasItemsInBox($row['ID']) ? ' style="background-color:#CEE7F0;"' : '') . ' data-box-id=' . $row['ID'] . '>'
					,'<center>' . ($row['NAME']) . '</center>'
					,($row['YarusCount'] != 0 ? 'Ярусов: <b>' . (/*temp*/$has_floor ? $row['YarusCount'] - 1 : $row['YarusCount']) . ($has_floor ? '<br/>Пол' : '') . '</b>' : '')
					,'<br/>' . (!empty($row['KOMM']) ? '<div class="tooltip">' . ($row['KOMM']) . '</div>' : '')
					,'</div>';

			if ($_GET['ID_sklad'] == 1) {
				if ($i == 13) {
					echo '<div class="spacer"><div class="entrance"></div></div>';
				} else if (!($i % 13)) {
					echo '<br/>';
				}
			}
			
			++$i;
		}

		echo '</div>';
	}
	
	
	if (is_admin) {

	}
}

?>
<script type="text/javascript">
$("#box .item").mouseover(function(e) {
	$(this).find(".tooltip").css("display", "block");;
}).mouseleave(function(e) {
	$(this).find(".tooltip").css("display", "none");;
});

$("body").click(function () {
	$("#search_sklad_autocomplete").css("display", "none");
});

$("#box .item").click(function(e) {
	var current_box_item = $(this);
	
	HidePopup();
	
	$.get("/sklad_ajax_show_popup.php?ID_item=" + $(this).data("box-id"), function(data) {
		UnCheck($(".item"));
		
		current_box_item.addClass("box_selected");
		
		$("body").append(data);

		var popup = $("#popup");
		
		popup.css({ "top" : e.pageY + 20, "left" : e.pageX - 20 }).find("input[type=text]:first").focus();

		// На даем элементы выйти за пределы экрана. Нужно подумать над более лучшей реализацией.
		var off = popup.offset(),t = off.top,l = off.left + 50, h = popup.height(), w = popup.width(), docH = $(window).height(), docW = $(window).width();

		var isVisible = (t > 0 && l > 0 && t + h < docH && l + w < docW);
				
		if (!isVisible) {
			var popup_img = $("#popup img");
			
			if (t + h < docW) {
				popup_img.css("left", (w - 20) + "px");
				popup.css("margin-left", "-" + (w - 30) + "px");
			} else {
				if (!popup.find("hr").length) {
					popup_img.css({ "top" : (h + 50) + "px", "transform" : "scale(-1, -1)" });
					popup.css("margin-top", "-" + (h + 60) + "px");
				} else {
					popup_img.css({ "bottom" : (h + 20) + "px" });
					popup.css("margin-bottom", "-" + (h + 60) + "px");
				}
			}
		}
	});
});

$(document).on("submit", "#popup", function() {
	var yarus = $(this).find("input[name=YARUS]");
	
	if (!yarus.prop("disabled")) {
		var yarus_count = yarus.val();

		if (yarus_count > 50) {
			alert("Слишком много ярусов для одной ячейки.");
			
			return false;
		}
		
		if (yarus_count > 10) {
			return confirm("Вы уверены что хотите ввести столько ярусов для этой ячейки?");
		} 
	}
});

$(document).on("click", "#popup .yarus_select tbody tr", function(e) {
	var current_yarus = $(this);

	if (current_yarus.hasClass("item_selected")) {
		HidePopupYarus();
			
		return;
	}
		
	HidePopupYarus();

	$.get("/sklad_ajax_show_popup_yarus.php?ID_yarus=" + $(this).data("yarus-id"), function(data) {
		$("#popup .yarus_select tbody tr").removeClass("item_selected");

		$("body").append(data);

		current_yarus.addClass("item_selected");

		var popup = $("#popup_yarus");
		
		popup.find("#box_yarus_id").text("Ячейка: " + $(".box_selected center").text() + ". Ярус: " + $("#popup .item_selected").find("td").first().text() + ".");
		
		popup.css("display", "none").fadeIn("fast");
		
		var popup_child = $("#popup"), popup_child_offset = $("#popup").offset();
		
		popup.css({"position" : "absolute", "top" : popup_child_offset.top , "left" : popup_child_offset.left + popup_child.width() + 25, "height" : popup_child.height(), "width" : "600" }).find("input[type=text]:first").focus();
		
		popup.find("table input[type=text],input[type=number],textarea").css("width", "520");

		var off = popup.offset(), l = off.left, w = popup.width(), docW = $(window).width();

		var isVisibleLeft = (docW < (l + w));

		if (!isVisibleLeft) {
			popup.css("margin-right", "-" + (popup_child.width() + w + 50) + "px");
		}
		
		var isVisibleRight = (docW > (l - 20 + w));

		if (!isVisibleRight) {
			popup.css({ "margin-left" : "-" + (popup_child.width() + 300) + "px", "margin-top" : "50px" });
		}
	});
});

$(document).on("click", "#multiselect", function() {
	var multi_select = $(this);
	
	var popup = $(this).closest(".popup");
	
	var checkbox = $(this).closest(".popup").find("#yarus_item_select input[type=checkbox]");
	
	checkbox.each(function() {
		if ($(this).closest("tr").css("display") != "none") {
			$(this).prop("checked", multi_select.prop("checked"));
			
			if (multi_select.prop("checked")) {
				$(this).closest("tr").addClass("item_selected");
			} else {
				$(this).closest("tr").removeClass("item_selected");
			}
		}
	});
	
	if (popup.attr("id") == "popup_yarus") $("#otk_confirm_remove, #otk_confirm").css("display", "");
});

$(document).on("click", "td:has(input[type=checkbox]), td:has(img)", function(e) {
	e.stopPropagation();
});

$(document).on("click", "#popup_yarus #yarus_item_select tr", function(e) {
	if (e.ctrlKey) {
		$(this).find("input[type=checkbox]").trigger("click"); 
		
		return;
	}
	
	var current_yarus_item = $(this);
	
	if (current_yarus_item.hasClass("item_selected") || $("#popup_yarus input[type=checkbox]:checked").length > 1) {
		BackToAdd();
		
		return;
	}
	
	var checkbox = $("#popup_yarus input[type=checkbox]");
	
	checkbox.each(function() {
		$(this).prop("checked", false);
	});
	
	current_yarus_item.find("input[type=checkbox]").prop("checked", true);
	
	$("#popup_yarus #yarus_item_select tr").removeClass("item_selected");

	current_yarus_item.addClass("item_selected");
	
	$("#popup_yarus input[type=submit]").val("Изменить");
	$("#popup_yarus input[name=COUNT]").val(current_yarus_item.find("td:nth-child(4)").text());
	$("#popup_yarus input[name=NAME]").val(current_yarus_item.find("td:nth-child(2)").text()).focus();
	$("#popup_yarus").data("yarus-item-id", current_yarus_item.data("yarus-item-id"));
	$("#popup_yarus textarea[name=KOMM]").val(current_yarus_item.find("td:nth-child(3)").text());
	$("#popup_yarus, #remove, #move, #otk_status").css("display", "block");
	
	var inputs = $("#popup_yarus input[type=submit], #popup_yarus input[name=COUNT], #popup_yarus input[name=NAME], #popup_yarus textarea[name=KOMM]");
	
	if (current_yarus_item.hasClass("otk_confirmed") || $("#popup input[name=NAME]").prop("disabled")) {
		inputs.prop("disabled", true);
		$("#remove, #move").css("display", "none");
	} else {
		inputs.prop("disabled", false);
		$("#remove, #move").css("display", "block");
	}
	
	if (current_yarus_item.hasClass("otk_confirmed")) {
		$("#otk_confirm").css("display", "none");
		$("#otk_confirm_remove").css("display", "");
	} else {
		$("#otk_confirm_remove").css("display", "none");
		$("#otk_confirm").css("display", "");
	}
});

$(document).on("click", "#popup_yarus input[type=submit]", function(e) {
	var yarus_item_name = $("#popup_yarus input[name=NAME]").val(), yarus_item_count = $("#popup_yarus input[name=COUNT]").val();
	
	if (yarus_item_name == "") {
		alert("Поле \"Название\" обязательно для заполнения.");
		
		return false;
	}

	if (yarus_item_count == "" || yarus_item_count == 0) {
		alert("Поле \"Количество\" обязательно для заполнения и не может быть равно нулю.");
		
		return false;
	}
	
	var yarus_id = $("#popup_yarus").data("yarus-id");
	
	$.post("/sklad_ajax_yarus_item_edit.php?mode=add_edit", { ID_yarus_item : $("#popup_yarus").data("yarus-item-id"), ID_yarus : yarus_id, ID_yarus : yarus_id, NAME : yarus_item_name, KOMM : $("#popup_yarus textarea[name=KOMM]").val(), COUNT : yarus_item_count }, function (data) {
		refreshYarusItems(yarus_id, function() {
			var yarus_item_select = $(this).parent();
				
			if ($("#popup_yarus input[type=submit]").val() != "Изменить") {
				yarus_item_select.scrollTop(yarus_item_select.prop("scrollHeight"));
			} else {
				ScrollToEditedItem();
			}

			BackToAdd();
		});

	});
});

$(document).on("click", "#popup_yarus #remove", function(e) {
	var yarus_id = $("#popup_yarus").data("yarus-id");
			
	if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
		if (confirm("Вы действительно хотите удалить выбранные записи?")) {
			var yarus_item = $("#yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
			});
			
			$.post("/sklad_ajax_yarus_item_edit.php?mode=remove", { ID_yarus_items : yarus_items_array, ID_yarus : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите удалить выбранную запись?")) {
			$.post("/sklad_ajax_yarus_item_edit.php?mode=remove", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")], ID_yarus : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	}
});

$(document).on("click", "#popup_yarus #delete_item", function(e) {
	var yarus_id = $("#popup_yarus").data("yarus-id");
		
	if (confirm("Вы действительно хотите удалить выбранную запись?")) {
		$.post("/sklad_ajax_yarus_item_edit.php?mode=remove", { ID_yarus_items : [$(this).closest("tr").data("yarus-item-id")], ID_yarus : yarus_id }, function () {
			refreshYarusItems(yarus_id, BackToAdd());
		});
	}
});

$(document).on("click", "#popup #delete_item", function(e) {	
	if (confirm("Вы действительно хотите удалить выбранный ярус?")) {
		$.post("/sklad_ajax_yarus_edit.php?mode=remove", { ID_yaruses : [$(this).closest("tr").data("yarus-id")], ID_box_item : $("#popup").data("box-id") }, function (data) {
			refreshYarus($("#popup").data("box-id"), BackToAdd());
			
			HidePopupYarus();
		});
	}
	return false;
});

$(document).on("click", "#popup_yarus #move", function(e) {
	var button_text = $(this).text();

	if ($("#move_items_block").css("display") == "block") {
		$(this).text("Изменить ярус");
		
		$("#move_items_block").css("display", "none");
		$("#remove, #otk_status").css("display", "block");
	} else {
		$(this).text("Отмена");
		
		$("#move_items_block").css("display", "block");
		$("#remove, #otk_status").css("display", "none");
	}
	
	$("#move_items_block").empty().load("/sklad_ajax_form_move_yarus_item.php", function () {
		$("#move_items_block #sklad_select").val($("#popup").data("sklad-id")).trigger("change");
	});
});

$(document).on("click", "#popup_yarus #otk_confirm", function(e) {
	var yarus_id = $("#popup_yarus").data("yarus-id");
		
	if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
		if (confirm("Вы действительно хотите подвердить контроль ОТК для выбранных записей?")) {
			var yarus_item = $("#yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
			});
			
			$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm", { ID_yarus_items : yarus_items_array }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите подвердить контроль ОТК для выбранной записи?")) {
			$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")] }, function () {
				refreshYarusItems(yarus_id, function () {
					$("#popup_yarus #multiselect").prop("checked", false);
					
					ScrollToEditedItem();
					
					BackToAdd();
				});
			});
		}
	}
});

$(document).on("click", "#popup_yarus #otk_confirm_remove", function(e) {
	var yarus_id = $("#popup_yarus").data("yarus-id");
		
	if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
		if (confirm("Вы действительно хотите снять подтверждение контроля ОТК для выбранных записей?")) {
			var yarus_item = $("#yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
			});
			
			$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm_remove", { ID_yarus_items : yarus_items_array }, function (data) {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите снять подтверждение контроля ОТК для выбранной записи?")) {
			$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm_remove", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")] }, function (data) {
				refreshYarusItems(yarus_id, function () {
					$("#popup_yarus #multiselect").prop("checked", false);
					
					ScrollToEditedItem();
					
					BackToAdd();
				});
			});
		}
	}
});

$(document).on("change", "#sklad_select", function() {
	var sklad_item_select = $("#sklad_item_select");

	sklad_item_select.empty().load("/sklad_ajax_item_move.php?show=sklad_items&ID_sklad=" + $(this).val(), function () {
		sklad_item_select.val($("#popup").data("box-id")).trigger("change").focus();
	});
});

$(document).on("change", "#popup_yarus input[type=checkbox]", function(e) {
	if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
		if ($(this).prop("checked")) {
			$(this).closest("tr").addClass("item_selected");
		} else {
			$(this).closest("tr").removeClass("item_selected");
		}
		
		$("#remove, #move, #otk_status").css("display", "block");
		$("#otk_confirm, #otk_confirm_remove").css("display", "");
	} else {
		BackToAdd();
	}
});

$(document).on("keyup", "#popup_yarus input[name=NAME]", function(e) {
	var autocomplete = $("#popup_yarus #autocomplete");
		
	if ($(this).val().length > 2) {
		autocomplete.empty().load("/sklad_ajax_yarus_item_edit.php?mode=search_item&text=" + encodeURIComponent($(this).val()), function () {
			console.log(autocomplete.find("option").length);
			if (autocomplete.find("option").length != 0) {
				autocomplete.css("display", "block");
			} else {
				autocomplete.css("display", "none");
			}
		});
		
	} else {
		autocomplete.css("display", "none");
	}
});

$(document).on("keyup", "#search_sklad", function(e) {
	var autocomplete = $("#search_sklad_autocomplete");
		
	if ($(this).val().length > 2) {
		autocomplete.empty().load("/sklad_ajax_yarus_item_edit.php?mode=search_sklad&text=" + encodeURIComponent($(this).val()), function () {
			console.log(autocomplete.find("option").length);
			if (autocomplete.find("option").length != 0) {
				autocomplete.css("display", "block");
			} else {
				autocomplete.css("display", "none");
			}
		});
		
	} else {
		autocomplete.css("display", "none");
	}
});

$(document).on("change", "#popup_yarus #autocomplete", function (){
	$("#popup_yarus input[name=NAME]").val($(this).val());

	$(this).css("display", "none");
});

$(document).on("change", "#popup input[type=checkbox]:not(input[name=floor])", function(e) {
	if ($(this).prop("checked")) {
		$(this).closest("tr").addClass("item_selected");
		
		$("#remove_yarus").css("display", "block");
	} else {
		$(this).closest("tr").removeClass("item_selected");
		
		$("#remove_yarus").css("display", "");
	}
});

$(document).on("change", "#sklad_item_select", function() {
	$("#sklad_yarus_select").empty().load("/sklad_ajax_item_move.php?show=sklad_item_yaruses&ID_sklad_item=" + $(this).val());
});

$(document).on("change", "#sklad_yarus_select", function() {
	var yarus_id = $("#popup_yarus").data("yarus-id");
		
	if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
		if (confirm("Вы действительно хотите перенести выбранные предметы?")) {
			var yarus_item = $("#popup_yarus #yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
				yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
			});
			
			$.post("/sklad_ajax_yarus_item_edit.php?mode=move", { ID_yarus_items : yarus_items_array, ID_yarus : $(this).val(), ID_yarus_from : yarus_id }, function (data) {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите перенести выбранный предмет?")) {
			$.post("/sklad_ajax_yarus_item_edit.php?mode=move", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")], ID_yarus : $(this).val(), ID_yarus_from : yarus_id }, function (data) {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	}
});

$(document).on("click", "#popup #remove_yarus", function(e) {
	var yarus_id = $("#popup").data("box-id");
			
	if ($("#popup input[type=checkbox]:not(input[name=floor]):checked").length > 0) {
		if (confirm("Вы действительно хотите удалить выбранные ярусы?")) {
			var yarus_item = $("#popup #yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-id"));
			});
			
			$.post("/sklad_ajax_yarus_edit.php?mode=remove", { ID_yaruses : yarus_items_array, ID_box_item : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите удалить выбранный ярус?")) {
			$.post("/sklad_ajax_yarus_edit.php?mode=remove", { ID_yaruses : [$("#popup_yarus input[name=yarus_item]").val()], ID_box_item : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	}
});

$(document).on("keyup", "#search_item", function () {
	var value = this.value.toLowerCase().trim();

	var items = $("#popup_yarus #yarus_item_select tr");
	
	items.each(function () {
		$(this).find("td").each(function () {
			var id = $(this).text().toLowerCase().trim();
			
			var not_found = (id.indexOf(value) == -1);
			
			if (!not_found) {
				$(this).closest("tr").css("display", "");
			} else {
				$(this).closest("tr").css("display", "none");
			}
			
			return not_found;
		});
	});
});

function refreshYarusItems(yarus_id, callback)
{
	$("#popup_yarus #yarus_item_select").empty().load("/sklad_ajax_show_popup_yarus.php?ID_yarus=" + yarus_id + " #popup_yarus #yarus_item_select", callback);
	
	refreshYarus($(".box_selected").data("box-id"), null);
}

function refreshYarus(box_id, callback)
{
	$("#popup").empty().load("/sklad_ajax_show_popup.php?ID_item=" + box_id + " #popup #test", callback);
}

function HidePopup()
{
	$("#popup, #popup_yarus").remove();
	
	UnCheck($(".item"));
}

function HidePopupYarus()
{
	$("#popup_yarus").remove();

	$("#popup .yarus_select tbody tr").removeClass("item_selected");
}

function UnCheck(element)
{
	$(element).removeClass("box_selected");
}

function BackToAdd()
{
	$("#move").text("Изменить ярус");
	$("#move_items_block, #remove, #move, #otk_status").css("display", "none");
	$("#popup_yarus #multiselect").prop("checked", false);
	$("#popup_yarus #yarus_item_select tr").removeClass("item_selected");
	$("#popup_yarus input[name=COUNT]").val("1");
	$("#popup_yarus input[name=NAME], #popup_yarus textarea[name=KOMM]").val("");
	$("#popup_yarus").data("yarus-item-id", "");
	$("#popup_yarus input[type=checkbox]").prop("checked", false);
	$("#popup_yarus input[type=submit]").val("Добавить");
	$("#popup_yarus input[type=submit], #popup_yarus input[name=COUNT], #popup_yarus input[name=NAME], #popup_yarus textarea[name=KOMM]").prop("disabled", $("#popup input[name=NAME]").prop("disabled"));
	$("#search_item").val("");
	$("#popup_yarus input[name=NAME]").focus();
}

function ScrollToEditedItem ()
{	
	var edited_item = $("#popup_yarus #yarus_item_select tr"), edited_element;
						
	edited_item.each(function () {
		
		if ($(this).data("yarus-item-id") == $("#popup_yarus").data("yarus-item-id")) {
			edited_element = $(this);
								
			return;
		}
	});
										
	$("#popup_yarus #yarus_item_select").parent().scrollTop(edited_element.offset().top - 450);
	
	edited_element.addClass("item_edited").fadeIn(1300, function () {
		edited_element.removeClass("item_edited");
	});
}
</script>
