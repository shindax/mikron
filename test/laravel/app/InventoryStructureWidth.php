<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryStructureWidth extends Model
{
    protected $table = 'inventory_table_structure_width';
	protected $fillable = ['structure_id', 'width'];
	public $timestamps = false;
}
