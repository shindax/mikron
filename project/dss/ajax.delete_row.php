<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;
$id = $_POST['id'];

$dss_images_path = '/dss_images@filename/';
$full_files_path = $_SERVER['DOCUMENT_ROOT']."/project/$files_path".$dss_images_path.$id;
$parent_id = 0 ;

try
{
    $query ="   SELECT parent_id, pictures
                FROM `dss_projects` 
                WHERE
                id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
    $parent_id = $row -> parent_id ;
    $json_arr = ( array ) json_decode( $row -> pictures );
}

foreach( $json_arr AS $key => $value )
{
	$name = $full_files_path."/".$value -> name;
	unlink( iconv( "UTF-8", "Windows-1251", $name ) );
}

function conv( $str )
{
    return $str ; // iconv( "UTF-8", "Windows-1251",  $str );
}

try
{
    $query ="   DELETE FROM `dss_projects` 
                WHERE
                id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

$new_ord = [] ;

try
{
    $query ="   SELECT id, ord FROM `dss_projects` 
                WHERE
                parent_id = $parent_id
                ORDER BY ord
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    $new_ord[] = $row -> id ;

foreach( $new_ord AS $key => $value )
{
    try
    {
        $query ="   UPDATE `dss_projects` 
                    SET ord = ".( $key + 1 )."
                    WHERE
                    id = $value
                ";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
        die("Can't get data: " . $e->getMessage());
    }
}



echo $query;