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

$str =   "<br><h2>".conv( "Гибка металла" )."</h2>";
$str =   "<br>";
$str .= "<table class='table tbl form3' id='cooperation_database_form3'>";
$str .= "
                   <col width='15%'>
                   <col width='15%'>
                   <col width='15%'>
                   <col width='15%'>
                   ";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='4'>".conv( "Гибка металла" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>".conv( "Вид гибки" )."</td>";
$str .= "<td class='Field AC'>".conv( "Цена в руб. без НДС" )."</td>";
$str .= "<td class='Field AC'>".conv( "Примечание" )."</td>";
$str .= "<td class='Field AC'>".conv( "Актуальность" )."</td>";
$str .= "</tr>";

try
{
    $query = "  SELECT form.*, users.FIO, DATE_FORMAT( form.actualization_date, '%d.%m.%Y %H:%i') AS actualization_date 
          FROM cooperation_database_form3 form
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

  $caption = conv( $row -> caption );
  $price = conv( $row -> price );
  $note = conv( $row -> note );
  $actualizator = conv( $row -> FIO );
  $actualization_date = $row -> actualization_date;

  $str .= "<tr class='$class' data-number='$id'>";

  $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='caption' value='$caption' /></td>";
  $str .= "<td class='Field AC'><textarea $readonly  class='row_data' data-field='price'>$price</textarea></td>";
  $str .= "<td class='Field AC'><textarea $readonly  class='row_data' data-field='note'>$note</textarea></td>";
 
  if( $id == 1 )
    $str .= "<td class='Field AC' rowspan='$row_count'><span class='actuality_date'>$actualization_date<br>$actualizator</span></td>";
  

  $str .= "</tr>";
  
}

  $str .= "</table>";

echo $str ;