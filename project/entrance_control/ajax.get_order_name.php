<?php
error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$data = array();
$error = false;

$id = $_POST['id'];
$dse_name = '';

      try
      {
          $query ="
                          SELECT
                          DSE_NAME dse_name
                          FROM `okb_db_zak`
                          WHERE
                          id='$id'
                  ";
          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $dse_name = $row -> dse_name ;

if( $error )
  $data = array('error' => $error_msg ) ;

if( strlen( $dbpasswd ) )
  echo iconv( "UTF-8", "Windows-1251", $dse_name );
    else
      echo $dse_name ;
?>