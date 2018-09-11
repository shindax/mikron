<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
//    return iconv( "UTF-8", "Windows-1251",  $str );
    return $str ;
}

function getBreakApartPD( $str )
{
        // Получаем начало PD : состояние и первая дата
        $state_and_dates_str = explode('#', $str ) ;
		$last_date = $state_and_dates_str[ count( $state_and_dates_str ) - 1 ];
		$last_date = explode(' ', $last_date );
		$last_date = $last_date[0];

        $state_and_first_date = explode('|', $state_and_dates_str[0] );
        $log_state = 1 * $state_and_first_date[0] ;

        $first_date = $state_and_dates_str[2];
        $first_date = explode( '|', $first_date );
		$first_date = $first_date[0];

        $arr = [ 'log_state' => $log_state, 'init_date' => $state_and_first_date[1], 'first_date' => $first_date, 'last_date' => $last_date ];
	
        return $arr ;
}

function GetOrdersByFieldInDateIntervalStart( $field , $tmp_from_date = 0, $tmp_to_date = 0 )
{
	global $pdo ;

	if( !$tmp_from_date )
		$tmp_from_date = "13.12.1901";

	if( !$tmp_to_date )
		$tmp_to_date = "19.01.2038";

	$from_date = strtotime( $tmp_from_date );
	$to_date = strtotime( $tmp_to_date );

	try
	{
	    $query = "SELECT ID, $field FROM okb_db_zak WHERE EDIT_STATE=0" ;
	    $stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}

	$zak_arr = [];

	while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	{
		$id = $row -> ID ;
		$pd = getBreakApartPD( $row -> $field );

		echo "$id<br>";
		debug( $pd );

		$raw_date = $pd['first_date'];
		if( !strlen( $raw_date ))
			continue ;
		$date = strtotime( $raw_date );
		if( $date >= $from_date && $date <= $to_date)
		  $zak_arr[] = $id ;
	}
	return $zak_arr;
}

$zak_arr = GetOrdersByFieldInDateIntervalStart( "PD8" );

$zak_list = join(',', $zak_arr );

echo $zak_list;


