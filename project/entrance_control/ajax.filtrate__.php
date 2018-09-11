<?php
error_reporting( 0 );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

$user_id = $_POST['user_id'];
$filter = $_POST['filter'];
$pages = [];
$str = '';

      try
      {
          $query ="
                    SELECT
                    okb_db_zak.NAME zak_name,
                    okb_db_entrance_control_items.dse_name dse_name,
                    okb_db_entrance_control_pages.id AS page_id
                    FROM
                    okb_db_entrance_control_items
                    INNER JOIN okb_db_entrance_control_pages ON okb_db_entrance_control_items.control_page_id = okb_db_entrance_control_pages.id
                    INNER JOIN okb_db_zakdet ON okb_db_entrance_control_items.order_item_id = okb_db_zakdet.ID
                    INNER JOIN okb_db_zak ON okb_db_zakdet.ID_zak = okb_db_zak.ID
                    WHERE 
                    okb_db_zak.NAME LIKE '%$filter%'
                    OR
                    okb_db_entrance_control_items.dse_name LIKE '%$filter%'
                    GROUP BY page_id
                  ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
        }
      while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $pages[] = $row -> page_id ;

foreach( $pages AS $page )
{
  $ec = new EntranceControl( $pdo, $page );
  $str .= $ec -> GetTable();
}

echo iconv("Windows-1251", "UTF-8", $str );
// echo $str;
