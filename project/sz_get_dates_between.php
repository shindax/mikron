<?php

function date_range($first, $last, $step = '+1 day', $output_format = 'Ymd' ) {

    $dates = array();
    $current = @strtotime($first);
    $last = @strtotime($last);

    while( $current <= $last ) {

        $dates[] = array(@date($output_format, $current), @date('d.m.Y', $current));
        $current = @strtotime($step, $current);
    }

    return $dates;
}

function GetSplitDate( $date )
{
	if( $date == 0 )
		return '';
    
	$year = substr( $date, 0, 4 );
	$month = substr( $date, 4, 2 );
	$day = substr( $date, 6, 2 );
  
	return $day . '.' . $month . '.' . $year;
}

$dates = date_range(@date('Ymd', @strtotime(GetSplitDate($_GET['date']) . ' -14 day')), @date('Ymd', @strtotime(GetSplitDate($_GET['date']) . ' +14 day')));

echo '<option style="color:red" value="0">Дата:</option>';

foreach ($dates as $date) {
		echo '<option value="' . $date[0] . '"' . ($_GET['date'] == $date[0] ? ' selected="selected"' : '') . '>' . $date[1] . '</option>';
}
