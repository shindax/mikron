<?php
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PlanFactCollector.php" );
require_once( "functions.php" );

$id = $_POST['id'];
$status = $_POST['status'];
$receivers = [];

try
{
    $query = "
			    SELECT 
				zak.NAME AS zak_name, 
				zak.TID AS tid,
				zak_type.description AS zak_type
				FROM `okb_db_zak` AS zak
				LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
				WHERE zak.id = $id
				";
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
$tid = $row -> tid ;
$zak_name = $row -> zak_type." ".$row -> zak_name;

try
{
    $query = "UPDATE okb_db_zak  
    SET ID_status = $status
    WHERE 
    ID=$id" ;
    
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

if( $status == 1 && $tid == 1 ) // Заказ типа 'ОЗ' выполнен
{
// Make notification
	$why = PLAN_FACT_STATE_CHANGE;

	$receivers = getResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP );
	
	$receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ));    
	$receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::COOPERATION_GROUP ));
	$receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ));
	$receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ));

	$receivers = array_unique( $receivers );
	$alink = "<a href=\"index.php?do=show&formid=39&id=$id\" target=\"_blank\">$zak_name</a>";
	$description = conv( "Заказ выполнен. Необходимо заполнить сводно-аналитический отчет" );

	foreach( $receivers AS $user_id )
	{
	        try
	        {
	            $query = "
	                              INSERT
	                              INTO `okb_db_plan_fact_notification` (`id`, `why`,`to_user`, `zak_id`, `field`, `stage`, `description`,`ack`,`timestamp`)
	                              VALUES (NULL, $why, $user_id, $id, 'pd11', $status, '$description', 0, NOW());
	                              " ;

	                             $stmt =  $pdo->prepare( $query );
	                             $stmt->execute();

	        }
	        catch (PDOException $e)
	        {
	           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	        }
	}
}

echo $query;
