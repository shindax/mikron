<?php

function score_calc( $plan_hours, $delay_time )
{
    $available_perc = 0.95;
    $perc = $plan_hours * $available_perc;
    $dispersion = $plan_hours - $perc;
    $subscore = $dispersion - $delay_time;
    $score = 5;

    if( $dispersion )
        $score = number_format( $subscore * 5 / $dispersion, 1 );
    
    return $score;
}

function conv( $str )
{
    global $dbpasswd;
    if( !strlen( $dbpasswd ))
        return $str ; 
        else
            return iconv( "UTF-8", "Windows-1251",  $str );
}

function min_to_hour( $min )
{
    $sign = $min < 0 ? "-" : "" ;
    $min = abs( $min );
    $hours = intval( $min / 60 );
    $minutes= $min - $hours * 60;
    $result = $hours ? $hours.":". ( $minutes < 10 ? "0".$minutes : $minutes ) : $minutes.conv("м");
    return $sign.$result;
}

function remove_excess( &$data, $arr )
{
    foreach( $arr AS $value )
        unset( $data[ $value ] );
}

function get_plan( $year, $month, $max_day, $res_id )
{
    global $pdo;
    $res = array_fill( 1, $max_day, 0.0 );

    try
    {
        $query = "
                  SELECT plan, score
                  FROM master_plan_evaluation
                  WHERE 
                  year = $year
                  AND
                  month = $month 
                  AND
                  master_res_id = $res_id
                 ";
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
        $score = $row -> score;
        $res = json_decode( $row -> plan, true );
    }

    return [ $res, $score ];
}// function get_plan( $year, $month, $max_day, $res_id )


function get_fact( $year, $month, $max_day, $res_id )
{
    global $pdo;
    $arr = [];
    $res_arr = [];
    $result = [];
    $score = [];
    $month = $month < 10 ? "0$month" : $month ;
    $from = $year.$month."01";
    $to = $year.$month."31";

    try
    {
        $query = "
                  SELECT ID AS dep_id
                  FROM okb_db_otdel
                  WHERE 
                  master_res_id = $res_id
                 ";
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        $arr[] = $row -> dep_id;

    try
    {
        $query = "
                  SELECT ID_resurs AS id_resurs
                  FROM okb_db_shtat
                  WHERE
                  ID_otdel IN (".join( ",", $arr ).")
                 ";
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      if( $row -> id_resurs )
        $res_arr[] = $row -> id_resurs;

    try
    {
        $query = "
                  SELECT `DATE` AS date, SUM( FACT ) AS fact
                  FROM okb_db_zadan
                  WHERE 
                  `DATE` >= $from AND `DATE` <= $to
                  AND ID_resurs IN (".join( ",", $res_arr ).")
                  GROUP BY `DATE`
                 ";
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
      $date = intval( $row -> date % 100 );
      $fact = number_format( $row -> fact, 1 );
      $result[ $date ] = $fact;
      $score[ $date ] = 0;
    }
   
    return [ $result, $score ];

}// function get_fact( $year, $month, $max_day, $res_id )

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}