<link rel="stylesheet" href="/project/reports/materials/css/jquery-ui.min.css">
<link rel="stylesheet" href="/project/reports/materials/css/theme.css">
<link rel="stylesheet" href="/project/reports/materials/css/bootstrap.min.css">
<link rel="stylesheet" href="/project/reports/materials/css/style.css">

<script type="text/javascript" src="/uses/jquery-ui.js"></script>
<script type="text/javascript" src="/project/reports/materials/js/adjust_calendar.js"></script>
<script type="text/javascript" src="/project/reports/materials/js/materials.js?ver2"></script>

 <?php
require_once( "db.php" );

global $pdo, $user;

$user_id = $user['ID'];

// $user_id = 15 ;

$disabled = ( $user_id == 15 ) ? '' : 'disabled' ;

function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
//  return iconv("Windows-1251", "UTF-8", $str );  
//  return $str ;
}

$id_arr = [];

try
{
    $query = "
      SELECT mat_price.id_mat id, mat.OBOZ name 
      FROM `okb_db_material_price` mat_price 
      LEFT JOIN `okb_db_mat` mat ON mat.ID = mat_price.id_mat 
      WHERE 1 GROUP BY mat_price.id_mat
    ";
    $stmt = $pdo->prepare( $query  );
    $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  
  
while ( $row = $stmt -> fetchObject() )
    $id_arr [] = [ 'id' => $row -> id , 'name' => $row -> name ];

$str = "<h2>".conv("Стоимость сталей")."&nbsp<a target='_blank' href='index.php?do=show&formid=73'>".conv("БД сортамента")."</a></h2><div id='main_div'><div id='accordion' class='hidden'>";


foreach( $id_arr AS $el )
{
  $name = conv( $el['name'] );
  $id = conv( $el['id'] );

  try
  {
      $query = "
        SELECT mat_price.id_sort id_sort 
        FROM `okb_db_material_price` mat_price 
        LEFT JOIN `okb_db_sort` sort ON sort.ID = mat_price.id_sort 
        WHERE  mat_price.id_mat = $id ";
      $stmt = $pdo->prepare( $query  );
      $stmt->execute();
  }
    catch (PDOException $e) 
      {
        die("Can't get data: " . $e->getMessage());
      }  

  $ids = [];

  while ( $row = $stmt -> fetchObject() )
    if( $row -> id_sort )
      $ids[] = $row -> id_sort ;

  $ids = join ( ',', $ids );

$str .= "<h3>$name</h3>
        <div class = 'my_pan'>
        <div class='col text-right'>
        <button class='btn btn-small btn-primary' type='button' data-id='$id' $disabled>".conv('Добавить сортамент')."</button></div>

        <table class='rdtbl tbl '>
        <tr class='first' data-ids = '$ids'>
        <td width='30%'>".conv("Сортамент")."</td>
        <td width='20%'>".conv("Стоимость, руб с НДС")."</td>
        <td width='30%'>".conv("Примечание")."</td>
        <td width='10%'>".conv("Дата<br>актуализации")."</td>
        <td width='4%'></td>        
        </tr>";


try
{
    $query = "
      SELECT mat_price.id id, sort.OBOZ sort_name , mat_price.price price, mat_price.id id, mat_price.note note,  
      DATE_FORMAT( mat_price.actuality, '%d.%m.%Y') AS date,
      DATE_FORMAT( mat_price.timestamp, '%d.%m.%Y') AS timestamp 
      FROM `okb_db_material_price` mat_price 
      LEFT JOIN `okb_db_sort` sort ON sort.ID = mat_price.id_sort 
      WHERE  mat_price.id_mat = $id ";
    $stmt = $pdo->prepare( $query  );
    $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  
  
while ($row = $stmt -> fetchObject() )
{
    if( strlen( $row -> sort_name ) )
      $sort_name = conv( $row -> sort_name ) ;
        else
          $sort_name = "<input class='sort_select' />";

    $cur_val = $row -> price;
    $price = number_format( $cur_val, 2, ',', ' ' );
    $note = conv( $row -> note );
    $id = $row -> id ;
    $date = $row -> date ;
    $id_sort = $row -> id_sort ;    
    $id = $row -> id ;

    if( $date == '00.00.0000')
      $date = ''; 

    $img = "<img src='uses/del_dis.png' />";

    if( $user_id == 1 || $user_id == 15 )
    {
        $img = "<img class='del_sort' src='uses/del.png' />";
        $can_select = '' ;                    
    }

    $str .="
        <tr data-id='$id'>
        <td class='Field'>$sort_name</td>
        <td class='Field AC'><input class='price_input' data-cur-val='$cur_val' data-id='$id' data-field='price' value='$price' $disabled /></td>
        <td class='Field'><input class='note_input' data-id='$id' data-field='note' value='$note' $disabled/></td>
        <td class='Field'><input class='actuality_input' data-id='$id' data-field='actuality' value='$date' $disabled/></td>
        <td class='Field AC'>$img</td>        
        </tr>";
}

$str .= "</table></div>"; 
}    
    
$str .= "</div></div>";

echo $str ;

?>


