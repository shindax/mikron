<link rel='stylesheet' href='project/act_cal/css/activeCalendar.css' type='text/css'>
<script type="text/javascript" src="project/act_cal/js/activeCalendar.js"></script>
<script type="text/javascript" src="project/act_cal/js/jquery-latest.js"></script>
<script type="text/javascript" src="project/act_cal/js/fix_head_table.js"></script>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("CommonFunctions.php");
global $user ;

$str = <<< TABLE

<div id='capt1'><span>���������� 1</span></div> 
<div id='capt2'><span>���:&nbsp;</span><span class='und'>������������ �����</span></div>
<div id='capt3'><span>������� �� ������</span></div>

<div id='payOrderTableDiv'><table class='payOrderTable'>

<col width="40px">
<col width="20%">       
<col width="10%">
<col width="20%">       
<col width="20%">
<col width="20%">       
        
<tr>
<td colspan='6' class='AC'><span class='head'>������ ��������</span></td>        
</tr>    
<tr class='row'>
<td class='AC'>����</td>
<td>� �����</td>
<td>����� ���.</td>
<td>����������</td>    
<td>������������ �������/�����</td>
<td>� ������/������</td>    
</tr>
<tr class='row'><td rowspan='14' class='datecell'>01.01.2000</td><td></td><td></td><td></td><td></td><td></td></tr>        
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>        
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>        
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>        

<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>        
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>        
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>        

<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
<tr class='row'><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
<tr class='row last'><td></td><td id='total_cell'>0,00</td><td></td><td></td><td></td></tr>        
    
</table></div>
        
<div class='sign'><div class='footer'>�����������</div>________________________</div><br>

<div class='sign'><div class='footer'>������������</div>________________________</div>
        
TABLE;



echo $str ;

?>
