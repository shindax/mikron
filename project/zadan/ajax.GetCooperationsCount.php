<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting(0);
ini_set('display_errors', false);

$operitems_id = $_POST['operitems_id'];
$row_count = 0 ;

try
{
    $query ="
				SELECT 
				COUNT(*) AS count 
				FROM `okb_db_operations_with_coop_dep` 
				WHERE 
				oper_id = $operitems_id
			" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

 if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	$row_count = $row -> count ;

try
{
    $query ="
				SELECT 
				SUM(count) AS total_count
				FROM `okb_db_operations_with_coop_dep` 
				WHERE 
				oper_id = $operitems_id
			" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

 if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	$row_total_count = $row -> total_count ;

echo "$row_count / $row_total_count";