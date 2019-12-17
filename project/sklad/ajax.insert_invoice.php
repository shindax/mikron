<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$id = $_POST['id'] ;
$count = $_POST['count'] ;
$user_id = $_POST['user_id'] ;

      try
      {
          $query = "SELECT MAX(batch) AS batch FROM okb_db_warehouse_reserve WHERE 1";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
      catch (PDOException $e)
      {
         die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
      }

      $row = $stmt->fetch( PDO::FETCH_OBJ );
      $batch = $row -> batch;
      $batch ++ ;

      try
      {
          $query ="INSERT 
                    INTO okb_db_warehouse_reserve
                    SET batch = $batch, tier_id = $id, count=$count, state = 0, user_id=$user_id, timestamp= NOW()
                    ";
                      
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }


echo $pdo -> lastInsertId ();