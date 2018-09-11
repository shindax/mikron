<?php
return array
(
	/* Группы пользователей для модуля "Инвентаризация" */
	
	'role_group_array' => array(0=>'Администратор', 1=>'Модератор', 2=>'Пользователь'),
	
	
	/* Типы полей в html-таблицах модуля "Инвентаризация".*/
	
	'type_fields' => array('text'=>array('name'=>'Строка', 'input'=>'<input type="text" value="" class="form-control form-control-sm"/>', 'db_table'=>'inventory_fields_type_text'),
							'textarea'=>array('name'=>'Текст', 'input'=>'<textarea class="form-control form-control-sm"></textarea>', 'db_table'=>'inventory_fields_type_textarea'),
							'number'=>array('name'=>'Число', 'input'=>'<input type="number" value="" class="form-control form-control-sm"/>', 'db_table'=>'inventory_fields_type_number'),
							'date'=>array('name'=>'Дата', 'input'=>'<input type="date" class="form-control form-control-sm"/>', 'db_table'=>'inventory_fields_type_date'),
							'file'=>array('name'=>'Файл', 'input'=>'<input type="file" name="file" class="form-control"/>', 'db_table'=>'inventory_fields_type_file'),
							// 'button_add_child'=>array('name'=>'Кнопка "Добавить дочернюю строку"', 'input'=>'<button type="button" class="btn btn-light btn-sm text-secondary add_child_str" title="Добваить дочернюю строку"><span class="oi oi-spreadsheet"></span></button>'),
							'button_delete'=>array('name'=>'Кнопка "Удалить строку"', 'input'=>'<button type="button" class="btn btn-outline-danger btn-sm border-0 mt-1 delete_str" title="Удалить строку"><span class="oi oi-trash"></span></button>'),
							'selection'=>array('name'=>'Выпадающий список', 'header'=>true, 'db_table'=>'inventory_fields_type_select', 'db_table_log'=>'inventory_log_select'),
							'selection_custom'=>array('name'=>'Свой список', 'header'=>true, 'db_table'=>'inventory_fields_type_select', 'db_table_log'=>'inventory_log_select')),
	
	
	/* Выборки предоставляемые системой, относятся к полю типа "Выпадающий список" - $type_fields['selection']
		Важно! Числовые ключи массива являются идентификаторами соответствующей выборки в базе данных!*/
	
	'selection' => array(1=>array('use'=>'use Illuminate\Support\Collection; use App\AllUsers;', 'name'=>'Список сотрудников', 'model'=>'AllUsers', 'fields'=>array('ID as id', 'NAME as name'), 'where'=>array('TID'=>0), 'order'=>'NAME', 'log_table'=>'inventory_log_selection_all_users'),
						2=>array('use'=>'use Illuminate\Support\Collection; use App\OldInventoryPlaces;', 'name'=>'Место нахождения', 'model'=>'OldInventoryPlaces', 'fields'=>array('ID as id', 'NAME as name'))),
	
	'selections_from_old_db' => array('AllUsers'=>'AllUsers', 'OldUsers'=>'OldUsers', 'OldInventoryPlaces'=>'OldInventoryPlaces')
);