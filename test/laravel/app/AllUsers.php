<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllUsers extends Model
{
    protected $connection = 'mysql_2';
	protected $table = 'okb_db_resurs';
	// protected $table = 'okb_db_inv';
	// protected $table = 'okb_db_inv_cat';
}
