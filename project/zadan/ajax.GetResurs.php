<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$date = $_POST['date'];
$shift = $_POST['shift'];
$result = '';
$date_arr = explode(".", $date );
$date = $date_arr[2].$date_arr[1].$date_arr[0] ;


function conv( $str )
{
        // $result = iconv("UTF-8", "Windows-1251", $str );
        $result = $str ;
  return $result;
}

try
      {
          $query ="
                                    SELECT
                                    okb_db_zadanres.ID_resurs AS id,
                                    okb_db_resurs.NAME AS name
                                    FROM
                                    okb_db_zadanres
                                    INNER JOIN okb_db_resurs ON okb_db_zadanres.ID_resurs = okb_db_resurs.ID
                                    WHERE
                                    okb_db_zadanres.DATE = $date
                                    AND
                                    okb_db_zadanres.SMEN = $shift
                                    ORDER BY name
                  ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
         catch (PDOException $e)
         {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
         }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
               $result .= "<option value='".( $row -> id )."'>".( $row -> name )."</option>";

echo conv( $result );
