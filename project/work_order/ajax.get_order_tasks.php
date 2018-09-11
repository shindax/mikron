<?php
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.OrderOperations.php" );

$id = $_POST['id'];

$ord = new OrderOperations( $id, $pdo );
$str = $ord -> getHtmlTree() ;

echo  iconv("Windows-1251", "UTF-8", $str );
//echo $str ;
