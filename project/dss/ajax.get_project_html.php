<?php
error_reporting( 0 );
//error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemItem.php" );

global $pdo;

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

echo $html;
