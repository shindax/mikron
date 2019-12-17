<?php
error_reporting( E_ALL );
// error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.AbstractBinaryTree.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php"		 );

error_reporting( E_ALL );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

SendMail( ['shindax@okbmikron.ru'], "Theme" , "Message" );
echo "Sent";

