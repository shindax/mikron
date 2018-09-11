<?php

header('Content-type: text/plain; charset=windows-1251');

define('MAV_ERP', true);

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';

include 'sklad_func.php';

$result = dbquery("SELECT ID,ORD,NAME,KOMM,COUNT,OTK_STATUS FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . (int) $_GET['ID_yarus'] . "
							ORDER BY ORD ASC");

$yarus_ord = mysql_result(dbquery("SELECT ORD FROM okb_db_sklades_yaruses WHERE ID = " . (int) $_GET['ID_yarus']), 0);

echo '<div class="popup" id="popup_yarus" data-yarus-id="' . (int) $_GET['ID_yarus'] . '" data-yarus-item-id="">
	<table style="width:100%">
		<tr><td>Название:</td><td><input type="text" name="NAME"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '/></td>
		<select size="12" id="autocomplete"></select>
		</tr>
		<tr><td>Количество:</td><td><input type="number" pattern="^[0-9]+$" value="1" name="COUNT"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '/></td></tr>
		<tr><td>Комментарии:</td><td><textarea style="float:left" name="KOMM"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '></textarea>
		<tr>
			<td>
				<input type="submit" value="Добавить" style="float:left"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '>
			</td>
			<td style="padding-top:1px;">
				<b style="float:right" id="box_yarus_id"></b>
				<a href="javascript:void(0)" onclick="HidePopupYarus()" style="font-size:12px;float:left;margin-left:4px;">Закрыть</a>
			</td>
		</tr>
	</table>
	<hr/>
	<div id="move_items_block"></div>
	<b>Выберите предмет</b>
	<div style="float:right">
		<input type="text" id="search_item"/>
	</div>
	<table class="rdtbl tbl yarus_select_thead">
		<tr class="first"><td>№</td><td>Наименование</td><td>Комментарий</td><td>Кол-во</td><td><input type="checkbox" id="multiselect"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/></td><td></td></tr>
	</table>
	<div style="height:165px;overflow-y:auto;">
		<table class="rdtbl tbl yarus_items" id="yarus_item_select">
		<tbody>';

	while ($row = mysql_fetch_assoc($result)) {
		echo '<tr' . ($row['OTK_STATUS'] == 1 ? ' class="otk_confirmed"' : '') . ' data-yarus-item-id="' . $row['ID'] . '">'
				,'<td>' . $row['ORD'] . '</td>'
				,'<td>' . htmlspecialchars($row['NAME']) . '</td>'
				,'<td>' . htmlspecialchars($row['KOMM']) . '</td>'
				,'<td>' . $row['COUNT'] . '</td>'
				,'<td>' . ($row['OTK_STATUS'] == 0 || $can_edit_otk ? '<input type="checkbox" name="ID_yarus_items"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/>' : '') . '</td>'
				,'<td>' . ($can_edit_sklad && $row['OTK_STATUS'] == 0 ? '<a href="javascript:void(0)" id="delete_item"><img src="uses/del.png"/></a>' : '') . '</td>'
			,'</tr>';
	}
	
	echo '</tbody>
		</table>
		</div>
		<div style="margin-top:4px">
			' . ($can_edit_otk ? '
			<div id="otk_status" style="float:left;">
				ОТК: <button id="otk_confirm">Подтвердить</button><button id="otk_confirm_remove">Снять</button>
			</div>' : '') . '
			' . ($can_edit_sklad ? '
			<button id="remove">Удалить</button>
			<button id="move">Изменить ярус</button>' : '') . '
		</div>
	</div>';
