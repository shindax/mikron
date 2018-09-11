<style>
<!--

#Printed * {
	font : normal 12pt "Times New Roman" Arial Verdana;
}
#Printed span.CODE39 {
	font : normal 36pt CODE39;
}

#Printed H6 {FONT : bold 6pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
#Printed H5 {FONT : bold 8pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
#Printed H4 {FONT : bold 10pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
#Printed H3 {FONT : bold 12pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
#Printed H2 {FONT : bold 16pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
#Printed H1 {FONT : bold 20pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}

#Printed b {
	font : bold 12pt "Times New Roman" Arial Verdana;
}

#PageTable {
	BORDER : black 2px solid;
        COLOR : #000;
        BORDER-COLLAPSE : collapse;
        Text-Align : center;
	Vertical-Align : middle;     
}

#PageTable TR TD {
	BORDER : black 1px solid;
	PADDING-RIGHT : 2px;
	PADDING-LEFT : 6px;
	PADDING-BOTTOM : 1px;
	PADDING-TOP : 2px;
	font-size : normal 12pt "Times New Roman" Arial Verdana;
	font-size:11pt;
	text-align: left;
	vertical-align: top;
	background: #fff;
}

#PageTable TR.first TD {
	text-align: center;
	font-weight:700;
}

#PageTable table.itable {
	border: none;
	padding: 0px;
	margin: 0px;
	width: 100%;
	background: none;
}

#PageTable table.itable td {
	border: none;
	padding: 0px;
	margin: 0px;
	background: none;
}

#PageTable table.itable tr {
	border: none;
	padding: 0px;
	margin: 0px;
	background: none;
}

#PageTable TR TD b {
	font : bold 12pt "Times New Roman" Arial Verdana;
	color: black;
}

div.a4p {
	width : 1000px;
	text-align: left;
	background: #fff;
	page-break-after:always;
}

.view div.a4p {
	display: block;
	border: 1px solid #444;
	padding: 20px;
	box-shadow: 3px 4px 20px #555555;
	margin: 40px;
}

table.view {
	width: 100%;
	margin: 0px;
	padding: 0px;
}


-->
</style>
<style type="text/css" media="print">
  @page { size: landscape; }
</style>
<center>
<div id="Printed" class="a4p">
<?php
	
	$result = dbquery("SELECT sy.ID as ID,si.NAME, sy.ORD as ORD, COUNT(sd.ID) as YarusItemCount FROM okb_db_sklades_yaruses sy
						LEFT JOIN okb_db_sklades_detitem sd ON sd.ID_sklades_yarus = sy.ID 
						LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
						WHERE ID_sklad = " . $_GET['p0'] . "
						GROUP BY sy.ID
						ORDER BY si.NAME") ;

	while ($row = mysql_fetch_array($result)){
		if ($row['YarusItemCount'] == 0) continue;
		
		echo '<div class="pagebreak"><h4>' . mysql_result(dbquery("SELECT `NAME` FROM `okb_db_sklades` WHERE ID = " . $_GET['p0']), 0) . ' . Ячейка: ' . $row['NAME'] . '. Ярус: ' . ($row['ORD'] == 0 ? 'Пол' : $row['ORD']) . '.</h4>
	
		<table ID="PageTable" border="0" cellpadding="0" cellspacing="0" width="1000">
		<tr class="first">
		<td>№</td>
		<td>Наименование</td>
		<td>Комментарий</td>
		<td>Кол-во</td>
		<td>ОТК</td>
		</tr>';
		
		$result2 = dbquery("SELECT * FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . $row['ID']);
		
		while ($row2 = mysql_fetch_assoc($result2)) {
			echo '<tr>'
				,'<td style="text-align:center;">' . $row2['ORD'] . '</td>'
				,'<td>' . $row2['NAME'] . '</td>'
				,'<td>' . $row2['KOMM'] . '</td>'
				,'<td style="text-align:center;">' . $row2['COUNT'] . '</td>'
				,'<td style="text-align:center;">' . ($row2['OTK_STATUS'] == 0 ? '—' : '+') . '</td>'
				,'</tr>';
					
		}
		
		echo '</table></div>';
	}
		


?>
</div>
</center>
