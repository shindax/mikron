<?php


	if (!defined("MAV_ERP")) { die("Access Denied"); }

$stl_s = " style='";
$stl_f_1 = "font-size:8pt;";
$stl_f_2 = "font-size:12pt;";
$stl_f_3 = "font-size:18pt;";
$stl_e = "'";
	
echo "<H2> Трудоемкость по видам работ</H2>
<table width='1576px' class='rdtbl tbl'><thead><tr class='first'>
<td".$stl_s."width:150px;".$stl_e.">Вид работы</td>
<td".$stl_s."width:175px;".$stl_e.">Операция</td>
<td".$stl_s."width:100px;".$stl_e.">Оборудование</td>
<td".$stl_s."width:100px;".$stl_e.">Заказ</td>
<td".$stl_s."width:425px;".$stl_e.">ДСЕ</td>
<td".$stl_s."width:85px;".$stl_e.">План<br>Н/Ч (шт.)</td>
<td".$stl_s."width:85px;".$stl_e.">Факт<br>Н/Ч (шт.)</td>
<td".$stl_s."width:85px;".$stl_e.">Осталось<br>Н/Ч (шт.)</td>
<td".$stl_s."width:85px;".$stl_e.">%<br>заверш.</td>
<td".$stl_s."width:85px;".$stl_e.">Затр.<br>Ч</td>
<td".$stl_s."width:85px;".$stl_e.">3атр. Ч/<br>факт. Н/Ч</td>
</tr></thead><tbody>"; 

$oper_tid = array(" ","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");
$oper_vid = array(" ","Газовым пламенем","Давлением","Контроль","Механическая","Термообработка","Окраска","Вручную","Сварка");
$zak_vid = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");

$park_tbl_mark = array();
$oper_tbl_name = array();
$oper_tbl_tid = array();

$arr_tbl_1 = array();
$arr_tbl_2 = array();
$arr_tbl_3 = array();
$arr_tbl_4_1 = array();
$arr_tbl_4_2 = array();
$arr_tbl_5_1 = array();
$arr_tbl_5_2 = array();
$arr_tbl_6 = array();
$arr_tbl_7 = array();
$arr_tbl_8 = array();
$arr_tbl_9 = array();
$arr_tbl_10 = array();
$arr_tbl_11 = array();
$arr_tbl_12 = array();
$arr_tbl_13 = array();
$arr_tbl_14 = array();
$arr_tbl_15 = array();

$res_3 = dbquery("SELECT ID, MARK FROM okb_db_park");
while($nam_3 = mysql_fetch_row($res_3)) {
	$park_tbl_mark[$nam_3[0]]=$nam_3[1];
}

$res_2 = dbquery("SELECT ID, NAME, TID FROM okb_db_oper");
while($nam_2 = mysql_fetch_row($res_2)) {
	$oper_tbl_name[$nam_2[0]]=$nam_2[1];
	$oper_tbl_tid[$nam_2[0]]=$nam_2[2];
}

$pred_zak = "";
$pred_park = "";
$pred_oper = "";

$res_1 = dbquery("SELECT okb_db_zak.NAME, okb_db_zak.TID, okb_db_zakdet.NAME, 
okb_db_zakdet.OBOZ, okb_db_operitems.ID_oper, okb_db_operitems.ID_park, 
okb_db_operitems.FACT2_NORM, okb_db_operitems.NORM_ZAK, okb_db_operitems.FACT, 
okb_db_operitems.NUM_ZAK, okb_db_operitems.FACT2_NUM, okb_db_zak.DSE_NAME 
FROM okb_db_zak 
INNER JOIN okb_db_zakdet ON okb_db_zak.ID=okb_db_zakdet.ID_zak 
INNER JOIN okb_db_operitems ON okb_db_zakdet.ID=okb_db_operitems.ID_zakdet 
WHERE okb_db_zak.EDIT_STATE=0 AND okb_db_zakdet.LID=0");
while($nam_1 = mysql_fetch_row($res_1)) {
	$arr_tbl_1[] = $oper_tid[$oper_tbl_tid[$nam_1[4]]];
	$arr_tbl_2[] = $oper_tbl_name[$nam_1[4]];
	$arr_tbl_3[] = $park_tbl_mark[$nam_1[5]];
	$arr_tbl_4_1[] = $zak_vid[$nam_1[1]];
	$arr_tbl_4_2[] = $nam_1[0];
	$arr_tbl_5_1[] = $nam_1[2];
	$arr_tbl_5_2[] = $nam_1[3];
	$arr_tbl_6[] = $nam_1[7];
	$arr_tbl_7[] = $nam_1[6];
	$arr_tbl_8[] = $nam_1[7]-$nam_1[6];
	if ($nam_1[7]==0) { $nam_1_7 = 1;}else{ $nam_1_7 = $nam_1[7];}
	$arr_tbl_9[] = round((($nam_1[6]/$nam_1_7)*100),2);
	$arr_tbl_10[] = $nam_1[8];
	if ($nam_1[6]==0) { $nam_1_6 = 1;}else{ $nam_1_6 = $nam_1[6];}
	$arr_tbl_11[] = round(($nam_1[8]/$nam_1_6),2);
	$arr_tbl_12[] = $nam_1[9] - $nam_1[10];
	$arr_tbl_13[] = $nam_1[9];
	$arr_tbl_14[] = $nam_1[10];
	$arr_tbl_15[] = $nam_1[11];
}

array_multisort($arr_tbl_1, $arr_tbl_2, $arr_tbl_3, $arr_tbl_4_2, $arr_tbl_4_1, $arr_tbl_5_2, $arr_tbl_5_1, $arr_tbl_6, $arr_tbl_7, $arr_tbl_8, $arr_tbl_9, $arr_tbl_10, $arr_tbl_11, $arr_tbl_12, $arr_tbl_13, $arr_tbl_14, $arr_tbl_15);

$disp_1 = "none";
$disp_2 = "block";
$cur_vid = "";
$cur_vid_w = "";
$cur_zak = "";
$cur_zak_w = "";
$cur_park = "";
$cur_park_w = "";
$cur_oper = "";
$cur_oper_w = "";

$sum_cur_z_6 = 0;
$sum_cur_z_7 = 0;
$sum_cur_z_10 = 0;

$sum_cur_p_6 = 0;
$sum_cur_p_7 = 0;
$sum_cur_p_10 = 0;

$sum_cur_o_6 = 0;
$sum_cur_o_7 = 0;
$sum_cur_o_10 = 0;

$sum_cur_v_6 = 0;
$sum_cur_v_7 = 0;
$sum_cur_v_10 = 0;

$cur_zak_key = 0;
$cur_park_key = 0;
$cur_oper_key = 0;
$cur_vid_key = 0;

$cur_v_ind = 0;
$cur_z_ind = 0;
$cur_p_ind = 0;
$cur_o_ind = 0;

$itog_sum_6 = 0;
$itog_sum_7 = 0;
$itog_sum_10 = 0;

foreach($arr_tbl_1 as $key_1 => $val_1){
	if ($arr_tbl_1[$key_1] !== $cur_vid){
		$cur_vid_key = $key_1;
		$cur_vid_w = $arr_tbl_1[$key_1];
		$cur_v_ind = $cur_v_ind + 1;
		while ($arr_tbl_1[$cur_vid_key] == $cur_vid_w){
			$sum_cur_v_6 += $arr_tbl_6[$cur_vid_key];
			$sum_cur_v_7 += $arr_tbl_7[$cur_vid_key];
			$sum_cur_v_10 += $arr_tbl_10[$cur_vid_key];
			$cur_vid_key += 1;
		}
		if ($sum_cur_v_6==0) { $sum_cur_v_6_0 = 1;}else{ $sum_cur_v_6_0 = $sum_cur_v_6;}
		if ($sum_cur_v_7==0) { $sum_cur_v_7_0 = 1;}else{ $sum_cur_v_7_0 = $sum_cur_v_7;}
		if (strlen(substr(strstr($sum_cur_v_6, "."), 1))==0) { $tbl_1_6 = $sum_cur_v_6.".00";}
		if (strlen(substr(strstr($sum_cur_v_6, "."), 1))==1) { $tbl_1_6 = $sum_cur_v_6."0";}
		if (strlen(substr(strstr($sum_cur_v_6, "."), 1))==2) { $tbl_1_6 = $sum_cur_v_6;}
		if (strlen(substr(strstr($sum_cur_v_6, "."), 1))>2) { $tbl_1_6 = number_format($sum_cur_v_6, 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_v_7, "."), 1))==0) { $tbl_1_7 = $sum_cur_v_7.".00";}
		if (strlen(substr(strstr($sum_cur_v_7, "."), 1))==1) { $tbl_1_7 = $sum_cur_v_7."0";}
		if (strlen(substr(strstr($sum_cur_v_7, "."), 1))==2) { $tbl_1_7 = $sum_cur_v_7;}
		if (strlen(substr(strstr($sum_cur_v_7, "."), 1))>2) { $tbl_1_7 = number_format($sum_cur_v_7, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_v_6-$sum_cur_v_7), "."), 1))==0) { $tbl_1_8 = ($sum_cur_v_6-$sum_cur_v_7).".00";}
		if (strlen(substr(strstr(($sum_cur_v_6-$sum_cur_v_7), "."), 1))==1) { $tbl_1_8 = ($sum_cur_v_6-$sum_cur_v_7)."0";}
		if (strlen(substr(strstr(($sum_cur_v_6-$sum_cur_v_7), "."), 1))==2) { $tbl_1_8 = ($sum_cur_v_6-$sum_cur_v_7);}
		if (strlen(substr(strstr(($sum_cur_v_6-$sum_cur_v_7), "."), 1))>2) { $tbl_1_8 = number_format(($sum_cur_v_6-$sum_cur_v_7), 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_v_7/$sum_cur_v_6_0*100), "."), 1))==0) { $tbl_1_9 = ($sum_cur_v_7/$sum_cur_v_6_0*100).".00";}
		if (strlen(substr(strstr(($sum_cur_v_7/$sum_cur_v_6_0*100), "."), 1))==1) { $tbl_1_9 = ($sum_cur_v_7/$sum_cur_v_6_0*100)."0";}
		if (strlen(substr(strstr(($sum_cur_v_7/$sum_cur_v_6_0*100), "."), 1))==2) { $tbl_1_9 = ($sum_cur_v_7/$sum_cur_v_6_0*100);}
		if (strlen(substr(strstr(($sum_cur_v_7/$sum_cur_v_6_0*100), "."), 1))>2) { $tbl_1_9 = number_format(($sum_cur_v_7/$sum_cur_v_6_0*100), 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_v_10, "."), 1))==0) { $tbl_1_10 = $sum_cur_v_10.".00";}
		if (strlen(substr(strstr($sum_cur_v_10, "."), 1))==1) { $tbl_1_10 = $sum_cur_v_10."0";}
		if (strlen(substr(strstr($sum_cur_v_10, "."), 1))==2) { $tbl_1_10 = $sum_cur_v_10;}
		if (strlen(substr(strstr($sum_cur_v_10, "."), 1))>2) { $tbl_1_10 = number_format($sum_cur_v_10, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_v_10/$sum_cur_v_7_0), "."), 1))==0) { $tbl_1_11 = ($sum_cur_v_10/$sum_cur_v_7_0).".00";}
		if (strlen(substr(strstr(($sum_cur_v_10/$sum_cur_v_7_0), "."), 1))==1) { $tbl_1_11 = ($sum_cur_v_10/$sum_cur_v_7_0)."0";}
		if (strlen(substr(strstr(($sum_cur_v_10/$sum_cur_v_7_0), "."), 1))==2) { $tbl_1_11 = ($sum_cur_v_10/$sum_cur_v_7_0);}
		if (strlen(substr(strstr(($sum_cur_v_10/$sum_cur_v_7_0), "."), 1))>2) { $tbl_1_11 = number_format(($sum_cur_v_10/$sum_cur_v_7_0), 2, '.', '');}
		echo "<tr>
		<td class='field' style='background:#CBDEF4;' colspan='5'><b class='not_tr' name='arr_tbl_1_".$cur_v_ind."' style='cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_1(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;+&nbsp;</b><b class='not_tr' name='arr_tbl_1_".$cur_v_ind."' style='display:none; cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_2(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;-&nbsp;&nbsp;</b>&nbsp;".$arr_tbl_1[$key_1]."</td>
		<td name='max_numb_6' class='field' style='text-align:right; background:#CBDEF4;'>".$tbl_1_6."</td>
		<td name='max_numb_7' class='field' style='text-align:right; background:#CBDEF4;'>".$tbl_1_7."</td>
		<td name='max_numb_8' class='field' style='text-align:right; background:#CBDEF4;'>".$tbl_1_8."</td>
		<td name='max_numb_9' class='field' style='text-align:right; background:#CBDEF4;'>".$tbl_1_9."</td>
		<td name='max_numb_10' class='field' style='text-align:right; background:#CBDEF4;'>".$tbl_1_10."</td>
		<td name='max_numb_11' class='field' style='text-align:right; background:#CBDEF4;'>".$tbl_1_11."</td>
		</tr>";
		$itog_sum_6 += $sum_cur_v_6;
		$itog_sum_7 += $sum_cur_v_7;
		$itog_sum_10 += $sum_cur_v_10;
		
		$tbl_1_6 = "";
		$tbl_1_7 = "";
		$tbl_1_8 = "";
		$tbl_1_9 = "";
		$tbl_1_10 = "";
		$tbl_1_11 = "";
		$sum_cur_v_6 = 0;
		$sum_cur_v_7 = 0;
		$sum_cur_v_10 = 0;
	}
	if ($arr_tbl_2[$key_1] !== $cur_oper){
		$cur_oper_key = $key_1;
		$cur_oper_w = $arr_tbl_2[$key_1];
		$cur_o_ind += 1;
		while ($arr_tbl_2[$cur_oper_key] == $cur_oper_w){
			$sum_cur_o_6 += $arr_tbl_6[$cur_oper_key];
			$sum_cur_o_7 += $arr_tbl_7[$cur_oper_key];
			$sum_cur_o_10 += $arr_tbl_10[$cur_oper_key];
			$cur_oper_key += 1;
		}
		if ($sum_cur_o_6==0) { $sum_cur_o_6_0 = 1;}else{ $sum_cur_o_6_0 = $sum_cur_o_6;}
		if ($sum_cur_o_7==0) { $sum_cur_o_7_0 = 1;}else{ $sum_cur_o_7_0 = $sum_cur_o_7;}
		if (strlen(substr(strstr($sum_cur_o_6, "."), 1))==0) { $tbl_1_6 = $sum_cur_o_6.".00";}
		if (strlen(substr(strstr($sum_cur_o_6, "."), 1))==1) { $tbl_1_6 = $sum_cur_o_6."0";}
		if (strlen(substr(strstr($sum_cur_o_6, "."), 1))==2) { $tbl_1_6 = $sum_cur_o_6;}
		if (strlen(substr(strstr($sum_cur_o_6, "."), 1))>2) { $tbl_1_6 = number_format($sum_cur_o_6, 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_o_7, "."), 1))==0) { $tbl_1_7 = $sum_cur_o_7.".00";}
		if (strlen(substr(strstr($sum_cur_o_7, "."), 1))==1) { $tbl_1_7 = $sum_cur_o_7."0";}
		if (strlen(substr(strstr($sum_cur_o_7, "."), 1))==2) { $tbl_1_7 = $sum_cur_o_7;}
		if (strlen(substr(strstr($sum_cur_o_7, "."), 1))>2) { $tbl_1_7 = number_format($sum_cur_o_7, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_o_6-$sum_cur_o_7), "."), 1))==0) { $tbl_1_8 = ($sum_cur_o_6-$sum_cur_o_7).".00";}
		if (strlen(substr(strstr(($sum_cur_o_6-$sum_cur_o_7), "."), 1))==1) { $tbl_1_8 = ($sum_cur_o_6-$sum_cur_o_7)."0";}
		if (strlen(substr(strstr(($sum_cur_o_6-$sum_cur_o_7), "."), 1))==2) { $tbl_1_8 = ($sum_cur_o_6-$sum_cur_o_7);}
		if (strlen(substr(strstr(($sum_cur_o_6-$sum_cur_o_7), "."), 1))>2) { $tbl_1_8 = number_format(($sum_cur_o_6-$sum_cur_o_7), 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_o_7/$sum_cur_o_6_0*100), "."), 1))==0) { $tbl_1_9 = ($sum_cur_o_7/$sum_cur_o_6_0*100).".00";}
		if (strlen(substr(strstr(($sum_cur_o_7/$sum_cur_o_6_0*100), "."), 1))==1) { $tbl_1_9 = ($sum_cur_o_7/$sum_cur_o_6_0*100)."0";}
		if (strlen(substr(strstr(($sum_cur_o_7/$sum_cur_o_6_0*100), "."), 1))==2) { $tbl_1_9 = ($sum_cur_o_7/$sum_cur_o_6_0*100);}
		if (strlen(substr(strstr(($sum_cur_o_7/$sum_cur_o_6_0*100), "."), 1))>2) { $tbl_1_9 = number_format(($sum_cur_o_7/$sum_cur_o_6_0*100), 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_o_10, "."), 1))==0) { $tbl_1_10 = $sum_cur_o_10.".00";}
		if (strlen(substr(strstr($sum_cur_o_10, "."), 1))==1) { $tbl_1_10 = $sum_cur_o_10."0";}
		if (strlen(substr(strstr($sum_cur_o_10, "."), 1))==2) { $tbl_1_10 = $sum_cur_o_10;}
		if (strlen(substr(strstr($sum_cur_o_10, "."), 1))>2) { $tbl_1_10 = number_format($sum_cur_o_10, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_o_10/$sum_cur_o_7_0), "."), 1))==0) { $tbl_1_11 = ($sum_cur_o_10/$sum_cur_o_7_0).".00";}
		if (strlen(substr(strstr(($sum_cur_o_10/$sum_cur_o_7_0), "."), 1))==1) { $tbl_1_11 = ($sum_cur_o_10/$sum_cur_o_7_0)."0";}
		if (strlen(substr(strstr(($sum_cur_o_10/$sum_cur_o_7_0), "."), 1))==2) { $tbl_1_11 = ($sum_cur_o_10/$sum_cur_o_7_0);}
		if (strlen(substr(strstr(($sum_cur_o_10/$sum_cur_o_7_0), "."), 1))>2) { $tbl_1_11 = number_format(($sum_cur_o_10/$sum_cur_o_7_0), 2, '.', '');}
		echo "<tr name='arr_tbl_1_".$cur_v_ind."' style='display:none;'>
		<td class='field' style='background:#CBDEF4;'>".$arr_tbl_1[$key_1]."</td>
		<td class='field' colspan='4'><b class='not_tr' name='arr_tbl_2_".$cur_o_ind."' style='cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_1(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;+&nbsp;</b><b class='not_tr' name='arr_tbl_2_".$cur_o_ind."' style='display:none; cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_2(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;-&nbsp;&nbsp;</b>&nbsp;".$arr_tbl_2[$key_1]."</td>
		<td class='field' style='text-align:right;'>".$tbl_1_6."</td>
		<td class='field' style='text-align:right;'>".$tbl_1_7."</td>
		<td class='field' style='text-align:right;'>".$tbl_1_8."</td>
		<td class='field' style='text-align:right;'>".$tbl_1_9."</td>
		<td class='field' style='text-align:right;'>".$tbl_1_10."</td>
		<td class='field' style='text-align:right;'>".$tbl_1_11."</td>
		</tr>";
		$tbl_1_6 = "";
		$tbl_1_7 = "";
		$tbl_1_8 = "";
		$tbl_1_9 = "";
		$tbl_1_10 = "";
		$tbl_1_11 = "";
		$sum_cur_o_6 = 0;
		$sum_cur_o_7 = 0;
		$sum_cur_o_10 = 0;
	}
	
	if ($arr_tbl_3[$key_1] !== $cur_park){
		$cur_park_key = $key_1;
		$cur_park_w = $arr_tbl_3[$key_1];
		$cur_p_ind = $cur_p_ind + 1;
		while ($arr_tbl_3[$cur_park_key] == $cur_park_w){
			$sum_cur_p_6 += $arr_tbl_6[$cur_park_key];
			$sum_cur_p_7 += $arr_tbl_7[$cur_park_key];
			$sum_cur_p_10 += $arr_tbl_10[$cur_park_key];
			$cur_park_key += 1;
		}
		if ($sum_cur_p_6==0) { $sum_cur_p_6_0 = 1;}else{ $sum_cur_p_6_0 = $sum_cur_p_6;}
		if ($sum_cur_p_7==0) { $sum_cur_p_7_0 = 1;}else{ $sum_cur_p_7_0 = $sum_cur_p_7;}
		if (strlen(substr(strstr($sum_cur_p_6, "."), 1))==0) { $tbl_1_6 = $sum_cur_p_6.".00";}
		if (strlen(substr(strstr($sum_cur_p_6, "."), 1))==1) { $tbl_1_6 = $sum_cur_p_6."0";}
		if (strlen(substr(strstr($sum_cur_p_6, "."), 1))==2) { $tbl_1_6 = $sum_cur_p_6;}
		if (strlen(substr(strstr($sum_cur_p_6, "."), 1))>2) { $tbl_1_6 = number_format($sum_cur_p_6, 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_p_7, "."), 1))==0) { $tbl_1_7 = $sum_cur_p_7.".00";}
		if (strlen(substr(strstr($sum_cur_p_7, "."), 1))==1) { $tbl_1_7 = $sum_cur_p_7."0";}
		if (strlen(substr(strstr($sum_cur_p_7, "."), 1))==2) { $tbl_1_7 = $sum_cur_p_7;}
		if (strlen(substr(strstr($sum_cur_p_7, "."), 1))>2) { $tbl_1_7 = number_format($sum_cur_p_7, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_p_6-$sum_cur_p_7), "."), 1))==0) { $tbl_1_8 = ($sum_cur_p_6-$sum_cur_p_7).".00";}
		if (strlen(substr(strstr(($sum_cur_p_6-$sum_cur_p_7), "."), 1))==1) { $tbl_1_8 = ($sum_cur_p_6-$sum_cur_p_7)."0";}
		if (strlen(substr(strstr(($sum_cur_p_6-$sum_cur_p_7), "."), 1))==2) { $tbl_1_8 = ($sum_cur_p_6-$sum_cur_p_7);}
		if (strlen(substr(strstr(($sum_cur_p_6-$sum_cur_p_7), "."), 1))>2) { $tbl_1_8 = number_format(($sum_cur_p_6-$sum_cur_p_7), 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_p_7/$sum_cur_p_6_0*100), "."), 1))==0) { $tbl_1_9 = ($sum_cur_p_7/$sum_cur_p_6_0*100).".00";}
		if (strlen(substr(strstr(($sum_cur_p_7/$sum_cur_p_6_0*100), "."), 1))==1) { $tbl_1_9 = ($sum_cur_p_7/$sum_cur_p_6_0*100)."0";}
		if (strlen(substr(strstr(($sum_cur_p_7/$sum_cur_p_6_0*100), "."), 1))==2) { $tbl_1_9 = ($sum_cur_p_7/$sum_cur_p_6_0*100);}
		if (strlen(substr(strstr(($sum_cur_p_7/$sum_cur_p_6_0*100), "."), 1))>2) { $tbl_1_9 = number_format(($sum_cur_p_7/$sum_cur_p_6_0*100), 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_p_10, "."), 1))==0) { $tbl_1_10 = $sum_cur_p_10.".00";}
		if (strlen(substr(strstr($sum_cur_p_10, "."), 1))==1) { $tbl_1_10 = $sum_cur_p_10."0";}
		if (strlen(substr(strstr($sum_cur_p_10, "."), 1))==2) { $tbl_1_10 = $sum_cur_p_10;}
		if (strlen(substr(strstr($sum_cur_p_10, "."), 1))>2) { $tbl_1_10 = number_format($sum_cur_p_10, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_p_10/$sum_cur_p_7_0), "."), 1))==0) { $tbl_1_11 = ($sum_cur_p_10/$sum_cur_p_7_0).".00";}
		if (strlen(substr(strstr(($sum_cur_p_10/$sum_cur_p_7_0), "."), 1))==1) { $tbl_1_11 = ($sum_cur_p_10/$sum_cur_p_7_0)."0";}
		if (strlen(substr(strstr(($sum_cur_p_10/$sum_cur_p_7_0), "."), 1))==2) { $tbl_1_11 = ($sum_cur_p_10/$sum_cur_p_7_0);}
		if (strlen(substr(strstr(($sum_cur_p_10/$sum_cur_p_7_0), "."), 1))>2) { $tbl_1_11 = number_format(($sum_cur_p_10/$sum_cur_p_7_0), 2, '.', '');}
		echo "<tr name='arr_tbl_2_".$cur_o_ind."'' style='display:none;'>
		<td class='field' style='background:#CBDEF4;'>".$arr_tbl_1[$key_1]."</td>
		<td class='field'>".$arr_tbl_2[$key_1]."</td>
		<td class='field' colspan='3' style='background:#a0ffa0;'><b class='not_tr' name='arr_tbl_3_".$cur_p_ind."' style='cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_1(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;+&nbsp;</b><b class='not_tr' name='arr_tbl_3_".$cur_p_ind."' style='display:none; cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_2(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;-&nbsp;&nbsp;</b>&nbsp;".$arr_tbl_3[$key_1]."</td>
		<td class='field' style='text-align:right; background:#a0ffa0;'>".$tbl_1_6."</td>
		<td class='field' style='text-align:right; background:#a0ffa0;'>".$tbl_1_7."</td>
		<td class='field' style='text-align:right; background:#a0ffa0;'>".$tbl_1_8."</td>
		<td class='field' style='text-align:right; background:#a0ffa0;'>".$tbl_1_9."</td>
		<td class='field' style='text-align:right; background:#a0ffa0;'>".$tbl_1_10."</td>
		<td class='field' style='text-align:right; background:#a0ffa0;'>".$tbl_1_11."</td>
		</tr>";
		$tbl_1_6 = "";
		$tbl_1_7 = "";
		$tbl_1_8 = "";
		$tbl_1_9 = "";
		$tbl_1_10 = "";
		$tbl_1_11 = "";
		$sum_cur_p_6 = 0;
		$sum_cur_p_7 = 0;
		$sum_cur_p_10 = 0;
	}
	
	if ($arr_tbl_4_2[$key_1] !== $cur_zak){
		$cur_zak_key = $key_1;
		$cur_zak_w = $arr_tbl_4_2[$key_1];
		$cur_z_ind += 1;
		while ($arr_tbl_4_2[$cur_zak_key] == $cur_zak_w){
			$sum_cur_z_6 += $arr_tbl_6[$cur_zak_key];
			$sum_cur_z_7 += $arr_tbl_7[$cur_zak_key];
			$sum_cur_z_10 += $arr_tbl_10[$cur_zak_key];
			$cur_zak_key += 1;
		}
		if ($sum_cur_z_6==0) { $sum_cur_z_6_0 = 1;}else{ $sum_cur_z_6_0 = $sum_cur_z_6;}
		if ($sum_cur_z_7==0) { $sum_cur_z_7_0 = 1;}else{ $sum_cur_z_7_0 = $sum_cur_z_7;}
		if (strlen(substr(strstr($sum_cur_z_6, "."), 1))==0) { $tbl_1_6 = $sum_cur_z_6.".00";}
		if (strlen(substr(strstr($sum_cur_z_6, "."), 1))==1) { $tbl_1_6 = $sum_cur_z_6."0";}
		if (strlen(substr(strstr($sum_cur_z_6, "."), 1))==2) { $tbl_1_6 = $sum_cur_z_6;}
		if (strlen(substr(strstr($sum_cur_z_6, "."), 1))>2) { $tbl_1_6 = number_format($sum_cur_z_6, 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_z_7, "."), 1))==0) { $tbl_1_7 = $sum_cur_z_7.".00";}
		if (strlen(substr(strstr($sum_cur_z_7, "."), 1))==1) { $tbl_1_7 = $sum_cur_z_7."0";}
		if (strlen(substr(strstr($sum_cur_z_7, "."), 1))==2) { $tbl_1_7 = $sum_cur_z_7;}
		if (strlen(substr(strstr($sum_cur_z_7, "."), 1))>2) { $tbl_1_7 = number_format($sum_cur_z_7, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_z_6-$sum_cur_z_7), "."), 1))==0) { $tbl_1_8 = ($sum_cur_z_6-$sum_cur_z_7).".00";}
		if (strlen(substr(strstr(($sum_cur_z_6-$sum_cur_z_7), "."), 1))==1) { $tbl_1_8 = ($sum_cur_z_6-$sum_cur_z_7)."0";}
		if (strlen(substr(strstr(($sum_cur_z_6-$sum_cur_z_7), "."), 1))==2) { $tbl_1_8 = ($sum_cur_z_6-$sum_cur_z_7);}
		if (strlen(substr(strstr(($sum_cur_z_6-$sum_cur_z_7), "."), 1))>2) { $tbl_1_8 = number_format(($sum_cur_z_6-$sum_cur_z_7), 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_z_7/$sum_cur_z_6_0*100), "."), 1))==0) { $tbl_1_9 = ($sum_cur_z_7/$sum_cur_z_6_0*100).".00";}
		if (strlen(substr(strstr(($sum_cur_z_7/$sum_cur_z_6_0*100), "."), 1))==1) { $tbl_1_9 = ($sum_cur_z_7/$sum_cur_z_6_0*100)."0";}
		if (strlen(substr(strstr(($sum_cur_z_7/$sum_cur_z_6_0*100), "."), 1))==2) { $tbl_1_9 = ($sum_cur_z_7/$sum_cur_z_6_0*100);}
		if (strlen(substr(strstr(($sum_cur_z_7/$sum_cur_z_6_0*100), "."), 1))>2) { $tbl_1_9 = number_format(($sum_cur_z_7/$sum_cur_z_6_0*100), 2, '.', '');}
		if (strlen(substr(strstr($sum_cur_z_10, "."), 1))==0) { $tbl_1_10 = $sum_cur_z_10.".00";}
		if (strlen(substr(strstr($sum_cur_z_10, "."), 1))==1) { $tbl_1_10 = $sum_cur_z_10."0";}
		if (strlen(substr(strstr($sum_cur_z_10, "."), 1))==2) { $tbl_1_10 = $sum_cur_z_10;}
		if (strlen(substr(strstr($sum_cur_z_10, "."), 1))>2) { $tbl_1_10 = number_format($sum_cur_z_10, 2, '.', '');}
		if (strlen(substr(strstr(($sum_cur_z_10/$sum_cur_z_7_0), "."), 1))==0) { $tbl_1_11 = ($sum_cur_z_10/$sum_cur_z_7_0).".00";}
		if (strlen(substr(strstr(($sum_cur_z_10/$sum_cur_z_7_0), "."), 1))==1) { $tbl_1_11 = ($sum_cur_z_10/$sum_cur_z_7_0)."0";}
		if (strlen(substr(strstr(($sum_cur_z_10/$sum_cur_z_7_0), "."), 1))==2) { $tbl_1_11 = ($sum_cur_z_10/$sum_cur_z_7_0);}
		if (strlen(substr(strstr(($sum_cur_z_10/$sum_cur_z_7_0), "."), 1))>2) { $tbl_1_11 = number_format(($sum_cur_z_10/$sum_cur_z_7_0), 2, '.', '');}
		echo "<tr name='arr_tbl_3_".$cur_p_ind."' style='display:none;'>
		<td class='field' style='background:#CBDEF4;'>".$arr_tbl_1[$key_1]."</td>
		<td class='field'>".$arr_tbl_2[$key_1]."</td>
		<td class='field' style='background:#a0ffa0;'>".$arr_tbl_3[$key_1]."</td>
		<td class='field' colspan='2' style='background:#b8ffb8;'><b class='not_tr' name='arr_tbl_4_".$cur_z_ind."' style='cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_1(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;+&nbsp;</b><b class='not_tr' name='arr_tbl_4_".$cur_z_ind."' style='display:none; cursor:pointer; border:1px solid #000; border-radius:6px;' onclick='show_tr_2(this.getAttribute(\"name\"), this.getAttribute(\"class\"), this);'>&nbsp;-&nbsp;&nbsp;</b>&nbsp;".$arr_tbl_4_1[$key_1]."&nbsp;&nbsp;&nbsp;&nbsp;".$arr_tbl_4_2[$key_1]."&nbsp;&nbsp;&nbsp;&nbsp;".$arr_tbl_15[$key_1]."</td>
		<td class='field' style='text-align:right; background:#b8ffb8;'>".$tbl_1_6."</td>
		<td class='field' style='text-align:right; background:#b8ffb8;'>".$tbl_1_7."</td>
		<td class='field' style='text-align:right; background:#b8ffb8;'>".$tbl_1_8."</td>
		<td class='field' style='text-align:right; background:#b8ffb8;'>".$tbl_1_9."</td>
		<td class='field' style='text-align:right; background:#b8ffb8;'>".$tbl_1_10."</td>
		<td class='field' style='text-align:right; background:#b8ffb8;'>".$tbl_1_11."</td>
		</tr>";
		$tbl_1_6 = "";
		$tbl_1_7 = "";
		$tbl_1_8 = "";
		$tbl_1_9 = "";
		$tbl_1_10 = "";
		$tbl_1_11 = "";
		$sum_cur_z_6 = 0;
		$sum_cur_z_7 = 0;
		$sum_cur_z_10 = 0;
	}
	
	if (strlen(substr(strstr($arr_tbl_6[$key_1], "."), 1))==0) { $tbl_1_6 = $arr_tbl_6[$key_1].".00";}
	if (strlen(substr(strstr($arr_tbl_6[$key_1], "."), 1))==1) { $tbl_1_6 = $arr_tbl_6[$key_1]."0";}
	if (strlen(substr(strstr($arr_tbl_6[$key_1], "."), 1))==2) { $tbl_1_6 = $arr_tbl_6[$key_1];}
	if (strlen(substr(strstr($arr_tbl_6[$key_1], "."), 1))>2) { $tbl_1_6 = number_format($arr_tbl_6[$key_1], 2, '.', '');}
	if (strlen(substr(strstr($arr_tbl_7[$key_1], "."), 1))==0) { $tbl_1_7 = $arr_tbl_7[$key_1].".00";}
	if (strlen(substr(strstr($arr_tbl_7[$key_1], "."), 1))==1) { $tbl_1_7 = $arr_tbl_7[$key_1]."0";}
	if (strlen(substr(strstr($arr_tbl_7[$key_1], "."), 1))==2) { $tbl_1_7 = $arr_tbl_7[$key_1];}
	if (strlen(substr(strstr($arr_tbl_7[$key_1], "."), 1))>2) { $tbl_1_7 = number_format($arr_tbl_7[$key_1], 2, '.', '');}
	if (strlen(substr(strstr($arr_tbl_8[$key_1], "."), 1))==0) { $tbl_1_8 = $arr_tbl_8[$key_1].".00";}
	if (strlen(substr(strstr($arr_tbl_8[$key_1], "."), 1))==1) { $tbl_1_8 = $arr_tbl_8[$key_1]."0";}
	if (strlen(substr(strstr($arr_tbl_8[$key_1], "."), 1))==2) { $tbl_1_8 = $arr_tbl_8[$key_1];}
	if (strlen(substr(strstr($arr_tbl_8[$key_1], "."), 1))>2) { $tbl_1_8 = number_format($arr_tbl_8[$key_1], 2, '.', '');}
	if (strlen(substr(strstr($arr_tbl_9[$key_1], "."), 1))==0) { $tbl_1_9 = $arr_tbl_9[$key_1].".00";}
	if (strlen(substr(strstr($arr_tbl_9[$key_1], "."), 1))==1) { $tbl_1_9 = $arr_tbl_9[$key_1]."0";}
	if (strlen(substr(strstr($arr_tbl_9[$key_1], "."), 1))==2) { $tbl_1_9 = $arr_tbl_9[$key_1];}
	if (strlen(substr(strstr($arr_tbl_9[$key_1], "."), 1))>2) { $tbl_1_9 = number_format($arr_tbl_9[$key_1], 2, '.', '');}
	if (strlen(substr(strstr($arr_tbl_10[$key_1], "."), 1))==0) { $tbl_1_10 = $arr_tbl_10[$key_1].".00";}
	if (strlen(substr(strstr($arr_tbl_10[$key_1], "."), 1))==1) { $tbl_1_10 = $arr_tbl_10[$key_1]."0";}
	if (strlen(substr(strstr($arr_tbl_10[$key_1], "."), 1))==2) { $tbl_1_10 = $arr_tbl_10[$key_1];}
	if (strlen(substr(strstr($arr_tbl_10[$key_1], "."), 1))>2) { $tbl_1_10 = number_format($arr_tbl_10[$key_1], 2, '.', '');}
	if (strlen(substr(strstr($arr_tbl_11[$key_1], "."), 1))==0) { $tbl_1_11 = $arr_tbl_11[$key_1].".00";}
	if (strlen(substr(strstr($arr_tbl_11[$key_1], "."), 1))==1) { $tbl_1_11 = $arr_tbl_11[$key_1]."0";}
	if (strlen(substr(strstr($arr_tbl_11[$key_1], "."), 1))==2) { $tbl_1_11 = $arr_tbl_11[$key_1];}
	if (strlen(substr(strstr($arr_tbl_11[$key_1], "."), 1))>2) { $tbl_1_11 = number_format($arr_tbl_11[$key_1], 2, '.', '');}
	echo "<tr name='arr_tbl_4_".$cur_z_ind."' style='display:none;'>
	<td class='field' style='background:#CBDEF4;'>".$arr_tbl_1[$key_1]."</td>
	<td class='field'>".$arr_tbl_2[$key_1]."</td>
	<td class='field' style='background:#a0ffa0;'>".$arr_tbl_3[$key_1]."</td>
	<td class='field' style='background:#b8ffb8;'>".$arr_tbl_4_1[$key_1]."&nbsp;&nbsp;&nbsp;&nbsp;".$arr_tbl_4_2[$key_1]."</td>
	<td class='field' style='background:#d4ffd4;'>".$arr_tbl_5_1[$key_1]."&nbsp;&nbsp;&nbsp;&nbsp;".$arr_tbl_5_2[$key_1]."</td>
	<td class='field' style='text-align:right;'>".$tbl_1_6." <b>(".$arr_tbl_13[$key_1].")</b></td>
	<td class='field' style='text-align:right;'>".$tbl_1_7." <b>(".$arr_tbl_14[$key_1].")</b></td>
	<td class='field' style='text-align:right;'>".$tbl_1_8." <b>(".$arr_tbl_12[$key_1].")</b></td>
	<td class='field' style='text-align:right;'>".$tbl_1_9."</td>
	<td class='field' style='text-align:right;'>".$tbl_1_10."</td>
	<td class='field' style='text-align:right;'>".$tbl_1_11."</td>
	</tr>";
	$tbl_1_6 = "";
	$tbl_1_7 = "";
	$tbl_1_8 = "";
	$tbl_1_9 = "";
	$tbl_1_10 = "";
	$tbl_1_11 = "";
	$cur_vid = $arr_tbl_1[$key_1];
	$cur_oper = $arr_tbl_2[$key_1];
	$cur_park = $arr_tbl_3[$key_1];
	$cur_zak = $arr_tbl_4_2[$key_1];
}

		if (strlen(substr(strstr($itog_sum_6, "."), 1))==0) { $tbl_1_6 = $itog_sum_6.".00";}
		if (strlen(substr(strstr($itog_sum_6, "."), 1))==1) { $tbl_1_6 = $itog_sum_6."0";}
		if (strlen(substr(strstr($itog_sum_6, "."), 1))==2) { $tbl_1_6 = $itog_sum_6;}
		if (strlen(substr(strstr($itog_sum_6, "."), 1))>2) { $tbl_1_6 = number_format($itog_sum_6, 2, '.', '');}
		if (strlen(substr(strstr($itog_sum_7, "."), 1))==0) { $tbl_1_7 = $itog_sum_7.".00";}
		if (strlen(substr(strstr($itog_sum_7, "."), 1))==1) { $tbl_1_7 = $itog_sum_7."0";}
		if (strlen(substr(strstr($itog_sum_7, "."), 1))==2) { $tbl_1_7 = $itog_sum_7;}
		if (strlen(substr(strstr($itog_sum_7, "."), 1))>2) { $tbl_1_7 = number_format($itog_sum_7, 2, '.', '');}
		if (strlen(substr(strstr(($itog_sum_6-$itog_sum_7), "."), 1))==0) { $tbl_1_8 = ($itog_sum_6-$itog_sum_7).".00";}
		if (strlen(substr(strstr(($itog_sum_6-$itog_sum_7), "."), 1))==1) { $tbl_1_8 = ($itog_sum_6-$itog_sum_7)."0";}
		if (strlen(substr(strstr(($itog_sum_6-$itog_sum_7), "."), 1))==2) { $tbl_1_8 = ($itog_sum_6-$itog_sum_7);}
		if (strlen(substr(strstr(($itog_sum_6-$itog_sum_7), "."), 1))>2) { $tbl_1_8 = number_format(($itog_sum_6-$itog_sum_7), 2, '.', '');}
		if (strlen(substr(strstr(($itog_sum_7/$itog_sum_6*100), "."), 1))==0) { $tbl_1_9 = ($itog_sum_7/$itog_sum_6*100).".00";}
		if (strlen(substr(strstr(($itog_sum_7/$itog_sum_6*100), "."), 1))==1) { $tbl_1_9 = ($itog_sum_7/$itog_sum_6*100)."0";}
		if (strlen(substr(strstr(($itog_sum_7/$itog_sum_6*100), "."), 1))==2) { $tbl_1_9 = ($itog_sum_7/$itog_sum_6*100);}
		if (strlen(substr(strstr(($itog_sum_7/$itog_sum_6*100), "."), 1))>2) { $tbl_1_9 = number_format(($itog_sum_7/$itog_sum_6*100), 2, '.', '');}
		if (strlen(substr(strstr($itog_sum_10, "."), 1))==0) { $tbl_1_10 = $itog_sum_10.".00";}
		if (strlen(substr(strstr($itog_sum_10, "."), 1))==1) { $tbl_1_10 = $itog_sum_10."0";}
		if (strlen(substr(strstr($itog_sum_10, "."), 1))==2) { $tbl_1_10 = $itog_sum_10;}
		if (strlen(substr(strstr($itog_sum_10, "."), 1))>2) { $tbl_1_10 = number_format($itog_sum_10, 2, '.', '');}
		if (strlen(substr(strstr(($itog_sum_10/$itog_sum_7), "."), 1))==0) { $tbl_1_11 = ($itog_sum_10/$itog_sum_7).".00";}
		if (strlen(substr(strstr(($itog_sum_10/$itog_sum_7), "."), 1))==1) { $tbl_1_11 = ($itog_sum_10/$itog_sum_7)."0";}
		if (strlen(substr(strstr(($itog_sum_10/$itog_sum_7), "."), 1))==2) { $tbl_1_11 = ($itog_sum_10/$itog_sum_7);}
		if (strlen(substr(strstr(($itog_sum_10/$itog_sum_7), "."), 1))>2) { $tbl_1_11 = number_format(($itog_sum_10/$itog_sum_7), 2, '.', '');}
echo "<tr class='first'>
<td colspan='5'><b style='float:right;'>ИТОГО:</b></td>
	<td><b>".$tbl_1_6."</b></td>
	<td><b>".$tbl_1_7."</b></td>
	<td><b>".$tbl_1_8."</b></td>
	<td><b>".$tbl_1_9."</b></td>
	<td><b>".$tbl_1_10."</b></td>
	<td><b>".$tbl_1_11."</b></td>
</tr>";
echo"</tbody></table>";

echo "<script type='text/javascript'>
function show_tr_1(b_name, tr_class, obj){
	var all_obj = document.getElementsByName(b_name).length;
	for (var f_a = 0; f_a < all_obj; f_a++){
		if (document.getElementsByName(b_name)[f_a].getAttribute('class') !== tr_class){
			document.getElementsByName(b_name)[f_a].style.display = 'table-row';
		}
	}
	obj.style.display = 'none';
	obj.parentNode.getElementsByTagName('b')[1].style.display = 'inline-block';
}
function show_tr_2(b_name, tr_class, obj){
	var nal_tbl_0 = 0;
	var cur_tbl_r_c_0 = 0;
	var cur_f_r;
	var cur_f_r_1;
			
		for (var f_b = (obj.parentNode.parentNode.rowIndex+1); f_b < obj.parentNode.parentNode.parentNode.getElementsByTagName('tr').length; f_b++){
			if (nal_tbl_0 == 0){
				if (obj.parentNode.parentNode.parentNode.rows[f_b].cells[0].getElementsByTagName('b')[0]){
					nal_tbl_0 = 1;
				}else{
					cur_tbl_r_c_0 = obj.parentNode.parentNode.parentNode.getElementsByTagName('tr').length;
				}
			}
			if (nal_tbl_0 == 1){
				cur_tbl_r_c_0 = f_b;
				nal_tbl_0 = 2;
			}
		}
		nal_tbl_0 = 0;
				
		for (var cur_ind = (obj.parentNode.parentNode.rowIndex-1); cur_ind < cur_tbl_r_c_0; cur_ind++){
			if (obj.parentNode.cellIndex == 0) {
				obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[1].getElementsByTagName('b')[0]){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[1].getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[1].getElementsByTagName('b')[1].style.display = 'none';
				}
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[2].getElementsByTagName('b')[0]){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[2].getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[2].getElementsByTagName('b')[1].style.display = 'none';
				}
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[0]){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[1].style.display = 'none';
				}
				obj.style.display = 'none';
				obj.parentNode.getElementsByTagName('b')[0].style.display = 'inline-block';
			}
			if (obj.parentNode.cellIndex == 1) {
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].getAttribute('name') == b_name){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
					cur_f_r = obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[2].getElementsByTagName('b')[0].getAttribute('name');
					obj.style.display = 'none';
					obj.parentNode.getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[2].getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[2].getElementsByTagName('b')[1].style.display = 'none';
				}
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].getAttribute('name') == cur_f_r){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
					cur_f_r_1 = obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[0].getAttribute('name');
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[1].style.display = 'none';
				}
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].getAttribute('name') == cur_f_r_1){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
				}
			}
			if (obj.parentNode.cellIndex == 2) {
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].getAttribute('name') == b_name){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
					cur_f_r = obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[0].getAttribute('name');
					obj.style.display = 'none';
					obj.parentNode.getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[0].style.display = 'inline-block';
					obj.parentNode.parentNode.parentNode.rows[cur_ind].cells[3].getElementsByTagName('b')[1].style.display = 'none';
				}
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].getAttribute('name') == cur_f_r){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
				}
			}
			if (obj.parentNode.cellIndex == 3) {
				if (obj.parentNode.parentNode.parentNode.rows[cur_ind].getAttribute('name') == b_name){
					obj.parentNode.parentNode.parentNode.rows[cur_ind].style.display = \"none\";
					obj.style.display = 'none';
					obj.parentNode.getElementsByTagName('b')[0].style.display = 'inline-block';
				}
			}
		}
}
var arr_numb_6 = [];
var arr_numb_7 = [];
var arr_numb_8 = [];
var arr_numb_9 = [];
var arr_numb_10 = [];
var arr_numb_11 = [];
for (var m_n = 0; m_n < document.getElementsByName('max_numb_6').length; m_n++){
	arr_numb_6.push(document.getElementsByName('max_numb_6')[m_n].innerHTML);
	arr_numb_7.push(document.getElementsByName('max_numb_7')[m_n].innerHTML);
	arr_numb_8.push(document.getElementsByName('max_numb_8')[m_n].innerHTML);
	arr_numb_9.push(document.getElementsByName('max_numb_9')[m_n].innerHTML);
	arr_numb_10.push(document.getElementsByName('max_numb_10')[m_n].innerHTML);
	arr_numb_11.push(document.getElementsByName('max_numb_11')[m_n].innerHTML);
}
for (var m_n = 0; m_n < document.getElementsByName('max_numb_6').length; m_n++){
	if (document.getElementsByName('max_numb_6')[m_n].innerHTML == Math.max.apply(Math, arr_numb_6)){
		document.getElementsByName('max_numb_6')[m_n].innerHTML = '<b style=\"color:red;\">' + document.getElementsByName('max_numb_6')[m_n].innerHTML + '</b>';
	}
	if (document.getElementsByName('max_numb_7')[m_n].innerHTML == Math.max.apply(Math, arr_numb_7)){
		document.getElementsByName('max_numb_7')[m_n].innerHTML = '<b style=\"color:red;\">' + document.getElementsByName('max_numb_7')[m_n].innerHTML + '</b>';
	}
	if (document.getElementsByName('max_numb_8')[m_n].innerHTML == Math.max.apply(Math, arr_numb_8)){
		document.getElementsByName('max_numb_8')[m_n].innerHTML = '<b style=\"color:red;\">' + document.getElementsByName('max_numb_8')[m_n].innerHTML + '</b>';
	}
	if (document.getElementsByName('max_numb_9')[m_n].innerHTML == Math.max.apply(Math, arr_numb_9)){
		document.getElementsByName('max_numb_9')[m_n].innerHTML = '<b style=\"color:red;\">' + document.getElementsByName('max_numb_9')[m_n].innerHTML + '</b>';
	}
	if (document.getElementsByName('max_numb_10')[m_n].innerHTML == Math.max.apply(Math, arr_numb_10)){
		document.getElementsByName('max_numb_10')[m_n].innerHTML = '<b style=\"color:red;\">' + document.getElementsByName('max_numb_10')[m_n].innerHTML + '</b>';
	}
	if (document.getElementsByName('max_numb_11')[m_n].innerHTML == Math.max.apply(Math, arr_numb_11)){
		document.getElementsByName('max_numb_11')[m_n].innerHTML = '<b style=\"color:red;\">' + document.getElementsByName('max_numb_11')[m_n].innerHTML + '</b>';
	}
}
</script>";
?>
