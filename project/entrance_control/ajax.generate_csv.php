<?php
error_reporting( 0 );
//error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControlCSV.php" );

$ids = $_POST['ids'];

$str = '';
$line = 1 ;

$str = conv("Дата;Лист №;Поставка/Кооперация;Поставщик/Кооператор;Операция;Заказ №;Наименование изделия;Наименование ДСЕ;Кол;ВР;ИБ;Д;П").EntranceControlCSV::EOL.EntranceControlCSV::EOL;

foreach( $ids AS $page )
{
  $ec = new EntranceControlCSV( $pdo, $page );
  $str .= $ec -> GetCSV();
  $str .= EntranceControlCSV::EOL ;
}

file_put_contents( "export.csv", $str );

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
