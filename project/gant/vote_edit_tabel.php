<?php

header('Content-type: text/html; charset=windows-1251');
	
include "includes.php";



	$DI_WName = Array('','Пн','Вт','Ср','Чт','Пт','Сб','Вс');
	$DI_MName = Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

	$today = TodayDate();
	$today = explode(".",$today);
	$today_DD = $today[0];
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$today_m = explode(".",TodayAddDays(-3));
	$today_m = $today_m[2]*10000+$today_m[1]*100+$today_m[0];

	$today_m8 = explode(".",TodayAddDays(-30));
	$today_m8 = $today_m8[2]*10000+$today_m8[1]*100+$today_m8[0];

	$DI_Date = TodayDate();
	if (isset($_GET["p0"])) $DI_Date = $_GET["p0"];
	$txtdd = $DI_Date;
	$DI_Date = explode(".",$DI_Date);

	$DI_YY = $DI_Date[2];
	$DI_LYY = $DI_YY;
	$DI_NYY = $DI_YY;
	$MY = $DI_Date[1].".".$DI_Date[2];

	$DI_MM = $DI_Date[1]-1;
	$DI_LMM = $DI_MM-1;
	if ($DI_LMM<0) $DI_LMM = 11;
	$DI_NMM = $DI_MM+1;
	if ($DI_NMM>11) $DI_NMM = 0;

	if ($DI_MM==0) $DI_LYY = $DI_YY-1;
	if ($DI_MM==11) $DI_NYY = $DI_YY+1;

	$DI_DD = 1;

	$lastM = $DI_MM;
	$yy = $DI_YY;
	if ($lastM<1) {
		$lastM = 12+$lastM;
		$yy = $yy - 1;
	}
	$lastM = $DI_DD.".".$lastM.".".$yy;

	$nextM = $DI_MM+2;
	$yy = $DI_YY;
	if ($nextM>12) {
		$nextM = $nextM-12;
		$yy = $yy + 1;
	}
	$nextM = $DI_DD.".".$nextM.".".$yy;

	$lastY = $DI_DD.".".($DI_MM+1).".".($DI_YY-1);
	$nextY = $DI_DD.".".($DI_MM+1).".".($DI_YY+1);

echo "
<form method='post' action='' onsubmit='ShowResourceTabelSend(this);return false;' id='tabel_form'>
<div style='padding: 20px;	margin: 0px;background: #c6d9f1;border: 1px solid #8ba2c2;box-shadow: 3px 4px 20px #555555;z-index: 200;text-align: left;'>
	<b style='margin-right: 30px; margin-left: 50px; font-size: 14pt;'>ВЫБРАННЫМ:</b>
	С <select name='firstday'>";
	
$maxDD = DI_MNum($DI_MM,$DI_YY);
					
for ($j = 0;$j < $maxDD; $j++) {
	echo "<option value='".($j+1)."' ";
		
	if ($j == 0) echo "selected";
		
	echo ">".($j+1);
}
					
echo "</select> ПО <select name='secondday';>";
	
for ($j = 0; $j < DI_MNum($DI_MM, $DI_YY); $j++) {
	echo "<option value='".($j+1)."' ";
	
	if ($j+1==$maxDD) echo "selected";
	
	echo ">".($j+1);
}
					
echo "</select><br><hr></hr>
<table style='width: 100%; border: none;'><tr><td style='width: 50%; border: none; background: none; text-align: left; vertical-align: top;'>
<b>ПРОСТАНОВКА ТОЛЬКО В ПЛАН</b><br><br>
<input type='hidden' name='id_oper' value='" . $_GET['id_oper'] . "'/>
<input type='radio' class='rinp' name='variant' value='by_st'> Согласно текущих графиков работ<br>
<br>
<input type='radio' class='rinp' name='variant' value='work'>
Работает: Смена<select name='var_smena'>
<option value='1' selected>1
<option value='2'>2
<option value='3'>3
</select> Время, ч <input type='text' value='8' style='width: 40px;' name='var_time'><br>
<br>
<input type='radio' class='rinp' name='variant' value='clear'> Очистить<br><br>
</td></tr></table>";

		 	  // TID "ОТ|ДО| Х| Б|НН|ПР| В|ЛЧ|НВ| K|РП| У|ПК|НП"
		 	  // TID " 1| 2| 3| 4| 5| 6| 7| 8| 9|10|11|12|13|14"

echo "<hr></hr><input type='submit' name='edit_tabel' style='margin-left: 50px;' value='Применить'>
</div></span></form>";
			
			
			
	function DI_MNum($Mon, $Year) {
		$nn = Array(31,28,31,30,31,30,31,31,30,31,30,31);
		$x = 28;
		$y = (Round($Year/4))*4;
		if ($y==$Year) $x = 29;
		$ret = $nn[$Mon];
		if ($Mon==1) $ret = $x;
		return $ret;
	}

	function TodayDate() {
		return date("d.m.Y");
	}

	function GetMonday($dweek=0){
		return date("d.m.Y", strtotime("last Monday")+($dweek*604800));
	}

	function GetSunday($dweek=0){
		return date("d.m.Y", strtotime("Sunday")+($dweek*604800));
	}

	function TodayInt() {
		return date("Ymd")*1;
	}

	function NextYear() {
		$today = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		return date("Y",$today)+1;
	}

	function TodayAddDays($x) {
		$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
		return date("d.m.Y",$theday+($x*86400));
	}

