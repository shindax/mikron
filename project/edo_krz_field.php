<?php
// $render_row - mysql переменная после query_fetch
global $user;
$arr_right_us = explode("|", $user['ID_rightgroups']);

$arr_cur_krz = array();
$arr_krz_ids = explode("|", $render_row['ID_krz']);

$i = 0 ;

foreach($arr_krz_ids as $key_2 => $val_2) 
{
if ( ( $val_2 !== '' ) and ( $val_2 !== '0' ) )
{
    $arr_cur_krz[$val_2*1]=$val_2*1;
    $re_s2 = dbquery("SELECT NAME FROM okb_db_krz where ID='".$val_2."' ");
    $na_m2 = mysql_fetch_array($re_s2);
    $re_s2_2 = dbquery("SELECT NAME, OBOZ, COUNT FROM okb_db_krzdet where ID_krz='".$val_2."' AND PID=0 ");
    $na_m2_2 = mysql_fetch_array($re_s2_2);

// shindax 11.01.2017    
    $del_img = '';
    $detail_count = $na_m2_2['COUNT'];
    if ((in_array("62", $arr_right_us)) or (in_array("1", $arr_right_us))) 
         $del_img = "<img src='uses/delml.png' style='cursor:pointer; float:none;' onclick='delet_cur_krz(".$val_2.", this);'>";

    $row_col = $i % 2 ? 'white' : '#ccc';
    $i ++ ;

    echo "<table id='cur_l_krz_".$val_2."'><tr style='border-bottom: 2px solid #ccc; background-color : $row_col'>";

    
    echo "<td style='width:12%'><a href='index.php?do=show&formid=7&id=".$val_2."' target='_blank'><img src='uses/view.png'></a>&nbsp;<b>".$na_m2['NAME']."</b></td>
          <td><b>".$na_m2_2['NAME']."</b></td>
          <td style='width:20%'><b>".$na_m2_2['OBOZ']."</b></td>
          <td style='width:10%; text-align:right'>$detail_count&nbsp;шт.</td>
          <td style='width:5%; text-align:center'>$del_img</td>";
    
    echo "</tr></table>";

  }
}

if ((in_array("62", $arr_right_us)) or (in_array("1", $arr_right_us))) {
	echo "<br><b onclick='show_hid_add_cur_krz();' style='background:#d5e7ff; cursor:pointer; border:2px solid #000; padding: 4px; border-radius:6px;'>&nbsp;Добавить существующее КРЗ&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b onclick='show_hid_add_new_krz();' style='background:#d5e7ff; cursor:pointer; border:2px solid #000; padding: 4px; border-radius:6px;'>&nbsp;Создать новое КРЗ&nbsp;</b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b id='count_new_krz1' style='display:none;'>&nbsp;Укажите количество новых КРЗ&nbsp;</b>
<b id='count_new_krz2' style='display:none; background:#d5e7ff; cursor:pointer; border:2px solid #000; padding: 4px; border-radius:6px;'>
<input id='count_new_krz3' onchange='add_new_dses(this.value);' style='width:25px;' maxlength=3></b><br><br>";
}
echo "<b style='display:none;' id='find_cur_krz'><input style='width:700px;' id='find_inp_cur_krz' onkeyup='find_list_cur_krz(this.value);'><br><br></b>";
echo "<select id='add_sel_cur_krz' size=12 style='width:700px; display:none;'>";
//$re_s1 = dbquery("SELECT NAME, ID FROM okb_db_krz order by ID desc");
//while ($na_m1 = mysql_fetch_row($re_s1)){
//	if (!$arr_cur_krz[$na_m1[1]]) {
//		$re_s1_2 = dbquery("SELECT NAME, OBOZ FROM okb_db_krzdet where ID_krz='".$na_m1[1]."' AND PID=0 ");
//		$na_m1_2 = mysql_fetch_row($re_s1_2);
//		echo "<option name='nam_sel_cur_krz' id='id_sel_cur_krz_".$na_m1[1]."' name2='0' onclick='sel_cur_krs(this);'>".$na_m1[0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$na_m1_2[0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$na_m1_2[1];
//	}
//}
echo "</select>";
echo "<b style='display:none;' id='brbr1'><br><br></b><b id='add_btn_cur_krz' onclick='add_cur_krz(this);' style='display:none; background:#d5e7ff; cursor:pointer; border:2px solid #000; padding: 4px; border-radius:6px;'>
&nbsp;Подтвердить&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='display:none;' id='brbr2'><br><br></b>";

echo "<table id='new_krz_tblhead' style='display:none;'><tbody><tr class='first'>
	<td colspan='2'>Основные свойства</td>
	</tr><tr>
	<td width='200' style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Дата создания</td>
	<td class='Field' style='padding:2px 5px 8px 5px; border:1px solid black;'>".date("d.m.Y")."</td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Инициатор</td>
	<td class='Field' style='padding:2px 5px 8px 5px; border:1px solid black;'><select id='new_krz_user'>
";
	$query = dbquery("SELECT * FROM okb_users");

	while($row = mysql_fetch_assoc($query)) {
		echo "<option value='". $row['ID'] ."'" . ($user['ID'] == $row['ID'] ? ' selected="selected"' : '') . ">" . $row['FIO'] . '</option>';
	}

echo "
	</select></td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Контрагент</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'>"; 
	echo "<img style='cursor:pointer;' onclick='show_hid_list_agent();' src='uses/link.png'><input name2='' readonly style='width:90%;' id='new_krz_sel_inp' value=''><input style='display:none; width:90%; border:1px solid black;' onkeyup='find_list_agent(this.value);' id='new_krz_sel_inp_find' value=''>";
	echo "<select id='new_krz_sel' size=12 style='display:none;'>";
	$re_s3 = dbquery("SELECT NAME, ID FROM okb_db_clients order by NAME");
	while ($na_m3 = mysql_fetch_array($re_s3)){
		echo "<option name='list_agent_new_krz' onclick='document.getElementById(\"new_krz_sel_inp\").setAttribute(\"name2\", \"".$na_m3['ID']."\"); document.getElementById(\"new_krz_sel_inp\").value=this.innerHTML; document.getElementById(\"new_krz_sel_inp\").style.display=\"block\"; document.getElementById(\"new_krz_sel_inp_find\").style.display=\"none\"; document.getElementById(\"new_krz_sel\").style.display=\"none\";'>".$na_m3['NAME'];
	}
	echo "</select>";
	echo "</td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Поставщик заготовки</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'>";
	echo "<select id='new_krz_val_2'><option>---<option>ОКБ Микрон<option>Заказчик</select>";
	echo "</td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Перспектива серийности</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'><input id='new_krz_val_3'></td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Необходимые сроки поставки</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'><input id='new_krz_val_4'></td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Прилагаемые доп. документы</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'><input id='new_krz_val_5'></td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Стоимость Н/Ч</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'><input onkeypress='return OnlyNum(event, this);' id='new_krz_val_6' value='1000'></td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Стоимость Н/Ч оснастки</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'><input onkeypress='return OnlyNum(event, this);' id='new_krz_val_7' value='1000'></td>
	</tr><tr>
	<td style='background:#c8daf2; padding:2px 5px 8px 5px; border:1px solid black;'>Примечание</td>
	<td class='rwField ntabg' style='padding:2px 5px 8px 5px; border:1px solid black;'><input id='new_krz_val_9'></td>
	</tr></tbody></table>
	
	<table id='new_krz_tbldse' style='display:none; margin-top:20px; margin-bottom:20px;'><tbody><tr class='first'>
	<td>Наименование изделия</td><td>№ чертежа изделия</td><td>кол-во</td>
	</tr></tbody><tbody id='new_krz_tbldse_tbody'></tbody></table>";
echo "<b style='display:none;' id='brbr3'></b><b id='add_btn_new_krz' onclick='add_new_krz(this);' style='display:none; background:#d5e7ff; cursor:pointer; border:2px solid #000; padding: 4px; border-radius:6px;'>
&nbsp;Подтвердить&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='display:none;' id='brbr4'><br><br></b>";

echo "<script type='text/javascript'>
function sel_cur_krs(obj){
	if(obj.innerHTML.substr(0,3)=='(v)'){
		obj.innerHTML = obj.innerHTML.substr(4);
		obj.setAttribute('name2', '0');
	}else{
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		obj.setAttribute('name2', '1');
	}
}
function add_cur_krz(obj){
	
	
	add_ids_krz = '';
	
	if ($('#add_sel_cur_krz').data('range') == '1') {
		$('#add_sel_cur_krz :selected').each(function(i, selected){ 
			  add_ids_krz = add_ids_krz + $(selected).val() + '|'; 
			});
			
		
		
				
			var req = getXmlHttp();
			req.open('GET', 'project/edo_add_cur_krz.php?p1='+add_ids_krz+'&p2=".$render_row["ID"]."');
			req.send(null);
			
			document.getElementById('curloadingpage1').style.display = 'block';
			setTimeout('location.href=\"".$pageurl."\";', 500);
			
			obj.style.display='none';
			document.getElementById('add_sel_cur_krz').style.display='none';
	} else {
		var opt_l = document.getElementsByName('nam_sel_cur_krz').length;
		for(var a_c=0; a_c < opt_l; a_c++){
			if (document.getElementsByName('nam_sel_cur_krz')[a_c].getAttribute('name2')=='1'){
				add_ids_krz = add_ids_krz+document.getElementsByName('nam_sel_cur_krz')[a_c].getAttribute('id').substr(15)+'|';
			}
		}
		
		var req = getXmlHttp();
		req.open('GET', 'project/edo_add_cur_krz.php?p1='+add_ids_krz+'&p2=".$render_row["ID"]."');
		req.send(null);
		
		document.getElementById('curloadingpage1').style.display = 'block';
		setTimeout('location.href=\"".$pageurl."\";', 500);
		
		obj.style.display='none';
		document.getElementById('add_sel_cur_krz').style.display='none';
	}
}
function show_hid_add_new_krz(){
	if (document.getElementById('new_krz_tblhead').style.display=='none'){
		document.getElementById('new_krz_tblhead').style.display='table';
		document.getElementById('new_krz_tbldse').style.display='table';
		document.getElementById('count_new_krz1').style.display='initial';
		document.getElementById('count_new_krz2').style.display='initial';
		document.getElementById('brbr3').style.display='block';
		document.getElementById('brbr4').style.display='block';
		document.getElementById('add_btn_new_krz').style.display='initial';
	}else{
		document.getElementById('new_krz_tblhead').style.display='none';
		document.getElementById('new_krz_tbldse').style.display='none';
		document.getElementById('count_new_krz1').style.display='none';
		document.getElementById('count_new_krz2').style.display='none';
		document.getElementById('brbr3').style.display='none';
		document.getElementById('brbr4').style.display='none';
		document.getElementById('add_btn_new_krz').style.display='none';
	}
}
function add_new_dses(dses_count){
	//alert(document.getElementById('new_krz_tbldse_tbody').rows.length);
	//document.getElementById('new_krz_tbldse_tbody').innerHTML='';
	
	var nam_dse_nw = '';
	var obz_dse_nw = '';
	var cnt_dse_nw = '';
	var spl_nam_dse_nw;
	var spl_obz_dse_nw;
	var spl_cnt_dse_nw;
	var txt_nam_dse_nw;
	var txt_obz_dse_nw;
	var txt_cnt_dse_nw;	
	
	if ((dses_count>0) && (dses_count<26)){
		if (document.getElementById('new_krz_tbldse_tbody').rows.length<dses_count){
			for (var d_f = 0; d_f<document.getElementsByName('dse_name_inp').length; d_f++){
				nam_dse_nw = nam_dse_nw+document.getElementsByName('dse_name_inp')[d_f].value+'|';
				obz_dse_nw = obz_dse_nw+document.getElementsByName('dse_oboz_inp')[d_f].value+'|';
				cnt_dse_nw = cnt_dse_nw+document.getElementsByName('dse_coun_inp')[d_f].value+'|';
			}
			spl_nam_dse_nw = nam_dse_nw.split('|');
			spl_obz_dse_nw = obz_dse_nw.split('|');
			spl_cnt_dse_nw = cnt_dse_nw.split('|');
			document.getElementById('new_krz_tbldse_tbody').innerHTML = '';
			for (var b_a=0; b_a<dses_count; b_a++){
				txt_nam_dse_nw = '';
				txt_obz_dse_nw = '';
				txt_cnt_dse_nw = '';
				if (spl_nam_dse_nw[b_a]) txt_nam_dse_nw = spl_nam_dse_nw[b_a];
				if (spl_obz_dse_nw[b_a]) txt_obz_dse_nw = spl_obz_dse_nw[b_a];
				if (spl_cnt_dse_nw[b_a]) txt_cnt_dse_nw = spl_cnt_dse_nw[b_a];
				document.getElementById('new_krz_tbldse_tbody').innerHTML+=
				'<tr><td class=\"rwField ntabg\" style=\"width:375px; padding:2px 5px 8px 5px; border:1px solid black;\"><input value=\"'+txt_nam_dse_nw+'\" name=\"dse_name_inp\"></td>'+
				'<td class=\"rwField ntabg\" style=\"width:250px; padding:2px 5px 8px 5px; border:1px solid black;\"><input value=\"'+txt_obz_dse_nw+'\" name=\"dse_oboz_inp\"></td>'+
				'<td class=\"rwField ntabg\" style=\"width:125px; padding:2px 5px 8px 5px; border:1px solid black;\"><input value=\"'+txt_cnt_dse_nw+'\" name=\"dse_coun_inp\"></td></tr>';
			}
		}
		if (document.getElementById('new_krz_tbldse_tbody').rows.length>dses_count){
			for (var b_b=document.getElementById('new_krz_tbldse_tbody').rows.length; b_b>dses_count; b_b--){
				document.getElementById('new_krz_tbldse_tbody').rows[b_b-1].remove();
			}
		}
	}
	if (dses_count>25){
		alert('Слишком большое количество');
	}
}
function add_new_krz(obj){
	var dses_nams_vals = '';
	var dses_oboz_vals = '';
	var dses_coun_vals = '';
	for (var c_c=0; c_c<document.getElementsByName('dse_name_inp').length; c_c++){
		dses_nams_vals = dses_nams_vals+document.getElementsByName('dse_name_inp')[c_c].value+'|';
		dses_oboz_vals = dses_oboz_vals+document.getElementsByName('dse_oboz_inp')[c_c].value+'|';
		dses_coun_vals = dses_coun_vals+document.getElementsByName('dse_coun_inp')[c_c].value+'|';
	}
	
	var req = getXmlHttp();
	//req.onreadystatechange = function() {
	//	if (req.readyState == 4) {
	//		if(req.status == 200) {
	//			alert(req.responseText);
	//		}
	//	}
	//}
	req.open('GET', 'project/edo_add_new_krz.php?p0=".date("Ymd")."&p1=' + document.getElementById('new_krz_user').value + '&p1_1='+document.getElementById('new_krz_sel_inp').getAttribute('name2')+'&p2='+document.getElementById('new_krz_val_2').options.selectedIndex+'&p3='+document.getElementById('new_krz_val_3').value+'&p4='+document.getElementById('new_krz_val_4').value+'&p5='+document.getElementById('new_krz_val_5').value+'&p6='+document.getElementById('new_krz_val_6').value+'&p7='+document.getElementById('new_krz_val_7').value+'&p8='+dses_coun_vals+'&p9='+document.getElementById('new_krz_val_9').value+'&p10='+document.getElementById('count_new_krz3').value+'&p11='+dses_nams_vals+'&p12='+dses_oboz_vals+'&p13=".$_GET['id']."');
	req.send(null);

	obj.style.display='none';
	document.getElementById('brbr3').style.display='none';
	document.getElementById('brbr4').style.display='none';
	document.getElementById('count_new_krz1').style.display='none';
	document.getElementById('count_new_krz2').style.display='none';
	document.getElementById('new_krz_tbldse').style.display='none';
	document.getElementById('new_krz_tblhead').style.display='none';
	document.getElementById('new_krz_tbldse_tbody').innerHTML='';
	document.getElementById('new_krz_sel_inp').value='';
	document.getElementById('new_krz_sel_inp').setAttribute('name2', '');
	document.getElementById('new_krz_val_2').value='---';
	document.getElementById('new_krz_val_3').value='';
	document.getElementById('new_krz_val_4').value='';
	document.getElementById('new_krz_val_5').value='';
	document.getElementById('new_krz_val_6').value='';
	document.getElementById('new_krz_val_7').value='';
	document.getElementById('new_krz_val_9').value='';
	document.getElementById('count_new_krz3').value='';
	
	document.getElementById('curloadingpage1').style.display = 'block';
	setTimeout('location.href=\"".$pageurl."\";', 500);
}
function delet_cur_krz(id_krz, obj)
{ 
	console.log( id_krz )
	 
	var req = getXmlHttp();
	req.open('GET', 'project/edo_del_cur_krz.php?p1='+id_krz+'&p2=".$render_row["ID"]."');
	req.send(null);
	obj.style.display = 'none';
	document.getElementById('cur_l_krz_'+id_krz).style.display='none';

}
function show_hid_add_cur_krz(){
	document.getElementById('add_sel_cur_krz').innerHTML='<option>Получение списка существующих крз...';
	
	var req = getXmlHttp();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				document.getElementById('add_sel_cur_krz').innerHTML=req.responseText;
			}else{
				document.getElementById('add_sel_cur_krz').innerHTML='<option>Ошибка получения списка КРЗ, пожалуйста сообщите об этом.';
			}
		}
	}
	req.open('GET', 'project/edo_get_cur_krz.php?p0=".$_GET['id']."');
	req.send(null);

	if (document.getElementById('add_sel_cur_krz').style.display=='block') {
		document.getElementById('brbr1').style.display='none';
		document.getElementById('brbr2').style.display='none';
		document.getElementById('add_sel_cur_krz').style.display='none';
		document.getElementById('add_btn_cur_krz').style.display='none';
		document.getElementById('find_cur_krz').style.display='none';
	}else{
	if (document.getElementById('add_sel_cur_krz').style.display=='none') {
		document.getElementById('brbr1').style.display='block';
		document.getElementById('brbr2').style.display='block';
		document.getElementById('add_sel_cur_krz').style.display='block';
		document.getElementById('add_btn_cur_krz').style.display='initial';
		document.getElementById('find_cur_krz').style.display='block';
		document.getElementById('find_inp_cur_krz').focus();
	}}
}
function find_list_cur_krz(val){
	if (val.indexOf('-') != -1) {
		var arr = val.split('-');
		$('#add_sel_cur_krz').show();
		$('#add_sel_cur_krz').data('range', '1');
		$('#add_sel_cur_krz').attr('multiple', true);
		$('#add_sel_cur_krz').load('/project/edo_krz_field_range.php?start=' + arr[0] + '&end=' + arr[1], function () {
			window.stop();
		});

		
		} else {
		
		if (val.length>2){
			
			
			for (var d_d=0; d_d < document.getElementsByName('nam_sel_cur_krz').length; d_d++){
				if (document.getElementsByName('nam_sel_cur_krz')[d_d].innerHTML.indexOf(val)==-1){
					document.getElementsByName('nam_sel_cur_krz')[d_d].style.display='none';
				}else{
					document.getElementsByName('nam_sel_cur_krz')[d_d].style.display='block';
				}
			}
		}
		
		
		$('#add_sel_cur_krz').data('range', '0');
	}
	
	
		if (val.length<3){
			for (var d_d=0; d_d < document.getElementsByName('nam_sel_cur_krz').length; d_d++){
				document.getElementsByName('nam_sel_cur_krz')[d_d].style.display='block';
			}
		}
}
function find_list_agent(val){
	if (val.length>1){
		for (var d_d=0; d_d < document.getElementsByName('list_agent_new_krz').length; d_d++){
			if (document.getElementsByName('list_agent_new_krz')[d_d].innerHTML.toLowerCase().indexOf(val.toLowerCase())==-1){
				document.getElementsByName('list_agent_new_krz')[d_d].style.display='none';
			}else{
				document.getElementsByName('list_agent_new_krz')[d_d].style.display='block';
			}
		}
	}
	if (val.length<2){
		for (var d_d=0; d_d < document.getElementsByName('list_agent_new_krz').length; d_d++){
			document.getElementsByName('list_agent_new_krz')[d_d].style.display='block';
		}
	}
}
function show_hid_list_agent(){
	if (document.getElementById('new_krz_sel').style.display=='block'){
		document.getElementById('new_krz_sel').style.display='none';		
		document.getElementById('new_krz_sel_inp').style.display='block';		
		document.getElementById('new_krz_sel_inp_find').style.display='none';		
	}else{
		document.getElementById('new_krz_sel').style.display='block';		
		document.getElementById('new_krz_sel_inp').style.display='none';		
		document.getElementById('new_krz_sel_inp_find').style.display='block';
		document.getElementById('new_krz_sel_inp_find').focus();
	}
}
</script>";
?>
<script>
function OnlyNum(e, obj){
	var nal_tochk = obj.value.split(".");
	
	var keynum;
	var keychar;
	var numcheck;
	var return2;
	if(window.event){
		keynum = e.keyCode;
	}else if(e.which) {
		keynum = e.which;
	}
	keychar = String.fromCharCode(keynum);
	if (keynum > 47 && keynum < 58) {
		return2 = true;
		if (nal_tochk.length > 1) {
			if (nal_tochk[1].length>1) return2 = false;
		}
	}else{ 
		return2 = false;
		if (keynum == 46) {
			return2 = true;
			if (nal_tochk.length > 1) return2 = false;
		}
	}
	return return2;
}
</script>