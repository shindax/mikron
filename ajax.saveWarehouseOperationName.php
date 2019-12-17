<?php
error_reporting( 0 );
error_reporting( E_ALL );

require_once( "wh_functions.php" );

$user_id = $_POST['user_id'];
$ref_id = $_POST['ref_id'];
$op_id = $_POST['op_id'];
$old_op_id = $_POST['old_op_id'];
$tier_item_id = $_POST['tier_item_id'];

try
{
    $query =
    " SELECT ID_sklades_yarus
      FROM okb_db_sklades_detitem
      WHERE id = $tier_item_id" ;
  
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$tier_id = $row -> ID_sklades_yarus;

try
{
    $query =
    " SELECT inv.dse_name, inv.id_zakdet, inv.count, detitem.NAME AS dse_detitem_name
      FROM okb_db_semifinished_store_invoices AS inv
      LEFT JOIN okb_db_sklades_detitem AS detitem ON detitem.ref_id = inv.id
      WHERE inv.id = $ref_id" ;
  
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$zakdet_id = $row -> id_zakdet ;
$dse_name = $row -> dse_name ;
$count = $row -> count ;

if( $dse_name == 'none')
  $dse_name = $row -> dse_detitem_name;

$old_operation_name = get_operation_name( $pdo, $old_op_id );
$new_operation_name = get_operation_name( $pdo, $op_id );

try
{
    $query =
    " UPDATE okb_db_semifinished_store_invoices 
      SET operation_id = $op_id
      WHERE id = $ref_id" ;
   
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
   
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$comm = iconv('windows-1251', 'utf-8', "c ".get_operation_name( $pdo, $old_op_id )).conv(" на ").iconv('windows-1251', 'utf-8', get_operation_name( $pdo, $op_id ));

try
{
$query = "INSERT INTO okb_db_warehouse_action_history 
        ( action_type_id, user_id, id_zakdet, dse_name, count, comment, from_tier )
        VALUES ( ".WH_OPERATION_EDIT.", $user_id, $zakdet_id, '$dse_name', $count, 
        '$comm', $tier_id )";
  
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

echo "$new_operation_name" ;

?>