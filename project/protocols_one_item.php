<?php

	if (!defined("MAV_ERP")) { die("Access Denied"); }

$cur_id = $_GET['id'];
$res_1 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$cur_id."') ");
$name_1 = mysql_fetch_array($res_1);
$res_11 = dbquery("SELECT * FROM okb_users where (ID='".$name_1['EUSER']."') ");
$name_11 = mysql_fetch_array($res_11);
$right_edit = 0;
$right_edit_arr = explode("|", $user['ID_rightgroups']);
foreach ($right_edit_arr as $kye1 => $vla1){
	if (($vla1 == '1') or ($vla1 == '62')) {
		$right_edit = 1;
	}
}
$arr_zaks = explode("|", $name_1['ID_zaks']);
$arr_txt = explode("|", $name_1['TXT']);
$arr_us2 = explode("|", $name_1['ID_users2']);
$arr_us3 = explode("|", $name_1['ID_users3']);
$arr_dp = explode("|", $name_1['DATA_PLAN']);
$zak_tip = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");

if ((!$_GET['p3']) and ($right_edit==1) and ($name_1['EDIT_STATE']=='0')) {
	$iniciat = "<input onclick='document.getElementById(\"itrres_div\").style.display=\"block\"; document.getElementById(\"itrres_div\").style.left = (this.getBoundingClientRect().left+13)+\"px\";document.getElementById(\"itrres_div\").style.top = (this.getBoundingClientRect().top-77+document.getElementById(\"vpdiv\").scrollTop)+\"px\"; document.getElementById(\"itrres_inp\").focus(); check_cur_img_div3(\"".$cur_id."\");' readonly style='border:1px solid #fff; cursor:pointer; width:14px; background:url(\"uses/link.png\") -4px -1px no-repeat;'><input disabled style='border:1px solid #fff; background:#fff; width:132px;' name='isp_us' value='".$name_1['ID_users']."'>";
}else{
	$iniciat = "<input style='width:146px;border:1px solid #fff;' readonly value='".$name_1['ID_users']."'>";
}

echo "<table style='background:#fff;' width='1366px'><tbody>
<tr>
<td colspan='6' style='text-align:center;vertical-align:middle; padding:5px; font-size:24pt;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$name_1['NAME']."</td>
<td colspan='2' style='text-align:Right;vertical-align:middle; padding:5px; font-size:12pt;'>Ведущий совещания: ".$iniciat."<br>Секретарь: <input style='width:146px;border:1px solid #fff;' readonly value='".$name_11['IO']."'></td>
</tr>
<tr>
<td colspan='4' style='text-align:left;vertical-align:middle; padding:5px; font-size:15pt;'></td>";
if ($_GET['p3']) { echo "<td colspan='3' style='text-align:right;vertical-align:middle; padding:5px; font-size:15pt;'>Дата печати: ".date("d.m.Y")."</td>";}
if (!$_GET['p3']) { echo "<td colspan='3' style='text-align:right;vertical-align:middle; padding:5px; font-size:15pt;'><a onclick='window.open(\"print.php?do=show&formid=150&id=".$cur_id."&p3=1\");' style='cursor:pointer;'>Версия для печати</a></td>";}
if ((!$_GET['p3']) and ($name_1['EDIT_STATE']=='0')) { if ($right_edit == 1) { echo "<td style='text-align:right;vertical-align:middle; padding:5px; font-size:15pt;'><a onclick='
if (confirm(\"Вы уверены что хотите подтвердить?\")){ 
var nam_zaks = \"\";
var nal_all_dan = 0;
	for (var f_p_i = 0; f_p_i < document.getElementsByName(\"dp_itr_pr4\").length; f_p_i++){
		if ((document.getElementsByName(\"txt_zak3\")[f_p_i].value.length > 1) && (document.getElementsByName(\"isp_us2\")[f_p_i].value.length > 1) && (document.getElementsByName(\"dp_itr_pr4\")[f_p_i].value.length !== 10)){
		}else{
			nal_all_dan = 1;
			nam_zaks = nam_zaks + document.getElementsByName(\"zak_nam_it7\")[f_p_i].innerText + \",\";
		}
	}
	if (nal_all_dan == 0) {
		vote2(\"protocols_edit.php?p1=1&p2=".$cur_id."\"); 
		location.href=\"index.php?do=show&formid=150&id=".$cur_id."\";
	}else{
		if(confirm(\"В следующих заказах\\n\\n\"+nam_zaks+\"\\n\\nне отмечены даты или исполнители или комментарий.\\nПродолжить без оформления ИТР заданий в этих заказах?\")){
			vote2(\"protocols_edit.php?p1=1&p2=".$cur_id."\"); 
			location.href=\"index.php?do=show&formid=150&id=".$cur_id."\";
		}
	}
};
' style='cursor:pointer;'>Закончить редактирование</a></td>";}else{ echo "<td></td>";}}
if (($_GET['p3']) and ($name_1['EDIT_STATE']=='0')) { echo "<td></td>";}
if ($name_1['EDIT_STATE']=='1') { echo "<td></td>";}
echo "</tr>
<tr>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' rowspan='2' width='50px'>Вид<br>заказа</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' rowspan='2' width='90px'>№<br>заказа</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' rowspan='2' width='250px'>Наименование заказа</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' rowspan='2' width='250px'>Заказчик</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' colspan='4'>Задание / Комментарий</td>
</tr><tr>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='100px'>Срок<br>исполнения</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='175px'>Исполнитель</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='150px'>Контролёр</td>
<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='300px'>Содержание</td>
</tr>";

$div_nam_id = array();
$div_nam_name = array();

	$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel!='18') AND (ID_otdel!='19') AND (ID_otdel!='21') AND (ID_otdel!='22') AND (BOSS='1') AND (ID_resurs!='0')) ");
	while($na3 = mysql_fetch_array($resu3)){
		$na3_1 = $na3['ID_resurs'];
		$na3_2 = $na3['ID'];
		$na3_3 = $na3['NAME'];
		
		$div_nam_id[] = $na3_1;
		$div_nam_name[] = $na3_3;
	}

array_multisort($div_nam_name, $div_nam_id);

echo "<span class='ltpopup'>
<div id='itrres_div' class='ltpopup' style='min-width: 220px; display: none;'>
<img class='limg' src='uses/line.png'>
<img src='style/search.png' style='width:14px;'><input id='itrres_inp' type='text' class='lid_input' style='width:200px;' onkeyup='setTimeout(showlistall, 400);' onblur='setTimeout(\"showlist()\",400);'>
<div class='lid_res' id='itrreslist_div'>";
foreach($div_nam_id as $key_div_1 => $val_div_1) {
	if ($val_div_1!==$cur_div_id){
		echo "<div class='hr'></div><a name='div_a_list' onclick='' style='cursor:pointer;'>".$div_nam_name[$key_div_1]."</a>";
		$cur_div_id = $val_div_1;
	}	
}
echo "</div>
</div>
</span>";

foreach($arr_zaks as $key_1 => $val_1) {
if ($_GET['p3']){
	$dat_dp = explode("-",$arr_dp[$key_1]);
	if (count($dat_dp)==3){
		$dat_dp_td = $dat_dp[2].".".$dat_dp[1].".".$dat_dp[0];
	}else{
		$dat_dp_td = "";
	}
	$stl_f = " font-size:150%;";
	$td_dp = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px; font-size:150%;' width='100px'>".$dat_dp_td."</td>";
	$td_us2 = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px; font-size:150%;' width='120px'>".$arr_us2[$key_1]."</td>";
	$td_us3 = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px; font-size:150%;' width='100px'>".$arr_us3[$key_1]."</td>";
	$td_txt = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px; font-size:150%;' width='100px'>".$arr_txt[$key_1]."</td>";
}else{
	$stl_f = "";	
if (($right_edit==1) and ($name_1['EDIT_STATE']=='0')) {
	$td_dp = "<td class='rwField ntabg' style='text-align:center;vertical-align:middle; padding:5px;' width='100px'><input name='dp_itr_pr4' style='background:#ddd;' onchange='vote2(\"protocols_dates.php?p1=".$key_1."&p2=".$cur_id."&value=\"+this.value)' type='date' min='1970-01-01' max='2099-01-01' value='".$arr_dp[$key_1]."'></td>";
	$td_us2 = "<td class='rwField ntabg' style='text-align:left;vertical-align:middle; padding:5px;' width='150px'><input onclick='document.getElementById(\"itrres_div\").style.display=\"block\"; document.getElementById(\"itrres_div\").style.left = (this.getBoundingClientRect().left+13)+\"px\";document.getElementById(\"itrres_div\").style.top = (this.getBoundingClientRect().top-77+document.getElementById(\"vpdiv\").scrollTop)+\"px\"; document.getElementById(\"itrres_inp\").focus(); check_cur_img_div(\"".$key_1."\");' readonly style='cursor:pointer; width:14px; background:url(\"uses/link.png\") -4px -1px no-repeat;'><input disabled style='background:#fff; width:132px;' name='isp_us2' value='".$arr_us2[$key_1]."'>
	<img src='uses/collapse.png' width='13px' style='cursor:pointer;' onclick='vote2(\"protocols_add_us2.php?p1=".$key_1."&p2=".$cur_id."&p3=".$val_1."&p4=".$arr_us3[$key_1]."\"); setTimeout(\"location.href=document.location\", 300);'></td>";
	$td_us3 = "<td class='rwField ntabg' style='text-align:left;vertical-align:middle; padding:5px;' width='150px'><input onclick='document.getElementById(\"itrres_div\").style.display=\"block\"; document.getElementById(\"itrres_div\").style.left = (this.getBoundingClientRect().left+13)+\"px\";document.getElementById(\"itrres_div\").style.top = (this.getBoundingClientRect().top-77+document.getElementById(\"vpdiv\").scrollTop)+\"px\"; document.getElementById(\"itrres_inp\").focus(); check_cur_img_div2(\"".$key_1."\");' readonly style='cursor:pointer; width:14px; background:url(\"uses/link.png\") -4px -1px no-repeat;'><input disabled style='background:#fff; width:132px;' name='isp_us3' value='".$arr_us3[$key_1]."'></td>";
	$td_txt = "<td class='rwField ntabg' style='text-align:center;vertical-align:middle; padding:5px;' width='300px'><textarea name='txt_zak3' style='resize:none; height:46px;' onchange='vote2(\"protocols_txt.php?p1=".$key_1."&p2=".$cur_id."&value=\"+this.value)' type='text'>".$arr_txt[$key_1]."</textarea></td>";
}else{
	$dat_dp = explode("-",$arr_dp[$key_1]);
	if (count($dat_dp)==3){
		$dat_dp_td = $dat_dp[2].".".$dat_dp[1].".".$dat_dp[0];
	}else{
		$dat_dp_td = "";
	}
	$td_dp = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='100px'>".$dat_dp_td."</td>";
	$td_us2 = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='100px'>".$arr_us2[$key_1]."</td>";
	$td_us3 = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='100px'>".$arr_us3[$key_1]."</td>";
	$td_txt = "<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;' width='100px'>".$arr_txt[$key_1]."</td>";
}
}
	if ($val_1 !=='0') {
	if ($val_1 !=='') {

		$res_2 = dbquery("SELECT * FROM okb_db_zak where (ID='".$val_1."') ");
		$name_2 = mysql_fetch_array($res_2);
		$res_3 = dbquery("SELECT * FROM okb_db_clients where (ID='".$name_2['ID_clients']."') ");
		$name_3 = mysql_fetch_array($res_3);
		echo "<tr>
		<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;".$stl_f."' width='50px'>".$zak_tip[$name_2['TID']]."</td>
		<td name='zak_nam_it7' class='Field' style='text-align:center;vertical-align:middle; padding:5px;".$stl_f."' width='90px'>".$name_2['NAME']."</td>
		<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;".$stl_f."' width='250px'>".$name_2['DSE_NAME']."</td>
		<td class='Field' style='text-align:center;vertical-align:middle; padding:5px;".$stl_f."' width='250px'>".$name_3['NAME']."</td>";
		echo $td_dp.$td_us2.$td_us3.$td_txt;
		echo "</tr>";
	}}
}

echo "</tbody></table>";
echo "<script type='text/javascript'>
	function showlist(){
		if (document.getElementById('itrres_div').style.display=='block'){
			document.getElementById('itrres_div').style.display='none';		
		}else{
			document.getElementById('itrres_div').style.display='block';
			document.getElementById('itrres_inp').focus();	
		}
	}
	function showlistall(){
		var divcont = document.getElementById('itrreslist_div').getElementsByTagName('div').length;
		if (divcont > 0) {
			for (var divind = 0; divind < divcont; divind++){
				var itrinp = document.getElementById('itrres_inp').value;
				var regex;
				regex = new RegExp (itrinp, 'i');
				var itrinp5 = document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].innerText;
				var itrinp3 = itrinp5.match(regex);
				if (itrinp3 !== null) {
					document.getElementById('itrreslist_div').getElementsByTagName('div')[divind].style.display='block';
					document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].style.display='block';
				}
				if (itrinp3 == null) {
					document.getElementById('itrreslist_div').getElementsByTagName('div')[divind].style.display='none';
					document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].style.display='none';
				}
			}
		}
	}
function check_cur_img_div(cur_key){
	var all_div_c = document.getElementsByName('div_a_list').length;
	for (var f_d_c = 0; f_d_c < all_div_c; f_d_c++){
		document.getElementsByName('div_a_list')[f_d_c].setAttribute('onclick','vote2(\"protocols_users2.php?p1='+cur_key+'&p2=".$cur_id."&value=\"+document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText);document.getElementsByName(\"isp_us2\")['+cur_key+'].value = document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText;');
	}
}
function check_cur_img_div2(cur_key){
	var all_div_c = document.getElementsByName('div_a_list').length;
	for (var f_d_c = 0; f_d_c < all_div_c; f_d_c++){
		document.getElementsByName('div_a_list')[f_d_c].setAttribute('onclick','vote2(\"protocols_users3.php?p1='+cur_key+'&p2=".$cur_id."&value=\"+document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText);document.getElementsByName(\"isp_us3\")['+cur_key+'].value = document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText;');
	}
}
function check_cur_img_div3(cur_pr_id){
	var all_div_c = document.getElementsByName('div_a_list').length;
	for (var f_d_c = 0; f_d_c < all_div_c; f_d_c++){
		document.getElementsByName('div_a_list')[f_d_c].setAttribute('onclick','vote2(\"protocols_users.php?p2=".$cur_id."&value=\"+document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText);document.getElementsByName(\"isp_us\")[0].value = document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText;location.href=\"index.php?do=show&formid=150&id=".$cur_id."\";');
	}
}
function vote2(url) {
	var req = getXmlHttp();
	req.open('GET', url, true);
	req.send(null);
}
</script>";
?>
