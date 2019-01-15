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

$page_id = $_POST['page_id'];
$task_id = $_POST['task_id'];
$user_id = $_POST['user_id'];

if( $task_id == 6 ) // Поставка 2
    $where = "( task_id = 6 OR task_id = 14 )";
if( $task_id == 7 ) // Поставка 3
    $where = "( task_id = 7 OR task_id = 15 )";

try
            {
                $query = "
                            UPDATE 
							coordination_page_items
                            SET ignored = 1 
                            WHERE page_id = $page_id AND $where";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query is : $query");
            }


    try
            {
                $query = "
                            SELECT krz2_id
                            FROM 
                            coordination_pages
                            WHERE id = $page_id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query is : $query");
            }

             if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                $krz2_id = $row -> krz2_id ;

$cp = new CoordinationPage( $pdo, $user_id, $krz2_id );
$str = $cp -> GetTable();

if( strlen( $dbpasswd ) )
    echo $str ; 
      else
        echo iconv("Windows-1251", "UTF-8", $str );