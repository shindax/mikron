<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$id = $_POST['id'];
$field = $_POST['field'];
$value = $_POST['value'];

            try
            {
                $query = "
                            UPDATE 
							labor_regulations_violation_items
                            SET $field = '$value' 
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

echo $query;
 