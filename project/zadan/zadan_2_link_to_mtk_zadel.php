<?php
$ID_resurs = $_GET["p2"];
$fact1 = $_GET["p3"];
$fact2 = $_GET["p4"];
$ID_curoper = $_GET["p5"];
$ID_undoper = $_GET["p6"];
$zadel = $_GET["p7"];
$smena = $_GET["p1"];
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);
$back_url = "index.php?do=show&formid=112&p0=".$pdate."&p1=".$smena."&p2=".$ID_resurs;

// предыдущая операция
$res3 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$ID_curoper."')");
$res3_1 = mysql_fetch_array($res3);
$res1 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$res3_1['ID_zak']."')");
$res1_1 = mysql_fetch_array($res1);
$res2 = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$res3_1['ID_zakdet']."')");
$res2_1 = mysql_fetch_array($res2);
$res1_tip = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
$res3_tip = array(" ", "Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");
$res4 = dbquery("SELECT * FROM ".$db_prefix."db_oper where (ID='".$res3_1['ID_oper']."')");
$res4_1 = mysql_fetch_array($res4);
$res5 = dbquery("SELECT * FROM ".$db_prefix."db_park where (ID='".$res3_1['ID_park']."')");
$res5_1 = mysql_fetch_array($res5);
$res_us_1 = dbquery("SELECT * FROM ".$db_prefix."users where (ID='".$res3_1['ID_user']."')");
$res_us_1 = mysql_fetch_array($res_us_1);

// текущая операция
$res8 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$ID_undoper."')");
$res8_1 = mysql_fetch_array($res8);
$res6 = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID='".$res8_1['ID_zak']."')");
$res6_1 = mysql_fetch_array($res6);
$res7 = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$res8_1['ID_zakdet']."')");
$res7_1 = mysql_fetch_array($res7);
$res6_tip = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
$res8_tip = array(" ", "Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");
$res9 = dbquery("SELECT * FROM ".$db_prefix."db_oper where (ID='".$res8_1['ID_oper']."')");
$res9_1 = mysql_fetch_array($res9);
$res10 = dbquery("SELECT * FROM ".$db_prefix."db_park where (ID='".$res8_1['ID_park']."')");
$res10_1 = mysql_fetch_array($res10);
$res_us_2 = dbquery("SELECT * FROM ".$db_prefix."users where (ID='".$res8_1['ID_user']."')");
$res_us_2 = mysql_fetch_array($res_us_2);

echo "
<h4><a href='".$back_url."'>Назад</a></h4>
<h2>Заказ: ".$res1_tip[$res1_1['TID']]." ".$res1_1['NAME']."</h2>
<h2>ДСЕ : ".$res2_1['NAME']."</h2>
<h2>Чертёж : ".$res2_1['OBOZ']."</h2><br><br>
<h4>Маршрутно-технологическая карта</h4><br>
</form><form>
<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: rgb(0, 0, 0); width: 1200px; padding: 0px;' border='1' cellpadding='0' cellspacing='0'>
	<thead>
	<tr class='first'>
		<td width='100'>Информация</td>
		<td width='30'>№</td>
		<td>Операция</td>
		<td>Оборудование</td>
		<td width='60'>Норма на ед., мин</td>
		<td width='60'>Норма п. з., мин</td>
		<td width='60'>Задел, шт</td>
		<td width='60'>На заказ, шт</td>
		<td width='60'>На заказ, Н/Ч</td>
		<td width='60'>Выполнено, Н/Ч /(шт.)</td>
		<td width='60'>Фактич. время, ч</td>
	</tr>
	</thead>
	<tbody>
	<tr>
<td rowspan='3' style='max-width: 100px;' class='Field'><br>(Предыдущая)</td>
<td rowspan='3' style='max-width: 30px;' class='Field'>".$res8_1['ORD']."</td>
<td class='Field'>".$res9_1['NAME']." - ".$res8_tip[$res9_1['TID']]."</td>
<td class='Field'>".$res10_1['MARK']."</td>
<td style='max-width: 60px;' class='Field'>".$res8_1['NORM']."</td>
<td style='max-width: 60px;' class='Field'>".$res8_1['NORM_2']."</td>
<td style='max-width: 60px;' class='Field'>".$res8_1['NUM_ZADEL']."</td>
<td style='max-width: 60px;' class='Field'>".$res8_1['NUM_ZAK']."</td>
<td style='max-width: 60px;' class='Field'>".$res8_1['NORM_ZAK']."</td>
<td style='max-width: 60px;' class='Field'>".$res8_1['NORM_FACT']." / <b style='color:blue'>".$fact2."</b></td>
<td style='max-width: 60px;' class='Field'>".$res8_1['FACT']."</td>
	</tr>
	<tr>
<td colspan='8' class='Field'><table><tbody><tr><td width='7px;'>Параметры:<span style='margin-left: 10px;'> </span></td><td>".$res8_1['MORE']."</td></tr></tbody></table></td>";
if ($res8_1['BRAK']=='0') $brak = "";
if ($res8_1['BRAK']>'0') $brak = $res8_1['BRAK'];
echo "<td style='max-width: 60px;' class='Field'>".$brak."</td>
	</tr>
	<tr>
<td class='Field' colspan='9' style='background: #ddd; text-align: right;'>";
if ($res8_1['ID_user']=='0') $logg = "Никем";
if ($res8_1['ID_user']>'0') $logg = $res_us_2['FIO']." ".Date("d.m.Y H:i",$res8_1['ETIME']);
echo "Обновлено: <b> ".$logg."</b></td>
	</tr>

	<tr>
<td rowspan='3' style='max-width: 100px;' class='Field'><br>(Текущая)</td>
<td rowspan='3' style='max-width: 30px;' class='Field'>".$res3_1['ORD']."</td>
<td class='Field'>".$res4_1['NAME']." - ".$res3_tip[$res4_1['TID']]."</td>
<td class='Field'>".$res5_1['MARK']."</td>
<td style='max-width: 60px;' class='Field'>".$res3_1['NORM']."</td>
<td style='max-width: 60px;' class='Field'>".$res3_1['NORM_2']."</td>
<td style='max-width: 60px;' class='Field'>".$res3_1['NUM_ZADEL']."</td>
<td style='max-width: 60px;' class='Field'>".$res3_1['NUM_ZAK']."</td>
<td style='max-width: 60px;' class='Field'>".$res3_1['NORM_ZAK']."</td>
<td style='max-width: 60px;' class='Field'>".$res3_1['NORM_FACT']." / <b style='color:red'>".$fact1."</b></td>
<td style='max-width: 60px;' class='Field'>".$res3_1['FACT']."</td>
	</tr>
	<tr>
<td colspan='8' class='Field'><table><tbody><tr><td width='7px;'>Параметры:<span style='margin-left: 10px;'> </span></td><td>".$res3_1['MORE']."</td></tr></tbody></table></td>";
if ($res3_1['BRAK']=='0') $brak = "";
if ($res3_1['BRAK']>'0') $brak = $res3_1['BRAK'];
echo "<td style='max-width: 60px;' class='Field'>".$brak."</td>
	</tr>
	<tr>
<td class='Field' colspan='9' style='background: #ddd; text-align: right;'>";
if ($res3_1['ID_user']=='0') $logg = "Никем";
if ($res3_1['ID_user']>'0') $logg = $res_us_1['FIO']." ".Date("d.m.Y H:i",$res3_1['ETIME']);
echo "Обновлено: <b> ".$logg."</b></td>
	</tr>
</tbody></table>";
?>