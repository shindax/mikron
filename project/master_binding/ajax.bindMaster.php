<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
//error_reporting( E_ALL );
error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$row_id = $_POST['row_id'];
$res_id = $_POST['res_id'];

            try
            {
                $query = "
                            UPDATE 
							okb_db_otdel
                            SET master_res_id = $res_id
                            WHERE
                            id = $row_id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }

echo $query;
 