<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

function get_val( $val )
{
  if( !isset( $val ) || $val == '' )
    return 0 ;
      else
        return $val ;
}

$id = $_POST['id'];
$count = get_val( $_POST['count']);

  try
  {
      $query ="
                      UPDATE okb_db_entrance_control_items
                      SET count = $count
                      WHERE id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

//echo "id : $id oper id : $oper_id order id : $order_id dse id : $dse_id count : $count";
echo $query ;

?>