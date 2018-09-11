<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$data = array();
$error = false;

$tags = [];

      try
      {
          $query ="
                    SELECT 
                    detitem.ID id, 
                    detitem.NAME name, 
                    detitem.KOMM, 
                    detitem.COUNT, 
                    inv.inv_num,
                    tier.NAME tier_name,
                    cell.NAME cell_name,
                    warehouse.NAME warehouse_name
                    FROM `okb_db_sklades_detitem` detitem
                    INNER JOIN okb_db_sklades_yaruses tier ON tier.ID = detitem.ID_sklades_yarus
                    INNER JOIN okb_db_sklades_item cell ON cell.ID = tier.ID_sklad_item
                    INNER JOIN okb_db_sklades warehouse ON warehouse.ID = cell.ID_sklad
                    INNER JOIN okb_db_semifinished_store_invoices inv ON inv.ID = detitem.ref_id
                    WHERE 
                    ref_id<>0 
                    AND 
                    detitem.COUNT <> 0
                    ORDER BY name
                      ";
                      
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $tags [] = [ 
                        'label' => $row -> name, 
                        'value' => $row -> name,
                        'id' => $row -> id,
                        'comment' => $row -> KOMM,
                        'count' => $row -> COUNT,
                        'inv_num' => $row -> inv_num,
                        'tier_name' => $row -> tier_name,                        
                        'cell_name' => $row -> cell_name,
                        'warehouse_name' => $row -> warehouse_name,
                        ];

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $tags );
?>