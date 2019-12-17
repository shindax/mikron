<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting(0);
ini_set('display_errors', false);

global $pdo;


$arr = $_POST['arr'];
$user_id = $_POST['user_id'];

$str="";

foreach( $arr AS $key => $value )
{
	$id_zakdet = $value['id_zakdet'];
	$count = $value['count'];
	$operation_id = $value['operation_id'];
	$pattern = $value['pattern'];	

	try
	{
		$query ="	SELECT id, count
					FROM okb_db_warehouse_dse_basket 
					WHERE 
					id_zakdet = $id_zakdet
					AND 
					operation_id = $operation_id
					AND
					pattern = '$pattern'
					";

		echo $query;
	    $stmt = $pdo->prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	}
	
	if( $stmt -> rowCount() )
	{
		$row = $stmt->fetch( PDO::FETCH_OBJ );
		$id = $row -> id ;
		$count += $row -> count ;

		try
		{
			$query = "	UPDATE okb_db_warehouse_dse_basket 
						SET count = $count
						WHERE id = $id
						";
		    $stmt = $pdo->prepare( $query );
		    $stmt -> execute();
		}
		catch (PDOException $e)
		{
		   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
		}	
	}
	else
	{
		try
		{
			$query ="INSERT INTO okb_db_warehouse_dse_basket ( id_zakdet, count, operation_id, pattern, user_id, timestamp ) VALUES ( $id_zakdet, $count, $operation_id, '".($pattern)."', $user_id, NOW())";

		    $stmt = $pdo->prepare( $query );
		    $stmt -> execute();
		}
		catch (PDOException $e)
		{
		   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
		}
	}

}

echo + $stmt -> rowCount();
