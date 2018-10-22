<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;

$id = $_POST['id'];
$user_id = $_POST['user_id'];

function conv( $str )
{
    return $str ; // iconv( "UTF-8", "Windows-1251",  $str );
}

try
{
    $query ="   SELECT id, solved
                FROM `dss_discussions` 
                WHERE
                id = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage().". Query : $query");
}

$str = "";
if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
         $id = $row -> id ;
         $solved = $row -> solved ;
    }

     $disc = new DecisionSupportSystemDiscussion( $pdo,  $user_id, $id );
     $str .= $disc -> GetHtml() ;

echo $str;