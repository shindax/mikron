<?php
header('Content-Type: text/html');
error_reporting( 0 );

error_reporting( E_ALL );
ini_set('display_errors', true);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST[ 'id' ];

  global $pdo ;

              try
            {
                $query = "UPDATE `okb_db_plan_fact_notification`
                SET `ack` = '1'
                WHERE
                id=$id
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

echo $query;