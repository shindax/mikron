<?php
//error_reporting( E_ALL );
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

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
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
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

function cyclic_to_copy( $dataset, $pid )
{
    foreach( $dataset AS $key => $value )
    {
        $lpid = dse_clone( $value['ID'], $pid );
        if( isset( $value['childs']) )
            cyclic_to_copy( $value['childs'], $lpid );
    }
}

function dse_clone( $item, $pid = 0 )
{
    echo "$item copied<br>";
}

function copy_dse( $dataset )
{
    $pid = dse_clone( $dataset['ID'] );
    if( isset( $dataset['childs']) )
       cyclic_to_copy( $dataset['childs'], $pid ) ;
}

function row_clone( $pdo, $table, $id, $pid = 0 )
{
    $last_insert_id = 0 ;

            try
            {
               $query = "CREATE TEMPORARY TABLE tmptable SELECT * FROM $table WHERE id = $id";
               $stmt = $pdo->prepare( $query );
               $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
            }

            try
            {
               $query = "UPDATE tmptable SET id = 0";
               $stmt = $pdo->prepare( $query );
               $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
            }

            if( $pid )
            {
                try
                {
                   $query = "UPDATE tmptable SET PID = $pid";
                   $stmt = $pdo->prepare( $query );
                   $stmt->execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
                }
            }

            try
            {
               $query = "INSERT INTO $table SELECT * FROM tmptable";
               $stmt = $pdo->prepare( $query );
               $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
            }
            
            $last_insert_id = $pdo -> lastInsertId();
            
            try
            {
               $query = "DROP TEMPORARY TABLE IF EXISTS tmptable";
               $stmt = $pdo->prepare( $query );
               $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
            }            

    return $last_insert_id;
}

$dataset = get_cat();
$dataset = full_map_tree( $dataset );
$dataset = loc_map_tree( $dataset, 11 );
//copy_dse( $dataset );
//debug( $dataset, true );

echo row_clone( $pdo, "okb_db_zakdet", 570568, 12345678 );