<?php
require_once("CommonFunctions.php");

error_reporting( E_ALL );
error_reporting( 0 );

global $db_prefix, $mysqli;

$id = $_GET['id'];

$result = dbquery("SELECT * FROM ".$db_prefix."db_itrzadan where (ID='".$id."') ");
$row = mysql_fetch_array( $result );
$exec_list = $row['ID_users2'];

$exec_list = explode( '|', $exec_list );
$executor = '';
    
for( $i = 0 ; $i < count( $exec_list ); $i ++ )
  $executor .= GetPerson( $exec_list[ $i ] )."<br>";

echo $executor ;

?>
