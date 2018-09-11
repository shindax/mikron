<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);

require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

$coop_req_id  = $_POST[ 'coop_req_id' ];
$user_id  = $_POST[ 'user_id' ];

try
{
   $query = "INSERT INTO okb_db_coop_tasks (id, coop_req_id, cagent_id, req_send_date, req_response_date, state, state_note, pricing, pricing_note, timestamp ) 
   			VALUES( NULL, $coop_req_id, 0, NOW(), NOW(), 0,'' , 0, '', NOW() )" ;
   $stmt = $pdo->prepare( $query );
   $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

$id = $pdo -> lastInsertId();

$query = "
                    SELECT *, 
                    DATE_FORMAT( tasks.req_send_date, '%d.%m.%Y') AS req_send_date,
                    DATE_FORMAT( tasks.req_response_date, '%d.%m.%Y') AS req_response_date,
                    clients.NAME AS client_name
                    FROM `okb_db_coop_tasks` AS tasks
                    LEFT JOIN okb_db_clients clients ON clients.ID = tasks.cagent_id
                	WHERE tasks.id = $id
            ";

try
{
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    $str = getTableRow( $row, 0, $user_id );

 //echo iconv("Windows-1251", "UTF-8", $str );
echo $str ;