<?php
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$user_id = $_POST['user_id'];
$type = $_POST['type'];
$arr = [];

if( $type == 1 )
  $query = "
            SELECT 
            zak.ID as id, 
            CONCAT( type.description,' ',zak.NAME ) AS name, 
            zak.DSE_NAME AS `desc`
            FROM okb_db_zak zak
            LEFT JOIN  okb_db_zak_type type ON type.ID = zak.TID
            WHERE EDIT_STATE=0 
            ORDER BY zak.name";

if( $type == 2 )
  $query = "SELECT id AS id, `name` AS name, '' AS `desc` FROM `okb_db_projects` WHERE 1 ORDER BY name";

try
{
                      $stmt = $pdo->prepare( $query );
                      $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}
  $row = $stmt->fetch(PDO::FETCH_OBJ ) ;
  while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
  {
    $arr[] = [ 'label' => $row -> name, 'id' => $row -> id, 'desc' => $row -> desc ];
  }

echo json_encode( $arr );

