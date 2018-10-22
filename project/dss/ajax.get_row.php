<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$id = $_POST['id'];
$user_id = $_POST['user_id'];
//$level = $_POST['level'] + DecisionSupportSystemItem :: LEVEL_SHIFT;

function conv( $str )
{
    return $str ; // iconv( "UTF-8", "Windows-1251",  $str );
}

$dss_item = new DecisionSupportSystemItem( $pdo, $user_id, $id, 0 );
$str = conv( $dss_item -> GetTableRow('','Field') );

echo $str;