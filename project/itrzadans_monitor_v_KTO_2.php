<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
	<TITLE>Монитор у конструкторов - список заданий</TITLE>
	<meta http-equiv='Content-Type' content='text/html; charset=windows-1251'>
	<LINK rel='stylesheet' href='/style/style.css' type='text/css'>
	<LINK rel='stylesheet' href='/style/print.css' type='text/css'>
</HEAD>
<script language="javascript">
<!--


-->
</script><BODY onClick="window.close();" style='background: #fff;'>
<!-- Viewport -->
<script>

document.addEventListener('DOMContentLoaded', function(){ 	
	setTimeout(function(){
		var timer;

		if (document.body.scrollTop == 0) {
			timer = setTimeout(function(){ location.href = document.location }, 3000);
		} else {
			clearTimeout(timer);
		}
	}, 1000);
}, false);
</script>
<?php

define('MAV_ERP', true);
date_default_timezone_set('Asia/Krasnoyarsk');

	function IntToDate($x) {
		$dd = $x*1;
		$dd_Y = floor($dd/10000);
		$dd_M = floor(($dd-($dd_Y*10000))/100);
		$dd_D = $dd-($dd_Y*10000)-($dd_M*100);
		if ($dd_M<10) $dd_M = "0".$dd_M;
		if ($dd_D<10) $dd_D = "0".$dd_D;
		$res = $dd_D.".".$dd_M.".".$dd_Y;
		if ($dd == 0) $res = "";
		return $res;
	}


include '../config.php';
include '../includes/database.php';


	
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

$resurs_id_nam_k = array();
$zak_fulname = array();
$itr_arr_0 = array();
$itr_arr_1 = array();
$itr_arr_2 = array();
$itr_arr_3 = array();
$itr_arr_4 = array();
$itr_arr_5 = array();
$itr_arr_3_1 = array();
$itr_arr_4_1 = array();
$itr_arr_5_1 = array();
$itr_arr_6 = array();
$itr_arr_7 = array();
$itr_arr_8 = array();
$itr_arr_9 = array();
$itr_arr_10 = array();
$tip_zak = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");

// выводим ИД и ФИО всех ресурсов для отображения контролёра
	$res5_2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_resurs!=0) ");
	while($name5_2 = mysql_fetch_array($res5_2)){
		$resurs_id_nam_k[$name5_2['ID_resurs']]=$name5_2['NAME'];
	}

// строим массив заданий по ресурсам
$itr_wher="and (STATUS!='Аннулировано') and (STATUS!='Завершено')";

	$res_5 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID_users='293') ".$itr_wher." ");
	while($name_5 = mysql_fetch_array($res_5)){
		$res3_2 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$name_5['ID_zak']."') ");
		$name3_2 = mysql_fetch_array($res3_2);
		
		$itr_arr_0[]=$name_5['ID'];
		$itr_arr_1[]=$name_5['DATE_PLAN'];
		$itr_arr_2[]=$name_5['TXT'];
		$itr_arr_3[]=$resurs_id_nam_k[$name_5['ID_users']];
		$itr_arr_4[]=$resurs_id_nam_k[$name_5['ID_users2']];
		$itr_arr_5[]=$resurs_id_nam_k[$name_5['ID_users3']];
		$itr_arr_3_1[]=$name_5['ID_users'];
		$itr_arr_4_1[]=$name_5['ID_users2'];
		$itr_arr_5_1[]=$name_5['ID_users3'];
		$itr_arr_6[]=$name_5['STATUS'];
		$zak_fulname[]=$name3_2['DSE_NAME'];
		$itr_arr_7[]=$name3_2['NAME'];
		$itr_arr_8[]=$tip_zak[$name3_2['TID']];
		$itr_arr_9[]=$name_5['TIP_FAIL'];
		$itr_arr_10[]=$name_5['ID_edo'];
		$itr_arr_11[]=$name_5['ID_zapr'];
	}

// сама таблица
array_multisort($itr_arr_4, $itr_arr_0, SORT_DESC, $itr_arr_5, $itr_arr_1, $itr_arr_6, $itr_arr_2, $itr_arr_3, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1, $zak_fulname, $itr_arr_9, $itr_arr_10, $itr_arr_11);
echo "<table class='rdtbl tbl' width='1100px'><tbody>
<tr style='position:fixed;' class='first'>
<td width='35px'>№</td>
<td width='63px'>Дата<br>выполнения</td>
<td width='63px'>Дата выполнения<br>факт</td>
<td width='60px'>Вид<br>документа</td>
<td style='min-width:130px;'>Заказ</td>
<td width='242'>содержание задания</td>
<td width='106px'>Автор</td>
<td width='110px'>Исполнитель</td>
<td width='108px'>Контролёр</td>
<td width='74px'>Статус</td>
</tr>
<tr><td colspan='10'  height='400px'></td></tr>";

foreach($itr_arr_0 as $keey_1 => $vaal_1){
	//if ()
	if($cur_itr_id!==$itr_arr_0[$keey_1]){
	$result5 = dbquery("SELECT MAX(ID) FROM okb_db_itrzadan_statuses where ((ID_edo='".$itr_arr_0[$keey_1]."') and (STATUS='Выполнено')) ");
	$name5 = mysql_fetch_row($result5);
	$total5 = $name5[0];
	$result5 = dbquery("SELECT * FROM okb_db_itrzadan_statuses where (ID='".$total5."') ");
	if ($name5 = mysql_fetch_array($result5)){
		$date_plan = $name5['DATA'][6].$name5['DATA'][7].".".$name5['DATA'][4].$name5['DATA'][5].".".$name5['DATA'][0].$name5['DATA'][1].$name5['DATA'][2].$name5['DATA'][3];
	}else{
		$date_plan = "";
	}

	$itr_coun = $itr_coun + 1;
	if ($name5['DATA']) $date_plan_count = $date_plan_count + 1;
	if ($name5['DATA']>$itr_arr_1[$keey_1]) { $date_prosr = $date_prosr + 1;}
	if ($itr_arr_6[$keey_1]=='Аннулировано') { $stat_an = $stat_an + 1;}
	if ($itr_arr_6[$keey_1]=='Завершено') { $stat_com = $stat_com + 1;}
	
	$asd = "";
	if ($itr_arr_10[$keey_1] !== '0')	{
		$result2 = dbquery("SELECT * FROM okb_db_edo_inout_files where (ID='".$name3."') ");
		$name2_2 = mysql_fetch_array($result2);

		$result3 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$name3."') ");
		$name4_2 = mysql_fetch_array($result3);

		if ($itr_arr_9[$keey_1] == 0) {
			$doc_nam3 = "ВХ | ";
			$doc_nam2 = $name2_2['NAME_IN'];
		}
		if ($itr_arr_9[$keey_1] == 1) {
			$doc_nam3 = "ИСХ | ";
			$doc_nam2 = $name2_2['NAME_IN'];
		}
		if ($itr_arr_9[$keey_1] == 2) {
			$doc_nam3 = "ПР | ";
			$doc_nam2 = $name4_2['NUMBER'];
		}
		$asd = $doc_nam3.$doc_nam2;
	}

	if (($itr_arr_11[$keey_1]!=='0') and ($itr_arr_10[$keey_1] == '0')){
		$asd = "Запрос №".$itr_arr_11[$keey_1];
	}
	
	echo "<tr>
	<td class='Field' width='40px'>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='65px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='65px'>".$date_plan."</td>
	<td class='Field' width='70px'>".$asd."</td>
	<td class='Field' width='135px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."<br>".$zak_fulname[$keey_1]."</td>
	<td class='Field' width='280px'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='120px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='120px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='120px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='75px'>".$itr_arr_6[$keey_1]."</td>
	</tr>";
	}
	$cur_itr_id = $itr_arr_0[$keey_1];
}

echo "<tr><td colspan='9'  height='400px'></td></tr></tbody></table>";

echo "<script type='text/javascript'>
window.onload ='document.body.scrollTop=0';
var set_int = setInterval('scrl_go()', '20');

function scrl_go(){
	var scrtlgo = document.body.scrollTop;
	document.body.scrollTop = document.body.scrollTop+1;
	var scrtlgo2 = document.body.scrollTop;
	if (scrtlgo == scrtlgo2){
		clearInterval(set_int);
		setTimeout('location.href=document.location', '2500');
	}
}

var dd1 = document.getElementsByName('status');
var dd2 = document.getElementsByName('itrdate');
var dd2_1 = document.getElementsByName('factdate');
var now = new Date()
var sss1 = 1, ssum;
ssum = sss1 + now.getMonth();
var fd_pd_d = 0;
for (var ff = 0; ff < dd1.length; ff++){
   if (dd1[ff].innerText == 'Принято к исполнению') {
      dd1[ff].style.backgroundColor = '#F7F346';
   }
   if (dd1[ff].innerText == 'Выполнено') {
      dd1[ff].style.backgroundColor = '#CA9DDC';
   }
   if (dd1[ff].innerText == 'Новое') {
      dd1[ff].style.backgroundColor = '#BBAE00';
   }
   if (dd1[ff].innerText == 'Принято') {
      dd1[ff].style.backgroundColor = '#66AAFF';
   }
   if (dd1[ff].innerText == 'На доработку') {
      dd1[ff].style.backgroundColor = '#8BBB69';
   }
   var ddate = dd2[ff].innerText;
   var dday = ddate.substr(0, 2);
   var dmon = ddate.substr(3, 2);
   var dyer = ddate.substr(6, 4);
   if (now.getFullYear() > dyer) {
      dd2[ff].style.backgroundColor = '#FF7474';
   }
   if (ssum > dmon) {
   if (dyer <= now.getFullYear()) {	   
       dd2[ff].style.backgroundColor = '#FF7474';
   }
   }
   if (now.getDate() > dday) {
   if (dmon <= ssum) {
   if (dyer <= now.getFullYear()) {	   
      dd2[ff].style.backgroundColor = '#FF7474';
	}
	}
	}
   if (dd2_1[ff].innerText.length>1){
   var ddate = dd2[ff].innerText;
   var dday = ddate.substr(0, 2);
   var dmon = ddate.substr(3, 2);
   var dyer = ddate.substr(6, 4);
   var ddate2 = dd2_1[ff].innerText;
   var dday2 = ddate2.substr(0, 2);
   var dmon2 = ddate2.substr(3, 2);
   var dyer2 = ddate2.substr(6, 4);
   if (dyer2 > dyer) {
      dd2_1[ff].style.backgroundColor = '#FF7474';
   }else{
	   fd_pd_d = 1;
   }
   if (dmon2 > dmon) {
   if (dyer <= dyer2) {
       dd2_1[ff].style.backgroundColor = '#FF7474';
	   fd_pd_d = 0;
   }else{
	   fd_pd_d = 1;
   }
   }
   if (dday2 > dday) {
   if (dmon <= dmon2) {
   if (dyer <= dyer2) {
      dd2_1[ff].style.backgroundColor = '#FF7474';
	  fd_pd_d = 0;
   }else{
	   fd_pd_d = 1;
	}
	}
	}
   }
   if (fd_pd_d == 1){
	   dd2[ff].style.backgroundColor = '#FFFFFF';
	   fd_pd_d = 0;
   }
}
</script>";
?>	<script type='text/javascript'>
	document.title = "Монитор у конструкторов - список заданий";
	</script>

	<div id='curloading' style='position:fixed; left:35%; top:40%; display:none;z-index:999'>
	<img src='project/img/loading_2.gif' width='200px'>
	<div style='position:absolute; left:18px; top:85px; width:165px; height:25px; background:#ccc'>
	</div>
	<div style='position:absolute; left:30px; top:90px;'>
	<font color='red'><b>Ждите, идёт обработка</b></font>
	</div>
	</div>
	<div style='
	position:fixed; 
	left:90%; top:15%;'>
	<table><tbody><tr><td style='width:55px;'>
	
		<div onclick='infaa()' onmouseover='getscroll1()' onmouseout='getscroll3()' id='scroll_left' style='display:none; width:41px; height:50px; background:url(project/img/scroll_left.png) no-repeat;'></div>
		
	</td><td>
		
		<div onmouseover='getscroll2()' onmouseout='getscroll3()' id='scroll_right' style='display:none; width:41px; height:50px; background:url(project/img/scroll_right.png) no-repeat;'></div>
		
	</td></tr></tbody></table>
	</div>
	<script type='text/javascript'>
		var	height_document = $(document).width(); 
		var	height_client = $(window).width();
		var scr1 = 7;
		var scr = 0;
		function infaa(){
		}
		function getscroll1(){
			scr = 1;
		}
		function getscroll2(){
			scr = 2;
		}
		function getscroll3(){
			scr = 0;
		}
		function setscr(){
			if (scr == 1){
				document.getElementById('vpdiv').scrollLeft = document.getElementById('vpdiv').scrollLeft - scr1;
				window.scrollBy( -7, 0 );			
			}
			if (scr == 2){
				document.getElementById('vpdiv').scrollLeft = document.getElementById('vpdiv').scrollLeft + scr1;
				window.scrollBy( 7, 0 );			
			}
		}			
		function checkscroll() {
			var	height_client = $(window).width();
			
			if (height_client < height_document) { 
				document.getElementById('scroll_left').style.display = 'block';
				document.getElementById('scroll_right').style.display = 'block';
			}else{
				document.getElementById('scroll_left').style.display = 'none';
				document.getElementById('scroll_right').style.display = 'none';
			}
		}
		setInterval('setscr()', 18);
		setInterval('checkscroll()', 1000);
		
if ('1'!=='28'){
	window.onbeforeunload = function(evt){
		if ('178'=='28') { return 'Данное сообщение вызвано в случае случайного закрытия/ухода со страницы. Если же вы намеренно уходите/закрываете страницу, подтвердите.';}
		if ('178'=='1507') { return 'Данное сообщение вызвано в случае случайного закрытия/ухода со страницы. Если же вы намеренно уходите/закрываете страницу, подтвердите.';}
//		if ('178'=='197') { return 'Данное сообщение вызвано в случае случайного закрытия/ухода со страницы. Если же вы намеренно уходите/закрываете страницу, подтвердите.';}
        document.getElementById('curloading').style.display = 'block';
	}
}
</script>
