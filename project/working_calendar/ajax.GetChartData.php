<?php
error_reporting( 0 );
ini_set('display_errors', false );

//error_reporting( E_ALL );
//ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar.php" );

global $pdo;

$user_arr = $_POST['user_arr'];
$month = $_POST['month'];
$year = $_POST['year'];

$base_cal = new BaseOrdersCalendar( $pdo, $user_arr, $year, $month );
$data = $base_cal -> GetChartData();

echo json_encode( [ "data" =>  $data ] );

