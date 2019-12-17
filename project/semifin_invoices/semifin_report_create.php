<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<script type="text/javascript" src="/project/semifin_invoices/js/semifin_invoices.js"></script>


<?php
error_reporting( E_ALL );
require_once( "functions.php" );
global $user;

echo "<script>var user_id=".$user['ID']."</script>";

$order_list = '';
$where = '';

$order_arr = [];

if ( isset($_GET["p0"]) )
{
  $order_list = ListUnique( $_GET["p0"] );

            try
            {
                $query = "
                        SELECT
                        okb_db_zadan.ID AS id,
                        okb_db_zadan.NUM AS count,
                        okb_db_zakdet.`NAME` AS zakdet_name,
                        okb_db_zakdet.`ID` AS zakdet_id,
                        okb_db_zak.`NAME` AS zak_name,
                        okb_db_zak_type.description AS zak_type,
                        okb_db_zakdet.OBOZ AS draw_name,
                        okb_db_zak.DSE_NAME AS zak_dse_name,
                        okb_db_zak.DSE_OBOZ AS zak_dse_draw,
                        
                        okb_db_oper.ID AS oper_id,

                        okb_db_zakdet.RCOUNT AS count_by_order

                        FROM okb_db_zadan
                        LEFT JOIN okb_db_zak ON okb_db_zadan.ID_zak = okb_db_zak.ID
                        LEFT JOIN okb_db_zakdet ON okb_db_zadan.ID_zakdet = okb_db_zakdet.ID
                        LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                        LEFT JOIN okb_db_operitems ON okb_db_operitems.ID = okb_db_zadan.ID_operitems
                        LEFT JOIN okb_db_oper ON okb_db_oper.ID = okb_db_operitems.ID_oper

                        WHERE 
                        okb_db_zadan.ID IN ( $order_list )
                        ORDER BY zakdet_name" ;

                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
}

if ( isset($_GET["p1"]) )
{
  $order_list = ListUnique( $_GET["p1"] );

            try
            {
                $query = "
                        SELECT
                        okb_db_zakdet.COUNT AS count,
                        okb_db_zakdet.`NAME` AS zakdet_name,
                        okb_db_zakdet.`ID` AS zakdet_id,
                        okb_db_zak.`NAME` AS zak_name,
                        okb_db_zak_type.description AS zak_type,
                        okb_db_zakdet.OBOZ AS draw_name,
                        okb_db_zak.DSE_NAME AS zak_dse_name,
                        okb_db_zak.DSE_OBOZ AS zak_dse_draw,
                        okb_db_zakdet.RCOUNT AS count_by_order

                        FROM okb_db_zakdet 
                        LEFT JOIN okb_db_zak ON okb_db_zakdet.ID_zak = okb_db_zak.ID
                        LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id

                        WHERE 
                        okb_db_zakdet.ID IN ( $order_list )
                        ORDER BY zakdet_name" ;

                // echo $query."<br>" ;

                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
            }
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
                    'zakdet_id' => $row -> zakdet_id,
                    'id' => isset( $row -> id ) ? $row -> id : 0 ,
                    'draw_name' => conv( $row -> draw_name ),
                    'count' => $row -> count,
                    'oper_id' => isset( $row -> oper_id ) ? $row -> oper_id : 0,
                    'count_by_order' => $row -> count_by_order
                ];
            }

foreach ( $order_arr AS $key => $value ) 
{
  $zadan_id = $value['id'];
  $zakdet_id = $value['zakdet_id'];
  $query = "";

  if ( isset($_GET["p0"]) )
      $query = "
                SELECT 
                  SUM(NUM_FACT) AS num_fact
                  FROM okb_db_zadan where ID_operitems IN 
                    ( 
                      SELECT ID_operitems FROM `okb_db_zadan` WHERE `ID` = $zadan_id
                    )
                " ;

  if ( isset($_GET["p1"]) )
      $query = "
                SELECT 
                  SUM(NUM_FACT) AS num_fact
                  FROM okb_db_zadan where ID_operitems IN 
                    ( 
                      SELECT ID_operitems FROM `okb_db_zadan` WHERE `ID_zakdet` = $zakdet_id
                    )
                " ;

        try
            {
              // if( $zakdet_id == 26486 )
              //     echo $query ;

                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            $row = $stmt->fetch(PDO::FETCH_OBJ );
            $order_arr[ $key ]['num_fact'] = $row -> num_fact;
}

// _debug( $order_arr );

$masters_option = GetMastersOptions();

$today = date("d.m.Y");
$today_dash = date("Y-m-d");

$str = "<div class='container'>

<div class='row'>
    <div class='col-sm-24'><h3>".conv('Накладная передачи полуфабрикатов на склад')."</h3>
    </div>
</div>

<div class='row'>
    <div class='col-sm-24 invoice-number'>
        <span><?= conv('№ ПФ- '); ?></span><input id='inv_num' value='0' readonly/><span id='today' data-day='$today_dash'>".conv(' от ')."$today</span>
    </div>
</div>
<br>
<div class='table-responsive'>
<table id='semifin_inv_create' class='table table-striped table-bordered'>

<col width='2%'>
<col width='15%'>
<col width='15%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='5%'>
<col width='10%'>
<col width='10%'>
<col width='20%'>
<col width='10%'>

 <thead>
  <tr class='info'>
  <th class='AC'>".conv('№')."</th>
  <th class='AC'>".conv('Материальные ценности')."<br>".conv('Наименование')."</th>
  <th class='AC'>".conv('№ Заказа')."</th>
  <th class='AC'>".conv('№ Чертежа')."</th>
  <th class='AC'>".conv('Операция')."</th>
  <th class='AC'>".conv('№ партии')."</th>
  <th class='AC'>".conv('Количество<br>на заказ/ост')."</th>  
  <th class='AC'>".conv('Количество')."</th>
  <th class='AC'>".conv('Мастер')."</th>
  <th class='AC'>".conv('Место передачи')."</th>
  <th class='AC'>".conv('Срок хранения')."</th>
  <th class='AC'>".conv('Комментарии')."</th>
</tr>
 </thead>";

 if( strlen( $order_list ) )
 {
    foreach( $order_arr AS $order )
    {
        $line = $order['line'];
        $id = $order['id'];
        $zakdet_id = $order['zakdet_id'];        

        $zakdet_name = $order['zakdet_name'];
        $zak_type = $order['zak_type'];
        $zak_name = $order['zak_name'];
        $oper_id = $order['oper_id'];        

        $count_by_order = $order['count_by_order'];
        $num_fact = $order['num_fact'];
        $remainder = $count_by_order - $num_fact;

        if( strlen ( $order['draw_name'] ) )
            $draw_name = $order['draw_name'] ;
                else
                    $draw_name = $order['zak_dse_draw'] ;
        $count = $order['count'] ;
        $option = GetSemifinishedStoreType( 'option' );

        $str .= "<tr class='order_row ".( $line %2 ? 'active' : '')."warning' data-id='$id' data-zakdet-id='$zakdet_id'>
                    <td class='AC'>$line</td>
                    <td class='AL'><span class='dse_name'>$zakdet_name</span></td>
                    <td class='AC'><span class='order_name'>$zak_type $zak_name</span></td>
                    <td class='AC'><span class='draw_name'>$draw_name</span></td>
                    <td><select class='operation_select'>".GetOperationOptions( $oper_id )."</select></td>
                    <td class='AC'><input class='part_num' /></td>
                    <td class='AC'>$count_by_order/$remainder</td>
                    <td class='AC'><input class='count' type='number' value='$count'/></td>
                    <td class='AC'><select class='master_select'>$masters_option</select></td>
                    <td class='AC'><input class='transfer_place' /></td>
                    <td class='AC'><select class='storage_time'>$option</select>
                    </td>
                    <td><input class='note' /></td>
                    </tr>";
    } // foreach( $order_arr AS $order )
  
    $str .= "</table>
          </div>";
 }//if( strlen( $order_list ) )
 else
 {
    $str .= "</table>
          </div>";

$str .= "<div class='row'>
              <div class='col-sm-24'>
                <button class='btn btn-small btn-success pull-right' type='button' id='add_dse'>".conv("Добавить ДСЕ")."</button>
              </div>
             </div>";
 }

$str .= "<div class='row'>
              <div class='col-sm-24'>
                <button class='btn btn-small btn-primary pull-right' type='button' id='save' disabled>".conv("Сохранить и закрыть")."</button>
              </div>
             </div>
           </div>";

echo $str ;

?>



