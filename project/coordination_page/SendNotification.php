<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php" );

function SendNotification( $persons, $email_arr, $user_id, $page_id, $male_message, $female_message, $why )
{

  global $pdo ;
  $query = '';

        try
      {
          $query ="
                      SELECT 
                      users.FIO AS name,
                      resurs.GENDER AS gender 
                      FROM `okb_users` users
                      INNER JOIN `okb_db_resurs` AS resurs ON resurs.ID_users = users.ID
                      WHERE users.ID=$user_id";

          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
      }

      $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
      $user_name = $row-> name ;
      $gender = $row-> gender ;

      if( $gender == 1 )
        $message = $male_message;
          else
            $message = $female_message;

                foreach( $persons AS $key => $to_user )
                {
                  if( $user_id != $to_user )
                  {
                    try
                    {
                        $query ="
                                  INSERT INTO okb_db_plan_fact_notification
                                  ( id, why, to_user, zak_id, field, stage, ack, description, timestamp )
                                  VALUES ( NULL, $why, $to_user ,0 ,$page_id ,0 ,0 ,'$user_name $message', NOW())
                                  ";
                        $stmt = $pdo->prepare( $query );
                        $stmt -> execute();

                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()).". Query : $query";
                    }
                  }
                }

    // SendMail( $email_arr, strip_tags( "$user_name $message" ), strip_tags( "$user_name $message" ) );
}
