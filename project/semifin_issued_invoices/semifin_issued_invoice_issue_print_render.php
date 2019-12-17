<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/print.css" media="print">

<style>
@media print
{

  .container
  {
    margin-left: 40px;
    margin-top: 40px;    
  }

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

span.req_name
{
    display:inline-block;
    border-bottom:1px solid black;
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

require_once( "functions.php" );

global $pdo;

$initiator_user_name = "";
$issued_user_name = "";

$doc_suffix = ["","","-а","-б","-в","-г","-д","-е","-ж","-з","-и","-к","-л","-м","-н","-о","-п","-р","-с","-т","-у","-ф","-х","-ц","-ч","-ш"];

if ( isset($_GET["p0"]) )
  $batch_id = $_GET["p0"];

$transaction = isset($_GET["p1"]) ?   $transaction = $_GET["p1"] : 0;
$report_num = $batch_id.$doc_suffix[ $transaction ];

$result = get_warehouse_structure();

$today = date("d.m.Y");
$data = [];

$head = "
<table  class='table table-striped table-bordered invoices_table'>

<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='5%'>
<col width='5%'>
<col width='25%'>

 <thead>
  <tr class='success'>
  <th class='AC'>".conv('ДСЕ')."</th>
  <th class='AC'>".conv('№ заказа')."</th>
  <th class='AC'>".conv('№ чертежа')."</th>

  <th class='AC'>".conv('Запрошено')."</th>  
  <th class='AC'>".conv('Выдано')."</th>
  <th class='AC'>".conv('Комментарии')."</th>
</tr>
 </thead>";

$tail= "</table>";

try
  {
      $query ="
           SELECT 
            inv.id AS inv_id,
            inv.operation_id AS operation_id,
            zakdet.NAME AS dse_name,
            zakdet.OBOZ AS dse_draw,

            zak.NAME AS zak_name,
            zaktype.description AS zak_type,

            initiator_user.IO AS initiator_user_name,
            inv.user_id AS initiator_user_id,
            issue_user.IO AS issued_user_name,

            iss_inv.id AS iss_inv_id,
            iss_inv.name AS iss_inv_name,
            iss_inv.issued_from AS issued_from,
            iss_inv.issued_from_res_id AS issued_from_res_id,
            iss_inv.batch AS batch,
            iss_inv.transaction AS transaction,
            res.count AS res_count

           FROM okb_db_semifinished_store_issued_invoices AS iss_inv
           LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id = iss_inv.created_from
           
           LEFT JOIN okb_users issue_user ON issue_user.ID = iss_inv.issued_user_id
           LEFT JOIN okb_users initiator_user ON initiator_user.ID = inv.user_id 

           LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = inv.id_zakdet
           LEFT JOIN okb_db_zak zak ON zak.ID = zakdet.ID_zak
           LEFT JOIN okb_db_zak_type zaktype ON zaktype.ID = zak.TID
           LEFT JOIN okb_db_warehouse_reserve AS res ON res.batch = iss_inv.batch                       
           WHERE 
                  iss_inv.batch = $batch_id
           ORDER BY batch DESC, transaction
           " ;

      // echo $query;

      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
  }

  while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
  {

    if( $transaction && $row -> transaction != $transaction )
      continue;

    $iss_arr = json_decode( $row -> issued_from, true );
    $data[ $row -> batch ][ $row -> transaction ]['initiator_user_name'] = conv( $row -> initiator_user_name ); 
    $data[ $row -> batch ][ $row -> transaction ]['issued_user_name'] = conv( $row -> issued_user_name );
    $data[ $row -> batch ][ $row -> transaction ]['name'] = conv( $row -> iss_inv_name );
    $data[ $row -> batch ][ $row -> transaction ]['items'][ $row -> iss_inv_id ] = 
      [
        "dse_name" => conv( $row -> dse_name ),
        "dse_draw" => conv( $row -> dse_draw ),
        "order_name" => conv( $row -> zak_type )." ".conv( $row -> zak_name ),
        "operation_id" => $row -> operation_id,        
        "issued_from" => $iss_arr,
        "res_count" => $row -> res_count
      ];
  }

  // debug( $data );

foreach ( $data as $key => $value ) 
  {
    $str .= "<div class='wrap'><h3>".conv("Накладная №$key").conv( $doc_suffix[ $transaction ] )."</h3>";

    // $str .= "<div class='print_div hidden'>
    //                   <button class='btn btn-big btn-primary float-right print_button' type='button' data-batch='$batch'>".conv("Распечатать")."</button></div>";
    // $str .= "</div>" ;

    $str .= $head ;

  foreach ( $value as $skey => $svalue ) 
  {
    $initiator_user_name = $svalue['initiator_user_name'];
    $issued_user_name = $svalue['issued_user_name'];

    $loc_line = 1 ;      
    $subitems_count = count( $svalue['items'] );
    $ssubitems_count = 0;
    
    foreach ( $svalue['items'] as $sskey => $ssvalue )
      $ssubitems_count += count( $ssvalue['issued_from'] );

    $str .= "<tr class=''>";
    // $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$svalue['name']}</td>";

        foreach ( $svalue['items'] as $sskey => $ssvalue )
        {
          $sloc_line = 1 ;

           if( $loc_line > 1 )
            $str .= "<tr class='$loc_line'>"; 
    
          $ssubitems_count = count( $ssvalue['issued_from'] );

          $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$ssvalue['dse_name']}</td>";
          $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$ssvalue['order_name']}</td>";
          $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$ssvalue['dse_draw']}</td>";


          foreach( $ssvalue['issued_from'] AS $ssskey => $sssvalue )
          {
            $wh = $sssvalue['wh'];
            $cell = $sssvalue['cell'];
            $tier = $sssvalue['tier'];
            $count = $sssvalue['count'];

            $comment = isset( $sssvalue['comments'] ) ? $sssvalue['comments'] : "" ;

            if( $sloc_line > 1 )
              $str .= "<tr class=''>"; 

            $str .= "<td class='field AC'>".( $ssvalue['res_count'] + $count )."</td>";
            // $str .= "<td class='field AC'>{$result['wh'][$wh]}</td>";
            // $str .= "<td class='field AC'>{$result['cells'][$cell]}</td>";
            // $str .= "<td class='field AC'>{$result['tiers'][$tier]}</td>";
            $str .= "<td class='field AC'>$count</td>";
            $str .= "<td class='field AC'>$comment</td>";
            
            if( $sloc_line == 1 )
              $str .= "</tr>";

            $sloc_line ++ ;
          }

          if( $loc_line == 1 )
            $str .= "</tr>";
          
          $loc_line ++ ;
        }

    $str .= "</tr>";
  }
  $str .= $tail ;
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
    <div class="col-sm-3"><span class='inv_num'><?= conv("№ ПФ-$report_num от $today"); ?></span></div>
    <div class="col-sm-2 border l-border b-border"><span><?= conv("ПДО"); ?></span></div>
    <div class="col-sm-2 border b-border"><span><?= conv("СХ"); ?></span></div>
    <div class="col-sm-2 border b-border"><span><?= conv("Производство"); ?></span></div>
    </div><!-- <div class="row"> -->

    <div class="clearfix">&nbsp;</div>

    <div class="row">
    <div class="col-sm-24"><span class='bold italic'><?= conv("Инициатор : "); ?></span><span><?= conv("Инженер ПДО ").$initiator_user_name; ?><span class='req_name'> </span></span></div>
    </div><!-- <div class="row"> -->

<?php

echo $str;
$user_info = GetUserInfo( $user['ID']  );
?>
    <div class="row delimiter">
    <div class="col-sm-24"><span>&nbsp;</span></div>
    </div><!-- <div class="row"> -->
    <div class="clearfix"></div>

<!-- table row footer -->
    <div class="row footer">
    <div class="col-sm-2 sign"><span  class='bold italic'>&nbsp;&nbsp;&nbsp;&nbsp;<?= conv("Отпустил"); ?></span></div>
    <div class="col-sm-4 b-border sign"><div><span><?= conv("кладовщик ").$issued_user_name ; ?></span></div></div>
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
    </div>

