<?php
header('Content-Type: text/html');
error_reporting( 0 );

// error_reporting( E_ALL );
// ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$ord = $_POST[ 'ord' ];
$field = $_POST[ 'field' ];
$data = $_POST[ 'data' ];

  global $pdo ;

      try
      {
          $query = "UPDATE `coordination_pages_rows`
          SET `$field` = '$data'
          WHERE
          ord=$ord
          " ;
          $stmt = $pdo->prepare( $query );
          $stmt->execute();
      }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
      }

echo $query;