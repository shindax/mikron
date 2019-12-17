<?php
// /var/www/okbmikron/www/project/sklad

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
  $result = iconv("UTF-8", "Windows-1251", $str );
  return $result;
}

function debug( $arr , $conv = false )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function get_warehouse_structure()
{
    global $pdo;

    $wh = [];
    $cells = [];
    $tiers = [];

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades AS wh
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $wh[ $row -> ID ] = conv( $row -> NAME );

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades_item AS cells
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $cells[ $row -> ID ] = conv( $row -> NAME );

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades_yaruses AS tiers
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $tiers[ $row -> ID ] = $row -> ORD;

        return [ "wh" => $wh, "cells" => $cells, "tiers" => $tiers ];
} // function get_warehouse_structure()

function GetUserInfo( $user_id  )
{
    global $pdo;

    try
    {
        $query = "   SELECT 
                 users.FIO AS user_name, 
                 resurs.GENDER AS gender
                 FROM okb_users AS users
                 LEFT JOIN okb_db_resurs AS resurs ON resurs.ID_users = users.ID
                 WHERE users.ID = $user_id";

        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }

    $row = $stmt->fetch( PDO::FETCH_OBJ );
    return [ 'name' => $row -> user_name, 'gender' => $row -> gender ];

} // function GetUserInfo( $user_id  )

function FixActionInHistory( $action_id, $user_id, $id_zakdet, $dse_name, $count, $message, $from_tier = 0 , $to_tier = 0 )
{
  global $pdo;
  
  try
  {
      $query = "INSERT INTO okb_db_warehouse_action_history 
        ( action_type_id, user_id, from_tier, to_tier, id_zakdet, dse_name, count, comment )
        VALUES ( $action_id, $user_id, $from_tier, $to_tier, $id_zakdet, '$dse_name', $count, 
        '$message' )
        ";

      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }
} // function FixActionInHistory( $action_id, $user_id, $id_zakdet, $dse_name, $count, $message, $from_tier = 0 , $to_tier = 0 )

function GetDSEName( $tier_id )
{
  global $pdo;

  try
  {
    $query = $query = " 
          SELECT inv.dse_name AS dse_name
          FROM okb_db_sklades_detitem AS detitem
          LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id = detitem.ref_id
          WHERE detitem.ID = $tier_id";

      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }

  $row = $stmt->fetch( PDO::FETCH_OBJ );
    return $row -> dse_name;
} // function GetDSEName( $tier_id )

function WarehouseUpdateAndDelete( $id , $iss_count, $user_id )
{
  global $pdo;

  try
  {
      $query = "  SELECT 
              detitem.COUNT AS count, 
              detitem.ID_sklades_yarus AS tier,
              inv.id_zakdet AS id_zakdet
            FROM `okb_db_sklades_detitem` AS detitem
            LEFT JOIN okb_db_semifinished_store_invoices AS inv ON inv.id = detitem.ref_id
            WHERE detitem.id = $id";

      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
  }

  $row = $stmt->fetch( PDO::FETCH_OBJ );
  $count = $row -> count - $iss_count ;
  $tier_id = $row -> tier;
  $id_zakdet = $row -> id_zakdet;

  $user_info = GetUserInfo( $user_id  );
  $user_name = $user_info['name'];
  $gender = $user_info['gender'];

  $dse_name = GetDSEName( $id );

    if( $gender == 1 || $gender == 0 )
        $action = "выдал ДСЕ :";
            else
                $action = "выдала ДСЕ :";

  $message = "$user_name $action $dse_name. в количестве $iss_count шт.";

  FixActionInHistory( WH_ISSUE, $user_id, $id_zakdet, $dse_name, $iss_count, $message, $tier_id, $count );

  if( $count )
  {
    if( $count < 0 )
      throw new Exception('Underflow assigning');

    try
    {
        $query = "  UPDATE okb_db_sklades_detitem
                    SET 
                    COUNT=$count
                    WHERE id = $id" ;
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

  }
  else
  {
    try
    {
        $query = "  DELETE FROM `okb_db_sklades_detitem` 
              WHERE id = $id";
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }
  }
} // function WarehouseUpdateAndDelete( $id , $iss_count, $user_id )

function findAtWarehouse( $id_zakdet, $operation_id, $pattern = '', $group = true )
{
  global $pdo;  
  $data = [];
  
  if( $group )
    $query ="
        SELECT 
          inv.id AS inv_id, 
          DATE_FORMAT( inv.timestamp, '%d.%m.%Y %H:%i:%s' ) AS date_time, 

           inv.accepted_by_QCD AS accepted_by_QCD, 

          inv.operation_id AS operation_id,
        
          zak.NAME AS zak_name,
          zak_type.description AS zak_type_description,
          oper.NAME AS oper_name,
          inv.note AS inv_note,
          inv.draw_name AS pattern,
        
          zakdet.ID AS id_zakdet,
          
          ( detitem.COUNT ) AS wh_count,
          
          detitem.NAME AS detitem_dse_name

        FROM okb_db_semifinished_store_invoices inv
        LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = inv.id_zakdet
        LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.id_zak
        LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID
        LEFT JOIN okb_db_oper AS oper ON oper.ID = inv.operation_id
        LEFT JOIN okb_db_sklades_detitem AS detitem ON detitem.ref_id = inv.id
        WHERE inv.id IN ( SELECT  ref_id FROM okb_db_sklades_detitem WHERE NAME LIKE '%$pattern%')
        #GROUP BY detitem.NAME
        " ;
        
        else
          $query ="
        SELECT 
          inv.id AS inv_id, 
          DATE_FORMAT( inv.timestamp, '%d.%m.%Y %H:%i:%s' ) AS date_time, 

          inv.accepted_by_QCD AS accepted_by_QCD, 

          inv.operation_id AS operation_id,
        
          zak.NAME AS zak_name,
          zak_type.description AS zak_type_description,
          oper.NAME AS oper_name,
          inv.note AS inv_note,
          inv.draw_name AS pattern,
        
          zakdet.ID AS id_zakdet,
          
          SUM( detitem.COUNT ) AS wh_count,
          
          detitem.NAME AS detitem_dse_name

        FROM okb_db_semifinished_store_invoices inv
        LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = inv.id_zakdet
        LEFT JOIN okb_db_zak AS zak ON zak.ID = zakdet.id_zak
        LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID
        LEFT JOIN okb_db_oper AS oper ON oper.ID = inv.operation_id
        LEFT JOIN okb_db_sklades_detitem AS detitem ON detitem.ref_id = inv.id
        WHERE detitem.NAME LIKE '%$pattern%'
        GROUP BY zak_name        
        " ;
    try
  {
      // echo conv( $query );

      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
  }

while( $row = $stmt->fetch( PDO::FETCH_ASSOC ) )
  $data[] = $row; 

  return $data ;
} // function findAtWarehouse( $id_zakdet, $operation_id, $pattern = '')

function getReserveCount( $id_zakdet = 0 , $operation_id = 0 , $id_zadan = 0, $pattern = "")
{
  global $pdo ;
  $count = 0 ;

    if( strlen( $pattern ))
      {
        try
        {
            $query ="SELECT SUM( count ) AS count
                FROM `okb_db_warehouse_reserve` 
                WHERE pattern LIKE '%$pattern%'" ;

            // echo conv( $query );
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();

          if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $count = $row -> count ;

        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
        }
    }

  return $count ;

} // function getReserveCount( $id_zakdet, $operation_id, $id_zadan = 0 )

function getLocationOfDSEAtWarehouse( $id_zakdet, $operation_id = 0, $id = 0, $pattern = '' )
{
    global $pdo;
    $arr = [];

     $query =
        "SELECT
          inv.id AS inv_id, 
          inv.storage_place AS storage_place,
          inv.id_zakdet AS id_zakdet
        FROM okb_db_sklades_detitem AS detitem
        JOIN okb_db_semifinished_store_invoices AS inv ON inv.id=detitem.ref_id 
        WHERE detitem.NAME LIKE '%$pattern%' " ; 

    try
    {
        // echo conv( $query );
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
    }

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ))
    {
        $inv_id = $row -> inv_id;
        $arr[ $inv_id ] = 
        [
            'id_zakdet' => $row -> id_zakdet,
            'storage_place' =>  json_decode( $row -> storage_place, true ),
        ];

    }

    return $arr ;

} // function getLocationOfDSEAtWarehouse( $id_zakdet, $operation_id, $id = 0 )


function DSE_merge( $arr )
{
  $tmp_arr = [];
  foreach( $arr AS $key => $val )
  {
    $tmp_key = $val['wh_dse_name'].$val['operation_id'];
    if( isset( $tmp_arr[ $tmp_key ] ) )
        $tmp_arr[ $tmp_key ]['count'] += $val['count'];
          else
            $tmp_arr[ $tmp_key ] = $val ;
  }
 
  ksort( $tmp_arr );
  return $tmp_arr;
}

function GetOperationName( $id )
{
    global $pdo;

    try
    {
        $query = "SELECT NAME FROM okb_db_oper
                  WHERE ID = $id";

        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }

    $row = $stmt->fetch( PDO::FETCH_OBJ );
    return $row -> NAME;
} 

function GetIssuesCount( $id )
{
    global $pdo;

    try
    {
        $query = "
                    SELECT MAX(`transaction`) AS count 
                    FROM okb_db_semifinished_store_issued_invoices 
                    WHERE batch = $id";

        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }

    $row = $stmt->fetch( PDO::FETCH_OBJ );
    return isset( $row -> count ) ? $row -> count : 0 ;
}

function GetBatchArr( $batch_id )
{
  global $pdo;
  $arr = [];

  try
{
  $query =
  "SELECT 
  res.count AS count , 
  res.id AS id, 
  res.batch AS batch
  FROM `okb_db_warehouse_reserve` AS res 
  WHERE res.batch = $batch_id";

  // echo $query;

  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
  $id = $row -> id;
  $batch = $row -> batch;
  $issued = get_history( $id );

  $arr[] = 
  [
    'batch' => $batch,
    'issued' => $issued,
    'count' => $row -> count,
  ];
 }
  
 return $arr;
}

function GetState( $batch_id )
{
  $count = 0 ;
  $issued = 0 ;
  $state = 0 ;
  $arr = GetBatchArr( $batch_id );

  foreach( $arr AS $key => $value )
    {
      $batch = $value['batch'];
      $count += $value['count'];
      $issued += $value['issued'];
    }

  if( $count == 0 )
    $state = 2 ;

  if( $count && $issued )
    $state = 1 ;
 
  return $state ;
}

function get_history( $id )
{
  global $pdo ;
  try
  {
    $query ="
    SELECT issued_from
    FROM okb_db_semifinished_store_issued_invoices
    WHERE
    issued_from_res_id = $id
    ";

    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query was : $query");
  }

  $count = 0;
  while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
  {
    $iss_arr = json_decode( $row -> issued_from, true );
    foreach( $iss_arr AS $value )
      $count += $value['count'];
  }
  
  return $count;
}
