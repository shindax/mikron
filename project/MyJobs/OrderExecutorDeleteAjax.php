<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

define("MAV_ERP", TRUE);
$configpath = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."/config.php" );
require_once( $configpath );

global $files_path, $mysqli;


$outstr = '';
$order_id = $_POST['order_id'] ;
$executor_id = $_POST['executor_id'] ;

$query ="SELECT * FROM okb_db_itrzadan WHERE ID = $order_id"; 

$result = $mysqli -> query( $query );

if( ! $result )
  exit("Ошибка обращения к БД OrderExecutorAddingAjax.php : ".$query." : ".$mysqli->error); 

$row = $result -> fetch_assoc();
$executor_list = $row['ID_users2'] ;

$executor_list_arr = explode('|', $executor_list );

$executor_list_out_arr = array();

foreach( $executor_list_arr AS $executor )
  if( $executor_id == $executor )
     continue;
       else
        $executor_list_out_arr [] = $executor;

$executor_list = implode( "|", $executor_list_out_arr )."|";
$executor_list = str_replace("||", "|",  $executor_list );

if( $executor_list == "|" )
    $executor_list = '';
      
$query = "UPDATE okb_db_itrzadan SET ID_users2='$executor_list' WHERE ID=$order_id" ;
$result = $mysqli->query( $query );
      
if ( !$result ) 
{
          $mysql_error_msg = mysql_error();
          $outstr .= "Database access error, in UploadProjectFilesAJAX.php. MySQL said :$mysql_error_msg";
}
 else
           $outstr .= "Query was successfull.\n";

 echo '' ;
?>
