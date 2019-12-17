<script type="text/javascript" src="/project/zadan/js/ch_new_nav.js?2"></script>
<script type="text/javascript" src="/project/zadan/js/check_cur_zak.js?2"></script>
<script type="text/javascript" src="/project/zadan/js/find_text_inp.js?2"></script>
<script type="text/javascript" src="/project/zadan/js/find_text_inp2.js?2"></script>
<script type="text/javascript" src="/project/zadan/js/zadan_full.js?2"></script>

<link rel='stylesheet' href='/project/zadan/css/style.css' type='text/css'>

<?php
setlocale(LC_ALL, 'en_US.UTF8');

$user_id = $user['ID'];

function txt($text)
{
	$text = stripslashes($text);
	$search = array("&#39;", "&quot;", "(", ")", "\n", "&#38;", "#", "&#092;", "+");
	$replace = array("@%1@", "@%2@", "@%3@", "@%4@", "@%5@", "@%6@", "@%7@", "@%8@", "@%9@");
	$text = str_replace($search, $replace, $text);
	return $text;
}

function check_all_tree_dse($id_cur_zak, $id_par_dse, $pid_par_dse, $child_n)
{
	global $arr_dse_nam, $arr_dse_oboz, $child_n_ar, $zak_tip, $ids_dse_cur_zak, $names_dse_cur_zak, $obozs_dse_cur_zak, $child_dse_cur_zak, $id_cur_zak;

	$ch_v = '';
	for ($ch_n = 1; $ch_n < $child_n; ++$ch_n)
		$ch_v .= ' .. /';

	$ids_dse_cur_zak .= $id_par_dse.'|';
	$names_dse_cur_zak .= $arr_dse_nam[$id_par_dse].'|';
	$obozs_dse_cur_zak .= $arr_dse_oboz[$id_par_dse].'|';
	$child_dse_cur_zak .= $ch_v.'|';
	$re_s2 = dbquery("SELECT ID, PID FROM okb_db_zakdet where (ID_zak='".$id_cur_zak."') AND (PID='".$id_par_dse."') order by ORD");
	while ($na_m2 = mysql_fetch_array($re_s2))
	{
		if ($na_m2['PID'] == $id_par_dse)
			$child_n_ar[$child_n] = $child_n+1;

		check_all_tree_dse($id_cur_zak, $na_m2['ID'], $na_m2['PID'], $child_n_ar[$child_n], $child_n_ar[$child_n_pr]);
	}
}

	if (db_check("db_zadan","MEGA_REDACTOR"))
    $editing = true;
	if ($editing)
	{

	echo "</form>";


	echo "<div id='dialog' title='Работы по кооперации'><div></div></div>";
	echo "<form id='form1x'>";

//zadanres под iframe /////////////////////////////////////////////////////////

	echo "<iframe id='cur_smen_sz' class='cur_smen_sz_fr' src=''></iframe>";

// предыдущие операции под iframe ///////////////////////////////////////////////////////////////

	echo "<iframe id='cur_res_pred_op' class='cur_res_pred_op_fr' src=''></iframe>";

// приоритетность операций по ресурсу ///////////////////////////////////////////////////////////////

	$ids_resurses = '';
	$ids_opers_cur_r = '';
	$pr_op_res = dbquery("SELECT ID, OPER_IDS FROM okb_db_resurs where TID=0 ");

	while ($pr_op_nam = mysql_fetch_row($pr_op_res))
	{
		$ids_resurses .= $pr_op_nam[0].'|';
		$ids_opers_cur_r .= $pr_op_nam[1].'=--=';
	}


// ТАБЛИЦА ///////////////////////////////////////////////////////////////
// Функционал перед отображением таблицы //////////

	$zak_tip = array(' ','ОЗ','КР','СП','БЗ','ХЗ','ВЗ');
	$oper_tip = array(' ', 'Заготовка','Сборка-сварка','Механообработка','Сборка','Термообработка','Упаковка','Окраска','Прочее');
	$park_tip = array(' ','Заготовка','Сборка-сварка','Механообработка','Сборка','Термообработка','Упаковка','Окраска');

	$child_n_ar = array();
	$ids_dse_cur_zak = '';
	$names_dse_cur_zak = '';
	$obozs_dse_cur_zak = '';
	$child_dse_cur_zak = '';
	$tips_zaks = '';
	$names_zaks = '';
	$dse_names_zaks = '';
	$ids_zaks = '';
	$ids_dses = '';
	$names_dses = '';
	$obozs_dses = '';
	$child_dses = '';
	$child_n_ar[0] = 1;
	$arr_dse_nam = array();
	$arr_dse_oboz = array();
	$arr_dse_pid = array();

	$arr_full_tbl_1 = '';
	$arr_full_tbl_2 = '';
	$arr_full_tbl_3 = '';
	$arr_full_tbl_4 = '';
	$arr_full_tbl_5 = '';
	$arr_full_tbl_5_1 = '';
	$arr_full_tbl_6 = '';
	$arr_full_tbl_7 = '';
	$arr_full_tbl_8 = '';
	$arr_full_tbl_9 = '';
	$arr_full_tbl_10 = '';
	$arr_full_tbl_11 = '';
	$arr_full_tbl_12 = '';
	$arr_full_tbl_13 = '';
	$arr_full_tbl_14 = '';
	$arr_full_tbl_15 = '';
	$arr_full_tbl_16 = '';
	$arr_full_tbl_17 = '';
	$arr_full_tbl_18 = '';
	$arr_full_tbl_19 = '';

// строю массив операций под каждое ДСЕ

	$arr_tbl_1 = array();
	$arr_tbl_2 = array();
	$arr_tbl_3 = array();
	$arr_tbl_4 = array();
	$arr_tbl_5 = array();
	$arr_tbl_5_1 = array();
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
	$arr_tbl_16 = array();

	$arr_opers_nam = array();
	$arr_opers_tid = array();
	$res_op_2 = dbquery("SELECT ID,NAME,TID FROM okb_db_oper ");

	while($nam_op_2 = mysql_fetch_array($res_op_2))
	{
		$arr_opers_nam[$nam_op_2['ID']]=$nam_op_2['NAME'];
		$arr_opers_tid[$nam_op_2['ID']]=$nam_op_2['TID'];
	}

	$arr_parks_nam = array();
	$arr_parks_mar = array();
	$arr_parks_tid = array();
	$res_op_3 = dbquery("SELECT ID,NAME,MARK,TID FROM okb_db_park ");

	while($nam_op_3 = mysql_fetch_array($res_op_3))
	{
		$arr_parks_nam[$nam_op_3['ID']]=$nam_op_3['NAME'];
		$arr_parks_mar[$nam_op_3['ID']]=$nam_op_3['MARK'];
		$arr_parks_tid[$nam_op_3['ID']]=$nam_op_3['TID'];
	}

	// $res_op_1 = dbquery("SELECT
	// okb_db_operitems.ID, okb_db_operitems.ORD, okb_db_operitems.NUM_ZAK,
	// okb_db_operitems.ID_zakdet, okb_db_operitems.ID_oper, okb_db_operitems.ID_park,
	// okb_db_operitems.NORM_ZAK, okb_db_operitems.FACT2_NUM, okb_db_operitems.FACT2_NORM,
	// okb_db_operitems.KSZ_NUM, okb_db_operitems.KSZ2_NUM, okb_db_operitems.MSG_INFO,
	// okb_db_operitems.BRAK, okb_db_operitems.NORM_FACT, okb_db_operitems.STATE, okb_db_operitems.CHANCEL
	// FROM okb_db_operitems
	// INNER JOIN okb_db_zak ON okb_db_operitems.ID_zak = okb_db_zak.ID
	// where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ='1') and (okb_db_operitems.CHANCEL='0')) order by okb_db_operitems.ID_zakdet,okb_db_operitems.BRAK,okb_db_operitems.ORD");

	$res_op_1 = dbquery("
	SELECT
	okb_db_operitems.ID, okb_db_operitems.ORD, okb_db_operitems.NUM_ZAK,
	okb_db_operitems.ID_zakdet, okb_db_operitems.ID_oper, okb_db_operitems.ID_park,
	okb_db_operitems.NORM_ZAK, SUM(zadan.NUM_FACT) as FACT2_NUM, SUM(zadan.FACT) as FACT2_NORM,
	okb_db_operitems.KSZ_NUM, okb_db_operitems.KSZ2_NUM, okb_db_operitems.MSG_INFO,
	okb_db_operitems.BRAK, okb_db_operitems.NORM_FACT, okb_db_operitems.STATE, okb_db_operitems.CHANCEL,
	SUM( coop.count ) cnt,
	COUNT( coop.count ) coop_cnt,
	SUM( coop.norm_hours ) norm_hours 	
	FROM okb_db_operitems
	INNER JOIN okb_db_zak ON okb_db_operitems.ID_zak = okb_db_zak.ID
	LEFT JOIN okb_db_operations_with_coop_dep coop ON coop.oper_id  = okb_db_operitems.ID
	LEFT JOIN okb_db_zadan zadan ON zadan.ID_operitems  = okb_db_operitems.ID
	where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ=1) and (okb_db_operitems.CHANCEL='0')) 
	GROUP BY okb_db_operitems.ID
	order by okb_db_operitems.ID_zakdet,okb_db_operitems.BRAK,okb_db_operitems.ORD
	");

	dbquery('SET group_concat_max_len = 2048');
	
		while($nam_op_1=mysql_fetch_row($res_op_1))
		{
				$per_7_txt = txt(mysql_result(dbquery("SELECT GROUP_CONCAT(TXT SEPARATOR '<br>') FROM okb_db_mtk_perehod where (ID_operitems = '".$nam_op_1[0]."')"), 0));

				$arr_tbl_1[$nam_op_1[3]] .= $nam_op_1[0].'|';
				$arr_tbl_2[$nam_op_1[3]] .= $nam_op_1[1].'|';
				$arr_tbl_3[$nam_op_1[3]] .= $nam_op_1[2].'|';
				$arr_tbl_4[$nam_op_1[3]] .= '<b>'.$arr_opers_nam[$nam_op_1[4]].' - '.$oper_tip[$arr_opers_tid[$nam_op_1[4]]].'</b><br>'.$per_7_txt.'|';
				$arr_tbl_5[$nam_op_1[3]] .= $arr_parks_nam[$nam_op_1[5]].' - '.$arr_parks_mar[$nam_op_1[5]].' - '.$park_tip[$arr_parks_tid[$nam_op_1[5]]].'|';
				$arr_tbl_5_1[$nam_op_1[3]] .= $nam_op_1[5].'|';
				$arr_tbl_6[$nam_op_1[3]] .= $nam_op_1[6].'|';
				$arr_tbl_7[$nam_op_1[3]] .= $nam_op_1[7].'|';
				$arr_tbl_8[$nam_op_1[3]] .= $nam_op_1[8].'|';
				$arr_tbl_9[$nam_op_1[3]] .= $nam_op_1[9].'|';
				$arr_tbl_10[$nam_op_1[3]] .= $nam_op_1[10].'|';
				$arr_tbl_11[$nam_op_1[3]] .= $nam_op_1[11].'|';
				$arr_tbl_12[$nam_op_1[3]] .= $nam_op_1[12].'|';
				$arr_tbl_13[$nam_op_1[3]] = $nam_op_1[3];
				$arr_tbl_14[$nam_op_1[3]] .= $nam_op_1[13].'|';
				$arr_tbl_15[$nam_op_1[3]] .= $nam_op_1[14].'|';
				$arr_tbl_16[$nam_op_1[3]] .= $nam_op_1[4].'|';
				$arr_tbl_17[$nam_op_1[3]] .= $nam_op_1[16].'|';
				$arr_tbl_18[$nam_op_1[3]] .= $nam_op_1[17].'|';
				$arr_tbl_19[$nam_op_1[3]] .= number_format( $nam_op_1[18], 2 ).'|';				
	}
	foreach($arr_tbl_1 as $k_tbl_1 => $v_tbl_1)
	{
		$arr_full_tbl_1 .= $arr_tbl_1[$k_tbl_1].'=--=';
		$arr_full_tbl_2 .= $arr_tbl_2[$k_tbl_1].'=--=';
		$arr_full_tbl_3 .= $arr_tbl_3[$k_tbl_1].'=--=';
		$arr_full_tbl_4 .= $arr_tbl_4[$k_tbl_1].'=--=';
		$arr_full_tbl_5 .= $arr_tbl_5[$k_tbl_1].'=--=';
		$arr_full_tbl_5_1 .= $arr_tbl_5_1[$k_tbl_1].'=--=';
		$arr_full_tbl_6 .= $arr_tbl_6[$k_tbl_1].'=--=';
		$arr_full_tbl_7 .= $arr_tbl_7[$k_tbl_1].'=--=';
		$arr_full_tbl_8 .= $arr_tbl_8[$k_tbl_1].'=--=';
		$arr_full_tbl_9 .= $arr_tbl_9[$k_tbl_1].'=--=';
		$arr_full_tbl_10 .= $arr_tbl_10[$k_tbl_1].'=--=';
		$arr_full_tbl_11 .= $arr_tbl_11[$k_tbl_1].'=--=';
		$arr_full_tbl_12 .= $arr_tbl_12[$k_tbl_1].'=--=';
		$arr_full_tbl_13 .= $arr_tbl_13[$k_tbl_1].'=--=';
		$arr_full_tbl_14 .= $arr_tbl_14[$k_tbl_1].'=--=';
		$arr_full_tbl_15 .= $arr_tbl_15[$k_tbl_1].'=--=';
		$arr_full_tbl_16 .= $arr_tbl_16[$k_tbl_1].'=--=';
		$arr_full_tbl_17 .= $arr_tbl_17[$k_tbl_1].'=--=';
		$arr_full_tbl_18 .= $arr_tbl_18[$k_tbl_1].'=--=';
		$arr_full_tbl_19 .= $arr_tbl_19[$k_tbl_1].'=--=';		
	}
	//echo ($arr_full_tbl_14)."<br>";
// строю дерево ДСЕ под каждый заказ

	$re_s4 = dbquery("SELECT okb_db_zakdet.ID, okb_db_zakdet.NAME, okb_db_zakdet.OBOZ, okb_db_zakdet.LID 
	FROM okb_db_zakdet 
	INNER JOIN okb_db_zak ON okb_db_zakdet.ID_zak = okb_db_zak.ID 
	where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ=1)) ");

	while($na_m4 = mysql_fetch_row($re_s4))
	{
		if ($na_m4[3]!=='0')
		{
			$arr_dse_nam[$na_m4[0]]=$arr_dse_nam[$na_m4[3]];
			$arr_dse_oboz[$na_m4[0]]=$arr_dse_oboz[$na_m4[3]];
		}
		else
		{
			$arr_dse_nam[$na_m4[0]]=$na_m4[1];
			$arr_dse_oboz[$na_m4[0]]=$na_m4[2];
		}
	}


// отображение таблицы /////////////////////////////////////////////
	echo "<h2>Добавление новых заданий</h2>";
	echo "<table width='1500px'>";

	$result_1 = dbquery("SELECT  ID, TID, NAME, DSE_NAME FROM okb_db_zak where (EDIT_STATE = '0') and (INSZ = 1) order by ORD");

	while($res_1 = mysql_fetch_array($result_1))
	{
		$id_cur_zak = $res_1['ID'];
		$re_s1 = dbquery("SELECT ID, PID FROM okb_db_zakdet where (ID_zak='".$id_cur_zak."') AND (PID='0') order by ORD");
		$na_m1 = mysql_fetch_array($re_s1);

		echo "<tbody id='tbody_".$id_cur_zak."'>
		<tr class='tr_gray' data-id='".$id_cur_zak."' id='tr_".$id_cur_zak."'>
		<td class='Field' colspan='11'>
			<img onclick='check_cur_zak(this.parentNode.parentNode.parentNode.id.substr(6), this.parentNode);' src='uses/collapse.png' class='img' >
			<img onclick='expand_cur_zak(this.parentNode.parentNode.parentNode.id.substr(6), this.parentNode);' src='uses/expand.png' class='h_img' >
			<b>".$zak_tip[$res_1['TID']]."&nbsp;&nbsp;".$res_1['NAME']."</b>&nbsp;&nbsp;&nbsp;&nbsp;".$res_1['DSE_NAME']."</td>
		</tr>";
		check_all_tree_dse($id_cur_zak, $na_m1['ID'], $na_m1['PID'], 1);
		$ids_zaks .= $id_cur_zak.'|';
		$ids_dse_cur_zak_expl = explode('|', $ids_dse_cur_zak);
		$names_dse_cur_zak_expl = explode('|', $names_dse_cur_zak);
		$obozs_dse_cur_zak_expl = explode('|', $obozs_dse_cur_zak);
		$child_dse_cur_zak_expl = explode('|', $child_dse_cur_zak);
		foreach($ids_dse_cur_zak_expl as $k_ar_13 => $v_ar_13)
		{
				if ($arr_tbl_13[$v_ar_13])
					{
						$ids_dses .= $v_ar_13.'|';
						$names_dses .= $names_dse_cur_zak_expl[$k_ar_13].'|';
						$obozs_dses .= $obozs_dse_cur_zak_expl[$k_ar_13].'|';
						$child_dses .= $child_dse_cur_zak_expl[$k_ar_13].'|';
						$count_id_d += 1;
					}
		}
		
		$ids_dses .= '=';
		$names_dses .= '=--=';
		$obozs_dses .= '=--=';
		$child_dses .= '=--=';
		$tips_zaks .= $zak_tip[$res_1['TID']].'|';
		$names_zaks .= $res_1['NAME'].'|';
		$dse_names_zaks .= $res_1['DSE_NAME'].'|';
		$ids_dse_cur_zak = '';
		$names_dse_cur_zak = '';
		$obozs_dse_cur_zak = '';
		$child_dse_cur_zak = '';
		echo '</tbody>';
	}
	echo "<tbody><tr height='200px'><td></td></tr></tbody>";
	echo "</table>";
	echo "<div style='position:fixed; left:1160px; top:100%;'><select id='sel_nav_ids_zaks' multiple size='10'></select></div>";
// меню управления /////////////////////////////////////////////
// 14.02.2018 shindax
// <input onkeyup='if(this.value.length>2){ find_text_inp(this.value)};' width='100%'>
// <input id='find_input' onkeydown='if( event.keyCode == 13 ){ find_text_inp(this.value); $( this ).focus(); };' width='100%'>

	echo "<div style='z-index:210; position:absolute; top:100%;'><div id='div_nijniy_menu'><table width='1150px' id='tbl_nijniy_menu' class='rdtbl tbl'><tbody><tr class='first'>
	<td width='200'><input type='button' value='Показать сменные задания' onclick='check_cur_hav(this);'></td>
	<td width='300'><b style='color:red;'>Навигация текущая:</b><br><b name='' id='nav_tekysh_1'></b>&nbsp;&nbsp;|&nbsp;<b name='' id='nav_tekysh_2'></b>&nbsp;смена&nbsp;&nbsp;|&nbsp;<b name='' id='nav_tekysh_3'></b><b name='' style='display:none;' id='nav_tekysh_4'></b><br>
	<select id='park_sel_cur_res'><option value='0' selected>Список оборудований у ресурса</option></select></td>
	<td width='450'><b style='color:red;'>Навигация новая:</b><input type='button' value='Применить' onclick='ch_new_nav();'><br>Дата <input type='date' id='navig_dat' min='1970-01-01' max='2099-01-01'> Смена <select id='navig_smen' value='0'><option value='0'>- - -</option><option value='1'>1 смена</option><option value='2'>2 смена</option><option value='3'>3 смена</option></select>
	Ресурс <input id='val_res_new_nav' width='135px' type='button' onclick='if (this.value !== \"Ждите...\") { check_res_sz_cur();};' value='- - -'></td>
	<td width='150'><input onkeydown='if( event.keyCode == 13 ) event.preventDefault()' onkeyup='if(this.value.length>2){ find_text_inp(this.value)};' width='100%'></td>
	</tr></tbody></table>
	</div></div>
	<div style='z-index:213; position:absolute; top:100%; left:880px;'>
	<div id='div_res_div'>
	<select onchange='document.getElementById(\"div_res_div\").style.display=\"none\"; document.getElementById(\"val_res_new_nav\").value = this.options[this.selectedIndex].text;' id='sel_res_div' multiple size='15'>
	</select></div></div>";

// javascripts после отображения таблицы ///////////////////////

	echo "<script language='javascript'>

	var ids_resurses = '".$ids_resurses."';
	var ids_opers_cur_r = '".$ids_opers_cur_r."';
	var arr_ids_resrs = ids_resurses.split('|');
	var arr_oprs_c_r = ids_opers_cur_r.split('=--=');
	var arr_ids_resrs_2 = [];
	var arr_oprs_c_r_2 = [];

	for (var i_rsr=0; i_rsr<(arr_ids_resrs.length-1); i_rsr++){
		arr_ids_resrs_2[arr_ids_resrs[i_rsr]]=arr_ids_resrs[i_rsr];
		arr_oprs_c_r_2[arr_ids_resrs[i_rsr]]=arr_oprs_c_r[i_rsr];
	}

	var ids_zak = '".$ids_zaks."';
	var ids_dse = '".$ids_dses."';
	var names_dse = '".$names_dses."';
	var obozs_dse = '".$obozs_dses."';
	var child_dse = '".$child_dses."';
	var tip_zak = '".$tips_zaks."';
	var nam_zak = '".$names_zaks."';
	var dsenam_zak = '".$dse_names_zaks."';

	var jv_arr_full_tbl_1 = '".$arr_full_tbl_1."';
	var jv_arr_full_tbl_2 = '".$arr_full_tbl_2."';
	var jv_arr_full_tbl_3 = '".$arr_full_tbl_3."';
	var jv_arr_full_tbl_4 = '".$arr_full_tbl_4."';
	var jv_arr_full_tbl_5 = '".$arr_full_tbl_5."';
	var jv_arr_full_tbl_5_1 = '".$arr_full_tbl_5_1."';
	var jv_arr_full_tbl_6 = '".$arr_full_tbl_6."';
	var jv_arr_full_tbl_7 = '".$arr_full_tbl_7."';
	var jv_arr_full_tbl_8 = '".$arr_full_tbl_8."';
	var jv_arr_full_tbl_9 = '".$arr_full_tbl_9."';
	var jv_arr_full_tbl_10 = '".$arr_full_tbl_10."';
	var jv_arr_full_tbl_11 = '".$arr_full_tbl_11."';
	var jv_arr_full_tbl_12 = '".$arr_full_tbl_12."';
	var jv_arr_full_tbl_13 = '".$arr_full_tbl_13."';
	var jv_arr_full_tbl_14 = '".$arr_full_tbl_14."';
	var jv_arr_full_tbl_15 = '".$arr_full_tbl_15."';
	var jv_arr_full_tbl_16 = '".$arr_full_tbl_16."';
	var jv_arr_full_tbl_17 = '".$arr_full_tbl_17."';
	var jv_arr_full_tbl_18 = '".$arr_full_tbl_18."';
	var jv_arr_full_tbl_19 = '".$arr_full_tbl_19."';	

	var jv_arr_full_tbl_1_spl = jv_arr_full_tbl_1.split('=--=');
	var jv_arr_full_tbl_2_spl = jv_arr_full_tbl_2.split('=--=');
	var jv_arr_full_tbl_3_spl = jv_arr_full_tbl_3.split('=--=');
	var jv_arr_full_tbl_4_spl = jv_arr_full_tbl_4.split('=--=');
	var jv_arr_full_tbl_5_spl = jv_arr_full_tbl_5.split('=--=');
	var jv_arr_full_tbl_5_1_spl = jv_arr_full_tbl_5_1.split('=--=');
	var jv_arr_full_tbl_6_spl = jv_arr_full_tbl_6.split('=--=');
	var jv_arr_full_tbl_7_spl = jv_arr_full_tbl_7.split('=--=');
	var jv_arr_full_tbl_8_spl = jv_arr_full_tbl_8.split('=--=');
	var jv_arr_full_tbl_9_spl = jv_arr_full_tbl_9.split('=--=');
	var jv_arr_full_tbl_10_spl = jv_arr_full_tbl_10.split('=--=');
	var jv_arr_full_tbl_11_spl = jv_arr_full_tbl_11.split('=--=');
	var jv_arr_full_tbl_12_spl = jv_arr_full_tbl_12.split('=--=');
	var jv_arr_full_tbl_13_spl = jv_arr_full_tbl_13.split('=--=');
	var jv_arr_full_tbl_14_spl = jv_arr_full_tbl_14.split('=--=');
	var jv_arr_full_tbl_15_spl = jv_arr_full_tbl_15.split('=--=');
	var jv_arr_full_tbl_16_spl = jv_arr_full_tbl_16.split('=--=');
	var jv_arr_full_tbl_17_spl = jv_arr_full_tbl_17.split('=--=');
	var jv_arr_full_tbl_18_spl = jv_arr_full_tbl_18.split('=--=');	
	var jv_arr_full_tbl_19_spl = jv_arr_full_tbl_19.split('=--=');	

	var jv2_arr_full_tbl_1_spl = [];
	var jv2_arr_full_tbl_2_spl = [];
	var jv2_arr_full_tbl_3_spl = [];
	var jv2_arr_full_tbl_4_spl = [];
	var jv2_arr_full_tbl_5_spl = [];
	var jv2_arr_full_tbl_5_1_spl = [];
	var jv2_arr_full_tbl_6_spl = [];
	var jv2_arr_full_tbl_7_spl = [];
	var jv2_arr_full_tbl_8_spl = [];
	var jv2_arr_full_tbl_9_spl = [];
	var jv2_arr_full_tbl_10_spl = [];
	var jv2_arr_full_tbl_11_spl = [];
	var jv2_arr_full_tbl_12_spl = [];
	var jv2_arr_full_tbl_13_spl = [];
	var jv2_arr_full_tbl_14_spl = [];
	var jv2_arr_full_tbl_15_spl = [];
	var jv2_arr_full_tbl_16_spl = [];
	var jv2_arr_full_tbl_17_spl = [];
	var jv2_arr_full_tbl_18_spl = [];	
	var jv2_arr_full_tbl_19_spl = [];		

	for (var ar_f_2=0; ar_f_2<(jv_arr_full_tbl_13_spl.length-1); ar_f_2++)
	{
		jv2_arr_full_tbl_1_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_1_spl[ar_f_2];
		jv2_arr_full_tbl_2_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_2_spl[ar_f_2];
		jv2_arr_full_tbl_3_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_3_spl[ar_f_2];
		jv2_arr_full_tbl_4_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_4_spl[ar_f_2];
		jv2_arr_full_tbl_5_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_5_spl[ar_f_2];
		jv2_arr_full_tbl_5_1_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_5_1_spl[ar_f_2];
		jv2_arr_full_tbl_6_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_6_spl[ar_f_2];
		jv2_arr_full_tbl_7_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_7_spl[ar_f_2];
		jv2_arr_full_tbl_8_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_8_spl[ar_f_2];
		jv2_arr_full_tbl_9_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_9_spl[ar_f_2];
		jv2_arr_full_tbl_10_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_10_spl[ar_f_2];
		jv2_arr_full_tbl_11_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_11_spl[ar_f_2];
		jv2_arr_full_tbl_12_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_12_spl[ar_f_2];
		jv2_arr_full_tbl_14_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_14_spl[ar_f_2];
		jv2_arr_full_tbl_15_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_15_spl[ar_f_2];
		jv2_arr_full_tbl_16_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_16_spl[ar_f_2];
		jv2_arr_full_tbl_17_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_17_spl[ar_f_2];
		jv2_arr_full_tbl_18_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_18_spl[ar_f_2];
		jv2_arr_full_tbl_19_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_19_spl[ar_f_2];	
	}

	var arr1_ids_zak = ids_zak.split('|');
	var arr1_ids_dse = ids_dse.split('=');
	var arr1_names_dse = names_dse.split('=--=');
	var arr1_obozs_dse = obozs_dse.split('=--=');
	var arr1_child_dse = child_dse.split('=--=');
	var arr1_tip_zak = tip_zak.split('|');
	var arr1_nam_zak = nam_zak.split('|');
	var arr1_dsenam_zak = dsenam_zak.split('|');
	var arr2_ids_zak = [];
	var arr2_ids_dse = [];
	var arr2_ids_dse_ch = [];
	var arr2_names_dse = [];
	var arr2_obozs_dse = [];
	var arr2_child_dse = [];
	var arr2_tip_zak = [];
	var arr2_nam_zak = [];
	var arr2_dsenam_zak = [];

	for (var ar_f_1=0; ar_f_1<(arr1_ids_zak.length-1); ar_f_1++)
	{
		arr2_ids_dse[arr1_ids_zak[ar_f_1]] = arr1_ids_dse[ar_f_1];
		arr2_ids_zak[arr1_ids_zak[ar_f_1]] = arr1_ids_zak[ar_f_1];
		arr2_names_dse[arr1_ids_zak[ar_f_1]] = arr1_names_dse[ar_f_1];
		arr2_obozs_dse[arr1_ids_zak[ar_f_1]] = arr1_obozs_dse[ar_f_1];
		arr2_child_dse[arr1_ids_zak[ar_f_1]] = arr1_child_dse[ar_f_1];
		arr2_tip_zak[arr1_ids_zak[ar_f_1]] = arr1_tip_zak[ar_f_1];
		arr2_nam_zak[arr1_ids_zak[ar_f_1]] = arr1_nam_zak[ar_f_1];
		arr2_dsenam_zak[arr1_ids_zak[ar_f_1]] = arr1_dsenam_zak[ar_f_1];
		var arr1_ids_dse_ch = arr1_ids_dse[ar_f_1].split('|');
		for (var ar_i_d_c=0;ar_i_d_c<(arr1_ids_dse_ch.length-1);ar_i_d_c++)
			arr2_ids_dse_ch[arr1_ids_dse_ch[ar_i_d_c]]=arr1_ids_zak[ar_f_1];
	}

	var jv3_arr_full_tbl_17_spl = '';
	var jv3_arr_full_tbl_18_spl = '';
	var jv3_arr_full_tbl_19_spl = '';	
  
	</script>";

		echo "<div id='delete_row_dialog' class='hidden' data-id='0' title='Удаление записи'>
				<div>
					<p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>Данная запись будет удалена. Вы уверены?</p>				
			    </div>
		</div>";


}else{
	echo "Access denied.";
}

	$basket_res = dbquery("SELECT * FROM okb_db_warehouse_dse_basket WHERE user_id = $user_id");

	if ( mysql_fetch_row( $basket_res ) )
		echo "<script>let items_in_basket = 1 </script>";
			else
				echo "<script>let items_in_basket = 0 </script>";

	echo "<div id='basket-dialog' title='Запрос ДСЕ на выдачу'></div>";
	echo "<div id='move-to-warehouse-dialog' title='Отправить ДСЕ на склад'>
			<table class='move-to-warehouse-dialog-table'>
			<col width='25%'>
			<col width='30%'>
			<col width='35%'>
			<col width='10%'>
			</table>
		  </div>";	
	echo "<div id='warehouse_dialog' class='hidden' title='Заявка на выдачу деталей со склада'></div>";

?>
<script>

window.onload = document.getElementById('sel_nav_ids_zaks').setAttribute('style','position:absolute; top:-194px;border:4px solid #000; z-index:9999999');document.getElementById('div_nijniy_menu').setAttribute('style','position:absolute; top:-89px;');document.getElementById('div_res_div').setAttribute('style','border:3px solid #000; display:none; position:absolute; top:-300px;');
</script>
