<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPage.php" );
require_once( "SendNotification.php" );
// error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$page_id = $_POST['page_id'];
$user_id = $_POST['user_id'];
$row_id = $_POST['row_id'];
$date = date("Y-m-d");
$ins_time = date("Y-m-d H:i:s");
$ins_loc_time = date("d.m.Y H:i");

try
{
    $query = "
                SELECT
                coordination_pages.id AS id,
                coordination_pages.krz2_id AS krz2_id,
                okb_db_krz2det.NAME AS unit_name,
                okb_db_krz2.`NAME` AS krz2_name
                FROM coordination_pages
                LEFT JOIN okb_db_krz2 ON coordination_pages.krz2_id = okb_db_krz2.ID
                LEFT JOIN okb_db_krz2det ON okb_db_krz2det.ID_krz2 = okb_db_krz2.ID
                WHERE
                coordination_pages.id = $page_id
                ";

                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query is $query");
}

$row = $stmt->fetch(PDO::FETCH_OBJ );
$krz2_id = $row -> krz2_id ;
$krz2_name = conv( $row -> krz2_name );
$unit_name = $row -> unit_name;


try
{
    $query = "
                UPDATE 
                coordination_page_items
                SET 
                coordinator_id = $user_id, 
                date = '$date', 
                ins_time = '$ins_time' 
                WHERE page_id=$page_id AND row_id = $row_id
                ";

                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
}

$str = $ins_loc_time;

if( $row_id == 5 ) // После подтверждения ОВК уведомления уходят в ПДО
    $sel_row = 6; 

if( $row_id == 9 ) // После подтверждения Матонина уведомления уходят в ОМТС
    $sel_row = 4; 

try
                        {
                            $query = "
                                        SELECT 
                                        `user_arr`,
                                        `email_arr`
                                        FROM `coordination_pages_rows` 
                                        WHERE 
                                        id = $sel_row
                                        ";

                                        $stmt = $pdo->prepare( $query );
                                        $stmt -> execute();
                        }

                        catch (PDOException $e)
                        {
                           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
                        }

                $row = $stmt->fetch(PDO::FETCH_OBJ );
                $user_arr = json_decode( $row -> user_arr );
                $email_arr = json_decode( $row -> email_arr );

                $href = "index.php?do=show&formid=30&id=$krz2_id";
                $a_text = "$krz2_name ( $unit_name )";
                $a_from = "ajax-save_acquainted";

                $male_message = "внес изменения в лист согласования № $page_id по КРЗ2";
                $female_message = "внесла изменения в лист согласования № $page_id по КРЗ2";
 
                SendNotification( $user_arr, $email_arr, $user_id, $page_id, $male_message, $female_message, $href, $a_text, $a_from, COORDINATION_PAGE_DATA_MODIFIED );

if( strlen( $dbpasswd ) )
    echo $str ;
        else
            echo $str;
 