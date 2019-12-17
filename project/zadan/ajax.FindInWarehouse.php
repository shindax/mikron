<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/project/sklad/functions.php" );

error_reporting(0);
ini_set('display_errors', false);

global $pdo;

$pattern = $_POST['pattern']; 
$id_zakdet = $_POST['id_zakdet'];
$id_zadan = $_POST['id_zadan'];
$operation_id = $_POST['id_operation'];

if( $id_zakdet )
	$group = true;
		else
			$group = false;

$reserve_count = + getReserveCount( $id_zakdet, $operation_id, $id_zadan, $pattern );
$data = findAtWarehouse( $id_zakdet, $operation_id, $pattern, $group );

// debug( $data );

$line = 1 ;
$str = "<table class='tbl reserve'>";
$str .= "<col width='2%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='10%'>";
$str .= "<col width='1%'>";

$str .= "<tr class='first'>
		<td class = 'AC'>".conv("№")."</td>
		<td class = 'AC'>".conv("Заказ")."</td>
		<td class = 'AC'>".conv("Операция")."</td>
		<td class = 'AC'>".conv("Дата")."</td>
		<td class = 'AC'>".conv("Количество<br>на складе")."</td>
		<td class = 'AC'>".conv("Доступно к выдаче")."</td>		
		<td class = 'AC'>".conv("Комментарий")."</td>
		<td class = 'AC'>".conv("Количество<br>на выдачу")."</td>
		</tr>";


foreach ( $data AS $key => $val )
{
		$accepted_by_QCD = $val['accepted_by_QCD'];
		$wh_count = $val['wh_count'] ;
		$may_give_out = $wh_count - $reserve_count ;

		$lpattern = strlen( $val['pattern'] ) ? conv( $val['pattern'] ) : conv( $val['detitem_dse_name'] );

		if( $wh_count == 0 )
			continue ;

		$zak_str = conv("Без заказа");
		if( strlen( $val['zak_type_description'] ) || strlen( $val['zak_name'] ))
		{
			$zak_description = conv( $val['zak_type_description'] );
			$zak_name = conv( $val['zak_name'] );
			$zak_str = "$zak_description $zak_name";
		}

		$oper_name = strlen( $val['oper_name'] ) ? conv( $val['oper_name'] ): conv( "Без операции" );

$str .= "<tr data-id='$operation_id' class='items_to_issue' data-pattern='$lpattern'>
		<td class = 'field AC'>".( $line ++ )."</td>
		<td class = 'field AC'>$zak_str</td>
		<td class = 'field AC'>$oper_name</td>
		<td class = 'field AC'>{$val['date_time']}</td>
		<td class = 'field AC'>$wh_count</td>
		<td class = 'field AC'>$may_give_out</td>
		<td class = 'field AC'>
		<input class='get_from_wh_req_comment_input' /></td>
		<td class = 'field AC'>
		<input type='number' data-max='$may_give_out' data-id_zakdet='$id_zakdet' data-id_zadan='$id_zadan' class='get_from_wh_req_input' value='0' ".( $may_give_out ? "" : "disabled" )."></input></td>
		</tr>";	
}

$str .= "</table>";

echo $str;
