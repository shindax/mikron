<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );
error_reporting( 0 );

$id = $_POST['id'];

$str = "<option value='0'>...</option>";

try
  {
      $query = "SELECT ID, NAME FROM `okb_db_sort` WHERE ID_sort_cat = $id ORDER BY NAME";
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

  }

  while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
  {
    if( strlen( $row -> NAME ) )
      $str .= "<option value='". ( $row -> ID )."'>".conv( $row -> NAME )."</option>";
  }


if( strlen( $dbpasswd ) )
  echo $str;
    else
      echo iconv("Windows-1251", "UTF-8", $str );
