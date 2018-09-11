<?php

require_once( 'functions.php' );
 
echo '<style type="text/css">
@import url(/project/reports/trial_period/styles.css);
</style><h2>Испытательный срок</h2><br/>
	<table class="rdtbl tbl report_table">
		<thead>
		<tr class="first">
			<td class="nbg"></td>
			<td>Сотрудник</td>
			<td>Должность</td>
			<td>Дата приема</td>
			<td>До окончания испытательного срока</td> 
		</tr>
		</thead> 
		<tbody>';
		
	$result = dbquery("SELECT *,`okb_db_special`.`NAME` as `SpecialName`
						FROM `okb_db_resurs`
						LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_resurs`.`ID_special`
						WHERE  `TID` = 0");

	while ($row = mysql_fetch_assoc($result)) {
		$d = substr($row['DATE_FROM'], 6, 2);
		$m = substr($row['DATE_FROM'], 4, 2);
		$y = substr($row['DATE_FROM'], 0, 4);
		
		$plus_30_timestamp = strtotime(date("$y-$m-$d 00:00:00") . ' +3 month');

		$diff = $plus_30_timestamp - time();
		
		$end = secondsToTime($diff);
		
		if ($end['d'] < 1) {
			continue;
		}
		
		echo '<tr>'
			,'<td class="nbg"></td>'
			,'<td class="first_level"><img src="/project/img/resurs.png"/> '
			,' '. htmlspecialchars($row['FF'] . ' ' . $row['II'] . ' ' . $row['OO']) . ' </td>'
			,'<td>' . $row['SpecialName'] . '</td>'
			,'<td>' . $d . '.' . $m . '.' . $y . '</td>'
			,'<td>' . /*($end['d'] > 31 ? floor(($end['d'] / date('t')) * 2) / 2 . ' месяцев' :*/$end['d'] . ' дней'/*)*/ .  '</td>' 
			,'</tr>';
					
	}

?>

	</tbody>
</table>
