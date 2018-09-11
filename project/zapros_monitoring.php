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
	//dbquery("UPDATE okb_db_zapros_all SET STATUS='".$statuss."' WHERE (ID='".$p_1[1]."')");
	
	if ($p_1[6]=='1') {
		$res_5 = dbquery("SELECT * FROM okb_db_zapros_all where (ID='".$p_1[1]."') ");
		$res_5 = mysql_fetch_array($res_5);
		//dbquery("INSERT INTO okb_db_itrzadan (DATE_PLAN, TIME_PLAN, ID_users, ID_users2, ID_users3, STARTTIME, STARTDATE, STATUS, TIP_JOB, TIP_FAIL, ID_zak, ID_edo, ID_zapr, TXT, CDATE, CTIME, EUSER, ETIME) VALUES ('".$res_5['DATE_PLAN']."', '".$res_5['TIME_PLAN']."', '".$res_5['ID_users2_plan']."', '".$res_5['ID_users2']."', '".$res_5['ID_users3']."', '".date("H:i:s")."', '".date("Ymd")."', 'Новое', '1', '9', '0', '0', '".$p_1[1]."', '".$res_5['TXT']."', '".date("Ymd")."', '".date("H:i:s")."', '".$res_5['ID_users2_plan']."', '".mktime()."')");
		//dbquery("INSERT INTO okb_db_itrzadan_statuses (DATA, TIME, STATUS, ID_edo, USER) VALUES ('".date("Ymd")."', '".mktime()."', 'Новое', '".$p_1[1]."', '".$res_5['ID_users2_plan']."')");
		echo "<script type='text/javascript'>
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=135');
		</script>";
	}
}

if (($p_1[6]=='3') and ($p_1[5]=='3')){
	//dbquery("UPDATE okb_db_zapros_all SET STATUS='".$statuss."' WHERE (ID='".$p_1[1]."')");
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
echo"<h2>Мониторинг запросов</h2><br>";
if (!$_GET['arch']){
	echo "<a href='index.php?do=show&formid=172&arch=1'>Архив</a><br><br>";
}else{
	echo "<a href='index.php?do=show&formid=172'>В работе</a><br><br>";
}

////////////        Отображение страницы
//
echo "<table class='rdtbl tbl' style='border-collapse: collapse; text-align: left; width: 1200px;' border='1'>
<thead>
<tr class='first'>
<td><img id='sort_itr_1' src='project/img5/0.gif' style='cursor:pointer'> №</td>
<td><img id='sort_itr_2' src='project/img5/c1.gif' style='cursor:pointer'> Дата<br>обработки</td>
<td>Содержание запроса</td>
<td><img id='sort_itr_3' src='project/img5/c1.gif' style='cursor:pointer'> Автор</td>
<td><img id='sort_itr_4' src='project/img5/c1.gif' style='cursor:pointer'> Кому отправлен<br>запрос</td>
<td><img id='sort_itr_5' src='project/img5/c1.gif' style='cursor:pointer'> Статус</td>
<td>Комментарий</td>
</tr></thead><tbody>";

// строки таблицы
$res_1 = dbquery("SELECT * FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$res_1 = mysql_fetch_array($res_1);

if ($arch) { $wher_arch = "(TIT_HEAD='1')";} else { $wher_arch = "(TIT_HEAD='0')";}

$zapros_arr_ID = array();
$zapros_arr_DATEPLAN = array();
$zapros_arr_TXT = array();
$zapros_arr_IDUSERS = array();
$zapros_arr_IDUSERS2 = array();
$zapros_arr_STATUS = array();
$zapros_arr_KOMM = array();

$res_10 = dbquery("SELECT * FROM okb_db_zapros_all where ".$wher_arch." AND STATUS!='Не отправлен'");
while ($res = mysql_fetch_array($res_10)) {
	$res_3 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs='".$res['ID_users']."') ");
	$res_3 = mysql_fetch_array($res_3);
	$res_4 = dbquery("SELECT * FROM okb_db_shtat where (ID_resurs='".$res['ID_users2_plan']."') ");
	$res_4 = mysql_fetch_array($res_4);
	
	$zapros_arr_ID[] = $res['ID'];
	$zapros_arr_DATEPLAN[] = $res['DATE_PLAN'];
	$zapros_arr_TXT[] = $res['TXT'];
	$zapros_arr_IDUSERS[] = $res_3['NAME'];
	$zapros_arr_IDUSERS2[] = $res_4['NAME'];
	$zapros_arr_STATUS[] = $res['STATUS'];
	$zapros_arr_KOMM[] = $res['KOMM'];
}

if (!$_GET["sort"]) array_multisort($zapros_arr_ID, SORT_DESC, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==2) array_multisort($zapros_arr_ID, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==1) array_multisort($zapros_arr_ID, SORT_DESC, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==3) array_multisort($zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_IDUSERS, $zapros_arr_ID, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==4) array_multisort($zapros_arr_DATEPLAN, $zapros_arr_IDUSERS, $zapros_arr_ID, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==5) array_multisort($zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_ID, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==6) array_multisort($zapros_arr_IDUSERS, SORT_DESC, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_ID, $zapros_arr_IDUSERS2, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==7) array_multisort($zapros_arr_IDUSERS2, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_ID, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==8) array_multisort($zapros_arr_IDUSERS2, SORT_DESC, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_ID, $zapros_arr_STATUS, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==9) array_multisort($zapros_arr_STATUS, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_ID, $zapros_arr_IDUSERS2, $zapros_arr_TXT, $zapros_arr_KOMM);
if ($_GET["sort"]==10) array_multisort($zapros_arr_STATUS, SORT_DESC, $zapros_arr_IDUSERS, $zapros_arr_DATEPLAN, SORT_DESC, $zapros_arr_ID, $zapros_arr_IDUSERS2, $zapros_arr_TXT, $zapros_arr_KOMM);
	
foreach ($zapros_arr_ID as $key_7 => $val_7){
	$bg_td_stat="";
	if ($zapros_arr_STATUS[$key_7]=='Отправлен') { $cur_status = "Новый"; $bg_td_stat="style='background-color:rgb(187, 174, 0);'";}else{ $cur_status=$zapros_arr_STATUS[$key_7];}
	if ($zapros_arr_STATUS[$key_7]=='Согласовано') { $bg_td_stat="style='background-color:#8BBB69;'";}
	if ($zapros_arr_STATUS[$key_7]=='Отклонено') { $bg_td_stat="style='background-color:#FF7474;'";}
	if ($zapros_arr_STATUS[$key_7]=='Выполнено') { $bg_td_stat="style='background-color:#66AAFF;'";}
	if ($zapros_arr_STATUS[$key_7]=='На доработку') { $bg_td_stat="style='background-color:#F7F346;'";}

echo "<tr>
<td class='Field' width='65px'><a href='index.php?do=show&formid=138&id=".$val_7."'><img src='uses/view.gif' alt='Просмотр'></a>".$val_7."</td>
<td class='Field' width='100px'>".IntToDate($zapros_arr_DATEPLAN[$key_7])."</td>
<td class='Field' width='300px'>".$zapros_arr_TXT[$key_7]."</td>
<td class='Field' width='150px'>".$zapros_arr_IDUSERS[$key_7]."</td>
<td class='Field' width='150px'>".$zapros_arr_IDUSERS2[$key_7]."</td>
<td class='Field' width='90px' ".$bg_td_stat.">".$cur_status."</td>
<td class='Field' width='300px'>".$zapros_arr_KOMM[$key_7]."</td>
</tr>"; 
}

////////////       Закрытие таблицы
//
echo "</tbody></table></div></tbody></table>";
?>