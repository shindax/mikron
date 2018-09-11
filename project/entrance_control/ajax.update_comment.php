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
$user_id = $_POST['user_id'];
$field = get_val( $_POST['field']);
$field_comment = $field."_comment";
$count = get_val( $_POST['count']);
$comment = get_val( $_POST['comment']);

  try
  {
      $query ="       SELECT pages.page_num page_num, pages.id page_id 
                      FROM okb_db_entrance_control_items AS items
                      INNER JOIN okb_db_entrance_control_pages AS pages ON pages.id = items.control_page_id
                      WHERE items.id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

   $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
   $page_num = $row ->  page_num;
   $page_id = $row ->  page_id;

  try
  {
      $query ="
                      UPDATE okb_db_entrance_control_items
                      SET $field = $count, $field_comment = '$comment'
                      WHERE id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

$head_only = 1 ;
$male_message = "внес изменения в лист входного контроля №$page_num";
$female_message = "внесла изменения в лист входного контроля №$page_num";

SendNotification( PRODUCTION_GROUP , $head_only, $user_id, $male_message, $female_message, ENTRANCE_CONTROL_PAGE_DATA_MODIFIED, $page_id );
$head_only = 0 ;
SendNotification( COOPERATION_GROUP , $head_only, $user_id, $male_message, $female_message, ENTRANCE_CONTROL_PAGE_DATA_MODIFIED, $page_id );

echo $query ;

?>