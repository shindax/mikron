<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( E_ALL );
// error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
  global $dbpasswd;
  if( strlen( $dbpasswd ) )
    return iconv( "UTF-8", "Windows-1251",  $str );
      else return $str ; 
}

$year = + $_POST['year'];
$month = + $_POST['month'];
$res_id = + $_POST['res_id'];
$score = + $_POST['score'];

try
{
    $query = "
                UPDATE 
                master_plan_evaluation
                SET score = '$score'
                WHERE
                master_res_id = $res_id
                AND
                year = $year
                AND
                month = $month
                ";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
}

echo $res_id;