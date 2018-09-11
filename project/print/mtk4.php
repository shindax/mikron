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

table.PageTable {
	border:2px solid #000 !important;
}
table.PageTable td {
	border:2px solid #000 !important;
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

	function OpenID($item,$n) {
		global $page_url, $db_prefix, $ID_zak, $editingzak, $opened, $bk, $operitems_url, $max_n;
		
		$ord = FVal($item,"db_zakdet","ORD");
		
		$cnt = FVal($item,"db_zakdet","COUNT");
		if ($item["LID"]!=="0") {
			$LITEM = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID='".$item["LID"]."')");
			$item = mysql_fetch_array($LITEM);
		}

	   // Цвет
		echo "<tr";

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
		global $db_prefix;

		echo "<tr style='height:62px;vertical-align:middle;' valign='middle'>";

		$OPER = dbquery("SELECT * FROM ".$db_prefix."db_oper where  (ID='".$item["ID_oper"]."')");
		$OPER = mysql_fetch_array($OPER);

		echo "<td style='vertical-align:middle;font-size:18pt'><i style='font-size:16pt'>".FVal($item,"db_operitems","ORD")."</i></td>";
		echo "<td style='width: 350px;vertical-align:middle;font-size:16pt'><i style='font-size:16pt'>".FVal($OPER,"db_oper","TID")."</i></td>";
		echo "<td style='vertical-align:middle;font-size:16pt'><i><b style='font-size:16pt'>".FVal($OPER,"db_oper","NAME")."</b></i></td>";
		echo "<td style='vertical-align:middle;font-size:16pt'><i style='font-size:16pt'>&nbsp;</td>";
		echo "</tr>\n";
	}
$url = "index.php?do=show&formid=99";

$ids = explode(',', $_GET['ids']);
$ids = array_values(array_unique($ids, SORT_REGULAR));
$treshold = 900;
$total_height = 490 ;

for ($i = 0; $i < count($ids); ++$i) 
{
  if( $i == 0 )
   {
    $div_class = "pagebreak";
    $total_height = 0 ;    
   }
      else
          if( $total_height >= $treshold )
              {
                $div_class = "pagebreak";
                $total_height = 0 ;
              }
              else
                $div_class = "";


	$id = $ids[$i];
	
	$_GET['id'] = $id;
	
	
//echo ($i % 2 == 0 ? "<div class='pagebreak' style='clear:both'>" . "Отчёт от ".date("d.m.Y H:i",mktime())."<br><br>" : '')."<table class='PageTable'  style='width:1050px;border:2px solid #000' cellpadding='0' cellspacing='0'>";

echo "<div class='$div_class' style='clear:both'>Отчёт от ".date("d.m.Y H:i",mktime())."<br><br>
      <table id='page_table_$i' class='PageTable'  style='width:1050px;border:2px solid #000' cellpadding='0' cellspacing='0'>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET["id"])) 
{

	$max_n = 0;	
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$_GET['id']."')");
	if ($res = mysql_fetch_array($xxx)) {

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (PID='".$item["ID"]."') order by ORD");
		if ($xxx = mysql_fetch_array($xxx)) $max_n = 1;
		$max_n = $max_n + 1;
	
		$ID_zak = $res["ID_zak"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$ID_zak."')");
		$zak = mysql_fetch_array($xxx);	

	$name = FVal($zak,"db_zak","TID")." ".$zak["NAME"];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


			$LITEM = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID='".$res["LID"]."')");
			$item = mysql_fetch_array($LITEM);

				$res2 = $res;

		if ($res["LID"]!=="0") {
			$LITEM = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID='".$item["LID"]."')");
			$res2 = mysql_fetch_array($LITEM);
		}


		//".FVal($res2,"db_zakdet","NAME")."
	echo "<tr>";
		echo "<td colspan='".(1+$max_n)."' style='text-align: center; vertical-align:middle;padding: 25px;width:216px'><b style='font-size: 24pt;'>Чертеж № ".FVal($res2,"db_zakdet","OBOZ")."</b><br/><span style='font-size:16pt'>".FVal($res2,"db_zakdet","NAME")."</span></td>";
		echo "<td colspan='3' style='vertical-align: middle; text-align: left;padding:25px;vertical-align:middle;width:43%;font-size:18pt'>Кол-во</td>";
	echo "</tr>";


	//OpenID($res,0);

	echo "<tr>";
		echo "<td colspan='20' style='text-align: center; padding: 10px;'><b style='font-size: 18pt;'>Маршрутно-технологическая карта</b></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='".(4+$max_n)."' style='text-align: center; padding: 0px; margin: 0px;'>";
		
		echo "<table class='inner_table' border='0' cellpadding='0' cellspacing='0' width='100%' style='BORDER-COLLAPSE : collapse; padding : 0px; margin : 0px; BORDER : 0px;'>";

		
		echo "<tr>";
		echo "<td style='vertical-align: middle; text-align: center;font-size:21pt' width='20'>№</td>\n";
		echo "<td style='vertical-align: middle; text-align: center;font-size:21pt' colspan='2'>Операция</td>\n";
		echo "<td style='vertical-align: middle; text-align: center;width:25%;font-size:21pt'></td>\n";
		echo "</tr>";


	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$res["ID"]."') order by ORD");
	
	$line_count = 0 ;
	
	while($res = mysql_fetch_array($xxx)) 
	{
		OpenMTK_ID($res);
		$total_height += 130 ;
	}

		echo "</table>";
		echo "</td>";
	echo "</tr>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "</table></div><br/><br/>";

}

echo '<script>$("a[title=Печать]").attr("href", "/print.php?do=show&formid=226&p1=' . $_GET['p1'] . '&p0=' . $_GET['p0'] . '&ids=' . $_GET['ids'] . '");</script>';

?>
