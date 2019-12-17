<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$id = $_POST['id'];
$arr = $_POST['arr'];

try
{
    $query ="
                UPDATE noncomplete_execution_causes
                SET responsible_res_id = '".json_encode( $arr )."'
                WHERE id = $id";

    $query = str_replace( '"', '', $query );

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

echo $query;