<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$error = false;
$id = $_POST['id'];
$row_id = $_POST['row_id'];

      try
      {
          $query ="
                        SELECT * FROM `okb_db_entrance_control_items`
                        WHERE control_page_id = $id";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

      if( $stmt -> rowCount() > 1 )
      {
            try
            {
                $query ="
                                DELETE  FROM okb_db_entrance_control_items
                                WHERE id = $row_id";
                $stmt = $pdo->prepare( $query );
                $stmt -> execute();
            }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
              }
      }
      else
      {
            try
            {
                $query ="
                                UPDATE  okb_db_entrance_control_items
                                SET
                                operation_id = 0,
                                order_item_id  = 0,
                                count = 0,

                                inwork_state = 0,
                                reject_state = 0,
                                rework_state = 0,
                                pass_state  = 0,

                                inwork_state_comment = '',
                                reject_state_comment = '',
                                rework_state_comment = '',
                                pass_state_comment  = ''
                                WHERE id = $row_id";

                              $stmt = $pdo->prepare( $query );
                              $stmt -> execute();
            }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
              }
      }



$ec = new EntranceControl( $pdo, $id );

$str = $ec -> GetTableBegin();
$str .= $ec -> GetTableContent();
$str .= $ec -> GetTableEnd();

//echo iconv("Windows-1251", "UTF-8", $str );
echo $str ;

?>