<?php
$p_1 = explode("|", $_GET['p1']);
$p_1_1 = strlen($p_1[0]);
$p_1_2 = substr($p_1[0], $p_1_1-1, 1);
$bg_stat = "";
$statuss = "";
$arch = $_GET['arch'];

if ($p_1[6]=='1'){ $statuss="Согласовано";}
if ($p_1[6]=='2'){ $statuss="Отклонено";}
if ($p_1[6]=='3'){ $statuss="Выполнено";}

if (($p_1_2=='1') and ($p_1[5]=='3')){
	dbquery("UPDATE okb_db_zapros_all SET STATUS='".$statuss."' WHERE (ID='".$p_1[1]."')");
	
	if ($p_1[6]=='1') {
		$res_5 = dbquery("SELECT * FROM okb_db_zapros_all where (ID='".$p_1[1]."') ");
		$res_5 = mysql_fetch_array($res_5);
		dbquery("INSERT INTO okb_db_itrzadan (DATE_PLAN, TIME_PLAN, ID_users, ID_users2, ID_users3, STARTTIME, STARTDATE, STATUS, TIP_JOB, TIP_FAIL, ID_zak, ID_edo, ID_zapr, TXT, CDATE, CTIME, EUSER, ETIME) VALUES ('".$res_5['DATE_PLAN']."', '".$res_5['TIME_PLAN']."', '".$res_5['ID_users2_plan']."', '".$res_5['ID_users2']."', '".$res_5['ID_users3']."', '".date("H:i:s")."', '".date("Ymd")."', 'Новое', '1', '9', '0', '0', '".$p_1[1]."', '".$res_5['TXT']."', '".date("Ymd")."', '".date("H:i:s")."', '".$res_5['ID_users2_plan']."', '".mktime()."')");
		dbquery("INSERT INTO okb_db_itrzadan_statuses (DATA, TIME, STATUS, ID_edo, USER) VALUES ('".date("Ymd")."', '".mktime()."', 'Новое', '".$p_1[1]."', '".$res_5['ID_users2_plan']."')");
		echo "<script type='text/javascript'>
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=135');
		</script>";
	}
}

if (($p_1[6]=='3') and ($p_1[5]=='3')){
	dbquery("UPDATE okb_db_zapros_all SET STATUS='".$statuss."' WHERE (ID='".$p_1[1]."')");
	dbquery("UPDATE okb_db_zapros_all SET TIME_FACT='".mktime()."' WHERE (ID='".$p_1[1]."')");
}

if (($p_1[6]=='2') and ($p_1[5]=='3')){
	dbquery("UPDATE okb_db_zapros_all SET STATUS='".$statuss."' WHERE (ID='".$p_1[1]."')");
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
echo"<h2>Запросы мне</h2><br>";

////////////        Отображение страницы
//
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 1150px;' border='1'>
<thead>
<tr class='first'>
<td>№</td>
<td>Дата<br>обработки</td>
<td>Содержание запроса</td>
<td>Автор</td>
<td>Статус</td>
<td>Комментарий</td>
</tr></thead><tbody>";

// строки таблицы
$res_1 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$res_1 = mysql_fetch_array($res_1);

if ($arch) { $wher_arch = "((STATUS='Выполнено') and (ID_users2_plan='".$res_1['ID']."'))";} else { $wher_arch = "((STATUS!='Не отправлен') and (STATUS!='Выполнено') and (ID_users2_plan='".$res_1['ID']."'))";}

$res_10 = dbquery("SELECT * FROM okb_db_zapros_all where ".$wher_arch." ");
while ($res = mysql_fetch_array($res_10)) {
	$res_3 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs='".$res['ID_users']."') ");
	$res_3 = mysql_fetch_array($res_3);
	
	$bg_td_stat="";
	if ($res['STATUS']=='Отправлен') { $cur_status = "Новый"; $bg_td_stat="style='background-color:rgb(187, 174, 0);'";}else{ $cur_status=$res['STATUS'];}
	if ($res['STATUS']=='Согласовано') { $bg_td_stat="style='background-color:#8BBB69;'";}
	if ($res['STATUS']=='Отклонено') { $bg_td_stat="style='background-color:#FF7474;'";}
	if ($res['STATUS']=='Выполнено') { $bg_td_stat="style='background-color:#66AAFF;'";}
	if ($res['STATUS']=='На доработку') { $bg_td_stat="style='background-color:#F7F346;'";}

echo "<tr>
<td class='Field' width='65px'><a href='index.php?do=show&formid=138&id=".$res['ID']."'><img src='uses/view.gif' alt='Просмотр'></a>".$res['ID']."</td>
<td class='Field' width='100px'>".IntToDate($res['DATE_PLAN'])."</td>
<td class='Field' width='300px'>".$res['TXT']."</td>
<td class='Field' width='150px'>".$res_3['NAME']."</td>
<td class='Field' width='90px' ".$bg_td_stat.">".$cur_status."</td>
<td class='Field' width='300px'>".$res['KOMM']."</td>
</tr>"; 
}

////////////       Закрытие таблицы
//
echo "</tbody></table></div></tbody></table>";

//////////////     Статус "просмотрено" у запросов
//

$result2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
$name2 = mysql_fetch_array($result2);

$result5 = dbquery("SELECT COUNT(*) FROM ".$db_prefix."db_zapros_all where ((ID_users2_plan='".$name2['ID']."') and (STATUS='Отправлен')) ");
$name5 = mysql_fetch_row($result5);
$total5 = $name5[0];

$sdh = 0;
for ($sdg = 0; $sdg < $total5; $sdg++){
  $result6 = dbquery("SELECT * FROM ".$db_prefix."db_zapros_all where ((ID_users2_plan='".$name2['ID']."') and (STATUS='Отправлен') and (ID>'".$sdh."')) ");
  $name6 = mysql_fetch_array($result6);
  $total6_1 = $name6['ID'];
  $sdh = $total6_1;

  dbquery("UPDATE ".$db_prefix."db_zapros_all SET STATUS='Просмотрено' where ((ID_users2_plan='".$name2['ID']."') and (STATUS='Отправлен') and (ID<'".($sdh+1)."')) ");
}
?>