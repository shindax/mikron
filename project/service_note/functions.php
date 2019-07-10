<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );


define(
            "LOCAL_FILES_PATH",
            $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."project".DIRECTORY_SEPARATOR.$files_path."/service_note@FILENAME/" );

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

function GetPagesArr( $year, $month )
{
  global $pdo ;
  $pages = [] ;

      try
      {
          $query ="
                    SELECT id 
                    FROM `service_notes` 
                    WHERE creation_date BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59'
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

function GetResList()
{
  global $pdo ;
  $options = "" ;  

      try
      {
          $query ="
                    SELECT ID AS id, NAME AS name 
                    FROM `okb_db_resurs` 
                    WHERE TID = 0
                    ORDER BY name";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              if( $row -> id )
                $options .= "<option value='".$row -> id."'>".conv( $row -> name )."</option>";

  return $options;
}

function GetResInfo( $user_id )
{
  global $pdo;

    try
    {
       $query ="SELECT ID, NAME FROM `okb_db_resurs` WHERE ID_users = $user_id";
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $res_id = 0 ;
    
    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $res_id = $row -> ID;

    return $res_id ;
 }

