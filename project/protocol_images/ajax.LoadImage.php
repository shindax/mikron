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

$total_images = $_POST['total_images'] ;
$image_num = $_POST['image_num'] ;

switch( $what )
{
    case 'project_plan'     : $field_to_read = 'project_plan_images'; break ;
    case 'plan'             : $field_to_read = 'plan_images'; break ;
    case 'report'           : $field_to_read = 'report_images'; break ;
}

$uploaddir = str_replace("//", "/",  "/project/".$files_path."/db_protocol_images@filename/" );

try
{
    $query ="
                SELECT
                $field_to_read
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
$img_arr = json_decode( $row -> $field_to_read );

$data = $error ? array('error' => $error_msg ) : array('file_name' => $img_arr[ $image_num - 1 ],'file_path' => $uploaddir.$img_arr[ $image_num - 1 ], 'total_images' => count( $img_arr ));
echo json_encode( $data );
?>