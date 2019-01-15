<?php

define('MAV_ERP', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database.php');

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

setlocale(LC_TIME, 'russian');

/*
$resource = mysql_fetch_assoc(dbquery("SELECT *, okb_db_special.NAME, okb_db_shtat.ID_otdel, otdel.NAME as OtdelNAME FROM `okb_db_resurs`
								LEFT JOIN `okb_db_safety_job` ON `okb_db_safety_job`.`ID_RESURS` = `okb_db_resurs`.`ID`
								LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_resurs`.`ID_special`
								LEFT JOIN `okb_db_shtat` ON `okb_db_shtat`.`ID_resurs` = `okb_db_resurs`.`ID`
								LEFT JOIN `okb_db_otdel` ON `okb_db_otdel`.`ID` = `okb_db_shtat`.`ID_otdel`
								LEFT JOIN `okb_db_otdel` otdel ON otdel.`ID` = `okb_db_otdel`.`PID`
								WHERE `okb_db_resurs`.`ID` = " . (int) $_GET['resource_id']));
*/

$resource = mysql_fetch_assoc(dbquery("
                SELECT *, 
                `okb_db_resurs`.`FF`,
                `okb_db_resurs`.`II`,
                `okb_db_resurs`.`OO`,
                okb_db_special.NAME AS speciality_name, 
                otdel.NAME as department_name 
                FROM `okb_db_resurs`
                LEFT JOIN `okb_db_shtat` ON `okb_db_shtat`.`ID_resurs` = `okb_db_resurs`.`ID`
                LEFT JOIN `okb_db_safety_job` ON `okb_db_safety_job`.`ID_RESURS` = `okb_db_resurs`.`ID`
                LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_shtat`.`ID_special`
                LEFT JOIN `okb_db_otdel` AS otdel ON otdel.ID = okb_db_shtat.ID_otdel
                WHERE `okb_db_resurs`.`ID` = " . (int) $_GET['resource_id']));

?><html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta name=Generator content="Microsoft Word 15 (filtered)">
<title>Разработан на основании</title>
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman",serif;}
p
	{margin-right:0cm;
	margin-bottom:5.95pt;
	margin-left:0cm;
	font-size:12.0pt;
	font-family:"Times New Roman",serif;}
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:1.0cm 42.5pt 2.0cm 2.0cm;}
div.WordSection1
	{page:WordSection1;}
			
	@media print {
  button {
    display: none !important;
  }
  input,
  textarea,select,option {
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
	-webkit-appearance: none;
-moz-appearance: none;
font-size:12.0pt;
	font-family:"Times New Roman",serif;
appearance: none;
border: none; /* If you want to remove the border as well */
background: none;
	font-weight:bold;
  }
  
  select {
  font-weight:normal;
  }
  #year {
	  margin-left:-20px;
  }
    
  body {margin-left:50px;}
  
  }
-->
</style>

</head>

<body lang=RU>

<div class=WordSection1>


<p align=right style='margin-top:0cm;margin-right:-.05pt;margin-bottom:0cm;
margin-left:0cm;margin-bottom:.0001pt;text-align:right'><span style='font-size:
8.0pt'>&nbsp;</span></p>

<p style='margin-top:0cm;margin-right:-.05pt;margin-bottom:0cm;margin-left:
0cm;margin-bottom:.0001pt'><img width=400   id="Рисунок 7"
src="/project/print/safety_card_print.png"></p>
<p align=right style='margin:0cm;margin-bottom:.0001pt;text-align:right'><span
style='font-size:8.0pt'>Приложение 1 к приказу №   ОП 026/17 от 26.06.2017г.</span></p>
<br/>
<br/>
<p align=center style='margin:0cm;margin-bottom:.0001pt;text-align:center'><b><span
style='font-size:14.0pt'>ПРОТОКОЛ № <u><?php echo mb_strtoupper($resource['B7_1'], 'cp1251'); ?></span></u></b></p>

<p align=center style='margin:0cm;margin-bottom:.0001pt;text-align:center'><b><span
style='font-size:11.0pt'>заседания комиссии по проверке знаний требований пожарно-технического минимума</span></b></p>

 
<p align=center style='margin:0cm;margin-bottom:.0001pt;text-align:center'><b>&nbsp;</b></p>

<p class=MsoNormal><b>&nbsp;</b></p>

<p class=MsoNormal><b>«<span style='color:red'><input style="width:18px" type="text"/></span>» <span
style='color:red'><select style="font-weight:bold;width:80px;">
	<option>января</option>
	<option>февраля</option>
	<option>марта</option>
	<option>апреля</option>
	<option>мая</option>
	<option>июня</option>
	<option>июля</option>
	<option>августа</option>
	<option>сентября</option>
	<option>октября</option>
	<option>ноября</option>
	<option>декабря</option>
</select></span><span id="year">&nbsp;&nbsp;20<input style="width:18px" type="text" value="<?php echo substr(0, 2, date('Y')); ?>"/>года</span>
<p class=MsoNormal><b>&nbsp;</b></p>

<p class=MsoNormal><b>&nbsp;</b></p>

<p class=MsoNormal style='line-height:150%'><b>Комиссия в составе: </b></p>

<p class=MsoNormal style='text-align:justify;line-height:150%'><b>Председателя 
- главного инженера ООО «Микрон»  Матонина В.В.</b></p>

<p class=MsoNormal style='text-align:justify;line-height:150%'><b>и членов
комиссии:</b></p>

<p class=MsoNormal style='text-align:justify;line-height:150%'><b>главного механика — Веселкина Е.В.,</b></p>


<p class=MsoNormal style='text-align:justify;line-height:150%'><b>специалиста по охране труда — Абрамович С.А. </b></p>

<p class=MsoNormal style='text-align:justify;line-height:150%'><b>На основании приказа № ОП 026/17  от 26.06.2017г., провела проверку знаний требований пожарно-технического минимума, в объеме, соответствующем профессиональным обязанностям работника и установила:</b></p>

<p class=MsoNormal><b>&nbsp;</b></p>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width=696
 style='width:522.15pt;border-collapse:collapse;border:none'>
 <tr style='height:7.7pt'>
  <td width=126 rowspan=2 style='width:94.3pt;border:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:10.0pt'>Фамилия, </span></b></p>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:10.0pt'>имя, отчество</span></b></p>
  </td>
  <td width=106 rowspan=2 style='width:79.75pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b><span
  style='font-size:10.0pt'>Должность, профессия</span></b></p>
  </td>
  <td width=164 rowspan=2 style='width:123.3pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-5.35pt;text-align:center'><b>&nbsp;</b></p>
  <p class=MsoNormal align=center style='margin-right:-5.35pt;text-align:center'><b><span
  style='font-size:10.0pt'>Место работы</span> </b><b><span style='font-size:
  9.0pt'>(наименование отдела, участка)</span></b></p>
  </td>
  <td width=203 colspan=2 style='width:152.35pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-top:0cm;margin-right:-12.2pt;
  margin-bottom:0cm;margin-left:-.05pt;margin-bottom:.0001pt;text-align:center;
  text-indent:.05pt'><b><span style='font-size:10.0pt'>Отметка о проверке
  знаний</span></b></p>
  </td>
  <td width=97 rowspan=2 style='width:72.45pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-12.2pt;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center;
  text-indent:5.4pt'><b><span style='font-size:10.0pt'>проверяемого</span></b></p>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center;
  text-indent:5.4pt'><b><span style='font-size:8.0pt'>(примечание)</span></b></p>
  </td>
 </tr>
 <tr style='height:40.95pt'>
  <td width=97 style='width:72.45pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:40.95pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:10.0pt'>Результат проверки знаний </span></b></p>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:9.0pt'>(сдал, не сдал)</span></b></p>
  </td>
  <td width=106 style='width:89.85pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:40.95pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:10.0pt'>Причины проверки</span> </b><b><span
  style='font-size:8.0pt'>знаний (перв, повт. и т.п.)</span></b></p>
  </td>
 </tr>
 <tr style='height:11.9pt'>
  <td width=126 style='width:94.3pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:11.9pt'>
  <p class=MsoNormal align=center style='margin-top:0cm;margin-right:-5.4pt;
  margin-bottom:0cm;margin-left:8.8pt;margin-bottom:.0001pt;text-align:center'><b>1</b></p>
  </td>
  <td width=106 style='width:79.75pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:11.9pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b><span
  style='font-size:11.0pt'>2</span></b></p>
  </td>
  <td width=164 style='width:123.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:11.9pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b>3</b></p>
  </td>
  <td width=97 style='width:72.45pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:11.9pt'>
  <p class=MsoNormal align=center style='text-align:center'><b>4</b></p>
  </td>
  <td width=106 style='width:79.85pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:11.9pt'>
  <p class=MsoNormal align=center style='text-align:center'><b>5</b></p>
  </td>
  <td width=97 valign=top style='width:72.45pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:11.9pt'>
  <p class=MsoNormal align=center style='text-align:center'><b>6</b></p>
  </td>
 </tr>
 <tr style='height:55.25pt'>
  <td width=126 style='width:94.3pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal style='margin-top:0cm;margin-right:-5.4pt;margin-bottom:
  0cm;margin-left:8.8pt;margin-bottom:.0001pt'><b><?php echo $resource['FF']; ?></b></p>
  <p class=MsoNormal style='margin-top:0cm;margin-right:-5.4pt;margin-bottom:
  0cm;margin-left:8.8pt;margin-bottom:.0001pt'><b><?php echo $resource['II']; ?></b></p>
  <p class=MsoNormal style='margin-top:0cm;margin-right:-5.4pt;margin-bottom:
  0cm;margin-left:8.8pt;margin-bottom:.0001pt'><b><?php echo $resource['OO']; ?></b></p>
  </td>
  <td width=106 style='width:79.75pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b><?php echo strtolower($resource['speciality_name']); ?></b></p>
  </td>
  <td width=164 style='width:123.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b>
  <?php
/*  
  if ($_GET['resource_id'] == 614 || $_GET['resource_id'] == 613 || $_GET['resource_id'] == 630 || $_GET['resource_id'] == 678) {
	  echo 'производство';
  } else if ($_GET['resource_id'] == 428 || $_GET['resource_id'] == 512 || $_GET['resource_id'] == 496 || $_GET['resource_id'] == 637) {
	echo 'Отдел ИТ';
  } else if ($_GET['resource_id'] == 643) {
	echo 'Служба главного инженера';
  } else if ($_GET['resource_id'] == 674 || $_GET['resource_id'] == 297) {
	echo 'Складское хозяйство';
  } else if(!empty($resource['OtdelNAME'])) {
	  echo $resource['OtdelNAME'];
	  
  } else {
	  echo 'дирекция';
  }

*/
   
  $dep_name = $resource['department_name'];
  $dep_name = preg_replace('/\d\.\d/', '', $dep_name );
  $dep_name = preg_replace('/\.\d/', '', $dep_name );
  $dep_name = preg_replace('/\d\./', '', $dep_name );
  echo $dep_name;
  
  ?></b></p>
  </td>
  <td width=97 style='width:72.45pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='text-align:center'><b>сдал</b></p>
  </td>
  <td width=106 style='width:89.85pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><input style="width:97px;" type="text"/></b></p>
  </td>
  <td width=97 valign=top style='width:72.45pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal><b></b></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b>&nbsp;</b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b><span style='width:210px;display:inline-block'>Председатель комиссии</span>_________________   В.В.
Матонин </b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b><span style='width:210px;display:inline-block'>Специалист по охране труда</span>_________________   С.А. Абрамович</b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b><span style='width:210px;display:inline-block'>Главный механик</span>_________________   Е.В.
Веселкин</b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b>&nbsp;</b></p>

</div>

</body>

</html>
