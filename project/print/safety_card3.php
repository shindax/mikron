<?php

define('MAV_ERP', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database.php');

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

setlocale(LC_TIME, 'russian');

$row = mysql_fetch_assoc(dbquery("SELECT *, `okb_db_resurs`.NAME as ResourceName,okb_db_special.NAME as `SpecialName`,`okb_db_safety_job`.`F3_1`,`okb_db_safety_job`.`F4_1` FROM `okb_db_resurs`
									LEFT JOIN `okb_db_safety_job` ON `okb_db_safety_job`.`ID_RESURS` = `okb_db_resurs`.`ID`
									LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_resurs`.`ID_special`
									WHERE `okb_db_resurs`.`ID` = " . $_GET['id']));

?><html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta name=Generator content="Microsoft Word 15 (filtered)">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:"Segoe UI";
	panose-1:2 11 5 2 4 2 4 2 2 3;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman",serif;}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman",serif;}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman",serif;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-link:"Текст выноски Знак";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:9.0pt;
	font-family:"Segoe UI",sans-serif;}
span.a
	{mso-style-name:"Текст выноски Знак";
	mso-style-link:"Текст выноски";
	font-family:"Segoe UI",sans-serif;}
.MsoChpDefault
	{font-size:10.0pt;}
 /* Page Definitions */
 @page WordSection1
	{size:595.3pt 841.9pt;
	margin:2.0cm 42.5pt 2.0cm 63.0pt;}
div.WordSection1
	{page:WordSection1;}
	
	
	
  select {
  font-weight:normal;
  }
			
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
  }
  
  input {font-size:10.5pt;}
  select {
  font-weight:normal;
  }
  #year {
	  margin-left:-28px;
  }
    
  body {margin-left:50px;margin:0;}
  
  }
-->
</style>

</head>

<body lang=RU>

<table>
<tr><td colspan=2 style='font-weight:bold;text-align:center;'>Протокол № <?php echo $row['F3_1'] ?>эл<br/>
проверки знаний правил работы в электроустановках<br/><br/>
</td></tr>



<tr>
	<td style="width:450px">Дата проверки</td>
	<td style="border-bottom:1px solid #000"><div style="margin-left:60px;display:flex;text-align:center;">

  <select style="float:left">
  <?php
  
  for ($i = 1; $i <= 31; ++$i) {
	  echo '<option>' . sprintf('%02d', $i) . '</option>';
  }
  
  ?>
    </select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <select style="float:left">

	<option>января</option>
	<option>февраля</option>
	<option>марта</option>
	<option>апреля</option>
	<option>мая</option>
	<option>июня</option>
	<option>июля</option>
	<option>августа</option>
	<option>сентябрь</option>
	<option>октябрь</option>
	<option>ноябрь</option>
	<option>декабрь</option>
  </select>

  <select>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option selected>2017</option>
  <option>2018</option>
  <option>2019</option>
  </select>
  г.
 </div> </td>
</tr>
<tr>
	<td style="width:450px">Причина проверки</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  <select>
  <option>первичная</option>
  <option>очередная</option>
  <option>внеплановая</option>
  </select></td>
</tr>
<tr>
	<td style="width:450px">Комиссия<br/>в составе:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	ООО «ОКБ Микрон»
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(наименование комиссии)
	</td>
</tr>
<tr>
	<td style="width:450px">Председатель комиссии</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	Главный инженер Матонин В.В.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(должность, фамилия, инициалы)
	</td>
</tr>
<tr>
	<td style="width:450px">Члены комиссии</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	Главный механик Веселкин Е.В.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(должность, фамилия, инициалы)
	</td>
</tr>


<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	Специалист по охране труда Абрамович С. А.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(должность, фамилия, инициалы)
	</td>
</tr>
<tr>
	<td colspan=2>провела проверку знаний нормативных документов и инструкций, в соответствии с занимаемой должностью</td>
</tr>

<tr>
	<td colspan=2><b>Проверяемый:</b></td>
</tr>

<tr>
	<td style="width:450px">Фамилия, имя, отчество</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	<?php echo $row['FF'] . ' ' . $row['II'] . ' ' . $row['OO']; ?>
	</td>
</tr>

<tr>
	<td style="width:450px">Место работы</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	ООО «ОКБ Микрон»

	</td>
</tr>

<tr>
	<td style="width:450px">Должность (профессия)</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
 <?php echo $row['SpecialName'] ?>
	</td>
</tr>

<tr>
	<td style="width:450px">Дата предыдущей проверки</td>
	<td style="border-bottom:1px solid #000"><div style="margin-left:60px;display:flex;text-align:center;">

  <select style="float:left">
  <?php
  
  for ($i = 1; $i <= 31; ++$i) {
	  echo '<option>' . sprintf('%02d', $i) . '</option>';
  }
  
  ?>
    </select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <select style="float:left">

	<option>января</option>
	<option>февраля</option>
	<option>марта</option>
	<option>апреля</option>
	<option>мая</option>
	<option>июня</option>
	<option>июля</option>
	<option>августа</option>
	<option>сентябрь</option>
	<option>октябрь</option>
	<option>ноябрь</option>
	<option>декабрь</option>
  </select>

  <select>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option selected>2017</option>
  <option>2018</option>
  <option>2019</option>
  </select>
  г.
 </div> </td>
</tr>
<tr>
	<td style="width:450px">Оценка, группа по электробезопасности</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
  <select>
  <option>II до 1000В</option>
  <option>III до 1000В</option>
  <option>IV до 1000В</option>
  <option>III до и выше 1000В</option>
  <option>IV до и выше 1000В</option>
  <option>V до и выше 1000В</option>

  </select>
  
	</td>
</tr>
<tr>
	<td><b>Результаты проверки знаний:</b>
	</td>
</tr>
<tr>
	<td style="width:450px">По устройству электроустановок и технической эксплуатации:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
удовл.
	</td>
</tr>
<tr>
	<td style="width:450px">По охране труда:  </td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
удовл.
	</td>
</tr>
<tr>
	<td style="width:450px">По пожарной безопасности:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
удовл.
	</td>
</tr>
<tr>
	<td style="width:450px">Других правил и инструкций органов государственного надзора:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
нет
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(наименование правил)
	</td>
</tr>


<tr>
	<td><br/><b>Заключение комиссии:</b>
	</td>
</tr>

<tr>
	<td style="width:450px">Общая оценка:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
удовлетворительно
	</td>
</tr>


<tr>
	<td style="width:450px">Группа по электробезопасности:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
 <select>
  <option>II до 1000В</option>
  <option>III до 1000В</option>
  <option>IV до 1000В</option>
  <option>III до и выше 1000В</option>
  <option>IV до и выше 1000В</option>
  <option>V до и выше 1000В</option>

  </select>	</td>
</tr>


<tr>
	<td style="width:450px">Продолжительность дублирования</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
не требуется
	</td>
</tr>

<tr>
	<td style="width:450px">Допущен к работе в качестве:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
<select>

<option>оперативно-ремонтного персонала</option>
<option>административно-технического персонала</option>
<option>ремонтного персонала</option>
<option>оперативного персонала</option>
<option>технологического персонала</option>
<option>электротехнологического персонала</option>
<option>электротехнического</option>

</select>	</td>
</tr>
<tr>
	<td style="width:450px">Дата следующей проверки</td>
	<td style="border-bottom:1px solid #000"><div style="margin-left:60px;display:flex;text-align:center;">

  <select style="float:left">
  <?php
  
  for ($i = 1; $i <= 31; ++$i) {
	  echo '<option>' . sprintf('%02d', $i) . '</option>';
  }
  
  ?>
    </select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <select style="float:left">

	<option>января</option>
	<option>февраля</option>
	<option>марта</option>
	<option>апреля</option>
	<option>мая</option>
	<option>июня</option>
	<option>июля</option>
	<option>августа</option>
	<option>сентябрь</option>
	<option>октябрь</option>
	<option>ноябрь</option>
	<option>декабрь</option>
  </select>

  <select>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option selected>2017</option>
  <option>2018</option>
  <option>2019</option>
  </select>
  г.
 </div> </td>
</tr>
<tr>
	<td><b>Подписи:</b>
	</td>
</tr>
<tr>
	<td style="width:450px">Председатель комиссии</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
Матонин В. В.
	</td>
</tr>
<tr>
	<td style="width:450px">Члены комиссии</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
Веселкин Е. В.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
Абрамович С. А.
	</td>
</tr>
<tr><td>&nbsp;</td><td></td></tr>
<tr>
	<td style="width:450px">С заключением комиссии ознакомлен:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
<?php echo $row['SpecialName'] . ' &nbsp;' . $row['ResourceName']; ?>
	</td>
</tr>
<tr>
	<td style="width:450px">Удостоверение № <?php echo $row['F4_1'] ?> выдано на руки</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
 
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(подпись, дата)
	</td>
</tr>

</table>



</body>

</html>
