<?php
header('Content-type: text/plain; charset=windows-1251');
define('MAV_ERP', true);

require_once( "wh_functions.php" );

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    return $str;
}

$wh_struct = get_warehouse_structure();

include 'config.php';
include 'includes/database.php';

dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

include 'includes/cookie.php';
include 'sklad_func.php';

$user_id = $user['ID'];
	
switch($_GET['mode'])
{
	case 'add_edit':
		if (!$can_edit_sklad) return;
	
		$_POST['NAME'] = iconv('utf-8', 'windows-1251', $_POST['NAME']);
		$_POST['KOMM'] = iconv('utf-8', 'windows-1251', $_POST['KOMM']);
		
		if (empty($_POST['ID_yarus_item'])) 
		{
			$comm = mysql_real_escape_string( $_POST['KOMM'] ) ;
			$count = ( int ) $_POST['COUNT'] ;
			$zakdet_id = $_POST['zakdet_id'];
			$operation_id = $_POST['operation_id'] ;
			$dse_name = $_POST['NAME'];
			$tier = ( int ) $_POST['ID_yarus'];			
			$dse_name = mysql_real_escape_string( $_POST['NAME'] );

			$query = "	 SELECT 
							CONCAT( zak_type.description, ' ', zak.NAME ) AS zak_name,
							zakdet.OBOZ AS draw
						 FROM okb_db_zakdet AS zakdet
						 LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
						 LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
						 WHERE zakdet.ID = $zakdet_id";
			
			$result = dbquery( $query );
			$row = mysql_fetch_assoc($result);
			$zak_name = $row['zak_name'];
			$draw = $row['draw'];
			$last_insert_inv_id = 0;

			if( $zakdet_id )
			{
				$query = "	INSERT INTO okb_db_semifinished_store_invoices 
							( id_zakdet, dse_name, order_name, draw_name, count, accepted_by_QCD, storage_place, issued_from, operation_id, note, create_date, user_id )
							VALUES( $zakdet_id, '$dse_name', 
							'$zak_name', 
							'$draw',
							$count, $count, '[]', '[]', $operation_id, '$comm', NOW(), $user_id );
							";

				dbquery( $query );
				$last_insert_inv_id = mysql_insert_id();

				$query = "INSERT INTO okb_db_sklades_detitem (ID_sklades_yarus, NAME, KOMM, COUNT, ref_id, ORD)
							SELECT $tier,
							'$dse_name',
							'$comm ".conv2("Накладная № ")."$last_insert_inv_id',
							$count,
							$last_insert_inv_id,
							IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades_detitem
								WHERE ID_sklades_yarus = $tier";

				dbquery( $query );
				$last_insert_detitem_id = mysql_insert_id();

				$query = "	SELECT 
								cell.ID AS cell_id,
								wh.ID AS wh_id
							FROM `okb_db_sklades_yaruses` AS tier
							LEFT JOIN okb_db_sklades_item AS cell ON cell.ID = tier.ID_sklad_item
							LEFT JOIN okb_db_sklades AS wh ON wh.ID = cell.ID_sklad
							WHERE tier.ID = $tier";

				$result = dbquery( $query );
				$row = mysql_fetch_assoc($result);

				$arr = [[
							'id' => $last_insert_detitem_id, 
							'wh' => $row['wh_id'],
							'cell' => $row['cell_id'],
							'tier' => $tier,
							'count' => $count,
							'comments' => ''
						]];


				$new_count = 0 ;

				foreach( $arr AS $key => $value )
					$new_count += $value['count'];

				$query = "	UPDATE okb_db_semifinished_store_invoices 
							SET 
							storage_place = '".json_encode( $arr )."', 
							count = $count,
							inv_num = $last_insert_inv_id
							WHERE id = $last_insert_inv_id
							";

				dbquery( $query );

			$user_info = GetUserInfo( $user_id );
			$user_name = $user_info['name'];
			$user_gender = $user_info['gender'];

			if( $user_gender == 1 || $user_gender == 0 )
				$action = conv2("разместил ДСЕ :");
				 else
				 	$action = conv2("разместила ДСЕ :");

			$comm = "$user_name $action $dse_name".conv2(" в количестве $count шт.");
			
			FixActionInHistory( WH_MANUAL_DISTRIBUTE_FROM_ORDER, $user_id, $zakdet_id, $dse_name, $count, $comm, 0, $tier );

			} // if( $zakdet_id )
			else // Без заказа
			{
				$query = "INSERT INTO okb_db_sklades_detitem 
						(
							ID_sklades_yarus, 
							NAME, 
							KOMM, 
							COUNT, 
							ref_id, 
							ORD
						)
							SELECT $tier,
							'$dse_name',
							'$comm ".conv2(". Без заказа")."',
							$count,
							0,
							IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades_detitem
								WHERE ID_sklades_yarus = $tier";
				dbquery( $query );
				$last_insert_detitem_id = mysql_insert_id();

				$user_info = GetUserInfo( $user_id );
				$user_name = $user_info['name'];
				$user_gender = $user_info['gender'];

				if( $user_gender == 1 || $user_gender == 0 )
					$action = conv2("разместил ДСЕ :");
					 else
					 	$action = conv2("разместила ДСЕ :");

				$comm = "$user_name $action $dse_name".conv2("в количестве $count шт.");

				FixActionInHistory( WH_MANUAL_DISTRIBUTE, $user_id, WH_NO_ZAKDET, $dse_name,$count, $comm, 0, $tier );

			} // if( $zakdet_id )
				// else Без заказа

		if( ! $last_insert_inv_id )
				create_invoice_from_wh_detitem( $zakdet_id, $wh_struct, $tier, $last_insert_detitem_id, $count, $dse_name, $draw, $user_id );
		
		} // if (empty($_POST['ID_yarus_item']))  
		else 
		{
			dbquery("UPDATE okb_db_sklades_detitem SET NAME = '" . mysql_real_escape_string($_POST['NAME']) . "',
														KOMM = '" . mysql_real_escape_string($_POST['KOMM']) . "',
														COUNT = " . (int) $_POST['COUNT'] . "
															WHERE ID = " . (int) $_POST['ID_yarus_item'] . " AND OTK_STATUS != 1");
		} // if (empty($_POST['ID_yarus_item'])) 
			// else
					
		break;
	case 'move':
		if (!$can_edit_sklad) return;
	
		$user_info = GetUserInfo( $user_id );
		$user_name = $user_info['name'];
		$user_gender = $user_info['gender'];

		if( $user_gender == 1 || $user_gender == 0 )
			$action = conv2("переместил ДСЕ");
			 else
			 	$action = conv2("переместила ДСЕ");

		$subaction = conv2("в количестве");
		$subaction2 = conv2("шт.");

		foreach ($_POST['ID_yarus_items'] as $yarus_item) 
		{
			$old_tier = GetTierId( $yarus_item );
			$new_tier = $_POST['ID_yarus'];

			$query = "SELECT MAX(ORD) FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . (int) $_POST['ID_yarus'];

			$ord = mysql_result( dbquery( $query ), 0 );
		
			$query = "UPDATE okb_db_sklades_detitem SET ID_sklades_yarus = " . (int) $_POST['ID_yarus'] . ",
														ORD = " . ($ord + 1) . "
						WHERE ID = " . (int) $yarus_item . " AND OTK_STATUS != 1";
			dbquery( $query );


			$query = "SELECT COUNT FROM okb_db_sklades_detitem WHERE ID = " . (int) $yarus_item;
			$count = mysql_result( dbquery( $query ), 0 );

			$query = "SELECT ref_id FROM okb_db_sklades_detitem WHERE ID = " . (int) $yarus_item;
			$id = mysql_result( dbquery( $query ), 0 );

			$query = "SELECT storage_place FROM okb_db_semifinished_store_invoices WHERE id = $id";
			$json = mysql_result( dbquery( $query ), 0 );
			$arr = json_decode( $json, true );
			$tier_id = $_POST['ID_yarus'];

			$query = "SELECT ID_sklad_item FROM okb_db_sklades_yaruses WHERE ID = $tier_id";
			$cell_id = mysql_result( dbquery( $query ), 0 );

			$query = "SELECT ID_sklad FROM okb_db_sklades_item WHERE ID = $cell_id";
			$wh_id = mysql_result( dbquery( $query ), 0 );

			$new_count = 0 ;

			foreach( $arr AS $key => $value )
			{
				$new_count += $value['count'];
				if( + $arr[$key]['id'] == + $yarus_item )
				{
					$arr[ $key ]['wh'] = $wh_id;
					$arr[ $key ]['cell'] = $cell_id;
					$arr[ $key ]['tier'] = $tier_id;
				}
			}

			$query = "UPDATE okb_db_semifinished_store_invoices 
					SET storage_place = '".json_encode( $arr )."', count = $new_count
						WHERE id = $id";
			dbquery( $query );
			
			$query = "SELECT id_zakdet FROM okb_db_semifinished_store_invoices
					  WHERE id = $id";
			$result = dbquery( $query );
			$id_zakdet = 0;

			if( $row = mysql_fetch_assoc($result) )
				$id_zakdet = $row['id_zakdet'];

			$dse_name = GetDSEName( $yarus_item );

			$comm = "$user_name $action $dse_name $subaction $count $subaction2";
			FixActionInHistory( WH_MOVING, $user_id, $id_zakdet, $dse_name, $count, $comm, $old_tier, $new_tier );
		}
		
		UpdateYarusItemORD($_POST['ID_yarus']);
		UpdateYarusItemORD($_POST['ID_yarus_from']);
		break;
	case 'remove':
		if (!$can_edit_sklad) 
			return;

		$user_info = GetUserInfo( $user_id );
		$user_name = $user_info['name'];
		$user_gender = $user_info['gender'];

		if( $user_gender == 1 || $user_gender == 0 )
			$action = conv2("удалил ДСЕ :");
			 else
			 	$action = conv2("удалила ДСЕ :");
		
		$subaction = conv2("в количестве");
		$subaction2 = conv2("шт. ");

		foreach($_POST['ID_yarus_items'] as $yarus_item) 
		{
			$tier_id = GetTierId( $yarus_item );
			$dse_name = GetDSEName( $yarus_item );

			$query = "	SELECT inv.id_zakdet, detitem.COUNT AS count
						FROM okb_db_sklades_detitem AS detitem
						LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id = detitem.ref_id
					  	WHERE detitem.id = $yarus_item"  ;
			$result = dbquery( $query );
			$row = mysql_fetch_assoc($result);
			$id_zakdet = isset( $row['id_zakdet'] ) ? $row['id_zakdet'] : 0 ;
			$count = $row['count'] ;

			$query = "	 SELECT ref_id
						 FROM okb_db_sklades_detitem
						 WHERE ID = $yarus_item";
			$result = dbquery( $query );
			$row = mysql_fetch_assoc($result);
		    $ref_id = $row['ref_id'];

			dbquery("DELETE FROM okb_db_sklades_detitem WHERE ID = " . (int) $yarus_item . " AND OTK_STATUS != 1");
			
			dbquery("DELETE FROM okb_db_semifinished_store_invoices  
			 		 WHERE id = $ref_id");

			$comm = "$user_name $action $dse_name $subaction $count $subaction2";
			FixActionInHistory( WH_REMOVE, $user_id, $id_zakdet, $dse_name, $count, $comm, $tier_id );
		}
		
		UpdateYarusItemORD($_POST['ID_yarus']);
		break;
	case 'otk_confirm':
		if (!$can_edit_otk) return;
		
		foreach($_POST['ID_yarus_items'] as $yarus_item) {
			dbquery("UPDATE okb_db_sklades_detitem SET OTK_STATUS = 1 WHERE ID = " . (int) $yarus_item);
		}
		break;
	case 'otk_confirm_remove':
		if (!$can_edit_otk) return;
	
		foreach($_POST['ID_yarus_items'] as $yarus_item) {
			dbquery("UPDATE okb_db_sklades_detitem SET OTK_STATUS = 0 WHERE ID = " . (int) $yarus_item);
		}
		break;
	case 'search_item':
		if (strlen($_GET['text']) < 3 || !$can_edit_sklad) return;

		$_GET['text'] = iconv('utf-8', 'windows-1251', urldecode($_GET['text']));
		
		$query = "	SELECT 
							zakdet.ID AS zakdet_id,
							zakdet.NAME AS NAME,
							zakdet.OBOZ AS OBOZ,
							zak.NAME AS zak_name,
							zak_type.description AS zak_type
					FROM okb_db_zakdet AS zakdet
					LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.ID_zak
					LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
					WHERE 
					zakdet.NAME LIKE '%" . $_GET['text'] . "%' OR OBOZ LIKE '%" . $_GET['text'] . "%'
					ORDER BY zak_name DESC
					";


		$result = dbquery( $query );
		
		while ($row = mysql_fetch_assoc($result)) {
			$zakdet_id = $row['zakdet_id'];
			$name = htmlspecialchars($row['NAME']);
			$oboz = htmlspecialchars($row['OBOZ']);
			$zak_type = $row['zak_type'];
			$zak_name = $row['zak_name'];			

			// echo '<option value="' . $name . '  -  ' . $oboz . '">'."$zak_type $zak_name " . substr($name, 0, 20) . ' - ' .  substr($oboz, 0, 20) . "</option>";

			echo '<option value="'.$zakdet_id.'">'."$zak_type $zak_name " . substr($name, 0, 50) . ' - ' .  substr($oboz, 0, 50) . "</option>";

		}
		break;
	case 'search_sklad':

		if (strlen($_GET['text']) < 3) return;

		$_GET['text'] = iconv('utf-8', 'windows-1251', urldecode($_GET['text']));
		$wh_id = $_GET['wh_id'];

		if( $wh_id == 0 )
			$wh_id = "s.ID";

		$result = dbquery("SELECT si.ID AS cell_id, sy.ID AS tier_id, sd.NAME,sd.KOMM,sy.ORD as Yarus,si.NAME as BoxName,s.NAME as SkladName FROM okb_db_sklades_detitem sd
							LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID = sd.ID_sklades_yarus
							LEFT JOIN okb_db_sklades_item si ON si.ID = sy.ID_sklad_item
							LEFT JOIN okb_db_sklades s ON s.ID = si.ID_sklad
								WHERE 
								(
								sd.NAME LIKE '%" . $_GET['text'] . "%' 
								OR 
								sd.KOMM LIKE '%" . $_GET['text'] . "%'
								$wh_where
								)
								AND s.ID = $wh_id
								");
	
		while ($row = mysql_fetch_assoc($result)) {
			$name = htmlspecialchars($row['NAME']);
			$oboz = htmlspecialchars($row['KOMM']);
			$cell_id = $row['cell_id'];
			$tier_id = $row['tier_id'];

			echo '<option data-cell-id="'.$cell_id.'" data-tier-id="'.$tier_id.'" value="' . $name . '  -  ' . $oboz . '">' . conv2('Склад') . ': ' . $row['SkladName'] . ' (' . $row['BoxName'] . ' - ' . ($row['Yarus'] == 0 ? conv2('Пол') : $row['Yarus']) . ') ' . substr($name, 0, 20) . ' - ' .  substr($oboz, 0, 20) . '</option>';
		}
		break;

} // switch($_GET['mode'])

