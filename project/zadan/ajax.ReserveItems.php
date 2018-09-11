<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$ids = $_POST['ids'];
$counts = $_POST['counts'];
$user_id = $_POST['user_id'];

$result = '';

foreach( $ids AS $key => $val )
{
	$count = $counts[ $key ];
	// try
	// {
	//     $query ="SELECT * FROM `okb_db_warehouse_reserve` WHERE tier_id = $val " ;
	//     $stmt = $pdo->prepare( $query );
	//     $stmt -> execute();
	// }
	// catch (PDOException $e)
	// {
	//    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	// }
	
	// if( $stmt -> rowCount() )
	// {
	// 	try
	// 	{
	// 	    $query ="UPDATE `okb_db_warehouse_reserve` SET count = $count WHERE tier_id = $val " ;
	// 	    $stmt = $pdo->prepare( $query );
	// 	    $stmt -> execute();
	// 	}
	// 	catch (PDOException $e)
	// 	{
	// 	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	// 	}
	// 	$result .= "record $val found and updated <br>";
	// }
	// else
	{
		try
		{
		    $query ="INSERT INTO `okb_db_warehouse_reserve` 
					 ( id, tier_id, count, user_id, timestamp )
					 VALUES
					 ( null, $val, $count, $user_id, NOW())" ;
		    $stmt = $pdo->prepare( $query );
		    $stmt -> execute();
		}
		catch (PDOException $e)
		{
		   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
		}

		$result .= "record $val was not found <br>";
	}
}

echo $result;