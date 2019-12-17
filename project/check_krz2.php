<?php

$NDS_val = 20;
$numdays = 45;

	function CheckCreateZakTime( $xxx, $shift = 0 ) 
	{
		global $numdays;

		$res = true;

	   // Проверка ограничения по дате
		$xdate=date("Ymd",mktime()-(( $numdays + $shift ) *86400))*1;
		
		if ($xxx["DATE_START"]*1<$xdate) 
      $res = false;

		return $res;
	}

	function CheckCreateZak( $xxx, $shift = 0 ) 
	{
		global $db_prefix, $numdays, $NDS_val;

		$res = true;

	   // Проверка не создан ли заказ
		if ($xxx["EDIT_STATE"]*1!==0) $res = false;

	   // Проверка заполнения дат
		if ($xxx["D1"]*1==0) $res = false;
		if ($xxx["D2"]*1==0) $res = false;
		if ($xxx["D3"]*1==0) $res = false;
		//if ($xxx["D4"]*1==0) $res = false;
		if ($xxx["D5"]*1==0) $res = false;
		//if ($xxx["D6"]*1==0) $res = false;
		//if ($xxx["D7"]*1==0) $res = false;
		if ($xxx["D8"]*1==0) $res = false;
		//if ($xxx["D9"]*1==0) $res = false;
		if ($xxx["D10"]*1==0) $res = false;
		if ($xxx["D11"]*1==0) $res = false;
		if ($xxx["D12"]*1==0) $res = false;
		if ($xxx["D13"]*1==0) $res = false;
		if ($xxx["D14"]*1==0) $res = false;
		//if ($xxx["D15"]*1==0) $res = false;
		//if ($xxx["D16"]*1==0) $res = false;
		//if ($xxx["D17"]*1==0) $res = false;

	   // Проверка заполнения данных экспертом
		if ($xxx["EXPERT_STATE"]*1==0) $res = false;

	   // Проверка заполнения планов приходов
		if ($res == true) {
			$S1 = 0;
			$S2 = 0;
			$PLAN = 0;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_arrival_plan where (ID_krz2 = '".$xxx["ID"]."') order by ID");
			while($arr = mysql_fetch_array($result)) {
				$PLAN += ($arr["PLAN"]*1);
				$S1 += ($arr["S1"]*1);
				$S2 += ($arr["S2"]*1);
				if ($arr["DATE"]*1==0) $res = false;
			}

			if (Round($PLAN)!==Round(1*$xxx["PRICE"])) $res = false;
			if (Round($S1)!==Round(((100+$NDS_val)/100)*$xxx["S1"])) $res = false;
			if (Round($S2)!==Round(((100+$NDS_val)/100)*$xxx["S2"])) $res = false;
			if ($PLAN==0) $res = false;

		}

	   // Проверка ограничения по дате
		if (!CheckCreateZakTime( $xxx, $shift )) $res = false;

		return $res;
	}




?>