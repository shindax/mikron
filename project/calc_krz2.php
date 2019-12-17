<?php

	if (!defined("MAV_ERP")) { die("Access Denied"); }

$NDS_val = 20; // НДС %


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

$det_array = array();
$count_array = array();

function OpenCalcID($item,$count) {
	global $db_prefix, $det_array, $count_array;

	if ($count>0) {
		$det_array[] = $item;
		$count_array[] = $count;

		$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where  (PID='".$item["ID"]."')");
		while($res = mysql_fetch_array($result)) {
			OpenCalcID($res,$count*$res["COUNT"]);
		}
	}
}

function Summ($field) {
	global $det_array, $count_array;

	$res = 0;
	for ($j=0;$j < count($count_array);$j++) {
		$res = $res + ($det_array[$j][$field]*$count_array[$j]);
	}
	return $res;
}

function SummL($field) {
	global $det_array, $count_array;

	$res = 0;
	for ($j=0;$j < count($count_array);$j++) {
		$res = $res + $det_array[$j][$field];
	}
	return $res;
}

function CalcArray($tid) {
	global $count_array, $det_array, $db_prefix, $DDD, $DDDx, $D_array;

	for ($j=0;$j < count($count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2detitems where  (ID_krz2det='".$det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$sc = $item["COUNT"]*$count_array[$j];
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$item["PRICE"]);
			$D_array[] = $item["PRICE"]."|".$sc."|".$item["NAME"];
		}
	}
}

function CalcArrayL($tid) {
	global $count_array, $det_array, $db_prefix, $DDD, $DDDx, $D_array;

	for ($j=0;$j < count($count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2detitems where  (ID_krz2det='".$det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$sc = $item["COUNT"];
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$item["PRICE"]);
			$D_array[] = $item["PRICE"]."|".$sc."|".$item["NAME"];
		}
	}
}

/////////////////////////////////////////////////////////////////////////
//
// НАЧАЛО
//
/////////////////////////////////////////////////////////////////////////

	$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where (PID='0') and (ID_krz2='".$ID_krz2."')");
	if ($det = mysql_fetch_array($result)) {
		$count = $det["COUNT"];
		OpenCalcID($det,$count);
	}
	$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where (ID='".$ID_krz2."')");
	$krz = mysql_fetch_array($result);

	$price_all = $krz["PRICE"]*(100/(100+$NDS_val));  // Вычли НДС далее всё без НДС

	if ($count==0) $count=1;
	if ($count=="") $count=1;

/////////////////////////////////////////////////////////////////////////
//
// РАЗРАБОТКА
//
/////////////////////////////////////////////////////////////////////////

	$D1_1 = SummL("D1");
	$D1_2 = SummL("D2");
	$D1 = $D1_1+$D1_2;

/////////////////////////////////////////////////////////////////////////
//
// ПРОИЗВОДСТВО
//
/////////////////////////////////////////////////////////////////////////

	$D2_1 = Summ("D3");
	$D2_2 = Summ("D4");
	$D2_3 = Summ("D5");
	$D2_4 = Summ("D6");
	$D2_5 = Summ("D7");
	$D2_6 = Summ("D8");
	$D2_7 = Summ("D9");
	$D2_8 = Summ("D10");
	$D2_9 = SummL("D11");
	$D2 = $D2_1+$D2_2+$D2_3+$D2_4+$D2_5+$D2_6+$D2_7+$D2_8+$D2_9;

/////////////////////////////////////////////////////////////////////////
//
// ТМЦ на изделие и упаковку ( в<br>том числе вспомогательные)
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArray(0);
		$D3 = $DDD;
		$Dp3 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// ТМЦ на специнструмент и оснащение
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL(1);
		$D4 = $DDD;
		$Dp4 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Покупные изделия
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArray(6);
		$D9 = $DDD;
		$Dp9 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Кооперация
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArray(2);
		$D5 = $DDD;
		$Dp5 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Транспорт
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL(3);
		$D6 = $DDD;
		$Dp6 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Коммерческие расходы
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL(4);
		$D7 = $DDD;
		$Dp7 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Спецмероприятия по ИС
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL(5);
		$D8 = $DDD;
		$Dp8 = $DDDx;

/////////////////////////////////////////////////////////////////////////
//
// Вес изделия
//
/////////////////////////////////////////////////////////////////////////

	$VES = $det["VES"]; // Summ("VES");
	$DALL = $price_all;
	$price = 0;
	if (($D1+$D2)>0) $price = ($DALL-($Dp3+$Dp4+$D5+$D6+$D7+$D8+$D9))/($D1+$D2);
	//$DALL = (($D1+$D2)*$price)+$Dp3+$Dp4+$D5+$D6+$D7+$D8+$D9;
	$RURTONN = 0;
	if ($VES>0) $RURTONN = (1000*$DALL)/$VES;
	$PRIB = 0;
	if ($VES>0) $PRIB = (1000*(($D1+$D2)*$price))/$VES;
	$MARSHA = (($D1+$D2)*$price) - $Dp4;

$S1 = $Dp3+$Dp4+$D9+$D5;
$S2 = $D7;
$res_price_1 = FReal($DALL/$count);
$res_price_one = $DALL/$count;
$res_price_norm = FReal($price);
$res_marsha = FReal($MARSHA);
$res_norm_plan = $D1+$D2;

dbquery("Update ".$db_prefix."db_krz2 Set NORM_PRICE:='".$price."' where (ID='".$ID_krz2."')");
dbquery("Update ".$db_prefix."db_krz2 Set S1:='".$S1."' where (ID='".$ID_krz2."')");
dbquery("Update ".$db_prefix."db_krz2 Set S2:='".$S2."' where (ID='".$ID_krz2."')");



	$result = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where (ID='".$ID_krz2."')");
	$krz = mysql_fetch_array($result);

	echo "<table class='tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 600px;' border='1' cellpadding='0' cellspacing='0'>\n";

	echo "<tr class='first'>\n";
	echo "<td class='Field' width='300'></td>\n";
	echo "<td class='Field'><b>Без НДС</b></td>\n";
	echo "<td class='Field'><b>С НДС</b></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Цена итого, руб</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["PRICE"]*(100/(100+$NDS_val)))."</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["PRICE"]*1)."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Цена на единицу, руб</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$res_price_one)."</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$res_price_one*((100+$NDS_val)/100))."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Всего Н/Ч</td>\n";
	echo "<td class='Field' style='text-align: center' colspan='2'>".FormatReal(2,$res_norm_plan)."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Цена Н/Ч, руб</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["NORM_PRICE"]*1)."</td>\n";
	echo "<td class='Field' style='text-align: left'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Маржа, руб</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$MARSHA)."</td>\n";
	echo "<td class='Field' style='text-align: left'></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Основные материалы, руб</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["S1"]*1)."</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["S1"]*((100+$NDS_val)/100))."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td class='Field first' style='text-align: left'>Коммерческие расходы, руб</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["S2"]*1)."</td>\n";
	echo "<td class='Field' style='text-align: left'>".FormatReal(2,$krz["S2"]*((100+$NDS_val)/100))."</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

?>