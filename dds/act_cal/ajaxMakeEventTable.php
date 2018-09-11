<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");
require_once("CommonFunctions.php");

$month = $_POST['month'];
$year = $_POST['year'];

$str = MakeEventsTable( $month, $year, 1 );

header('Content-Encoding: gzip');
echo gzencode( $str );

?>