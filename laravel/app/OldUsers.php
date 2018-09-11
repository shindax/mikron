<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldUsers extends Model
{
    protected $connection = 'mysql_2';
	protected $table = 'okb_users';
}