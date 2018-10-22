<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$master_id = $_POST['master_id'];
$res_id = $_POST['res_id'];
$day = $_POST['day'];
$month = $_POST['month'];
$year = $_POST['year'];
$type = $_POST['type'];
$value = $_POST['value'];

try
{
    $query = "
                SELECT id
                FROM master_evaluation
                WHERE
                master_res_id = $master_id
                AND 
                date = '$year-$month-$day'
                AND
                eval_type = $type
                ";

               $stmt = $pdo->prepare( $query );
               $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
}

if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    $id = $row -> id;

if( $id )
{
    try
    {
        $query = "
                    UPDATE 
    				master_evaluation
                    SET score = $value, updated_by_res = $res_id
                    WHERE
                    id = $id
                    AND
                    eval_type = $type
                    ";

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
    }
}
else
{
 try
    {
        $query = "
                    INSERT INTO master_evaluation
                    ( master_res_id, eval_type, score, date, timestamp, updated_by_res )
                    VALUES
                    ( $master_id, $type, $value, '$year-$month-$day', NOW(), $res_id )
                    ";

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
    }   
}

echo $id;
 