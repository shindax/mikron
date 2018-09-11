<?php

$URL = "index.php?do=show&formid=67&p2=";



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









///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Вывод по датам подготовки производства ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function OutByDatesTable($q,$txt) {
	global $db_prefix, $URL;

	$qst = explode("|",$q);
	$text = explode("|",$txt);

	for ($i=0;$i < count($qst);$i++) {
		$c0[$i] = 0;
		$c1[$i] = 0;
		$c2[$i] = 0;
		$c3[$i] = 0;
		$c4[$i] = 0;
		$c5[$i] = 0;
	}

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (EDIT_STATE='0')");
	while ($res=mysql_fetch_array($result)) {
		for ($i=0;$i < count($qst);$i++) {
			$st = DateStudy($res,$qst[$i]);
			if ($st==0) $c0[$i] = $c0[$i] + 1;
			if ($st==1) $c1[$i] = $c1[$i] + 1;
			if ($st==2) $c2[$i] = $c2[$i] + 1;
			if ($st==3) $c3[$i]= $c3[$i] + 1;
			if ($st==4) $c4[$i] = $c4[$i] + 1;
			if ($st==5) $c5[$i] = $c5[$i] + 1;
		}
	}

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "<tr class='first'>\n";
		echo "<td class='Field'>Выполнение</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field' width='100px;'>".$text[$i]."</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td class='Field' style='text-align: left;'>В работе</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field'><a href='".$URL.$qst[$i]."&p3=isnt_1' target='_blank'>".($c0[$i]+$c2[$i]+$c3[$i]+$c4[$i]+$c5[$i])."</a></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td class='Field' style='text-align: left;'>Без даты</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field'><a href='".$URL.$qst[$i]."&p3=is_0' target='_blank'>".$c0[$i]."</a></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td class='Field' style='text-align: left;'>Более 7 дней</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field'><a href='".$URL.$qst[$i]."&p3=is_5' target='_blank'>".$c5[$i]."</a></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td class='Field' style='text-align: left;'>В течение 7 дней</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field'><a href='".$URL.$qst[$i]."&p3=is_2' target='_blank'>".$c2[$i]."</a></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td class='Field' style='text-align: left;'>Сегодня</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field'><a href='".$URL.$qst[$i]."&p3=is_3' target='_blank'>".$c3[$i]."</a></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td class='Field' style='text-align: left;'>Просроченные</td>\n";
		for ($i=0;$i < count($qst);$i++) echo "<td class='Field'><a href='".$URL.$qst[$i]."&p3=is_4' target='_blank'><b style='color: #f44;'>".$c4[$i]."</b></a></td>\n";
	echo "</tr>\n";

	echo "</table>";
}




   ///////////////////////

	echo "<h2>Подготовка производства:</h2>";
	OutByDatesTable("PD1|PD2|PD3|PD13","КД|Нормы расхода|МТК|Оснастка и инструмент");
	
   /////////////////////

	echo "<h2>Комплектация:</h2>";
	OutByDatesTable("PD4|PD5|PD6|PD7|PD14","Проработка|Предоплата|Окончательный расчёт|Поставка|Входной контроль");

	
   /////////////////////

	echo "<h2>Производство:</h2>";
	OutByDatesTable("PD12|PD8","Дата начала|Дата окончания");
	
   /////////////////////

	echo "<h2>Коммерция:</h2>";
	OutByDatesTable("PD9|PD10|PD11","Предоплата|Окончательный расчёт|Поставка");

   /////////////////////





?>