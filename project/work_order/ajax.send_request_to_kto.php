<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

//$id = $_POST['id'];
//$msg = $_POST['msg'];

$str = "Zzz";

echo  iconv("Windows-1251", "UTF-8", $str );
//echo $str ;
