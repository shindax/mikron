<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( E_ALL );
//error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

function conv( $str )
{
  global $dbpasswd;
  if( strlen( $dbpasswd ) )
    return iconv( "UTF-8", "Windows-1251",  $str );
      else return $str ; 
}

function min_to_hour( $min )
{
    $sign = $min < 0 ? "-" : "" ;
    $min = abs( $min );
    $hours = intval( $min / 60 );
    $minutes= $min - $hours * 60;
    $result = $hours ? $hours.":". ( $minutes < 10 ? "0".$minutes : $minutes ) : $minutes.conv("м");
    return $sign.$result;
}

$year = + $_POST['year'];
$month = + $_POST['month'];
$dep_id = + $_POST['dep_id'];
$value = + $_POST['val'];
$viol_minutes = + $_POST['viol'];

$norm_plan_minus_viol = 0;
$score = 0;

$norm_plan_minus_viol = $value * 60 - $viol_minutes;
$score = $value != 0 ? number_format( $norm_plan_minus_viol * 5 / ( $value * 60 ), 2 ) : 0;
$score = $score < 0 ? 0 : $score ;

try
{
    $query = "
                SELECT id FROM department_month_norm_plan
                WHERE
                dep_id = $dep_id
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
    if( $stmt -> rowCount() )
    {
        try
            {
                $query = "
                            UPDATE 
                            department_month_norm_plan
                            SET plan = '$value', score = '$score'
                            WHERE
                            dep_id = $dep_id
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
    }
    else
    {
     try
            {
                $query = "
                            INSERT INTO department_month_norm_plan
                            ( dep_id, year, month, plan, score )
                            VALUES( $dep_id, $year, $month, $value, $score )
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }   
    }

$arr = [ min_to_hour( $norm_plan_minus_viol ), $score, $norm_plan_minus_viol, $value, $viol_minutes ];

echo json_encode( $arr ) ;
 