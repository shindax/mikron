<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo, $dbpasswd;

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

$list = $_POST['list'] ;
$res_id = $_POST['res_id'] ;

try
{
    $query ="SELECT ID AS id, NAME AS name FROM  `okb_db_resurs` WHERE ID IN ( $list ) ORDER BY name";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}

catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$str = "";

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
{
  $id = $row -> id;
	$str .= "<option data-id='$id' ".( $id == $res_id ? "disabled" : "").">". conv( $row -> name )."</option>";
}

echo $str;
