<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);


require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$order_id = $_POST[ 'order_id' ];
$user_id = $_POST[ 'user_id' ];
$date = $_POST[ 'date' ];
$val = $_POST[ 'val' ];

    try
    {
        $query = "SELECT id FROM okb_db_working_calendar
        WHERE
        order_id=$order_id
        AND
        user_id=$user_id
        AND
        date = '$date'
        " ;
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

    $row_count = $stmt -> rowCount() ;

    if( $row_count )
    {
      $row = $stmt->fetch(PDO::FETCH_OBJ );
      $id = $row -> id ;
                try
              {
                  $query = "UPDATE okb_db_working_calendar
                  SET hour_count = $val
                  WHERE
                  id = $id ;
                  " ;
                  $stmt = $pdo->prepare( $query );
                  $stmt->execute();
              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
              }
    }
    else
    {
                try
              {
                  $query = "INSERT INTO okb_db_working_calendar
                  ( user_id, order_id, date, hour_count, state )
                  VALUES
                  ( $user_id, $order_id, '$date', $val, 0 )
                  " ;
                  $stmt = $pdo->prepare( $query );
                  $stmt->execute();
              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't insert data : " . $e->getMessage());
              }

  }

    try
        {
            $query ="
                        SELECT
                        SUM( hour_count ) hour_count
                        FROM
                        okb_db_working_calendar
                        WHERE
                        order_id=$order_id
                        AND
                        user_id=$user_id
                        ";

                  $stmt = $pdo->prepare( $query );
                  $stmt->execute();

           $row = $stmt->fetch( PDO::FETCH_OBJ );
           $hour_count = $row -> hour_count ? $row -> hour_count : 0 ;
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

echo $hour_count;