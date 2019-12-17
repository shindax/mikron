<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( "functions.php" );

$error = false;
$error_msg = '';
$date = $_POST['date'];

$project_plan_date = DateConvert( $_POST['project_plan_date'] );
$plan_date = DateConvert( $_POST['plan_date'] ) ;
$report_date = DateConvert( $_POST['report_date'] ) ;

        try
        {
            $query =" UPDATE okb_db_protocol_images_ref_dates 
            SET 
            project_plan_date = '$project_plan_date', 
            plan_date = '$plan_date', 	
            report_date = '$report_date' 
            WHERE ref_date = '$date'";

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }


$data = $error ? ['error' => $error_msg ] : [ 'query' => $query , 'date' => $date, 'report_date' => $report_date, 'plan_date' => $plan_date, 'project_plan_date' => $project_plan_date ];
echo json_encode(str_replace(array("\r", "\n", "\t"), "", $data) );
?>
