<?php
//error_reporting( 0 );
require_once( "functions.php" );
require_once( "class.PlanFactCollector.php" );

$id = $_POST['id'];
$stage = $_POST['stage'];
$field = $_POST['field'];
$why = $_POST['why'];
$description = isset( $_POST['description'] ) ? $_POST['description'] : '';
$notifier_user_id = $_POST['user_id'];

$receivers = [];

if( $stage == 0 )
  $stage = getCurrentStatus( $id );

if( $why == 2 )
{
      switch( $stage )
        {
                    case 10 :
                                        $receivers = getResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP ) ;
                                                                break ;
                    case 20 :
                                        $receivers = getResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ) ;
                                        break ;

                    case 30 :
                                        $receivers = getResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ) ;
                                        break ;
                    case 40 :
                    case 41 :
                                        $receivers = getResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ) ;
                                        break ;
        }

}
if( $why == 4 )
{
              $receivers =
              array_merge(
                                    getHeadResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP ) ,
                                    getHeadResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ),
                                    getHeadResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ),
                                    getHeadResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP )
              ) ;

// for debug
//$receivers = [ 1 ];
}
foreach(  $receivers AS $user_id )
{
  if(  $user_id == $notifier_user_id )
    continue;

        try
        {
            $query = "
                              INSERT
                              INTO `okb_db_plan_fact_notification` (`id`, `why`,`to_user`, `zak_id`, `field`, `stage`, `description`,`ack`,`timestamp`)
                              VALUES (NULL, $why, '$user_id', $id, '$field', '$stage', '$description', '0', NOW());
                              " ;

                             $stmt =  $pdo->prepare( $query );
                             $stmt->execute();

        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
}

echo $query;
//echo $notifier_user_id;