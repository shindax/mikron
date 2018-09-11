<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php" );

$data = array();
$error = false;
$id = $_POST['id'];
$field = $_POST['field'];
$result = '';

      try
      {
          $query ="
                        SELECT $field AS comment FROM `okb_db_entrance_control_items`
                        WHERE `id` = $id";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
        }
           if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $result = conv( $row -> comment );

echo $result ;
?>