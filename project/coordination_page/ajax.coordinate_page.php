<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPage.php" );
//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$date = new DateTime();
$time = $date->format('H:i:s');

$page_id = $_POST['page_id'];
$coord_date = "{$_POST['coord_date']} $time";

try
            {
                $query = "
                            UPDATE 
							coordination_pages
                            SET 
                            coordinated = '$coord_date'
                            WHERE
                            id = $page_id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

echo $query;
 