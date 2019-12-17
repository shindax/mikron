<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting(0);
ini_set('display_errors', false);

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}


$str = "<table class='tbl basket_table'>";
$str .= "<col width='30%'>";
$str .= "<col width='30%'>";
$str .= "<col width='10%'>";
$str .= "<col width='1%'>";


try
{
    $query ="
    SELECT  zakdet.ID AS zakdet_id, 
            zakdet.NAME AS zakdet_name,
            oper.ID AS oper_id, 
            oper.NAME AS oper_name,
            basket.count AS count,
            basket.pattern AS pattern,
            basket.id AS id
    FROM okb_db_warehouse_dse_basket AS basket
    LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.ID = basket.id_zakdet
    LEFT JOIN okb_db_oper AS oper ON oper.ID = basket.operation_id
    WHERE 1" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}
while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
    $zakdet_name = strlen( $row->zakdet_name ) ? $row->zakdet_name : $row -> pattern;
    $oper_name = $row->oper_id ? $row->oper_name : "без операции";

	$str .= "<tr data-pattern='{$row->pattern}' data-id='".( $row->id ? $row->id : 0 )."' data-zakdet_id='".( $row->zakdet_id ? $row->zakdet_id : 0 )."' data-oper_id='".( $row ->oper_id ? $row ->oper_id : 0 )."'>";
	$str .= "<td class='Field AC'>$zakdet_name</td>";
	$str .= "<td class='Field AC'>$oper_name</td>";
	$str .= "<td class='Field AC'><span class='count'>{$row->count}</span></td>";
    $str .= "<td class='Field AC'><img class='del_pict' src='uses/del.png'></td>";    
	$str .= "</tr>";
}

$str .= "</table>";

echo conv( $str );