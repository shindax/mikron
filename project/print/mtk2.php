<style> 
<!--
table.PageTable {
	BORDER : black 2px solid;
        COLOR : #000;
        BORDER-COLLAPSE : collapse;
        Text-Align : center;
	Vertical-Align : middle;
	width: 1000px;
	background: #fff;
}
table.PageTable TD {
	BORDER : black 1px solid;
	PADDING-RIGHT : 4px;
	PADDING-LEFT : 6px;
	PADDING-BOTTOM : 2px;
	PADDING-TOP : 2px;
	height : 19px;
	text-align: left;
	vertical-align: top;
}

table.PageTable TD a {
	text-decoration: none;
	color: #000;
}
table.PageTable TD a:hover {
	color: blue;
}
table.PageTable TR * {
	font-size : 12pt;
	font-family: "Times New Roman";
}
table.PageTable TD.first {
	Text-Align : left;
	background : #ddd;
}
.low1 {font : normal 8pt "Times New Roman" Arial Verdana;}
.low2 {font : normal 7pt "Times New Roman" Arial Verdana;}
.low3 {font : normal 6pt "Times New Roman" Arial Verdana;}
table.PageTable TR.top TD {
	BORDER : black 1px solid;
        BORDER-BOTTOM : black 2px solid;
	PADDING-BOTTOM : 2px;
	font : bold 12pt "Times New Roman" Arial Verdana;
	background : #bbb;
}
table.PageTable TR.center TD {
	BORDER : black 2px solid;
	font : bold 12pt "Times New Roman" Arial Verdana;
	background : #bbb;
}
table.PageTable TD.num {
	BORDER-right : black 2px solid;
	background : #bbb;
}
table.PageTable TR.bottom TD {
	BORDER : black 1px solid;
        BORDER-TOP : black 2px solid;
	PADDING-BOTTOM : 2px;
        Text-Align : center;
	Vertical-Align : middle;
	font : bold 12pt "Times New Roman" Arial Verdana;
}
input.colored {background : #fbb;}
H6 {FONT : bold 6pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
H5 {FONT : bold 8pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
H4 {FONT : bold 10pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
H3 {FONT : bold 12pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
H2 {FONT : bold 16pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
H1 {FONT : bold 20pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
td.TAL {text-align: left;}
td.TAR {text-align: right;}
a.acl {
text-decoration: none;
color: black;
}
a.acl:hover {
text-decoration: none;
color: blue;
}
-->
</style>

<?php

$url = "index.php?do=show&formid=64";

$resurs = dbquery("SELECT * FROM ".$db_prefix."db_resurs where  (ID='".$_GET["p0"]."')");
$resurs = mysql_fetch_array($resurs);

$operitems = dbquery("SELECT * FROM ".$db_prefix."db_operitems where  (ID='".$_GET["p1"]."')");
$operitems = mysql_fetch_array($operitems);

$izd = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID='".$operitems["ID_zakdet"]."')");
$izd = mysql_fetch_array($izd);

$zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where  (ID='".$izd["ID_zak"]."')");
$zak = mysql_fetch_array($zak);

$first_dse = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID_zak='".$zak["ID"]."') and (PID='0')");
$first_dse = mysql_fetch_array($first_dse);

$name = FVal($zak,"db_zak","TID")." ".$zak["NAME"];

$zadres = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (ID_operitems='".$_GET["p1"]."') and (ID_resurs='".$_GET["p0"]."') order by DATE, SMEN");

echo "Отчёт от ".date("d.m.Y H:i",mktime())."<br><br>";

echo "<h2>Сменные задания на сотрудника по операции</H2>";

echo "<table class='PageTable' cellpadding='0' cellspacing='0'>";


//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>";
		echo "<td class='first'>Ресурс</td>";
		echo "<td class='TAL'><i>".$resurs["NAME"]."</i></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class='first'>Заказ</td>";
		echo "<td class='TAL'><i><b>".$name."</b> ".$first_dse["NAME"]." - ".$first_dse["OBOZ"]."</i></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class='first'>ДСЕ</td>";
		echo "<td class='TAL'><i>".$izd["NAME"]." - ".$izd["OBOZ"]."</i></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class='first'>№ Операции в МТК</td>";
		echo "<td class='TAL'><i>".FVal($operitems,"db_operitems","ORD")."</i></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class='first'>Операция</td>";
		echo "<td class='TAL'><i>".FVal($operitems,"db_operitems","ID_oper")."</i></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class='first'>На заказ, шт</td>";
		echo "<td class='TAL'><i>".FVal($izd,"db_zakdet","RCOUNT")."</i></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class='first'>На заказ, Н/Ч</td>";
		echo "<td class='TAL'><i>".FVal($operitems,"db_operitems","NORM_ZAK")."</i></td>";
	echo "</tr>";

	echo "</table>";

	echo "<h2>Сменные задания</h2>";

	echo "<table class='PageTable' border='0' cellpadding='0' cellspacing='0'>";

	echo "<tr class='first'>";
		echo "<td>Дата</td>";
		echo "<td>Смена</td>";
		echo "<td>План, шт</td>";
		echo "<td>План, Н/Ч</td>";
		echo "<td>Факт, шт</td>";
		echo "<td>Факт, Н/Ч</td>";
		echo "<td>Факт, ч</td>";
	echo "</tr>";

		$x1=0;
		$x2=0;
		$x3=0;
		$x4=0;
		$x5=0;

	while ($zad = mysql_fetch_array($zadres)) {

	echo "<tr class='first'>";
		echo "<td><a class='acl' href='".$url."&p0=".$zad["DATE"]."&p1=".$zad["SMEN"]."' target='_blank'>".FVal($zad,"db_zadan","DATE")."</a></td>";
		echo "<td>".FVal($zad,"db_zadan","SMEN")."</td>";
		echo "<td>".FVal($zad,"db_zadan","NUM")."</td>";
		echo "<td>".FVal($zad,"db_zadan","NORM")."</td>";
		echo "<td>".FVal($zad,"db_zadan","NUM_FACT")."</td>";
		echo "<td>".FVal($zad,"db_zadan","NORM_FACT")."</td>";
		echo "<td>".FVal($zad,"db_zadan","FACT")."</td>";
	echo "</tr>";

		$x1 = $x1 + $zad["NUM"]*1;
		$x2 = $x2 + $zad["NORM"]*1;
		$x3 = $x3 + $zad["NUM_FACT"]*1;
		$x4 = $x4 + $zad["NORM_FACT"]*1;
		$x5 = $x5 + $zad["FACT"]*1;

	}

	echo "<tr class='first'>";
		echo "<td class='TAR' colspan='2'><b>Итого:</b></td>";
		echo "<td><b>$x1</b></td>";
		echo "<td><b>$x2</b></td>";
		echo "<td><b>$x3</b></td>";
		echo "<td><b>$x4</b></td>";
		echo "<td><b>$x5</b></td>";
	echo "</tr>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "</table>";

?>
