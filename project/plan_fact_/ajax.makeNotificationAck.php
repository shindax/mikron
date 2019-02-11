<?php
error_reporting( 0 );
require_once( "functions.php" );

$id = $_POST['id'];
$where = 1 ;

if( isset( $_POST['where'] ) )
  $where = $_POST['where'];

try
{
    $query = "
                      UPDATE `okb_db_plan_fact_notification` SET `ack`= $where
                      WHERE id = $id
                      " ;

                      $stmt =  $pdo->prepare( $query );
                      $stmt->execute();

}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

echo $id;