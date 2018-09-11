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
	PADDING-RIGHT : 4px;
	PADDING-LEFT : 6px;
	PADDING-BOTTOM : 4px;
	PADDING-TOP : 4px;
	font : normal 12pt "Times New Roman" Arial Verdana;
	height : 19px;
	text-align: left;
	vertical-align: top;
	background: #fff;
}

#PageTable TR.first TD {
	text-align: center;
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
<center>

<?php

// Печать всех сменок разом




//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$smena = $_GET["p1"];
$pdate = $_GET["p0"]*1;
$p_2 = $_GET['p2'];
$date = IntToDate($pdate);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function Show_header($resurs, $list_page_num) {
	global $smena, $date, $print_names;

	echo "
	<div id='Printed' class='a4p'>
	Отчёт от ".date("d.m.Y H:i",mktime())."<b style='float:right;font-size:125%;'>Лист №".$list_page_num."</b>
	<H1 style='margin-left:15px'>Сменное задание \"ОКБ МИКРОН\"</H1>
	<center><b style='font-size:165%'>".$resurs["NAME"]."           ".$smena." смена ".$date."</b><br><br></center>

	<div style='width: 100%; text-align: right; font-size:150%;'>
	 &nbsp;
	</div>

	<table border='0' cellpadding='0' cellspacing='0' width='1000'><tr>
	<td style='text-align: right;'>".$print_names."</td></td></table>
	<br>
	<table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='1000'>
	";

	echo "<tr class='first'>\n";
	echo "<td rowspan='2' width='25'>№</td>\n";
	echo "<td colspan='2'>Операция</td>\n";
	echo "<td rowspan='2' width='80'>Оборуд.</td>\n";
	echo "<td colspan='2'>План</td>\n";
	echo "<td rowspan='2' width='100'>На заказ <b>осталось</b> / всего,<br>Н/Ч (шт) / <br>норма на ед.</td>\n";
	echo "<td colspan='3'>Факт</td>\n";
	echo "<td rowspan='2' width='100'>ОТК</td>\n";
	echo "</tr>\n";


	echo "<tr class='first'>\n";
	echo "<td width='20'>№</td>\n";
	echo "<td>Наименование</td>\n";
	echo "<td width='30'>Кол-во</td>\n";
	echo "<td width='30'>Н/Ч</td>\n";
	echo "<td width='50'>Кол-во</td>\n";
	echo "<td width='50'>Н/Ч</td>\n";
	echo "<td width='50'>Затр. время, ч</td>\n";
	echo "</tr>\n";
}
function Show_header2($resurs, $list_page_num) {
	global $smena, $date, $print_names;

	echo "
	<div id='Printed' class='a4p'>
	Отчёт от ".date("d.m.Y H:i",mktime())."<b style='float:right;font-size:125%;'>Лист №".$list_page_num."</b>

	<center><b style='font-size:165%'>".$resurs["NAME"]."           ".$smena." смена ".$date."</b><br></center>
	<table border='0' cellpadding='0' cellspacing='0' width='1000'><tr>
	<td style='text-align: right;'>".$print_names."</td></td></table>
	<br>
	<table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='1000'>
	";

	echo "<tr class='first'>\n";
	echo "<td rowspan='2' width='25'>№</td>\n";
	echo "<td colspan='2'>Операция</td>\n";
	echo "<td rowspan='2' width='80'>Оборуд.</td>\n";
	echo "<td colspan='2'>План</td>\n";
	echo "<td rowspan='2' width='100'>На заказ <b>осталось</b> / всего,<br>Н/Ч (шт) / <br>норма на ед.</td>\n";
	echo "<td colspan='3'>Факт</td>\n";
	echo "<td rowspan='2' width='100'>ОТК</td>\n";
	echo "</tr>\n";


	echo "<tr class='first'>\n";
	echo "<td width='20'>№</td>\n";
	echo "<td>Наименование</td>\n";
	echo "<td width='30'>Кол-во</td>\n";
	echo "<td width='30'>Н/Ч</td>\n";
	echo "<td width='50'>Кол-во</td>\n";
	echo "<td width='50'>Н/Ч</td>\n";
	echo "<td width='50'>Затр. время, ч</td>\n";
	echo "</tr>\n";
}
function Show_header3($resurs, $list_page_num) {
	global $smena, $date, $print_names;

	echo "
	<div id='Printed' class='a4p'>
	Отчёт от ".date("d.m.Y H:i",mktime())."<b style='float:right;font-size:125%;'>Лист №".$list_page_num."</b>

	<center><b style='font-size:165%'>".$resurs["NAME"]."           ".$smena." смена ".$date."</b><br></center>
	<table border='0' cellpadding='0' cellspacing='0' width='1000'><tr>
	<td style='text-align: right;'>".$print_names."</td></td></table>
	<br>
	<table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='1000'>
	";
}
function Show_footer() {

	echo "
	</table>\n";
// Пустографик
		echo "<br><table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='1000'>";
		echo "<tr class='first'><td>№</td><td>Инициатор</td><td width='70'>Заказ</td><td width='170'>Наименование заказа / изделия</td><td width='170'>Деталь</td><td width='170'>Чертёж</td><td width='170'>Операция</td><td>Кол-во готовых, шт</td><td>Факт Н/Ч</td><td>Затр. время, ч</td></tr>";
		echo "<tr><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		echo "<tr><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		echo "<tr><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		echo "<tr><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		echo "<tr><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td>&nbsp;<br>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		echo "</table>";
	echo "<br>
	<table border='0' cellpadding='0' cellspacing='0' width='1000'>
	<tr>
	<td width='300' style='text-align: left; padding-left: 20px; font-size:140%;'>Задание составил:<br><br>Задание получил:</td>
	<td></td>
	<td width='300' style='text-align: left; padding-left: 20px; font-size:140%;'>Задание выполнил:<br><br>Работу принял:</td>
	</tr>
	</table>
	</div>
	";
}
















$zzz = dbquery("SELECT * FROM ".$db_prefix."db_resurs where ID=".$p_2." order by binary (NAME)");
while ($resurs = mysql_fetch_array($zzz)) {
	
	$plan_p2 = 0;
	$plan_n1 = 0;
	$isstarted = false;
	$zadanresults = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$resurs["ID"]."') and (SMEN = '".$smena."') order by ORD");
	$zadan_counts = dbquery("SELECT COUNT(ID) FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$resurs["ID"]."') and (SMEN = '".$smena."') order by ORD");
	$zadan_c_t = mysql_fetch_row($zadan_counts);
	while ($zadan = mysql_fetch_array($zadanresults)) {

		$plan_n1 = $plan_n1 + (1*$zadan["NORM"]);
		if (!$isstarted) {
			$next_page_num = 1;
			Show_header($resurs, $next_page_num);
			$isstarted = true;
			$row_ind = 0;
		}
		
		$row_ind = $row_ind + 1;
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$zadan["ID_zak"]."')");
		$zak = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$zadan["ID_zakdet"]."')");
		$izd = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$zadan["ID_zak"]."') and (PID = '0')");
		$first_dse = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$zadan["ID_operitems"]."')");
		$oper = mysql_fetch_array($result);

		$zname = $zak["YY"];
		$tid = FVal($zak,"db_zak","TID");
		if ($zak["PID"]!=="0") {
			$yyyy = dbquery("SELECT * FROM ".$db_prefix."db_zak where  (ID='".$zak["PID"]."')");
			$parent = mysql_fetch_array($yyyy);
			$zname = $zname."-".$parent["NAME"];
				if ($parent["TID"]==1) $tid = "ВОЗ";
				if ($parent["TID"]==2) $tid = "ВКР";
				if ($parent["TID"]==3) $tid = "ВСП";
		}
		$zname = $tid." ".$zname." ".$zak["NAME"];

		$zak_zakdet = "<b>".$zname."</b> ".$first_dse["NAME"]."<br><b>".$izd["OBOZ"]." ".$izd["NAME"]."</b>";
		if ($first_dse["ID"] == $izd["ID"]) $zak_zakdet = "<b>".$zname."</b> ".$first_dse["NAME"]."<br><b>".$izd["OBOZ"]." ".$izd["NAME"]."</b>";
		
		if (($row_ind > 6) and ($zadan_c_t[0] > 7)){
			$zadan_c_t[0] = $zadan_c_t[0] - 7;
			$row_ind = 0;
			echo "</table></div>";
			$next_page_num = $next_page_num + 1;
			Show_header2($resurs, $next_page_num);
		}
		echo "<tr>\n";
		echo "<td rowspan='4'>".$zadan["ORD"]."</td>\n";
		echo "<td colspan='6' style='background: #ccc;'>".$zak_zakdet."</td>\n";
		echo "<td colspan='4' style='text-align: center;'><span class='CODE39'>*sz".$zadan["ID"]."*</span></td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		echo "<td colspan='4'>№ склада заготовки: ".FVal($zadan,"db_zadan","CEH1")."</td>\n";
		echo "<td colspan='6'>№ склада изделия: ".FVal($zadan,"db_zadan","CEH2")."</td>\n";
		echo "</tr>\n";


		$ost = 0;
		if ($oper["NORM_ZAK"]>0) $ost = $izd["RCOUNT"]*(($oper["NORM_ZAK"]-$oper["NORM_FACT"])/$oper["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		$ost = "<b>".($oper["NORM_ZAK"]-$oper["NORM_FACT"])." (".$ost.")</b><br>".$oper["NORM_ZAK"]." (".$izd["RCOUNT"].")<br>".round(($oper["NORM"])/(60),2);

		echo "<tr>\n";
		echo "<td>".FVal($oper,"db_operitems","ORD")."</td>\n";
		echo "<td>".FVal($zadan,"db_zadan","ID_operitems")."</td>\n";
		echo "<td>".FVal($zadan,"db_zadan","ID_park")."</td>\n";
		echo "<td>".FVal($zadan,"db_zadan","NUM")."</td>\n";
		echo "<td>".FVal($zadan,"db_zadan","NORM")."</td>\n";
		echo "<td style='text-align: center;'>$ost</td>\n";
		echo "<td></td>\n";
		echo "<td>".FVal($zadan,"db_zadan","NORM_FACT")."</td>\n";
		echo "<td></td>\n";
		echo "<td></td>\n";
		echo "</tr>\n";

		echo "<tr>\n";
		$opermore = "";
		echo "<td colspan='3'>".$opermore."<b>Примечание:</b><br>".FVal($zadan,"db_zadan","MORE")."</td><td colspan='7'><b>Причина невыполнения:</b><br>&nbsp;</td>\n";
		echo "</tr>\n";
		
		/*if (($next_page_num == 1) and ($zadan_c_t[0] == 5)){
			$row_ind = 0;
			echo "<tr><td colspan='11' style='background:#ccc;'>Итого план Н/Ч:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(".$plan_n1.")</b></td></tr>";
			$plan_p2 = 2;
			echo "</table></div>";
			$next_page_num = $next_page_num + 1;
			Show_header3($resurs, $next_page_num);
		}*/
		if (($row_ind == 7) and ($zadan_c_t[0] == 7)){
			$row_ind = 0;
			echo "<tr><td colspan='11' style='background:#ccc;'>Итого план Н/Ч:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(".$plan_n1.")</b></td></tr>";
			$plan_p2 = 2;
			echo "</table></div>";
			$next_page_num = $next_page_num + 1;
			Show_header3($resurs, $next_page_num);
		}
		if (($row_ind == 6) and ($zadan_c_t[0] == 6)){
			$row_ind = 0;
			echo "<tr><td colspan='11' style='background:#ccc;'>Итого план Н/Ч:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(".$plan_n1.")</b></td></tr>";
			$plan_p2 = 2;
			echo "</table></div>";
			$next_page_num = $next_page_num + 1;
			Show_header3($resurs, $next_page_num);
		}
	}
	if ($isstarted) {
		if ($plan_p2 == 0) echo "<tr><td colspan='11' style='background:#ccc;'>Итого план Н/Ч:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(".$plan_n1.")</b></td></tr>";
		Show_footer();
	}
}






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


?>

</center>