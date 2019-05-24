<?php
error_reporting( E_ALL );
date_default_timezone_set("Asia/Krasnoyarsk");
// error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php" );

// require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/project/coordination_page/SendNotification.php" );


// $href = "index.php?do=show&formid=30&id=123";
// $a_text = "Имя КРЗ2 ( имя узла )";
// $a_from = "test-php";

// SendNotification( [ 96 ], ['shindax@okbmikron.ru'], 13, 1 , 'внес изменения в лист согласования', 'внесла изменения в лист согласования', $href, $a_text, $a_from, COORDINATION_PAGE_DATA_MODIFIED );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$arr = array_fill( 1, 31, 0.1 );
echo json_encode( $arr );