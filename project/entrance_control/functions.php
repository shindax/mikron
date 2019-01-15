<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
define(
            "LOCAL_FILES_PATH",
            $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."project".DIRECTORY_SEPARATOR.$files_path."/db_entrance_control@FILENAME/" );

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

function GetAllPages( $cur_num )
{
  global $pdo ;
  $options = '';

      try
      {
          $query ="
                          SELECT * FROM `okb_db_entrance_control_pages`
                          WHERE 1
                          ORDER BY id DESC
                      ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $options .= "<option value='".( $row -> id )."' ". ( $row -> id == $cur_num ? "selected" : "" ) .">".( conv( $row -> page_num ))."</option>";

      return $options  ;
}


function GetFirstPageNum()
{
  global $pdo ;
  $num = 0 ;

      try
      {
          $query ="SELECT MIN( id ) AS num FROM `okb_db_entrance_control_pages` WHERE 1";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $num =$row -> num;

      return $num  ;
}

function GetPageNums()
{
  global $pdo ;
  $num = 0 ;

      try
      {
          $query ="SELECT MAX( id ) AS num FROM `okb_db_entrance_control_pages` WHERE 1";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $num =$row -> num;

      return $num  ;
}

function GetPagesNumArr( $year, $month )
{
  global $pdo ;
  $pages = [] ;

      try
      {
          $query ="
                    SELECT id 
                    FROM `okb_db_entrance_control_pages` 
                    WHERE date BETWEEN '$year-$month-01' AND '$year-$month-31'
                    ORDER BY id DESC";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $pages[] = $row -> id;

      return $pages  ;

}

function SendNotification( $direction, $head_only, $user_id, $male_message, $female_message, $why, $entrance_control_page_id = 0 )
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

              try
                {
                    $query = "SELECT
                              responsible_persons.persons AS persons
                              FROM okb_db_responsible_persons AS responsible_persons
                              WHERE responsible_persons.id = $direction";
                    $stmt = $pdo -> prepare( $query );
                    $stmt -> execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
                }

                $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
                $persons = json_decode( $row ->  persons );

                if( $head_only )
                  $persons = [ $persons[0] ];

                foreach( $persons AS $key => $value )
                {
                    try
                    {
                        $query ="
                                  INSERT INTO okb_db_plan_fact_notification
                                  ( id, why, to_user, zak_id, field, stage, ack, description, timestamp )
                                  VALUES ( NULL, $why, $value,0 , $entrance_control_page_id ,0,0,'$user_name $message', NOW())
                                  ";
                        $stmt = $pdo->prepare( $query );
                        $stmt -> execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                }
}