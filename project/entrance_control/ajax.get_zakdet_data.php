<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$data = array();
$error = false;
$id = $_POST['id'];
$data = [];

      try
      {
          $query ="
                        SELECT
                        `NAME` dse_name,
                        `OBOZ` dse_draw
                        FROM `okb_db_zakdet`
                        WHERE `ID` = $id";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $data = [ 'dse_name' => $row -> dse_name, 'dse_draw'=> $row -> dse_draw ];

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $data );
