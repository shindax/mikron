<?php

header('Content-type: text/plain; charset=windows-1251');

define('MAV_ERP', true);

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';

include 'sklad_func.php';
	
$row = mysql_fetch_assoc(dbquery("SELECT NAME,YARUS,KOMM,ID_sklad FROM okb_db_sklades_item WHERE ID = " . (int) $_GET['ID_item']));

$result = dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . (int) $_GET['ID_item'] . " AND ORD = 0");

$has_floor = (bool) mysql_num_rows($result) > 0;

if ($has_floor) $has_items_in_floor = (bool) mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . mysql_result($result, 0) . " LIMIT 1")) > 0;

	$result = dbquery("SELECT sy.ID,sy.ORD, COUNT(sd.ID) as YarusItemCount FROM okb_db_sklades_yaruses sy
						LEFT JOIN okb_db_sklades_detitem sd ON sd.ID_sklades_yarus = sy.ID
						WHERE sy.ID_sklad_item = " . (int) $_GET['ID_item'] . "
							GROUP BY sy.ID
							ORDER BY sy.ORD ASC");
	
echo '<div class="popup" id="popup" data-box-id="' . $_GET['ID_item'] . '" data-sklad-id="' . $row['ID_sklad'] . '"><div id="test">
	<img src="/project/tabel/tr.png" class="h"/>
	<form method="post">
	<table style="width:100%;">
		<tr>
			<td width="125">Название:</td><td><input type="text" name="NAME" value="' . htmlspecialchars($row['NAME']) . '"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '/></td>
		</tr>
		<tr>
			<td>Добавить ярусов:</td>
			<td>
			<div style="width:100%;">
				<div style="float:left;width:50%;text-align:left">
					Кол-во: <input style="width:50%" type="number" pattern="^[0-9]+$" name="YARUS" value="0"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') .'/></div>
				<div style="float:right;width:50%;text-align:right">
					После: <input type="number" style="width:50%" pattern="^[0-9]+$" name="YARUS_from" value="' . ($has_floor ? mysql_num_rows($result) - 1 : mysql_num_rows($result)) .'"' . (!$can_edit_sklad || mysql_num_rows($result) == 0 ? ' disabled="disabled"' : '') .'/></div>
				</div>
			</td>
		</tr>
		<tr><td>Комментарии:<br/>
		Пол: <input type="checkbox" name="floor"' . ($has_floor ? ' checked="checked"' . ($has_items_in_floor || !$can_edit_sklad ? ' disabled="disabled"' : '') : '') . '/>
		</td><td><textarea style="float:left" name="KOMM"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '>' . htmlspecialchars($row['KOMM']) . '</textarea></td></tr>
		<tr>
			<td colspan="2" style="padding-top:4px;">
				<input type="hidden" name="ID_item" value="' . $_GET['ID_item'] . '"/>
				<input type="hidden" name="ID_sklad" value="' . $row['ID_sklad'] . '"/>
				<input type="submit" name="edit_item" value="Применить" style="float:left"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '>
			</td>
			<td>
				<a href="javascript:void(0)" onclick="HidePopup()" style="margin-top:3px;margin-left:4px;font-size:12px;float:right">Закрыть</a>
			</td>
		</tr>
	</table>
	</form>';

if (mysql_num_rows($result) > 0) {
	echo '<hr/>
	<b>Выберите ярус</b>
	<table class="rdtbl tbl">
		<tr class="first"><td width="38">№</td><td width="90">Инфо 1</td><td width="85">Инфо 2</td><td width="50">Инфо 3</td><td width="30"><input type="checkbox" id="multiselect"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/></td><td></td></tr>
	</table>
	<div style="height:180px;overflow-y:auto;">
		<table class="rdtbl tbl yarus_select" id="yarus_item_select">
		<tbody>';

	while ($row = mysql_fetch_assoc($result)) {
		echo '<tr' . ($row['YarusItemCount'] > 0 ? ' class="otk_confirmed"' : '') . ' data-id="' . $row['ID'] . '" data-yarus-id=' . $row['ID'] . '>'
			,'<td width="38">' . ($row['ORD'] == 0 ? 'Пол' : $row['ORD']) . '</td>'
			,'<td width="90"></td>'
			,'<td width="85"></td>'
			,'<td width="50"></td>'
			,'<td width="30">' . ($can_edit_sklad && $row['YarusItemCount'] == 0 ? '<input type="checkbox" name="ID_yarus_items"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/>' : '') . '</td>'
			,'<td>' . ($can_edit_sklad && $row['YarusItemCount'] == 0 ? '<a href="javascript:void(0)" id="delete_item"><img src="uses/del.png"/></a>' : '') . '</td>'
			,'</tr>';
	}
	
	echo '</tbody>
		</table>
	</div>
		<div style="margin-top:4px">
			' . ($can_edit_sklad ? '<button id="remove_yarus">Удалить</button>' : '') . '
		</div></div>
	</div>';
}
