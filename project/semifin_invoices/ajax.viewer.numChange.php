<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.SemifinInvoice.php" );
require_once( "functions.php" );

$year = $_POST['year'];
$num = $_POST['num'];
$str = "";

if( $num )
{
      $inv = new SemifinInvoice( $pdo, $num );
      $str .= $inv -> GetTable();
}
else
{
    $result = GetSemifinishedStoreUsedDate( $year );
    $year = $result['year'];
    $year_option = $result['option'];

    $inv_numbers = GetSemifinishedStoreInvoicesNumber( $year ) ;
    $inv_num_option = conv(  GetSemifinishedStoreInvoicesNumberOptions( $inv_numbers ) );

    foreach( $inv_numbers AS $number )
    {
    	$inv = new SemifinInvoice( $pdo, $number );
    	$str .= $inv -> GetTable();
    }
}

//echo iconv("Windows-1251", "UTF-8",  $str );
echo $str;
