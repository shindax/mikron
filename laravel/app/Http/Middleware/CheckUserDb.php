<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Closure;
use App\User;
use Config;

class CheckUserDb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if(isset($_COOKIE['user_id']) and isset($_COOKIE['user_pass']))
		{
			$okbdb_user_id = (int)$_COOKIE['user_id'];
			$okbdb_login_pass = explode("=", $_COOKIE['user_pass']);
			
			$user = DB::table('users')
					->where('users.okbdb_user_id', $okbdb_user_id)
					->where('users.login', $okbdb_login_pass[0])
					->where('users.password', $okbdb_login_pass[1])
					->leftJoin('users_roles_groups as roles', 'users.id', '=', 'roles.user_id')
					->select(DB::raw('min(roles.role) as role, users.id'))
					->groupBy('users.id')
					->get();
		
		
			$par = Config::get('parametrs');
			
			if(!$user or !isset($par['role_group_array'][$user[0]->role]))
				return redirect('/error_user');
		}
		else
			return redirect('/error_user');
		
        return $next($request);
    }
}
