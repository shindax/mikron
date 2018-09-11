<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST['id'];
$count = $_POST['cnt'];
$res_count = $_POST['res_cnt'];
$rec_id = $_POST['rec_id'];

try
{
    $query =
    "SELECT COUNT FROM okb_db_sklades_detitem WHERE id = $id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row = $stmt->fetch(PDO::FETCH_OBJ );

$count = $row -> COUNT ;
$delta = $count - $res_count;

try
{
    $query =
    "UPDATE okb_db_warehouse_reserve SET state = 1 WHERE id = $rec_id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

try
{
    $query =
    "UPDATE okb_db_sklades_detitem SET COUNT = $delta WHERE id = $id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

echo $delta;