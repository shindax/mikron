<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
// error_reporting(0);
error_reporting(E_ALL);
// ini_set('display_errors', false);

global $pdo;

$data = isset( $_POST['data'] ) ? $_POST['data'] : [];
// const IGNORE_OPERATION = true ;

function getBasketCount( $id_zakdet, $operation_id, $pattern )
{
	global $pdo ;
	$count = 0 ;

	try
		{
		    $query ="SELECT count AS count
		    		FROM `okb_db_warehouse_dse_basket` 
		    		WHERE pattern = '$pattern'";
		    $stmt = $pdo->prepare( $query );
		    $stmt -> execute();

		}
		catch (PDOException $e)
		{
		   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
		}

		if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
			$count = $row -> count ;

	return $count ;
}

foreach ( $data AS $key => $value ) 
{
	$pattern = $value['pattern'];
	$zakdet_id = $value['zakdet_id'];

	try
	{
	    $query ="
	    SELECT count
		FROM okb_db_warehouse_reserve
		WHERE pattern = '$pattern'";

	    $stmt = $pdo->prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}

	$reserved = 0 ;
	
	if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
		$reserved = $row -> count ;

	try
	{
	    $query ="
	    SELECT
	    	id AS id,
	    	operation_id AS operation_id,
	    	accepted_by_QCD AS count, 
	    	storage_place AS storage_place
		FROM okb_db_semifinished_store_invoices AS inv
		WHERE ID IN ( SELECT ref_id FROM okb_db_sklades_detitem WHERE NAME LIKE '%$pattern%' )";

	    $stmt = $pdo->prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}


	while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$count = 0 ;
		$storage_place = json_decode( $row -> storage_place, true );
		foreach ( $storage_place AS $value ) 
			$count += $value['count'];

		$basket_count = getBasketCount( $zakdet_id, $row -> operation_id, $pattern );
		
		if( isset( $data[ $key ]['count'] ) )
			$data[ $key ]['count'] += $count - $reserved - $basket_count;
			else
				$data[ $key ]['count'] = $count - $reserved - $basket_count;

		$data[ $key ]['inv_id'] = $row -> id;
		$data[ $key ]['operation_id'] = $row -> operation_id;
		$data[ $key ]['pattern'] = $pattern;
	}
}

foreach ( $data AS $key => $value ) 
	if( !isset( $data[ $key ]['count'] ) )
			unset( $data[ $key ] );

echo json_encode( $data );

