<?php

    const  PREPARE_GROUP = 1;
    const  EQUIPMENT_GROUP = 2;
    const  COOPERATION_GROUP = 3;
    const  PRODUCTION_GROUP = 4 ;
    const  COMMERTION_GROUP = 5;
    const  TECHNICAL_CONTROL_GROUP = 6;

// Notofication causes

    const  NEW_ENTRANCE_CONTROL_PAGE_ADDED = 9;    
    const  ENTRANCE_CONTROL_PAGE_DATA_MODIFIED = 10;    


    $files_path = "63gu88s920hb045e";

    $dblocation = "127.0.0.1";   
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
      die("Can't connect: " . $e->getMessage());
    }  
