<?php
function CalculateZakaz_FReal($x) {
	$ret = number_format( $x, 1, ","," ");
	if ($x==floor($x)) $ret = number_format($x, 0, ","," ");
	return $ret;
}
function CalculateOperitem($id) {
	global $db_prefix;
	$operitem = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$id."')"); 
	$operitem = mysql_fetch_array($operitem);
	$zakdet = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$operitem["ID_zakdet"]."')"); 
	$zakdet = mysql_fetch_array($zakdet);
	if ($operitem["ID_zak"]*1!==$zakdet["ID_zak"]) dbquery("Update ".$db_prefix."db_operitems Set ID_zak:='".$zakdet["ID_zak"]."' where (ID='".$id."')");
	if (($operitem["NUM_ZAK"]*1!==$zakdet["RCOUNT"]*1) && ($operitem["BRAK"]*1==0)) dbquery("Update ".$db_prefix."db_operitems Set NUM_ZAK:='".$zakdet["RCOUNT"]."' where (ID='".$id."')");
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
	if ($operitem["BRAK"]*1==0) {		//если не исправление брака
	   if ($operitem["CHANCEL"]*1==0) {	//если не отмена
		$normzak = number_format((($zakdet["RCOUNT"]*1-$operitem["NUM_ZADEL"]*1)*($operitem["NORM"]/60))+($operitem["NORM_2"]/60), 2, '.', '');
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   } else {				//если отмена: Н/Ч план = Н/Ч факт
		$normzak = $norm_fact;
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   }
	} else {
	   if ($operitem["CHANCEL"]*1==0) {	//если не отмена
		$normzak = number_format(($operitem["NUM_ZAK"]*($operitem["NORM"]/60))+($operitem["NORM_2"]/60), 2, '.', '');
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   } else {				//если отмена: Н/Ч план = Н/Ч факт
		$normzak = $norm_fact;
		if ($operitem["NORM_ZAK"]*1!==$normzak*1) dbquery("Update ".$db_prefix."db_operitems Set NORM_ZAK:='".$normzak."' where (ID='".$id."')");
	   }
	}
	$summ_n = 0;
	$summ_nv = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet='".$zakdet["ID"]."')");
	while($item = mysql_fetch_array($result)) {
		$summ_nv += $item["NORM_FACT"]*1;
		$summ_n += $item["NORM_ZAK"]*1;
	}
	$percent = "";
	if ($summ_nv>0) {
		$percent = "~ %";
		if ($summ_n>0) $percent=CalculateZakaz_FReal(100*($summ_nv/$summ_n))."%";
	}
	if ($zakdet["PERCENT"]!==$percent) dbquery("Update ".$db_prefix."db_zakdet Set PERCENT:='".$percent."' where (ID='".$zakdet["ID"]."')");
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
}

$xxx1 = dbquery("SELECT ID FROM okb_db_zak WHERE EDIT_STATE=0 order by ID desc");
while($res1 = mysql_fetch_row($xxx1)){
  $xxx2 = dbquery("SELECT ID FROM okb_db_zakdet WHERE ID_zak=".$res1[0]." AND MTK_OK=1 order by ID desc");
  while($res2 = mysql_fetch_row($xxx2)){
    $xxx3 = dbquery("SELECT ID FROM okb_db_operitems WHERE ID_zakdet=".$res2[0]." AND NORM!='' AND NORM!=0 AND NORM_ZAK=0 AND CHANCEL=0 AND BRAK=0 order by ID desc");
    while($res3 = mysql_fetch_row($xxx3)){
      CalculateOperitem($res3[0]);
}}}

echo "ok";
?>