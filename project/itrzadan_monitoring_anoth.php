<?php
$ind = 0;
$filtr_1 = $_GET["p1"];
$filtr_2 = $_GET["p2"];
$filtr_3 = $_GET["p3"];

$not_all = 0;
if (strlen($filtr_1)>0) { $expl_filtr_1 = explode("|", $filtr_1); $count_p = count($expl_filtr_1)-1; $not_all = 1;}
if (strlen($filtr_2)>0) { $expl_filtr_2 = explode("|", $filtr_2); $count_p = count($expl_filtr_2)-1; $not_all = 2;}
if (strlen($filtr_3)>0) { $expl_filtr_3 = explode("|", $filtr_3); $count_p = count($expl_filtr_3)-1; $not_all = 3;}

// ��� ��������� �� ���� ��� ������ ���������� �� ������� db_itrzadan
//$res1 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$user['ID']."') ");
if (($user['ID']==3) or ($user['ID']==4) or ($user['ID']==16) or ($user['ID']==1)) { $wher_us = 4;}else{ $wher_us=$user['ID'];}
$res1 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID_users='".$wher_us."') ");
$name1 = mysql_fetch_array($res1);

$otdels_arr = array();
$otdels_arr_1 = array();
$otdels_arr_2 = array();
$otdels_arr_3 = array();
$otdels_arr_4 = array();
$resurs_arr = array();
$resurs_id_nam = array();
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
$tip_zak = array(" ","��","��","��","��","��","��");

// ���� ����� ��� ������������� ������ �������� ������ ��� ����������������
$res2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((BOSS='1') and (NOTTAB='0') and (ID_resurs='".$name1['ID']."')) ");
if ($name2 = mysql_fetch_array($res2)){
//echo "<br>".$name2['ID_otdel'];

// ���� ��� �������� ������ �� ������� �1 ���������
$res3 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$name2['ID_otdel']."') ");
while ($name3 = mysql_fetch_array($res3)){
	$otdels_arr[]=$name3['ID'];
	//echo "<br>".count($otdels_arr)." = ".$name3['PID']." = ".$name3['ID'];
	$all_otdels = $all_otdels.$name3['ID']."|";
}

// ���� ��� �������� ������ �� ������� �2 ���������
for($arfo = 0; $arfo < count($otdels_arr); $arfo++){
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4)){
		$otdels_arr_1[]=$name4['ID'];
		$child_otdels_1 =  $child_otdels_1."<br>".count($otdels_arr_1)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// ���� ��� �������� ������ �� ������� �3 ���������
for($arfo = 0; $arfo < count($otdels_arr_1); $arfo++){
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr_1[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4)){
		$otdels_arr_2[]=$name4['ID'];
		$child_otdels_2 =  $child_otdels_2."<br>".count($otdels_arr_2)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// ���� ��� �������� ������ �� ������� �4 ���������
for($arfo = 0; $arfo < count($otdels_arr_2); $arfo++){
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr_2[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4)){
		$otdels_arr_3[]=$name4['ID'];
		$child_otdels_3 =  $child_otdels_3."<br>".count($otdels_arr_3)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// ���� ��� �������� ������ �� ������� �5 ���������
for($arfo = 0; $arfo < count($otdels_arr_3); $arfo++){
	$res4 = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (PID='".$otdels_arr_3[$arfo]."') ");
	while ($name4 = mysql_fetch_array($res4)){
		$otdels_arr_4[]=$name4['ID'];
		$child_otdels_4 =  $child_otdels_4."<br>".count($otdels_arr_4)." = ".$name4['PID']." = ".$name4['ID'];
		$all_otdels = $all_otdels.$name4['ID']."|";
	}
}

// ������� ���� �� ����������� ������
//echo $child_otdels_1;
//echo $child_otdels_2;
//echo $child_otdels_3;
//echo $child_otdels_4;

// ������� ���� ���� �� ������� �������� � ������
//echo "<br><br><br><br>";
$all_otdels_1 = $name2['ID_otdel']."|".$all_otdels;
//echo $all_otdels_1;
//echo "<br>";

// ������� ���� ���� ������������ ������� � �� ����������
$ids_all_otdels = explode("|",$all_otdels_1);
$count_ids_otdels = count($ids_all_otdels);
//echo $count_ids_otdels;
//echo "<br><br><br><br>";

// ������� �� � ��� ���� �������� �� ������������� �������
for($ids_f = 0; $ids_f < $count_ids_otdels; $ids_f++){
	$res2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((ID_resurs!=0) and (ID_otdel='".$ids_all_otdels[$ids_f]."')) ");
	while($name2 = mysql_fetch_array($res2)){
		$res2_2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$name2['ID_resurs']."') ");
		$name2_2 = mysql_fetch_array($res2_2);
		//echo "<br>".$name2_2['ID']." = ".$name2_2['NAME'];
		$resurs_arr[]=$name2_2['ID'];
		$resurs_id_nam[$name2_2['ID']]=$name2_2['NAME'];
	}
}
//echo "<br><br>".count($resurs_arr);

// ������ ������ ������� �� ��������
$arch = $_GET['arch'];
if ($arch) { $itr_wher="and ((STATUS='������������') or (STATUS='���������'))";}else{ $itr_wher="and (STATUS!='������������') and (STATUS!='���������')";}

for ($itr_res=0; $itr_res < count($resurs_arr); $itr_res++){
	$res_5 = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID_zak='0') AND (ID_users='".$resurs_arr[$itr_res]."') ".$itr_wher."");
	while($name_5 = mysql_fetch_array($res_5)){
		$res3_2 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$name_5['ID_zak']."') ");
		$name3_2 = mysql_fetch_array($res3_2);
		
		$itr_arr_0[]=$name_5['ID'];
		$itr_arr_1[]=$name_5['DATE_PLAN'];
		$itr_arr_2[]=$name_5['TXT'];
		$itr_arr_3[]=$resurs_id_nam[$name_5['ID_users']];
		$itr_arr_4[]=$resurs_id_nam[$name_5['ID_users2']];
		$itr_arr_5[]=$resurs_id_nam[$name_5['ID_users3']];
		$itr_arr_3_1[]=$name_5['ID_users'];
		$itr_arr_4_1[]=$name_5['ID_users2'];
		$itr_arr_5_1[]=$name_5['ID_users3'];
		$itr_arr_6[]=$name_5['STATUS'];
		$zak_id_nam[$name_5['ID_zak']]=$name3_2['NAME'];
		$itr_arr_7[]=$zak_id_nam[$name_5['ID_zak']];
		$itr_arr_8[]=$tip_zak[$name3_2['TID']];
	}
}

// ���� �������
$uniq_itr_arr_3 = array_unique($itr_arr_3);
$uniq_itr_arr_3_1 = array_unique($itr_arr_3_1);
array_multisort($uniq_itr_arr_3, $uniq_itr_arr_3_1);
if (count($uniq_itr_arr_3)<10) {
	$count_row = count($uniq_itr_arr_3)+1;
}else{
	$count_row = 10;
}
echo "<table class='rdtbl tbl' width='1100px'><thead>
<tr><td class='Field' style='background:#98b8e2' colspan='5'><p style='float:right;'>�������: >><input type='button' value='���������' onclick='location.href=window.location.href;'></p></td>
<td class='Field' style='background:#98b8e2'><div style='position:relative;'>
<input type='button' value='�������' onclick='if (document.getElementById(\"itr_filtr_1\").style.display==\"none\"){ document.getElementById(\"itr_filtr_1\").style.display=\"block\"; this.value=\"�������\";}else{ document.getElementById(\"itr_filtr_1\").style.display=\"none\"; this.value=\"�������\";}'>
<div id='itr_filtr_1' style='background:#c6d9f1; padding:5px; display:none; border:1px solid #8ba2c2; position:absolute; left:15px; top:20px;'><select size='".$count_row."' multiple>
<option onclick='check_filtr_clear(this);' style='width:150px;'>--- (���) ---";
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
<input type='button' value='�������' onclick='if (document.getElementById(\"itr_filtr_2\").style.display==\"none\"){ document.getElementById(\"itr_filtr_2\").style.display=\"block\"; this.value=\"�������\";}else{ document.getElementById(\"itr_filtr_2\").style.display=\"none\"; this.value=\"�������\";}'>
<div id='itr_filtr_2' style='background:#c6d9f1; padding:5px; display:none; border:1px solid #8ba2c2; display:none; position:absolute; left:15px; top:20px;'><select size='".$count_row."' multiple>
<option onclick='check_filtr_clear(this);' style='width:150px;'>--- (���) ---";
foreach($uniq_itr_arr_4 as $keey_1 => $vaal_1){
	echo "<option name='opt_filtr' onclick='check_filtr_2(this);' style='width:150px;' value='".$uniq_itr_arr_4_1[$keey_1]."'>".$uniq_itr_arr_4[$keey_1];
}
$uniq_itr_arr_5 = array_unique($itr_arr_5);
$uniq_itr_arr_5_1 = array_unique($itr_arr_5_1);
array_multisort($uniq_itr_arr_5, $uniq_itr_arr_5_1);
if (count($uniq_itr_arr_5)<10) {
	$count_row = count($uniq_itr_arr_5)+1;
}else{
	$count_row = 10;
}
echo "</select></div></div></td>
<td class='Field' style='background:#98b8e2'><div style='position:relative;'>
<input type='button' value='�������' onclick='if (document.getElementById(\"itr_filtr_3\").style.display==\"none\"){ document.getElementById(\"itr_filtr_3\").style.display=\"block\"; this.value=\"�������\";}else{ document.getElementById(\"itr_filtr_3\").style.display=\"none\"; this.value=\"�������\";}'>
<div id='itr_filtr_3' style='background:#c6d9f1; padding:5px; display:none; border:1px solid #8ba2c2; display:none; position:absolute; left:15px; top:20px;'><select size='".$count_row."' multiple>
<option onclick='check_filtr_clear(this);' style='width:150px;'>--- (���) ---";
foreach($uniq_itr_arr_5 as $keey_1 => $vaal_1){
	echo "<option name='opt_filtr' onclick='check_filtr_3(this);' style='width:150px;' value='".$uniq_itr_arr_5_1[$keey_1]."'>".$uniq_itr_arr_5[$keey_1];
}
$uniq_itr_arr_6 = array_unique($itr_arr_6);
sort($uniq_itr_arr_6);
if (count($uniq_itr_arr_6)<10) {
	$count_row = count($uniq_itr_arr_6)+1;
}else{
	$count_row = 10;
}
echo "</select></div></div></td>
<td class='Field' style='background:#98b8e2'></td></tr>
<script type='text/javascript'>
function check_filtr_1(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	if(getUrlVars()['p1']){
		var filt_1 = getUrlVars()['p1'];
		filt_1 = filt_1.replace(obj.value+'|','');
		if(obj.innerHTML.substr(0,3)=='(v)'){
			obj.innerHTML = obj.innerHTML.substr(4);
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p1='+filt_1);
			if (getUrlVars()['p1'].length<1){ history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort);}
		}else{
			obj.innerHTML = \"(v) \"+obj.innerHTML;
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p1='+obj.value+'|'+filt_1);
		}
	}else{
		var pick_cur = document.getElementsByName('opt_filtr').length;
		for (var pick_for=0; pick_for<pick_cur;pick_for++){
			if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
		}
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p1='+obj.value+'|');}
}

function check_filtr_2(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	if(getUrlVars()['p2']){
		var filt_2 = getUrlVars()['p2'];
		filt_2 = filt_2.replace(obj.value+'|','');
		if(obj.innerHTML.substr(0,3)=='(v)'){
			obj.innerHTML = obj.innerHTML.substr(4);
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p2='+filt_2);
			if (getUrlVars()['p2'].length<1){ history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort);}
		}else{
			obj.innerHTML = \"(v) \"+obj.innerHTML;
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p2='+obj.value+'|'+filt_2);
		}
	}else{
		var pick_cur = document.getElementsByName('opt_filtr').length;
		for (var pick_for=0; pick_for<pick_cur;pick_for++){
			if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
		}
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p2='+obj.value+'|');}
}

function check_filtr_3(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	if(getUrlVars()['p3']){
		var filt_3 = getUrlVars()['p3'];
		filt_3 = filt_3.replace(obj.value+'|','');
		if(obj.innerHTML.substr(0,3)=='(v)'){
			obj.innerHTML = obj.innerHTML.substr(4);
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p3='+filt_3);
			if (getUrlVars()['p3'].length<1){ history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort);}
		}else{
			obj.innerHTML = \"(v) \"+obj.innerHTML;
			history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p3='+obj.value+'|'+filt_3);
		}
	}else{
		var pick_cur = document.getElementsByName('opt_filtr').length;
		for (var pick_for=0; pick_for<pick_cur;pick_for++){
			if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
		}
		obj.innerHTML = \"(v) \"+obj.innerHTML;
		history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort+'&p3='+obj.value+'|');}
}

function check_filtr_clear(obj){
	if(getUrlVars()['arch']) { var arch='&arch=1';}else{ var arch='';}
	if(getUrlVars()['sort']){ var sort = getUrlVars()['sort'];}else{ var sort=1;}
	var pick_cur = document.getElementsByName('opt_filtr').length;
	for (var pick_for=0; pick_for<pick_cur;pick_for++){
		if(document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(0,3)=='(v)'){ document.getElementsByName('opt_filtr')[pick_for].innerHTML=document.getElementsByName('opt_filtr')[pick_for].innerHTML.substr(4);}
	}
	history.replaceState(0, 'New page title', 'index.php?do=show&formid=152'+arch+'&sort='+sort);
}

function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>
<tr class='first'>
<td>�</td>
<td><img id='sort_itr_1' src='project/img5/0.gif' style='cursor:pointer'> ����<br>����������</td>
<td>���� ����������<br>����</td>
<td>�����</td>
<td>���������� �������</td>
<td><img id='sort_itr_2' src='project/img5/c1.gif' style='cursor:pointer'> �����</td>
<td><img id='sort_itr_3' src='project/img5/c1.gif' style='cursor:pointer'> �����������</td>
<td><img id='sort_itr_4' src='project/img5/c1.gif' style='cursor:pointer'> ��������</td>
<td><img id='sort_itr_5' src='project/img5/c1.gif' style='cursor:pointer'> ������</td>
</tr></thead><tbody>";

if (!$_GET["sort"]) array_multisort($itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==2) array_multisort($itr_arr_1, SORT_DESC, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==1) array_multisort($itr_arr_1, $itr_arr_3, $itr_arr_4, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==3) array_multisort($itr_arr_3, $itr_arr_4, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==4) array_multisort($itr_arr_3, SORT_DESC, $itr_arr_4, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==5) array_multisort($itr_arr_4, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==6) array_multisort($itr_arr_4, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_5, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==7) array_multisort($itr_arr_5, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==8) array_multisort($itr_arr_5, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_6, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==9) array_multisort($itr_arr_6, $itr_arr_5, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);
if ($_GET["sort"]==10) array_multisort($itr_arr_6, $itr_arr_5, SORT_DESC, $itr_arr_3, $itr_arr_1, $itr_arr_0, $itr_arr_2, $itr_arr_4, $itr_arr_7, $itr_arr_8, $itr_arr_3_1, $itr_arr_4_1, $itr_arr_5_1);

foreach($itr_arr_0 as $keey_1 => $vaal_1){
	//if ()
	if($cur_itr_id!==$itr_arr_0[$keey_1]){
	$result5 = dbquery("SELECT MAX(ID) FROM okb_db_itrzadan_statuses where ((ID_edo='".$itr_arr_0[$keey_1]."') and (STATUS='���������')) ");
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
		if ($itr_arr_6[$keey_1]=='������������') { $stat_an = $stat_an + 1;}
		if ($itr_arr_6[$keey_1]=='���������') { $stat_com = $stat_com + 1;}
	echo "<tr>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='��������'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>
	</tr>";
			}
		}
	}
	if ($not_all==2){
		for ($for_all=0; $for_all<$count_p;$for_all++){
			if($itr_arr_4_1[$keey_1]==$expl_filtr_2[$for_all]){
		$itr_coun = $itr_coun + 1;
		if ($name5['DATA']) $date_plan_count = $date_plan_count + 1;
		if ($name5['DATA']>$itr_arr_1[$keey_1]) { $date_prosr = $date_prosr + 1;}
		if ($itr_arr_6[$keey_1]=='������������') { $stat_an = $stat_an + 1;}
		if ($itr_arr_6[$keey_1]=='���������') { $stat_com = $stat_com + 1;}
	echo "<tr>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='��������'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>
	</tr>";
			}
		}
	}
	if ($not_all==3){
		for ($for_all=0; $for_all<$count_p;$for_all++){
			if($itr_arr_5_1[$keey_1]==$expl_filtr_3[$for_all]){
		$itr_coun = $itr_coun + 1;
		if ($name5['DATA']) $date_plan_count = $date_plan_count + 1;
		if ($name5['DATA']>$itr_arr_1[$keey_1]) { $date_prosr = $date_prosr + 1;}
		if ($itr_arr_6[$keey_1]=='������������') { $stat_an = $stat_an + 1;}
		if ($itr_arr_6[$keey_1]=='���������') { $stat_com = $stat_com + 1;}
	echo "<tr>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='��������'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>
	</tr>";
			}
		}
	}
	if ($not_all==0){
		$itr_coun = $itr_coun + 1;
		if ($name5['DATA']) $date_plan_count = $date_plan_count + 1;
		if ($name5['DATA']>$itr_arr_1[$keey_1]) { $date_prosr = $date_prosr + 1;}
		if ($itr_arr_6[$keey_1]=='������������') { $stat_an = $stat_an + 1;}
		if ($itr_arr_6[$keey_1]=='���������') { $stat_com = $stat_com + 1;}
	echo "<tr>
	<td class='Field' width='65px'><a href='index.php?do=show&formid=122&id=".$itr_arr_0[$keey_1]."'><img src='uses/view.gif' alt='��������'></a>".$itr_arr_0[$keey_1]."</td>
	<td name='itrdate' class='Field' width='105px'>".IntToDate($itr_arr_1[$keey_1])."</td>
	<td name='factdate' class='Field' width='105px'>".$date_plan."</td>
	<td class='Field' width='115px'>".$itr_arr_8[$keey_1]." ".$itr_arr_7[$keey_1]."</td>
	<td class='Field' width='290'>".$itr_arr_2[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_3[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_4[$keey_1]."</td>
	<td class='Field' width='150px'>".$itr_arr_5[$keey_1]."</td>
	<td name='status' class='Field' width='85px'>".$itr_arr_6[$keey_1]."</td>
	</tr>";
	}
	}
	$cur_itr_id = $itr_arr_0[$keey_1];
}
echo "<tr class='first'><td colspan='9'>����� �������<b>&nbsp;&nbsp;&nbsp;&nbsp;(".$itr_coun.")</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
�� ��� ���������<b>&nbsp;&nbsp;&nbsp;&nbsp;(".$date_plan_count.")</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� ��� ����������<b style='color:red;'>&nbsp;&nbsp;&nbsp;&nbsp;(".$date_prosr.")</b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������� ������������<b style='color:red;'>&nbsp;&nbsp;&nbsp;&nbsp;(".$stat_an.")</b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������� ���������<b style='color:green;'>&nbsp;&nbsp;&nbsp;&nbsp;(".$stat_com.")</b></td></tr></tbody></table>";

$ind = 1;
}

echo "
<script type='text/javascript'>
var dd1 = document.getElementsByName('status');
var dd2 = document.getElementsByName('itrdate');
var dd2_1 = document.getElementsByName('factdate');
for (var ff = 0; ff < dd1.length; ff++){
   if (dd1[ff].innerText == '���������') {
      dd1[ff].style.backgroundColor = '#CA9DDC';
   }
   if (dd1[ff].innerText == '������� � ����������') {
      dd1[ff].style.backgroundColor = '#F7F346';
   }
   if (dd1[ff].innerText == '�����') {
      dd1[ff].style.backgroundColor = '#BBAE00';
   }
   if (dd1[ff].innerText == '�������') {
      dd1[ff].style.backgroundColor = '#66AAFF';
   }
   if (dd1[ff].innerText == '�� ���������') {
      dd1[ff].style.backgroundColor = '#8BBB69';
   }
   if (dd1[ff].innerText == '���������') {
      dd1[ff].style.color = 'green';
      dd1[ff].style.fontWeight='bold';
   }
   if (dd1[ff].innerText == '������������') {
      dd1[ff].style.color = 'red';
      dd1[ff].style.fontWeight='bold';
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
   }
   if (dmon2 > dmon) {
   if (dyer <= dyer2) {
       dd2_1[ff].style.backgroundColor = '#FF7474';
   }
   }
   if (dday2 > dday) {
   if (dmon <= dmon2) {
   if (dyer <= dyer2) {
      dd2_1[ff].style.backgroundColor = '#FF7474';
	}
	}
	}
   }
}

function getUrlVars() {
   var vars = {};
   var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	   vars[key] = value;
	   });
   return vars;
}</script>";
?>