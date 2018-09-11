<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);


require_once( "functions.php" );

$user_id = $_POST['user_id'];
$cur_date = $_POST['cur_date'];
$prev_date = $_POST['prev_date'];
$prev_prev_date = $_POST['prev_prev_date'];

$data = [];
$error = false;
$rows = [];

try
{
    $query =
    "SELECT * FROM `okb_db_working_calendar`
    WHERE
    user_id = $user_id
    AND
    (
        date = '$cur_date'
        OR
        date = '$prev_date'
        OR
        date = '$prev_prev_date'
    )
    " ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row_count = $stmt -> rowCount() ;

 if( $row_count )
   while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
         {
            $order_id = $row -> order_id;

           if( $row -> date == $cur_date )
              $suffix = 'now';

           if( $row -> date == $prev_date )
              $suffix = 'prev';

           if( $row -> date == $prev_prev_date )
              $suffix = 'prev-prev';

           $rows[] = [ 'order_id' => $order_id, 'hour_count' => $row -> hour_count , 'suffix' => $suffix ];
         }

$data = $error ? ["error" => $error_msg ] :
    [
      'result' => 'OK',
      'user_id' => $user_id,
      'date' => $cur_date,
      'row_count' => $row_count,
      'rows' => $rows,
      ];
echo json_encode( $data );
