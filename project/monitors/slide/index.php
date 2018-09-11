<?php
	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
	Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
	Header("Pragma: no-cache"); // HTTP/1.1 
	Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");

	function makefilelist($folder, $sort = true, $rsort = false) {
		$res = array(); 
		$temp = opendir($folder);
		while ($file = readdir($temp)) {
                	if (($file!==".") && ($file!=="..")) $res[] = $file;
		}
		closedir($temp);
		if ($sort) sort($res);
		if ($rsort) rsort($res);
		return $res;
	}

	$last = file("last.txt");
	$last = $last[0];

	$files = makefilelist("img/", false, true);
	$next = $files[0];
	$isit = false;

	for ($j=0;$j < count($files);$j++) {
		if ($isit==true) {
			$next = $files[$j];
			$isit = false;
		}
		if ($files[$j] == $last) $isit = true;
	}

	$img = "img/".$next;

	$save=fopen("last.txt","w+");
	fwrite($save,$next);
	fclose($save);


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<TITLE>Slide</TITLE>
</head>
<style>
	body {
		padding: 0px;
		margin: 0px;
		background: #000;
		overflow: hidden;
	}
	table {
		margin: 0px;
		padding: 0px;
		border-collapse: collapse;
		border-spacing: 0px;
		width: 100%;
		height: 100%;
	}
	tr {
		padding: 0px;
		margin: 0px;
		border: 0px;
	}
	td {
		padding: 0px;
		margin: 0px;
		text-align: center;
		vertical-align: middle;
		border: 0px;
	}
	img {
		padding: 0px;
		margin: 0px;
		border: 0px;
		height: 1078px;
	}
</style>
<body>
<table>
<tr>
<td>
<img id='ximg' src='<?php echo $img; ?>'>
</td>
</tr>
</table>
</body>
</html>