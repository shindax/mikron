<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
//    return iconv( "UTF-8", "Windows-1251",  $str );
    return $str ;
}

try
{
    $query = "SELECT ID, ID_krz FROM okb_db_edo_inout_files
    WHERE 1" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$krz_arr = [];

while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
{
    $id = $row -> ID;
    $krz_list = $row -> ID_krz;
    $krz_arr[$id] = $krz_list; 
}    

foreach ( $krz_arr as $id => $value ) 
{
    $krz_sub_arr = explode('|', $value );
    foreach ( $krz_sub_arr as $subkey => $subvalue ) 
    {
        if( $subvalue[0] == '0' )
            if(strlen( $subvalue ) == 1 )
                unset( $krz_sub_arr[$subkey]);
                else
            $krz_sub_arr[ $subkey ] = substr( $subvalue, 1 );
    }
    
    $krz_sub_list = join('|', $krz_sub_arr ) ;
    $krz_arr[ $id ] = $krz_sub_list;
}

foreach ( $krz_arr as $id => $krz_list ) 
{
    try
    {
        $query = "UPDATE okb_db_edo_inout_files SET ID_krz='$krz_list'  WHERE ID=$id" ;
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

}


debug( $krz_arr );