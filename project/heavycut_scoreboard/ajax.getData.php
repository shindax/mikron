<?php
require_once( $_SERVER['DOCUMENT_ROOT'].'/classes/db.php' );
require_once( 'functions.php' );

const  MAX_SEC_IN_DAY = 24 * 60 * 60 ; // 86400
const  AVAILABLE_SEC_IN_DAY = 22 * 60 * 60 ; // 79200

$data = [];
$from_date = new DateTime( $_POST['date_from'] ) ;
$to_date = new DateTime( $_POST['date_to'] );
$to_date -> modify('+1 day');

$period = new DatePeriod
(
     $from_date ,
     new DateInterval('P1D'),
     $to_date
);

foreach ($period as $key => $value) 
{
	$date = $value->format('Y-m-d');
	$date = new DateTime( $date );

	$highchart_date = $date -> format('d.m');
	$date = $date -> format('Y-m-d');

	$now = new DateTime();
	$today = $now -> format('Y-m-d');

	$machine_result = GetStatistics( $date, 2, $today == $date );
	$machine_on_time = $machine_result['ontime'] ;

	$tool_result = GetStatistics( $date, 4, $today == $date );
	$tool_on_time = $tool_result['ontime'];

	$machine_perc = number_format( $machine_on_time * 100 / AVAILABLE_SEC_IN_DAY );

	if( $machine_on_time )
		// $tool_perc = number_format( $tool_on_time * 100 / $machine_on_time );
		$tool_perc = number_format( $tool_on_time * 100 / AVAILABLE_SEC_IN_DAY );
		else
			$tool_perc = 0;

	 $data[] = [ 
					"date" => $date,
					"highchart_date" => $highchart_date,					
					"machine_perc" => $machine_perc,
					"tool_perc" => $tool_perc,
					"machine_on_time" => secondsToTime( $machine_on_time ) ,
					"tool_on_time" => secondsToTime( $tool_on_time ) ,
			   ];
 } // foreach ($period as $key => $value) 

echo json_encode( $data ) ;
