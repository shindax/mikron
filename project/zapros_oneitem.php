<?php
$cur_id = $_GET['id'];
$res_1 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$res_1 = mysql_fetch_array($res_1);

//////////////
// загрузка данных выбранного запроса
$res_10 = dbquery("SELECT * FROM okb_db_zapros_all where (ID='".$cur_id."') ");
$res = mysql_fetch_array($res_10);
$zap_inf_1 = $res['ID_users'];
$zap_inf_2 = $res['ID_users2_plan'];
$zap_inf_3 = $res['ID_users2'];
$zap_inf_4 = $res['ID_users3'];
$zap_inf_5 = $res['CDATE'];
$zap_inf_6 = $res['CTIME'];
$zap_inf_7 = $res['TXT'];
$zap_inf_8 = $res['DATE_PLAN'];
$zap_inf_9 = $res['TIME_PLAN'];
$zap_inf_10 = $res['DATE_FACT'];
$zap_inf_11 = $res['TIME_FACT'];
$zap_inf_12 = $res['SOGL'];
$zap_inf_13 = $res['KOMM'];
$zap_inf_14 = $res['STATUS'];
$zap_inf_15 = $res['TIP_ZAPR'];

if ($zap_inf_15 == 0){

////////////////
// задание класса для полей
if ((($zap_inf_14=='На доработку') or ($zap_inf_14=='Отправлен') or ($zap_inf_14=='Просмотрено')) and ($res_1['ID']==$zap_inf_2)) {
	$class = "rwField ntabg";
	$mainn = "<b style='color:red;float:right;'>*&nbsp;&nbsp;</b>";
	if ($zap_inf_12=='2') {
		$mainn_1 = "<b id='main_komm_1' style='display:none; color:red;float:right;'>*&nbsp;&nbsp;</b>";
		$mainn_2 = "<b id='main_komm_2' style='display:none; color:red;float:right;'>*&nbsp;&nbsp;</b>";
		$mainn_3 = "<b id='main_komm' style='display:block; color:red;float:right;'>*&nbsp;&nbsp;</b>";
	}else{
		$mainn_1 = "<b id='main_komm_1' style='display:block; color:red;float:right;'>*&nbsp;&nbsp;</b>";
		$mainn_2 = "<b id='main_komm_2' style='display:block; color:red;float:right;'>*&nbsp;&nbsp;</b>";
		$mainn_3 = "<b id='main_komm' style='display:none; color:red;float:right;'>*&nbsp;&nbsp;</b>";
	}
}else{
	$class = "Field";
	$mainn = "";
}

///////////////
// список исполнителей для руководителя
if ((($zap_inf_14=='Отправлен') or ($zap_inf_14=='Просмотрено')) and ($res_1['ID']==$zap_inf_2)) {
$arr_shtat = array();
$arr_otdel = array();
$arr_otdel_2 = array();
$resu1 = dbquery("SELECT * FROM okb_db_shtat where (   (((ID_otdel='103') OR (ID_otdel='8') OR (ID_otdel='9') OR ID_special = 1 OR ID_special = 97) OR  (ID_resurs='".$zap_inf_2."'))  )");
while ($na1 = mysql_fetch_array($resu1)){
	$arr_otdel[] = $na1['ID_otdel'];
	$resu1_1 = dbquery("SELECT * FROM okb_db_otdel where (PID='".$na1['ID_otdel']."')");
	while ($na1_1 = mysql_fetch_array($resu1_1)){
		$arr_otdel_2[] = $na1_1['ID'];
	}
}
foreach ($arr_otdel as $keey_1 => $vaal_1){
	$resu1_2 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs!='0') and (ID_otdel='".$vaal_1."')) GROUP BY ID_resurs");
	while ($na1_2 = mysql_fetch_array($resu1_2)){
		$arr_shtat[$na1_2['ID_resurs']] = $na1_2['NAME'];
	}
}
foreach ($arr_otdel_2 as $keey_1 => $vaal_1){
	$resu1_2 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs!='0') and (ID_otdel='".$vaal_1."') and (BOSS='1')) GROUP BY ID_resurs");
	while ($na1_2 = mysql_fetch_array($resu1_2)){
		$arr_shtat[$na1_2['ID_resurs']] = $na1_2['NAME'];
	}
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
$list_res = $list_res."<div class='hr'></div><a href='javascript:void(0)' style='text-align:center;' onclick='parent.location=\"index.php?do=show&formid=138&id=".$cur_id."&edit_list=db_zapros_all|".$cur_id."|ID_users2|0\";'>- - - - -</a>";
foreach ($arr_shtat as $keey_1 => $vaal_1){
	$list_res = $list_res."<div class='hr'></div><a href='javascript:void(0)' onclick='parent.location=\"index.php?do=show&formid=138&id=".$cur_id."&edit_list=db_zapros_all|".$cur_id."|ID_users2|".$keey_1."\";'>".$vaal_1."</a>";
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
</script>".$arr_shtat[$zap_inf_3];
}else{
	$resu5_13 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs='".$zap_inf_3."')");
	$na5_13 = mysql_fetch_array($resu5_13);
	$list_res = $na5_13['NAME'];
}

////////////////////
// контролёр
if ((($zap_inf_14=='На доработку') or ($zap_inf_14=='Отправлен') or ($zap_inf_14=='Просмотрено')) and ($res_1['ID']==$zap_inf_2)) {
$list_res_2 = "<a href='javascript:void(0);' onclick='showlist2(); setTimeout(showlistall2, 400);'>
<img src='uses/link.png'></a><span class='ltpopup'>
<div id='itrres_div2' class='ltpopup' style='width: 220px; display: none;'>
<img class='limg' onclick='showlist2();' src='uses/line.png'>
<input style='
	border: 1px solid #fff;
	width: 100%;
	background: #fff 2px 3px URL(style/search.gif) no-repeat;
	margin: 0px;
	padding: 4px 4px 4px 20px;
	color: #444444;
	text-align: left;' 
id='itrres_inp2' type='text' onkeyup='setTimeout(showlistall2, 800);' onblur='setTimeout(showlist2, 800);'>
<div class='lid_res' id='itrreslist_div2'>";

$resu5_2 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs!='0') GROUP BY ID_resurs ORDER BY NAME");
$list_res_2 = $list_res_2."<div class='hr'></div><a style='text-align:center;' href='javascript:void(0)' onclick='parent.location=\"index.php?do=show&formid=138&id=".$cur_id."&edit_list=db_zapros_all|".$cur_id."|ID_users3|0\";'>- - - - -</a>";
while ($na5_2 = mysql_fetch_array($resu5_2)){
	$list_res_2 = $list_res_2."<div class='hr'></div><a href='javascript:void(0)' onclick='parent.location=\"index.php?do=show&formid=138&id=".$cur_id."&edit_list=db_zapros_all|".$cur_id."|ID_users3|".$na5_2['ID_resurs']."\";'>".$na5_2['NAME']."</a>";
}
$list_res_2 = $list_res_2."</div></div></span>

<script type='text/javascript'>
if (document.getElementById('itrreslist_div2').getElementsByTagName('div')) {
	var divcont = document.getElementById('itrreslist_div2').getElementsByTagName('div').length;
		
	if (divcont > 0) {
		for (var divind = 0; divind < divcont; divind++){
			document.getElementById('itrreslist_div2').getElementsByTagName('div')[divind].style.display='none';
			document.getElementById('itrreslist_div2').getElementsByTagName('a')[divind].style.display='none';
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
}
</script>"; 
}else{
	$list_res_2="";
}
$resu5_3 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs='".$zap_inf_4."')");
$na5_3 = mysql_fetch_array($resu5_3);

////////////		Согласование
//
if ((($zap_inf_14=='На доработку') or ($zap_inf_14=='Отправлен') or ($zap_inf_14=='Просмотрено')) and ($res_1['ID']==$zap_inf_2)) {
	if ($res['SOGL']=='0') { $select_1 = "selected"; $select_2 = ""; $select_3 = ""; $select_4 = "";}
	if ($res['SOGL']=='1') { $select_1 = ""; $select_2 = "selected"; $select_3 = ""; $select_4 = "";}
	if ($res['SOGL']=='2') { $select_1 = ""; $select_2 = ""; $select_3 = "selected"; $select_4 = "";}
	if ($res['SOGL']=='3') { $select_1 = ""; $select_2 = ""; $select_3 = ""; $select_4 = "selected";}

	$sogl = "<select id='zapros_sogl' onchange='vote(this , \"db_edit.php?db=db_zapros_all&field=SOGL&id=".$cur_id."&value=\"+this.value); if (this.value==\"2\"){ document.getElementById(\"main_komm\").style.display=\"block\"; document.getElementById(\"main_komm_1\").style.display=\"none\"; document.getElementById(\"main_komm_2\").style.display=\"none\";}else{ document.getElementById(\"main_komm\").style.display=\"none\"; document.getElementById(\"main_komm_1\").style.display=\"block\"; document.getElementById(\"main_komm_2\").style.display=\"block\";};'>
	<option value='0' ".$select_1.">---</option>
	<option value='1' ".$select_2.">Согласовано</option>
	<option value='3' ".$select_4.">Выполнено</option>
	<option value='2' ".$select_3.">Отклонено</option></select>";
}else{
	if ($res['SOGL']=='0') { $sogl = "---";}
	if ($res['SOGL']=='1') { $sogl = "Согласовано";}
	if ($res['SOGL']=='3') { $sogl = "Выполнено";}
	if ($res['SOGL']=='2') { $sogl = "Отклонено";}
}

////////////		Комментарий
//
if ((($zap_inf_14=='На доработку') or ($zap_inf_14=='Отправлен') or ($zap_inf_14=='Просмотрено')) and ($res_1['ID']==$zap_inf_2)) {
	$komm = "<input id='komment' type='text' onchange='vote(this , \"db_edit.php?db=db_zapros_all&field=KOMM&id=".$cur_id."&value=\"+this.value);' value='".$res['KOMM']."'>";
}else{
	$komm = $res['KOMM'];
}

////////////        Задание стиля и внешнего вида
//
echo "<link rel='stylesheet' href='project/dnevnik/index.css' type='text/css'>
<table class='shablon' style='border-collapse: collapse; border: 0px solid black; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>
<tbody>
<tr><td colspan='2' height='30' style='vertical-align: bottom; padding: 0px 0px 5px 145px;'><div class='links'></div></td></tr>
<tr><td width='220'><div class='swin' style='width:200px;'>";
include "project/dnevnik/menu.php"; 
echo "</div></td><td><div class='swin'>";
echo"<h2>Запрос № ".$cur_id."</h2><br>";

////////////        Отображение таблицы
//
$res_2 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$zap_inf_1."') ");
$res_2 = mysql_fetch_array($res_2);
$res_3 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$zap_inf_2."') ");
$res_3 = mysql_fetch_array($res_3);

$bg_td_stat="";
if ($zap_inf_14=='Отправлен') { $cur_status = "Новый"; $bg_td_stat="background-color:rgb(187, 174, 0);";}else{ $cur_status=$res['STATUS'];}
if ($zap_inf_14=='Согласовано') { $bg_td_stat="background-color:#8BBB69;";}
if ($zap_inf_14=='Отклонено') { $bg_td_stat="background-color:#FF7474;";}
if ($zap_inf_14=='Выполнено') { $bg_td_stat="background-color:#66AAFF;";}
if ($zap_inf_14=='На доработку') { $bg_td_stat="background-color:#F7F346;";}
if ($zap_inf_8 < date("Ymd")) { $bg_dat_plan = "background-color:#FF7474;"; }else{ $bg_dat_plan="";}
if (strlen($zap_inf_10)<2) { $fact_date = "";}else{ $fact_date=substr($zap_inf_10, 6, 2).".".substr($zap_inf_10, 4, 2).".".substr($zap_inf_10, 0, 4);}
if ($zap_inf_11=='0') { $fact_time = "";}else{ $fact_time=date("H:i:s", $zap_inf_11);}
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 700px;' border='1'>
<thead>
<tr class='first'>
<td colspan='2'>Содержание запроса</td>
</tr></thead><tbody>
<tr>
<td width='250px' class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Автор</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$res_2['NAME']."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Кому отправлен</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$res_3['NAME']."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Исполнитель".$mainn_1."</td>
<td id='ispolnitel' style='text-align:left; padding-left:10px;' class='".$class."'>".$list_res."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Контролёр".$mainn_2."</td>
<td id='kontroler' class='".$class."' style='text-align:left; padding-left:10px;'>".$list_res_2.$na5_3['NAME']."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Дата формирования</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".substr($zap_inf_5, 6, 2).".".substr($zap_inf_5, 4, 2).".".substr($zap_inf_5, 0, 4)."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Время формирования</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$zap_inf_6."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Содержание</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$zap_inf_7."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Дата исполнения</td>
<td class='Field' style='".$bg_dat_plan."text-align:left; padding-left:10px;'>".substr($zap_inf_8, 6, 2).".".substr($zap_inf_8, 4, 2).".".substr($zap_inf_8, 0, 4)."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Время исполнения</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$zap_inf_9."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Дата исполнения факт.</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$fact_date."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Время исполнения факт.</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$fact_time."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Решение по запросу".$mainn."</td>
<td class='".$class."' style='text-align:left; padding-left:10px;'>".$sogl."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Комментарий".$mainn_3."</td>
<td class='".$class."' style='text-align:left; padding-left:10px;'>".$komm."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Статус</td>
<td class='Field' style='".$bg_td_stat."text-align:left; padding-left:10px;'>".$cur_status."</td>
</tr>
</tbody></table>";

/////////////		Проверка согласован ли запрос или нет
//
if ((($zap_inf_14=='На доработку') or ($zap_inf_14=='Отправлен') or ($zap_inf_14=='Просмотрено')) and ($res_1['ID']==$zap_inf_2)) {
	echo "<br><p><a id='podtverdit' style='cursor:pointer;' onclick='checkval();'><b style='font-size:200%'>Подтвердить решение</b></a></div></tbody></table>";	
}else{
	echo "</div></tbody></table>";	
}

echo "<script type='text/javascript'>
function checkval(e) {
e = window.event;
var obj = e.target || e.srcElement;
if (document.getElementById('podtverdit')){
	if ((document.getElementById('zapros_sogl').value !== '0') && (document.getElementById('kontroler').innerText.length > '2') && (document.getElementById('ispolnitel').innerText.length > '2')){
		if (document.getElementById('zapros_sogl').value == '1') {
			document.location.href = 'index.php?do=show&formid=135&p1='+Math.floor(Math.random() * (100 - 1 + 1)) + 1+'|'+'$cur_id'+'|'+'$zap_inf_3'+'|'+'$zap_inf_4'+'|'+'$zap_inf_8'.length+'|3|'+document.getElementById('zapros_sogl').value;
		}
	}else{
		if (document.getElementById('zapros_sogl').value == '2') {
			if (document.getElementById('komment').value.length > '0'){
				document.location.href = 'index.php?do=show&formid=135&p1='+Math.floor(Math.random() * (100 - 1 + 1)) + 0+'|'+'$cur_id'+'|'+'$zap_inf_3'+'|'+'$zap_inf_4'+'|'+'$zap_inf_8'.length+'|3|'+document.getElementById('zapros_sogl').value;
			}else{
				alert('Если \"Отклонено\", то комментарий не может быть пустым');	
			}
		}
		if (document.getElementById('zapros_sogl').value == '1') {
		if (confirm('\"Исполнитель / Контролёр\" не заполнены, продолжить без оформления ИТР задания?')){
			document.location.href = 'index.php?do=show&formid=135&p1='+Math.floor(Math.random() * (100 - 1 + 1)) + 0+'|'+'$cur_id'+'|'+'$zap_inf_3'+'|'+'$zap_inf_4'+'|'+'$zap_inf_8'.length+'|3|'+document.getElementById('zapros_sogl').value;
		}}
		if (document.getElementById('zapros_sogl').value == '3') {
			document.location.href = 'index.php?do=show&formid=135&p1='+Math.floor(Math.random() * (100 - 1 + 1)) + 0+'|'+'$cur_id'+'|'+'$zap_inf_3'+'|'+'$zap_inf_4'+'|'+'$zap_inf_8'.length+'|3|'+document.getElementById('zapros_sogl').value;
		}
	}
}}

function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}
</script>";

}

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////

if ($zap_inf_15 == 1){
	
////////////        Задание стиля и внешнего вида
//
echo "<link rel='stylesheet' href='project/dnevnik/index.css' type='text/css'>
<table class='shablon' style='border-collapse: collapse; border: 0px solid black; color: #000; width: 100%;' border='1' cellpadding='0' cellspacing='0'>
<tbody>
<tr><td colspan='2' height='30' style='vertical-align: bottom; padding: 0px 0px 5px 145px;'><div class='links'></div></td></tr>
<tr><td width='220'><div class='swin' style='width:200px;'>";
include "project/dnevnik/menu.php"; 
echo "</div></td><td><div class='swin'>";
echo"<h2>Запрос № ".$cur_id." на сдвиг плановой даты в заданиях</h2><br>";

if (($zap_inf_14=='Отправлен') and ($res_1['ID']==$zap_inf_2)) {
	$class = "rwField ntabg";
}else{
	$class = "Field";
}

////////////		Согласование
//
if (($zap_inf_14=='Отправлен') and ($res_1['ID']==$zap_inf_2)) {
	if ($res['SOGL']=='0') { $select_1 = "selected"; $select_2 = ""; $select_3 = "";}
	if ($res['SOGL']=='1') { $select_1 = ""; $select_2 = "selected"; $select_3 = "";}
	if ($res['SOGL']=='2') { $select_1 = ""; $select_2 = ""; $select_3 = "selected";}

	$sogl = "<select id='zapros_sogl2' onchange='vote(this , \"db_edit.php?db=db_zapros_all&field=SOGL&id=".$cur_id."&value=\"+this.value);'>
	<option value='0' ".$select_1.">---</option>
	<option value='1' ".$select_2.">Согласовано</option>
	<option value='2' ".$select_3.">Отклонено</option></select>";
}else{
	if ($res['SOGL']=='0') { $sogl = "---";}
	if ($res['SOGL']=='1') { $sogl = "Согласовано";}
	if ($res['SOGL']=='2') { $sogl = "Отклонено";}
}

////////////		Комментарий
//
$patterns[0] = "/\<br\>/";
$replacements[0] = "\n";

if (($zap_inf_14=='Отправлен') and ($res_1['ID']==$zap_inf_2)) {
	$txt_kom = $res['KOMM'];
	$komm = "<textarea style='resize:none; height:95px;' id='komment2' type='text' onchange='this.value=this.value.replace(/\\n/g, \"<br>\"); vote(this , \"db_edit.php?db=db_zapros_all&field=KOMM&id=".$cur_id."&value=\"+this.value); this.value=this.value.replace(/\<br\>/g, \"\\n\");'>".preg_replace($patterns, $replacements, $res['KOMM'])."</textarea>";
}else{
	$txt_kom = $res['KOMM'];
	$komm = $res['KOMM'];
}

////////////        Выделение заданий
//

$arr_all_itr = explode("Задание №", $zap_inf_7);
$check_itr_txt = "";
$check_itr_lnk = "";
$arr_new_dts = array();

foreach($arr_all_itr as $kke_1 => $vva_1){
	if (strlen($vva_1) > 5){
		$numb_itr = substr($vva_1, 0, 5)*1;
		$s1_pr_m = $vva_1;
		preg_match("/\d\d\.\d\d\.\d\d\d\d/", $s1_pr_m, $s2_pr_m);
		$expl_dat = explode(".", $s2_pr_m[0]);
		$arr_new_dts[$numb_itr] = $expl_dat[2].$expl_dat[1].$expl_dat[0];
		$check_itr_lnk = $check_itr_lnk."<a name='lnk_itr_nam' id='lnk_itr_".$arr_new_dts[$numb_itr]."' href='index.php?do=show&formid=122&id=".$numb_itr."' target='_blank'>>>></a><br>";
		if (($zap_inf_14=='Отправлен') and ($res_1['ID']==$zap_inf_2)) {
			$check_itr_txt = $check_itr_txt."<input name='chk_itr' id='chk_id_itr_".$numb_itr."' style='margin-top:1px; margin-bottom:0px;' type='checkbox'><br>";
		}else{
			$check_itr_txt = $check_itr_txt."<input name='chk_itr' id='chk_id_itr_".$numb_itr."' disabled style='display:none; margin-top:1px; margin-bottom:0px;' type='checkbox'><br>";			
		}
	}
}

////////////        Отображение таблицы
//
$res_2 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$zap_inf_1."') ");
$res_2 = mysql_fetch_array($res_2);
$res_3 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$zap_inf_2."') ");
$res_3 = mysql_fetch_array($res_3);

$bg_td_stat="";
if ($zap_inf_14=='Отправлен') { $cur_status = "Новый"; $bg_td_stat="background-color:rgb(187, 174, 0);";}else{ $cur_status=$res['STATUS'];}
if ($zap_inf_14=='Согласовано') { $bg_td_stat="background-color:#8BBB69;";}
if ($zap_inf_14=='Отклонено') { $bg_td_stat="background-color:#FF7474;";}
if ($zap_inf_14=='Выполнено') { $bg_td_stat="background-color:#66AAFF;";}
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 720px;' border='1'>
<thead>
<tr class='first'>
<td colspan='2'>Содержание запроса</td>
<td>Выд.</td>
<td>Просм.</td>
</tr></thead><tbody>
<tr>
<td width='250px' class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Автор</td>
<td colspan='3' class='Field' style='text-align:left; padding-left:10px;'>".$res_2['NAME']."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Кому отправлен</td>
<td colspan='3' class='Field' style='text-align:left; padding-left:10px;'>".$res_3['NAME']."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Дата формирования</td>
<td colspan='3' class='Field' style='text-align:left; padding-left:10px;'>".substr($zap_inf_5, 6, 2).".".substr($zap_inf_5, 4, 2).".".substr($zap_inf_5, 0, 4)."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Время формирования</td>
<td colspan='3' class='Field' style='text-align:left; padding-left:10px;'>".$zap_inf_6."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Содержание</td>
<td class='Field' style='text-align:left; padding-left:10px;'>".$zap_inf_7."</td>
<td width='40px' class='Field' style='text-align:left; padding-left:10px;'>".$check_itr_txt."</td>
<td width='40px' class='Field' style='text-align:left; padding-left:10px;'>".$check_itr_lnk."</td>
</tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Решение по запросу</td>
<td colspan='3' class='".$class."' style='text-align:left; padding-left:10px;'>".$sogl."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Комментарий</td>
<td colspan='3' class='".$class."' style='text-align:left; padding-left:10px;'>".$komm."</td>
</tr>
<tr>
<td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Статус</td>
<td colspan='3' class='Field' style='".$bg_td_stat."text-align:left; padding-left:10px;'>".$cur_status."</td>
</tr>
</tbody></table>";

/////////////////////
//      проверка согласован ли или нет
if (($zap_inf_14=='Отправлен') and ($res_1['ID']==$zap_inf_2)) {
	echo "<br><p><a id='podtverdit2' style='cursor:pointer;' onclick='checkval2(this);'><b style='font-size:200%'>Подтвердить решение</b></a></div></tbody></table>";	
}else{
	echo "</div></tbody></table>";	
}
}

echo "<script type='text/javascript'>
function checkval2(obj) {
if (document.getElementById('podtverdit2')){
	if (document.getElementById('zapros_sogl2').value !== '0'){
		if (document.getElementById('zapros_sogl2').value == '1') {
			var checked_itrs = document.getElementsByName('chk_itr').length;
			var checked_itrs_alr = '';
			var checked_itrs_zpr = '';
			var checked_itrs_dts = '';
			var last_indof_1 = 0;
			var last_indof_2 = 0;
			var txt_komm = '".$txt_kom."';
			var cur_ind_lim_1 = -1;
			var cur_ind_lim_2 = -4;
			var len_arr_kom_1 = 0;
			var len_arr_kom_2 = 0;
			var lim_neotm = 0;
			var lim_neotm_itrs = '';
			for (var ch_i_f=0; ch_i_f < checked_itrs; ch_i_f++){
						len_arr_kom_1 = document.getElementById('komment2').value.indexOf('\\n', last_indof_1 + 1);
						len_arr_kom_2 = txt_komm.indexOf('\<br\>', last_indof_2 + 1);
						
				if (document.getElementsByName('chk_itr')[ch_i_f].checked == true){
					checked_itrs_alr = checked_itrs_alr + document.getElementsByName('chk_itr')[ch_i_f].id.substr(11) + ',';
					checked_itrs_zpr = checked_itrs_zpr + document.getElementsByName('chk_itr')[ch_i_f].id.substr(11) + '|';
					checked_itrs_dts = checked_itrs_dts + document.getElementsByName('lnk_itr_nam')[ch_i_f].id.substr(8) + '|';
				}else{
					if (ch_i_f == (checked_itrs-1)){
						len_arr_kom_1 = document.getElementById('komment2').value.length-1;
						len_arr_kom_2 = txt_komm.length-4;
					}
					if (document.getElementById('komment2').value.substr((cur_ind_lim_1+1),(len_arr_kom_1-(cur_ind_lim_1+1)))==txt_komm.substr((cur_ind_lim_2+4),(len_arr_kom_2-(cur_ind_lim_2+4)))){
						lim_neotm = 1;
						lim_neotm_itrs = lim_neotm_itrs + document.getElementsByName('chk_itr')[ch_i_f].id.substr(11) + ',';
					}
					//alert (len_arr_kom_1 + '='+cur_ind_lim_1+' --- '+len_arr_kom_2 + '='+cur_ind_lim_2+' = '+document.getElementsByName('chk_itr')[ch_i_f].id.substr(11)+'\\n'+document.getElementById('komment2').value.substr((cur_ind_lim_1+1),(len_arr_kom_1-(cur_ind_lim_1+1)))+'\\n'+txt_komm.substr((cur_ind_lim_2+4),(len_arr_kom_2-(cur_ind_lim_2+4)))+'\\n'+(cur_ind_lim_1+1)+' = '+(len_arr_kom_1-(cur_ind_lim_1+1)));
				}
					cur_ind_lim_1 = len_arr_kom_1;
					cur_ind_lim_2 = len_arr_kom_2;
					last_indof_1 = document.getElementById('komment2').value.indexOf('\\n');
					last_indof_2 = txt_komm.indexOf('\<br\>');
			}
			if (lim_neotm == 1){
				if (confirm('У не отмеченных заданий '+lim_neotm_itrs+'останутся старые комментарии.\\nЖелательно изменить комментарий почему осталась без изменений.\\nИли продолжить с таким же комментарием?')) {
					vote2(\"edit_dp_itr.php?p1=\"+checked_itrs_zpr+\"&p2=\"+checked_itrs_dts+\"&p3=".$cur_id."&p4=1\");
				}
			}
			if (lim_neotm == 0){
				vote2(\"edit_dp_itr.php?p1=\"+checked_itrs_zpr+\"&p2=\"+checked_itrs_dts+\"&p3=".$cur_id."&p4=1\");
			}
		}else{
			vote2(\"edit_dp_itr.php?p3=".$cur_id."&p4=0\");
		}
	}else{
		alert('Согласование это обязательное поле.');
	}
}}
function vote2(url) {
	var req = getXmlHttp();	
	req.open('GET', url, true);
	req.send(null);
	setTimeout (\"location.href = 'index.php?do=show&formid=135';\", 500);
}
</script>";
?>