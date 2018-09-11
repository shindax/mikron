<style>
.AL
{
  text-align : left !IMPORTANT;
  background : #8FBC8F !IMPORTANT;
  cursor : pointer ;
}

.AR
{
  text-align : right !IMPORTANT;
  background : #8FBC8F !IMPORTANT;
  cursor : pointer ;
}

.AC
{
  text-align : center !IMPORTANT;
  background : #8FBC8F !IMPORTANT;
  cursor : pointer ;
}

.hidden
{
  display : none !IMPORTANT; 
}


progress 
{
  background-color: #AFEEEE;
  border: 0;
  height: 80%;
  width:100%;
  border-radius: 0 9px 9px 0;
}

progress::-webkit-progress-bar 
{
  background-color: #B0C4DE;
  border: 1 solid #000;
  height: 20px;
  width:100%;
  border-radius: 0 9px 9px 0;
}
progress::-webkit-progress-value 
{
  background-color: #2F4F4F ;
  border: 1 solid;
  height: 100%;
  width:100%;
  border-radius: 0 9px 9px 0;
}

.progress
{
  width:400px;
}

.progress_count 
{
  color : white;
  font-size:10pt;
  font-weight:200;
  letter-spacing:1px;
  margin-left:10px;
  margin-top:-18px; 
  position:absolute;
}

.progress_count:after 
{
content:"%";
}

.progress_td
{
  position:relative;
}
.progress_empty
{
  color : black ;
}

</style>

<?php
require_once("page_ids.php");
global $PROJECT_ORDER_MONITORING_PAGE_ID;

$ind = 0;
$filtr_1 = $_GET["p1"];
$filtr_2 = $_GET["p2"];
$filtr_3 = $_GET["p3"];

$not_all = 0;
if (strlen($filtr_1)>0) { $expl_filtr_1 = explode("|", $filtr_1); $count_p = count($expl_filtr_1)-1; $not_all = 1;}
if (strlen($filtr_2)>0) { $expl_filtr_2 = explode("|", $filtr_2); $count_p = count($expl_filtr_2)-1; $not_all = 2;}
if (strlen($filtr_3)>0) { $expl_filtr_3 = explode("|", $filtr_3); $count_p = count($expl_filtr_3)-1; $not_all = 3;}

$id_arr = array( 1, 3, 4, 13, 91 );

if( array_search( $user['ID'], $id_arr ) === false )
  $wher_us = $user['ID'];
    else
      $wher_us = 4;
    
$res1 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$wher_us."') ");
$name1 = mysql_fetch_array($res1);

$otdels_arr = array();
$otdels_arr_1 = array();
$otdels_arr_2 = array();
$otdels_arr_3 = array();
$otdels_arr_4 = array();
$resurs_arr = array();
$resurs_id_nam = array();
$resurs_id_nam_k = array();
$zak_id_nam = array();
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

//
// ищем отдел, где вышеуказанный ресурс является боссом без совместительства
$res2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((BOSS='1') and (NOTTAB='0') and (ID_resurs='".$name1['ID']."')) ");

if ($name2 = mysql_fetch_array($res2))
{
// берём все входящие отделы на уровень №1 вхождения
$res3 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$name2['ID_otdel']."') ");
while ( $name3 = mysql_fetch_array( $res3 ) )
{
	$otdels_arr[]=$name3['ID'];
	//echo "<br>".count($otdels_arr)." = ".$name3['PID']." = ".$name3['ID'];
	$all_otdels = $all_otdels.$name3['ID']."|";
}

// берём все входящие отделы на уровень №2 вхождения
for($arfo = 0; $arfo < count($otdels_arr); $arfo++)
{
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4))
	{
		$otdels_arr_1[]=$name4['ID'];
		$child_otdels_1 =  $child_otdels_1."<br>".count($otdels_arr_1)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// берём все входящие отделы на уровень №3 вхождения
for($arfo = 0; $arfo < count($otdels_arr_1); $arfo++)
{
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr_1[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4))
	{
		$otdels_arr_2[]=$name4['ID'];
		$child_otdels_2 =  $child_otdels_2."<br>".count($otdels_arr_2)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// берём все входящие отделы на уровень №4 вхождения
for($arfo = 0; $arfo < count($otdels_arr_2); $arfo++)
{
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr_2[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4))
	{
		$otdels_arr_3[]=$name4['ID'];
		$child_otdels_3 =  $child_otdels_3."<br>".count($otdels_arr_3)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// берём все входящие отделы на уровень №5 вхождения
for($arfo = 0; $arfo < count($otdels_arr_3); $arfo++)
{
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr_3[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4)){
		$otdels_arr_4[]=$name4['ID'];
		$child_otdels_4 =  $child_otdels_4."<br>".count($otdels_arr_4)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// выводим инфу всех ИД отделов входящих в дерево
//echo "<br><br><br><br>";
$all_otdels_1 = $name2['ID_otdel']."|".$all_otdels;

// выводим инфу всех получившихся отделов и их количество
$ids_all_otdels = explode("|",$all_otdels_1);
$count_ids_otdels = count($ids_all_otdels);

// выводим ИД и ФИО всех ресурсов из перечисленных отделов
for($ids_f = 0; $ids_f < $count_ids_otdels; $ids_f++)
{
	$res2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((ID_resurs!=0) and (ID_otdel='".$ids_all_otdels[$ids_f]."')) ");
//	$res2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where 1");    
	while($name2 = mysql_fetch_array($res2))
	{
		$res2_2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$name2['ID_resurs']."') ");
//		$res2_2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where 1");
		$name2_2 = mysql_fetch_array($res2_2);
		//echo "<br>".$name2_2['ID']." = ".$name2_2['NAME'];
		$resurs_arr[]=$name2_2['ID'];
		$resurs_id_nam[$name2_2['ID']]=$name2_2['NAME'];
	}
}

//echo "<br><br>".count($resurs_arr);

// выводим ИД и ФИО всех ресурсов для отображения контролёра
	$res5_2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_resurs!=0) ");
	while($name5_2 = mysql_fetch_array($res5_2))
	{
		$resurs_id_nam_k[$name5_2['ID_resurs']]=$name5_2['NAME'];
	}

// строим массив заданий по ресурсам
$arch = $_GET['arch'];
if ($arch) 
    $itr_wher="and ((STATUS='Аннулировано') or (STATUS='Завершено'))";
      else
        $itr_wher="and (STATUS!='Аннулировано') and (STATUS!='Завершено')";


for ( $itr_res = 0 ; $itr_res < count($resurs_arr); $itr_res ++ )
 {

	$res_5 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID_zak='0') AND (ID_proj!='0') AND ((ID_users2='".$resurs_arr[$itr_res]."') OR (ID_users='".$resurs_arr[$itr_res]."') ) ".$itr_wher." GROUP BY ID ");
	while($name_5 = mysql_fetch_array($res_5))
	{
//		$res3_2 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$name_5['ID_zak']."') ");
		$res3_2 = dbquery("SELECT * FROM ".$db_prefix."db_projects where (ID='".$name_5['ID_proj']."')");
		$name3_2 = mysql_fetch_array($res3_2);
		
		$itr_arr_0[]=$name_5['ID'];
		$itr_arr_1[]=$name_5['DATE_PLAN'];
		$itr_arr_2[]=$name_5['TXT'];
        
//		$itr_arr_3[]=$resurs_id_nam[$name_5['ID_users']];
		$result = dbquery("
      SELECT * FROM ".$db_prefix."db_resurs where ID=".$name_5['ID_users']) ;
		$creator_name = mysql_fetch_array($result);
		$itr_arr_3[]= $creator_name['NAME'] ;
        
		$itr_arr_4[]=$resurs_id_nam[$name_5['ID_users2']];
		$itr_arr_5[]=$resurs_id_nam_k[$name_5['ID_users3']];
		$itr_arr_3_1[]=$name_5['ID_users'];
		$itr_arr_4_1[]=$name_5['ID_users2'];
		$itr_arr_5_1[]=$name_5['ID_users3'];
		$itr_arr_6[]=$name_5['STATUS'];
		$zak_id_nam[$name_5['ID_zak']]=$name3_2['name'];
		$itr_arr_7[]=$zak_id_nam[$name_5['ID_zak']];
	}
}

// сама таблица
$uniq_itr_arr_3 = array_unique($itr_arr_3);
$uniq_itr_arr_3_1 = array_unique($itr_arr_3_1);
array_multisort($uniq_itr_arr_3, $uniq_itr_arr_3_1);

if (count($uniq_itr_arr_3)<10) 
 $count_row = count($uniq_itr_arr_3)+1;
  else
 	 $count_row = 10;

if ($_GET['spec_view']==2){
	$p_url_1 = "";
	$p_url_2 = "";
	if ($_GET["sort"]) $p_url_1 = "&sort=".$_GET["sort"];
	if ($_GET["arch"]) $p_url_2 = "&arch=".$_GET["arch"];
//	$href_spec_v = "<a href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID".$p_url_1.$p_url_2."'>Открыть мониторинг заданий (заказы) в стандартном виде</a>";
}
if (!$_GET['spec_view']){
	$p_url_1 = "";
	$p_url_2 = "";
	if ($_GET["sort"]) $p_url_1 = "&sort=".$_GET["sort"];
	if ($_GET["arch"]) $p_url_2 = "&arch=".$_GET["arch"];
//	$href_spec_v = "<a href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID&spec_view=2".$p_url_1.$p_url_2."'>Открыть мониторинг заданий (заказы) по службам</a>";
}
echo "<table class='rdtbl tbl' width='1200px'><thead>
<!--tr><td class='Field' style='background:#98b8e2' colspan='9'>".$href_spec_v."</td></tr-->
<tr><td class='Field' style='background:#98b8e2' colspan='5'><p style='float:right;'>Фильтры: >><input type='button' value='Применить' onclick='location.href=window.location.href;'></p></td>
<td class='Field' style='background:#98b8e2'><div style='position:relative;'>
<input type='button' value='Открыть' onclick='if (document.getElementById(\"itr_filtr_1\").style.display==\"none\"){ document.getElementById(\"itr_filtr_1\").style.display=\"block\"; this.value=\"Закрыть\";}else{ document.getElementById(\"itr_filtr_1\").style.display=\"none\"; this.value=\"Открыть\";}'>
<div id='itr_filtr_1' style='background:#c6d9f1; padding:5px; display:none; border:1px solid #8ba2c2; position:absolute; left:15px; top:20px;'><select size='".$count_row."' multiple>
<option onclick='check_filtr_clear(this);' style='width:150px;'>--- (все) ---";

foreach($uniq_itr_arr_3 as $keey_1 => $vaal_1){
	echo "<option name='opt_filtr' onclick='check_filtr_1(this);' style='width:150px;' value='".$uniq_itr_arr_3_1[$keey_1]."'>".$uniq_itr_arr_3[$keey_1];
}
$uniq_itr_arr_4 = array_unique($itr_arr_4);
$uniq_itr_arr_4_1 = array_unique($itr_arr_4_1);
array_multisort($uniq_itr_arr_4, $uniq_itr_arr_4_1);
if (count($uniq_itr_arr_4)<10) {
	$count_row = count($uniq_itr_arr_4)+1;
}else{
	$count_row = 10;
}
echo "</select></div></div></td>
<td class='Field' style='background:#98b8e2'><div style='position:relative;'>
<input type='button' value='Открыть' onclick='if (document.getElementById(\"itr_filtr_2\").style.display==\"none\"){ document.getElementById(\"itr_filtr_2\").style.display=\"block\"; this.value=\"Закрыть\";}else{ document.getElementById(\"itr_filtr_2\").style.display=\"none\"; this.value=\"Открыть\";}'>
<div id='itr_filtr_2' style='background:#c6d9f1; padding:5px; display:none; border:1px solid #8ba2c2; display:none; position:absolute; left:15px; top:20px;'><select size='".$count_row."' multiple>
<option onclick='check_filtr_clear(this);' style='width:150px;'>--- (все) ---";

foreach($uniq_itr_arr_4 as $keey_1 => $vaal_1)
    {
        echo "<option name='opt_filtr' onclick='check_filtr_2(this);' style='width:150px;' value='".$uniq_itr_arr_4_1[$keey_1]."'>".$uniq_itr_arr_4[$keey_1];
    }
$uniq_itr_arr_5 = array_unique($itr_arr_5);
$uniq_itr_arr_5_1 = array_unique($itr_arr_5_1);
array_multisort($uniq_itr_arr_5, $uniq_itr_arr_5_1);
if (count($uniq_itr_arr_5)<10) 
  $count_row = count($uniq_itr_arr_5)+1;
     else
        $count_row = 10;

echo "</select></div></div></td>
<td class='Field' style='background:#98b8e2'><div style='position:relative;'>
<input type='button' value='Открыть' onclick='if (document.getElementById(\"itr_filtr_3\").style.display==\"none\"){ document.getElementById(\"itr_filtr_3\").style.display=\"block\"; this.value=\"Закрыть\";}else{ document.getElementById(\"itr_filtr_3\").style.display=\"none\"; this.value=\"Открыть\";}'>
<div id='itr_filtr_3' style='background:#c6d9f1; padding:5px; display:none; border:1px solid #8ba2c2; display:none; position:absolute; left:15px; top:20px;'><select size='".$count_row."' multiple>
<option onclick='check_filtr_clear(this);' style='width:150px;'>--- (все) ---";

foreach($uniq_itr_arr_5 as $keey_1 => $vaal_1)
	echo "<option name='opt_filtr' onclick='check_filtr_3(this);' style='width:150px;' value='".$uniq_itr_arr_5_1[$keey_1]."'>".$uniq_itr_arr_5[$keey_1];

$uniq_itr_arr_6 = array_unique($itr_arr_6);
sort($uniq_itr_arr_6);
if (count($uniq_itr_arr_6)<10) 
   $count_row = count($uniq_itr_arr_6)+1;
    else
      $count_row = 10;

echo "</select></div></div></td>
<td class='Field' style='background:#98b8e2'></td></tr>
<script type='text/javascript'>
function check_filtr_1(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['spec_view']) { var spec_view='&spec_view=2';}else{ var spec_view='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	if(getUrlVars()['p1']){
		var filt_1 = getUrlVars()['p1'];
		filt_1 = filt_1.replace(obj.value+'|','');
		if(obj.innerHTML.substr(0,3)=='(v)'){
			obj.innerHTML = obj.innerHTML.substr(4);
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p1='+filt_1);
			if (getUrlVars()['p1'].length<1){ history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort);}
		}else{
			obj.innerHTML = \"(v) \"+obj.innerHTML;
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p1='+obj.value+'|'+filt_1);
		}
	}else{
		var pick_cur = document.getElementsByName('opt_filtr').length;
		for (var pick_for=0; pick_for<pick_cur;pick_for++){
			if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
		}
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p1='+obj.value+'|');}
}

function check_filtr_2(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['spec_view']) { var spec_view='&spec_view=2';}else{ var spec_view='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	if(getUrlVars()['p2']){
		var filt_2 = getUrlVars()['p2'];
		filt_2 = filt_2.replace(obj.value+'|','');
		if(obj.innerHTML.substr(0,3)=='(v)'){
			obj.innerHTML = obj.innerHTML.substr(4);
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p2='+filt_2);
			if (getUrlVars()['p2'].length<1){ history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort);}
		}else{
			obj.innerHTML = \"(v) \"+obj.innerHTML;
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p2='+obj.value+'|'+filt_2);
		}
	}else{
		var pick_cur = document.getElementsByName('opt_filtr').length;
		for (var pick_for=0; pick_for<pick_cur;pick_for++){
			if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
		}
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p2='+obj.value+'|');}
}

function check_filtr_3(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['spec_view']) { var spec_view='&spec_view=2';}else{ var spec_view='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	if(getUrlVars()['p3']){
		var filt_3 = getUrlVars()['p3'];
		filt_3 = filt_3.replace(obj.value+'|','');
		if(obj.innerHTML.substr(0,3)=='(v)'){
			obj.innerHTML = obj.innerHTML.substr(4);
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p3='+filt_3);
			if (getUrlVars()['p3'].length<1){ history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort);}
		}else{
			obj.innerHTML = \"(v) \"+obj.innerHTML;
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p3='+obj.value+'|'+filt_3);
		}
	}else{
		var pick_cur = document.getElementsByName('opt_filtr').length;
		for (var pick_for=0; pick_for<pick_cur;pick_for++){
			if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
		}
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort+'&p3='+obj.value+'|');}
}

function check_filtr_clear(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['spec_view']) { var spec_view='&spec_view=2';}else{ var spec_view='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	var pick_cur = document.getElementsByName('opt_filtr').length;
	for (var pick_for=0; pick_for<pick_cur;pick_for++){
		if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
	}
	history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch+spec_view+'&sort='+sort);
}

function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>
<tr class='first'>
<td>№</td>
<td>Дата<br>выполнения</td>
<td>Дата выполнения<br>факт</td>
<td><img id='sort_itr_1' src='project/img5/0.gif' style='cursor:pointer'> Заказ</td>
<td>содержание задания</td>
<td><img id='sort_itr_2' src='project/img5/c1.gif' style='cursor:pointer'> Автор</td>
<td><img id='sort_itr_3' src='project/img5/c1.gif' style='cursor:pointer'> Исполнитель</td>
<td><img id='sort_itr_4' src='project/img5/c1.gif' style='cursor:pointer'> Контролёр</td>
<td><img id='sort_itr_5' src='project/img5/c1.gif' style='cursor:pointer'> Статус</td>
</tr></thead><tbody>";

if (!$_GET["spec_view"]){
if (!$_GET["sort"]) array_multisort($itr_arr_7, $itr_arr_8, $itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==2) array_multisort($itr_arr_7, SORT_DESC, $itr_arr_8, $itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==1) array_multisort($itr_arr_7, $itr_arr_8, $itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==3) array_multisort($itr_arr_3, $itr_arr_4, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==4) array_multisort($itr_arr_3, SORT_DESC, $itr_arr_4, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==5) array_multisort($itr_arr_4, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==6) array_multisort($itr_arr_4, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==7) array_multisort($itr_arr_5, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==8) array_multisort($itr_arr_5, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==9) array_multisort($itr_arr_6, $itr_arr_5, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==10) array_multisort($itr_arr_6, $itr_arr_5, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
}

$itr_arr_11 = "";
$itr_arr_12 = "";
if ($_GET["spec_view"]==2){
	$arr_itr_us2_ids = array_unique($itr_arr_4_1);
	
	$arr_otdels_ids = array();
	$ids_otdels = dbquery("SELECT ID_resurs, ID_otdel FROM ".$db_prefix."db_shtat where ID_resurs!='0' AND NOTTAB='0'");
	while($ids_otdels_r = mysql_fetch_array($ids_otdels)){
		$arr_otdels_ids[$ids_otdels_r['ID_resurs']]=$ids_otdels_r['ID_otdel'];
	}
	
	$new_arr_ids_otdel = array();
	foreach($arr_itr_us2_ids as $k1_1 => $v1_1){
		$new_arr_ids_otdel[]=$arr_otdels_ids[$v1_1];
	}
	$uniq_new_arr_ids_otdel = array_unique($new_arr_ids_otdel);
	
	$arr_otdels_nams = array();
	$arr_otdels_oboz = array();
	$nams_otdels = dbquery("SELECT NAME, ID, OBOZ FROM ".$db_prefix."db_otdel");
	while($nams_otdels_r = mysql_fetch_array($nams_otdels)){
		$arr_otdels_nams[$nams_otdels_r['ID']]=$nams_otdels_r['NAME'];
		$arr_otdels_oboz[$nams_otdels_r['ID']]=$nams_otdels_r['OBOZ'];
	}
	
	$ids_names_otdels = array();
	$ids_oboz_otdels = array();
	foreach($uniq_new_arr_ids_otdel as $k1_1 => $v1_1){
		$ids_names_otdels[] = $arr_otdels_nams[$v1_1];
		$ids_oboz_otdels[] = $arr_otdels_oboz[$v1_1];
	}
	
	array_multisort($ids_names_otdels, $ids_oboz_otdels);
/*	foreach($ids_names_otdels as $k1_1 => $v1_1){
		echo $v1_1."<br>";
	}*/

	
	$itr_arr_11 = array();
	$itr_arr_12 = array();
	foreach($itr_arr_4_1 as $k1_1 => $v1_1)
	{
		$itr_arr_11[$k1_1] = $arr_otdels_nams[$arr_otdels_ids[$v1_1]];
		$itr_arr_12[$k1_1] = $arr_otdels_oboz[$arr_otdels_ids[$v1_1]];
	}
if (!$_GET["sort"]) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_7, $itr_arr_8, $itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==2) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_7, SORT_DESC, $itr_arr_8, $itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==1) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_7, $itr_arr_8, $itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==3) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_3, $itr_arr_4, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==4) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_3, SORT_DESC, $itr_arr_4, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==5) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_4, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==6) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_4, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==7) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_5, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==8) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_5, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==9) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_6, $itr_arr_5, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==10) array_multisort($itr_arr_11, $itr_arr_12, $itr_arr_6, $itr_arr_5, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
}

$pred_otdel = "";

asort( $itr_arr_7  ); // Сортировка по имени заказа
$prev_order_name = '';

foreach($itr_arr_7 as $keey_1 => $vaal_1)
{
  $order_name = $vaal_1 ;

	if($cur_itr_id!==$itr_arr_0[$keey_1])
   {
	$result5 = dbquery("SELECT MAX(ID) FROM okb_db_itrzadan_statuses where ((ID_edo='".$itr_arr_0[$keey_1]."') and (STATUS='Выполнено')) ");
	$name5 = mysql_fetch_row($result5);
	$total5 = $name5[0];
	$result5 = dbquery("SELECT * FROM okb_db_itrzadan_statuses where (ID='".$total5."') ");
	if ($name5 = mysql_fetch_array($result5)){
		$date_plan = $name5['DATA'][6].$name5['DATA'][7].".".$name5['DATA'][4].$name5['DATA'][5].".".$name5['DATA'][0].$name5['DATA'][1].$name5['DATA'][2].$name5['DATA'][3];
	}else{
		$date_plan = "";
	}

	if ($not_all==1){
		for ($for_all=0; $for_all<$count_p;$for_all++){
			if($itr_arr_3_1[$keey_1]==$expl_filtr_1[$for_all]){
		$itr_coun = $itr_coun + 1;
		if ($name5['DATA']) $date_plan_count = $date_plan_count + 1;
		if ($name5['DATA']>$itr_arr_1[$keey_1]) { $date_prosr = $date_prosr + 1;}
		if ($itr_arr_6[$keey_1]=='Аннулировано') { $stat_an = $stat_an + 1;}
		if ($itr_arr_6[$keey_1]=='Завершено') { $stat_com = $stat_com + 1;}
		if ($itr_arr_11[$keey_1]!==$pred_otdel){
			$colsp_tr = "9";
			if ($_GET['spec_view']==2) $colsp_tr = "10";
			echo "<tr style='background:#CBDEF4; height:33px;'><td class='Field' colspan=".$colsp_tr."><b style='font-size:130%; float:left;'>    ".$itr_arr_11[$keey_1]."</b></td></tr>";
		}
	
	
  if( $prev_order_name != $order_name )
  {
    $prev_order_name = $order_name;
    $key = $keey_1 ;
    $query = "
              SELECT prj.perc_of_execution, prj.STATUS, crt.name CR_NAME, exec.name EX_NAME, chk.name CH_NAME 
              FROM ".$db_prefix."db_projects prj 
              INNER JOIN ".$db_prefix."db_resurs crt ON crt.ID = prj.ID_creator 
              INNER JOIN ".$db_prefix."db_resurs exec ON exec.ID = prj.ID_executor 
              INNER JOIN ".$db_prefix."db_resurs chk ON chk.ID = prj.ID_checker 
              where prj.name='$order_name'";

    $result = dbquery( $query );
    $row = mysql_fetch_assoc( $result );
    
    $creator = $row['CR_NAME'];
    $executor = $row['EX_NAME'];
    $checker = $row['CH_NAME'];
    $state = $row['STATUS'];    
    $perc = $row['perc_of_execution'];

    echo "<tr class='project_head' data-opened='0' data-proj-id='$key'>
          <td class='Field AL project_title' data-proj-id='$key' colspan='4'>&nbsp;&nbsp;$order_name&nbsp;<span>&#9658;</span></td>
          <td class='Field AR project_title progress_td'>
          <progress value='$perc' min ='0' max='100'></progress>
          <div class='progress_count'>$perc</div>
          </td>
          <td class='Field AC project_title'>$creator</td>
          <td class='Field AC project_title'>$executor</td>
          <td class='Field AC project_title'>$checker</td>
          <td class='Field AC project_title'>$state</td>
          </tr>";
  }

//	echo "<tr data-id='2'>
	echo "<tr class='ord_row hidden' data-proj='$key' data-ord='".$itr_arr_0[$keey_1]."'>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='Просмотр'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290' style='padding:6px;'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>";
	$ind_row_txt = "";
	if ($ind_row >= 5) 
    { 
      $ind_row_txt = $itr_arr_12[$keey_1]; 
      $ind_row = 0; 
    }
	if ($_GET['spec_view']==2) 
    echo "<td style='background:#CBDEF4; border-right:1px solid #000;' width='60px'>".$ind_row_txt."</td></tr>";
	$pred_otdel = $itr_arr_11[$keey_1];
	$ind_row_txt = "";
	$ind_row = $ind_row + 1;
			}
		}
	}
	if ($not_all==2)
	{
		for ( $for_all=0; $for_all < $count_p ; $for_all ++)
		{
			if( $itr_arr_4_1[$keey_1]==$expl_filtr_2[$for_all] )
			{
        $itr_coun = $itr_coun + 1;
        if ($name5['DATA']) 
          $date_plan_count ++ ;
        if ($name5['DATA']>$itr_arr_1[$keey_1]) 
          $date_prosr ++ ;
        if ($itr_arr_6[$keey_1]=='Аннулировано') 
          $stat_an ++ ; 
        if ($itr_arr_6[$keey_1]=='Завершено') 
          $stat_com ++ ;
        if ($itr_arr_11[$keey_1]!==$pred_otdel)
        {
          $colsp_tr = "9";
          if ($_GET['spec_view']==2) 
            $colsp_tr = "10";
          echo "<tr style='background:#CBDEF4; height:33px;'><td class='Field' colspan=".$colsp_tr."><b style='font-size:130%; float:left;'>    ".$itr_arr_11[$keey_1]."</b></td></tr>";
        }


  if( $prev_order_name != $order_name )
  {
    $prev_order_name = $order_name;
    $key = $keey_1 ;
    $query = "
              SELECT prj.perc_of_execution, prj.STATUS, crt.name CR_NAME, exec.name EX_NAME, chk.name CH_NAME 
              FROM ".$db_prefix."db_projects prj 
              INNER JOIN ".$db_prefix."db_resurs crt ON crt.ID = prj.ID_creator 
              INNER JOIN ".$db_prefix."db_resurs exec ON exec.ID = prj.ID_executor 
              INNER JOIN ".$db_prefix."db_resurs chk ON chk.ID = prj.ID_checker 
              where prj.name='$order_name'";

    $result = dbquery( $query );
    $row = mysql_fetch_assoc( $result );
    
    $creator = $row['CR_NAME'];
    $executor = $row['EX_NAME'];
    $checker = $row['CH_NAME'];
    $state = $row['STATUS'];    
    $perc = $row['perc_of_execution'];

    echo "<tr class='project_head' data-opened='0' data-proj-id='$key'>
          <td class='Field AL project_title' data-proj-id='$key' colspan='4'>&nbsp;&nbsp;$order_name&nbsp;<span>&#9658;</span></td>
          <td class='Field AR project_title progress_td'>
          <progress value='$perc' min ='0' max='100'></progress>
          <div class='progress_count'>$perc</div>
          </td>
          <td class='Field AC project_title'>$creator</td>
          <td class='Field AC project_title'>$executor</td>
          <td class='Field AC project_title'>$checker</td>
          <td class='Field AC project_title'>$state</td>
          </tr>";
  }
	
	echo "<tr class='ord_row hidden' data-proj='$key' data-ord='".$itr_arr_0[$keey_1]."'>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='Просмотр'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290' style='padding:6px;'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>";
	$ind_row_txt = "";
	if ($ind_row >= 5) { $ind_row_txt = $itr_arr_12[$keey_1]; $ind_row = 0; }
	if ($_GET['spec_view']==2) echo "<td style='background:#CBDEF4; border-right:1px solid #000;' width='60px'>".$ind_row_txt."</td></tr>";
	$pred_otdel = $itr_arr_11[$keey_1];
	$ind_row_txt = "";
	$ind_row = $ind_row + 1;
			}
		}
	} // 	if ($not_all==2)
	if ($not_all==3)
     {
		for ($for_all=0; $for_all<$count_p;$for_all++)
        {
			if($itr_arr_5_1[$keey_1]==$expl_filtr_3[$for_all])
                {
                    $itr_coun = $itr_coun + 1;
                    if ($name5['DATA']) 
                        $date_plan_count = $date_plan_count + 1;
                    if ($name5['DATA']>$itr_arr_1[$keey_1]) 
                        $date_prosr = $date_prosr + 1;
                    if ($itr_arr_6[$keey_1]=='Аннулировано') 
                        $stat_an = $stat_an + 1;
                    if ($itr_arr_6[$keey_1]=='Завершено') 
                        $stat_com = $stat_com + 1;
                    if ($itr_arr_11[$keey_1]!==$pred_otdel)
                        {
                            $colsp_tr = "9";
                            if ($_GET['spec_view']==2) 
                                $colsp_tr = "10";
                            echo "<tr style='background:#CBDEF4; height:33px;'><td class='Field' colspan=".$colsp_tr."><b style='font-size:130%; float:left;'>    ".$itr_arr_11[$keey_1]."</b></td></tr>";
                        }
	
	

	echo "<tr>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='Просмотр'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290' style='padding:6px;'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>";
	$ind_row_txt = "";
	if ($ind_row >= 5) 
        { 
               $ind_row_txt = $itr_arr_12[$keey_1]; 
               $ind_row = 0; 
        }
	if ($_GET['spec_view']==2) 
        echo "<td style='background:#CBDEF4; border-right:1px solid #000;' width='60px'>".$ind_row_txt."</td></tr>";
    $pred_otdel = $itr_arr_11[$keey_1];
	$ind_row_txt = "";
	$ind_row = $ind_row + 1;
			}
		}
	}
	if ($not_all==0)
     {
		$itr_coun = $itr_coun + 1;
		if ($name5['DATA']) 
                $date_plan_count = $date_plan_count + 1;
		if ($name5['DATA']>$itr_arr_1[$keey_1]) 
                $date_prosr = $date_prosr + 1;
                    
		if ($itr_arr_6[$keey_1]=='Аннулировано') 
            $stat_an = $stat_an + 1;
		if ($itr_arr_6[$keey_1]=='Завершено') 
            $stat_com = $stat_com + 1;
		if ($itr_arr_11[$keey_1]!==$pred_otdel)
            {
                $colsp_tr = "9";
                if ($_GET['spec_view']==2) 
                    $colsp_tr = "10";
                echo "<tr style='background:#CBDEF4; height:33px;'><td class='Field' colspan=".$colsp_tr."><b style='font-size:130%; float:left;'>    ".$itr_arr_11[$keey_1]."</b></td></tr>";
            }


  if( $prev_order_name != $order_name )
  {
    $prev_order_name = $order_name;
    $key = $keey_1 ;
    $query = "
              SELECT prj.perc_of_execution, prj.STATUS, crt.name CR_NAME, exec.name EX_NAME, chk.name CH_NAME 
              FROM ".$db_prefix."db_projects prj 
              INNER JOIN ".$db_prefix."db_resurs crt ON crt.ID = prj.ID_creator 
              INNER JOIN ".$db_prefix."db_resurs exec ON exec.ID = prj.ID_executor 
              INNER JOIN ".$db_prefix."db_resurs chk ON chk.ID = prj.ID_checker 
              where prj.name='$order_name'";

    $result = dbquery( $query );
    $row = mysql_fetch_assoc( $result );
    
    $creator = $row['CR_NAME'];
    $executor = $row['EX_NAME'];
    $checker = $row['CH_NAME'];
    $state = $row['STATUS'];    
    $perc = $row['perc_of_execution'];


    echo "<tr class='project_head' data-opened='0' data-proj-id='$key'>
          <td class='Field AL project_title' data-proj-id='$key' colspan='4'>&nbsp;&nbsp;$order_name&nbsp;<span>&#9658;</span></td>
          <td class='Field AR project_title progress_td'>
          <progress value='$perc' min ='0' max='100'></progress>
          <div class='progress_count'>$perc</div>
          </td>
          <td class='Field AC project_title'>$creator</td>
          <td class='Field AC project_title'>$executor</td>
          <td class='Field AC project_title'>$checker</td>
          <td class='Field AC project_title'>$state</td>
          </tr>";
  }

	echo "<tr class='ord_row hidden' data-proj='$key' data-ord='".$itr_arr_0[$keey_1]."'>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='Просмотр'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290' style='padding:6px;'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>";
	$ind_row_txt = "";
	if ($ind_row >= 5) 
        { 
            $ind_row_txt = $itr_arr_12[$keey_1]; 
            $ind_row = 0; 
        }
	if ($_GET['spec_view']==2) 
        echo "<td style='background:#CBDEF4; border-right:1px solid #000;' width='60px'>".$ind_row_txt."</td></tr>";
	$pred_otdel = $itr_arr_11[$keey_1];
	$ind_row_txt = "";
	$ind_row = $ind_row + 1;
	}
 }
	$cur_itr_id = $itr_arr_0[$keey_1];
} // foreach($itr_arr_0 as $keey_1 => $vaal_1)


echo "<tr class='first total_row'><td colspan='9'>Итого заданий<b id='total'>&nbsp;&nbsp;&nbsp;&nbsp;(".$itr_coun.")</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Из них выполнено<b>&nbsp;&nbsp;&nbsp;&nbsp;(".$date_plan_count.")</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Из них просрочено<b style='color:red;'>&nbsp;&nbsp;&nbsp;&nbsp;(".$date_prosr.")</b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Заданий аннулировано<b style='color:red;'>&nbsp;&nbsp;&nbsp;&nbsp;(".$stat_an.")</b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Заданий завершено<b style='color:green;'>&nbsp;&nbsp;&nbsp;&nbsp;(".$stat_com.")</b></td></tr></tbody></table>";

$ind = 1;
}

echo "
<script type='text/javascript'>
var dd1 = document.getElementsByName('status');
var dd2 = document.getElementsByName('itrdate');
var dd2_1 = document.getElementsByName('factdate');
for (var ff = 0; ff < dd1.length; ff++)
{
   if (dd1[ff].innerText == 'Выполнено')
      dd1[ff].style.backgroundColor = '#CA9DDC';

   if (dd1[ff].innerText == 'Принято к исполнению')
      dd1[ff].style.backgroundColor = '#F7F346';

   if (dd1[ff].innerText == 'Новое') 
      dd1[ff].style.backgroundColor = '#BBAE00';

   if (dd1[ff].innerText == 'Принято')
      dd1[ff].style.backgroundColor = '#66AAFF';

   if (dd1[ff].innerText == 'На доработку') 
      dd1[ff].style.backgroundColor = '#8BBB69';

  if (dd1[ff].innerText == 'Завершено') 
   {
      dd1[ff].style.color = 'green';
      dd1[ff].style.fontWeight='bold';
   }
   if (dd1[ff].innerText == 'Аннулировано') {
      dd1[ff].style.color = 'red';
      dd1[ff].style.fontWeight='bold';
   }

   if (dd2_1[ff].innerText.length>1)
   {
   var ddate = dd2[ff].innerText;
   var dday = ddate.substr(0, 2);
   var dmon = ddate.substr(3, 2);
   var dyer = ddate.substr(6, 4);
   var ddate2 = dd2_1[ff].innerText;
   var dday2 = ddate2.substr(0, 2);
   var dmon2 = ddate2.substr(3, 2);
   var dyer2 = ddate2.substr(6, 4);
   if (dyer2 > dyer) 
      dd2_1[ff].style.backgroundColor = '#FF7474';
   if (dmon2 > dmon) 
      if (dyer <= dyer2) 
       dd2_1[ff].style.backgroundColor = '#FF7474';

   if (dday2 > dday) 
     if (dmon <= dmon2) 
       if (dyer <= dyer2) 
            dd2_1[ff].style.backgroundColor = '#FF7474';
   }
}

function getUrlVars() 
{
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>";
?>
