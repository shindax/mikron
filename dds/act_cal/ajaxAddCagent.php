<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");
require_once("CommonFunctions.php");

global $user ;
global $mysqli;

$time = mktime();

$user_id = $_POST['user_id'];
$query ="INSERT INTO okb_db_clients ( NAME , ETIME, EUSER ) 
    VALUES( '', $time, $user_id )"; 

$result = $mysqli -> query( $query );

if( ! $result ) 
  exit("Ошибка обращения к БД в функции ajaxAddRecord.php Query :<br>$query<br>".$mysqli->error); 

$rec_id = $mysqli -> insert_id ;

$query ="SELECT ID FROM okb_db_clients WHERE 1"; 
$result = $mysqli -> query( $query );

if( ! $result ) 
  exit("Ошибка обращения к БД в функции ajaxAddRecord.php Query :<br>$query<br>".$mysqli->error); 

$rec_cnt = $result -> num_rows ;

$str = "<tr><td class='field AC'>$rec_cnt</td><td class='field AL'><input data-id='$rec_id' id='inp_$rec_id' class='inp inp_name new_record' type='text' /></td></tr>";

header('Content-Encoding: gzip');
echo gzencode( $str );

?>