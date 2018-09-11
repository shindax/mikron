<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.SemifinInvoice.php" );
require_once( "functions.php" );

$year = $_POST['year'];

$result = GetSemifinishedStoreUsedDate( $year );
$year = $result['year'];
$year_option = $result['option'];

 $str = "<div class='row'>
    <div class='col-sm-24'><h3>".conv('Накладная передачи полуфабрикатов на склад')."</h3></div>
    <hr>
</div>";

$str .= "<div class='row'>
  <div class='form-group col-sm-3'>
  <span class='form-span'>".conv('Год : ')."</span>
  <select id='year-select'>$year_option</select>
  </div>";

$inv_numbers = GetSemifinishedStoreInvoicesNumber( $year ) ;
$inv_num_option = conv(  GetSemifinishedStoreInvoicesNumberOptions( $inv_numbers ) );

$str .= "<div class='form-group col-sm-9'>
  <span class='form-span'>".conv('Номер накладной : ')."</span>
  <select id='inv-num-select'>$inv_num_option</select>
  </div>

</div>";

foreach( $inv_numbers AS $number )
{
	$inv = new SemifinInvoice( $pdo, $number );
	$str .= $inv -> GetTable();
}

//echo iconv("Windows-1251", "UTF-8",  $str );
echo $str ;
