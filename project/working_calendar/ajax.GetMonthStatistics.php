<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);


require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$user_id = $_POST['user_id'];
$date = $_POST['date'];

$data = [];
$error = false;

try
{
    $query = "
                                SELECT
                                order_id,
                                SUM( hour_count ) month_hours
                                FROM `okb_db_working_calendar`
                                WHERE
                                MONTH( date ) = MONTH( '$date' )
                                AND
                                YEAR( date ) = YEAR( '$date' )
                                AND
                                `user_id` IN ( $user_id )
                                AND
                                `order_id` IN
                                        (
                                              SELECT DISTINCT ( order_id ) order_id
                                              FROM `okb_db_working_calendar`
                                              WHERE
                                              MONTH( date ) = MONTH( '$date' )
                                              AND
                                              YEAR( date ) = YEAR( '$date' )
                                              AND
                                              `user_id` IN ( $user_id )
                                         )
                                   GROUP BY `order_id`";

          $stmt = $pdo->prepare( $query );
          $stmt->execute();
}
catch (PDOException $e)
{
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). ". Query : ". $query );
}

while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        $rows[] =
        [
                'order_id' => $row -> order_id, 'month_hours' => $row -> month_hours
        ];

$data = $error ? ["error" => $error_msg ] : [ 'result' => 'OK', 'user_id' => $user_id , 'date' => $date, 'rows' => $rows ];

echo json_encode( $data );



