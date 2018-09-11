<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( "functions.php" );

$data = array('image_name' => '');
$error = false;
$error_msg = '';
$files = array();

$id = $_POST['id'];
$what = $_POST['what'];
$total_images = $_POST['total_images'];
$image_num = 1 * $_POST['image_num'] - 1 ;

switch( $what )
{
    case 'project_plan'     : $field_to_update = 'project_plan_images'; break ;
    case 'plan'             : $field_to_update = 'plan_images'; break ;
    case 'report'           : $field_to_update = 'report_images'; break ;
}

$uploaddir = str_replace("//", "/",  $_SERVER['DOCUMENT_ROOT']."/project/".$files_path."/db_protocol_images@filename/" );

$files_arr = [] ;
$tmp_file_name = '';

try
{
    $query ="
                SELECT
                $field_to_update
                FROM
                okb_db_protocol_images
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

$row = $stmt->fetch(PDO::FETCH_OBJ );
$cur_arr = json_decode( $row -> $field_to_update );
unset( $cur_arr[ 1 * $image_num ] );
$new_arr = [];

foreach( $cur_arr AS $key => $value )
    $new_arr [] = $value;

$data = json_encode( $new_arr );

try
{
    $query = "UPDATE okb_db_protocol_images SET $field_to_update = '".$data."' WHERE ID = $id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}
$files_list = join( ",", $files_arr );

$image_num ++ ;

if( $image_num < 0 )
    $image_num = 1 ;

if( $image_num > count( $new_arr ) )
    $image_num = count( $new_arr );

$data = $error ? array('error' => $error_msg ) : array('current_image' => $image_num, 'total_images' => count( $cur_arr  ));

echo json_encode( $data );
?>