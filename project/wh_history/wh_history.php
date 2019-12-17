<link rel="stylesheet" href="/project/wh_history/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/wh_history/css/style.css" media="screen">

<script type="text/javascript" src="/project/wh_history/js/constants.js"></script>
<script type="text/javascript" src="/project/wh_history/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/project/wh_history/js/wh_history.js"></script>

<?php
error_reporting( E_ALL );
error_reporting( 0 );
require_once( "functions.php" );

$op_select = get_warehouse_action_select();

$wh_struct =  get_warehouse_structure();
$wh_options = "";

foreach ( $wh_struct['wh'] AS $key => $value ) 
{
  $wh_options .= "<option value='$key'>$value</option>";
}

?>
<div class="container">

<div class="row">
    <div class="col-sm-24"><h4><?= conv("История операций по складам"); ?></h4></div>
    <hr>
</div>

<div class="row">
  <div class="form-group col-sm-4">
  <span class='form-span'><?= conv("Склад : "); ?></span>
  <select id='wh_select'>
      <option value='0'><?= conv("Все склады"); ?></option>
      <?= $wh_options; ?>
    </select>
  </div>

  <div class="form-group col-sm-3">
  <span class='form-span'><?= conv("C даты : "); ?></span>
  <input id='from_date' class='datepicker' />
  </div>

  <div class="form-group col-sm-3">
  <span class='form-span'><?= conv("По дату : "); ?></span>
  <input id='to_date'  class='datepicker' />  
  </div>

  <div class="form-group col-sm-6">
  <span class='form-span'><?= conv("Операция : "); ?></span>
  <select id='op_select'>
    <?= $op_select; ?>
  </select>
  </div>

  <div class="form-group col-sm-5">
  <span class='form-span'><?= conv("Поиск : "); ?></span>
  <input id='find' />  
  </div>

  <div class="form-group col-sm-2">
    <button id='clear_filter' type="button" class="btn btn-sm btn-primary"><?= conv("Сбросить фильтр"); ?></button>
  </div>

</div>
<div class="table-responsive" id='table_div'>
</div><!-- <div class="table-responsive"> -->
</div>
