<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

// error_reporting( E_ALL );
// global $pdo ;

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function map_tree($dataset) 
{
	$tree = [];

	foreach ($dataset as $id=>&$node) {    
		if (!$node['parent_id']){
			$tree[$id] = &$node;
		}else{ 
            $dataset[$node['parent_id']]['childs'][$id] = &$node;
		}
	}

	return $tree;
}

function sybcycle( $data, $parent_id, $base_id )
{
	global $pdo; 

	foreach( $data AS $key => $val )
	{
		$name = $val['name'];
		$descr = $val['descr'];
		$creator_id = $val['creator_id'];
		$date = $val['date'];

        try
        {
            $query = "
            			INSERT INTO DSS_projects 
            			( id, base_id, parent_id, name, description, create_date, team, pictures, timestamp )
            			VALUES ( NULL, $base_id, $parent_id, '$name', '$descr', '$date', '[ $creator_id ]','[]', NOW() )
            		  ";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }

        $parent_id = $pdo->lastInsertId();
        if( count( $val['childs'] ) )
        	sybcycle( $val['childs'], $parent_id, $base_id );
	}
}


function main_cycle( $data )
{
	global $pdo; 

	foreach( $data AS $key => $val )
	{
		$name = $val['name'];
		$descr = $val['descr'];
		$creator_id = $val['creator_id'];
		$date = $val['date'];

        try
        {
            $query = "
            			INSERT INTO DSS_projects 
            			( id, parent_id, name, description, create_date, team, pictures, timestamp )
            			VALUES ( NULL, 0, '$name', '$descr', '$date', '[ $creator_id ]','[]', NOW() )
            		  ";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }

        $parent_id = $pdo->lastInsertId();
        $base_id = $parent_id ;

        try
        {
            $query = "
                        UPDATE DSS_projects SET base_id = $base_id WHERE id = $base_id
                      ";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }

        if( count( $val['childs'] ) )
        	sybcycle( $val['childs'], $parent_id, $base_id );
	}
}


$data = [];

        try
        {
            $query = "	SELECT pr.ID id, pr.name name, pr.descr descr, pr.ID_creator creator_id, 			pr.beg_date_plan date
            			FROM okb_db_projects pr
            			WHERE 1
            		  ";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {	
        	$data[ $row -> id ] = [
        								'parent_id' => 0,
        								'id' => $row -> id,
        								'name' => $row -> name,
        								'descr' => $row -> descr,
        								'creator_id' => $row -> creator_id,
        								'date' => IntToDate( $row -> date ),
        								'childs' => []
        							];
        }


        try
        {
            $query = "	SELECT 
            			zad.ID id, zad.TXT name, zad.STARTDATE date, zad.ID_users creator_id,
            			zad.ID_edo parent_id, zad.ID_proj prj_parent_id
            			FROM `okb_db_itrzadan` zad
            			WHERE ID_proj <> 0
            		  ";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {	
        	$parent_id = $row -> parent_id ? $row -> parent_id : $row -> prj_parent_id ;

        	$data[ $row -> id ] = [
        								'parent_id' => $parent_id,
        								'id' => $row -> id,
        								'name' => $row -> name,
        								'descr' => '',
        								'creator_id' => $row -> creator_id,
        								'date' => IntToDate( $row -> date ),
        								'childs' => []
        							];
        }

$data_tree = map_tree( $data );
//debug( $data_tree, 1 );
main_cycle( $data_tree );