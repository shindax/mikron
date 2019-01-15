<?php
error_reporting( 0 );
error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );

$id = $_POST['id'];
$str = "$id";
$uploaddir = str_replace("//", "/",  LOCAL_FILES_PATH );

// Создадим папку если её нет
if( ! is_dir( $uploaddir ) )
    mkdir( $uploaddir, 0777 );

//foreach( $_FILES AS $file )

$file = $_FILES[ 0 ]; // in this place one file only processed
{
    $tmp_file_name = time() ;
    $pass = 0 ;

    while( time() == $tmp_file_name && ( count( $_FILES  ) > 1 ) )
        $tmp_file_name = time()."_".$pass ++ ;

    $real_file_name_arr = explode('.', $file['name'] );
    $tmp_file_name .= ".".$real_file_name_arr[ 1 ];
    $viewpath = LOCAL_FILES_PATH.DIRECTORY_SEPARATOR."$tmp_file_name" ;

    if( @move_uploaded_file( $file['tmp_name'] , $uploaddir . $tmp_file_name ) )
        $files_arr[] = $tmp_file_name;
    else
        $error = 1 ;
}

$filename = $files_arr[0];

  try
  {
      $query ="
                      UPDATE okb_db_entrance_control_pages
                      SET image='$filename'
                      WHERE id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

echo  $filename;
