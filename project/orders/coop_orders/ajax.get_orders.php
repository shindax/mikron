<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

function fixJSON($str)
{
	$str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
	return iconv('cp1251', 'utf-8', $str);
}

$data = array();
$error = false;

$query = "SELECT ID, TID, NAME,  DSE_NAME FROM okb_db_zak WHERE EDIT_STATE=0 ORDER BY DSE_NAME";
$result = $mysqli -> query( $query );

if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 
    $error = true ;
 }

$ord_type = array(" ","нг","йп","яо","аг","уг","бг");    

if( $result -> num_rows )
  while( $row = $result -> fetch_object() )
    {
       $id = $row -> ID ;
       $tid = $row -> TID ;
       $name = $row -> NAME ;
       $dse_name = $row -> DSE_NAME ;        
       $data[] = array( 'id' => $id, 'tid' => fixJSON( $ord_type[ $tid ] ), 'name' => fixJSON( $name ), 'dse_name' => fixJSON( $dse_name ));
    }

if( $error )
  $data = array('error' => $error_msg ) ;

//file_put_contents( 'c:\sites\mic.ru\www\project\orders\coop_orders\ajax_log.txt', count( $data ) );  

echo json_encode( $data );
?>