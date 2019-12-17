<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );

error_reporting( E_ALL );
// error_reporting( 0 );
date_default_timezone_set("Asia/Krasnoyarsk");

$year = + $_POST['year'];
$month = + $_POST['month'];
$day = $_POST['day'];
$res_id = + $_POST['res_id'];
$value = + $_POST['val'];
$viol_minutes = + $_POST['viol'];

$norm_plan_minus_viol = 0;
$score = 0;

$norm_plan_minus_viol = $value * 60 - $viol_minutes;
$score = 
        $value == 0 ? 0
        :
        number_format( $norm_plan_minus_viol * 5 / ( $value * 60 ), 2 ) ;

// shindax 05.08.2019
$score = score_calc( $value,  $viol_minutes / 60 );
$score = $score < 0 ? 0 : $score ;

try
{
    $query = "
                SELECT id, plan FROM master_plan_evaluation
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
    if( $stmt -> rowCount() )
    {
        $row = $stmt->fetch( PDO::FETCH_OBJ );
        $id = $row -> id ;
        $plan = json_decode( $row -> plan, true );
        $plan[ $day ] = $value ;
        $plan = json_encode( $plan );

        try
            {
                $query = "
                            UPDATE 
                            master_plan_evaluation
                            SET plan = '$plan'
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
    }
    else
    {
        $plan = array_fill( 1, 31, 0 );
        $plan[ $day ] = $value ;
        $plan = json_encode( $plan );

     try
            {
                $query = "
                            INSERT INTO master_plan_evaluation
                            ( master_res_id, year, month, plan, score )
                            VALUES( $res_id, $year, $month, '$plan', $score)
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage()." Query is $query");
            }   
    }


$arr = [ $norm_plan_minus_viol, $norm_plan_minus_viol, $score, $value, $viol_minutes ];

$str = json_encode( $arr );
echo $str;
 