<link rel='stylesheet' href='/project/cooperation_database/css/style.css?v=2' type='text/css'>

<?php
error_reporting( E_ALL );
// error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "./cooperation_dep.php" );

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

$user_id = $_POST['user_id'];
$table = "cooperation_database_form6";

if( in_array( $user_id, $cooperation_dep ) )
  $readonly = '';
    else
        $readonly = 'readonly';

$str =   "<br><h2>".conv( "Лазерная резка" )."</h2>";
$str =   "<br>";
$str .= "<table class='table tbl form5' id='$table'>";
$str .= "
                   <col width='16%'>
                   <col width='16%'>
                   <col width='16%'>
                   <col width='16%'>
                   <col width='16%'>
                   <col width='16%'>
                   ";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='6'>".conv( "Лазерная резка" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='2'>".conv( "Общая стоимость заказа" )."</td>";
$str .= "<td class='Field AC'>".conv( "От 50 тыс. руб" )."</td>";
$str .= "<td class='Field AC'>".conv( "15-50 тыс. руб" )."</td>";
$str .= "<td class='Field AC'>".conv( "до 15 тыс. руб" )."</td>";
$str .= "<td class='Field AC' rowspan='2'>".conv( "Актуальность" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>".conv( "Материал" )."</td>";
$str .= "<td class='Field AC'>".conv( "Толщина в мм." )."</td>";
$str .= "<td class='Field AC' colspan='3'>".conv( "Стоимость за метр реза, руб. без НДС" )."</td>";
$str .= "</tr>";



  $data = [];
  $material = [];
  $actualization_date = null ;
  $actualizator = null ;

  try
  {
      $query = "  SELECT 
                  form.id AS id, form.material AS material,
                  users.FIO AS actualizator, 
                  DATE_FORMAT( form.actualization_date, '%d.%m.%Y %H:%i') AS actualization_date 
                  FROM $table form
                  LEFT JOIN okb_users users ON users.ID = form.actualizator
                  WHERE form.pid = 0 ";
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }

  // Multiple record
  while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
  {
    $data[ $row -> id ] = [];
    $material[ $row -> id ] = conv( $row -> material );
    $actualization_date = $row -> actualization_date ;
    $actualizator = conv( $row -> actualizator );
  }

  try
  {
      $query = "SELECT * FROM $table WHERE pid <> 0";
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }

  while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    $data[ $row -> pid ][] = [ 'id' => $row -> id ,'thickness' => conv( $row -> thickness ), 'price' => conv( $row -> price ), 'price2' => conv( $row -> price2 ) , 'price3' => conv( $row -> price3 ) ];

  foreach( $data AS $key => $value )
  {
    $row_count = count( $data[ $key ] );
    $line = 1 ;
   
    foreach( $data[ $key ] AS $skey => $svalue )
    {
        $id = $svalue[ 'id' ];
        $str .= "<tr data-number='$id'>";

        if( $line == 1 )
          $str .= "<td class='Field AC' rowspan='$row_count'>{$material["$key"]}</td>";

        $str .= "<td class='Field AC'>{$svalue['thickness']}</td>";
        $str .= "<td class='Field AC'><input $readonly class='row_data' data-field='price' value='".$svalue['price']."' /></td>";      
        $str .= "<td class='Field AC'><input $readonly class='row_data' data-field='price2' value='".$svalue['price2']."' /></td>";      
        $str .= "<td class='Field AC'><input $readonly class='row_data' data-field='price3' value='".$svalue['price3']."' /></td>";      


        if( $line == 1 )
          $str .= "<td class='Field AC' rowspan='$row_count'><span class='actuality_date'>$actualization_date<br>$actualizator</span></td>";

        $line ++ ;
    }

  }

$colspan = 6 ;

$str .= "<tr><td class='no_border Field' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Все цены указаны без НДС" )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Стоимость резки изделия рассчитывается по формуле:" )."</td>";
$str .= "</tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "(длина контуров изделий, м) х (стоимость реза 1м материала изделия) + (количество контуров изделий) х S, где S = толщина металла" )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "В прейскуранте указаны цены при условии  передачи заказчиком чертежей в формате DXF (экспорт и программ AutoCAD? Kompas, CorelDRAW и других), не требующих дополнительных изменений. В ином случае стоимость обработки чертежей оговаривается отдельно." )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "При резке деталей, требующей их позиционирования на столе, применяется повышающий коэффициент 1.5 - 0.3 в зависимости от сложности работ." )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Минимальная стоимость заказа 1500 руб. " )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "На большие заказы - ИНДИВИДУАЛЬНЫЙ РАСЧЁТ ЦЕН" )."</td>";
$str .= "</tr>";

$str .= "</table>";

echo $str ;