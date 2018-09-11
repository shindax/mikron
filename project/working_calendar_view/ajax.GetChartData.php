<?php
//error_reporting( E_ALL );
//ini_set('display_errors', true);

error_reporting( 0 );
ini_set('display_errors', false );


require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar.php" );

$user_arr = $_POST['user_arr'];
$month = $_POST['month'];
$year = $_POST['year'];

$base_cal = new BaseOrdersCalendar( $pdo, $user_arr );
$data = $base_cal -> GetChartData( $month, $year );

//echo json_encode( [ "data" =>  $data , "user" => $base_cal -> GetUserID(), "month" => $month, "year" => $year ] );
echo json_encode( [ "data" =>  $data ] );