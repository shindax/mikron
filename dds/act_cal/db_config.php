<?php
error_reporting( E_ALL );
error_reporting( 0 );

switch( gethostname() )
{  
case 'Iktorn' : // Мой сервер
    // Адрес сервера MySQL 
  $dblocation = "localhost"; 
  // Имя базы данных на хостинге или локальной машине 
  $dbname = "okbdb"; 
  // Имя пользователя базы данных 
  $dbuser = "root"; 
  // и его пароль 
  $dbpasswd = ""; 
 
  // Устанавливаем соединение с сервером MySQL 
  $mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
        exit("Ошибка установки соединения.$mysqli->error"); 
 
  // Устанавливаем кодировку соединения. Следует выбрать ту кодировку, 
  // в которой данные будут отправляться MySQL-серверу 
  $mysqli->query("SET NAMES 'cp1251'"); 
  break ;

case 'Programm-001' : 
  // Адрес сервера MySQL 
  $dblocation = "localhost"; 
  // Имя базы данных на хостинге или локальной машине 
  $dbname = "okbnew"; 
  // Имя пользователя базы данных 
  $dbuser = "root"; 
  // и его пароль 
  $dbpasswd = "150182"; 
 
  // Устанавливаем соединение с сервером MySQL 
  $mysqli = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname); 
  
  if ( mysqli_connect_errno() ) 
        exit("Ошибка установки соединения.$mysqli->error"); 
 
  // Устанавливаем кодировку соединения. Следует выбрать ту кодировку, 
  // в которой данные будут отправляться MySQL-серверу 
  $mysqli->query("SET NAMES 'cp1251'"); 
  
  break ;
}
?>
