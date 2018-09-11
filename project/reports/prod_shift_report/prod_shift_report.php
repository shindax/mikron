<link rel="stylesheet" href="/project/reports/prod_shift_report/css/style.css" media="screen" type="text/css" />
<script type="text/javascript" src="/project/reports/prod_shift_report/js/prod_shift_report.js"></script>

<?php

echo "<script>var print_page_form_id = 223; </script>";

require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

$str = "<h4 id='title'></h4>
        <div id='main_div'>
        <a class='alink hidden' target='_blank' id='print_link' src=''>Распечатать отчет</a>
        <table id='prod_shift_report' class='tbl'>
        </table></div>";

echo "<br><input id='report_date' type='date' /><span>Выберите дату отчета о перечне работающего персонала</span>";
echo $str;

?>
