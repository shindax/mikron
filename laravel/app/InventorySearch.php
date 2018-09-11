<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventorySearch extends Model
{
    protected $table = 'inventory_search';
	protected $fillable = ['catalog_id', 'file_id', 'structure_id', 'field_id', 'type_field', 'text', 'disable'];
	public $timestamps = false;
}
