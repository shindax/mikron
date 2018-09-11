<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( "functions.php" );

$error = false;
$error_msg = '';
$date = $_POST['date'];

$date_arr = split('-', $date );

$year = $date_arr[0];
$month = $date_arr[1];

$project_plan_date = "$year-$month-26" ;
$plan_date = "$year-$month-26" ;

if( $month == 12 )
 {
  $year ++ ;
  $month = 1 ;
 }
 else
  $month ++ ;

$report_date = "$year-$month-05" ; 

try
{
    $query ="
                SELECT
                *
                FROM
                okb_db_protocol_images_ref_dates 
                WHERE
                ref_date = '$date'
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

$row_count = $stmt->rowCount();
if( $row_count )
    {
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $project_plan_date = $row->project_plan_date;
        $plan_date = $row->plan_date;
        $report_date = $row->report_date;
    }
    else
    {
        $row_count = 1;

        try
        {
            $query ="
                INSERT INTO okb_db_protocol_images_ref_dates 
                VALUES ( NULL, '$date','$project_plan_date','$plan_date','$report_date' )
            ";

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }


    }

$data = $error ? ['error' => $error_msg ] : [ 'var' => 0, 'count' => $row_count, 'project_plan_date'  => $project_plan_date, 'plan_date'  => $plan_date, 'report_date' => $report_date ];
echo json_encode( $data );
?>