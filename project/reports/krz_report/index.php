<?php  

$krz_open_close_lines = file('/var/www/okbmikron/logs/pizdec.log', FILE_IGNORE_NEW_LINES);
//192.168.1.108 - - [01/Mar/2017:10:52:48 +0700] "GET /index.php?do=show&formid=7&id=8170&edit_state=db_krz|8170|EDIT_STATE|1 HTTP/1.1" 302
// 22325 "http://okbmikron/index.php?do=show&formid=7&id=8170" "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.142 Safari/535.19"
function convert_log_time($s)
{
    $s = preg_replace('#:#', ' ', $s, 1);
    $s = str_replace('/', ' ', $s);
    if (!$t = strtotime($s)) return FALSE;
    return date('d-m-Y H:i:s', $t);
}
echo '<pre>';

$idkrz_from_log = array();

foreach($krz_open_close_lines as $krz_open_close_line) {
	preg_match_all('#(\[.*\]).+edit_state=(db_krz|.+|EDIT_STATE|1) HTTP#Us', $krz_open_close_line, $out);
	 
	list(, $id_krz, , $state) = explode('|', $out[2][0]);
	
	list ($x) = explode(' ', $out[1][0]);
	
	if ($state == 1) {
		$idkrz_from_log[$id_krz] = convert_log_time(str_replace('[', '', $x));
	} 
}


$edo_result = dbquery("SELECT * FROM `okb_db_edo_inout_files` WHERE `TIP_FAIl` = 0 AND `VID_FAIL` = 1 AND `ID_krz` != '' ORDER BY `ID` DESC");

echo '<table class="rdtbl tbl">
<thead>
	<tr class="first">
		<td>Заявка</td>
		<td>Дата открытия КРЗ</td>
		<td>Дата закрытия КРЗ</td>
		<td>Дата отправки КП</td>
		<td></td>
	</tr>
</thead>
</tbody>
';

while ($row = mysql_fetch_assoc($edo_result)) {
	echo '<tr>
	<td width="100" style="text-align:center" class="Field"> ' . $row['DATA'] . '<br/>' . $row['MORE'] . '</td>
	';
	
	$krzs1 = explode('|', $row['ID_krz']);
 
	
	$krz1_start = mysql_result(dbquery("SELECT `DATE_START` FROM `okb_db_krz` WHERE `ID` = " . $krzs1[0]), 0);
	
	$krzs1_end = '';
	$krzs1_text = '';
		
	$edo_out_id = '';
	
	foreach ($krzs1 as $krz1) {
		if (!empty($idkrz_from_log[$krz1])) $krzs1_end = $idkrz_from_log[$krz1];
		
		$krzs1_text .= ' <a href="/index.php?do=show&formid=7&id=' . $krz1 . '">' . $krz1 . '</a>';
		
		
			$edo_out_id = mysql_result(dbquery("SELECT ID FROM `okb_db_edo_inout_files` WHERE `ID_krz` LIKE '%" . $krz1 . "%' LIMIT 1"), 0);
	
	}

		$edo_out_date = mysql_result(dbquery("SELECT DATA FROM `okb_db_edo_inout_files` WHERE TIP_FAIL = 1 AND `ID_krz` LIKE '%" . $row['ID_krz'] . "%' LIMIT 1"), 0);
		$edo_out_id = mysql_result(dbquery("SELECT ID FROM `okb_db_edo_inout_files` WHERE TIP_FAIL = 1 AND `ID_krz` LIKE '%" . $row['ID_krz'] . "%' LIMIT 1"), 0);

	echo '<td class="Field">' . $krz1_start . '</td>';
	echo '<td class="Field">' . $krzs1_end . '</td>';
	echo '<td class="Field"><a href="/index.php?do=show&formid=111&id=' . $edo_out_id . '">' . $edo_out_date . '</a></td>';
	echo '<td class="Field">' . $krzs1_text . '</td>';
}


//9700|9701|9702|9703|9704|9705|9706|9707|9708|9709|9710|







