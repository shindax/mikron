<?php
require_once( "db.php" );

define( 'FIRST_POINT', 1 );
define( 'SECOND_POINT', 2 );
define( 'FIRST_SECOND_POINT', 3 );

define( 'THIRD_POINT', 4 );
define( 'COMPLETED', 7 );

define( 'START', 0 );
define( 'PREPARE', 10 );
define( 'EQUIPMENT', 20 );
define( 'PRODUCTION', 30 );
define( 'COMM_READY_TO_SHIPMENT', 40 );
define( 'COMM_SHIPPED_WOUT_PAY', 41 );
define( 'READY', 50 );

function conv( $str )
{
  global $dblocation ;

  if( $dblocation == "127.0.0.1" )
    $result = iconv("UTF-8", "Windows-1251", $str );
      else
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;

  return $result;
}

function DateConvert( $date )
{
    return date("Y-m-d", strtotime( $date ));
}

function MakeDateWithDot( $date, $day = 0 )
{
    $timestamp = strtotime( $date );

    if( $day )
        $out_date = $day ;
        else
            $out_date = date('d', $timestamp);

    $out_date .= ".".date('m', $timestamp) . "." . date('Y', $timestamp);

    return $out_date;
}


function MakeDateWithDash( $date, $day = 0 )
{
    $timestamp = strtotime( $date );
    $out_date = date('Y', $timestamp) . "-" . date('m', $timestamp) . "-";
    if( $day )
        $out_date .= $day ;
    else
        $out_date .= date('d', $timestamp);
    return $out_date;
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

function stageLogic(
    $stage_prepare,
    $stage_equipment,
    $stage_production,
    $stage_commertion
)
{
    $stage = START;

    if( $stage_prepare )
        $stage = PREPARE;

    if( ( $stage_prepare == COMPLETED ) || $stage_equipment )
        $stage = EQUIPMENT;

    if( ( $stage_equipment == COMPLETED ) && ( $stage_prepare == COMPLETED ) ) // all points in equipment are done
        $stage = PRODUCTION;

    if( $stage_production == COMPLETED ) // all points in production are done
        $stage = COMM_READY_TO_SHIPMENT;


    if(
        ( $stage_prepare == COMPLETED ) &&
        ( $stage_equipment == COMPLETED )&&
        ( ( $stage_commertion == COMPLETED )  || ( $stage_commertion & FIRST_POINT ) )
     )
        $stage = COMM_SHIPPED_WOUT_PAY;

    if(
        ( $stage_prepare == COMPLETED ) &&
        ( $stage_equipment == COMPLETED ) &&
        ( $stage_production == COMPLETED ) &&
        ( $stage_commertion == COMPLETED )
    )
        $stage = READY;

    if( ( $stage_production != COMPLETED ) && ( $stage_equipment != COMPLETED ) && ( $stage_production == COMPLETED ))
        $stage = PRODUCTION;

    if( $stage_prepare != COMPLETED )
        $stage = PREPARE;

    if( ( $stage_equipment & FIRST_SECOND_POINT ) && $stage_equipment != COMPLETED )
        $stage = EQUIPMENT;

    return $stage ;
}

function isChecked( $val )
{
    if( $val )
        return 'checked';
        else
            return '';
}

function getStatusesList()
{
  global $pdo ;

  $status_list = "<ul>";

        try
        {
            $query = "SELECT * FROM `okb_db_zak_statuses` WHERE 1";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $status_list .= "<li><input data-id='". ( $row -> id )."' type='checkbox' value='". conv( $row -> description )."'>".conv( $row -> description )."</li>";

   $status_list .= "</ul>";
   return $status_list  ;
}

function getStagesList()
{
  global $pdo ;

  $stage_list = "<ul>";

        try
        {
            $query = "SELECT * FROM `okb_db_zak_stages` WHERE 1";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $stage_list .= "<li><input data-id='". ( $row -> id )."' type='checkbox' value='". conv( $row -> description )."'>".conv( $row -> description )."</li>";

   $stage_list .= "</ul>";
   return $stage_list  ;
}

function extractDate( $val )
{
  return explode(' ', $val )[0];
}

function getStagesArray( $ajax = 0 )
{
  global $pdo, $dblocation ;

  $stage_arr = [];

        try
        {
            $query = "SELECT * FROM `okb_db_zak_stages` WHERE 1";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        if( $ajax )
        {
          if( $dblocation == "127.0.0.1" )
            $stage_arr [ $row -> id ] = conv( $row -> description );
             else
              $stage_arr [ $row -> id ] = $row -> description ;
        }
              else
               $stage_arr [ $row -> id ] = conv( $row -> description );

   return $stage_arr  ;
}

function MakeLogicData( $pd1 , $pd2 , $pd3 )
{

    return ( $pd1 ? THIRD_POINT : 0 ) | ( $pd2 ? SECOND_POINT : 0 ) | ( $pd3 ? FIRST_POINT : 0 ) ;
}


function getResponsiblePersonsID( $direction )
{
  global $pdo ;

        try
        {
            $query = "SELECT * FROM `okb_db_responsible_persons` WHERE id = $direction";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        $row = $stmt->fetch( PDO::FETCH_OBJ );

   return   json_decode( $row -> persons , true );
}

function getHeadResponsiblePersonsID( $direction )
{
    $arr = getResponsiblePersonsID( $direction );
    $val = array_shift ( $arr );
    return [  $val ];
}

function getCurrentStatus( $id )
{
  global $pdo ;

        try
        {
            $query = "SELECT `ID_stage` AS stage FROM `okb_db_zak` WHERE id = $id " ;

                             $stmt =  $pdo->prepare( $query );
                             $stmt->execute();

        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if( $row = $stmt->fetch( PDO::FETCH_OBJ ))
          return $row -> stage ;
           else
              return 0 ;
}

function getZakType( $id )
{
  global $pdo ;

        try
        {
            $query = "SELECT `TID` AS type FROM `okb_db_zak` WHERE id = $id " ;

                             $stmt =  $pdo->prepare( $query );
                             $stmt->execute();

        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if( $row = $stmt->fetch( PDO::FETCH_OBJ ))
          return $row -> type ;
           else
              return 0 ;
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
    $raw_date = $pd['first_date'];
    if( !strlen( $raw_date ))
      continue ;
    $date = strtotime( $raw_date );
    if( $date >= $from_date && $date <= $to_date)
      $zak_arr[] = $id ;
  }
  return $zak_arr;
}