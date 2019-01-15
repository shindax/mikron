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

$id = $_POST['id'];
$page_id = $_POST['page_id'];
$user_id = $_POST['user_id'];
$row_id = $_POST['row_id'];
$date = date("Y-m-d");
$ins_time = date("Y-m-d H:i:s");
$ins_loc_time = date("d.m.Y H:i");

try
{
    $query = "
                UPDATE 
                coordination_page_items
                SET 
                coordinator_id = $user_id, 
                date = '$date', 
                ins_time = '$ins_time' 
                WHERE page_id=$page_id AND row_id = 5
                ";

                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
}

$str = $ins_loc_time;

if( strlen( $dbpasswd ) )
    echo $str ;
        else
            echo $str;
 