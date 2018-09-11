<?php

function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
}

function uconv( $str )
{
  return iconv("Windows-1251", "UTF-8", $str );
}



function date_compare( $date1, $date2 )
{
  if( strtotime( $date1 ) == strtotime( $date2 ) )
    return '=' ;
    
  if( strtotime( $date1 ) > strtotime( $date2 ) )
      $result = '>' ;
        else
          $result = '<' ;

  return $result ;
}

function IsDateInRange( $date, $from_date, $to_date )
{
    
   if( date_compare( $date, $from_date ) == '=' )
    $result = 0 ;
    
   if( date_compare( $date, $from_date ) == '<' )
    $result = -1 ;

   if( date_compare( $date, $to_date ) == '>' )
    $result = 1 ;
    
    return $result ;
}


function IsDateInThisMonth( $date, $rel_date )
{
  $month  = substr( $date, 3, 2 );
  $year   = substr( $date, 6, 4 );  

  $rel_month  = substr( $rel_date, 3, 2 );
  $rel_year   = substr( $rel_date, 6, 4 );  

  if( $year > $rel_year )
    $result = 1 ;

  if( $year < $rel_year )
    $result = -1 ;


  if( $year == $rel_year )
  {
    if( $month > $rel_month )
      $result = 1 ;
    if( $month < $rel_month )
      $result = -1 ;
    if( $month == $rel_month )
      $result = 0 ;
  }

  return  $result ;
}


function GetLastDayDate( $date )
{
  if( $date == '' || $date == 0 )
    return ;
  
  $year = substr( $date, 6, 4 );
  $month = substr( $date, 3, 2 );
  $last_day = strftime( "%d", mktime(0, 0, 0, $month + 1 , 0, $year ) );

  return "$last_day.$month.$year";
}

function GetWeekLastDates( $date, $week )
{
  $curday = substr( $date, 0, 2 ) ;
  $in_week = 0 ;
  
  $date = GetLastDayDate( $date );

  $dayscount = substr( $date, 0, 2 ) ;
  $period = $dayscount / 4 ;
  $day = round( $period * $week, 0 );
  
  $month = substr( $date, 3, 2 );
  $year = substr( $date, 6, 4 );  

  return Array( 'todate' => "$day.$month.$year", 'inweek' => ( $curday >= $day - $period && $curday <= $day ) ? 1 : 0 );
}

function IsInWeek( $date, $week )
{
  $curday = substr( $date, 0, 2 ) ;
  $in_week = 0 ;
  
  $date = GetLastDayDate( $date );

  $dayscount = substr( $date, 0, 2 ) ;
  $period = $dayscount / 4 ;
  $day = round( $period * $week, 0 );

  return ( $curday >= $day - $period && $curday <= $day ) ? 1 : 0 ;
}

function BuildBar( $task, $indate, $suffix = '' )
{

 //bar_inprocess bar_executed bar_over bar_executed_over bar_completed

    $bars = Array('','','','');
    $beg_date = $task['beg_date'];
    $beg_day = 1 * substr( $beg_date, 0, 2 ) ;
    
    $end_date = $task['date'];    
    $end_day = 1 * substr( $end_date, 0, 2 ) ;

    $status = $task['status'];

    $begin_in_this_month = IsDateInThisMonth( $beg_date, $indate );
    $end_in_this_month = IsDateInThisMonth( $end_date, $indate );
    
    $beg_date_inserted = 0 ;
    $end_date_inserted = 0 ;
        
    for( $i = 0 ; $i < 4 ; $i ++ )
          {
          
            if( $status == 'Выполнено' && $begin_in_this_month != 0 )
                $class = 'bar_executed_over'.$suffix ;
                      
            if( $end_date_inserted )
            {
              $beg_date_inserted = 0 ;
              $class = 'bar_over'.$suffix ;
              
            }

            if( $end_date_inserted && $status == 'Завершено' )
            {
              $beg_date_inserted = 0 ;
              $class = '' ;
            }

            $bars[ $i ]['value'] = '' ;
            $bars[ $i ]['class'] = '';

            if( $begin_in_this_month == 0 )
              if( IsInWeek( $beg_date, $i + 1 ) )
               {
                $bars[ $i ]['value'] = $beg_day ;
                
                switch( $status )
                {
                  case 'Выполнено' : $class = 'bar_executed'.$suffix; break ;
                  case 'Завершено' : $class = 'bar_completed'.$suffix; break ;
                  default          : $class = 'bar_inprocess'.$suffix; 
                }
                $beg_date_inserted = 1 ;
               }

            if( $end_in_this_month == 0 )
              if( IsInWeek( $end_date, $i + 1 ) )            
               {
                $bars[ $i ]['value'] = $end_day ;

                switch( $status )
                {
                  case 'Выполнено' : $class = 'bar_executed'.$suffix  ; break ;
                  case 'Завершено' : $class = 'bar_completed'.$suffix ; break ;
                  default          : $class = 'bar_inprocess'.$suffix ; 
                }

                $end_date_inserted = 1 ;
               }

            if( $begin_in_this_month == -1 && $status != 'Выполнено' && $status != 'Завершено' )
               $class = 'bar_over'.$suffix ;

            if( $begin_in_this_month == -1 && $status == 'Завершено' && $end_date_inserted != 1 )
               $class = 'bar_completed'.$suffix ;

            $bars[ $i ]['class'] = $class;
            
          }

    return $bars ;
}

function GetSplitDate( $date )
{
   if( $date == 0 || $date == '')
    return '';

  if( strlen( $date ) == 8 )
  {
    
    $year = substr( $date, 0, 4 );
    $month = substr( $date, 4, 2 );
    $day = substr( $date, 6, 2 );
  }

  if( strlen( $date ) == 10 )
  {
    $pos = strripos( $date, '-' );

    if ($pos === false) // Delimiter is '.'
    {
      // 01.01.2017
      $day = substr( $date, 0, 2 );
      $month = substr( $date, 3, 2 );
      $year = substr( $date, 6, 4 );      
    }
    else  // Delimiter is '-'
    {
      // 2017-01-01
      $day = substr( $date, 8, 2 );
      $month = substr( $date, 5, 2 );
      $year = substr( $date, 0, 4 );      
    }
    
  }

  return $day.'.'.$month.'.'.$year ;
}
