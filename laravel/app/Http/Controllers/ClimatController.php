<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

use App\AllUsers;
use App\OldUsers;
use Carbon\CarbonPeriod;
use Config;

use Validator;

class ClimatController extends Controller
{
    public function __construct()
	{
		// $this->parametrs = Config::get('parametrs_budgeting');
	}
	
	
	public function index(Request $request)
    {
		$okbdb_user_id = (int)$_COOKIE['user_id'];
		
		// print_r($okbdb_user_id);
		// exit;
		
		return view('climat', array('user_id'=>$okbdb_user_id, 'workplaces'=>$this->get_workplace()));
	}
	
	
	
	public function set_workplace(Request $request)
	{
		if($request->ajax())
		{
			$id = (int)$request->input('id');
			
			$catalog_id = (int)$request->input('catalog_id');
			
			$cat_flag = $request->input('cat_flag');
			
			$text = $request->input('text');
			
			$delete = (int)$request->input('delete');
			
			if($delete > 0)
			{
				if($cat_flag == 'true')
				{
					DB::table('workplace_catalogs')->where('id', $delete)->delete();
					
					$workplace_files_id_array = array();
					
					$workplace_files_id_list = DB::table('workplace_files')->where('catalog_id', $delete)->select('id')->get();
					
					if($workplace_files_id_list->isEmpty() == false)
					{
						foreach($workplace_files_id_list as &$row)
							$workplace_files_id_array[] = $row->id;
					}
					
					if(!empty($workplace_files_id_array))
					{
						DB::table('workplace_environment')->whereIn('zone_id', $workplace_files_id_array)->delete();
					}
					
					DB::table('workplace_files')->where('catalog_id', $delete)->delete();
				}
				else
				{
					DB::table('workplace_environment')->where('zone_id', $delete)->delete();
					
					DB::table('workplace_files')->where('id', $delete)->delete();
				}
					
				return 0;
			}
			elseif($id == 0)
			{
				if($cat_flag == 'true')
					$id = DB::table('workplace_catalogs')->insertGetId(array('name'=>$text));
				else
					$id = DB::table('workplace_files')->insertGetId(array('name'=>$text, 'catalog_id'=>$catalog_id));
			}
			else
			{
				if($cat_flag == 'true')
					$id = DB::table('workplace_catalogs')->where('id', $id)->update(array('name'=>$text));
				else
					$id = DB::table('workplace_files')->where('id', $id)->update(array('name'=>$text));
			}
			
			return $id;
		}
	}
	
	
	
	private function get_workplace()
	{
		$workplaces = array();
		
		$workplace_catalogs = DB::table('workplace_catalogs')->orderBy('name')->get();
		
		if($workplace_catalogs->isEmpty() == false)
		{
			foreach($workplace_catalogs as &$row)
			{
				$workplaces[$row->id] = array('name'=>$row->name);
			}
			
			$workplace_files = DB::table('workplace_files')->orderBy('name')->get();
			
			if($workplace_files->isEmpty() == false)
			{
				foreach($workplace_files as &$row)
				{
					$workplaces[$row->catalog_id]['children'][] = array('id'=>$row->id, 'name'=>$row->name);
				}
			}
		}
		
		return $workplaces;
	}
	
	
	
	public function filter(Request $request)
	{
		if($request->ajax())
		{
			$id = $request->input('id');
			$workplaces = $request->input('workplaces');
			
			$place_list = DB::table('workplace_files as files')
									->leftJoin('workplace_catalogs as catalogs', 'catalogs.id', '=', 'files.catalog_id')
									->leftJoin('workplace_environment as environment', 'environment.zone_id', '=', 'files.id')
									->select(DB::raw('files.id, files.name, catalogs.name as area, MAX(environment.id) as current_data_id'))
									->whereIn('files.id', $workplaces)
									->orderBy('files.name')
									->groupBy('files.id')
									->get();
			
			if($place_list->isEmpty() == false)
			{
				$place_list_array = array();
				$current_data_id_array = array();
				
				foreach($place_list as $row)
				{
					$place_list_array[$row->id] = array('name'=>$row->name.' ('.$row->area.')', 'min_temperature'=>'', 'max_temperature'=>'', 'min_humidity'=>'', 'max_humidity'=>'', 'sum_temperature'=>0, 'sum_humidity'=>0, 'count'=>0);
					$current_data_id_array[] = $row->current_data_id;
				}
				
				$environment_current_list = DB::table('workplace_environment')
											->select(DB::raw('zone_id, DATE_FORMAT(date, "%d.%m.%Y %H:%i:%s") as date, temperature, humidity'))
											->whereIn('id', $current_data_id_array)
											->get();
				
				if($environment_current_list->isEmpty() == false)
				{
					foreach($environment_current_list as $row)
					{
						$place_list_array[$row->zone_id]['current_data'] = array('date'=>$row->date, 'temperature'=>$row->temperature, 'humidity'=>$row->humidity);
					}
				}
				
				$environment_list = DB::table('workplace_environment')
									// ->select(DB::raw('zone_id, DATE_FORMAT(date, "%H,%i,%s,%m,%d,%Y") as date, temperature, humidity'))
									->select(DB::raw('zone_id, DATE_FORMAT(date, "%Y,%m,%d,%H,%i,%s") as date, temperature, humidity'))
									->when($id == 'today', function ($query) {
										return $query->whereDate('date', date("Y-m-d"));
									})
									->when($id == 'week', function ($query) {
										return $query->whereDate('date', '>=', date("Y-m-d", strtotime('Mon this week')))->whereDate('date', '<=', date("Y-m-d", strtotime('Sun this week')));
										// return $query->whereBetween('date', [date("Y-m-d", strtotime('Mon this week')), date("Y-m-d", strtotime('Sun this week'))]);
									})
									->when($id == 'month', function ($query) {
										return $query->whereDate('date', '>=', date("Y-m-01"))->whereDate('date', '<=', date("Y-m-t"));
										// return $query->whereBetween('date', [date("Y-m-01"), date("Y-m-t")]);
									})
									->when($id == 'date', function ($query) use ($request) {
										
										$date_start = trim($request->input('date_start'));
										$date_end = trim($request->input('date_end'));
										
										if($date_start !== '' and $date_end !== '')
											return $query->whereDate('date', '>=', $date_start)->whereDate('date', '<=', $date_end);
											// return $query->whereBetween('date', [$date_start, $date_end]);
										elseif($date_start !== '')
											return $query->whereDate('date', '>=', $date_start);
										elseif($date_end !== '')
											return $query->whereDate('date', '<=', $date_end);
									})
									->whereIn('zone_id', $workplaces)
									->orderBy('id')
									->get();
				
				if($environment_list->isEmpty() == false)
				{
					// print_r($environment_list);
					// exit;
					
					foreach($environment_list as &$row)
					{
						$row->date = explode(',', $row->date);
						
						$row->date[0] = (int)$row->date[0];
						$row->date[1] = (int)$row->date[1] - 1;
						$row->date[2] = (int)$row->date[2];
						$row->date[3] = (int)$row->date[3];
						$row->date[4] = (int)$row->date[4];
						$row->date[5] = (int)$row->date[5];
						
						$row->temperature = (float)$row->temperature;
						$row->humidity = (float)$row->humidity;
						
						$place_list_array[$row->zone_id]['data'][] = array('date'=>$row->date, 'temperature'=>$row->temperature, 'humidity'=>$row->humidity);
						
						++$place_list_array[$row->zone_id]['count'];
						
						$place_list_array[$row->zone_id]['sum_temperature'] += $row->temperature;
						$place_list_array[$row->zone_id]['sum_humidity'] += $row->humidity;
						
						if($place_list_array[$row->zone_id]['min_temperature'] == '')
						{
							$place_list_array[$row->zone_id]['min_temperature'] = $row->temperature;
							$place_list_array[$row->zone_id]['max_temperature'] = $row->temperature;
						}
						else
						{
							if($place_list_array[$row->zone_id]['min_temperature'] > $row->temperature)
								$place_list_array[$row->zone_id]['min_temperature'] = $row->temperature;
							
							if($place_list_array[$row->zone_id]['max_temperature'] < $row->temperature)
								$place_list_array[$row->zone_id]['max_temperature'] = $row->temperature;
						}
						
						if($place_list_array[$row->zone_id]['min_humidity'] == '')
						{
							$place_list_array[$row->zone_id]['min_humidity'] = $row->humidity;
							$place_list_array[$row->zone_id]['max_humidity'] = $row->humidity;
						}
						else
						{
							if($place_list_array[$row->zone_id]['min_humidity'] > $row->humidity)
								$place_list_array[$row->zone_id]['min_humidity'] = $row->humidity;
							
							if($place_list_array[$row->zone_id]['max_humidity'] < $row->humidity)
								$place_list_array[$row->zone_id]['max_humidity'] = $row->humidity;
						}
					}
				}
				
				$place_list_array = array_values($place_list_array);
				
				// print_r($place_list_array);
				// exit;
				
				return $place_list_array;
			}
			
			// print_r($id);
			// exit;
		}
	}
}
