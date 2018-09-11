<script type='text/javascript' charset='utf-8' src='uses/jquery.js'></script>
<script type='text/javascript' charset='utf-8' src='uses/jquery-ui.js'></script>
<script type="text/javascript" src="/project/reports/multimachine_emp/js/multimachine_emp.print.js"></script>

<script src="/vendor/highcharts/highcharts.js"></script>
<script src="/vendor/highcharts/modules/exporting.js"></script>

<link rel='stylesheet' href='/project/reports/multimachine_emp/css/print.css' type='text/css'>
<link rel='stylesheet' href='/project/reports/multimachine_emp/css/bootstrap.min.css' type='text/css'>

<?php
error_reporting( E_ALL );
ini_set('display_errors', true);

$year = $_GET['p1'];
$month = $_GET['p0'];

$month_arr = [ "","январь" , "февраль" ,"март" , "апрель" , "май" , "июнь" ,"июль" ,"август" ,
                    "сентябрь" , "октябрь", "ноябрь", "декабрь"  ];

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

require_once( $_SERVER['DOCUMENT_ROOT']."/project/reports/multimachine_emp/functions.php" );

function GetTableEnd()
{
    return "</table>";
}

$head_title = "<div class='head'>
                          <div>
                            <div><h2>".conv( "Отчет по многостаночникам за ").conv( $month_arr[ $month ])." ". $year.conv(" г.")."</h2></div>
                          </div>
                      </div>";

$content_begin = "<div class='container' id='wrap'>";
$content_end = "</div><!--div class='container'-->";


// $sign = "</div><div class='row sign'>";
// $sign .= "<div class='col-sm-2'><span>".conv("Согласовано")."</span></div>";
// $sign .= "<div class='col-sm-2'><span>_______________</span></div>";
// $sign .= "<div class='col-sm-2'><span>".conv("Директор ОКБ 'Микрон'<br>Рудых М.Г.")."</span></div>";
// $sign .= "</div>";// class='row'
// $sign .= "<div class='clearfix'></div>";
// $sign .= "<div class='row sign'>";
// $sign .= "<div class='col-sm-2'><span>".conv("Исполнитель")."</span></div>";
// $sign .= "<div class='col-sm-2'><span>_______________</span></div>";
// $sign .= "</div>";// class='row'

$sign = "</div><br><br><div class='row sign'>";
$sign .= "<span>".conv("Согласовано")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
$sign .= "<span>_______________</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$sign .= "<span>".conv("Директор ОКБ 'Микрон'<br>Рудых М.Г.")."</div>";

$sign .= "<div class='clearfix'></div>";

$sign .= "<div class='row sign'>";
$sign .= "<span>".conv("Исполнитель")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
$sign .= "<span>_______________</span>";
$sign .= "</div>";// class='row'




 $content = GetMonthTableBegin( $month , $year );
 $content .= GetMonthTableRow( $month , $year );
 $content .= GetTableEnd();

echo $head_title ;
echo $content_begin;
echo $content;
echo $content_end;
echo $sign;