<?php
/*
функция переадресации
$url - путь переадресации
	Еесли оставить пустым - переадресация на текущую страницу
	Если 0 или false - переадресация на предыдушюю страницу
*/
function _Redirect($url=null)
{
	if($url===null)
		$url = $_SERVER['REQUEST_URI'];
	elseif(!$url)
	{
		if(isset($_SERVER['HTTP_REFERER']))
			$url = $_SERVER['HTTP_REFERER'];
		else
			$url = '/';
	}
	
	header('location: '.$url);
	
	exit;
}

/*
функция преобразует дату между пользотвательским форматом вида 01.02.2008 и форматом MySQL date вида 2008-02-01
$date - строка даты
*/
function dateConvert($date)
{
	if(strstr($date, '.'))
	{
		$date = explode('.', $date);
		$date = array_reverse($date);
		$date = implode($date, '-');
	}
	elseif(strstr($date, '-'))
	{
		$date = explode('-', $date);
		$date = array_reverse($date);
		$date = implode($date, '.');
	}
	
	return $date;
}

/*
преобразует дату формата вида - 01.01.2008 или 2008-01-01 в формат вида - 1 января 2008
Используется в шаблонах Smarty
	пример:
	{insert name=dateFormat date=$переменная_с_датой}
*/
function insert_dateFormat($val)
{
	if(isset($val['date']))
		$date = $val['date'];
	else
		return $val;
	
	$moon_list = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
	
	if(strstr($date, '.'))
		$date = explode('.', $date);
	elseif(strstr($date, '-'))
	{
		$date = explode('-', $date);
		$date = array_reverse($date);
	}
	
	$date[0] *= 1;
	$date[1] = $moon_list[$date[1]-1];
	$date = implode($date, ' ');
	
	return $date;
}

/*
функция отправляет e-mail сообщение
исходящий адрес берется из константы MAIL_FROM
$mess - текст сообщения
$mail_to - адрес для отправки (если нет - берется из константы MAIL_TO)
$theme - тема письма (если нет - берется из константы MAIL_THEME)
$type - тип тела письма (по умолчанию - простой текст)
$charset - кодировка письма (по умолчанию KOI8-R)
*/
function SendEmail($mess, $mail_to=null, $theme=null, $type='text/plain', $charset='KOI8-R')
{	
	if(!$mail_to)
		$mail_to = MAIL_TO;
	
	if(!$theme)
		$theme = MAIL_THEME;
	
	$head = 'From: '.MAIL_FROM."\n";
	$head .= 'Content-Type: '.$type.'; charset="'.$charset.'"'."\n";
	
	$mess = @iconv("UTF-8", $charset."//IGNORE", $mess);
	$theme = @iconv("UTF-8", $charset."//IGNORE", $theme);
	
	return @mail($mail_to, $theme, $mess, $head);
}

/*
объявляет переменую шаблона
$name - имя
$value - значение
*/
function Assign($name, $value)
{
	STemplate::assign($name, $value);
}

/*
объявляет константу и переменную шаблона
$name - имя
$value - значение
*/
function DefineAssign($name, $value)
{
	if(!defined($name))
		define($name, $value);
	
	Assign($name, $value);
}

/*
обрезает строку до указанного числа слов
из строки удаляются html тэги
$str - строка
$z - максимальное число слов
*/
function WordLimiter($str, $z)
{
	$str = strip_tags($str);
	$str_list = explode(' ', $str);
	
	$z_str = count($str_list);
	
	if($z_str<=$z)
		return $str;
	
	$str_limit = array();
	for($i=0; $i<$z; $i++)
		$str_limit[] = $str_list[$i];
	
	return implode(' ', $str_limit).'...';
}

/*
возвращает текующую дату_время в формате MySQL
$format - флаг формата
	если задан - возвращается формат MySQL date
	иначе - MySQL datetime
*/
function Now($format=false)
{
	if(!$format)
		return date("Y-m-d H:i:s");
	else
		return date("Y-m-d");
}

/*
загружает класс
$name - имя класса
*/
function LoadClass($name)
{
	if(class_exists($name))
		return;
	
	$file = 'core/class/Class'.$name.'.inc.php';
	
	if(file_exists($file))
		include($file);
}

spl_autoload_register('LoadClass'); //регистрируем автозагрузичк вместо __autoload()

/*
загружает набор функций
$name - имя класса
*/
function LoadFuncs($name)
{
	$file = 'core/funcs/'.$name.'.php';
	
	if(file_exists($file))
		include_once($file);
}

/*
отладочная функция
выводит на экран дамп переменной аналогично функциям var_dump() и print_r()
сохраняет предварительное форматирование
преобразует HTML спецсимволы в их сущности
$format - флаг формата
	Если поднят - используется var_dump()
	Иначе - print_r()
*/
function Dump($var, $format=0)
{
	ob_start();
	
	if(!$format)
		print_r($var);
	else
		var_dump($var);
	
	$text = ob_get_contents();
	
	ob_end_clean();
	
	$text = htmlspecialchars($text, ENT_NOQUOTES);
	
	echo '<pre>'.iconv('utf-8', 'cp1251', $text).'</pre>';
}

/*
Функция безопасного обращения к элементам массива.
Возвращает значение элемента из массива произвольной размерности или NULL, если элемент не найтен
Если функцию вызвать по ссылке - будет возвращена ссылка на элемент массива
$mass - массив значений (принимается по ссылке)
$keys - массив ключей или один ключ
*/
function &getElement(&$mass, $keys=null)
{
	$default = null;
	
	if(is_array($keys))
	{
		foreach($keys as $key)
		{
			if(isset($mass[$key]))
				$mass = &$mass[$key];
			else
				return $default;
		}
		
		return $mass;
	}
	elseif($keys!==null)
	{
		if(isset($mass[$keys]))
			return $mass[$keys];
		else
			return $default;
	}
	else
		return $mass;
}

/*
возвращает значение элемента из глобального массива $_POST
Функция с произвольным числом параметров (ключи массива)
	если ни один задан - возвращается число элементов массива
*/
function POST()
{
	$keys = func_get_args();
	
	if($keys)
		return getElement($_POST, $keys);
	else
		return count($_POST);
}

/*
возвращает значение элемента из глобального массива $_GET
Функция с произвольным числом параметров (ключи массива)
	если ни один задан - возвращается число элементов массива
*/
function GET()
{
	$keys = func_get_args();
	
	if($keys)
		return getElement($_GET, $keys);
	else
		return count($_GET);
}

/*
возвращает значение элемента из глобального массива $_SERVER
Функция с произвольным числом параметров (ключи массива)
	если ни один задан - возвращается число элементов массива
*/
function SERV()
{
	$keys = func_get_args();
	
	if($keys)
		return getElement($_SERVER, $keys);
	else
		return count($_SERVER);
}

/*
возвращает значение элемента из глобального массива $_SESSION
Функция с произвольным числом параметров (ключи массива)
	если ни один задан - возвращается число элементов массива
*/
function SESS()
{
	$keys = func_get_args();
	
	if($keys)
		return getElement($_SESSION, $keys);
	else
		return count($_SESSION);
}

/*
возвращает значение элемента из глобального массива $_COOKIE
Функция с произвольным числом параметров (ключи массива)
	если ни один задан - возвращается число элементов массива
*/
function COOK()
{
	$keys = func_get_args();
	
	if($keys)
		return getElement($_COOKIE, $keys);
	else
		return count($_COOKIE);
}

/*
возвращает случайную строку заданной длинны
$length - длинна строки
*/
function randStr($length=8)
{
	$simbols = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	
	$limit = strlen($simbols) - 1;
	
	$str = '';
	
	for($i=0; $i<$length; $i++)
		$str .= substr($simbols, rand(0, $limit), 1);
	
	return $str;
}

/*
проверяет является ли текущий запрос к серверу AJAX-запросом
*/
function isAjax()
{
	if(SERV('HTTP_X_REQUESTED_WITH')=='XMLHttpRequest')
		return true;
	else
		return false;
}

/*
собирает адрес для автоматического реврайта вида index~id-1.html
$params - массив параметров (ключ=>значение)
*/
function Url($params=array())
{
	$mode = getElement($params, 'mode');
	
	$url = array();
	
	if($mode)
	{
		$url[] = $mode;
		unset($params['mode']);
	}
	elseif(!$params)
		$url[] = 'index';
	
	foreach($params as $key=>$value)
		$url[] = $key.KEY_VALUE_SEPARATOR.$value;
	
	return implode(VAR_SEPARATOR, $url).UNIT_EXT;
}

/*
проверяет значение
Если негативное - переадресация на другой URL
*/
function valid($var, $url=null)
{
	if($var)
		return;
	
	header ("HTTP/1.1 404 Not Found");
	print '404 Not Found';
	// STemplate::display(ERROR_404);
	
	exit;
	
	/* if($url===true)
	{
		header ("HTTP/1.1 404 Not Found");
		print '404 Not Found';
		// STemplate::display(ERROR_404);
		
		exit;
	}
	elseif(!$url)
		$url = $this->NODE['url'];
	
	Redirect($url); */
}
?>