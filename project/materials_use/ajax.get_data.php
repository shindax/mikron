<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );
error_reporting( 0 );

$mat = $_POST['mat'];
$sort = $_POST['sort'];

$data = get_data( $mat, $sort );
$str = get_table( $data );

// $str = "$mat : $sort";

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
