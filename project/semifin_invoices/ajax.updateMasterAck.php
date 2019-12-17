<?php
error_reporting( E_ALL );
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
date_default_timezone_set("Asia/Krasnoyarsk");

global $pdo;

$id = $_POST['rec_id'];
$now = new DateTime();
$mysql_time = $now->format('Y-m-d H:i');
$table_time = $now->format('d.m.Y H:i');

try
{
    $query = "UPDATE okb_db_semifinished_store_invoices 
    		  SET 
    		  	host_master_ack = 1,
    		  	host_master_ack_datetime = '$mysql_time'
    		  WHERE id = $id";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

echo $table_time;

