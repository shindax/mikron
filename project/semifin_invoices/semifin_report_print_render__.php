<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">

<link rel="stylesheet" href="/project/semifin_invoices/css/print.css" media="print">
<style>
@media print 
{
   .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
        float: left;
   }
   .col-sm-12 {
        width: 100%;
   }
   .col-sm-11 {
        width: 91.66666667%;
   }
   .col-sm-10 {
        width: 83.33333333%;
   }
   .col-sm-9 {
        width: 75%;
   }
   .col-sm-8 {
        width: 66.66666667%;
   }
   .col-sm-7 {
        width: 58.33333333%;
   }
   .col-sm-6 {
        width: 50%;
   }
   .col-sm-5 {
        width: 41.66666667%;
   }
   .col-sm-4 {
        width: 33.33333333%;
   }
   .col-sm-3 {
        width: 25%;
   }
   .col-sm-2 {
        width: 16.66666667%;
   }
   .col-sm-1 {
        width: 8.33333333%;
   }
}

</style>

<?php
error_reporting( E_ALL );

function conv( $str )
{
    $result = iconv("UTF-8", "Windows-1251", $str );
        return $result;
}

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

if ( isset($_GET["p0"]) )
$id = $_GET["p0"];

$report_num = $id;
$today = date("d.m.Y");

$order_arr = [];
$str = "<div class='container'>
            <div class='row'>
                <div class='col-sm-24 text-center'><h5>".conv("Накладная передачи полуфабрикатов на склад")."</h5></div>
            </div>";

$str .= "<div class='row' style='background:cyan; margin:0; padding:0;'>
             <div class='col-sm-4 
' style='background:yellow'>".conv("№ ПФ-280 от 01.01.2017г.")."</div>
             <div  class='col-sm-2' style='background:green'>".conv("№ ПФ-281 от 01.01.2017г.")."</div>
         </div>";

$str .= "</div>";
echo $str ;
?>
