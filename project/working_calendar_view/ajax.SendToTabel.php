<?php
header('Content-Type: text/html');
// error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php");
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php");

global $pdo;

$from_date = ReformatDate( $_POST['from_date'] );
$to_date = ReformatDate( $_POST['to_date'] );
$data = join( ',', $_POST['data'] );

try
{
    $query ="SELECT user_id AS id, 
        DATE_FORMAT( date, '%Y%m%d') AS date, 
        hour_count AS hours
        FROM `okb_db_working_calendar` 
        WHERE 
        date >= '$from_date' 
        AND 
        date <= '$to_date' 
        AND user_id IN ( $data )
        ORDER BY date
        ";
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$arr = [];  

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
   {
      $id = $row -> id ;
      $date = $row -> date ;      
      $hours = $row -> hours ;
      if( isset( $arr[$id][$date] ) )
        $arr[$id][$date] += $hours;
         else
            $arr[$id][$date] =  $hours;
   }


   foreach( $arr AS $key => $value )
   {
      $id = $key ;

        foreach( $value AS $date => $hours )      
        {
            try
            {
                $query ="SELECT ID AS id
                    FROM `okb_db_tabel` 
                    WHERE 
                    DATE = $date 
                    AND 
                    ID_resurs = $id
                    ";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            if( $row = $stmt->fetch( PDO::FETCH_OBJ )) // Record found
              {
                $rec_id = $row -> id;

                try
                {
                    $query ="UPDATE `okb_db_tabel` SET FACT = $hours
                        WHERE 
                        DATE = $date 
                        AND 
                        ID_resurs = $id
                        ";
                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
                }
                catch (PDOException $e)
                {
                   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }

          } // if( $row = $stmt->fetch( PDO::FETCH_OBJ )) // Record found
      }// foreach( $value AS $date => $hours )      
   }

echo $query ;