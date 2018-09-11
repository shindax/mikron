<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
date_default_timezone_set("Asia/Krasnoyarsk");

function getArg( $var )
{
   return isset( $var ) ? $var : 0 ;
}

$id = getArg( $_POST['id'] )  ;
$state = getArg( $_POST['state'] );

                try
                {
                    $query = "
                                      UPDATE `okb_db_shtat`
                                      SET presense_in_shift_orders = $state WHERE id = $id" ;
                                     $stmt =  $pdo->prepare( $query );
                                     $stmt->execute();
                }
                catch (PDOException $e)
                {
                   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }

echo "$id : $state ";

