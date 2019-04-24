<?php
require_once( "db.php" );

function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
}

function ajax_conv( $str )
{
    return $str;
}


function DateConvert( $date )
{
    return date("Y-m-d", strtotime( $date ));
}

function MakeDateWithDot( $date, $day = 0 )
{
    $timestamp = strtotime( $date );

    if( $day )
        $out_date = $day ;
        else
            $out_date = date('d', $timestamp);

    $out_date .= ".".date('m', $timestamp) . "." . date('Y', $timestamp);

    return $out_date;
}


function MakeDateWithDash( $date, $day = 0 )
{
    $timestamp = strtotime( $date );
    $out_date = date('Y', $timestamp) . "-" . date('m', $timestamp) . "-";
    if( $day )
        $out_date .= $day ;
    else
        $out_date .= date('d', $timestamp);
    return $out_date;
}
