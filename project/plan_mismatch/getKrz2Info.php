<?php

$NDS_val = 20; // ÍÄÑ %
$det_array = [];
$count_array = [];

function OpenCalcID($item,$count) 
{
	global $db_prefix, $det_array, $count_array;

	if ($count>0) {
		$det_array[] = $item;
		$count_array[] = $count;
		$val = $item["ID"];

		if( $val )
		{
			$result = dbquery("SELECT * FROM okb_db_krz2det where PID=$val");
			while($res = mysql_fetch_array($result))
				OpenCalcID($res,$count*$res["COUNT"]);
		}
	}
}

function Summ($field) {
	global $det_array, $count_array;

	$res = 0;
	$lcount = count($count_array);
	for ( $j = 0; $j < $lcount; $j ++ ) 
		$res = $res + ($det_array[$j][$field]*$count_array[$j]);

	return $res;
}

function SummL($field) {
	global $det_array, $count_array;
	$lcount = count($count_array);
	$res = 0;
	for ( $j = 0; $j < $lcount; $j ++ ) 
		$res = $res + $det_array[$j][$field];

	return $res;
}

function GetKrz2Info( $ID_krz2 )
{
	global $NDS_val;

	$result = dbquery("SELECT * FROM okb_db_krz2det where PID=0 and ID_krz2=$ID_krz2");
	
	if ($det = mysql_fetch_array($result)) 
	{
		$count = $det["COUNT"];
		OpenCalcID($det,$count);
	}
	
	$result = dbquery("SELECT * FROM okb_db_krz2 where ID=$ID_krz2");
	$krz = mysql_fetch_array($result);

	$price_all = $krz["PRICE"]*( 100 / ( 100 + $NDS_val )); 

	$D1_1 = SummL("D1");
	$D1_2 = SummL("D2");
	$D1 = $D1_1+$D1_2;

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

	 $res_norm_plan = $D1+$D2;

 return $res_norm_plan;
}