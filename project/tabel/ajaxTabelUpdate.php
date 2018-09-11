<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

global $mysqli;

$id   = $_POST['id'];
$date = $_POST['date'];
$val  = $_POST['val'];

$query = "UPDATE okb_db_tabel 
                 SET doc_issued='$val' 
                 WHERE DATE=$date AND ID_resurs=$id";

$result = $mysqli -> query( $query );

if( ! $result ) 
  exit("Ошибка обращения к БД в функции ajaxTabelUpdate.php Query :<br>$query<br>".$mysqli->error); 

echo $val ;

?>