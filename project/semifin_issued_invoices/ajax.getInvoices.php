<?php
require_once( "functions.php" );
global $pdo;

$data = [];
$year = isset( $_POST['year'] ) ? + $_POST['year'] : 0 ;
$month = + $_POST['month'];
$batch = + $_POST['batch'];

$where = " 1 ";

if( $year )
  $where = "iss_inv.date >= '$year-01-01 00:00:00' AND iss_inv.date <= '$year-12-31 23:59:59'";

if( $month && $year )
  $where = "iss_inv.date >= '$year-$month-01 00:00:00' AND iss_inv.date <= '$year-$month-31 23:59:59'";

if( $month && !$year )
  $where = " DATE_FORMAT( iss_inv.date , '%m' ) = $month ";

if( $batch )
  $where .= "AND iss_inv.batch = $batch";

$head = "
<table  class='table table-striped table-bordered invoices_table'>

<col width='10%'>
<col width='5%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>

 <thead>
  <tr class='success'>
  <th class='AC'>".conv('№ Заявки')."</th>
  <th class='AC'>".conv('Дата')."</th>  
  <th class='AC'>".conv('№ заказа')."</th>
  <th class='AC'>".conv('ДСЕ') ."</th>
  <th class='AC'>".conv('№ чертежа')."</th>
  
  <th class='AC'>".conv('Запр.')."</th>
  
  <th class='AC'>".conv('Склад')."</th>
  <th class='AC'>".conv('Ячейка')."</th>
  <th class='AC'>".conv('Ярус')."</th>
  <th class='AC'>".conv('Количество')."</th>
  <th class='AC'>".conv('Комментарии')."</th>
</tr>
 </thead>";

$tail= "</table>";
$str = "";

$result = get_warehouse_structure();

try
  {
      $query ="
           SELECT 
            inv.operation_id AS operation_id,
            zakdet.NAME AS dse_name,
            zakdet.OBOZ AS dse_draw,

            zak.NAME AS zak_name,
            zaktype.description AS zak_type,
            
            users.IO AS user_name,

            iss_inv.id AS iss_inv_id,
            iss_inv.name AS iss_inv_name,
            iss_inv.issued_from AS issued_from,
            iss_inv.issued_from_res_id AS issued_from_res_id,
            iss_inv.batch AS batch,
            iss_inv.transaction AS transaction,
            DATE_FORMAT( iss_inv.date, '%d.%m.%Y %H:%i') AS iss_inv_date,
            
            res.count AS res_count

           FROM okb_db_semifinished_store_issued_invoices AS iss_inv
           LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id = iss_inv.created_from
           
           LEFT JOIN okb_users users ON users.ID = iss_inv.issued_user_id
           LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = inv.id_zakdet
           LEFT JOIN okb_db_zak zak ON zak.ID = zakdet.ID_zak
           LEFT JOIN okb_db_zak_type AS zaktype ON zaktype.ID = zak.TID
           LEFT JOIN okb_db_warehouse_reserve AS res ON res.batch = iss_inv.batch            
           WHERE 
           $where
           ORDER BY batch, transaction DESC
           " ;

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
    $iss_arr = json_decode( $row -> issued_from, true );

    $data[ $row -> batch ][ $row -> transaction ]['name'] = conv( $row -> iss_inv_name );
    $data[ $row -> batch ][ $row -> transaction ]['date'] = conv( $row -> iss_inv_date );

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
    $str .= "<div class='wrap'><h3>".conv("Заявка №$key")."</h3>";

    // $str .= "<div class='print_div'>
    //                   <button class='btn btn-big btn-primary float-right print_button' type='button' data-batch='$batch'>".conv("Распечатать")."</button></div>";


    $str .= "</div>" ;

    $str .= $head ;

  foreach ( $value as $skey => $svalue ) 
  {

    $loc_line = 1 ;      
    $subitems_count = count( $svalue['items'] );
    $ssubitems_count = 0;

    $print = "<button data-batch='$batch' data-transaction='$skey' class='btn btn-sm btn-success print_button'><span class='glyphicon glyphicon-print'></span></button>";
    
    foreach ( $svalue['items'] as $sskey => $ssvalue )
      $ssubitems_count += count( $ssvalue['issued_from'] );

    $str .= "<tr>";
    $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$svalue['name']}<br>$print</td>";
    $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$svalue['date']}</td>";

        foreach ( $svalue['items'] as $sskey => $ssvalue )
        {

          $res_count = $ssvalue['res_count'];
          $sloc_line = 1 ;

           if( $loc_line > 1 )
            $str .= "<tr class='$loc_line'>"; 
    
          $ssubitems_count = count( $ssvalue['issued_from'] );

          $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$ssvalue['order_name']}</td>";
          $str .= "<td class='field AC' rowspan='$ssubitems_count'>{$ssvalue['dse_name']}</td>";
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

            $str .= "<td class='field AC'>".( $res_count + $count )."</td>";
            $str .= "<td class='field AC'>{$result['wh'][$wh]}</td>";
            $str .= "<td class='field AC'>{$result['cells'][$cell]}</td>";
            $str .= "<td class='field AC'>{$result['tiers'][$tier]}</td>";
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

echo $str;


