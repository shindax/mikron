<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

function GetResInfo( $user_id )
{
	global $user, $pdo;

    try
    {
       $query ="SELECT ID, NAME FROM `okb_db_resurs` WHERE ID_users = $user_id";
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $res = [] ;
    
    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $res = [ 'id' => $row -> ID, 'res_name' => conv( $row -> NAME ) ];

    return $res ;
 
 } // function GetResInfo( $user_id )


function GetResName( $res_id )
{
  global $user, $pdo;

    try
    {
       $query ="SELECT NAME FROM `okb_db_resurs` WHERE ID = $res_id";
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $name = "";
    
    if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $name = ( $row -> NAME );

    return $name ;
 
 } // function GetResName( $res_id )

function CmpByCount( $a, $b )
{
    if ( $a['count'] == $b['count'] ) 
        return 0;

    return $a['count'] > $b['count'] ? -1 : 1;
}

function GetData( $res_id )
{
  global $pdo;
  $data = [];

  try
  {
      $query = "
          SELECT 
          precedents.id,
          precedents.zadan_id,
          precedents.cause,
          DATE_FORMAT(precedents.date,'%d.%m.%Y') AS shut_date,

          zakdet.NAME AS dse_name,
          zakdet.OBOZ AS dse_draw,
          zakdet.ID AS dse_id,

          zadan.NORM AS norm_plan,
          zadan.NORM_FACT AS norm_fact,

          zadan.id_zak,
          zadan.SMEN AS shift,
          CONCAT(RIGHT( zadan.DATE, 2), '.', SUBSTRING( zadan.DATE, -4, 2), '.', LEFT( zadan.DATE, 4)) AS zadan_date,
          zadan.DATE AS bin_date,

          CONCAT(zak_type.description, ' ', zak.NAME) AS zak_name,
          zak.DSE_NAME AS zak_dse_name,

          oper.NAME AS operation,
          park.NAME AS unit_name,
          park.MARK AS unit_type,
          resurs1.NAME  AS res_name,
          users.FIO  AS shutter_name,
          causes.description AS cause

          FROM `noncomplete_execution_precedents` AS precedents
          INNER JOIN okb_db_zadan AS zadan ON zadan.ID = precedents.zadan_id
          LEFT JOIN okb_db_zak AS zak ON zak.ID = zadan.id_zak
          LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.id = zak.TID
          LEFT JOIN okb_db_operitems AS operitems ON operitems.id = zadan.id_operitems

          LEFT JOIN okb_db_oper AS oper ON oper.id = operitems.ID_oper
          LEFT JOIN okb_db_park AS park ON park.id = operitems.ID_park

          LEFT JOIN okb_db_resurs AS resurs1 ON resurs1.id = zadan.ID_resurs
          LEFT JOIN okb_users AS users ON users.id = precedents.shutter_user_id

          LEFT JOIN noncomplete_execution_causes AS causes ON causes.id = precedents.cause
          LEFT JOIN okb_db_zakdet AS zakdet ON zakdet.id = zadan.ID_zakdet

          WHERE 
          cause IN 
          (
            SELECT nec.id
            FROM noncomplete_execution_causes nec
            LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(nec.responsible_res_id, CAST( res.id AS JSON ), '$')
            WHERE res.ID = $res_id
          )
          ORDER BY zadan.DATE, zadan.SMEN
          ";

      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

  }

  while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
  {
    $data[ $row -> id_zak ]['ord_id'] = $row -> id_zak;
    $data[ $row -> id_zak ]['ord_name'] = conv( $row -> zak_name );    
    $data[ $row -> id_zak ]['ord_dse_name'] = conv( $row -> zak_dse_name );

    $data[ $row -> id_zak ]['count'] ++;
    $data[ $row -> id_zak ]['tasks'][ $row -> zadan_id ] = 
      [
        'shutter_name' => conv( $row -> shutter_name ),
        'shut_date' => $row -> shut_date,
        'cause' => conv( $row -> cause ),
        'dse_id' => $row -> dse_id,
        'dse_name' => conv( $row -> dse_name ),
        'dse_draw' => conv( $row -> dse_draw ),
        'operation' => conv( $row -> operation ),
        'unit_name' => conv( $row -> unit_name ),
        'unit_type' => conv( $row -> unit_type ),
        'res_name' => conv( $row -> res_name ),
        'date' => $row -> zadan_date, 
        'bin_date' => $row -> bin_date,
        'shift' => $row -> shift,
        'norm_plan' => $row -> norm_plan ? $row -> norm_plan : 0 ,
        'norm_fact' => $row -> norm_fact ? $row -> norm_fact : 0 ,
      ];
  }

  usort( $data, "CmpByCount");
  return $data ;

} // function GetData( $res_id )


function GetTotalData( $res_id = 0 )
{
  global $pdo;

  $query ="
            SELECT prec.cause, COUNT( prec.id ) AS count, causes.description AS description
            FROM `noncomplete_execution_precedents` AS prec
            LEFT JOIN `noncomplete_execution_causes` AS causes ON causes.id = prec.cause
            WHERE ";

  if( ! $res_id )
       $query .= " 1 ";
          else
            $query .= "cause IN 
                            (
                            SELECT nec.id
                            FROM noncomplete_execution_causes nec
                            LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(nec.responsible_res_id, CAST( res.id AS JSON ), '$')
                            WHERE res.ID = $res_id
                          )";
      $query .= " GROUP BY prec.cause";

    try
    {
       $stmt = $pdo -> prepare( $query );
       $stmt->execute();
    }

    catch (PDOException $e)
    {
        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $data = [];
    
    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
      $data[] = 
        [
          'name' => ( $row -> description ),
          'y' => $row -> count          
        ];  

    return $data ;
}