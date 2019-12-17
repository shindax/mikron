<script type="text/javascript" src="/project/print_krz/js/krz_calc.js?arg=1"></script>
<?php


/////////////////////////////////////////////////////////////////////////
//
// ФУНКЦИИ ВЫВОДА
//
/////////////////////////////////////////////////////////////////////////

function FReal($x) {
	$ret = number_format( $x, 2, ',', ' ');
	if ($x==floor($x)) $ret = number_format($x, 0, ',', ' ');
	return $ret;
}

function OutC($num,$name,$D) {
	global $count, $price;
	echo "
	<tr class='center'>
		<td>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td></td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td>".FReal(($price*$D)/$count)."</td>
		<td>".FReal($price*$D)."</td>
	</tr>
	";
}

function OutD($num,$name,$D) {
	global $count, $price;

	echo "
	<tr>
		<td class='num'>".$num."</td>
		<td colspan='2' class='first'>".$name."</td>
		<td>Н/Ч</td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td>".FReal(($price*$D)/$count)."</td>
		<td>".FReal($price*$D)."</td>
	</tr>
	";
}

function OutKGC($num,$name,$D,$Dp) {
	global $count, $price;
	echo "
	<tr class='center'>
		<td class='num'>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td></td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td>".FReal($Dp/$count)."</td>
		<td>".FReal($Dp)."</td>
	</tr>
	";
}

function OutKG($num,$arr) {
	global $count;

	if ($arr!=="") {
	for ($j=0;$j < count($arr);$j++) {
		$xx = explode("|",$arr[$j]);
		$price = $xx[0];
		$D = $xx[1];
		$name = $xx[2];
	echo "
	<tr>
		<td class='num'>".$num.".".($j+1)."</td>
		<td class='first'>".$name."</td>
		<td>";
	RVal("prc_".$xx[3],$price);
	echo "</td>
		<td>кг</td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td>".FReal(($price*$D)/$count)."</td>
		<td>".FReal($price*$D)."</td>
	</tr>
	";
	}
	}
}

function OutRC($num, $name, $D, $type = null, $VES = null, $D1_D2 = null, $Dtemp = null, $DALL = null, $Dp4  = null) 
{
	global $count;
	
	$val = FReal($D/$count);
	
	$str =  "
	<tr class='center'>
		<td class='num'>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td></td>
		<td></td>
		<td></td><td>";
	$str .= "<input class='recalc_input_$num' type='text' value='$val' data-dp4='$Dp4' data-type='$type' data-count='$count' data-dall='$DALL' data-ves='$VES' data-d1_d2='$D1_D2' data-dtemp='$Dtemp'></input>";
	$str .= "</td>
		<td class='recalc_input_".( $num + 1 )."'>" .number_format($DALL, 2, ',', ' ') . "</td>
	</tr>
	";

	echo $str; 

}

function OutR($num,$arr) {
	global $count;

	if ($arr!=="") {
	for ($j=0;$j < count($arr);$j++) {
		$xx = explode("|",$arr[$j]);
		$price = $xx[0];
		$D = $xx[1];
		$name = $xx[2];
	echo "
	<tr>
		<td class='num'>".$num.".".($j+1)."</td>
		<td colspan='2' class='first'>".$name."</td>
		<td>руб</td>
		<td></td>
		<td></td>
		<td>".FReal($price/$count)."</td>
		<td>";
	RVal("prc_".$xx[3],$price);
	echo "</td>
	</tr>
	";
	}
	}
}

function OutRC2($num,$name,$D,$Dp) {
	global $count, $price;
	echo "
	<tr class='center'>
		<td class='num'>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td></td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td>".FReal($Dp/$count)."</td>
		<td>".FReal($Dp)."</td>
	</tr>
	";
}

function OutR2($num,$arr) {
	global $count;

	if ($arr!=="") {
	for ($j=0;$j < count($arr);$j++) {
		$xx = explode("|",$arr[$j]);
		$price = $xx[0];
		$D = $xx[1];
		$name = $xx[2];
	echo "
	<tr>
		<td class='num'>".$num.".".($j+1)."</td>
		<td class='first'>".$name."</td>
		<td>";
	RVal("prc_".$xx[3],$price);
	echo "</td>
		<td>шт</td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td>".FReal(($price*$D)/$count)."</td>
		<td>".FReal($price*$D)."</td>
	</tr>
	";
	}
	}
}

function OutVES($num,$name,$D) {
	global $count;
	echo "
	<tr class='center'>
		<td class='num'>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td></td>
		<td>".FReal($D/$count)."</td>
		<td>".FReal($D)."</td>
		<td></td>
		<td></td>
	</tr>
	";
}

function OutRT( $num , $name , $D,  $type, $VES, $D1_D2, $Dtemp, $DALL, $Dp4 ) 
{
	global $count;
	
	$val = FReal( $D );	
	
	echo "
	<tr class='center'>
		<td class='num'>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td colspan='5'><input class='recalc_input_$num' type='text' value='$val' data-dp4='$Dp4' data-type='$type' data-count='$count' data-dall='$DALL' data-ves='$VES' data-d1_d2='$D1_D2' data-dtemp='$Dtemp'></input></td>
	</tr>
	";
}

function OutM($num,$name,$D) {
	global $count;
	echo "
	<tr class='center'>
		<td class='num'>".$num."</td>
		<td colspan='2'>".$name."</td>
		<td colspan='4'></td>
		<td class='recalc_input_$num'>".FReal($D)."</td>
	</tr>
	";
}

/////////////////////////////////////////////////////////////////////////
//
// ФУНКЦИИ РАСЧЁТА
//
/////////////////////////////////////////////////////////////////////////

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
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdetitems where  (ID_krzdet='".$det_array[$j]["ID"]."') and (TID='".$tid."') ORDER BY ID");
		while ($item = mysql_fetch_array($xxx)) {
			$I_price = $item["PRICE"];
			$I_count = $item["COUNT"];
			if (isset($_POST["prc_".$item["ID"]])) $I_price = $_POST["prc_".$item["ID"]];
			if (isset($_POST["cnt_".$item["ID"]])) $I_count = $_POST["cnt_".$item["ID"]];
			$sc = $I_count*$count_array[$j];
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$I_price);
			$D_array[] = $I_price."|".$sc."|".$item["NAME"]."|".$item["ID"];
		}
	}
}

function CalcArray2($tid) {
	global $count_array, $det_array, $db_prefix, $DDD, $DDDx, $D_array;

	for ($j=0;$j < count($count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdetitems where  (ID_krzdet='".$det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$I_price = $item["COUNT"];
			$I_count = $count_array[$j];
			if (isset($_POST["prc_".$item["ID"]])) $I_price = $_POST["prc_".$item["ID"]];
			if (isset($_POST["cnt_".$item["ID"]])) $I_count = $_POST["cnt_".$item["ID"]];
			$sc = $I_count;
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$I_price);
			$D_array[] = $I_price."|".$sc."|".$item["NAME"]."|".$item["ID"];
		}
	}
}

function CalcArrayL($tid) {
	global $count_array, $det_array, $db_prefix, $DDD, $DDDx, $D_array;

	for ($j=0;$j < count($count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdetitems where  (ID_krzdet='".$det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$I_price = $item["PRICE"];
			$I_count = $item["COUNT"];
			if (isset($_POST["prc_".$item["ID"]])) $I_price = $_POST["prc_".$item["ID"]];
			if (isset($_POST["cnt_".$item["ID"]])) $I_count = $_POST["cnt_".$item["ID"]];
			$sc = $I_count;
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$I_price);
			$D_array[] = $I_price."|".$sc."|".$item["NAME"]."|".$item["ID"];
		}
	}
}

function CalcArrayL2($tid) {
	global $count_array, $det_array, $db_prefix, $DDD, $DDDx, $D_array;

	for ($j=0;$j < count($count_array);$j++) {
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdetitems where  (ID_krzdet='".$det_array[$j]["ID"]."') and (TID='".$tid."')");
		while ($item = mysql_fetch_array($xxx)) {
			$I_price = $item["COUNT"];
			$I_count = 1;
			if (isset($_POST["prc_".$item["ID"]])) $I_price = $_POST["prc_".$item["ID"]];
			if (isset($_POST["cnt_".$item["ID"]])) $I_count = $_POST["cnt_".$item["ID"]];
			$sc = $I_count;
			$DDD = $DDD + $sc;
			$DDDx = $DDDx + ($sc*$I_price);
			$D_array[] = $I_price."|".$sc."|".$item["NAME"]."|".$item["ID"];
		}
	}
}

/////////////////////////////////////////////////////////////////////////
//
// РАЗРАБОТКА
//
/////////////////////////////////////////////////////////////////////////

	$D1_1 = SummL("D1");
	$D1_2 = SummL("D2");
	$D1 = $D1_1+$D1_2;

	OutC("1","Разработка",$D1);
	OutD("1.1","Разработка КД на изделие",$D1_1);
	OutD("1.2","Разработка КД на инструмент и оснастку",$D1_2);

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

	OutC("2","Производство",$D2);
	OutD("2.1","Заготовка",$D2_1);
	OutD("2.2","Сборка-сварка",$D2_2);
	OutD("2.3","Механообработка",$D2_3);
	OutD("2.4","Сборка",$D2_4);
	OutD("2.5","Термообработка",$D2_5);
	OutD("2.6","Упаковка",$D2_6);
	OutD("2.7","Окраска",$D2_7);
	OutD("2.8","Штамповка",$D2_8);
	OutD("2.9","Оснастка",$D2_9);

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
	OutKGC("3","ТМЦ на изделие и упаковку ( в<br>том числе вспомогательные)",$D3,$Dp3);
	OutKG("3",$D_array);

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
	OutKGC("4","ТМЦ на специнструмент и оснащение",$D4,$Dp4);
	OutKG("4",$D_array);

/////////////////////////////////////////////////////////////////////////
//
// Покупные изделия
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArray2(6);
		$D9 = $DDD;
		$Dp9 = $DDDx;
	OutRC2("5","Покупные изделия",$D9,$Dp9);
	OutR2("5",$D_array); 

/////////////////////////////////////////////////////////////////////////
//
// Кооперация
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArray2(2);
		$D5 = $DDD;
		$Dp5 = $DDDx;
	OutRC2("6","Кооперация",$D5,$Dp5);
	OutR2("6",$D_array); 

/////////////////////////////////////////////////////////////////////////
//
// Транспорт
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL2(3);
		$D6 = $DDD;
		$Dp6 = $DDDx;
	OutRC("7","Транспорт",$Dp6);
	OutR("7",$D_array); 

/////////////////////////////////////////////////////////////////////////
//
// Коммерческие расходы
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL2(4);
		$D7 = $DDD;
		$Dp7 = $DDDx;
	OutRC("8","Коммерческие расходы",$Dp7);
	OutR("8",$D_array); 

/////////////////////////////////////////////////////////////////////////
//
// Спецмероприятия по ИС
//
/////////////////////////////////////////////////////////////////////////

	$D_array = [];
	$DDD = 0;
	$DDDx = 0;
	CalcArrayL2(5);
		$D8 = $DDD;
		$Dp8 = $DDDx;
	OutRC("9","Спецмероприятия по ИС",$Dp8);
	OutR("9",$D_array);

/////////////////////////////////////////////////////////////////////////
//
// Вес изделия
//
/////////////////////////////////////////////////////////////////////////

	$VES = $count*$det["VES"]; // Summ("VES");
	$D1_D2 = ( $D1 + $D2 );

  $Dtemp = $Dp3+$Dp4+$Dp5+$Dp6+$Dp7+$Dp8+$Dp9 ;
	
	$DALL = ($D1_D2 *$price) + $Dtemp ;
	
	$RURTONN = 0;

	if ($VES>0) 
    $RURTONN = (1000*$DALL)/$VES;
  
  $PRIB = 0;
	
	if ($VES>0) 
    $PRIB = (1000*(($D1+$D2)*$price))/$VES;
  
  $MARSHA = (($D1+$D2)*$price) - $Dp4;

	OutVES("10","Вес изделия, кг",$VES);

/////////////////////////////////////////////////////////////////////////
//
// Маржа, руб
//
/////////////////////////////////////////////////////////////////////////

	OutM("11","Маржа, руб",$MARSHA);

/////////////////////////////////////////////////////////////////////////
//
// Уровень прибыли на тонну, руб
//
/////////////////////////////////////////////////////////////////////////

	OutRT("12","Уровень прибыли на тонну, руб",$PRIB, 'prib', $VES, $D1_D2, $Dtemp, $DALL, $Dp4 );

/////////////////////////////////////////////////////////////////////////
//
// Цена на тонну, руб
//
/////////////////////////////////////////////////////////////////////////
	
		OutRT("13","Цена на тонну, руб",$RURTONN, 'rurtonn', $VES, $D1_D2, $Dtemp, $DALL, $Dp4 );

/////////////////////////////////////////////////////////////////////////
//
// Цена без НДС
//
/////////////////////////////////////////////////////////////////////////

	
	OutRC("14","Цена без НДС, руб",$DALL, 'price_without_nds', $VES, $D1_D2, $Dtemp, $DALL, $Dp4 );
?>