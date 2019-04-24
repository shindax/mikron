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
$table = "cooperation_database_form4";

if( in_array( $user_id, $cooperation_dep ) )
  $readonly = '';
    else
        $readonly = 'readonly';

$str =   "<br><h2>".conv( "Гальваника и защитные покрытия" )."</h2>";
$str =   "<br>";
$str .= "<table class='table tbl form4' id='$table'>";
$str .= "
                   <col width='25%'>
                   <col width='15%'>
                   <col width='20%'>
                   <col width='45%'>
                   <col width='15%'>                   
                   ";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC' colspan='5'>".conv( "Гальваника и защитные покрытия" )."</td>";
$str .= "</tr>";

$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>".conv( "Вид покрытия" )."</td>";
$str .= "<td class='Field AC'>".conv( "Ед. изм" )."</td>";
$str .= "<td class='Field AC'>".conv( "Цена в руб. без НДС" )."</td>";
$str .= "<td class='Field AC'>".conv( "Примечание" )."</td>";
$str .= "<td class='Field AC'>".conv( "Актуальность" )."</td>";
$str .= "</tr>";

$data = [];
$cover = [];
$units = [];
$actualization_date = null ;
$actualizator = null ;

try
{
    $query = "  SELECT 
                form.id AS id, form.cover AS cover, form.units AS units,
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
  $cover[ $row -> id ] = conv( $row -> cover );
  $units[ $row -> id ] = conv( $row -> units );  
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
  $data[ $row -> pid ][] = [ 'id' => $row -> id ,'note' => conv( $row -> note ) , 'price' => conv( $row -> price ) ];

// debug( $data );

foreach( $data AS $key => $value )
{
  $row_count = count( $data[ $key ] );
  $line = 1 ;
 
  foreach( $data[ $key ] AS $skey => $svalue )
  {
      $id = $svalue[ 'id' ];
      $str .= "<tr data-number='$id'>";

      if( $line == 1 )
      {
        $str .= "<td class='Field AC' rowspan='$row_count'>{$cover["$key"]}</td>";
        $str .= "<td class='Field AC' rowspan='$row_count'>{$units["$key"]}</td>";        
      }

      $str .= "<td class='Field AC'><input $readonly class='row_data' data-field='price' value='".$svalue['price']."' /></td>";      
      $str .= "<td class='Field AC'><input $readonly class='row_data' data-field='note' value='".$svalue['note']."' /></td>";      

      if( $line == 1 )
        $str .= "<td class='Field AC' rowspan='$row_count'><span class='actuality_date'>$actualization_date<br>$actualizator</span></td>";

      $line ++ ;
  }

}

$str .= "</table>";

echo $str ;