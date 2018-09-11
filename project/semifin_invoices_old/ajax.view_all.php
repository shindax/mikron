<?php
require_once( "functions.php" );

$num = $_POST['num'];
$year = $_POST['year'];

$arr = GetSemifinishedInvoices( $year );
$str = '';

foreach( $arr AS $invoice )
{
  $order_arr = GetSemifinishedStoreData( $year, $invoice );
  $str .= "<tr class='order_row success'>
              <td colspan='9'>
              <div><span>".conv("Накладная № ")."$invoice </span><button class='btn btn-small btn-primary pull-right' type='button' data-id='$invoice'>".conv("Распечатать")."</button></div>
              </td>
              </tr>";
  $str .= GetSemifinishedHTMLData( $order_arr );
}

//echo iconv("Windows-1251", "UTF-8",  $str );
echo $str;

