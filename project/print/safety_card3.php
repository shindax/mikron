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
	{mso-style-link:"����� ������� ����";
	margin:0cm;
	margin-bottom:.0001pt;
	font-size:9.0pt;
	font-family:"Segoe UI",sans-serif;}
span.a
	{mso-style-name:"����� ������� ����";
	mso-style-link:"����� �������";
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
<tr><td colspan=2 style='font-weight:bold;text-align:center;'>�������� � <?php echo $row['F3_1'] ?>��<br/>
�������� ������ ������ ������ � �����������������<br/><br/>
</td></tr>



<tr>
	<td style="width:450px">���� ��������</td>
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
  </select>

  <select>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option selected>2017</option>
  <option>2018</option>
  <option>2019</option>
  </select>
  �.
 </div> </td>
</tr>
<tr>
	<td style="width:450px">������� ��������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  <select>
  <option>���������</option>
  <option>���������</option>
  <option>�����������</option>
  </select></td>
</tr>
<tr>
	<td style="width:450px">��������<br/>� �������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	��� ���� ������
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(������������ ��������)
	</td>
</tr>
<tr>
	<td style="width:450px">������������ ��������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	������� ������� ������� �.�.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(���������, �������, ��������)
	</td>
</tr>
<tr>
	<td style="width:450px">����� ��������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	������� ������� �������� �.�.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(���������, �������, ��������)
	</td>
</tr>


<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	���������� �� ������ ����� ��������� �. �.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(���������, �������, ��������)
	</td>
</tr>
<tr>
	<td colspan=2>������� �������� ������ ����������� ���������� � ����������, � ������������ � ���������� ����������</td>
</tr>

<tr>
	<td colspan=2><b>�����������:</b></td>
</tr>

<tr>
	<td style="width:450px">�������, ���, ��������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	<?php echo $row['FF'] . ' ' . $row['II'] . ' ' . $row['OO']; ?>
	</td>
</tr>

<tr>
	<td style="width:450px">����� ������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
	��� ���� ������

	</td>
</tr>

<tr>
	<td style="width:450px">��������� (���������)</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
 <?php echo $row['SpecialName'] ?>
	</td>
</tr>

<tr>
	<td style="width:450px">���� ���������� ��������</td>
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
  </select>

  <select>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option selected>2017</option>
  <option>2018</option>
  <option>2019</option>
  </select>
  �.
 </div> </td>
</tr>
<tr>
	<td style="width:450px">������, ������ �� �������������������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
  <select>
  <option>II �� 1000�</option>
  <option>III �� 1000�</option>
  <option>IV �� 1000�</option>
  <option>III �� � ���� 1000�</option>
  <option>IV �� � ���� 1000�</option>
  <option>V �� � ���� 1000�</option>

  </select>
  
	</td>
</tr>
<tr>
	<td><b>���������� �������� ������:</b>
	</td>
</tr>
<tr>
	<td style="width:450px">�� ���������� ���������������� � ����������� ������������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
�����.
	</td>
</tr>
<tr>
	<td style="width:450px">�� ������ �����:  </td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
�����.
	</td>
</tr>
<tr>
	<td style="width:450px">�� �������� ������������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
�����.
	</td>
</tr>
<tr>
	<td style="width:450px">������ ������ � ���������� ������� ���������������� �������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
���
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(������������ ������)
	</td>
</tr>


<tr>
	<td><br/><b>���������� ��������:</b>
	</td>
</tr>

<tr>
	<td style="width:450px">����� ������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
�����������������
	</td>
</tr>


<tr>
	<td style="width:450px">������ �� �������������������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
 <select>
  <option>II �� 1000�</option>
  <option>III �� 1000�</option>
  <option>IV �� 1000�</option>
  <option>III �� � ���� 1000�</option>
  <option>IV �� � ���� 1000�</option>
  <option>V �� � ���� 1000�</option>

  </select>	</td>
</tr>


<tr>
	<td style="width:450px">����������������� ������������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
�� ���������
	</td>
</tr>

<tr>
	<td style="width:450px">������� � ������ � ��������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
<select>

<option>����������-���������� ���������</option>
<option>���������������-������������ ���������</option>
<option>���������� ���������</option>
<option>������������ ���������</option>
<option>���������������� ���������</option>
<option>����������������������� ���������</option>
<option>�������������������</option>

</select>	</td>
</tr>
<tr>
	<td style="width:450px">���� ��������� ��������</td>
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
  </select>

  <select>
  <option>2014</option>
  <option>2015</option>
  <option>2016</option>
  <option selected>2017</option>
  <option>2018</option>
  <option>2019</option>
  </select>
  �.
 </div> </td>
</tr>
<tr>
	<td><b>�������:</b>
	</td>
</tr>
<tr>
	<td style="width:450px">������������ ��������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
������� �. �.
	</td>
</tr>
<tr>
	<td style="width:450px">����� ��������</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
�������� �. �.
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
��������� �. �.
	</td>
</tr>
<tr><td>&nbsp;</td><td></td></tr>
<tr>
	<td style="width:450px">� ����������� �������� ����������:</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
<?php echo $row['SpecialName'] . ' &nbsp;' . $row['ResourceName']; ?>
	</td>
</tr>
<tr>
	<td style="width:450px">������������� � <?php echo $row['F4_1'] ?> ������ �� ����</td>
	<td style="border-bottom:1px solid #000;text-align:center;width:320px;">  
 
	</td>
</tr>
<tr>
	<td style="width:450px">&nbsp;</td>
	<td style="font-size:9.5pt;text-align:center;width:320px;">  
	(�������, ����)
	</td>
</tr>

</table>



</body>

</html>
