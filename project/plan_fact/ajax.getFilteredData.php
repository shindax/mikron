<?php
error_reporting( 0 );

require_once( "functions.php" );
require_once( "class.PlanFactCollector.php" );

function GetSplitDate( $date )
{
   if( $date == 0 )
    return '';

  $year = substr( $date, 6, 4 );
  $month = substr( $date, 3, 2 );
  $day = substr( $date, 0, 2 );
  return 1 * $year.$month.$day;
}

$stage = $_POST['stage'];
$status = $_POST['status'];
$radio = $_POST['radio'];
$ord_type = $_POST['ord_type'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];
$user_id = $_POST['user_id'];

$stage_range = '';

if( count( $stage ) )
{
  $stage_range .= "  ID_stage IN ( ";
  foreach( $stage AS $val )
    $stage_range .= "$val,";

  $stage_range = substr( $stage_range, 0, -1).") ";
}

$status_range = '';

if( count( $status ) )
{
  $status_range .= "  ID_status IN ( ";
  foreach( $status AS $val )
    $status_range .= "$val,";

  $status_range = substr( $status_range, 0, -1).") ";
}

$orders = new PlanFactCollector( $user_id, $dblocation, $dbname, $dbuser, $dbpasswd, 1 );
$query = $orders -> getQuery();
$where = $orders -> getWhere();
$order = $orders -> getOrder();

if(
    count( $stage ) == 0 &&
    count( $status ) == 0 &&
    strlen( $from_date ) == 0 &&
    strlen( $to_date ) == 0 &&
    $radio == 0 &&
    $ord_type == 0
)
{
  $orders -> collectRawData();
}
else
{

$ord_type_where = "";

switch( $ord_type  )
{
  case 1:
                          $ord_type_where = "( TID IN ( 1, 2, 3, 6 ) )";
                          break;
  case 2:
                          $ord_type_where = "( TID IN ( 5 ) )";
                          break;
  case 3:
                          $ord_type_where = "( TID IN ( 4 ) )";
                          break;
}

$spl_from_date = GetSplitDate( $from_date );
$spl_to_date = GetSplitDate( $to_date );

$radio1 = '';
$radio2 = '';
$radio3 = '';
$radio4 = '';
$radio5 = '';

switch( $radio  )
{
  case 1:               break;

  case 2:
                        if( strlen( $spl_from_date ) && strlen( $spl_to_date ) )
                          $radio2 = "( DATE >= $spl_from_date AND DATE <= $spl_to_date )";

                        if( strlen( $spl_from_date ) && !strlen( $spl_to_date ) )
                          $radio2 = "( DATE >= $spl_from_date )";

                        if( !strlen( $spl_from_date ) && strlen( $spl_to_date ) )
                          $radio2 = "( DATE <= $spl_to_date )";

                        if( !strlen( $spl_from_date ) && !strlen( $spl_to_date ) )
                          $radio2 = '';

                        break;

  case 3:
                          if( strlen( $spl_from_date ) && strlen( $spl_to_date ) )
                          $radio3 = "( DATE_PLAN >= $spl_from_date AND DATE_PLAN <= $spl_to_date )";

                        if( strlen( $spl_from_date ) && !strlen( $spl_to_date ) )
                          $radio3 = "( DATE_PLAN >= $spl_from_date )";

                        if( !strlen( $spl_from_date ) && strlen( $spl_to_date ) )
                          $radio3 = "( DATE_PLAN <= $spl_to_date )";

                        if( !strlen( $spl_from_date ) && !strlen( $spl_to_date ) )
                          $radio3 = '';

                          break;

case 4:                  // PD12 - начало производства
                          $arr = GetOrdersByFieldInDateIntervalStart( "PD12" ,$from_date, $to_date );
                          $list = join(',', $arr );
                          if( !count( $arr ))
                            $list = "0";
                          $radio4 = "( okb_db_zak.ID IN ($list) ) ";

                          break;

case 5:                  // PD8 - окончание производства
                          $arr = GetOrdersByFieldInDateIntervalStart( "PD8" ,$from_date, $to_date );
                          $list = join(',', $arr );
                          if( !count( $arr ))
                            $list = "0";
                          $radio5 = "( okb_db_zak.ID IN ($list) ) ";
                          break;
}

if( strlen( $stage_range ) )
  $where .= " AND $stage_range ";

if( strlen( $status_range ) )
  $where .= " AND $status_range ";

if( $radio == 1 && strlen( $radio1 ) )
  $where .= " AND $radio1 ";

if( $radio == 2  && strlen( $radio2 ) )
  $where .= " AND $radio2 ";

if( $radio == 3  && strlen( $radio3 ) )
  $where .= " AND $radio3 ";

if( $radio == 4  && strlen( $radio4 ) )
  $where .= " AND $radio4 ";

if( $radio == 5  && strlen( $radio5 ) )
  $where .= " AND $radio5 ";

if( $ord_type == 4 )
   $where .= " HAVING kd_state = 0 ";

if( strlen( $ord_type_where ) )
  $where .= " AND $ord_type_where ";

// $file = 'log.txt';
// file_put_contents($file, "$ord_type $query $where $order" );

$orders -> collectRawData( "$query $where $order" );
}

$result = $orders -> getTable();
echo $result ;

