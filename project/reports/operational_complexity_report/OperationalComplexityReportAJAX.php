<?php
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");
require_once("class.OperationalComplexityReport.php");
global $mysqli;

$order_id = $_POST['id'] ;
$db_prefix = $_POST['db_prefix'] ;

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



$report = new OperationalComplexityReport( $mysqli, $db_prefix, $order_id );
$arr = $report -> GetOperationsArr();

usort( $arr, mysortfunc );
$arr = PurgeClean( $arr ) ;

$str = '';
$num = 1 ;

//$nodata = iconv( "UTF-8", "Windows-1251", "Нет данных" );
$nodata = "Нет данных";

$tip_oper = array(" ","Заготовка","Сборка-сварка","Механообработка","Сборка","Термообработка","Упаковка","Окраска","Прочее");

if( count( $arr ) )
foreach( $arr AS $key => $val )
{
  $tid = $val['tid'];
  $op_name = $tip_oper[ $tid ]." - ".$val['name'];
  $fact = $val['fact'];

  $str .= "<tr class = 'row'><td class='Field AC'>$num</td><td class='Field AL'>$op_name</td><td class='Field AC'>$fact</td></tr>";
  $num ++ ;
}
 else
  $str .= "<tr class = 'row'><td id='nodata' colspan='3' class='Field AC'>$nodata</td></tr>";

echo iconv( "UTF-8", "Windows-1251", $str );
//echo $str ;
?>