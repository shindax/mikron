<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
  return $result;
}

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function get_warehouse_action_select()
{
    global $pdo;	

    $actions = "<option value='0'>".conv("Все операции"). "</option>";

    try
    {
        $query =
        "SELECT *
         FROM okb_db_warehouse_action_type AS type
         WHERE 1
         ORDER BY type.ord
         " ;
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        $actions .= "<option value='".$row -> id."'>".conv( $row -> description )."</option>";

    return $actions;
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
            $tiers[ $row -> ID ] = "A".$row -> ORD;

        return [ "wh" => $wh, "cells" => $cells, "tiers" => $tiers ];
}

function get_operation_name( $pdo, $id )
{
  if( $id == 0 )
    return iconv('utf-8', 'windows-1251', "Нет операции" );

  try
  {
      $query = "
            SELECT NAME AS name
            FROM `okb_db_oper` 
            WHERE ID = $id";
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

  }

  $row = $stmt->fetch( PDO::FETCH_OBJ );
  return iconv('utf-8', 'windows-1251', $row -> name );
} 
