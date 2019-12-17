<link rel="stylesheet" href="/project/reports/prod_shift_report/css/style.css" media="screen" type="text/css" />

<script type="text/javascript" src="/project/reports/prod_shift_report/js/prod_shift_report.js"></script>
<center>

<style>
strong
{
  font-weight: bold;
}
.print_tbl_div
{
  page-break-after: always !IMPORTANT;  
  width: 900px;
  float: none ;
  text-align: left;
}


#print_tbl_1,#print_tbl_2,#print_tbl_3
{
  width : 100%;
  table-layout: fixed;
}

#print_tbl_1 td.AC, #print_tbl_2 td.AC, #print_tbl_3 td.AC, .tbl td.AC
{
  text-align: center;
  vertical-align: middle !IMPORTANT;
}

td.AL
{
  text-align: left;
  vertical-align: middle;
}

td.AR
{
  text-align: right;
  vertical-align: middle;
}

.subhead
{
  background: #eee !IMPORTANT;
}

.department
{
  background: #ccc !IMPORTANT;
  vertical-align: middle !IMPORTANT;  
}

* { -webkit-print-color-adjust: exact; } 

.shift_total
{
  font-size: 15px;
  font-weight: bold;
}

span.res_name
{
  padding-left:5px;
}


</style>

<div id='Printed' class='a4p'>    

<?php
//error_reporting( E_ALL );
error_reporting( 0 );
ini_set('display_errors', 'off');
require_once('CommonFunctions.php');

$date = $_GET['p0'];
$year = substr( $date, 0, 4 );
$month = substr( $date, 4, 2 );
$day = substr( $date, 6, 2 );

function conv( $str )
{
  return   iconv("UTF-8", "Windows-1251", $str );
}

function cmp_by_master($a, $b)
{
    if ( $a['master_name'] == $b['master_name'] ) 
    {
      if ( $a['name'] == $b['name'] ) 
        return 0;
      
      return ( $a['name'] < $b['name'] ) ? -1 : 1;
    }
    return ( $a['master_name'] < $b['master_name'] ) ? -1 : 1;
}

function cmp_by_dep($a, $b)
{
    if ( $a['dep_name'] == $b['dep_name'] ) 
    {
      if ( $a['name'] == $b['name'] ) 
        return 0;
      
      return ( $a['name'] < $b['name'] ) ? -1 : 1;
    }
    return ( $a['dep_name'] < $b['dep_name'] ) ? -1 : 1;
}

$str = "";

for( $i = 1 ; $i <= 3 ; $i ++ )
{
    $res_arr = GetDateProdShift( $date, $i );
    $cnt = count( $res_arr );
    usort( $res_arr , "cmp_by_master");    
	  $total_hour = 0 ;
	
$str .= "<div class='print_tbl_div'><h4>".conv( "Отчет о перечне работающего персонала за $day.$month.$year. Смена $i" )."</h4>
        <table id='print_tbl_$i' class='tbl print_table'>
        <col width='4%' />
        <col width='5%' />
        <col width='15%' />
        <col width='10%' />
        <col width='10%' />        
        <col width='54%' />        
        ";

if( $cnt )
{
    $cnt_suff = conv( GetSuffix( $cnt ) );
    $str .= conv( "
    <tr class='department'>
      <td colspan='6' class='field AC'>Смена № $i. $cnt $cnt_suff</td>
    </tr>" );

    $dep = [];

    // foreach( $res_arr AS $value )
    // {
    //   $dep[$value['dep_id']]['name'] = $value['dep_name'] ;
      
    //   if( isset( $dep[$value['dep_id']]['hour'] ) )
    //     $dep[$value['dep_id']]['hour'] += $value['hour'] ;
    //       else
    //         $dep[$value['dep_id']]['hour'] = $value['hour'] ;
    //   $dep[$value['dep_id']]['childs'][] = $value ;
    // }

    foreach( $res_arr AS $value )
    {
      $dep[$value['master_res_id']]['name'] = $value['master_name'] ;
      
      if( isset( $dep[$value['master_res_id']]['hour'] ) )
        $dep[$value['master_res_id']]['hour'] += $value['hour'] ;
          else
            $dep[$value['master_res_id']]['hour'] = $value['hour'] ;
     
      // unset( $value['master_res_id'] );
      // unset( $value['master_name'] );
      // unset( $value['dep_id'] );
      // unset( $value['dep_name'] );

      $dep[$value['master_res_id']]['childs'][] = $value ;
    }

  $str .= "<tr class='row_$i department'>
              <td colspan='6' class='field AC department'><span class='shift_total'>".conv( "Смена $i, сотрудников $cnt")."</span></td>
           </tr>";


  $total_line = 1 ;
  foreach( $dep AS $dkey => $value )
	{
	    $line = 1 ;
      $name = $value['name'];
      
      if( !strlen( $name ) )
        $name = conv( "Неполные данные");

      $hour = $value['hour'];

      $dep_cnt =  count( $value['childs'] );
      $dep_suff = conv( GetSuffix( $dep_cnt ) );

      $str .= "<tr class='row_$i department' data-dep_id='$dkey'>
                  <td colspan='6' class='field AC department'>$name $dep_cnt $dep_suff. ".conv("Часов всего : ")."$hour </td>
               </tr>";

      $str .= "<tr class='row_$i subhead'>
              <td class='field AC' width='4%'>##</td>      
              <td class='field AC' width='4%'>#</td>
              <td class='field AC'>".conv("ФИО" )."</td>
              <td class='field AC'>".conv("План" )."</td>
              <td class='field AC'>".conv("Факт" )."</td>              
              <td class='field AC'>".conv("Примечания" )."</td>
              ";

    foreach( $value['childs'] AS $row )
    {
    	$hour = $row['hour'];
  		$total_hour += $hour ;
      $name = conv( $row['name'] );
      $dep_name = $row['dep_name']; 

      $str .= "<tr class='people_print_row'>
                      <td class='field AC'>".$total_line++."</td>      
                      <td class='field AC'>".$line++." / $i</td>
                      <td class='field AL'><span class='res_name'>$name</span></td>
                      <td class='field AC'>$hour</td>
                      <td class='field AC'></td>
                      <td class='field AC'></td>
                      </tr>";
    }
	}

$str .= conv( "
  <tr class='subhead'>
    <td colspan='3' class='field AC'>
        <span class='shift_total'>Cотрудников : $cnt</span>
    </td>
    <td colspan='2' class='field AC'>
      <span class='shift_total'>Часов $total_hour</span>
    </td>
    <td class='field AC'>
      <span class='shift_total'>Смена № $i</span>
    </td>  
  </tr>" );
 }
 else
$str .= conv( "
  <tr class='department'>
  <td colspan='6' class='field AC'><span class='shift_total'>Смена № $i. Нет данных</span></td>
  </tr>" );

  $str .= "</table></div>";

}

echo $str ;

?>
</div>    
</center>
