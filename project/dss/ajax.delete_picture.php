<?php
header('Content-Type: text/html');
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( 0 );

$dss_images_path = '/dss_images@filename/';
$full_files_path = "/project/$files_path".$dss_images_path;

$id = $_POST['id'];
$name = $_POST['name'];

$todelete = $_SERVER['DOCUMENT_ROOT'].$full_files_path."$id/$name";
unlink( iconv( "UTF-8", "Windows-1251", $todelete ) );

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

foreach( $json_arr AS $key => $value )
{
     if( $value -> name == $name )
         unset( $json_arr[ $key ]);
}

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

echo $todelete;
?>