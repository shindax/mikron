<?php
require_once( "db.php" );

$value = $_POST['val'];
$id = $_POST['id'];

function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
//  return $str ;
}

try
{
    $query = " UPDATE okb_db_itrzadan SET `KOMM1`= '$value' WHERE ID = $id";
    $stmt = $pdo->prepare( $query  );
    $stmt->execute();
}
  catch (PDOException $e) 
    {
      die("Can't get data: " . $e->getMessage());
    }  


echo $query;
	
?>
