<?php
error_reporting( E_ALL );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

function fixJSON($str)
{
	$str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
	return iconv('cp1251', 'utf-8', $str);
}

$id_mat_cat = $_POST['id_mat_cat'];

//file_put_contents( 'c:\sites\mic.ru\www\project\orders\coop_orders\ajax_log.txt', $id );

$data = array();
$error = false;

$query = "SELECT ID, NAME FROM okb_db_mat WHERE ID_mat_cat = $id_mat_cat ORDER BY NAME";
$result = $mysqli -> query( $query );

if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 
    $error = true ;
 }

if( $result -> num_rows )
  while( $row = $result -> fetch_object() )
    {
       $id = $row -> ID ;
       $name = $row -> NAME ;
       $data[] = array( 'id' => $id, 'mat_name' => fixJSON( $name ) );
    }

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $data );
?>