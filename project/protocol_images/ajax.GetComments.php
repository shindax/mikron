<?php
header('Content-Type: text/html');

require_once( "functions.php" );
error_reporting( 0 );

$data = array('image_name' => '');
$error = false;
$error_msg = '';

$id = $_POST['id'];
$what = $_POST['what'];

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

$data = $error ? array('error' => $error_msg ) : array( 'query' => $query, 'comments' => $project_plan_comments ) ;
echo json_encode( $data );
?>