<?php
error_reporting( E_ALL );
// error_reporting( 0 );

require_once( "/heavycut_scoreboard/functions.php" );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php" );

// require_once( "functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/project/coordination_page/SendNotification.php" );

// SendNotification( [ 96 ], ['shindax@okbmikron.ru'], 13, 1 , 'male_message', 'female_message', COORDINATION_PAGE_DATA_MODIFIED );

// SendMail( $email_arr, strip_tags( "$user_name $message" ), strip_tags( "$user_name $message" ) );

// function conv( $str )
// {
//     return iconv( "UTF-8", "Windows-1251",  $str );
// }

$result = GetStatistics( "2019-04-14", 2 );
debug( $result );

echo "Ontime : {$result['ontime']}<br>";
echo "Offtime : {$result['offtime']}<br>";

