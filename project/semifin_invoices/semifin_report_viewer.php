<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<script type="text/javascript" src="/project/semifin_invoices/js/semifin_invoices.js"></script>

<?php

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.SemifinInvoice.php" );
require_once( "functions.php" );

global $pdo ;
$cur_year = date("Y") ;

$str = "<div class='container'>";
$str .= "<div class='row'>
    <div class='col-sm-24'><h3>".conv('Накладная передачи полуфабрикатов на склад')."</h3></div>
    <hr>
</div>";

$result = GetSemifinishedStoreUsedDate( $cur_year );
$year = $result['year'];
$year_option = $result['option'];

$str .= "<div class='row'>
  <div class='form-group col-sm-3'>
  <span class='form-span'>".conv('Год : ')."</span>
  <select id='year-select'>$year_option</select>
  </div>";

$inv_numbers = array_reverse( GetSemifinishedStoreInvoicesNumber( $year ) );
$inv_num_option = conv(  GetSemifinishedStoreInvoicesNumberOptions( $inv_numbers ) );

$str .= "<div class='form-group col-sm-6'>
  <span class='form-span'>".conv('Номер накладной : ')."</span>
  <select id='inv-num-select'>
  <option value='0'>$inv_num_option</option>
  </select>
  </div>";

$str .= "<div class='form-group col-sm-15'>
    <button class='btn btn-big btn-primary pull-right' id='create'>".conv("Создать")."</button>
  </div>";

$str .= "</div>";

foreach( $inv_numbers AS $number )
{
	$inv = new SemifinInvoice( $pdo, $number );
	$str .= $inv -> GetTable();
}

$str .= "</div>";
echo $str ;

