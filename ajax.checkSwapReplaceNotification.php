<?php
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;
$user_id = $_POST['user_id'];
$result = 0 ;

      try
      {
          $query ="
                          SELECT confirm FROM `okb_db_chack_swap_replace_notification`
                          WHERE
                          user_id='$user_id'
                  ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

          if( $stmt -> rowCount() )
          {
            $row = $stmt->fetch(PDO::FETCH_OBJ );
            $result = $row -> confirm ;            
          }
          else
          {
                try
                {
                    $query ="
                                    INSERT INTO `okb_db_chack_swap_replace_notification`
                                    (id, user_id, confirm, timestamp) VALUES ( null, $user_id, 0, NOW() ) 
                            ";
                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
                    $result = 0;
                }
                  catch (PDOException $e)
                  {
                    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                  }
          }

echo $result;
?>