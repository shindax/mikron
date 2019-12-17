<?php
require_once( "functions.php" );

global $pdo;

$obj = $_POST['obj'];
$num = $_POST['num'];
$user_id = $_POST['user_id'];

$user_info = GetUserInfo( $user_id  );
$user_name = $user_info['name'];
$gender = $user_info['gender'];

$cur_year = date("Y") ;
$today = date("Y-m-d") ;

foreach( $obj AS $item )
{
    $id_zadan = $item['id'];
    $id_zakdet = $item['zakdet_id'];    
        if( !strlen( $id_zadan ) )
            $id_zadan = 0 ;

    $dse_name = $item['dse_name'];
    $order_name = $item['order_name'];
    $draw_name = $item['draw_name'];
    $operation_id = $item['operation_id'];
    $master_id = $item['master_id'];
    $part_num = $item['part_num'];
    
    $count = $item['count'];
        if( !strlen( $count ) )
            $count = 0 ;

    $transfer_place = $item['transfer_place'];
    $storage_time = $item['storage_time'];
    $note = $item['note'];

    try
    {
        $query = "INSERT INTO okb_db_semifinished_store_invoices
                 SET 
                 id_zadan = $id_zadan,
                 id_zakdet=$id_zakdet,
                 dse_name = '$dse_name',
                 order_name = '$order_name',
                 draw_name = '$draw_name',
                 part_num = '$part_num',
                 count = $count,
                 host_master_id = $master_id,
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

    $last_insert_id = $pdo -> lastInsertId();

    try
    {
        $query = "  UPDATE okb_db_semifinished_store_invoices
                    SET inv_num = $last_insert_id
                    WHERE id = $last_insert_id
                  ";
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }


    if( $gender == 1 || $gender == 0 )
        $action = "передал на склад ДСЕ :";
            else
                $action = "передала на склад ДСЕ :";

    $message =  "$user_name $action $dse_name. Заказ : $order_name.  в количестве {$count}шт." ;
    
    FixActionInHistory( WH_RECEIVED_FROM_SHIFT_ORDER, $user_id, $id_zakdet, $dse_name, $count, $message );

} // foreach( $obj AS $item )

echo $num;


