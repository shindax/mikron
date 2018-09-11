<link rel="stylesheet" href="/project/work_order/css/style.css">
<script src="/project/work_order/js/work_order.js"></script>
<script src="/project/work_order/js/visibility_check.js"></script>

<?php
error_reporting( E_ALL );
//error_reporting( 0 );

// Используются классы :
// require_once( "classes/class.OrderOperations.php" );
// require_once( "classes/class.DSEOperations.php" );

require_once( "classes/db.php" );
require_once( "functions.php" );
require_once( "get_zak_table.php" );

echo getHead();

$str = "<table id='order_table'>
            <col width='2%'></col>
            <col width='40%'></col>
            <col width='20%'></col>
            <col width='4%'></col>
            <col width='4%'></col>
            <col width='4%'></col>
            <col width='20%'></col>
            <col width='2%'></col>

";

$arr = getZakArray( $pdo );

    foreach( $arr AS $val )
    {
        $str .= "<tr class='zak_row' id='".$val['zak_id']."' >
                          <td colspan='8'>
                          <img data-state='0' class='coll_image' src='uses/collapse.png' />
                          <span>".$val['zak_type']."</span>
                          <span>".$val['zak_name']."</span>
                          <span>".$val['dse_name']."</span>
                          </td>
                    </tr>";
    }

$str .= "</table>";

$str .=   "<div id='dialog-confirm' title='".conv("Запрос в КТО")."'>
           <p><span class='ui-icon ui-icon-alert' style='float:left; margin:12px 12px 20px 0;'></span>".conv("Послать запрос в КТО?")."</p>
           </div>";

$str .=   "<div id='input-data-dialog' title='".conv("Введите необходимые данные")."'>
           <span></span>".conv("Введите дату и смену")."</div>";

$str .=   "<div id='working_orders_pane' title='".conv("Сменные задания")."'>
              </div>";

echo $str ;

echo "<iframe id='shift_order' data-closed='0' src=''></iframe>";

echo conv( getFooter() );

