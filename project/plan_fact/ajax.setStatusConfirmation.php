<?php
error_reporting( 0 );

require_once( "functions.php" );

date_default_timezone_set("Asia/Krasnoyarsk");

$id = $_POST['id'];
//$field = str_replace('_conf_conf', '_conf', $_POST['field']);
$field = $_POST['field'];
$state = $_POST['state'] == 'true' ? 1 : 0 ;


try
{
    $query = "UPDATE okb_db_zak
    SET $field = $state
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}


//echo "Field : $field  State : $state ID : $id";
echo $state;