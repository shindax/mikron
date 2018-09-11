<link rel="stylesheet" href="/project/reports/operational_complexity_report/css/style.css">

<center>
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
require_once("class.OperationalComplexityReport.php");

global $mysqli;

$order_id = $_GET['p0'] ;
$db_prefix = $_GET['p1'] ;

error_reporting( 0 );

function cmp($aa, $bb) 
{
    $a = $aa['tid'];
    $b = $bb['tid'];
    
    if ($a == $b) {
        return 0;
    }
    return ( $a < $b ) ? -1 : 1;
}


function mysortfunc( $a , $b ) 
{

    if ($a['tid']<$b['tid']) { return -1; }

    if ($a['tid']>$b['tid']) { return 1; }

    if ($a['tid']==$b['tid']) 
    {
        if ($a['name']==$b['name']) 
          return 0; 
        
        return ( $a['name'] < $b['name'] ) ? -1 : 1 ; 
    }
}

function PurgeClean( $arr ) 
{
  $newarr = array();

  foreach( $arr AS $key => $val )
  {
    if( $val['op_id'] == 93 && $arr[ $key - 1 ]['op_id'] == 8 )
        continue ;
        
    if( $val['op_id'] == 8 && $arr[ $key + 1 ]['op_id'] == 93 )
    {
       $fact = $arr[ $key + 1 ]['fact'];
       $val['fact'] += $fact ;
       $arr[ $key + 1 ]['op_id'] = 0 ;
       $newarr[] = $val ;
    }
    else
        $newarr[] = $val ;
  }
  
  return $newarr ;
}


        $table = $db_prefix."db_zak";
        
        $query = "SELECT TID, NAME, DSE_NAME FROM `$table` WHERE ID=$order_id" ;
        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$this -> mysqli->error); 
        
        if( $result -> num_rows )
         {
            $row = $result -> fetch_object() ;
            $order_name = $row -> NAME ;
            $order_tid = $row -> TID ;
            $dse_name = $row -> DSE_NAME ;
         }

$dse_name = iconv( "Windows-1251", "UTF-8", $dse_name );

$tid = array( "","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
$today = date("d.m.Y", time());

$report = new OperationalComplexityReport( $mysqli, $db_prefix, $order_id );
$arr = $report -> GetOperationsArr();

usort( $arr, mysortfunc );
$arr = PurgeClean( $arr ) ;

$str  = "<div class='print_head'><h1 class='print_h1' >Заказ ".($tid [ $order_tid ] )." $order_name</h1>";
$str .= "<h2 class = 'print_h2'>$dse_name</h2>";
$str .= "<h2 class = 'print_h2'>Отчет по операционной трудоёмкости</h2></div>";
$str .= "<p class='print_span'>Отчет от $today</p>";
$num = 1 ;

$nodata = iconv( "UTF-8", "Windows-1251", "Нет данных" );

$tip_oper = array(" ","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");

if( count( $arr ) )
{
  $str .=  "<table class='tbl print_tbl'>
        
        <tr class='first'>
        <td width='5%'>№</td><td>Наименование операции</td><td width='10%'>НЧ</td>
        </tr>";
  
  foreach( $arr AS $key => $val )
  {
    $tid = $val['tid'];
    $op_name = $tip_oper[ $tid ]." - ".$val['name'];
    $fact = $val['fact'];

    $str .= "<tr>
             <td class='Field AC'>$num</td>
             <td class='Field AL'>$op_name</td>
             <td class='Field AC'>$fact</td>
             </tr>";
    $num ++ ;
  }
  $str .= "</table>";
}
 else
  $str .= "<tr><td colspan='3' class='Field AC'>$nodata</td></tr>";

echo iconv( "UTF-8", "Windows-1251", $str );
//echo $str ;
?>
</center>