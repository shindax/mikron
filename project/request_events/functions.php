<?php

function showDate( $date ) // $date --> ����� � ������� Unix time
{
    $stf      = 0;
    $cur_time = time();
    $diff     = $cur_time - $date;
 
    $seconds = array( '�������', '�������', '������' );
    $minutes = array( '������', '������', '�����' );
    $hours   = array( '���', '����', '�����' );
    $days    = array( '����', '���', '����' );
    $weeks   = array( '������', '������', '������' );
    $months  = array( '�����', '������', '�������' );
    $years   = array( '���', '����', '���' );
    $decades = array( '�����������', '�����������', '�����������' );
 
    $phrase = array( $seconds, $minutes, $hours, $days, $weeks, $months, $years, $decades );
    $length = array( 1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600 );
 
    for ( $i = sizeof( $length ) - 1; ( $i >= 0 ) && ( ( $no = $diff / $length[ $i ] ) <= 1 ); $i -- ) {
        ;
    }
    if ( $i < 0 ) {
        $i = 0;
    }
    $_time = $cur_time - ( $diff % $length[ $i ] );
    $no    = floor( $no );
    $value = sprintf( "%d %s ", $no, getPhrase( $no, $phrase[ $i ] ) );
 
    if ( ( $stf == 1 ) && ( $i >= 1 ) && ( ( $cur_time - $_time ) > 0 ) ) {
        $value .= time_ago( $_time );
    }
 
    return $value . ' �����';
}
 
function getPhrase( $number, $titles ) {
    $cases = array( 2, 0, 1, 1, 1, 2 );
 
    return $titles[ ( $number % 100 > 4 && $number % 100 < 20 ) ? 2 : $cases[ min( $number % 10, 5 ) ] ];
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
