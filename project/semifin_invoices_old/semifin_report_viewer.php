<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<script type="text/javascript" src="/project/semifin_invoices/js/semifin_invoices.js"></script>


<?php
error_reporting( E_ALL );
error_reporting( 0 );
require_once( "functions.php" );

$cur_year = date("Y") ;

//RestoreData() ;

?>
<div class="container">

<div class="row">
    <div class="col-sm-24"><h4><?= conv("Накладная передачи полуфабрикатов на склад"); ?></h4></div>
    <hr>
</div>

<div class="row">
  <div class="form-group col-sm-3">
  <span class='form-span'><?= conv("Год : "); ?></span>
  <select id="year-select">
  <?= GetSemifinishedStoreUsedDate( $cur_year ) ?>
  </select>
  </div>

  <div class="form-group col-sm-9">
  <span class='form-span'><?= conv("Номер накладной : "); ?></span>
  <select id="inv-num-select">
  </select>
  </div>

</div>

<div class="table-responsive">
<table id="invoices_table" class="table table-striped table-bordered">

<col width="1%">
<col width="15%">
<col width="10%">
<col width="20%">
<col width="10%">
<col width="5%">
<col width="10%">
<col width="10%">
<col width="10%">

 <thead>
  <tr class="info">
  <th class="AC"><?= conv("№"); ?></th>
  <th class="AC"><?= conv("Материальные ценности"); ?><br><?= conv("Наименование"); ?></th>

  <th class="AC"><?= conv("№ Заказа"); ?></th>
  <th class="AC"><?= conv("№ Чертежа"); ?></th>
  <th class="AC"><?= conv("№ партии"); ?></th>
  <th class="AC"><?= conv("Количество"); ?></th>
  <th class="AC"><?= conv("Место передачи"); ?></th>
  <th class="AC"><?= conv("Срок хранения"); ?></th>
  <th class="AC"><?= conv("Комментарии"); ?></th>
</tr>
 </thead>
<?php

$start_inv_num = GetSemifinishedStoreStartInvoicesNumber( $cur_year );

if( $start_inv_num )
{
  $order_arr = GetSemifinishedStoreData( $cur_year, $start_inv_num );
  echo GetSemifinishedHTMLData( $order_arr );
}

//debug( $order_arr );

?>

</table>
</div><!-- <div class="table-responsive"> -->
        <div class='row'>
            <div class='col-sm-24'>
                <button class="btn btn-small btn-primary pull-right hidden" type="button" id="print" data-id="<?= $start_inv_num ?>"><?= conv("Распечатать"); ?></button>
            </div><!-- <div class='sm-col-20'> -->
        </div><!-- <div class='row'> -->
</div>
