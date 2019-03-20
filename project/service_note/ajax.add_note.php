<?php
error_reporting( 0 );
// error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.ServiceNoteTable.php" );

global $pdo, $dbpasswd;

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

$res_id = $_POST['res_id'];
$can_edit = $_POST['can_edit'];
$note_number = 0;

  try
  {
      $query ="
                      SELECT MAX( note_number ) AS note_number FROM service_notes WHERE 1
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

     if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        $note_number = $row -> note_number;
  
  $note_number ++;

  try
  {
      $query ="
                      INSERT INTO service_notes 
                      ( note_number, creator_res_id, creation_date, executed ) 
                      VALUES
                      ( $note_number, $res_id, NOW(), 0 ) 
                      ";
     $stmt = $pdo->prepare( $query );
     $stmt -> execute();
  }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }

$id = $pdo->lastInsertId();
$note = new ServiceNoteTable( $pdo, $id, $can_edit );
$str = $note -> GetTable();

echo $str;

