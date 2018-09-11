<style> 
<!--
table.PageTable {
	BORDER : black 2px solid;
        COLOR : #000;
        BORDER-COLLAPSE : collapse;
        Text-Align : center;
	Vertical-Align : middle;
	width: 1420px;
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
-->
</style>

<?php

$url = "index.php?do=show&formid=99";

echo "Отчёт от ".date("d.m.Y H:i",mktime())."<br><br>";

echo "<table class='PageTable' cellpadding='0' cellspacing='0'>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET["id"])) {

	function OpenID($item,$n) {
		global $page_url, $db_prefix, $ID_zak, $editingzak, $opened, $bk, $operitems_url, $max_n;
		
		$ord = FVal($item,"db_zakdet","ORD");
		
		$cnt = FVal($item,"db_zakdet","COUNT");
		if ($item["LID"]!=="0") {
			$LITEM = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID='".$item["LID"]."')");
			$item = mysql_fetch_array($LITEM);
		}

	   // Цвет
		echo "<tr>";

	   // №
		for ($j=0;$j < $max_n;$j++) {
			if ($n!==$j) echo "<td style='width: 15px;'>&nbsp;</td>";
			if ($n==$j) echo "<td style='width: 15px;' class='first'><i>".$ord."</i></td>";
		}

	   // Наименование
		echo "<td><i>".FVal($item,"db_zakdet","NAME")."</i></td>";
		echo "<td><i>".FVal($item,"db_zakdet","OBOZ")."</i></td>";
		if ($n==0) $cnt = "";
		echo "<td style='background: #$cl;'><i>".$cnt."</i></td>";
		echo "<td style='width: 15px;'><i>".FVal($item,"db_zakdet","RCOUNT")."</i></td>";

		echo "</tr>\n";

	   // Вывод child
		if ($n==0) {
		$xx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
		while($res = mysql_fetch_array($xx)) {
			OpenID($res,$n+1);
		}
		}
	}


	function OpenMTK_ID($item) {
		global $page_url, $db_prefix, $ID_zak, $editingzak, $opened, $url, $itog_1, $itog_2, $itog_3, $today_0;

		$itog_1 = $itog_1 + $item["NORM"];
		$itog_2 = $itog_2 + $item["NORM_2"];
		$itog_3 = $itog_3 + $item["NORM_ZAK"];

	   // Цвет
		echo "<tr>";

			$OPER = dbquery("SELECT * FROM ".$db_prefix."db_oper where  (ID='".$item["ID_oper"]."')");
			$OPER = mysql_fetch_array($OPER);

		$per_7_txt = '';
		$result_7 = dbquery("SELECT TXT FROM ".$db_prefix."db_mtk_perehod where (ID_operitems = '".$item["ID"]."')");
		while($per_7 = mysql_fetch_row($result_7)){
			$per_7_txt = $per_7_txt.$per_7[0]."<br>";
		}
		
		echo "<td><i>".FVal($item,"db_operitems","ORD")."</i></td>";
		echo "<td style='width: 150px;'><i>".FVal($OPER,"db_oper","TID")."</i></td>";
		echo "<td><i><b>".FVal($OPER,"db_oper","NAME")."</b></i></td>";
		echo "<td><i>".FVal($item,"db_operitems","ID_park")."</i></td>";
		echo "<td><i>".FVal($item,"db_operitems","NORM")."</i></td>";
		echo "<td><i>".FVal($item,"db_operitems","NORM_2")."</i></td>";
		echo "<td><i>".FVal($item,"db_operitems","NORM_ZAK")."</i></td>";
		echo "<td><i>".$per_7_txt."</i></td>";


			$Resurses = "";
			$ResNORM = array();
			$ResFACT = array();
			$ResCOUNT = array();
			$ResIDS = array();
			$zadres = dbquery("SELECT ID_resurs, NORM_FACT, NUM_FACT, FACT, DATE, SMEN FROM ".$db_prefix."db_zadan where  (ID_operitems='".$item["ID"]."') and (DATE<='".DateToInt($today_0)."') and (EDIT_STATE='1')");
			while ($zad = mysql_fetch_array($zadres)) {
				if (!in_array($zad["ID_resurs"],$ResIDS)) {
					$ResIDS[] = $zad["ID_resurs"];
					$ResNORM[$zad["ID_resurs"]] = 0;
					$ResFACT[$zad["ID_resurs"]] = 0;
				}
				$ResNORM[$zad["ID_resurs"]] = $ResNORM[$zad["ID_resurs"]] + 1*$zad["NORM_FACT"];
				$ResFACT[$zad["ID_resurs"]] = $ResFACT[$zad["ID_resurs"]] + 1*$zad["FACT"];
				$ResCOUNT[$zad["ID_resurs"]] = $ResCOUNT[$zad["ID_resurs"]] + 1*$zad["NUM_FACT"];
			}
			for ($rj=0;$rj < count($ResIDS);$rj++) {
				$resurs = dbquery("SELECT NAME, ID FROM ".$db_prefix."db_resurs where  (ID='".$ResIDS[$rj]."')");
				$resurs = mysql_fetch_array($resurs);
				if (($ResNORM[$ResIDS[$rj]]*1>0) or ($ResCOUNT[$ResIDS[$rj]]*1>0) or ($ResFACT[$ResIDS[$rj]]*1>0)) $Resurses = $Resurses."<tr><td><a class='acl' href='".$url."&p0=".$ResIDS[$rj]."&p1=".$item["ID"]."'><i>".$resurs["NAME"]."</i></a></td><td style='text-align: center; width: 40px;'><i>".$ResNORM[$ResIDS[$rj]]."</i></td><td style='text-align: center; width: 40px;'><i>".$ResCOUNT[$ResIDS[$rj]]."</i></td><td style='text-align: center; width: 40px;'>".$ResFACT[$ResIDS[$rj]]."</td></tr>";
			}

		$koop = dbquery("SELECT
    oper_id,
    count,
     norm_hours, comment
    FROM `okb_db_operations_with_coop_dep` WHERE oper_id = " . $item ['ID'] . "
	");
		
		$koop_text = '<table>';
		
		while ($row_koop = mysql_fetch_assoc($koop)) {
			if (!empty($row_koop['oper_id'])) {
				$koop_text .= '<tr><td><a href="/index.php?do=show&formid=126&p3=' .  $row_koop['oper_id'] . '">Кооперация</a></td><td>' . round($row_koop['norm_hours'], 2) . '</td><td>' . $row_koop['count'] . '</td><td>' . $row_koop['comment'] . '</td></tr>';
			}
		}
		
		$koop_text .= '</table>';
		
		

		echo "<td style='padding: 0px;'>
		"  . $koop_text . "
		<table border='0' cellpadding='0' cellspacing='0' width='100%' style='BORDER-COLLAPSE : collapse; padding : 0px; margin : 0px; BORDER : 0px;'>".$Resurses."</table></td>";
		echo "<td style='border-right: 0px;'></td>";

		echo "</tr>\n";
	}

	$max_n = 0;	
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$id."')");
	if ($res = mysql_fetch_array($xxx)) {

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
		if ($xxx = mysql_fetch_array($xxx)) $max_n = 1;
		$max_n = $max_n + 1;
	
		$ID_zak = $res["ID_zak"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$ID_zak."')");
		$zak = mysql_fetch_array($xxx);	

	$name = FVal($zak,"db_zak","TID")." ".$zak["NAME"];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>";
		echo "<td colspan='".(1+$max_n)."' style='border-bottom: 2px solid black; text-align: center; padding: 10px;'><b style='font-size: 18pt;'>ЗАКАЗ № ".$name."</b></td>";
		echo "<td colspan='3' style='border: 2px solid black; vertical-align: middle; text-align: center;'>".TodayDate()."</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td rowspan='2' colspan='".$max_n."' style='border: 2px solid black; vertical-align: middle; text-align: center; width: ".(15*$max_n)."px;'>№</td>\n";
		echo "<td rowspan='2' style='border: 2px solid black; vertical-align: middle; text-align: center;' width='900'>Наименование ДСЕ</td>\n";
		echo "<td rowspan='2' style='border: 2px solid black; vertical-align: middle; text-align: center;'>№ чертежа ДСЕ</td>\n";
		echo "<td colspan='2' style='border: 2px solid black; vertical-align: middle; text-align: center;'>Кол-во<br>ДСЕ</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width='40' style='border: 2px solid black; vertical-align: middle; text-align: center;'>На ед.</td>\n";
		echo "<td width='40' style='border: 2px solid black; vertical-align: middle; text-align: center;'>На заказ</td>\n";
	echo "</tr>";

	OpenID($res,0);

	echo "<tr>";
		echo "<td colspan='".(4+$max_n)."' style='border-top: 2px solid black; text-align: center; padding: 10px;'><b style='font-size: 18pt;'>Маршрутно-технологическая карта</b></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='".(4+$max_n)."' style='text-align: center; padding: 0px; margin: 0px; border: 0px;'>";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='BORDER-COLLAPSE : collapse; padding : 0px; margin : 0px; BORDER : 0px;'>";

		echo "<tr>";
		echo "<td style='border-left: 0px; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' width='20'>№</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' colspan='2' width='350'>Операция</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' width='160'>Оборудование</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' width='50'>Норма на ед., мин</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' width='50'>Норма п. з., мин</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' width='50'>На заказ, Н/Ч</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;' width='340'>Параметры</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; vertical-align: middle; text-align: center;'>Исполнитель | Н/Ч | шт | ч</td>\n";
		echo "<td style='border-left: 2px solid black; border-bottom: 2px solid black; border-right: 0px; vertical-align: middle; text-align: center;' width='80'>Отметка<br>ОТК</td>\n";
		echo "</tr>";


	$itog_1 = 0;
	$itog_2 = 0;
	$itog_3 = 0;

	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$res["ID"]."') order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenMTK_ID($res);
	}

	echo "<tr>";
	echo "<td colspan='4' style='border-left: 0px; border-top: 2px solid black; border-bottom: 0px; text-align: right;'><b>Итого:</b></td>";
	echo "<td style='border-left: 2px solid black; border-top: 2px solid black; border-bottom: 0px;'><i>".$itog_1."</i></td>";
	echo "<td style='border-left: 2px solid black; border-top: 2px solid black; border-bottom: 0px;'><i>".$itog_2."</i></td>";
	echo "<td style='border-left: 2px solid black; border-top: 2px solid black; border-bottom: 0px;'><i>".$itog_3."</i></td>";
	echo "<td colspan='3' style='border-left: 2px solid black; border-top: 2px solid black; border-right: 0px; border-bottom: 0px;'></td>";
	echo "</tr>";

		echo "</table>";
		echo "</td>";
	echo "</tr>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "</table>";

?>
