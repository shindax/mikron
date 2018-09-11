<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
// use Illuminate\Support\Validation;
use App\AllUsers;
use App\OldUsers;
use App\InventoryRoleUsers;
use App\OldInventoryPlaces;
use App\InventoryStructureHeight;
use App\InventoryStructureWidth;
use App\InventorySearch;
use Config;

use Validator;

class UserController extends Controller
{
    public function __construct()
	{
		$this->parametrs = Config::get('parametrs');
	}
	
    public function index()
    {
		// $this->import_search();
		// exit;
		// $ar = '';
		// $this->getMenu();
		// dd($this->ar);
		
		// $par = Config::get('parametrs');
		
		// dd($par['type_fields']);
		// exit;
		
		$user = $this->get_user();
		
		if($user)
		{
			$catalog_list = DB::table('inventory_catalogs')
								->select('id', 'parent_id', 'user_id', 'name')
								->where('disable', 0)
								->orderBy('id')
								->get();
			
			$this->ar = array();
			$this->id_list = array();
			
			$this->get_queue($catalog_list);
			
			if(!empty($this->ar))
			{
				foreach($this->ar as $key=>$row)
				{
					if($key > 0 and $row['parent_id'] > 0)
					{
						$prev_key = $key - 1;
						
						if($row['parent_id'] == $this->ar[$prev_key]['id'] or $row['parent_id'] == $this->ar[$prev_key]['parent_id'])
						{
							if(isset($this->ar[$prev_key]['parent_id_list']))
								$this->ar[$key]['parent_id_list'] = $this->ar[$prev_key]['parent_id_list'];
						}
						
						$this->ar[$key]['parent_id_list'][$row['parent_id']] = $row['parent_id'];
					}
					
					if(isset($this->ar[$key]['parent_id_list']))
						$this->ar[$key]['class'] = implode(' ', $this->ar[$key]['parent_id_list']);
				}
			}
			// dd($this->ar);
			// exit;
			
			$user_list = '';
			
			if($user['role'] == 1 or $user['role'] == 0)
			{
				$user_list = DB::table('users_roles_groups as roles')
							->leftJoin('users', 'users.id', '=', 'roles.user_id')
							->select(DB::raw('MIN(roles.role) as role, roles.user_id as id, users.name, users.email'))
							->where([['roles.user_id', '!=', $user['id']]])
							->when($user['role'] == 1, function($query) use ($user)
									{
										return $query->where([['roles.parent_user', '=', $user['id']]]);
									})
							->groupBy('roles.user_id')
							->orderBy('users.name')
							->get();
				
				// if($user_list->isEmpty() == true)
					// $user_list = '';
			}
			
			$catalog_id_array = array();
			
			$parent_catalog_list = array();
			
			if($user['role'] == 1 or $user['role'] == 2)
			{
				$catalog_id_list = DB::table('users_roles_groups')
									->select('catalog_id')
									->where('user_id', $user['id'])
									->get();
				
				// dd($catalog_id_list);
				
				if($catalog_id_list->isEmpty() == false)
				{
					foreach($catalog_id_list as $row)
						$catalog_id_array[$row->catalog_id] = $row->catalog_id;
				}
				
				// dd($catalog_id_array);
				// dd($this->ar);
				foreach($this->ar as &$catalog)
				{
					if(!isset($catalog['parent_id_list']))
						$catalog['parent_id_list'] = array();
					// if($catalog['user_id'] == $user['id'] or isset($catalog_id_array[$catalog['id']]))
					if(isset($catalog_id_array[$catalog['id']]))
					{
						// print_r(array_intersect_key($catalog_id_array, $catalog['parent_id_list']));
						if(empty(array_intersect_key($catalog_id_array, $catalog['parent_id_list'])))
							$parent_catalog_list[$catalog['id']] = $catalog;
					}
				}
			}
			elseif($user['role'] == 0)
			{
				foreach($this->ar as $catalog)
				{
					if($catalog['parent_id'] == 0)
						$parent_catalog_list[$catalog['id']] = $catalog;
				}
			}
			
			// dd($parent_catalog_list);
			// exit;
			
			
			$old_users = OldUsers::select('ID', 'LOGIN', 'PASS', 'FIO', 'email')->where('STATE', 0)->where('ID', '!=', $user['okbdb_user_id'])->orderBy('FIO')->get();
		
			foreach($old_users as &$row)
				$row->FIO = iconv('cp1251', 'utf-8', $row->FIO);
		
			return view('inventory', array('user' => $user, 'parent_catalog_list' => $parent_catalog_list, 'catalog_chechbox_list' => $this->ar, 'user_list' => $user_list, 'old_users' => $old_users));
		}
	}
	
	public function get_childs_catalog(Request $request)
	{
		if($request->ajax())
		{
			// $this->import_attachment_user();
			// $this->import_search();
			
			$id = (int)$request->input('id');
			$attach_user = (int)$request->input('attach_user');
			
			// return $attach_user;
			// print_r($attach_user);
			// exit;
			
			if($id > 0)
			{
				$result_array = array();
				
				$child_list = DB::table('inventory_catalogs')
							->select('id', 'name')
							->where('parent_id', $id)
							->where('disable', 0)
							->orderBy('name')
							->get();
				
				if($child_list->isEmpty() == false and $attach_user == 0)
				{
					$result_html = '<div role="tablist">';
					
					foreach($child_list as $row)
					{
						$result_html .= '<div class="card alert-primary">
							<div class="card-header pt-1 pb-1" role="tab" data-catalog="'.$row->id.'">
								<h5 class="float-left mt-2">
									<a class="collapsed text-dark" href="#0">'.$row->name.'</a>
								</h5>
								<div class="btn-toolbar add_edit_delete">
									<div class="btn-group btn-group-sm float-right" role="group">
										<button type="button" class="btn btn-outline-success add_child" title="Добваить дочерний раздел"><span class="oi oi-plus"></span></button>
										<button type="button" class="btn btn-outline-warning edit_catalog" title="Редактировать раздел"><span class="oi oi-pencil"></span></button>
										<button type="button" class="btn btn-outline-danger delete_catalog" title="Удалить раздел"><span class="oi oi-trash"></span></button>
									</div>
									<div class="btn-group btn-group-sm float-right mr-4" role="group">
										<button type="button" class="btn btn-outline-secondary add_string" title="Добваить строку в таблице"><span class="oi oi-spreadsheet"></span></button>
										<button type="button" class="btn btn-outline-info modal_structure" title="Структура таблицы"><span class="oi oi-fork"></span></button>
									</div>
									<div class="btn-group btn-group-sm float-right mr-4" role="group">
										<button type="button" class="btn btn-outline-primary table_list" title="Ведомость"><span class="oi oi-document"></span></button>
									</div>
								</div>
							</div>
							<div data-catalog-body="'.$row->id.'" class="collapse">
								<div id="catalog_'.$row->id.'" class="card-body">
								</div>
							</div>
						</div>
						';
					}
					
					$result_html .= '</div>';
					
					$result_array['result_html'] = $result_html;
				}
				
				$result_array['structure_table'] = $this->get_structure_table($id);
				
				$result_array['table'] = $this->get_table($id, $result_array['structure_table']);
				
				$all_users = AllUsers::select('ID', 'NAME', 'FF', 'II', 'OO')->orderBy('NAME')->get();
				
				$all_users_array = array();
				
				foreach($all_users as &$row)
					$all_users_array[$row->ID] = array('name'=>iconv('cp1251', 'utf-8', $row->NAME), 'full_name'=>iconv('cp1251', 'utf-8', $row->FF).' '.iconv('cp1251', 'utf-8', $row->II).' '.iconv('cp1251', 'utf-8', $row->OO));
				
				// print_r($result_array['table']);
				// exit;
				
				if(!empty($this->ar))
				{
					if($attach_user > 0)
					{
						foreach($this->ar as $key=>&$row)
						{
							if($row['attach_user_id'] !== $attach_user)
							{
								unset($this->ar[$key]);
								unset($result_array['table'][$key]);
							}
							else
								$row['attach_user'] = $all_users_array[$row['attach_user_id']];
						}
					}
					else
					{
						foreach($this->ar as &$row)
						{
							if($row['attach_user_id'] > 0)
								$row['attach_user'] = $all_users_array[$row['attach_user_id']];
						}
					}
				}
				
				$result_array['files'] = $this->ar;
				
				// print_r($this->ar);
				if(isset($result_array['structure_table']))
					$result_array['order_structure'] = array_keys($result_array['structure_table']);
				
				// array_shift($result_array['order_structure']);
				
				// print_r($result_array['order_structure']);
				// exit;
				
				return $result_array;
			}
			else
				return 0;
		}
		else
			return 0;
	}
	
	public function get_structure(Request $request)
	{
		if($request->ajax())
		{
			$result_array = array();
			
			$catalog_id = (int)$request->input('catalog_id');
			
			$result_build = $this->build_structure_tree_table($catalog_id);
			
			if(isset($result_build['structure_html']))
				$result_array['structure_html'] = $result_build['structure_html'];
			
			$result_array['select_structure_html'] = $result_build['select_structure_html'];
			
			$selection_custom = $this->get_selection_custom($catalog_id, null, $this->get_user());
			
			$result_array['selection_custom'] = $this->get_html_selection_custom($selection_custom);
			
			$result_array['select_type_fields'] = $this->get_type_fields_select($catalog_id, $selection_custom);
			
			return $result_array;
		}
		else
			return 0;
	}
	
	
	public function set_structure(Request $request)
	{
		if($request->ajax())
		{
			$delete = (int)$request->input('delete');
			$id = (int)$request->input('id');
			$parent_id = 0;
			$parent_id = (int)$request->input('parent_id');
			$catalog_id = (int)$request->input('catalog_id');
			$type_field = $request->input('type_field');
			$name = $request->input('name');
			$selection_id = (int)$request->input('selection_id');
			$log_flag = (int)$request->input('log_flag');
			
			$result_array = array();
			
			if($delete > 0)
			{
				$validator = Validator::make($request->all(), ['delete' => 'exists:inventory_table_structure,id']);
				
				if(!$validator->fails())
				{
					DB::table('inventory_table_structure')->where('id', $delete)->update(['disable'=>1]);
					InventorySearch::where('structure_id', $delete)->update(['disable'=>1]);
					
					$this->child_id_list = array();
					
					$this->get_child_structure('inventory_table_structure', $delete);
					
					if(!empty($this->child_id_list))
						DB::table('inventory_table_structure')->whereIn('id', $this->child_id_list)->update(['disable'=>1]);
					
					$structure = DB::table('inventory_table_structure')
								->where('catalog_id', $catalog_id)
								->where('disable', 0)
								->orderBy('position')
								->select('id')
								->get();
					
					$structure_array = array();
					
					foreach($structure as $row)
						$structure_array[] = $row->id;
					
					DB::table('inventory_table_structure_height')->whereIn('structure_id', $structure_array)->delete();
					DB::table('inventory_table_structure_width')->whereIn('structure_id', $structure_array)->delete();
				}
				else
					return 0;
			}
			elseif($id > 0)
			{
				$validator = Validator::make($request->all(), ['id' => 'exists:inventory_table_structure']);
				
				if(!$validator->fails())
				{
					$structure = DB::table('inventory_table_structure')
								->where('catalog_id', $catalog_id)
								->where('disable', 0)
								->orderBy('position')
								->select('id')
								->get();
					
					$structure_array = array();
					
					foreach($structure as $row)
						$structure_array[] = $row->id;
					
					DB::table('inventory_table_structure_height')->whereIn('structure_id', $structure_array)->delete();
					DB::table('inventory_table_structure_width')->whereIn('structure_id', $structure_array)->delete();
					
					DB::table('inventory_table_structure')->where('id', $id)->update(['parent_id'=>$parent_id, 'name'=>$name, 'log_flag'=>$log_flag]);
				}
				else
					return 0;
			}
			else
			{
				$result_array['id'] = DB::table('inventory_table_structure')->insertGetId(['parent_id'=>$parent_id, 'catalog_id'=>$catalog_id, 'type_field'=>$type_field, 'name'=>$name, 'log_flag'=>$log_flag, 'disable'=>0, 'position'=>0]);
				
				if($selection_id > 0)
					DB::table('inventory_selections_cross')->insert(['structure_id'=>$result_array['id'], 'selection_id'=>$selection_id]);
				
				$structure = DB::table('inventory_table_structure')
								->where('catalog_id', $catalog_id)
								->where('disable', 0)
								->orderBy('position')
								->select('id')
								->get();
					
				$structure_array = array();
				
				foreach($structure as $row)
					$structure_array[] = $row->id;
				
				DB::table('inventory_table_structure_height')->whereIn('structure_id', $structure_array)->delete();
				DB::table('inventory_table_structure_width')->whereIn('structure_id', $structure_array)->delete();
			}
			
			$result_array['html'] = $this->build_structure_tree_table($catalog_id);
			
			return $result_array;
		}
	}
	
	
	public function set_selection_custom(Request $request)
	{
		if($request->ajax())
		{
			$id = (int)$request->input('id');
			$catalog_id = (int)$request->input('catalog_id');
			$name = $request->input('name');
			$name_element = $request->input('name_element');
			$this->selection_id = $request->input('selection_id');
			$input = $request->input('input');
			$this->del = (int)$request->input('delete');
			
			$user = $this->get_user();
			
			if($this->del > 0)
			{
				if($input == 'selection_custom')
				{
					$used_selections_id = DB::table('inventory_table_structure as structure')
							->where('structure.type_field', $input)
							->where('structure.catalog_id', $catalog_id)
							->where('structure.disable', 0)
							->leftJoin('inventory_selections_cross as cross', function($join){
								$join->on('cross.structure_id', '=', 'structure.id')
								->where('cross.selection_id', '=', $this->del);
							})
							->select('cross.selection_id')
							->groupBy('cross.selection_id')
							->get();
							
					if($used_selections_id->isEmpty() == false and isset($used_selections_id[0]->selection_id))
						return 0;
					else
					{
						DB::table('inventory_custom_selections_elements')->where('selection_id', '=', $this->del)->delete();
						DB::table('inventory_custom_selections')->where('id', '=', $this->del)->delete();
					}
				}
				elseif($input == 'selection_custom_element')
				{
					$used_selections_id = DB::table('inventory_table_structure as structure')
							->where('structure.type_field', 'selection_custom')
							->where('structure.catalog_id', $catalog_id)
							->where('structure.disable', 0)
							->leftJoin('inventory_selections_cross as cross', function($join){
								$join->on('cross.structure_id', '=', 'structure.id')
								->where('cross.selection_id', '=', $this->selection_id);
							})
							// ->select('cross.selection_id', 'structure.id')
							->select('cross.selection_id', 'structure.id')
							// ->groupBy('cross.selection_id')
							->get();
							
					if(isset($used_selections_id[0]->selection_id))
					{
						$used_elements = DB::table('inventory_fields_type_select')
									->where('structure_id', '=', $used_selections_id[0]->id)
									->where('select', '=', $this->del)
									->select(DB::raw('count(*) as count_str'))
									->get();
						
						if($used_elements[0]->count_str == 0)
							DB::table('inventory_custom_selections_elements')->where('id', '=', $this->del)->delete();
						else
							return 0;
					}
					else
					{
						DB::table('inventory_custom_selections_elements')->where('id', '=', $this->del)->delete();
					}
				}
			}
			elseif(isset($name_element) or $input == 'selection_element')
			{
				if($input == 'selection_element')
					$name_element = $name;
					
				if($id > 0)
				{
					DB::table('inventory_custom_selections_elements')->where('id', $id)->update(['name'=>$name]);
				}
				else
				{
					DB::table('inventory_custom_selections_elements')->insert(['selection_id'=>$this->selection_id, 'catalog_id'=>$catalog_id, 'name'=>$name_element]);
				}
			}
			elseif($id > 0)
			{
				DB::table('inventory_custom_selections')->where('id', $id)->update(['name'=>$name]);
			}
			else
			{
				DB::table('inventory_custom_selections')->insert(['catalog_id'=>$catalog_id, 'user_id'=>$user['id'], 'name'=>$name]);
			}
			
			$result_array = array();
			
			$result_build = $this->build_structure_tree_table($catalog_id);
			
			if(isset($result_build['structure_html']))
				$result_array['structure_html'] = $result_build['structure_html'];
			
			$result_array['select_structure_html'] = $result_build['select_structure_html'];
			
			$selection_custom = $this->get_selection_custom($catalog_id);
			
			$result_array['selection_custom'] = $this->get_html_selection_custom($selection_custom);
			
			$result_array['select_type_fields'] = $this->get_type_fields_select($catalog_id, $selection_custom);
			
			return $result_array;
		}
		else
			return 0;
	}
	
	
	public function add_string(Request $request)
	{
		if($request->ajax())
		{
			$catalog_id = $request->input('catalog_id');
			
			$structure = $this->get_structure_table($catalog_id);
			
			if(!empty($structure))
			{
				foreach($structure as &$row)
				{
					if($row['type_field'] !== '')
					{
						if(isset($this->parametrs['type_fields'][$row['type_field']]['input']))
							$row['html'] = $this->parametrs['type_fields'][$row['type_field']]['input'];
						elseif($row['type_field'] == 'selection')
						{
							$selection = '';
							
							$selection_id = DB::table('inventory_selections_cross')->select('selection_id')->where('structure_id', $row['id'])->get();
							
							if($selection_id->isEmpty() == false)
							{
								$selection = $this->parametrs['selection'][$selection_id[0]->selection_id];
								
								$eval_str ='';
								
								$eval_str .= $selection['use'].' ';
								
								$eval_str .= "\$result = ".$selection['model'];
								
								if(isset($selection['fields']))
								{
									$fields_str = '';
									
									foreach($selection['fields'] as &$field)
										$field = "'".$field."'";
									
									$eval_str .= '::select('.implode(",", $selection['fields']).')';
									
									if(isset($selection['where']))
									{
										foreach($selection['where'] as $key=>&$where)
										{
											if(gettype($where) == 'string')
												$eval_str .= "->where('".$key."', '".$where."')";
											else
												$eval_str .= "->where('".$key."', ".$where.")";
										}
									}
									if(isset($selection['order']))
										$eval_str .= "->orderBy('".$selection['order']."')";
									
									$eval_str .= '->get(); return $result;';
									
									$result = '';
									
									eval($eval_str);
									
									if($result->isEmpty() == false)
									{
										if(isset($this->parametrs['selections_from_old_db'][$selection['model']]))
										{
											$result_array = array();
											
											foreach ($result as $key=>$res)
												$res->name = iconv('cp1251', 'utf-8', $res->name);
										}
										// $result = $result->toArray();
										// var_dump($result);
										// exit;
										
										$row['html'] = $this->get_string_select($result, $row['type_field'], $selection_id[0]->selection_id);
									}
								}
							}
							else
								return false;
						}
						elseif($row['type_field'] == 'selection_custom')
						{
							$selection = DB::table('inventory_selections_cross as cross')
													->leftJoin('inventory_custom_selections as custom', 'custom.id', '=', 'cross.selection_id')
													->select('cross.selection_id as id', 'custom.name as name')
													->where('cross.structure_id', $row['id'])
													->get();
							
							if($selection->isEmpty() == false)
							{
								$childs = DB::table('inventory_custom_selections_elements as child')
													->select('child.id as id', 'child.name as name')
													->where('selection_id', $selection[0]->id)
													->get();
								
								$childs = $childs->toArray();
								
								foreach($childs as &$ro)
								{
									$ro = array('id'=>$ro->id, 'name'=>$ro->name);
								}
								
								$row['html'] = $this->get_string_select($childs, $row['type_field'], $selection[0]->id);
							}
							else
								return false;
						}
					}
				}
				
				return $structure;
			}
			else
				return 0;
		}
		else
			return 0;
	}
	
	public function set_catalog(Request $request)
	{
		if($request->ajax())
		{
			$id = (int)$request->input('id');
			$parent_id = (int)$request->input('parent_id');
			$value = $request->input('value');
			$delete = (int)$request->input('delete');
			
			$user = $this->get_user();
			
			if($delete > 0)
			{
				if($user['role'] == 0)
				{
					DB::table('inventory_catalogs')->where('id', $delete)->update(['disable'=>1, 'user_id'=>$user['id']]);
					
					return 1;
				}
				elseif($user['role'] == 1)
				{
					$catalogs_role = DB::table('users_roles_groups')
									->select('catalog_id', 'role')
									->where('user_id', $user['id'])
									->get();
					
					$cat_flag = array();
					$cat_id_list = array();
					
					foreach($catalogs_role as $row)
					{
						if($row->role < 2)
						{
							$cat_id_list[$row->catalog_id] = $row->catalog_id;
							
							if($row->catalog_id == $delete)
								$cat_flag = $row->catalog_id;
						}
					}
					
					if(!empty($cat_flag))
						DB::table('inventory_catalogs')->where('id', $delete)->update(['disable'=>1, 'user_id'=>$user['id']]);
					else
					{
						$catalog_list = DB::table('inventory_catalogs')
								->select('id', 'parent_id')
								->where('disable', 0)
								->get();
						
						
						if($catalog_list->isEmpty() == false)
						{
							$this->ar = array();
							$this->id_list = array();
							
							$this->get_queue($catalog_list);
							
							foreach($this->ar as $key=>$row)
							{
								if($key > 0 and $row['parent_id'] > 0)
								{
									$prev_key = $key - 1;
									
									if($row['parent_id'] == $this->ar[$prev_key]['id'] or $row['parent_id'] == $this->ar[$prev_key]['parent_id'])
									{
										if(isset($this->ar[$prev_key]['parent_id_list']))
											$this->ar[$key]['parent_id_list'] = $this->ar[$prev_key]['parent_id_list'];
									}
									
									$this->ar[$key]['parent_id_list'][$row['parent_id']] = $row['parent_id'];
								}
							}
							// print_r($this->ar);
							// exit;
							
							if(!empty($cat_id_list))
							{
								foreach($this->ar as $row)
								{
									if($row['id'] == $delete)
									{
										
										if(!empty(array_intersect_key($cat_id_list, $row['parent_id_list'])))
											DB::table('inventory_catalogs')->where('id', $delete)->update(['disable'=>1, 'user_id'=>$user['id']]);
										else
											return 0;
									}
								}
							}
							else
								return 0;
						}
					}
					
					return 1;
				}
				else
					return 0;
			}
			elseif($id > 0)
			{
				$validator = Validator::make($request->all(), ['value' => 'max:255']);
				
				if(!$validator->fails())
				{
					DB::table('inventory_catalogs')->where('id', $id)->update(['name'=>$value, 'user_id'=>$user['id']]);
					
					return 1;
				}
				else
					return 0;
			}
			else
			{
				$validator = Validator::make($request->all(), ['value' => 'max:255']);
				
				if(!$validator->fails())
				{
					$result_array = array();
					
					$result_array['id'] = DB::table('inventory_catalogs')->insertGetId(['parent_id'=>$parent_id, 'user_id'=>$user['id'], 'name'=>$value, 'disable'=>0, 'old_id'=>0, 'old_parent_id'=>0]);
					
					return $result_array;
				}
				else
					return 0;
			}
		}
		else
			exit;
	}
	
	public function set_string(Request $request)
	{
		if($request->ajax())
		{
			$file_id = (int)$request->input('file_id');
			$id = (int)$request->input('id');
			$input_type = $request->input('input_type');
			$structure_id = (int)$request->input('structure_id');
			$value = $request->input('value');
			$text = $request->input('text');
			$catalog_id = (int)$request->input('catalog_id');
			$parent_id = (int)$request->input('parent_id');
			$delete = (int)$request->input('delete');
			
			// var_dump($value);
			// exit;
			// var_dump($text);
			// var_dump($id);
			// exit;
			
			$fields_array = '';
			$user = $this->get_user();
			
			if($delete > 0)
			{
				$validator = Validator::make($request->all(), ['delete' => 'exists:inventory_files,id']);
				
				if(!$validator->fails())
				{
					DB::table('inventory_files')->where('id', $delete)->update(['disable'=>1]);
					InventorySearch::where('file_id', $delete)->update(['disable'=>1]);
					
					
					// $file_list = DB::table('inventory_files')
							// ->select('id', 'parent_id')
							// ->where('catalog_id', $catalog_id)
							// ->where('disable', 0)
							// ->orderBy('id')
							// ->get();
					
					// $this->ar = '';
					
					// if($file_list->isEmpty() == false)
						// $this->get_files_structure($delete, $file_list);
					
					// if($this->ar !== '')
						// return $this->ar;
					// else
						return 1;
				}
				else
					return 0;
			}
			
			$result_array = array();
			
			if($file_id == 0)
			{
				$inventory_number = 0;
				
				$result_array['file_id'] = DB::table('inventory_files')->insertGetId(['catalog_id'=>$catalog_id, 'parent_id'=>$parent_id, 'inventory_number'=>$inventory_number, 'attach_user_id'=>0, 'disable'=>0]);
				$file_id = $result_array['file_id'];
				
				$max_inventory_number = DB::table('inventory_files')->where('catalog_id', '=', $catalog_id)->max('inventory_number');
				
				if($max_inventory_number == 0)
					$max_inventory_number = $catalog_id * 100000;
				
				$inventory_number = ++$max_inventory_number;
				
				$result_array['inventory_number'] = $inventory_number;
				
				DB::table('inventory_files')->where('id', $file_id)->update(['inventory_number'=>$inventory_number]);
				
				InventorySearch::updateOrCreate(['catalog_id'=>$catalog_id, 'file_id'=>$file_id, 'structure_id'=>0, 'field_id'=>0, 'type_field'=>'inventory_number'], ['text'=>$inventory_number, 'disable'=>0]);
			}
			
			if($input_type == 'text' or $input_type == 'textarea' or $input_type == 'number' or $input_type == 'date')
			{
				$validate_directive = '';
				
				if($input_type == 'text')
					$validate_directive = ['value' => 'max:255'];
				elseif($input_type == 'textarea')
					$validate_directive = ['value' => 'max:65535'];
				
				if($validate_directive !== '')
				{
					$validator = Validator::make($request->all(), $validate_directive);

					if($validator->fails())
						return 'validation_error';
				}
				
				if($id > 0)
					DB::table($this->parametrs['type_fields'][$input_type]['db_table'])->where('id', $id)->where('file_id', $file_id)->where('structure_id', $structure_id)->update(array($input_type=>$value));
				else
					$result_array['input_id'] = DB::table($this->parametrs['type_fields'][$input_type]['db_table'])->insertGetId(['structure_id'=>$structure_id, 'file_id'=>$file_id, $input_type=>$value]);
			}
			elseif($input_type == 'selection' or $input_type == 'selection_custom')
			{
				$select_id = 0;
				
				if($structure_id == -1)
					DB::table('inventory_files')->where('id', $file_id)->update(['attach_user_id'=>(int)$value]);
				elseif($id > 0)
				{
					DB::table($this->parametrs['type_fields'][$input_type]['db_table'])->where('id', $id)->where('file_id', $file_id)->where('structure_id', $structure_id)->update(array('select'=>$value));
					
					$result_array = 1;
					$select_id = $id;
				}
				else
				{
					$result_array['input_id'] = DB::table($this->parametrs['type_fields'][$input_type]['db_table'])->insertGetId(['structure_id'=>$structure_id, 'file_id'=>$file_id, 'select'=>$value]);
					$select_id = $result_array['input_id'];
				}
				
				
				if($structure_id == -1)
				{
					$fields_array = ['file_id'=>$file_id, 'user_id'=>$user['id'], 'select'=>(int)$value];
					
					$this->set_log('inventory_log_attach_user', $structure_id, $fields_array);
				}
				else
				{
					$fields_array = ['select_id'=>$select_id, 'user_id'=>$user['id'], 'select'=>(int)$value];
					
					$this->set_log($this->parametrs['type_fields'][$input_type]['db_table_log'], $structure_id, $fields_array);
				}
			}
			
			if($structure_id == -1)
				$id = 0;
			elseif($id == 0)
				$id = $result_array['input_id'];
			
			if($input_type == 'selection' or $input_type == 'selection_custom' or $input_type == 'number')
				$value = (int)$value;
			
			if($value !== null and $value !== 'null' and $value !== '' and $value !== 0 or $structure_id == -1 and $value !== null and $value !== 0)
				InventorySearch::updateOrCreate(['catalog_id'=>$catalog_id, 'file_id'=>$file_id, 'structure_id'=>$structure_id, 'field_id'=>$id, 'type_field'=>$input_type], ['text'=>$text, 'disable'=>0]);
			else
				InventorySearch::where(['catalog_id'=>$catalog_id, 'file_id'=>$file_id, 'structure_id'=>$structure_id, 'field_id'=>$id, 'type_field'=>$input_type])->delete();
			
			return $result_array;
		}
		else
			return 0;
	}
	
	
	public function set_file(Request $request)
	{
		if($request->ajax())
		{
			$id = (int)$request->input('id');
			// $parent_id = (int)$request->input('parent_id');
			$delete = (int)$request->input('delete');
			$value = $request->input('value');
			
			$user = $this->get_user();
			
			$file_img = '';
			$result = array();
			
			if($delete > 0)
			{
				$path_file = DB::table('inventory_fields_type_file')->select('url')->where('id', $delete)->get();
				
				Storage::delete($path_file[0]->url);
				
				DB::table('inventory_fields_type_file')->where('id', $delete)->delete();
				
				InventorySearch::where(['catalog_id'=>(int)$request->input('catalog_id'), 'file_id'=>(int)$request->input('file_id'), 'structure_id'=>(int)$request->input('structure_id'), 'field_id'=>$delete, 'type_field'=>'file'])->delete();
				
				return 1;
			}
			
			
			$path = $request->file('file')->store('public');
			
			if($id > 0)
			{
				$path_file = DB::table('inventory_fields_type_file')->select('url')->where('id', $id)->get();
				
				Storage::delete($path_file[0]->url);
				
				DB::table('inventory_fields_type_file')->where('id', $id)->update(['url'=>$path, 'user_id'=>$user['id'], 'mime'=>$request->file('file')->getClientOriginalExtension()]);
			}
			else
			{
				if($request->file_id == 0)
				{
					$inventory_number = 0;
					
					$result_array['file_id'] = DB::table('inventory_files')->insertGetId(['catalog_id'=>$request->catalog_id, 'parent_id'=>0, 'inventory_number'=>$inventory_number, 'attach_user_id'=>0, 'disable'=>0]);
					$request->file_id = $result_array['file_id'];
					
					$max_inventory_number = DB::table('inventory_files')->where('catalog_id', '=', $request->catalog_id)->max('inventory_number');
					
					if($max_inventory_number == 0)
						$max_inventory_number = $request->catalog_id * 100000;
					
					$inventory_number = ++$max_inventory_number;
					
					$result_array['inventory_number'] = $inventory_number;
					
					DB::table('inventory_files')->where('id', $request->file_id)->update(['inventory_number'=>$inventory_number]);
					
					$result['inventory_number'] = $inventory_number;
					
					InventorySearch::updateOrCreate(['catalog_id'=>$request->catalog_id, 'file_id'=>$request->file_id, 'structure_id'=>0, 'field_id'=>0, 'type_field'=>'inventory_number'], ['text'=>$inventory_number, 'disable'=>0]);
				}
				
				$id = DB::table('inventory_fields_type_file')->insertGetId(['structure_id'=>$request->structure_id, 'file_id'=>$request->file_id, 'file'=>$_FILES['file']['name'], 'url'=>$path, 'user_id'=>$user['id'], 'mime'=>$request->file('file')->getClientOriginalExtension()]);
				
				$result['field_id'] = $id;
				$result['file_id'] = $request->file_id;
			}
			
			InventorySearch::updateOrCreate(['catalog_id'=>$request->catalog_id, 'file_id'=>$request->file_id, 'structure_id'=>$request->structure_id, 'field_id'=>$id, 'type_field'=>'file'], ['text'=>$_FILES['file']['name'], 'disable'=>0]);
			
			
			$date_user = DB::table('inventory_fields_type_file as file')
					->leftJoin('users', 'users.id', '=', 'file.user_id')
					->select(DB::raw('users.name, file.mime, DATE_FORMAT(file.date, "%d.%m.%Y %H:%i:%s") as date'))
					->where('file.id', $id)
					->get();
			
			$date_user[0]->mime = strtolower($date_user[0]->mime);
			
			if($date_user[0]->mime == 'pdf')
				$file_img = '<img width="25" src="/laravel/public/file-pdf.svg"/>';
			elseif($date_user[0]->mime == 'jpg' or $date_user[0]->mime == 'jpeg' or $date_user[0]->mime == 'tiff' or $date_user[0]->mime == 'png')
				$file_img = '<img width="25" src="/laravel/public/file-media.svg"/>';
			elseif($date_user[0]->mime == 'zip' or $date_user[0]->mime == 'rar' or $date_user[0]->mime == '7z')
				$file_img = '<img width="25" src="/laravel/public/file-zip.svg"/>';
			else
				$file_img = '<img width="25" src="/laravel/public/file.svg"/>';
			
			$result['link'] = '<a href="/laravel/storage/app/'.$path.'" target="_blank" data-toggle="tooltip" data-placement="top" title="'.$date_user[0]->date.' Пользователь: '.$date_user[0]->name.' Файл: '.$_FILES['file']['name'].'">'.$file_img.'</a><button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
			
			return $result;
		}
	}
	
	
	
	public function set_user_role(Request $request)
	{
		if($request->ajax())
		{
			$parent_user = $this->get_user();
			
			$okbdb_user_id = (int)$request->input('user_id');
			
			$delete = (int)$request->input('delete');
			
			if($delete > 0)
			{
				if($parent_user['role'] == 0)
					InventoryRoleUsers::where('user_id', $delete)->delete();
				else
					InventoryRoleUsers::where('parent_user', $parent_user['id'])->where('user_id', $delete)->delete();
			}
			else
			{
				if($okbdb_user_id == 0)
					return 0;
				
				$user_id = DB::table('users')->select('id')->where('okbdb_user_id', $okbdb_user_id)->get();
				
				$new_user_flag = 0;
				
				if($user_id->isEmpty() == true)
				{
					$old_user = OldUsers::select('ID', 'LOGIN', 'PASS', 'FIO', 'email')->where('STATE', 0)->where('ID', $okbdb_user_id)->get();
					
					if($old_user->isEmpty() == false)
					{
						$old_user = $old_user[0]->toArray();
						
						$user_id = DB::table('users')->insertGetId(array('okbdb_user_id'=>$okbdb_user_id, 'login'=>$old_user['LOGIN'], 'email'=>$old_user['email'], 'password'=>$old_user['PASS'], 'name'=>iconv('cp1251', 'utf-8', $old_user['FIO'])));
						
						$new_user_flag = 1;
					}
					else
						return 0;
				}
				else
					$user_id = $user_id[0]->id;
				
				
				if($request->input('array_checkbox') !== null)
					$array_checkbox = array_diff($request->input('array_checkbox'), array(''));
				else
					$array_checkbox = '';
				
				// print_r($array_checkbox);
				// exit;
				if($array_checkbox == '' and $new_user_flag == 0)
				{
					InventoryRoleUsers::where('user_id', $user_id)
					->when($parent_user['role'] == 1, function($query) use ($parent_user)
					{
						return $query->where('parent_user', $parent_user['id']);
					})
					->delete();
				}
				else
				{
					$catalog_id_array = array();
					
					foreach($array_checkbox as $catalog_id=>$role)
					{
						$catalog_id_array[] = $catalog_id;
						
						InventoryRoleUsers::updateOrCreate(['user_id'=>$user_id, 'parent_user'=>$parent_user['id'], 'catalog_id'=>$catalog_id], ['role'=>$role]);
					}
					
					InventoryRoleUsers::where('user_id', $user_id)
					->when($parent_user['role'] == 1, function($query) use ($parent_user)
						{
							return $query->where('parent_user', $parent_user['id']);
						})
					->whereNotIn('catalog_id', $catalog_id_array)->delete();
				}
			}
			
			
			$user_list = DB::table('users_roles_groups as roles')
							->leftJoin('users', 'users.id', '=', 'roles.user_id')
							->select(DB::raw('MIN(roles.role) as role, roles.user_id as id, users.name, users.email'))
							->where([['roles.user_id', '!=', $parent_user['id']]])
							->when($parent_user['role'] == 1, function($query) use ($parent_user)
									{
										return $query->where([['roles.parent_user', '=', $parent_user['id']]]);
									})
							->groupBy('roles.user_id')
							->orderBy('users.name')
							->get();
			
			$html = '';
			
			if($user_list->isEmpty() == false)
			{
				foreach($user_list as $row)
				{
					$html .= '<tr data-user-id="'.$row->id.'">
						<td>'.$row->name.'</td>
						<td>'.$row->email.'</td>
						<td>
							<div class="btn-group btn-group-sm" role="group">
								<button type="button" class="btn btn-light text-warning edit_user" title="Редактировать пользователя"><span class="oi oi-pencil"></span></button>
								<button type="button" class="btn btn-light text-danger delete_user" title="Удалить пользователя"><span class="oi oi-trash"></span></button>
							</div>
						</td>
					</tr>';
				}
			}
			
			return $html;
		}
	}
	
	public function set_structure_size(Request $request)
	{
		if($request->ajax())
		{
			$structure_size_array = $request->input('structure_size_array');
			
			// print_r($structure_size_array);
			// $width = (float)$request->input('width');
			// $height = (float)$request->input('height');
			// $id = (int)$request->input('id');
			
			foreach($structure_size_array as $row)
			{
				InventoryStructureWidth::updateOrCreate(['structure_id'=>(int)$row['id']], ['width'=>(float)$row['width']]);
				
				if((int)$row['id'] > 0)
					InventoryStructureHeight::updateOrCreate(['structure_id'=>(int)$row['id']], ['height'=>(float)$row['height']]);
			}
			
			return 1;
		}
	}
	
	public function get_user_role(Request $request)
	{
		if($request->ajax())
		{
			$result_array = array();
			
			$result_array['parent_user'] = $this->get_user();
			
			if($result_array['parent_user']['role'] == 1)
			{
				$roles_parent_user = DB::table('users_roles_groups')
										->select('parent_user', 'role', 'catalog_id')
										->where('user_id', $result_array['parent_user']['id'])
										->get();
				
				if($roles_parent_user->isEmpty() == false)
				{
					$roles_parent_user_array = array();
					
					foreach($roles_parent_user as $row)
					{
						$roles_parent_user_array[$row->catalog_id] = $row->role;
					}
					
					$result_array['roles_parent_user'] = $roles_parent_user_array;
					
					// print_r($result_array['roles_parent_user']);
					// exit;
				}
			}
			
			$okbdb_user_id = (int)$request->input('old_user_id');
			
			$user_id = (int)$request->input('user_id');
			
			if($okbdb_user_id > 0)
			{
				$user_id = DB::table('users')->select('id')->where('okbdb_user_id', $okbdb_user_id)->get();
				
				if($user_id->isEmpty() == false)
					$user_id = $user_id[0]->id;
				else
					$user_id = 0;
			}
			elseif($user_id > 0)
			{
				$okbdb_user_id = DB::table('users')->select('okbdb_user_id')->where('id', $user_id)->get();
				
				if($okbdb_user_id->isEmpty() == false)
					$result_array['okbdb_user_id'] = $okbdb_user_id[0]->okbdb_user_id;
				else
					$result_array['okbdb_user_id'] = 0;
			}
			
			
			if($user_id > 0)
			{
				$result_array['user_id'] = $user_id;
				
				$roles_user = DB::table('users_roles_groups')
										->select('parent_user', 'role', 'catalog_id')
										->where('user_id', $user_id)
										->get();
				
				if($roles_user->isEmpty() == false)
					$result_array['roles_user'] = $roles_user;
			}
			
			return $result_array;
			// print_r($result_array);
			// exit;
		}
	}
	
	
	private function get_string_select($options_object=null, $selection, $selection_id)
	{
		$result = array();
		$result_array = array();
		
		$html = '<div class="btn-group">
					<button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" data-selection="'.$selection.'" data-selection-id="'.$selection_id.'" aria-haspopup="true" aria-expanded="false"></button>
					<div class="dropdown-menu">';
		
		$html .= '<button class="dropdown-item" type="button" value="0">---</button>';
		
		foreach($options_object as $row)
		{
			if($row['id'] > 0)
			{
				$html .= '<button class="dropdown-item" type="button" value="'.$row['id'].'">'.$row['name'].'</button>';
				
				$result_array[$row['id']] = $row['name'];
			}
		}
			
		$html .= '</div>
			</div>';
		
		$result['html'] = $html;
		// $result['array'] = array_diff($result_array, array(''));
		$result['array'] = $result_array;
		
		return $result;
	}
	
	
	private function get_structure_table($catalog_id)
	{
		$structure_list = DB::table('inventory_table_structure as str')
						->leftJoin('inventory_table_structure_width as width', 'width.structure_id', '=', 'str.id')
						->leftJoin('inventory_table_structure_height as height', 'height.structure_id', '=', 'str.id')
						->select('str.id', 'str.parent_id', 'str.type_field', 'str.name', 'str.log_flag', 'width.width', 'height.height')
						->where('str.catalog_id', $catalog_id)
						->where('str.disable', 0)
						->orderBy('str.position')
						->orderBy('str.id')
						->get();
		
		$this->ar = array();
		$this->id_list = array();
		
		if($structure_list->isEmpty() == false)
		{
			$this->get_queue($structure_list);
			
			$structure_array = array();
			
			$attach_html_array = $this->get_selection(-1);
			
			$attach_width = DB::table('inventory_table_structure_width')->where('structure_id', -1)->pluck('width');
			
			if($attach_width->isEmpty() == false)
			{
				if($attach_width[0] > 0)
					$structure_array[-1]['width'] = $attach_width[0];
			}
			
			$structure_array[-1]['html'] = $attach_html_array['html'];
			
			$structure_array[-1]['array'] = $attach_html_array['array'];
			
			foreach($this->ar as &$row)
			{
				if(isset($this->parametrs['type_fields'][$row['type_field']]['input']))
					$row['html'] = $this->parametrs['type_fields'][$row['type_field']]['input'];
				elseif($row['type_field'] == 'selection')
					$row['html'] = $this->get_selection($row['id']);
				elseif($row['type_field'] == 'selection_custom')
				{
					$row['html'] = $this->get_selection_custom(null, $row['id']);
					// print_r($row['html']);
					// exit;
					
					$row['html'] = $this->get_string_select($row['html'][0]['childs'], $row['type_field'], $row['html'][0]['id']);
				}
				
				if($row['type_field'] == 'text' or $row['type_field'] == 'number' or $row['type_field'] == 'date' or $row['type_field'] == 'selection' or $row['type_field'] == 'selection_custom')
					$row['order'] = 1;
				
				$structure_array[$row['id']] = $row;
			}
			
			// print_r($this->ar);
			// exit;
			return $structure_array;
		}
	}
	
	
	private function get_table($catalog_id, $structure_table=null, $file_id_list=null)
	{
		$file_list = DB::table('inventory_files')
							->select('id', 'parent_id', 'inventory_number', 'attach_user_id')
							->when($file_id_list, function ($query) use ($file_id_list) {
										return $query->whereIn('id', $file_id_list);
									})
							->where('catalog_id', $catalog_id)
							->where('disable', 0)
							->orderBy('id', 'desc')
							->get();
		
		
		$this->ar = array();
		$this->id_list = array();
		
		if($file_list->isEmpty() == false)
		{
			$this->get_queue($file_list, false, true);
			
			// dd($this->ar);
			// dd($this->ar);
			// exit;
			if(!empty($this->ar) and $structure_table !== null)
			{
				$structure_id_list = array();
				
				$structure_id_list = array_keys($structure_table);
				
				$res = array();
				$res_array = array();
				
				foreach($this->ar as $row)
					$res_array[$row['id']] = array();
				
				$res['date'] = DB::table('inventory_fields_type_date')->whereIn('file_id', $this->id_list)->whereIn('structure_id', $structure_id_list)->select(DB::raw('id, structure_id, file_id, DATE_FORMAT(date, "%d.%m.%Y") as date'))->get();
				
				$res['file'] = DB::table('inventory_fields_type_file as file')
							->leftJoin('users', 'users.id', '=', 'file.user_id')
							->select(DB::raw('file.id, file.structure_id, file.file_id, file.user_id, file.file, file.url, file.mime, DATE_FORMAT(file.date, "%d.%m.%Y %H:%i:%s") as date, users.name'))
							->whereIn('file.file_id', $this->id_list)
							->whereIn('file.structure_id', $structure_id_list)
							->get();
				
				$res['number'] = DB::table('inventory_fields_type_number')->whereIn('file_id', $this->id_list)->whereIn('structure_id', $structure_id_list)->get();
				$res['select'] = DB::table('inventory_fields_type_select')->whereIn('file_id', $this->id_list)->whereIn('structure_id', $structure_id_list)->get();
				$res['text'] = DB::table('inventory_fields_type_text')->whereIn('file_id', $this->id_list)->whereIn('structure_id', $structure_id_list)->get();
				$res['textarea'] = DB::table('inventory_fields_type_textarea')->whereIn('file_id', $this->id_list)->whereIn('structure_id', $structure_id_list)->get();
				
				foreach($res['file'] as &$row)
				{
					$file_img = '';
					
					$row->mime = strtolower($row->mime);
					
					if($row->mime == 'pdf')
						$file_img = '<img width="25" src="/laravel/public/file-pdf.svg"/>';
					elseif($row->mime == 'jpg' or $row->mime == 'jpeg' or $row->mime == 'tiff' or $row->mime == 'png')
						$file_img = '<img width="25" src="/laravel/public/file-media.svg"/>';
					elseif($row->mime == 'zip' or $row->mime == 'rar' or $row->mime == '7z')
						$file_img = '<img width="25" src="/laravel/public/file-zip.svg"/>';
					else
						$file_img = '<img width="25" src="/laravel/public/file.svg"/>';
					
					$row->link = '<a href="/laravel/storage/app/'.$row->url.'" target="_blank" data-toggle="tooltip" data-placement="top" title="'.$row->date.' Пользователь: '.$row->name.' Файл: '.$row->file.'">'.$file_img.'</a><button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>';
				}
				
				foreach($res as $key=>$row)
				{
					for($x=0; $x<count($row); $x++)
						$res_array[$row[$x]->file_id][$row[$x]->structure_id] = (array)$row[$x];
				}
				// dd($res_array);
				// exit;
				return $res_array;
			}
		}
		
		/* $structure = $this->get_structure_table($catalog_id);
		
		$db_tables_array = array();
		
		if(!empty($structure))
		{
			foreach($structure as &$row)
			{
				if(isset($this->parametrs['type_fields'][$row['type_field']]['db_table']))
					$db_tables_array[$this->parametrs['type_fields'][$row['type_field']]['db_table']] = $this->parametrs['type_fields'][$row['type_field']]['db_table'];
			}
			
			
			$file_list = DB::table('inventory_files')
							->select('id', 'parent_id', 'inventory_number')
							->where('catalog_id', $catalog_id)
							->where('disable', 0)
							->orderBy('id', 'desc')
							->get();
			
			$this->ar = '';
			
			if($file_list->isEmpty() == false)
			{
				$this->get_queue($file_list);
				
				// print_r($this->ar);
				// exit;
				if($this->ar !== '')
				{
					$files_id = array();
					
					foreach($this->ar as &$file)
					{
						$files_id[] = $file['id'];
					}
					
					foreach($db_tables_array as &$db_table)
					{
						if($db_table == 'inventory_fields_type_date')
							$db_table = DB::table($db_table)->whereIn('file_id', $files_id)->select(DB::raw('id, structure_id, file_id, DATE_FORMAT(date, "%d.%m.%Y") as date'))->get();
						elseif($db_table == 'inventory_fields_type_file')
						{
							$db_table = DB::table($db_table.' as file')
									->leftJoin('users', 'users.id', '=', 'file.user_id')
									->select(DB::raw('users.name, file.id, file.structure_id, file.file_id, file.user_id, file.file, file.url, DATE_FORMAT(file.date, "%d.%m.%Y %H:%i:%s") as date'))
									->whereIn('file.file_id', $files_id)
									->get();
						}
						else
							$db_table = DB::table($db_table)->whereIn('file_id', $files_id)->get();
					}
					
					
					
					foreach($this->ar as &$file)
					{
						foreach($structure as $key=>&$row)
						{
							// print_r($row);
							// exit;
							if(isset($this->parametrs['type_fields'][$row['type_field']]['db_table']))
							{
								$data_flag = 0;
								
								foreach($db_tables_array[$this->parametrs['type_fields'][$row['type_field']]['db_table']] as &$db_table)
								{
									if($db_table->file_id == $file['id'] and $db_table->structure_id == $row['id'])
									{
										$data_flag = 1;
										
										$file['structure'][$key] = $row;
										
										if($row['type_field'] == 'selection' or $row['type_field'] == 'selection_custom')
											$file['structure'][$key]['data'] = array('id'=>$db_table->id, 'value'=>$db_table->select);
										elseif($row['type_field'] == 'file')
											$file['structure'][$key]['data'] = array('id'=>$db_table->id, 'value'=>$db_table->$row['type_field'], 'url'=>$db_table->url, 'date'=>$db_table->date, 'user'=>$db_table->name);
										else
										{
											if($db_table->$row['type_field'] == null)
												$db_table->$row['type_field'] = '';
											
											$file['structure'][$key]['data'] = array('id'=>$db_table->id, 'value'=>$db_table->$row['type_field']);
										}
									}
								}
								
								if($data_flag == 0)
								{
									$file['structure'][$key] = $row;
									$file['structure'][$key]['data'] = array('id'=>'', 'value'=>'');
								}
							}
							else
							{
								$file['structure'][$key] = $row;
								$file['structure'][$key]['data'] = array('id'=>'', 'value'=>'');
							}
						}
					}
					
					// print_r($this->ar);
					// exit;
					return $this->ar;
				}
			}
		} */
	}
	
	
	
	private function get_selection($structure_id)
	{
		$selection = '';
		
		if($structure_id == -1)
		{
			$selection = $this->parametrs['selection'][1];
			$selection_id = 0;
		}
		else
			$selection_id = DB::table('inventory_selections_cross')->select('selection_id')->where('structure_id', $structure_id)->get();
		
		if(gettype($selection_id) == 'object' and $selection_id->isEmpty() == false or $selection !== '')
		{
			if($selection == '')
				$selection = $this->parametrs['selection'][$selection_id[0]->selection_id];
			
			$eval_str ='';
			
			$eval_str .= $selection['use'].' ';
			
			$eval_str .= "\$result = ".$selection['model'];
			
			if(isset($selection['fields']))
			{
				$fields_str = '';
				
				foreach($selection['fields'] as &$field)
					$field = "'".$field."'";
				
				$eval_str .= '::select('.implode(",", $selection['fields']).')';
				
				if(isset($selection['where']))
				{
					foreach($selection['where'] as $key=>&$where)
					{
						if(gettype($where) == 'string')
							$eval_str .= "->where('".$key."', '".$where."')";
						else
							$eval_str .= "->where('".$key."', ".$where.")";
					}
				}
				if(isset($selection['order']))
					$eval_str .= "->orderBy('".$selection['order']."')";
				
				$eval_str .= '->get(); return $result;';
				
				$result = '';
				
				eval($eval_str);
				
				if($result->isEmpty() == false)
				{
					if(isset($this->parametrs['selections_from_old_db'][$selection['model']]))
					{
						foreach($result as &$res)
							$res->name = iconv('cp1251', 'utf-8', $res->name);
					}
					
					if($selection_id !== 0)
						return $this->get_string_select($result, 'selection', $selection_id[0]->selection_id);
					else
						return $this->get_string_select($result, 'selection', $selection_id);
				}
			}
		}
	}
	
	
	private function get_selection_name($structure_id, $selection)
	{
		if($selection == 'selection')
		{
			$selection_id = DB::table('inventory_selections_cross')->select('selection_id')->where('structure_id', $structure_id)->get();
			
			if($selection_id->isEmpty() == false)
				return $this->parametrs['selection'][$selection_id[0]->selection_id]['name'];
			else
				return false;
		}
		elseif($selection == 'selection_custom')
		{
			$selection_name = DB::table('inventory_selections_cross as cross')
			->leftJoin('inventory_custom_selections as custom', 'custom.id', '=', 'cross.selection_id')
			->select('custom.name as name')
			->where('cross.structure_id', $structure_id)
			->get();
			
			if($selection_name->isEmpty() == false)
				return $selection_name[0]->name;
			else
				return false;
		}
		else
			return false;
	}
	
	private function get_html_selection_custom($selection_custom)
	{
		if($selection_custom)
		{
			$html_childs = '';
			
			$html = '<div class="col-5">
						<div class="list-group" id="list-tab" role="tablist">
						';
			
			foreach($selection_custom as $key=>&$row)
			{
				$active = '';
				
				if($key == 0)
				{
					$key = ' show active';
					
					$active = ' active';
				}
				else
					$key = '';
					
				
				$html .= '<a class="list-group-item list-group-item-action list-group-item-light'.$active.'" data-id="'.$row['id'].'" id="selection_'.$row['id'].'" data-toggle="list" href="#list_'.$row['id'].'" role="tab" aria-controls="'.$row['id'].'">
								'.$row['name'].'
								<div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-light text-warning edit_selection" title="Редактировать список"><span class="oi oi-pencil"></span></button>
									<button type="button" class="btn btn-light text-danger delete_selection" title="Удалить список"><span class="oi oi-trash"></span></button>
								</div>
							</a>
							';
				
				$html_childs .= '<div class="tab-pane fade'.$key.'" id="list_'.$row['id'].'" role="tabpanel" aria-labelledby="selection_'.$row['id'].'">
									<ul class="list-group">';
				
				if(isset($row['childs']))
				{
					foreach($row['childs'] as &$child)
					{
						$html_childs .= '<li class="list-group-item list-group-item-light d-flex justify-content-between align-items-center" data-id="'.$child['id'].'" id="child_'.$child['id'].'">
										'.$child['name'].'
										<div class="btn-group btn-group-sm" role="group">
											<button type="button" class="btn btn-light text-warning edit_selection_element" title="Редактировать элемент списка"><span class="oi oi-pencil"></span></button>
											<button type="button" class="btn btn-light text-danger delete_selection_element" title="Удалить элемент списка"><span class="oi oi-trash"></span></button>
										</div>
									</li>';
					}
				}
				
				$html_childs .= '</ul>
							</div>';
			}
			
			$html .= '</div>
					</div>
					<div class="col-7">
						<div class="tab-content" id="nav-tabContent">
							'.$html_childs.'
						</div>
					</div>
					';
			
			return $html;
		}
	}
	
	private function get_type_fields_select($catalog_id, $selection_custom)
	{
		$type_field_html = '<option class="font-weight-bold" value="" selected>-- Тип поля --</option>';
			
		foreach($this->parametrs['type_fields'] as $key=>&$row)
		{
			if(isset($row['header']) and $row['header'] == true)
			{
				$type_field_html .= '<option class="font-weight-bold" disabled>-- '.$row['name'].' --</option>
				';
				
				if($key == 'selection')
				{
					foreach($this->parametrs['selection'] as $k=>&$selection)
						$type_field_html .= '<option data-id="'.$k.'" value="'.$key.'">'.$selection['name'].'</option>
					';
				}
				elseif($key == 'selection_custom')
				{
					if($selection_custom)
					{
						foreach($selection_custom as $custom)
							$type_field_html .= '<option data-id="'.$custom['id'].'" value="'.$key.'">'.$custom['name'].'</option>
						';
					}
				}
			}
			else
				$type_field_html .= '<option value="'.$key.'">'.$row['name'].'</option>
			';
		}
		
		return $type_field_html;
	}
	
	private function get_selection_custom($catalog_id, $selection_id=null, $user=false)
	{
		// print_r($user);
		// exit;
		$where = '';
		
		if($selection_id !== null)
		{
			$selection_id = DB::table('inventory_selections_cross')
							->select('selection_id')
							->where('structure_id', $selection_id)
							->groupBy('selection_id')
							->get();
			
			
			$where = [['id', '=', $selection_id[0]->selection_id]];
		}
		else
			$where = [['catalog_id', '=', $catalog_id]];
			
		$selection_list = DB::table('inventory_custom_selections')
							->select('id', 'name')
							->where($where)
							->when($user, function($query) use ($user){
								return $query->orWhere('user_id', $user['id']);
							})
							->orderBy('name')
							->get();
		
		if($selection_list->isEmpty() == false)
		{
			$selection_list = $selection_list->toArray();
							
			$id_list = array();
			
			foreach($selection_list as &$row)
			{
				$row = (array)$row;
				$id_list[] = $row['id'];
			}
			
			$selection_element_list = DB::table('inventory_custom_selections_elements')
									->select('id', 'selection_id', 'name')
									->whereIn('selection_id', $id_list)
									->orderBy('name')
									->get();
			
			if($selection_element_list->isEmpty() == false)
			{
				$selection_element_list = $selection_element_list->toArray();
				
				foreach($selection_list as &$row)
				{
					foreach($selection_element_list as &$element)
					{
						$element = (array)$element;
						
						if($element['selection_id'] == $row['id'])
							$row['childs'][] = $element;
					}
				}
			}
			
			return $selection_list;
		}
		else
			return false;
	}
	
	private function get_queue($list, $level_on_off=true, $id_to_key=false, $id=0, $max_level=null, $level=0)
	{
		$transient_arr = array();
		
		foreach($list as $row)
		{
			if($row->parent_id == $id)
			{
				$array_fields = array();
				$array_fields['id'] = $row->id;
				$array_fields['parent_id'] = $row->parent_id;
				
				if(isset($row->user_id))
					$array_fields['user_id'] = $row->user_id;
				
				if(isset($row->type_field))
					$array_fields['type_field'] = $row->type_field;
				
				if(isset($row->name))
					$array_fields['name'] = $row->name;
				
				if(isset($row->inventory_number))
					$array_fields['inventory_number'] = $row->inventory_number;
				
				if(isset($row->attach_user_id))
					$array_fields['attach_user_id'] = $row->attach_user_id;
				
				if(isset($row->log_flag))
					$array_fields['log_flag'] = $row->log_flag;
				
				if(isset($row->selection_id))
					$array_fields['selection_id'] = $row->selection_id;
				
				if(isset($row->width))
					$array_fields['width'] = $row->width;
				
				if(isset($row->height))
					$array_fields['height'] = $row->height;
				
				if(isset($row->position))
					$array_fields['position'] = $row->position;
				
				$transient_arr[] = $array_fields;
			}
		}
		
		foreach($transient_arr as $row)
		{
			if($max_level > 0 and $level >= $max_level)
				break;
			
			if($level_on_off == true)
			{
				$row['level'] = $level;
				
				$row['level_html'] = '';
				
				if($level > 0)
				{
					$count = $level*2;
					
					for($x=0; $x++<$count;)
						$row['level_html'] .= '&nbsp;';
				}
			}
			
			if($id_to_key == true)
				$this->ar[$row['id']] = $row;
			else
				$this->ar[] = $row;
			
			$this->id_list[] = $row['id'];
			
			$this->get_queue($list, $level_on_off, $id_to_key, $row['id'], $max_level, $level+1);
		}
	}
	
	private function get_user_catalogs($user_id)
	{
		$cat_id_list = DB::table('users_roles_groups')
						->select('catalog_id')
						->where('user_id', $user_id)
						->where('catalog_id', '!=', 0)
						->groupBy('catalog_id')
						->get();
		
		if(!empty($cat_id_list))
		{
			$id_array = array();
			
			foreach($cat_id_list as $row)
				$id_array[] = $row->catalog_id;
			
			return $id_array;
		}
		else
			return false;
	}
	
	private function get_cat_tree($catalog_list)
	{
		$new_array = array();
		
		foreach($catalog_list as &$row)
			$new_array[$row['id']] = array('parent_id'=>$row['parent_id'], 'name'=>$row['name']);
		
		foreach($new_array as &$row)
		{
			if(isset($new_array[$row['parent_id']]))
			{
				$new_array[$row['parent_id']][] = $row;
			}
		}
	}
	
	private function build_structure_tree_table($catalog_id)
	{
		$result = array();
		
		$structure_list = DB::table('inventory_table_structure as structure')
							// ->where('structure.type_field', 'selection_custom')
							->where('structure.catalog_id', $catalog_id)
							->where('structure.disable', 0)
							->leftJoin('inventory_selections_cross as cross', 'cross.structure_id', '=', 'structure.id')
							->orderBy('structure.position')
							->orderBy('structure.id')
							->select('structure.id', 'structure.parent_id', 'structure.type_field', 'structure.name', 'structure.position', 'structure.log_flag', 'cross.selection_id')
							->get();
		
		if($structure_list->isEmpty() == false)
		{
			$select_structure_html = '<option class="font-weight-bold" value="" selected>-- Поместить в ячейку --</option>
			';
			
			$this->ar = array();
			$this->get_queue($structure_list);
			
			// print_r($this->ar);
			// exit;
			
			if(!empty($this->ar))
			{
				$html = '';
				
				foreach($this->ar as $key=>&$row)
				{
					$level = '';
					
					if($row['level'] > 0)
					{
						$count = $row['level']*2;
						
						for($x=0; $x++<$count;)
							$level .= '&nbsp;';
					}
					
					$select_structure_html .= '<option value="'.$row['id'].'">'.$level.$row['name'].'</option>
					';
					
					if($row['parent_id'] > 0)
					{
						/* $row['parents'] = $row['parent_id'].' ';
						
						foreach($this->ar as &$ro)
						{
							if($ro['id'] == $row['parent_id'] and $ro['parents'] !== '')
							{
								$row['parents'] .= $ro['parents'];
								
								break;
							}
							elseif($ro['id'] == $row['parent_id'] and $ro['parents'] == '')
								break;
						}
						
						$row['parents'] = trim($row['parents']); */
						
						// $html .= '<tr data-id="'.$row['id'].'" data-parent="'.$row['parent_id'].'" data-parents="'.$row['parents'].'" class="d_none"><th class="pl-'.($row['level']+1).'">';
						
						// $row['html'] = '<div class="row d_none" data-id="'.$row['id'].'" data-parent="'.$row['parent_id'].'" data-parents="'.$row['parents'].'" data-position="'.$row['position'].'"><div class="col-sm-12"><div class="row parent_html"><div class="col-sm border-top border-bottom">';
						$row['html'] = '<div class="row d_none" data-id="'.$row['id'].'"><div class="col-sm-12"><div class="row parent_html"><div class="col-sm border-top border-bottom">';
					}
					else
					{
						// $row['parents'] = '';
						
						// $html .= '<tr data-id="'.$row['id'].'"><th class="pl-'.($row['level']+1).'">';
						// $row['html'] = '<div class="row" data-id="'.$row['id'].'" data-position="'.$row['position'].'"><div class="col-sm-12"><div class="row parent_html"><div class="col-sm border-top border-bottom">';
						$row['html'] = '<div class="row" data-id="'.$row['id'].'"><div class="col-sm-12"><div class="row parent_html"><div class="col-sm border-top border-bottom">';
					}
					
					++$key;
					
					if(isset($this->ar[$key]) and $this->ar[$key]['parent_id'] == $row['id'])
						$row['html'] .= '<button type="button" class="btn btn-link btn-sm text-info open_structure p-0" title="Раскрыть">
											<span class="oi oi-menu"></span>
										</button>';
						// $html .= '<button type="button" class="btn btn-link btn-sm text-info open_structure" title="Раскрыть">
											// <span class="oi oi-menu"></span>
										// </button>';
					
					// $html .= '</th>
							// <td class="pl-'.($row['level']+3).'">'.$row['name'].'</td>';
					// $row['html'] .= '</div>
									// <div class="col-sm-6 border border-left-0">'.$level.$row['name'].'</div>';
					$row['html'] .= '</div>
									<div class="col-sm-6 border border-left-0">'.$row['name'].'</div>';
					
					if($row['type_field'] == 'selection' or $row['type_field'] == 'selection_custom')
						$row['html'] .= '<div class="col-sm-3 border border-left-0" data-type="'.$row['type_field'].'" data-id="'.$row['selection_id'].'">'.$this->get_selection_name($row['id'], $row['type_field']).'</div>';
						// $html .= '<td data-type="'.$row['type_field'].'" data-id="'.$row['selection_id'].'">'.$this->get_selection_name($row['id'], $row['type_field']).'</td>';
					else
						$row['html'] .= '<div class="col-sm-3 border border-left-0" data-type="'.$row['type_field'].'">'.$this->parametrs['type_fields'][$row['type_field']]['name'].'</div>';
						// $html .= '<td data-type="'.$row['type_field'].'">'.$this->parametrs['type_fields'][$row['type_field']]['name'].'</td>';
					
					if($row['log_flag'] == 1)
						$row['html'] .= '<div class="col-sm border-top border-bottom" data-log="1"><small class="text-muted"><span class="oi oi-check"></span></small></div>';
						// $html .= '<td data-log="1"><small class="text-muted"><span class="oi oi-check"></span></small></td>';
					else
						$row['html'] .= '<div class="col-sm border-top border-bottom"></div>';
						// $html .= '<td></td>';
							
					$row['html'] .= '<div class="col-sm border-top border-bottom">
									<div class="btn-group btn-group-sm" role="group">
										<button type="button" class="btn btn-light text-warning edit" title="Редактировать ячейку"><span class="oi oi-pencil"></span></button>
										<button type="button" class="btn btn-light btn-sm text-danger delete" title="Удалить ячейку"><span class="oi oi-trash"></span></button>
									</div>
								</div>
							</div>
							<div class="row disable" data-id="0"></div>
							</div>
							';
					// $html .= '<td>
								// <div class="btn-group btn-group-sm" role="group">
									// <button type="button" class="btn btn-light text-warning edit" title="Редактировать ячейку"><span class="oi oi-pencil"></span></button>
									// <button type="button" class="btn btn-light btn-sm text-danger delete" title="Удалить ячейку"><span class="oi oi-trash"></span></button>
								// </div>
							// </td>
						// </tr>
						// ';
					
				}
				
				// $result['structure_html'] = $html;
				$result['structure_html'] = $this->ar;
			}
			else
				$select_structure_html = '<option class="font-weight-bold" value="" selected disabled>-- Поместить в ячейку --</option>
				';
		}
		else
			$select_structure_html = '<option class="font-weight-bold" value="" selected disabled>-- Поместить в ячейку --</option>
			';
		
		$result['select_structure_html'] = $select_structure_html;
		
		return $result;
	}
	
	/* private function get_files_structure($file_id = null, $list, $child_list = null)
	{
		$transient_arr = array();
		
		if($file_id > 0)
		{
			foreach($list as &$row)
			{
				if($row->parent_id == $file_id)
				{
					$this->ar[$row->id] = $row->id;
					$transient_arr[$row->id] = $row->id;
					
					unset($row);
				}
			}
		}
		elseif($child_list !== null)
		{
			foreach($child_list as &$row)
			{
				foreach($list as &$ro)
				{
					if($ro->parent_id == $row)
					{
						$this->ar[$ro->id] = $ro->id;
						$transient_arr[$ro->id] = $ro->id;
						
						unset($ro);
					}
				}
			}
		}
		
		if(!empty($transient_arr))
			$this->get_files_structure(null, $list, $transient_arr);
	} */
	
	private function get_user()
	{
		$okbdb_user_id = (int)$_COOKIE['user_id'];
		
		$user = DB::table('users')
					->where('users.okbdb_user_id', $okbdb_user_id)
					->leftJoin('users_roles_groups as roles', 'roles.user_id', '=', 'users.id')
					->select(DB::raw('min(roles.role) as role, users.id, users.okbdb_user_id, users.name'))
					->groupBy('users.id')
					->get();
		
		return (array)$user[0];
	}
	
	private function set_log($table, $structure_id, $fields_array)
	{
		if($structure_id == -1)
			DB::table($table)->insert($fields_array);
		else
		{
			$log_flag = DB::table('inventory_table_structure')
									->where('id', $structure_id)
									->pluck('log_flag');
			if($log_flag[0] == 1)					
				DB::table($table)->insert($fields_array);
		}
	}
	
	public function get_log(Request $request)
	{
		if($request->ajax())
		{
			$file_id = $request->input('file_id');
			$structure_id = $request->input('structure_id');
			$catalog_id = $request->input('catalog_id');
			
			// print_r($file_id);
			// print_r($structure_id);
			
			// $type_field = DB::table('inventory_table_structure')
							// ->where('id', $structure_id)
							// ->pluck('type_field');
			
			if($structure_id == -1)
			{
				$log_list = DB::table('inventory_log_attach_user as log')
							->leftJoin('users as user', 'user.id', '=', 'log.user_id')
							->select(DB::raw('log.select, DATE_FORMAT(log.date, "%d.%m.%Y %H:%i:%s") as date, user.name as user_name'))
							->where('log.file_id', $file_id)
							->orderBy('log.id')
							->get();
			}
			else
			{
				$select_id = DB::table('inventory_fields_type_select')
								->where('structure_id', $structure_id)
								->where('file_id', $file_id)
								->pluck('id');
				
				if($select_id->isEmpty() == true)
					exit;
			
				$log_list = DB::table('inventory_log_select as log')
								->leftJoin('users as user', 'user.id', '=', 'log.user_id')
								->select(DB::raw('log.select, DATE_FORMAT(log.date, "%d.%m.%Y %H:%i:%s") as date, user.name as user_name'))
								->where('log.select_id', $select_id[0])
								->orderBy('log.id')
								->get();
			}
			
			if($log_list->isEmpty() == false)
			{
				$html = '<table class="table table-sm">
						  <thead>
							<tr>
							  <th scope="col">Дата</th>
							  <th scope="col">Изменение</th>
							  <th scope="col">Модератор</th>
							</tr>
						  </thead>
						  <tbody>
						  ';
						  
				if($structure_id == -1)
				{
					$all_users = AllUsers::select('ID', 'NAME')->get();
					
					$all_users_array = array();
					
					foreach($all_users as &$row)
						$all_users_array[$row->ID] = iconv('cp1251', 'utf-8', $row->NAME);
					
					foreach($log_list as &$row)
					{
						$html .= '<tr>
									<td>'.$row->date.'</td>';
						
						if($row->select)
						{
							$html .=	'<td>'.$all_users_array[$row->select].'</td>';
						}
						else
							$html .=	'<td>Пустое значение</td>';
						
						$html .=	'<td>'.$row->user_name.'</td>
								</tr>
								';
						
					}
						$html .= '</tbody>
								</table>';
				}
				else
				{
					$structure_table = $this->get_structure_table($catalog_id);
					
					foreach($log_list as &$row)
					{
						$html .= '<tr>
									<td>'.$row->date.'</td>';
						
						if($row->select)
						{
							$html .=	'<td>'.$row->select = $structure_table[$structure_id]['html']['array'][$row->select].'</td>';
						}
						else
							$html .=	'<td>Пустое значение</td>';
						
						$html .=	'<td>'.$row->user_name.'</td>
								</tr>
								';
						
					}
						$html .= '</tbody>
								</table>';
				}
				// print_r($log_list);
				// exit;
				return $html;
			}
			else
				return 0;
		}
		else
			return 0;
	}
	
	
	
	public function get_table_list($id)
	{
		$user = $this->get_user();
		
		if($user and (int)$id > 0)
		{
			$catalog_list = DB::table('inventory_catalogs')
								->select('id', 'parent_id', 'user_id', 'name')
								->where('disable', 0)
								->where('id', (int)$id)
								->groupBy('id')
								->get();
			
			return view('inventory_list', array('catalog_list' => $catalog_list));
		}
	}
	
	public function get_list(Request $request)
	{
		if($request->ajax())
		{
			$catalog_id = $request->input('catalog_id');
			
			$result_array = array();
				
			$child_list = DB::table('inventory_catalogs')
						->select('id', 'name')
						->where('parent_id', $catalog_id)
						->where('disable', 0)
						->orderBy('name')
						->get();
			
			if($child_list->isEmpty() == false)
			{
				$result_html = '<div role="tablist">';
				
				foreach($child_list as $row)
				{
					$result_html .= '<div class="card alert-primary">
						<div class="card-header pt-1 pb-1" role="tab" data-catalog="'.$row->id.'">
							<h5 class="float-left mt-2">
								<a class="list_table text-dark" href="#0">'.$row->name.'</a>
							</h5>
						</div>
						<div data-catalog-body="'.$row->id.'" class="collapse">
							<div id="catalog_'.$row->id.'" class="card-body">
							</div>
						</div>
					</div>
					';
				}
				
				$result_html .= '</div>';
				
				$result_array['result_html'] = $result_html;
			}
			
			$result_array['structure_table'] = $this->get_structure_table($catalog_id);
			$html = '';
			
			if(isset($result_array['structure_table']))
			{
				foreach($result_array['structure_table'] as $key=>$row)
				{
					if($key == -1 or $row['name'] !== 'Наименование' and $row['name'] !== 'Модель' and $row['name'] !== 'Зав. номер' and $row['name'] !== 'Инвентарный номер 1' and $row['name'] !== 'Состояние' and $row['name'] !== 'Примечание')
						unset($result_array['structure_table'][$key]);
				}
				
				$result_array['table'] = $this->get_table($catalog_id, $result_array['structure_table']);
				
				// dd($result_array['table']);
				// $result_array['files'] = $this->ar;
				// print_r($result_array['files']);
				// exit;
				
				$all_users = AllUsers::select('ID', 'NAME')->orderBy('NAME')->get();
				
				$all_users_array = array();
				
				foreach($all_users as &$row)
					$all_users_array[$row->ID] = iconv('cp1251', 'utf-8', $row->NAME);
				
				if(!empty($this->ar))
				{
					$html .= '<table class="table table-bordered" style="width:600px;">
								<thead class="new_bg" style="color:#fff;">
									<tr>
										<th scope="col">Инвентарный номер</th>
										<th scope="col">Ответственный</th>
										<th scope="col">Наименование</th>
										<th scope="col">Модель</th>
										<th scope="col">Зав. номер</th>
										<th scope="col">Инвентарный номер 1</th>
										<th scope="col">Состояние</th>
										<th scope="col">Примечание</th>
									</tr>
								</thead>
							<tbody>
							';
					
					foreach($this->ar as $key=>&$row)
					{
						if($key % 2 == 0)
							$html .= '<tr class="bg_string_light">
								<td>'.$row['inventory_number'].'</td>
								';
						else
							$html .= '<tr class="bg-white">
								<td>'.$row['inventory_number'].'</td>
								';
						
						if($row['attach_user_id'] > 0)
							$html .= '<td>'.$all_users_array[$row['attach_user_id']].'</td>
									';
						else
							$html .= '<td></td>
									';
						
						foreach($result_array['structure_table'] as &$str)
						{
							if(isset($result_array['table'][$row['id']][$str['id']]))
							{
								if($str['type_field'] == 'selection' or $str['type_field'] == 'selection_custom')
								{
									if($result_array['table'][$row['id']][$str['id']]['select'] > 0 and isset($str['html']['array'][$result_array['table'][$row['id']][$str['id']]['select']]))
										$html .= '<td>'.$str['html']['array'][$result_array['table'][$row['id']][$str['id']]['select']].'</td>';
									else
										$html .= '<td></td>';
								}
								else
									$html .= '<td>'.$result_array['table'][$row['id']][$str['id']][$str['type_field']].'</td>';
							}
							else
								$html .= '<td></td>';
						}
						
						$html .= '</tr>';
					}
					
					
					$html .= '</tbody>
						</table>';
				}
			}
			if(isset($result_array['result_html']))
				$html .= $result_array['result_html'];
			
			return $html;
		}
		else
			return 0;
	}
	
	
	
	private function get_child_structure($table, $id=null, $childs = array())
	{
		if($id !== null)
		{
			$childs = DB::table($table)->select('id')->where('parent_id', $id)->get();
			
			if($childs->isEmpty() == false)
			{
				$array_id = array();
				
				foreach($childs as &$row)
				{
					$this->child_id_list[$row->id] = $row->id;
					$array_id[$row->id] = $row->id;
				}
				
				$this->get_child_structure($table, null, $array_id);
			}
			else
				return;
			
		}
		elseif(!empty($childs))
		{
			$new_childs = DB::table($table)->select('id')->whereIn('parent_id', $childs)->get();
			
			if($new_childs->isEmpty() == false)
			{
				$array_id = array();
				
				foreach($new_childs as &$row)
				{
					$this->child_id_list[$row->id] = $row->id;
					$array_id[$row->id] = $row->id;
				}
				
				$this->get_child_structure($table, null, $array_id);
			}
			else
				return;
		}
		else
			return;
	}
	
	
	
	
	public function search(Request $request)
	{
		if($request->ajax())
		{
			$value = $request->input('value');
			
			if($value == '')
			{
				
				$cat_list = DB::table('inventory_catalogs')
							->select('id', 'name')
							->where('parent_id', 0)
							->where('disable', 0)
							->orderBy('id')
							->get();
				
				if($cat_list->isEmpty() == false)
				{
					$result_array = array();
					
					$result_html = '';
					
					foreach($cat_list as $row)
					{
						$result_html .= '<div class="card alert-primary">
							<div class="card-header pt-1 pb-1" role="tab" data-catalog="'.$row->id.'">
								<h5 class="float-left mt-2">
									<a class="collapsed text-dark" href="#0">'.$row->name.'</a>
								</h5>
								<div class="btn-toolbar add_edit_delete">
									<div class="btn-group btn-group-sm float-right" role="group">
										<button type="button" class="btn btn-outline-success add_child" title="Добваить дочерний раздел"><span class="oi oi-plus"></span></button>
										<button type="button" class="btn btn-outline-warning edit_catalog" title="Редактировать раздел"><span class="oi oi-pencil"></span></button>
										<button type="button" class="btn btn-outline-danger delete_catalog" title="Удалить раздел"><span class="oi oi-trash"></span></button>
									</div>
									<div class="btn-group btn-group-sm float-right mr-4" role="group">
										<button type="button" class="btn btn-outline-secondary add_string" title="Добваить строку в таблице"><span class="oi oi-spreadsheet"></span></button>
										<button type="button" class="btn btn-outline-info modal_structure" title="Структура таблицы"><span class="oi oi-fork"></span></button>
									</div>
									<div class="btn-group btn-group-sm float-right mr-4" role="group">
										<button type="button" class="btn btn-outline-primary table_list" title="Ведомость"><span class="oi oi-document"></span></button>
									</div>
								</div>
							</div>
							<div data-catalog-body="'.$row->id.'" class="collapse">
								<div id="catalog_'.$row->id.'" class="card-body">
								</div>
							</div>
						</div>
						';
					}
					
					$result_array['result_html'] = $result_html;
					
					return $result_array;
				}
				else
					return 0;
			}
			
			$search_list = DB::table('inventory_search as search')
						->leftJoin('inventory_catalogs as cat', 'cat.id', '=', 'search.catalog_id')
						->select('search.catalog_id', 'cat.name as catalog_name', 'search.file_id', 'search.structure_id', 'search.field_id', 'search.type_field', 'search.text')
						->where('search.text', 'LIKE', '%'.$value.'%')
						->where('search.disable', 0)
						->where('cat.disable', 0)
						->orderBy('cat.name')
						->get();
			
			if($search_list->isEmpty() == false)
			{
				$all_users = AllUsers::select('ID', 'NAME', 'FF', 'II', 'OO')->orderBy('NAME')->get();
				
				$all_users_array = array();
				
				foreach($all_users as &$row)
					$all_users_array[$row->ID] = array('name'=>iconv('cp1251', 'utf-8', $row->NAME), 'full_name'=>iconv('cp1251', 'utf-8', $row->FF).' '.iconv('cp1251', 'utf-8', $row->II).' '.iconv('cp1251', 'utf-8', $row->OO));
				
				$result_array = array();
				
				foreach($search_list as &$row)
				{
					$result_array[$row->catalog_id]['catalog_name'] = $row->catalog_name;
					$result_array[$row->catalog_id]['file_list'][$row->file_id] = array('catalog_id'=>$row->catalog_id, 'catalog_name'=>$row->catalog_name, 'file_id'=>$row->file_id, 'structure_id'=>$row->structure_id, 'field_id'=>$row->field_id, 'type_field'=>$row->type_field, 'text'=>$row->text);
					$result_array[$row->catalog_id]['file_id_list'][] = $row->file_id;
				}
				
				
				foreach($result_array as $key=>&$row)
				{
					$row['catalog'] = '<div class="card alert-primary">
						<div class="card-header pt-1 pb-1" data-catalog="'.$key.'">
							<h5 class="float-left mt-2">
								<a class="collapsed text-dark just_close" href="#0">
								'.$row['catalog_name'].'
								</a>
							</h5>
							<div class="btn-toolbar add_edit_delete" role="toolbar">
								<div class="btn-group btn-group-sm float-right" role="group">
									<button type="button" class="btn btn-outline-success add_child" title="Добваить дочерний раздел"><span class="oi oi-plus"></span></button>
									<button type="button" class="btn btn-outline-warning edit_catalog" title="Редактировать раздел"><span class="oi oi-pencil"></span></button>
									<button type="button" class="btn btn-outline-danger delete_catalog" title="Удалить раздел"><span class="oi oi-trash"></span></button>
								</div>
								<div class="btn-group btn-group-sm float-right mr-4" role="group">
									<button type="button" class="btn btn-outline-secondary add_string" title="Добваить строку в таблице"><span class="oi oi-spreadsheet"></span></button>
									<button type="button" class="btn btn-outline-info modal_structure" title="Структура таблицы"><span class="oi oi-fork"></span></button>
								</div>
								<div class="btn-group btn-group-sm float-right mr-4" role="group">
									<button type="button" class="btn btn-outline-primary table_list" title="Ведомость"><span class="oi oi-document"></span></button>
								</div>
							</div>
						</div>
						<div data-catalog-body="'.$key.'" class="collapse show">
							<div id="catalog_'.$key.'" class="card-body">
							</div>
						</div>
					</div>
					';
					
					$row['structure_table'] = $this->get_structure_table($key);
					
					
					$row['table'] = $this->get_table($key, $row['structure_table'], $row['file_id_list']);
					
					foreach($this->ar as &$ro)
					{
						if($ro['attach_user_id'] > 0)
							$ro['attach_user'] = $all_users_array[$ro['attach_user_id']];
					}
					
					$row['files'] = $this->ar;
				}
				
				// dd($result_array);
				return $result_array;
			}
			else
				return 0;
		}
	}
	
	
	
	
	public function sortable(Request $request)
	{
		if($request->ajax())
		{
			$id = (int)$request->input('id');
			
			$catalog_id = (int)$request->input('catalog_id');
			
			$parent_id = (int)$request->input('parent_id');
			
			$sortable_list = array_diff($request->input('sortable_list'), array(''));
			
			// print_r($sortable_list);
			
			$position = 1;
			
			foreach($sortable_list as $row)
			{
				if($row == $id)
					DB::table('inventory_table_structure')->where('id', $row)->update(array('parent_id'=>$parent_id, 'position'=>$position));
				else
					DB::table('inventory_table_structure')->where('id', $row)->update(array('position'=>$position));
				
				$position++;
			}
			
			$structure_id_list = DB::table('inventory_table_structure')->where('catalog_id', $catalog_id)->select('id')->get();
			
			$structure_id_list_array = array();
			
			foreach($structure_id_list as $row)
				$structure_id_list_array[] = $row->id;
			
			DB::table('inventory_table_structure_height')->whereIn('structure_id', $structure_id_list_array)->delete();
			DB::table('inventory_table_structure_width')->whereIn('structure_id', $structure_id_list_array)->delete();
			
			return 1;
		}
	}
	
	
	
	
	private function import()
	{
		/* $catalog_list = AllUsers::where('ID', '!=', 3)->where('PID', '!=', 3)->orderBy('id')->get();
		
		foreach($catalog_list as $row)
		{
			DB::table('inventory_catalogs')->insert(['old_id'=>$row->ID, 'old_parent_id'=>$row->PID, 'user_id'=>1, 'parent_id'=>0, 'name'=>iconv('cp1251', 'utf-8', $row->NAME), 'disable'=>0]);
		}
		
		$catalogs = DB::table('inventory_catalogs')->get();
		
		foreach($catalogs as $row)
		{
			DB::table('inventory_catalogs')->where('old_parent_id', $row->old_id)->update(['parent_id'=>$row->id]);
		}
		
		echo 1; */
		
		/* $structure_list = DB::table('inventory_table_structure as str')
							->leftJoin('inventory_selections_cross as cross', 'cross.structure_id', '=', 'str.id')
							->where('str.catalog_id', 1)
							->select('str.id', 'str.parent_id', 'str.type_field', 'str.name', 'str.log_flag', 'cross.selection_id')
							->get();
		
		$this->ar = '';
		$this->get_queue($structure_list, true, true);
		
		$catalogs = DB::table('inventory_catalogs')->where('id', '>', 1)->get();
		
		foreach($catalogs as &$row)
		{
			$new_id_list = array();
			
			foreach($this->ar as $key=>&$ro)
			{
				$parent_id = 0;
				$id = 0;
				
				if($ro['parent_id'] > 0)
					$parent_id = $new_id_list[$ro['parent_id']];
				
				if($ro['type_field'] == 'selection' or $ro['type_field'] == 'selection_custom')
				{
					$id = DB::table('inventory_table_structure')->insertGetId(['parent_id'=>$parent_id, 'type_field'=>$ro['type_field'], 'name'=>$ro['name'], 'log_flag'=>$ro['log_flag'], 'catalog_id'=>$row->id, 'disable'=>0]);
					
					DB::table('inventory_selections_cross')->insert(['structure_id'=>$id, 'selection_id'=>$ro['selection_id']]);
				}
				else
					$id = DB::table('inventory_table_structure')->insertGetId(['parent_id'=>$parent_id, 'type_field'=>$ro['type_field'], 'name'=>$ro['name'], 'log_flag'=>$ro['log_flag'], 'catalog_id'=>$row->id, 'disable'=>0]);
				
				$new_id_list[$key] = $id;
			}
		}
		
		echo 1;
		exit; */
		
		
		/* $catalogs = DB::table('inventory_catalogs')->get();
		
		foreach($catalogs as &$row)
		{
			$files = '';
			
			$files = AllUsers::where('ID_inv_cat', $row->old_id)->get();
			
			if($files->isEmpty() == false)
			{
				$structure_list = '';
				
				$structure_list = DB::table('inventory_table_structure as str')
							->leftJoin('inventory_selections_cross as cross', 'cross.structure_id', '=', 'str.id')
							->where('str.catalog_id', $row->id)
							->select('str.id', 'str.parent_id', 'str.type_field', 'str.name', 'str.log_flag', 'cross.selection_id')
							->get();
				
				$this->ar = '';
				$this->get_queue($structure_list, true, true);
				
				foreach($files as &$ro)
				{
					$inventory_number = 0;
					$file_id = 0;
						
					$file_id = DB::table('inventory_files')->insertGetId(['catalog_id'=>$row->id, 'parent_id'=>0, 'inventory_number'=>$inventory_number, 'disable'=>0]);
					
					$max_inventory_number = DB::table('inventory_files')->where('catalog_id', '=', $row->id)->max('inventory_number');
						
					if($max_inventory_number == 0)
						$max_inventory_number = $row->id * 100000;
						
					$inventory_number = ++$max_inventory_number;
					
					DB::table('inventory_files')->where('id', $file_id)->update(['inventory_number'=>$inventory_number]);
					
					foreach($this->ar as &$str)
					{
						if($str['name'] == 'Наименование')
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->NAME)]);
						elseif($str['name'] == 'Модель')
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->MODEL)]);
						elseif($str['name'] == 'Зав. номер')
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->ZAVNUM)]);
						elseif($str['name'] == 'Порядковый номер' and $ro->ORD_NUM > 0)
							DB::table('inventory_fields_type_number')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'number'=>$ro->ORD_NUM]);
						elseif($str['name'] == 'Инвентарный номер 1' and $ro->INV > 0)
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->INV)]);
						elseif($str['name'] == 'Инвентарный номер 2' and $ro->OLD_INV > 0)
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->OLD_INV)]);
						elseif($str['name'] == 'Комплектность')
							DB::table('inventory_fields_type_textarea')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'textarea'=>iconv('cp1251', 'utf-8', $ro->KOMPL)]);
						elseif($str['name'] == 'Состояние')
							DB::table('inventory_fields_type_select')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'select'=>$ro->SOST]);
						elseif($str['name'] == 'Выдано' and $ro->DATEVID > 0)
						{
							$datevid = preg_split('//',$ro->DATEVID);
							array_pop($datevid);
							array_shift($datevid);
							
							$datevid = $datevid[0].$datevid[1].$datevid[2].$datevid[3].'-'.$datevid[4].$datevid[5].'-'.$datevid[6].$datevid[7];
							
							DB::table('inventory_fields_type_date')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'date'=>$datevid]);
						}
						elseif($str['name'] == 'Ответственный')
							DB::table('inventory_fields_type_select')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'select'=>$ro->ID_resurs]);
						elseif($str['name'] == 'Использование')
						{
							if($ro->USETP == 1)
								$ro->USETP = 5;
							elseif($ro->USETP == 2)
								$ro->USETP = 6;
							elseif($ro->USETP == 3)
								$ro->USETP = 7;
							
							DB::table('inventory_fields_type_select')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'select'=>$ro->USETP]);
						}
						elseif($str['name'] == 'Год выпуска' and $ro->YY > 0)
							DB::table('inventory_fields_type_number')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'number'=>$ro->YY]);
						elseif($str['name'] == 'Дата последней поверки' and $ro->POVDATE > 0)
						{
							$povdate = preg_split('//',$ro->POVDATE);
							array_pop($povdate);
							array_shift($povdate);
							
							$povdate = $povdate[0].$povdate[1].$povdate[2].$povdate[3].'-'.$povdate[4].$povdate[5].'-'.$povdate[6].$povdate[7];
							
							DB::table('inventory_fields_type_date')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'date'=>$povdate]);
						}
						elseif($str['name'] == 'Класс точности')
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->TCLASS)]);
						elseif($str['name'] == 'Цена деления, диапазон измерений')
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->SCALE)]);
						elseif($str['name'] == 'Дата последней инвентаризации' and $ro->LASTDATE > 0)
						{
							$lastdate = preg_split('//',$ro->LASTDATE);
							array_shift($lastdate);
							array_pop($lastdate);
							
							$lastdate = $lastdate[0].$lastdate[1].$lastdate[2].$lastdate[3].'-'.$lastdate[4].$lastdate[5].'-'.$lastdate[6].$lastdate[7];
							
							DB::table('inventory_fields_type_date')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'date'=>$lastdate]);
						}
						elseif($str['name'] == 'Дата приобретения СИ' and $ro->PRIOBR > 0)
							DB::table('inventory_fields_type_text')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $ro->PRIOBR)]);
						elseif($str['name'] == 'Место нахождения')
							DB::table('inventory_fields_type_select')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'select'=>$ro->ID_inv_places]);
						elseif($str['name'] == 'Примечание')
							DB::table('inventory_fields_type_textarea')->insert(['structure_id'=>$str['id'], 'file_id'=>$file_id, 'textarea'=>iconv('cp1251', 'utf-8', $ro->MORE)]);
					}
				}
			}
			else
				continue;
		}
		
		echo 1; */
		
		/* $catalog_id = 7;
		
		$files = AllUsers::where('ID_inv_cat', $catalog_id)->get();
		
		foreach($files as $row)
		{
			$inventory_number = 0;
			$file_id = 0;
				
			$file_id = DB::table('inventory_files')->insertGetId(['catalog_id'=>$catalog_id, 'parent_id'=>0, 'inventory_number'=>$inventory_number, 'disable'=>0]);
			
			$max_inventory_number = DB::table('inventory_files')->where('catalog_id', '=', $catalog_id)->max('inventory_number');
				
			if($max_inventory_number == 0)
				$max_inventory_number = $catalog_id * 100000;
				
			$inventory_number = ++$max_inventory_number;
			
			DB::table('inventory_files')->where('id', $file_id)->update(['inventory_number'=>$inventory_number]);
			
			DB::table('inventory_fields_type_text')->insert(['structure_id'=>33, 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $row->NAME)]);
			DB::table('inventory_fields_type_text')->insert(['structure_id'=>34, 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $row->MODEL)]);
			
			if($row->YY > 0)
				DB::table('inventory_fields_type_number')->insert(['structure_id'=>43, 'file_id'=>$file_id, 'number'=>$row->YY]);
			if($row->INV > 0)
				DB::table('inventory_fields_type_text')->insert(['structure_id'=>36, 'file_id'=>$file_id, 'text'=>$row->INV]);
			if($row->OLD_INV > 0)
				DB::table('inventory_fields_type_text')->insert(['structure_id'=>37, 'file_id'=>$file_id, 'text'=>$row->OLD_INV]);
			
			DB::table('inventory_fields_type_select')->insert(['structure_id'=>41, 'file_id'=>$file_id, 'select'=>iconv('cp1251', 'utf-8', $row->ID_resurs)]);
			DB::table('inventory_fields_type_select')->insert(['structure_id'=>39, 'file_id'=>$file_id, 'select'=>iconv('cp1251', 'utf-8', $row->SOST)]);
			
			if($row->LASTDATE > 0)
			{
				$lastdate = preg_split('//',$row->LASTDATE);
				array_shift($lastdate);
				array_pop($lastdate);
				
				$lastdate = $lastdate[0].$lastdate[1].$lastdate[2].$lastdate[3].'-'.$lastdate[4].$lastdate[5].'-'.$lastdate[6].$lastdate[7];
				
				DB::table('inventory_fields_type_date')->insert(['structure_id'=>45, 'file_id'=>$file_id, 'date'=>$lastdate]);
			}
			if($row->POVDATE > 0)
			{
				$povdate = preg_split('//',$row->POVDATE);
				array_pop($povdate);
				array_shift($povdate);
				
				$povdate = $povdate[0].$povdate[1].$povdate[2].$povdate[3].'-'.$povdate[4].$povdate[5].'-'.$povdate[6].$povdate[7];
				
				DB::table('inventory_fields_type_date')->insert(['structure_id'=>44, 'file_id'=>$file_id, 'date'=>$povdate]);
			}
			
			DB::table('inventory_fields_type_text')->insert(['structure_id'=>47, 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $row->MORE)]);
			DB::table('inventory_fields_type_text')->insert(['structure_id'=>35, 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $row->ZAVNUM)]);
			DB::table('inventory_fields_type_text')->insert(['structure_id'=>38, 'file_id'=>$file_id, 'text'=>iconv('cp1251', 'utf-8', $row->KOMPL)]);
			
			if($row->DATEVID > 0)
			{
				$datevid = preg_split('//',$row->DATEVID);
				array_pop($datevid);
				array_shift($datevid);
				
				$datevid = $datevid[0].$datevid[1].$datevid[2].$datevid[3].'-'.$datevid[4].$datevid[5].'-'.$datevid[6].$datevid[7];
				
				DB::table('inventory_fields_type_date')->insert(['structure_id'=>40, 'file_id'=>$file_id, 'date'=>$datevid]);
			}
			
			if($row->USETP == 1)
				$row->USETP = 5;
			elseif($row->USETP == 2)
				$row->USETP = 6;
			elseif($row->USETP == 3)
				$row->USETP = 7;
			
			DB::table('inventory_fields_type_select')->insert(['structure_id'=>42, 'file_id'=>$file_id, 'select'=>$row->USETP]);
			
			DB::table('inventory_fields_type_select')->insert(['structure_id'=>46, 'file_id'=>$file_id, 'select'=>$row->ID_inv_places]);
			
			// print_r($row);
			// exit;
		}
		
		echo 1; */
	}
	
	private function import_attachment_user()
	{
		/* $structure_list = DB::table('inventory_table_structure')
							->where('name', 'Ответственный')
							->where('disable', 0)
							->select('id')
							->get();
		
		$structure_id_array = array();
		$str_array = array();
		
		foreach($structure_list as &$row)
		{
			$str_array[$row->id] = $row->id;
			
			$select_list = DB::table('inventory_fields_type_select')
							->where('structure_id', $row->id)
							->select('id as select_id', 'file_id', 'select')
							->get();
			
			
			$select_list_array = array();
			
			if($select_list->isEmpty() == false)
			{
				foreach($select_list as &$ro)
				{
					if($ro->select == null)
						$ro->select = 0;
					
					DB::table('inventory_files')->where('id', $ro->file_id)->update(['attach_user_id'=>$ro->select]);
					
					$log_list = DB::table('inventory_log_select')
								->select('user_id', 'select', 'date')
								->where('select_id', $ro->select_id)
								->orderBy('id')
								->get();
					
					
					$log_list_array = array();
					
					if($log_list->isEmpty() == false)
					{
				
						foreach($log_list as &$list)
						{
							$log_list_array[] = array('user_id'=>$list->user_id, 'select'=>$list->select, 'date'=>$list->date);
						}
					}
					
					$select_list_array[] = array('file_id'=>$ro->file_id, 'select'=>$ro->select_id, 'log_list'=>$log_list_array);
				}
			}
			
			$structure_id_array[$row->id] = $select_list_array;
		}
		// print_r($structure_id_array);
		// exit;
		
		foreach($structure_id_array as &$row)
		{
			if(count($row) > 0)
			{
				foreach($row as &$r)
				{
					if(!empty($r['log_list']))
					{
						foreach($r['log_list'] as &$log)
						{
							if($log['select'] == '')
								$log['select'] = 0;
							
							DB::table('inventory_log_attach_user')->insert(['file_id'=>$r['file_id'], 'user_id'=>$log['user_id'], 'select'=>$log['select'], 'date'=>$log['date']]);
						}
					}
				}
			}
		}
		
		DB::table('inventory_table_structure')->whereIn('id', $str_array)->update(['disable'=>1]);
		DB::table('inventory_table_structure')->where('name', 'Использование')->update(['parent_id'=>0]);
		DB::table('inventory_table_structure_height')->whereIn('structure_id', $str_array)->delete();
		DB::table('inventory_table_structure_width')->whereIn('structure_id', $str_array)->delete();
		
		echo 1; */
	}
	
	private function import_search()
	{
		/* $file_list = DB::table('inventory_files')
					->select('id', 'catalog_id', 'inventory_number', 'attach_user_id')
					->where('disable', 0)
					->get();
		
		
		$custom_selections_elements = DB::table('inventory_custom_selections_elements')->select('id', 'selection_id', 'name')->get();
		
		$custom_selection_list = array();
		
		foreach($custom_selections_elements as &$row)
			$custom_selection_list[$row->selection_id][$row->id] = $row->name;
		
		
		$old_places = OldInventoryPlaces::select('ID', 'NAME')->get();
		
		$old_places_array = array();
		
		foreach($old_places as &$row)
			$old_places_array[$row->ID] = iconv('cp1251', 'utf-8', $row->NAME);
		
		
		
		$all_users = AllUsers::select('ID', 'NAME')->get();
		
		$all_users_array = array();
		
		foreach($all_users as &$row)
			$all_users_array[$row->ID] = iconv('cp1251', 'utf-8', $row->NAME);
		
		
		$file_list_array = array();
		
		foreach($file_list as &$row)
		{
			if(isset($all_users_array[$row->attach_user_id]))
				$file_list_array[$row->id] = array('catalog_id'=>$row->catalog_id, 'inventory_number'=>$row->inventory_number, 'attach_user'=>$all_users_array[$row->attach_user_id], 'array'=>array());
			else
				$file_list_array[$row->id] = array('catalog_id'=>$row->catalog_id, 'inventory_number'=>$row->inventory_number, 'attach_user'=>'', 'array'=>array());
		}
		
		$date_list = DB::table('inventory_fields_type_date as dt')
					->leftJoin('inventory_table_structure as str', 'str.id', '=', 'dt.structure_id')
					->select(DB::raw('dt.id, dt.structure_id, dt.file_id, DATE_FORMAT(dt.date, "%d.%m.%Y") as date'))
					->where('str.disable', 0)
					->where('dt.date', '!=', null)
					->get();
		
		foreach($date_list as &$row)
		{
			if(isset($file_list_array[$row->file_id]))
				$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'date', 'text'=>$row->date);
		}
		
		
		$number_list = DB::table('inventory_fields_type_number as num')
					->leftJoin('inventory_table_structure as str', 'str.id', '=', 'num.structure_id')
					->select(DB::raw('num.id, num.structure_id, num.file_id, num.number'))
					->where('str.disable', 0)
					->where('num.number', '>', 0)
					->get();
		
		foreach($number_list as &$row)
		{
			if(isset($file_list_array[$row->file_id]))
				$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'number', 'text'=>$row->number);
		}
		
		
		$text_list = DB::table('inventory_fields_type_text as txt')
					->leftJoin('inventory_table_structure as str', 'str.id', '=', 'txt.structure_id')
					->select(DB::raw('txt.id, txt.structure_id, txt.file_id, txt.text'))
					->where('str.disable', 0)
					->where('txt.text', '!=', '')
					->where('txt.text', '!=', '0')
					->get();
		
		foreach($text_list as &$row)
		{
			if(isset($file_list_array[$row->file_id]))
				$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'text', 'text'=>$row->text);
		}
		
		
		$textarea_list = DB::table('inventory_fields_type_textarea as txt')
						->leftJoin('inventory_table_structure as str', 'str.id', '=', 'txt.structure_id')
						->select(DB::raw('txt.id, txt.structure_id, txt.file_id, txt.textarea'))
						->where('str.disable', 0)
						->where('txt.textarea', '!=', '')
						->where('txt.textarea', '!=', '0')
						->get();
		
		foreach($textarea_list as &$row)
		{
			if(isset($file_list_array[$row->file_id]))
				$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'textarea', 'text'=>$row->textarea);
		}
		
		
		$select_list = DB::table('inventory_fields_type_select as select')
						->leftJoin('inventory_table_structure as str', 'str.id', '=', 'select.structure_id')
						->leftJoin('inventory_selections_cross as cross', 'cross.structure_id', '=', 'str.id')
						->select(DB::raw('select.id, select.structure_id, select.file_id, select.select, str.type_field, cross.selection_id'))
						->where('str.disable', 0)
						->where('select.select', '!=', null)
						->where('select.select', '!=', 0)
						->get();
		
		foreach($select_list as &$row)
		{
			if(isset($file_list_array[$row->file_id]))
			{
				if($row->type_field == 'selection')
				{
					if($row->selection_id == 1)
						$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'selection', 'text'=>$all_users_array[$row->select]);
					elseif($row->selection_id == 2)
						$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'selection', 'text'=>$old_places_array[$row->select]);
				}
				else
				{
					$file_list_array[$row->file_id]['array'][] = array('field_id'=>$row->id, 'structure_id'=>$row->structure_id, 'type_field'=>'selection_custom', 'text'=>$custom_selection_list[$row->selection_id][$row->select]);
				}
			}
		}
		
		// dd($file_list_array[1]);
		// exit;
		
		foreach($file_list_array as $key=>&$row)
		{
			InventorySearch::updateOrCreate(['catalog_id'=>$row['catalog_id'], 'file_id'=>$key, 'structure_id'=>0, 'field_id'=>0, 'type_field'=>'inventory_number'], ['text'=>$row['inventory_number'], 'disable'=>0]);
			
			if($row['attach_user'] !== '')
				InventorySearch::updateOrCreate(['catalog_id'=>$row['catalog_id'], 'file_id'=>$key, 'structure_id'=>-1, 'field_id'=>0, 'type_field'=>'selection'], ['text'=>$row['attach_user'], 'disable'=>0]);
			
			if(!empty($row['array']))
			{
				foreach($row['array'] as &$ro)
				{
					InventorySearch::updateOrCreate(['catalog_id'=>$row['catalog_id'], 'file_id'=>$key, 'structure_id'=>$ro['structure_id'], 'field_id'=>$ro['field_id'], 'type_field'=>$ro['type_field']], ['text'=>$ro['text'], 'disable'=>0]);
				}
			}
		}
		
		echo 1;
		exit; */
	}
}
?>