<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function get_wh_place( $wh, $tier_id )
{
	$cell_id = $wh['tiers'][ $tier_id ];
	$wh_id = $wh['cells'][ $cell_id ];
	return [ 'wh' => $wh_id, 'cell' => $cell_id, 'tier' => $tier_id ];
}

function split_name( $name )
{
	$arr = explode("-", $name );
	foreach ( $arr AS $key => $value ) 
	{
		$arr[ $key ] = trim( $arr[ $key ] );
	}

	if( count( $arr ) > 1 )
	{
		$name = $arr[0];
		unset( $arr[0] );
		$draw = join("-", $arr );
	}	
	else
		{	
			$name = "none";
			$draw = $arr[0];
		}

	return[ 'name' => $name , 'draw' => $draw ];
}

function get_warehouse_structure()
{
    global $pdo;

    $cells = [];
    $tiers = [];

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades_item AS cells
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
			$cells[ $row -> ID ] = $row -> ID_sklad ;
            
        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades_yaruses AS tiers
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $tiers[ $row -> ID ] = $row -> ID_sklad_item;

        return [ "cells" => $cells, "tiers" => $tiers ];
} 

function create_invoice_from_wh_detitem( $id_zakdet, $wh_struct, $tier, $id, $count, $dse_name, $dse_draw, $user_id )
{
	global $pdo;

	$place = get_wh_place( $wh_struct, $tier );
	$place['id'] = $id;
	$place['count'] = $count;
	$place = [ $place ];

	$now = new DateTime();
	$today = $now -> format('Y-m-d');

	$dse_name = iconv('windows-1251', 'utf-8', $dse_name );

	try
	{
	    $query = "	INSERT INTO okb_db_semifinished_store_invoices 
	    			( id_zakdet, dse_name, draw_name, count, accepted_by_QCD, storage_place, create_date, user_id )
	    			VALUES( $id_zakdet, '$dse_name', '$dse_draw', $count, $count, '".json_encode( $place ) ."', '$today', $user_id )
	    		 ";

	    $stmt = $pdo -> prepare( $query );
	    $stmt -> execute();

	}
	catch (PDOException $e)
	{
		$err_string = "Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query";
		
		$file = 'log_err.txt';
		file_put_contents( $file, $err_string );

	  	die( $err_string );
	}

	$last_insert_id = $pdo -> lastInsertId();

	try
	{
	    $query = "	UPDATE `okb_db_sklades_detitem` 
	    			SET ref_id = $last_insert_id, KOMM = '".conv( "Накладная №$last_insert_id")."'
	    			WHERE id = $id
	    		 ";
	    $stmt = $pdo -> prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
	}
}

function conv( $str )
{
	return $str; // iconv('utf-8', 'windows-1251', $str );
}

function conv2( $str )
{
	return iconv('utf-8', 'windows-1251', $str );
}

function GetTierId( $rec_id  )
{
	$query = "	 SELECT ID_sklades_yarus
				 FROM okb_db_sklades_detitem
				 WHERE ID = $rec_id";
	
	$result = dbquery( $query );
	$row = mysql_fetch_assoc($result);

    return $row['ID_sklades_yarus'];

} //function GetTierId( $rec_id  )

function GetUserInfo( $user_id  )
{
	$query = "	 SELECT 
				 users.FIO AS user_name, 
				 resurs.GENDER AS gender
				 FROM okb_users AS users
				 LEFT JOIN okb_db_resurs AS resurs ON resurs.ID_users = users.ID
				 WHERE users.ID = $user_id";
	
	$result = dbquery( $query );
	$row = mysql_fetch_assoc( $result );

    return [ 'name' => $row['user_name'], 'gender' => $row['gender'] ];

} // function GetUserInfo( $user_id  )

function FixActionInHistory( $action_id, $user_id, $id_zakdet, $dse_name, $count, $message, $from_tier = 0 , $to_tier = 0 )
{
	$query = "INSERT INTO okb_db_warehouse_action_history 
				( action_type_id, user_id, from_tier, to_tier, id_zakdet, dse_name, count, comment )
				VALUES ( $action_id, $user_id, $from_tier, $to_tier, $id_zakdet, '$dse_name', $count, 
				'$message' )
			  ";
	dbquery( $query );
} //function FixActionInHistory(

function GetDSEName( $rec_id )
{
	$query = "	SELECT 
				detitem.ref_id AS ref_id,
				detitem.NAME AS tier_dse_name,
				inv.dse_name AS ref_dse_name
				FROM okb_db_sklades_detitem AS detitem
				LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id = detitem.ref_id
				WHERE detitem.ID = $rec_id";

	$result = dbquery( $query );
	$row = mysql_fetch_assoc($result);

	$name = "unknown";
	
	if( $row['ref_id'] )
		$name = $row['ref_dse_name'];
			else
				$name = $row['tier_dse_name'];

	return $name;
} //function GetDSEName( $rec_id )

function create_invoices_from_scratch( $pdo, $user_id )
{
	$data = [];
	$wh_struct = get_warehouse_structure();

	try
	{
	    $query = "
	    			SELECT * 
	    			FROM `okb_db_sklades_detitem` 
	    			WHERE ref_id = 0";
	    $stmt = $pdo -> prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

	}

	while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$info = split_name( $row -> NAME );

		$data[ $row -> ID ] = [
								'id' => $row -> ID,
								'tier' => $row -> ID_sklades_yarus,
								'name' => conv( $info['name'] ),
								'draw' => conv( $info['draw'] ),
								'count'=> $row -> COUNT
								];
	}

	foreach ( $data AS $key => $value ) 
	{
		$id = $value['id'];
		$tier = $value['tier'];
		$count = $value['count'];

		create_invoice_from_wh_detitem( 0, $wh_struct, $tier, $id, $count, $value['name'], $value['draw'], $user_id );

	} //foreach ( $data AS $key => $value ) 

	_debug( $data );

} // function create_invoices_from_scratch( $pdo, $user_id )

function get_operation_name( $pdo, $id )
{
	if( $id == 0 )
		return iconv('utf-8', 'windows-1251', "Нет операции" );

	try
	{
	    $query = "
	    			SELECT NAME AS name
	    			FROM `okb_db_oper` 
	    			WHERE ID = $id";
	    $stmt = $pdo -> prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

	}

	$row = $stmt->fetch( PDO::FETCH_OBJ );
	return iconv('utf-8', 'windows-1251', $row -> name );
} 

