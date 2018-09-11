<?php
error_reporting( 0 );

require_once( "functions.php" );

$id = $_POST['id'];
$status = $_POST['status'];

try
{
    $query = "UPDATE okb_db_zak  
    SET ID_status = $status
    WHERE 
    ID=$id" ;
    
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

echo $query;