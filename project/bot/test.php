<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

global $pdo;
$arr = [];

try
{
    $query ="SELECT ID, `ID_krz`, `ID_krz2` 
             FROM `okb_db_edo_inout_files` 
             WHERE 1
        ";
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}

catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
      $id = $row -> ID ;
      $krz = processKrz( $row -> ID_krz );
      $krz2 = $row -> ID_krz2 ;
      $arr[] = ['id' => $id, 'krz' => $krz, 'krz2' => $krz2 ];
}

foreach( $arr AS $key => $item )
{
  $id = $item['id']; 
  $krz = $item['krz'];
  $krz2 = $item['krz2'];

  try
  {
      $query ="UPDATE `okb_db_edo_inout_files` 
               SET ID_krz ='$krz', ID_krz2 = '$krz2' 
               WHERE ID = $id";
      $stmt = $pdo->prepare( $query );
      $stmt -> execute();
  }

  catch (PDOException $e)
  {
     die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
  }
}

echo "Finished";

function processKrz( $krz )
{
  if( $krz == '0' || $krz == '0|' )
    $str = '0|';
  else
  {
    $arr = explode('|', $krz );
    foreach( $arr AS $key => $item )
    {
        if( isset( $item[0] ) && $item[0] == '0' && strlen( $item ) > 1 )
          $arr[ $key ] = mb_substr( $item, 1 );
    }
    $str = join( '|', $arr );
  }
  return $str ;
}