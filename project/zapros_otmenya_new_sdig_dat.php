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
echo"<h2>Запрос на сдвиг плановой даты в заданиях</h2><br>";

////////////        Отображение страницы
//
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 1100px;' border='1'>
<thead>
<tr class='first'>
<td width='35px'><input onchange='sel_all(this);' type='checkbox'></td>
<td width='65px'>№</td>
<td width='125px'>Дата исполнения<br>плановая</td>
<td width='125px'>Новая дата</td>
<td width='100px'>Заказ</td>
<td width='285px'>Содержание задания</td>
<td width='150px'>Автор</td>
<td width='200px'>Комментарий к запросу</td>
</tr></thead><tbody>"; 

$arr_res_nam = array();
$res_3 = dbquery("SELECT ID, NAME FROM okb_db_resurs where (TID=0) ");
while ($nam_3 = mysql_fetch_array($res_3)){
	$arr_res_nam[$nam_3['ID']] = $nam_3['NAME'];
}

$zak_tid = array (" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");

$res_2 = dbquery("SELECT * FROM okb_db_itrzadan where (ID_users2='".$nam_1['ID']."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') ");
while ($nam_2 = mysql_fetch_array($res_2)){
	$res_z_2 = dbquery("SELECT * FROM okb_db_zak where (ID='".$nam_2['ID_zak']."') ");
	$nam_z_2 = mysql_fetch_array($res_z_2);
	echo "<tr>
	<td class='Field'><input name='itr_check' id='id_itr_".$nam_2['ID']."' type='checkbox'></td>
	<td class='Field'><a href='index.php?do=show&formid=122&id=".$nam_2['ID']."&p3=0'><img src='uses/view.gif'></a>".$nam_2['ID']."</td>
	<td class='Field'>".IntToDate($nam_2['DATE_PLAN'])."</td>
	<td class='Field'><input name='itr_newdp' style='background:#ddd;' type='date' min='1970-01-01' max='2099-01-01'></td>
	<td class='Field'>".$zak_tid[$nam_z_2['TID']]." | ".$nam_z_2['NAME']."</td>
	<td class='Field'>".$nam_2['TXT']."</td>
	<td name='itr_idus' class='Field'>".$arr_res_nam[$nam_2['ID_users']]."</td>
	<td class='rwField ntabg'><input name='itr_komm' type='text'></td>
	</tr>";
}

echo "</tbody></table>";
echo "<br><p><a style='cursor:pointer;' onclick='if(confirm(\"Подтвердить отправку запроса?\")){ vote2();};'><b style='font-size:200%'>Подтвердить отправку запроса</b></a></div></tbody></table>";

$res_irt = dbquery("SELECT COUNT(ID) FROM okb_db_itrzadan where (ID_users2='".$nam_1['ID']."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') ");
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
var idus_itr = '';
var komms_itrs = '';
var ids_itrs_n = '';
var ids_itrs_k = '';
var nal_otr = 0;
var nal_otr_2 = 0;

	for(var e_a_i = 0; e_a_i < ".$nam_irt[0]."; e_a_i++){
		if (document.getElementsByName('itr_check')[e_a_i].checked == true){
			if (document.getElementsByName('itr_newdp')[e_a_i].value.length > 1){
				if (document.getElementsByName('itr_komm')[e_a_i].value.length > 1){
					ids_itrs = ids_itrs + document.getElementsByName('itr_check')[e_a_i].id.substr(7)+'|';
					newdp_itrs = newdp_itrs + document.getElementsByName('itr_newdp')[e_a_i].value+'|';
					idus_itr = idus_itr + document.getElementsByName('itr_idus')[e_a_i].innerText+'|';
					komms_itrs = komms_itrs + document.getElementsByName('itr_komm')[e_a_i].value+'|';
				}else{
					nal_otr_2 = 1;
					ids_itrs_k = ids_itrs_k + document.getElementsByName('itr_check')[e_a_i].id.substr(7)+',';	
				}	
			}else{
				nal_otr = 1;
				ids_itrs_n = ids_itrs_n + document.getElementsByName('itr_check')[e_a_i].id.substr(7)+',';	
			}
		}
	}
	if ((nal_otr_2 == 0) && (nal_otr == 0)) {
		vote3(\"zapros_sdvig_date.php?p1=\"+ids_itrs+\"&p2=\"+newdp_itrs+\"&p3=\"+idus_itr+\"&p4=\"+komms_itrs+\"&p5=".$nam_1['ID']."\");
	}else{
		if(confirm(\"В следующих отмеченных заданиях \"+ids_itrs_n+ids_itrs_k+\" не проставлены новые даты или комментарии. Продолжить без них?\")){
			vote3(\"zapros_sdvig_date.php?p1=\"+ids_itrs+\"&p2=\"+newdp_itrs+\"&p3=\"+idus_itr+\"&p4=\"+komms_itrs+\"&p5=".$nam_1['ID']."\");
		}
	}
}
function vote3(url) {
	var req = getXmlHttp();	
	req.open('GET', url, true);
	req.send(null);
	window.location.reload();
}
</script>";
?>