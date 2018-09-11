<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$user_id = $_POST[ 'user_id' ];
$rate = $_POST[ 'val' ];
$field = $_POST[ 'field' ];

$str = $user_id. " : ". $rate ." : ". $field ;

  global $pdo ;

  $query = "SELECT data FROM `okb_db_personal_data` WHERE user_id=$user_id";

        try
        {
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }

       $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
       $data = json_decode( $row -> data, true );
       $data['base_penalty_rate'] = $rate ;
       $new_data = json_encode( $data );

              try
            {
                $query = "UPDATE `okb_db_personal_data`
                SET `data` = '$new_data'
                WHERE
                user_id=$user_id
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

echo $new_data;