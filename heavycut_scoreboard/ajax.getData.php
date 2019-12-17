<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );
error_reporting( E_ALL );

const  MAX_SEC_IN_DAY = 24 * 60 * 60 ; // 86400
const  AVAILABLE_SEC_IN_DAY = 22 * 60 * 60 ; // 79200

$date = $_POST['date'];
$date = new DateTime( $date );
$date = $date -> format('Y-m-d');

$now = new DateTime();
$today = $now -> format('Y-m-d');

$machine_result = GetStatistics( $date, 2, $today == $date );
$machine_on_time = $machine_result['ontime'] ;
$machine_off_time = $machine_result['offtime'];

$tool_result = GetStatistics( $date, 4, $today == $date );
$tool_on_time = $tool_result['ontime'];

$machine_perc = number_format( $machine_on_time * 100 / AVAILABLE_SEC_IN_DAY );

if( $machine_on_time )
	// $tool_perc = number_format( $tool_on_time * 100 / $machine_on_time );
	$tool_perc = number_format( $tool_on_time * 100 / AVAILABLE_SEC_IN_DAY );
	else
		$tool_perc = 0;


if( $date == $today )
{
	$date = new DateTime();
	$date = "Today is ".$date -> format('Y-m-d H:i:s');
}
else
{
	$date = $_POST['date'];
	$date = new DateTime( $date );
	$date = "Date is ".$date -> format('Y-m-d');
}

$machine_on_time_str = "Machine on time : ".secondsToFullTime( $machine_on_time )." ( $machine_on_time seconds total )";
$machine_off_time_str = "Machine off time : ".secondsToFullTime( $machine_off_time )." ( $machine_off_time seconds total )";

echo json_encode( [ 
						"date" => $date,
						"machine_perc" => $machine_perc,
						"tool_perc" => $tool_perc,
						"machine_on_time" => secondsToTime( $machine_on_time ), 
						"machine_on_time_str" => $machine_on_time_str, 
						"machine_off_time" => $machine_off_time,
						"machine_off_time_str" => $machine_off_time_str
				 ]);
