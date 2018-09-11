<?php
error_reporting( 0 );
error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/project/entrance_control/functions.php" );

$uploaddir = str_replace("//", "/",  LOCAL_FILES_PATH );
$id = $_POST['id'];

  try
  {
      $query = "SELECT image FROM `okb_db_entrance_control_pages` WHERE id = $id";
      $stmt = $pdo -> prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
    die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

  }
        // One record
  $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
  $file_name = $row -> image ;

if( unlink( $uploaddir.$file_name ) )
 {
  try
  {
      $query ="
                      UPDATE okb_db_entrance_control_pages
                      SET image = ''
                      WHERE id = $id
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
}// if( unlink( $uploaddir.$file_name ) )

echo $uploaddir.$file_name ;

?>