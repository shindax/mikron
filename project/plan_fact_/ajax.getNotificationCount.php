<?php
error_reporting( 0 );
require_once( "functions.php" );

$user_id = $_POST['user_id'];
$why_arr = $_POST['why_arr'];
$why_list = join( ",", $why_arr );

try
{
    $query = "
                      SELECT COUNT(*) count
                      FROM
                      okb_db_plan_fact_notification
                      WHERE
                      to_user = $user_id
                      AND 
                      ack = 0
                      AND
                      why IN ( $why_list )
                      " ;

                      $stmt = $pdo->prepare( $query );
                      $stmt->execute();

}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

  $row = $stmt->fetch(PDO::FETCH_OBJ ) ;

echo $row -> count;

