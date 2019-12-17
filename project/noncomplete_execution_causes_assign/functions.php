<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

function GetUsersOptions()
{
	global $pdo;

	$list = "";

    try
    {
        $query ="
                    SELECT ID, NAME 
                    FROM `okb_db_resurs` 
                    WHERE 
                    TID=0
                    AND
                    ID_users <> 0
                    AND
                    ID <> 428 # Шендаков
                    AND
                    ID <> 512 # Пименов
                    AND
                    ID <> 637 # Носырев
                    AND
                    ID <> 964 # Хрустов
                    ORDER BY NAME
                    ";
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
    }

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
        $name = $row -> NAME ;
        if( strlen( $name ) && $name != "Вакансия ..")
            $list .= "<option value='". ( $row -> ID )."'>".conv( $name )."</option>";
    }

   return $list ;
}

function GetNoncompleteExecutionCauses()
{
	global $pdo ;
	$data = [];

	try
	{
	    $query = "	SELECT 
					nec.id AS rec_id, 
					nec.description , 
					res.NAME AS res_name, 
					res.ID AS res_id
					FROM noncomplete_execution_causes nec
					LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(nec.responsible_res_id, CAST( res.id AS JSON ), '$')
					WHERE 1
					ORDER BY rec_id, res.NAME
				";
	    $stmt = $pdo -> prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
	}

	while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$data[ $row -> rec_id ][ 'description' ] = conv( $row -> description ) ;
		$data[ $row -> rec_id ]['persons'][ $row -> res_id ] = conv( $row -> res_name ) ;
	}

	return $data ;
}

////////////////////////////////////////////////////////////////////////////////

function GetNoncompleteExecutionCauseExplanation()
{
    global $pdo ;
    $data = [];

    try
    {
        $query = "  SELECT *
                    FROM noncomplete_execution_cause_explanations
                    WHERE 1
                ";
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }

    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        $data[ $row -> cause_id ][ $row -> id ] = conv( $row -> description ) ;

    return $data ;
} // function GetNoncompleteExecutionCauseExplanation()

////////////////////////////////////////////////////////////////////////////////

function GetResInfo( $user_id )
{
	global $user, $pdo;

    try
    {
       $query ="SELECT ID, NAME FROM `okb_db_resurs` WHERE ID_users = $user_id";
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $res_id = 0 ;
    
    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $res_id = $row -> ID;

    return $res_id ;
 }
