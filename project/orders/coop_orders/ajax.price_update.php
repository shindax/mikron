<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

$id = $_POST['id'];
$plan_price = 1 * $_POST['plan_price'];
$work_price = 1 * $_POST['work_price'];
$fact_price = 1 * $_POST['fact_price'];
$eff = 1 * $_POST['eff'];

$data = array();

$query = "UPDATE okb_db_koop_req SET CENA_PLAN = '$plan_price', STOIM_RAB = '$work_price', CENA_FACT = '$fact_price', EFFECTN  = '$eff' WHERE ID = $id";
$result = $mysqli -> query( $query );

//file_put_contents( 'c:\OpenServer\domains\mic.ru\project\orders\coop_orders\ajax_log.txt', $query );


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