<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<script type="text/javascript" src="/project/semifin_invoices/js/semifin_invoices.js"></script>


<?php
error_reporting( E_ALL );
require_once( "functions.php" );

$order_list = '';
if ( isset($_GET["p0"]) )
  $order_list = $_GET["p0"];

$order_arr = [];

            try
            {
                $query = "SELECT MAX(inv_num) cur_num FROM `okb_db_semifinished_store_invoices` WHERE 1";
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            $row = $stmt->fetch(PDO::FETCH_OBJ );

            $inv_num = 0 ;

            if( is_null ( $row -> cur_num ) )
                $inv_num = 1 ;
                else
                    $inv_num = $row -> cur_num + 1 ;

  if( strlen( $order_list ) )
    {
            try
            {
                $query = "
                        SELECT
                        okb_db_zadan.ID AS id,
                        okb_db_zadan.NUM AS count,
                        okb_db_zakdet.`NAME` AS zakdet_name,
                        okb_db_zak.`NAME` AS zak_name,
                        okb_db_zak_type.description AS zak_type,
                        okb_db_zakdet.OBOZ AS draw_name,
                        okb_db_zak.DSE_NAME AS zak_dse_name,
                        okb_db_zak.DSE_OBOZ AS zak_dse_draw
                        FROM okb_db_zadan
                        LEFT JOIN okb_db_zak ON okb_db_zadan.ID_zak = okb_db_zak.ID
                        LEFT JOIN okb_db_zakdet ON okb_db_zadan.ID_zakdet = okb_db_zakdet.ID
                        LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                        WHERE
                        okb_db_zadan.ID IN ( $order_list )
                        ORDER BY zakdet_name
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            $line = 1 ;
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
                $order_arr [] =
                [
                    'line' => $line ++,
                    'zak_name' => conv( $row -> zak_name ),
                    'zak_type' => conv( $row -> zak_type ),
                    'zak_dse_name' => conv( $row -> zak_dse_name ),
                    'zak_dse_draw' => conv( $row -> zak_dse_draw ),
                    'zakdet_name' => conv( $row -> zakdet_name ),
                    'id' => $row -> id,
                    'draw_name' => conv( $row -> draw_name ),
                    'count' => $row -> count
                ];
            }
      }

$today = date("d.m.Y");
$today_dash = date("Y-m-d");


?>
<div class="container">

<div class="row">
    <div class="col-sm-24"><h4><?= conv("Накладная передачи полуфабрикатов на склад"); ?></h4>
    </div>
</div>

<div class="row">
    <div class="col-sm-24 invoice-number">
        <span><?= conv("№ ПФ- "); ?></span><input id='inv_num' value="<?= $inv_num; ?>" readonly/><span id="today" data-day="<?= $today_dash; ?>"><?= conv(" от "); ?><?= $today; ?></span>
    </div>
</div>

<div class="table-responsive">

<table class="table table-striped table-bordered">

<col width="1%">
<col width="15%">
<col width="10%">
<col width="10%">
<col width="10%">
<col width="5%">
<col width="10%">
<col width="20%">
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
  <th><?= conv("Комментарии"); ?></th>
</tr>
 </thead>

<?php

    foreach( $order_arr AS $order )
    {
        $line = $order['line'];
        $id = $order['id'];

        if( strlen( $order['zakdet_name']) )
            $zakdet_name = $order['zakdet_name'];
                else
                   $zakdet_name = $order['zak_dse_name'];

        $zak_type = $order['zak_type'];
        $zak_name = $order['zak_name'];
        if( strlen ( $order['draw_name'] ) )
            $draw_name = $order['draw_name'] ;
                else
                    $draw_name = $order['zak_dse_draw'] ;
        $count = $order['count'] ;
        $option = GetSemifinishedStoreType( 'option' );

        echo "<tr class='order_row ".( $line %2 ? 'active' : '')."warning' data-id='$id'>
                    <td class='AC'>$line</td>
                    <td><span class='dse_name'>$zakdet_name</span></td>
                    <td class='AC'><span class='order_name'>$zak_type $zak_name</span></td>
                    <td class='AC'><span class='draw_name'>$draw_name</span></td>
                    <td><input  class='part_num' /></td>
                    <td class='AC'><input class='count' value='$count'/></td>
                    <td class='AC'><input class='transfer_place' /></td>
                    <td class='AC'><select class='storage_time'>$option</select>
                    </td>
                    <td><input class='note' /></td>
                    </tr>";
    }


?>
</table>
</div><!-- <div class="table-responsive"> -->
        <div class='row'>
            <div class='col-sm-24'>
                <button class="btn btn-small btn-primary pull-right" type="button" id='save_and_print'><?= conv("Сохранить и распечатать"); ?></button>
            </div><!-- <div class='sm-col-20'> -->
        </div><!-- <div class='row'> -->
</div>


