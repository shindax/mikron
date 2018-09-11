<?php
error_reporting( 0 );
ini_set('display_errors', false );

// error_reporting( E_ALL );
// ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrdersCalendarNew.php" );

function conv( $str )
{
	return $str ;
}

$user_id = $_POST['user_id'];
$month = $_POST['month'];
$year = $_POST['year'];

$cal = new OrdersCalendar( $pdo, $user_id, $year, $month );
$table = $cal -> GetDataTable() ;

//echo $table ;
echo iconv('utf-8', 'windows-1251', $table );