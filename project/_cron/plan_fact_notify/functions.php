<?php

// Подготовка
// PD1 - КД
// PD2 - Нормы расхода
// PD3 - МТК

// Комплектация
// PD4 - Проработка
// PD7 - Поставка

// Производство
// PD12 - Дата нач.
// PD8  - Дата оконч.
// PD13 - Инструмент и остнастка

// Коммерция
// PD9  - Предоплата
// PD10 - Оконч.расчет
// PD11 - Поставка

date_default_timezone_set('Asia/Krasnoyarsk');

const  PREPARE_GROUP = 1;
const  EQUIPMENT_GROUP = 2;
const  PRODUCTION_GROUP = 4 ;
const  COMMERTION_GROUP = 5;

    $dblocation = "127.0.0.1";
    $dbname = "okbdb";
    $charset = 'utf8';
    $dbuser = "root";
    $dbpasswd = "";

    $count = 1 ;
  	
    $dsn = "mysql:host=$dblocation;dbname=$dbname;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

  try{
        $pdo = new PDO($dsn,$dbuser, $dbpasswd, $opt);
     }
  catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't connect: " . $e->getMessage());
    }

function getResponsiblePersonsID( $direction )
{
  global $pdo ;

        try
        {
            $query = "SELECT * FROM `okb_db_responsible_persons` WHERE id = $direction";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        $row = $stmt->fetch( PDO::FETCH_OBJ );

   return   json_decode( $row -> persons , true );
}

function getHeadResponsiblePersonsID( $direction )
{
    $arr = getResponsiblePersonsID( $direction );
    $val = array_shift ( $arr );
    return [  $val ];
}

function conv( $str )
{
  $result = iconv("UTF-8", "Windows-1251", $str );
  $result = $str ;
  return $result;
}


function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function getDayDiff( $now, $date )
{
      $datetime2 = new DateTime("00:00:00 $date");
      $interval = $now->diff( $datetime2 );
      return 1 * $interval->format('%R%a');
}


function getState( $str )
{
      $arr = explode( "#", $str );
      $el = $arr[ 0 ];
      $arr = explode( "|", $el );

      if( isset( $arr[0] ) )
        return 1 * $arr[0];
}

function getLastDate( $str )
{
      $arr = explode( "#", $str );
      $el = $arr[ count( $arr ) - 1 ];
      $arr = explode( " ", $el );
      return $arr[0];
}

function checkStageStepName( $stage_step )
{
    $stage_step_name = '';

    switch( $stage_step )
    {
      case 'pd1' : $stage_step_name = 'КД'; break ;
      case 'pd2' : $stage_step_name = 'Нормы расхода'; break ;
      case 'pd3' : $stage_step_name = 'МТК'; break ;

      case 'pd4' : $stage_step_name = 'Проработка'; break ;
      case 'pd7' : $stage_step_name = 'Комплектация'; break ;

      case 'pd12' : $stage_step_name = 'Пр-во. Дата нач.'; break ;
      case 'pd8' : $stage_step_name = 'Пр-во. Дата оконч.'; break ;
      case 'pd13' : $stage_step_name = 'Инструмент и оснастка'; break ;

      case 'pd9' : $stage_step_name = 'Предоплата'; break ;
      case 'pd10' : $stage_step_name = 'Оконч. расчет'; break ;
      case 'pd11' : $stage_step_name = 'Поставка'; break ;
    }

    return conv( $stage_step_name );
}

function checkItem( $now, $id, $stage, $state, $last_date, $stage_step, $receivers )
{
  global
            $group_heads,
            $prepare_group,
            $equipment_group,
            $production_group,
            $commertion_group,
            $all_groups ;

    $send = 0 ;
    $last_date_diff = getDayDiff( $now, $last_date );
    $last_date_arr = explode( ".", $last_date );

    if( // Processing 2018 year only
        $state 
        || 
        $last_date == '' 
        || 
        $last_date_arr [2] < 2018 // Processing 2018 year only
       )
        return ; 

    $stage_step_descr = checkStageStepName( $stage_step );

    if( $last_date_diff == 0 )
    {
            $send = 0 ; // $send = 1 ; Don't send stage ending notification now
                        // Не посылать это уведомление. По просьбе Трифонова 19.04.2018 отключено уведомление "Окончание этапа "
            $why = 6 ;
            $message = conv("День окончания этапа ");
            $receivers_descr = conv( "Получатели : все службы" );
            $receivers = $all_groups ;
    } // if( $last_date_diff == 0 )
    else
      {
        if( $last_date_diff < 1 )
        {
            $send = 1 ;
            $why = 5 ;
            $message = conv("Просрочка даты окончания этапа ");
            $receivers_descr = conv( "Получатели : все службы" );
            $receivers = $all_groups ;
        } // if( $last_date_diff == 1 )
            else
            {
                if( $last_date_diff == 1 )
                {
                    $send = 1 ;
                    $send = 0 ; // Не посылать это уведомление. По просьбе Трифонова 06.02.2018 отключено уведомление "1 день до даты окончания этапа "

                    $why = 3 ;
                    $message = conv("1 день до даты окончания этапа ");
                    $receivers_descr = conv("Получатели : Руководители служб" );
                    $receivers = $group_heads ;
                } // if( $last_date_diff == 1 )
                else
                {
                      if( $last_date_diff > 1 && $last_date_diff <= 5 )
                      {
                        if( $stage_step == 'pd4' || $stage_step == 'pd7' )
                        {
                          $send = 1 ;
                          $why = 8 ;
                          $message = conv("5 или менее дней до даты окончания этапа ");
                          $receivers_descr = conv( "Получатели : ОМТС" );
                          $receivers = $equipment_group ;
                        }

                      }// if( $last_date_diff > 1 && $last_date_diff <= 5 )
                      else
                      {
                            if( $last_date_diff > 5 && $last_date_diff <= 10 )
                            {
                        if( $stage_step == 'pd12' || $stage_step == 'pd8' || $stage_step == 'pd13' )
                              {
                                $send = 1 ;
                                $why = 7 ;
                                $message = conv("10 или менее дней до даты окончания этапа ");
                                $receivers_descr = conv( "Получатели : ПДО" );
                                $receivers = $production_group ;
                              }
                            }// if( $last_date_diff > 1 && $last_date_diff <= 5 )
                      } // else if( $last_date_diff > 1 && $last_date_diff <= 5 )
                } // else if( $last_date_diff == 1 )

            } // else if( $last_date_diff == 1 )
         } // else if( $last_date_diff == 0 )


        if( $send )
              makeNotification( $id, $why, $stage, $message, $stage_step, $stage_step_descr , $last_date, $receivers_descr, $receivers );
}

function makeNotification( $id, $why, $stage, $msg, $stage_step, $stage_step_descr, $date, $receivers_descr, $receivers )
{
  global $pdo, $count;

  $description = $msg.$stage_step_descr.". ".$date." ".$receivers_descr;

  $date_arr = explode(".", $date);
  $date_str = '';

  if( isset( $date_arr[2] ) && isset( $date_arr[1] ) && isset( $date_arr[0] )  )
      $date_str = $date_arr[2]."-".$date_arr[1]."-".$date_arr[0]." 00:00:00";

foreach(  $receivers AS $user_id )
        {
       try
        {
            $stmt = $pdo->prepare( "
                                                                  SELECT id
                                                                  FROM `okb_db_plan_fact_notification`
                                                                  WHERE
                                                                  to_user = $user_id
                                                                  AND
                                                                  zak_id = $id
                                                                  AND
                                                                  field = '$stage_step'
#                                                                  AND
#                                                                  ack = 0
                                                                  " );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
       if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        {
               $row_id = $row -> id ;

                try
                {
                    $query = "
                                      UPDATE `okb_db_plan_fact_notification`
                                      SET `why` = $why, `stage` = $stage, `description` = '$description',`ack` = 0
                                      WHERE id = $row_id
                                      " ;

                                     $stmt =  $pdo->prepare( $query );
                                     $stmt->execute();
//                                     echo "$count Updating. New date is $date<br>";
                                     $count ++;
                }
                catch (PDOException $e)
                {
                   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }
        }
        else
        {
                try
                {
                    $query = "
                                      INSERT
                                      INTO `okb_db_plan_fact_notification` (`id`, `why`,`to_user`, `zak_id`, `field`, `stage`, `description`,`ack`,`timestamp`)
                                      VALUES (NULL, $why, '$user_id', $id, '$stage_step', '$stage', '$description', '0', NOW() );
                                      " ;

                                     $stmt =  $pdo->prepare( $query );
                                     $stmt->execute();
//                                     echo "$count Inserting. Date is $date<br>";      
                                     $count ++;                               

                }
                catch (PDOException $e)
                {
                   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }
           }
        }
}
