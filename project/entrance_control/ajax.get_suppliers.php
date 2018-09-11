<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$data = array();
$error = false;
$clients = [];

      try
      {
          $query ="
                          SELECT
                          ID id,
                          NAME name
                           FROM `okb_db_clients`
                           WHERE 1
                           ORDER BY NAME
                        ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
           {
              $name = $row -> name ;

              $clients[] = [ 'label' => str_replace( '&quot;', '"', $name ), 'value' => $row -> id ];
           }

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $clients );
?>