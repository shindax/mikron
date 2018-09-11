<script type="text/javascript" src="/project/reports/operational_complexity_report/js/OperationalComplexityReport.js?1"></script>    
<script src="project/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="project/chosen/chosen.css">
<link rel="stylesheet" href="/project/reports/operational_complexity_report/css/style.css">

<?php

global $db_prefix ;

echo "<script> var db_prefix='".$db_prefix."';</script>";

echo "<h2 id='title'>Заказы</h2>
<div id='wrapper'>
<a class='alink hidden' target='_blank' id='print_link' src=''>Распечатать отчет</a>
<form>
<div id='container'>
<select data-placeholder='Поиск заказа...' class='chosen-select' style='width:900px;' tabindex='2'>
<option value=''></option>";

$yyy = dbquery("SELECT * FROM okb_db_v_zak_finder");
while ($b = mysql_fetch_array($yyy)) 
  { 
    echo $b["S_01"].$b["S_02"].$b["S_03"].$b["LINK"].$b["S_1"].$b["S_2"].$b["S_3"].$b["S_4"].$b["FINISH"]; 
  }

echo  "</select></div></form><br/>";

echo "<table class='tbl'>
      <tr class='first'>
      <td width='5%'>№</td><td>Наименование операции</td><td width='10%'>НЧ</td>
      <tr class='row' style='display:none'>
      <td></td><td></td><td></td>
      </tr></table>";

echo "</div>";

?>