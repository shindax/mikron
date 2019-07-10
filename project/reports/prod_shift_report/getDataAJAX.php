<?php
header('Content-Type: text/html');
ini_set('display_errors', 'off');

require_once('CommonFunctions.php');

function conv( $str )
{
  return $str; // iconv("UTF-8", "Windows-1251", $str );
}

function cmp_by_master_name($a, $b)
{
    if ( $a['master_name'] == $b['master_name'] ) 
    {
      if ( $a['name'] == $b['name'] ) 
        return 0;
      
      return ( $a['name'] < $b['name'] ) ? -1 : 1;
    }
    return ( $a['master_name'] < $b['master_name'] ) ? -1 : 1;
}

function cmp_by_name($a, $b)
{
    if ( $a['dep_name'] == $b['dep_name'] ) 
    {
      if ( $a['name'] == $b['name'] ) 
        return 0;
      
      return ( $a['name'] < $b['name'] ) ? -1 : 1;
    }
    return ( $a['dep_name'] < $b['dep_name'] ) ? -1 : 1;
}

$date = $_POST['date'];
$date = explode('-', $date );
$date = join( $date );

$str = "    <table id='prod_shift_report' class='tbl'>
            <col width='4%'>
            <col width='4%'>        
            <col width='10%'>
            <col width='50%'>
            ";
$total = 0 ;

for( $i = 1 ; $i <= 3 ; $i ++ )
{
    $res_arr = GetDateProdShift( $date, $i );
    $cnt = count( $res_arr );
    usort( $res_arr , "cmp_by_master_name");

    $total_hour = 0 ;

if( $cnt  )    
{
  $cnt_suff = GetSuffix( $cnt );
  $total += $cnt ;
  $str .= "
  <tr class='first'>
    <td class='field AL' colspan='4'>
      <img data-state='0' data-id='$i' src='/uses/collapse.png' title='Свернуть' class='expang_img' />".conv("Смена № " )."$i. $cnt $cnt_suff
    </td>
  </tr>";

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

    // debug( $dep );

    $total_line = 1 ;

    foreach( $dep AS $dkey => $value )
    {
      $line = 1 ;
      $name = $value['name'];

      if( !strlen( $name ) )
              $name = conv( "Неполные данные");
      
      $hour = $value['hour'];

      $dep_cnt =  count( $value['childs'] );
      $dep_suff = GetSuffix( $dep_cnt ) ;

      $str .= "<tr class='row_$i department hidden' data-dep_id='$dkey'>
                  <td colspan='3' class='field department'>$name $dep_cnt $dep_suff</td>
                  <td class='field'>".conv("Часов всего : ")."$hour </td>
               </tr>";

      $str .= "<tr class='row_$i subhead hidden'>
              <td class='field AC'>".conv("#" )."</td>      
              <td class='field AC'>".conv("#" )."</td>
              <td class='field AL'>".conv("ФИО" )."</td>
              <td class='field AL'>".conv("Часов" )."</td>
              ";

      foreach( $value['childs'] AS $row )
    	{
          $name = conv( $row['name'] );
          $hour = $row['hour'];
          $dep_name = conv( $row['dep_name'] );
          $res_id = conv( $row['res_id'] );
          $total_hour += $hour ;
            $str .= "<tr class='row_$i people hidden'>
                        <td class='field AC'>".$total_line++."</td>            
                        <td class='field AC'>".$line++." / $i</td>
                        <td class='field' data-res_id='$res_id'>$name</td>
    					          <td class='field'>$hour </td>
                      </tr>";
    	}
    }
  	
  	 $str .= "<tr class='total row_$i people hidden'>
        <td class='field AR' colspan='4'><span class='shift_total'>".conv("Смена № $i. Итого : сотрудников $cnt. часов ")."$total_hour</span></td>
      </tr>";
 }
 else
$str .= conv("<tr class='first'>
  <td class='field AL' colspan='4'>Смена № $i. Нет данных</td>
  </tr>");
}

$str .= "
<tr class='total'>
<td class='field AL' colspan='4'>".conv("Итого" )." : <span id='total_count'>$total</span> ".GetSuffix( $total )."</td>
</tr>";
$str .= "</table>";

echo  iconv("UTF-8", "Windows-1251", $str );
// echo $str ;

?>