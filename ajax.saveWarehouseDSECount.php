<?php
error_reporting( 0 );
error_reporting( E_ALL );

require_once( "wh_functions.php" );

function FixActionInHistoryPDO( $pdo, $action_id, $user_id, $id_zakdet, $dse_name, $count, $message, $from_tier = 0 , $to_tier = 0 )
{

try
{
  $query = "INSERT INTO okb_db_warehouse_action_history 
        ( action_type_id, user_id, from_tier, to_tier, id_zakdet, dse_name, count, comment )
        VALUES ( $action_id, $user_id, $from_tier, $to_tier, $id_zakdet, '$dse_name', $count, 
        '$message' )
        ";
   
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}
} //function FixActionInHistory(

$user_id = $_POST['user_id'];
$ref_id = $_POST['ref_id'];
$tier_item_id = $_POST['tier_item_id'];
$count = $_POST['count'];
$old_count = $_POST['old_count'];
$tier_id = 0 ;
$zakdet_id = 0 ;
$dse_name = "";

try
{
    $query =
    " UPDATE okb_db_sklades_detitem 
      SET COUNT = '$count' 
      WHERE id = $tier_item_id" ;
   
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

try
{
    $query =
    " SELECT dse_name, id_zakdet, storage_place
      FROM okb_db_semifinished_store_invoices
      WHERE id = $ref_id" ;
  
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$storage_place = json_decode( $row -> storage_place, true );
$zakdet_id = $row -> id_zakdet ;
$dse_name = $row -> dse_name ;

$new_count = 0 ;

foreach ( $storage_place AS $key => $value ) 
{
    if( $storage_place[ $key ]['id'] == $tier_item_id )
    {
      $storage_place[ $key ]['count'] = $count;
      $tier_id = $storage_place[ $key ]['tier'];
    }

    $new_count += $storage_place[ $key ]['count'] ;
}

try
{
    $query =
    " UPDATE okb_db_semifinished_store_invoices 
      SET storage_place = '".json_encode( $storage_place )."',
      count = $new_count, accepted_by_QCD = $new_count
      WHERE id = $ref_id" ;
   
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

try
{
    $query =
    " SELECT 
      users.FIO AS user_name, 
      resurs.GENDER AS gender
      FROM okb_users AS users
      LEFT JOIN okb_db_resurs AS resurs ON resurs.ID_users = users.ID
      WHERE users.ID = $user_id" ;
  
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$user_name = conv( $row -> user_name );
$user_gender = $row -> gender;

if( $user_gender == 1 || $user_gender == 0 )
  $action = conv("отредактировал количество ДСЕ с $old_count шт. на ".$count."шт");
   else
    $action = conv("отредактировала количество ДСЕ $dse_name с $old_count шт. на ".$count."шт");

$comm = "$user_name $action";

FixActionInHistoryPDO( $pdo, WH_DATA_EDIT, $user_id, $zakdet_id, $dse_name, $count, $comm, $old_count, $tier_id );

echo $count;

?>