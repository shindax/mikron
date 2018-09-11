<?php
/*
класс для работы с сессией
все методы являются статичными
объект создавать не нужно
*/
class SESS
{
	/*
	возвращает/устанавливает директорию для хранения сессионных файлов
	$dir - адрес директории (не обязательно)
		если задано и директория существует - установить ее для хранения
		иначе возвращает текущее значение
	*/
	static function dir($dir=null)
	{
		if($dir and file_exists($dir) and is_dir($dir))
			return session_save_path($dir);
		else
			return session_save_path();
	}
	
	/*
	возвращает/устанавливает имя сессии
	$name - имя (не обязательно)
		если задано - установить
		иначе возвращает текущее значение
	*/
	static function name($name=null)
	{
		if($name)
			return session_name($name);
		else
			return session_name();
	}
	
	/*
	возвращает/устанавливает идентификатор сессии
	$id - идентификатор (не обязательно)
		если задано - установить
		иначе возвращает текущее значение
	*/
	static function id($id=null)
	{
		if($id)
			return session_id($id);
		else
			return session_id();
	}
	
	/*
	открывает сессию
	повторыный вызов не открывает сессию повторно
	все методы, изменяющие содержимое сессии вызывают этот метод
	поэтому напрямую использовать его имеет смысл только если вам нужно открыть сессию, не изменяя ее содержимое
	*/
	static function open()
	{
		if(!self::id())
			session_start();
	}
	
	/*
	устанавливает значение элемента в сессию
	если элемента (ов) не существует он (они) будут созданы
	$keys - ключ или массив ключей сессии
		если один ключ - установить значение элемента с заданным ключем в корне сессии
		если массив ключей - каждый следующий элемент с заданным ключем будет установлен внутри предыдущего элемента
	$value - значение
		если массив ключей - будет установлено в самый глубокий элемент
	*/
	static function set($keys, $value)
	{
		self::open();
		
		if(is_array($keys))
		{
			$mass = &$_SESSION;
			
			$z = count($keys)-1;
			
			for($i=0; $i<=$z; $i++)
			{
				if(!isset($mass[$keys[$i]]) or !is_array($mass[$keys[$i]]))
					$mass[$keys[$i]] = array();
				
				if($i==$z)
					$mass[$keys[$i]] = $value;
				else
					$mass = &$mass[$keys[$i]];
			}
		}
		else
			$_SESSION[$keys] = $value;
	}
	
	/*
	удаляет элемент из сессии
	$keys - ключ или массив ключей сессии
		если один ключ - удалить элемент из корня сессии
		если массив ключей - производится поиск каждого следующего элемента с заданным ключем внутри предыдущего
		будет удален самый глубокий элемент
	*/
	static function uset($keys)
	{
		self::open();
		
		if(is_array($keys))
		{
			$mass = &$_SESSION;
			
			$z = count($keys)-1;
			
			for($i=0; $i<=$z; $i++)
			{
				if($i==$z)
					unset($mass[$keys[$i]]);
				else
					$mass = &$mass[$keys[$i]];
			}
		}
		elseif(isset($_SESSION[$keys]))
			unset($_SESSION[$keys]);
	}
	
	/*
	возвращает значение элемента из сессии
	если элемент не найден - возвращает NULL
	если метод вызвать по ссылке - будет возвращена ссылка на элемент сессии
	$keys - ключ или массив ключей сессии
		если один ключ - возвратить значение элемента с заданным ключем из корня сессии
		если массив ключей - производится поиск каждого следующего элемента с заданным ключем внутри предыдущего
	*/
	static function &get($keys)
	{
		self::open();
		
		$default = null;
		
		if(is_array($keys))
		{
			$mass = &$_SESSION;
			
			foreach($keys as $key)
			{
				if(isset($mass[$key]))
					$mass = &$mass[$key];
				else
					return $default;
			}
			
			return $mass;
		}
		elseif(isset($_SESSION[$keys]))
			return $_SESSION[$keys];
		else
			return $default;
	}
	
	/*
	проверяет наличе элемента (ов) в сессии
	метод с переменным числом параметров
	каждый параметр передается методу get() (см. справку по методу get())
	если хотябы одного элемента нет - возвращает FALSE
	иначе возвращает TRUE
	*/
	static function check()
	{
		$args = func_get_args();
		
		$check = true;
		
		foreach($args as $key)
			if(self::get($key)===null)
				$check = false;
		
		return $check;
	}
	
	/*
	удаляет элемент (ы) из сессии
	метод с переменным числом параметров
	каждый параметр передается методу uset() (см. справку по методу uset())
	*/
	static function reset()
	{
		$args = func_get_args();
		
		foreach($args as $key)
			self::uset($key);
	}
	
	/*
	очищает сессию
	*/
	static function clean()
	{
		self::open();
		
		$_SESSION = array();
	}
	
	/*
	уничтожает сессию
	*/
	static function close()
	{	
		session_destroy();
	}
}
?>