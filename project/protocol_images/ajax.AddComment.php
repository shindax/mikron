<?php
header('Content-Type: text/html');
date_default_timezone_set("Asia/Krasnoyarsk");

require_once( "functions.php" );
error_reporting( 0 );

$id = $_POST['id'];
$what = $_POST['what'];
$comment = $_POST['comment'];
$now = date("Y-m-d");

try
{
    $query = "SELECT project_plan_comments
    FROM okb_db_protocol_images
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

$row = $stmt->fetch( PDO::FETCH_OBJ );
$project_plan_comments = json_decode( $row -> project_plan_comments );
$project_plan_comments[] = ["date" => $now, "comment" => $comment ];

try
{
    $query = "UPDATE okb_db_protocol_images SET project_plan_comments = '". json_encode( $project_plan_comments , JSON_UNESCAPED_UNICODE ) ."' 
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

echo $query;
?>