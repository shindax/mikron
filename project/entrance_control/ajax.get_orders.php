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
                          ID,
                          NAME
                          FROM `okb_db_zak`
                          WHERE
                          LEFT(NAME,2) >= 15                          
                          OR
                          NAME REGEXP '^[А-С]'
                          # AND EDIT_STATE = 0 
                          ORDER BY NAME
                      ";
                      
//   AND EDIT_STATE = 0
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $tags [] = [ 'label' => $row -> NAME, 'value' => $row -> ID ];

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $tags );
?>