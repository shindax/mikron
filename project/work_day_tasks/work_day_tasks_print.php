<link rel='stylesheet' href='/project/work_day_tasks/css/print_style.css' type='text/css'>

<center>
<div id='Printed' class='a4p'>    

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.User.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function getBrowser()
{
$user_agent = $_SERVER["HTTP_USER_AGENT"];

  if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
  elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
  elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
  elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
  elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
  else $browser = "Неизвестный";
  return $browser;
}


$year = $_GET['p0'];
$month = $_GET['p1'] > 9 ? $_GET['p1'] : "0".$_GET['p1'];
$day = $_GET['p2'] > 9 ? $_GET['p2'] : "0".$_GET['p2'];

$year_second = $_GET['p5'];
$month_second = $_GET['p6'] > 9 ? $_GET['p6'] : "0".$_GET['p6'];
$day_second = $_GET['p7'] > 9 ? $_GET['p7'] : "0".$_GET['p7'];

$dep_id = $_GET['p3'];
$dep_name = iconv( "Windows-1251", "UTF-8", $_GET['p4'] );

if( getBrowser() == "Firefox" )
  $dep_name = $_GET['p4'];

error_reporting( E_ALL );

$str = "<div class='container'>";
$str .= "<div class='row' id='table_div'>";
$str .= "<h3>".conv( "Перечень работ за $day.$month.$year - $day_second.$month_second.$year_second.")."</h4>";
$str .= "<h4>".conv( "Подразделение: $dep_name")."</h4>";
$str .= "</div>";

$str .= "<div class='row' id='table_div'>";

$str .= "
<table id='day_hour_table' class='table table-striped'>
<col width='2%'>
<col width='15%'>
<col width='67%'>
<col width='6%'>
<col width='6%'>
  <thead>
    <tr class='table-primary'>
      <th>".conv( "#" )."</th>
      <th>".conv( "Сотрудник" )."</th>
      <th>".conv( "Название задачи" )."</th>      
      <th class='AC'>".conv( "Часов" )."</th>
      <th class='AC'>".conv( "Итого" )."</th>      
    </tr>
  </thead>
  <tbody>";

$second_date = array();

if($year_second !== '' and $month_second !== '' and $day_second !== '')
	$second_date = array('year'=>$year_second, 'month'=>$month_second, 'day'=>$day_second);

$user_arr = User::GetUsersArrByDepartment( $dep_id );

$data = [];

foreach( $user_arr AS $user )
{
  $base_cal = new BaseOrdersCalendar( $pdo,[ $user ] ,$year ,$month, $day, $second_date );
  $user_data = $base_cal -> GetDayHourData();

  if( count( $user_data['data'] ) )
    $data[] = $user_data ;
}

$line = 1 ;
foreach( $data AS $key => $item )
{
  $rowspan = count( $item['data'] ) ? "rowspan='".count( $item['data'] )."'" : '';
  
  $class = $key % 2 ? 'even' : 'odd';
  $name = conv( $item['name'] );
  $task_count = count( $item['data'] );
  $str .= "<tr class='data_row $class'><td $rowspan class='ALC'><strong>".( $line ++ )."</strong></td><td class='ALC' $rowspan><strong>$name</strong></td>";

    $total = 0 ;

    foreach( $item['data'] AS $key => $data ) 
      $total += $data['hours'];

  if( count( $item['data'] ) )
    foreach( $item['data'] AS $key => $data ) 
    {
      $class = $key % 2 ? 'even' : 'odd';
      $task = conv( $data['name']);
      $count = $data['hours'];
      if( $key )
        $str .= "<tr class=''>";
      $str .= "<td class='$class AL'>".( $key + 1 ).". $task</td><td class='AC $class'><strong>$count</strong></td>";
      
      if( !$key )
        $str .= "<td class='AC' $rowspan><strong>$total</strong></td>";

      if( $key )
        $str .= "</tr>";

    }
    else
    {
      $str .= "<td colspan='2'></td>";
    }
  $str .= "</tr>";
}

$str .= "</tbody>
</table>";

$str .= "</div>"; // "<div class='row'>"

echo $str ;
?>
</div>    
</center>
