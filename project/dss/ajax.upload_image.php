<?php
header('Content-Type: text/html');
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
//error_reporting( 0 );

$dss_images_path = '/dss_images@filename/';
$full_files_path = "/project/$files_path".$dss_images_path;

$id = $_POST['id'];
$date = date("d.m.Y");

$uploaddir = str_replace("//", "/",  $_SERVER['DOCUMENT_ROOT'].$full_files_path."/$id/" );

// Создадим папку если её нет
if( ! is_dir( $uploaddir ) )
    mkdir( $uploaddir, 0777 );

$files_arr = [] ;



foreach( $_FILES AS $file )
{
    $file_name = iconv( 'utf-8', 'windows-1251', $file['name'] );

    if( @move_uploaded_file( $file['tmp_name'] , $uploaddir.$file_name ) )
        $files_arr[] = $file['name'] ;
}

try
{
    $query ="   SELECT pictures
                FROM `dss_projects` 
                WHERE
                ID = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    $json_arr = ( array ) json_decode( $row -> pictures );

foreach( $files_arr AS $key => $file )
    $json_arr[] = [ "name" => $file , "date" => $date, "comment" => "" ] ;

$json_arr = array_values( $json_arr );
$json_str = json_encode( $json_arr, JSON_UNESCAPED_UNICODE );

try
{
    $query ="   UPDATE `dss_projects` 
                SET pictures='$json_str'
                WHERE
                ID = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

echo $json_str;
?>