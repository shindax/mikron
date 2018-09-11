<?php
$defult_data2 = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+1209600;
$defult_data1 = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+86400;
$count_Mday = date("t", strtotime(date("Y-m")));
$mon_arr = explode("|","|январь|февраль|март|апрель|май|июнь|июль|август|сентябрь|октябрь|ноябрь|декабрь");
$tid_zak = explode("|","|ОЗ|КР|СП|БЗ|ХЗ|ВЗ");
$tid_oper = explode("|", "|Заготовка|Сборка-сварка|Механообработка|Сборка|Термообработка|Упаковка|Окраска|Прочее");
$right_us = explode("|",$user['ID_rightgroups']);

if ($_GET['p1']){
	$sqldate_1 = $_GET['p1'];
	$sqldate_2 = $_GET['p2'];
	$inpdate_1 = substr($_GET['p1'],0,4)."-".substr($_GET['p1'],4,2)."-".substr($_GET['p1'],6,2);
	$inpdate_2 = substr($_GET['p2'],0,4)."-".substr($_GET['p2'],4,2)."-".substr($_GET['p2'],6,2);
}else{
	$sqldate_1 = date("Ymd")+1;
	$sqldate_2 = date("Ymd",$defult_data2);
	$inpdate_1 = date("Y",$defult_data1)."-".date("m",$defult_data1)."-".date("d",$defult_data1);
	$inpdate_2 = date("Y",$defult_data2)."-".date("m",$defult_data2)."-".date("d",$defult_data2);
}
$sql_month_1 = substr($sqldate_1,4,2)*1;
$sql_month_2 = substr($sqldate_2,4,2)*1;

if ($sql_month_1!==$sql_month_2) $colspan_thead = ($count_Mday-substr($sqldate_1,6,2)+substr($sqldate_2,6,2)+1);
if ($sql_month_1==$sql_month_2) $colspan_thead = (substr($sqldate_2,6,2)-substr($sqldate_1,6,2)+1);

	if (strpos($_SERVER['PHP_SELF'], 'index.php')==1){
		echo "С какое:&nbsp;&nbsp;&nbsp;&nbsp;<input id=inp_date_1 type=date min='".date("Y")."-".date("m")."-".(date("d")+1)."' value='".$inpdate_1."'>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;по какое:&nbsp;&nbsp;&nbsp;&nbsp;<input id=inp_date_2 type=date value='".$inpdate_2."'>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value='Применить' onclick='location.href=\"index.php?do=show&formid=".$_GET['formid']."&p1=\"+document.getElementById(\"inp_date_1\").value.substr(0,4)+document.getElementById(\"inp_date_1\").value.substr(5,2)+document.getElementById(\"inp_date_1\").value.substr(8)+\"&p2=\"+document.getElementById(\"inp_date_2\").value.substr(0,4)+document.getElementById(\"inp_date_2\").value.substr(5,2)+document.getElementById(\"inp_date_2\").value.substr(8)'><br><br>";
	}
	echo "<b style='margin-left:50px; font-size:20px;'>Планирование производства на период c ".substr($sqldate_1,6,2).".".substr($sqldate_1,4,2).".".substr($sqldate_1,0,4)." по ".substr($sqldate_2,6,2).".".substr($sqldate_2,4,2).".".substr($sqldate_2,0,4)."</b>";
	echo "<table style='page-break-after:always;' width='1500px' class='rdtbl tbl'><tbody>";
	echo "<thead><tr class=first>
	<td rowspan=2>ФИО</td>
	<td rowspan=2>Примечание</td>
	<td colspan=".$colspan_thead.">Сколько запланировано по дням c ".substr($sqldate_1,6,2).".".substr($sqldate_1,4,2).".".substr($sqldate_1,0,4)." по ".substr($sqldate_2,6,2).".".substr($sqldate_2,4,2).".".substr($sqldate_2,0,4)."&nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;смена Н/Ч (шт.)</td>
	<td rowspan=2>ИТОГО<br>за период</td></tr><tr class=first>";
	
	if ($sql_month_1!==$sql_month_2){
		for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=$count_Mday; $a_a++){
			echo "<td>".($a_a)."</td>";
		}
		for ($a_a=1; $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
			echo "<td>".($a_a)."</td>";
		}
	}
	if ($sql_month_1==$sql_month_2){
		for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
			echo "<td>".($a_a)."</td>";
		}
	}
	
	echo "</tr></thead>";
		
	$res_arr = array();
	$date_arr = array();
	$norm_arr = array();
	$num_arr = array();
	$res_nam_arr = array();
	$smen_arr = array();
	$tidzak_arr = array();
	$nam_zak_arr = array();
	$nam2_zak_arr = array();
	$oboz_zakdet_arr = array();
	$name_zakdet_arr = array();
	$zadan_id_arr = array();
	$oper_nam_arr = array();
	$datefull_arr = array();
	$sql_qry_1 = dbquery("SELECT okb_db_zadan.NORM, okb_db_zadan.NUM, RIGHT(okb_db_zadan.DATE,2), (SELECT okb_db_resurs.NAME FROM okb_db_resurs where okb_db_zadan.ID_resurs=okb_db_resurs.ID), okb_db_zadan.ID_resurs, okb_db_zadan.SMEN, (SELECT okb_db_zak.TID FROM okb_db_zak where okb_db_zadan.ID_zak=okb_db_zak.ID), 
	(SELECT okb_db_zak.NAME FROM okb_db_zak where okb_db_zadan.ID_zak=okb_db_zak.ID), (SELECT okb_db_zak.DSE_NAME FROM okb_db_zak where okb_db_zadan.ID_zak=okb_db_zak.ID), (SELECT okb_db_zakdet.OBOZ FROM okb_db_zakdet where okb_db_zadan.ID_zakdet=okb_db_zakdet.ID), (SELECT okb_db_zakdet.NAME FROM okb_db_zakdet where okb_db_zadan.ID_zakdet=okb_db_zakdet.ID), okb_db_zadan.ID, 
	(SELECT okb_db_operitems.ID_oper FROM okb_db_operitems where okb_db_operitems.ID=okb_db_zadan.ID_operitems), okb_db_zadan.DATE 
	FROM okb_db_zadan where okb_db_zadan.DATE>='".$sqldate_1."' and okb_db_zadan.DATE<='".$sqldate_2."' order by (SELECT okb_db_resurs.NAME FROM okb_db_resurs where okb_db_zadan.ID_resurs=okb_db_resurs.ID), okb_db_zadan.DATE, okb_db_zadan.ID");
	while ($sql_txt_1 = mysql_fetch_row($sql_qry_1)) {
		$res_arr[$sql_txt_1[4]] = $sql_txt_1[4];
		$res_nam_arr[$sql_txt_1[4]] = $sql_txt_1[3];
		$date_arr[$sql_txt_1[4]] = $date_arr[$sql_txt_1[4]].($sql_txt_1[2]*1)."|";
		$norm_arr[$sql_txt_1[4]] = $norm_arr[$sql_txt_1[4]].$sql_txt_1[0]."|";
		$num_arr[$sql_txt_1[4]] = $num_arr[$sql_txt_1[4]].$sql_txt_1[1]."|";
		$smen_arr[$sql_txt_1[4]] = $smen_arr[$sql_txt_1[4]].$sql_txt_1[5]."|";
		$tidzak_arr[$sql_txt_1[4]] = $tidzak_arr[$sql_txt_1[4]].$sql_txt_1[6]."|";
		$nam_zak_arr[$sql_txt_1[4]] = $nam_zak_arr[$sql_txt_1[4]].$sql_txt_1[7]."|";
		$nam2_zak_arr[$sql_txt_1[4]] = $nam2_zak_arr[$sql_txt_1[4]].$sql_txt_1[8]."|";
		$oboz_zakdet_arr[$sql_txt_1[4]] = $oboz_zakdet_arr[$sql_txt_1[4]].$sql_txt_1[9]."|";
		$name_zakdet_arr[$sql_txt_1[4]] = $name_zakdet_arr[$sql_txt_1[4]].$sql_txt_1[10]."|";
		$zadan_id_arr[$sql_txt_1[4]] = $zadan_id_arr[$sql_txt_1[4]].$sql_txt_1[11]."|";
		$oper_nam_arr[$sql_txt_1[4]] = $oper_nam_arr[$sql_txt_1[4]].$sql_txt_1[12]."|";
		$datefull_arr[$sql_txt_1[4]] = $datefull_arr[$sql_txt_1[4]].(substr($sql_txt_1[13],6,2)*1)."_".(substr($sql_txt_1[13],4,2)*1)."|";
	}
	
	$count_row = 0;
	$count_page = 1;
	foreach($res_arr as $k_1 => $v_1){
		$expl_dates = explode("|", $date_arr[$v_1]);
		$expl_datesfull = explode("|", $datefull_arr[$v_1]);
		$expl_norms = explode("|", $norm_arr[$v_1]);
		$expl_nums = explode("|", $num_arr[$v_1]);
		$expl_smen = explode("|", $smen_arr[$v_1]);
		$expl_tidzak = explode("|", $tidzak_arr[$v_1]);
		$expl_namzak = explode("|", $nam_zak_arr[$v_1]);
		$expl_nam2zak = explode("|", $nam2_zak_arr[$v_1]);
		$expl_obozzakdet = explode("|", $oboz_zakdet_arr[$v_1]);
		$expl_namezakdet = explode("|", $name_zakdet_arr[$v_1]);
		$expl_zadanid = explode("|", $zadan_id_arr[$v_1]);
		$expl_opernam = explode("|", $oper_nam_arr[$v_1]);
		
		$summ_norm_arr = array();
		$summ_num_arr = array();
				
		foreach($expl_datesfull as $k_2 => $v_2){
			$summ_norm_arr[$v_2] = $summ_norm_arr[$v_2]+$expl_norms[$k_2];
			$summ_num_arr[$v_2] = $summ_num_arr[$v_2]+$expl_nums[$k_2];
		}
		
		$summ_norm_res = 0;
		$summ_num_res = 0;
		$count_row = $count_row + 85;
		
		echo "<tr style='background:#cbdef4;'><td style='width:300px;' class=Field><b style='font-size:18px;'>".$res_nam_arr[$k_1]."</b></td>";
		echo "<td class=Field style='width:200px;'></td>";
		
		if ($sql_month_1!==$sql_month_2){
			for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=$count_Mday; $a_a++){
				if ($summ_norm_arr[$a_a."_".$sql_month_1]) echo "<td class=Field style='text-align:center; min-width:63px;'>".$summ_norm_arr[$a_a."_".$sql_month_1]." Н/Ч<br>".$summ_num_arr[$a_a."_".$sql_month_1]." шт.</td>";
				if ($summ_norm_arr[$a_a."_".$sql_month_1]) $summ_norm_res = $summ_norm_res+$summ_norm_arr[$a_a."_".$sql_month_1];
				if ($summ_norm_arr[$a_a."_".$sql_month_1]) $summ_num_res = $summ_num_res+$summ_num_arr[$a_a."_".$sql_month_1];
				if (!$summ_norm_arr[$a_a."_".$sql_month_1]) echo "<td class=Field style='text-align:center; min-width:17px;'></td>";
			}
			for ($a_a=1; $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
				if ($summ_norm_arr[$a_a."_".$sql_month_2]) echo "<td class=Field style='text-align:center; min-width:63px;'>".$summ_norm_arr[$a_a."_".$sql_month_1]." Н/Ч<br>".$summ_num_arr[$a_a."_".$sql_month_1]." шт.</td>";
				if ($summ_norm_arr[$a_a."_".$sql_month_2]) $summ_norm_res = $summ_norm_res+$summ_norm_arr[$a_a."_".$sql_month_1];
				if ($summ_norm_arr[$a_a."_".$sql_month_2]) $summ_num_res = $summ_num_res+$summ_num_arr[$a_a."_".$sql_month_1];
				if (!$summ_norm_arr[$a_a."_".$sql_month_2]) echo "<td class=Field style='text-align:center; min-width:17px;'></td>";
			}
		}
		if ($sql_month_1==$sql_month_2){
			for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
				if ($summ_norm_arr[$a_a."_".$sql_month_1]) echo "<td class=Field style='text-align:center; min-width:63px;'>".$summ_norm_arr[$a_a."_".$sql_month_1]." Н/Ч<br>".$summ_num_arr[$a_a."_".$sql_month_1]." шт.</td>";
				if ($summ_norm_arr[$a_a."_".$sql_month_1]) $summ_norm_res = $summ_norm_res+$summ_norm_arr[$a_a."_".$sql_month_1];
				if ($summ_norm_arr[$a_a."_".$sql_month_1]) $summ_num_res = $summ_num_res+$summ_num_arr[$a_a."_".$sql_month_1];
				if (!$summ_norm_arr[$a_a."_".$sql_month_1]) echo "<td class=Field style='text-align:center; min-width:17px;'></td>";
			}
		}
			
		echo "<td class=Field style='text-align:center; min-width:87px;'>".$summ_norm_res." Н/Ч<br>".$summ_num_res." шт.</td>";
		echo "</tr>";
		
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			
		$true_tr_nam = array();
		$arr_tr_nam = array();
		$arr_tr_norm = array();
		$arr_tr_num = array();
		$arr_tr_smen = array();
		
			//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			
		foreach($expl_zadanid as $k_3 => $v_3){
		if (($v_3!=="0") and ($v_3!=="")){
			if (!$arr_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]]) {
				$arr_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $expl_datesfull[$k_3]."|";
				$arr_tr_norm[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $expl_norms[$k_3]."|";
				$arr_tr_num[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $expl_nums[$k_3]."|";
				$arr_tr_smen[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $expl_smen[$k_3]."|";
			}else{
				$arr_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $arr_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]].$expl_datesfull[$k_3]."|";
				$arr_tr_norm[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $arr_tr_norm[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]].$expl_norms[$k_3]."|";
				$arr_tr_num[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $arr_tr_num[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]].$expl_nums[$k_3]."|";
				$arr_tr_smen[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $arr_tr_smen[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]].$expl_smen[$k_3]."|";
			}
		}}
			
			//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			
			
		foreach($expl_zadanid as $k_3 => $v_3){
		if (($v_3!=="0") and ($v_3!=="")){
			$sql_qry_2 = dbquery("SELECT TID, NAME FROM okb_db_oper where ID=".$expl_opernam[$k_3]);
			$sql_txt_2 = mysql_fetch_array($sql_qry_2);
			$sql_qry_5 = dbquery("SELECT MORE2 FROM okb_db_zadan where ID=".$v_3);
			$sql_txt_5 = mysql_fetch_array($sql_qry_5);
			
			$summ_norm_per = 0;
			$summ_num_per = 0;
			
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			
			$expl_new_dats = explode("|",$arr_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]]);
			$arr_new_dats = array();
			$expl_new_norm = explode("|",$arr_tr_norm[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]]);
			$arr_new_norm = array();
			$expl_new_num = explode("|",$arr_tr_num[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]]);
			$arr_new_num = array();
			$expl_new_smen = explode("|",$arr_tr_smen[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]]);
			$arr_new_smen = array();
			foreach($expl_new_dats as $k_4 => $v_4){
				$arr_new_dats[$v_4] = $v_4;
				$arr_new_norm[$v_4] = $expl_new_norm[$k_4];
				$arr_new_num[$v_4] = $expl_new_num[$k_4];
				$arr_new_smen[$v_4] = $expl_new_smen[$k_4];
			}
			
			if ($true_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]]){
			}else{
				$true_tr_nam[$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]] = $expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3];
			//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
			
			echo "<tr name=".$expl_namzak[$k_3].$expl_obozzakdet[$k_3].$expl_opernam[$k_3]."><td style='width:300px;' class=Field><b>".$tid_zak[$expl_tidzak[$k_3]]." - ".$expl_namzak[$k_3]."</b> ".substr($expl_nam2zak[$k_3],0,30);
			echo "<br>".$expl_obozzakdet[$k_3]." ".substr($expl_namezakdet[$k_3],0,30)."
			<br>".$tid_oper[$sql_txt_2['TID']]."&nbsp;&nbsp;-&nbsp;&nbsp;".$sql_txt_2['NAME']."</td>";
			if (((in_array("1",$right_us)) or (in_array("17",$right_us)) or (in_array("18",$right_us))) and (strpos($_SERVER['PHP_SELF'], 'index.php')==1)){
				echo "<td class=Field><input style='width:199px;' type='text' name='db_zadan_MORE2_edit_".$v_3."' value='".$sql_txt_5['MORE2']."' onchange='vote(this , \"db_edit.php?db=db_zadan&field=MORE2&id=".$v_3."&value=\"+this.value);'></td>";
			}else{
				echo "<td class=Field style='width:200px;'>".$sql_txt_5['MORE2']."</td>";
			}
			
			if ($sql_month_1!==$sql_month_2){
				for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=$count_Mday; $a_a++){
					if ($arr_new_dats[$a_a."_".$sql_month_1]){
						//echo "<td class=Field style='text-align:center; max-width:85px;'>".$expl_smen[$k_3]."см. ".$expl_norms[$k_3]." (".$expl_nums[$k_3].")</td>";
						echo "<td class=Field style='text-align:center; min-width:63px;'>".$arr_new_smen[$a_a."_".$sql_month_1]." смена<br>".$arr_new_norm[$a_a."_".$sql_month_1]." Н/Ч<br>".$arr_new_num[$a_a."_".$sql_month_1]." шт.</td>";
						$summ_norm_per = $summ_norm_per + $arr_new_norm[$a_a."_".$sql_month_1];
						$summ_num_per = $summ_num_per + $arr_new_num[$a_a."_".$sql_month_1];
					}else{
						echo "<td class=Field style='text-align:center; min-width:17px;'></td>";
					}
				}
				for ($a_a=1; $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
					if ($arr_new_dats[$a_a."_".$sql_month_2]){
						//echo "<td class=Field style='text-align:center; max-width:85px;'>".$expl_smen[$k_3]."см. ".$expl_norms[$k_3]." (".$expl_nums[$k_3].")</td>";
						echo "<td class=Field style='text-align:center; min-width:63px;'>".$arr_new_smen[$a_a."_".$sql_month_2]." смена<br>".$arr_new_norm[$a_a."_".$sql_month_2]." Н/Ч<br>".$arr_new_num[$a_a."_".$sql_month_2]." шт.</td>";
						$summ_norm_per = $summ_norm_per + $arr_new_norm[$a_a."_".$sql_month_2];
						$summ_num_per = $summ_num_per + $arr_new_num[$a_a."_".$sql_month_2];
					}else{
						echo "<td class=Field style='text-align:center; min-width:17px;'></td>";
					}
				}
			}
			if ($sql_month_1==$sql_month_2){
				for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
					if ($arr_new_dats[$a_a."_".$sql_month_1]){
						//echo "<td class=Field style='text-align:center; max-width:85px;'>".$expl_smen[$k_3]."см. ".$expl_norms[$k_3]." (".$expl_nums[$k_3].")</td>";
						echo "<td class=Field style='text-align:center; min-width:63px;'>".$arr_new_smen[$a_a."_".$sql_month_1]." смена<br>".$arr_new_norm[$a_a."_".$sql_month_1]." Н/Ч<br>".$arr_new_num[$a_a."_".$sql_month_1]." шт.</td>";
						$summ_norm_per = $summ_norm_per + $arr_new_norm[$a_a."_".$sql_month_1];
						$summ_num_per = $summ_num_per + $arr_new_num[$a_a."_".$sql_month_1];
					}else{
						echo "<td class=Field style='text-align:center; min-width:17px;'></td>";
					}
				}
			}
			
			echo "<td class=Field style='text-align:center; min-width:87px;'>".$summ_norm_per." Н/Ч<br>".$summ_num_per." шт.</td></tr>";
			$count_row = $count_row + 113;
			if ($count_row>1955) {
				echo "<b style='float:right;'>Лист №".$count_page."</b></tbody></table><table style='page-break-after:always;' width='1500px' class='rdtbl tbl'><tbody>";
				echo "<thead><tr class=first>
				<td rowspan=2>ФИО</td>
				<td rowspan=2>Примечание</td>
				<td colspan=".$colspan_thead.">Сколько запланировано по дням c ".substr($sqldate_1,6,2).".".substr($sqldate_1,4,2).".".substr($sqldate_1,0,4)." по ".substr($sqldate_2,6,2).".".substr($sqldate_2,4,2).".".substr($sqldate_2,0,4)."&nbsp;&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;смена Н/Ч (шт.)</td>
				<td rowspan=2>ИТОГО<br>за период</td></tr><tr class=first>";
				
				if ($sql_month_1!==$sql_month_2){
					for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=$count_Mday; $a_a++){
						echo "<td>".($a_a)."</td>";
					}
					for ($a_a=1; $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
						echo "<td>".($a_a)."</td>";
					}
				}
				if ($sql_month_1==$sql_month_2){
					for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
						echo "<td>".($a_a)."</td>";
					}
				}
				
				echo "</tr></thead>";
				$count_row = 110;
				$count_page = $count_page+1;
			}
			
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv

			}

			//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

		}}
	}
		
		$plan_zad_date_arr = array();
		$plan_zad_norm_arr = array();
		$norm_summ_plan = 0;
		$sql_qry_3 = dbquery("SELECT RIGHT(DATE,2), NORM FROM okb_db_planzad where DATE>='".$sqldate_1."' and DATE<='".$sqldate_2."' order by DATE");
		while($sql_txt_3 = mysql_fetch_row($sql_qry_3)){
			$plan_zad_date_arr[$sql_txt_3[0]*1] = $sql_txt_3[0]*1;
			$plan_zad_norm_arr[$sql_txt_3[0]*1] = $plan_zad_norm_arr[$sql_txt_3[0]*1]+$sql_txt_3[1]*1;
		}
		
			echo "<tr style='background:#cbdef4;'><td class=Field><b>БЕЗ РЕСУРСА</b><br></td><td class=Field></td>";
			
			if ($sql_month_1!==$sql_month_2){
				for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=$count_Mday; $a_a++){
					if ($plan_zad_date_arr[$a_a]==$a_a){
						echo "<td class=Field style='text-align:center;'>".$plan_zad_norm_arr[$a_a]." Н/Ч</td>";
						$norm_summ_plan = $norm_summ_plan+$plan_zad_norm_arr[$a_a];
					}else{
						echo "<td class=Field style='text-align:center;'></td>";
					}
				}
				for ($a_a=1; $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
					if ($plan_zad_date_arr[$a_a]==$a_a){
						echo "<td class=Field style='text-align:center;'>".$plan_zad_norm_arr[$a_a]." Н/Ч</td>";
						$norm_summ_plan = $norm_summ_plan+$plan_zad_norm_arr[$a_a];
					}else{
						echo "<td class=Field style='text-align:center;'></td>";
					}
				}
			}
			if ($sql_month_1==$sql_month_2){
				for ($a_a=(substr($sqldate_1,6,2)*1); $a_a<=(substr($sqldate_2,6,2)*1); $a_a++){
					if ($plan_zad_date_arr[$a_a]==$a_a){
						echo "<td class=Field style='text-align:center;'>".$plan_zad_norm_arr[$a_a]." Н/Ч</td>";
						$norm_summ_plan = $norm_summ_plan+$plan_zad_norm_arr[$a_a];
					}else{
						echo "<td class=Field style='text-align:center;'></td>";
					}
				}
			}
			
			echo "<td class=Field style='text-align:center;'>".$norm_summ_plan." Н/Ч</td></tr>";
	echo "</tbody></table>";
?>