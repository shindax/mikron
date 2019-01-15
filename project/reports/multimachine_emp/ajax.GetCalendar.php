<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/reports/multimachine_emp/functions.php" );

$month = $_POST['month'];
$year = $_POST['year'];

function conv( $str )
{
//    return iconv("UTF-8","Windows-1251",  $str );
    return $str ;
}

function GetTableEnd( $month , $year )
{
    return "</table></div></div>

        <div class='row'>
            <div class='col-sm-12'>
                <button class='btn btn-small btn-primary pull-right' type='button' id='print' data-month='$month' data-year='$year'>".conv('Распечатать')."</button>
            </div>
        </div>";
}

$header = GetMonthTableBegin( $month , $year );
$content = GetMonthTableRow( $month , $year );
$footer = GetTableEnd( $month , $year );

if( strlen( $content ))
  $str = $header.$content.$footer;
  else
    $str = "<h2>".conv("Нет данных")."</h2>";

//echo iconv("UTF-8", "Windows-1251", $str );
echo $str;
