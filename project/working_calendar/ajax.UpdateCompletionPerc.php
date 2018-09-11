<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);


require_once( "functions.php" );

$id = $_POST['id'];
$val = $_POST['val'];

try
{
    $query = "UPDATE okb_db_itrzadan SET comp_perc = $val
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

echo $id." : ".$val;
