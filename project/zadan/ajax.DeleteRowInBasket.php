<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting(0);
ini_set('display_errors', false);

$id = $_POST["id"];

try
{
    $query ="DELETE FROM okb_db_warehouse_dse_basket WHERE id = $id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}


echo $query;