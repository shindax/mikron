<link rel="stylesheet" href="/project/reports/ord_and_equip_order/css/style.css">
<script type="text/javascript" src="/project/reports/ord_and_equip_order/js/ord_and_equip_order.js"></script>

<?php
error_reporting( E_ALL );

require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
include("class.order.php");

global $mysqli ;

$order = new Orders( $mysqli );
$options = $order -> GetOrdersOptions();

$select_all = "<div class='sel_div'><select size='20' id='all_orders'>$options</select></div>";
$select_sel = "<div class='sel_div'><select size='20' id='sel_orders'></select></div>";
$buttons    = "<div class='but_div'>
                     <div class='inner_but_div'>
                        <button id='all_button'> >> </button>
                        <button id='sel_button'> << </button>
                        <button id='query_button' disabled>".(str_out('Выбрать'))."</button>
                        <button id='clear_button' disabled>".(str_out('Очистить'))."</button>
                        </div>
               </div>";

$delimiter  = "<div class='but_div'><div class='inner_but_div'>&nbsp</div></div>";


$table = "<div class='tbl_div'><table class='rdtbl tbl' id='eq_tbl'>
           <tr class='first'>
           <td width='4%'>".(str_out('№'))."</td>           
           <td>".(str_out('Оборудование'))."</td>
           <td width='15%'>".(str_out('Н/Ч'))."</td>
           </tr>
           </table></div>";

$table2 = "<div class='tbl_div'><table class='rdtbl tbl' id='dist_tbl'>
           <tr class='first'>
           <td>".(str_out('Заказ'))."</td>
           <td width='15%'>".(str_out('Н/Ч'))."</td>
           <td width='15%'>".(str_out('%'))."</td>                      
           </tr>
           </table></div>";


echo str_out( '<h2>Оборудование в заказах</h2>');

echo $select_all;
echo $buttons;
echo $select_sel;
echo $table;
echo $delimiter;
echo $table2;