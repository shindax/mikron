<?php
header('Content-Type: text/html');

require_once( "functions.php" );
error_reporting( 0 );

$data = array('image_name' => '');
$error = false;
$error_msg = '';

$id = $_POST['id'];
$what = $_POST['what'];
$date = DateConvert( $_POST['date'] );

switch( $what )
{
    case 'project_plan'     : $field_to_update = 'project_plan_date_fact'; break ;
    case 'plan'                 : $field_to_update = 'plan_date_fact'; break ;
    case 'report'               : $field_to_update = 'report_date_fact'; break ;
}

    // $query = "SELECT $field_to_update FROM okb_db_protocol_images

try
{
    $query = "SELECT DATE_FORMAT( $field_to_update , '%d.%m.%Y') old_date FROM okb_db_protocol_images
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

$row = $stmt->fetch(PDO::FETCH_OBJ );
$old_date = $row -> old_date ;

try
{
    $query = "UPDATE okb_db_protocol_images SET $field_to_update = '".$date."'
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

$data = $error ? array('error' => $error_msg ) : array( 'query' => $query,
                                                                                'date' => $_POST['date'] ,
                                                                                'old_date' => $old_date ) ;
echo json_encode( $data );
?>