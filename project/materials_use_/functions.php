<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
  global $dbpasswd;
  if( strlen( $dbpasswd ) )
    return $str;
      else
        return iconv("UTF-8", "Windows-1251", $str );
}


function get_mat_cat_options()
{
  global $pdo;
  $options = "<option value='0'>...</option>";

      try
        {
            $query = "SELECT ID, NAME FROM `okb_db_mat_cat` WHERE PID = 0 ORDER BY NAME";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        // Multiple record
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
          $options .= "<option value='". ( $row -> ID )."'>".conv( $row -> NAME )."</option>";
        }

  return $options;
}

function get_sort_cat_options()
{
  global $pdo;
  $options = "<option value='0'>...</option>";

      try
        {
            $query = "SELECT ID, NAME FROM `okb_db_sort_cat` WHERE PID = 0 ORDER BY NAME";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        // Multiple record
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
          $options .= "<option value='". ( $row -> ID )."'>".conv( $row -> NAME )."</option>";
        }

  return $options;
}

function get_mat_cat_count( $id )
{
  global $pdo;
  $count = 0 ;

      try
        {
            $query = "SELECT COUNT( ID ) count FROM `okb_db_mat_cat` WHERE PID = $id ORDER BY NAME";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        // Multiple record
        if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
          $count = $row -> count ;
        }

  return $count ;
}

function get_sort_cat_count( $id )
{
  global $pdo;
  $count = 0 ;

      try
        {
            $query = "SELECT COUNT( ID ) count FROM `okb_db_sort_cat` WHERE PID = $id ORDER BY NAME";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        // Multiple record
        if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
          $count = $row -> count ;
        }

  return $count ;
}