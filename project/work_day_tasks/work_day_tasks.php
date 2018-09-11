<script type="text/javascript" src="/project/work_day_tasks/js/constants.js"></script>
<!--script type="text/javascript" src="/project/work_day_tasks/js/bootstrap.min.js"></script-->
<script type="text/javascript" src="/project/work_day_tasks/js/work_day_tasks.js"></script>

<link rel='stylesheet' href='/project/work_day_tasks/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/work_day_tasks/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.User.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar.php" );

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

error_reporting( E_ALL );


$str = "<div class='head'>
                          <div>
                            <div><h2>".conv( "Просмотр перечня задач за период")."</h2></div>
                            <div class='work_cal_view_setup'>
                                     <span class='label'>".conv( "Дата с")." : </span><input type='text' id='datepicker' readonly='readonly'>
                                     <span class='label'> ".conv( "по")." : </span><input type='text' id='datepicker_second' readonly='readonly'>
                                     <span class='label'>".conv( "Подразделение").":</span>
                                     <select id='department' >
                                           <option value='0' data-id='0'>...</option>
                                           <option value='91,104,118,141,147,148,149,150,151,152' data-id='91'>".conv("Конструкторский отдел")."</option>
                                           <option value='103' data-id='103'>".conv("Технологический отдел")."</option>
                                     </select>
                            </div>
                          </div>

                          <div id='pie_div'><img id='user_chart_img' class='chart' src=''></div>
                      </div>";


$str .= "<div class='container'>";

$str .=  "<div class='row'>
          <div class='col-sm-12' style='text-align:right'>
              <hr>
              <button class='btn btn-small btn-primary hidden' type='button' id='print_btn'>".conv('Распечатать')."</button>
          </div></div>";

$str .= "<div class='row' id='table_div'>";
$str .= "</div>"; // "<div class='row'>"




echo $str ;
