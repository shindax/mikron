<?php


	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$tid_zak = explode("|","|ОЗ|КР|СП|БЗ|ХЗ|ВЗ");
	
	$zak_IDs = explode("|",$_GET["p2"]);

	$csv_file = '';

	$csv_file .= "Заказ;;";
	$csv_file .= "Наименование ДСЕ;";
	$csv_file .= "Чертёж;";
	$csv_file .= "Операция;;";
	$csv_file .= "Оборуд.;";
	$csv_file .= "План;;";
	$csv_file .= "Факт;;";
	$csv_file .= "Осталось;;";
	$csv_file .= "Затр. часы;";
	$csv_file .= "Материал;";
	$csv_file .= "\n";

	$csv_file .= "Вид;";
	$csv_file .= "Номер;;;";
	$csv_file .= "Номер;";
	$csv_file .= "Наименование;;";
	$csv_file .= "Шт;";
	$csv_file .= "Н/Ч;";
	$csv_file .= "Шт;";
	$csv_file .= "Н/Ч;";
	$csv_file .= "Шт;";
	$csv_file .= "Н/Ч;;";
	$csv_file .= "\n";

	function FF($x) {
		$res = $x;
		if ($x==0) $res = "";
		return $res;
	}

	function OutMTK($izd) {
		global $csv_file, $db_prefix, $sf, $snf, $snn, $url;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet='".$izd["ID"]."') order by ORD");
		while ($oper = mysql_fetch_array($xxx)) {
			
			$xx2x = dbquery("SELECT * FROM ".$db_prefix."db_oper where (ID='".$oper["ID_oper"]."') ");
			$ope2r = mysql_fetch_array($xx2x);
			$xx3x = dbquery("SELECT * FROM ".$db_prefix."db_park where (ID='".$oper["ID_park"]."') ");
			$ope3r = mysql_fetch_array($xx3x);

			$num = 0;
			$f = 0;
			$nf = 0;

			$yyy = dbquery("SELECT FACT, NORM_FACT, NUM_FACT FROM ".$db_prefix."db_zadan where (ID_operitems='".$oper["ID"]."') and (EDIT_STATE='1') order by ID");
			while ($zad = mysql_fetch_array($yyy)) {
				$num = $num + $zad["NUM_FACT"]*1;
				$f = $f + $zad["FACT"]*1;
				$nf = $nf + $zad["NORM_FACT"]*1;
			}

			$sf = $sf + $f;
			$snf = $snf + $nf;
			$snn = $snn + $oper["NORM_ZAK"]*1;
			$rcount = $izd["RCOUNT"]*1;
			$rnorm = $oper["NORM_ZAK"]*1;
			
			$mat =  mysql_fetch_assoc(dbquery("SELECT *,okb_db_mat.OBOZ as MaterialName,okb_db_sort.OBOZ as MaterialSort  FROM okb_db_zn_zag
LEFT JOIN 
okb_db_mat ON okb_db_mat.ID = okb_db_zn_zag.ID_mat
LEFT JOIN okb_db_sort ON okb_db_sort.ID = okb_db_zn_zag.ID_sort
			WHERE ID_zakdet = " .$izd["ID"] ));
			
			$csv_file .= ";;;;";
			$csv_file .= $oper["ORD"].";";
			$csv_file .= $ope2r['NAME'].";";
			$csv_file .= $ope3r['MARK'].";";
			$csv_file .= $rcount.";";
			$csv_file .= $rnorm.";";
			$csv_file .= FF($num).";";
			$csv_file .= FF($nf).";";
			$csv_file .= round(FF($rcount-$num),2).";";
			$csv_file .= round(FF($rnorm-$nf),2).";";
			$csv_file .= FF($f).";";
			$csv_file .= (!empty($mat['MaterialName']) ? $mat['MaterialName'] . '/' . $mat['MaterialSort'] : '');
			$csv_file .= "\n";
			
			$csv_file = str_replace('.', ',', $csv_file);
		}
	}

	function OutIZD($izd,$n) {
		global $tid_zak, $csv_file, $db_prefix;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID='".$izd["ID"]."')");
		while ($chld = mysql_fetch_array($xxx)) {

		$xxx3 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$izd["ID_zak"]."')");
		$zak2 = mysql_fetch_array($xxx3);


			$csv_file .= $tid_zak[$zak2['TID']].";";
			$csv_file .= $zak2['NAME'].";;;;";
			$csv_file .= $chld["NAME"]."   ".$chld["OBOZ"].";;";
			$csv_file .= "\n";

			OutMTK($chld);

			OutIZD($chld,$n." ... /");

		}
	}
	
	for ($j=0;$j < count($zak_IDs);$j++) {

		$snn = 0;
		$sf = 0;
		$snf = 0;

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$zak_IDs[$j]."')");
		$zak = mysql_fetch_array($xxx);

		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak='".$zak_IDs[$j]."') and (PID='0')");
		$izd = mysql_fetch_array($xxx);

		$csv_file .= $tid_zak[$zak['TID']].";";
		$csv_file .= $zak['NAME'].";;;;";
		$csv_file .= $izd["NAME"]."   ".$izd["OBOZ"].";;";
		$csv_file .= "\n";

		// Выдали по сути первый ДСЕ
		OutMTK($izd);
		OutIZD($izd," ... /");

		$csv_file .= "\n;;;;;;Итого:";
		$csv_file .= $snn.";;";
		$csv_file .= $snf.";;";
		$csv_file .= ($snn-$snf).";";
		$csv_file .= $sf.";";
	}
	
	$file_name = 'export_tabel.csv';
	$file = fopen($file_name,"w");
	fwrite($file,trim($csv_file));
	fclose($file);

	header('Content-type: application/csv');
	header("Content-Disposition: inline; filename=".$file_name);
	readfile($file_name);
	unlink($file_name);

?>
