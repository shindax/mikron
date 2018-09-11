<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$data = array();
$error = false;
$zak_id = $_POST['id'];
$tags = [];

      try
      {
          $query ="
                        SELECT
                        `ID` id,
                        `NAME` dse_name,
                        `OBOZ` dse_draw
                        FROM `okb_db_zakdet`
                        WHERE `ID_zak` = $zak_id";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $tags[] = [ 'label' => $row -> dse_name." ".$row -> dse_draw, 'value' => $row -> id ];

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $tags );
?>