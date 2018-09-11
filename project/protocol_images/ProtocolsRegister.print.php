<link rel='stylesheet' href='/project/protocol_images/css/style.css' type='text/css'>

<?php
//ini_set('display_errors', true);
error_reporting( E_ALL );
error_reporting( 0 );

require_once( "functions.php" );
require_once( "class.monthPlanReportDocumentation.php" );

$date = $_GET['p0'];
$doc = new MonthPlanReportDocumentation( $dblocation, $dbname, $dbuser, $dbpasswd );
echo $doc -> getPrintData( $date );
