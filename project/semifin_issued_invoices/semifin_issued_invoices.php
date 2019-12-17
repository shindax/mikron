<link rel="stylesheet" href="/project/semifin_issued_invoices/css/bootstrap.min.css" media="screen">

<link rel="stylesheet" href="/project/semifin_issued_invoices/css/style.css" media="screen">

<script type="text/javascript" src="/project/semifin_issued_invoices/js/semifin_issued_invoices.js"></script>


<?php
error_reporting( E_ALL );
error_reporting( 0 );
require_once( "functions.php" );

$cur_year = date("Y") ;
$cur_month = date("n") ;
$batch = isset( $_GET['p0'] ) ? $_GET['p0'] : 0 ;

if( $batch )
{
  $date = GetSelectedInvoiceDate( $batch );
  $cur_year = $date["year"];
  $cur_month = $date["month"];
}

echo "<script>var year = $cur_year; var month = $cur_month; var selected_in = $batch;</script>";

?>

<div class="container">

<div class="row">
    <div class="col-sm-24 hidden"><h4><?= conv("Накладные на выдачу полуфабрикатов со склада"); ?></h4></div>
    <hr>
</div>

<div class="row">
  <div class="form-group col-sm-3 hidden">
  <span class='form-span'><?= conv("Год : "); ?></span>
  <select id="year-select">
    <?= GetYearOptions( $cur_year ); ?>
  </select>
  </div>

  <div class="form-group col-sm-4 hidden">
  <span class='form-span'><?= conv("Месяц : "); ?></span>
  <select id="month-select">
    <?= GetMonthOptions( $cur_month ) ?>
  </select>
  </div>

  <div class="form-group col-sm-5 hidden">
  <span class='form-span'><?= conv("№ Накладной : "); ?></span>
  <select id="inv-num-select">
  </select>
  </div>

</div>


<div class="table-responsive">
</div><!-- <div class="table-responsive"> -->
</div>

