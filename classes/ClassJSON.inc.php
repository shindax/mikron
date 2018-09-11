<?php
/*
класс для преобразования данных из формата PHP в формат JSON
*/
class JSON
{
	var $mass; //массив, который нужно преобразовать
	
	var $save_keys; //флаг принудительного сохранения всех ключей. Если поднят - все массивы в результате будут ассоциативными
	var $quote_keys; //флаг взятия ассоциативных ключей в кавычки.
	
	/*
	конструктор
	параметры см. выше
	*/
	function __construct($save_keys=false, $quote_keys=true)
	{
		$this->save_keys = $save_keys;
		$this->quote_keys = $quote_keys;
	}
	
	/*
	подготавливает ключ массива
	$key - ключ
	$type - тип массива (0 - нумерованный, 1 - ассоциативный)
	*/
	private function preKey($key, $type)
	{
		if($type)
		{
			if($this->quote_keys)
				$key = '"'.$key.'":';
			else
				$key = $key.':';
		}
		else
			$key = null;
		
		return $key;
	}
	
	/*
	подготавливает значение массива
	$value - значение
	*/
	private function preValue($value)
	{
		if(!is_numeric($value) and is_string($value))
		{
			$value = '"'.addslashes($value).'"';
			$value = str_replace(array("\n", "\r", "\'"), array('\n', '\r', "'"), $value);
		}
		elseif($value===null)
			$value = 'null';
		
		return $value;
	}
	
	/*
	преобразует массив в формат JSON
	рекурсивный метод
	возвращает преобразованный массив в виде строки
	$mass - массив, который нужно преобразовать
		не обязательный параметр
		если не задан, то будет взят параметр, переданный конструктору
	*/
	function encode($mass=null)
	{
		if($mass===null)
			$mass = $this->mass;
		
		if(!is_array($mass))
			return $this->preValue($mass);
		elseif(empty($mass))
			return '[]';
		
		$type = 0;
		$json = array();
		
		if($this->save_keys)
			$type = 1;
		else
			foreach($mass as $key=>$value)
				if(is_string($key))
				{
					$type = 1;
					break;
				}
		
		foreach($mass as $key=>$value)
		{
			if(is_array($value))
				$json[] = $this->preKey($key, $type).$this->encode($value);
			else
				$json[] = $this->preKey($key, $type).$this->preValue($value);
		}
		
		$json = implode(',', $json);
		
		if($type)
			$json = '{'.$json.'}';
		else
			$json = '['.$json.']';
		
		return $json;
	}
	
	/*
	Преобразует JSON в массив
	*/
	function decode($json)
	{
		return json_decode($json);
	}
}
?>