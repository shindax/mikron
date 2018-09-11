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

switch( $what )
{
    case 'project_plan'     : $field_to_update = 'project_plan_images'; break ;
    case 'plan'             : $field_to_update = 'plan_images'; break ;
    case 'report'           : $field_to_update = 'report_images'; break ;
}

$uploaddir = str_replace("//", "/",  $_SERVER['DOCUMENT_ROOT']."/project/".$files_path."/db_protocol_images@filename/" );

// Создадим папку если её нет
if( ! is_dir( $uploaddir ) )
    mkdir( $uploaddir, 0777 );

$files_arr = [] ;
$tmp_file_name = '';

foreach( $_FILES AS $file )
{
    $tmp_file_name = time() ;
    $pass = 0 ;

    while( time() == $tmp_file_name && ( count( $_FILES  ) > 1 ) )
        $tmp_file_name = time()."_".$pass ++ ;

    $real_file_name_arr = explode('.', $file['name'] );
    $tmp_file_name .= ".".$real_file_name_arr[ 1 ];
    $viewpath = "/project/".$files_path."/db_protocol_images@filename/$tmp_file_name" ;

    if( @move_uploaded_file( $file['tmp_name'] , $uploaddir . $tmp_file_name ) )
        $files_arr[] = $tmp_file_name;
    else
        $error = 1 ;
}

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
$res_arr = array_merge( $cur_arr, $files_arr );
$data = json_encode( $res_arr );

try
{
    $query = "UPDATE okb_db_protocol_images SET $field_to_update = '".$data."' WHERE ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}
$files_list = join( ",", $files_arr );

$data = $error ? array('error' => $error_msg ) : array('total_images' => count( $res_arr  ));
echo json_encode( $data );
?>