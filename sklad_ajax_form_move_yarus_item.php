<?php

header('Content-type: text/plain; charset=windows-1251');

define('MAV_ERP', true);

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';

include 'sklad_func.php';
	
$result = dbquery("SELECT ID,NAME FROM okb_db_sklades ORDER BY ID ASC");

$sklad = '';

while ($row = mysql_fetch_assoc($result)) {
	$sklad .= '<option value="' . $row['ID'] . '">' . htmlspecialchars($row['NAME']) . '</option>';
}

echo '
	<table style="width:100%;position:absolute;" class="rdtbl tbl">
		<thead>
			<tr class="first"><td width="160">Склад</td><td width="250">Ячейка</td><td>Ярус</td></tr>
		</thead>
		<tbody>
			<tr>
				<td class="Field">
					<select style="width:100%" size="11" id="sklad_select">' . $sklad . '</select>
				</td>
				<td class="Field">
					<select style="width:100%" size="11" id="sklad_item_select">' . $sklad_items . '</select>
				</td class="Field">
				<td>
					<select style="width:100%" size="11" id="sklad_yarus_select">' . $sklad_yarus . '</select>
				</td>
			</tr>
		</tbody>
	</table>';
	