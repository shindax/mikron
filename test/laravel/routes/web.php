<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
	use Illuminate\Http\Request;
	use App\Task;
	
Route::get('/budgeting', 'BudgetingController@index')->middleware('check_user_db_budget');

Route::get('/', 'UserController@index')->middleware('check_user_db');

Route::post('/inventory/set_string', 'UserController@set_string')->middleware('check_user_db');

Route::post('/inventory/set_file', 'UserController@set_file')->middleware('check_user_db');

Route::get('/error_user', function ()
{
	return view('common.error_user');
});

Route::get('/attach/{id}', 'AttachUserController@index');

Route::post('/inventory/add_edit_cat', 'UserController@add_edit_cat');

Route::post('/inventory/get_childs_catalog', 'UserController@get_childs_catalog');

Route::post('/inventory/get_structure', 'UserController@get_structure');

Route::post('/inventory/add_string', 'UserController@add_string');

Route::post('/inventory/set_structure', 'UserController@set_structure');

Route::post('/inventory/set_selection_custom', 'UserController@set_selection_custom');

Route::post('/inventory/set_catalog', 'UserController@set_catalog');

Route::post('/inventory/set_user_role', 'UserController@set_user_role');

Route::post('/inventory/get_user_role', 'UserController@get_user_role');

Route::post('/inventory/get_log', 'UserController@get_log');

Route::post('/inventory/set_structure_size', 'UserController@set_structure_size');

Route::get('/inventory/table_list/{id}', 'UserController@get_table_list');

Route::post('/inventory/table_list/get_list', 'UserController@get_list');

Route::post('/inventory/search', 'UserController@search');

Route::post('/inventory/sortable', 'UserController@sortable');

// Route::post('/inventory/add_edit_cat', function ()
// {
	// return response()->json(['response' => 'This is get method']);
	// return view('common.error_user');
// });

/* Route::get('/task', function ()
{
	$tasks = Task::orderBy('created_at', 'asc')->get();

	return view('tasks', [
	'tasks' => $tasks
	]);
});

Route::post('/task', function (Request $request)
{
	$validator = Validator::make($request->all(), [
	'name' => 'required|max:255',
	]);

	if ($validator->fails())
	{
		return redirect('/task')
		->withInput()
		->withErrors($validator);
	}

	$task = new Task;
	$task->name = $request->name;
	$task->save();

	return redirect('/task');
});

Route::delete('/task/{task}', function (Task $task)
{
	$task->delete();

	return redirect('/task');
}); */

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
