<?php

$my = explode(".",$_GET["p0"]);
$YY = $my[2];
$MM = $my[1];
$date_start = ($YY-1)*10000+$MM*100+32;
$date_end = $YY*10000+$MM*100+32;

$DI_MName = Array('������','�������','����','������','���','����','����','������','��������','�������','������','�������');

$MMN = $MM + 1;
if ($MMN>12) $MMN = 1;

$MM = $DI_MName[$MM-1];
$MMN = $DI_MName[$MMN-1];

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	�������� ������ ID
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$vo_ids = Array();
	$vo_dates = Array();
	$vo_count = Array();
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (TID='15') and (DATE>'".$date_start."') and (DATE<'".$date_end."') order by DATE");
	while($tab = mysql_fetch_array($xxx)) {
		$vo_ids[] = $tab["ID_resurs"];

		$dates = $vo_dates[$tab["ID_resurs"]]."";
		if ($dates == "") {
			$dates = IntToDate($tab["DATE"]);
		} else {
			$dates = $dates.", ".IntToDate($tab["DATE"]);
		}

		$vo_dates[$tab["ID_resurs"]] = $dates;
		$vo_count[$tab["ID_resurs"]] = $vo_count[$tab["ID_resurs"]]*1+1;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if ($print_mode == "on") echo "����� �� ".date("d.m.Y H:i",mktime());

		echo "<h2>� ���� ���������� �������</h2><h4>".$MMN." ".($YY-1)." - ".$MM." ".$YY." �</h4>";

		echo "<table class='rdtbl tbl' style='width: 900px;' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>";
			echo "<td width='200'>������</td>";
			echo "<td width='100'>����� ��</td>";
			echo "<td>���� ��</td>";
		echo "</tr>";
		echo "</thead>";

		echo "<tbody>";

			
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary(NAME)");
		while($res = mysql_fetch_array($xxx)) {
			if (in_array($res["ID"],$vo_ids)) {
				echo "<tr>";
					echo "<td class='Field'>".$res["NAME"]."</td>";
					echo "<td class='Field'>".$vo_count[$res["ID"]]."</td>";
					echo "<td class='Field'>".$vo_dates[$res["ID"]]."</td>";
				echo "</tr>";
			}
		}


		echo "</tbody>";
		echo "</table>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>