<?php
header('Content-Type: text/html');
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo ;

$list = $_GET['list'];
$val = $_GET['value'];
$row_set = [];

try
{
    $query = "
      SELECT ID id, OBOZ name
      FROM `okb_db_sort` 
      WHERE 
      OBOZ LIKE '$val%'
      AND
      ID NOT IN ( $list ) 
      ORDER BY name
    ";

   $stmt = $pdo->prepare( $query  );
   $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  
 
while ( $row = $stmt -> fetchObject() )
	$row_set[] = ["value" => $row -> name, "data_id" => $row -> id ];

echo  json_encode( $row_set );

?>