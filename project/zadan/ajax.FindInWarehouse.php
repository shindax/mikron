<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}

global $pdo;

$pattern = $_POST['pattern']; 
$id = $_POST['id'];
$rescount = 0 ;


try
{
    $query ="
			SELECT detitem.ID id, detitem.NAME name,detitem.KOMM comment, oper.NAME oper_name, detitem.COUNT count, SUM( reserve.count ) rescount, reserve.state state
			FROM `okb_db_sklades_detitem` detitem
			LEFT JOIN okb_db_semifinished_store_invoices inv ON inv.id = detitem.ref_id
			LEFT JOIN okb_db_zadan zadan ON zadan.id = inv.id_zadan
			LEFT JOIN okb_db_operitems operitems ON operitems.id = zadan.ID_operitems
			LEFT JOIN okb_db_oper oper ON oper.id = operitems.ID_oper
 			LEFT JOIN okb_db_warehouse_reserve reserve ON reserve.tier_id = detitem.ID
			WHERE detitem.NAME LIKE '$pattern' AND reserve.state=0" ;
			
//AND reserve.state = 0			
			
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$line = 1 ;

$row = $stmt->fetch( PDO::FETCH_OBJ ) ;

$str = "<table class='tbl'>";
$str .= "<tr class='first'>
		<td class = 'AC'>".conv("№")."</td>
		<td class = 'AC'>".conv("Заказ")."</td>
		<td class = 'AC'>".conv("Операция")."</td>
		<td class = 'AC'>".conv("Кол.<br>на заказ")."</td>
		<td class = 'AC'>".conv("Комментарий")."</td>
		<td class = 'AC'>".conv("Резерв")."</td>
		<td class = 'AC'>".conv("Кол.<br>на выд")."</td>
		</tr>";



if( $row -> id == NULL )
{
try
{
    $query ="
			SELECT detitem.ID id, detitem.NAME name,detitem.KOMM comment, oper.NAME oper_name, detitem.COUNT count, SUM( reserve.count ) rescount, reserve.state state
			FROM `okb_db_sklades_detitem` detitem
			LEFT JOIN okb_db_semifinished_store_invoices inv ON inv.id = detitem.ref_id
			LEFT JOIN okb_db_zadan zadan ON zadan.id = inv.id_zadan
			LEFT JOIN okb_db_operitems operitems ON operitems.id = zadan.ID_operitems
			LEFT JOIN okb_db_oper oper ON oper.id = operitems.ID_oper
 			LEFT JOIN okb_db_warehouse_reserve reserve ON reserve.tier_id = detitem.ID
			WHERE detitem.NAME LIKE '$pattern'" ;
			
//AND reserve.state = 0			
			
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
    $row = $stmt->fetch( PDO::FETCH_OBJ ) ;    
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

}
else
{
 $rescount = $row -> rescount;
  
  if( $row -> state )
    $rescount = 0;
}

{
 
	$str .= "<tr class='warehouse_item' data-id='".$row -> id."'>";
	$str .= "<td class='Field AC'>$line</td><td class='Field AL'>".conv( $row -> name )."	</td>
			<td class='Field AС'>".conv( $row -> oper_name )."</td>
			<td class='Field AС'>".$row -> count."</td>
			<td class='Field AL'>".conv( $row -> comment )."</td>
			<td class='Field AС'>$rescount</td>

			<td class='Field AL'><input class='warehouse_count' value='0'/></td>";
	$str .= "</tr>";
	$line ++ ;
}


//$str .= "<tr><td class='Field AL' colspan='7'>".conv( $query ) ."</td></tr>";

$str .= "</table>";

echo $str;
