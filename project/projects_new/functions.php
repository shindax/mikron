<?php

function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
}
