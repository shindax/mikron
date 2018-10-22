<?php
header('Content-Type: text/html');
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( 0 );

$dss_images_path = '/dss_images@filename/';
$full_files_path = "/project/$files_path".$dss_images_path;

$id = $_POST['id'];
$comment = $_POST['comment'];
$name = $_POST['name'];

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
     if( $value -> name == $name )
         $json_arr[ $key ] -> comment = $comment;

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