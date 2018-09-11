<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST['id'] ;


      try
      {
          $query ="DELETE FROM `okb_db_warehouse_reserve` WHERE ID=$id";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

echo $id;