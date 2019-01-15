<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;
$id = $_POST['id'];

$count = 0 ;
$norm_hours = 0 ;
$oper_id = 0 ;

try
{
    $query ="   SELECT count, norm_hours, oper_id FROM `okb_db_operations_with_coop_dep` 
                WHERE
                id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
    $count = $row -> count ;
    $norm_hours = $row -> norm_hours ;
    $oper_id = $row -> oper_id ;
}

try
{
    $query ="   DELETE FROM `okb_db_operations_with_coop_dep` 
                WHERE
                id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

echo json_encode( [ $count, $norm_hours, $oper_id] );