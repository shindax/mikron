<?php
// error_reporting( E_ALL );
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$user_id = $_POST['user_id'];
$arr = $_POST['arr'];

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

try
{
    $query = "SELECT MAX(batch) AS batch FROM okb_db_warehouse_reserve WHERE 1";
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$batch = $row -> batch;

foreach ( $arr AS $key => $value ) 
{
	$batch ++ ;

	$operation_id = $value['operation_id'] ? $value['operation_id'] : 0;
	$id_zakdet = $value['id_zakdet'] ? $value['id_zakdet'] : 0 ;
	$id_zadan = $value['id_zadan'] ? $value['id_zadan'] : 0 ;
	$iss_count = $value['count'];
	$comment = $value['comment'];
	$pattern = $value['pattern'];

			try
			{
			    $query ="INSERT INTO `okb_db_warehouse_reserve` 
						 ( 
						 	batch,
						 	id_zakdet, 
						 	id_zadan, 
						 	count, 
						 	operation_id,
						 	pattern,
						 	comment, user_id, timestamp )
						 VALUES
						 ( 
						 	$batch,
						 	$id_zakdet, 
						 	$id_zadan, 
						 	$iss_count, 
						 	$operation_id, 
						 	'$pattern',
						 	'$comment', 
						 	$user_id, 
						 	NOW())" ;

				echo $query;
			    $stmt = $pdo->prepare( $query );
			    $stmt -> execute();
			}
			catch (PDOException $e)
			{
			   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
			}
}



echo $str;
