<?php

    const  PREPARE_GROUP = 1;
    const  EQUIPMENT_GROUP = 2;
    const  COOPERATION_GROUP = 3;
    const  PRODUCTION_GROUP = 4 ;
    const  COMMERTION_GROUP = 5;
    const  TECHNICAL_CONTROL_GROUP = 6;

// LAST = 16

// Notofication causes
// План-факт
    const  PLAN_FACT_STATE_CHANGE = 2;
    const  PLAN_FACT_1_DAY_BEFORE_STATE_END = 3;
    const  PLAN_FACT_DATE_CHANGE = 4;
    const  PLAN_FACT_DATE_EXPIRE = 5;
    const  PLAN_FACT_STATE_END_DATE = 6;    
    const  PLAN_FACT_10_DAY_BEFORE_STATE_END = 7;
    const  PLAN_FACT_5_DAY_BEFORE_STATE_END = 8;
    const  PLAN_FACT_CONFIRMATION_REQUEST = 16;

// листы согласования
    const  NEW_ENTRANCE_CONTROL_PAGE_ADDED = 9;    
    const  ENTRANCE_CONTROL_PAGE_DATA_MODIFIED = 10;    

// листы входного контроля
    const  COORDINATION_PAGE_CREATE = 11;    
    const  COORDINATION_PAGE_DATA_MODIFIED = 12;            

//    Изменение данных в листе согласования

    const  DECISION_SUPPORT_SYSTEM_THEME_CREATE = 13;
    const  DECISION_SUPPORT_SYSTEM_NEW_MESSAGE = 14;
    const  DECISION_SUPPORT_DECISION_MAKING = 15;

    $files_path = "63gu88s920hb045e";

    $dblocation = "127.0.0.1";
    $dbname = "okbdb";
    $charset = 'utf8';
    $dbuser = "okbmikron";
    $dbpasswd = "fm2TU9IMTB_hnI0Z";

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
