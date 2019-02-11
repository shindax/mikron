<?php
error_reporting( 0 );
require_once( "functions.php" );

$user_id = $_GET['user_id'];
$arr = [];

try
{
    $query = "
                      SELECT
                      okb_db_zak_type.description AS ord_type,
                      okb_db_zak.`ID` AS ord_id,
                      okb_db_zak.`NAME` AS ord_name,
                      okb_db_zak_stages.description AS stage_name,
                      okb_db_plan_fact_notification.stage stage,
                      okb_db_zak.DSE_NAME AS dse_name,
                      okb_db_zak.DSE_OBOZ AS dse_draw,
                      okb_db_plan_fact_notification.id AS note_id,
                      okb_db_plan_fact_notification.why AS why,
                      okb_db_plan_fact_notification.description AS description
                      FROM
                      okb_db_plan_fact_notification
                      LEFT JOIN okb_db_zak_stages ON okb_db_plan_fact_notification.stage = okb_db_zak_stages.id
                      LEFT JOIN okb_db_zak ON okb_db_plan_fact_notification.zak_id = okb_db_zak.ID
                      LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                      WHERE
                      okb_db_plan_fact_notification.to_user = $user_id
                      AND
                      okb_db_plan_fact_notification.ack = 0
#                      LIMIT 1
                      " ;

                      $stmt = $pdo->prepare( $query );
                      $stmt->execute();

}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

  $total_count = $stmt -> rowCount() ;
  $row = $stmt->fetch(PDO::FETCH_OBJ ) ;

  if( $stmt -> rowCount())
  {
          $id = $row -> note_id ;
          $ord_id = $row -> ord_id ;
          $ord_type = $row -> ord_type ;
          $ord_name = $row -> ord_name ;
          $dse_name = $row -> dse_name ;
          $dse_draw = $row -> dse_draw ;
          $stage_name = $row -> stage_name ;
          $stage = $row -> stage ;
          $why = $row -> why ;
          $description = $row -> description ;

          $arr[] = [  "dse" => $dse_name, "ord_name" => "$ord_type $ord_name", "draw" => $dse_draw, "stage_name" => $stage_name, "id" => $id, "ord_id" => $ord_id, "stage" => $stage, "why" => $why, "description" => $description, 'total_count' => $total_count ];
}

echo json_encode( $arr );