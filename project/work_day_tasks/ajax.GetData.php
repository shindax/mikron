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

$second_date = array();

if($year_second !== '' and $month_second !== '' and $day_second !== '')
	$second_date = array('year'=>$year_second, 'month'=>$month_second, 'day'=>$day_second);

$str = "
<table id='day_hour_table' class='table table-striped'>
<col width='2%'>
<col width='15%'>
<col width='73%'>
<col width='5%'>
<col width='5%'>
  <thead>
    <tr class='table-primary'>
      <th>".conv( "#" )."</th>
      <th>".conv( "Сотрудник" )."</th>
      <th>".conv( "Название задачи" )."</th>      
      <th class='AC'>".conv( "Часов" )."</th>
      <th class='AC'>".conv( "Всего" )."</th>
    </tr>
  </thead>
  <tbody>";

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
  $rowspan = count( $item['data'] ) ? "rowspan='".count( $item['data'] )."'" : '';
    
  $str .= "<tr class='$class data_row'><td $rowspan class='ALC'><strong>".( $line ++ )."</strong></td><td class='ALC' $rowspan ><strong>$name</strong></td>";

    $total = 0 ;

    foreach( $item['data'] AS $key => $data ) 
      $total += $data['hours'];

    if( count( $item['data'] ) )
    foreach( $item['data'] AS $key => $data ) 
    {
      $task = conv( $data['name']);
      $count = $data['hours'];
      if( $key )
        $str .= "<tr class=''>";
      
      $str .= "<td class='$class'>".( $key + 1 ).". $task</td><td class='AC $class'><strong>$count</strong></td>";

      if( !$key )
        $str .= "<td class='AC' $rowspan><strong>$total</strong></td>";

      if( $key )
        $str .= "</tr>";
    }
    else
    {
      $str .= "<td class='$class' colspan='3'></td>";
    }
  $str .= "</tr>";
}

$str .= "</tbody>
</table>";

//echo $str;
echo iconv("UTF-8", "Windows-1251", $str );