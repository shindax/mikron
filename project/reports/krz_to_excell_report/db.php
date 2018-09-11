<?php

    $dblocation = "localhost";   
    $dbname = "okbdb"; 
    $charset = 'utf8';
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
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't connect : " . $e->getMessage());
    }  
