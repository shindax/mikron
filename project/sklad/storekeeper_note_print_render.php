<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/print.css" media="print">

<style>
  @media print
  {

  .container
  {
    margin-left: 20px;
  }

   .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
    float: left;
  }
  .col-sm-12 {
    width: 100%;
  }
  .col-sm-11 {
    width: 91.66666667%;
  }
  .col-sm-10 {
    width: 83.33333333%;
  }
  .col-sm-9 {
    width: 75%;
  }
  .col-sm-8 {
    width: 66.66666667%;
  }
  .col-sm-7 {
    width: 58.33333333%;
  }
  .col-sm-6 {
    width: 50%;
  }
  .col-sm-5 {
    width: 41.66666667%;
  }
  .col-sm-4 {
    width: 16%;
  }
  .col-sm-3 {
    width: 25%;
  }
  .col-sm-2 {
    width: 10%;
  }
  .col-sm-1 {
    width: 8.33333333%;
  }

  .sign
  {
    padding-bottom : 5px !important;
  }

  .sign div span
  {
    font-size:14px !important;
  }

  .col-sm-24 span
  {
    font-size:12px !important;
  }

  .sign span
  {
    margin-top : -30px !important;
  }

  table 
  {
    margin:20px 0 20px 0 !important;
    width: 95% !important;
    border-collapse: collapse !important;
  }

  td, th
  {
    padding: 3px !important;
    border: 1px solid black !important; 
  }


  table
  {
    display : none ;
  }

  table.table
  {
    display : block ;
  }
}

td.AC
{
  vertical-align: middle;
  text-align: center;
}
table
{
  width: 100;
  table-layout: fixed;
}


</style>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( "functions.php" );

global $pdo;

if ( isset($_GET["p0"]) )
  $list = $_GET["p0"];
$data = [];

$subquery = "";
$pattern = "";

try
{
  $query =
    "
      SELECT *
      FROM okb_db_warehouse_reserve AS res
      WHERE res.id IN( $list )" ;

  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
 die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
}
while( $row = $stmt->fetch(PDO::FETCH_OBJ ))
  if( $row -> id_zakdet == 0 )
  {
    $pattern = $row->pattern;
    $subquery .= " OR res.pattern LIKE '%$pattern%'";    
  }

try
{
  $query =
    "
      SELECT 
        res.id AS rec_id,
        res.id_zakdet AS id_zakdet,
        res.pattern AS pattern,
        res.operation_id AS operation_id,
        oper.NAME as operation_name,
        zakdet.NAME AS dse_name,
        zakdet.OBOZ AS dse_draw,
        zak.NAME AS zak_name,
        zak.ID AS zak_id,
        zak_type.description AS zak_type
      FROM okb_db_warehouse_reserve AS res
      LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.ID = res.id_zakdet
      LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
      LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
      LEFT JOIN okb_db_oper AS oper ON oper.id = res.operation_id
      WHERE res.id IN( $list ) $subquery" ;

  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
 die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
}

while( $row = $stmt->fetch(PDO::FETCH_ASSOC ))
  $data[ $row['rec_id'] ] = $row;

foreach ( $data AS $key => $val ) 
{
  $storage_place = [];
  $location = getLocationOfDSEAtWarehouse( $val['id_zakdet'], $val['operation_id'], 0, $val['pattern'] );
  
  $storage_place = array_shift( array_column( $location, 'storage_place' ) );

  $count = 0 ;
  foreach ( $storage_place AS $lval ) 
      $count += $lval['count'] ;

  $data[ $key ][ 'count' ] = $count;
  $data[ $key ][ 'storage_place' ] = $storage_place;
}

foreach ( $data AS $key => $val ) 
  if( $val['count'] == 0 )
    unset( $data[ $key ] );

$wh_struct = get_warehouse_structure();
$wh = $wh_struct["wh"];
$cells = $wh_struct["cells"];
$tiers = $wh_struct["tiers"];

$str = "<div class='container'>";
$str .= "<div class='col-xl-12'>";
$str .= "<table class='table'>";
$str .= "<col width='10%'>";
$str .= "<col width='5%'>";
$str .= "<col width='10%'>";
$str .= "<col width='10%'>";
$str .= "<col width='4%'>";
$str .= "<col width='10%'>";
$str .= "<col width='4%'>";
$str .= "<col width='4%'>";
$str .= "<col width='4%'>";
$str .= "<col width='4%'>";
$str .= "<col width='4%'>";

$str .= "<tr>";
$str .= "<td class='field AC'>".conv("ДСЕ")."</td>";
$str .= "<td class='field AC'>".conv("Заказ")."</td>";
$str .= "<td class='field AC'>".conv("Чертеж")."</td>";
$str .= "<td class='field AC'>".conv("Операция")."</td>";
$str .= "<td class='field AC'>".conv("Кол. всего")."</td>";
$str .= "<td class='field AC'>".conv("Склад")."</td>";
$str .= "<td class='field AC'>".conv("Ячейка")."</td>";
$str .= "<td class='field AC'>".conv("Ярус")."</td>";
$str .= "<td class='field AC'>".conv("Кол.")."</td>";
$str .= "<td class='field AC'>".conv("Прим")."</td>";
$str .= "</tr>";

foreach ( $data as $key => $val ) 
{
  $storage_place_count = count( $val['storage_place'] );
  $str .= "<tr>";
  $str .= "<td class='field AC' rowspan='$storage_place_count'>".( strlen( $val['dse_name'] ) ? conv( $val['dse_name'] ) : conv( $val['pattern'] ) )."</td>";
  $str .= "<td class='field AC' rowspan='$storage_place_count'>".conv( $val['zak_type'] )." ".conv( $val['zak_name'] )."</td>";
  $str .= "<td class='field AC' rowspan='$storage_place_count'>".conv( $val['dse_draw'] )."</td>";
  $str .= "<td class='field AC' rowspan='$storage_place_count'>".conv( $val['operation_name'] )."</td>";
  $str .= "<td class='field AC' rowspan='$storage_place_count'>{$val['count']}</td>";

  $pass = 1 ;
  
  foreach ( $val['storage_place'] as $skey => $sval ) 
  {
    if( $pass != 1 )
      $str .= "<tr>";
     
    $str .= "<td class='field AC'>".$wh[$sval['wh']]."</td>";
    $str .= "<td class='field AC'>".$cells[$sval['cell']]."</td>";
    $str .= "<td class='field AC'>".( $tiers[$sval['tier']] == 0 ? conv("Пол") : $tiers[$sval['tier']])."</td>";
    $str .= "<td class='field AC'>".$sval['count']."</td>";
    $str .= "<td class='field AC'></td>";

    if( $pass == 1 )
      $str .= "</tr>";

    $pass ++;
  }

  $str .= "</tr>";
}

$str .= "</table>";
$str .= "</div>";
$str .= "</div>";

echo $str;

// debug( $data, true );

