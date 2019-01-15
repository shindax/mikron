<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$error = false;
$id = $_POST['id'];


      try
      {
          $query ="
                          INSERT INTO okb_db_entrance_control_items
                          ( id, control_page_id, operation_id, order_item_id, count )
                          VALUES ( NULL, $id, 0, 0, 0 )";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

$ec = new EntranceControl( $pdo, $id );

$str = $ec -> GetTableBegin();
$str .= $ec -> GetTableContent();
$str .= $ec -> GetTableEnd();

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
