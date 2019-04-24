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
$str .= "<table class='table tbl form1' id='cooperation_database_form1'>";
$str .= "
                   <col width='25%'>
                   <col width='10%'>
                   <col width='15%'>
                   <col width='15%'>
                   <col width='15%'>
                   <col width='15%'>
                   <col width='10%'>
                   ";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='7'>".conv( "Литье" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' rowspan='2'>".conv( "Металл" )."</td>";
$str .= "<td class='Field AC' rowspan='2'>".conv( "Вид формовки" )."</td>";
$str .= "<td class='Field AC' colspan='4'>".conv( "Сложность литья" )."</td>";
$str .= "<td class='Field AC' rowspan='2'>".conv( "Актуальность" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>1</td>";
$str .= "<td class='Field AC'>2</td>";
$str .= "<td class='Field AC'>3</td>";
$str .= "<td class='Field AC'>4</td>";
$str .= "</tr>";

try
{
    $query = "  SELECT form.*, users.FIO, DATE_FORMAT( form.actualization_date, '%d.%m.%Y %H:%i') AS actualization_date 
          FROM cooperation_database_form1 form
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

// Multiple record
while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
  $id = $row -> id ;
  $class = $classes[ $id %2 ];

  $caption = conv( $row -> caption );
  $row_type = conv( $row -> row_1_type );
  $col_1 = conv( $row -> row_1_col_1 );
  $col_2 = conv( $row -> row_1_col_2 );
  $col_3 = conv( $row -> row_1_col_3 );
  $col_4 = conv( $row -> row_1_col_4 );
  $actualizator = conv( $row -> FIO );
  $actualization_date = $row -> actualization_date;

  $str .= "<tr class='$class' data-number='$id' data-row='1'>";

  if( $id == 6 )
  {   
    $str .= "<td class='Field AC' rowspan='2'><input $readonly  class='caption_data' value='$caption'/></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='type'>$row_type</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_1'>$col_1</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_2'>$col_2</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_3'>$col_3</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_4'>$col_4</textarea></td>";
  }
  else
  {   
    $str .= "<td class='Field AC' rowspan='2'><input $readonly  class='caption_data' value='$caption' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='type' value='$row_type' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_1' value='$col_1' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_2' value='$col_2' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_3' value='$col_3' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_4' value='$col_4' /></td>";
  } 
  
  if( $id == 1 )
    $str .= "<td class='Field AC' rowspan='12'><span class='actuality_date'>$actualization_date<br>$actualizator</span></td>";
  
  $str .= "</tr>";

  $row_type = conv( $row -> row_2_type );
  $col_1 = conv( $row -> row_2_col_1 );
  $col_2 = conv( $row -> row_2_col_2 );
  $col_3 = conv( $row -> row_2_col_3 );
  $col_4 = conv( $row -> row_2_col_4 );

  $str .= "<tr  class='$class' data-number='$id' data-row='2'>";

  if( $id == 6 )
  {   
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='type'>$row_type</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_1'>$col_1</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_2'>$col_2</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_3'>$col_3</textarea></td>";
    $str .= "<td class='Field AC textarea'><textarea $readonly  class='row_data' data-field='col_4'>$col_4</textarea></td>";
  }
  else
  {   
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='type' value='$row_type' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_1' value='$col_1' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_2' value='$col_2' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_3' value='$col_3' /></td>";
    $str .= "<td class='Field AC'><input $readonly  class='row_data' data-field='col_4' value='$col_4' /></td>";
  } 


  $str .= "</tr>";
  
}

$str .= "<tr>";
$str .= "<td class='no_border Field' colspan='7'></td>";
$str .= "</tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan='7'>".conv( "Сложность литья зависит от геометрии отливки, группы точночти, чистоты поверхности" )."</td>";
$str .= "</tr>";

$str .= "<tr>";
$str .= "<td class='Field AL bold' colspan='7'>".conv( "Цена может меняться" )."</td>";
$str .= "</tr>";

$str .= "</table>";

echo $str;