<script src="/project/sklad/js/bootstrap.min.js"></script>
<script src="/project/sklad/js/report_by_order.js"></script>

<link rel='stylesheet' id="bootstrap-css" href='/project/sklad/css/bootstrap.min.css' type='text/css'>
<link href="/project/sklad/css/bootstrap-glyphicons.min.css" rel="stylesheet" type="text/css" />
<link href="/project/sklad/maps/glyphicons-fontawesome.min.css" rel="stylesheet" type="text/css" />
<link rel='stylesheet' href='/project/sklad/css/style_by_order.css' type='text/css'>

<?php
// require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );
$user_id = $user['ID'];

$basket_res = dbquery("SELECT * FROM okb_db_warehouse_dse_basket WHERE user_id = $user_id");

if ( mysql_fetch_row( $basket_res ) )
  echo "<script>let items_in_basket = 1 </script>";
    else
      echo "<script>let items_in_basket = 0 </script>";

echo "<div><h2>Отчет по ДСЕ на складе по заказам <a href='#' class='top_menu hidden' target='_blank'>Печать</a></h2>";
echo "<div id='basket-dialog' title='Запрос ДСЕ на выдачу' class='hidden'></div>";

$wh_struct = get_warehouse_structure();
$wh = $wh_struct["wh"];
$cells = $wh_struct["cells"];
$tiers = $wh_struct["tiers"];

$query_with_zak = "
              SELECT 
              inv.id AS inv_id,
    #         inv.count,
              inv.storage_place AS storage_place,
              inv.id_zadan AS zadan_id,
              inv.operation_id AS operation_id,
              inv.id_zakdet AS inv_zakdet_id,

              inv.dse_name AS inv_dse_name,
              inv.draw_name AS inv_dse_draw,

              #zakdet.NAME AS inv_dse_name,
              #zakdet.OBOZ AS inv_dse_draw,

              zakdet.ID AS zakdet_id,
              zakdet.ID_zak AS zak_id,

              CONCAT(zak_type.description,' ', zak.NAME) AS order_name,
              zak.DSE_NAME AS zak_dse_name,
              zak.DSE_OBOZ AS zak_dse_draw,
              oper.NAME AS operation_name,
             
              wh_detitem.NAME AS wh_dse_name,
              wh_detitem.COUNT AS count,

              basket.count AS basket_count

              FROM okb_db_sklades_detitem AS wh_detitem

              LEFT JOIN okb_db_semifinished_store_invoices AS inv ON wh_detitem.ref_id = inv.id
              LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.ID = inv.id_zakdet
              LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
              LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID
              LEFT JOIN okb_db_oper AS oper ON oper.ID = inv.operation_id
              LEFT JOIN okb_db_warehouse_dse_basket AS basket ON basket.pattern = wh_detitem.NAME
              WHERE zakdet.ID_zak <> 0
              #GROUP BY wh_detitem.NAME
              ORDER BY zak.NAME, inv.id_zakdet
              ";

$query_without_zak = "
              SELECT 
              inv.id AS inv_id,
    #         inv.count,
              inv.storage_place AS storage_place,
              inv.id_zadan AS zadan_id,
              inv.operation_id AS operation_id,
              inv.id_zakdet AS inv_zakdet_id,

              inv.dse_name AS inv_dse_name,
              inv.draw_name AS inv_dse_draw,

              #zakdet.NAME AS inv_dse_name,
              #zakdet.OBOZ AS inv_dse_draw,

              zakdet.ID AS zakdet_id,
              zakdet.ID_zak AS zak_id,

              CONCAT(zak_type.description,' ', zak.NAME) AS order_name,
              zak.DSE_NAME AS zak_dse_name,
              zak.DSE_OBOZ AS zak_dse_draw,
              oper.NAME AS operation_name,
             
              wh_detitem.NAME AS wh_dse_name,
              SUM( wh_detitem.COUNT ) AS count,

              basket.count AS basket_count

              FROM okb_db_sklades_detitem AS wh_detitem

              LEFT JOIN okb_db_semifinished_store_invoices AS inv ON wh_detitem.ref_id = inv.id
              LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.ID = inv.id_zakdet
              LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
              LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID
              LEFT JOIN okb_db_oper AS oper ON oper.ID = inv.operation_id
              LEFT JOIN okb_db_warehouse_dse_basket AS basket ON basket.pattern = wh_detitem.NAME
              WHERE inv.id_zakdet = 0
              GROUP BY wh_detitem.NAME
              ORDER BY zak.NAME, inv.id_zakdet
              ";

$arr = [];
getData( $query_without_zak, $arr );
getData( $query_with_zak, $arr );

function getData( $query, &$arr )
{
  global $pdo;

    try
    {
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

    }

    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
    	$arr[ $row -> order_name ]['zak_id'] = $row -> zak_id ;
    	$arr[ $row -> order_name ]['zak_name'] = $row -> order_name ;
    	$arr[ $row -> order_name ]['zak_dse_name'] = $row -> zak_dse_name ;
    	$arr[ $row -> order_name ]['zak_dse_draw'] = $row -> zak_dse_draw ;
      $arr[ $row -> order_name ]['count'] = 0 ;
    	$arr[ $row -> order_name ]['childs'][] = [ 
    												'inv_id' => $row -> inv_id,
    												'inv_dse_name' => $row -> inv_dse_name,
    												'inv_dse_draw' => iconv("UTF-8", "Windows-1251", $row -> inv_dse_draw ),
    												'count' => $row -> count,
                            'basket_count' => $row -> basket_count,
    												'zakdet_id' => $row -> zakdet_id,
                            'zadan_id' => $row -> zadan_id,
                            'operation_id' => $row -> operation_id,
    												'operation_name' => $row -> operation_name,
    												'storage_place' => json_decode( $row -> storage_place, true ),
                            'wh_dse_name' => $row -> wh_dse_name
    											 ];

    }
}


$accordion = "<div class='accordion' id='warehouseDSEByOrder'>
  <div class='card z-depth-0 bordered'>";

// debug( $arr );

foreach ( $arr AS $key => $value ) 
{
  $count = 0 ;
  foreach( $value['childs'] AS $skey => $svalue ) 
    $count += $svalue['count'];
  $arr[ $key ]['count'] = $count;
  $arr[ $key ]['positions'] = count( $value['childs'] );
}

foreach ( $arr AS $key => $value ) 
  foreach ( $arr[$key]['childs'] AS $skey => $svalue ) 
    unset( $arr[ $key ]['childs'][ $skey ]['storage_place']);

// debug( $arr );

foreach ( $arr AS $key => $value ) 
{

	$body = "
  <table class='table table-striped'>
  <col width='2%'>
  <col width='20%'>
  <col width='20%'>
  <col width='10%'>
  <col width='10%'>
  <col width='2%'>
  <col width='2%'>  
  <thead>
    <tr class='first'>
      <th scope='col'>#</th>
      <th scope='col'>ДСЕ</th>
      <th scope='col'>Чертеж</th>
      <th scope='col'>Операция</th>
      <th scope='col'>Количество</th>
    </tr>
  </thead>
  <tbody>";

$line = 1 ;

$value['childs'] = DSE_merge( $value['childs'] );

// _debug( $value['childs'] );

foreach ($value['childs'] AS $skey => $svalue ) 
{

  $storage_place = "";
  $count_to_issue = 0 ;
  foreach ( $svalue['storage_place'] AS $lkey => $lvalue)
    $count_to_issue += $lvalue['count'] ;

    $dse_name = strlen( $svalue['inv_dse_name'] && $svalue['inv_dse_name'] != "none") ? conv( $svalue['inv_dse_name'] ) : conv( $svalue['wh_dse_name'] );
  
    $data_zakdet_id = $svalue['zakdet_id'] ? $svalue['zakdet_id'] : 0 ;
    $data_zadan_id = $svalue['zadan_id'] ? $svalue['zadan_id'] : 0 ;
    $data_operation_id = $svalue['operation_id'] ? $svalue['operation_id'] : 0 ;
    $basket_count = $svalue['basket_count'] ? $svalue['basket_count'] : 0 ;
    $reserve_count = getReserveCount( $data_zakdet_id , $data_operation_id , $data_zadan_id, $dse_name );
    $count = $svalue['count'] - $basket_count - $reserve_count;

    $pattern = strlen( $svalue['inv_dse_draw'] ) ? $svalue['inv_dse_draw'] : conv( $svalue['wh_dse_name'] );

    $acount = "<a href='#' class='acount in_wh' data-inv_id='{$svalue['inv_id']}' data-zakdet_id='$data_zakdet_id' data-zadan_id='$data_zadan_id' data-pattern='$pattern' data-operation_id='$data_operation_id'>$count</a>";

    $body .= "
    	<tr>
          <th scope='row'>$line</th>
          <td class='AL'>$dse_name</td>
          <td class='AC'>{$svalue['inv_dse_draw']}</td>
          <td class='AC'>".conv($svalue['operation_name'])."</td>
    	    <td class='AC'>$acount</td>
        </tr>";
        $line ++;
}

$body .="</tbody>
</table>";

	$zak_name = conv( $value['zak_name'] );
	$zak_dse_name = conv( $value['zak_dse_name'] );	
	$zak_dse_draw = conv( $value['zak_dse_draw'] );	
  $count = $value['count']; 
  $positions = $arr[ $key ]['positions'];
  if( $value['zak_id'] )
    $caption = " $zak_name $zak_dse_name $zak_dse_draw ($count шт. в $positions поз.) ";
      else
        $caption = "Позиции без заказов";

	$accordion .= GetCard( $value['zak_id'], $caption, $body );
}

$accordion .= "</div></div>";
$accordion .=  "
<div id='warehouse_dialog' class='hidden' title='Заявка на выдачу деталей со склада'>
</div>";

echo $accordion;

function GetCard( $id = 0 , $caption="", $body = "" )
{
	return "  
    <div class='card-header' id='heading$id'>
      <h5 class='mb-0'>
        <button class='btn btn-link' type='button' data-toggle='collapse' data-target='#collapse$id'
          aria-expanded='true' aria-controls='collapse$id'>
          $caption
        </button>
      </h5>
    </div>
    
    <div id='collapse$id' class='collapse' aria-labelledby='heading$id'
      data-parent='#warehouseDSEByOrder'>
      <div class='card-body'>$body</div>
    </div>";
}
