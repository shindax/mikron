<?php

$ID_krz2det = $_GET["id"];

	$editingkrz = false;
	$ID_krz2 = "";
	$item = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where  (ID='".$ID_krz2det."')");
	if ($item = mysql_fetch_array($item)) {
		$ID_krz2 = $item["ID_krz2"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where  (ID='".$ID_krz2."')");
		if ($xxx = mysql_fetch_array($xxx)) {
			if (($xxx["EDIT_STATE"]=="0") && ($xxx["EXPERT_STATE"]=="0")) $editingkrz = true;
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ����� ������ ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET["id"])) {


	function OutLine($item,$field,$name,$ed) {
		global $editingkrz;

		echo "<tr>\n";
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='nbg'></td>\n";
		echo "<td class='Field' style='text-align: left'>".$name."</td>\n";
		echo "<td class='Field'></td>\n";
		echo "<td class='Field'>".$ed."</td>\n";
		Field($item,"db_krz2det",$field,$editingkrz,"","","");
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='Field'></td>\n";
		echo "</tr>\n";
	}


	function OpenID($item) {
		global $pageurl, $db_prefix, $ID_krz2det, $editingkrz;
		
	   // ����
		echo "<tr class='cl_1'>";

		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='nbg' width='7'></td>\n";
		//AddTreeField($item,"db_krz2detitems",1);

		Field($item,"db_krz2detitems","NAME",$editingkrz,"","","");

		$edizm = "��";
		$editprice = true;
		if (($item["TID"]!=="1") and ($item["TID"]!=="0")) {
			echo "<td class='Field'></td>\n";
			$edizm = "���";
		} else {
			Field($item,"db_krz2detitems","PRICE",$editingkrz,"","","");
		}

		echo "<td class='Field'>".$edizm."</td>\n";

		Field($item,"db_krz2detitems","COUNT",$editingkrz,"","","");

	   // ��������		
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) DelField($item,"db_krz2detitems",true,1);

		echo "</tr>\n";
	}

	function OutTableTMC($tid,$names) {
		global $db_prefix, $ID_krz2det, $editingkrz, $pageurl;

		echo "<tr height='30'>\n";
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='nbg'><span><a href='".$pageurl."&addnew=db_krz2detitems&addf=ID_krz2det&addv=".$ID_krz2det."&addf2=TID&addv2=".$tid."'>+</a></span></td>\n";
		echo "<td class='Field' colspan='4' style='text-align: left; padding-left: 40px; padding-top: 10px;'><b>".$names."</b></td>\n";
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='Field'></td>\n";
		echo "</tr>\n";

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2detitems where (ID_krz2det = '".$ID_krz2det."') and (TID = '".$tid."') order by ID");
		while($res = mysql_fetch_array($xxx)) {
			OpenID($res);
		}

	}

	function OutTableStart($summ,$names) {
		global $editingkrz, $event_link;

		echo "<tr height='30'>\n";
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='nbg'></td>\n";
		echo "<td class='Field' colspan='3' style='text-align: left; padding-left: 40px; padding-top: 10px;'><b>".$names."</b></td>\n";
		echo "<td class='Field' style='text-align: left; padding-top: 10px;' id='summ_1'><b>".$summ."</b></td>\n";
		if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='Field'>".$event_link."</td>\n";
		echo "</tr>\n";

	}

   // ������� ///////////////////////////////////////////////////////////////////////

	if ($print_mode=="off") echo "<div class='links'><a href='index.php?do=show&formid=33&id=".$ID_krz2."'>����� � ���</a></div>";

	echo "<h2>�������� ������� �� ���</h2>";
	echo "<span class='line'>".$item["NAME"]." - ".$item["OBOZ"]."</span><br><br>";

   // HEADER ///////////////////////////////////////////////////////////////
	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1000px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<thead>\n";
	echo "<tr class='first'>\n";
	if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td class='nbg' width='27'></td>\n";
	echo "<td>����������</td>\n";
	echo "<td width='200'>���� ��. ���. ��� ���</td>\n";
	echo "<td width='30'>��. ���.</td>\n";
	echo "<td width='200'>�� ��.</td>\n";
	if ((db_adcheck("db_krz2detitems")) && ($editingkrz)) echo "<td width='27'></td>\n";
	echo "</tr>\n";
	echo "</thead>\n";

   // TBODY
	echo "<tbody>\n";

	OutLine($item,"VES","��� ������","��");

	$summ = $item["D1"]+$item["D2"];
	OutTableStart($summ,"����������");

	OutLine($item,"D1","���������� �� �� �������","�/�");
	OutLine($item,"D2","���������� �� �� ���������� � ��������","�/�");

	$summ = $item["D3"]+$item["D4"]+$item["D5"]+$item["D6"]+$item["D7"]+$item["D8"]+$item["D9"]+$item["D10"]+$item["D11"];
	OutTableStart($summ,"������������");

	OutLine($item,"D3","���������","�/�");
	OutLine($item,"D4","������-������","�/�");
	OutLine($item,"D5","���������������","�/�");
	OutLine($item,"D6","������","�/�");
	OutLine($item,"D7","��������������","�/�");
	OutLine($item,"D8","��������","�/�");
	OutLine($item,"D9","�������","�/�");
	OutLine($item,"D10","���������","�/�");
	OutLine($item,"D11","��������","�/�");


	OutTableTMC(0,"��� �� ������� � �������� (� ��� ����� ���������������)");
	OutTableTMC(1,"��� �� �������������� � ���������");
	OutTableTMC(6,"�������� �������");
	OutTableTMC(2,"����������");
	OutTableTMC(3,"���������");
	OutTableTMC(4,"������������ �������");
	OutTableTMC(5,"��������������� �� ��");


	echo "</tbody>\n";
	echo "</table>\n";
	echo "<div style='width: 100%; text-align: right;'><b>��� ���� ��������� ��� ���</b></div>";

}




?>