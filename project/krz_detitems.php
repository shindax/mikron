<?php
class krz_detitems
{
	var $table_krz = 'okb_db_krz';
	var $table_krzdet = 'okb_db_krzdet';
	var $table_krzdetitems = 'okb_db_krzdetitems';
	var $table_rights = 'okb_rightgroups';
	var $table_zak = 'okb_db_zak';
	var $table_zak_type = 'okb_db_zak_type';
	
	var $array_table_html = '';
	var $array_table_html_edit = '';
	
	var $user = '';
	
	var $load_class_css = '<style>
						TD.rwField INPUT.load_fild{
							background-image: url(project/krz_js/spin.svg);
							background-repeat: no-repeat;
							background-position: right;
						}
						</style>';
	
	function __construct()
	{
		$ceil_array = array('Назад в КРЗ', 'Просмотр позиций на ДСЕ', 'Показатель', 'Цена ед. изм. без НДС', 'Ед. изм.', 'На ед.');
		
		$array_table_html = array();
		$array_table_html[] = array('name'=>'Вес детали', 'unit'=>'кг', 'db_field'=>'VES');
		$array_table_html[] = array('name'=>'Разработка', 'childs'=>array(0=>array('name'=>'Разработка КД на изделие', 'unit'=>'Н/Ч', 'db_field'=>'D1'),
																		  1=>array('name'=>'Разработка КД на инструмент и оснастку', 'unit'=>'Н/Ч', 'db_field'=>'D2')));
		$array_table_html[] = array('name'=>'Производство', 'childs'=>array(0=>array('name'=>'Заготовка', 'unit'=>'Н/Ч', 'db_field'=>'D3'),
																			1=>array('name'=>'Сборка-сварка', 'unit'=>'Н/Ч', 'db_field'=>'D4'),
																			2=>array('name'=>'Механообработка', 'unit'=>'Н/Ч', 'db_field'=>'D5'),
																			3=>array('name'=>'Сборка', 'unit'=>'Н/Ч', 'db_field'=>'D6'),
																			4=>array('name'=>'Термообработка', 'unit'=>'Н/Ч', 'db_field'=>'D7'),
																			5=>array('name'=>'Упаковка', 'unit'=>'Н/Ч', 'db_field'=>'D8'),
																			6=>array('name'=>'Окраска', 'unit'=>'Н/Ч', 'db_field'=>'D9'),
																			7=>array('name'=>'Штамповка', 'unit'=>'Н/Ч', 'db_field'=>'D10'),
																			8=>array('name'=>'Оснастка', 'unit'=>'Н/Ч', 'db_field'=>'D11')));
		
		$array_table_html_edit = array();
		$array_table_html_edit[] = array('unit'=>'кг.', 'tid'=>0, 'name'=>'ТМЦ на изделие и упаковку (в том числе вспомогательные)');
		$array_table_html_edit[] = array('unit'=>'кг.', 'tid'=>1, 'name'=>'ТМЦ на специнструмент и оснащение');
		$array_table_html_edit[] = array('unit'=>'руб.', 'tid'=>6, 'name'=>'Покупные изделия');
		$array_table_html_edit[] = array('unit'=>'руб.', 'tid'=>2, 'name'=>'Кооперация');
		$array_table_html_edit[] = array('unit'=>'руб.', 'tid'=>3, 'name'=>'Транспорт');
		$array_table_html_edit[] = array('unit'=>'руб.', 'tid'=>4, 'name'=>'Коммерческие расходы');
		$array_table_html_edit[] = array('unit'=>'руб.', 'tid'=>5, 'name'=>'Спецмероприятия по ИС');
		
		$this->ceil_array = $this->decode_array($ceil_array);
		$this->array_table_html = $this->decode_array($array_table_html);
		$this->array_table_html_edit = $this->decode_array($array_table_html_edit);
		
		// include_once('/../classes/functions.php');
		// include('/../classes/ClassMysql.inc.php');
		// include('/../classes/ClassSelector.inc.php');
		// include('/../classes/ClassJSON.inc.php');
		// include_once('/../classes/ClassSESS.inc.php');
		
		
		// $host		= '127.0.0.1';
		// $base		= 'okbdb';
		// $user		= 'root';
		// $pass		= '';
		// $pconnect	= false;
		
		include_once($_SERVER['DOCUMENT_ROOT'].'/classes/functions.php');
		include($_SERVER['DOCUMENT_ROOT'].'/classes/ClassMysql.inc.php');
		include($_SERVER['DOCUMENT_ROOT'].'/classes/ClassSelector.inc.php');
		include($_SERVER['DOCUMENT_ROOT'].'/classes/ClassJSON.inc.php');
		include_once($_SERVER['DOCUMENT_ROOT'].'/classes/ClassSESS.inc.php');

		$host		= '127.0.0.1';
		$base		= 'okbdb';
		$user		= 'okbmikron';
		$pass		= 'fm2TU9IMTB_hnI0Z';
		$pconnect	= false;

		global $db;
				
		$db = new Mysql($host, $base, $user, $pass, $pconnect);
		
		$this->db = $db;
		
		$this->user = SESS::get('user');
		
		if(isAjax())
		{
			if(isset($_POST['order_id']))
				$this->ajax_orders();
			elseif($this->user_permission_check($this->table_krzdet) == true)
				$this->ajax_send();
			
			exit;
		}
		
		$this->index();
	}
	
	function index()
	{
		$user_write_permission = $this->user_permission_check($this->table_krzdet);
		
		$id = (int)$_GET["id"];
		
		$item = $this->db->select($this->table_krzdet, null, array('ID'=>$id));
		
		valid($item);
		
		$edit_state = $this->db->select($this->table_krz, 'EDIT_STATE', array('ID'=>$item['ID_krz']));
		
		if(isset($edit_state) and $edit_state == 1)
			$user_write_permission = '';
		
		// $html = '<script>var error_field = "'.iconv('utf-8', 'cp1251', 'Поле Показатель не может быть пустым!').'"; var confirm_send = "'.iconv('utf-8', 'cp1251', 'Удалить запись!').'"; var ID_krzdet = "'.$id.'"; var u_w = "'.$user_write_permission.'"; var del_alt = "'.iconv('utf-8', 'cp1251', 'Удалить строку').'";</script>
		// <script type="text/javascript" src="project/krz_js/krz_detitems.js"></script>';
		
		$html = '<script>var error_field = "'.iconv('utf-8', 'cp1251', 'Поле Показатель не может быть пустым!').'"; var confirm_send = "'.iconv('utf-8', 'cp1251', 'Удалить запись!').'"; var confirm_add_1 = "'.iconv('utf-8', 'cp1251', 'Добавить строк: ').'"; var confirm_add_2 = "'.iconv('utf-8', 'cp1251', ' шт.').'"; var ID_krzdet = "'.$id.'"; var u_w = "'.$user_write_permission.'"; var del_alt = "'.iconv('utf-8', 'cp1251', 'Удалить строку').'";</script>
		<script type="text/javascript" src="project/krz_js/krz_detitems.js"></script>';
		
		if ($GLOBALS['print_mode'] == "off")
			$html .= '<div class="links"><a href="index.php?do=show&formid=7&id='.$item['ID_krz'].'">'.$this->ceil_array[0].'</a></div>';

		$html .= '<h2>'.$this->ceil_array[1].'</h2>';
		$html .= '<span class="line">'.$item['NAME'].' - '.$item['OBOZ'].'</span><br><br>';
		
		$html .= '<table class="rdtbl tbl" style="border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1000px;" border="1" cellpadding="0" cellspacing="0">
				<thead>
					<tr class="first">
						<td class="nbg" width="27"></td>
						<td>'.$this->ceil_array[2].'</td>
						<td width="200">'.$this->ceil_array[3].'</td>
						<td width="30">'.$this->ceil_array[4].'</td>
						<td width="200">'.$this->ceil_array[5].'</td>
						<td width="27"></td>
					</tr>
				</thead>
				<tbody>';
					
		foreach($this->array_table_html as $key=>&$row)
		{
			if(isset($row['childs']))
			{
				$htm = '';
				
				foreach($row['childs'] as &$child)
				{
					$row['summ'] = $row['summ'] + floatval($item[$child['db_field']]);
					
					$htm .= '<tr>
							<td class="nbg"></td>
							<td class="Field" style="text-align: left;">'.$child['name'].'</td>
							<td class="Field"></td>
							<td class="Field">'.$child['unit'].'</td>
							<td class="rwField ntabg">';
							
							if($user_write_permission == 1)
								$htm .= '<input type="number" class="detail" id="'.$child['db_field'].'" value="'.floatval($item[$child['db_field']]).'"/>';
							else
								$htm .= floatval($item[$child['db_field']]);
								
					$htm .= '</td>
							 <td class="Field"></td>
						</tr>';
				}
				
				$html .= '<tr>
							<td class="nbg"></td>
							<td class="Field" colspan="3" style="text-align: left; padding-left: 40px; padding-top: 10px;"><b>'.$row['name'].'</b></td>
							<td class="Field parent_'.$key.'" style="text-align: left; padding-top: 10px;"><b>'.$row['summ'].'</b></td>
							<td class="Field"><a href="#0" class="reload"><img src="style/refresh.png" /></a></td>
						</tr>';
				
				$html .= $htm;
			}
			else
			{
				$html .= '<tr>
							<td class="nbg"></td>
							<td class="Field" style="text-align: left;">'.$row['name'].'</td>
							<td class="Field"></td>
							<td class="Field">'.$row['unit'].'</td>';
							
							if(isset($row['db_field']))
							{
								if($user_write_permission == 1)
									$html .= '<td class="rwField ntabg"><input type="number" class="detail" id="'.$row['db_field'].'" value="'.$item[$row['db_field']].'"/></td>';
								else
									$html .= '<td class="rwField ntabg">'.$item[$row['db_field']].'</td>';
							}
							else
							{
								if($user_write_permission == 1)
									$html .= '<td class="rwField ntabg"><input type="number" class="detail" id="'.$row['db_field'].'" value=""/></td>';
								else
									$html .= '<td class="rwField ntabg"></td>';
							}
							
							$html .= '<td class="Field"></td>
						</tr>';
			}
		}
		
		$html .= $this->get_edit_table($user_write_permission);
		
		$html .= '</tbody>
				</table>
				<div style="width: 100%; text-align: right;"><b>'.iconv('utf-8', 'cp1251', 'Все цены указывать без НДС').'</b></div>';
	
		echo $this->load_class_css;
		echo $html;
		exit;
	}
	
	private function get_edit_table($user_write_permission)
	{
		$tid_list = array();
		
		foreach($this->array_table_html_edit as &$row)
			$tid_list[] = $row['tid'];
		
		$child_list = new Selector($this->table_krzdetitems);
		$child_list->setWhere('ID_krzdet = ', (int)$_GET["id"]);
		$child_list->setWhere('TID IN', $tid_list);
		$child_list->setOrder('ID');
		$child_list = $child_list->getResult();
		
		if(!empty($child_list))
		{
			foreach($this->array_table_html_edit as &$row)
			{
				foreach($child_list as &$ro)
				{
					if($ro['TID'] == $row['tid'])
						$row['childs'][] = $ro;
				}
			}
			
		}
		
		$edit_table_html = '';
		
		foreach($this->array_table_html_edit as &$row)
		{
			$edit_table_html .= '<tr class="add_'.$row['tid'].'">
				<td class="nbg" style="padding:2px 0 2px 0; text-align:left;">
					<span>';
			
			if($user_write_permission == 1)
				$edit_table_html .= '<input type="button" class="add_str" value="+" />
									<input type="number" style="width:20px;" value="1"/>
									<input type="hidden" name="'.$row['unit'].'" value="'.$row['tid'].'" />';
			
			$edit_table_html .= '</span>
				</td>
				<td class="Field" colspan="4" style="text-align: left; padding-left: 40px; padding-top: 10px;">
					<b>'.$row['name'].'</b>
				</td>
				<td class="Field"></td>
			</tr>';
			
			if(isset($row['childs']))
			{
				foreach($row['childs'] as &$ro)
				{
					if($user_write_permission == 1)
						$edit_table_html .= '<tr class="cl_1 from_db child_'.$row['tid'].'" id="'.$ro['ID'].'">
							<input type="hidden" value="'.$row['tid'].'" />
							<td class="nbg"></td>
							<td class="rwField ntabg">
								<input type="text" name="name" value="'.htmlspecialchars($ro['NAME']).'" class="input_child" />
							</td>
							<td class="rwField ntabg">
								<input type="number" name="price" value="'.$ro['PRICE'].'" class="input_child" />
							</td>
							<td class="Field">'.$row['unit'].'</td>
							<td class="rwField ntabg">
								<input type="number" name="count" value="'.$ro['COUNT'].'" class="input_child" />
							</td>
							<td class="Field">
								<a href="#0" class="del" title="'.iconv('utf-8', 'cp1251', 'Удалить строку').'">
									<img src="uses/del.png" alt="'.iconv('utf-8', 'cp1251', 'Удалить строку').'"/>
								</a>
							</td>
						</tr>';
					else
						$edit_table_html .= '<tr class="cl_1 from_db child_'.$row['tid'].'" id="'.$ro['ID'].'">
							<td class="nbg"></td>
							<td class="rwField ntabg">'.$ro['NAME'].'</td>
							<td class="rwField ntabg">'.$ro['PRICE'].'</td>
							<td class="Field">'.$row['unit'].'</td>
							<td class="rwField ntabg">'.$ro['COUNT'].'</td>
							<td class="Field"></td>
						</tr>';
				}
			}
		}
		
		return $edit_table_html;
	}
	
	private function decode_array($arr)
	{
		foreach($arr as &$row)
		{
			if(isset($row['name']))
				$row['name'] = iconv('utf-8', 'cp1251', $row['name']);
			
			if(isset($row['unit']))
				$row['unit'] = iconv('utf-8', 'cp1251', $row['unit']);
			
			if(isset($row['childs']))
			{
				foreach($row['childs'] as &$ro)
				{
					$ro['name'] = iconv('utf-8', 'cp1251', $ro['name']);
					$ro['unit'] = iconv('utf-8', 'cp1251', $ro['unit']);
				}
			}
			elseif(!is_array($row))
				$row = iconv('utf-8', 'cp1251', $row);
		}
		
		return $arr;
	}
	
	private function user_permission_check($table)
	{
		$table = str_replace('okb_', '', $table);
		
		$write = explode('|', $this->user['ID_rightgroups']);
		$read = explode('|', $this->user['ID_forms']);
		
		foreach($write as &$row)
		{
			$row = (int)$row;
		}
		
		foreach($read as &$row)
		{
			$row = (int)$row;
		}
		
		array_pop($write);
		array_pop($read);
		
		array_shift($write);
		array_shift($read);
		
		$write_list = new Selector($this->table_rights);
		$write_list->setColumn('RIGHTS');
		$write_list->setWhere('ID IN', $write);
		$write_list = $write_list->getResult();
		if(!empty($write_list))
		{
			$special_tables = array('users', 'rightgroups', 'viewgroups', 'formgroups', 'forms', 'formsitem');
			
			$write_list['search_string'] = '';
			
			foreach($write_list as $row)
			{
				if(isset($row['RIGHTS']))
					$write_list['search_string'] .= $row['RIGHTS'];
			}
			
			if($this->user['print_mode'] == 'on')
				return false;
			elseif((int)$this->user['STATE'] == 1)
				return false;
			elseif(strripos($write_list['search_string'], "superadmin") !== false or strripos($write_list['search_string'], $table."|superadmin") !== false or strripos($write_list['search_string'], $table."|add") !== false)
				return true;
			elseif((int)$this->user['ID'] == 1 and in_array($table, $special_tables))
				return true;
			else
				return false;
		}
	}
	
	private function ajax_send()
	{
		$json = new JSON(true);
		
		if(isset($_POST['del']) and $_POST['del'] == 1)
		{
			if(isset($_POST['id']))
			{
				$this->db->delete($this->table_krzdetitems, array('id'=>$_POST['id']));
				
				if(!SESS::get('add_del'))
					SESS::set('add_del', 1);
				
				$result = 1;
			}
			elseif(SESS::get('add_del'))
			{
				SESS::uset('add_del');
				
				$result = 0;
			}
		}
		elseif(isset($_POST['field_id']))
		{
			$this->db->update($this->table_krzdet, array($_POST['field_id']=>floatval($_POST['field_value'])), array('ID'=>$_POST['ID_krzdet']));
			
			$detail = $this->db->select($this->table_krzdet, null, array('ID'=>$_POST['ID_krzdet']));
			
			$result = array();
			
			foreach($this->array_table_html as $key=>&$row)
			{
				if(isset($row['childs']))
				{
					$summ = 0;
					
					foreach($row['childs'] as &$child)
					{
						$summ = $summ + floatval($detail[$child['db_field']]);
					}
					
					$result[] = array('class'=>'parent_'.$key, 'summ'=>$summ);
				}
			}
			
			if(!SESS::get('add_del'))
				SESS::set('add_del', 1);
				
		}
		else
		{
			$field = '';
			
			if($_POST['field_name'] == 'name')
			{
				$_POST['field_value'] = iconv('utf-8', 'cp1251', $_POST['field_value']);
				$field = 'NAME';
			}
			elseif($_POST['field_name'] == 'price')
				$field = 'PRICE';
			elseif($_POST['field_name'] == 'count')
				$field = 'COUNT';
			else
				exit;
			
			$result = [];
			
			if($_POST['from_db'] == 0)
			{
				$str_id = $this->db->insert($this->table_krzdetitems, array($field=>$_POST['field_value'], 'ID_krzdet'=>$_POST['ID_krzdet'], 'TID'=>$_POST['tid']));
				
				if(!SESS::get('add_del'))
					SESS::set('add_del', 1);
				
				$result['id'] = $str_id;
			
				$result['from_db'] = 1;
			}
			elseif($_POST['id'] > 0)
			{
				$this->db->update($this->table_krzdetitems, array($field=>$_POST['field_value']), array('id'=>$_POST['id']));
				
				if(!SESS::get('add_del'))
					SESS::set('add_del', 1);
				
				$result = 1;
			}
			else
				$result = 0;
		}
		
		if(isset($result))
			echo $json->encode($result);
		
		exit;
	}
	
	private function ajax_orders()
	{
		$json = new JSON(true);
		
		if(isset($_POST['order_id']))
		{
			$order_list = array_unique(array_diff($json->decode($_POST['order_id']), array('')));
			
			if(!empty($order_list))
			{
				$order_name_list = new Selector($this->table_zak.' as zak');
				
				$join = $order_name_list->setJoin($this->table_zak_type.' as tid_name', 'left');
				$join->setOn('tid_name.id = zak.TID');
				
				$order_name_list->setColumn('zak.ID');
				$order_name_list->setColumn('zak.NAME');
				$order_name_list->setColumn('tid_name.description');
				$order_name_list->setWhere('zak.ID IN', $order_list);
				$order_name_list = $order_name_list->getResult();
				
				$new_name_list = array();
				
				foreach($order_name_list as $row)
					$new_name_list[$row['ID']] = array('description'=>$row['description'], 'name'=>iconv('cp1251', 'utf-8', $row['NAME']));
				
				echo $json->encode($new_name_list);
			}
		}
		
		exit;
	}
}
$krz = new krz_detitems();
?>