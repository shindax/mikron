<?php
require_once( "functions.php" );
require_once( "class.monthPlanReportDocumentation.php" );

error_reporting( 0 );

$date = $_POST['date'];
$doc = new MonthPlanReportDocumentation( $dblocation, $dbname, $dbuser, $dbpasswd, 1 );
//echo $date_div;
echo $doc -> GetData( $date );
