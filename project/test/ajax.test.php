<?php
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

$id = $_POST['id'];
$name = '';

if( $id )
try
{
    $query = "SELECT NAME FROM okb_db_zak
    WHERE ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row = $stmt->fetch(PDO::FETCH_OBJ );
$name = $row -> NAME;

echo $name ;
