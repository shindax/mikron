<?php

	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	$csv_file = '';
	
	$resIDS = array();
	$sel_resIDS = array();
	$sel_resDATE = array();
	$sel_resFACT = array();
	
	$date_1 = $_GET['p1'];
	$date_2 = $_GET['p2'];
	$sel_res = $_GET['p3'];

	$resurses = dbquery("SELECT NAME, ID FROM okb_db_resurs where TID='0' order by NAME ");
	while ($rowres = mysql_fetch_array($resurses)){
		$resIDS[$rowres['ID']] = $rowres['NAME'];
	}
	$res = dbquery("SELECT ID_resurs, FACT, DATE, SMEN, TID FROM okb_db_tabel where (DATE>='".$date_1."') AND (DATE<='".$date_2."') ");
	while ($row = mysql_fetch_assoc($res)){
	  $sel_resIDS[$row['ID_resurs']] = $resIDS[$row['ID_resurs']];
	  $sel_resDATE[$row['ID_resurs']] = $sel_resDATE[$row['ID_resurs']].$row['DATE']."|";
	  $sel_resFACT[$row['ID_resurs']] = $sel_resFACT[$row['ID_resurs']].$row['FACT']."|";
	}
	
	$csv_file .= "Ресурс;";
	$delta_date = $date_2 - $date_1 + 1;
	$start_date = substr($date_1,6,2);
	$end_date = substr($date_2,6,2)+1;
	for ($start_date; $start_date < $end_date; $start_date++){
		$csv_file .= $start_date.";";
	}
	$start_date = substr($date_1,6,2);
	$csv_file .= "Факт\n";
	
	asort($sel_resIDS);
	$uniq_arr_1 = array_unique($sel_resIDS);
	if ($sel_res == "all") {
		$uniq_arr = $uniq_arr_1;
	}else{
		$eplx_sel_res = explode("|",$sel_res);
		foreach($eplx_sel_res as $key_4 => $val_4){
			$uniq_arr[$val_4]=$sel_resIDS[$val_4];
		}
	}
	foreach($uniq_arr as $key_1 => $val_1){
		if (strlen($val_1) > 2){
			$explDATE = explode("|",$sel_resDATE[$key_1]);
			$explFACT = explode("|",$sel_resFACT[$key_1]);
			asort($explDATE, SORT_NUMERIC);
			$csv_file .= $val_1.";";
			$tabelDATE = array();
			$tabelFACT = array();
			$FACT_sum = 0;
			foreach($explDATE as $key_2 => $val_2){
				$tabelDATE[substr($val_2,6,2)*1] = substr($val_2,6,2)*1;
				$tabelFACT[substr($val_2,6,2)*1] = str_replace(".",",",$explFACT[$key_2]);
				$FACT_sum = $FACT_sum + $explFACT[$key_2];
			}
			for ($start_date; $start_date < $end_date; $start_date++){
				if ($tabelDATE[$start_date]){
					$csv_file .= $tabelFACT[$start_date].";";
				}else{
					$csv_file .= ";";
				}
			}
			$start_date = substr($date_1,6,2);
			$csv_file .= $FACT_sum."\n";
		}
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