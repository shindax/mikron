<?php
error_reporting( 0 );
// error_reporting( E_ALL );

date_default_timezone_set("Asia/Krasnoyarsk");
require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );

$id = $_POST['id'];
$field = $_POST['field'];
$val = $_POST['val'];

$date = new DateTime();
$date_time = $date->format('d.m.Y H:i');

$query ="
        UPDATE okb_db_entrance_control_items
        SET $field = '$val'
        WHERE id = $id
        ";


if( $field == 'inwork_state' )
    $query ="
          UPDATE okb_db_entrance_control_items
          SET $field = '$val', inwork_ch_state_date = NOW()
          WHERE id = $id
          ";

  try
  {
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

echo $date_time ;
