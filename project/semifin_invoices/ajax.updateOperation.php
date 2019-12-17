<?php
//error_reporting( E_ALL );
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$rec_id = $_POST['id'];
$operation_id = $_POST['operation_id'];

$result = "OK";

try
{
    $query = "SELECT inv.warehouse_item_id warehouse_item_id, oper.NAME oper_name, inv.note note
    		  FROM okb_db_semifinished_store_invoices inv
    		  INNER JOIN okb_db_oper oper ON oper.ID = $operation_id
    		  WHERE inv.ID = $rec_id";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

$row = $stmt->fetch(PDO::FETCH_OBJ );
$warehouse_item_id = $row -> warehouse_item_id ;
$operation = $row -> oper_name ;
$inv_comment = $row -> note ;

try
{
    $query = "UPDATE okb_db_semifinished_store_invoices 
    		  SET operation_id = $operation_id
    		  WHERE ID = $rec_id";
	$stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

if( $warehouse_item_id )
{
	$comment = "Из накладной по полуфабрикатам. Операция : $operation. Комментарий : $inv_comment";

	try
	{
	    $query = "UPDATE `okb_db_sklades_detitem` 
	    		  SET KOMM = '$comment'
	    		  WHERE ID = $warehouse_item_id";
		$stmt = $pdo->prepare( $query );
	    $stmt->execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
	}
}

echo $oper_name;

