<?php
error_reporting( E_ALL );
require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.PlanFactCollector.php" );

function MakeAnalitycsReport( $zak_id, $zak_type )
{
    global $pdo;
    $receivers = [];
        
  if( $zak_type == 1 ) // Заказ типа 'ОЗ' выполнен
  {
  // Make notification
    $why = PLAN_FACT_STATE_CHANGE;

    $receivers = getResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP );
    $receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ));    
    $receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::COOPERATION_GROUP ));
    $receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ));
    $receivers = array_merge( $receivers, getResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ));

    $alink = "<a href=\"index.php?do=show&formid=39&id=$zak_id\" target=\"_blank\">$zak_name</a>";

    $description = "Заказ выполнен. Необходимо заполнить сводно-аналитический отчет";

    foreach( $receivers AS $user_id )
    {
            try
            {
                $query = "
                                  INSERT
                                  INTO `okb_db_plan_fact_notification` (`id`, `why`,`to_user`, `zak_id`, `field`, `stage`, `description`,`ack`,`timestamp`)
                                  VALUES (NULL, $why, $user_id, $zak_id, 'pd8', 0, '$description', 0, NOW());
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
} // functions MakeAnalitycsReport( $zak_id )

$id = $_POST['id'];
$stage = $_POST['stage'];
$field = $_POST['field'];
$why = $_POST['why'];
$description = isset( $_POST['description'] ) ? $_POST['description'] : '';
$notifier_user_id = $_POST['user_id'];

$receivers = [];
$query = "";

if( $stage == 0 )
  $stage = getCurrentStatus( $id );

$zak_type = getZakType( $id );

// shindax 25.12.2018 Исключены уведомления коммерческого отдела для заказов типа "ХЗ" и "ВЗ" ( TID == 5 || TID == 6 )

if( $why == PLAN_FACT_STATE_CHANGE )
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

                                        MakeAnalitycsReport( $id, $zak_type );
                                        $receivers = ( $zak_type == 5 || $zak_type == 6 ) ? [] : getResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ) ;
                                        break ;
        }
}
if( $why == PLAN_FACT_DATE_CHANGE )
{
              $receivers =
              array_merge(
                                    getHeadResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP ) ,
                                    getHeadResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ),
                                    getHeadResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ),
                                    ( $zak_type == 5 || $zak_type == 6 ) ? [] : array_merge( getHeadResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ), [ 128 ] )
              ) ;

// Помимо Трифонова уведомления о переносах дат получает Рудакова И.В ( user_id = 128 )

// for debug
//$receivers = [ 1 ];
}

if( $why == PLAN_FACT_CONFIRMATION_REQUEST )
{
switch( $stage )
        {
                    case 1 :
                                    $receivers = getResponsiblePersonsID( PlanFactCollector::PREPARE_GROUP ) ;
                                        break ;
                    case 2 :
                                    $receivers = getResponsiblePersonsID( PlanFactCollector::EQUIPMENT_GROUP ) ;
                                        break ;

                    case 4 :
                                    $receivers = getResponsiblePersonsID( PlanFactCollector::PRODUCTION_GROUP ) ;
                                        break ;
                    case 5 :
                                    $receivers = getResponsiblePersonsID( PlanFactCollector::COMMERTION_GROUP ) ;
                                        break ;
        }

  $stage = $notifier_user_id ;        

// Отключено по запросу от Матиковой 11.02.2019
$receivers = [];
}     

foreach( $receivers AS $user_id )
{
// Трифонов может посылать уведомления об изменении дат этапов сам себе  
    if( 
        $user_id == $notifier_user_id 
        && 
        !( $user_id == 145 && $why == PLAN_FACT_DATE_CHANGE )
      )
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

echo $notifier_user_id." : ".$stage;
