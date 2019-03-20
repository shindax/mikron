<?php
header('Content-Type: text/html');
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$req_id = $_POST[ 'req_id' ];

global $pdo ;

try
{
    $query = "UPDATE okb_db_coop_request_tasks SET selected = 0
    		  WHERE 
    		  coop_req_id=$req_id" ;
  $stmt = $pdo->prepare( $query );
  $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

echo $query;