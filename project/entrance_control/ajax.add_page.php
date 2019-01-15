<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$today = date("Y-m-d");
$year = date("Y");
$month = date("m");

$user_id = $_POST['user_id'];

      try
      {
          $query ="
                        SELECT id, page_num FROM `okb_db_entrance_control_pages`
                        WHERE id = ( SELECT max(id) from `okb_db_entrance_control_pages` )";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
        }
           if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $result = $row -> page_num ;

      if( $stmt -> rowCount() )
      {
            $page_num_arr = explode("-", $result );
            $page_num = ( $page_num_arr[0] + 1 )."-$year";
            $last_id = $result -> $id ;
      }
      else
      {
          $page_num = "0203-2018";
          $last_id = 0 ;
      }

      try
      {
          $query ="
                          INSERT INTO okb_db_entrance_control_pages
                          ( `id`, `date`, page_num, image, proc_type_id, client_id )
                          VALUES
                          ( NULL, '$today', '$page_num' , '', 1,0 )
                        ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

      $last_id = $pdo -> lastInsertId();

        try
        {
            $query ="
                            INSERT INTO okb_db_entrance_control_items
                            ( id, control_page_id, operation_id, order_item_id, count )
                            VALUES ( NULL, $last_id, 0, 0, 0 )";
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }


//$pages = GetPagesNumArr( $year, $month );
$str = '';

//foreach( $pages AS $page )
{
  // $ec = new EntranceControl( $pdo, $page );
  $ec = new EntranceControl( $pdo, $last_id );
  $str .= $ec -> GetTableBegin();
  $str .= $ec -> GetTableContent();
  $str .= $ec -> GetTableEnd();
}

// Послать уведомление в ОТК
$head_only = 0 ;
$male_message = "добавил новый лист входного контроля №$page_num";
$female_message = "добавила новый лист входного контроля №$page_num";

SendNotification( TECHNICAL_CONTROL_GROUP, $head_only, $user_id, $male_message, $female_message, NEW_ENTRANCE_CONTROL_PAGE_ADDED, $last_id);

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
