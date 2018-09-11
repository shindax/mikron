<?php

	if (!defined("MAV_ERP")) { die("Access Denied"); }

////////////////////////////////////////////////////////////////////////////
//
// Ôóíêöèÿ ïîëíîãî ïåðåðàñ÷¸òà çàêàçà ïî ID:
//
//	CalculateZakaz($id - ID çàêàçà)
//
// * Ðàñ÷¸ò âõîäèìîñòåé ÄÑÅ
// * Ðàñ÷¸ò íîðì íà çàêàç ïî îïåðàöèÿì
// * Ðàñ÷¸ò ñóììàðíîãî ôàêòà èç ñìåíîê ïî îïåðàöèÿì
// * Ðàñ÷¸ò èòîãîâûõ ñóìì äëÿ çàêàçà
//
////////////////////////////////////////////////////////////////////////////
//
// Ôóíêöèÿ ïåðåðàñ÷¸òà îòäåëüíîé îïåðàöèè ïî ID: (äëÿ ïðîâåäåíèÿ ÑÇ)
//
//	CalculateOperitem($id - ID îïåðàöèè ÌÒÊ)
//
// * Ðàñ÷¸ò ñóììàðíîãî ôàêòà èç ñìåíîê ïî îïåðàöèÿì
// * Ðàñ÷¸ò èòîãîâûõ ñóìì äëÿ çàêàçà
//
////////////////////////////////////////////////////////////////////////////

function CalculateZakaz_FReal($x) {
	$ret = number_format( $x, 1, ","," ");
	if ($x==floor($x)) $ret = number_format($x, 0, ","," ");
	return $ret;
}

function CalculateZakazDC($id,$count, $zak_id)
{
	global $db_prefix;

      dbquery("Update ".$db_prefix."db_zakdet Set ID_zak = $zak_id where (ID='".$id."')");

	dbquery("Update ".$db_prefix."db_zakdet Set RCOUNT:=RCOUNT+'".$count."' where (ID='".$id."')");

	$result = dbquery("SELECT ID, LID, COUNT, RCOUNT FROM ".$db_prefix."db_zakdet where (PID='".$id."') order by ID");
	while($res = mysql_fetch_array($result)) {
		if ($res["LID"]=="0") CalculateZakazDC($res["ID"],$count*$res["COUNT"], $zak_id);
		if ($res["LID"]!=="0") CalculateZakazDC($res["LID"],$count*$res["COUNT"] , $zak_id);
	}
}

function CalculateZakaz($id)
{
	global $db_prefix;

   /////////////////////////////////////////////

	//Çàêàç
	$zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$id."')");
	$zak = mysql_fetch_array($zak);

	//First DSE
	$det = dbquery("SELECT ID, COUNT FROM ".$db_prefix."db_zakdet where  (PID='0') and (ID_zak='".$id."')");
	$det = mysql_fetch_array($det);

	// ÎÁÍÓËÅÍÈÅ ÂÕÎÄÈÌÎÑÒÅÉ ÄÑÅ
	dbquery("Update ".$db_prefix."db_zakdet Set RCOUNT:='0' where (ID_zak='".$id."')");

	// ÐÀÑ×¨Ò ÂÕÎÄÈÌÎÑÒÅÉ ÄÑÅ
	CalculateZakazDC($det["ID"],$zak["DSE_COUNT"], $id );

			$zsumm_n = 0;
			$zsumm_nv = 0;

	// ÐÀÑ×¨Ò ÄÀÍÍÛÕ ÌÒÊ
	$detres = dbquery("SELECT ID, RCOUNT, PERCENT FROM ".$db_prefix."db_zakdet where  (ID_zak='".$id."') and (LID='0') order by ID");
	while($xdet = mysql_fetch_array($detres)) {

		// ÏÐÎÑÒÀÍÎÂÊÀ RCOUNT Â ËÈÄÛ
		dbquery("Update ".$db_prefix."db_zakdet Set RCOUNT:='".$xdet["RCOUNT"]."' where (LID='".$xdet["ID"]."')");

			$summ_n = 0;
			$summ_nv = 0;

		$operres = dbquery("SELECT ID, ID_zak, NORM_ZAK, NORM, NORM_2, NORM_FACT, FACT, BRAK, CHANCEL, NUM_ZAK, NUM_ZADEL FROM ".$db_prefix."db_operitems where (ID_zakdet='".$xdet["ID"]."') order by ID");
		while($xoper = mysql_fetch_array($operres)) {

			// ÏÐÎÑÒÀÍÎÂÊÀ ID_zak
			if ($xoper["ID_zak"]*1!==$id) dbquery("Update ".$db_prefix."db_operitems Set ID_zak:='".$id."' where (ID='".$xoper["ID"]."')");

			// ÏÐÎÑÒÀÍÎÂÊÀ RCOUNT åñëè íå èñïðàâëåíèå áðàêà
			if (($xoper["NUM_ZAK"]*1!==$xdet["RCOUNT"]*1) && ($xoper["BRAK"]*1==0)) dbquery("Update ".$db_prefix."db_operitems Set NUM_ZAK:='".$xdet["RCOUNT"]."' where (ID='".$xoper["ID"]."')");

			// ÐÀÑ×¨Ò ÔÀÊÒÀ ÈÇ ÑÇ
			$norm_fact = 0;
			$fact = 0;
			$zadres = dbquery("SELECT ID, FACT, NORM_FACT FROM ".$db_prefix."db_zadan where  (ID_operitems='".$xoper["ID"]."')  and (EDIT_STATE = '1') order by ID");
			while($zad = mysql_fetch_array($zadres)) {
				$fact += $zad["FACT"]*1;
				$norm_fact += $zad["NORM_FACT"]*1;
			}
			$fact = number_format($fact, 2, '.', '');
			$norm_fact = number_format($norm_fact, 2, '.', '');
			if ($xoper["NORM_FACT"]*1!==$norm_fact*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_FACT:='".$norm_fact."' where (ID='".$xoper["ID"]."')");
			if ($xoper["FACT"]*1!==$fact*1) dbquery("Update ".$db_prefix."db_operitems Set FACT:='".$fact."' where (ID='".$xoper["ID"]."')");

			// ÐÀÑ×¨Ò ÏËÀÍÎÂÛÕ ÍÎÐÌ
			if ($xoper["BRAK"]*1==0) {		//åñëè íå èñïðàâëåíèå áðàêà
			   if ($xoper["CHANCEL"]*1==0) {	//åñëè íå îòìåíà
				$normzak = number_format((($xdet["RCOUNT"]*1-$xoper["NUM_ZADEL"]*1)*($xoper["NORM"]/60))+($xoper["NORM_2"]/60), 2, '.', '');
				if ($xoper["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$xoper["ID"]."')");
			   } else {				//åñëè îòìåíà: Í/× ïëàí = Í/× ôàêò
				$normzak = $norm_fact;
				if ($xoper["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$xoper["ID"]."')");
			   }
			} else {				//åñëè èñïðàâëåíèå áðàêà
			   if ($xoper["CHANCEL"]*1==0) {	//åñëè íå îòìåíà
				$normzak = number_format(($xoper["NUM_ZAK"]*($xoper["NORM"]/60))+($xoper["NORM_2"]/60), 2, '.', '');
				if ($xoper["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$xoper["ID"]."')");
			   } else {				//åñëè îòìåíà: Í/× ïëàí = Í/× ôàêò
				$normzak = $norm_fact;
				if ($xoper["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$xoper["ID"]."')");
			   }
			}

								$koop = mysql_fetch_assoc(dbquery("SELECT
    oper_id,
     count,
    norm_hours
    FROM `okb_db_operations_with_coop_dep` WHERE oper_id = " . $xoper ['ID'] . "
	"));
	
			
				$summ_nv = $summ_nv + $norm_fact + $koop['norm_hours'] ;
				$summ_n = $summ_n + $normzak;
		}

			$zsumm_n = $zsumm_n + $summ_n;
			$zsumm_nv = $zsumm_nv + $summ_nv;


			$percent = "";
			if ($summ_nv>0) {
				$percent = "~ %";
				if ($summ_n>0) $percent=CalculateZakaz_FReal(100*($summ_nv/$summ_n))."%";
			}
			if ($xdet["PERCENT"]!==$percent) dbquery("Update ".$db_prefix."db_zakdet Set PERCENT:='".$percent."'  where (ID='".$xdet["ID"]."')");
	}

	$zsumm_v = 0;
	if ($zsumm_n>0) $zsumm_v = number_format(100*($zsumm_nv/$zsumm_n), 2, '.', ' ');
	$zsumm_no = number_format($zsumm_n-$zsumm_nv, 2, '.', ' ');
	$zsumm_n = number_format($zsumm_n, 2, '.', ' ');
	$zsumm_nv = number_format($zsumm_nv, 2, '.', ' ');

	dbquery("Update ".$db_prefix."db_zak Set SUMM_N:='".$zsumm_n."' where (ID='".$id."')");
	dbquery("Update ".$db_prefix."db_zak Set SUMM_NO:='".$zsumm_no."' where (ID='".$id."')");
	dbquery("Update ".$db_prefix."db_zak Set SUMM_NV:='".$zsumm_nv."' where (ID='".$id."')");
	dbquery("Update ".$db_prefix."db_zak Set SUMM_V:='".$zsumm_v."' where (ID='".$id."')");

   /////////////////////////////////////////////

}



function CalculateOperitem($id) {
	global $db_prefix;

   /////////////////////////////////////////////

	//operitem
	$operitem = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$id."')");
	$operitem = mysql_fetch_array($operitem);

	//zakdet
	$zakdet = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$operitem["ID_zakdet"]."')");
	$zakdet = mysql_fetch_array($zakdet);

	// ÏÐÎÑÒÀÍÎÂÊÀ ID_zak
	if ($operitem["ID_zak"]*1!==$zakdet["ID_zak"]) dbquery("Update ".$db_prefix."db_operitems Set ID_zak:='".$zakdet["ID_zak"]."' where (ID='".$id."')");

	// ÏÐÎÑÒÀÍÎÂÊÀ RCOUNT åñëè íå èñïðàâëåíèå áðàêà
	if (($operitem["NUM_ZAK"]*1!==$zakdet["RCOUNT"]*1) && ($operitem["BRAK"]*1==0)) dbquery("Update ".$db_prefix."db_operitems Set NUM_ZAK:='".$zakdet["RCOUNT"]."' where (ID='".$id."')");

	// ÐÀÑ×¨Ò ÔÀÊÒÀ ÈÇ ÑÇ
	$norm_fact = 0;
	$fact = 0;
	$zadres = dbquery("SELECT ID, FACT, NORM_FACT FROM ".$db_prefix."db_zadan where  (ID_operitems='".$id."') and (EDIT_STATE = '1') order by ID");
	while($zad = mysql_fetch_array($zadres)) {
		$fact += $zad["FACT"];
		$norm_fact += $zad["NORM_FACT"];
	}
	$fact = number_format($fact, 2, '.', '');
	$norm_fact = number_format($norm_fact, 2, '.', '');
	if ($operitem["NORM_FACT"]*1!==$norm_fact*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_FACT:='".$norm_fact."' where (ID='".$id."')");
	if ($operitem["FACT"]*1!==$fact*1) dbquery("Update ".$db_prefix."db_operitems Set FACT:='".$fact."' where (ID='".$id."')");

	// ÐÀÑ×¨Ò ÏËÀÍÎÂÛÕ ÍÎÐÌ
	if ($operitem["BRAK"]*1==0) {		//åñëè íå èñïðàâëåíèå áðàêà
	   if ($operitem["CHANCEL"]*1==0) {	//åñëè íå îòìåíà
		$normzak = number_format((($zakdet["RCOUNT"]*1-$operitem["NUM_ZADEL"]*1)*($operitem["NORM"]/60))+($operitem["NORM_2"]/60), 2, '.', '');
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   } else {				//åñëè îòìåíà: Í/× ïëàí = Í/× ôàêò
		$normzak = $norm_fact;
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   }
	} else {
	   if ($operitem["CHANCEL"]*1==0) {	//åñëè íå îòìåíà
		$normzak = number_format(($operitem["NUM_ZAK"]*($operitem["NORM"]/60))+($operitem["NORM_2"]/60), 2, '.', '');
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   } else {				//åñëè îòìåíà: Í/× ïëàí = Í/× ôàêò
		$normzak = $norm_fact;
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   }
	}

	// ÐÀÑ×¨Ò ÏÐÎÖÅÍÒÀ ÏÎ ÄÑÅ
	$summ_n = 0;
	$summ_nv = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet='".$zakdet["ID"]."')");
	while($item = mysql_fetch_array($result)) {
		$summ_nv += $item["NORM_FACT"]*1;
		$summ_n += $item["NORM_ZAK"]*1;
	}
	
	
					$koop = mysql_fetch_assoc(dbquery("SELECT
    oper_id,
    SUM(count) count,
    SUM( norm_hours ) norm_hours
    FROM `okb_db_operations_with_coop_dep` WHERE oper_id = " . $id . "
	"));
	
	$summ_nv += $koop['norm_hours'];
	
	$percent = "";
	if ($summ_nv>0) {
		$percent = "~ %";
		if ($summ_n>0) $percent=CalculateZakaz_FReal(100*($summ_nv/$summ_n))."%";
	}
	if ($zakdet["PERCENT"]!==$percent) dbquery("Update ".$db_prefix."db_zakdet Set PERCENT:='".$percent."' where (ID='".$zakdet["ID"]."')");

	// ÐÀÑ×¨Ò ÑÂÎÄÍÛÕ ÄÀÍÍÛÕ ÍÀ ÇÀÊÀÇ
	$zsumm_n = 0;
	$zsumm_nv = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zak='".$zakdet["ID_zak"]."')");
	while($item = mysql_fetch_array($result)) {
		$zsumm_nv += $item["NORM_FACT"]*1;
		$zsumm_n += $item["NORM_ZAK"]*1;
	}

	$zsumm_v = 0;
	if ($zsumm_n>0) $zsumm_v = number_format(100*($zsumm_nv/$zsumm_n), 2, '.', ' ');
	$zsumm_no = number_format($zsumm_n-$zsumm_nv, 2, '.', ' ');
	$zsumm_n = number_format($zsumm_n, 2, '.', ' ');
	$zsumm_nv = number_format($zsumm_nv, 2, '.', ' ');

	dbquery("Update ".$db_prefix."db_zak Set SUMM_N:='".$zsumm_n."' where (ID='".$zakdet["ID_zak"]."')");
	dbquery("Update ".$db_prefix."db_zak Set SUMM_NO:='".$zsumm_no."' where (ID='".$zakdet["ID_zak"]."')");
	dbquery("Update ".$db_prefix."db_zak Set SUMM_NV:='".$zsumm_nv."' where (ID='".$zakdet["ID_zak"]."')");
	dbquery("Update ".$db_prefix."db_zak Set SUMM_V:='".$zsumm_v."' where (ID='".$zakdet["ID_zak"]."')");

   /////////////////////////////////////////////

}

?>

<script>

$( function()
{
/*
  var tds = $( 'input[name^="db_zakdet_NAME_edit"]')
  var cnt = 0 ;

  $.each( tds , function( key, item )
    {
      var val = $( item ).val();
      var str = val.replace(/\"/g, '');
      $( item ).val( str );
    });
*/
});

</script>