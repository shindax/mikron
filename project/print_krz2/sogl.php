<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$ID_krz2 = $_GET["id"];
$show = false;

	$item = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where  (ID='".$ID_krz2."')");
	if ($item = mysql_fetch_array($item)) {
		$show = true;
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where (ID_krz2 = '".$ID_krz2."') and (PID = '0')");
		$dse = mysql_fetch_array($xxx);
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


echo "
<style>
	b {font-size: 12pt;}
	td.Field {padding-top: 5px; padding-bottom: 5px;}
	tr.first td.Field {padding: 3px;}
</style>
<br>
<H2 style='text-align: center; padding: 0px; margin: 0px;'>���� ������������ ".FVal($item,"db_krz2","NAME")."</H2><br><center>\"___\" __________ 20__ �</center><br><br>
<table style='border: 0px; width: 100%;'>
<tr>
<td width='350px;'></td>
<td style='font-size: 12pt; text-align: left;'>
��������: <b>".FVal($item,"db_krz2","ID_clients")."</b><br>
������������ �������: <b>".FVal($dse,"db_krz2det","NAME")."</b><br>
����������: <b>".FVal($dse,"db_krz2det","COUNT")." ��</b><br>
� ���: <b>".FVal($item,"db_krz2","NAME")."</b> &nbsp; &nbsp; &nbsp; 
� �������: <b>".FVal($dse,"db_krz2det","OBOZ")."</b><br>
����������: ".FVal($item,"db_krz2","MORE")."<br>".FVal($item,"db_krz2","MORE_EXPERT")."
</td>
</tr>
</table>
<br>

";

echo "<table class='tbl' border='0' cellpadding='0' cellspacing='0' width='100%'>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr class='first'>\n";
	echo "<td class='Field' width='15%' rowspan='2'>���������</td>\n";
	echo "<td class='Field' width='14%' rowspan='2'>�.�.�.</td>\n";
	echo "<td class='Field' width='13%' rowspan='2'>�������</td>\n";
	echo "<td class='Field' colspan='2' rowspan='2'>���� ������ ������<br>��� ����������� ������</td>\n";
	echo "<td class='Field' colspan='2'>������������</td>\n";
	echo "<td class='Field' width='11%' rowspan='2'>����������</td>\n";
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td class='Field' width='12%'>����</td>\n";
	echo "<td class='Field' width='11%'>�����</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>��������</td>\n";
	echo "<td class='Field'>����� �.�.</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' colspan='2'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>�����������<br>��������</td>\n";
	echo "<td class='Field'>����� �.�.</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' colspan='2'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='4'>�����������<br>�������������<br>���������</td>\n";
	echo "<td class='Field' rowspan='4'>������� �.�.</td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "<td class='Field' class='mini' width='12%'>�������� ����<br>�������� ���.</td>\n";
	echo "<td class='Field' class='mini' width='12%'></td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>����������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>�����. ������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>��������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>���������<br>������������</td>\n";
	echo "<td class='Field'>��������� �.�.</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='2'>��������� ���</td>\n";
	echo "<td class='Field' rowspan='2'>������� �.�.</td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' class='mini'>������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>���������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='2'>��������� ���</td>\n";
	echo "<td class='Field' rowspan='2'>��������� �.�.</td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' class='mini'>����������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>��������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='2'>��������� ����</td>\n";
	echo "<td class='Field' rowspan='2'>����������� �.�.</td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' class='mini'>����������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>��������</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='3'>���. �� �� ���������� ������������</td>\n";
	echo "<td class='Field' rowspan='3'>�������� �.�.</td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "<td class='Field' class='mini'>��</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>��</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>���</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>���������</td>\n";
	echo "<td class='Field'>".FVal($item,"db_krz2","ID_users")."</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' colspan='2'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "</table>";

echo "<br><center><b>�������������� ����������</b></center><br>";

echo "<table class='tbl' border='0' cellpadding='0' cellspacing='0' width='100%'>";

	echo "<tr class='first'>\n";
	echo "<td class='Field' width='200'>������������</td>\n";
	echo "<td class='Field' width='120'>�������������</td>\n";
	echo "<td class='Field'>��������������</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>���������� ��������</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>���������� ������������</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>���������</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>�������</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>��������</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'><br></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'><br></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'><br></td>\n";
	echo "</tr>\n";



echo "</table>";

?>