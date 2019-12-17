<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
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
        width: 16%;
   }
   .col-sm-3 {
        width: 25%;
   }
   .col-sm-2 {
        width: 10%;
   }
   .col-sm-1 {
        width: 8.33333333%;
   }

    .sign
 {
    padding-bottom : 5px !important;
 }

  .sign div span
 {
    font-size:14px !important;
 }

    .col-sm-24 span
 {
    font-size:12px !important;
 }

  .sign span
 {
    margin-top : -30px !important;
 }

table 
{
    margin:20px 0 20px 0 !important;
    width: 95% !important;
    border-collapse: collapse !important;
}

td, th
{
    padding: 3px !important;
    border: 1px solid black !important; 
}


table
{
    display : none ;
}

table.table
{
    display : block ;
}

}

td.AC
{
    vertical-align: middle;
    text-align: center;
}

</style>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}

if ( isset($_GET["p0"]) )
$id = $_GET["p0"];

$report_num = $id;
$today = date("d.m.Y");


    try
    {
        $query ="
                 SELECT res.id, res.state,  detitem.NAME dse_name, detitem.KOMM comment, res.count res_count, detitem.COUNT count, inv.inv_id, detitem.ID detitem_id, tier.ORD tier_name, item.NAME item_name, wh.NAME wh_name, users.IO user_name, res.user_id user_id
                 FROM `okb_db_warehouse_reserve` res
                 LEFT JOIN okb_db_sklades_detitem detitem ON detitem.ID = res.tier_id
                 LEFT JOIN okb_db_semifinished_store_invoices inv ON inv.warehouse_item_id = res.tier_id
           LEFT JOIN okb_db_sklades_yaruses tier ON tier.ID = detitem.ID_sklades_yarus
           LEFT JOIN okb_db_sklades_item item ON item.ID = tier.ID_sklad_item
           LEFT JOIN okb_db_sklades wh ON wh.ID = item.ID_sklad 
           LEFT JOIN okb_users users ON users.ID = res.user_id
            WHERE res.id = $id
           ORDER BY res.id
           " ;

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    if ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
        $semifin_invoice[] = 
            [
        'rec_id' => $row -> id,
                'inv_num' => $row -> inv_id,
                'dse_name' => conv( $row -> dse_name ),
                'comment' => conv( $row -> comment ),
                'count' => $row -> count,
                'res_count' => $row -> res_count,
                'detitem_id' => $row -> detitem_id,
                'state' => $row -> state,
        'tier' => $row -> tier_name,
        'item' => conv( $row -> item_name ),
        'wh' => conv( $row -> wh_name ),
        'user_name' => conv( $row -> user_name ),
        'user_id' => $row -> user_id
            ];      
    }

?>
    <div class="container">

    <div class="row">
    <div class="col-sm-24 text-center"><h5><?= conv("Накладная выдачи полуфабрикатов со склада"); ?></h5></div>
    </div><!-- <div class="row"> -->

    <div class="row">
    <div class="col-sm-3"><span class='inv_num'>&nbsp;</span></div>
    <div class="col-sm-2 border l-border "><span><?= conv("Инициатор"); ?></span></div>
    <div class="col-sm-2 border"><span><?= conv("Отправитель"); ?></span></div>
    <div class="col-sm-2 border"><span><?= conv("Получатель"); ?></span></div>
    </div><!-- <div class="row"> -->

    <div class="clearfix"></div>

    <div class="row">
    <div class="col-sm-3"><span class='inv_num'><?= conv("№ ПФ- $report_num от $today"); ?></span></div>
    <div class="col-sm-2 border l-border b-border"><span><?= conv("ПДО"); ?></span></div>
    <div class="col-sm-2 border b-border"><span><?= conv("Производство"); ?></span></div>
    <div class="col-sm-2 border b-border"><span><?= conv("ПСХ"); ?></span></div>
    </div><!-- <div class="row"> -->

    <div class="clearfix">&nbsp;</div>

    <div class="row">
    <div class="col-sm-24"><span class='bold italic'><?= conv("Инициатор"); ?></span><span><?= conv("Инженер ПДО"); ?> _________</span></div>
    </div><!-- <div class="row"> -->

<?php

$str .= "
<div class='row'>
<table id='semifin_invoices' class='table table-striped'>
<col width='3%'>
<col width='30%'>
<col width='30%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>

  <thead>
    <tr class='table-primary'>
      <th>".conv( "№ накл" )."</th>      
      <th>".conv( "ДСЕ" )."</th>
      <th>".conv( "Комментарий" )."</th>
      <th>".conv( "Склад" )."</th>
      <th>".conv( "Ячейка" )."</th>
      <th>".conv( "Ярус" )."</th>            
      <th>".conv( "Кол." )."</th>
      <th>".conv( "Запр. кол." )."</th>      
    </tr>
  </thead>
  <tbody>";


        foreach( $semifin_invoice AS $val )
        {
          $rec_user_id = $val['user_id'];

            $str .=
                "<tr>
                  <td class='AC'><span class='inv_num'>".$val['inv_num']."</span></td>
                  <td class='AL'><span>".$val['dse_name']."</span></td>
                  <td class='AL'><span>".$val['comment']."</span></td>
                  <td class='AC'><span>".$val['wh']."</span></td>
                  <td class='AC'><span>".$val['item']."</span></td>
                  <td class='AC'><span>".$val['tier']."</span></td>
                  <td class='AC'><span class='count'>".$val['count']."</span></td>
                  <td class='AC'><span class='res_count'>".$val['res_count']."</span></td>                  
                </tr>";
        }

$str .= "</tbody></table></div>";

echo $str;
?>
    <div class="row delimiter">
    <div class="col-sm-24"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span  class='bold italic'>&nbsp;&nbsp;&nbsp;&nbsp;<?= conv("Отпустил"); ?></span></div>
    <div class="col-sm-4 b-border sign"><div><span><?= conv("мастер"); ?></span></div></div>
    <div class="col-sm-4 b-border sign"><span>&nbsp;</span></div>
    <div class="col-sm-2  sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 b-border sign"><span>&nbsp;</span></div>
    <div class="col-sm-2  sign"><span>&nbsp;</span></div>
    <div class="col-sm-4  sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border  sign"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span><?= conv("должность"); ?></span></div>
    <div class="col-sm-4 sign"><span><?= conv("подпись"); ?></span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span><?= conv("дата"); ?></span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

    <div class="row">
    <div class="col-sm-24"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span  class='bold italic'><?= conv("Принял"); ?></span></div>
    <div class="col-sm-4 b-border sign"><div><span><?= conv("контролер"); ?></span></div></div>
    <div class="col-sm-4 b-border sign"><span>&nbsp;</span></div>
    <div class="col-sm-2  sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 b-border sign"><span>&nbsp;</span></div>
    <div class="col-sm-2  sign"><span>&nbsp;</span></div>
    <div class="col-sm-4  sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border  sign"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span><?= conv("должность"); ?></span></div>
    <div class="col-sm-4 sign"><span><?= conv("подпись"); ?></span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span><?= conv("дата"); ?></span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

    <div class="row">
    <div class="col-sm-24"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>


<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span  class='bold italic'><?= conv("Получил"); ?></span></div>
    <div class="col-sm-4 b-border sign"><div><span><?= conv("кладовщик"); ?></span></div></div>
    <div class="col-sm-4 b-border sign"><span>&nbsp;</span></div>
    <div class="col-sm-2  sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 b-border sign"><span>&nbsp;</span></div>
    <div class="col-sm-2  sign"><span>&nbsp;</span></div>
    <div class="col-sm-4  sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border  sign"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span><?= conv("должность"); ?></span></div>
    <div class="col-sm-4 sign"><span><?= conv("подпись"); ?></span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span><?= conv("дата"); ?></span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>
    </div>

<script>
$( function()
{
    alert();
});
</script>