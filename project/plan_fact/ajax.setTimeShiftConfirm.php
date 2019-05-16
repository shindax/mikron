<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST['id'];
$field = $_POST['field'];
$state = $_POST['state'];

try
{
    $query = "UPDATE okb_db_zak_ch_date_history
    SET confirmed = $state
    WHERE 
    id=$id
    AND
    pd='$field'
    " ;
    
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

echo $query;