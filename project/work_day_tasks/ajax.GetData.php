<?php
// error_reporting( E_ALL );
// ini_set('display_errors', true);

error_reporting( 0 );
ini_set('display_errors', false );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.User.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar.php" );

function conv( $str )
{
    //return iconv( "UTF-8", "Windows-1251",  $str );
    return $str ;
}

$dep_id = $_POST['dep_id'];
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];

$year_second = $_POST['year_second'];
$month_second = $_POST['month_second'];
$day_second = $_POST['day_second'];

$second_date = [];

if($year_second !== '' and $month_second !== '' and $day_second !== '')
	$second_date = ['year'=>$year_second, 'month'=>$month_second, 'day'=>$day_second ];

$str = "
<table id='day_hour_table' class='table table-striped'>
<col width='2%'>
<col width='15%'>
<col width='58%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='10%'>
  <thead>
    <tr class='table-primary'>
      <th>".conv( "#" )."</th>
      <th>".conv( "Сотрудник" )."</th>
      <th>".conv( "Название задачи" )."</th>      
      <th class='AC'>".conv( "Часов" )."</th>
      <th class='AC'>".conv( "Всего по задаче" )."</th>            
      <th class='AC'>".conv( "Всего за период" )."</th>
      <th class='AC'>".conv( "Премирование" )."</th>
    </tr>
  </thead>
  <tbody>";

// if( $dep_id )
  $user_arr = User::GetUsersArrByDepartment( $dep_id );

$data = [];

foreach( $user_arr AS $user )
{
  $base_cal = new BaseOrdersCalendar( $pdo,[ $user ] ,$year ,$month, $day, $second_date );
  $user_data = $base_cal -> GetDayHourData();
  $data[] = $user_data ;
}

$line = 1 ;
foreach( $data AS $key => $item )
{
  $class = $key % 2 ? 'table-success' : '';
  $name = conv( $item['name'] );

  if( count( $item['data'] ) )
    $name .= "<br>".conv( $item['day_types'] );
  
  $rowspan = count( $item['data'] ) ? "rowspan='".count( $item['data'] )."'" : '';
  $bonus_payment = '';
  
  foreach( $item['bonus_payment'] AS $bonus )
	$bonus_payment .= $bonus['date'].' - '.$bonus['bonus'].' %<br/>';
    
  $str .= "<tr class='$class data_row' data-user_id='".$item['id']."'><td $rowspan class='AC'><strong>".( $line ++ )."</strong></td><td class='ALC' $rowspan ><strong>$name</strong></td>";

    $total = 0 ;

    foreach( $item['data'] AS $key => $data ) 
      $total += $data['hours'];

    if( count( $item['data'] ) )
    foreach( $item['data'] AS $key => $data ) 
    {
      $task = conv( $data['name']);
      $count = $data['hours'];
      $hour_count_by_order = $data['hour_count_by_order'];
      $row_id = $data['row_id'];

      if( $key )
        $str .= "<tr class='' data-id='$row_id'>";
      
      $str .= "
               <td class='$class'>".( $key + 1 ).". $task</td>
               <td class='AC $class'><strong>$count</strong></td>
               <td class='AC $class'><strong>$hour_count_by_order</strong></td>               
               ";

      if( !$key )
	  {
        $str .= "<td class='AC' $rowspan><strong>$total</strong></td>";
        $str .= "<td class='AC' $rowspan><strong>$bonus_payment</strong></td>";
	  }

      if( $key )
        $str .= "</tr>";
    }
    else
    {
      $str .= "<td class='$class' colspan='5'></td>";
    }
  $str .= "</tr>";
}

$str .= "</tbody></table>";

if( strlen( $dbpasswd ) )
  echo iconv("UTF-8", "Windows-1251", $str );
    else
      echo $str;
    