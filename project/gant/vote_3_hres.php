<?php

	include "includes.php";


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

$second_txt = "";
$first_txt = "<div style='float: left; width: 250px;'><b>������� ������ �� ��������:</b></div><div style='float: left; margin-left: 20px;'>����/���� � ��, �/�<br>%<br>���� �� ������, �/�</div>";

if (isset($_GET["sel"])) {
	$second_txt = "<div style='float: left; width: 250px;'><b>������� ������ �� ���:</b></div><div style='float: left; margin-left: 20px;'>���� �/�<br>%<br>���� �/�</div>";
	if (substr_count($_GET["sel"], "o")>0) $second_txt = "<div style='float: left; width: 250px;'><b>������� ������ �� ��������:</b><br>���� �/�, %, ���� �/�</div><div style='float: left; margin-left: 20px;'>�������� �/�<br>���� / ������<br>����� �/�</div>";
}

echo "<table>";
echo utftxt("<tr><td class='GNT2' style='padding: 5px 5px 5px 40px; border-top: 0px solid black; border-right: 0px solid black;'>".$first_txt."</td></tr>");
if (isset($_GET["sel"])) echo utftxt("<tr><td class='GNT2' style='border-top: 1px solid black; padding: 5px 5px 5px 40px; border-right: 0px solid black;'>".$second_txt."</td></tr>");
echo "</table>";

?>