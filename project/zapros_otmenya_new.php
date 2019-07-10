<?php
$res_1 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$res_1 = mysql_fetch_array($res_1);

$mainn = "<b style='color:red;float:right;'>*&nbsp;&nbsp;</b>";

// проверка - создавать ли новый или загрузить последний
$res = dbquery("SELECT * FROM okb_db_zapros_all where ((STATUS='Не отправлен') and (ID_users='".$res_1['ID']."')) ");
if ($res = mysql_fetch_array($res)) {
	$zap_inf_0 = $res['ID'];
	$zap_inf_2 = $res['ID_users2_plan'];
	$zap_inf_5 = $res['DATE_PLAN'];
	$zap_inf_6 = $res['TIME_PLAN'];
	$zap_inf_7 = $res['TXT'];
	$zap_inf_8 = $res['STATUS'];
}else{	
	dbquery("INSERT INTO okb_db_zapros_all (DATE_PLAN, ID_users, CDATE, CTIME, TIME_PLAN, STATUS, SOGL, TIT_HEAD, ID_itrzadan) VALUES ('0', '".$res_1['ID']."', '".date("Ymd")."', '".date("H:i:s")."', '17:00:00', 'Не отправлен', '0', '0', '0')");
	$res = dbquery("SELECT * FROM okb_db_zapros_all where ((STATUS='Не отправлен') and (ID_users='".$res_1['ID']."')) ");
	$res = mysql_fetch_array($res);
	$zap_inf_0 = $res['ID'];
	$zap_inf_2 = $res['ID_users2_plan'];
	$zap_inf_5 = $res['DATE_PLAN'];
	$zap_inf_6 = $res['TIME_PLAN'];
	$zap_inf_7 = $res['TXT'];
	$zap_inf_8 = $res['STATUS'];
}

// вывод значения по умолчанию для даты
if ($res['DATE_PLAN']==0) { 
	$cal_dat="---";
	$expl_date = explode(".",date("d.m.Y"));
}else{ 
	$cal_dat=IntToDate($res['DATE_PLAN']);
	$expl_date = explode(".",IntToDate($zap_inf_5));
}

// список руководителей кому идёт запрос
$arr_shtat = array();
$resu1 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs!='0') GROUP BY ID_resurs ");
while ($na1 = mysql_fetch_array($resu1)){
	$resu2 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na1['ID_resurs']."')");
	$na2 = mysql_fetch_array($resu2);
	$arr_shtat[$na2['ID']] = $na2['NAME'];
}

$list_res = "<a href='javascript:void(0);' onclick='showlist(); setTimeout(showlistall, 400);'>
<img src='uses/link.png'></a><span class='ltpopup'>
<div id='itrres_div' class='ltpopup' style='width: 220px; display: none;'>
<img class='limg' onclick='showlist();' src='uses/line.png'>
<input style='
	border: 1px solid #fff;
	width: 100%;
	background: #fff 2px 3px URL(style/search.gif) no-repeat;
	margin: 0px;
	padding: 4px 4px 4px 20px;
	color: #444444;
	text-align: left;' 
id='itrres_inp' type='text' onkeyup='setTimeout(showlistall, 800);' onblur='setTimeout(showlist, 800);'>
<div class='lid_res' id='itrreslist_div'>";

asort($arr_shtat);
foreach($arr_shtat as $keey_1 => $vaal_1){
	$list_res = $list_res."<div class='hr'></div><a href='javascript:void(0)' onclick='parent.location=\"index.php?do=show&formid=137&edit_list=db_zapros_all|".$zap_inf_0."|ID_users2_plan|".$keey_1."\";'>".$vaal_1."</a>";
}
$list_res = $list_res."</div></div></span>

<script type='text/javascript'>
if (document.getElementById('itrreslist_div').getElementsByTagName('div')) {
	var divcont = document.getElementById('itrreslist_div').getElementsByTagName('div').length;
		
	if (divcont > 0) {
		for (var divind = 0; divind < divcont; divind++){
			document.getElementById('itrreslist_div').getElementsByTagName('div')[divind].style.display='none';
			document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].style.display='none';
		}
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
}
</script>".$arr_shtat[$zap_inf_2];

////////////        Задание стиля и внешнего вида
//
echo "<link rel='stylesheet' href='project/dnevnik/index.css' type='text/css'>
<table class='shablon' style='border-collapse: collapse; border: 0px solid black; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>
<tbody>
<tr><td colspan='2' height='30' style='vertical-align: bottom; padding: 0px 0px 5px 145px;'><div class='links'></div></td></tr>
<tr><td width='220'><div class='swin' style='width:200px;'>";
include "project/dnevnik/menu.php"; 
echo "</div></td><td><div class='swin'>";
echo"<h2>Новый запрос от меня</h2><br>";

////////////        Отображение страницы
//
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 700px;' border='1'>
<thead>
<tr class='first'>
<td colspan='2'>Содержание запроса № (<b style='font-size:120%;'>".$zap_inf_0."</b>)</td>
</tr></thead><tbody>
<tr>
<td width='250px' class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Кому идёт запрос".$mainn."</td>
<td class='rwField ntabg' style='text-align:left; padding-left:10px;'>".$list_res."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Содержание".$mainn."</td>
<td class='rwField tabg' style='text-align:left; padding-left:10px;'><textarea style='height: 40px; resize: none;' name='db_zapros_all_TXT_edit_".$res['ID']."' onchange='vote(this , \"db_edit.php?db=db_zapros_all&field=TXT&id=".$res['ID']."&value=\"+TXT(this.value));'>".$res['TXT']."</textarea></td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Дата исполнения".$mainn."</td>
<td style='text-align:left; padding-left:10px;' class='rwField ntabg'><input id='db_zapros_all_DATE_PLAN_edit_".$res['ID']."_Input' type='hidden' name='db_zapros_all_DATE_PLAN_edit_".$res['ID']."_Input' value='".IntToDate($res['DATE_PLAN'])."'><span id='db_zapros_all_DATE_PLAN_edit_".$res['ID']."_Span' style='cursor: hand;' onclick='DI_Create(".$expl_date[0].",".$expl_date[1].",".$expl_date[2].",".$expl_date[0].",".$expl_date[1].",".$expl_date[2].",\"db_zapros_all_DATE_PLAN_edit_".$res['ID']."\",\"db_edit.php?db=db_zapros_all&field=DATE_PLAN&id=".$res['ID']."&value=\");'>".$cal_dat."</span></td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Время исполнения</td>
<td class='rwField ntabg' style='text-align:left; padding-left:10px;'><input onchange='vote(this , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+this.value);' type='text' name='db_zapros_all_TIME_PLAN_edit_".$res['ID']."' value='".$res['TIME_PLAN']."'></td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Текущий статус</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$res['STATUS']."</td>
</tr>
</tbody></table>";
echo "<br><p><a style='cursor:pointer;' onclick='checkval();'><b style='font-size:200%'>Подтвердить отправку запроса</b></a></div></tbody></table>";

echo "<script type='text/javascript'>
function checkval(e) {
e = window.event;
var obj = e.target || e.srcElement;
var inp1 = '$zap_inf_2';
var inp2 = document.getElementsByName('db_zapros_all_TXT_edit_'+'$zap_inf_0')[0].value.length;
var inp3 = document.getElementById('db_zapros_all_DATE_PLAN_edit_'+'$zap_inf_0'+'_Span').innerText;
if ((inp1 !== '0') && (inp2 > 0) && (inp3 !== '---')) {
	document.location.href = 'index.php?do=show&formid=136&p1='+'$zap_inf_0'+'|'+inp1+'|'+inp2+'|'+inp3.length+'|0';
}else{
	alert ('Проверьте заполнение полей \"Кому идёт запрос / Содержание / Дата исполнения\"');
}
}

-function listen(){
  var strind = 0;

  prepTabs = function (t){
	document.getElementsByName('db_zapros_all_TIME_PLAN_edit_".$res['ID']."')[0].onkeydown=maskatime;
	document.getElementsByName('db_zapros_all_TIME_PLAN_edit_".$res['ID']."')[0].onclick=maskatimeclick;
  }

  var maskatimeclick = function (e) {
    e = window.event
    var obj = e.target || e.srcElement;
	obj.setSelectionRange(0,0);
	strind = 0;
  }
  var maskatime = function (e) {
    e = window.event
    var obj = e.target || e.srcElement;
	var cursorind = 0;
	var dlina_all = 8;
	var dlina_posle1 = obj.value.substr(strind, dlina_all - strind);
	var dlina_do1 = obj.value.substr(0, strind - 1);
	var dlina_posle = obj.value.substr(strind + 1, (dlina_all - strind) - 1);
	var dlina_do = obj.value.substr(0, strind);
	var dvoetoch1 = obj.value.substr(2, 1);
	var dvoetoch2 = obj.value.substr(5, 1);
	
	if (((e.keyCode > 47) && (e.keyCode < 58)) || ((e.keyCode > 95) && (e.keyCode < 106))) {
		strind = strind + 1;
		if (strind == 1) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 2) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 3) { strind = strind + 1;}
		if (strind == 4) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 5) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 6) { strind = strind + 1;}
		if (strind == 7) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 8) { obj.setSelectionRange(strind - 1,strind);}
		if (strind >= 9) { obj.setSelectionRange(7,8); strind = 8;}
	}
	if (e.keyCode == 37) {
		if (strind == 8) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 7) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 6) { strind = strind - 1;}
		if (strind == 5) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 4) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 3) { strind = strind - 1;}
		if (strind == 2) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 1) { obj.setSelectionRange(0,0);}
		if (strind <= 0) { strind = 1;}
		strind = strind - 1;
	}
	if (e.keyCode == 39) {
		strind = strind + 1;
		if (strind == 1) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 2) { strind = strind + 1;}
		if (strind == 3) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 4) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 5) { strind = strind + 1;}
		if (strind == 6) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 7) { obj.setSelectionRange(strind - 1,strind);}
		if (strind == 8) { obj.setSelectionRange(strind - 1,strind);}
		if (strind >= 9) { obj.setSelectionRange(7,7); strind = 8;}
	}
	if (e.keyCode == 35) {
		strind = 8;
	}
	if (e.keyCode == 36) {
		strind = 0;
	}
	if (e.keyCode == 20) {
	}
	if (e.keyCode == 46) {
		if (strind <= 7) { obj.value = dlina_do + '00' + dlina_posle; obj.setSelectionRange(strind,strind);}
		if (strind >= 8) { obj.value = dlina_do + '0' + dlina_posle; obj.setSelectionRange(7,7); strind = 7;}
	}
	if (e.keyCode == 8) {
		if (strind == 8) { obj.value = dlina_do1 + '00' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 7) { obj.value = dlina_do1 + '00' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 6) { strind = strind - 1;}
		if (strind == 5) { obj.value = dlina_do1 + '0:' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 4) { obj.value = dlina_do1 + '00' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 3) { strind = strind - 1;}
		if (strind == 2) { obj.value = dlina_do1 + '0:' + dlina_posle1; obj.setSelectionRange(strind - 1,strind);}
		if (strind == 1) { obj.value = dlina_do1 + '0' + dlina_posle1; obj.setSelectionRange(0,0);}
		if (strind <= 0) { strind = 1;}
		strind = strind - 1;
	}
	if (((e.keyCode > 64) && (e.keyCode < 91)) || ((e.keyCode > 185) && (e.keyCode < 193)) || ((e.keyCode > 218) && (e.keyCode < 223))) {
		obj.setSelectionRange(8,8);
		setTimeout(clearke, 100);		
	}
	function clearke() {
	  obj.value = dlina_do + dlina_posle1;
	  obj.setSelectionRange(strind,strind)
	}
	setTimeout(clearke2, 250)
	function clearke2() {
		if ((dvoetoch1 !== ':') || (dvoetoch2 !== ':')){
			obj.value = obj.value.substr(0, 2) + ':' + obj.value.substr(3, 2) + ':' + obj.value.substr(6, 2);
			obj.setSelectionRange(strind,strind);
		}
		if (obj.value.length > 8) {
			obj.value = obj.value.substr(0, 8);
			obj.setSelectionRange(strind,strind);
		}
		if ((obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = '2' + obj.value.substr(1, 7); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);}
		if (obj.value.substr(1, 1) == '4') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);};}
		if (obj.value.substr(1, 1) == '5') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);};}
		if (obj.value.substr(1, 1) == '6') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);};}
		if (obj.value.substr(1, 1) == '7') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);};}
		if (obj.value.substr(1, 1) == '8') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);};}
		if (obj.value.substr(1, 1) == '9') { 
		if ((obj.value.substr(0, 1) == '2') || (obj.value.substr(0, 1) == '3') || (obj.value.substr(0, 1) == '4') || (obj.value.substr(0, 1) == '5') || (obj.value.substr(0, 1) == '6') || (obj.value.substr(0, 1) == '7') || (obj.value.substr(0, 1) == '8') || (obj.value.substr(0, 1) == '9')) { obj.value = obj.value.substr(0, 1) + '3' + obj.value.substr(2, 6); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);};}
		if ((obj.value.substr(3, 1) == '6') || (obj.value.substr(3, 1) == '7') || (obj.value.substr(3, 1) == '8') || (obj.value.substr(3, 1) == '9')) { obj.value = obj.value.substr(0, 3) + '5' + obj.value.substr(4, 4); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);}
		if ((obj.value.substr(6, 1) == '6') || (obj.value.substr(6, 1) == '7') || (obj.value.substr(6, 1) == '8') || (obj.value.substr(6, 1) == '9')) { obj.value = obj.value.substr(0, 6) + '5' + obj.value.substr(7, 1); obj.setSelectionRange(strind,strind); vote(obj , \"db_edit.php?db=db_zapros_all&field=TIME_PLAN&id=".$res['ID']."&value=\"+obj.value);}
	}
 }
  
 window.onload = prepTabs
}()
function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>";
?>