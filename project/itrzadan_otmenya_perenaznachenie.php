<?php
$res_1 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$nam_1 = mysql_fetch_array($res_1);

$mainn = "<b style='color:red;float:right;'>*&nbsp;&nbsp;</b>";

////////////        Задание стиля и внешнего вида
//
echo "<link rel='stylesheet' href='project/dnevnik/index.css' type='text/css'>
<table class='shablon' style='border-collapse: collapse; border: 0px solid black; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>
<tbody>
<tr><td colspan='2' height='30' style='vertical-align: bottom; padding: 0px 0px 5px 145px;'><div class='links'></div></td></tr>
<tr><td width='220'><div class='swin' style='width:200px;'>";
include "project/dnevnik/menu.php"; 
echo "</div></td><td><div class='swin'>";
echo"<h2>Переназначение исполнителя</h2><br>Для поиска используйте (F3)<br><br><b>ВНИМАНИЕ!!!</b><br> Помните, что после переназначение заданий, выделенные задания примут статус 'Аннулировано'.<br> А новым исполнителям отправятся задания в статусе 'Новое'.<br><br>";

////////////        Отображение страницы
//
$us_righ = $user['ID_rightgroups'];
$righs = explode("|",$us_righ);
$righs_count = count($righs);
for ($fcount1 = 0; $fcount1 < $righs_count; $fcount1++){
if (($righs[$fcount1]=='63') or ($righs[$fcount1]=='1')){
$edittrigh = 1;
}
}
if ($edittrigh == 1){
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 1300px;' border='1'>
<thead>
<tr class='first'>
<td width='35px'><input onchange='sel_all(this);' type='checkbox'></td>
<td width='65px'>№</td>
<td width='125px'>Дата исполнения<br>плановая</td>
<td width='150px'>Исполнитель</td>
<td width='150px'>Контролёр</td>
<td>Заказ</td>
<td width='285px'>Содержание задания</td>
<td width='200px'>Комментарий к заданию</td>
</tr></thead><tbody>"; 

$div_nam_id = array();
$div_nam_name = array();

	$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel!='18') AND (ID_otdel!='19') AND (ID_otdel!='21') AND (ID_otdel!='22') AND (ID_resurs!='0')) ");
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

$curme = 0;
$resu1 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs='".$nam_1['ID']."') and (BOSS='1') and (NOTTAB='0')) ");
$na1 = mysql_fetch_array($resu1);
$na1_1 = $na1['ID_otdel'];
$na1_2 = $na1['BOSS'];

$resu9 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs='".$nam_1['ID']."') and (BOSS='1') and (NOTTAB='1')) ");
$na9 = mysql_fetch_array($resu9);
$na9_1 = $na9['ID_otdel'];
$na9_2 = $na9['BOSS'];
$na9_3 = $na9['NOTTAB'];

$div_nam_id2 = array();
$div_nam_name2 = array();

echo "<span class='ltpopup'>
<div id='itrres_div2' class='ltpopup' style='min-width: 220px; display: none;'>
<img class='limg' src='uses/line.png'>
<img src='style/search.png' style='width:14px;'><input id='itrres_inp2' type='text' class='lid_input' style='width:200px;' onkeyup='setTimeout(showlistall2, 400);' onblur='setTimeout(\"showlist2()\",400);'>
<div class='lid_res' id='itrreslist_div2'>";
	global $user;

	if ($na1_2 == '1' || $user['ID'] == 169) {
$curme = 1;




	if ($user['ID'] == 43) {
		
		$resu3 = dbquery("SELECT * FROM okb_db_shtat where (((ID_otdel='103') OR (ID_otdel='8') OR (ID_otdel='9')  OR ID_special = 2 OR ID_special = 97) and (ID_resurs!='0')) ");
	} else if ($user['ID'] == 31) {
		
		$resu3 = dbquery("SELECT * FROM okb_db_shtat where (((ID_otdel='104') OR (ID_otdel='".$na1_1."') AND (ID_resurs!='0')) ");
	} else if ($user['ID'] == 169) {
		
		$resu3 = dbquery("SELECT * FROM okb_db_shtat where (((ID_otdel='103') OR (ID_otdel='8') OR (ID_otdel='9')  OR ID_special = 2 OR ID_special = 97) and (ID_resurs!='0')) ");
	}
		$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel='".$na1_1."') and (ID_resurs!='0')) ");
	}
	while($na3 = mysql_fetch_array($resu3)){
		$na3_1 = $na3['ID_resurs'];
		$na3_2 = $na3['ID'];
		
		$resu4 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na3_1."') ");
		$na4 = mysql_fetch_array($resu4);
		$na4_1 = $na4['NAME'];
		$na4_2 = $na4['ID'];
		$na4_3 = $na4['TID'];
		
		if ($na4_3 !== '1'){
			$div_nam_id2[] = $na4_2;
			$div_nam_name2[] = $na4_1;
		}
	}

	$resu6 = dbquery("SELECT * FROM okb_db_otdel where (PID='".$na1_1."') ");
	while($na6 = mysql_fetch_array($resu6)){
		$na6_1 = $na6['ID'];
		
		$resu7 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs!='0') and (ID_otdel='".$na6_1."') and (BOSS='1')) ");
		$na7 = mysql_fetch_array($resu7);
		$na7_1 = $na7['ID_resurs'];
		
		$resu8 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na7_1."') ");
		$na8 = mysql_fetch_array($resu8);
		$na8_1 = $na8['NAME'];
		$na8_2 = $na8['ID'];
		$na8_3 = $na8['TID'];
		
		if ($na8_3 !== '1'){		
		if (($na8_2 !== $render_row['ID_users'])){
			$div_nam_id2[] = $na8_2;
			$div_nam_name2[] = $na8_1;
		}
		}
	}
}

if (($na9_2 == '1') and ($na9_3 == '1')) {
	$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel='".$na9_1."') and (ID_resurs!='0')) ");
	while($na3 = mysql_fetch_array($resu3)){
		$na3_1 = $na3['ID_resurs'];
		$na3_2 = $na3['ID'];
		
		$resu4 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na3_1."') ");
		$na4 = mysql_fetch_array($resu4);
		$na4_1 = $na4['NAME'];
		$na4_2 = $na4['ID'];
		$na4_3 = $na4['TID'];
		
		if ($na4_3 !== '1'){		
		if ($na4_2 !== $render_row['ID_users']){
			$div_nam_id2[] = $na4_2;
			$div_nam_name2[] = $na4_1;
		}else{
			if ($curme == 0) {
				$div_nam_id2[] = $na4_2;
				$div_nam_name2[] = $na4_1;
			}
		}
		}
	}

	$resu6 = dbquery("SELECT * FROM okb_db_otdel where (PID='".$na9_1."') ");
	while($na6 = mysql_fetch_array($resu6)){
		$na6_1 = $na6['ID'];
		
		$resu7 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs!='0') and (ID_otdel='".$na6_1."') and (BOSS='1')) ");
		$na7 = mysql_fetch_array($resu7);
		$na7_1 = $na7['ID_resurs'];

		$resu8 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na7_1."') ");
		$na8 = mysql_fetch_array($resu8);
		$na8_1 = $na8['NAME'];
		$na8_2 = $na8['ID'];
		$na8_3 = $na8['TID'];
		
		if ($na8_3 !== '1'){		
		if ($na8_2 !== $render_row['ID_users']){
			$div_nam_id2[] = $na8_2;
			$div_nam_name2[] = $na8_1;
		}
		}
	}
}
array_multisort($div_nam_name2, $div_nam_id2);

$cur_id = 0;
foreach($div_nam_id2 as $keey_1 => $vaal_1){
	if (strlen($div_nam_name[$keey_1])>1){
		if ($vaal_1!==$cur_id){
			echo "<div class='hr'></div><a name='div_a_list' onclick='' style='cursor:pointer;'>".$div_nam_name2[$keey_1]."</a>";
			$cur_id = $vaal_1;
		}
	}
}

echo "</div>
</div>
</span>";

$zak_tid = array (" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");

$coun_ind = 0;
$res_2 = dbquery("SELECT * FROM okb_db_itrzadan where (ID_users='".$nam_1['ID']."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') order by ID_zak");
while ($nam_2 = mysql_fetch_array($res_2)){
	$res_z_2 = dbquery("SELECT * FROM okb_db_zak where (ID='".$nam_2['ID_zak']."') ");
	$nam_z_2 = mysql_fetch_array($res_z_2);
	echo "<tr name='ids_zak_".$nam_z_2['ID']."'>
	<td class='Field'><input name='itr_check' id='id_itr_".$nam_2['ID']."' type='checkbox'></td>
	<td class='Field'><a href='index.php?do=show&formid=122&id=".$nam_2['ID']."&p3=0'><img src='uses/view.gif'></a>".$nam_2['ID']."</td>
	<td class='Field'><input name='itr_newdp' style='background:#ddd;' type='date' min='1970-01-01' max='2099-01-01' value='".substr($nam_2['DATE_PLAN'], 0, 4)."-".substr($nam_2['DATE_PLAN'], 4, 2)."-".substr($nam_2['DATE_PLAN'], 6, 2)."'></td>
	<td class='rwField ntabg'><input onclick='document.getElementById(\"itrres_div2\").style.display=\"block\"; document.getElementById(\"itrres_div2\").style.left = (this.getBoundingClientRect().left-213)+\"px\";document.getElementById(\"itrres_div2\").style.top = (this.getBoundingClientRect().top-167+document.getElementById(\"vpdiv\").scrollTop)+\"px\"; document.getElementById(\"itrres_inp2\").focus(); check_cur_img_div(\"".$coun_ind."\");' readonly style='cursor:pointer; width:14px; background:url(\"uses/link.png\") -4px -1px no-repeat;'><input disabled style='background:#fff; width:120px;' name='isp_us2' value=''></td>
	<td class='rwField ntabg'><input onclick='document.getElementById(\"itrres_div\").style.display=\"block\"; document.getElementById(\"itrres_div\").style.left = (this.getBoundingClientRect().left-213)+\"px\";document.getElementById(\"itrres_div\").style.top = (this.getBoundingClientRect().top-167+document.getElementById(\"vpdiv\").scrollTop)+\"px\"; document.getElementById(\"itrres_inp\").focus(); check_cur_img_div2(\"".$coun_ind."\");' readonly style='cursor:pointer; width:14px; background:url(\"uses/link.png\") -4px -1px no-repeat;'><input disabled style='background:#fff; width:120px;' name='isp_us3' value='".$nam_1['NAME']."'></td>
	<td class='Field'><b>".$zak_tid[$nam_z_2['TID']]." | ".$nam_z_2['NAME']."</b>&nbsp;&nbsp;&nbsp;".$nam_z_2['DSE_NAME']."</td>
	<td class='rwField ntabg'><textarea name='itr_txt' style='resize:none;'>".$nam_2['TXT']."</textarea></td>
	<td class='rwField ntabg'><input name='itr_komm' type='text'></td>
	</tr>";
	$coun_ind = $coun_ind + 1;
}

echo "</tbody></table>";
echo "<br><p><a style='cursor:pointer;' onclick='if(confirm(\"Подтвердить переназначение?\")){ vote2();};'><b style='font-size:200%'>Подтвердить переназначение.</b></a>";} echo "</div></tbody></table>";

$res_irt = dbquery("SELECT COUNT(ID) FROM okb_db_itrzadan where (ID_users='".$nam_1['ID']."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') ");
$nam_irt = mysql_fetch_row($res_irt);

echo "<script type='text/javascript'>
function sel_all(obj) {
	for(var e_a_i = 0; e_a_i < ".$nam_irt[0]."; e_a_i++){
		document.getElementsByName('itr_check')[e_a_i].checked = obj.checked;
	}
}
function vote2() {
var ids_itrs = '';
var newdp_itrs = '';
var idus2_itr = '';
var idus3_itr = '';
var komms_itrs = '';
var txt_itrs = '';
var ids_itrs_dp = '';
var ids_itrs_us2 = '';
var nal_otr = 0;
var nal_otr_2 = 0;
var ids_zaks = '';

	for(var e_a_i = 0; e_a_i < ".$nam_irt[0]."; e_a_i++){
		if (document.getElementsByName('itr_check')[e_a_i].checked == true){
			if (document.getElementsByName('isp_us2')[e_a_i].value.length > 1){
				if (document.getElementsByName('itr_newdp')[e_a_i].value.length == 10){
					ids_itrs = ids_itrs + document.getElementsByName('itr_check')[e_a_i].id.substr(7)+'|';
					newdp_itrs = newdp_itrs + document.getElementsByName('itr_newdp')[e_a_i].value+'|';
					idus2_itr = idus2_itr + document.getElementsByName('isp_us2')[e_a_i].value+'|';
					idus3_itr = idus3_itr + document.getElementsByName('isp_us3')[e_a_i].value+'|';
					komms_itrs = komms_itrs + document.getElementsByName('itr_komm')[e_a_i].value+'|';
					txt_itrs = txt_itrs + document.getElementsByName('itr_txt')[e_a_i].value+'|';
					ids_zaks = ids_zaks + document.getElementsByName('itr_check')[e_a_i].parentNode.parentNode.getAttribute('name').substr(8)+'|';
				}else{
					nal_otr_2 = 1;
					ids_itrs_dp = ids_itrs_dp + document.getElementsByName('itr_check')[e_a_i].id.substr(7)+',';
				}
			}else{
				nal_otr = 1;
				ids_itrs_us2 = ids_itrs_us2 + document.getElementsByName('itr_check')[e_a_i].id.substr(7)+',';	
			}
		}
	}
	if ((nal_otr_2 == 0) && (nal_otr == 0)) {
		vote3(\"itrzadan_otmenya_perenaznachenie_func.php?p1=\"+ids_itrs+\"&p2=\"+newdp_itrs+\"&p3=\"+txt_itrs+\"&p8=\"+ids_zaks+\"&p5=".$nam_1['ID']."&p6=\"+idus2_itr+\"&p7=\"+idus3_itr+\"&p4=\"+komms_itrs);
	}else{
		if(confirm(\"В следующих отмеченных заданиях \"+ids_itrs_us2+ids_itrs_dp+\" не выбраны исполнитель или дата. Продолжить без них?\")){
			vote3(\"itrzadan_otmenya_perenaznachenie_func.php?p1=\"+ids_itrs+\"&p2=\"+newdp_itrs+\"&p3=\"+txt_itrs+\"&p8=\"+ids_zaks+\"&p5=".$nam_1['ID']."&p6=\"+idus2_itr+\"&p7=\"+idus3_itr+\"&p4=\"+komms_itrs);
		}
	}
}
function vote3(url) {
	var req = getXmlHttp();	
	req.open('GET', url, true);
	req.send(null);
	setTimeout('location.href = \"index.php?do=show&formid=118\";', 500);
}
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
	function showlist2(){
		if (document.getElementById('itrres_div2').style.display=='block'){
			document.getElementById('itrres_div2').style.display='none';		
		}else{
			document.getElementById('itrres_div2').style.display='block';
			document.getElementById('itrres_inp2').focus();	
		}
	}
	function showlistall2(){
		var divcont = document.getElementById('itrreslist_div2').getElementsByTagName('div').length;
		if (divcont > 0) {
			for (var divind = 0; divind < divcont; divind++){
				var itrinp = document.getElementById('itrres_inp2').value;
				var regex;
				regex = new RegExp (itrinp, 'i');
				var itrinp5 = document.getElementById('itrreslist_div2').getElementsByTagName('a')[divind].innerText;
				var itrinp3 = itrinp5.match(regex);
				if (itrinp3 !== null) {
					document.getElementById('itrreslist_div2').getElementsByTagName('div')[divind].style.display='block';
					document.getElementById('itrreslist_div2').getElementsByTagName('a')[divind].style.display='block';
				}
				if (itrinp3 == null) {
					document.getElementById('itrreslist_div2').getElementsByTagName('div')[divind].style.display='none';
					document.getElementById('itrreslist_div2').getElementsByTagName('a')[divind].style.display='none';
				}
			}
		}
	}

function check_cur_img_div(cur_key){
	var all_div_c = document.getElementsByName('div_a_list').length;
	for (var f_d_c = 0; f_d_c < all_div_c; f_d_c++){
		document.getElementsByName('div_a_list')[f_d_c].setAttribute('onclick','document.getElementsByName(\"isp_us2\")['+cur_key+'].value = document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText;');
	}
}
function check_cur_img_div2(cur_key){
	var all_div_c = document.getElementsByName('div_a_list').length;
	for (var f_d_c = 0; f_d_c < all_div_c; f_d_c++){
		document.getElementsByName('div_a_list')[f_d_c].setAttribute('onclick','document.getElementsByName(\"isp_us3\")['+cur_key+'].value = document.getElementsByName(\"div_a_list\")['+f_d_c+'].innerText;');
	}
}
</script>";
?>