<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

define("MAV_ERP", TRUE);
$configpath = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."/config.php" );
require_once( $configpath );

global $files_path, $mysqli;


$order_id = $_POST['order_id'] ;
$executor_id = $_POST['executor_id'] ;

$query ="SELECT * FROM okb_db_itrzadan WHERE ID = $order_id"; 

$result = $mysqli -> query( $query );

if( ! $result )
  exit("Ошибка обращения к БД OrderExecutorAddingAjax.php : ".$query." : ".$mysqli->error); 

$row = $result -> fetch_assoc();
$executor_list = $row['ID_users2'] ;

$executor_list_arr = explode('|', $executor_list );

$already_in_list = 0 ;

foreach( $executor_list_arr AS $executor )
  if( $executor_id == $executor )
    $already_in_list = 1 ;

$outstr = '';

if( $already_in_list == 0 )
{

$executor_list .= $executor_id."|";
      
$query = "UPDATE okb_db_itrzadan SET ID_users2='$executor_list' WHERE ID=$order_id" ;
$result = $mysqli->query( $query );

      
if ( !$result ) 
{
          $mysql_error_msg = mysql_error();
          $outstr .= "Database access error, in UploadProjectFilesAJAX.php. MySQL said :$mysql_error_msg";
}
 else
           $outstr .= "Query was successfull.\n";


$query ="SELECT * FROM okb_db_resurs WHERE ID = $executor_id"; 
$result = $mysqli -> query( $query );

if( ! $result )
  exit("Ошибка обращения к БД OrderExecutorAddingAjax.php : ".$query." : ".$mysqli->error); 

 $row = $result -> fetch_assoc();
 $name = $row['NAME'] ;

 $title = iconv( "UTF-8", "Windows-1251", "Удалить исполнителя");

 $outstr = "<div title='$title' id='executor_".$executor_id."' class='executor_span'>".$name."<img id='del_executor_img_".$executor_id."' src='uses/del.png' title='$title' class='del_executors_img'/></div>";
}

//echo iconv( "UTF-8", "Windows-1251", $outstr );
echo $outstr ;
?>
