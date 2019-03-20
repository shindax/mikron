<?php
//error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/project/service_note/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.ServiceNoteTable.php" );

global $pdo, $dbpasswd;

// function conv( $str )
// {
//    global $dbpasswd;
    
//     if( !strlen( $dbpasswd ) )
//         return iconv( "UTF-8", "Windows-1251",  $str );
//         else
//           return $str;
// }

$year = $_POST['year'];
$month = $_POST['month'];
$res_id = $_POST['res_id'];
$can_edit = $_POST['can_edit'];
$pages = GetPagesArr( $year, $month );

$str = "";
$classes = [ 'odd', 'even' ];

$line = 1 ;
foreach( $pages AS $key => $val )
{
  $note = new ServiceNoteTable( $pdo, $val , $can_edit );
  $str .= "<div class='row'><div class='col-sm-12'>";

  if( $line == 1 )
    $str .= ServiceNoteTable :: GetTableHead();
  $str .= $note -> GetTableContent( $classes[ $key % 2 ] );
  $str .= "</div></div>";
  $line ++ ;
}

$str .= ServiceNoteTable :: GetTableEnd();


if( !strlen( $dbpasswd ) )
    $str = iconv( "Windows-1251", "UTF-8", $str );

echo $str;

