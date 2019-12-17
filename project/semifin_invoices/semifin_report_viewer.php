<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<script type="text/javascript" src="/project/semifin_invoices/js/semifin_invoices.js"></script>

<script>$('body').css('cursor','wait')</script>

<?php
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.SemifinInvoice.php" );
require_once( "functions.php" );

global $pdo ;
global $user;
$user_id = $user['ID'];
$user_info = GetUserInfo( $user_id );
$res_id = $user_info['res_id'];

$inv_id = isset( $_GET['p0'] ) ? $_GET['p0'] : 0 ;

if( $user_id == 130 || $user_id == 1 ) // Соловова
  $disabled = false ;
    else
      $disabled = true ;

echo "<script>var user_id=$user_id, res_id = $res_id, can_accept = 1 </script>";

$cur_year = date("Y") ;

$str = "<div class='container'>";
$str .= "<div class='row'>
    <div class='col-sm-24'><h3>".conv('Накладная передачи полуфабрикатов на склад')."</h3></div>
    <hr>
</div>";
// $str .= "<div class='row'>
//     <div class='col-sm-24'><h6>semifin_invoices/semifin_report_viewer.php</h6></div>
//     <hr>
// </div>";


$result = GetSemifinishedStoreUsedDate( $cur_year );
$year = $result['year'];
$year_option = $result['option'];

$str .= "<div class='row'>
  <div class='form-group col-sm-3'>
  <span class='form-span'>".conv('Год : ')."</span>
  <select id='year-select' disabled>$year_option</select>
  </div>";

$inv_numbers = array_reverse( GetSemifinishedStoreInvoicesNumber( $year ) );

//debug
$inv_numbers = array_slice( $inv_numbers, 0 , 25 );

$inv_num_option = conv(  GetSemifinishedStoreInvoicesNumberOptions( $inv_numbers, $inv_id ) );

$str .= "<div class='form-group col-sm-6'>
  <span class='form-span'>".conv('Номер накладной : ')."</span>
  <select id='inv-num-select' disabled>
    $inv_num_option
  </select>
  </div>";

$str .= "<div class='form-group col-sm-15'>
    <button class='btn btn-big btn-primary pull-right' id='create'>".conv("Создать")."</button>
  </div>";

$str .= "</div>";

foreach( $inv_numbers AS $number )
{
  if( $number )
  {
	 $inv = new SemifinInvoice( $pdo, $number, $disabled );
	 $str .= $inv -> GetTable();
  }
}

$str .= "</div>";

$str .= "<div class='hidden' id='storage_place_dialog' title='".conv("Место хранения")."'>
          <div>".
            GetStoragePlaceDialogTableBegin().
            GetStoragePlaceDialogTableRow().
            GetStoragePlaceDialogTableEnd().
          "</div>
    </div>";

$str .= "<div class='hidden' id='accept_by_QCD_dialog' title='".conv("Принять ДСЕ")."'>
          <div><input id='accept_by_QCD_input' type='number' /></div>
    </div>";

echo $str ;

