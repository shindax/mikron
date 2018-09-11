<?php

	ini_set('display_errors', '0');
	error_reporting(0);
	
	header('Content-type: text/html; charset=windows-1251');
	
	define("MAV_ERP", TRUE);
	
	$not_func_style = true;
	
	
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	dbquery('SET NAMES cp1251');
	
	$resource_name = dbresult(dbquery("SELECT NAME FROM ".$db_prefix."db_shtat WHERE ID_resurs = " . $_GET['resource_id']), 0);

	echo '
	<div style="border: 5px solid #fff; background: #fff;">
		<b style="float:left;padding:3px">' . $resource_name . '</b><br/><br/>
		<table cellpadding="10">
			<thead>
				<tr class="RTOP" style="background: #c6d9f1 URL(img/bgtop.gif) repeat-x;">
					<td colspan="2" width="300">Операция</td>
					<td colspan="2" width="100">План</td>
					<td></td>
				</tr>
				<tr class="RTOP" style="background: #c6d9f1 URL(img/bgtop.gif) repeat-x;">
				<td width="35">№</td>
				<td width="200">Наименование</td>
				<td width="50">Кол-во</td>
				<td width="50">Н/Ч</td>
				<td style="text-align:center">Смена</td>
				</tr>
			</thead>
		</table>
		<div style="overflow-y:auto;max-height:250px">
			<table border="1" style="height:auto" cellpadding="8">';
	
	$result = dbquery("SELECT ID_operitems,NUM,NORM,SMEN FROM ".$db_prefix."db_zadan
							WHERE (DATE = '" . $_GET['date'] . "') and (ID_resurs = '" . $_GET['resource_id'] . "')
							ORDER by ORD");
							
	while($row = mysql_fetch_assoc($result)) {
		$operitem_ord = mysql_result(dbquery("SELECT ORD FROM ".$db_prefix."db_operitems WHERE ID = " . $row['ID_operitems']), 0);
	
		echo '<tr style="background: #bcd0e9;height:40px">';

		echo '<td width="34">' . $operitem_ord . '</td>';
		Field($row, "db_zadan", 'ID_operitems', false, '', '', "width:182px;");
		Field($row, "db_zadan", 'NUM', false, '', '', "width:50px;");
		Field($row, "db_zadan", 'NORM', false, "", "", "width:47px");
		Field($row, "db_zadan", 'SMEN', false, '', '', "text-align:center");
		
		echo '</tr>';
	}

	echo '
			</table>
		</div>
	</div>';
