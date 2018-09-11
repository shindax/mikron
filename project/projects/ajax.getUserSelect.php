<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once("CommonFunctions.php");

$id = $_POST['id'];
$select = '';

$query ="SELECT NAME, ID_resurs
              FROM okb_db_shtat
              WHERE ID_otdel=$id
              ORDER BY NAME";

$result = $mysqli -> query( $query );

 if( ! $result )
         exit( "Database access error : ".__FILE__." at ".__LINE__." line.".$mysqli -> error );

    if( $result -> num_rows )
        while( $row = $result -> fetch_object() )
        {
          if( $row -> ID_resurs )
              $select .= "<option value='".( $row -> ID_resurs )."'>".($row -> NAME)."</option>";
        }
//echo iconv("Windows-1251", "UTF-8", $select );
echo $select ;
