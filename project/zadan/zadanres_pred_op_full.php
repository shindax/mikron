<?php

$ID_resurs = $_GET["p2"];
$smena = $_GET["p1"];
if (($smena!=="1") && ($smena!=="2") && ($smena!=="3")) $smena = "1";
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (db_adcheck("db_zadan")) { $a_deist = 1;}
	if (db_check("db_zadan","MEGA_REDACTOR")) $editing = true;

$resurs = dbquery("SELECT NAME FROM ".$db_prefix."db_resurs where (ID = '".$ID_resurs."')");
if (($resurs = mysql_fetch_array($resurs)) && ($_GET["p0"]) && ($_GET["p1"])){

		$inwork = Array();
		$result = dbquery("SELECT ID_operitems FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (SMEN = '".$smena."') and (ID_resurs = '".$ID_resurs."')");
		while($res = mysql_fetch_array($result)) {
			$inwork[] = $res["ID_operitems"];
		}
		$inwork = implode("|", $inwork);
echo "<script language='javascript'>
	function addzadan(obj_id, id_oper, url) {
		if(document.getElementById(obj_id)){
			obj = document.getElementById(obj_id);
			obj.innerHTML = '........';
		}
		if(obj_id){
			obj_id.innerHTML = '........';
		}
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					if(document.getElementById(obj_id)){
						obj = document.getElementById(obj_id);
						obj.innerHTML = '<img src=\"uses/ok.png\" style=\"cursor:pointer;\" onclick=if(confirm(\"Вернуть?\")){delzada(\"'+obj_id+'\",\"'+id_oper+'\",\"'+url+'\");}>';
					}
					if(obj_id){
						obj_id.innerHTML = '<img src=\"uses/ok.png\" style=\"cursor:pointer;\" onclick=if(confirm(\"Вернуть?\")){delzada(\"'+obj_id.id+'\",\"'+id_oper+'\",\"'+url+'\");}>';
					}
				}
			}
		}
		req.open('GET', 'project/zadan/zadanadd.php?'+url, true);
		req.send(null);
	}

	function delzada(obj_id, id_oper, url) {
					if(document.getElementById(obj_id)){
						obj = document.getElementById(obj_id);
						obj.innerHTML = '........';
					}
					if(obj_id){
						obj_id.innerHTML = '........';
					}

		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					if(document.getElementById(obj_id)){
						obj.innerHTML = '<a href=\"javascript:void(0);\" onClick=addzadan(\"'+obj_id+'\",\"'+id_oper+'\",\"'+url+'\");>>>></a>';
					}
					if(obj_id){
						obj_id.innerHTML = '<a href=\"javascript:void(0);\" onClick=addzadan(\"'+obj_id.id+'\",\"'+id_oper+'\",\"'+url+'\");>>>></a>';
					}
				}
			}
		}
		req.open('GET', 'project/zadan/zadandel.php?'+url, true);
		req.send(null);
	}

</script>";

   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //
   // БЛОК ДОБАВЛЕНИЯ ЗАДАНИЙ
   //
   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   ///// ПРОДОЛЖЕНИЕ НЕЗАКОНЧЕННЫХ ОПЕРАЦИЙ

	   // ВЫЧИСЛЕНИЕ ТРЕБУЕМЫХ ОПЕРАЦИЙ ///////////////////////////////////////////////////////////////
		$usedoper = array();

		$result = dbquery("SELECT ID_operitems FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$ID_resurs."')");
		while($res = mysql_fetch_array($result)) {
			$usedoper[$res["ID_operitems"]] = $res["ID_operitems"];
		}

        $pdate2 =  date("Ymd", (mktime(0,0,0,substr($pdate,4,2),substr($pdate,6,2),substr($pdate,0,4)))-604800);

		$collected = array();
		$result = dbquery("SELECT okb_db_operitems.STATE, okb_db_zadan.ID_operitems FROM okb_db_zadan INNER JOIN okb_db_operitems ON okb_db_zadan.ID_operitems=okb_db_operitems.ID where (okb_db_zadan.ID_resurs = '".$ID_resurs."') and (okb_db_zadan.DATE<'$pdate') and (okb_db_zadan.DATE>'$pdate2') and (okb_db_operitems.STATE='0') GROUP BY okb_db_zadan.ID_operitems order by okb_db_zadan.DATE desc");
		while($res = mysql_fetch_row($result)) {
			if (!$usedoper[$res[1]]) $collected[$res[1]] = $res[1];
		}
		if (count($collected)>0) {
	   // ПОДПИСЬ ///////////////////////////////////////////////////////////////
		echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>";
			echo "<h2>Продолжить работу над операциями</h2>";
		echo "</td><td style='text-align: right;'>";
		echo "</td></tr></table><br>";

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 950px;' border='1' cellpadding='0' cellspacing='0'>";

		echo "<thead name='links_btn1_2'>";
		echo "<tr class='first'>";
		echo "<td>Заказ / ДСЕ</td>";
		echo "<td width='20'>№<br>МТК</td>";
		echo "<td width='125'>Операция</td>";
		echo "<td width='100'>Оборудование</td>";
		echo "<td width='50'>На заказ,<br>Н/Ч</td>";
		echo "<td width='40'></td>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$td_zak = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
		$oper_td = array(" ","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");
		$result_32 = dbquery("SELECT ID, ID_zak, ID_zakdet, ID_oper, ID_park, ORD, NORM_ZAK FROM okb_db_operitems ");
		while($res32 = mysql_fetch_row($result_32)) {
		if ($res32[0] == $collected[$res32[0]]) {
			$result_33_1 = dbquery("SELECT * FROM okb_db_zakdet where ID='".$res32[2]."' ");
			$res33_1 = mysql_fetch_array($result_33_1);
			$result_33 = dbquery("SELECT * FROM okb_db_zak where ID='".$res33_1['ID_zak']."' ");
			$res33 = mysql_fetch_array($result_33);
			$result_34 = dbquery("SELECT * FROM okb_db_oper where ID='".$res32[3]."' ");
			$res34 = mysql_fetch_array($result_34);
			$result_35 = dbquery("SELECT * FROM okb_db_park where ID='".$res32[4]."' ");
			$res35 = mysql_fetch_array($result_35);
			echo "<tr>
			<td class='Field'><b>".$td_zak[$res33['TID']]."&nbsp;&nbsp;".$res33['NAME']."</b>&nbsp;&nbsp;&nbsp;&nbsp;".$res33['DSE_OBOZ']."&nbsp;".$res33['DSE_NAME']."&nbsp;&nbsp;<b>".$res33_1['OBOZ']."</b>&nbsp;&nbsp;".$res33_1['NAME']."</td>
			<td class='Field'>".$res32[5]."</td>
			<td class='Field'>".$oper_td[$res34['TID']]." - ".$res34['NAME']."</td>
			<td class='Field'>".$res35['MARK']."</td>
			<td class='Field'>".$res32[6]."</td>
			<td class='Field' id='loi_".$res32[0]."'>";if ($a_deist==1) { echo "<a href='javascript:void(0);' onClick='addzadan(\"loi_".$res32[0]."\",".$res32[0].", \"date=".$pdate."&smen=".$smena."&resurs=".$ID_resurs."&idoper=".$res32[0]."\");'>>>></a>";} echo"</td>
			</tr>";
		}
		}
		echo "</tbody>";
		echo "</table>";
		}

}
echo "<script language='javascript'>
if (document.getElementsByClassName('highlite')){
	var trall = document.getElementsByClassName('highlite').length;
	for (var trind = 0; trind < (trall/2); trind++){
		if (document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0]){
			var tdval = 1, tdsum;
			tdsum=tdval+trind;
			document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0].innerText = tdsum;
		}
	}
}
window.onload = function(){
	document.getElementById('vpdiv').parentNode.parentNode.parentNode.rows[0].style.display='none';
	setTimeout('document.getElementsByClassName(\"bottom\")[0].style.display=\"none\"', '1500');
	document.getElementById('vpdiv').onscroll = function(){
		document.getElementsByName('links_btn1_2')[1].style.top=\"0px\";
	}
}
</script>";
?>