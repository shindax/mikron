<?php
require_once( "functions.php" );

$year = $_POST['year'];
$option = GetSemifinishedStoreInvoicesNumber( $year );

echo $option ;

