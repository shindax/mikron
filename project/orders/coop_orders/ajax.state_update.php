<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

$id = $_POST['id'];
$state = 1 * $_POST['state'];

$data = array();

$query = "UPDATE okb_db_koop_req SET STATE = '$state' WHERE ID = $id";
$result = $mysqli -> query( $query );

if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 
    $error = true ;
 }
  else
    $data = array('result' => 'OK' ) ;    

if( $error )
  $data = array('error' => $error_msg ) ;


echo json_encode( $data );
?>