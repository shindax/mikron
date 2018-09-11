<?php
require_once( "functions.php" );

$num = $_POST['num'];
$year = $_POST['year'];

if( $num == 0 )
        $num = GetSemifinishedStoreStartInvoicesNumber( $year );

$order_arr = GetSemifinishedStoreData( $year, $num );
//echo iconv("Windows-1251", "UTF-8",  GetSemifinishedHTMLData( $order_arr ) );
echo GetSemifinishedHTMLData( $order_arr );

