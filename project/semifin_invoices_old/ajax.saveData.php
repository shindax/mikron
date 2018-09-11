<?php
require_once( "functions.php" );

$data = $_POST['data'];

foreach( $data AS $val )
{
    $id = $val['id'];

   $dse_name = $val['dse_name'];
   $order_name = $val['order_name'];
   $draw_name = $val['draw_name'];

    $inv_num = $val['inv_num'];
    $today = $val['today'];
    $part_num = $val['part_num'];
    $count = $val['count'];
    $transfer_place = $val['transfer_place'];
    $storage_time = $val['storage_time'];
    $note = $val['note'];

     try
        {
                $query =
                "INSERT INTO okb_db_semifinished_store_invoices
                ( id, dse_name,  order_name,  draw_name, inv_num, id_zadan, part_num, count, transfer_place, storage_time, create_date, note, timestamp )
                VALUES
                ( NULL, '$dse_name',  '$order_name',  '$draw_name', $inv_num, $id, '$part_num', $count, '$transfer_place',$storage_time, '$today', '$note', NOW() )" ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
        }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't insert data : " . $e->getMessage().". Query : $query");
            }

}

echo ( $query );
//echo json_encode( $data );