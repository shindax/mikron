<?php
error_reporting( 0 );

require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

$proj_id = $_POST['proj_id'];
$img = $_SERVER['DOCUMENT_ROOT'].$_POST['img'];

// ОТладочный вывод
$outfile = 'log.txt';
$outstr = "project id : ".$proj_id."\n" ;
$outstr .= "img : ".$img."\n" ;

$input = file_get_contents("php://input"); 
$outstr .= $input ;

$str = $proj_id." deleted";

			$query = "UPDATE okb_db_projects SET filename='' WHERE ID=$proj_id" ;
      $outstr .= "\n".$query."\n" ;
      $result = $mysqli->query( $query );
      
      if ( !$result ) 
      {
        $outstr .= "Ошибка DB, запрос не удался\n";
        $outstr .= 'MySQL Error: ' . mysql_error();
      }
      else
        $outstr .= "Query was success!\n";

$img = str_replace("//", "/", $img );
$outstr .= "deleting $img : ".( unlink( $img ) ? 'OK' : 'fail');

// file_put_contents( $outfile,  $outstr  );
echo iconv("Windows-1251", "UTF-8", $str );
?>
