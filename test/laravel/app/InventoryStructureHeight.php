<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryStructureHeight extends Model
{
    protected $table = 'inventory_table_structure_height';
	protected $fillable = ['structure_id', 'height'];
	public $timestamps = false;
}
