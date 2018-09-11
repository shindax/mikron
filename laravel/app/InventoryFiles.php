<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryFiles extends Model
{
    protected $fillable = ['parent_id', 'catalog_id'];
	// protected $guarded = ['updated_at', 'created_at'];
	protected $table = 'inventory_files';
}
