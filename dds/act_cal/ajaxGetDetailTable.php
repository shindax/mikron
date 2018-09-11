<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");
require_once("CommonFunctions.php");

$month  = $_POST['month'];
$year   = $_POST['year'];
$day    = $_POST['day'];
$event_id = $_POST['ev_id'];
$event_name = iconv("Windows-1251", "UTF-8", GetEventName( $event_id ) );
$month_name = iconv("Windows-1251", "UTF-8", GetMonthName( $month, 1 ) );

if( $month < 10 )
  $month = "0$month";

if( $day < 10 )
  $day = "0$day";

$date = "".$year.$month.$day ;

    $query ="SELECT * FROM okb_db_get_sobitiya ev
                      LEFT JOIN okb_db_clients cagent ON cagent.ID = ev.ID_client  
                      WHERE DATE=$date AND ID_sob=$event_id ORDER BY NAME"; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("Ошибка обращения к БД в ajaxGetDetailTable.php. Query :<br>$query<br>".$mysqli->error); 
  
    $i = 0 ;
    if( $result -> num_rows )
    {
$str =  "<span>События за $day $month_name $year г. Количество событий : ".$result -> num_rows."</span>";
$str .=  "<div id='det_table_div'>
         
         <table width='100%' class='rdtbl tbl' id='det_table'>

         <thead>
         <tr class='first'>
         <td widtd='20%'class='field compact'>Наименование событий</td>
         <td widtd='20%'class='field compact'>Контрагент</td>
         <td widtd='6%'class='field compact'>Кол.</td>
         <td widtd='12%'class='field compact'>Цена</td>
         <td widtd='12%'class='field compact'>Стоимость</td>
         <td widtd='30%' class='field compact'>Комментарий</td>
         </tr></thead><tbody>";

       $i = 0 ;
       while( $row = $result -> fetch_object() )
        {
          $count = $row -> COUNT;
          $price = $row -> PRICE;
          $total = $count * $price  ;
          $cagent_name = iconv("Windows-1251", "UTF-8", $row -> NAME );
          $comment = iconv("Windows-1251", "UTF-8", $row -> KOMM );
          $str .= "<tr class='".( $i % 2 ? 'odd' : 'even' )."_row' >";
          $str .= "<td class='field AL compact'>".++$i." . $event_name</td>";
          $str .= "<td class='field AL compact'>$cagent_name</td>";
          $str .= "<td class='field AC compact'>".ZeroGroup($count)."</td>";
          $str .= "<td class='field AC compact'>".ZeroGroup($price)."</td>";
          $str .= "<td class='field AC compact'>".ZeroGroup($total)."</td>";
          $str .= "<td class='field AL compact'>$comment</td>";
          $str .= "</tr>";
        }
      }

$str .= "</tbody></table></div>";


//echo $str;
header('Content-Encoding: gzip');
echo gzencode( $str );
?>