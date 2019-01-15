<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

$id = $_POST['id'];
$res_id = $_POST['res_id'];
$level = $_POST['level'] + DecisionSupportSystemItem :: LEVEL_SHIFT;

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

try
{
    $query ="
                SELECT id 
                FROM `dss_projects`
                WHERE parent_id = $id
                ORDER BY ord
                ";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$str = '';

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
  $dss_item = new DecisionSupportSystemItem( $pdo, $res_id, $row -> id, $level );
  $str .= conv( $dss_item -> GetTableRow('','Field') );
}

echo $str;