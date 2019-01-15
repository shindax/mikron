<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

function conv( $str )
{
//    return iconv( "UTF-8", "Windows-1251",  $str );
    return $str ;
}

echo "Completed";

phpinfo();

//system('explorer', $retval);

