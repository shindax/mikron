<?php
error_reporting( 0 );
//error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

function collectData( $pdo, $filter )
{
  $pages = [];

      try
      {
          $query ="
                    SELECT distinct pages.id
                    FROM 
                    `okb_db_entrance_control_pages` pages
                    LEFT JOIN `okb_db_entrance_control_items` items ON items.control_page_id = pages.id
                    LEFT JOIN `okb_db_zakdet` zakdet ON zakdet.ID = items.order_item_id
                    LEFT JOIN `okb_db_zak` zak ON zak.ID = zakdet.ID_zak
                    LEFT JOIN `okb_db_clients` clients ON clients.ID = pages.client_id
                    WHERE 
                    zak.NAME LIKE ('%$filter%')
                    OR
                    items.dse_name LIKE ('%$filter%')
                    OR
                    items.dse_draw LIKE ('%$filter%')
                    OR
                    clients.NAME LIKE '%$filter%'
                        ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $pages[] = $row -> id;

  return $pages ;            
}

$user_id = $_POST['user_id'];
$filter = $_POST['filter'];

$str = '';

$pages = array_unique( collectData( $pdo, $filter ) );

$line = 1 ;

foreach( $pages AS $page )
{

// case 280 : // Горлевская
// case 224 : // Михальчук
// case 130 : // Соловова

  $ec = new EntranceControl( $pdo, $page );
  // if( $user_id == 130 || $user_id == 224 || $user_id == 280 )
  if( $user_id == 224 )
    $ec -> EnableImageDeleting();
 
  $ec -> HtmlPageNum( $line ++ );
  $str .= $ec -> GetTable();
}

if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
