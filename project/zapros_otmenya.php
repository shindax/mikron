<?php
$p_1 = explode("|", $_GET['p1']);
$arch = $_GET['arch'];
if (($p_1[0]!=='0') and ($p_1[1]!=='0') and ($p_1[2]>'0') and ($p_1[3]=='10') and ($p_1[4]=='0')){
	dbquery("UPDATE okb_db_zapros_all SET STATUS='Отправлен' WHERE ((ID='".$p_1[0]."'))");
	dbquery("UPDATE okb_db_zapros_all SET ID_users3='".$p_1[1]."' WHERE ((ID='".$p_1[0]."'))");
	echo "<script type='text/javascript'>
	history.replaceState(0, 'New page title', 'index.php?do=show&formid=136');
	</script>";
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
echo"<h2>Запросы от меня</h2><br>";

////////////        Отображение страницы
//
if (!$arch) { $tit_arch = "<td>В архив</td><td>На<br>дораб.</td>"; $tit_width = "1150px";}else{ $tit_arch = ""; $tit_width = "1060px";}
echo "<a href='index.php?do=show&formid=137'>Создать новый запрос от меня</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href='index.php?do=show&formid=151'>На сдвиг даты в задании(ях)</a><br><br>
<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: ".$tit_width.";' border='1'>
<thead>
<tr class='first'>
<td>№</td>
<td>Дата<br>обработки</td>
<td>Содержание запроса</td>
<td>Кому направлен запрос</td>
<td>Статус</td>
<td>Комментарий</td>
".$tit_arch."
</tr></thead><tbody>";

// строки таблицы
$res_1 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$res_1 = mysql_fetch_array($res_1);

if ($arch) { $wher_arch = "((TIT_HEAD='1') and (ID_users='".$res_1['ID']."'))";} else { $wher_arch = "((STATUS!='Не отправлен') and (TIT_HEAD!='1') and (ID_users='".$res_1['ID']."'))";}

$res_10 = dbquery("SELECT * FROM okb_db_zapros_all where ".$wher_arch." ");
while ($res = mysql_fetch_array($res_10)) {
	$res_3 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs='".$res['ID_users2_plan']."') ");
	$res_3 = mysql_fetch_array($res_3);
	
	$in_arch = "";
	$bg_td_stat="";
	if ($res['STATUS']=='Отправлен') { $bg_td_stat="style='background-color:rgb(187, 174, 0);'";}
	if ($res['STATUS']=='Согласовано') { $bg_td_stat="style='background-color:#8BBB69;'";}
	if ($res['STATUS']=='Отклонено') { $bg_td_stat="style='background-color:#FF7474;'";}
	if ($res['STATUS']=='Выполнено') { $bg_td_stat="style='background-color:#66AAFF;'";}
	if ($res['STATUS']=='На доработку') { $bg_td_stat="style='background-color:#F7F346;'";}
	if ((!$arch) and ($res['STATUS']=='Отклонено')) { $bg_td_stat="style='background-color:#FF7474;'"; $in_arch = "<td class='Field' width='60px'><input type='button' value='OK' onclick='vote(this , \"db_edit.php?db=db_zapros_all&field=TIT_HEAD&id=".$res['ID']."&value=1\"); this.parentNode.parentNode.style.display=\"none\";'></td><td class='Field' width='60px'></td>";}
	if ((!$arch) and ($res['STATUS']=='Выполнено')) { $bg_td_stat="style='background-color:#66AAFF;'"; $in_arch = "<td class='Field' width='60px'><input id='in_arch_zapr_".$res['ID']."' type='button' value='OK' onclick='vote(this , \"db_edit.php?db=db_zapros_all&field=TIT_HEAD&id=".$res['ID']."&value=1\"); this.parentNode.parentNode.style.display=\"none\";'></td>
	<td class='Field' width='60px'><input id='in_dorab_zapr_".$res['ID']."' type='button' value='OK' onclick='document.getElementById(\"new_komm_zapr\").style.display=\"block\";'>
	<div id='new_komm_zapr' style='display:none; position:relative;'><div style='position:absolute; top:10; left:-200px; width:230px; background:#c8daf2; box-shadow:3px 4px 20px #555555; border:1px solid #8ba2c2;'><center><br>Введите новый комментарий<br><textarea id='text_new_komm_".$res['ID']."' style='resize:none; width:200px; height:50px;'></textarea></center>
	<input type='button' value='Подтвердить' onclick='vote(this , \"db_edit.php?db=db_zapros_all&field=STATUS&id=".$res['ID']."&value=На доработку\"); document.getElementById(\"in_dorab_zapr_".$res['ID']."\").style.display=\"none\"; document.getElementById(\"in_arch_zapr_".$res['ID']."\").style.display=\"none\";
	vote(this , \"db_edit.php?db=db_zapros_all&field=KOMM&id=".$res['ID']."&value=\"+document.getElementById(\"text_new_komm_".$res['ID']."\").value); document.getElementById(\"new_komm_zapr\").style.display=\"none\"; document.getElementById(\"cur_komm_status_".$res['ID']."\").innerHTML=\"На доработку\";
	document.getElementById(\"cur_komm_status_".$res['ID']."\").style.background=\"#F7F346\"; document.getElementById(\"cur_komm_txt_".$res['ID']."\").innerHTML=document.getElementById(\"text_new_komm_".$res['ID']."\").value; vote(this , \"db_edit.php?db=db_zapros_all&field=SOGL&id=".$res['ID']."&value=0\"); '><br><br></div></div></td>";}
	if ((!$arch) and ($res['STATUS']!=='Выполнено') and ($res['STATUS']!=='Отклонено')) { $in_arch = "<td class='Field' width='60px'></td><td class='Field' width='60px'></td>";}
	
echo "<tr>
<td class='Field' width='65px'><a href='index.php?do=show&formid=138&id=".$res['ID']."'><img src='uses/view.gif' alt='Просмотр'></a>".$res['ID']."</td>
<td class='Field' width='100px'>".IntToDate($res['DATE_PLAN'])."</td>
<td class='Field' width='300px'>".$res['TXT']."</td>
<td class='Field' width='150px'>".$res_3['NAME']."</td>
<td id='cur_komm_status_".$res['ID']."' class='Field' width='80px' ".$bg_td_stat.">".$res['STATUS']."</td>
<td id='cur_komm_txt_".$res['ID']."' class='Field' width='300px'>".$res['KOMM']."</td>
".$in_arch."
</tr>"; 
}

////////////       Закрытие таблицы
//
echo "</tbody></table></div></tbody></table>";
?>