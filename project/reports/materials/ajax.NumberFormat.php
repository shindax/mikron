<?php
header('Content-Type: text/html');
error_reporting( 0 );

$number = $_POST[ 'number' ];
echo number_format( $number, 2, ',', ' ' );
