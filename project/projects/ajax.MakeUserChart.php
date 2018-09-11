<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( "class.ordersCalendar.php" );

$user_id = $_POST[ 'user_id' ];

$cal = new OrdersCalendar( $dblocation, $dbname, $dbuser, $dbpasswd, $user_id );
$orders = $cal -> GetData();
$cal -> MakeChart();

echo $cal -> GetImageName();