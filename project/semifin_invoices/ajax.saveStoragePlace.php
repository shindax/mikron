<?php
require_once( "functions.php" );

function FindOrCreate( $id, $in_id, $tier, $count, $pattern = '' )
{
	global $pdo ;

        try
        {
            $query = "SELECT id FROM `okb_db_sklades_detitem` 
            WHERE 
            id = $in_id
            ";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
        }

        if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
			$res_id = $row -> id ;
			try
		        {
		            $query = "UPDATE `okb_db_sklades_detitem` 
		            SET ID_sklades_yarus = $tier, COUNT = $count, NAME = '$pattern'
		            WHERE 
		            id = $res_id
		            ";
		            $stmt = $pdo -> prepare( $query );
		            $stmt -> execute();
		        }
		        catch (PDOException $e)
		        {
		          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
		        }        	
        }
        else
        {
			try
			{
			    $query = "	INSERT INTO okb_db_sklades_detitem 
			    			( NAME, ID_sklades_yarus, KOMM, COUNT, ref_id, ORD ) 
			    			VALUES ( '$pattern', $tier, 'Из накладной по полуфабрикатам', $count , $id, 0 )
			              ";
			    $stmt = $pdo->prepare( $query );
			    $stmt->execute();
			}
			catch (PDOException $e)
			{
			  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
			}

			$res_id = $pdo -> lastInsertId();
        }
     
     return $res_id ;
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

$id = $_POST['id'];
$data = $_POST['data'];
$user_id = $_POST['user_id'];

$user_info = GetUserInfo( $user_id  );
$user_name = $user_info['name'];
$gender = $user_info['gender'];

try
{
    $query = "	SELECT dse_name, order_name, id_zakdet
    			FROM okb_db_semifinished_store_invoices
              	WHERE id = $id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$dse_name = $row -> dse_name;
$order_name = $row -> order_name;
$id_zakdet = $row -> id_zakdet;
$total_count = 0 ;

foreach ( $data AS $key => $value ) 
{
	$rec_id = $data[ $key ]['id'];
    $draw_name = $data[ $key ]['draw_name'];
	$tier = $data[ $key ]['tier'];
	$count = $data[ $key ]['count'];
	$total_count += $count ;
	$data[ $key ]['id'] = FindOrCreate( $id, $rec_id, $tier, $count, $draw_name );

    if( $gender == 1 || $gender == 0 )
        $action = "распределил ДСЕ :";
            else
                $action = "распределила ДСЕ :";
	
	$message = "$user_name $action $dse_name, заказ : $order_name в количестве $count шт.";
	FixActionInHistory( WH_POPULATE, $user_id, $id_zakdet, $dse_name, $count, $message, 0, $tier );
}

$json_data = json_encode( $data, JSON_UNESCAPED_UNICODE );

try
{
    $query = "UPDATE okb_db_semifinished_store_invoices
              SET storage_place = '$json_data', count = $total_count
              WHERE id = $id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
}

echo $total_count;
