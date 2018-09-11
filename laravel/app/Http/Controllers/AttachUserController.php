<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\AllUsers;
use App\OldUsers;
use App\InventoryRoleUsers;
use App\OldInventoryPlaces;
use App\InventoryStructureHeight;
use App\InventoryStructureWidth;
use Config;

use Validator;

class AttachUserController extends Controller
{
    public function __construct()
	{
		$this->parametrs = Config::get('parametrs');
	}
	
	public function index($id)
    {
		$file_list = DB::table('inventory_files as file')
							->leftJoin('inventory_catalogs as cat', 'cat.id', '=', 'file.catalog_id')
							->select('file.id', 'file.catalog_id', 'file.inventory_number', 'cat.name as catalog_name')
							->where('file.attach_user_id', $id)
							->where('file.disable', 0)
							->get();
		
		if($file_list->isEmpty() == false)
		{
			$catalog_list = array();
			
			foreach($file_list as $row)
			{
				$catalog_list[$row->catalog_id] = $row->catalog_name;
			}
			
			return view('attach_users', array('catalog_list' => $catalog_list, 'attach_user'=>$id));
			
		}
	}
}
