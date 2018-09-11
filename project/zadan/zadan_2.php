<?php
$ID_resurs = $_GET["p2"];
$smena = $_GET["p1"];
if (($smena!=="1") && ($smena!=="2") && ($smena!=="3")) $smena = "1";
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);
$back_url = "index.php?do=show&formid=64&p0=".$pdate."&p1=".$smena;
$title = $smena." см. ".$date;

// верхняя область ///////////////////////////////////////////////////////////////
$us_righ = $user['ID_rightgroups'];
$righs = explode("|",$us_righ);
$righs_count = count($righs);
for ($fcount1 = 0; $fcount1 < $righs_count; $fcount1++){
	if (($righs[$fcount1]=='18') or ($righs[$fcount1]=='17') or ($righs[$fcount1]=='1')){
		$edittrigh = 1;
	}
}

$resurs = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$ID_resurs."')");
$resurs = mysql_fetch_array($resurs);
$res_par = explode("|",$resurs['PARK_IDS']);
$res_op = explode("|",$resurs['OPER_IDS']);
$result = dbquery("SELECT * FROM ".$db_prefix."db_shtat INNER JOIN ".$db_prefix."db_special ON okb_db_shtat.ID_special=okb_db_special.ID where (okb_db_shtat.ID_resurs = '".$ID_resurs."')");

echo "</form>\n";
echo "<div class='links'><a href='".$back_url."'>Назад</a><br><br></div>";
echo "<form id='form1x' method='post' action='".$back_url."'>
<br><table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n
<h2>".$resurs["NAME"]."<span><br>";
while($shtat = mysql_fetch_array($result)) {
	echo "<br>".$shtat['NAME']." ".FVal($shtat,"db_shtat","ID_speclvl");
}
echo "</span></h2></td>
<td style='text-align: right;'><div class='links'>
".$smena." смена ".$date."<br><br>
<input type='hidden' name='add_zadan_to_resurs' value='".$ID_resurs."'>
</div></td></tr></table>";

if ($edittrigh == '1'){

   ///// ПРОДОЛЖЕНИЕ НЕЗАКОНЧЕННЫХ ОПЕРАЦИЙ
////////// <><><>
		$usedoper[] = "0";
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$ID_resurs."')");
		while($res = mysql_fetch_array($result)) {
			$usedoper[] = $res["ID_operitems"];
		}

		$pdate2 = explode(".",$date);
		$pdate2[0] = $pdate2[0] - 7;
		if ($pdate2[0]<1) {
			$pdate2[0] = 30 + $pdate2[0];
			$pdate2[1] = $pdate2[1] - 1;
			if ($pdate2[1]<1) {
				$pdate2[1] = 12 + $pdate2[2];
				$pdate2[2] = $pdate2[2] - 1;
			
			}
		}
		$pdate2 = $pdate2[2]*10000+$pdate2[1]*100+$pdate2[0];


		$now_is_used[] = "0";
		$collected[] = "0";
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_resurs = '".$ID_resurs."') and (DATE<'$pdate') and (DATE>'$pdate2') order by DATE desc");
		while($res = mysql_fetch_array($result)) {
			if (!in_array($res["ID_operitems"],$now_is_used)) {
				$now_is_used[] = $res["ID_operitems"];
				$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$res["ID_operitems"]."')");
				if ($xxx = mysql_fetch_array($xxx)) {
					if (($xxx["STATE"]=="0") && (!in_array($xxx["ID"],$usedoper))) $collected[] = $xxx["ID"];
				}
			}
		}

		if (count($collected)>1) {
	   // ПОДПИСЬ ///////////////////////////////////////////////////////////////
		echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n";
			echo "<h2>Продолжить работу над операциями</h2>";
		echo "</td><td style='text-align: right;'>";
		echo "</td></tr></table><br>";

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td><a href='".$back_url."' style='float:left;'>Назад</a>Заказ / ДСЕ</td>\n";
		echo "<td width='20'>№<br>МТК</td>\n";
		echo "<td width='180'>Операция</td>\n";
		echo "<td width='100'>Оборудование</td>\n";
		echo "<td width='50'>На заказ,<br>Н/Ч</td>\n";
		echo "<td width='24'><input type='submit' value='Добавить' style='width:85px;'><br><input type='submit' value='выделенные' style='width:85px;'></td>\n";
		echo "</tr>\n";
		echo "</thead>";

		echo "<tbody>";
		for ($j=1;$j < count($collected);$j++) {
			OpenLastID($collected[$j]);
		}
		echo "</tbody>";

		echo "</table>\n";
		echo "<br><br><br>";
		}
}

	function OpenLastID($i) {
		global $pageurl, $db_prefix, $editing;

		$item = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$i."')");
		$item = mysql_fetch_array($item);

	   // Цвет
		echo "<tr>";

	   // Заказ / ДСЕ
		$izd = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$item["ID_zakdet"]."')");
		$izd = mysql_fetch_array($izd);
		$zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$izd["ID_zak"]."')");
		$zak = mysql_fetch_array($zak);

		echo "<td class='Field' style='text-align: left;'><b style='margin-right: 10px;'>".FVal($zak,"db_zak","TID")." ".$zak["NAME"]."</b> ".$zak["DSE_NAME"]." / ".$izd["OBOZ"]." ".$izd["NAME"]."</td>";

	   // №
		Field($item,"db_operitems","ORD",false,"","","");

	   // Операция
		Field($item,"db_operitems","ID_oper",false,"","","");

	   // Оборудование
		Field($item,"db_operitems","ID_park",false,"","","");

	   // На заказ
		Field($item,"db_operitems","NORM_ZAK",false,"","","");

	   // Действие
		echo "<td class='Field'>";
		if (db_adcheck("db_zadan")) echo "<input type='checkbox' name='zak_zad[]' value='".$i."'>";
		echo "</td>";

		echo "</tr>\n";
	}
//////////  </></></>	

if ($edittrigh == '1'){

// ЗАГОЛОВОК ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
echo "<table style='width: 100%; padding: 0px;' cellpadding='0' cellspacing='0'><tr><td style='text-align: left;'>\n
<h2>Добавление новых заданий</h2>
</td><td style='text-align: right;'>
</td></tr></table><br>";

// ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1832px;' border='1' cellpadding='0' cellspacing='0'>\n
<thead>
<tr class='first'>\n
<td colspan='2'>Заказ<br><a href='".$back_url."' style='float:left;'>Назад</a></td>\n
<td>Приор.</td>\n
<td>Наименование заказа</td>\n
<td>Входящий узел</td>\n
<td>ДСЕ</td>\n
<td colspan='2'>(№) Операция</td>\n
<td>Оборудование</td>\n
<td>КЗ<br>Н/Ч</td>\n
<td>ВП<br>Н/Ч</td>\n
<td>КСЗ<br>Н/Ч</td>\n
<td>КО<br>Н/Ч</td>\n
<td>Задел<br>(ШТ.)</td>\n
<td>Сообщение ПП</td>\n
<td><input type='submit' value='Добавить' style='width:85px;'><br><input type='submit' value='выделенные' style='width:85px;'></td>\n
</tr>\n
</thead><tbody>";

// перезначение TID у заказа (префикс)
$res_zak_tip = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
// перезначение TID у операции (префикс)
$res_oper_tip = array(" ", "Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");
// начальные переменные
$cur_numf = 0;
$background_ind = 0;
$cwres1 = 0;
$dse_name_arr = array();
// взятие всех ДСЕ для наполнения массива их именами
$res1_1 = dbquery("SELECT 
okb_db_zak.DSE_NAME, okb_db_zakdet.OBOZ, okb_db_zakdet.PID, okb_db_zakdet.ID 
FROM okb_db_zakdet 
INNER JOIN okb_db_zak ON okb_db_zakdet.ID_zak = okb_db_zak.ID 
where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ='1') and (okb_db_zak.PRIOR>'0'))");
// получаю массив: все заказы в работе где PRIOR>0 --> все ДСЕ --> все МТК
$res1 = dbquery("SELECT 
okb_db_operitems.ID, okb_db_operitems.ID_park, okb_db_operitems.ID_oper, 
okb_db_zak.TID, okb_db_zak.NAME, okb_db_zak.PRIOR, okb_db_zak.DSE_NAME, 
okb_db_zakdet.OBOZ, okb_db_zakdet.NAME, okb_db_operitems.ORD, 
okb_db_oper.NAME, okb_db_park.MARK, okb_db_operitems.NUM_ZAK, 
okb_db_operitems.NORM_ZAK, okb_db_operitems.FACT2_NUM, okb_db_operitems.FACT2_NORM, 
okb_db_operitems.KSZ_NUM, okb_db_operitems.KSZ2_NUM, okb_db_oper.TID, 
okb_db_zakdet.ID, okb_db_zak.ID, okb_db_operitems.MSG_INFO, okb_db_zakdet.PID 
FROM okb_db_operitems 
INNER JOIN okb_db_zakdet ON okb_db_operitems.ID_zakdet = okb_db_zakdet.ID 
INNER JOIN okb_db_zak ON okb_db_operitems.ID_zak = okb_db_zak.ID 
INNER JOIN okb_db_oper ON okb_db_operitems.ID_oper = okb_db_oper.ID 
INNER JOIN okb_db_park ON okb_db_operitems.ID_park = okb_db_park.ID 
where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ='1') and (okb_db_zak.PRIOR>'0')) order by okb_db_zak.PRIOR, okb_db_zak.ORD, okb_db_zakdet.OBOZ, okb_db_operitems.ORD");

//////////////// ФУНКЦИОНАЛ
// наполнение массива именами ДСЕ
while ($wres1_1 = mysql_fetch_row($res1_1)){
	$dse_name_arr[$wres1_1[3]]=$wres1_1[1]."</b><br>".$wres1_1[0];
}
// перебираю все записи выбранного массива
while ($wres1 = mysql_fetch_row($res1)){
// проверяю если длина значения ксз_нум и ксз2_нум и факт_норм и факт_нум и задел = или < 0 то значение = "0"
if (strlen($wres1[16])>0) {
	$ksz_num = $wres1[16];
}else{
	$ksz_num = "0";
}
if (strlen($wres1[17])>0) {
	$ksz2_num = $wres1[17];
}else{
	$ksz2_num = "0";
}
if (strlen($wres1[14])>0) {
	$vp_num = $wres1[14];
}else{
	$vp_num = "0";
}
if (strlen($wres1[15])>0) {
	$vp_norm = $wres1[15];
}else{
	$vp_norm = "0";
}
// проверка если текущая операция это следующая после предыдущей, в выбранном заказе -> ДСЕ
if ($wres1[20] == $cur_zak){
	if ($wres1[19] == $cur_dse){
		if ($wres1[0] > $cur_oper){
			$zadel = $cur_numf - $wres1[14] - $ksz_num;
		}else{
			$zadel = "0";
		}
	}else{
		$zadel = "0";
	}
}else{
	$zadel = "0";
}
// перебираю все оборудования у выбранного ресурса
for ($fres_par = 0; $fres_par < count($res_par); $fres_par++){
	if ($res_par[$fres_par]==$wres1[1]){
		// перебираю все операции у выбранного ресурса
		for ($fres_op = 0; $fres_op < count($res_op); $fres_op++){
			if ($res_op[$fres_op]==$wres1[2]){
				if (($wres1[1]==$res_par[$fres_par]) and ($wres1[2]==$res_op[$fres_op])){
					// выводить строки где количество по факту < количество на заказ
					if ($wres1[14]<$wres1[12]){
////////////////////// вывод самой таблицы

					$cwres1 = $cwres1 + 1;
					// формирование гиперссылки на задел
					if ($zadel <> 0) {
						$zadel_link = "<a href='index.php?do=show&formid=116&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs."&p5=".$wres1[0]."&p6=".$cur_oper."&p3=".$vp_num."&p4=".$cur_numf."'><b>".$zadel."</b></a>";
					}else{
						$zadel_link = $zadel;
					}
					if ($vp_num <> 0) {
						$vp_num_link = "<a href='index.php?do=show&formid=126&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs."&p3=".$wres1[0]."'><b>".$vp_num."<br>".$vp_norm."</b></a>";
					}else{
						$vp_num_link = $vp_num."<br>".$vp_norm;
					}
					if ($ksz_num <> 0) {
						$ksz_num_link = "<a href='index.php?do=show&formid=129&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs."&p3=".$wres1[0]."'><b>".$ksz_num."<br>".$ksz2_num."</b></a>";
					}else{
						$ksz_num_link = $ksz_num."<br>".$ksz2_num;
					}
					// чередование цвета через заказ
					if ($wres1[20] !== $cur_zak2) {
						$background_ind = $background_ind + 1;
						if ($background_ind == 1) { $background = "fff";}
						if ($background_ind == 2) { $background = "b5f3fe"; $background_ind = 0;}
					}
					// проверка на повторение
					if ($wres1[0]!==$cur_oper2){
					$msg_pp = explode("||", $wres1[21]);
					if ($msg_pp[1]) { $disabl_1 = "disabled"; $disabl_2 = "none"; $disabl_3=$msg_pp[0]; $disabl_4="Field";}else{ $disabl_1 = ""; $disabl_2 = "display"; $disabl_3=$wres1[21]; $disabl_4="rwField ntabg";}
					echo "<tr onmouseover='this.style.background=\"#D7DFEB\"' onmouseout='this.style.background=\"#".$background."\"' style='background:#".$background."'><td class='Field' width='30px'>".$res_zak_tip[$wres1[3]]."</td>
					<td class='Field' style='width:75px;' >".$wres1[4]."</td>
					<td class='Field' style='width:45px;'>".$wres1[5]."</td>
					<td style='width:300px;' class='Field'><b>".$wres1[6]."</b></td>
					<td style='width:200px;' class='Field'><b>".$dse_name_arr[$wres1[22]]."</td>
					<td style='width:200px;' class='Field'><b>".$wres1[7]."</b><br>".$wres1[8]."</td>
					<td class='Field' width='25px'>".$wres1[9]."</td>
					<td class='Field' width='130px'>".$wres1[10]."<br>".$res_oper_tip[$wres1[18]]."</td>
					<td style='width:150px;' class='Field'>".$wres1[11]."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$wres1[12]."<br>".$wres1[13]."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$vp_num_link."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$ksz_num_link."</td>
					<td style='width:45px;text-align:center;' class='Field'>".($wres1[12]-$vp_num-$ksz_num)."<br>".round(($wres1[13]-$vp_norm-$ksz2_num),2)."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$zadel_link."</td>
					"./*"<td class='rwField ntabg'><textarea style='resize:none;' name='db_operitems_MSG_INFO_edit_".$wres1[0]."' onchange='vote(this , \"db_edit.php?db=db_operitems&field=MSG_INFO&id=".$wres1[0]."&value=\"+TXT(this.value));'>".$wres1[21]."</textarea></td>*/"
					<td style='width:190px;' class='".$disabl_4."'></td>
					<td class='Field xx' width='60px'><input type='checkbox' name='zak_zad[]' value='".$wres1[0]."'></td>
					</tr>";
					$cur_oper2 = $wres1[0];
					$cur_zak2 = $wres1[20];
					}
					}
				}
			}
		}
	}
}
	$cur_zak = $wres1[20];
	$cur_dse = $wres1[19];
	$cur_oper = $wres1[0];
	$cur_numf = $vp_num;
}
// начальные переменные
$cur_numf = 0;
$dse_name_arr = array();
// взятие всех ДСЕ для наполнения массива их именами
$res1_1 = dbquery("SELECT 
okb_db_zak.DSE_NAME, okb_db_zakdet.OBOZ, okb_db_zakdet.PID, okb_db_zakdet.ID 
FROM okb_db_zakdet 
INNER JOIN okb_db_zak ON okb_db_zakdet.ID_zak = okb_db_zak.ID 
where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ='1') and (okb_db_zak.PRIOR='0'))");
// получаю массив: все заказы в работе где PRIOR>0 --> все ДСЕ --> все МТК
$res1 = dbquery("SELECT 
okb_db_operitems.ID, okb_db_operitems.ID_park, okb_db_operitems.ID_oper, 
okb_db_zak.TID, okb_db_zak.NAME, okb_db_zak.PRIOR, okb_db_zak.DSE_NAME, 
okb_db_zakdet.OBOZ, okb_db_zakdet.NAME, okb_db_operitems.ORD, 
okb_db_oper.NAME, okb_db_park.MARK, okb_db_operitems.NUM_ZAK, 
okb_db_operitems.NORM_ZAK, okb_db_operitems.FACT2_NUM, okb_db_operitems.FACT2_NORM, 
okb_db_operitems.KSZ_NUM, okb_db_operitems.KSZ2_NUM, okb_db_oper.TID, 
okb_db_zakdet.ID, okb_db_zak.ID, okb_db_operitems.MSG_INFO, okb_db_zakdet.PID 
FROM okb_db_operitems 
INNER JOIN okb_db_zakdet ON okb_db_operitems.ID_zakdet = okb_db_zakdet.ID 
INNER JOIN okb_db_zak ON okb_db_operitems.ID_zak = okb_db_zak.ID 
INNER JOIN okb_db_oper ON okb_db_operitems.ID_oper = okb_db_oper.ID 
INNER JOIN okb_db_park ON okb_db_operitems.ID_park = okb_db_park.ID 
where ((okb_db_zak.EDIT_STATE = '0') and (okb_db_zak.INSZ='1') and (okb_db_zak.PRIOR='0')) order by okb_db_zak.PRIOR, okb_db_zak.ORD, okb_db_zakdet.OBOZ, okb_db_operitems.ORD");

//////////////// ФУНКЦИОНАЛ
// наполнение массива именами ДСЕ
while ($wres1_1 = mysql_fetch_row($res1_1)){
	$dse_name_arr[$wres1_1[3]]=$wres1_1[1]."</b><br>".$wres1_1[0];
}
// перебираю все записи выбранного массива
while ($wres1 = mysql_fetch_row($res1)){
// проверяю если длина значения ксз_нум и ксз2_нум и факт_норм и факт_нум и задел = или < 0 то значение = "0"
if (strlen($wres1[16])>0) {
	$ksz_num = $wres1[16];
}else{
	$ksz_num = "0";
}
if (strlen($wres1[17])>0) {
	$ksz2_num = $wres1[17];
}else{
	$ksz2_num = "0";
}
if (strlen($wres1[14])>0) {
	$vp_num = $wres1[14];
}else{
	$vp_num = "0";
}
if (strlen($wres1[15])>0) {
	$vp_norm = $wres1[15];
}else{
	$vp_norm = "0";
}
// проверка если текущая операция это следующая после предыдущей, в выбранном заказе -> ДСЕ
if ($wres1[20] == $cur_zak){
	if ($wres1[19] == $cur_dse){
		if ($wres1[0] > $cur_oper){
			$zadel = $cur_numf - $wres1[14] - $ksz_num;
		}else{
			$zadel = "0";
		}
	}else{
		$zadel = "0";
	}
}else{
	$zadel = "0";
}
// перебираю все оборудования у выбранного ресурса
for ($fres_par = 0; $fres_par < count($res_par); $fres_par++){
	if ($res_par[$fres_par]==$wres1[1]){
		// перебираю все операции у выбранного ресурса
		for ($fres_op = 0; $fres_op < count($res_op); $fres_op++){
			if ($res_op[$fres_op]==$wres1[2]){
				if (($wres1[1]==$res_par[$fres_par]) and ($wres1[2]==$res_op[$fres_op])){
					// выводить строки где количество по факту < количество на заказ
					if ($wres1[14]<$wres1[12]){
////////////////////// вывод самой таблицы

					$cwres1 = $cwres1 + 1;
					// формирование гиперссылки на задел
					if ($zadel <> 0) {
						$zadel_link = "<a href='index.php?do=show&formid=116&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs."&p5=".$wres1[0]."&p6=".$cur_oper."&p3=".$vp_num."&p4=".$cur_numf."'><b>".$zadel."</b></a>";
					}else{
						$zadel_link = $zadel;
					}
					if ($vp_num <> 0) {
						$vp_num_link = "<a href='index.php?do=show&formid=126&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs."&p3=".$wres1[0]."'><b>".$vp_num."<br>".$vp_norm."</b></a>";
					}else{
						$vp_num_link = $vp_num."<br>".$vp_norm;
					}
					if ($ksz_num <> 0) {
						$ksz_num_link = "<a href='index.php?do=show&formid=129&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs."&p3=".$wres1[0]."'><b>".$ksz_num."<br>".$ksz2_num."</b></a>";
					}else{
						$ksz_num_link = $ksz_num."<br>".$ksz2_num;
					}
					// чередование цвета через заказ
					if ($wres1[20] !== $cur_zak2) {
						$background_ind = $background_ind + 1;
						if ($background_ind == 1) { $background = "fff";}
						if ($background_ind == 2) { $background = "b5f3fe"; $background_ind = 0;}
					}
					// проверка на повторение
					if ($wres1[0]!==$cur_oper2){
					$msg_pp = explode("||", $wres1[21]);
					if ($msg_pp[1]) { $disabl_1 = "disabled"; $disabl_2 = "none"; $disabl_3=$msg_pp[0]; $disabl_4="Field";}else{ $disabl_1 = ""; $disabl_2 = "display"; $disabl_3=$wres1[21]; $disabl_4="rwField ntabg";}
					echo "<tr onmouseover='this.style.background=\"#D7DFEB\"' onmouseout='this.style.background=\"#".$background."\"' style='background:#".$background."'><td class='Field' width='30px'>".$res_zak_tip[$wres1[3]]."</td>
					<td class='Field' style='width:75px;' >".$wres1[4]."</td>
					<td class='Field' style='width:45px;'>".$wres1[5]."</td>
					<td style='width:300px;' class='Field'><b>".$wres1[6]."</b></td>
					<td style='width:200px;' class='Field'><b>".$dse_name_arr[$wres1[22]]."</td>
					<td style='width:200px;' class='Field'><b>".$wres1[7]."</b><br>".$wres1[8]."</td>
					<td class='Field' width='25px'>".$wres1[9]."</td>
					<td class='Field' width='130px'>".$wres1[10]."<br>".$res_oper_tip[$wres1[18]]."</td>
					<td style='width:150px;' class='Field'>".$wres1[11]."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$wres1[12]."<br>".$wres1[13]."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$vp_num_link."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$ksz_num_link."</td>
					<td style='width:45px;text-align:center;' class='Field'>".($wres1[12]-$vp_num-$ksz_num)."<br>".round(($wres1[13]-$vp_norm-$ksz2_num),2)."</td>
					<td style='width:45px;text-align:center;' class='Field'>".$zadel_link."</td>
					"./*"<td class='rwField ntabg'><textarea style='resize:none;' name='db_operitems_MSG_INFO_edit_".$wres1[0]."' onchange='vote(this , \"db_edit.php?db=db_operitems&field=MSG_INFO&id=".$wres1[0]."&value=\"+TXT(this.value));'>".$wres1[21]."</textarea></td>*/"
					<td style='width:190px;' class='".$disabl_4."'></td>
					<td class='Field xx' width='60px'><input type='checkbox' name='zak_zad[]' value='".$wres1[0]."'></td>
					</tr>";
					$cur_oper2 = $wres1[0];
					$cur_zak2 = $wres1[20];
					}
					}
				}
			}
		}
	}
}
	$cur_zak = $wres1[20];
	$cur_dse = $wres1[19];
	$cur_oper = $wres1[0];
	$cur_numf = $vp_num;
}
			
echo "</table></form>";
}
echo "<br><div class='links'><a href='".$back_url."'>Назад</a><br><br></div>";
$title = "обор.".$smena."см. ".$date;
?>
