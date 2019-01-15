<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$user_id = $_POST['user_id'];
$filter = $_POST['filter'];

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
            $page_num = ( $page_num_arr[0] + 1 )."-2018";
            $last_id = $result -> $id ;
      }
      else
      {
          $page_num = "0203-2018";
          $last_id = 0 ;
      }


$pages = GetPagesNumArr();
$str = '';

foreach( $pages AS $page )
{
  $ec = new EntranceControl( $pdo, $page, $filter );
  // $ec -> Filtrate( $filter );
  $str .= $ec -> GetTable();
}

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
