<?php 
    $dblocation = "127.0.0.1";   
    $dbname = "okbdb"; 
    $charset = 'cp1251';
    $dbuser = "root"; 
    $dbpasswd = ""; 

    $dsn = "mysql:host=$dblocation;dbname=$dbname;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

  try{
        $pdo = new PDO($dsn,$dbuser, $dbpasswd, $opt);
     }
  catch (PDOException $e) 
    {
      die("Can't connect: " . $e->getMessage());
    }  
