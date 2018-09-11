<link rel='stylesheet' href='project/act_cal/css/activeCalendar.css' type='text/css'>
<script type="text/javascript" src="project/act_cal/js/activeCalendar.js"></script>
<script type="text/javascript" src="project/act_cal/js/jquery-latest.js"></script>
<script type="text/javascript" src="project/act_cal/js/fix_head_table.js"></script>

<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("CommonFunctions.php");
global $user ;

echo '<img id="loadImg" src="project/act_cal/img/loading_2.gif" />';

$now = getdate();

$month_sel = GetMonthList( $now['mon'] );
$year_sel = GetYearList( $now['year']);

$month_day_count = GetMonthDayCount( $now['mon'],  $now['year'] );

$str = " <div id='headerMain'><div id='header'>";
$str .= "$month_sel&nbsp;&nbsp;$year_sel <span id='capt'>Регистрация событий за ".GetMonthName( $now['mon'] )." ".$now['year']."г.</span><br>";

$str .= "<div id='capt_first_col'><table width='100%' class='rdtbl tbl capt_table'>
         <tr class='first capt_table'>
         <td class='field AC capt_table'>Событие</td>
         </tr>
         </table></div>";

$str .= "<div id='capt_second_col'>
         <table width='100%' class='rdtbl tbl capt_table' id='capt_second_col_table'>
         <tr class='first' id='capt_second_col_row'>".addCell($month_day_count)."</tr>
         </table></div>";

$str .= "<div id='capt_third_col'><table width='100%' class='rdtbl tbl capt_table'>
         <tr class='first'>
         <td class='field AC capt_table'>Итого</td>
         </tr>
         </table></div>";

$str .= "</div></div>";

$str  .= "<div id='main_div'>";

$str .= "<div id='table_div'>";
$str .= MakeEventsTable( $now['mon'], $now['year'] );
$str .= "</div></div>"; 

$str .= "<div id='pos_det_div'><div id='det_div' data-month='0' data-year='1900' data-day='0' data-ev_id='0'>";
$str .= "</div></div>"; 

$str .= "</div>"; //echo "<div id='main_div'>";

echo $str ;

//echo $_SERVER['SERVER_NAME'];

?>
