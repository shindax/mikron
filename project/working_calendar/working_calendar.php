<script type="text/javascript" src="/project/working_calendar/js/constants.js"></script>
<script type="text/javascript" src="/project/working_calendar/js/date.js"></script>
<script type="text/javascript" src="/project/working_calendar/js/working_calendar.js"></script>

<script src="/vendor/highcharts/highcharts.js"></script>
<script src="/vendor/highcharts/modules/exporting.js"></script>

<link rel='stylesheet' href='/project/working_calendar/css/style.css' type='text/css'>
<link rel='stylesheet' href='/project/working_calendar/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/working_calendar/css/jquery-ui.css' type='text/css'>

<?php
error_reporting( E_ALL );
ini_set('display_errors', true);

//error_reporting(0);
//ini_set('display_errors', false);

require_once( "functions.php" );
require_once( "dialogs.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrdersCalendarNew.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrdersCalendarNew_.php" );

global $pdo;

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function console( $arg1, $arg2 = '', $arg3 = '', $arg4 = '', $arg5 = '', $arg6 = '' )
{
  echo "<script>console.log('$arg1 : $arg2 : $arg3 : $arg4 : $arg5 : $arg6 ');</script>";
}

$env = GetEnviroment();
$cal = new OrdersCalendar( $pdo, $env['user_id'] );

$month = date("j");
$year = date("Y");

echo $cal -> GetHeadTitle();
echo $content_begin;
echo  $cal -> GetWorkingCalendar($month,$year) ;
echo $content_end;
