<?php
header('Content-Type: text/html');
ini_set('display_errors', 'off');

require_once('CommonFunctions.php');

function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
}

$date = $_POST['date'];
$date = explode('-', $date );
$date = join( $date );

$str = '';
$total = 0 ;

for( $i = 1 ; $i <= 3 ; $i ++ )
{
    $res_arr = GetDateProdShift( $date, $i );
    $cnt = count( $res_arr );

$total_hour = 0 ;


if( $cnt  )    
{
$cnt_suff = GetSuffix( $cnt );
$total += $cnt ;
$str .= "
<tr class='first'>
<td colspan='3' class='field AL'>
<img data-state='0' data-id='$i' src='/uses/collapse.png' title='Свернуть' class='expang_img' />".conv("Смена № " )."$i. $cnt $cnt_suff</td>
</tr>
<tr class='row_$i subhead hidden'>
<td class='field AC' width='4%'>".conv("№" )."</td>
<td class='field AL'>".conv("ФИО" )."</td>
<td class='field AL'>".conv("Часов" )."</td>
</tr>";


    $line = 1 ;
    foreach( $res_arr AS $row )
	{
//		$id = " ID : ".$row['res_id'];
      $name = conv( $row['name'] );
      $hour = $row['hour'];
      $total_hour += $hour ;
        $str .= "<tr class='row_$i people hidden'>
                    <td class='field AC'>".$line++." / $i</td>
                     <td class='field'>$name</td>
					<td class='field'>$hour </td>
                    </tr>";
	}
	
	 $str .= "<tr class='total row_$i people hidden'><td colspan='2' class='field AL'>".conv("Смена № ".$i." Итого часов : ")."</td><td>$total_hour</td></tr>";
 }
 else
$str .= conv("<tr class='first'><td colspan='3' class='field AL'>Смена № $i. Нет данных</td></tr>");
}

$str .= "
<tr class='total'>
<td colspan='3' class='field AL'>".conv("Итого" )." : <span id='total_count'>$total</span> ".GetSuffix( $total )."</td>
</tr>";


echo $str ;

?>