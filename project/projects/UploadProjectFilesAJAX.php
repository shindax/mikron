<?php
error_reporting( 0 );
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

define("MAV_ERP", TRUE);
$configpath = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."/config.php" );
require_once( $configpath );

global $files_path, $mysqli;

    $data = array('image_name' => '');
    $id = $_POST['id'];
    $error = false;
    $error_msg = '';
    $mysql_error_msg = '';
    $files = array();

    $uploaddir = str_replace("//", "/",  $_SERVER['DOCUMENT_ROOT']."/project/".$files_path."/db_projects@filename/" );
    $tmp_file_name = time();

// ОТладочный вывод
    $outfile = 'UploadProjectFilesAJAX.php.log.txt';
    $outstr = "project id : $id\n" ;

// Создадим папку если её нет
if( ! is_dir( $uploaddir ) ) 
  mkdir( $uploaddir, 0777 );

// Если загружается нескольк офайлов
//	foreach( $_FILES as $file )
// В данном случае только один
   $file = $_FILES[0];

   $real_file_name_arr = explode('.', $file['name'] );
   $tmp_file_name .= ".".$real_file_name_arr[ 1 ];
   $viewpath = "/project/".$files_path."/db_projects@filename/$tmp_file_name" ; 
  
  		if( @move_uploaded_file( $file['tmp_name'] , $uploaddir . $tmp_file_name ) )
      {
        $outstr .= "File ".$uploaddir . $tmp_file_name." successfully moved.\n";
      
        $query = "UPDATE okb_db_projects SET filename='$tmp_file_name' WHERE ID=$id" ;
        $outstr .= "Database query is : ".$query."\n" ;
        $result = $mysqli->query( $query );
      
        if ( !$result ) 
        {
          $mysql_error_msg = mysql_error();
          $outstr .= "Database access error, in UploadProjectFilesAJAX.php. MySQL said :$mysql_error_msg";
          $error = 2 ;
        }
          else
           $outstr .= "Query was successfull.\n";
      }
        else
          $error = 1 ;

  switch( $error )
  {
    case 1  : $error_msg  = "Files loading error." ; break ;
    case 2  : $error_msg  = "Database access error, in UploadProjectFilesAJAX.php. MySQL said : $mysql_error_msg" ; break ;    
    default: break ;        
  }
  
  file_put_contents( $outfile,  $outstr );
  
  $data = $error ? array('error' => $error_msg ) : array('image_name' => $viewpath );
  echo json_encode( $data );
?>