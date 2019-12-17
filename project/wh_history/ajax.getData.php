<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );

global $mysqli;

$from_date  = $_POST['from_date']." 00:00:00";
$to_date = $_POST['to_date']." 59:59:59";

$pattern = $_POST['pattern'];
$pat_where = "";

$wh = $_POST['wh'];
$wh_where = "";

$op = $_POST['op'];
$op_where = "";

if( $wh )
  $wh_where = "AND ( from_wh.ID = $wh OR to_wh.ID = $wh )"; 

if( $op )
  $op_where = "AND  hist.action_type_id = $op"; 

if( strlen( $pattern )) 
  $pat_where = "AND  hist.comment LIKE '%$pattern%'"; 

try
{
    $query ="
                  SELECT 
                  hist.id,
                  hist.action_type_id,
                  hist.from_tier,
                  hist.to_tier,
                  hist.id_zakdet,
                  hist.dse_name AS hist_dse_name,
                  hist.count AS count,
                  hist.comment AS comment,
                  DATE_FORMAT( hist.timestamp, '%d.%m.%Y %H:%i' ) AS date,

                  zakdet.NAME AS dse_name,
                  zakdet.OBOZ AS dse_draw,

                  zak.NAME AS zak_name,

                  zak_type.description AS zak_type,

                  act_type.description AS act_type,
                  user.FIO AS user_name,

                  from_tier.ORD AS from_tier_name,
                  to_tier.ORD AS to_tier_name,

                  from_cell.NAME AS from_cell_name,
                  to_cell.NAME AS to_cell_name,

                  from_wh.NAME AS from_wh_name,
                  to_wh.NAME AS to_wh_name                  

                  FROM okb_db_warehouse_action_history AS hist
                  LEFT JOIN okb_db_warehouse_action_type AS act_type ON act_type.id = hist.action_type_id
                  LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.ID = hist.id_zakdet
                  LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
                  LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
                  LEFT JOIN okb_users AS user ON user.ID = hist.user_id

                  LEFT JOIN okb_db_sklades_yaruses AS from_tier ON from_tier.ID = hist.from_tier
                  LEFT JOIN okb_db_sklades_yaruses AS to_tier ON to_tier.ID = hist.to_tier

                  LEFT JOIN okb_db_sklades_item AS from_cell ON from_cell.ID = from_tier.ID_sklad_item
                  LEFT JOIN okb_db_sklades_item AS to_cell ON to_cell.ID = to_tier.ID_sklad_item

                  LEFT JOIN okb_db_sklades AS from_wh ON from_wh.ID = from_cell.ID_sklad
                  LEFT JOIN okb_db_sklades AS to_wh ON to_wh.ID = to_cell.ID_sklad

                  WHERE 
                  hist.timestamp >= '$from_date'
                  AND
                  hist.timestamp <= '$to_date'
                  $wh_where
                  $op_where
                  $pat_where
                  ORDER BY date DESC
                  ";

    // echo $query;

    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
  }

  $data = [];

 while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $data[] =  $row ;
     
  // debug( $data[0] );

  $str = "<table class='tbl' id='history_table'>
          <col width = '3%' />          
          <col width = '15%' />
          <col width = '10%' />
          <col width = '10%' />
          <col width = '10%' />
          <col width = '8%' />
          <col width = '8%' />
          <col width = '8%' />
          <col width = '8%' />
          <col width = '5%' />                    

          <tr class='first'>
            <td class='field'>".conv("#")."</td>          
            <td class='field'>".conv("Операция по складу")."</td>
            <td class='field'>".conv("ДСЕ")."</td>
            <td class='field'>".conv("Заказ")."</td>
            <td class='field'>".conv("Чертеж")."</td>
            <td class='field'>".conv("Источник")."</td>
            <td class='field'>".conv("Приемник")."</td>
            <td class='field'>".conv("Инициатор")."</td>
            <td class='field'>".conv("Дата")."</td>
            <td class='field'>".conv("Кол-во")."</td>
          </tr>";

  $line = 1 ;
  $class = ["even", "odd"];

  foreach( $data AS $value )
  {
    $action_type = $value -> action_type_id;
    if( $value -> id_zakdet )
    {
      $dse_name = conv( $value -> dse_name );
      $dse_draw = conv( $value -> dse_draw );
      $ord_name = conv( $value -> zak_type." ".$value -> zak_name );
    }
    else
    {
      if( strlen( $value -> hist_dse_name ) )
        $dse_name = conv( $value -> hist_dse_name );
          else
            $dse_name = conv( $value -> comment );
    
      $dse_draw = "-";
      $ord_name = "-" ; 
    }

    $src = "-";
    $dest = "-";    

    if( $value -> from_tier && $action_type != WH_DATA_EDIT )
        $src = conv( $value -> from_wh_name )."<br>".conv( "Яч. ".$value -> from_cell_name )."<br>".conv( "Яр. ".$value -> from_tier_name );
    
    if( $value -> to_tier && $action_type != WH_ISSUE )
        $dest = conv( $value -> to_wh_name )."<br>".conv( "Яч. ".$value -> to_cell_name )."<br>".conv( "Яр. ".$value -> to_tier_name );

      $count = $value -> count;

      if( $action_type == WH_DATA_EDIT ) // Редактирование даннных. Старое значение сохранено в from_tier
        $count = "c ".$value -> from_tier. conv(" на ") .$count ;

      if( $action_type == WH_ISSUE ) // Выдача. В to_tier сохранен остаток
        $count = conv("выдано :").$count.conv(" шт.<br>остаток :").$value -> to_tier;

      if( $action_type == WH_OPERATION_EDIT ) // Редактирование операции. ID старой операции значение сохранено в from_tier, новой в to_tier
      {
        $dest = conv( $value -> comment );
      }

    $str .= "
            <tr data-id='".$value -> id."' class='".$class[ $line % 2 ]."'>
            <td class='field AC'>$line</td>          
            <td class='field AC'>".conv( $value -> act_type )."</td>
            <td class='field AC'>$dse_name</td>
            <td class='field AC'>$ord_name</td>
            <td class='field AC'>$dse_draw</td>
            <td class='field AC'>$src</td>
            <td class='field AC'>$dest</td>
            <td class='field AC'>".conv( $value -> user_name )."</td>
            <td class='field AC'>".$value -> date."</td>
            <td class='field AC'>$count</td>
            </tr>";
            $line ++ ;
  }

  $str .=  "</table>";

// $str = "$from_date : $to_date : $pattern : <br> $query";

echo $str; // iconv( "Windows-1251", "UTF-8", $str );

