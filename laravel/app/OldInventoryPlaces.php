<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldInventoryPlaces extends Model
{
	protected $connection = 'mysql_2';
	protected $table = 'okb_db_inv_places';
}
