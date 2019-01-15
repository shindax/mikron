<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}


$id = $_POST['id'] ;

try
{
    $query ="SELECT html FROM  `dss_projects` WHERE id = $id";
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}

catch (PDOException $e)
{
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
}

$row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
$html = $row -> html ;

echo conv( $html );
