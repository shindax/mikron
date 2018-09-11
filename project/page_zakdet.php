<?php
function CalculateZakaz_FReal($x) {
	$ret = number_format( $x, 1, ","," ");
	if ($x==floor($x)) $ret = number_format($x, 0, ","," ");
	return $ret;
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

	// ПРОСТАНОВКА ID_zak
	if ($operitem["ID_zak"]*1!==$zakdet["ID_zak"]) dbquery("Update ".$db_prefix."db_operitems Set ID_zak:='".$zakdet["ID_zak"]."' where (ID='".$id."')");

	// ПРОСТАНОВКА RCOUNT если не исправление брака
	if (($operitem["NUM_ZAK"]*1!==$zakdet["RCOUNT"]*1) && ($operitem["BRAK"]*1==0)) dbquery("Update ".$db_prefix."db_operitems Set NUM_ZAK:='".$zakdet["RCOUNT"]."' where (ID='".$id."')");

	// РАСЧЁТ ФАКТА ИЗ СЗ
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

	// РАСЧЁТ ПЛАНОВЫХ НОРМ
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

	// РАСЧЁТ ПРОЦЕНТА ПО ДСЕ
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

	// РАСЧЁТ СВОДНЫХ ДАННЫХ НА ЗАКАЗ
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

     $norm_edit = false;
     $mtk_edit = false;
     $dse_edit = false;
     $show_brak = false;

     $htext = "";

     $result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$id."')");
     if ($dse = mysql_fetch_array($result)) {
          $htext = "<H2>Заказ №: ".FVal($dse,"db_zakdet","ID_zak")."</H2>";
     }

     $result = dbquery("SELECT ID FROM ".$db_prefix."db_operitems where (ID_zakdet='".$id."') and (BRAK='1') limit 0,1");
     if ($brak = mysql_fetch_array($result)) $show_brak = true;

     $zak_url = "";

     $result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$dse["ID_zak"]."')");
     if ($zak = mysql_fetch_array($result)) {
          $zak_url = "<div class='links'><a href='index.php?do=show&formid=39&id=".$zak["ID"]."&p3=".$_GET['id']."'>В заказ</a></div>";
          if ($zak["EDIT_STATE"]*1==0) {
               if ($zak["IZD_CORR"]*1==0) $dse_edit = true;   // Спецификация ДСЕ корректно заполнена
               if ($dse["MTK_OK"]*1==0) $mtk_edit = true;     // МТК корректно заполнена
               if ($dse["NORM_OK"]*1==0) $norm_edit = true;   // Нормы расхода корректно заполнены
          }
     }

///////////////////////////////////////////////////////////////////////////////////////////////////////////

     if ($zak["EDIT_STATE"]*1==0) {

///////////////////////////////////////////////////////////////////////////////////////////////////////////

     if (isset($_GET["set_mtk_ok"])) {
          if (db_check("db_zakdet","MTK_OK")) {
            dbquery("Update ".$db_prefix."db_zakdet Set MTK_OK:='1' where (ID='".$id."')");
			$res_5 = dbquery("SELECT ID FROM ".$db_prefix."db_operitems where ID_zakdet='".$id."' ");
			while($txt_5 = mysql_fetch_array($res_5)) {
				CalculateOperitem($txt_5['ID']);
			}
          }
          redirect($pageurl."&event","script");
          exit();
     }

     if (isset($_GET["set_mtk_notok"])) {
          if (db_check("db_zakdet","MTK_OK")) {
                dbquery("Update ".$db_prefix."db_zakdet Set MTK_OK:='0' where (ID='".$id."')");
          }
          redirect($pageurl."&event","script");
          exit();
     }

     if (isset($_GET["set_norm_ok"])) {
          if (db_check("db_zakdet","NORM_OK")) {
                dbquery("Update ".$db_prefix."db_zakdet Set NORM_OK:='1' where (ID='".$id."')");
          }
          redirect($pageurl."&event","script");
          exit();
     }

     if (isset($_GET["set_norm_notok"])) {
          if (db_check("db_zakdet","NORM_OK")) {
                dbquery("Update ".$db_prefix."db_zakdet Set NORM_OK:='0' where (ID='".$id."')");
          }
          redirect($pageurl."&event","script");
          exit();
     }

///////////////////////////////////////////////////////////////////////////////////////////////////////////

     }

///////////////////////////////////////////////////////////////////////////////////////////////////////////

     $redact_norm_link = "";
     if ((db_check("db_zakdet","NORM_OK")) && ($zak["EDIT_STATE"]*1==0)) {
        if ($norm_edit) $redact_norm_link = "<br><a href='$pageurl&set_norm_ok'>Нормы расхода заполнены</a>";
        if (!$norm_edit) $redact_norm_link = "<br><a href='$pageurl&set_norm_notok'>Редактировать нормы расхода</a>";
     }

     $redact_mtk_link = "";
     if ((db_check("db_zakdet","MTK_OK")) && ($zak["EDIT_STATE"]*1==0)) {
        if ($mtk_edit) $redact_mtk_link = "<br><a href='$pageurl&set_mtk_ok'>МТК заполнено</a>";
        if (!$mtk_edit) $redact_mtk_link = "<br><a href='$pageurl&set_mtk_notok'>Редактировать МТК</a>";
     }
?>