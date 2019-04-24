<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

//error_reporting( E_ALL );
error_reporting( 0 );

function get_cat()
{
	global $pdo;

	$arr_cat = [];

            try
            {
                $query = "SELECT ID, PID, NAME FROM `okb_db_zakdet` WHERE 1";
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            while( $row = $stmt->fetch(PDO::FETCH_ASSOC ) )
            	{
					$arr_cat[$row['ID']] = $row;
            	}

	return $arr_cat;
}

// Построение дерева
function full_map_tree( $dataset ) 
{
	$tree = [];

	foreach ($dataset as $id=>&$node) 
		if (!$node['PID'])
			$tree[$id] = &$node;
				else
            		$dataset[$node['PID']]['childs'][$id] = &$node;
 	return $tree;
}

// Построение локального дерева
function loc_map_tree( $dataset, $id ) 
{
	$subtree = 0 ;
	$found = 0 ;

	foreach ( $dataset as $key => $node ) 
	{
		if( $dataset[ $key ]['ID'] == $id )
			return $dataset[ $key ];
		
		if( isset( $dataset[ $key ]['childs'] ) )
			$subtree = loc_map_tree( $dataset[ $key ]['childs'], $id );	

		if( $subtree )
			break ;
	}

	return $subtree ;
}

function cyclic_to_delete( $dataset, &$to_delete )
{
    foreach( $dataset AS $key => $value )
    {
        $to_delete[] = $value['ID'];
        if( isset( $value['childs']) )
                cyclic_to_delete( $value['childs'], $to_delete );
    }
}


function delete( $dataset )
{
	global $pdo;

	$to_delete = [];
	
	$to_delete[] = $dataset['ID'];

	if( isset( $dataset['childs']) )
          cyclic_to_delete( $dataset['childs'], $to_delete ) ;

	$to_delete_list = join(",", $to_delete );

	$table_arr = [
            'okb_db_mtk_perehod',
            'okb_db_mtk_perehod_img',
            'okb_db_operitems',
            'okb_db_planzad',
            'okb_db_zadan',
            'okb_db_zn_instr',
            'okb_db_zn_pok',
            'okb_db_zn_zag'];

            try
            {
               $query = "DELETE FROM `okb_db_zakdet` WHERE ID IN ( $to_delete_list )";
               $stmt = $pdo->prepare( $query );
               $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
            }
	
            foreach( $table_arr AS $key => $value )
            {
	            try
	            {
	               $query = "DELETE FROM `$value` WHERE ID_zakdet IN ( $to_delete_list )";
	               $stmt = $pdo->prepare( $query );
	               $stmt->execute();
	            }
	            catch (PDOException $e)
	            {
	              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
	            }
            }
       
	return  count( $to_delete );
}

function ZakdetRemove( $id )
{
		$dataset = get_cat();
		$dataset = full_map_tree( $dataset );
		$dataset = loc_map_tree( $dataset, $id );
		return delete( $dataset );
}
