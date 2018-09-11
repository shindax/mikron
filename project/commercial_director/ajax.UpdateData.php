<?php
header('Content-Type: text/html');
error_reporting( 0 );

// error_reporting( E_ALL );
// ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$data = $_POST[ 'data' ];
$id = $_POST[ 'id' ];
$field = $_POST[ 'field' ];

$str = $id. " : ". $rate ;

  global $pdo ;

      try
      {
          $query = "UPDATE `okb_db_plan_fact_carry_causes`
          SET `$field` = '$data'
          WHERE
          id=$id
          " ;
          $stmt = $pdo->prepare( $query );
          $stmt->execute();
      }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
      }

echo $query;