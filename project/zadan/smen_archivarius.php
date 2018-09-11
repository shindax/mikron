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

<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$pdate = $_GET["p0"] * 1;
$smen = $_GET['p1'];

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$y = substr($pdate, 0, 4); $m = substr($pdate, 4, 2); $d = substr($pdate, 6, 2);
		
$date = $d . '.' . $m . '.' . $y;

$zadan_ids = explode(',', $_GET['zadan_ids']);
$zadan_count = count($zadan_ids);

function TableHeader()
{
	global $smen, $date;
	
	return '<div id="Printed" class="a4p"><div style="text-align: right; font-size:105%;font-weight:700">
		–≈≈—“– —Ã≈Õ€  <span style="text-decoration:underline;font-size:11pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;π' . $smen. '&nbsp;&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Ã¿—“≈– ___________________________________________________________________<br>
		</div><br/>
		<table ID="PageTable" border="0" cellpadding="0" cellspacing="0" width="1000">
		<tr class="first">
		<td>◊≈–“≈∆</td>
		<td>Õ¿»Ã≈ÕŒ¬¿Õ»≈</td>
		<td>–¿¡Œ“Õ» </td>
		<td>œ–»◊»Õ¿ Õ≈¬Œ«¬–¿“¿</td>
		</tr>';
}

function TableEmpty()
{
	echo '<tr>'
			,'<td width="280">&nbsp;</td>'
			,'<td width="280">&nbsp;</td>'
			,'<td width="80" style="text-align:center">&nbsp;</td>'
			,'<td width="140">&nbsp;</td>'
			,'<td>&nbsp;</td>'
			,'</tr>';
}

if ($_GET['zadan_ids'] != '') {
	for ($i = 0; $i < $zadan_count; $i += 30) {
		echo TableHeader();
		
		$x = 0;
		
		for ($y = $i; $y < $i + 30 && $y < $zadan_count; ++$y) {
			$zadan = mysql_fetch_array(dbquery("SELECT ID_resurs,ID_zakdet,okb_db_zakdet.NAME,okb_db_zakdet.OBOZ,okb_db_resurs.NAME as NAME_RESURS FROM okb_db_zadan
													LEFT JOIN okb_db_zakdet ON okb_db_zakdet.ID = okb_db_zadan.ID_zakdet
													LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_zadan.ID_resurs
													WHERE okb_db_zadan.ID = " . (int) $zadan_ids[$y] . " AND okb_db_resurs.ID IS NOT NULL
													ORDER BY okb_db_resurs.NAME ASC, okb_db_zakdet.OBOZ"));
													
			echo '<tr>'
				,'<td width="280">' . $zadan['OBOZ'] . '</td>'
				,'<td width="280">' . $zadan['NAME'] . '</td>'
				,'<td width="140">' . $zadan['NAME_RESURS'] . '</td>'
				,'<td></td>'
				,'</tr>';
				
			++$x;
		}
		
		$yy = 30 - $x;
		
		for ($y = 0; $y < $yy; ++$y) {
			echo TableEmpty();
		}

		echo '</table></div>';
	}
} else {
	$result = dbquery("SELECT ID_resurs,ID_zakdet,okb_db_zakdet.NAME,okb_db_zakdet.OBOZ,okb_db_resurs.NAME as NAME_RESURS FROM okb_db_zadan
													LEFT JOIN okb_db_zakdet ON okb_db_zakdet.ID = okb_db_zadan.ID_zakdet
													LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_zadan.ID_resurs
													WHERE okb_db_zadan.DATE = " .  $pdate . " AND okb_db_zadan.SMEN = " . $smen . " AND okb_db_resurs.ID IS NOT NULL
													ORDER BY okb_db_resurs.NAME ASC, okb_db_zakdet.OBOZ");

	$zadan_array = array();
	
	while ($row = mysql_fetch_array($result)) $zadan_array[] = $row;
	
	$zadan_count = mysql_num_rows($result);

	for ($i = 0; $i < $zadan_count; $i += 30) {
		echo TableHeader();
	
		$x = 0;
		
		for ($y = $i; $y < $i + 30; ++$y) {
			echo '<tr>'
				,'<td width="280">' . $zadan_array[$y]['OBOZ'] . '</td>'
				,'<td width="280">' . $zadan_array[$y]['NAME'] . '&nbsp;</td>'
				,'<td width="140">' . $zadan_array[$y]['NAME_RESURS'] . '</td>'
				,'<td></td>'
				,'</tr>';
				
			++$x;
		}
		
		$yy = 30 - $x;
		
		for ($y = 0; $y < $yy; ++$y) {
			echo TableEmpty();
		}

		echo '</table></div>';
	}
}

echo '<script>$("a[title=œÂ˜‡Ú¸]").attr("href", "/print.php?do=show&formid=219&p1=' . $_GET['p1'] . '&p0=' . $_GET['p0'] . '&zadan_ids=' . $_GET['zadan_ids'] . '");</script>';

?>

</center>
