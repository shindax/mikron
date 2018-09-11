<?php
error_reporting( E_ALL );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

function fixJSON($str)
{
	$str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), $str);
	return iconv('cp1251', 'utf-8', $str);
}

$id = $_POST['id'];

//file_put_contents( 'c:\sites\mic.ru\www\project\orders\coop_orders\ajax_log.txt', $id );

$data = array();
$error = false;

$query = "SELECT ID, NAME,  OBOZ FROM okb_db_zakdet WHERE ID_zak = $id ORDER BY NAME";
$result = $mysqli -> query( $query );


if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__." : ".$mysqli->error); 
    $error_msg = $mysqli->error ;
    $error = true ;
 }

if( $result -> num_rows )
  while( $row = $result -> fetch_object() )
    {
       $id = $row -> ID ;
       $name = $row -> NAME ;
       $draw = $row -> OBOZ ;        
       $data[] = array( 'id' => $id, 'dse_name' => fixJSON( $name ), 'draw' => fixJSON( $draw ));
    }

if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $data );
?>