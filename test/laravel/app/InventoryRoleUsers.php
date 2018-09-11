<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryRoleUsers extends Model
{
    protected $table = 'users_roles_groups';
	protected $fillable = ['user_id', 'parent_user', 'catalog_id', 'role'];
	public $timestamps = false;
}
