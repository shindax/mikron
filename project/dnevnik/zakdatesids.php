<?php

	$Field = $_GET["p2"];

	$ds["isnt_1"] = "в работе";
	$ds["is_0"] = "без даты";
	$ds["is_1"] = "выполнено";
	$ds["is_2"] = "в течение 7 дней";
	$ds["is_3"] = "сегодня";
	$ds["is_4"] = "просроченные";
	$ds["is_5"] = "более 7 дней";

	$fn["PD1"] = "Подготовка производства, КД";
	$fn["PD2"] = "Подготовка производства, нормы расхода";
	$fn["PD3"] = "Подготовка производства, МТК";
	$fn["PD13"] = "Подготовка производства, оснастка и инструмент";
	$fn["PD4"] = "Комплектация, проработка";
	$fn["PD5"] = "Комплектация, предоплата";
	$fn["PD6"] = "Комплектация, окончательный расчёт";
	$fn["PD7"] = "Комплектация, поставка";
	$fn["PD14"] = "Комплектация, входной контроль";
	$fn["PD12"] = "Производство, дата начала";
	$fn["PD8"] = "Производство, дата окончания";
	$fn["PD9"] = "Коммерция, предоплата";
	$fn["PD10"] = "Коммерция, окончательный расчёт";
	$fn["PD11"] = "Коммерция, поставка";

	$text = "<h4>".$fn[$Field]." - ".$ds[$_GET["p3"]]."</h4>";






function DateStudy($row,$field) {
	global $today_7, $today_1, $today_0;

	// 0 - нет даты
	// 1 - зелёная
	// 2 - за 7 дней
	// 3 - сегодня
	// 4 - просрочена
	// 5 - более 7 дней

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