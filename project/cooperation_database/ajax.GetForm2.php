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

$str =   "<br><h2>".conv( "Термообработка" )."</h2>";
$str =   "<br>";
$str .= "<table class='table tbl form2' id='cooperation_database_form2'>";
$str .= "
                   <col width='20%'>
                   <col width='10%'>
                   <col width='5%'>
                   <col width='50%'>
                   <col width='10%'>                   
                   ";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='5'>".conv( "Термообработка" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>".conv( "Материал" )."</td>";
$str .= "<td class='Field AC'>".conv( "Вид ТО" )."</td>";
$str .= "<td class='Field AC'>".conv( "Цена в руб. без НДС" )."</td>";
$str .= "<td class='Field AC'>".conv( "Примечание" )."</td>";
$str .= "<td class='Field AC'>".conv( "Актуальность" )."</td>";
$str .= "</tr>";

try
{
    $query = "  SELECT form.*, users.FIO, DATE_FORMAT( form.actualization_date, '%d.%m.%Y %H:%i') AS actualization_date 
          FROM cooperation_database_form2 form
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

  $material = conv( $row -> material );
  $ts_type = conv( $row -> ts_type );
  $price = conv( $row -> price );
  $note = conv( $row -> note );
  $actualizator = conv( $row -> FIO );
  $actualization_date = $row -> actualization_date;

  $str .= "<tr class='$class' data-number='$id'>";

  if( $id == 1 )
    $str .= "<td class='Field AC' rowspan='$row_count'><input $readonly  class='row_data' data-field='material' value='$material' /></td>";

  $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='ts_type' value='$ts_type' /></td>";
  $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='price' value='$price' /></td>";
  $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='note'>$note</textarea></td>";
 
  if( $id == 1 )
    $str .= "<td class='Field AC' rowspan='$row_count'><span class='actuality_date'>$actualization_date<br>$actualizator</span></td>";
  

  $str .= "</tr>";
  
}

$str .= "<tr>";
$str .= "<td class='no_border Field' colspan='5'></td>";
$str .= "</tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan='5'>".conv( "ВНИМАНИЕ" )."</td>";
$str .= "</tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan='5'>".conv( "Закалка заготовок из высоколегированных марок сталей, например 14Х12В2МФ, 18Х11МНФБ, 10X23Х28, 08Х20Н14С2, 20Х25Н20С2 происходит многоступенчато и расчитывается индивидуально." )."</td>";
$str .= "</tr>";


$str .= "</table>";

echo $str ;