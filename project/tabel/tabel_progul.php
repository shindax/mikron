<?php

$my = explode(".",$_GET["p0"]);
$YY = $my[2];
$MM = $my[1];
$date_start = $YY*10000+$MM*100+0;
$date_end = $YY*10000+$MM*100+32;

$DI_MName = Array('������','�������','����','������','���','����','����','������','��������','�������','������','�������');

$MM = $DI_MName[$MM-1];

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	�������� ������ ID
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$progul_ids = Array();
	$progul_dates = Array();
	$progul_count = Array();
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (TID='6') and (DATE>'".$date_start."') and (DATE<'".$date_end."') order by DATE");
	while($tab = mysql_fetch_array($xxx)) {
		$progul_ids[] = $tab["ID_resurs"];

		$dates = $progul_dates[$tab["ID_resurs"]]."";
		if ($dates == "") {
			$dates = IntToDate($tab["DATE"]);
		} else {
			$dates = $dates.", ".IntToDate($tab["DATE"]);
		}

		$progul_dates[$tab["ID_resurs"]] = $dates;
		$progul_count[$tab["ID_resurs"]] = $progul_count[$tab["ID_resurs"]]*1+1;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if ($print_mode == "on") echo "����� �� ".date("d.m.Y H:i",mktime());

		echo "<h2>�������</h2><h4>".$MM." ".$YY." �</h4>";

		echo "<table class='rdtbl tbl' style='width: 900px;' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td width='200'>������</td>";
			echo "<td width='100'>�����<br>���������</td>";
			echo "<td>���� ���������</td>";
		echo "</tr>";
		echo "</thead>";

		echo "<tbody>";

			
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where TID='0' order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$progul_ids)) {
				echo "<tr>";
					echo "<td class='Field'>".$res["NAME"]."</td>";
					echo "<td class='Field'>".$progul_count[$res["ID"]]."</td>";
					echo "<td class='Field'>".$progul_dates[$res["ID"]]."</td>";
				echo "</tr>";
			}
		}


		echo "</tbody>";
		echo "</table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>