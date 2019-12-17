<?php
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$ord = $_POST['ord'];
$id = $_POST['id'];
$type = $_POST['type'];

$prj_id = 0 ;
$zak_id = 0 ;

if( $type == 1 )
  $zak_id = $id ;

if( $type == 2 )
  $prj_id = $id ;

$query = "
          UPDATE okb_db_itrzadan SET ID_zak= $zak_id, ID_proj=$prj_id WHERE ID = $ord";
try
{
                      $stmt = $pdo->prepare( $query );
                      $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

echo "OK";

