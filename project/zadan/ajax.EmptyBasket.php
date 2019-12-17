<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting(0);
ini_set('display_errors', false);

$str = "<table class='tbl basket_table'>";
$arr = [];
$user_id = $_POST["user_id"];

try
{
    $query ="SELECT *
             FROM okb_db_warehouse_dse_basket
             WHERE user_id = $user_id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}
    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        $arr[] = [
                    'id_zakdet' => $row -> id_zakdet,
                    'operation_id' => $row -> operation_id,
                    'count' => $row -> count,
                    'pattern' => $row -> pattern,
                 ];
try
{
    $query ="DELETE FROM okb_db_warehouse_dse_basket WHERE user_id = $user_id" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}


echo json_encode( $arr );