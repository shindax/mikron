<style> 
<!--
#PageTable {
	BORDER : black 2px solid;
        COLOR : #000;
        BORDER-COLLAPSE : collapse;
        Text-Align : center;
	Vertical-Align : middle;     
}
#PageTable TD {
	BORDER : black 1px solid;
	PADDING-RIGHT : 4px;
	PADDING-LEFT : 6px;
	PADDING-BOTTOM : 2px;
	PADDING-TOP : 1px;
	font : normal 12pt "Times New Roman" Arial Verdana;
	height : 19px;
	text-align: center;
}
#PageTable TD.first {
	Text-Align : left;
	background : #ddd;
}
.low1 {font : normal 8pt "Times New Roman" Arial Verdana;}
.low2 {font : normal 7pt "Times New Roman" Arial Verdana;}
.low3 {font : normal 6pt "Times New Roman" Arial Verdana;}
#PageTable TR.top TD {
	BORDER : black 1px solid;
        BORDER-BOTTOM : black 2px solid;
	PADDING-BOTTOM : 2px;
	font : bold 12pt "Times New Roman" Arial Verdana;
	background : #bbb;
}
#PageTable TR.center TD {
	BORDER : black 2px solid;
	font : bold 12pt "Times New Roman" Arial Verdana;
	background : #bbb;
}
#PageTable TD.num {
	BORDER-right : black 2px solid;
	background : #bbb;
}
#PageTable TR.bottom TD {
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
<div><div>
<?php
	if ($print_mode == "off") {
		$rxxx = dbquery("SELECT ID_krz, ID FROM ".$db_prefix."db_krzdet where (ID='".$_GET["id"]."')");
		$dxxx = mysql_fetch_array($rxxx);
		echo "<div class='links'>";
		echo "<a href='index.php?do=show&formid=7&id=".$dxxx["ID_krz"]."'>Назад в КРЗ</a><br>";
		echo "</div>";
	}

	if (($doform) && ($print_mode == "off")) 
	{
		$prturl = str_replace ("index.php","print.php", $pageurl);
		echo "<center><input type='submit' value='Пересчитать'><input type='submit' value='Печать' onClick='submit_btn(\"$prturl\",\"form1\");'></center>";
	} else {
		echo "<br><br>";
	}
?>


<?php echo $print_H; ?>
	<table ID='PageTable' style='background: #fff;' border='0' cellpadding='0' cellspacing='0' width='990'>