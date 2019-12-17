<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST['id'];
$user_id = $_POST['user_id'];
$field = $_POST['field'];
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];

$day = $day < 10 ? "0$day" : $day ;
$month = $month < 10 ? "0$month" : $month ;

$data = "0|$day.$month.$year#$user_id#$day.$month.$year";

try
{
    $query =
    "UPDATE okb_db_zak SET $field = '$data' WHERE id = $id" ;
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

echo $query;