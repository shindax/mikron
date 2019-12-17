<?php
error_reporting( E_ALL );
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$id = $_POST['id'];
$tier = $_POST['tier'];
$operation = $_POST['operation'];
$inv_comment = $_POST['comment'];

$dse_name = $_POST['dse_name'];
$order_name = $_POST['order_name'];
$draw_name = $_POST['draw_name'];
$count = $_POST['count'];

$comment = "Из накладной по полуфабрикатам. Операция : $operation. Комментарий : $inv_comment";

$rec_id = 0 ;
$ord = 0 ;
$result = "Not found";

try
{
    $query = "SELECT MAX( ORD ) ord FROM `okb_db_sklades_detitem` 
    		  WHERE ID_sklades_yarus = $tier";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

if( $stmt->rowCount() )
{
	$row = $stmt->fetch(PDO::FETCH_OBJ );
	$ord = 1 + $row -> ord ;
}

try
{
    $query = "SELECT * FROM `okb_db_sklades_detitem` WHERE `ref_id`=$id";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

if( $stmt->rowCount() )
{
	$row = $stmt->fetch(PDO::FETCH_OBJ );
	$rec_id = $row -> ID ;

	try
	{
	    $query = "UPDATE `okb_db_sklades_detitem` 
	    		  SET ORD = $ord, ID_sklades_yarus = $tier, ref_id = $id, KOMM='$comment', COUNT = $count 
	    		  WHERE ID = $rec_id";
		$stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
	}

	$result = "Updated : $query";
}
else
{
	$query = "";
	try
	{
	    $query = "INSERT INTO `okb_db_sklades_detitem` 
	    		  SET NAME='$order_name $dse_name $draw_name', ORD = $ord, ID_sklades_yarus = $tier, ref_id = $id, KOMM='$comment', COUNT = $count ";
		$stmt = $pdo->prepare( $query );
	    $stmt->execute();
		$rec_id = $pdo->lastInsertId();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
	}

	$result = "Inserted and updated";
}

try
{
    $query = "UPDATE okb_db_semifinished_store_invoices 
    		  SET warehouse_item_id	 = $rec_id
    		  WHERE id = $id";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}


echo $result;

