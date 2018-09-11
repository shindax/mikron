<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("db_config.php");

$month_arr = array("январь" => 31, "февраль" => 28 ,"март" => 31, "апрель" => 30, "май" => 31, "июнь" => 30,"июль" => 31,"август" => 31,
                    "сентябрь" => 30, "октябрь" => 31, "ноябрь" => 30, "декабрь" => 31 );

function GetAvailableYearList()
{
   global $mysqli;
   
   $query ="SELECT MAX( DATE ) max_date, MIN( DATE ) min_date FROM okb_db_get_sobitiya WHERE 1"; 
    
    $result = $mysqli -> query( $query );
    
    if( ! $result ) 
         exit("Ошибка обращения к БД в функции GetAvailableYearList. Query :<br>$query<br>".$mysqli->error); 
 
    if( $result -> num_rows )
      {
        $row = $result -> fetch_object();
        $max_date = substr( $row -> max_date, 0 , 4 );
        $min_date = substr( $row -> min_date, 0 , 4 );
        return range( $min_date , $max_date );        
      }
      else
        return array();
}

function GetYearList( $cur_year = 2016 )
{
  
  $year_arr = GetAvailableYearList();
  
  $i = 1 ;
  $year_sel = "<select id='year_sel'>";

  foreach( $year_arr AS $year )
  {
    $year_sel .= "<option value='".$year."'";
    if( $year == $cur_year )
        $year_sel .= "selected>$year</option>";
        else
           $year_sel .= ">$year</option>";
  }    
  $year_sel .= "</select>";
  return $year_sel ;
}

function GetMonthList( $cur_month = 1 )
{
  global $month_arr ;
  $i = 1 ;
  $month_sel = "<select id='month_sel'>";

  foreach( $month_arr AS $month => $day_count )
  {
    $month_sel .= "<option value='".$i."'";
    if( $i == $cur_month )
        $month_sel .= "selected>$month</option>";
        else
           $month_sel .= ">$month</option>";
    $i ++;
  }    
  $month_sel .= "</select>";
  return $month_sel ;
}


function GetMonthDayCount( $month, $year )
{
  global $month_arr ;
  $i = 1 ;
  foreach( $month_arr AS $name => $value )
      if( $i ++ == $month )
      {
        if( !( $year % 4 ) && $month == 2 )
          $value ++ ;
          
        return $value ;
      }
        
  return 0;
}

function GetMonthName( $month, $declension = 0 )
{
  global $month_arr ;
  $i = 1 ;
  foreach( $month_arr AS $name => $value )
      if( $i ++ == $month )
      {
        if( $declension )
          $name[strlen( $name ) - 1 ] = 'я';
        return $name ;
      }
      
  return '';
}

function ZeroGroup( $val )
{
  $len = strlen( $val );
  $new_val = strrev( $val );
  $str = '' ;
  
  for( $i = 0 ; $i < $len ; $i += 3 )
    $str .= substr( $new_val , $i, 3 )."'";

  $str[ strlen( $str ) - 1 ] = '';
  $str = strrev( $str );
  $str = str_replace("'", '&nbsp;', $str );
  return $str ;
}

function addCell( $num )
{
  $str = '';
  
  for( $i = 1 ; $i <= $num ; $i ++ )
      $str .= "<td data-day='".$i."'class='field AC ord_cell head_col'>$i</td>";
  
  return $str ;
}

function makeMonthTable( $arr , $ajax = 0 )
{
  $month = $arr['Month'];
  $year = $arr['Year'];

  $str = '';
  $i = 0 ;
    foreach( $arr['Events'] AS $key => $value )
    {
       $str .= "<tr data-ev_id='$key' data-month='$month' data-year='$year' class='".( $i%2?'even':'odd')."_row' >";        
           foreach( $value AS $cell_col => $cell_value )
                if( $cell_col )
                  {
                     $day_of_month = "$cell_col-е ".GetMonthName( $month, 1 );

                     if( $ajax )
                        $day_of_month = iconv("Windows-1251", "UTF-8", $day_of_month );
                    
                    if( $cell_value )
                      $str .= "<td data-day='$cell_col' title= '$day_of_month' class='AC ord_cell'>".ZeroGroup( $cell_value )."</td>";
                       else
                        $str .= "<td data-day='$cell_col' class='AC'>&ndash;</td>";
                  }
       $i ++ ;
       $str .= "</tr>";
    }
  
  return $str ;
}

function GetUserResourceID( $user_id )
{
   global $mysqli;
    
    if( ! $user_id )
        return 0;
    
// Определить ID пользователя в таблице ресурсов
    $query ="
    SELECT ID FROM okb_db_resurs 
    WHERE ID_users = $user_id "; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
      {
         exit("Ошибка обращения к БД в функции GetUserResourceID № ".$mysqli -> errno." . Query :<br>$query<br>".$mysqli->error); 
      }
  
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_assoc() )
        $user_res_id = $row['ID'];
  }
 else 
     return 0 ;

 return $user_res_id ;
}

function GetEventName( $event_id )
{
   global $mysqli;
    
    if( ! $event_id )
        return 0;
    
// Определить ID пользователя в таблице ресурсов
    $query ="SELECT NAME FROM okb_db_sobitiya WHERE ID = $event_id"; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("Ошибка обращения к БД в функции GetEventName. Query :<br>$query<br>".$mysqli->error); 
  
  if( $result -> num_rows )
  {
      while ( $row = $result -> fetch_object() )
        $event_name = $row -> NAME;
  }
 else 
     return '' ;

     return $event_name ;
}

function GetEventList( $month, $year )
{
   global $mysqli;

   $events_array = array( 'Month' => $month, 'Year' => $year, 'Events' => array() );
   $days_count = GetMonthDayCount( $month, $year );

// Определяем диапазон дат
   $from = $year * 10000 + $month * 100 + 1;
   $to = $year * 10000 + $month * 100 + $days_count ;

// Определяем количество типов произошедших событий и подготавливаем массив
    $query ="SELECT DISTINCT(ID_sob) FROM okb_db_get_sobitiya WHERE DATE >= $from AND DATE <= $to"; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("Ошибка обращения к БД в функции GetEventList in CommonFunctions.php. Query :<br>$query<br>".$mysqli->error); 
  
    if( $result -> num_rows )
      {
            while( $row = $result -> fetch_object() )
                for( $j = 1 ; $j <= $days_count ; $j ++ )
                    $events_array['Events'][ $row -> ID_sob ][$j] = 0 ;
      }

// Получаем сами события и складываем их в массив
      
    $query ="SELECT * FROM okb_db_get_sobitiya WHERE DATE >= $from AND DATE <= $to"; 

    $result = $mysqli -> query( $query );
    if( ! $result ) 
         exit("Ошибка обращения к БД в функции GetEventList in CommonFunctions.php. Query :<br>$query<br>".$mysqli->error); 
  
    if( $result -> num_rows )
       while( $row = $result -> fetch_object() )
        {
            $ev_id = $row -> ID_sob ;
            $sum = $row -> COUNT * $row -> PRICE;
            $date = 0 + $row -> DATE ;
            $date -= $year * 10000 + $month * 100 ;
            $events_array['Events'][ $ev_id ][ $date ] += $sum ;
        }

//Подсчёт общей суммы. Лежит в ячейке [0];
    foreach( $events_array['Events'] AS $i => $value )
    {
        $sum = 0 ;
            for( $j = 1 ; $j <= $days_count ; $j ++ )
                $sum += $value[$j];

         $events_array['Events'][$i][0] = $sum ;
    }

    return $events_array ;
}

function MakeEventsTable( $month, $year , $ajax = 0 )
{
$arr = GetEventList( $month, $year );

$first_col = "<div id='first_col'>
              <table width='100%' class='rdtbl tbl' id='first_col_table'>";

$third_col = "<div id='third_col'>
             <table width='100%' class='rdtbl tbl' id='third_col_table'>";

$i = 1 ;

foreach( $arr['Events'] AS $key => $value )
{

  $ev_name = GetEventName( $key );

  if( $ajax )
    $ev_name  = iconv("Windows-1251", "UTF-8", $ev_name );

 $first_col .= "<tr class='".( $i%2?'odd':'even')."_row'>
                <td class='AL'>$i. $ev_name</td>
                </tr>";
 $third_col .= "<tr class='".( $i%2?'odd':'even')."_row'>
                <td class='AC'>". ZeroGroup( $arr['Events'][$key][0])."</td>
                </tr>";
                $i ++ ;
}
               
$first_col .= "</table></div>";
$third_col .= "</table></div>";

$sec_col = "<div id='second_col'>
            <table width='100%' class='rdtbl tbl' id='second_col_table'>";
$sec_col .=  makeMonthTable( $arr , 1 ) ;
$sec_col .=  "</table></div>";

$str = $first_col.$sec_col.$third_col; 

return $str;
}


?>