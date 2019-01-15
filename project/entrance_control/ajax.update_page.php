<?php
error_reporting( 0 );
error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );

$id = $_POST['id'];
$field = $_POST['field'];
$val = $_POST['val'];

  try
  {
      $query ="
                      UPDATE okb_db_entrance_control_pages
                      SET $field = '$val'
                      WHERE id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

echo $query ;
