<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$order_id = $_POST['order_id'];
$table_id = $_POST['table_id'];


      try
      {
          $query ="
                          DELETE  FROM okb_db_entrance_control_items
                          WHERE id = $order_id";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

$ec = new EntranceControl( $pdo, $table_id );

$str = $ec -> GetTableBegin();
$str .= $ec -> GetTableContent();
$str .= $ec -> GetTableEnd();

//echo iconv("Windows-1251", "UTF-8", $str );
echo $str ;
?>