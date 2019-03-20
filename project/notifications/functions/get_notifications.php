<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function getNotifications( $why_arr )
{
	global $pdo, $user_id;

	$why_list = join(",", $why_arr );
	$str = "";

try
{
    $query = 	"
				SELECT
				okb_db_plan_fact_notification.id,
				okb_db_plan_fact_notification.field field,
				okb_db_plan_fact_notification.stage stage,				
				okb_db_zak.`NAME` AS zak_name,
				okb_db_zak.`DSE_NAME` AS dse_name,
				okb_db_zak_type.description AS zak_type,
				okb_db_plan_fact_notification.description AS notification_description,
				DATE_FORMAT( okb_db_plan_fact_notification.timestamp, '%d.%m.%Y') AS notification_time,
				okb_db_plan_fact_notification.zak_id,
				okb_db_notification_types.area area,
				okb_db_plan_fact_notification.why why
				FROM
				okb_db_plan_fact_notification
				LEFT JOIN okb_db_zak ON okb_db_plan_fact_notification.zak_id = okb_db_zak.ID
				LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
				LEFT JOIN okb_db_notification_types ON okb_db_notification_types.ID = okb_db_plan_fact_notification.why
				WHERE
				okb_db_plan_fact_notification.to_user = $user_id 
				AND
				okb_db_plan_fact_notification.ack = 0
				AND
				okb_db_plan_fact_notification.why IN ( $why_list )
				";

  
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
}

if( $stmt -> rowCount() )
	while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$zak_id = $row -> zak_id ;
		$zak_name = conv( $row -> zak_name );
		$zak_type = conv( $row -> zak_type );
		$dse_name = conv( $row -> dse_name );	
		$rec_id = $row -> id ;
		$time = $row -> notification_time ;
		$description = conv( $row -> notification_description );
		$area = conv( $row -> area );
		$why = $row -> why ;
		$field = $row -> field ;
		$stage = $row -> stage ;		

		$str .= makeCard( $rec_id, $area, $zak_type." ".$zak_name, $dse_name, $zak_id, $description, $time, $why, $field, $stage );
	}
else
	$str .= conv("<h2>Нет непрочитанных уведомлений</h2>");

	return $str ;
}