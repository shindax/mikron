<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$oper_id = $_POST['oper_id'];
$count = $_POST['count'];
$norm_hours = round( $count * $_POST['norm_hours'], 2 );
$comment = $_POST['comment'];
$user_id = $_POST['user_id'];
$user_id = 1;

if( $count )
{
  try
  {
      $query =
      "INSERT INTO `okb_db_operations_with_coop_dep`
      ( id, oper_id, date, count, norm_hours, comment, user_id )
      VALUES
      ( NULL, $oper_id, NOW(), $count, $norm_hours, '$comment', $user_id )
      " ;
      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
  }
}

  try
  {
      $query =
      "SELECT COUNT( * ) count 
      FROM `okb_db_operations_with_coop_dep`
      WHERE
      oper_id = $oper_id
      " ;
      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
  }

  $row_count = 0;

   if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
      $row_count = $row -> count ;

$result = '';

if( $row_count )
{
  try
  {
      $query =
      "SELECT SUM( count ) count 
      FROM `okb_db_operations_with_coop_dep`
      WHERE
      oper_id = $oper_id
      " ;
      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }
  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
  }

  $total = 0;

   if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
      $total = $row -> count ;

    $result = "$row_count/$total";
    $result = "$total";
  }

echo $result;