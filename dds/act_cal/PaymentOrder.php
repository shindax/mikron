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

<div id='capt1'><span>Приложение 1</span></div> 
<div id='capt2'><span>СФО:&nbsp;</span><span class='und'>Коммерческий отдел</span></div>
<div id='capt3'><span>Задание на платеж</span></div>

<div id='payOrderTableDiv'><table class='payOrderTable'>

<col width="40px">
<col width="20%">       
<col width="10%">
<col width="20%">       
<col width="20%">
<col width="20%">       
        
<tr>
<td colspan='6' class='AC'><span class='head'>Реестр платежей</span></td>        
</tr>    
<tr class='row'>
<td class='AC'>Дата</td>
<td>№ счета</td>
<td>Сумма руб.</td>
<td>Контрагент</td>    
<td>Наименование товаров/услуг</td>
<td>№ заказа/статья</td>    
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
        
<div class='sign'><div class='footer'>Исполнитель</div>________________________</div><br>

<div class='sign'><div class='footer'>Руководитель</div>________________________</div>
        
TABLE;



echo $str ;

?>
