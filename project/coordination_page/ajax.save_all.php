<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPage.php" );
require_once( "SendNotification.php" );
//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$id = $_POST['id'];
$date = $_POST['date'];
$comment = $_POST['comment'];
$user_id = $_POST['user_id'];
$row_id = $_POST['row_id'];
$task_id = $_POST['task_id'];
$ins_time = date("Y-m-d H:i:s");

    try
    {
        $query = "
                    SELECT
                    coordination_pages.id AS id,
                    coordination_pages.krz2_id AS krz2_id,
                    okb_db_krz2.`NAME` AS krz2_name 
                    FROM
                    coordination_page_items
                    LEFT JOIN coordination_pages ON coordination_pages.id = coordination_page_items.page_id
                    LEFT JOIN okb_db_krz2 ON coordination_pages.krz2_id = okb_db_krz2.ID
                    WHERE
                    coordination_page_items.id = $id
                    ";

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
    }

$row = $stmt->fetch(PDO::FETCH_OBJ );
$page_id = $row -> id ;
$krz2_id = $row -> krz2_id ;
$krz2_name = conv( $row -> krz2_name );

            try
            {
                $query = "
                            UPDATE 
							coordination_page_items
                            SET 
                            coordinator_id = $user_id, 
                            date = '$date', 
                            ins_time = '$ins_time',
                            comment = '$comment'
                            WHERE
                            id = $id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }
        try
            {
                $query = "
                            SELECT 
                            page.krz2_id krz2_id
                            FROM 
                            coordination_page_items
                            INNER JOIN coordination_pages page ON page.id = coordination_page_items.page_id 
                            WHERE
                            coordination_page_items.id = $id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }

             if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                $krz2_id = $row -> krz2_id ;

$cp = new CoordinationPage( $pdo, $user_id, $krz2_id );
$str = $cp -> GetTable();

            try
            {
                $query = "
                            SELECT row_id 
                            FROM 
                            coordination_page_items
                            WHERE
                            coordination_page_items.id = $id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }

             $row = $stmt->fetch(PDO::FETCH_OBJ );
             $cur_row = $row -> row_id ;

            try
            {
                $query = "
                            SELECT row_id 
                            FROM coordination_page_items 
                            WHERE id = 
                                (   SELECT MIN(id) 
                                    FROM coordination_page_items 
                                    WHERE 
                                        id > $id 
                                    AND 
                                    ignored = 0 
                                )
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }

             $row = $stmt->fetch(PDO::FETCH_OBJ );
             $next_row = $row -> row_id ;

// ( $task_id == 1 && $row_id == 1 ) 


             $sel_row = 0 ;

            if( $next_row != $cur_row )
                    $sel_row = $next_row ;

            if ( $task_id == 1 && $row_id == 1 ) 
                    $sel_row = 2 ;

            if ( $cur_row == 7 ) 
                    $sel_row = 8 ;

            if( $sel_row )
             {

                        try
                        {
                            $query = "
                                        SELECT 
                                        `user_arr` 
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

                $male_message = "внес изменения в лист согласования № $page_id по КРЗ2 <a href=\"index.php?do=show&formid=30&id=$krz2_id\" target=\"_blank\">$krz2_name</a>";
                $female_message = "внесла изменения в лист согласования № $page_id по КРЗ2 <a href=\"index.php?do=show&formid=30&id=$krz2_id\" target=\"_blank\">$krz2_name</a>";

               SendNotification( $user_arr, $user_id, $page_id, $male_message, $female_message, 12 );

                    $file = 'log.txt';
                    file_put_contents($file, "cur_row : $cur_row");

            }

echo $str ;
//echo "$cur_row : $next_row ".iconv("Windows-1251", "UTF-8", $str );
 