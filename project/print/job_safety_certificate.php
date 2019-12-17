<script type="text/javascript" src="/uses/jquery.js"></script>
<style>
span.black
{
  color : black !IMPORTANT;
}
</style>


<script>
// let message = '';

// if (window.jQuery) 
//   message = 'Used jQuery V: ' + jQuery.fn.jquery;
//       else
//         message = 'jQuery not used in krz_calc.js!';

// console.log( message );

//Replace all inputs with spans. Chrome 72 beta сrutch.
window.matchMedia("print").addListener(function() 
{
  $('input').each(function( index, value ) 
            {
              $( value ).replaceWith("<span class='black'>" + $( value ).val() + "</span>").css('color','black');
            });
})

</script>


<?php

function conv( $str )
{
//    return iconv( "Windows-1251",  "UTF-8", $str );
    return $str ;
}

define('MAV_ERP', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database.php');

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

setlocale(LC_TIME, 'russian');

$row = mysql_fetch_assoc(dbquery("
                  SELECT *,
                  okb_db_special.NAME as `SpecialName`,
                  `okb_db_safety_job`.`A10_1` 
                  FROM `okb_db_resurs` 
                  LEFT JOIN `okb_db_shtat` ON `okb_db_shtat`.`ID_resurs` = `okb_db_resurs`.`ID`
									LEFT JOIN `okb_db_safety_job` ON `okb_db_safety_job`.`ID_RESURS` = `okb_db_resurs`.`ID`
									LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_shtat`.`ID_special`
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
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:"Segoe UI";
	panose-1:2 11 5 2 4 2 4 2 2 3;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:8.0pt;
	margin-left:0cm;
	line-height:107%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-link:"Текст выноски Знак";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:9.0pt;
	font-family:"Segoe UI",sans-serif;}
p.ConsPlusNonformat, li.ConsPlusNonformat, div.ConsPlusNonformat
	{mso-style-name:ConsPlusNonformat;
	margin:0cm;
	margin-bottom:.0001pt;
	text-autospace:none;
	font-size:10.0pt;
	font-family:"Courier New";}
p.ConsPlusNormal, li.ConsPlusNormal, div.ConsPlusNormal
	{mso-style-name:ConsPlusNormal;
	margin:0cm;
	margin-bottom:.0001pt;
	text-autospace:none;
	font-size:10.0pt;
	font-family:"Arial",sans-serif;}
span.a
	{mso-style-name:"Текст выноски Знак";
	mso-style-link:"Текст выноски";
	font-family:"Segoe UI",sans-serif;}
.MsoChpDefault
	{font-family:"Calibri",sans-serif;}
.MsoPapDefault
	{margin-bottom:8.0pt;
	line-height:107%;}
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:2.0cm 42.5pt 2.0cm 35.45pt;}
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
	  margin-left:-18px;
  }
    
  body {margin-left:50px;}
  
  }
-->
</style>

</head>

<body lang=RU>

<div class=WordSection1>


<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=725
 style='width:543.9pt;margin-left:-7.35pt;border-collapse:collapse;border:none'>
 <tr>
  <td width=350 rowspan=15 style='border:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><img width=320
   id="Рисунок 2"
  src="/project/print/job_safety_certificate.png"></p>
  <p class=ConsPlusNonformat style='text-align:justify'>&nbsp;</p>
  <p class=ConsPlusNonformat style='text-align:justify'>&nbsp;</p>
  <p class=ConsPlusNonformat style='text-align:justify'>&nbsp;</p>
  <p class=ConsPlusNonformat align=center style='text-align:center'><b><span
  style='font-size:12.0pt;font-family:"Times New Roman",serif'>УДОСТОВЕРЕНИЕ</span></b></p>
  <p class=ConsPlusNonformat align=center style='text-align:center'><span
  style='font-family:"Times New Roman",serif'>о проверке знания</span></p>
  <p class=ConsPlusNonformat align=center style='text-align:center'><span
  style='font-family:"Times New Roman",serif'>требований охраны труда</span></p>
  </td>
  <td width=16 rowspan=15 style='width:11.8pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:none;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat align=center style='text-align:center'>&nbsp;</p>
  </td>
  <td width=350 valign=top style='border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat align=center style='text-align:center'><b><span
  style='font-family:"Times New Roman",serif'>&nbsp;</span></b></p>
  <p class=ConsPlusNonformat align=center style='text-align:center'><b><span
  style='font-family:"Times New Roman",serif'>УДОСТОВЕРЕНИЕ </span></b><b><span
  lang=EN-US style='font-family:"Times New Roman",serif'>RU</span></b><b><span
  style='font-family:"Times New Roman",serif'> 1</span></b><span
  style='font-family:"Times New Roman",serif'> № <?php echo conv( $row['A10_1'] ); ?></span></p>
  </td>
 </tr>
 <tr style='height:19.05pt'>
  <td width=369 valign=bottom style='width:276.7pt;border:none;border-right:
  solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:19.05pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>Выдано: <?php echo conv($row['FF']) . ' ' . conv($row['II']) . ' ' . conv($row['OO']); ?></span></p>
  </td>
 </tr>


 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>в том, что он  (она)  прошел(а)  проверку знания
  требований</span></p>
  </td>
 </tr>
 <tr style='height:11.75pt'>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:11.75pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>охраны труда по итогам обучения по охране труда</span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>продолжительностью 40 часов.</span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>Место работы: ООО «ОКБ Микрон».</span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>Должность: <?php echo conv( $row['SpecialName'] )?></span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>Протокол заседания комиссии по   проверке знания
  требований охраны труда             </span></p>
  </td>
 </tr>
 <tr style='height:17.5pt'>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.5pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>от &quot;&nbsp;<input style="width:17px" type="text"/>&quot; <select style="width:70px;">
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
</select> <span id="year">&nbsp;&nbsp;20<input style="width:18px" type="text" value="<?php echo substr(0, 2, date('Y')); ?>"/>года</span> № <?php echo conv( $row['A8_1']); ?></span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>&nbsp;</span></p>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>Действительно до &quot;&nbsp;<input style="width:17px" type="text"/>&quot; <select style="width:70px;">
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
</select> <span id="year">&nbsp;&nbsp;20<input style="width:18px" type="text" value="<?php echo substr(0, 2, date('Y')); ?>"/>года</span></span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>&nbsp;</span></p>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>Председатель комиссии Матонин В.В. ___________</span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>&nbsp;</span></p>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>М.П.</span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>&nbsp;</span></p>
  </td>
 </tr>
 <tr>
  <td width=369 valign=top style='width:276.7pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=ConsPlusNonformat style='text-align:justify'>&quot;&nbsp;<input style="width:16px" type="text"/>&nbsp;&quot; <select style="width:70px;">
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
</select> <span id="year">20<input style="width:18px" type="text" value="<?php echo substr(0, 2, date('Y')); ?>"/>года</span></p>
  <p class=ConsPlusNonformat style='text-align:justify'><span style='font-family:
  "Times New Roman",serif'>            </span></p>
  </td>
 </tr>
</table>

</div>

</body>

</html>
