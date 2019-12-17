<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

// error_reporting(0);
error_reporting( E_ALL );
// ini_set('display_errors', false);

function getReserveCount( $id_zakdet, $operation_id = 0, $pattern = "")
{
	global $pdo ;
	$count = 0 ;

	try
		{
		    $query ="SELECT SUM( count ) AS count
		    		FROM `okb_db_warehouse_reserve` 
		    		WHERE pattern = '$pattern'";

		    $stmt = $pdo->prepare( $query );
		    $stmt -> execute();



		}
		catch (PDOException $e)
		{
		   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
		}

		if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
			$count = $row -> count ;

	return $count ;
}

function getBasketCount( $id_zakdet, $operation_id = 0, $pattern = "")
{
	global $pdo ;
	$count = 0 ;

	try
		{
		    $query ="SELECT count AS count
		    		FROM `okb_db_warehouse_dse_basket` 
		    		WHERE pattern = '$pattern'";
		    $stmt = $pdo->prepare( $query );
		    $stmt -> execute();

		}
		catch (PDOException $e)
		{
		   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
		}

		if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
			$count = $row -> count ;

	return $count ;
}

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
        // $result = $str ;
  return $result;
}

global $pdo;

$pattern = $_POST['pattern'] ; 
$id_zakdet = $_POST['id_zakdet'];
$operation_id = $_POST['operation_id'];
$operitems_id = $_POST['operitems_id'];
$rescount = 0 ;

try
{
    $query ="
			SELECT 
				inv.id AS inv_id, 
				DATE_FORMAT( inv.timestamp, '%d.%m.%Y %H:%i:%s' ) AS date_time, 
				SUM( inv.accepted_by_QCD ) AS accepted_by_QCD, 
				inv.operation_id AS operation_id,
				zak.NAME AS zak_name,
				zak_type.description AS zak_type_description,
				oper.NAME AS oper_name,
				inv.note AS inv_note,
			
				zakdet.ID AS id_zakdet,
				SUM( detitem.COUNT ) AS wh_count

			FROM okb_db_sklades_detitem AS detitem
			
			LEFT JOIN okb_db_semifinished_store_invoices inv ON detitem.ref_id = inv.id
			LEFT JOIN okb_db_oper AS oper ON oper.ID = inv.operation_id			
			LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = inv.id_zakdet
			LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.id_zak
			LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID

			WHERE inv.id IN ( SELECT ref_id FROM okb_db_sklades_detitem WHERE NAME LIKE '%$pattern%' )";

	// echo conv( $query );

    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$line = 1 ;

$str = "<table class='tbl reserve'>";

$str .= "<col width='2%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='1%'>";

$str .= "<tr class='first'>
		<td class = 'AC'>".conv("№")."</td>
		<td class = 'AC'>".conv("Заказ")."</td>
		<td class = 'AC'>".conv("Операция")."</td>
		<td class = 'AC'>".conv("Дата")."</td>
		<td class = 'AC'>".conv("Количество<br>на складе")."</td>
		<td class = 'AC'>".conv("Доступно к выдаче")."</td>		
		<td class = 'AC'>".conv("Количество<br>на выдачу")."</td>
		</tr>";

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
		$accepted_by_QCD = $row -> accepted_by_QCD ;
		$operation_id = $row ->  operation_id;
		$reserve_count = getReserveCount( $id_zakdet, $operation_id, $pattern );
		$basket_count = getBasketCount( $id_zakdet, $operation_id, $pattern );

		$wh_count = $row -> wh_count ;
		$may_give_out = $wh_count - $reserve_count - $basket_count;

		if( $wh_count == 0 )
			continue ;

		$zak_str = conv("Без заказа");
		if( strlen( $row -> zak_type_description ) || strlen( $row -> zak_name ))
		{
			$zak_description = conv( $row -> zak_type_description );
			$zak_name = conv( $row -> zak_name );
			$zak_str = "$zak_description $zak_name";
		}

		$oper_name = strlen( $row -> oper_name ) ? conv( $row -> oper_name ): conv( "Без операции" );

$str .= "<tr data-id='$operation_id' class='items_to_issue' data-pattern='".conv( $pattern )."'>
		<td class = 'field AC'>".( $line ++ )."</td>
		<td class = 'field AC'>$zak_str</td>
		<td class = 'field AC'>$oper_name</td>
		<td class = 'field AC'>".( $row -> date_time )."</td>
		<td class = 'field AC'>$wh_count</td>
		<td class = 'field AC'>$may_give_out</td>
		<td class = 'field AC'>
		<input type='number' data-max='$may_give_out' data-id_zakdet='$id_zakdet' data-id_zadan='' class='get_from_wh_req_input' value='0' ".( $may_give_out ? "" : "disabled" )."></input></td>
		</tr>";	
}

$str .= "</table>";

echo $str;
