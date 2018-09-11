<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");
require_once("CommonFunctions.php");

global $user ;
global $mysqli;

$time = mktime();

$id = $_POST['id'];
$user_id = $_POST['user_id'];

$name = iconv("UTF-8", "Windows-1251", $_POST['name'] );
$type = iconv("UTF-8", "Windows-1251", $_POST['type'] );

$query = "UPDATE okb_db_sobitiya 
                  SET NAME='$name', VALUE='$type', ETIME=$time, EUSER=$user_id 
                  WHERE ID=$id";

$result = $mysqli -> query( $query );

if( ! $result ) 
  exit("Ошибка обращения к БД в функции ajaxUpdateRecordEventName.php Query :<br>$query<br>".$mysqli->error); 

echo $user_id ;

?>