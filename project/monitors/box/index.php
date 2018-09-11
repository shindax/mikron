<?php
	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
	Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
	Header("Pragma: no-cache"); // HTTP/1.1 
	Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
</head>
<style> 
<!--
body {
	padding: 0px;
	margin: 0px;
	background: #ccd5e8;
}
#PageTable {
	BORDER: 2px solid black;
        COLOR: #000;
        BORDER-COLLAPSE: collapse;
        Text-Align: left;
	padding: 0px;
	margin: 0px;
}
#PageTable TD {
	BORDER : 1px solid black;
	PADDING : 10px;
	height : 50px;
	background: #fff;
	Vertical-Align: top;
	font: normal 12pt Verdana;
}
#PageTable TR.first TD {
	background: #adbad8 URL(img/bgtop.gif) repeat-x;
	PADDING : 10px;
	font : bold 16pt Verdana;
	BORDER : black 2px solid;
	color: #fff;
	Vertical-Align: middle;
        Text-Align: center;
}
#PageTable TD .RED {
        color: red;
}

-->
</style>
<body scroll="no">
<table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr class='first'>
	<td style='width: 180px;'>Ф.И.О.</td>
	<td>Проект в работе</td>
	<td>Проект в производстве</td>
	<td style='width: 180px;'>Просрочка по проекту</td>
	<td style='width: 180px;'>Технологичность<br>Корректность<br>Ошибки</td>
	</tr>

<?php

	$url = "http://kto-009/monitor/data.txt";
	echo implode("",file($url));
?>


</table>
</body>
</html>