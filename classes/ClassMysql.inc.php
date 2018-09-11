<?php
class Mysql
{
	var $config;				//информация о подключении
	
	private $connection;		//ресурс подключения к базе данных
	
	var $debug		= true;		//флаг дебага. Если поднят - при ошибочном SQL запросе будет выведено сообщение об ошибке и скрипт будет остановлен
	
	//лог событий
	var $log 		= false;	//флаг лога редактирования данных. Если поднят, то в таблицу лога будут записаны события вызванные методами insert(), update(), delete()
	var $log_table	= 'log';	//имя таблицы лога
	var $user_id;				//id пользователя
	var $module_id;				//id модуля
	
	//лог SQL
	var $sql_log	= false;	//флаг логирования SQL запросов. Если поднят - все запросы будут записаны в файл
	var $sql_log_file = 'mysql_log.sql'; //имя файла лога запросов.
	
	/*
	конструктор выполняет подключение к базе данных
	$host - хост
	$db - имя базы данных
	$user - имя пользователя
	$pass - пароль
	$pconnect - флаг постоянного соедниения (по умолчанию опущен)
	*/
	function __construct($host='', $db='', $user='', $pass='', $pconnect=false)
	{
		$this->config['host'] = $host;
		$this->config['user'] = $user;
		$this->config['pass'] = $pass;
		$this->config['db'] = $db;
		$this->config['pconnect'] = $pconnect;
		
		if($this->config['pconnect'])
			$functtion = 'mysql_pconnect';
		else
			$functtion = 'mysql_connect';
		
		$this->connection = @$functtion($host, $user, $pass, true)
			or exit('Error of connect to data Base "'.$this->config['db'].'"');
		
		@mysql_select_db($db, $this->connection)
			or exit('Error of select data Base "'.$this->config['db'].'"<br />'.$this->getErrorText());
		
		mysql_set_charset('cp1251', $this->connection);
		// mysql_set_charset('utf8', $this->connection);
		/*
		$this->query("SET NAMES 'utf8'");
		$this->query("SET collation_connection = 'utf8_general_ci'");
		$this->query("SET collation_server = 'utf8_general_ci'");
		$this->query("SET character_set_client = 'utf8'");
		$this->query("SET character_set_connection = 'utf8'");
		$this->query("SET character_set_results = 'utf8'");
		$this->query("SET character_set_server = 'utf8'");
		*/
	}
	
	/*
	закрывает соединение с базой данных если соединение не постоянное
	*/
	function __destruct()
	{
		if(is_resource($this->connection) and !$this->config['pconnect'])
			mysql_close($this->connection);
	}
	
	/*
	метод включения SQL лога
	при включении файл лога очищается
	*/
	function setSqlLog($val=true)
	{
		$this->sql_log = $val;
		
		if($val)
			@file_put_contents($this->sql_log_file, null);
	}
	
	/*
	метод установки флага лога
	$val - значение флага
	$user_id - id пользователя
	$module_id - id модуля
	*/
	function setLog($log=true, $user_id=null, $module_id=null)
	{
		$this->log = $log;
		
		if($user_id)
			$this->user_id = $user_id;
		
		if($module_id)
			$this->module_id = $module_id;
	}
	
	/*
	возвращает номер и текст ошибки SQL
	*/
	function getErrorText()
	{
		return mysql_errno($this->connection).": ".mysql_error($this->connection);
	}
	
	/*
	метод выполняет запрос к базе данных
	Возвращает ресурс результата
	$query - запрос
	Если флаг SQL лога поднят - записать в файл лога запрос
	Если запрос вызвал ошибку и флаг дебага поднят - вывести сообщение об ошибке и остановить скрипт
	*/
	function query($query)
	{
		if($this->sql_log and $this->sql_log_file)
		{
			$file = @fopen($this->sql_log_file, 'a+');
			@fputs($file, $query."\r\n");
			@fclose($file);
		}
		
		$res = mysql_query($query, $this->connection);
		
		if(mysql_errno($this->connection) and $this->debug)
		{
			// exit('Error of SQL<br />query: <code>'.$query.'</code><br />'.$this->getErrorText());
			
			echo 'Error of SQL';
			echo "\n";
			echo '<br /><br />';

			if($query)
			{
				echo '<pre>';
				echo "\n";
				echo $query;
				echo "\n";
				echo'</pre><br />';
				echo "\n";
			}

			echo $this->getErrorText();

			$trace = debug_backtrace();
			$trace = array_slice($trace, 1);

			$call_num = count($trace);

			echo "\n";
			echo '<table cellspacing="20">';
			echo "\n";
			echo '<tr>';
			echo '<th>Call</th>';
			echo '<th>File</th>';
			echo '<th>Line</th>';
			echo '<th>Function</th>';
			echo '</tr>';
			echo "\n";

			foreach($trace as $key=>$call)
			{
				echo '<tr>';
				echo '<td>'.($call_num-$key).'</td>';
				echo '<td>'.@$call['file'].'</td>';
				echo '<td>'.@$call['line'].'</td>';
				echo '<td>'.@$call['class'].@$call['type'].@$call['function'].'()</td>';
				echo '</tr>';
				echo "\n";
			}

			echo '</table>';
			echo "\n";
			exit;
		}
		elseif(mysql_errno($this->connection))
			return false;
		else
			return $res;
	}
	
	/*
	возвращает строку из результата запроса в виде одномерного массива или единственного значнения
	$res - ресурс результата
	$field - имя поля
		Если задано - возвращается единственное значение указанного поля
	*/
	function data($res, $field=null)
	{
		if(!is_resource($res))
			return false;
			
		$data = mysql_fetch_assoc($res);
		
		if(!$field)
			return $data;
		else
			return $data[$field];
	}
	
	/*
	возвращает результат запроса в виде двумерного (одномерного) массива
	$res - ресурс результата
	$key - имя ключа массива строки, которое будет взято за номер строки
		если не задан - номер будет сгенерирован автоматически
	$value - имя ключа массива строки, которое будет взято за значение строки
		если задан - результат будет представлять из себя уже одномерный массив
	*/
	function dataFull($res, $key=null, $value=null)
	{
		if(!is_resource($res))
			return false;
		
		$data = array();
		
		while($row = mysql_fetch_assoc($res))
		{
			if($key)
			{
				if($value)
					$data[$row[$key]] = $row[$value];
				else
					$data[$row[$key]] = $row;
			}
			elseif($value)
				$data[] = $row[$value];
			else
				$data[] = $row;
		}
		
		return $data;
	}
	
	/*
	возвращает число сток в результате запроса
	$res - ресурс результата
	*/
	function getNum($res)
	{
		if(!is_resource($res))
			return false;
		
		return mysql_num_rows($res);
	}
	
	/*
	возвращает сгенерированный ID последней операцией INSERT
	*/
	function getInsertId()
	{
		return mysql_insert_id($this->connection);
	}
	
	/*
	рекурсивный метод обработки значений перед вставкой в SQL запрос
	Удаляет лишние пробелы в начале и конце значения переменной (строки), экранирует спецсимволы
	$val - значение или массив
	$tags - флаг сохранения html тегов (необязательный параметр. по умолчанию поднят)
	*/
	function preData($val, $tags=true)
	{
		if(is_array($val))
	    {
			foreach($val as $key=>$value)
				$val[$key] = $this->preData($value, $tags);
	    }
	    elseif(is_string($val))
	    {
			$val = trim($val);
			
	        if(!$tags)
				$val = strip_tags($val);
			
			$val = mysql_real_escape_string($val);
			
	        $val = "'".$val."'";
	    }
	    elseif(is_bool($val))
			$val = (int)$val;
		elseif($val===null)
			$val = "''";
		
	    return $val;
	}
	
	/*
	метод вставки строки в таблицу базы даннных
	$table - имя таблицы базы, в которую надо всавить строку
	$val - ассоциативный массив значений полей (ключ - имя поля)
	$tags - флаг сохранения html тегов (необязательный параметр. по умолчанию поднят)
	Возвращает сгенерированный идентификатор
	*/
	function insert($table, $val, $tags=true)
	{
		$col_list = array();
		$val_list = array();
		
		foreach($val as $key=>$value)
		{
			$col_list[] = $key;
			$val_list[] = $value;
		}
		
		$col_list = implode($col_list, ', ');
		
		$val_list = $this->preData($val_list, $tags);
		$val_list = implode($val_list, ', ');
		
		$error = false;
		$this->query('insert into '.$table.' ('.$col_list.') values ('.$val_list.')') or $error = true;
		
		if($error)
			return false;
		
		elseif($id = $this->getInsertId())
		{
			if($this->log)
				$this->systemLog($table, $id, 1);
			
			return $id;
		}
		else
			return true;
	}
	
	/*
	метод обновления строки в таблице базы даннных
	Если строка с указанным ключем уже существует - обновляет строку эту строку
	Иначе вставляет новую строку
	$table - имя таблицы базы, в которой надо обновить строку
	$val - ассоциативный массив значений полей (ключ - имя поля)
	$tags - флаг сохранения html тегов (необязательный параметр. по умолчанию поднят)
	Возвращает (сгенерированный) идентификатор
	*/
	function replace($table, $val, $tags=true)
	{
		$col_list = array();
		$val_list = array();
		
		foreach($val as $key=>$value)
		{
			$col_list[] = $key;
			$val_list[] = $value;
		}
		
		$col_list = implode($col_list, ', ');
		
		$val_list = $this->preData($val_list, $tags);
		$val_list = implode($val_list, ', ');
		
		$error = false;
		$this->query('replace into '.$table.' ('.$col_list.') values ('.$val_list.')') or $error = true;
		
		if($error)
			return false;
		
		elseif($id = $this->getInsertId())
		{
			if($this->log)
			{
				if(mysql_affected_rows($this->connection)>1)
					$action = 2; //обновление
				else
					$action = 1; //вставка
				
				$this->systemLog($table, $id, $action);
			}
			
			return $id;
		}
		else
			return true;
	}
	
	/*
	метод изменения строки в таблице базы даннных
	$table - имя таблицы базы
	$val - ассоциативный массив значений полей (ключ - имя поля)
	$where - ассоциативный массив полей идентификаторов(ключ - имя поля)
	$tags - флаг сохранения html тегов (необязательный параметр. по умолчанию поднят)
	*/
	function update($table, $val, $where, $tags=true)
	{
		$col_list = array();
		
		foreach($val as $key=>$value)
			if(is_numeric($key))
				$col_list[] = $value;
			else
				$col_list[] = $key.'='.$this->preData($value, $tags);
		
		$col_list = implode($col_list, ', ');
		
		$where_list = array();
		
		foreach($where as $key=>$value)
		{
			$value = $this->preData($value);
			
			if(is_array($value))
				$val = ' IN ('.implode($value, ', ').')';
			else
				$val = '= '.$value;
			
			$where_list[] = $key.$val;
		}
		
		$where_list = implode($where_list, ' and ');
		
		$error = false;
		$this->query('update '.$table.' set '.$col_list.' where '.$where_list) or $error = true;
		
		if($error)
			return false;
		else
		{
			if($this->log and isset($where['id']))
				$this->systemLog($table, $where['id'], 2);
			
			return true;
		}
	}
	
	/*
	метод удаления строки из таблицы базы даннных
	$table - имя таблицы базы, в которую надо всавить строку
	$where - ассоциативный массив полей идентификаторов(ключ - имя поля)
	*/
	function delete($table, $where)
	{
		$where_list = array();
		
		foreach($where as $key=>$value)
		{
			$value = $this->preData($value);
			
			if(is_array($value))
				$val = ' IN ('.implode($value, ', ').')';
			else
				$val = '= '.$value;
			
			$where_list[] = $key.$val;
		}
		
		$where_list = implode($where_list, ' and ');
		
		$error = false;
		$this->query('delete from '.$table.' where '.$where_list) or $error = true;
		
		if($error)
			return false;
		else
		{
			if($this->log and isset($where['id']))
				$this->systemLog($table, $where['id'], 3);
			
			return true;
		}
	}
	
	/*
	метод осуществляет простую выборку данных из ОДНОЙ строки таблицы
	$table - имя таблицы или список имен таблиц
	$columns - имя поля или список
		Если является массивом - будет возвращен результат в виде одномерного ассоциативного массива со значениями указанных полей
		Иначе если строка - будет возвращено единственное значение указанного поля
		Иначе если ничему не равен - будут возвращен результат в виде одномерного ассоциативного массива со значениями всех полей
	$where - список критериев
		Может являться ассоциативным массивом (ключ - имя поля). В этом случае все критерии будут объеденены через оператор AND
		Может являться строкой
	*/
	function select($table=null, $columns=null, $where=null)
	{
		$sql = 'select';
		
		if(is_array($columns))
		{
			$sql .= ' '.implode($columns, ', ');
		}
		elseif($columns)
			$sql .= ' '.$columns;
		else
			$sql .= ' *';
		
		if($table)
			$sql .= ' from '.$table;
		
		if(is_array($where))
		{
			$where_list = array();
			foreach($where as $key=>$value)
			{
				$value = $this->preData($value);
				
				if(is_array($value))
					$val = ' IN ('.implode($value, ', ').')';
				else
					$val = '= '.$value;
				
				$where_list[] = $key.$val;
			}
			
			$sql .= ' where '.implode($where_list, ' and ');
		}
		elseif($where)
			$sql .= ' where '.$where;
		
		$result = $this->query($sql);
		if(!$result)
			return false;
		
		if(is_array($columns) or !$columns)
			return $this->data($result);
		elseif($columns)
			return $this->data($result, $columns);
	}
	
	/*
	очищает (пересоздает заново) одну или несколько таблиц
	$table - имя таблицы или список имен таблиц через запятую
	*/
	function truncate($table)
	{
		$this->query('truncate table '.$table);
	}
	
	/*
	выполняет SQL запрос
	возвращает результат выполнения запроса
	$sql - SQL запрос
	$format - флаг формата возвращаемого результата (не обязательный параметр, по умолчанию поднят).
		Если поднят - результат возвращается в виде двумерного массива
			$key - имя ключа массива строки, которое будет взято за номер строки
				если не задан - номер будет сгенерирован автоматически
			$value - имя ключа массива строки, которое будет взято за значение строки
				если задан - результат будет представлять из себя уже одномерный массив
		Если опущен - результат возвращается в виде одномерного массива
			$key - имя поля, значение которого нужно вернуть
				возвращается единственное значение заданного поля
	*/
	function execute($sql, $format=true, $key=null, $value=null)
	{
		$res = $this->query($sql);
		if(!$res)
			return false;
		
		if($format)
			$result = $this->dataFull($res, $key, $value);
		else
			$result = $this->data($res, $key);
		
		return $result;
	}
	
	/*
	записывает событие в системный лог (используется толко в администраторском разделе)
	формат лога: Id_пользователя, Имя_таблицы, Id_объекта, Действие (1-insert, 2-update, 3-delete), Дата_и_время
	$table - имя таблицы, задействованной в событии
	$id - идентификатор строки, над которой произошло событие
	$action - ключ события
	*/
	function systemLog($table, $id, $action)
	{
		if($this->log and $this->user_id)
			$this->query('insert into '.$this->log_table." (user_id, module_id, table_name, object_id, action) values (".$this->user_id.", ".$this->module_id.", '$table', $id, $action)");
	}
}
?>