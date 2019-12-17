<script>
if(!window.jQuery)
	document.write(unescape('<script type="text/javascript" src="uses/jquery.js">%3C/script%3E'));
</script>


<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Ìèðîøíèêîâ À.Â.
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);

	$print_mode = "on";

	include "start.php";

	if ($use_gzip) gzip_start();

/////////////////////////////////////////////////////////////////////////////////////
//
// BODY
//
/////////////////////////////////////////////////////////////////////////////////////

//echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<HTML>
<HEAD>
	<TITLE>$title</TITLE>
	<meta http-equiv='Content-Type' content='text/html; charset=".$html_charset."'>
	<LINK rel='stylesheet' href='style/style.css' type='text/css'>
	<LINK rel='stylesheet' href='style/print.css' type='text/css'>
</HEAD>
<script language=\"javascript\">
<!--
	window.onload = function() {
		window.print();
	}
-->
</script>";
if ($_GET['formid']=='18') echo "<BODY style='background: #fff;'>";
if ($_GET['formid']!=='18') echo "<BODY onClick=\"window.close();\" style='background: #fff;'>";

if ($copy_state) echo "<img style='position: fixed; left: 100px; top: 100px;' src='style/copy.gif'>\n";

echo "\n<!-- Viewport -->\n

";
	if ($user==0) {
	} else {
		include "includes/do_".$do.".php";
	}

echo "
</BODY>
</HTML>
";

	if ($use_gzip) gzip_output();
?>

<script>
// Replace all inputs with spans. Chrome 72 beta ñrutch.
if( location.href.indexOf('print.php') != -1 )
{
		$('input').each(function( index, value ) 
							{
								$( value ).replaceWith("<span>" + $( value ).val() + "</span>");
							});

		$('div[id^="newitr_"]').hide()
}
	
</script>
