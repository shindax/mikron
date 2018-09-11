<?php


/////////////////////////////////////////////////////////////////////////
//
// ФУНКЦИИ РАСЧЁТА
//
/////////////////////////////////////////////////////////////////////////

function FReal($x) {
	$ret = number_format( $x, 2, ',', ' ');
	if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
	return $ret;
}

$NC_det_array = [];
$NC_count_array = [];

function NC_OpenCalcID($item,$count) {
	global $db_prefix, $NC_det_array, $NC_count_array;

	if ($count>0) {
		$NC_det_array[] = $item;
		$NC_count_array[] = $count;

		$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where  (PID='".$item["ID"]."')");
		while($res = mysql_fetch_array($result)) {
			NC_OpenCalcID($res,$count*$res["COUNT"]);
		}
	}
}

function NC_Summ($field) {
	global $NC_det_array, $NC_count_array;

	$res = 0;
	for ($j=0;$j < count($NC_count_array);$j++) {
		$res = $res + ($NC_det_array[$j][$field]*$NC_count_array[$j]);
	}
	return $res;
}

function NC_SummL($field) {
	global $NC_det_array, $NC_count_array;

	$res = 0;
	for ($j=0;$j < count($NC_count_array);$j++) {
		$res = $res + $NC_det_array[$j][$field];
	}
	return $res;
}

function NC_CalcArray($tid) {
	global $NC_count_array, $NC_det_array, $db_prefix, $DDD, $DDDx, $NC_D_array;

	for ($j=0;$j < count($NC_count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2detitems where  (ID_krz2det='".$NC_det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$sc = $item["COUNT"]*$NC_count_array[$j];
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$item["PRICE"]);
			$NC_D_array[] = $item["PRICE"]."|".$sc."|".$item["NAME"];
		}
	}
}

function NC_CalcArrayL($tid) {
	global $NC_count_array, $NC_det_array, $db_prefix, $DDD, $DDDx, $NC_D_array;

	for ($j=0;$j < count($NC_count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2detitems where  (ID_krz2det='".$NC_det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$sc = $item["COUNT"];
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$item["PRICE"]);
			$NC_D_array[] = $item["PRICE"]."|".$sc."|".$item["NAME"];
		}
	}
}

/////////////////////////////////////////////////////////////////////////
//
// НАЧАЛО
//
/////////////////////////////////////////////////////////////////////////

	$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where (PID='0') and (ID_krz2='".$ID_krz2."')");
	if ($nc_det = mysql_fetch_array($result)) {
		$nc_count = $nc_det["COUNT"];
		NC_OpenCalcID($nc_det,$nc_count);
	}

/////////////////////////////////////////////////////////////////////////
//
// РАЗРАБОТКА
//
/////////////////////////////////////////////////////////////////////////

	$D1_1 = NC_SummL("D1");
	$D1_2 = NC_SummL("D2");
	$D1 = $D1_1+$D1_2;

/////////////////////////////////////////////////////////////////////////
//
// ПРОИЗВОДСТВО
//
/////////////////////////////////////////////////////////////////////////

	$D2_1 = NC_Summ("D3");
	$D2_2 = NC_Summ("D4");
	$D2_3 = NC_Summ("D5");
	$D2_4 = NC_Summ("D6");
	$D2_5 = NC_Summ("D7");
	$D2_6 = NC_Summ("D8");
	$D2_7 = NC_Summ("D9");
	$D2_8 = NC_Summ("D10");
	$D2_9 = NC_SummL("D11");
	$D2 = $D2_1+$D2_2+$D2_3+$D2_4+$D2_5+$D2_6+$D2_7+$D2_8+$D2_9;

/////////////////////////////////////////////////////////////////////////
//
// ТМЦ на изделие и упаковку ( в<br>том числе вспомогательные)
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArray(0);
		$D3 = $DDD;
		$Dp3 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// ТМЦ на специнструмент и оснащение
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArrayL(1);
		$D4 = $DDD;
		$Dp4 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Кооперация
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArray(6);
		$D9 = $DDD;
		$Dp9 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Кооперация
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArray(2);
		$D5 = $DDD;
		$Dp5 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Транспорт
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArrayL(3);
		$D6 = $DDD;
		$Dp6 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Коммерческие расходы
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArrayL(4);
		$D7 = $DDD;
		$Dp7 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Спецмероприятия по ИС
//
/////////////////////////////////////////////////////////////////////////

	$NC_D_array = [];
	$DDD = 0;
	$DDDx = 0;
	NC_CalcArrayL(5);
		$D8 = $DDD;
		$Dp8 = $DDDx;

	$price = 0;
	if (($D1+$D2)>0) $price = ($price_all-($Dp3+$Dp4+$D5+$D6+$D7+$D8+$D9))/($D1+$D2);

?>