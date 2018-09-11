<?php
header('Content-Type: text/html');
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$id = $_POST['id'];
$select = "<option value='0'>Все сотрудники</option>";

if( $id == 91 )
{
  $id = "91,104,118,141,147,148,149,150,151,152";
}

try
{
    $query ="SELECT NAME, ID_resurs
              FROM okb_db_shtat
              WHERE 
              ID_otdel IN ($id)
              ORDER BY NAME";
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}


$row_count = $stmt -> rowCount() ;

  if( $row_count )
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
           if( $row -> ID_resurs )
               $select .= "<option value='".( $row -> ID_resurs )."'>".($row -> NAME)."</option>";


echo iconv("UTF-8", "Windows-1251", $select );
//echo $select ;
