<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
  global $dblocation ;

  if( $dblocation == "127.0.0.1" )
    $result = iconv("UTF-8", "Windows-1251", $str );
      else
        $result = iconv("UTF-8", "Windows-1251", $str );

  return $result;
}

$table = $_POST['table'];
$id = $_POST['id'];
$field = $_POST['field'];
$data = $_POST['data'];
$user_id = $_POST['user_id'];

try
{
   $query =
   "  UPDATE $table SET actualizator = $user_id, actualization_date = NOW(), timestamp = NOW() 
      WHERE 1" ;
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

try
{
   $query =
   "UPDATE $table SET $field = '$data'  WHERE id = $id" ;
   $stmt = $pdo->prepare( $query );
   $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

try
{
    $query = " SELECT 
               users.FIO, DATE_FORMAT( form.actualization_date, 
               '%d.%m.%Y %H:%i') AS actualization_date 
               FROM $table form
               LEFT JOIN okb_users users ON users.ID = form.actualizator
               WHERE 1";
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
}


$row = $stmt->fetch( PDO::FETCH_OBJ ); 
$str = $row -> actualization_date."<br>".$row -> FIO;

echo conv( $str );