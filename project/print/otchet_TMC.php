<?php
$order_b = " ORDER BY ID desc";
$qur_sql = dbquery("SELECT * FROM okb_db_tmc_req where CDATE > ".(date("Ymd")-300).$order_b);
$ids_usrs = "Все";
$date_m_inp2 = date("m")-3;
$date_Y_inp2 = date("Y");
if ($date_m_inp2<1) { $date_m_inp2 = 12-$date_m_inp2; $date_Y_inp2 = $date_Y_inp2-1;}
if ($date_m_inp2<10) $date_m_inp2 = "0".$date_m_inp2;
$date_ful_inp1 = $date_Y_inp2."-".$date_m_inp2."-".date("d");
$date_ful_inp2 = date("Y-m-d");
if ($_GET['p2']) {
	$gtp2 = explode("|", $_GET['p2']);
	$wher_id = " AND ID_users=".$gtp2[2];
	if (($gtp2[2] == 0) or ($gtp2[2] == "0")) $wher_id = "";
	$qur_sql = dbquery("SELECT * FROM okb_db_tmc_req where CDATE > ".$gtp2[0]." AND CDATE <=".$gtp2[1].$wher_id.$order_b);
	$qur_sql8 = dbquery("SELECT ID, IO FROM okb_users where ID=".$gtp2[2]);
	$qur_txt8 = mysql_fetch_array($qur_sql8);
	$ids_usrs = $qur_txt8['IO'];
	if (($qur_txt8['ID']==0) or ($qur_txt8=="0")) $ids_usrs = "Все";
	$date_ful_inp1 = substr($gtp2[0],0,4)."-".substr($gtp2[0],4,2)."-".substr($gtp2[0],6,2);
	$date_ful_inp2 = substr($gtp2[1],0,4)."-".substr($gtp2[1],4,2)."-".substr($gtp2[1],6,2);
}
echo "<table><tbody><tr><td>С какое<br><input id='cdate_1' onblur='check_cdat();' type=date value='".$date_ful_inp1."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
echo "<td>По какое<br><input id='cdate_2' type=date value='".$date_ful_inp2."' onblur='check_cdat();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "Автор заявки:&nbsp;&nbsp;&nbsp;</td><td><input readonly onclick='document.getElementById(\"sel_io_usr\").style.display=\"block\";' value='".$ids_usrs."'>
&nbsp;&nbsp;&nbsp;<select style='display:none;' id='sel_io_usr' size=12><option value=0 onclick='set_new_datas(this.value)'>Все";
$qur_sql6 = dbquery("SELECT okb_db_tmc_req.ID_users FROM okb_db_tmc_req Group by okb_db_tmc_req.ID_users order by (SELECT IO FROM okb_users WHERE okb_users.ID=okb_db_tmc_req.ID_users)");
while($qur_txt6 = mysql_fetch_array($qur_sql6)){
	$qur_sql7 = dbquery("SELECT ID, IO FROM okb_users where ID=".$qur_txt6['ID_users']);
	$qur_txt7 = mysql_fetch_array($qur_sql7);
	echo "<option value=".$qur_txt7['ID']." onclick='set_new_datas(this.value)'>".$qur_txt7['IO'];
}
echo "</select></td></tr></tbody></table>";
$sorlexpl = explode("|", "|Согл.|Откл.");
$napr_arr = explode("|","|Хоз. расходы|Служба ГИ3|Канцелярия|Заказы|Оборудование|Расходники|СИЗ|Рубин|Инструменты|Стройка");
$tid_zak_arr = explode("|","|ОЗ|КР|СП|БЗ|ХЗ|ВЗ");
$status_sgi = explode("|", "|Приост.|Аннул.");
echo "<table class='rdtbl tbl' width='1550px'><tbody>";
	echo "<tr class='first'>
	<td>№</td>
	<td>Автор</td>
	<td>Наименование</td>
	<td>Ед. измерения<br>Количество</td>
	<td>Назначение</td>
	<td>Требуемый<br>срок</td>
	<td>Дата факт.<br>выполнения</td>
	<td>Согл.1</td>
	<td>Согл.2</td>
	<td>Статус</td>
	<td>Комментарий</td>
	</tr>";
while($qur_txt = mysql_fetch_array($qur_sql)){
	$zaknam = "";
	if ($qur_txt['ID_zak']!=="0") {
		$qur_sql2 = dbquery("SELECT NAME, TID FROM okb_db_zak where ID=".$qur_txt["ID_zak"]);
		$qur_txt2 = mysql_fetch_array($qur_sql2);
		$zaknam = $tid_zak_arr[$qur_txt2['TID']]." | ".$qur_txt2['NAME'];
	}
	$qur_sql3 = dbquery("SELECT IO FROM okb_users where ID=".$qur_txt['SOGLUSER1']);
	$qur_txt3 = mysql_fetch_array($qur_sql3);
	$qur_sql4 = dbquery("SELECT IO FROM okb_users where ID=".$qur_txt['SOGLUSER2']);
	$qur_txt4 = mysql_fetch_array($qur_sql4);
	$qur_sql5 = dbquery("SELECT IO FROM okb_users where ID=".$qur_txt['ID_users']);
	$qur_txt5 = mysql_fetch_array($qur_sql5);
	$trebsrok = "";
	if ($qur_txt['DATE']>1) $trebsrok = substr($qur_txt['DATE'],6,2).".".substr($qur_txt['DATE'],4,2).".".substr($qur_txt['DATE'],0,4);
	$sogldate1 = "";
	if ($qur_txt['SOGLDATE1']>1) $sogldate1 = substr($qur_txt['SOGLDATE1'],6,2).".".substr($qur_txt['SOGLDATE1'],4,2).".".substr($qur_txt['SOGLDATE1'],0,4);
	$sogldate2 = "";
	if ($qur_txt['SOGLDATE2']>1) $sogldate2 = substr($qur_txt['SOGLDATE2'],6,2).".".substr($qur_txt['SOGLDATE2'],4,2).".".substr($qur_txt['SOGLDATE2'],0,4);
	$plandat = "";
	$plandat = explode("#",$qur_txt['DATEPLAN']);
	$col_sogl1 = "#000";
	$col_sogl2 = "#000";
	$col_stats = "#000";
	if ($qur_txt['SOGL1']==1) $col_sogl1="#22dd22";
	if ($qur_txt['SOGL1']==2) $col_sogl1="#ff2222";
	if ($qur_txt['SOGL2']==1) $col_sogl2="#22dd22";
	if ($qur_txt['SOGL2']==2) $col_sogl2="#ff2222";
	if ($qur_txt['STATE']==2) $col_stats="#ff2222";
	echo "<tr>
	<td style='width:75px;' class='Field'>".$qur_txt['NAME']."</td>
	<td style='width:125px;' class='Field'>".$qur_txt5['IO']."</td>
	<td style='width:300px;' class='Field'>".$qur_txt['TXT']."</td>
	<td style='width:95px;' class='Field'>".$qur_txt['COUNT']."&nbsp;".$qur_txt['EDIZM']."</td>
	<td style='width:140px;' class='Field'>".$napr_arr[$qur_txt['NAZN']]."&nbsp;&nbsp;&nbsp;".$zaknam."</td>
	<td style='width:75px;' class='Field'>".$trebsrok."</td>
	<td style='width:75px;' class='Field'>".$plandat[count($plandat)-1]."</td>
	<td style='width:125px;' class='Field'>".$qur_txt3["IO"]."<br><b style='color:".$col_sogl1.";'>".$sorlexpl[$qur_txt['SOGL1']]."</b>&nbsp;&nbsp;&nbsp;".$sogldate1."</td>
	<td style='width:125px;' class='Field'>".$qur_txt4["IO"]."<br><b style='color:".$col_sogl2.";'>".$sorlexpl[$qur_txt['SOGL2']]."</b>&nbsp;&nbsp;&nbsp;".$sogldate2."</td>
	<td style='width:50px;' class='Field'><b style='color:".$col_stats.";'>".$status_sgi[$qur_txt['STATE']]."</b></td>
	<td class='Field'>".$qur_txt['MORE']."</td>
	</tr>";
}
echo "</tbody></table>";
	//$qur_sql = dbquery("SELECT * FROM okb_db_tmc_req");
	//$qur_txt = mysql_fetch_array($qur_sql);

?>
<script>
var event_ind = 0, dd1=0, mm1=0, yy1=0, dd2=0, mm2=0, yy2=0;
function check_cdat(){
	event_ind = 0;
	dd1 = document.getElementById('cdate_1').value.split('-')[2];
	mm1 = document.getElementById('cdate_1').value.split('-')[1];
	yy1 = document.getElementById('cdate_1').value.split('-')[0];
	dd2 = document.getElementById('cdate_2').value.split('-')[2];
	mm2 = document.getElementById('cdate_2').value.split('-')[1];
	yy2 = document.getElementById('cdate_2').value.split('-')[0];
	if ((yy2<=yy1) && (mm2<=mm1) && (dd2<dd1)) {
		alert ('Дата "по какое" меньше чем "с какого"!');
	}else{
		event_ind = 1;
	}
}
function set_new_datas(id_us){
	check_cdat();
	if (event_ind == 1) location.href="index.php?do=show&formid=207&p2="+yy1+mm1+dd1+"|"+yy2+mm2+dd2+"|"+id_us;
}
</script>
