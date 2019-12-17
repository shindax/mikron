<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( 0 );

function conv( $str )
{
	return $str ;
}

function average_array( $arr )
{
	foreach ( $arr AS $key => $value ) 
		if( $value == 0 && $arr[ $key - 1 ] != 0 && $arr[ $key + 1 ] != 0 )
			$arr[ $key ] = ( $arr[ $key - 1 ] + $arr[ $key + 1 ] ) / 2 ;

	return $arr;
}


function fill_array( $arr, $to_day )
{
	$val = 0 ;
	if( end( $arr ) )
		return $arr;
	$temp = array_reverse( $arr );
	$len = count( $temp );
	for( $i = 0 ; $i < $len ; $i ++ )
		if( ! $temp[ $i ] )
			unset( $temp[ $i ] );
		else
		{	
			$val = $temp[ $i ];
			break ;
		}

	for( $i = $len ; $i > 0 ; $i -- )
		if( ! $arr[ $i ] )
			$arr[ $i ] = $val;
			else
				break;

	foreach ( $arr as $key => $value) 
		if( $key > $to_day )
			unset( $arr[ $key ] );


	foreach ( $arr as $key => $value) 
		if( $arr[ $key ] == 0 && $arr[ $key - 1 ] != 0 && $arr[ $key + 1 ] != 0 )
			$arr[ $key ] = ( $arr[ $key - 1 ] + $arr[ $key + 1 ] ) / 2 ;

	return $arr;

} // function fill_array( $arr, $to_day )

$month_names = [ "", "январь","февраль","март", "апрель", "май", "июнь","июль", "август", 
                    "сентябрь", "октябрь", "ноябрь", "декабрь" ];
$year = $_POST['year'];
$month = + $_POST['month'];
$id = $_POST['order_id'];
$series = [];

$dayInMonth = date('t', mktime(0, 0, 0, $month , 1, $year)); 
$today = date('j'); 
$main_title = conv("График сводных данных по заказу");
$month_title = $month_names[ $month ]." ".$year."г.";

$colors = 
[
	"#7cb5ec",
	"#434348",
	"#90ed7d",
	"#f7a35c",
	"#8085e9",
	"#f15c80",
	"#e4d354",
	"#2b908f",
	"#f45b5b",
	"#91e8e1",
	"#7cb5ec"
];

$dashStyles = 
[
	'Solid',
	'ShortDash',
	'ShortDot',
	'ShortDashDot',
	'ShortDashDotDot',
	'Dot',
	'Dash',
	'LongDash',
	'DashDot',
	'LongDashDot',
	'LongDashDotDot'
];

$month_arr = [];

for( $i = 1 ; $i <= $dayInMonth; $i ++ )
	$month_arr[ $i ] = 0;

for( $i = 0 ; $i < 11 ; $i ++ )
	$series[] = 
			[
				"name" => "",
				"data" => $month_arr,
				"dashStyle" => $dashStyles[$i],
				"color" => $colors[$i]
			];

$series[0]["name"] = conv("План н/ч на зак.");
$series[1]["name"] = conv("План н/ч на ед.");
$series[2]["name"] = conv("Выполнено н/ч на зак.");
$series[3]["name"] = conv("Выполнено н/ч на el.");
$series[4]["name"] = conv("Кооперация шт.");
$series[5]["name"] = conv("Кооперация н/ч на зак.");
$series[6]["name"] = conv("Кооперация н/ч на ед.");
$series[7]["name"] = conv("Осталось н/ч на зак.");
$series[8]["name"] = conv("Осталось н/ч на ед.");
$series[9]["name"] = conv("Факт н/ч на зак.");
$series[10]["name"] = conv("Факт н/ч на ед.");

try
{
    $query = "
    SELECT 
    *, CAST( date_format( datetime, '%d') AS UNSIGNED ) AS day
    FROM `okb_db_zak_svod_log` 
    WHERE 
    order_id = $id
    AND
    datetime >= '$year-$month-1 00:00:00'
    AND
    datetime <= '$year-$month-31 23:59:59'";
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
}

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
	$day = $row -> day ;
	$series[0]["data"][ $day ]  = + $row -> plan_for_order;
	$series[1]["data"][ $day ]  = + $row -> plan_for_one;
	$series[2]["data"][ $day ]  = + $row -> done_for_order;
	$series[3]["data"][ $day ]  = + $row -> done_for_one;

	$series[4]["data"][ $day ]  = + $row -> coop_count;
	$series[5]["data"][ $day ]  = + $row -> coop_for_order;
	$series[6]["data"][ $day ]  = + $row -> coop_for_one;

	$series[7]["data"][ $day ]  = + $row -> left_for_order;
	$series[8]["data"][ $day ]  = + $row -> left_for_one;

	$series[9]["data"][ $day ]  = + $row -> fact_hours_for_order;
	$series[10]["data"][ $day ] = + $row -> fact_hours_for_one;
}
 
foreach ( $series AS $key => $value ) 
 	$series[ $key ][ "data" ] = fill_array( $value[ "data" ], $today );

for( $i = 0 ; $i < 11 ; $i ++ )
	$series[$i]["data"] = average_array( array_values( $series[$i]["data"] ));

echo json_encode( [ "query" => $query, "main_title" => $main_title, "month_title" => $month_title, "month_arr" => array_keys( $month_arr ), "series" => array_values($series)] );