<?php

function FReal($x) 
{
	$ret = number_format( $x, 2, ",", " ");
	
	if ( $x == floor($x)) 
		$ret = number_format($x, 0, ",", " ");
	
	if ($x==0) 
		$ret = "";
	
	return $ret;
}

function FDReal($x,$d) 
{
	$ret = "~";
	if ( $d * 1 > 0 ) 
		$ret = FReal(($x*1)/($d*1));
	return $ret;
}
