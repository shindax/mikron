<?php

define('MAV_ERP', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database.php');

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

if (isset($_GET['set_print_id'])) {
	dbquery("UPDATE `okb_db_koop_req` SET `PRINT_ID` = '" . $_GET['set_print_id'] . "' WHERE `ID` = " . $_GET['id']);
	
	die();
}

$data = mysql_fetch_assoc(mysql_query("SELECT `okb_db_koop_req`.*,`okb_db_zak`.`NAME` as `OrderName` FROM `okb_db_koop_req`
										LEFT JOIN `okb_db_zak` ON `okb_db_zak`.`ID` = `okb_db_koop_req`.`ID_zak`
										WHERE `okb_db_koop_req`.`ID` = " . (int) $_GET['id']));

$print_id = $data['PRINT_ID'];
										
$dse = mysql_fetch_assoc(mysql_query("SELECT * FROM `okb_db_zakdet` WHERE `OBOZ` LIKE '%" . $data['OBOZ'] . "%' LIMIT 1"));
		
	$mat =  mysql_fetch_assoc(mysql_query("SELECT *,okb_db_mat.OBOZ as MaterialName,okb_db_sort.OBOZ as MaterialSort  FROM okb_db_zn_zag
LEFT JOIN 
okb_db_mat ON okb_db_mat.ID = okb_db_zn_zag.ID_mat
LEFT JOIN okb_db_sort ON okb_db_sort.ID = okb_db_zn_zag.ID_sort
			WHERE ID_zakdet = " . $dse['ID'] ));
													
$date = $data['CDATE'];										
$year = substr( $date, 0, 4 );
$month = substr( $date, 4, 2 );
$day = substr( $date, 6, 2 );

$months = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');										
/*echo '<pre>';

print_r($data);
print_r($dse);
print_r($mat);
*/
?><html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta name=Generator content="Microsoft Word 15 (filtered)">
<script src="/uses/jquery.js"></script>
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:8.0pt;
	margin-left:0cm;
	line-height:107%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
.MsoChpDefault
	{font-family:"Calibri",sans-serif;}
.MsoPapDefault
	{margin-bottom:8.0pt;
	line-height:107%;}
@page WordSection1
	{size:841.9pt 595.3pt;
	margin:3.0cm 2.0cm 42.5pt 2.0cm;}
div.WordSection1
	{page:WordSection1;}
	textarea {resize: none}
			
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
	font-weight:normal;
  }
  
  #k {
	  font-weight:bold;
	  font-size:11pt;
  }
  
  select {
  font-weight:normal;
  }
  #year {
	  margin-left:-20px;
  }
  
  }
  
.MsoNormalTable
{
}  
-->
</style>

</head>

<body lang=RU>

<div class=WordSection1>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 align=left
 width=940 style='width:750.25pt;border-collapse:collapse;margin-left:6.75pt;
 margin-right:6.75pt'>
 <tr style='height:30.75pt'>
  <td width=237 nowrap colspan=2 style='width:178.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:30.75pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b><span style='color:black'>Заявка на кооперацию</span></b></p>
  </td>
  <td width=123 style='width:92.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:30.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></b></p>
  </td>
  <td width=96 style='width:72.25pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:30.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></b></p>
  </td>
  <td width=107 style='width:80.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:30.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></b></p>
  </td>
  <td width=115 style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:30.75pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:30.75pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:30.75pt'></td>
 </tr>
 <tr style='height:33.75pt'>
  <td width=237 colspan=2 style='width:178.0pt;border:none;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:33.75pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b><span style='color:black'>ЗАКАЗ  <?php echo $data['OrderName']; ?></span></b></p>
  </td>
  <td id="init1" width=123 style='width:92.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:33.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>Инициатор</span></b></p>
  </td>
  <td width=96 style='width:72.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:33.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>Отправитель</span></b></p>
  </td>
  <td width=107 style='width:80.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:33.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>Получатель</span></b></p>
  </td>
  <td width=115 style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:33.75pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:33.75pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:33.75pt'></td>
 </tr>
 <tr style='height:15.75pt'>
  <td width=237 nowrap colspan=2 style='width:178.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b><span style='color:black'>№ К-<?php echo substr(date('Y'), 2, 2); ?>-<input type="text" size="1" id="k" value="<?php echo $print_id; ?>" style="width:40px"/> от &quot;<?php echo $day ?>&quot; <?php echo $months[(int)$month] ?> <?php echo $year ?></span></b></p>
  </td>
  <td width=123 style='width:92.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>ПДО</span></b></p>
  </td>
  <td width=96 style='width:72.25pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>Производство</span></b></p>
  </td>
  <td width=107 style='width:80.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:10.0pt;
  color:black'>ОВК</span></b></p>
  </td>
  <td width=115 style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
 </tr>
 <tr style='height:15.0pt'>
  <td width=51 nowrap valign=bottom style='width:38.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=187 nowrap style='width:140.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=123 nowrap valign=bottom style='width:92.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=96 nowrap valign=bottom style='width:72.25pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=107 nowrap valign=bottom style='width:80.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=115 nowrap valign=bottom style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
 </tr>
 <tr style='height:15.0pt'>
  <td width=51 nowrap valign=bottom style='width:38.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td  id="init2"  width=309 nowrap colspan=2 style='width:232.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b><i><span style='font-size:9.0pt;color:black'>Инициатор</span></i></b><span
  style='font-size:9.0pt;color:black'>   Инженер ПДО
  ______________                 </span></p>
  </td>
  <td width=96 nowrap valign=bottom style='width:72.25pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=107 nowrap valign=bottom style='width:80.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=115 nowrap valign=bottom style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.0pt'></td>
 </tr>
 <tr style='height:15.75pt'>
  <td width=51 nowrap valign=bottom style='width:38.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=187 nowrap style='width:140.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=123 nowrap valign=bottom style='width:92.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=96 nowrap valign=bottom style='width:72.25pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=107 nowrap valign=bottom style='width:80.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=115 nowrap valign=bottom style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:15.75pt'></td>
 </tr>
 <tr style='height:15.75pt' class="q">
  <td id="number" width=51 nowrap rowspan=2 valign=bottom style='width:38.0pt;border:solid windowtext 1.0pt;
  border-bottom:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='color:black'>№<br>п/п</span></b></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>Материальные ценности</span></b></p>
  </td>
  <td width=203 colspan=2 rowspan=2 style='width:152.25pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid black 1.0pt;border-right:none;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>№ Чертежа</span></b></p>
  </td>
  <td id="kolichestvo" width=0 rowspan=2 style='width:0.0pt;border:solid windowtext 1.0pt;
  border-bottom:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>Кол-во</span></b></p>
  </td>
  <td width=125 rowspan=2 style='width:94.0pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid black 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>Материал</span></b></p>
  </td>
  <td width=137 rowspan=2 style='width:103.0pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid black 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>Операция</span></b></p>
  </td>
  <td width=100 rowspan=2 style='width:10.0pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid black 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>План Н/Ч</span></b></p>
  </td>
  <td id="options" width=100 rowspan=2 style='width:10.0pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid black 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>Параметры детали</span></b></p>
  </td>
 </tr>
 <tr style='height:15.75pt' class='q'>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:15.75pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><span style='font-size:9.0pt;
  color:black'>Наименование</span></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>1</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'><?php echo $data['TXT'] ?></span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'><?php echo $data['OBOZ'] ?></span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'><?php echo $data['COUNT']; ?></span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'><?php echo (!empty($mat['MaterialName']) || !empty($mat['MaterialSort']) ? '<textarea style="width:115px" >' . $mat['MaterialName'] . "\n" . $mat['MaterialSort'] . '</textarea>' : '<textarea style="text-align:centerwidth:115px"></textarea>') ?></span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'><?php echo $data['VIDRABOT']; ?></span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'><?php echo ($data['PLAN_NCH'] == 0 ? '' : $data['PLAN_NCH']); ?></span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'><?php echo $data['OPTIONS']; ?></span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'>&nbsp;</span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'>&nbsp;</span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'>&nbsp;</span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'>&nbsp;</span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'>&nbsp;</span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=309 colspan=2 style='width:232.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-size:12.0pt;
  font-family:"Times New Roman",serif;color:black'>&nbsp;</span></p>
  </td>
  <td width=203 colspan=2 style='width:152.25pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='font-family:"Times New Roman",serif;
  color:black'>&nbsp;</span></p>
  </td>
  <td width=115 nowrap style='width:86.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap style='width:94.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=137 nowrap style='width:103.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
  <td width=100 nowrap style='width:50.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><b><i><span style='font-size:10.0pt;
  color:black'>&nbsp;</span></i></b></p>
  </td>
 </tr>
 <tr style='height:32.1pt'>
  <td width=51 style='width:38.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'></td>
  <td width=187 style='width:140.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'></td>
  <td width=123 style='width:92.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'></td>
  <td width=96 style='width:72.25pt;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'></td>
  <td width=107 style='width:80.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:32.1pt'></td>
  <td width=115 nowrap valign=bottom style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:32.1pt'></td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:32.1pt'></td>
  <td width=137 nowrap style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:32.1pt'></td>
 </tr>
 <tr style='height:12.0pt'>
  <td width=51 nowrap valign=bottom style='width:38.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.0pt'></td>
  <td width=187 nowrap style='width:140.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.0pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b><i><span style='font-size:10.0pt;color:black'>Принял   </span></i></b><u><span
  style='font-size:10.0pt;color:black'> ______________          </span></u></p>
  </td>
  <td width=123 nowrap valign=bottom style='width:92.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.0pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal'><span style='color:black'>___________</span></p>
  </td>
  <td width=203 nowrap colspan=2 valign=bottom style='width:152.25pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.0pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal'><u><span style='color:black'>                                       
  </span></u></p>
  </td>
  <td width=115 nowrap valign=bottom style='width:86.0pt;border:none;
  border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.0pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='color:black'>&nbsp;</span></p>
  </td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.0pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.0pt'></td>
 </tr>
 <tr style='height:17.25pt'>
  <td width=51 nowrap valign=bottom style='width:38.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:17.25pt'></td>
  <td width=187 nowrap style='width:140.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:17.25pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.0pt;color:black'>                           
  должность</span></p>
  </td>
  <td width=123 nowrap valign=bottom style='width:92.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:17.25pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.0pt;color:black'>                подпись</span></p>
  </td>
  <td width=203 nowrap colspan=2 valign=bottom style='width:152.25pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.25pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.0pt;color:black'>                
  расшифровка</span></p>
  </td>
  <td width=115 nowrap valign=bottom style='width:86.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:17.25pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.0pt;color:black'>           дата</span></p>
  </td>
  <td width=125 nowrap valign=bottom style='width:94.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:17.25pt'></td>
  <td width=137 nowrap valign=bottom style='width:103.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:17.25pt'></td>
 </tr>
</table>

<p class=MsoNormal>&nbsp;</p>

</div>
<script>

$(document).on("keyup", "#k", function () {
	$.get("/project/print/cooperation_print.php?id=" + <?php echo $_GET['id'] ?> + "&set_print_id=" + $(this).val());


});

$(function () {
	

	
	})

</script>
</body>

</html>
