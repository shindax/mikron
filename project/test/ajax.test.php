<?php
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

$val = $_POST['val'];

echo $val ;
