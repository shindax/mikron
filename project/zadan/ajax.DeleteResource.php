<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$emp_list = $_POST['list'];

try
{
    $query ="
    DELETE
    FROM `okb_db_zadanres` 
    WHERE ID IN ( $emp_list )" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

echo $query;