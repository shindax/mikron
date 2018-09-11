<link rel="stylesheet" href="/project/reports/constructors_project_tasks/css/style.css">
<script type="text/javascript" src="/project/reports/constructors_project_tasks/js/constructors_project_tasks.js"></script>

<?php

//echo "<script>var print_page_form_id = 223; </script>";

require_once( "db.php" );
require_once( "functions.php" );


$str = "
        <h1 id='title'>".conv("План работ конструкторского отдела")."</h1>
        <div id='main_div'>";
$str .= "<div id='seldiv'><input id='report_date' type='date' /><span>".conv("&nbsp;&nbsp;Выберите месяц отчета о заданиях по КО")."</span></div><br>";
        
$str .= "<a class='alink hidden' target='_blank' id='print_link' src=''>".conv("Распечатать отчет")."</a><br><br>
        <table id='constructors_project_tasks' class='tbl'>
        </table></div>";

echo $str;

?>
