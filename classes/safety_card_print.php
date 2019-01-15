<script type="text/javascript" src="/uses/jquery.js"></script>
<style>
span.black
{
  color : black !IMPORTANT;
}
</style>


<script>
let message = '';

// if (window.jQuery) 
//   message = 'Used jQuery V: ' + jQuery.fn.jquery;
//       else
//         message = 'jQuery not used in krz_calc.js!';

// console.log( message );

//Replace all inputs with spans. Chrome 72 beta �rutch.
window.matchMedia("print").addListener(function() 
  {
    $('input').each(function( index, value ) 
              {
                $( value ).replaceWith("<span class='black'>" + $( value ).val() + "</span>").css('color','black');
              });
  })

</script>


<?php



define('MAV_ERP', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database.php');

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
<title>���������� �� ���������</title>
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
  textarea {resize: none}

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
0cm;margin-bottom:.0001pt'><img width=400   id="������� 7"
src="/project/print/safety_card_print.png"></p>
<p align=right style='margin-top:0cm;margin-right:-.05pt;margin-bottom:0cm;
margin-left:0cm;margin-bottom:.0001pt;text-align:right'><span style='font-size:
8.0pt'> 
���������� 1 � ������� � �� 027/17  �� 26.06.2017�.
</span></p><br/>

<p align=center style='margin:0cm;margin-bottom:.0001pt;text-align:center'><b><span
style='font-size:14.0pt'>�������� � <u><?php echo mb_strtoupper($resource['A8_1'], 'cp1251'); ?></u></span></b></p>

<p align=center style='margin:0cm;margin-bottom:.0001pt;text-align:center'><b><span
style='font-size:11.0pt'>��������� �������� �� �������� ������ ���������� ������ ����� ����������</span></b></p><br/>

<div style="text-align:center;margin:0 auto"><div  style="margin:0 auto;border-bottom:1px solid #000;width:400px">��� ���� ������</div> 
<span><small>(������ ������������ �����������)</small></span>
</div>

<p align=center style='margin:0cm;margin-bottom:.0001pt;text-align:center'><b><span
style='font-size:11.0pt'>&nbsp;</span></b></p>

<p class=MsoNormal><b>&nbsp;</b></p>

<div style="float:right">
<p class=MsoNormal><b>�<span style='color:red'><input style="width:18px" type="text"/></span>� <span
style='color:red'><select style="font-weight:bold;width:80px;">
	<option>������</option>
	<option>�������</option>
	<option>�����</option>
	<option>������</option>
	<option>���</option>
	<option>����</option>
	<option>����</option>
	<option>�������</option>
	<option>��������</option>
	<option>�������</option>
	<option>������</option>
	<option>�������</option>
</select></span><span id="year">&nbsp;&nbsp;20<input style="width:18px" type="text" value="<?php echo substr(0, 2, date('Y')); ?>"/>����</span></b></p>
</div>

<p class=MsoNormal><b>&nbsp;</b></p>

<p class=MsoNormal><b>&nbsp;</b></p>
� ������������ � �������� (�������������) ������������ (������������) ����������� ��

"<span style="border-bottom:1px solid #000">&nbsp;26&nbsp;</span>"	<div style="display:inline-block;text-align:center;border-bottom:1px solid #000;width:100px">����</div>	20	<span style="border-bottom:1px solid #000">&nbsp;17</span>	�. �	<span style="border-bottom:1px solid #000">&nbsp;��027/17</span>	�������� � �������:
<br/>  <div style="display:inline-block;float:left;width:100%">������������&nbsp;<div style="display:inline-block;border-bottom:1px solid #000;;width:80%">������� �.�., ������� �������</div></div><br/>
<div style="margin:0 auto;text-align:center;"><small>(�.�.�., ���������)</small></div><br/>
<div style="display:inline-block;float:left;width:100%">������&nbsp;<div style="display:inline-block;border-bottom:1px solid #000;;width:80%">�������� �.�., ������� �������</div></div><br/>
<div style="margin:0 auto;text-align:center;"><small>(�.�.�., ���������)</small></div><br/>
<div style="display:inline-block;border-bottom:1px solid #000;;width:100%">��������� �.�., ���������� �� ������ �����</div>
 
�������������� *:<br/>
������� �������������� ������ ��������� ���������� ���������<br/>
<div style="display:inline-block;border-bottom:1px solid #000;;width:100%">&nbsp;</div>
<div style="margin:0 auto;text-align:center;"><small>(�.�.�., ���������)</small></div><br/>
<div style="display:inline-block;float:left;width:100%">������� �������� ��������������&nbsp;<div style="display:inline-block;border-bottom:1px solid #000;;width:80%">&nbsp;</div></div><br/>
<div style="margin:0 auto;text-align:center;"><small>(�.�.�., ���������)</small></div><br/>
��������������� ��������� ����� �������� ���������� ���������
<div style="display:inline-block;border-bottom:1px solid #000;;width:100%">&nbsp;</div>

<div style="margin:0 auto;text-align:center;"><small>(�.�.�., ���������)</small></div><br/>
������� �������� ������ ���������� ������ ����� ���������� �� ��������� �������� ������
<div style="display:inline-block;border-bottom:1px solid #000;;width:100%;float:left;"><div style="float:left;margin-top:3px;"></div>
<div style="float:left "> <textarea style="font-weight:normal " cols=60 rows=1 type="text"></textarea></div> 	</div>
<div style="margin:0 auto;text-align:center;"><small>(������������ ��������� �������� �� ������ �����)</small></div><br/>

� ������  	 <input style="font-weight:normal;width:20px;" type="text" value="40"/> �����


<p class=MsoNormal><b>&nbsp;</b></p>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width=696
 style='width:522.15pt;border-collapse:collapse;border:none'>
 <tr style='height:7.7pt'>
  <td width=126 rowspan=2 style='width:94.3pt;border:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:9.0pt'>�. �. �.</span></b></p>
  </td>
  <td width=106 rowspan=2 style='width:79.75pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b><span
  style='font-size:9.0pt'>���������</span></b></p>
  </td>
  <td width=164 rowspan=2 style='width:123.3pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-5.35pt;text-align:center'><b>&nbsp;</b></p>
  <p class=MsoNormal align=center style='margin-top:-20;margin-right:-5.35pt;text-align:center'><b><span
  style='font-size:9.0pt'>������������ ������������� (���, �������, �����, �����������, ���������� � �.�.)</span></b></p>
  </td>
  <td width=203 colspan=2 style='width:152.35pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-12.2pt;text-align:center;
  text-indent:.05pt'><b><span style='font-size:10.0pt'>������� � ��������
  ������</span></b></p>
  </td>
  <td width=97 rowspan=2 style='width:72.45pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 18.4pt 0cm 5.4pt;height:7.7pt'>
  <p class=MsoNormal align=center style='margin-right:-12.2pt;text-align:center'><b><span
  style='font-size:9.0pt'>������� ������������</span></b></p>
  </td>
 </tr>
 <tr style='height:40.95pt'>
  <td width=97 style='width:72.45pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:40.95pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:10.0pt'>��������� ���-����� ������ (����/�� ����), � ��������� �������������</span></b></p>
  </td>
  <td width=106 style='width:89.85pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:40.95pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><span
  style='font-size:10.0pt'>������� ���-����� ������ (���������, ������������ � �.�.)</span></b></p>
  </td>
 </tr>

 <tr style='height:55.25pt'>
  <td width=126 style='text-align:center;width:94.3pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 18.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal style='margin-top:0cm;margin-right:-5.4pt;margin-bottom:
  0cm;margin-left:8.8pt;margin-bottom:.0001pt'><b><?php echo $resource['FF']; ?></b></p>
  <p class=MsoNormal style='margin-top:0cm;margin-right:-5.4pt;margin-bottom:
  0cm;margin-left:8.8pt;margin-bottom:.0001pt'><b><?php echo $resource['II']; ?></b></p>
  <p class=MsoNormal style='margin-top:0cm;margin-right:-5.4pt;margin-bottom:
  0cm;margin-left:8.8pt;margin-bottom:.0001pt'><b><?php echo $resource['OO']; ?></b></p>
  </td>
  <td width=106 style='width:79.75pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 18.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b><?php echo strtolower($resource['speciality_name']); ?> </b></p>
  </td>
  <td width=164 style='width:123.3pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 18.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='margin-right:-5.4pt;text-align:center'><b>
  <?php

/*  
  if ($_GET['resource_id'] == 614 || $_GET['resource_id'] == 613 || $_GET['resource_id'] == 630 || $_GET['resource_id'] == 678) {
	  echo '������������';
  } else if ($_GET['resource_id'] == 428 || $_GET['resource_id'] == 512 || $_GET['resource_id'] == 496 || $_GET['resource_id'] == 637) {
	echo '����� ��';
  } 
  else if ($_GET['resource_id'] == 715) { // ����� ������
	echo '����� ������';
  } 
  else if ($_GET['resource_id'] == 134) {  
	echo '���';
  }
  else if ($_GET['resource_id'] == 643) {
	  echo '������ �������� ��������';
  } else if ($_GET['resource_id'] == 710) {
	  echo '��������������� �����';
  } else if ($_GET['resource_id'] == 743 || $_GET['resource_id'] == 674 || $_GET['resource_id'] == 297 ||$_GET['resource_id'] == 709 || $_GET['resource_id'] == 713) {
	echo '��������� ���������';
  } else if(!empty($resource['OtdelNAME'])) {
	  echo $resource['OtdelNAME'];
	  
  } else {
	  echo '��������';
  }

*/

//switch( $_GET['resource_id'] )
//{
//  case 628 : $dep_name = "��������� ������"; break ; // ��������
//  default : $dep_name =  $resource['dep_name']; break ;
//}

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
  <p class=MsoNormal align=center style='text-align:center'><b>����(�), �������������
� <?php echo $resource['A10_1'] ?>
</b></p>
  </td>
  <td width=106 style='width:89.85pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal align=center style='text-align:center'><b><input style="width:97px;" type="text" value="���������"/></b></p>
  </td>
  <td width=97 valign=top style='width:72.45pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:55.25pt'>
  <p class=MsoNormal><b>&nbsp;</b></p>
  </td>
 </tr>
</table>

<p class=MsoNormal><b>&nbsp;</b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b>&nbsp;</b></p>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b><span style='width:210px;display:inline-block'>������������ ��������</span>_________________�� <span style="border-bottom:1px solid #000">������� �.�.</span></b></p>
<small style="margin-left:250px">(�������)</small>
<small style="margin-left:80px;">(�.�.�.)</small>

<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b><span style='width:210px;display:inline-block'>����� ��������</span>_________________�� <span style="border-bottom:1px solid #000">�������� �.�.</span></b></p>
<small style="margin-left:250px">(�������)</small>
<small style="margin-left:80px;">(�.�.�.)</small>
<p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
margin-left:0cm'><b><span style='width:210px;display:inline-block'> </span>_________________�� <span style="border-bottom:1px solid #000">��������� �.�.</span></b></p>
 <small style="margin-left:250px">(�������)</small>
<small style="margin-left:80px;">(�.�.�.)</small>
<br/>
 
������������� **:<br/>
������� �������������� ������ ��������� ���������� ��������� 		
<div style="width:135px;border-bottom:1px solid #000;margin-left:12%">&nbsp;</div>
<small style="margin-left:14.5%;">(�������)</small>
<small style="margin-left:4%;">(�.�.�.)</small><br/>	
������� �������� ��������������		 
<div style="width:135px;border-bottom:1px solid #000;margin-left:12%">&nbsp;</div>
<small style="margin-left:14.5%;">(�������)</small>
<small style="margin-left:4%;">(�.�.�.)</small><br/>	
��������������� ��������� ����� �������� ���������� ���������
<div style="width:135px;border-bottom:1px solid #000;margin-left:12%">&nbsp;</div>

	<br/>		
<small style="margin-left:14.5%;">(�������)</small>
<small style="margin-left:4%;">(�.�.�.)</small><br/>	<br/>	

* �����������, ���� ��������� � ������ ��������.<br/>
** �������������, ���� ��������� � ������ ��������.<br/>

</div>

</body>

</html>
