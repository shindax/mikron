<?php
header('Content-Type: text/html');
error_reporting( 0 );
//error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrdersCalendarMonthView.php" );
global $dbpasswd;

$user_arr = $_POST['user_arr'];
$month = $_POST['month'];
$year = $_POST['year'];

$var = new OrdersCalendarHtml( $pdo , $user_arr, $year, $month );
$str = $var -> GetTable( $month, $year );

if( strlen( $dbpasswd ) )
	echo iconv("UTF-8", "Windows-1251", $str );
		else
			echo $str;

