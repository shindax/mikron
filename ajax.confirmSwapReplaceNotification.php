<?php
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;
$user_id = $_POST['user_id'];

      try
      {
          $query ="
                          UPDATE `okb_db_chack_swap_replace_notification`
                          SET confirm = 1 
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


echo $user_id;
?>