<?php
 
if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
			$result = dbquery("SELECT okb_db_koop_req.*,z.NAME as ZakazName,u.FIO as FIO,zt.description as ZakType

			FROM `okb_db_koop_req`  
			LEFT JOIN okb_db_zak z ON z.ID = okb_db_koop_req.ID_zak
			LEFT JOIN okb_users u ON okb_db_koop_req.ID_users = u.ID 
			LEFT JOIN okb_db_zak_type zt oN zt.id = z.TID
			
			 WHERE  okb_db_koop_req.CDATE <= " . str_replace('-', '', $_GET['date_to']) . "
					AND okb_db_koop_req.CDATE >= " . str_replace('-', '', $_GET['date_from']) . " ORDER BY `ID` DESC");

}
 
 $count = mysql_num_rows($result);
echo '
� ���������� �: <input type="date" id="date_from" value="' . $_GET['date_from'] . '"/>
 �� <input value="' . $_GET['date_to'] . '" type="date" id="date_to"/>

 <input type="button" id="date_submit" value="���������"/>
<br/><br/>
 ';
 
if ($count > 1) {
	echo '<br/> �����: ' . $count . '.<br/><br/>';

 echo '
<table class="tbl" style="width:1200px;">

<tr class="first">
		<td width="80">���. �</td>
		<td width="240">������������ ������</td>
		<td width="160">�����</td>
		<td width="50">���-��</td>
		<td width="60">���� �/�</td>
		<td width="120">����������</td>
		<td>�����</td>
		<td width="100">����<br>��������</td>
		<td width="100">������</td>
	</tr>';
	}
$i = 1;

 
while ($row = mysql_fetch_assoc($result)) {
	

echo '	<tr>
<td class="Field cl_00" rowspan="10">
'. $row['NAME'] .  '</td>
<td class="Field" style="background: #c8daf2;" colspan="4" rowspan="2">
<b>' . $row['FIO'] . '</b> ' . $row['LAST_CHANGE'] . '</td>
<td class="Field" style="background: #c8daf2;" colspan="3">
</td>
<td style="background: #c8daf2;" class="rwField ntabg">  </td>
 

 </tr>
	<tr data-user-id="59" data-id="991">
<td class="Field" style="background: #c8daf2;" colspan="3">
</td>
<td style="background: #c8daf2;" class="rwField ntabg">  </td>
 
	</tr>
	<tr data-user-id="59" data-id="991">
<td style="max-width: 250px;" class="Field">  ' . $row['TXT'] . '</td>
<td style="max-width: 180px;" class="Field">' . $row['OBOZ'] . '</td>
<td style="max-width: 50px;" class="Field">' . $row['COUNT'] . '</td>
<td style="max-width: 50px;" class="Field">' . $row['PLAN_NCH'] . '</td>
<td style="max-width: 120px;" rowspan="2" class="Field">�����</td>
<td rowspan="2" class="Field">' . $row['ZakType'] . ' ' . $row['ZakazName'] . '</td>';

$d = substr($row['DATE'], 6, 2);
$m = substr($row['DATE'], 4, 2);
$y = substr($row['DATE'], 0, 4);

echo '
<td rowspan="2" class="Field">' . $d . '.' . $m . '.' . $y . '</td>
<td rowspan="2" class="rwField ntabg"><select><option value="0" selected="">---</option><option ' . ($row['STATE'] == 1 ? ' selected ' : '') . ' value="1">������.</option><option  value="2">�����.</option></select></td>
 
	</tr>
	<tr data-user-id="59" data-id="991">
<td colspan="4" class="Field">&nbsp;</td>
 
	</tr>
	<tr data-user-id="59" data-id="991">
<td class="Field" style="background: #e8f0fb;" colspan="6">
<b>��� �����, �����������:</b></td>
<td class="Field" style="background: #c8daf2;">
������:</td>
<td class="Field">&nbsp;</td>
 
	</tr>
	<tr data-user-id="59" data-id="991">
<td colspan="8" class="Field">' . $row['VIDRABOT'] . '</td>
 
	</tr>
	<tr data-user-id="59" data-id="991">
<td class="Field" style="background: #e8f0fb;" colspan="8">
<b>��������� ������:</b></td>
	</tr>
	<tr data-user-id="59" data-id="991">
<td colspan="8" class="Field">' . $row['OPTIONS'] . '</td>
	</tr>
	<tr data-user-id="59" data-id="991">
<td class="Field" style="background: #e8f0fb;" colspan="8">
<b>����������� ��� (����������/����������):</b></td> 
	</tr>
	<tr data-user-id="59" data-id="991">
<td colspan="8" class="rwField">' . $row['comment'] . '</td>
	</tr>';

}

echo '</table>';
echo '<script>$("a[title=������]").attr("href", "/print.php?do=show&formid=247&date_from=' . $_GET['date_from'] . '&date_to=' . $_GET['date_to'] . '");</script>';

?>
 
<script type="text/javascript">

$(document).on("click", "#date_submit", function () {
 	var date_from = $("#date_from").val(), date_to = $("#date_to").val();
	
	if (date_from != "" && date_to != "") {
		window.location.href = "/index.php?do=show&formid=247&date_from=" + date_from + "&date_to=" + date_to;
	}
}) 
</script>