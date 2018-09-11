<?php
class storage_archive
{
	function __construct()
	{
		include_once($_SERVER['DOCUMENT_ROOT'].'/classes/functions.php');
		include($_SERVER['DOCUMENT_ROOT'].'/classes/ClassMysql.inc.php');
		include($_SERVER['DOCUMENT_ROOT'].'/classes/ClassSelector.inc.php');
		include($_SERVER['DOCUMENT_ROOT'].'/classes/ClassJSON.inc.php');

		$host		= '127.0.0.1';
		$base		= 'okbdb';
		$user		= 'okbmikron';
		$pass		= 'fm2TU9IMTB_hnI0Z';
		$pconnect	= false;

		global $db;
				
		$db = new Mysql($host, $base, $user, $pass, $pconnect);
		
		$this->db = $db;
		
		if(isAjax())
		{
			$this->ajax_send();
		}
		
		$this->index_orders();
	}

	function index_orders()
	{
		print'<script>
			var order_n = "'.iconv('utf-8', 'cp1251', '№ заказа').'";
			var error_client = "'.iconv('utf-8', 'cp1251', 'Заказчик не выбран!').'";
			var error_date = "'.iconv('utf-8', 'cp1251', 'Дата не указана или заполнена не корректно!').'";
			var error_count = "'.iconv('utf-8', 'cp1251', 'Количество позиций указанно не корректно!').'";
			var confirm_send = "'.iconv('utf-8', 'cp1251', 'Внести изменение?').'";
			</script>';
		
		$res = new Selector('okb_db_zak as ord');

		$join = $res->setJoin('okb_db_zak_type as tid_name', 'left');
		$join->setOn('tid_name.id = ord.TID');

		$join_2 = $res->setJoin('okb_db_clients as clients', 'left');
		$join_2->setOn('clients.id = ord.ID_clients');
		
		$res->setColumn('ord.ID');
		$res->setColumn('tid_name.description as TID');
		$res->setColumn('ord.NAME');
		$res->setColumn('ord.INSZ');
		$res->setColumn('ord.PRIOR');
		$res->setColumn('ord.INGANT');
		$res->setColumn('ord.DSE_NAME');
		$res->setColumn('ord.DSE_OBOZ');
		$res->setColumn('ord.DSE_COUNT');
		$res->setColumn('ord.ID_clients');
		$res->setColumn('(SELECT SUM(order_count) FROM okb_db_orders_ship WHERE EXISTS(SELECT id FROM okb_db_orders_ship WHERE order_id = ord.ID) and order_id = ord.ID) as summ');
		$res->setColumn('clients.NAME as client_name');
		$res->setWhere('EXISTS((SELECT * FROM okb_db_orders_ship WHERE order_id = ord.ID)) and (SELECT SUM(order_count) FROM okb_db_orders_ship WHERE order_id = ord.ID) = ord.DSE_COUNT');
		$res->setWhere('ord.EDIT_STATE = 3');
		
		$res = $res->getResult();
		
		// var_dump($res);
		// exit;
		
		if(!empty($res))
		{
			/* echo 1;
			exit; */
			$html = '<h2>'.iconv('utf-8', 'cp1251', 'В архиве').'</h2>
					<table class="rdtbl tbl" style="border-collapse: collapse; border: 0px solid black; text-align: left; color: rgb(0, 0, 0); width: 1250px; padding: 0px;">
						<thead>
							<tr class="first">
								<td>'.iconv('utf-8', 'cp1251', 'Вид заказа').'</td>
								<td class="ord_n">'.iconv('utf-8', 'cp1251', '№ заказа').'</td>
								<td>'.iconv('utf-8', 'cp1251', 'Наимен. чертежа').'</td>
								<td>'.iconv('utf-8', 'cp1251', 'Номер чертежа').'</td>
								<td>'.iconv('utf-8', 'cp1251', 'Заказчик').'</td>
								<td>'.iconv('utf-8', 'cp1251', 'Кол-во').'</td>
							</tr>
						</thead>
							<tbody>';
			foreach($res as &$row)
			{
				// var_dump($row);
				// exit;
				if($row['summ'] !== null and $row['summ'] == $row['DSE_COUNT'])
				{
					$html .= '<tr data-proj data-zak data-user-id="" data-id="'.$row['ID'].'" id="'.$row['ID'].'">
									<td id="type" class="Field">
										<a href="javascript:void(0)">'.$row['TID'].'</a>
									</td>
									<td class="Field">
										<a href="index.php?do=show&formid=39&id='.$row['ID'].'"><img src="uses/view.gif"/></a><div class="order_name">'.$row['NAME'].'</div>
									</td>
									<td id="dse_name" class="Field">
										<a href="javascript:void(0)">'.$row['DSE_NAME'].'</a>
									</td>
									<td class="Field">'.$row['DSE_OBOZ'].'</td>
									<td id="ID_clients" class="Field">
										<a href="javascript:void(0)">'.$row['client_name'].'</a>
									</td>
									<td class="Field">
										<a href="#0" class="count">'.(int)$row['summ'].'</a>
									</td>
								</tr>';
				}
			}
			
			$html .= '</tbody>
					</table>';
			
			print($html);
			
			print('<div id="dialog" style="display:none;" title="">
				<table class="dial_tab" style="width:810px; margin:0 0 30px 20px;">
				</table>
				'.$this->get_form().'</div>');
		}
		else
			print(iconv('utf-8', 'cp1251', '<h3>Нет архивных позиций</h3>'));
	}
	
	private function get_form()
	{
		/* $client_list = new Selector('okb_db_clients');
		$client_list->setColumn('ID');
		$client_list->setColumn('NAME');
		$client_list->setWhere('PZAK = 1');
		$client_list->setOrder('name');
		$client_list = $client_list->getResult();
		
		if(!empty($client_list))
		{
			$html_clients_select = '<select id="client">
									<option value="">'.iconv('utf-8', 'cp1251', 'Выбрать заказчика').'</option>';
			
			foreach($client_list as &$row)
				$html_clients_select .= '<option value="'.$row['ID'].'">'.$row['NAME'].'</option>';
				
			
			$html_clients_select .= '</select>';
			
			return '<form name="order" id="order"><p class="form_fields"><span>'.$html_clients_select.'</span><span><input type="date" name="date"/></span><span><input type="number" name="count" placeholder="'.iconv('utf-8', 'cp1251', 'Кол-во').'" value=""/></span><span><input type="button" value="'.iconv('utf-8', 'cp1251', 'Отправить').'" id="send_ord"/></span></p></form>';
		}
		else */
			return '';
	}
	
	private function ajax_send()
	{
		$json = new JSON(true);
		
		$all_count = $this->db->select('okb_db_zak', 'DSE_COUNT', array('ID'=>$_POST['order_id']));
		
		if(isset($_POST['count_goods_result']))
		{
			
			$ship_count = $this->db->select('okb_db_orders_ship', 'SUM(order_count)', array('order_id'=>$_POST['order_id']));
			
			if(($ship_count + $_POST['count_goods_result']) > $all_count or $_POST['count_goods_result'] < 0)
			{
				echo $json->encode('error_count');
				exit;
			}
		
			$this->db->insert('okb_db_orders_ship', array('order_id'=>$_POST['order_id'], 'client_id'=>$_POST['client_id'], 'date'=>$_POST['date'], 'order_count'=>$_POST['count_goods_result']));
		}
		
		$ship_list = new Selector('okb_db_orders_ship as ship');
		
		$join = $ship_list->setJoin('okb_db_clients as client', 'left');
		$join->setOn('client.ID = ship.client_id');
		
		$ship_list->setColumn('ship.id');
		$ship_list->setColumn('ship.order_id');
		$ship_list->setColumn('ship.client_id');
		$ship_list->setColumn('client.NAME as client_name');
		$ship_list->setColumn('DATE_FORMAT(ship.date, "%d.%m.%Y") as date');
		$ship_list->setColumn('ship.order_count');
		$ship_list->setWhere('ship.order_id =', $_POST['order_id']);
		$ship_list->setOrder('ship.id');
		$ship_list = $ship_list->getResult();
		
		if(!empty($ship_list))
		{
			$ship_html = '<tr><th style="width:410px;"><b>'.iconv('utf-8', 'cp1251', 'Заказчик').'</b></th><th style="width:150px;"><b>'.iconv('utf-8', 'cp1251', 'Дата отправки').'</b></th><th><b>'.iconv('utf-8', 'cp1251', 'Отправлено шт.').'</b></th></tr>';
			
			
			$summ_ship = 0;
			
			foreach($ship_list as &$row)
			{
				$summ_ship = $summ_ship + $row['order_count'];
				$ship_html .= '<tr class="form_fields"><td style="width:410px;">'.$row['client_name'].'</td><td style="width:150px;">'.$row['date'].'</td><td>'.$row['order_count'].'</td></tr>';
			}
			// echo $ship_html;
			// exit;
			
			$result['count'] = $all_count - $summ_ship;
			$result['htm'] = $ship_html;
			
				
			echo $json->encode($result);
			exit;
		}
		else
			exit;
	}
}
$st = new storage_archive();
?>