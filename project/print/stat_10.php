<?php

echo "<H2>������������ ������ � ������</H2>";


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ����� ������ ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function OpenZakID($item,$n) {
		global $db_prefix;
		
		echo "<tr>";
		echo "<td class='Field AL'>".$n."</td>";

	   // ��� ������
		echo "<td class='Field AL'>".FVal($item,"db_zak","TID")."</td>";

	   // � ������
		echo "<td class='Field AL'>".$item["NAME"]."</td>";

	   // ������������ ���
		echo "<td class='Field AL'>".$item["DSE_NAME"]."</td>";

	   // ��������
		echo "<td class='Field AL'>".FVal($item,"db_zak","ID_clients")."</td>";

	   // ����� �/�
		echo "<td class='Field'>".FVal($item,"db_zak","SUMM_N")."</td>";
		$norms = $norms+$dd;

	   // ������� �/�
		echo "<td class='Field'>".FVal($item,"db_zak","SUMM_NO")."</td>";

	   // ��������� %
		echo "<td class='Field'>".FVal($item,"db_zak","SUMM_V")."</td>";

	   // ���� �������
		echo "<td class='Field'>".FVal($item,"db_zak","DATE")."</td>";

	   // ���� ��������� ��-��
		$values = explode("|",$item["PD8"]);
		$numval = count($values)-1;
		$lastval = $values[$numval];
		if ($lastval=="") $lastval = "##";
		$lastval = explode("#",$lastval);

		echo "<td class='Field'>".$lastval[0]."</td>";

	   // ���� ��������
		echo "<td class='Field'><b>".FVal($item,"db_zak","DATE_PLAN")."</b></td>";

	   // �������������
		echo "<td class='Field'>".FVal($item,"db_zak","ID_users2")."</td>";


		echo "</tr>\n";
	}

   // ����� ������� ///////////////////////////////////////////////////////////////

	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "<thead>";
	echo "<tr class='first'>\n";
	echo "<td width='35'>�</td>\n";
	echo "<td width='35'>���<br>������</td>\n";
	echo "<td width='80'>� ������</td>\n";
	echo "<td>������.<br>������</td>\n";
	echo "<td>��������</td>\n";
	echo "<td>�����<br>�/�</td>\n";
	echo "<td>���.<br>�/�</td>\n";
	echo "<td>���.<br>%</td>\n";
	echo "<td>���� �������</td>\n";
	echo "<td>���� ��������� ��-��</td>\n";
	echo "<td><b>���� ��������</b></td>\n";
	echo "<td width='120'>�������������</td>\n";
	echo "</tr>\n";
	echo "</thead>";

	echo "<tbody>";

	$nn = 1;
   // ���� ������� ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (EDIT_STATE='0') and (ID_clients<>'23') order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenZakID($res,$nn);
		$nn = $nn + 1;
	}

	echo "</tbody>";
	echo "</table>";
?>