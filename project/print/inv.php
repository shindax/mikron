<style>
<!--

div.mark {
	display: block;
	width: 320px;
	margin: 1px;
	padding: 4px;
	float: left;
	border: 1px solid black;
}

#mark {
	border-collapse: collapse;
	border: 1px solid black;
	width: 330px;
	float: left;
	margin: 1px;
	border-spacing: 0px;
}

#mark tr {
	border: 0px solid black;
	padding: 0px;
	margin: 0px;
}

#mark td {
	border: 0px solid black;
	padding: 4px;
	margin: 0px;
	vertical-align: top;
	text-align: left;
}

#mark td.AR {
	text-align: right;
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

* {
	font-family: Arial;
}

-->
</style>
<center>
	<div id='Printed' class='a4p'>

<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function xstr($txt,$x) {
		$res = substr($txt,0,$x)."";
		if ($res!==$txt."") $res = $res."...";
		return $res;
	}

	$inv_cat = $_GET['p0'];

	$inv_cat_IDs = Array();

	$inv_cat_IDs[] = $inv_cat;

	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_inv_cat where (PID='".$inv_cat."')");
	while($res = mysql_fetch_array($xxx)) {
		$inv_cat_IDs[] = $res["ID"];
	}

	$where = "((ID_inv_cat='".implode("') or (ID_inv_cat='",$inv_cat_IDs)."'))";

		$x = 0;
	echo "<table style='width: 100%;'><tr><td>";

	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_inv where ".$where." order by INV");
	while($res = mysql_fetch_array($xxx)) {
		$x = $x + 1;

		echo "<table id='mark'><tr><td style='padding: 12px 0px 0px 8px;'><b style='font-size: 12pt;'>ОКБ Микрон</b><br><b style='font-size: 10pt;'>".$res["INV"]."</b></td><td class='AR'><span class='CODE39'>*iv".$res["INV"]."*</span></td></tr><tr><td colspan='2'><b>".xstr($res["NAME"],44)."</b><br>Модель: ".xstr($res["MODEL"],32)."<br>Зав. №: ".xstr($res["ZAVNUM"],32)."</td></tr></table>\n";

		if ($x==42) {
			$x = 0;
			echo "</td></tr></table></div><div id='Printed' class='a4p'><table style='width: 100%;'><tr><td>";
		}

	}

	echo "</td></tr></table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


?>
	</div>
</center>