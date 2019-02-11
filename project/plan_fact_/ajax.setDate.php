<?php
error_reporting( 0 );

require_once( "functions.php" );

date_default_timezone_set("Asia/Krasnoyarsk");

$id = $_POST['id'];
$field = $_POST['field'];
$date = $_POST['date'];
$user_id = $_POST['user_id'];
$cause = $_POST['cause'];
$comment = $_POST['comment'];
$today = date("d.m.Y");
$date_string = "$today $date";

try
{
    $query = "SELECT $field FROM okb_db_zak
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row = $stmt->fetch(PDO::FETCH_OBJ );
$data = $row -> $field ;
$data .= "|$today#$user_id#$date";
$index = count( explode('|', $row -> $field ) ) ;

try
{
    $query = "UPDATE okb_db_zak
    SET $field = '$data'
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

try
{
    $query = "INSERT INTO okb_db_zak_ch_date_history ( id, zak_id, pd, date_index, date_string, cause, comment, user_id, timestamp ) VALUES ( NULL, $id, '$field', $index, '$date_string', $cause, '$comment', $user_id, NOW() )" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't insert data : " . $e->getMessage());
}

echo explode( '|', $data )[0]; // return current state