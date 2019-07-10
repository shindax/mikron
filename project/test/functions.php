<?php
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}


function GetStatistics( $from_day, $type, $not_full_day = false )
{
	global $pdo ;

	$day = new DateTime( $from_day );
	$day->modify('+1 day');
	$to_day = $day->format('Y-m-d');

	try
	{
	    $query = "SELECT * FROM `machine_log` 
	    		  WHERE type = $type AND date >= '$from_day' AND date < '$to_day'
	    		  ORDER BY DATE
	    		  ";

	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	}

	// echo $stmt -> rowCount()."<br>";

	$data = [];
	$curstate = 5;
	$state = null;

	while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	{
		$state = $row -> value ;
		if( $state != $curstate )
		{
			$curstate = $state ;
			$data[] = [ 'id' => $row -> id, 'date' => $row -> date, 'state' => $state ];
		}
	}

	if( $not_full_day )
	{
		$now = new DateTime();
		$data[] = [ "id" => "id", "date" => $now -> format("Y-m-d H:i:s"), "state" => $state ? 0 : 1  ];
	}
	else
		$data[] = [ "id" => "id", "date" => "$from_day 23:59:59", "state" => $state ? 0 : 1  ];	


	$curdate = "$from_day 00:00:00";

	$ontime = 0 ;
	$offtime = 0 ;

	foreach( $data AS $value )
	{
		if( $curdate != $value['date'] )
		{
			$state = + $value['state'];
			$diff_time = GetDiff( $curdate, $value['date'] ); 
			// echo "$curdate {$value['date']} $diff_time <br>";

			if( $state )
				$offtime += $diff_time ;
				else
					$ontime += $diff_time ;

			$curdate = $value['date'];
		}
	}

	$ontime = $ontime > 5 ? $ontime : 0 ;
	$offtime = $offtime > 5 ? $offtime : 0 ;	

	return [ 'data' => $data, 'ontime' => $ontime, 'offtime' => $offtime ];
}

function secondsToTime($seconds) 
{
   $then = new DateTime(date('Y-m-d H:i:s', 0));
   $now = new DateTime(date('Y-m-d H:i:s', $seconds));
   $diff = $then->diff($now);
   $str = round( $diff->h + $diff->i / 60 );
   return $str;	
}

function secondsToFullTime($seconds) 
{
   $then = new DateTime(date('Y-m-d H:i:s', 0));
   $now = new DateTime(date('Y-m-d H:i:s', $seconds));
   $diff = $then->diff($now);   
   $str = $diff->h." hours ".$diff->i." minutes";
   return $str;	
}

function GetDiff( $from, $to ) // Get time in seconds
{
	  $datetime1 = new DateTime( $from );
      $datetime2 = new DateTime( $to );
      $sec = $datetime2->getTimestamp() - $datetime1->getTimestamp();
      return $sec;
}
