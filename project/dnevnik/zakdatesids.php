<?php

	$Field = $_GET["p2"];

	$ds["isnt_1"] = "� ������";
	$ds["is_0"] = "��� ����";
	$ds["is_1"] = "���������";
	$ds["is_2"] = "� ������� 7 ����";
	$ds["is_3"] = "�������";
	$ds["is_4"] = "������������";
	$ds["is_5"] = "����� 7 ����";

	$fn["PD1"] = "���������� ������������, ��";
	$fn["PD2"] = "���������� ������������, ����� �������";
	$fn["PD3"] = "���������� ������������, ���";
	$fn["PD13"] = "���������� ������������, �������� � ����������";
	$fn["PD4"] = "������������, ����������";
	$fn["PD5"] = "������������, ����������";
	$fn["PD6"] = "������������, ������������� ������";
	$fn["PD7"] = "������������, ��������";
	$fn["PD14"] = "������������, ������� ��������";
	$fn["PD12"] = "������������, ���� ������";
	$fn["PD8"] = "������������, ���� ���������";
	$fn["PD9"] = "���������, ����������";
	$fn["PD10"] = "���������, ������������� ������";
	$fn["PD11"] = "���������, ��������";

	$text = "<h4>".$fn[$Field]." - ".$ds[$_GET["p3"]]."</h4>";






function DateStudy($row,$field) {
	global $today_7, $today_1, $today_0;

	// 0 - ��� ����
	// 1 - ������
	// 2 - �� 7 ����
	// 3 - �������
	// 4 - ����������
	// 5 - ����� 7 ����

	$values = $row[$field];
	if ($values=="") $values ="0|";
	$values = explode("|",$values);
	$numval = count($values)-1;
	$lastval = $values[$numval];
	if ($lastval=="") $lastval = "##";
	$lastval = explode("#",$lastval);

	$res = 0;
	if ($lastval[2]=="") {
		$res = 0;
	} else {
		if (DateToInt($today_7)<DateToInt($lastval[2])) $res = 5;
		if (DateToInt($today_7)==DateToInt($lastval[2])) $res = 5;
		if (DateToInt($today_7)>DateToInt($lastval[2])) $res = 2;
		if (DateToInt($today_1)>DateToInt($lastval[2])) $res = 3;
		if (DateToInt($today_0)>DateToInt($lastval[2])) $res = 4;
	}
	if ($values[0]=="1") {
		$res = 1;
	}

	return $res;
}


if ($_GET["p3"]!=="isnt_1") {
	$ids = array();
	$ids[] = 0;

	$result = dbquery("SELECT ID, ".$_GET["p2"]." FROM ".$db_prefix."db_zak where (EDIT_STATE='0')");
	while ($res=mysql_fetch_array($result)) {
		$st = DateStudy($res,$Field);
		if ("is_".$st==$_GET["p3"]) $ids[] = $res["ID"];
	}

	$ids = implode(",",$ids);

	$where = "ID IN (".$ids.")";
} else {

	$where = "(EDIT_STATE='0') and (".$Field." like '%0|%')";
}
?>