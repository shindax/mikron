<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

$id = $_POST['id'];

try
{
    $query ="
                DELETE FROM noncomplete_execution_cause_explanations
                WHERE cause_id = $id";

    $query = str_replace( '"', '', $query );

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

try
{
    $query ="
                DELETE FROM noncomplete_execution_causes
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