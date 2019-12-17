<script src="/project/sklad/js/bootstrap.min.js"></script>
<script src="/project/sklad/js/warehouse.js"></script>

<link rel='stylesheet' id="bootstrap-css" href='/project/sklad/css/bootstrap.min.css' type='text/css'>
<link href="/project/sklad/css/bootstrap-glyphicons.min.css" rel="stylesheet" type="text/css" />
<link href="/project/sklad/maps/glyphicons-fontawesome.min.css" rel="stylesheet" type="text/css" />

<link rel='stylesheet' href='/project/sklad/css/style.css' type='text/css'>

<link rel='stylesheet' href='/project/sklad/css/jquery-ui.css' type='text/css'>

<?php
// require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );


global $user;
$user_id = $user['ID'];
$semifin_invoices = [];
echo "<script>var user_id = $user_id;</script>";

try
{
  $query =
  "  SELECT 
  zakdet.NAME AS dse_name,
  zakdet.OBOZ AS dse_draw,

  zak.NAME AS zak_name,
  zaktype.description AS zak_type,

  res.count AS count ,
  res.id AS id,
  res.pattern AS pattern,
  res.batch AS batch,
  res.comment AS comment,
  res.operation_id AS res_operation_id,

  users.IO AS user_name,

  inv.id_zakdet AS id_zakdet,
  inv.draw_name AS draw_name,
  inv.id AS inv_id,

  DATE_FORMAT( res.timestamp, '%d.%m.%Y %H:%i:%s' ) AS date_time
  FROM `okb_db_warehouse_reserve` AS res
  LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id_zakdet = res.id_zakdet

  JOIN okb_users users ON users.ID = res.user_id
  LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = inv.id_zakdet
  LEFT JOIN okb_db_zak zak ON zak.ID = zakdet.ID_zak
  LEFT JOIN okb_db_zak_type zaktype ON zaktype.ID = zak.TID

  WHERE 1 
  ORDER BY batch DESC, pattern";

  // echo $query;

  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
  $id = $row -> id;
  $batch = $row -> batch;
  $issued = get_history( $id );

    $semifin_invoices[ $batch ][$id] = 
  [
    'rec_id' => $id,
    'batch' => $batch,
    'date_time' => $row -> date_time,
    'zak_name' => conv( $row -> zak_name ),
    'zak_type' => conv( $row -> zak_type ),
    'dse_name' => conv( $row -> dse_name ),
    'dse_draw'=> conv( $row -> dse_draw ),
    'comment' => conv( $row -> comment ),
    'pattern' => conv( $row -> pattern ),
    'issued' => $issued,
    'user_name' => conv( $row -> user_name ),
    'user_id' => $row -> user_id,
    'count' => $row -> count,
    'operation_id' => $row -> res_operation_id,
    'operation_name' => ( $row -> res_operation_id ? conv( GetOperationName( $row -> res_operation_id )) : conv("Без операции") ),
    'id_zakdet' => $row -> id_zakdet,
  ];
}

// debug( $semifin_invoices );

$str = "<h2>".conv("Заявки на выдачу полуфабрикатов")."</h2>";

$str .= "<div class='container'>";

$str .= "<hr>";

$str .= "
<div class='row'>
<table class='table table-striped semifin_invoices'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='20%'>
<col width='4%'>
<col width='4%'>
<col width='4%'>    
<col width='10%'>    
<!-- table-primary -->
<thead>
<tr class='table-success'>
<th>".conv( "№ заявки" )."</th>
<th>".conv( "Заказ" )."</th>
<th>".conv( "ДСЕ" )."</th>
<th>".conv( "Чертеж" )."</th>
<th>".conv( "Операция" )."</th>
<th>".conv( "Дата создания" )."</th>
<th>".conv( "Запросил" )."</th>
<th>".conv( "Комментарий" )."</th>
<th>".conv( "Запр." )."</th>
<th>".conv( "Выдать." )."</th>
<th>".conv( "Выдано." )."</th>
<th>".conv( "Состояние" )."</th>
<th></th>      
</tr>
</thead>
<tbody>";

$line = 1 ;

$state_str = [ "Готово к выдаче", "Выдается", "Выдано" ];

foreach( $semifin_invoices AS $key => $gval )
{
  $state = GetState( $key );

  $rows = count( $gval );
  $cur_row = 1 ;
  
 foreach( $gval AS $val ) 
  {

    $rec_user_id = $val['user_id'];
    $img_class = 'del_img_dis';
    $img_pict = '/uses/del_dis.png';

    if( $rec_user_id == $user_id )
    {
      $img_class = 'del_img';
      $img_pict = '/uses/del.png';
    }

  $storekeeper_note = "<button class='btn btn-sm btn-dark storekeeper_button'><span class='glyphicon glyphicon-edit'></span></button>";

	$issues_count = GetIssuesCount( $val['batch'] );

    $issues_count = $issues_count ? "<button class='btn btn-sm btn-info issues_count_button'><span class='glyphicon'>".GetIssuesCount( $val['batch'] )."</span></button>" : "<button class='btn btn-sm btn-info issues_count_button hidden'><span class='glyphicon'>".GetIssuesCount( $val['batch'] )."</span></button>";

    $requested_count = "<span class='issued'>".( $val['count'] + $val['issued'] )."</span>";

    $a_res_count = $val['count'] ? "<a title='".conv("Выдать")."' href='#' class='res_count' data-pattern='".$val['pattern']."'>".$val['count']."</a>" : "<span class='issued'>".$val['count']."</span>";

    $a_issued = $val['issued'] ? "<a href='#' title='".conv("История выдачи")."' class='issued_count'>".$val['issued']."</a>" : "<span class='issued_count_span'>0</span>";

    $issue = "<span class='state_text'>".conv( $state_str[ $state ] )."</span>";

    $img_pict = "";

    $inv_name = conv("Заявка №").$val['batch'];

    $str .=
    "<tr 
    data-id-zakdet='".$val['id_zakdet']."'                 
    data-rec-id='".$val['rec_id']."'
    data-operation-id='".$val['operation_id']."'
    data-batch='".$val['batch']."'
    class='".( $state == 1 ? "not_completed" : "")."'
    >";

    if( $cur_row == 1 )
    {
      $str .= "<td class='AC' rowspan='$rows'><span>$inv_name</span></td>";
      $line ++ ;
    }
    
    $dse_name = strlen( $val['dse_name'] ) ? $val['dse_name'] : $val['pattern'] ;

    $str .= "
    <td class='AL'><span>{$val['zak_type']} {$val['zak_name']}</span></td>    
    <td class='AL'><span>$dse_name</span></td>
    <td class='AL'><span>{$val['dse_draw']}</span></td>
    <td class='AC'><span>{$val['operation_name']}</span></td>
    <td class='AC'><span>{$val['date_time']}</span></td>
    <td class='AC'><span>{$val['user_name']}</span></td>
    <td class='AL'><span>{$val['comment']}</span></td>
    <td class='AC'>$requested_count</td>
    <td class='AC'>$a_res_count</td>
    <td class='AC'>$a_issued</td>";
    
    if( $cur_row == 1 )
      $str .= "<td class='AC' rowspan='$rows'>$issue<br>$storekeeper_note $issues_count</td>";
    
    $str .= "<td class='AC'><img class='$img_class' src='$img_pict' /></td>                  
    </tr>";
    $cur_row ++ ;
  }
}


$str .= "</table></div></div>";

$str .=  "
<div id='warehouse_dialog' class='hidden' title='".conv("Выдать со склада")."'>
</div>";

$str .=  "
<div id='issue_history_dialog' class='hidden' title='".conv("История выдачи со склада")."'>
</div>";

echo $str ;
