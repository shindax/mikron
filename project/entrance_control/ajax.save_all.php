<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$id = $_POST['id'];

$ec = new EntranceControl( $pdo, $id );

$str = $ec -> GetTableBegin();
$str .= $ec -> GetTableContent();
$str .= $ec -> GetTableEnd();

//echo iconv("Windows-1251", "UTF-8", $str );
echo $str ;

?>