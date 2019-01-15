<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$id = $_POST['id'];
$res_id = $_POST['res_id'];
$str = "";
$level = 0 ;

$query = "";

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

function get_cat( $pdo, $id )
{
    global $query ;

	$arr_cat = [];

            try
            {
    			$query = "SELECT id, parent_id FROM `dss_projects` 
                        WHERE base_id = $id ORDER BY ord";
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            while( $row = $stmt->fetch(PDO::FETCH_ASSOC ) )
                	$arr_cat[$row['id']] = $row;

	return $arr_cat;
}

// Построение дерева
function full_map_tree( $dataset ) 
{
	$tree = [];

	foreach ($dataset as $id=>&$node) 
		if (!$node['parent_id'])
			$tree[$id] = &$node;
				else
            		$dataset[$node['parent_id']]['childs'][$id] = &$node;
 	return $tree;
}

$dataset = get_cat( $pdo, $id );
$dataset = full_map_tree( $dataset );

foreach( $dataset AS $key => $value )
	bypass( $pdo, $res_id, $dataset[ $key ]['childs'], $str, $level + 20 );	

function bypass( $pdo, $res_id, $dataset, &$str, $level )
{
	foreach( $dataset AS $key => $value )
	{
		$dss_item = new DecisionSupportSystemItem( $pdo, $res_id, $key, $level );
		$str .= conv( $dss_item -> GetTableRow( '','Field', 1 ) );	
	 	if( isset( $value['childs']) )
           cyclic_bypass( $pdo, $res_id, $value['childs'], $str, $level + 20 ) ;
	}
}

function cyclic_bypass( $pdo, $res_id, $dataset, &$str, $level )
{
    foreach( $dataset AS $key => $value )
    {
		$dss_item = new DecisionSupportSystemItem( $pdo, $res_id, $key, $level );
		$str .= conv( $dss_item -> GetTableRow('','Field', true ) );	
        if( isset( $value['childs']) )
                cyclic_bypass( $pdo, $res_id, $value['childs'], $str, $level + 20 );
    }
}

echo $str;