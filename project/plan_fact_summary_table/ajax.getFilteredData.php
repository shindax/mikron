<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PlanFactSummaryTable.php" );
require_once( "functions.php" );
date_default_timezone_set("Asia/Krasnoyarsk");

error_reporting( 0 );
ini_set('display_errors', false );

function getArg( $var )
{
   return isset( $var ) ? $var : 0 ;
}

function getStringArg( $var )
{
   return strlen( $var ) ? $var : 0 ;
}

$stage = getArg( $_POST['stage'] )  ;
$status = getArg( $_POST['status'] );
$ord_type = getArg( $_POST['ord_type'] );
$date_filter = getArg( $_POST['date_filter'] );
$from_date = getStringArg( $_POST['from_date'] );
$to_date = getStringArg( $_POST['to_date'] );

$filter = " EDIT_STATE = 0";

if( $stage )
  $filter .=  " AND ID_stage IN ( ". join(", ", $stage )." )";

if( $status )
  $filter .=  " AND ID_status IN ( ". join(", ", $status )." )";

if( $ord_type )
  switch( $ord_type )
      {
          case 1 :      $filter .=  " AND TID IN ( 1, 2, 3, 6 ) "; break ;
          case 4 :      $filter .=  " AND TID = 4 "; break ;
          case 5 :      $filter .=  " AND TID = 5 "; break ;
      }


$out_str = "";

// Получить ставку штрафа за просрочку
$rate = getPenaltyRate( 1 );

$table = new PlanFactSummaryTable( $pdo, 1, $rate, $filter, $from_date,$to_date );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 2, $rate, $filter, $from_date,$to_date );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 4 ,$rate, $filter, $from_date,$to_date );
$out_str .= $table -> GetTable() ;
$table = new PlanFactSummaryTable( $pdo, 5 ,$rate, $filter, $from_date,$to_date );
$out_str .= $table -> GetTable() ;

//echo iconv("Windows-1251", "UTF-8", $out_str );
echo $out_str;
