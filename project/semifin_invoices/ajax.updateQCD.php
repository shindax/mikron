<?php
error_reporting( E_ALL );
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$id = $_POST['id'];
$count = $_POST['count'];

try
{
    $query = "UPDATE okb_db_semifinished_store_invoices 
    		  SET accepted_by_QCD	 = $count, count = $count
    		  WHERE id = $id";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

echo $result;

