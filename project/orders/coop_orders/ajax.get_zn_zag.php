<?php
error_reporting( E_ALL );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

$id = $_POST['id'];

$data = array( 'id' => 0, 'id_mat' => 0, 'id_mat_cat' => 0 );
$error = false;

$query = "SELECT zn_zag.ID, zn_zag.ID_mat, mat.ID_mat_cat, matcat.PID
          FROM okb_db_zn_zag zn_zag 
          INNER JOIN okb_db_mat mat ON zn_zag.ID_mat = mat.ID
          INNER JOIN okb_db_mat_cat matcat ON matcat.ID = mat.ID_mat_cat 
          WHERE ID_zakdet = $id";
          
$result = $mysqli -> query( $query );

if( ! $result ) 
 {
    exit("Database access error in ".__FILE__." in line ".__LINE__.": ".$mysqli->error); 
    $error = true ;
 }


//       file_put_contents( 'c:\sites\mic.ru\www\project\orders\coop_orders\ajax_log.txt', "$id : $id_mat : $id_mat_cat" );

if( $result -> num_rows )
   {
       $row = $result -> fetch_object();
       $id = $row -> ID ;
       $id_mat = $row -> ID_mat;
       $id_mat_cat = $row -> ID_mat_cat;
       $id_pid = $row -> PID;
       $data = array( 'id' => $id, 'id_mat' => $id_mat, 'id_mat_cat' => $id_mat_cat, 'id_mat_cat_pid' => $id_pid );
   }


if( $error )
  $data = array('error' => $error_msg ) ;

echo json_encode( $data )
?>