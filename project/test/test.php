<?php
error_reporting( E_ERROR );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );
require_once( "getZakInfo.php" );
require_once( "getKrz2Info.php" );

function conv( $str )
{
  global $dbpasswd;
  if( ! strlen( $dbpasswd ) )
    return iconv( "UTF-8", "Windows-1251",  $str );
      else return $str ; 
}

