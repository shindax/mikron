<?php
function convert_log_time($s)
{
    $s = preg_replace('#:#', ' ', $s, 1);
    $s = str_replace('/', ' ', $s);
    if (!$t = strtotime($s)) return FALSE;
    return date('Y-m-d H:i:s', $t);
}
function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
    return $obj;
}

$before = date('Ymd', strtotime(date('Y-m-d H:i:s') . ' -3 day'));

if (!isset($_GET['date_from']) && !isset($_GET['date_to'])) {
	$result = dbquery("SELECT krz.*, clients.NAME as ClientName,okb_users.FIO as FIO

	FROM `okb_db_krz` krz
	LEFT JOIN okb_db_clients clients ON clients.ID = krz.ID_clients
	LEFT JOIN okb_users ON okb_users.ID = krz.ID_users

	 WHERE `EDIT_STATE` = 0 AND DATE_START <= " . $before . " ORDER BY `ID` DESC");
} else {
	switch ($_GET['type'])
	{
		case 1:
			$result = dbquery("SELECT krz.*, clients.NAME as ClientName,okb_users.FIO as FIO

			FROM `okb_db_krz` krz
			LEFT JOIN okb_db_clients clients ON clients.ID = krz.ID_clients
			LEFT JOIN okb_users ON okb_users.ID = krz.ID_users

			 WHERE `EDIT_STATE` = 0 AND DATE_START <= " . str_replace('-', '', $_GET['date_to']) . "
					AND DATE_START >= " . str_replace('-', '', $_GET['date_from']) . " ORDER BY `ID` DESC");

			break;
		case 2:
			
			$result = dbquery("SELECT krz.*, clients.NAME as ClientName,okb_users.FIO as FIO

			FROM `okb_db_krz` krz
			LEFT JOIN okb_db_clients clients ON clients.ID = krz.ID_clients
			LEFT JOIN okb_users ON okb_users.ID = krz.ID_users

			 WHERE `EDIT_STATE` = 0 AND DATE_START >= " . $before . " ORDER BY `ID` DESC");
					
			break;
		case 3:
			
			
			$result = dbquery("SELECT krz.*, clients.NAME as ClientName,okb_users.FIO as FIO

			FROM `okb_db_krz` krz
			LEFT JOIN okb_db_clients clients ON clients.ID = krz.ID_clients
			LEFT JOIN okb_users ON okb_users.ID = krz.ID_users

			 WHERE `EDIT_STATE` = 1 AND DATE_START <= " . str_replace('-', '', $_GET['date_to']) . "
					AND DATE_START >= " . str_replace('-', '', $_GET['date_from']) . " ORDER BY `ID` DESC");
				
			break;
	}
}
 
 $count = mysql_num_rows($result);
 
echo '
В промежутке с: <input ' . ($_GET['type'] == 2 ? 'disabled="disabled"' : '' ) . ' type="date" id="date_from" value="' . $_GET['date_from'] . '"/>
 по <input ' . ($_GET['type'] == 2 ? 'disabled="disabled"' : '' ) . ' value="' . $_GET['date_to'] . '" type="date" id="date_to"/>
 <select name="report_type">
	 <option value="1" ' . ($_GET['type'] == 1 ? 'selected="selected"' : '') . '>Просроченные</option>
	 <option value="2" ' . ($_GET['type'] == 2 ? 'selected="selected"' : '') . '>В работе</option>
	 <option value="3" ' . ($_GET['type'] == 3 ? 'selected="selected"' : '') . '>Выполненные</option>
 </select>
 <input type="button" id="date_submit" value="Применить"/>
<br/><br/>
 
<table class="tbl" style="width:1200px;">

<tr class="First">

	<td >№ п/п</td>
	<td>№ КРЗ	</td>
	<td>Наименование ДСЕ	</td>
	<td>№ Чертежа		</td>
	<td>Заказчик</td>
	<td>Инициатор</td>
	<td>Дата созд.	</td>';
	
			if ($_GET['type'] == 1) {
	echo '
	<td style="text-align:center;">Просрочка, дней</td>';
			}
				if ($_GET['type'] == 3) {
	echo '
	<td nowrap="nowrap" style="text-align:center;">Дата закрытия</td>';
	echo '
	<td nowrap="nowrap" style="text-align:center;">Просрочка, дней</td>';
			}	
 echo '</tr>';
$i = 1;

if ($_GET['type'] == 3) {
			/* $krz_open_close_lines = file('/var/www/okbmikron/logs/pizdec.log', FILE_IGNORE_NEW_LINES);
 
		$idkrz_from_log = array();

		foreach($krz_open_close_lines as $krz_open_close_line) {
			preg_match_all('#(\[.*\]).+edit_state=(db_krz|.+|EDIT_STATE|1) HTTP#Us', $krz_open_close_line, $out);
			 
			list(, $id_krz, , $state) = explode('|', $out[2][0]);
			
			list ($x) = explode(' ', $out[1][0]);
			
			if ($state == 1) {
				$idkrz_from_log[$id_krz] = convert_log_time(str_replace('[', '', $x));
			} 
		}
*/

}
 
while ($row = mysql_fetch_assoc($result)) {
	$krzdet = mysql_fetch_assoc(dbquery("SELECT `NAME`,OBOZ FROM okb_db_krzdet WHERE `ID_krz` = " . $row['ID']));
	
	$year = substr($row['DATE_START'], 0, 4);
	$month = substr($row['DATE_START'], 4, 2);
	$day = substr($row['DATE_START'], 6, 2);
	
	$a = strtotime(date($year . '-' . $month . '-' . $day . ' 00:00:00'));
	$b = strtotime(date('Y-m-d H:i:s') . ' -3 day');
	
	$exp =  secondsToTime($a - $b);
	$exp_days = ($exp['d'] + 1 > 0 ? 0 : str_replace('-', '', $exp['d']));
 
	if ($_GET['type'] == 3){
		$date_from_log = $row['EDIT_STATE_DATE'];
		
		$seconds_from_log = strtotime($date_from_log);
		
			$exp =  secondsToTime($a - $seconds_from_log);
		$exp_days = $exp['d'];
	}
 
	if ($_GET['type'] == 1 && $exp_days == 0) {
		$count -= 1;
		
		continue;
	} 
	
	$exp_days = str_replace('-', '', ($_GET['type'] == 3 ? $exp_days + 1 : $exp_days) ) ;
	
	
	if ($_GET['type'] == 3) {
		if ($exp_days > 2 && $exp_days < 1000) {
			$style = 'style="background-color:#ccc"';
		} else {
			$style = '';
		}
	}
	
 
	echo '
	
	<tr ' . $style. '>
		<td class="Field" nowrap="nowrap" style="text-align:center;">' . $i . ' / ' . $count . '</td>
		<td class="Field"><a href="/index.php?do=show&formid=7&id=' . $row['ID'] .'">' . $row['NAME'] . '</a></td>
		<td class="Field">' . $krzdet['NAME'] . '</td>
		<td class="Field">' . $krzdet['OBOZ'] . '</td>
		<td class="Field">' . $row['ClientName'] . '</td>
		<td class="Field" nowrap="nowrap">' . $row['FIO'] . '</td>
		<td class="Field">' .  $day . '.' . $month. '.' . $year. '</td>';
				
		if ($_GET['type'] == 1 || $_GET['type'] == 3){
			echo ' <td  nowrap="nowrap" class="Field" style="text-align:center;">' .  ($_GET['type'] == 3 ? $row['EDIT_STATE_DATE'] : $exp_days). '</td>';
			if ($_GET['type'] != 1) echo ' <td  nowrap="nowrap" class="Field" style="text-align:center;">' .      ($exp_days > 2 ? $exp_days : '—')  . '</td>';
		}
		
		echo '
	</tr>
	';

//	dbquery('UPDATE okb_db_krz SET EDIT_STATE_DATE = "' . $idkrz_from_log[$row['ID']] . '" WHERE ID = ' . $row['ID']);
	
	++$i;
	

}

echo '</table>';

?>

<script type="text/javascript">

$(document).on("click", "#date_submit", function () {
 	var date_from = $("#date_from").val(), date_to = $("#date_to").val();
	
	if (date_from != "" && date_to != "") {
		window.location.href = "/index.php?do=show&formid=243&date_from=" + date_from + "&date_to=" + date_to + "&type=" + $("select[name=report_type]").val();
	}
}).on("change", "select[name=report_type]", function () {
	var value = $(this).val(); 
	switch (value)
	{
		case '1': 
			$("#date_from, #date_to").prop("disabled", false);
			break;
		case '2':
			
			$("#date_from, #date_to").prop("disabled", true);
			break;
		case '3':
			
			$("#date_from, #date_to").prop("disabled", false);
			break;
	}
});;

</script>