<?php

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
	}

// сама таблица
array_multisort($itr_arr_4, $itr_arr_0, SORT_DESC, $itr_arr_5, $itr_arr_1, $itr_arr_6, $itr_arr_2, $itr_arr_3, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
echo "<table class='rdtbl tbl' width='1100px'><tbody>
<tr style='position:fixed;' class='first'>
<td width='47px'>№</td>
<td width='86px'>Дата<br>выполнения</td>
<td width='86px'>Дата выполнения<br>факт</td>
<td style='min-width:138px;'>Заказ</td>
<td width='217'>содержание задания</td>
<td width='115px'>Автор</td>
<td width='118px'>Исполнитель</td>
<td width='115px'>Контролёр</td>
<td width='80px'>Статус</td>
</tr>
<tr><td colspan='9'  height='400px'></td></tr>";

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
	echo "<tr>
	<td class='Field' width='65px'>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."&nbsp;&nbsp;&nbsp;&nbsp;".$zak_fulname[$keey_1]."</td>
	<td class='Field' width='290'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>
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
		location.href = document.location;
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
?>