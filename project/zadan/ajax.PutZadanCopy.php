<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( E_ALL );

$row_count = 0;

function date_convert( $date, $convert_to = '' )
{
  $year = 0 ;
  $month = 0 ;
  $day = 0 ;
  $result = '' ;
  $date_arr = [];
  $pos = 0 ;

  $pos = stripos( $date, "." ) ;

  if( $pos ) // Search '.' delimiter
    {
      if( $pos != 2 )
        $result = "wrong date : $date";
      else
      {
        $date_arr = explode(".", $date );
        $year = $date_arr[2];
        $month = $date_arr[1];
        $day = $date_arr[0];
      }
    }
    else
    {
       $pos = stripos( $date, "-" ) ;

      if( $pos ) // Search '.' delimiter
      {
        if( $pos != 4 )
          $result = "wrong date : $date";
          else
          {
              $date_arr = explode("-", $date );
              $year = $date_arr[0];
              $month = $date_arr[1];
              $day = $date_arr[2];
          }
      }
      else
      {
         if( strlen( $date ) != 8 )
            $result = "wrong date : $date";

        $year = substr( $date, 0, 4 );
        $month = substr( $date, 4, 2 );
        $day = substr( $date, 6, 2 );

      }
    }

  if( $result == '' )
  {
    if( $convert_to == "." )
      $result = "$day.$month.$year";

    if( $convert_to == "-" )
      $result = "$year-$month-$day";

    if( $convert_to == "" )
    $result = $year.$month.$day ;
  }

  return $result ;
}

$date = date_convert( $_POST['date'] );
$shift = $_POST['shift'];
$resurs = $_POST['resurs'];
$zadan_arr = $_POST['zadan_arr'];
$zadan_copy_arr = [];

foreach( $zadan_arr AS $zadan )
{

        try
        {
                $query =    "
                                      CREATE TEMPORARY TABLE foo AS
                                      SELECT * FROM okb_db_zadan WHERE id = $zadan ";

              $stmt = $pdo->prepare( $query );
              $stmt -> execute();
          }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

        try
        {
                $query =    "
                                   UPDATE foo SET id=NULL, FACT=0, NUM_FACT=0, SMEN=$shift, DATE=$date, ID_resurs=$resurs, copied_from=$zadan" ;

              $stmt = $pdo->prepare( $query );
              $stmt -> execute();
          }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

        try
        {
                $query =    "SELECT * FROM foo WHERE 1";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
          }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
      }

      $foo_row = $stmt->fetch(PDO::FETCH_OBJ ) ;
      $foo_date = $foo_row -> DATE ;
      $foo_smen = $foo_row -> SMEN ;
      $foo_id_zak = $foo_row -> ID_zak ;
      $foo_id_zakdet = $foo_row -> ID_zakdet ;
      $foo_id_operitems = $foo_row -> ID_operitems ;
      $foo_id_park = $foo_row -> ID_park ;

        try
        {
                $query =    "SELECT * FROM `okb_db_zadan`
                                    WHERE
                                    `DATE` = $foo_date
                                    AND
                                    SMEN = $foo_smen
                                    AND
                                    ID_zak = $foo_id_zak
                                    AND
                                    ID_zakdet = $foo_id_zakdet
                                    AND
                                    ID_operitems = $foo_id_operitems
                                    AND
                                    ID_park =  $foo_id_park
                                    ";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
          }
      catch (PDOException $e)
      {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
      }

      $row_count = $stmt -> rowCount();

//      if( ! ( $row_count ) )
      {
          try
          {
                $query =    " INSERT INTO okb_db_zadan SELECT * FROM foo ";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
            }
          catch (PDOException $e)
          {
             die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
          }
      }

          try
          {
                $query =  "DROP TABLE foo ";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
            }
      catch (PDOException $e)
      {
         die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
      }

}

//echo "$date :  $shift : $resurs : ".join( $zadan_arr ,", ") ;
echo $row_count ;