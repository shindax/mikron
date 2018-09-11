<?php
error_reporting( E_ALL );
require_once( "functions.php" );

$order_list = '';
if ( isset($_GET["p0"]) )
$order_list = $_GET["p0"];
$order_list = explode(',', $order_list );

$part_num = '';
if ( isset($_GET["p1"]) )
$part_num = $_GET["p1"];
$part_num = explode(',', $part_num );

$count = '';
if ( isset($_GET["p2"]) )
$count = $_GET["p2"];
$count = explode(',', $count );

$transfer_place = '';
if ( isset($_GET["p3"]) )
$transfer_place = $_GET["p3"];
$transfer_place = explode(',', $transfer_place );

$storage_time = '';

if ( isset($_GET["p4"]) )
$storage_time = $_GET["p4"];
$storage_time = explode(',', $storage_time );

if ( isset($_GET["p5"]) )
    $today = $_GET["p5"];
        else
            $today = date("d.m.Y");

$report_num = '';
if ( isset($_GET["p6"]) )
  $report_num = $_GET["p6"];


$note = '';
if ( isset($_GET["p7"]) )
    {
          $note = $_GET["p7"];
         $note = explode(',', $note );
    }


$order_arr = [] ;

foreach( $order_list AS $key => $val )
{
            try
            {
                $query = "
                        SELECT
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
                        okb_db_zadan.ID = $val
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
                // echo $query;
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." $query");
            }

             $stime = GetSemifinishedStoreType( $storage_time[$key] );

            $row = $stmt->fetch(PDO::FETCH_OBJ ) ;
            $zakdet_name = conv( $row -> zakdet_name );
            if( strlen( $zakdet_name ) == 0 )
                $zakdet_name = conv( $row -> zak_dse_name );
                    if( strlen( $zakdet_name ) == 0 )
                        $zakdet_name = "��� ������";

            $draw_name = conv( $row -> draw_name );
            if( strlen( $draw_name ) == 0 )
                $draw_name = conv( $row -> zak_dse_draw );
                    if( strlen( $draw_name ) == 0 )
                        $draw_name = "��� ������";


                $order_arr [] =
                [
                    'id' => $val,
                    'count' => $count[$key],
                    'part_num' => $part_num[$key],
                    'transfer_place' => $transfer_place[$key],
                    'storage_time' => $stime,

                    'zak_name' => conv( $row -> zak_name ),
                    'zak_type' => conv( $row -> zak_type ),
                    'zakdet_name' => $zakdet_name,
                    'draw_name' => $draw_name,
                    'note' => $note[$key]
                ];
}

//debug( $order_arr );
?>

<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/print.css" media="print">

    <div class="container">

    <div class="row">
    <div class="col-sm-24 text-center"><h5>��������� �������� �������������� �� �����</h5></div>
    </div><!-- <div class="row"> -->

    <div class="row">
    <div class="col-sm-4"><span>&nbsp;</span></div>
    <div class="col-sm-2 border l-border "><span>���������</span></div>
    <div class="col-sm-2 border"><span>�����������</span></div>
    <div class="col-sm-2 border"><span>����������</span></div>
    </div><!-- <div class="row"> -->

    <div class="clearfix"></div>

    <div class="row">
    <div class="col-sm-4"><span class='inv_num'>� ��-<?= $report_num ?> ��  <?= $today; ?></span></div>
    <div class="col-sm-2 border l-border b-border"><span>���</span></div>
    <div class="col-sm-2 border b-border"><span>������������</span></div>
    <div class="col-sm-2 border b-border"><span>���</span></div>
    </div><!-- <div class="row"> -->

    <div class="clearfix">&nbsp;</div>

    <div class="row">
    <div class="col-sm-24"><span class='bold italic'>���������</span><span>������� ��� _________</span></div>
    </div><!-- <div class="row"> -->

    <div class="row">
    <div class="col-sm-24"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->

    <!-- table head -->
    <div class="row table_head">
    <div class="col-sm-1 border l-border"><span>� �/�</span></div>
    <div class="col-sm-4 border"><div><span>������������ ��������</span></div><div><span>������������</span></div></div>
    <div class="col-sm-2 border"><span>� ������</span></div>
    <div class="col-sm-4 border"><span>� �������</span></div>
    <div class="col-sm-3 border"><span>� ������</span></div>
    <div class="col-sm-1 border"><span>���-��</span></div>
    <div class="col-sm-3 border"><span>����� ��������</span></div>
    <div class="col-sm-3 border"><span>���� ��������</span></div>
    <div class="col-sm-3 border"><span>�����������</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>
<?php
foreach( $order_arr AS $key => $order )
{
    if( $key + 1 == count( $order_arr ) )
        $border = ' border b-border ';
            else
                $border = ' border ';
?>
    <div class="row">
    <div class="col-sm-1 <?= $border?> l-border"><span><?= $key + 1 ?></span></div>
    <div class="col-sm-4<?= $border?>"><div class='AL'><span><?= $order['zakdet_name']?></span></div></div>
    <div class="col-sm-2 <?= $border?>"><span><?= $order['zak_type']?>&nbsp;<?= $order['zak_name']?></span></div>
    <div class="col-sm-4 <?= $border?>"><span><?= $order['draw_name']?></span></div>
    <div class="col-sm-3 <?= $border?>"><span><?= strlen($order['part_num']) == 0 ? '&nbsp;' : $order['part_num']?></span></div>
    <div class="col-sm-1 <?= $border?>"><span><?= $order['count']?></span></div>
    <div class="col-sm-3 <?= $border?>"><span><?= strlen($order['transfer_place']) == 0 ? '&nbsp;' : $order['transfer_place'] ?></span></div>
    <div class="col-sm-3 <?= $border?>"><span><?= $order['storage_time']?></span></div>
    <div class="col-sm-3 <?= $border?>"><span><?= $order['note'] ? $order['note'] : '&nbsp;' ; ?></span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>
<?php
 }
?>
    <div class="row delimiter">
    <div class="col-sm-24"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span  class='bold italic'>&nbsp;&nbsp;&nbsp;&nbsp;��������</span></div>
    <div class="col-sm-4 b-border sign"><div><span>������</span></div></div>
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
    <div class="col-sm-4 sign"><div><span>���������</span></div></div>
    <div class="col-sm-4 sign"><span>�������</span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>����</span></div>
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
    <div class="col-sm-2 sign"><span  class='bold italic'>������</span></div>
    <div class="col-sm-4 b-border sign"><div><span>���������</span></div></div>
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
    <div class="col-sm-4 sign"><div><span>���������</span></div></div>
    <div class="col-sm-4 sign"><span>�������</span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>����</span></div>
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
    <div class="col-sm-2 sign"><span  class='bold italic'>�������</span></div>
    <div class="col-sm-4 b-border sign"><div><span>���������</span></div></div>
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
    <div class="col-sm-4 sign"><div><span>���������</span></div></div>
    <div class="col-sm-4 sign"><span>�������</span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>����</span></div>
    <div class="col-sm-2 sign"><span>&nbsp;</span></div>
    <div class="col-sm-4 sign"><span>&nbsp;</span></div>
    <div class="col-sm-2 wh_border"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>
    </div>


<?php

    // <div class="row note">
    // <div class="col-sm-24"><span>����������</span></div>
    // </div><!-- <div class="row"> -->
    // <div class="clearfix"></div>

    // <div class="row note-table-head">
    // <div class="col-sm-2">&nbsp;<span></span></div>
    // <div class="col-sm-4 border l-border"><span>������������ �����</span></div>
    // <div class="col-sm-4 border"><span>���� ��������</span></div>
    // </div><!-- <div class="row"> -->
    // <div class="clearfix"></div>

    // <div class="row note-table">
    // <div class="col-sm-2">&nbsp;<span></span></div>
    // <div class="col-sm-4 border l-border"><span>��������������</span></div>
    // <div class="col-sm-4 border"><span>14 ����</span></div>
    // </div><!-- <div class="row"> -->
    // <div class="clearfix"></div>

    // <div class="row note-table">
    // <div class="col-sm-2">&nbsp;<span></span></div>
    // <div class="col-sm-4 border l-border"><span>�������������</span></div>
    // <div class="col-sm-4 border"><span>15 ���� - 2 ������</span></div>
    // </div><!-- <div class="row"> -->
    // <div class="clearfix"></div>

    // <div class="row note-table">
    // <div class="col-sm-2">&nbsp;<span></span></div>
    // <div class="col-sm-4 border  l-border b-border"><span>������������</span></div>
    // <div class="col-sm-4 border  b-border"><span>����� 2-� �������</span></div>
    // </div><!-- <div class="row"> -->

