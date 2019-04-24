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

$user_id = $_POST['user_id'];

if( in_array( $user_id, $cooperation_dep ) )
  $readonly = '';
    else
        $readonly = 'readonly';

$str =   "<br><h2>".conv( "Литье" )."</h2>";
$str =   "<br>";
$str .= "<table class='table tbl form7' id='cooperation_database_form7'>";
$str .= "
                   <col width='4%'>
                   <col width='14%'>
                   <col width='14%'>
                   <col width='14%'>
                   <col width='14%'>
                   <col width='15%'>
                   <col width='15%'>
                   ";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='7'>".conv( "Прайс лист на услуги гидроабразивной резки в руб. без НДС" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' rowspan='2'>".conv( "Толщина мм." )."</td>";
$str .= "<td class='Field AC' colspan='6'>".conv( "Металлы" )."</td>";
$str .= "</tr>";


$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>".conv( "Сталь" )."</td>";
$str .= "<td class='Field AC'>".conv( "Нержавейка" )."</td>";
$str .= "<td class='Field AC'>".conv( "Медь, латунь" )."</td>";
$str .= "<td class='Field AC'>".conv( "Алюминий" )."</td>";
$str .= "<td class='Field AC'>".conv( "Титан" )."</td>";
$str .= "<td class='Field AC'>".conv( "Актуальность" )."</td>";
$str .= "</tr>";

try
{
    $query = "  SELECT form.*, users.FIO, DATE_FORMAT( form.actualization_date, '%d.%m.%Y %H:%i') AS actualization_date 
          FROM cooperation_database_form7 form
          LEFT JOIN okb_users users ON users.ID = form.actualizator
          WHERE 1";
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
}

$classes = [ 'odd', 'even' ];
$row_count = $stmt -> rowCount();

// Multiple record
while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
  $id = $row -> id ;
  $class = $classes[ $id %2 ];

  $actualizator = conv( $row -> FIO );
  $actualization_date = $row -> actualization_date;

  $thickness = conv( $row -> thickness );
  $material1 = conv( $row -> material1 );
  $material2 = conv( $row -> material2 );
  $material3 = conv( $row -> material3 );
  $material4 = conv( $row -> material4 );
  $material5 = conv( $row -> material5 );

  $str .= "<tr class='$class' data-number='$id'>";

  $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='thickness' value='$thickness' /></td>";

  $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='material1' value='$material1' /></td>";

$str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='material2' value='$material2' /></td>";

$str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='material3' value='$material3' /></td>";
 
$str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='material4' value='$material4' /></td>";

$str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='material5' value='$material5' /></td>";

  if( $id == 1 )
    $str .= "<td class='Field AC' rowspan='$row_count'><span class='actuality_date'>$actualization_date<br>$actualizator</span></td>";
  

  $str .= "</tr>";
  
}

$colspan = 7 ;

$str .= "<tr><td class='no_border Field' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Цены указаны ориентировочно в руб/п.м при среднем качестве и сложности реза." )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Окончательная цена расчитывавется индивидуально по каждому заказу и может изменяться в зависимости от материала, сложности изделия, качества реза и объема партии." )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Минимальная стоимость заказа 1500 руб. " )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Для выполнения резки необходим чертеж, выполненный в autokad и формате DXF." )."</td>";
$str .= "</tr>";

$str .= "<tr><td class='Field AL bold' colspan=$colspan></td></tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan=$colspan>".conv( "Размер материала должен быть 20 мм с каждой стороны больше размера детали." )."</td>";
$str .= "</tr>";

$str .= "</table>";

echo $str ;