<?php
// NEXT = 22

// Движение по складу
// 
    const  WH_MANUAL_DISTRIBUTE = 1;                // 1 Внесение данных вручную
    const  WH_MANUAL_DISTRIBUTE_FROM_ORDER = 2;     // 2 Внесение данных из СЗ
    const  WH_POPULATE = 3;                         // 3 Распределение на складе
    const  WH_MOVING = 4;                           // 4 Перемещение по складу
    const  WH_REMOVE = 5;                           // 5 Удаление со склада
    const  WH_ISSUE = 6;                            // 6 Выдача со склада
    const  WH_RECEIVED_FROM_SHIFT_ORDER = 7;        // 7 Принято из СЗ
    const  WH_DATA_EDIT = 8;                        // 8 Редактирование количества
    const  WH_OPERATION_EDIT = 9;                   // 9 Редактирование операции
    const  WH_PUT_TO_BASKET = 10;                   // 10 положить в корзину
    const  WH_ISSUE_FROM_BASKET = 11;               // 11 выдача из корзины
    const  WH_BASKET_EMPTY = 12;                    // 12 очистка корзины

    const  PREPARE_GROUP = 1;
    const  EQUIPMENT_GROUP = 2;
    const  COOPERATION_GROUP = 3;
    const  PRODUCTION_GROUP = 4 ;
    const  COMMERTION_GROUP = 5;
    const  TECHNICAL_CONTROL_GROUP = 6;

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
    const  PLAN_FACT_ORDER_COMPLETED = 18;

// листы согласования
    const  NEW_ENTRANCE_CONTROL_PAGE_ADDED = 9;    
    const  ENTRANCE_CONTROL_PAGE_DATA_MODIFIED = 10;    

// листы входного контроля
    const  COORDINATION_PAGE_CREATE = 11;    
    const  COORDINATION_PAGE_DATA_MODIFIED = 12;            

//    Система принятия решений

    const  DECISION_SUPPORT_SYSTEM_THEME_CREATE = 13;
    const  DECISION_SUPPORT_SYSTEM_NEW_MESSAGE = 14;
    const  DECISION_SUPPORT_DECISION_MAKING = 15;
    const  DECISION_SUPPORT_DECISION_CONFIRM_REQUEST = 17;

    $files_path = "63gu88s920hb045e";

    $dblocation = "127.0.0.1";
    $dbname = "okbdb";
    $charset = 'utf8';

    $dbuser = "root";
    $dbpasswd = "jTkiNiD4vT";

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
