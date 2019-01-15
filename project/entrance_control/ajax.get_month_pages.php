<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$year = $_POST['year'];
$month = $_POST['month'];
$user_id = $_POST['user_id'];

$pages = GetPagesNumArr( $year, $month );

$str = '';

$line = 1 ;
foreach( $pages AS $page )
{
  $ec = new EntranceControl( $pdo, $page );
  if( $user_id == 130 || $user_id == 224 )
    $ec -> EnableImageDeleting();

 $ec -> HtmlPageNum( $line ++ );
 $str .= $ec -> GetTable();
}

if( strlen( $dbpasswd ) )
	echo $str;
		else
			echo iconv("Windows-1251", "UTF-8", $str );
			
