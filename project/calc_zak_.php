<?php
if (!defined("MAV_ERP")) { die("Access Denied"); }

////////////////////////////////////////////////////////////////////////////
//
// Функция полного перерасчёта заказа по ID:
//
//	CalculateZakaz($id - ID заказа)
//
// * Расчёт входимостей ДСЕ
// * Расчёт норм на заказ по операциям
// * Расчёт суммарного факта из сменок по операциям
// * Расчёт итоговых сумм для заказа
//
////////////////////////////////////////////////////////////////////////////
//
// Функция перерасчёта отдельной операции по ID: (для проведения СЗ)
//
//	CalculateOperitem($id - ID операции МТК)
//
// * Расчёт суммарного факта из сменок по операциям
// * Расчёт итоговых сумм для заказа
//
////////////////////////////////////////////////////////////////////////////

function CalculateZakaz_FReal($x) 
{
	$ret = number_format( $x, 1, ","," ");
	if ($x==floor($x)) 
		$ret = number_format($x, 0, ","," ");
	return $ret;
}

function CalculateZakazDC($id,$count, $zak_id)
{
	global $db_prefix;

	dbquery("	UPDATE ".$db_prefix."db_zakdet 
				SET 
				ID_zak = $zak_id,
				RCOUNT=RCOUNT+$count
				where ID=$id");

//  dbquery("Update ".$db_prefix."db_zakdet Set ID_zak = $zak_id where (ID='".$id."')");
//	dbquery("Update ".$db_prefix."db_zakdet Set RCOUNT:=RCOUNT+'".$count."' where (ID='".$id."')");

	$result = dbquery("	SELECT ID, LID, COUNT, RCOUNT 
						FROM ".$db_prefix."db_zakdet 
						WHERE PID=$id
						ORDER BY ID");
	
	while($res = mysql_fetch_array($result)) 
	{
		if ($res["LID"]=="0") 
			CalculateZakazDC($res["ID"],$count*$res["COUNT"], $zak_id);
			else
				CalculateZakazDC($res["LID"],$count*$res["COUNT"] , $zak_id);
	}
}

function CalculateZakaz($id)
{
	global $db_prefix;

	//Заказ
	$zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$id."')");
	$zak = mysql_fetch_array($zak);

	//First DSE
	$det = dbquery("SELECT ID, COUNT FROM ".$db_prefix."db_zakdet 
					WHERE  
					PID=0
					AND
					ID_zak=$id");

	$det = mysql_fetch_array($det);

	// ОБНУЛЕНИЕ ВХОДИМОСТЕЙ ДСЕ
	dbquery("	UPDATE ".$db_prefix."db_zakdet 
				SET RCOUNT=0 
				WHERE 
				ID_zak=$id");

	// РАСЧЁТ ВХОДИМОСТЕЙ ДСЕ
	CalculateZakazDC($det["ID"],$zak["DSE_COUNT"], $id );

			$zsumm_n = 0;
			$zsumm_nv = 0;

	// РАСЧЁТ ДАННЫХ МТК
	$detres = dbquery("SELECT ID, RCOUNT, PERCENT 
						FROM ".$db_prefix."db_zakdet 
						WHERE  
						ID_zak=$id
						AND 
						LID=0 
						ORDER BY ID");
	while($xdet = mysql_fetch_array($detres)) 
	{
		// ПРОСТАНОВКА RCOUNT В ЛИДЫ
		dbquery("	UPDATE ".$db_prefix."db_zakdet 
					SET RCOUNT=".$xdet["RCOUNT"]."
					WHERE 
					LID=".$xdet["ID"]);

			$summ_n = 0;
			$summ_nv = 0;

		$operres = dbquery("
							SELECT ID, ID_zak, NORM_ZAK, NORM, NORM_2, NORM_FACT, FACT, BRAK, CHANCEL, NUM_ZAK, NUM_ZADEL 
							FROM ".$db_prefix."db_operitems 
							WHERE 
							ID_zakdet=".$xdet["ID"]." 
							ORDER BY ID");
		while($xoper = mysql_fetch_array($operres)) 
		{
			// ПРОСТАНОВКА ID_zak
			if ($xoper["ID_zak"]*1!==$id) dbquery("Update ".$db_prefix."db_operitems Set ID_zak:='".$id."' where (ID='".$xoper["ID"]."')");

			// ПРОСТАНОВКА RCOUNT если не исправление брака
			if (
					( $xoper["NUM_ZAK"] * 1 !== $xdet["RCOUNT"] * 1 ) 
					&& 
					( $xoper["BRAK"] * 1 == 0 )
				) 
				dbquery("Update ".$db_prefix."db_operitems Set NUM_ZAK:='".$xdet["RCOUNT"]."' where (ID='".$xoper["ID"]."')");

			// РАСЧЁТ ФАКТА ИЗ СЗ
			$norm_fact = 0;
			$fact = 0;
			$zadres = dbquery("SELECT ID, FACT, NORM_FACT FROM ".$db_prefix."db_zadan 
								WHERE  
								(ID_operitems='".$xoper["ID"]."') 
								AND 
								(EDIT_STATE = '1') 
								ORDER BY ID");

			while( $zad = mysql_fetch_array( $zadres ) ) 
			{
				$fact += $zad["FACT"]*1;
				$norm_fact += $zad["NORM_FACT"]*1;
			}

			$fact = number_format($fact, 2, '.', '');
			$norm_fact = number_format($norm_fact, 2, '.', '');
			
			if ( $xoper["NORM_FACT"] * 1 !== $norm_fact * 1 ) 
				dbquery("UPDATE ".$db_prefix."db_operitems 
					SET NORM_FACT ='".$norm_fact."' 
					WHERE 
					(ID='".$xoper["ID"]."')");
			
			if ($xoper["FACT"]*1!==$fact*1) 
				dbquery("UPDATE ".$db_prefix."db_operitems 
						 SET FACT ='".$fact."' 
						 WHERE 
						 (ID='".$xoper["ID"]."')");

			// РАСЧЁТ ПЛАНОВЫХ НОРМ
			if ( $xoper["BRAK"] * 1 == 0 ) 
			{		//если не исправление брака
			   if ( $xoper["CHANCEL"] * 1 == 0 ) 
			   {	//если не отмена
				$normzak = number_format((($xdet["RCOUNT"]*1-$xoper["NUM_ZADEL"]*1)*($xoper["NORM"]/60))+($xoper["NORM_2"]/60), 2, '.', '');

				if ( $xoper["NORM_ZAK"] * 1 !== $normzak * 1 ) 
					dbquery("UPDATE ".$db_prefix."db_operitems 
							 SET NORM_ZAK:='".$normzak."' 
							 WHERE 
							 ID=".$xoper["ID"]);
			   } // if ( $xoper["CHANCEL"] * 1 == 0 )  
			   else 
			   {	//если отмена: Н/Ч план = Н/Ч факт
				$normzak = $norm_fact;
				if ( $xoper["NORM_ZAK"] * 1 !== $normzak * 1 ) 
					dbquery("UPDATE ".$db_prefix."db_operitems 
							 SET NORM_ZAK:='".$normzak."' 
							 WHERE 
							 ID=".$xoper["ID"]);
			   } // else если отмена: Н/Ч план = Н/Ч факт
			} // if ($xoper["BRAK"]*1==0)  
			else 
			{	// если исправление брака
			   if ( $xoper["CHANCEL"] * 1 == 0 ) 
			   { //если не отмена
				$normzak = number_format(($xoper["NUM_ZAK"]*($xoper["NORM"]/60))+($xoper["NORM_2"]/60), 2, '.', '');
				if ( $xoper["NORM_ZAK"]*1!==$normzak*1) 
					dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' 
							WHERE 
							ID=".$xoper["ID"]);
			   } 
			   else 
			   {	//если отмена: Н/Ч план = Н/Ч факт
				$normzak = $norm_fact;
				if ( $xoper["NORM_ZAK"]*1!==$normzak*1 ) 
					dbquery("UPDATE ".$db_prefix."db_operitems 
							SET NORM_ZAK:='".$normzak."' 
							WHERE 
							ID=".$xoper["ID"]);
			   }
			}// else если исправление брака

				$koop = mysql_fetch_assoc(dbquery("SELECT
			    oper_id,
			     count,
			    norm_hours
			    FROM `okb_db_operations_with_coop_dep` 
			    WHERE 
			    oper_id = ".$xoper ['ID']));
	
			
				$summ_nv = $summ_nv + $norm_fact + $koop['norm_hours'] ;
				$summ_n = $summ_n + $normzak;
		
		}// while($xoper = mysql_fetch_array($operres))

			$zsumm_n = $zsumm_n + $summ_n;
			$zsumm_nv = $zsumm_nv + $summ_nv;


			$percent = "";
			if ($summ_nv>0) 
			{
				$percent = "~ %";
				if ($summ_n>0) 
					$percent=CalculateZakaz_FReal(100*($summ_nv/$summ_n))."%";
			}
			if ( $xdet["PERCENT"] !== $percent ) 
				dbquery("	UPDATE ".$db_prefix."db_zakdet 
							SET PERCENT='".$percent."'
							WHERE 
							ID=".$xdet["ID"]);
	} //while($xdet = mysql_fetch_array($detres)) 

	$zsumm_v = 0;
	
	if ($zsumm_n>0) 
		$zsumm_v = number_format(100*($zsumm_nv/$zsumm_n), 2, '.', ' ');
	
	$zsumm_no = number_format($zsumm_n-$zsumm_nv, 2, '.', ' ');
	$zsumm_n = number_format($zsumm_n, 2, '.', ' ');
	$zsumm_nv = number_format($zsumm_nv, 2, '.', ' ');

	dbquery("UPDATE ".$db_prefix."db_zak 
			 SET 
			 SUMM_N:='".$zsumm_n."', 
			 SUMM_NO:='".$zsumm_no."', 
			 SUMM_NV:='".$zsumm_nv."',
			 SUMM_V:='".$zsumm_v."'
			 WHERE (ID='".$id."')");

	// dbquery("Update ".$db_prefix."db_zak Set SUMM_N:='".$zsumm_n."' where (ID='".$id."')");
	// dbquery("Update ".$db_prefix."db_zak Set SUMM_NO:='".$zsumm_no."' where (ID='".$id."')");
	// dbquery("Update ".$db_prefix."db_zak Set SUMM_NV:='".$zsumm_nv."' where (ID='".$id."')");
	// dbquery("Update ".$db_prefix."db_zak Set SUMM_V:='".$zsumm_v."' where (ID='".$id."')");


}

function CalculateOperitem($id) 
{
	global $db_prefix;

   /////////////////////////////////////////////

	//operitem
	$operitem = dbquery("SELECT * FROM ".$db_prefix."db_operitems WHERE ID=$id");
	$operitem = mysql_fetch_array($operitem);

	//zakdet
	$zakdet = dbquery("SELECT * FROM ".$db_prefix."db_zakdet WHERE ID=".$operitem["ID_zakdet"]);
	$zakdet = mysql_fetch_array($zakdet);

	// ПРОСТАНОВКА ID_zak
	if ($operitem["ID_zak"]*1!==$zakdet["ID_zak"]) 
		dbquery("UPDATE ".$db_prefix."db_operitems 
				 SET ID_zak:='".$zakdet["ID_zak"]."' 
				 WHERE ID=$id");

	// ПРОСТАНОВКА RCOUNT если не исправление брака
	if (
			( $operitem["NUM_ZAK"] * 1 !== $zakdet["RCOUNT"] * 1 ) 
				&& 
			( $operitem["BRAK"] * 1 == 0 )
		) 
		dbquery("UPDATE ".$db_prefix."db_operitems 
				 SET NUM_ZAK:='".$zakdet["RCOUNT"]."' where ID=$id");

	// РАСЧЁТ ФАКТА ИЗ СЗ
	$norm_fact = 0;
	$fact = 0;
	$zadres = dbquery("SELECT ID, FACT, NORM_FACT FROM ".$db_prefix."db_zadan 
						WHERE  
						ID_operitems=$id
						AND 
						EDIT_STATE = 1 
						ORDER BY ID");
	
	while($zad = mysql_fetch_array($zadres)) 
	{
		$fact += $zad["FACT"];
		$norm_fact += $zad["NORM_FACT"];
	}

	$fact = number_format($fact, 2, '.', '');
	$norm_fact = number_format($norm_fact, 2, '.', '');
	
	if ($operitem["NORM_FACT"]*1!==$norm_fact*1) 
		dbquery("UPDATE ".$db_prefix."db_operitems 
				 SET NORM_FACT:='".$norm_fact."' 
				 WHERE ID=$id");
	
	if ($operitem["FACT"]*1!==$fact*1) 
		dbquery("UPDATE ".$db_prefix."db_operitems 
				 SET FACT='".$fact."' 
				 WHERE ID=$id");

	// РАСЧЁТ ПЛАНОВЫХ НОРМ
	if ($operitem["BRAK"]*1==0) 
	{	//если не исправление брака
	   if ($operitem["CHANCEL"]*1==0) 
	   {	//если не отмена
		
		$normzak = number_format((($zakdet["RCOUNT"]*1-$operitem["NUM_ZADEL"]*1)*($operitem["NORM"]/60))+($operitem["NORM_2"]/60), 2, '.', '');
		
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) 
			dbquery("	UPDATE ".$db_prefix."db_operitems 
						SET NORM_ZAK:='".$normzak."' 
						WHERE ID=$id");
	   } // if ($operitem["CHANCEL"]*1==0) 
	   else 
	   {				//если отмена: Н/Ч план = Н/Ч факт
		$normzak = $norm_fact;
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) 
			dbquery("	UPDATE ".$db_prefix."db_operitems 
						SET NORM_ZAK:='".$normzak."' 
						WHERE ID=$id");
	   }// else 
	} // if ($operitem["BRAK"]*1==0) 
	else 
	{
	   if ($operitem["CHANCEL"]*1==0) 
	   {	//если не отмена
		$normzak = number_format(($operitem["NUM_ZAK"]*($operitem["NORM"]/60))+($operitem["NORM_2"]/60), 2, '.', '');
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) 
			dbquery("	UPDATE ".$db_prefix."db_operitems 
						SET NORM_ZAK='".$normzak."' 
						WHERE ID=$id");
	   } // if ($operitem["CHANCEL"]*1==0) 
	   else 
	   {				//если отмена: Н/Ч план = Н/Ч факт
		$normzak = $norm_fact;
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) 
			dbquery("	UPDATE ".$db_prefix."db_operitems 
						SET NORM_ZAK:='".$normzak."' 
						WHERE ID=$id");
	   }// else 
	}

	// РАСЧЁТ ПРОЦЕНТА ПО ДСЕ
	$summ_n = 0;
	$summ_nv = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems WHERE ID_zakdet=".$zakdet["ID"]);
	while($item = mysql_fetch_array($result)) 
	{
		$summ_nv += $item["NORM_FACT"] * 1;
		$summ_n += $item["NORM_ZAK"] * 1;
	}
	
	$koop = mysql_fetch_assoc(dbquery("
		SELECT
	    oper_id,
	    SUM(count) count,
	    SUM( norm_hours ) norm_hours
	    FROM `okb_db_operations_with_coop_dep` 
	    WHERE oper_id =$id"));
	
	$summ_nv += $koop['norm_hours'];
	
	$percent = "";
	if ($summ_nv>0) 
	{
		$percent = "~ %";
		if ( $summ_n > 0 ) 
			$percent = CalculateZakaz_FReal( 100 * ( $summ_nv / $summ_n ) )."%";
	}
	if ($zakdet["PERCENT"]!==$percent) 
		dbquery("	UPDATE ".$db_prefix."db_zakdet 
					SET PERCENT:='".$percent."' 
					WHERE ID=".$zakdet["ID"]."");

	// РАСЧЁТ СВОДНЫХ ДАННЫХ НА ЗАКАЗ
	$zsumm_n = 0;
	$zsumm_nv = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zak='".$zakdet["ID_zak"]."')");
	while($item = mysql_fetch_array($result)) 
	{
		$zsumm_nv += $item["NORM_FACT"]*1;
		$zsumm_n += $item["NORM_ZAK"]*1;
	} // while($item = mysql_fetch_array($result)) 

	$zsumm_v = 0;
	if ($zsumm_n>0) 
		$zsumm_v = number_format(100*($zsumm_nv/$zsumm_n), 2, '.', ' ');

	$zsumm_no = number_format($zsumm_n-$zsumm_nv, 2, '.', ' ');
	$zsumm_n = number_format($zsumm_n, 2, '.', ' ');
	$zsumm_nv = number_format($zsumm_nv, 2, '.', ' ');

	dbquery("	UPDATE ".$db_prefix."db_zak 
				SET 
				SUMM_N:='$zsumm_n',
				SUMM_NO:='$zsumm_no',
				SUMM_NV:='$zsumm_nv',
				SUMM_V:='$zsumm_v'				
				WHERE ID=".$zakdet["ID_zak"]);

	// dbquery("Update ".$db_prefix."db_zak Set SUMM_N:='".$zsumm_n."' where (ID='".$zakdet["ID_zak"]."')");
	// dbquery("Update ".$db_prefix."db_zak Set SUMM_NO:='".$zsumm_no."' where (ID='".$zakdet["ID_zak"]."')");
	// dbquery("Update ".$db_prefix."db_zak Set SUMM_NV:='".$zsumm_nv."' where (ID='".$zakdet["ID_zak"]."')");
	// dbquery("Update ".$db_prefix."db_zak Set SUMM_V:='".$zsumm_v."' where (ID='".$zakdet["ID_zak"]."')");

}// function CalculateOperitem($id) 
