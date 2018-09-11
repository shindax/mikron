<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$arr = $_POST['data'];
$arr_list = join(',', $arr );
$rows = [];

try
{
    $query =
    "SELECT
    oper_id,
    SUM(count) count,
    SUM( norm_hours ) norm_hours
    FROM `okb_db_operations_with_coop_dep` WHERE oper_id IN ( $arr_list ) GROUP BY oper_id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row_count = $stmt -> rowCount() ;

 if( $row_count )
   while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
         {
            $id =
           $rows[] = [
                'oper_id' => $row -> oper_id , 'count' => $row -> count , 'norm_hours' => $row -> norm_hours , 'comment' => $row -> comment ];
         }

echo json_encode( [ "rows" => $rows, "result" => "OK" ] );