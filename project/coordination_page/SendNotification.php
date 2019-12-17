<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php" );

function SendNotification( $persons, $email_arr, $user_id, $page_id, $male_message, $female_message, $href, $a_text, $a_from, $why, $stage = 0 )
{
  $persons = array_unique( $persons );
  $email_arr = array_unique( $email_arr );

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

      $message = $row-> gender == 1 ? $male_message : $female_message;

      $msg_a = "<a href=\"$href\" target=\"_blank\" data-from=\"$a_from\">$a_text</a>";
      $href = base64_encode( $href );
      $a = "<a href='https://okbmikron.ru/redirect.php?url=$href' target='_blank' data-from='$a_from'>$a_text</a>";    
      $theme_message = "$user_name $message $a_text";
      $body_message = "$user_name $message $a";

      foreach( $persons AS $key => $to_user )
                {
                  if( $user_id != $to_user )
                  {
                    try
                    {
                        $query ="
                                  INSERT INTO okb_db_plan_fact_notification
                                  ( id, why, to_user, zak_id, field, stage, ack, description, timestamp )
                                  VALUES ( NULL, $why, $to_user ,0 ,$page_id ,$stage ,0 ,'$user_name $message $msg_a', NOW())
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

    SendMail( $email_arr, strip_tags( $theme_message ) , $body_message );
}
