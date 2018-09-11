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
-->
</style>

<?php

echo "Отчёт от ".date("d.m.Y H:i",mktime())."<br><br>";

echo "<table class='PageTable' cellpadding='0' cellspacing='0'>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET["id"])) {

	function OpenID($item,$n,$c,$cl) {
		global $page_url, $db_prefix, $ID_zak, $editingzak, $opened, $bk, $operitems_url, $max_n, $SCount;
		
		$ord = FVal($item,"db_zakdet","ORD");
		$count = $c*$item["COUNT"];
		$SCount = $SCount + $count;
		$cnt = FVal($item,"db_zakdet","COUNT");
		if ($n==0) $cnt = "";

		$isclds = false;
		$xx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
		if (mysql_num_rows($xx)>0) {
			$SCount = $SCount - $count;
			$isclds = true;
		}

		if ($item["LID"]!=="0") {
			$LITEM = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID='".$item["LID"]."')");
			$item = mysql_fetch_array($LITEM);
			$cl = "ddd";
		}

	   // Цвет
		echo "<tr>";

	   // №
		for ($j=0;$j < $max_n;$j++) {
			if ($n!==$j) echo "<td style='width: 15px;'></td>";
			if ($n==$j) echo "<td class='first' style='width: 15px;'><i>".$ord."</i></td>";
		}

	   // Наименование
		if (!$isclds) {
			echo "<td style='background: #$cl;'><i>".FVal($item,"db_zakdet","NAME")."</i></td>";
			echo "<td style='background: #$cl;'><i>".FVal($item,"db_zakdet","OBOZ")."</i></td>";
			echo "<td style='background: #$cl;'><i>".$cnt."</i></td>";
		}
		if ($isclds) {
			echo "<td style='background: #$cl;'><i><b>".FVal($item,"db_zakdet","NAME")."</b></i></td>";
			echo "<td style='background: #$cl;'><i><b>".FVal($item,"db_zakdet","OBOZ")."</b></i></td>";
			echo "<td style='background: #$cl;'><i><b>".$cnt."</b></i></td>";
		}

		echo "</tr>\n";

	   // Вывод child

		while($res = mysql_fetch_array($xx)) {
			OpenID($res,$n+1,$count,$cl);
		}
	}

	function OpenID_N($item,$n) {
		global $db_prefix, $ID_zak, $editingzak, $opened, $max_n;
		
		if ($n>$max_n) $max_n = $n;

		$xx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
		while($res = mysql_fetch_array($xx)) {
			OpenID_N($res,$n+1);
		}
	}

	$max_n = 0;	
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$id."')");
	if ($res = mysql_fetch_array($xxx)) {

		OpenID_N($res,0);
		$max_n = $max_n + 1;
	
		$ID_zak = $res["ID_zak"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$ID_zak."')");
		$zak = mysql_fetch_array($xxx);

		$name = FVal($zak,"db_zak","TID")." ".$zak["NAME"];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>";
		echo "<td colspan='".(3+$max_n)."' style='border-bottom: 2px solid black; text-align: center;'><b style='font-size: 18pt;'>ЗАКАЗ № ".$name."</b></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='".$max_n."' style='border: 2px solid black; vertical-align: middle; text-align: center;'>№</td>\n";
		echo "<td style='border: 2px solid black; vertical-align: middle; text-align: center;'>Наименование ДСЕ</td>\n";
		echo "<td width='180' style='border: 2px solid black; vertical-align: middle; text-align: center;'>№ чертежа ДСЕ</td>\n";
		echo "<td width='50' style='border: 2px solid black; vertical-align: middle; text-align: center;'>Кол-во ДСЕ на ед.</td>";
	echo "</tr>";

	$SCount = 0;
	OpenID($res,0,1,"fff");
	if ($res["COUNT"]*1>0) $SCount = $SCount/$res["COUNT"];

	echo "<tr>";
		echo "<td colspan='".(2+$max_n)."' style='border-top: 2px solid black; text-align: right;'><b>ИТОГО деталей в сборке:</b></td>";
		echo "<td style='border-top: 2px solid black; text-align: center;'>".$SCount."</td>";
	echo "</tr>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "</table>";

?>
