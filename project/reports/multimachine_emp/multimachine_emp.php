<script type="text/javascript" src="/project/reports/multimachine_emp/js/constants.js"></script>
<script type="text/javascript" src="/project/reports/multimachine_emp/js/date.js"></script>

<script type="text/javascript" src="/project/reports/multimachine_emp/js/jquery.monthpicker.js"></script>
<script type="text/javascript" src="/project/reports/multimachine_emp/js/multimachine_emp.js"></script>

<script src="/vendor/highcharts/highcharts.js"></script>
<script src="/vendor/highcharts/modules/exporting.js"></script>

<link rel='stylesheet' href='/project/reports/multimachine_emp/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/reports/multimachine_emp/css/bootstrap.min.css' type='text/css'>

<?php
error_reporting( E_ALL );
ini_set('display_errors', true);

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
                            <div><h2>".conv( "Отчет по многостаночникам")."</h2></div>
                            <div class='work_cal_view_setup'>
                                     <span class='label'>".conv( "Месяц").":</span><input type='text' id='monthpicker' readonly='readonly'>
                            </div>
                          </div>
                      </div>";

$content_begin = "<div class='container' id='wrap'>";
$content_end = "</div><!--div class='container'-->";

echo $head_title ;
echo $content_begin;
echo $content_end;
