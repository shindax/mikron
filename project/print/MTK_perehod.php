<?php
define("MAV_ERP", TRUE);

$dse_id = $_GET['id'];

$resurses_nam_arr = array();
$resurses_all = dbquery("SELECT ID_users, NAME FROM okb_db_resurs where TID='0' ");
while($name_rs = mysql_fetch_row($resurses_all)){
	$resurses_nam_arr[$name_rs[0]] = $name_rs[1];
}

$avtor_arr = array();
$avtor_td = "";

// переменные по стилям
$fld = " class='Field'";
$rfld = " class='rwField ntabg'";
$stl_s = " style='";
$stl_f_1 = "font-size:8pt;";
$stl_f_2 = "font-size:11pt;";
$stl_f_3 = "font-size:16pt;";
$stl_f_3_1 = "font-size:14pt;";
$stl_e = "'";
$stl_c = "font-family:Times new roman;height:30px; text-align:center; padding:3px 4px 3px 4px; vertical-align:middle;";
$stl_cl = "font-family:Times new roman;height:30px; text-align:left; padding:3px 4px 3px 4px; vertical-align:middle;";
$stl_b = "border:3px solid black;";
$stl_bg = "background:#ddd;";
$stl_bl = "border-left:3px solid black;";
$stl_br = "border-right:3px solid black;";
$stl_bt = "border-top:3px solid black;";
$stl_bb = "border-bottom:3px solid black;";

// Технологическая карта карта
//
// шапка

$result_dse = dbquery("SELECT * FROM okb_db_zakdet where ID='".$dse_id."' ");
$name_dse = mysql_fetch_array($result_dse);
$arr_oper_tid = array(" ","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_0' style='border-bottom: 3px solid #000;'>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_e." colspan='4' rowspan='2'><b style='font-family:Times new roman; font-size:24pt; text-align:center;'>Технологическая карта</b><br><b style='font-family:Times new roman; ".$stl_f_3."text-align:center;'>".$arr_oper_tid[$name_oper_nam['TID']]."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_b."background:#ddd;".$stl_e." rowspan='2' id='avt_".$dse_id."'><b style='font-size:140%;'>АВТОР</b><br></td>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_f_3.$stl_e." colspan='6'>".$name_dse['OBOZ']."</td>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_e." colspan='2'>Лист № <b style='font-size:12pt;' name='list_tbl_num'>1</b></td>
</tr>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_f_2.$stl_e." colspan='8'>".$name_dse['NAME']."</td>
</tr>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_f_2.$stl_e." colspan='13'>Заготовка</td>
</tr>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_bl.$stl_f_2.$stl_e." colspan='4'>Наименование и марка материала</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e.">Масса<br>детали</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e." colspan='3'>Профиль и размеры<br>(длина, ширина, высота)</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e." colspan='3'>Тверд.</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_br.$stl_e." colspan='2'>Масса<br>загот.</td>
</tr>";

$result_zag_dse = dbquery("SELECT * FROM okb_db_zn_zag where ID_zakdet='".$dse_id."' ");
while ($name_zag_dse_1 = mysql_fetch_array($result_zag_dse)){
	$result_mat_1 = dbquery("SELECT * FROM okb_db_mat where ID='".$name_zag_dse_1['ID_mat']."' ");
	$name_mat_1 = mysql_fetch_array($result_mat_1);
	$result_sort_1 = dbquery("SELECT * FROM okb_db_sort where ID='".$name_zag_dse_1['ID_sort']."' ");
	$name_sort_1 = mysql_fetch_array($result_sort_1);

	echo "<tr>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_bl.$stl_e." colspan='4'>".$name_mat_1['OBOZ']." - ".$name_sort_1['OBOZ']."<br>".$name_mat_2['OBOZ']." - ".$name_sort_2['OBOZ']."</td>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_e.">".$name_dse['MASS']."<br>".$name_dse['MASS']."</td>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_e." colspan='3'>".$name_zag_dse_1['LL']."x".$name_zag_dse_1['WW']."x".$name_zag_dse_1['HH']."<br>".$name_zag_dse_2['LL']."x".$name_zag_dse_2['WW']."x".$name_zag_dse_2['HH']."</td>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_e." colspan='3'></td>
	<td".$fld.$stl_s.$stl_c.$stl_br.$stl_f_1.$stl_e." colspan='2'>".$name_zag_dse_1['NORM']."<br>".$name_zag_dse_2['NORM']."</td>
	</tr>";
}

$result_zag_dse = dbquery("SELECT * FROM okb_db_zn_zag where ID_zakdet='".$dse_id."' ");
if (!mysql_fetch_array($result_zag_dse)){
	echo "<tr>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_bl.$stl_e." colspan='4'></td>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_e."></td>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_e." colspan='3'></td>
	<td".$fld.$stl_s.$stl_c.$stl_f_1.$stl_e." colspan='3'></td>
	<td".$fld.$stl_s.$stl_c.$stl_br.$stl_f_1.$stl_e." colspan='2'></td>
	</tr>";	
}

echo "<tr>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bt.$stl_bl.$stl_f_3.$stl_e." rowspan='2'>№</td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bt.$stl_f_3.$stl_e." rowspan='2' colspan='2'>Содержание перехода</td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_bl.$stl_f_3.$stl_e." colspan='3'>Инструмент (код и наименование)</td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_bl.$stl_f_2.$stl_e." colspan='2'>Расчётн.разм.</td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_f_2.$stl_e." colspan='3'>Режим обработки</td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_f_2.$stl_e." rowspan='2'>То</td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_br.$stl_f_2.$stl_e." rowspan='2'>Тп</td>
</tr>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bl.$stl_f_2.$stl_e.">Вспомагательный</td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_f_2.$stl_e.">Режущий</td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_f_2.$stl_e.">Измерительный</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_bl.$stl_e.">Диаметр<br>ширина</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e.">Длина</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e.">S</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e.">N</td>
<td".$fld.$stl_s.$stl_c.$stl_f_2.$stl_e.">V</td>
</tr>";

// Перечисление операций на изделие

$result_op = dbquery("SELECT * FROM okb_db_operitems where ID_zakdet='".$dse_id."' AND CHANCEL = 0 ORDER BY ORD");
while ($name_op = mysql_fetch_array($result_op)){ 

$result_oper_nam = dbquery("SELECT * FROM okb_db_oper where ID='".$name_op['ID_oper']."' ");
$name_oper_nam = mysql_fetch_array($result_oper_nam);
$result_park = dbquery("SELECT * FROM okb_db_park where ID='".$name_op['ID_park']."' ");
$name_park = mysql_fetch_array($result_park);

echo "<tr name='tr1_oper' id='tr1_oper_id_".$name_op['ID']."'>
<td".$fld.$stl_s.$stl_c.$stl_bl.$stl_bt.$stl_bg.$stl_e."><b".$stl_s.$stl_f_1.$stl_e.">Номер<br>операции</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_f_2.$stl_bg.$stl_e." colspan='3'><b".$stl_s.$stl_f_3_1.$stl_e.">Наименование операции</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_f_2.$stl_bg.$stl_e." colspan='3'><b".$stl_s.$stl_f_3_1.$stl_e.">Оборудование (наименование, марка)</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_bg.$stl_e." colspan='2'><b".$stl_s.$stl_f_1.$stl_e.">Норма<br>на ед., мин</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_bg.$stl_e." colspan='2'><b".$stl_s.$stl_f_1.$stl_e.">Норма<br>на пз., мин</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_bg.$stl_e."><b".$stl_s.$stl_f_1.$stl_e.">На зак.<br>шт.</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bt.$stl_br.$stl_bg.$stl_e."><b".$stl_s.$stl_f_1.$stl_e.">На зак.<br>Н/Ч</b></td>
</tr>
<tr name='tr2_oper' id='tr2_oper_id_".$name_op['ID']."'>
<td".$fld.$stl_s.$stl_c.$stl_b."width:55px;".$stl_bg.$stl_f_2.$stl_e."><b".$stl_s.$stl_f_2.$stl_e.">".$name_op['ORD']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bg.$stl_f_2.$stl_e." colspan='3'><b".$stl_s.$stl_f_3_1.$stl_e.">".$arr_oper_tid[$name_oper_nam['TID']]." - ".$name_oper_nam['NAME']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bg.$stl_f_2.$stl_e." colspan='3'><b".$stl_s.$stl_f_3_1.$stl_e.">".$name_park['NAME']." - ".$name_park['MARK']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bg.$stl_e." colspan='2'><b".$stl_s.$stl_f_2.$stl_e.">".$name_op['NORM']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bg.$stl_e." colspan='2'><b".$stl_s.$stl_f_2.$stl_e.">".$name_op['NORM_2']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_bg.$stl_e."><b".$stl_s.$stl_f_2.$stl_e.">".$name_op['NUM_ZAK']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_bb.$stl_br.$stl_bg.$stl_e."><b".$stl_s.$stl_f_2.$stl_e.">".$name_op['NORM_ZAK']."</b></td>
</tr>";
if (($name_op['ID_user']!=='') and ($name_op['ID_user']!=='0')){
	$avtor_arr[$name_op['ID_user']] = $name_op['ID_user'];
}

	// Перечисление переходов по операции

	$result = dbquery("SELECT * FROM okb_db_mtk_perehod where ID_operitems='".$name_op['ID']."' ORDER BY TID");
	while ($name = mysql_fetch_array($result)){ 
		echo "<tr name='tr_per_par_tr_".$name_op['ID']."'>
		<td".$fld.$stl_s.$stl_c.$stl_bl.$stl_e.">".$name['TID']."</td>
		<td".$fld.$stl_s.$stl_cl."min-width:370px;".$stl_e." colspan='2'><b".$stl_s.$stl_f_3_1.$stl_e.">".$name['TXT']."</b></td>
		<td".$fld.$stl_s.$stl_c.$stl_bl."width:140px;".$stl_e."><b".$stl_s.$stl_f_3_1.$stl_e.">".$name['INSTR_1']."</b></td>
		<td".$fld.$stl_s.$stl_c."width:140px;".$stl_e."><b".$stl_s.$stl_f_3_1.$stl_e.">".$name['INSTR_2']."</b></td>
		<td".$fld.$stl_s.$stl_c.$stl_br."width:140px;".$stl_e."><b".$stl_s.$stl_f_3_1.$stl_e.">".$name['INSTR_3']."</b></td>
		<td".$fld.$stl_s.$stl_c."width:70px;".$stl_e.">".$name['DIAM_SHIR']."</td>
		<td".$fld.$stl_s.$stl_c."width:70px;".$stl_e.">".$name['DLINA']."</td>
		<td".$fld.$stl_s.$stl_c."width:35px;".$stl_e.">".$name['R_O_S']."</td>
		<td".$fld.$stl_s.$stl_c."width:35px;".$stl_e.">".$name['R_O_N']."</td>
		<td".$fld.$stl_s.$stl_c."width:35px;".$stl_e.">".$name['R_O_V']."</td>
		<td".$fld.$stl_s.$stl_c."width:50px;".$stl_e.">".$name['R_O_TO']."</td>
		<td".$fld.$stl_s.$stl_c.$stl_br."width:50px;".$stl_e.">".$name['R_O_TP']."</td>
		</tr>";
	}
}
foreach($avtor_arr as $eke_1 => $ava_1){
	$avtor_td .= "<b>".$resurses_nam_arr[$ava_1]."</b><br>";
}
echo "<script type='text/javascript'>
document.getElementById('avt_".$dse_id."').innerHTML = document.getElementById('avt_".$dse_id."').innerHTML + '".$avtor_td."';
</script>";

echo "</tbody></table>
<div name='MTK_perehod_div' style='height:7px;'></div>";

// вторая страница

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_1' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// третья страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_2' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// четвёртая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_3' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// пятая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_4' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// шестая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_5' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// седьмая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_6' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// восьмая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_7' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// девятая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_8' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

// десятая страница 

echo "<table style='background:#fff;' name='MTK_perehod' width='1366px'><tbody id='MTK_perehod_9' style='border-bottom: 3px solid #000;'>
</tbody></table>
<div name='MTK_perehod_div' style='height:0px;'></div>";

/////////////////////////////////////////////////////
// карта эскизов
/////////////////////////////////////////////////////

$result = dbquery("SELECT * FROM okb_db_mtk_perehod_img where ID_zakdet='".$dse_id."' ORDER BY ID_operitems,TID ");
while ($name = mysql_fetch_array($result)){ 

$result_op_2 = dbquery("SELECT * FROM okb_db_operitems where ID='".$name['ID_operitems']."' AND CHANCEL = 0");
$name_op_2 = mysql_fetch_array($result_op_2);
$result_oper_nam_2 = dbquery("SELECT * FROM okb_db_oper where ID='".$name_op_2['ID_oper']."' ");
$name_oper_nam_2 = mysql_fetch_array($result_oper_nam_2);

echo "<table style='background:#fff;' width='1366px'><tbody>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_bl.$stl_bt.$stl_e.">№ операции</td>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_e." rowspan='2'><b style='font-family:Times new roman; font-size:16pt; text-align:center;'>".$arr_oper_tid[$name_oper_nam_2['TID']]." - ".$name_oper_nam_2['NAME']."</b></td>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_f_3.$stl_e." colspan='2'>".$name_dse['OBOZ']."</td>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_e.">Лист № <b style='font-size:12pt;'>".$name['TID']."</b></td>
</tr>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_bl.$stl_f_3.$stl_e.">".$name_op_2['ORD']."</td>
<td".$fld.$stl_s.$stl_c.$stl_b.$stl_f_3.$stl_e." colspan='8'>".$name_dse['NAME']."</td>
</tr>
<tr>
<td".$fld.$stl_s.$stl_c.$stl_b."height:870px;".$stl_e." colspan='13'>
<img src='project/63gu88s920hb045e/db_mtk_perehod@IMAGES/".$name['IMG']."' style='max-width:1343px; max-height:850px;'>
</td>
</tr></tbody></table></span><div name='div_img' style='height:26px;'></div>";
}

// проверка на перенос на след.страницу ТК и КЭ
echo "<!--script type='text/javascript'>
var arr_tr_index_hid = [];
var arr_tr_oper_hid = [];
var ch_full_h = 0;
var next_ind;
var new_ind_tbl;
if (getComputedStyle(document.getElementsByName('MTK_perehod')[0]).height.substr(0, (getComputedStyle(document.getElementsByName('MTK_perehod')[0]).height.length-2))<966) {
	document.getElementsByName('MTK_perehod_div')[0].style.height=(966-getComputedStyle(document.getElementsByName('MTK_perehod')[0]).height.substr(0, (getComputedStyle(document.getElementsByName('MTK_perehod')[0]).height.length-2)));
}else{
	var count_opers_tbl1 = document.getElementsByName('tr1_oper').length;
	var id_sel_oper = document.getElementsByName('tr1_oper')[count_opers_tbl1-1].id.substr(12);
	var count_pers_in_op_tbl1 = document.getElementsByName('tr_per_par_tr_'+id_sel_oper).length;
	for (var f_per_op = 0; f_per_op < count_pers_in_op_tbl1; f_per_op++){
		document.getElementsByName('tr_per_par_tr_'+id_sel_oper)[f_per_op].style.display='none';
		arr_tr_index_hid.push(document.getElementsByName('tr_per_par_tr_'+id_sel_oper)[f_per_op].rowIndex);
	}
	document.getElementsByName('tr1_oper')[count_opers_tbl1-1].style.display='none';
	arr_tr_index_hid.push(document.getElementsByName('tr1_oper')[count_opers_tbl1-1].rowIndex);
	document.getElementsByName('tr2_oper')[count_opers_tbl1-1].style.display='none';
	arr_tr_index_hid.push(document.getElementsByName('tr2_oper')[count_opers_tbl1-1].rowIndex);
	arr_tr_oper_hid.push(id_sel_oper);
	ch_full_h = 1;
	check_height_tbl('0', 1);
}
function check_height_tbl(index_tbl, ch_full_h_f){
	if (getComputedStyle(document.getElementsByName('MTK_perehod')[index_tbl]).height.substr(0, (getComputedStyle(document.getElementsByName('MTK_perehod')[index_tbl]).height.length-2))<966) {
		document.getElementsByName('MTK_perehod_div')[index_tbl].style.height=(966-getComputedStyle(document.getElementsByName('MTK_perehod')[index_tbl]).height.substr(0, (getComputedStyle(document.getElementsByName('MTK_perehod')[index_tbl]).height.length-2)));
		next_ind = 1;
		new_ind_tbl = index_tbl+next_ind;
	}else{
		var count_opers_tbl1 = document.getElementsByName('tr1_oper').length;
		var id_sel_oper = document.getElementsByName('tr1_oper')[count_opers_tbl1-1-arr_tr_oper_hid.length].id.substr(12);
		var count_pers_in_op_tbl1 = document.getElementsByName('tr_per_par_tr_'+id_sel_oper).length;
		for (var f_per_op = 0; f_per_op < count_pers_in_op_tbl1; f_per_op++){
			document.getElementsByName('tr_per_par_tr_'+id_sel_oper)[f_per_op].style.display='none';
			arr_tr_index_hid.push(document.getElementsByName('tr_per_par_tr_'+id_sel_oper)[f_per_op].rowIndex);
		}
		document.getElementsByName('tr1_oper')[count_opers_tbl1-1-arr_tr_oper_hid.length].style.display='none';
		arr_tr_index_hid.push(document.getElementsByName('tr1_oper')[count_opers_tbl1-1-arr_tr_oper_hid.length].rowIndex);
		document.getElementsByName('tr2_oper')[count_opers_tbl1-1-arr_tr_oper_hid.length].style.display='none';
		arr_tr_index_hid.push(document.getElementsByName('tr2_oper')[count_opers_tbl1-1-arr_tr_oper_hid.length].rowIndex);
		arr_tr_oper_hid.push(id_sel_oper);
		ch_full_h = ch_full_h_f;
		check_height_tbl(index_tbl, ch_full_h_f);
	}
}

function show_next_tbl(index_tbl, ch_full_h_f){
	document.getElementById('MTK_perehod_'+index_tbl).innerHTML=document.getElementById('MTK_perehod_0').innerHTML;
	document.getElementsByName('list_tbl_num')[index_tbl].innerHTML=index_tbl+1;
	var tbl_count_rows = document.getElementById('MTK_perehod_'+index_tbl).rows.length;

  function getMin(arr) {
    var arrLen = arr.length,
        minEl = arr[0];
    for (var i = 0; i < arrLen; i++) {
      if (minEl > arr[i]) {
        minEl = arr[i];
      }
    }
    return minEl;
  }
  fMin = getMin(arr_tr_index_hid);
  
	for (var f_c_tb=7; f_c_tb<fMin; f_c_tb++){
		document.getElementById('MTK_perehod_'+index_tbl).rows[f_c_tb].style.display='none';
	}
	for (var f_c_tb=fMin; f_c_tb<tbl_count_rows; f_c_tb++){
		document.getElementById('MTK_perehod_'+index_tbl).rows[f_c_tb].style.display='table-row';
	}
	arr_tr_index_hid = [];
	arr_tr_oper_hid = [];
	check_height_tbl(index_tbl, (ch_full_h_f+1));	
}

if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
if (ch_full_h==1){
	ch_full_h = 0;
	show_next_tbl(new_ind_tbl++, ch_full_h);
}
document.getElementsByName('div_img')[document.getElementsByName('div_img').length-1].style.height='0px';
</script-->";
?>