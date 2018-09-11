<?php
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DSEOperations.php" );

$zakdet_id = $_POST['zakdet_id'];

$str = '';

foreach( $zakdet_id AS $id )
  {
    $zakdet = new DSEOperations( $id, $pdo );
    $zakdet -> getData();
    $str .= $zakdet -> getHtmlTableRow();
  }

//echo $str ;
echo iconv("Windows-1251", "UTF-8", $str );
