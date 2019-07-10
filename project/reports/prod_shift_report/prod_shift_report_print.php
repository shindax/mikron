<link rel="stylesheet" href="/project/reports/prod_shift_report/css/style.css" media="screen" type="text/css" />

<script type="text/javascript" src="/project/reports/prod_shift_report/js/prod_shift_report.js"></script>
<center>
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

for( $i = 1 ; $i <= 3 ; $i ++ )
{
    $res_arr = GetDateProdShift( $date, $i );
    $cnt = count( $res_arr );
	$total_hour = 0 ;
	
	
$title = "Отчет о перечне работающего персонала за $day.$month.$year. Смена $i" ;
$str = "<div class='pagebreak'><h4>$title</h4><table id='print_tbl_$i' class='tbl print_table'>";

if( $cnt  )    
{
$cnt_suff = GetSuffix( $cnt );
$str .= "
<tr class='first print_first'>
<td colspan='6' class='field AL'>
Смена № $i. $cnt $cnt_suff</td>
</tr>";

    $line = 1 ;
    foreach( $res_arr AS $row )
	{
		$hour = $row['hour'];
		$total_hour += $hour ;
        $str .= "<tr class='people_print_row'>
                    <td width='5%' class='field AC'>".$line++." / $i</td>
                    <td width='20%' class='field AL'>".iconv("UTF-8", "Windows-1251", $row['name'] )."</td>
                    <td width='4%' class='field AC'>$hour</td>					
                    <td width='4%' class='field AC'></td>										
                    <td width='20%'class='field AL'></td>
                    <td class='field AL'></td>
                    </tr>";
	}
$str .= "
  <tr class='first print_first'><td colspan='2' class='field AL'>Смена № $i. Итого часов : </td><td>$total_hour</td><td></td><td></td><td></td></tr>";
 }
 else
$str .= "
  <tr class='first print_first'><td colspan='6' class='field AL'>Смена № $i. Нет данных</td></tr>";


$str .= "
<tr class='first print_first'>
<td colspan='6' class='field AL print_field'>Смена № $i. $cnt $cnt_suff</td>
</tr></table></div>
";

echo $str ;
}

?>
</div>    
</center>
