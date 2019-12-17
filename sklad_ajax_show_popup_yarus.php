<style>
.first td, .AC
{
	text-align: center !IMPORTANT;
}
.tier_count
{
	text-decoration: none;
	font-weight:bold;
}
#count_dialog
{
	display: flex !IMPORTANT;
	justify-content : center;
	align-items : center;
	padding: 0;
	margin: 0
}
#count_input, #operation_select
{
	width: 100% !IMPORTANT;
}
.count_dialog_title, .ui-dialog-titlebar
{
	background-color: #2F4F4F !IMPORTANT;
	color: white !IMPORTANT;
}
</style>

<?php

header('Content-type: text/plain; charset=windows-1251');

define('MAV_ERP', true);

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';

include 'sklad_func.php';

$query = "	SELECT 
				detitem.ID AS ID,
				detitem.ORD AS ORD,
				detitem.NAME AS NAME,
				detitem.KOMM AS KOMM,
				detitem.COUNT AS COUNT,
				detitem.OTK_STATUS AS OTK_STATUS,
				detitem.ref_id,

				zak.NAME AS zak_name,
				zak_type.description AS zak_type,
				zakdet.NAME AS zakdet_name,
				zakdet.OBOZ AS zakdet_draw,
				oper.NAME AS oper_name,
				oper.ID AS oper_id,
				kind.NAME AS kind_name

			FROM okb_db_sklades_detitem AS detitem

			LEFT JOIN okb_db_semifinished_store_invoices AS semifinished_store_invoices ON semifinished_store_invoices.id = detitem.ref_id 
			LEFT JOIN okb_db_zadan AS zadan ON zadan.ID =  semifinished_store_invoices.id_zadan

			LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.ID = semifinished_store_invoices.id_zakdet
			LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
			LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID

			LEFT JOIN okb_db_oper AS oper ON oper.ID = semifinished_store_invoices.operation_id
			LEFT JOIN okb_db_oper_kind AS kind ON kind.id = oper.TID
			WHERE ID_sklades_yarus = " . (int) $_GET['ID_yarus'] . "
							ORDER BY ORD ASC";

$result = dbquery( $query );

$yarus_ord = mysql_result(dbquery("SELECT ORD FROM okb_db_sklades_yaruses WHERE ID = " . (int) $_GET['ID_yarus']), 0);

$op_select_options = GetOperationSelectOptions();

echo "<script>var can_edit=".canEdit( $user['ID'] )."</script>";
echo "<script>var user_id={$user['ID']}</script>";

echo "<div id='count_dialog' title='Количество ДСЕ'>
			<div>
				<input type='number' id='count_input' data-old_count='' value='' />
		    </div>
		</div>";


echo "<div id='operation_dialog' title='Операция'>
			<div>
				<select type='number' id='operation_select' data-old_id=''>
					$op_select_options				
				</select>
		    </div>
		</div>";


echo '<div class="popup" id="popup_yarus" data-yarus-id="' . (int) $_GET['ID_yarus'] . '" data-yarus-item-id="">
	<table style="width:100%">
		<tr><td>Название:</td><td><input type="text" id="dse_name" name="NAME"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '/></td>
		<select size="12" id="autocomplete"></select>
		</tr>
		
		<tr><td>Количество:</td><td><input type="number" pattern="^[0-9]+$" value="1" name="COUNT"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '/></td></tr>

		<tr><td>Операция:</td><td><select id="op_select">'.$op_select_options.'</select></td></tr>

		<tr><td>Комментарии:</td><td><textarea style="float:left" name="KOMM"' . (!$can_edit_sklad ? ' disabled="disabled"' : '') . '></textarea>
		<tr>
			<td>
				<input id="insert" type="submit" value="Добавить" style="float:left" disabled>
			</td>
			<td style="padding-top:1px;">
				<b style="float:right" id="box_yarus_id"></b>
				<a href="javascript:void(0)" onclick="HidePopupYarus()" style="font-size:12px;float:left;margin-left:4px;">Закрыть</a>
			</td>
		</tr>
	</table>
	<hr/>
	<div id="move_items_block"></div>
	<b>Выберите предмет</b>
	<div style="float:right">
		<input type="text" id="search_item"/>
	</div>
	<!-- <table class="rdtbl tbl yarus_select_thead">
		<tr class="first">
			<td>№</td>
			<td>Наименование</td>
			<td>Заказ</td>
			<td>Чертеж</td>
			<td>Операция</td>
			<td>Комментарий</td>
			<td>Кол-во</td>
			<td><input type="checkbox" id="multiselect"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/></td>

			</tr>
	</table> -->
	<div style="height:165px;overflow-y:auto;">
		<table class="rdtbl tbl yarus_items" id="yarus_item_select">
		<tbody>
		<tr class="first">
			<td>№</td>
			<td>Наименование</td>
			<td>Заказ</td>
			<td>Чертеж</td>
			<td>Операция</td>
			<td>Комментарий</td>
			<td>Кол-во</td>
			<td><input type="checkbox" id="multiselect"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/></td>

			</tr>
		';

	while ($row = mysql_fetch_assoc($result)) {

        $oper_name = strlen( $row['oper_name'] ) ? $row['oper_name'] : "Нет операции";

		$oper_id = $row['oper_id'] ? $row['oper_id'] : 0 ;
        $oper_name = "<a class='operation_a' href='#' data-id='$oper_id' >$oper_name</a>";

        $kind_name = $row['kind_name'];
        $kind_name = strlen( $kind_name ) ? "$kind_name -" : "";

        if( $row['ref_id'] &&  strlen( $row['zakdet_name'] ))
        	$dse_name = $row['zakdet_name'];
        		else
        			$dse_name = $row['NAME'];

		echo '<tr' . ($row['OTK_STATUS'] == 1 ? ' class="otk_confirmed"' : '') . ' data-yarus-item-id="' . $row['ID'] . '" data-ref_id="'.$row['ref_id'].'">'
				,'<td>' . $row['ORD'] . '</td>'
				,'<td>' . $dse_name . '</td>'
				,'<td>' . $row['zak_type'].' '. $row['zak_name'] . '</td>'	
				,'<td>'.$row['zakdet_draw'].'</td>'
				,'<td>'.$kind_name.$oper_name.'</td>'

//				,'<td>' . ($can_edit_sklad && $row['ref_id'] == 0 && $row['OTK_STATUS'] == 0 ? '<a href="javascript:void(0)" id="delete_item"><img src="uses/del.png"/></a>' : '') . '</td>'
//				
				,'<td>' . $row['KOMM'] . '</td>'
				,'<td class="AC"><a href="#" class="tier_count">' . $row['COUNT'] . '</a></td>'
				,'<td>' . ($row['OTK_STATUS'] == 0 || $can_edit_otk ? '<input type="checkbox" name="ID_yarus_items"' . (!$can_edit_sklad && !$can_edit_otk ? ' disabled="disabled"' : '') . '/>' : '') . '</td>'				
				,'</tr>';
	}
	
	echo '</tbody>
		</table>
		</div>
		<div style="margin-top:4px">
			' . ($can_edit_otk ? '
			<div id="otk_status" style="float:left;">
				ОТК: <button id="otk_confirm">Подтвердить</button><button id="otk_confirm_remove">Снять</button>
			</div>' : '') . '
			' . ($can_edit_sklad ? '
			<button id="remove">Удалить</button>
			<button id="move">Изменить ярус</button>' : '') . '
		</div>
	</div>';


function canEdit( $user_id )
{
	$can_edit = 0 ;
	$query = "	SELECT REPLACE(
				RIGHT( LEFT( ID_rightgroups, LENGTH(ID_rightgroups)-1 ), LENGTH( LEFT( ID_rightgroups, LENGTH(ID_rightgroups)-1 ) )-1 ), '|', ',') AS right_list
				FROM `okb_users`  
				WHERE ID = $user_id";
	$result = dbquery( $query );
	
	$row = mysql_fetch_assoc($result);
	$arr = explode( ",", $row['right_list']);

	if( in_array( 69, $arr ) || $user_id == 1 ) // 69 - редактор склада
		$can_edit = 1 ;

	return $can_edit;
}


function GetOperationSelectOptions()
{
	$arr = [];

	$query = "SELECT 
                oper.ID AS id, 
                oper.NAME AS name,
                kind.name AS kind_name
            FROM `okb_db_oper` AS oper
            LEFT JOIN okb_db_oper_kind AS kind ON kind.id = oper.TID
            WHERE oper.ID NOT IN ( 8 )
            ORDER BY kind.NAME, oper.NAME";
	$result = dbquery( $query );
	while ($row = mysql_fetch_assoc($result)) 
	{
			$op_name = $row['name'];
	        $kind_name = $row['kind_name'];
	        $kind_name = strlen( $kind_name ) ? "$kind_name - " : "";
	        $arr[ $row['id'] ] = $kind_name.$op_name ;
	}

	$options = "<option value='0' selected>Нет операции</option>";
	foreach ( $arr as $key => $value ) 
		$options .= "<option value='$key'>$value</option>";
	
	return $options;
}
?>

<script type="text/javascript" src="/js/warehouse.js?arg=0"></script>
