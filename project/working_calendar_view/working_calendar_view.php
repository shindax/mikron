<script type="text/javascript" src="/project/working_calendar_view/js/constants.js"></script>
<script type="text/javascript" src="/project/working_calendar_view/js/date.js"></script>

<script type="text/javascript" src="/project/working_calendar_view/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/working_calendar_view/js/working_calendar_view.js"></script>

<script src="/vendor/highcharts/highcharts.js"></script>
<script src="/vendor/highcharts/modules/exporting.js"></script>

<link rel='stylesheet' href='/project/working_calendar_view/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/working_calendar_view/css/bootstrap.min.css' type='text/css'>

<?php
error_reporting( E_ALL );
ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrdersCalendarMonthView.php" );

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

$head_title = "<div class='head'>
                          <div>
                            <div><h2>".conv( "Просмотр рабочего календаря")."</h2></div>
                            <div class='work_cal_view_setup'>
                                     <span class='label'>".conv( "Месяц").":</span><input type='text' id='monthpicker' readonly='readonly'>
                                     <span class='label'>".conv( "Подразделение").":</span>
                                     <select id='department' >
                                           <option value='0' data-id='0'>...</option>
                                           <option value='91' data-id='91'>".conv("Конструкторский отдел")."</option>
                                            <option value='103' data-id='103'>".conv("Технологический отдел")."</option>
                                          <option value='118' data-id='118'>".conv("Группа Концептуального проектирования")."</option>
                                     </select>
                                     <span class='label'>".conv( "Сотрудник").":</span><select id='employee' ></select>
                            </div>
                          </div>

                          <div id='pie_div'><img id='user_chart_img' class='chart' src=''></div>
                      </div>";


$content_begin = "<div class='container' id='wrap'>";
$content_end = "</div><!--div class='container'-->";

echo $head_title ;
echo $content_begin;
echo $content_end;
