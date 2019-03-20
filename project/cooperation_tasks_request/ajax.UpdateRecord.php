<?php
header('Content-Type: text/html');
error_reporting( 0 );

// error_reporting( E_ALL );
// ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST[ 'id' ];
$val = $_POST[ 'val' ];
$field = $_POST[ 'field' ];

global $pdo ;

try
{
    $query = "UPDATE okb_db_coop_request_tasks SET $field = '$val'
    WHERE
    id=$id" ;
   $stmt = $pdo->prepare( $query );
   $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

echo $query;