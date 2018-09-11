<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}

$id_zak = $_POST['id_zak'];
$options = "<option value=''>Все операции</option>";

try
{
    $query =
    "SELECT
    DISTINCT( okbdb.okb_db_park.MARK ) AS model, 
    okbdb.okb_db_park.`NAME` AS operation
    FROM
    okbdb.okb_db_operitems
    INNER JOIN okbdb.okb_db_park ON okbdb.okb_db_operitems.ID_park = okbdb.okb_db_park.ID
    WHERE
    okbdb.okb_db_operitems.ID_zak = $id_zak
    ORDER BY operation" ;
    $stmt = $pdo->prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

   while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
         {
            $options .= "<option value='".( ( $row-> model ) )."'>".( $row-> operation )." : ".( $row-> model )."</option>";
         }

echo conv( $options );
