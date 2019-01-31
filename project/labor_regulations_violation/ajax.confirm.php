<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( E_ALL );
//error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}


$day = $_POST['day'];
$month = $_POST['month'];
$year = $_POST['year'];
$dep_id = $_POST['dep_id'];
$user_id = $_POST['user_id'];
$shift = $_POST['shift'];

            try
            {
                $query = "
                            INSERT 
                            labor_regulations_violation_confirmation
                            ( dep_id, confirmed, confirmed_by, shift, date ) 
                            VALUES
                            (
                                $dep_id, 1, $user_id, $shift, '$year-$month-$day'
                            )
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            } 

echo $query;
 