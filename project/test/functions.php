<?php

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function GetMachineOnOffTime( $from_day, $to_day ) 
{
	global $pdo;

	$curstate = 0 ;
	$curtime = null;
	$work_time = [];
	$result = [ 0, 0 ] ;
	$result['start_state'] = 0 ;
	$result['stop_state'] = 0 ;	
	$state = 0 ;
	$time = 0;

	try
	{
	    $query = "SELECT * FROM `machine_log` 
	    		  WHERE type = 2 AND date >= '$from_day' AND date < '$to_day'
	    		  ORDER BY DATE
	    		  ";
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();

	    // echo "<br>Query: $query<br><br>";

	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	}

	switch( $stmt -> rowCount() )
	{
		case 0 : break ;
		case 1 :

			if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
			{
				$time = $row -> date ; 
				$state = $row -> value ? + 1 : + 0 ;

				$curtime = "$from_day 00:00:00";
				$work_time[] = [ 'state' => $state ? 0 : 1 , 'time' => $curtime, 'diff' => 0 ];
			}
			break ;

		default :
			while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
			{
				$time = $row -> date ; 
				$state = $row -> value ;
			
				if( !$curtime )
				{
					$curtime = $time ;
					$work_time[] = [ 'state' => $state, 'time' => $time, 'diff' => 0 ];
					$result['start_state'] = $state ;
					$curstate = $state;
				}

				if( $curstate != $state )
					{
						$diff_time = GetDiff( $curtime, $time ); 
						$work_time[] = [ 'state' => $state, 'time' => $time, 'diff' => $diff_time ];
						$curstate = $state;
						$curtime = $time ;
					}
			}
			break ;
	
	} // switch( $stmt -> rowCount() )

	if( $stmt -> rowCount() )
	{
		$curtime = "$from_day 23:59:59";
		$diff_time = GetDiff( $time, $curtime ); // Время прошедшее с предыдущего стостояния
		$work_time[] = [ 'state' => $state ? 0 : 1 , 'time' => $curtime, 'diff' => $diff_time ];
		$result['stop_state'] = $state ;

		_debug( $work_time );

		foreach( $work_time AS $value )
			$result[ !$value['state'] ] += $value['diff'];
	}

	return $result ;
} // function GetMachineOnOffTime( $from_day, $to_day ) 

function GetToolOnOffTime( $from_day, $to_day ) 
{
	global $pdo;

	$curstate = 0 ;
	$state = 0 ;	
	$curtime = null;
	$work_time = [];
	$result = [ 0, 0 ] ;

	try
	{
	    $query = "SELECT * FROM `machine_log` WHERE type = 4 AND date >= '$from_day' AND date < '$to_day'";
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();

	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	}

		while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
		{
			$time = $row -> date ; 
			$state = $row -> value ;
			
			if( !$curtime )
			{
				$curtime = $time ;
				$work_time[] = [ 'state' => $state, 'time' => $time, 'diff' => 0 ];
				$result['start_state'] = $state ;
			}
			if( $curstate != $state )
				{
					$diff_time = GetDiff( $curtime, $time ); // Время прошедшее с предыдущего стостояния
					$work_time[] = [ 'state' => $state, 'time' => $time, 'diff' => $diff_time ];
					$curstate = $state;
					$curtime = $time ;
				}
		}
	
	$result['stop_state'] = $state ;

foreach( $work_time AS $value )
	$result[ !$value['state'] ] += $value['diff'];

	return $result ;

} // function GetToolOnOffTime( $from_day, $to_day ) 

function secondsToTime($seconds) 
{
   $then = new DateTime(date('Y-m-d H:i:s', 0));
   $now = new DateTime(date('Y-m-d H:i:s', $seconds));
   $diff = $then->diff($now);
   $str = "";
   
   // if( $diff->y )
   // 	$str .= $diff->y.' years, ';
   // if( $diff->m )
   // 	$str .= $diff->m.' months, ';
   // if( $diff->d )
   // 	$str .= $diff->d.' days, ';

   	$str .= $diff->h.' hours, ';
   	$str .= $diff->i.' minutes, ';
   	$str .= $diff->s.' seconds';

   return $str;	
}

function GetDiff( $from, $to ) // Get time in seconds
{
	  $datetime1 = new DateTime( $from );
      $datetime2 = new DateTime( $to );
      return $datetime2->getTimestamp() - $datetime1->getTimestamp();
}

function DateTimeSeeder( $date, $val, $minsec, $maxsec )
{
	global $pdo;

	$datetime1 = new DateTime( $date );
	$datetime2 = new DateTime( $date );
	$datetime2 = $datetime2 -> add(new DateInterval('P1D')); 
	
	$from_sec = $datetime1->getTimestamp();
	$to_sec = $datetime2->getTimestamp();

	$state = 0;
	$query = "INSERT INTO `machine_log` ( `type`, `date`, `value`) VALUES ";

	for( ; $from_sec < $to_sec ; $from_sec += rand( $minsec, $maxsec ) )
	{
		$date = new DateTime( date('Y-m-d H:i:s', $from_sec ) );	
		$date_str = $date -> format( 'Y-m-d H:i:s' );
		$state = $state ? 0 : 1;
		$query .= "( $val, '$date_str', $state ),";
	}
	
	$query[ strlen( $query ) - 1 ] = ';';

	try
	{
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	}

}