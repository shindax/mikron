<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo;

$obj = $_POST['obj'];
$num = $_POST['num'];
$user_id = $_POST['user_id'];

$cur_year = date("Y") ;
$today = date("Y-m-d") ;

foreach( $obj AS $item )
{
    $id_zadan = $item['id'];
        if( !strlen( $id_zadan ) )
            $id_zadan = 0 ;

    $dse_name = $item['dse_name'];
    $order_name = $item['order_name'];
    $draw_name = $item['draw_name'];
    $operation_id = $item['operation_id'];
    $part_num = $item['part_num'];
    
    $count = $item['count'];
        if( !strlen( $count ) )
            $count = 0 ;

    $transfer_place = $item['transfer_place'];
    $storage_time = $item['storage_time'];
    $note = $item['note'];

    try
    {
        $query = "INSERT INTO `okb_db_semifinished_store_invoices`
                 SET 
                 inv_num = $num ,
                 id_zadan = $id_zadan,
                 dse_name = '$dse_name',
                 order_name = '$order_name',
                 draw_name = '$draw_name',
                 part_num = '$part_num',
                 count = $count,
                 transfer_place = '$transfer_place',
                 storage_time = $storage_time,
                 warehouse_item_id = 0,
                 operation_id = $operation_id,
                 note = '$note',
                 create_date = '$today',
                 user_id = $user_id
                  ";
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }
}

echo $num;

