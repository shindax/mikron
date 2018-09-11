<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST[ 'id' ];

global $pdo ;

try
{
   $query = "DELETE FROM okb_db_logistic_rates WHERE id=$id" ;
   $stmt = $pdo->prepare( $query );
   $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

echo $query;