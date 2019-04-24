<?php
error_reporting( 0 );
error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/service_note/functions.php" );

$uploaddir = str_replace("//", "/",  LOCAL_FILES_PATH );
$id = $_POST['id'];

  try
  {
    $query ="DELETE FROM service_notes WHERE id = $id";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

echo $query;

?>